{COMODIN}
<div style="padding-top: 10px;"></div>
<table align="center" width="90%" cellpadding="0" cellspacing="0" id="tabla_general_z" class="display ui-widget ui-widget-content">
  <thead>
    <tr>
      <th>fila</th>
      <th>inventario_entrada</th>
      <th>fecha</th>
      <th>cod_maestro</th>
      <th>num_declaracion</th>
      <th>tipo_declaracion</th>
      <th>subpartida</th>
      <th>modalidad</th>
      <th>trm</th>
      <th>cantidad_nonac</th>
      <th>fob</th>
      <th>fletes</th>
      <th>arancel</th>
      <th>iva</th>
      <th>ubicación</th>
      <th>observacion</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td style="text-align: center;color:red;">{n}</td>
      <td style="text-align: center;">{inventario_entrada}</td>
      <td style="text-align: center;">{fecha}</td>
      <td style="text-align: center;">{cod_maestro}</td>
      <td style="text-align: center;">{num_declaracion}</td>
      <td style="text-align: center;">{tipo_declaracion}</td>
      <td style="text-align: center;">{subpartida}</td>
      <td style="text-align: center;">{modalidad}</td>
      <td style="text-align: right;">{trm}</td>
      <td style="text-align: right;">{cantidad_nonac}</td>
      <td style="text-align: right;">{fob}</td>
      <td style="text-align: right;">{fletes}</td>
      <td style="text-align: right;">{arancel}</td>
      <td style="text-align: right;">{iva}</td>
      <td style="text-align: center;">{ubicacion}</td>
      <td style="text-align: center;">{observacion}</td>
    </tr>
    <!-- END ROW  -->
  </tbody>
</table>
<script>
  $(document).ready(function() {
    $('#tabla_general_z').dataTable({
      "aaSorting": [],
      "oLanguage": {
        "sLengthMenu": "Mostrar _MENU_ registros por p&aacute;gina",
        "sZeroRecords": "No hay registros para mostrar",
        "sInfo": "Mostrando _START_ a _END_ registros de _TOTAL_",
        "sInfoEmpty": "Mostrando 0 a 0 registros de 0",
        "sInfoFiltered": "",
        "sSearch": "Buscar:"
      }
    });
  });
</script>