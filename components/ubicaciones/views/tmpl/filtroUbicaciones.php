<style>
  body { font-size: 62.5%; }
  label { display: inline-block; }
  legend { padding: 0.5em; }
  fieldset fieldset label { display: block; }
  #frmubicacionesb label { width: 170px; margin-left: 5px;} /*ancho de las etiquetas de los campos*/
  .ui-dialog .ui-state-error { padding: .3em; }
</style>
{COMODIN}
<link type="text/css" href="./integrado/cz_estilos/jquery.autocomplete.css" rel="stylesheet" />
<div id="winubicaciones" title="Consulta Ubicaciones">
  <div id="frmubicaciones">
    <p id="msgubicaciones">Seleccione uno o varios filtros para delimitar resultados.</p>
    <form name="frmubicacionesb" id="frmubicacionesb" method="post" action="" style="width:100%;">
      <fieldset class="ui-widget ui-widget-content ui-corner-all">
        <legend class="ui-widget ui-widget-header ui-corner-all">
          <div id="filtroubicaciones">Filtro de Ubicaciones</div>
        </legend>  
        <p>
          <label>Por Cuenta de:</label>
          <input type="text" name="buscarClienteu" id="buscarClienteu" size="50" />
        </p>
        <p> 
          <label>Nit:</label>
          <input type="text" name="nitu" id="nitu" value="{nitu}" readonly=""/>
        </p>
        <p>
          <label>Documento de Transporte:</label>
          <input type="text" name="doctteu" id="doctteu" />
        </p>
        <p>
          <label>Orden:</label>
          <input type="text" name="doasignadou" id="doasignadou" />
        </p>
        <p>
          <label>Ubicaci&oacute;n:</label>
          <input type="text" name="ubicacion" id="ubicacion" />
          <input type="hidden" name="ubicacionu" id="ubicacionu" />
          <input type="checkbox" class="css-checkbox" id="todos" name="todos" value="1" />
          <label for="todos" class="css-label mac-style">Todas</label>
        </p>
        <p>
          <label>Referencia:</label>
          <input type="text" name="referencia" id="referencia" size="50" />
          <input type="hidden" name="referenciau" id="referenciau" />
        </p>
      </fieldset>
    </form>
  </div>
</div>
<script>
	$(function() {
    $( "#winubicaciones" ).dialog({
      autoOpen: false,
      resizable: false,
      height: 420,
      width: 600,
			modal: true,
			buttons: {
        "Consultar": function() {
          //Parámetros de envío de información
          $.ajax({
            url: 'index_blank.php?component=ubicaciones&method=listadoUbicaciones',
            type: "POST",
            async: false,
            data: $('#frmubicacionesb').serialize(),
            success: function(msm) {
              $("#winubicaciones").dialog("close");
              $('#componente_central').html(msm);
            }
          });
        }
      },
		});
  });
  
  $(function() {
    $("#todos").click(function() {
		  if($("#todos").is(':checked')) {
        $("#todos").val(1);
		  } else {
        $("#todos").val(0);
		  }
    });
  });

  // Muestra la Ventana de Filtro
  $( "#winubicaciones" ).dialog( "open" );

  // Limpia los campos del formulario
  $("#frmubicacionesb")[0].reset();
  $("#frmubicacionesb input:hidden").val('').trigger('change');
  
	$(function() {
    $("#buscarClienteu").autocomplete("./index_blank.php?component=ubicaciones&method=findCliente", {
      width: 300,
      selectFirst: false
    });

    $("#buscarClienteu").result(function(event, data, formatted) {
      $("#nitu").val(data[1]);
      //Busca el Documento de Transporte
      $("#doctteu").autocomplete("./index_blank.php?component=ubicaciones&method=findDocumento&cliente="+$("#nitu").val(), {
        width: 300,
        selectFirst: false
      });
      
      $("#doctteu").result(function(event, data, formatted) {
        $("#doctteu").val(data[1]);
        $("#doasignadou").val(data[2]);
      });
    });
  });
  
  $(function() {
    $("#ubicacion").autocomplete("./index_blank.php?component=ubicaciones&method=findUbicacion", {
      width: 300,
      selectFirst: false
    });
    
    $("#ubicacion").result(function(event, data, formatted) {
      $("#ubicacionu").val(data[1]);
    });

    $("#referencia").autocomplete("./index_blank.php?component=ubicaciones&method=findReferencia", {
      width: 300,
      selectFirst: false
    });
    
    $("#referencia").result(function(event, data, formatted) {
      $("#referenciau").val(data[1]);
    });
  });
</script>