{COMODIN}
<div style="height: 30px;"></div>
<div align="center">
<div id="envioDatos" style="width: 700px;" align="left">
<link type="text/css" href="./integrado/cz_estilos/jquery.autocomplete.css" rel="stylesheet" />
<fieldset class="ui-widget ui-widget-content ui-corner-all">
  <legend class="ui-widget ui-widget-header ui-corner-all">
		Consulta C&oacute;digo de Serial en Base de Datos
	</legend>
	<div class="margenes">
  	Leer el C&oacute;digo del Serial a consultar en la base de datos<br /><br />
		<table cellpadding="0" cellspacing="0" id="tabla_seriales" style="width:100%;">
			<tr>
				<td>C&oacute;digo Serial:</td>
				<td style="text-align:center;">
					<input type="text" name="buscaSerial" id="buscaSerial" size="33" value="" />
				</td>
        <td style="text-align:center;">
        	<button type="button" class="submit" id="btnBuscar">Buscar</button>
			</tr>
		</table>
	</div>
  <table style="display:none;" align="center" width="100%"cellpadding="0" cellspacing="0" id="tabla_general">
		<tr><td align="center">Orden:</td><td id="norden" align="center"></td>
		<td align="center">Serial:</td><td align="center">
      <div id="cserial"></div></td><td align="center">
      <div class="borde_circular">
        <a class="tblank" href="" target="_blank">
          <img src="img/acciones/printer.gif" title="Imprimir Serial" width="25" height="25" border="0" />
        </a>
      </div> 
    </td></tr>
    </table></div></td></tr>
	</table>
</fieldset>
</div>
</div>
<input type="hidden" name="serial" id="serial" value="" />
<input type="hidden" name="orden" id="orden" value=""  />
<input type="hidden" name="doc_tte" id="doc_tte" value=""  />
<input type="hidden" name="ubicacion" id="ubicacion" value=""  />
<div style="height: 20px;"></div>
<script>
	$("#btnBuscar").button({
    text: true,
    icons: {
      primary: "ui-icon-arrowthick-1-n"
    }
  })
  .click(function() {
		$.ajax({
			url: 'index_blank.php?component=seriales&method=buscarSerial',
			data: {
				serial: $("#serial").attr("value")
			},
			async: true,
			type: "POST",
			success: function(msm) {
        if(msm=='No Existe') alert("El Código de Serial "+$("#serial").val()+" no existe en la BD. Por favor, escribir un nuevo Código.");
        else {
          var datos = msm.split("|");
          //Asigna info del código de consulta
          $("#serial").val(datos[0]);
          $("#orden").val(datos[1]);
          $("#doc_tte").val(datos[2]);
          $("#ubicacion").val(datos[3]);
				  document.getElementById("tabla_general").style.display = "";
				  $('#norden').html($("#orden").val());
				  var bserial = $("#doc_tte").val()+" :: "+$("#ubicacion").val()+"<br/><img src='components/seriales/views/tmpl/generar.php?serial="+$('#serial').attr('value')+"'>";
				  $("div/#cserial").html(bserial);          
        }
			}
		});
		return false;
 	});
  
  $(function() {
    $("#buscaSerial").autocomplete("./index_blank.php?component=seriales&method=findSerial", {
      width: 300,
      selectFirst: false
    });
    
    $("#buscaSerial").result(function(event, data, formatted) {
      $("#serial").val(data[0]);
      $("#orden").val(data[1]);
    });
  });

  $('a[class="tblank"]').click(function() {
    var param = "serial="+$("#serial").val()+"&orden="+$("#orden").val();
    param += "&doc_tte="+$("#doc_tte").val()+"&ubicacion="+$("#ubicacion").val();
    window.open("index_blank.php?component=seriales&method=imprimirSerial&"+param);
    return false;
  });	
</script>
