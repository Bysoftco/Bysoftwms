{COMODIN}
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
  <tr>
    <th colspan="4">Listado de Tarifas por Cliente</th>
  </tr>
</table><br />

<!-- BEGIN ROW  -->
<table width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
  <tr>
    <td class="tituloForm">
      <input type="radio" name="seleccion" onclick="javascript:seleccionado('{id_tarifa}')" />
      <span onclick="javascript:verServicios('servicio_{contador}')" style="cursor: pointer;" >
	    Nombre Tarifa: {nombre_tarifa} <strong>{general}</strong>
      </span>
    </td>
  </tr>
</table>
<div id="servicio_{contador}" style="display: none;">
  <table width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr>
      <th>Servicio</th>
      <th>Base</th>
      <th>Valor M&iacute;nimo</th>
      <th>Tope</th>
      <th>Valor</th>
      <th>Adicional</th>
      <th>Dias</th>
      <th>Vigencia</th>
    </tr>
    <!-- BEGIN ROW2  -->
    <tr>
      <td>{servicio}</td>
      <td width="10%">{base}</td>
      <td width="10%" align="right">{valor_minimo}</td>
      <td width="10%" align="right">{tope}</td>
      <td width="10%" align="right">{valor}</td>
      <td width="10%" align="right">{adicional}</td>
      <td width="10%" align="right">{dias}</td>
      <td width="10%" align="right">{vigencia}</td>
    </tr>
    <!-- END ROW2  -->
  </table><br /><br />
</div>
<!-- END ROW  -->

<input type="hidden" name="noSeleccionado" id="noSeleccionado" />
<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");
  $('.noSeleccion').css('display', 'none');

  function verServicios(id_servicios) {
	var valor = $('#'+id_servicios).css('display');
	if(valor=='none') {
      $('#'+id_servicios).css('display','block');
	} else {
      $('#'+id_servicios).css('display','none');
	}
  }

  function seleccionadoT(codTarifa) {
	$('#noSeleccionado').attr('value', codTarifa);
	$('.noSeleccionT').css('display', 'block');
  }
</script>