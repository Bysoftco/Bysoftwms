{COMODIN}
<div style="height: 5px;"></div>
<div class="div_barraFija">
  <div id="titulo_ruta">{titulo_accion}</div>
</div><br />
<form name="form_alistamiento_kit" id="form_alistamiento_kit" method="post" action="javascript:enviarAlistamiento()" >
  <fieldset>
    <legend>
      Informaci&oacute;n de Kit
    </legend>
    <input type="hidden" name="id_kit" id="id_kit" value="{id_kit}" />
    <input type="hidden" name="doc_cliente" id="doc_cliente" value="{doc_cliente}" />
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
      <tr>
        <th colspan="2">Armado Kits</th>
      </tr>
      <tr>
        <td style="width: 40%" height="27px" class="tituloForm">Nombre Cliente</td>
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
        <td height="27px" class="tituloForm">C&oacute;digo Kit</td>
        <td>
          {codigo_kit}
          <input type="hidden" name="codigo_kit" id="codigo_kit" value="{codigo_kit}" />
        </td>
      </tr>
      <tr>
        <td height="27px" class="tituloForm">Nombre Kit</td>
        <td>{nombre_kit}</td>
      </tr>
    </table>
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
      <tr>
        <th></th>
        <th>Tipo Mercanc&iacute;a</th>
        <th>Piezas Disponibles</th>
        <th>Piezas a Retirar</th>
      </tr>
      <tr>
        <td style="height: 30px;">
          <input type="radio" name="radio_mercancia" id="kits_nacionales" value="nal" />
        </td>
        <td>Kits Nacionales</td>
        <td style="text-align: right">
          {disponible_nal} Kits
          <input type="hidden" name="disponible_nal" id="disponible_nal" value="{disponible_nal}" />
        </td>
        <td style="text-align: right">
          <span id="mostrar_nacional" style="display: none;">
            <input style="width:50px" class="digits cantidadPiezas" name="cantidad_nacional" id="cantidad_nacional" /> Kits
          </span>
        </td>
      </tr>
      <tr>
        <td style="height: 30px;"><input type="radio" name="radio_mercancia" id="kits_no_nacionales" value="nonal" /></td>
        <td>Kits Extranjeros</td>
        <td style="text-align: right">
          {disponible_nonal} Kits
          <input type="hidden" name="disponible_nonal" id="disponible_nonal" value="{disponible_nonal}" />
        </td>
        <td style="text-align: right">
          <span id="mostrar_no_nacional" style="display: none;">
            <input style="width:50px" class="digits cantidadPiezas" name="cantidad_no_nacional" id="cantidad_no_nacional" /> Kits
          </span>
        </td>
      </tr>
      <tr>
        <td style="height: 30px;"><input type="radio" name="radio_mercancia" id="kits_mixtos" value="mixta" /></td>
        <td>Kits Mixtos</td>
        <td style="text-align: right">
          {disponible_mixto} Kits
          <input type="hidden" name="disponible_mixto" id="disponible_mixto" value="{disponible_mixto}" />
        </td>
        <td style="text-align: right">
          <span id="mostrar_mixto" style="display: none;">
            <input style="width:50px" class="digits cantidadPiezas" name="cantidad_mixto" id="cantidad_mixto" /> Kits
          </span>
        </td>
      </tr>
    </table>
  </fieldset><br />
  <div style="display: none" id="mostrarContenido" name="mostrarContenido">
    <table width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
      <tr>
        <th width="40%" style="text-align: left">FMM</th>
        <td><input type="text" name="fmm" id="fmm" /></td>
      </tr>
      <tr>
        <th style="text-align: left">Unidad de Empaque</th>
        <td>
          <select id="unidad_empaque" name="unidad_empaque">{unidades_empaque}</select>
        </td>
      </tr>
      <tr>
        <th style="text-align: left">Observaciones</th>
        <td>
          <textarea style="width: 400px" name="observaciones" id="observaciones"></textarea>
        </td>
      </tr>
    </table>
    <div style="text-align: center;padding-top: 10px;">
      <input id="enviarFormulario" type="submit" class="button" value="Retirar" />
    </div>
  </div>
</form>
<script>
  $().ready(function() {
    $("#form_alistamiento_kit").validate();
  });

  $("#kits_nacionales").click(function() {
    $("#cantidad_no_nacional").attr("value", "");
    $("#mostrar_no_nacional").css("display", "none");
        
    $("#cantidad_mixto").attr("value", "");
    $("#mostrar_mixto").css("display", "none");
        
    $("#mostrar_nacional").css("display", "block");
    $("#mostrarContenido").css("display", "block");
        
    $("#cantidad_nacional").addClass("required");
    $("#cantidad_no_nacional").removeClass("required");
    $("#cantidad_mixto").removeClass("required");
        
    $("#fmm").addClass("required");
    $("#observaciones").addClass("required");
    $("#unidad_empaque").addClass("required");
  });

  $("#kits_no_nacionales").click(function() {
    $("#cantidad_nacional").attr("value", "");
    $("#mostrar_nacional").css("display", "none");
        
    $("#cantidad_mixto").attr("value", "");
    $("#mostrar_mixto").css("display", "none");
        
    $("#mostrar_no_nacional").css("display", "block");
    $("#mostrarContenido").css("display", "block");
        
    $("#cantidad_nacional").removeClass("required");
    $("#cantidad_no_nacional").addClass("required");
    $("#cantidad_mixto").removeClass("required");
        
    $("#fmm").addClass("required");
    $("#observaciones").addClass("required");
    $("#unidad_empaque").addClass("required");
  });
    
  $("#kits_mixtos").click(function() {
    $("#cantidad_no_nacional").attr("value", "");
    $("#mostrar_no_nacional").css("display", "none");
        
    $("#cantidad_nacional").attr("value", "");
    $("#mostrar_nacional").css("display", "none");
        
    $("#mostrar_mixto").css("display", "block");
    $("#mostrarContenido").css("display", "block");
        
    $("#cantidad_nacional").removeClass("required");
    $("#cantidad_no_nacional").removeClass("required");
    $("#cantidad_mixto").addClass("required");
        
    $("#fmm").addClass("required");
    $("#observaciones").addClass("required");
    $("#unidad_empaque").addClass("required");
  });

  function enviarAlistamiento() {
    var pasa = false;
    if($("#kits_nacionales").is(':checked')) {
      if(parseInt($("#cantidad_nacional").attr("value")) > parseInt($("#disponible_nal").attr("value"))) {
        alert("La cantidad de Kits a retirar no puede exceder la cantidad disponible.");
      } else {
        pasa = true;
      }
    } else if($("#kits_no_nacionales").is(':checked')) {
      if(parseInt($("#cantidad_no_nacional").attr("value")) > parseInt($("#disponible_nonal").attr("value"))) {
        alert("La cantidad de Kits a retirar no puede exceder la cantidad disponible.");
      } else {
        pasa = true;
      }
    } else if($("#kits_mixtos").is(':checked')) {
      if(parseInt($("#cantidad_mixto").attr("value")) > parseInt($("#disponible_mixto").attr("value"))) {
        alert("La cantidad de Kits a retirar no puede exceder la cantidad disponible.");
      } else {
        pasa = true;
      }
    }

    if(pasa) {
      $.ajax({
        url: 'index_blank.php?component=Kits&method=generarAlistamiento',
        async: true,
        type: "POST",
        data: $("#form_alistamiento_kit").serialize(),
        success: function(msm) {
          jQuery(document.body).overlayPlayground('close');void(0);
          $('#componente_central').html(msm);
        }
      });
    }
  }
</script>