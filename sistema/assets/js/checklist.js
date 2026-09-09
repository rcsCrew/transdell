(function () {
  const ITEMS = window.ITENS;
  const FOTOS_EXISTENTES = window.FOTOS_EXISTENTES || {};
  const CHECKLIST_ID = window.CHECKLIST_ID;

  // state[chave] = { fotos: [{id, foto_url}] }
  const state = {};
  ITEMS.forEach(it => {
    state[it.chave] = { fotos: FOTOS_EXISTENTES[it.chave] || [] };
  });

  /**
   * Reduz a foto pra no máximo 1280px do lado maior antes de enviar. Fotos de
   * celular saem com vários MB — isso corta pra ~200-400KB sem perder o que
   * a IA precisa ver, e o upload fica bem mais rápido.
   */
  async function comprimirFoto(file, maxLado = 1280, qualidade = 0.82) {
    if (file.size < 300 * 1024) return file; // já é pequena, não vale a pena

    try {
      const imagem = await carregarImagem(file);
      const largura = imagem.naturalWidth || imagem.width;
      const altura = imagem.naturalHeight || imagem.height;
      const escala = Math.min(1, maxLado / Math.max(largura, altura));

      if (escala >= 1) return file;

      const canvas = document.createElement('canvas');
      canvas.width = Math.round(largura * escala);
      canvas.height = Math.round(altura * escala);
      canvas.getContext('2d').drawImage(imagem, 0, 0, canvas.width, canvas.height);

      const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', qualidade));
      if (imagem.close) imagem.close();
      return blob || file;
    } catch (err) {
      return file;
    }
  }

  async function carregarImagem(file) {
    if (window.createImageBitmap) {
      try {
        return await createImageBitmap(file, { imageOrientation: 'from-image' });
      } catch (err) { /* cai pro fallback abaixo */ }
    }
    return new Promise((resolve, reject) => {
      const img = new Image();
      img.onload = () => resolve(img);
      img.onerror = reject;
      img.src = URL.createObjectURL(file);
    });
  }

  let coords = { lat: null, lng: null };
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      pos => { coords = { lat: pos.coords.latitude, lng: pos.coords.longitude }; },
      () => {},
      { timeout: 8000 }
    );
  }

  const container = document.getElementById('itemsContainer');

  ITEMS.forEach(it => {
    const div = document.createElement('div');
    div.className = 'item';
    div.id = 'item-' + it.chave;
    div.innerHTML = `
      <div class="item-head">
        <span class="item-name">${it.nome}</span>
        <span class="badge" id="badge-${it.chave}">Pendente</span>
      </div>
      <div class="item-body">
        <div class="photo-gallery" id="gallery-${it.chave}"></div>
      </div>
    `;
    container.appendChild(div);
    renderItem(it.chave, it.nome);
  });

  function renderItem(chave, nome) {
    const gallery = document.getElementById('gallery-' + chave);
    const badge = document.getElementById('badge-' + chave);
    const item = document.getElementById('item-' + chave);
    const fotos = state[chave].fotos;

    const limite = window.MAX_FOTOS_POR_ITEM || 6;
    const botaoAdicionar = fotos.length < limite ? `
      <label class="add-photo-btn" id="btn-${chave}">
        +
        <input type="file" accept="image/*" capture="environment" style="display:none" onchange="window.__handlePhoto('${chave}', '${nome.replace(/'/g, "\\'")}', this)">
      </label>
    ` : '';

    gallery.innerHTML = fotos.map(f => `
      <div class="photo-thumb-wrap"><img src="${f.foto_url}"><span class="photo-status-dot ok"></span></div>
    `).join('') + botaoAdicionar;

    if (fotos.length === 0) {
      badge.textContent = 'Pendente';
      badge.className = 'badge';
      item.className = 'item';
    } else {
      badge.textContent = fotos.length + (fotos.length > 1 ? ' fotos' : ' foto');
      badge.className = 'badge ok';
      item.className = 'item ok';
    }
  }

  window.__handlePhoto = async function (chave, nome, input) {
    const file = input.files[0];
    if (!file) return;
    input.value = '';

    const btn = document.getElementById('btn-' + chave);
    const previewUrl = URL.createObjectURL(file);
    const previewId = 'preview-' + chave + '-' + Date.now();
    btn.insertAdjacentHTML('beforebegin', `<div class="photo-thumb-wrap" id="${previewId}"><img src="${previewUrl}"><span class="photo-status-dot pendente"></span></div>`);

    try {
      const fotoParaEnviar = await comprimirFoto(file);

      const formData = new FormData();
      formData.append('checklist_id', CHECKLIST_ID);
      formData.append('item_chave', chave);
      formData.append('item_nome', nome);
      formData.append('foto', fotoParaEnviar, 'foto.jpg');
      if (coords.lat !== null) {
        formData.append('lat', coords.lat);
        formData.append('lng', coords.lng);
      }

      const resp = await fetch('/api/enviar_foto.php', { method: 'POST', body: formData });
      const data = await resp.json();

      if (!resp.ok) {
        throw new Error(data.erro || 'Erro ao enviar foto.');
      }

      state[chave].fotos.push({ id: data.foto_id, foto_url: data.foto_url });
      renderItem(chave, nome);

      // Dispara a análise da IA na hora, sem esperar o próximo minuto do
      // worker agendado. keepalive faz o navegador terminar de mandar essa
      // requisição mesmo que o motorista feche a aba ou saia da tela logo
      // em seguida — a foto já está salva, então não tem risco de disparar
      // análise de algo que não chegou no servidor.
      const formDataAnalise = new FormData();
      formDataAnalise.append('checklist_id', CHECKLIST_ID);
      formDataAnalise.append('foto_id', data.foto_id);
      fetch('/api/processar_foto_agora.php', { method: 'POST', body: formDataAnalise, keepalive: true }).catch(() => {});
    } catch (err) {
      document.getElementById(previewId)?.remove();
      alert(err.message || 'Não foi possível enviar a foto agora. Tente de novo.');
    }

    updateProgress();
  };

  function updateProgress() {
    const total = ITEMS.length;
    const done = ITEMS.filter(it => state[it.chave].fotos.length > 0).length;
    document.getElementById('progressCount').textContent = `${done}/${total} itens`;
    document.getElementById('progressFill').style.width = (done / total * 100) + '%';
  }

  // Avisa se o motorista tentar sair da tela (fechar aba, voltar, recarregar)
  // com o checklist incompleto. Isso é best-effort — nenhum site consegue
  // bloquear de verdade o fechamento de uma aba/app, e navegadores de
  // celular costumam ignorar esse aviso. A garantia de verdade é que o
  // checklist só é aceito como "enviado" quando todo item tem foto.
  window.addEventListener('beforeunload', function (e) {
    const done = ITEMS.filter(it => state[it.chave].fotos.length > 0).length;
    if (done < ITEMS.length) {
      e.preventDefault();
      e.returnValue = '';
    }
  });

  document.getElementById('btnEnviar').addEventListener('click', async function () {
    const done = ITEMS.filter(it => state[it.chave].fotos.length > 0).length;
    if (done < ITEMS.length) {
      alert(`Faltam ${ITEMS.length - done} item(ns) sem foto ainda.`);
      return;
    }

    const btn = this;
    btn.disabled = true;
    btn.textContent = 'Enviando...';

    try {
      const formData = new FormData();
      formData.append('checklist_id', CHECKLIST_ID);
      const resp = await fetch('/api/enviar_checklist.php', { method: 'POST', body: formData });
      const data = await resp.json();

      if (!resp.ok) {
        throw new Error(data.erro || 'Erro ao enviar checklist.');
      }

      alert('Checklist enviado! As fotos vão ser analisadas automaticamente — se algo der crítico, o gestor é avisado.');
      window.location.href = '/index.php';
    } catch (err) {
      alert(err.message || 'Erro ao enviar checklist.');
      btn.disabled = false;
      btn.textContent = 'Enviar checklist';
    }
  });

  updateProgress();
})();
