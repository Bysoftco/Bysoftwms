{COMODIN}
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general_d">
  <thead>
    <tr>
      <th>No.</th>
      <th>Fecha de Carga</th>
      <th>Nombre del Documento</th>
      <th width="10px">Seleccionar</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td style="text-align:center;">{n}</td>
      <td style="text-align:center;">{fechadoc}</td>
      <td>{nomdoc}</td>
      <td align="center"><input type="radio" name="seleccion" onclick="javascript:seleccionado('{nomdoc}')" /></td>
    </tr>
    <!-- END ROW  -->
  </tbody>
</table>
<br />
<input type="hidden" name="noSeleccionado" id="noSeleccionado" />
<input type="hidden" name="documento" id="documento" value="{numero_identificacion}" />
<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");
  $('.noSeleccion').css('display', 'none');
  
  function seleccionadoDA(documento) {
    $('#noSeleccionado').attr('value', documento);
    $('.noSeleccion').css('display', 'block');
  }
  
  $(document).ready(function() {
    $('#tabla_general_d').dataTable({
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