<style>
  body { font-family: Arial,Helvetica,sans-serif; font-size: 11px; color:#523A0B; }  	
  label { display: inline-block; width: 120px; margin-left: 5px; }
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
<div id="winfiltroeac" title="Formulario de Acondicionados">
  <div id="frmfiltroeac">
    <p id="msgfiltroe">Seleccione uno o varios filtros para delimitar resultados.</p>
    <form name="frmfiltroetac" id="frmfiltroetac" method="post" action="">
      <fieldset class="ui-widget ui-widget-content ui-corner-all">
        <legend class="ui-widget ui-widget-header ui-corner-all">
          <div id="filtroetiquetarac">Filtro de Acondicionados</div>
        </legend>  
        <p>
          <label>Por Cuenta de:</label>
          <input type="text" name="buscarClienteeac" id="buscarClienteeac" size="50" />
        </p>
        <p> 
          <label>Nit:</label>
          <input type="text" name="niteac" id="niteac" />
        </p>
        <p>
          <label>Fecha Desde:</label>
          <input type="text" name="fechadesdeeac" id="fechadesdeeac" placeholder="aaaa-mm-dd" />
        </p>
        <p>
          <label>Fecha Hasta:</label>
          <input type="text" name="fechahastaeac" id="fechahastaeac" placeholder="aaaa-mm-dd" />
        </p>
        <p>
          <label>Acondicionamiento:</label>
          <input type="text" name="nacondiciona" id="nacondiciona" />
        </p>
      </fieldset>
    </form>
  </div>
</div>
<script>
	$(function() {
    $( "#winfiltroeac" ).dialog({
      autoOpen: false,
      resizable: false,
      height: 410,
      width: 550,
			modal: true,
			buttons: {
        "Consultar": function() {
					//Validación de fechas
					if($("#fechahastaeac").val() < $("#fechadesdeeac").val()) alert("La Fecha Hasta debe ser mayor que la Fecha Desde. "
						+"Por favor, Verificar las Fechas del Filtro.");
					else {
						//Parámetros de envío de información
						$.ajax({
							url: 'index_blank.php?component=acondicionamientos&method=listadoEtiquetar',
							type: "POST",
							async: false,
							data: $('#frmfiltroetac').serialize(),
							success: function(msm) {
								$("#winfiltroeac").dialog("close");
								$('#componente_central').html(msm);
							}
						});
					}
        },
			},
		});
  });

  // Muestra la Ventana de Filtro para Etiquetar Alistamientos
  $( "#winfiltroeac" ).dialog( "open" );

  // Limpia los campos del formulario
  $("#frmfiltroetac")[0].reset();

  //Inicializa fecha_desde y fecha_hasta con la fecha actual
  $(function() {
    //$("#fecha_desdec").datepicker({dateFormat:"yy-mm-dd"}).datepicker("setDate",new Date());	  
		$("#fechadesdeeac").datepicker();
    $("#fechadesdeeac").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    });
    
    //$("#fecha_hastac").datepicker({dateFormat:"yy-mm-dd"}).datepicker("setDate",new Date());    
		$("#fechahastaeac").datepicker();		
    $("#fechahastaeac").datepicker('option', {
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    });
  });

	$(function() {
    $("#buscarClienteeac").autocomplete("scripts_index.php?clase=Orden&metodo=findCliente", {
      width: 300,
      selectFirst: false
    });

    $("#buscarClienteeac").result(function(event, data, formatted) {
      $("#niteac").val(data[1]);
    });
  });
</script>