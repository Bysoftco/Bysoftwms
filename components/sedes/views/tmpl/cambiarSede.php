{COMODIN}
<p><fieldset class="ui-widget ui-widget-content ui-corner-all">
	<div class="margenes">
  	Seleccione la nueva Sede<br /><br />
		<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_seriales">
			<tr>
				<th colspan="2">SEDES DISPONIBLES</th>
			</tr>
			<tr>
				<td style="width:45%">Nueva Sede:</td>
				<td>
					<select name="idsede" id="idsede" onchange="javascript:cambiaSede();">
          	{select_sede}
					</select>
				</td>
      </tr>
		</table>
	</div>
</fieldset></p>
<script>
  function cambiaSede() {
    $.ajax({
      url: 'index_blank.php?component=sedes&method=cambiaSede',
      type: "POST",
      async: false,
      data: {
        sede: $('#idsede').attr('value')
      },
      success: function(msm) {
        if(msm == 'cambiosede') {
          cambioSede();
        }
      }
    });
  }
  
  function cambioSede() {
    $.ajax({
      url: 'index_blank.php?component=template&method=armar_header',
      success: function(msm) {
        jQuery(document.body).overlayPlayground('close');void(0);
        $('#componente_central').html(msm);
        document.location.href = document.location.href; // Recarga la página sin pedir confirmación
      }
    });
  }
</script>