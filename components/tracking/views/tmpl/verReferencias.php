{COMODIN}
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general_z">
    <thead>
        <tr>
            <th width="100px">C&oacute;digo Referencia</th>
            <th>Descripci&oacute;n Referencia</th>
            <th width="10px">Seleccionar</th>
        </tr>
    </thead>
    <tbody>
      <tr id="{id_tr_estilo}">
        <td>99</td>
        <td>BULTOS</td>
        <td></td>
      </tr>
      <!-- BEGIN ROW  -->
      <tr id="{id_tr_estilo}">
        <td>{id_referencia}</td>
        <td>{nombre_referencia}</td>
        <td align="center"><input type="radio" name="seleccion" onclick="javascript:seleccionado('{id_referencia}')" /></td>
      </tr>
      <!-- END ROW  -->
    </tbody>
</table>
<br />{paginacion}

<input type="hidden" name="noSeleccionado" id="noSeleccionado" />

<!-- <form name="form_filtros" id="form_filtros" action="index_blank.php">
  <input type="hidden" name="pagina" id="pagina" value="{pagina}" />
  <input type="hidden" name="buscar" id="buscar" value="{buscar}" />
  <input type="hidden" name="orden" id="orden" value="{orden}" />
  <input type="hidden" name="id_orden" id="id_orden" value="{id_orden}" />
</form> -->

<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");
  $('.noSeleccion').css('display', 'none');

  function paginar(pagina) {
    $('#pagina').attr('value', pagina);
  }
  
  function seleccionadoR(codReferencia) {
    $('#noSeleccionado').attr('value', codReferencia);
    $('.noSeleccion').css('display', 'block');
  }
  
    $(document).ready(function() {
      $('#tabla_general_z').dataTable({
          "aaSorting": [],
          "oLanguage": {
              "sLengthMenu": "Mostrar _MENU_ registros por página",
              "sZeroRecords": "No hay registros para mostrar",
              "sInfo": "Mostrando _START_ a _END_ registros de _TOTAL_",
              "sInfoEmpty": "Mostrando 0 a 0 registros de 0",
              "sInfoFiltered": ""
          }
      });
    });
</script>