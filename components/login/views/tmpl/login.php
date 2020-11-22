<link rel="stylesheet" href="integrado/cz_estilos/jquery.autocomplete.css" />
{COMODIN}
<div id="content">
  <div id="div_login"></div>
  <form name="envio_datos" id="envio_datos" method="post" action="index_blank.php">
    <div class="centraTabla">
      <div id="alerta_error"></div>
      <table class="table_login" border="0">
        <tr align="center" height="100px">
          <td colspan="2"><img src="img/logos/logo_bysoft.png" /></td>
        </tr>
        <tr>
          <td class="font_login">ID. Usuario : </td>
          <td><input class="login" type="text" name="usuario" id="usuario" /></td>
        </tr>
        <tr>
          <td class="font_login">Clave de Acceso : </td>
          <td><input class="login" type="password" name="clave" id="clave" /></td>
        </tr>
        <tr>
				  <td class="font_login">Sede : </td>
          <td>
            <input  type="text" name="sede_nombre" id="sede_nombre" class="login"/>
            <select class="login" name="sede_id" id="sede_id" >
              {select_sedes}
            </select>
          </td>
        </tr>
        <tr align="center" height="60px">
          <td colspan="2">
            <input type="button" class="button" onclick="validar_envio()" name="ingresar" id="ingresar" value="Ingresar" />
          </td>
        </tr>
        <tr align="center">
          <td colspan="2" class="copyright" valign="top">Copyright &copy; 2013 Bysoft (v2.0)</td>
        </tr>        
      </table>
    </div>
    <input type="hidden" name="component" id="component" value="login" />
    <input type="hidden" name="method" id="method" value="login_usuario" />
  </form>

  <form name="formAlertas" id="formAlertas" method="POST" action="">
    <input type="hidden" name="component" id="component" value="Alertas" />
    <input type="hidden" name="method" id="method" value="mostrarAlertas" />
  </form>
</div>
<script type='text/javascript' src='integrado/cz_javascript/lib/jquery.autocomplete.js'>
</script>
<script>
  function seleccionar(elemento) {
    var combo = document.forms["envio_datos"].sede_id;
    var cantidad = combo.length;

    for(i=0;i<cantidad;i++) {
      if(combo[i].value == elemento) {
        combo[i].selected = true;
      }   
    }
  }

  $(function() {
    $("#sede_nombre").autocomplete("index_blank.php?component=login&method=findSede", {
      width: 260,
      selectFirst: false
    });
    $("#sede_nombre").result(function(event, data, formatted) {    
      seleccionar(data[1]);                       
    });
  });

  function validar_envio() {
    $.ajax({
      url: $('#envio_datos').attr('action'),
      data: $('#envio_datos').serialize(),
      success: function(msm) {
        switch(msm) {
          case 'faltante':
            $('#alerta_error').html('* Debe Ingresar Usuario y clave para continuar.');
            $("#alerta_error").animate({width: "98%"},100);
            $("#alerta_error").animate({width: "100%"},100);
            break;
          case 'error':
            $('#alerta_error').html('* Datos Incorrectos, Por favor verifique.');
            $("#alerta_error").animate({width: "98%"},100);
            $("#alerta_error").animate({width: "100%"},100);
            break;
          case 'usractivo':
            $('#alerta_error').html('* Usuario Conectado. Este perfil no permite múltiples sesiones.');
            $("#alerta_error").animate({width: "98%"},100);
            $("#alerta_error").animate({width: "100%"},100);
            break;          
          case 'logueado':
            logEntrar();
            break;
        }
      }
    });
  }

  function logEntrar() {
    $.ajax({
      url: 'index_blank.php?component=logAplicacion&method=logMenu&accionLog=1',
      success: function(msm) {
        if(confirm("\xbfDesea ver el Reporte de Alertas?")) {
          //location.href="index.php?component=Alertas&method=mostrarAlertas";
          $("#formAlertas").submit();
        } else {
          location.href = "";
        }
      }
    });
  }
</script>