{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="padding-top: 8px;">
          CONSULTAR INTERFAZ SIZFRA Z.F.
        </td>
      </tr>
    </table>
  </div>
</div>
<div style="padding-top: 43px;"></div>
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general_z" class="display">
  <thead>
    <tr>
      <th>No.</th>
      <th>Fecha</th>
      <th>Nombre Interfaz</th>
      <th>Cantidad</th>
      <th>Peso</th>
      <th>Valor</th>
      <th>Acci&oacute;n</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td style="text-align: center;">{n}</td>
      <td style="text-align: center;">{fecha}</td>
      <td style="padding-left: 15px;">{interfaz}</td>
      <td style="text-align: right;">{cantidad}</td>
      <td style="text-align: right;">{peso}</td>
      <td style="text-align: right;">{valor}</td>
      <td style="text-align: center;">
        <a href="integrado/_files/{interfaz}.txt" target="_blank" title="Ver Interfaz {interfaz}">
          <img src="img/acciones/buscar.png" border="0" width="20px;" height="20px;"/>
        </a>
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
      "iDisplayLength": 20,
      "aLengthMenu": [[20, 40, 60, -1], [20, 40, 60, "Todos"]],
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