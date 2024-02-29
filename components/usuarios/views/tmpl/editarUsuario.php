{COMODIN}
<div class="div_barraFija">
	<div id="titulo_ruta" style="width: 100%;text-align: center;">{titulo_accion}</div>
</div><br />
<form name="envioDatos" id="envioDatos" action="javascript:enviarDatos()">
  <fieldset>
    <legend>Informaci&oacute;n de Usuario</legend>
    <div class="margenes">
      Los campos marcados con un asterisco (*) son obligatorios.<br /><br />
      <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
        <tr>
          <th colspan="2">DETALLE DE USUARIO</th>
        </tr>
        <tr>
          <td width="30%">Usuario *</td>
          <td>
            <input alt="<br />* Debe ingresar <b>Usuario</b> para poder continuar" class="{required:true}" type="text" name="usuario" id="usuario" value="{usuario}" />
          </td>
        </tr>
        <tr>
          <td>Perfil *</td>
          <td>
            <select alt="<br />* Debe seleccionar <b>Perfil</b> para poder continuar" class="{required:true}" name="perfil_id" id="perfil_id" >
              {select_perfil}
            </select>
          </td>
        </tr>
        <tr>
          <td>Nombres *</td>
          <td>
            <input alt="<br />* Debe ingresar <b>Nombres</b> para poder continuar" class="{required:true}" type="text" name="nombre_usuario" id="nombre_usuario" value="{nombre_usuario}" />
          </td>
        </tr>
        <tr>
          <td>Apellidos *</td>
          <td>
            <input alt="<br />* Debe ingresar <b>Apellidos</b> para poder continuar" class="{required:true}" type="text" name="apellido_usuario" id="apellido_usuario" value="{apellido_usuario}" />
          </td>
        </tr>
        <tr>
          <td>Correo Electr&oacute;nico *</td>
          <td>
            <input alt="<br />* Digite un <b>Correo Electr&oacute;nico</b> v&aacute;lido para poder continuar" class="{required:true, email:true}" type="text" name="mail_usuario" id="mail_usuario" value="{mail_usuario}" />
          </td>
        </tr>
        <tr>
          <td>Sede *</td>
          <td>
            <select alt="<br />* Debe seleccionar <b>Sede</b> para poder continuar" class="{required:true}" name="sede_id" id="sede_id" >
              {select_sedes}
            </select>
          </td>
        </tr>
      </table>
    </div>
  </fieldset>
	<br />
    {sedesUsuarios}
	<fieldset class="fieldset_rda" style="display:{edicion_clave};">
		<legend>
			<input type="checkbox" name="edClave" id="edClave" onclick="javascript:editarClave(this.checked)" />
			Editar Clave
		</legend>
		<div id="claveOculta" style="display: none;">
		<br /><br />
			<fieldset>
				<legend>
					Informaci&oacute;n de Clave
				</legend>
				<div class="margenes">
					<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
						<tr>
							<td width="30%">Clave Actual *</td>
							<td><input alt="<br />* Debe ingresar <b>Clave Actual</b> para poder continuar" type="password" id="claveActual" name="claveActual"  /></td>
						</tr>
						<tr>
							<td>Clave Nueva *</td>
							<td><input alt="<br />* La <b>Clave Nueva</b> debe tener entre 5 y 10 caracteres" type="password" id="claveNueva" name="claveNueva"  /></td>
						</tr>
						<tr>
							<td>Confirmar Clave Nueva *</td>
							<td><input type="password" alt="<br />* Los campos <b>Clave Nueva</b> y <b>Confirmar Clave Nueva</b> deben ser iguales" id="confClaveNueva" name="confClaveNueva"  /></td>
						</tr>
					</table>
				</div>
			</fieldset>
		</div>
	</fieldset><br /><br />
	<center>
		<input name="enviar" id="enviar" class="button small yellow2" type="submit" value="Enviar" />
	</center>
</form>

<div style="height: 20px;"></div>
<script>
  Nifty("div.div_barraFija","top transparent");

  $().ready(function() {
    $("#envioDatos").validate();
  });

  function editarClave(editar) {
    if(editar) {
      $('#claveOculta').css('display','block');
      $("#claveActual").addClass('required');
      $("#claveNueva").addClass('required');
      $("#claveNueva").addClass('{rangelength:[5, 10]}');
      $("#confClaveNueva").attr('equalTo', '#claveNueva');
    } else {
      $('#claveOculta').css('display','none');
      $('#claveActual').attr('value','');
      $('#claveNueva').attr('value','');
      $('#confClaveNueva').attr('value','');
      $('#claveActual').removeClass('required');
      $("#claveNueva").removeClass('required');
      $("#claveNueva").removeClass('rangelength');
      $("#confClaveNueva").attr('equalTo', '');
    }
  }

  function enviarDatos() {
    var editarClave = document.getElementById('edClave').checked;

    if(editarClave) {
      var claveAct = $('#claveActual').attr('value');
      $.ajax({
        url: 'index_blank.php?component=usuarios&method=validarClave',
        type: "POST",
        async: false,
        data: 'clave='+claveAct+'&id={id}',
        success: function(msm) {
          if(msm=='valido') {
            var usuario = strTrim($('#usuario').attr('value'));
            $('#usuario').attr('value', usuario); 
            if(confirm('\xbf Realmente desea almacenar estos datos para el usuario '+usuario+' ?')) {
              $.ajax({
                url: 'index_blank.php?component=usuarios&method=validarRepetido&usuario='+usuario+'&id={id}',
                type: "POST",
                async: false,
                success: function(msm) {
                  if(msm=='valido') {
                    $.ajax({
                      url: 'index_blank.php?component=usuarios&method=editar&id={id}',
                      type: "POST",
                      async: false,
                      data: $('#envioDatos').serialize(),
                      success: function(contenido) {
                        jQuery(document.body).overlayPlayground('close');void(0);
                        $('#componente_central').html(contenido);
                      }
                    });
                  } else {
                    alert('El usuario '+usuario+' ya se encuentra creado\nPor favor verifique.');
                  }
                }
              });
            }
          } else if(msm=='noValido') {
            alert('La clave actual no es correcta.\npor favor verifique o contacte al administrador del sistema.');
          } else {
            alert('error de consulta');
          }
        }
      });
    } else {
      var usuario = strTrim($('#usuario').attr('value'));
      var sede = $("#sede_id").attr('value');
      $('#usuario').attr('value', usuario); 
      if(confirm('\xbf Realmente desea almacenar estos datos para el usuario '+usuario+' ?')) {
        $.ajax({
          url: 'index_blank.php?component=usuarios&method=validarRepetido&usuario='+usuario+'&id={id}&sede='+sede,
          type: "POST",
          async: false,
          success: function(msm) {
            if(msm=='valido') {
              $.ajax({
                url: 'index_blank.php?component=usuarios&method=editar&id={id}',
                type: "POST",
                async: false,
                data: $('#envioDatos').serialize(),
                success: function(contenido) {
                  jQuery(document.body).overlayPlayground('close');void(0);
                  $('#componente_central').html(contenido);
                }
              });
            } else {
              alert('El usuario '+usuario+' ya se encuentra creado\nPor favor verifique.');
            }
          }
        });
      }
    }
  }
</script>