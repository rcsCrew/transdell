<?php
/** @var array $gestor */
/** @var string $paginaAtual */
$paginaAtual = $paginaAtual ?? '';
?>
<header>
  <img class="logo-img" src="/assets/img/transdell-logo.png" alt="Transdell">
  <p class="eyebrow">Painel do gestor</p>
  <h1>Frota — Checklists</h1>
  <a class="logout" href="/painel/logout.php">Sair (<?= e($gestor['nome']) ?>)</a>
  <nav class="painel-nav">
    <a href="/painel/dashboard.php" class="<?= $paginaAtual === 'dashboard' ? 'ativo' : '' ?>">Checklists</a>
    <a href="/painel/historico.php" class="<?= $paginaAtual === 'historico' ? 'ativo' : '' ?>">Histórico por placa</a>
    <a href="/painel/motoristas.php" class="<?= $paginaAtual === 'motoristas' ? 'ativo' : '' ?>">Motoristas</a>
    <a href="/painel/caminhoes.php" class="<?= $paginaAtual === 'caminhoes' ? 'ativo' : '' ?>">Veículos</a>
    <a href="/painel/itens.php" class="<?= $paginaAtual === 'itens' ? 'ativo' : '' ?>">Itens do checklist</a>
    <a href="/painel/perfil.php" class="<?= $paginaAtual === 'perfil' ? 'ativo' : '' ?>">Meu perfil</a>
  </nav>
</header>
