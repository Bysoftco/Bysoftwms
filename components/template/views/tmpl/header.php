{COMODIN}
<div id="header">
  <div class="contenedorHeader">
    <div class="logo">
      <img src="img/logos/logo_bysoft.png" width="300px" />
    </div>
    <div class="infoUser">{NOMBRE_USUARIO}<br />
      <div class="popupscambiarSede">
        <a href="" title="Cambiar Sede"><b>Sede: </b></a>
        <font color="red"><b>{NOMBRE_SEDE}</b></font>
      </div>
      <a href="javascript:salir()"><strong>Salir del Sistema</strong></a>
    </div>
  </div>
  <div class="contenedorMenu" id="menu">
    <div class="menu">
      {MENU}
    </div>
  </div>
  <div id="conversor" title="Conversor de Divisas" style="display:none;">
    <iframe style="display:block;margin: 0 auto;background:#ffffff;" src="http://dolar.wilkinsonpc.com.co/widgets/oanda-convertidor-divisas-300x250-pub.php" width="300" height="175" align="middle" frameborder="0" scrolling="no">
    </iframe>
  </div>
</div>
<input type="hidden" name="usuario_login" id="usuario_login" value="{USUARIO}"/>
<script>
  // Utilidad de Conversión de Divisas
  var ctrlPressed = false;
  var teclaCtrl = 17, teclaI = 73;

  $(document).keydown(function(e) {
    if(e.keyCode == teclaCtrl)
      ctrlPressed = true;

    if(ctrlPressed && (e.keyCode == teclaI)) {
      $( "#conversor" ).dialog({
        autoOpen: true,
        resizable: false,
        height: 225,
        width: 350,
        modal: true,
      });
    }
  });

  $(document).keyup(function(e) {
    if(e.keyCode == teclaCtrl)
      ctrlPressed = false;
  });

  function salir() {
    if(confirm("\xbf Realmente desea abandonar la sesi\xf3n ?")) {
      $.ajax({
        url: 'index_blank.php?component=login&method=cerrar_sesion',
        success: function(msm) {
          location.href = "";
        }
      });
    }
  }

  $('.popupscambiarSede a').wowwindow({
		draggable: true,
		width: 550,
		height: 150,
		overlay: {
			clickToClose: false,
			background: '#000000'
		},
		onclose: function() {
			$('.formError').remove();
		},
		before: function() {
			$.ajax({
				url: 'index_blank.php?component=sedes&method=cambiarSede',
        data: {
          usuario_login: $("#usuario_login").val()
        },
				async: true,
				type: "POST",
				success: function(msm) {
					$('#wowwindow-inner').html(msm);
				}
			});
		}
	});  
</script>