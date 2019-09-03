<style>
  body { font-size: 62.5%; }
  label { display: inline-block; }
  legend { padding: 0.5em; }
  fieldset fieldset label { display: block; }
  #frmfiltrorl label { width: 170px; margin-left: 5px;} /*ancho de las etiquetas de los campos*/
  .ui-dialog .ui-state-error { padding: .3em; }
  .ui-datepicker-trigger { position: relative; top: 5px; right: 1px; height:22px; } /* Configuración tamaño imagen del DatePicker */
</style>
{COMODIN}
<link rel="stylesheet" type="text/css" href="./integrado/cz_estilos/jquery.autocomplete.css" />
<div id="winfiltrorl" title="Formulario Reporte Levantes">
  <div id="frmfiltrorl">
    <p id="msgfiltrorl">Seleccione uno o varios filtros para delimitar resultados.</p>
    <form name="frmReportelevantes" id="frmReportelevantes" method="post" action="" style="width:100%;">
      <fieldset class="ui-widget ui-widget-content ui-corner-all">
        <legend class="ui-widget ui-widget-header ui-corner-all">
          <div id="filtroexistencias">Filtro Reporte Levantes</div>
        </legend>  
        <p>
          <label>Por Cuenta de:</label>
          <input type="text" name="buscarClienterl" id="buscarClienterl" size="50" value="{cliente}" {soloLectura} />
        </p>
        <p> 
          <label>Nit:</label>
          <input type="text" name="nitrl" id="nitrl" value="{usuario}" {soloLectura} />
        </p>
        <p>
          <label>Fecha Desde:</label>
          <input type="text" name="fechadesderl" id="fechadesderl" placeholder="aaaa-mm-dd" />
        </p>
        <p>
          <label>Fecha Hasta:</label>
          <input type="text" name="fechahastarl" id="fechahastarl" placeholder="aaaa-mm-dd" />
        </p>
        <p>
          <label>Documento de Transporte:</label>
          <input type="text" name="doctterl" id="doctterl" />
        </p>
        <p>
          <label>Orden:</label>
          <input type="text" name="doasignadorl" id="doasignadorl" />
        </p>
        <p>
          <label>Referencia:</label>
          <input type="text" name="referencias" id="referencias" size="50" />
          <input type="hidden" name="referenciarl" id="referenciarl" />
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
    $( "#winfiltrorl" ).dialog({
      autoOpen: false,
      resizable: false,
      height: 420,
      width: 600,
			modal: true,
			buttons: {
        "Consultar": function() {
					//Validación de fechas
					if($("#fechahastarl").val() < $("#fechadesderl").val()) alert("La Fecha Hasta debe ser mayor que la Fecha Desde. "
						+"Por favor, Verificar las Fechas del Filtro.");
					else {
						//Parámetros de envío de información
						$.ajax({
							url: 'index_blank.php?component=reportelevantes&method=listadoReportelevantes',
							type: "POST",
							async: false,
							data: $('#frmReportelevantes').serialize(),
							success: function(msm) {
								$("#winfiltrorl").dialog("close");
								$('#componente_central').html(msm);
							}
						});
					}
        },
			},
		});
  });

  // Muestra la Ventana de Filtro Reporte Levantes
  $( "#winfiltrorl" ).dialog( "open" );

  // Limpia los campos del formulario
  $("#frmReportelevantes")[0].reset();
  $("#frmReportelevantes input:hidden").val('').trigger('change');

  //Inicializa fecha_desde y fecha_hasta
  $(function() {	  
		$("#fechadesderl").datepicker();
    $("#fechadesderl").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    });
      
		$("#fechahastarl").datepicker();		
    $("#fechahastarl").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    });
  });

	$(function() {
    $("#buscarClienterl").autocomplete("./index_blank.php?component=reportelevantes&method=findCliente", {
      width: 300,
      selectFirst: false
    });

    $("#buscarClienterl").result(function(event, data, formatted) {
      $("#nitrl").val(data[1]);
      //Busca el Documento de Transporte
      $("#doctterl").autocomplete("./index_blank.php?component=reportelevantes&method=findDocumento&cliente="+$("#nitrl").val(), {
        width: 300,
        selectFirst: false
      });
      
      $("#doctterl").result(function(event, data, formatted) {
        $("#doctterl").val(data[1]);
        $("#doasignadorl").val(data[2]);
      });
    });
  });
  
  $(function() {
    $("#referencias").autocomplete("./index_blank.php?component=reportelevantes&method=findReferencia", {
      width: 300,
      selectFirst: false
    });
    
    $("#referencias").result(function(event, data, formatted) {
      $("#referenciarl").val(data[1]);
    });
  });
</script>