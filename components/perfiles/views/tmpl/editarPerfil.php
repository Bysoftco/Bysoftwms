{COMODIN}
<div class="div_barraFija">
	<div id="titulo_ruta" style="width: 100%;text-align: center;">{titulo_accion}</div>
</div><br /><br />
<span>
	Los campos marcados con un asterisco (*) son obligatorios.
</span><br /><br /> 
<form name="envioDatos" id="envioDatos" action="javascript:EnviarFormulario()">
	<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
		<tr>
			<th colspan="2">DETALLE DE PERFIL</th>
		</tr>
		<tr>
			<td>Nombre Perfil *</td>
			<td>
				<input alt="<br />* Debe ingresar <b>Nombre Perfil</b> para poder continuar" class="login estiloPerfiles {required:true}" type="text" name="nombre" id="nombre" value="{nombre_perfil}" />
			</td>
		</tr>
		<tr>
			<td>Descripci&oacute;n *</td>
			<td>
				<textarea alt="<br />* Debe ingresar <b>Descripci&oacute;n</b> para poder continuar" class="textarea_login estiloPerfiles {required:true}" name="descripcion" id="descripcion">{descripcion}</textarea>
			</td>
		</tr>
		<tr>
			<th colspan="2">PERMISOS</th>
		</tr>
		<tr>
			<td colspan="2">
				{menuPermisos}
			</td>
		</tr>
	</table><br />
	<center><input name="enviar" id="enviar" type="submit" value="Enviar" /></center>
	<input type="hidden" name="id" id="id" value="{id}">
</form>

<script>
	Nifty("div.div_barraFija","top transparent");

	$().ready(function(){
		$("#envioDatos").validate();
	});

	function EnviarFormulario() {
	  var checkboxes = document.getElementsByName("permisos[]");
	  
	  var cont = 0;
	  for (var x=0; x < checkboxes.length; x++) {
			if (checkboxes[x].checked) {
			 cont = cont + 1;
			 break;
			}
		}
		if(cont>0) {
			$.ajax({
				url:'index_blank.php?component=perfiles&method=editarPerfil',
				type: "POST",
				async:false,
				data:$('#envioDatos').serialize(),
				success: function (msm) {
					jQuery(document.body).overlayPlayground('close');void(0);
					$('#componente_central').html(msm);
				}
			});
		} else {
			alert('Debe seleccionar al menos un permiso para continuar.')
		}
	}

	function regresar() {
		$.ajax({
			url:'index_blank.php?component=perfiles&method=listadoPerfiles',
			type: "POST",
			success: function (msm) {
				$('#componente_central').html(msm);
				$('.formError').remove();
			}
		});
	}
</script>