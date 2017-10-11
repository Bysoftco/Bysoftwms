{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="padding-top: 8px;">
          REUBICACIONES
        </td>
      </tr>
    </table>
  </div>
  <div id="contenedor_opciones" align="left">
    <table border="0">
      <tr>
				<td align="center">
					<div class="borde_circular">
						<a href="javascript: vFiltro()">
							<img src="img/acciones/ver.png" title="Filtro Reubicaciones" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
        <td align="center">
          <div class="borde_circular">
            <a href="javascript: reubicar()">
              <img src="img/acciones/reubicar.png" title="Reubicar" width="25" height="25" border="0" />
            </a>
          </div> 
        </td>
      </tr>
    </table>
  </div>
</div>
<div style="padding-top: 43px;"></div>
<link type="text/css" href="./integrado/cz_estilos/jquery.autocomplete.css" rel="stylesheet" />
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general_z" class="display">
  <thead>
    <tr>
      <th>Ubicaci&oacute;n</th>
      <th>Cliente</th>
      <th>C&oacute;digo Referencia</th>
      <th>Referencia</th>      
      <th>Documento TTE</th>
      <th>Orden</th>
      <th>Nueva Ubicación</th>
      <th>Fecha Reubicaci&oacute;n</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td style="text-align: center;padding: 2.5px;">
        <img src="components/reubicaciones/views/tmpl/generar.php?ocupacion={nombre_ubicacion}" />
        <input name="codigo{n}" type="hidden" id="codigo{n}" value="{codigo}" />
      </td>
      <td>[{doc_cliente}] {nombre_cliente}</td>
      <td style="text-align: center;">{codigo_referencia}</td>
      <td>{nombre_referencia}</td>
      <td style="text-align: center;">{doc_transporte}</td>
      <td style="text-align: center;">{orden}</td>
      <td style="text-align: center;">
        <input type="text" name="reubica{n}" id="reubica{n}" maxlength="30" size="28" style="width:auto;"
          onfocus="seleccion({n})" oninput="mostrar({n})" />
        <input type="hidden" name="ubicacionr{n}" id="ubicacionr{n}" />
      </td>
      <td style="text-align: center;">{fecha_reubica}</td>
    </tr>
    <!-- END ROW  -->
  </tbody>
</table>
<input type="hidden" name="nr" id="nr" value="{tr}" />
<input type="hidden" name="nitr" id="nitr" value="{nitr}" />
<input type="hidden" name="doctter" id="doctter" value="{doctter}" />
<input type="hidden" name="doasignador" id="doasignador" value="{doasignador}" />
<input type="hidden" name="ubicacionr" id="ubicacionr" value="{ubicacionr}" />
<input type="hidden" name="referenciar" id="referenciar" value="{referenciar}" />
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

  function vFiltro() {
    $.ajax({
      url: 'index_blank.php?component=reubicaciones&method=filtroReubicaciones',
      type: "POST",
      async: false,
      success: function(msm) {
        $("#winreubicaciones").dialog("close");
        $('#componente_central').html(msm);
      }
    });
  }

  function seleccion(n) {
    $('#reubica'+n).autocomplete("./index_blank.php?component=reubicaciones&method=findReubicaciones", {
      width: 200,
      selectFirst: false
    });
    
    $('#reubica'+n).result(function(event, data, formatted) {
      $('#ubicacionr'+n).val(data[1]);
    });
  }
  
  function mostrar(n) {
    $.ajax({
      url: 'index_blank.php?component=reubicaciones&method=findReubicaciones&q='+$('#reubica'+n).val(),
      type: "POST",
      async: false,
      success: function(msm) {
        var arreglo = msm.split("|");
        $('#ubicacionr'+n).val(arreglo[1].trim());
      }
    });
  }
  
  function reubicar() {
    var fechar = new Date().toJSON().slice(0,10); //Captura fecha en formato yyyy-mm-dd
    var tabla = $('#tabla_general_z').DataTable();
    
    $.ajax({
      url: 'index_blank.php?component=reubicaciones&method=Reubicar&nr='+$('#nr').val()
            +'&nitr='+$('#nitr').val()+'&doctter='+$('#doctter').val()+'&doasignador='
            +$('#doasignador').val()+'&ubicacionr='+$('#ubicacionr').val()
            +'&referenciar='+$('#referenciar').val()+'&fechar='+fechar,
      type: "POST",
      async: false,
      data: tabla.$('input').serialize(),
      success: function(msm) {
        $('#componente_central').html(msm);
      }
    });
  }
</script>