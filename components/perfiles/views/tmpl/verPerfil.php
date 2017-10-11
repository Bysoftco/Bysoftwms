{COMODIN}
<div class="div_barraFija">
	<div id="titulo_ruta">Vista Información Perfil</div>
</div><br />
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
	<tr>
		<th colspan="2" height="30px" align="center">DETALLE DE PERFIL</th>
	</tr>
	<tr>
		<th width="40%">Nombre Perfil</th>
		<td>{nombre_perfil}</td>
	</tr>
	<tr>
		<th>DescripciÃ³n</th>
		<td>{descripcion}</td>
	</tr>
	<tr>
		<th colspan="2" align="center">PERMISOS</th>
	</tr>
	<tr>
		<td colspan="2">{menuPermisos}</td>
	</tr>
</table>
<script>
Nifty("div.div_barraFija","top transparent");
</script>