{COMODIN}
<link rel="stylesheet" type="text/css" href="./template/css/filtros.css"/>
<link rel="stylesheet" type="text/css" href="./integrado/cz_estilos/jquery.autocomplete.css" />
<div id="winfiltrosal" title="Formulario Salidas de Mercancía">
  <div id="frmfiltrosal">
    <p id="msgfiltrosal">Seleccione uno o varios filtros para delimitar resultados.</p>
    <form name="frmSalidas" id="frmSalidas" method="post" action="">
      <fieldset class="ui-widget ui-widget-content ui-corner-all">
        <legend class="ui-widget ui-widget-header ui-corner-all">
          <div id="filtrosalidas">Filtro Salidas de Mercancía</div>
        </legend>  
        <p>
          <label>Por Cuenta de:</label>
          <input type="text" name="buscarClientefsl" id="buscarClientefsl" size="50" value="{cliente}" {soloLectura} />
        </p>
        <p> 
          <label>Nit:</label>
          <input type="text" name="nitfsl" id="nitfsl" value="{usuario}" {soloLectura} />
        </p>
        <p>
          <label>Fecha Desde:</label>
          <input type="text" name="fechadesdefsl" id="fechadesdefsl" placeholder="aaaa-mm-dd" />
        </p>
        <p>
          <label>Fecha Hasta:</label>
          <input type="text" name="fechahastafsl" id="fechahastafsl" placeholder="aaaa-mm-dd" />
        </p>
        <p>
          <label>Orden:</label>
          <input type="text" name="doasignadofsl" id="doasignadofsl" />
        </p>
        <p>
          <label>Documento de Transporte:</label>
          <input type="text" name="docttefsl" id="docttetfsl" />
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
    $( "#winfiltrosal" ).dialog({
      autoOpen: false,
      resizable: false,
      height: 420,
      width: 600,
			modal: true,
			buttons: {
        "Consultar": function() {
					//Validación de fechas
					if($("#fechahastafsl").val() < $("#fechadesdefsl").val()) alert("La Fecha Hasta debe ser mayor que la Fecha Desde. "
						+"Por favor, Verificar las Fechas del Filtro.");
					else {
						//Parámetros de envío de información
						$.ajax({
							url: 'index_blank.php?component=salidas&method=listadoSalidas',
							type: "POST",
							async: false,
							data: $('#frmSalidas').serialize(),
							success: function(msm) {
								$("#winfiltrosal").dialog("close");
								$('#componente_central').html(msm);
							}
						});
					}
        },
			},
		});
  });

  // Muestra la Ventana Filtro Salidas de Mercancía
  $( "#winfiltrosal" ).dialog( "open" );

  // Limpia los campos del formulario
  $("#frmSalidas")[0].reset();

  //Inicializa fecha_desde y fecha_hasta con la fecha actual
  $(function() {
    //$("#fecha_desdec").datepicker({dateFormat:"yy-mm-dd"}).datepicker("setDate",new Date());	  
		$("#fechadesdefsl").datepicker();
    $("#fechadesdefsl").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    });
    
    //$("#fecha_hastac").datepicker({dateFormat:"yy-mm-dd"}).datepicker("setDate",new Date());    
		$("#fechahastafsl").datepicker();		
    $("#fechahastafsl").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    });
  });

	$(function() {
    $("#buscarClientefsl").autocomplete("./index_blank.php?component=salidas&method=findCliente", {
      width: 300,
      selectFirst: false
    });

    $("#buscarClientefsl").result(function(event, data, formatted) {
      $("#nitfsl").val(data[1]);
    });
  });
</script>