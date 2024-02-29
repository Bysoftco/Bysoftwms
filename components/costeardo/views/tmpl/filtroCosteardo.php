<style>
  body { font-family: Arial,Helvetica,sans-serif; font-size: 62.5%; color:#523A0B; }  	
  label { display: inline-block; width: 90px; margin-left: 5px; }
  legend { padding: 0.5em; }
  fieldset fieldset label { display: block; }
  #frmFiltrocdo label { width: 100px; margin-left: 5px;}/*ancho de las etiquetas de los campos*/
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
<div id="vfiltro" title="Formulario de Costear DO">
  <div id="frmfiltroc">
    <p id="msgboxfiltro">Seleccione uno o varios filtros para delimitar resultados.</p>
    <form name="frmFiltrocdo" id="frmFiltrocdo" method="post" action="">
      <fieldset class="ui-widget ui-widget-content ui-corner-all">
        <legend class="ui-widget ui-widget-header ui-corner-all">
          <div id="filtrocosteardo">Filtro de Costear DO</div>
        </legend>  
        <p>
          <label>Por Cuenta de:</label>
          <input type="text" name="buscarClientec" id="buscarClientec" size="50" />
        </p>
        <p> 
          <label>Nit:</label>
          <input type="text" name="nitc" id="nitc" />
        </p>
        <p>
          <label>Fecha Desde:</label>
          <input type="text" name="fechadesdec" id="fechadesdec" placeholder="aaaa-mm-dd" />
        </p>
        <p>
          <label>Fecha Hasta:</label>
          <input type="text" name="fechahastac" id="fechahastac" placeholder="aaaa-mm-dd" />
        </p>
        <p>
          <label>DO:</label>
          <input type="text" name="doasignadoc" id="doasignadoc" />
        </p>
      </fieldset>
    </form>
  </div>
</div>
<script>
	$(function() {
    $( "#vfiltro" ).dialog({
      autoOpen: false,
      resizable: false,
      height: 400,
      width: 530,
			modal: true,
			buttons: {
        "Consultar": function() {
					//Validación de fechas
					if($("#fechahastac").val() < $("#fechadesdec").val()) alert("La Fecha Hasta debe ser mayor que la Fecha Desde. "
						+"Por favor, Verificar las Fechas del Filtro.");
					else {
						//Parámetros de envío de información
						$.ajax({
							url: 'index_blank.php?component=costeardo&method=listadoCosteardo',
							type: "POST",
							async: false,
							data: $('#frmFiltrocdo').serialize(),
							success: function(msm) {
								$("#vfiltro").dialog("close");
								$('#componente_central').html(msm);
							}
						});
					}
        },
			},
		});
  });

  // Muestra la Ventana de Filtro de Costear DO
  $( "#vfiltro" ).dialog( "open" );

  // Limpia los campos del formulario
  $("#frmFiltrocdo")[0].reset();

  //Inicializa fecha_desde y fecha_hasta con la fecha actual
  $(function() {
    //$("#fecha_desdec").datepicker({dateFormat:"yy-mm-dd"}).datepicker("setDate",new Date());	  
		$("#fechadesdec").datepicker();
    $("#fechadesdec").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    });
    
    //$("#fecha_hastac").datepicker({dateFormat:"yy-mm-dd"}).datepicker("setDate",new Date());    
		$("#fechahastac").datepicker();		
    $("#fechahastac").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    });
  });

	$(function() {
    $("#buscarClientec").autocomplete("./index_blank.php?component=costeardo&method=findCliente", {
      width: 300,
      selectFirst: false
    });

    $("#buscarClientec").result(function(event, data, formatted) {
      $("#nitc").val(data[1]);
    });
  });
</script>