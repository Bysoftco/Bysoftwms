{COMODIN}
<div style="width:80%; margin: 0 auto; text-align: center; padding-top: 15px;">
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr>
      <th colspan="2">Orden No. {numero_orden}</th>
    </tr>
    <tr style="text-align: left; font-weight: 900;">
      <td style="width: 40%;">Documento de transporte: </td>
      <td>{doc_transporte}</td>
    </tr>
    <tr style="text-align: left; font-weight: 900;">
      <td class="tituloForm" >Cliente: </td>
      <td>[{doc_cliente}] {nombre_cliente}</td>
    </tr>
    <tr style="text-align: left;">
      <td>Modelo/Lote/Cosecha: </td>
      <td>{modelo}</td>
    </tr>
    <tr style="text-align: left;">
      <td>Última Operación: </td>
      <td>[{cod_ultima}] {nom_ultima}</td>
    </tr>
    <tr style="text-align: left;">
      <td>Agencia aduanera: </td>
      <td>{doc_agencia} - {nom_agencia}</td>
    </tr>
    <tr style="text-align: left;">
      <td>Controles: </td>
      <td>{nombre_control} - {nombre_entidad}</td>
    </tr>
  </table><br /><br />
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general_z">
    <thead>
      <tr>
        <th>Operación</th>
        <th>Fecha Operación</th>
        <th>C&oacute;digo Referencia</th>
        <th>Referencia</th>
        <th>FMM</th>
        <th>U.Comercial</th>
        <th>Piezas</th>
      </tr>
    </thead>
    <tbody>
      <!--  BEGIN ROW -->
      <tr>
        <td align="left">[{num_operacion}]-{operacion}</td>
        <td style="text-align: center;">{fecha_operacion}</td>
        <td style="text-align: center;">{cod_referencia}</td>
        <td align="left">{nom_referencia}</td>
        <td style="text-align: center;">{fmm}</td>
        <td style="text-align: center;">{unidad_comercial}</td>
        <td align="right">{piezas}</td>
      </tr>
      <!-- END ROW -->
    </tbody>
  </table>
</div>
<script>
  $(document).ready(function() {
    $('#tabla_general_z').dataTable({
      "aaSorting": [],
      "oLanguage": {
        "sLengthMenu": "Mostrar _MENU_ registros por página",
        "sZeroRecords": "No hay registros para mostrar",
        "sInfo": "Mostrando _START_ a _END_ registros de _TOTAL_",
        "sInfoEmpty": "Mostrando 0 a 0 registros de 0",
        "sInfoFiltered": "",
        "sSearch": "Buscar:"
      }
    });
  });
</script>