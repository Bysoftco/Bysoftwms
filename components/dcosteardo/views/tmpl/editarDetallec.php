<style>
  #infodetallec { font-family: Arial,Helvetica,sans-serif; font-size: 11px; }  	
  label { display: inline-block; width: 140px; margin-left: 5px; }
  legend { padding: 0.5em; }
  fieldset fieldset label { display: block; }
  .ui-datepicker-trigger { position:relative; top: 5px; right: 1px; height:22px; } /* Configuración tamaño imagen del DatePicker */
</style>
{COMODIN}
<link rel="stylesheet" type="text/css" href="./integrado/cz_estilos/jquery.autocomplete.css" />
<div id="infodetallec">
  <form name="frmDetallec" id="frmDetallec" method="post" action="">
    <fieldset class="ui-widget ui-widget-content ui-corner-all">
      <legend class="ui-widget ui-widget-header ui-corner-all">
        <div id="detallecosteardo">Costos del DO: {do_asignado}</div>
      </legend>
      <p>
        <label>N&uacute;mero de Detalle:</label>
        <input type="text" name="numdetalle" id="numdetalle" value="{numdetalle}" style="text-align:right;" readonly="" />
      </p>
      <p>
        <label>Nombre del Servicio:</label>
        <input type="text" name="buscarServicio" id="buscarServicio" size="50" value="{nomservicio}" />
      </p>
      <p>
        <label>Fecha del Servicio:</label>
        <input type="text" name="fecha" id="fecha" placeholder="aaaa-mm-dd" value="{fecha}" />
      </p>
      <p>
        <label>Valor del Servicio:</label>
        <input type="text" name="valordetalle" id="valordetalle" style="text-align:right;" value="{valordetalle}" />
      </p>
    </fieldset><br />
    <div align="center"><button type="button" class="submit" id="btnRegistrar">Registrar</button></div>
    <input type="hidden" name="ingreso" id="ingreso" value="0" />
    <input type="hidden" name="gasto" id="gasto" value="0" />
    <input type="hidden" name="naturaleza" id="naturaleza" value="{naturaleza}" />
    <input type="hidden" name="fecha" id="fecha" value="{fecha}" />
    <input type="hidden" name="valordetalle" id="valordetalle" value="{valordetalle}" />
    <input type="hidden" name="do_asignado" id="do_asignado" value="{do_asignado}" />
    <input type="hidden" name="doc_tte" id="doc_tte" value="{doc_tte}" />
    <input type="hidden" name="codservicio" id="codservicio" value="{codservicio}" />
    <input type="hidden" name="totalingreso" id="totalingreso" value="{totalingreso}" />
    <input type="hidden" name="totalgasto" id="totalgasto" value="{totalgasto}" />
    <input type="hidden" name="actualiza" id="actualiza" value="{actualiza}" />    
  </form>
</div>
<script>
  // Limpia los campos del formulario
  $("#frmDetallec")[0].reset();
  
	$(function() {
    $("#buscarServicio").autocomplete("./index_blank.php?component=dcosteardo&method=findServicio", {
      width: 260,
      selectFirst: false
    });

    $("#buscarServicio").result(function(event, data, formatted) {
      $("#naturaleza").val(data[1]);
      $("#codservicio").val(data[2]);
      $("#valordetalle").val(data[3]);
    });
    
    $("#fecha").datepicker({
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      changeMonth: true,
      showOn: 'both',
      buttonImage: 'integrado/imagenes/calendar.png',
      buttonImageOnly: true
    }).datepicker("setDate",$("#fecha").val());

    $("#btnRegistrar")
      .button({
        icons: {
          primary: "ui-icon-disk"
        }
      })
      .click(function() {
        if($("#codservicio").val()!=0) {
          //Valida la naturaleza del servicio
          if($("#naturaleza").val()=='C') {
            $("#gasto").val($("#valordetalle").val());
          } else {
            $("#ingreso").val($("#valordetalle").val());
          }
          if($("#valordetalle").val()>0) {
            $.ajax({
              url: 'index_blank.php?component=dcosteardo&method=registrar',
              type: "POST",
              async:false,
              data:$('#frmDetallec').serialize(),
              success: function (contenido) {
                jQuery(document.body).overlayPlayground('close');void(0);
                $('#vdetallec').html(contenido);
              }
            });          
          } else alert("Error, el Valor del Servicio debe ser mayor a 0.");          
        } else alert("Error, debe especificar el Nombre del Servicio.");
			});
  });
</script>