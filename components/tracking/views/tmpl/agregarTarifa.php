{COMODIN}
Los campos marcados con un asterisco (*) son obligatorios.<br /><br />
<form name="enviar_datos" id="enviar_datos" action="javascript:enviarDatos()" method="post" >
  <table width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr align="center">
      <th colspan="5">Información Tarifa</th>
    </tr>
    <tr>
      <td>Nombre Tarifa *</td>
      <td><input type="text" name="nombre_tarifa" id="nombre_tarifa" class="required" value="{nombre_tarifa}" /></td>
      <td>General <input type="checkbox" name="tarifa_general" id="tarifa_general" {checked_general} /></td>
      <td>Agregar Servicio</td>
      <td align="center" width="5%"><img style="cursor: pointer;" src="img/acciones/agregar.png" title="Agregar Servicio" width="15" height="15" border="0" onclick="javascript:agregarServicios()" /></td>
    </tr>
  </table><br />

  <!-- BEGIN ROW -->
  {servicio_atado}
  <!-- END ROW -->
  <div id="agregarServicio"></div>
  <br />

  <input type="hidden" name="cliente" id="cliente" value="{cliente}" />
  <center><input name="enviar" id="enviar" class="button small yellow2" type="submit" value="Enviar" /></center>
</form>

<br /><br />

<script>
  var numeral = '{numeral}';

  $().ready(function() {
    $("#enviar_datos").validate();

	$('.eliminarServ').css('display', 'block');
	if(document.getElementsByName('servicio[]').length<2){
      $('.eliminarServ').css('display', 'none');
	}
  });

  function agregarServicios() {
    $.ajax({
      url: 'index_blank.php?component=clientes&method=agregarServicio',
      data: 'numeral='+numeral,
      async: true,
      type: "POST",
      success: function(msm) {
        numeral++;
        $('#agregarServicio').html($('#agregarServicio').html()+msm);
        $('.eliminarServ').css('display', 'block');
        if(document.getElementsByName('servicio[]').length<2) {
          $('.eliminarServ').css('display', 'none');
        }
      }
    });
  }

  function enviarDatos() {
    $.ajax({
      url: 'index_blank.php?component=clientes&method=nuevaTarifa',
      data: $('#enviar_datos').serialize(),
      type: "POST",
      success: function(msm) {
        jQuery(document.body).overlayPlayground('close');void(0);
        $("#tarifasCliente").html(msm);
      }
    });
  }

  function eliminarServicio(codigo) {
    $('#eliminar_'+codigo).html('');
	$('.eliminarServ').css('display', 'block');
	if(document.getElementsByName('servicio[]').length<2) {
      $('.eliminarServ').css('display', 'none');
	}
  }
</script>