<style>
  body { font-size: 62.5%; }
  label { display: inline-block; }
  legend { padding: 0.5em; }
  fieldset fieldset label { display: block; }
  #frmfiltroisc label { width: 135px; margin-left: 5px;} /*ancho de las etiquetas de los campos*/
  .ui-dialog .ui-state-error { padding: .3em; }
  .ui-datepicker-trigger { position: relative; top: 5px; right: 1px; height:22px; } /* Configuraci�n tama�o imagen del DatePicker */
</style>
{COMODIN}
<div id="winfiltroisc" title="Deshacer Interfaz Sizfra">
  <div id="frmfiltroisc">
    <p id="msgbox_filtro_requeridoc">Seleccione uno o varios filtros para delimitar resultados.</p>
    <form name="frmSizfrac" id="frmSizfrac" method="post" action="">
      <fieldset class="ui-widget ui-widget-content ui-corner-all">
        <legend class="ui-widget ui-widget-header ui-corner-all">
          <div id="filtrosizfrac">Filtro de Datos Sizfra</div>
        </legend>  
        <p>
          <label>Nombre de Interfaz <span style="color:red">*</span>:</label>
          <input type="text" name="nombreinterfazc" id="nombreinterfazc" size="20" class="required ui-widget-content" />
        </p>
      </fieldset>
    </form>
    <p id="msgfiltroisc">El campo marcado con un asterisco (<span style="color:red">*</span>) es obligatorio.</p>
  </div>
</div>
<script>
  $(document).ready(function() {
    $("#frmSizfrac").validate({
      submitHandler: function(form) {
        bValid = true;                        
      }
    });
  });
 
	$(function() {
    $( "#winfiltroisc" ).dialog({
      autoOpen: false,
      resizable: false,
      height: 255,
      width: 600,
			modal: true,
			buttons: {
        "Aceptar": function() {
          $("#frmSizfrac").submit();
        }
      },
		});
  });

  // Muestra la Ventana de Filtro
  $( "#winfiltroisc" ).dialog( "open" );

  // Limpia los campos del formulario
  $("#frmSizfrac")[0].reset();
</script>