{COMODIN}
<table width="100%">
	<tr>
		<td align="center" class="titulo_rojo">{titulo}</td>
	</tr>
	<tr>
		<td align="center">Seleccione la fecha que desea consultar</td>
	</tr>
	<tr>
		<td align="center"><div id="datepicker"></div></td>
	</tr>
</table>
<input type="hidden" id="fecha_mostrar" />

<script>
	$('#datepicker').datepicker({
		inline: false,
		dateFormat: 'yy-mm-dd',
		monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		dayNamesMin: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
		dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
		altField: "#fecha_mostrar",
		altFormat: "yy-mm-dd",
		onSelect: function(textoFecha, objDatepicker){
			var fecha_mostrar=$('#fecha_mostrar').attr('value');
			$('#ejemploid').attr('value', fecha_mostrar);
			alert(fecha_mostrar);
		}
	});
</script>