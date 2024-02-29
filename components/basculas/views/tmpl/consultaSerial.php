{COMODIN}
<div style="height: 30px;"></div>
<div align="center">
<div id="envioDatos" style="width: 500px;" align="left">
<fieldset class="ui-widget ui-widget-content ui-corner-all">
  <legend class="ui-widget ui-widget-header ui-corner-all">
		Consulta C&oacute;digo de Serial en Base de Datos
	</legend>
	<div class="margenes">
  	Leer el C&oacute;digo del Serial a consultar en la base de datos<br /><br />
		<table cellpadding="0" cellspacing="0" id="tabla_seriales">
			<tr>
				<td style="width:30%; height:30px;" width="left">C&oacute;digo Serial:</td>
				<td style="width:43%; text-align:left;">
					<input type="text" name="serial" id="serial" value="" />
				</td>
        <td style="width:27%; text-align:left;">
        	<button type="button" class="submit" id="btnBuscar">Buscar</button>
			</tr>
		</table>
	</div>
  <table style="display:none;" align="center" width="100%"cellpadding="0" cellspacing="0" id="tabla_general">
		<tr><td align="center">Orden:</td><td id="norden" align="center"></td>
		<td align="center">Serial:</td><td align="center">
    <div id="cserial"></div></td><td><div id="contenedor_opciones" align="left">
    <table border="0">
      <tr><td align="center"><div class="borde_circular" onclick="javascript:imprimirSerial()">
      <a href="javascript:void(0)"><img src="img/acciones/borrarseriales.png" title="Eliminar Todos" width="25" height="25" border="0" />
      </a></div></td></tr>
    </table></div></td></tr>
	</table>
</fieldset>
</div>
</div>
<input type="hidden" name="numorden" id="numorden" value="{numorden}"  />
<input type="hidden" name="codreferencia" id="codreferencia" value="{codreferencia}" />
<input type="hidden" name="numordenfull" id="numordenfull" value="{numordenfull}" />
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
			url: 'index_blank.php?component=seriales&method=buscarDB',
			data: {
				serial: $("#serial").attr("value")
			},
			async: true,
			type: "POST",
			success: function(msm) {
				document.getElementById("tabla_general").style.display = "";
				$('#norden').html(msm);
				var bserial = "<img src='components/seriales/views/tmpl/generar.php?serial="+$('#serial').attr('value')+"'>";
				$("div/#cserial").html(bserial);
			}
		});
		return false;
 	});	
</script>
