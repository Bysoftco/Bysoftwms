<style>
  body { font-size: 62.5%; }
  label { display: inline-block; }
  legend { padding: 0.5em; }
  fieldset fieldset label { display: block; }
  #frmfiltrois label { width: 135px; margin-left: 5px;} /*ancho de las etiquetas de los campos*/
  .ui-dialog .ui-state-error { padding: .3em; }
  .ui-datepicker-trigger { position: relative; top: 5px; right: 1px; height:22px; } /* Configuración tamaño imagen del DatePicker */
</style>
{COMODIN}
<link rel="stylesheet" type="text/css" href="./integrado/cz_estilos/jquery.autocomplete.css" />
<div id="winfiltrois" title="Generar Interfaz Sizfra">
  <div id="frmfiltrois">
    <p id="msgbox_filtro_requerido">Seleccione uno o varios filtros para delimitar resultados.</p>
    <form name="frmSizfra" id="frmSizfra" method="post" action="">
      <fieldset class="ui-widget ui-widget-content ui-corner-all">
        <legend class="ui-widget ui-widget-header ui-corner-all">
          <div id="filtrosizfra">Filtro de Datos Sizfra</div>
        </legend>  
        <p>
          <label>Nombre de Interfaz <span style="color:red">*</span>:</label>
          <input type="text" name="nombreinterfaz" id="nombreinterfaz" size="20" class="required ui-widget-content" />

          <label>Consecutivo <span style="color:red">*</span>:</label>
          <input type="text" name="consecutivo" id="consecutivo" size="10" class="required ui-widget-content" />
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
          <label>Fecha Desde <span style="color:red">*</span>:</label>
          <input type="text" name="fechadesdefe" id="fechadesdefe" placeholder="aaaa-mm-dd" class="required ui-widget-content" />
        </p>
        <p>
          <label>Fecha Hasta <span style="color:red">*</span>:</label>
          <input type="text" name="fechahastafe" id="fechahastafe" placeholder="aaaa-mm-dd" class="required ui-widget-content" />
        </p>
        <p>
          <label>Email Destino <span style="color:red">*</span>:</label>
          <input type="email" name="emaildestino" id="emaildestino" class="required ui-widget-content" />
        </p>
      </fieldset>
    </form>
    <p id="msgfiltrois">El campo marcado con un asterisco (<span style="color:red">*</span>) es obligatorio.</p>
  </div>
</div>
<input type="hidden" name="usuario" id="usuario" value="{usuario}" />
<input type="hidden" name="cliente" id="cliente" value="{cliente}" />
<script>
  $(document).ready(function() {
    $("#frmSizfra").validate({
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
        
        $.post("index_blank.php?component=sizfra&method=findInterfaz&nombreinterfaz="+$("#nombreinterfaz").val() ,function(data) {
          //Validación información del formulario
          if(data!=0) {
            bValid = false;
            $("#msgbox_filtro_requerido").html('El Nombre de la Interfaz '+$("#nombreinterfaz").val()+' ya existe en la BD. Por favor, escribir un nuevo Nombre.').addClass('ui-state-error');
            setTimeout(function(){
              $("#msgbox_filtro_requerido").html('Seleccione uno o varios filtros para delimitar resultados.').removeClass('ui-state-error');
            }, 5000);
          }

          if(bValid) {
            //Parámetros de envío de información
            $.ajax({
              url: 'index_blank.php?component=sizfra&method=listadoSizfra',
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

  //Inicializa fecha_desde y fecha_hasta con la fecha actual
  $(function() {
		$("#fechadesdefe").datepicker();
    $("#fechadesdefe").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    });
    
		$("#fechahastafe").datepicker();		
    $("#fechahastafe").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    });
  });
  
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