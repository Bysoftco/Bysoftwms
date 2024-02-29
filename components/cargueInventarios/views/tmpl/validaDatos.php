{COMODIN}
<div style="padding-top: 10px;"></div>
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general_z" class="display ui-widget ui-widget-content">
  <thead>
    <tr>
      <th>fila</th>
      <th>arribo</th>
      <th>orden</th>
      <th>fecha</th>
      <th>referencia</th>
      <th>cantidad</th>
      <th>peso</th>
      <th>valor</th>
      <th>fmm</th>
      <th>modelo</th>
      <th>embalaje</th>
      <th>un_empaque</th>
      <th>posici&oacute;n</th>
      <th>cant_declara</th>
      <th>observaci&oacute;n</th>
      <th>fecha_expira</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td style="text-align: center;color:red;">{n}</td>
      <td style="text-align: center;">{arribo}</td>
      <td style="text-align: center;">{orden}</td>
      <td style="text-align: center;">{fecha}</td>
      <td style="text-align: center;">{referencia}</td>
      <td style="text-align: right;">{cantidad}</td>
      <td style="text-align: right;">{peso}</td>
      <td style="text-align: right;">{valor}</td>
      <td style="text-align: center;">{fmm}</td>
      <td style="text-align: center;">{modelo}</td>
      <td style="text-align: center;">{embalaje}</td>
      <td style="text-align: center;">{unimedida}</td>
      <td style="text-align: center;">{posicion}</td>
      <td style="text-align: right;">{cant_declaraciones}</td>
      <td style="text-align: center;">{observacion}</td>
      <td style="text-align: center;">{fecha_expira}</td>
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