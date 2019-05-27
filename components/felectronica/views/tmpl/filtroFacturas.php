<style>
  body { font-size: 62.5%; }
  label { display: inline-block; width: 100px; }
  legend { padding: 0.5em; }
  fieldset fieldset label { display: block; }
  #frmfiltroped label { width: 110px; margin-left: 10px;} /*ancho de las etiquetas de los campos*/
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
<div id="winfiltrofel" title="Formulario Facturación Electrónica">
  <div id="frmfiltrofel">
    <p id="msgfiltrofel">Seleccione uno o varios filtros para delimitar resultados.</p>
    <form name="frmfelectronica" id="frmfelectronica" method="post" action="">
      <fieldset class="ui-widget ui-widget-content ui-corner-all">
        <legend class="ui-widget ui-widget-header ui-corner-all">
          <div id="filtrofrmfelectronica">Filtro Facturación Electrónica</div>
        </legend>  
        <p>
          <label>Por Cuenta de:</label>
          <input type="text" name="buscarClientefel" id="buscarClientefel" size="50" value="{cliente}" {soloLectura} />
        </p>
        <p> 
          <label>Nit:</label>
          <input type="text" name="nitfel" id="nitfel" value="{usuario}" {soloLectura} />
        </p>
        <p>
          <label>Fecha Desde:</label>
          <input type="text" name="fechadesdefel" id="fechadesdefel" placeholder="aaaa-mm-dd" />
        </p>
        <p>
          <label>Fecha Hasta:</label>
          <input type="text" name="fechahastafel" id="fechahastafel" placeholder="aaaa-mm-dd" />
        </p>
        <p>
          <label>Factura:</label>
          <input type="text" name="facturafiltrofel" id="facturafiltrofel" />
        </p>
        <p>
          <label>Orden:</label>
          <input type="text" name="dofiltrofel" id="dofiltrofel" />
        </p>        
        <p>
          <label>Documento:</label>
          <input type="text" name="docfiltrofel" id="docfiltrofel" />
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
    $( "#winfiltrofel" ).dialog({
      autoOpen: false,
      resizable: false,
      height: 420,
      width: 550,
			modal: true,
			buttons: {
        "Consultar": function() {
					//Validación de fechas
					if($("#fechahastafel").val() < $("#fechadesdefel").val()) alert("La Fecha Hasta debe ser mayor que la Fecha Desde. "
						+"Por favor, Verificar las Fechas del Filtro.");
					else {
						//Parámetros de envío de información
						$.ajax({
							url: 'index_blank.php?component=felectronica&method=listadoFacturas',
							type: "POST",
							async: false,
							data: $('#frmfelectronica').serialize(),
							success: function(msm) {
								$("#winfiltrofel").dialog("close");
								$('#componente_central').html(msm);
							}
						});
					}
        },
			},
		});
  });

  // Muestra la Ventana de Filtro de Existencias
  $( "#winfiltrofel" ).dialog( "open" );

  // Limpia los campos del formulario
  $("#frmfelectronica")[0].reset();

  //Inicializa fecha_desde y fecha_hasta con la fecha actual
  $(function() {	  
		$("#fechadesdefel").datepicker();
    $("#fechadesdefel").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    });
        
		$("#fechahastafel").datepicker();		
    $("#fechahastafel").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    });
  });

	$(function() {
    $("#buscarClientefel").autocomplete("./index_blank.php?component=felectronica&method=findCliente", {
      width: 300,
      selectFirst: false
    });

    $("#buscarClientefel").result(function(event, data, formatted) {
      $("#nitfel").val(data[1]);
    });
  });
</script>