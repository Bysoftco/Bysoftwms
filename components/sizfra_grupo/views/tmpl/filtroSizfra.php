<style>
  body { font-size: 62.5%; }
  label { display: inline-block; }
  legend { padding: 0.5em; }
  fieldset fieldset label { display: block; }
  #frmfiltroisx label { width: 135px; margin-left: 5px;} /*ancho de las etiquetas de los campos*/
  .ui-dialog .ui-state-error { padding: .3em; }  
</style>
{COMODIN}
<link rel="stylesheet" type="text/css" href="./integrado/cz_estilos/jquery.autocomplete.css" />
<div id="winfiltroisx" title="Generar Interfaz Grupos Sizfra">
  <div id="frmfiltroisx" >    
    <form name="frmSizfrax" id="frmSizfrax" method="post" action="">
      <fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px;">
        <p>
          <label>Nombre de Interfaz <span style="color:red">*</span>:</label>
          <input type="text" name="nombreinterfaz" id="nombreinterfaz" size="20" class="required ui-widget-content" />
        </p>        
        <p>
          <label>Email Destino <span style="color:red">*</span>:</label>
          <input type="email" name="emaildestino" id="emaildestino" class="required ui-widget-content" />
        </p>
      </fieldset>
    </form>    
  </div>
</div>
<input type="hidden" name="usuario" id="usuario" value="{usuario}" />
<input type="hidden" name="cliente" id="cliente" value="{cliente}" />
<script>
  $(document).ready(function() {
    $("#frmSizfrax").validate({
      submitHandler: function(form) {
        bValid = true;
        
        //Validación de fechas
        if($("#fechahastafe").val() < $("#fechadesdefe").val()) {
          $("#msgbox_filtro_requerido").html('La Fecha Hasta debe ser mayor que la Fecha Desde. Por favor, verificar las fechas del filtro.').addClass('ui-state-error');
          setTimeout(function() {
            $("#msgbox_filtro_requerido").html('Seleccione uno o varios filtros para delimitar resultados.').removeClass('ui-state-error');
          }, 4000);
          bValid = false;
        }
        
        $.post("index_blank.php?component=sizfra_grupo&method=findInterfaz&nombreinterfaz="+$("#nombreinterfaz").val() ,function(data) {          
          if(bValid) {            
            //Parámetros de envío de información
            $.ajax({
              url: 'index_blank.php?component=sizfra_grupo&method=listadoSizfra',
              type: "POST",
              async: false,
              data: $('#frmSizfrax').serialize(),
              success: function(msm) {
                $("#winfiltroisx").dialog("close");
                $('#componente_central').html(msm);
              }
            });
          }
        });
      }
    });
  });
 
	$(function() {
    $( "#winfiltroisx" ).dialog({
      autoOpen: false,
      resizable: false,
      height: 200,
      width: 600,
			modal: true,
			buttons: {
        "Aceptar": function() {
          $("#frmSizfrax").submit();
        }
      },
		});
  });

  // Muestra la Ventana de Filtro
  $( "#winfiltroisx" ).dialog( "open" );

  // Limpia los campos del formulario
  $("#frmSizfrax")[0].reset(); 
</script>