"use client";

export default function VehicleInquiryForm() {
  return (
    <form onSubmit={(e) => e.preventDefault()}>
      <div className="form-field"><label htmlFor="v-nome">Nome completo *</label><input id="v-nome" type="text" required /></div>
      <div className="form-row">
        <div className="form-field"><label htmlFor="v-email">E-mail *</label><input id="v-email" type="email" required /></div>
        <div className="form-field"><label htmlFor="v-tel">Telefone *</label><input id="v-tel" type="tel" required /></div>
      </div>
      <div className="form-field">
        <label htmlFor="v-veiculo">Veículo de interesse</label>
        <select id="v-veiculo">
          <option>Selecione um veículo</option>
          <option>Mercedes-Benz 710 (2020)</option>
          <option>Volkswagen Delivery (2019)</option>
          <option>Scania R420 (2018)</option>
          <option>Mercedes-Benz Actros (2017)</option>
        </select>
      </div>
      <div className="form-field"><label htmlFor="v-msg">Mensagem</label><textarea id="v-msg"></textarea></div>
      <button type="submit" className="btn btn-primary btn-block">Enviar Mensagem</button>
    </form>
  );
}
