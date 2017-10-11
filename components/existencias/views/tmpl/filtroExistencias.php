<style>
  body { font-size: 62.5%; }
  label { display: inline-block; width: 100px; }
  legend { padding: 0.5em; }
  fieldset fieldset label { display: block; }
  #frmfiltroex label { width: 110px; margin-left: 10px;} /*ancho de las etiquetas de los campos*/
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
<div id="winfiltroex" title="Formulario de Existencias">
  <div id="frmfiltroex">
    <p id="msgfiltroex">Seleccione uno o varios filtros para delimitar resultados.</p>
    <form name="frmExistencias" id="frmExistencias" method="post" action="">
      <fieldset class="ui-widget ui-widget-content ui-corner-all">
        <legend class="ui-widget ui-widget-header ui-corner-all">
          <div id="filtroexistencias">Filtro de Existencias</div>
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
        <p> 
          <label>Tipo de Ingreso:</label>
          <select name="tipoingresofe" id="tipoingresofe" class="ui-widget-content">
            <option value="">[Seleccionar]</option>
            <option value="consignada">Consignada</option>
            <option value="asignada">Asignada</option>
          </select>
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
    $( "#winfiltroex" ).dialog({
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
							url: 'index_blank.php?component=existencias&method=listadoExistencias',
							type: "POST",
							async: false,
							data: $('#frmExistencias').serialize(),
							success: function(msm) {
								$("#winfiltroex").dialog("close");
								$('#componente_central').html(msm);
							}
						});
					}
        },
			},
		});
  });

  // Muestra la Ventana de Filtro de Existencias
  $( "#winfiltroex" ).dialog( "open" );

  // Limpia los campos del formulario
  $("#frmExistencias")[0].reset();

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
    $("#buscarClientefe").autocomplete("./index_blank.php?component=existencias&method=findCliente", {
      width: 300,
      selectFirst: false
    });

    $("#buscarClientefe").result(function(event, data, formatted) {
      $("#nitfe").val(data[1]);
    });
  });
</script>