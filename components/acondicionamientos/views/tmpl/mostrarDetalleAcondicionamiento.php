{COMODIN}
<link rel='StyleSheet' href='integrado/cz_estilos/reporteGerencial.css' type="text/css"/>
<table align="center" width="100%" cellpadding="0" cellspacing="0" style="font-size: 12px;">
  <thead>
    <tr>
      <th colspan="10">Detalle del Alistamiento No. {codigo_operacion} ( Pedido {pedido} )</th>
    </tr>
    <tr>
      <th>Orden</th>
      <th>Arribo</th>
      <th>Referencia</th>
      <th>Fecha</th>
      <th>Ubicación</th>
      <th>Piezas Nal.</th>
      <th>Peso Nal.</th>
      <th>Valor CIF</th>
      <th>Piezas Ext.</th>
      <th>Peso Ext.</th>
      <th>Valor FOB</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW -->
    <tr>
      <td>{orden_detalle}</td>
      <td>{arribo}</td>
      <td>{nombre_referencia}</td>
      <td>{fecha_detalle}</td>
      <td>{nombre_ubicacion}</td>
      <td style="text-align: right;">{cantidad_nacional}</td>
      <td style="text-align: right;">{peso_nacional}</td>
      <td style="text-align: right;">{valor_cif}</td>
      <td style="text-align: right;">{cantidad_extranjera}</td>
      <td style="text-align: right;">{peso_extranjera}</td>
      <td style="text-align: right;">{valor_fob}</td>
    </tr>
    <!-- END ROW -->
  </tbody>
</table>