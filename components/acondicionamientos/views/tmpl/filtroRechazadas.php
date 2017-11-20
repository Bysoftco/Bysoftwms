<style>
  body { font-size: 62.5%; }
  label { display: inline-block; width: 100px; }
  legend { padding: 0.5em; }
  fieldset fieldset label { display: block; }
  #frmfiltrore label { width: 110px; margin-left: 10px;} /*ancho de las etiquetas de los campos*/
  h1 { font-size: 1.2em; margin: .6em 0; }
  div#users-contain { width: 100%; margin: 5px 0; margin-left: 0%; }
  div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
  div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
  .ui-dialog .ui-state-error { padding: .3em; }
  tbody tr.odd th,tbody tr.odd td {border-color:#EBE5D9;background:#F7F4EE;}
  tbody tr:hover td,tbody tr:hover th {background:#EAECEE;border-color:#523A0B;}
  .ui-datepicker-trigger { position: relative; top: 5px; right: 1px; height:22px; } /* Configuración tamaño imagen del DatePicker */
</style>
{COMODIN}
<link rel="stylesheet" type="text/css" href="./integrado/cz_estilos/jquery.autocomplete.css" />
<div id="winfiltrore" title="Formulario de Rechazadas">
  <div id="frmfiltrore">
    <p id="msgfiltrore">Seleccione uno o varios filtros para delimitar resultados.</p>
    <form name="frmRechazadas" id="frmRechazadas" method="post" action="">
      <fieldset class="ui-widget ui-widget-content ui-corner-all">
        <legend class="ui-widget ui-widget-header ui-corner-all">
          <div id="filtrorechazadas">Filtro de Rechazadas</div>
        </legend>  
        <p>
          <label>Por Cuenta de:</label>
          <input type="text" name="buscarClientefr" id="buscarClientefr" size="50" value="{cliente}" {soloLectura} />
        </p>
        <p> 
          <label>Nit:</label>
          <input type="text" name="nitfr" id="nitfr" value="{usuario}" {soloLectura} />
        </p>
        <p>
          <label>Fecha Desde:</label>
          <input type="text" name="fechadesdefr" id="fechadesdefr" placeholder="aaaa-mm-dd" />
        </p>
        <p>
          <label>Fecha Hasta:</label>
          <input type="text" name="fechahastafr" id="fechahastafr" placeholder="aaaa-mm-dd" />
        </p>
        <p>
          <label>Orden:</label>
          <input type="text" name="doasignadofr" id="doasignadofr" />
        </p>
        <p> 
          <label>Tipo de Rechazo:</label>
          <select name="tiporechazofr" id="tiporechazofr">{select_tiporechazo}</select>
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
    $( "#winfiltrore" ).dialog({
      autoOpen: false,
      resizable: false,
      height: 420,
      width: 550,
			modal: true,
			buttons: {
        "Consultar": function() {
					//Validación de fechas
					if($("#fechahastafr").val() < $("#fechadesdefr").val()) alert("La Fecha Hasta debe ser mayor que la Fecha Desde. "
						+"Por favor, Verificar las Fechas del Filtro.");
					else {
						//Parámetros de envío de información
						$.ajax({
							url: 'index_blank.php?component=acondicionamientos&method=listadoRechazadas',
							type: "POST",
							async: false,
							data: $('#frmRechazadas').serialize(),
							success: function(msm) {
								$("#winfiltrore").dialog("close");
								$('#componente_central').html(msm);
							}
						});
					}
        },
			},
		});
  });

  // Muestra la Ventana de Filtro de Rechazadas
  $( "#winfiltrore" ).dialog( "open" );

  // Limpia los campos del formulario
  $("#frmRechazadas")[0].reset();

  //Inicializa fecha_desde y fecha_hasta con la fecha actual
  $(function() {
    //Configuración Fecha Desde
		$("#fechadesdefr").datepicker();
    $("#fechadesdefr").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    }); 
    //Configuración Fecha Hasta   
		$("#fechahastafr").datepicker();		
    $("#fechahastafr").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    });
  });

	$(function() {
    $("#buscarClientefr").autocomplete("./index_blank.php?component=existencias&method=findCliente", {
      width: 300,
      selectFirst: false
    });

    $("#buscarClientefr").result(function(event, data, formatted) {
      $("#nitfr").val(data[1]);
    });
  });
</script>