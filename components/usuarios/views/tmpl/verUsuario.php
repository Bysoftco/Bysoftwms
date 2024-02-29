{COMODIN}
<div class="div_barraFija">
	<div id="titulo_ruta" style="width: 100%;text-align: center;">{titulo_accion}</div>
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
				<td width="30%" style="padding-left:10px;">Usuario</td>
				<td style="padding-left:10px;">
					{usuario}
				</td>
			</tr>
			<tr>
				<td style="padding-left:10px;">Perfil</td>
				<td style="padding-left:10px;">
					{perfil}
				</td>
			</tr>
			<tr>
				<td style="padding-left:10px;">Nombres</td>
				<td style="padding-left:10px;">
					{nombre_usuario}
				</td>
			</tr>
			<tr>
				<td style="padding-left:10px;">Apellidos</td>
				<td style="padding-left:10px;">
					{apellido_usuario}
				</td>
			</tr>
			<tr>
				<td style="padding-left:10px;">Correo Electr&oacute;nico</td>
				<td style="padding-left:10px;">
					{mail_usuario}
				</td>
			</tr>
			<tr>
				<td style="padding-left:10px;">Sede</td>
				<td style="padding-left:10px;">
					{sede}
				</td>
			</tr>
		</table>
	</div>
</fieldset>
<script>
	Nifty("div.div_barraFija","top transparent");
</script>