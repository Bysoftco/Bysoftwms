{COMODIN}
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general" >
  <tr>
    <th colspan="4">Datos del Cliente</th>
  </tr>
  <tr>
    <td class="tituloForm" width="20%">Tipo de Identificaci&oacute;n</td>
    <td width="30%">{tipo_identificacion}</td>
    <td class="tituloForm">Tipo Cliente</td>
    <td>{tipo_cliente}</td>
  </tr>
  <tr>
    <td class="tituloForm">N&uacute;mero de Identificaci&oacute;n</td>
    <td>{numero_identificacion}</td>
    <td class="tituloForm">Autorertenedor</td>
    <td><input type="checkbox" name="autoretenedor" id="autoretenedor" {seleccionado} /></td>
  </tr>
  <tr>
    <td class="tituloForm">D&iacute;gito de verificaci&oacute;n</td>
    <td>{digito_verificacion}</td>
    <td class="tituloForm" width="20%">R&eacute;gimen</td>
    <td width="30%">{regimen}</td>
  </tr>
  <tr>
    <td class="tituloForm">Raz&oacute;n Social</td>
    <td>{razon_social}</td>
    <input type="hidden" name="razon_social" id="razon_social" value="{razon_social}" />
    <td class="tituloForm">Actividad Econ&oacute;mica</td>
    <td>{actividad_economica}</td>
  </tr>
  <tr>
    <td colspan="4"></td>
  </tr>
  <tr>
    <th colspan="4">Informaci&oacute;n de contacto del cliente</th>
  </tr>
  <tr>
    <td class="tituloForm">Direcci&oacute;n</td>
    <td>{direccion}</td>
    <td class="tituloForm">Celular</td>
    <td>{celular}</td>
  </tr>
  <tr>
    <td class="tituloForm">Tel&eacute;fono(s) fijo(s)</td>
    <td>{telefonos_fijos}</td>
    <td class="tituloForm">TeleFax</td>
    <td>{telefax}</td>
  </tr>
  <tr>
    <td class="tituloForm">P&aacute;gina Web</td>
    <td>{pagina_web}</td>
    <td class="tituloForm">Correo Electr&oacute;nico</td>
    <td>{correo_electronico}</td>
  </tr>
  <tr>
    <td colspan="4"></td>
  </tr>
  <tr>
    <th colspan="4">Par&aacute;metros Adicionales</th>
  </tr>
  <tr>
    <td class="tituloForm">Tipo Facturaci&oacute;n</td>
    <td>{tipo_facturacion}</td>
    <td class="tituloForm">D&iacute;as para pago</td>
    <td>{dias_pago}</td>
  </tr>
  <tr>
    <td class="tituloForm">Periodicidad (en d&iacute;as)</td>
    <td>{periodicidad}</td>
    <td class="tituloForm">D&iacute;as de Gracia</td>
    <td>{dias_gracia}</td>
  </tr>
  <tr>
    <td class="tituloForm">Circular 170</td>
    <td><input type="checkbox" name="cir170" id="cir170" {seleccionado170} /></td>
    <td class="tituloForm">Comercial</td>
    <td>{comercial}</td>
  </tr>
</table>

<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");
  $('.noSeleccion').css('display', 'none');

  function listarClientes() {
	$.ajax({
      url: 'index_blank.php?component=clientes&method=listadoClientes',
      async: true,
      type: "POST",
      success: function(msm) {
        $('#componente_central').html(msm);
      }
	});
  }
</script>