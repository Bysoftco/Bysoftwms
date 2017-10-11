<style>
  body { font-size: 62.5%; }
  label { display: inline-block; }
  legend { padding: 0.5em; }
  fieldset fieldset label { display: block; }
  #frmfiltrois label { width: 135px; margin-left: 5px;} /*ancho de las etiquetas de los campos*/
  .ui-dialog .ui-state-error { padding: .3em; }  
</style>
{COMODIN}
<link rel="stylesheet" type="text/css" href="./integrado/cz_estilos/jquery.autocomplete.css" />
<div id="winfiltrois" title="Generar Interfaz Items Sizfra">
  <div id="frmfiltrois" >    
    <form name="frmSizfra" id="frmSizfra" method="post" action="">
      <fieldset class="ui-widget ui-widget-content ui-corner-all">        
        <p>
          <label>Nombre de Interfaz <span style="color:red">*</span>:</label>
          <input type="text" name="nombreinterfaz" id="nombreinterfaz" size="20" class="required ui-widget-content" />
        </p>
        <p>
          <label>Por Cuenta de:</label>
          <input type="text" name="buscarClientefe" id="buscarClientefe" size="50" value="{cliente}" />
        </p>
        <p> 
          <label>Nit:</label>
          <input type="text" name="nitfe" id="nitfe" value="{usuario}" readonly="" />
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
    $("#frmSizfra").validate({
      submitHandler: function(form) {
        bValid = true;
        
        //Validaci�n de fechas
        if($("#fechahastafe").val() < $("#fechadesdefe").val()) {
          $("#msgbox_filtro_requerido").html('La Fecha Hasta debe ser mayor que la Fecha Desde. Por favor, verificar las fechas del filtro.').addClass('ui-state-error');
          setTimeout(function() {
            $("#msgbox_filtro_requerido").html('Seleccione uno o varios filtros para delimitar resultados.').removeClass('ui-state-error');
          }, 4000);
          bValid = false;
        }
        
        $.post("index_blank.php?component=sizfra_item&method=findInterfaz&nombreinterfaz="+$("#nombreinterfaz").val() ,function(data) {          
          if(bValid) {            
            //Par�metros de env�o de informaci�n
            $.ajax({
              url: 'index_blank.php?component=sizfra_item&method=listadoSizfra',
              type: "POST",
              async: false,
              data: $('#frmSizfra').serialize(),
              success: function(msm) {
                $("#winfiltrois").dialog("close");
                $('#componente_central').html(msm);
              }
            });
          }
        });
      }
    });
  });
 
	$(function() {
    $( "#winfiltrois" ).dialog({
      autoOpen: false,
      resizable: false,
      height: 448,
      width: 600,
			modal: true,
			buttons: {
        "Aceptar": function() {
          $("#frmSizfra").submit();
        }
      },
		});
  });

  // Muestra la Ventana de Filtro
  $( "#winfiltrois" ).dialog( "open" );

  // Limpia los campos del formulario
  $("#frmSizfra")[0].reset();
    
    
    
    $(function() {
    $("#buscarClientefe").autocomplete("./index_blank.php?component=sizfra&method=findCliente", {
      width: 300,
      selectFirst: false
    });

    $("#buscarClientefe").result(function(event, data, formatted) {
      $("#nitfe").val(data[1]);
    });
  });
   
    
    
</script>