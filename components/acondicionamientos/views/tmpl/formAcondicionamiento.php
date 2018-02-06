{COMODIN}
<link rel="stylesheet" type="text/css" href="integrado/cz_estilos/jquery.autocomplete.css" />
<form style="padding-top: 5px;" name="form_acondicionamiento" id="form_acondicionamiento" method="post" action="javascript:enviarAcondicionamiento()" >
  <fieldset>
    <legend>
      Informaci&oacute;n de Acondicionamiento
    </legend>
    <input type="hidden" name="doc_cliente" id="doc_cliente" value="{documento_cliente}" />
    <input type="hidden" name="doc_tte" id="doc_tte" value="{doc_tte}" />
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
      <tr>
        <th colspan="4">Datos del Cliente</th>
      </tr>
      <tr>
        <td style="width: 20%; height: 27px; padding-left:5px;" class="tituloForm">Nombre Cliente:</td>
        <td style="padding-left: 5px;">{nombre_cliente}</td>
        <td style="width: 20%; height: 27px; padding-left:5px;" class="tituloForm">Documento Cliente:</td>
        <td style="padding-left: 5px;">{documento_cliente}</td>        
      </tr>
      <tr>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">Tipo de Mercancía:</td>
        <td style="padding-left: 5px;">
          {nombre_tipo_mercancia}
          <input type="hidden" name="tipo_mercancia" id="tipo_mercancia" value="{tipo_mercancia}" />
		  <input type="text" name="tipo_retiro" id="tipo_mercancia" value="1" />
        </td>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">Pedido:</td>
        <td style="padding-left: 5px;">
          <input type="text" name="pedido" id="pedido" value="1" class="required" />
        </td>
      </tr>
      <tr>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">FMM:</td>
        <td>
          <input type="text" name="fmm" id="fmm" value="98001" class="required" />
        </td>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">Placa:</td>
        <td style="padding-left: 5px;">
          <input type="text" name="placa" id="placa" value="{placa}" class="required" size="15" />
          <input type="hidden" name="id_camion" id="id_camion" value="{id_camion}" />
        </td>
      </tr>
      <tr>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">Conductor:</td>
        <td>
          <input type="text" name="conductor" id="conductor" value="{conductor_nombre}" readonly="readonly" />
        </td>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">Fecha:</td>
        <td style="padding-left: 5px;">{fecha}</td>
        <input type="hidden" name="fecha" id="fecha" value="{fecha}" />
      </tr>
      <tr>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">Ciudad:</td>
        <td>
          <input type="text" name="ciudad" id="ciudad" value="{nombre_ciudad}" size="20" />
          <input type="hidden" name="codigo_ciudad" id="codigo_ciudad" value="{codigo_ciudad}" />
        </td>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">Destinatario:</td>
        <td style="padding-left: 5px;">
          <input type="text" name="destinatario" id="destinatario" value="{destinatario}" class="required" size="50" />
        </td>
      </tr>
      <tr>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">Direcci&oacute;n:</td>
        <td colspan="3">
          <input type="text" style="" name="direccion" id="direccion" value="{direccion}" size="50" />
        </td>
      </tr>
    </table>
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
      <tr>
        <th>Código</th>
        <th>Referencia</th>
        <th>Piezas Disponible</th>
        <th>Piezas a Acondicionar</th>
      </tr>
      <!-- Visualiza Información de la Primera Referencia Seleccionada -->
      <tr>
        <td>
          {codigo_referencia}
          <input type="hidden" value="{cod_referencia}" class="cantidadPiezas" name="cod_referencia"/>
        </td>
        <td>{nombre_referencia}</td>
        <td style="text-align: right">
          {disponible} Piezas
          <input type="hidden" name="disponible[{cod_referencia}]" id="disponible{cod_referencia}" value="{disponible}" />
        </td>
        <td style="text-align: right">
          <input style="width:50px" class="required number" name="cantidad_retirar[{cod_referencia}]" id="cantidad_retirar{cod_referencia}" /> Piezas
        </td>
      </tr>
    </table><br />
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
      <tr>
        <td>
          Observaciones<br/><textarea name="observaciones" cols="90" rows="3"></textarea>
        </td>
      </tr>
    </table>
  </fieldset><br />
  <center>
    <input name="enviarAcondicionar" id="enviarAcondicionar" class="button small yellow2" type="submit" value="Enviar" />
	</center>
</form>
<script>
  $().ready(function() {
    $("#form_acondicionamiento").validate();
  });

  function enviarAcondicionamiento() {
    var recorrer = $(".cantidadPiezas");
    var cont = true;
    for(var x=0; x < recorrer.length; x++) {
      if(empty($("#cantidad_retirar"+recorrer[x].value).attr("value"))|| $("#cantidad_retirar"+recorrer[x].value).attr("value")==0) {
        alert("Debe ingresar valores superiores a cero para acondicionar la Mercancía.");
        cont = false;
        break;
      }
      if(parseFloat(($("#cantidad_retirar"+recorrer[x].value).attr("value"))) > parseFloat($("#disponible"+recorrer[x].value).attr("value"))) {
        alert("La cantidad a Retirar no puede exceder la Cantidad Disponible");
        cont = false;
        break;
      }
    }
    if(cont) {
      $.ajax({
        url: 'index_blank.php?component=acondicionamientos&method=generarAcondicionamiento',
        async: true,
        type: "POST",
        data:$('#form_acondicionamiento').serialize(),
        success: function(msm) {
          jQuery(document.body).overlayPlayground('close');void(0);
          $('#componente_central').html(msm);
        }
      });
    }
  }
  
  $("#placa").autocomplete("scripts_index.php?clase=Orden&metodo=findConductor", {
    width: 260,
    selectFirst: false
  });
    
  $("#placa").result(function(event, data, formatted) {
    $("#placa").val(data[1]);
    $("#conductor").val(data[3]);
    $("#id_camion").val(data[4]);
  });
  
  $("#ciudad").autocomplete("scripts_index.php?clase=Levante&metodo=findCiudad", {
    width: 260,
    selectFirst: false
  });
    
  $("#ciudad").result(function(event, data, formatted) {
    $("#ciudad").val(data[2]);
    $("#codigo_ciudad").val(data[1]);
  });
</script>