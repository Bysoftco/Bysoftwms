<style>
  body { font-size: 62.5%; }
  label { display: inline-block; width: 100px; }
  legend { padding: 0.5em; }
  fieldset fieldset label { display: block; }
  #frmfiltroex label { width: 110px; margin-left: 10px;} /*ancho de las etiquetas de los campos*/
  .ui-datepicker-trigger { position: relative; top: 6px; right: 1px; height:23px; } /* Configuración tamaño imagen del DatePicker */
</style>
{COMODIN}
<link rel="stylesheet" type="text/css" href="./integrado/cz_estilos/jquery.autocomplete.css" />
<div id="winfiltrov" title="Formulario de Vencimientos">
  <div id="frmfiltrov">
    <p id="msgfiltrov">Seleccione uno o varios filtros para delimitar resultados.</p>
    <form name="frmVencimientos" id="frmVencimientos" method="post" action="">
      <fieldset class="ui-widget ui-widget-content ui-corner-all">
        <legend class="ui-widget ui-widget-header ui-corner-all">
          <div id="filtrovencimientos">Filtro de Vencimientos</div>
        </legend>  
        <p>
          <label>Por Cuenta de:</label>
          <input type="text" name="buscarClientefe" id="buscarClientefe" size="50" value="{cliente}" {soloLectura} />
        </p>
        <p> 
          <label>Nit:</label>
          <input type="text" name="nitfe" id="nitfe" value="{usuario}" {soloLectura} />
        </p>
        <p>
          <label>Fecha Desde:</label>
          <input type="text" name="fechadesdefe" id="fechadesdefe" placeholder="aaaa-mm-dd" />
        </p>
        <p>
          <label>Fecha Hasta:</label>
          <input type="text" name="fechahastafe" id="fechahastafe" placeholder="aaaa-mm-dd" />
        </p>
        <p>
          <label>Orden:</label>
          <input type="text" name="doasignadofe" id="doasignadofe" />
        </p>
      </fieldset>
    </form>
  </div>
</div>
<input type="hidden" name="usuario" id="usuario" value="{usuario}" />
<input type="hidden" name="cliente" id="cliente" value="{cliente}" />
<input type="hidden" name="soloLectura" id="soloLectura" value="{soloLectura}" />
<script>
	$(function() {
    $( "#winfiltrov" ).dialog({
      autoOpen: false,
      resizable: false,
      height: 420,
      width: 550,
			modal: true,
			buttons: {
        "Consultar": function() {
					//Validación de fechas
					if($("#fechahastafe").val() < $("#fechadesdefe").val()) alert("La Fecha Hasta debe ser mayor que la Fecha Desde. "
						+"Por favor, Verificar las Fechas del Filtro.");
					else {
						//Parámetros de envío de información
						$.ajax({
							url: 'index_blank.php?component=vencimientos&method=listadoVencimientos',
							type: "POST",
							async: false,
							data: $('#frmVencimientos').serialize(),
							success: function(msm) {
								$("#winfiltrov").dialog("close");
								$('#componente_central').html(msm);
							}
						});
					}
        },
			},
		});
  });

  // Muestra la Ventana de Filtro de Costear DO
  $( "#winfiltrov" ).dialog( "open" );

  // Limpia los campos del formulario
  $("#frmVencimientos")[0].reset();

  //Inicializa fecha_desde y fecha_hasta con la fecha actual
  $(function() {
    //$("#fecha_desdec").datepicker({dateFormat:"yy-mm-dd"}).datepicker("setDate",new Date());	  
		$("#fechadesdefe").datepicker();
    $("#fechadesdefe").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    });
    
    //$("#fecha_hastac").datepicker({dateFormat:"yy-mm-dd"}).datepicker("setDate",new Date());    
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
    $("#buscarClientefe").autocomplete("./index_blank.php?component=vencimientos&method=findCliente", {
      width: 300,
      selectFirst: false
    });

    $("#buscarClientefe").result(function(event, data, formatted) {
      $("#nitfe").val(data[1]);
    });
  });
</script>