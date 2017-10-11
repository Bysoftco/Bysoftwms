<style>
  body { font-size: 62.5%; }
  label { display: inline-block; }
  legend { padding: 0.5em; }
  fieldset fieldset label { display: block; }
  #frmreubicacionesb label { width: 170px; margin-left: 5px;} /*ancho de las etiquetas de los campos*/
  .ui-dialog .ui-state-error { padding: .3em; }
</style>
{COMODIN}
<link type="text/css" href="./integrado/cz_estilos/jquery.autocomplete.css" rel="stylesheet" />
<div id="winreubicaciones" title="Reubicaciones">
  <div id="frmreubicaciones">
    <p id="msgreubicaciones">Seleccione uno o varios filtros para delimitar resultados.</p>
    <form name="frmreubicacionesb" id="frmreubicacionesb" method="post" action="" style="width:100%;">
      <fieldset class="ui-widget ui-widget-content ui-corner-all">
        <legend class="ui-widget ui-widget-header ui-corner-all">
          <div id="filtroreubicaciones">Filtro de Reubicaciones</div>
        </legend>  
        <p>
          <label>Por Cuenta de:</label>
          <input type="text" name="buscarClienter" id="buscarClienter" size="50" />
        </p>
        <p> 
          <label>Nit:</label>
          <input type="text" name="nitr" id="nitr" value="{nitr}" readonly=""/>
        </p>
        <p>
          <label>Documento de Transporte:</label>
          <input type="text" name="doctter" id="doctter" />
        </p>
        <p>
          <label>Orden:</label>
          <input type="text" name="doasignador" id="doasignador" />
        </p>
        <p>
          <label>Ubicaci&oacute;n:</label>
          <input type="text" name="ubicacionf" id="ubicacionf" />
          <input type="hidden" name="ubicacionr" id="ubicacionr" />
        </p>
        <p>
          <label>Referencia:</label>
          <input type="text" name="referenciaf" id="referenciaf" size="50" />
          <input type="hidden" name="referenciar" id="referenciar" />
        </p>
      </fieldset>
    </form>
  </div>
</div>
<script>
	$(function() {
    $( "#winreubicaciones" ).dialog({
      autoOpen: false,
      resizable: false,
      height: 420,
      width: 600,
			modal: true,
			buttons: {
        "Consultar": function() {
          //Parámetros de envío de información
          $.ajax({
            url: 'index_blank.php?component=reubicaciones&method=listadoReubicaciones',
            type: "POST",
            async: false,
            data: $('#frmreubicacionesb').serialize(),
            success: function(msm) {
              $("#winreubicaciones").dialog("close");
              $('#componente_central').html(msm);
            }
          });
        }
      },
		});
  });
  
  // Muestra la Ventana de Filtro
  $( "#winreubicaciones" ).dialog( "open" );

  // Limpia los campos del formulario
  $("#frmreubicacionesb")[0].reset();
  $("#frmreubicacionesb input:hidden").val('').trigger('change');
  
	$(function() {
    $("#buscarClienter").autocomplete("./index_blank.php?component=reubicaciones&method=findCliente", {
      width: 300,
      selectFirst: false
    });

    $("#buscarClienter").result(function(event, data, formatted) {
      $("#nitr").val(data[1]);
      //Busca el Documento de Transporte
      $("#doctter").autocomplete("./index_blank.php?component=reubicaciones&method=findDocumento&cliente="+$("#nitr").val(), {
        width: 300,
        selectFirst: false
      });
      
      $("#doctter").result(function(event, data, formatted) {
        $("#doctter").val(data[1]);
        $("#doasignador").val(data[2]);
      });
    });
  });
  
  $(function() {
    $("#ubicacionf").autocomplete("./index_blank.php?component=reubicaciones&method=findReubicaciones", {
      width: 300,
      selectFirst: false
    });
    
    $("#ubicacionf").result(function(event, data, formatted) {
      $("#ubicacionr").val(data[1]);
    });

    $("#referenciaf").autocomplete("./index_blank.php?component=ocupacion&method=findReferencia", {
      width: 300,
      selectFirst: false
    });
    
    $("#referenciaf").result(function(event, data, formatted) {
      $("#referenciar").val(data[1]);
    });
  });
</script>