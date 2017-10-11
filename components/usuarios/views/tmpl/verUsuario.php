{COMODIN}
<div style="height: 10px;"></div>
<div class="div_barraFija">
	<div id="titulo_ruta">{titulo_accion}</div>
</div><br />

<fieldset>
	<legend>
		Informaci&oacute;n de Usuario
	</legend>
	<div class="margenes">
		<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
			<tr>
				<th colspan="2">DETALLE DE USUARIO</th>
			</tr>
			<tr>
				<td width="30%">Usuario </td>
				<td>
					{usuario}
				</td>
			</tr>
			<tr>
				<td>Perfil </td>
				<td>
					{perfil}
				</td>
			</tr>
			<tr>
				<td>Nombres </td>
				<td>
					{nombre_usuario}
				</td>
			</tr>
			<tr>
				<td>Apellidos </td>
				<td>
					{apellido_usuario}
				</td>
			</tr>
			<tr>
				<td>Correo Electr&oacute;nico </td>
				<td>
					{mail_usuario}
				</td>
			</tr>
			<tr>
				<td>Sede </td>
				<td>
					{sede}
				</td>
			</tr>
		</table>
	</div>
</fieldset>
	<br />
<div style="height: 20px;"></div>
<script>
Nifty("div.div_barraFija","top transparent");

</script>