{COMODIN}
<style>
  td.ajustarancho {
    width: 1px;
    white-space: nowrap;
}
</style>
<link rel="stylesheet" type="text/css" href="integrado/cz_estilos/jquery.autocomplete.css" />
<form style="padding-top: 5px;" name="form_alistamiento" id="form_alistamiento" method="post" action="javascript:enviarAlistamiento()" >
  <fieldset>
    <legend>
      Informaci&oacute;n de Alistamiento
    </legend>
    <input type="hidden" name="doc_cliente" id="doc_cliente" value="{documento_cliente}" />
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
      <tr>
        <th colspan="6">Datos del Cliente</th>
      </tr>
      <tr>
        <td style="width: 20%; height: 27px; padding-left:5px;" class="tituloForm ajustarancho">Nombre Cliente:</td>
        <td style="padding-left: 5px;">{nombre_cliente}</td>
        <td style="width: 20%; height: 27px; padding-left:5px;" class="tituloForm ajustarancho">Documento Cliente:&nbsp;</td>
        <td style="padding-left: 5px;" colspan="4">{documento_cliente}</td>        
      </tr>
      <tr>
        <td style="height: 27px; padding-left:5px;" class="tituloForm ajustarancho">Tipo de Mercanc&iacute;a:&nbsp;</td>
        <td style="padding-left: 5px;">
          {nombre_tipo_mercancia}
          <input type="hidden" name="tipo_mercancia" id="tipo_mercancia" value="{tipo_mercancia}" />
        </td>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">Pedido:</td>
        <td style="padding-left: 5px;" colspan="4">
          <input type="text" name="pedido" id="pedido" value="1" class="required" />
        </td>
      </tr>
      <tr>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">FMM:</td>
        <td>
          <input type="text" name="fmm" id="fmm" value="98001" class="required" />
        </td>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">Placa:</td>
        <td style="padding-left: 5px;" colspan="4">
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
        <td style="padding-left: 5px;" colspan="4">{fecha}</td>
        <input type="hidden" name="fecha" id="fecha" value="{fecha}" />
      </tr>
      <tr>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">Ciudad:</td>
        <td>
          <input type="text" name="ciudad" id="ciudad" value="{nombre_ciudad}" size="20" />
          <input type="hidden" name="codigo_ciudad" id="codigo_ciudad" value="{codigo_ciudad}" />
        </td>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">Destinatario:</td>
        <td style="padding-left: 5px;" colspan="4">
          <input type="text" name="destinatario" id="destinatario" value="{destinatario}" class="required" size="50" />
        </td>
      </tr>
      <tr>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">Direcci&oacute;n:</td>
        <td>
          <input type="text" name="direccion" id="direccion" value="{direccion}" size="50" />
        </td>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">Peso Bruto:</td>
        <td colspan="4" style="text-align:center;">
          <input name="peso_bruto" type="text" class="required ui-widget-content" id="peso_bruto" style="text-align: right;" value="0" size="15" />
        </td>
      </tr>
      <tr>
        <td style="height: 27px; padding-left:5px;" class="tituloForm">Bultos:</td>
        <td>
          <input name="bultos" type="text" class="required ui-widget-content" id="bultos" style="text-align: right;" value="0" onfocusout="actualizaObs()" size="15" />
        </td>
        <td style="height: 27px; padding-left:5px;" class="tituloForm ajustarancho">Ocultar Peso:</td>
        <td style="text-align: center;width:auto;">
          <input type="checkbox" id="ocultarPB" onChange="actualizaObs()" />
        </td>
        <td style="height: 27px; padding-left:5px;" class="tituloForm ajustarancho">Ocultar Valor:&nbsp;</td>
        <td style="text-align: center;width:auto;">
          <input type="checkbox" id="ocultarVC" onChange="actualizaObs()" />
        </td>
      </tr>
    </table>
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
      <tr>
        <th>C&oacute;digo</th>
        <th>Referencia</th>
        <th>Piezas Disponible</th>
        <th>Piezas a Alistar</th>
      </tr>
      <!--  BEGIN ROW -->
      <tr>
        <td style="text-align: center;">
          {codigo_referencia}
          <input type="hidden" value="{cod_referencia}" class="cantidadPiezas"/>
        </td>
        <td>{nombre_referencia}</td>
        <td style="text-align:center;">
          {disponible} Piezas
          <input type="hidden" name="disponible[{cod_referencia}]" id="disponible{cod_referencia}" value="{disponible}" />
        </td>
        <td style="text-align:center;">
          <input style="width:50px;text-align:right;" class="required number" name="cantidad_retirar[{cod_referencia}]" id="cantidad_retirar{cod_referencia}" /> Piezas
        </td>
      </tr>
      <!-- END ROW -->
    </table><br />
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
      <tr>
        <td>
          Observaciones<br/><textarea style="width:100%;" name="observaciones" id="obs" cols="90"></textarea>
        </td>
      </tr>
    </table>
  </fieldset><br />
  <center>
    <input name="enviarAlistar" id="enviarAlistar" class="button small yellow2" type="submit" value="Enviar" />
	</center>
</form>
<script>
  $().ready(function() {
    $("#form_alistamiento").validate();
  });

  function enviarAlistamiento() {
    var recorrer = $(".cantidadPiezas");
    var cont = true;
    for(var x=0; x < recorrer.length; x++) {
      if(empty($("#cantidad_retirar"+recorrer[x].value).attr("value"))|| $("#cantidad_retirar"+recorrer[x].value).attr("value")==0) {
        alert("Debe ingresar valores superiores a cero para alistar la Mercanc\u00eda.");
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
        url: 'index_blank.php?component=Alistamientos&method=generarAlistamiento',
        async: true,
        type: "POST",
        data:$('#form_alistamiento').serialize(),
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

  function actualizaObs() {
    var psbrt = $('#peso_bruto').val();
    var blts = $('#bultos').val();
    var newobs = $('#obs').val();
    var loncadena = newobs.length;
    const resto = newobs.indexOf("[")==0 || newobs.indexOf("[")==-1 ? 0 : 3;
    var extraer;

    if(newobs.indexOf("[")==0) {
      extraer = loncadena;
    } else if(newobs.indexOf("[")==-1) {
      extraer = 0;
    } else {
      extraer = (loncadena-newobs.indexOf("["))+resto;
    }

    //Inicializa Observaciones
    newobs = newobs.slice(0,loncadena-extraer);

    //Actualiza Observación con Peso Bruto
    if(psbrt.length!=0) {
      newobs += newobs.length==0 ? '[' + psbrt + ']' : ' - ' + '[' + psbrt + ']';
    }
    //Actualiza Observación con Bultos
    if(blts.length!=0) {
      newobs += newobs.length==0 ? '{' + blts + '}' : ' - ' + '{' + blts + '}';
    }
    //Verifica ocultar Peso
    newobs += document.getElementById('ocultarPB').checked ? ' - (1' : ' - (0';
    //Verifica ocultar Valor CIF
    newobs += document.getElementById('ocultarVC').checked ? '1)' : '0)';
    $('#obs').val(newobs);
  }
</script>