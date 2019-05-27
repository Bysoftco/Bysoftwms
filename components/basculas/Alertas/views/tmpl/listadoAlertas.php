{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="padding-top: 8px;">
          ALERTAS
        </td>
      </tr>
    </table>
  </div>
</div>
<!-- <div style="height: 50px"></div> -->
<div style="padding-top: 43px;"></div>
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general_z">
  <thead>
    <tr>
      <th>Tipo Alerta</th>
      <th>Doc. Cliente</th>
      <th>Arribo</th>
      <th>Orden</th>
      <th>Doc. Transporte</th>
      <th>Referencia</th>
      <th>Piezas</th>
      <th>U.Comercial</th>
      <th>FMM</th>
      <th>Fecha Manifiesto</th>
      <th>Fecha Límite</th>
      <th>Prorroga</th>
      <th>Control</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td>{tipo_alerta}</td>
      <td>{doc_cliente}</td>
      <td>{arribo}</td>
      <td>{orden}</td>
      <td>{doc_tte}</td>
      <td>[{cod_referencia}] {nom_referencia}</td>
      <td style="text-align: right;">{cantidad}</td>
      <td>{unidad_comercial}</td>
      <td>{fmm}</td>
      <td style="text-align: center;">{fecha_manifiesto}</td>
      <td style="text-align: center;">{fecha_limite}</td>
      <td style="text-align: center;">{prorroga}</td>
      <td>{control_final}</td>
      <td style="text-align: center;">
        <img src="{bandera}" title="" width="15" height="15" border="0" />
      </td>
    </tr>
    <!-- END ROW  -->
  </tbody>
</table>
<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");

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