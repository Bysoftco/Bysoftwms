{COMODIN}
<div style="height: 5px;"></div>
<div class="div_barraFija">
	<div id="titulo_ruta">{titulo_accion}</div>
</div><br />

<form name="envioDatos" id="envioDatos" action="javascript:enviarDatos()">
  <fieldset>
    <legend>
      Informaci&oacute;n de Kit
    </legend>
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
      <tr>
        <th colspan="2">Armado Kits</th>
      </tr>
      <tr>
        <td height="27px" class="tituloForm">Nombre Cliente</td>
        <td>{nombre_cliente}</td>
      </tr>
      <tr>
        <td height="27px" class="tituloForm">Documento Cliente</td>
        <td>
          {documento_cliente}
          <input type="hidden" name="cliente" id="cliente" value="{documento_cliente}" />
        </td>
      </tr>
      <tr>
        <td class="tituloForm">C&oacute;digo Kit</td>
        <td>
          {codigo_kit}
          <input style="display: {ocultar_codigo}" type="text" name="codigo_kit" id="codigo_kit" class="inputTabla required" value="{codigo_kit}" />
          <input type="hidden" name="idKit" id="idKit" value="{id_kit}" />
        </td>
      </tr>
      <tr>
        <td class="tituloForm">Nombre Kit</td>
        <td><input type="text" name="nombre_kit" class="inputTabla required" value="{nombre_kit}" /></td>
      </tr>
      <tr>
        <td class="tituloForm">U. Comercial</td>
        <td>
          <select name="u_comercial" style="width:255px" >
            {select_u_comercial}
          </select>
        </td>
      </tr>
      <tr>
        <td class="tituloForm">Presentaci&oacute;n Venta</td>
        <td>
          <select name="p_venta" style="width:255px" >
            {select_p_venta}
          </select>
        </td>
      </tr>
      <tr>
        <td class="tituloForm">Descripci&oacute;n</td>
        <td><textarea class="textarea_login required" name="descripcion">{descripcion_kit}</textarea></td>
      </tr>
      <tr>
        <td class="tituloForm" colspan="2">
          <table width="100%" align="center">
            <tr>
              <td>Vence <input type="checkbox" name="vence_referencia" {vence_referencia} /></td>
              <td>Serial <input type="checkbox" name="serial_referencia" {serial_referencia} /></td>
              <td>Min Stock <input type="checkbox" name="minimo_stock" {minimo_stock} /></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <th colspan="2">Composici&oacute;n Kits</th>
      </tr>
      <tr>
        <td colspan="2">
          <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
            <tr>
              <th>C&oacute;digo de referencia</th>
              <th>Descripci&oacute;n de referencia</th>
              <th>Piezas en el Kit</th>
            </tr>
            <!-- BEGIN ROW -->
            <tr>
              <td>{codigo_referencia}</td>
              <td>{descripcion_referencia}</td>
              <td align="center">
                <input type="text" value="{cantidad_ref}" name="cantidad[{codigo_referencia}]" id="cantidad{codigo_referencia}" class="digits cantidadPiezas" style="width:50px" /> Unidades
              </td>
            </tr>
            <!-- END ROW -->
          </table><br /><br />
          <div style="text-align: center; padding-top: 10px;">
            <input type="submit" name="enviar" value="Enviar" class="button small yellow2" /><br /><br />
          </div>                     
        </td>
      </tr>
    </table>
  </fieldset>
  <input type="hidden" value="{id_referencia}" name="id_referencia" />
</form>
<script>
  $().ready(function() {
    $("#envioDatos").validate();
  });

  function enviarDatos() {
    var codigo = $("#codigo_kit").attr('value');
    var cliente = $("#doc_cliente").attr('value');
    var nombreCliente = '{nombre_cliente}';
    
    var checkboxes = $(".cantidadPiezas");
    var cont = 0;
    for(var x=0; x < checkboxes.length; x++) {
      if(!empty(checkboxes[x].value)) {
        cont = cont + 1;
        break;
      }
    }
    if(cont>0) {
      var codigoValido = 'valido';
      if(empty($("#idKit").val())) {
        codigoValido = validarCodigo(codigo, cliente);
      }
      if(codigoValido=='valido'){
        $.ajax({
          url: "index_blank.php?component=Kits&method=guardarKit",
          type: "POST",
          data: $('#envioDatos').serialize(),
          success: function (msm){
            jQuery(document.body).overlayPlayground('close');void(0);
            $('#componente_central').html(msm);
          }
        });
      } else if(codigoValido=='invalido') {
        alert('Ya existe una referencia o un Kit con el código '+codigo+' para el cliente '+nombreCliente+'\nPor favor verifique.')
      } else {
        alert('Error al verificar código Kit');
      }
    } else {
      alert("Debe ingresar cantidad en las referencias que compondrán el Kit.")
    }
  }

  function validarCodigo(codigo, cliente) {
    var retorno  ='';
    $.ajax({
      url: 'index_blank.php?component=Kits&method=validarCodigo',
      type: "POST",
      async: false,
      data: 'codigo_kit='+codigo+'&cod_cliente='+cliente,
      success: function (contenido) {
        retorno = contenido;
      }
    });
    return retorno;
  }
</script>