<style>
  body { font-family: Arial,Helvetica,sans-serif; font-size: 11px; color:#523A0B; }  	
  label { display: inline-block; width: 180px; margin-left: 5px; }
  legend { padding: 0.5em; }
  fieldset fieldset label { display: block; }
  #formulario label { width: 160px; margin-left: 10px;}/*ancho de las etiquetas de los campos*/
  h1 { font-size: 1.2em; margin: .6em 0; }
  div#users-contain { width: 100%; margin: 5px 0; margin-left: 0%; }
  div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
  div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
  .ui-dialog .ui-state-error { padding: .3em; }
  tbody tr.odd th,tbody tr.odd td {border-color:#EBE5D9;background:#F7F4EE;}
  tbody tr:hover td,tbody tr:hover th {background:#EAECEE;border-color:#523A0B;}
  .ui-datepicker-trigger { position:relative; top: 5px; right: 1px; height:22px; } /* Configuración tamaño imagen del DatePicker */
</style>
{COMODIN}
<link rel="stylesheet" type="text/css" href="./integrado/cz_estilos/jquery.autocomplete.css" />
<div id="winfiltro" title="Formulario de Tracking">
  <div id="frmfiltroc">
    <p id="msgfiltro">Seleccione uno o varios filtros para delimitar resultados.</p>
    <form name="frmFiltrotr" id="frmFiltrotr" method="post" action="">
      <fieldset class="ui-widget ui-widget-content ui-corner-all">
        <legend class="ui-widget ui-widget-header ui-corner-all">
          <div id="filtrotracking">Filtro de Tracking</div>
        </legend>  
        <p>
          <label>Por Cuenta de:</label>
          <input type="text" name="buscarClientet" id="buscarClientet" size="50" />
        </p>
        <p> 
          <label>Nit:</label>
          <input type="text" name="nitt" id="nitt" />
        </p>
        <p>
          <label>Fecha Desde:</label>
          <input type="text" name="fechadesdet" id="fechadesdet" placeholder="aaaa-mm-dd" />
        </p>
        <p>
          <label>Fecha Hasta:</label>
          <input type="text" name="fechahastat" id="fechahastat" placeholder="aaaa-mm-dd" />
        </p>
        <p>
          <label>DO:</label>
          <input type="text" name="doasignadot" id="doasignadot" />
        </p>
        <p>
          <label>Documento de Transporte:</label>
          <input type="text" name="docttet" id="docttet" />
        </p>
        <p>
          <label>Email Destino:</label>
          <input type="text" name="emaildestino" id="emaildestino" />
        </p>
      </fieldset>
    </form>
  </div>
</div>
<script>
	$(function() {
    $( "#winfiltro" ).dialog({
      autoOpen: false,
      resizable: false,
      height: 500,
      width: 620,
			modal: true,
			buttons: {
        "Consultar": function() {
					//Validación de fechas
					if($("#fechahastat").val() < $("#fechadesdet").val()) alert("La Fecha Hasta debe ser mayor que la Fecha Desde. "
						+"Por favor, Verificar las Fechas del Filtro.");
					else {
						//Parámetros de envío de información
						$.ajax({
							url: 'index_blank.php?component=tracking&method=listadoTracking',
							type: "POST",
							async: false,
							data: $('#frmFiltrotr').serialize(),
							success: function(msm) {
								$("#winfiltro").dialog("close");
								$('#componente_central').html(msm);
							}
						});
					}
        },
			},
		});
  });

  // Muestra la Ventana de Filtro de Costear DO
  $( "#winfiltro" ).dialog( "open" );

  // Limpia los campos del formulario
  $("#frmFiltrotr")[0].reset();

  //Inicializa fecha_desde y fecha_hasta con la fecha actual
  $(function() {
    //$("#fecha_desdec").datepicker({dateFormat:"yy-mm-dd"}).datepicker("setDate",new Date());	  
		$("#fechadesdet").datepicker();
    $("#fechadesdet").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    });
    
    //$("#fecha_hastac").datepicker({dateFormat:"yy-mm-dd"}).datepicker("setDate",new Date());    
		$("#fechahastat").datepicker();		
    $("#fechahastat").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    });
  });

	$(function() {
    $("#buscarClientet").autocomplete("./index_blank.php?component=tracking&method=findCliente", {
      width: 300,
      selectFirst: false
    });

    $("#buscarClientet").result(function(event, data, formatted) {
      $("#nitt").val(data[1]);
			$("#emaildestino").val(data[2]);
    });
  });
</script>