<style>
  td.ajustarancho {
    width: 1px;
    white-space: nowrap;
  }
</style>
{COMODIN}
<div style="padding-top: 10px;"></div>
<fieldset>
  <legend>
    Informaci&oacute;n de Alistamiento
  </legend><br />
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr>
      <th colspan="6">
        Descripci&oacute;n del Alistamiento No. {codigo_operacion}
        <input type="hidden" name="codigo_operacion" id="codigo_operacion" value="{codigo_operacion}" />
      </th>
    </tr>
    <tr>
      <td class="tituloForm ajustarancho">Nombre Cliente&nbsp;</td>
      <td>&nbsp;<b>{razon_social}</b></td>
      <td class="tituloForm ajustarancho">Documento Cliente&nbsp;</td>
      <td>&nbsp;
        {numero_documento}
        <input type="hidden" name="doc_cliente" id="doc_cliente" value="{numero_documento}" />
      </td>
      <td class="tituloForm ajustarancho">Fecha de Alistamiento&nbsp;</td>
      <td>&nbsp;{fecha}</td>
    </tr>
    <tr>
      <td class="tituloForm ajustarancho">Destinatario&nbsp;</td>
      <td>&nbsp;{destinatario}</td>
      <td class="tituloForm ajustarancho">Ciudad / Direcci&oacute;n&nbsp;</td>
      <td>&nbsp;{ciudad} / {direccion}</td>      
      <td class="tituloForm ajustarancho">Conductor&nbsp;</td>
      <td>&nbsp;{conductor_nombre}</td>
    </tr>
    <tr>
      <td class="tituloForm ajustarancho">Identificaci&oacute;n&nbsp;</td>
      <td>&nbsp;{conductor_identificacion}</td>
      <td class="tituloForm ajustarancho">Placa&nbsp;</td>
      <td>&nbsp;{placa}</td>
      <td class="tituloForm ajustarancho">Empresa Transportadora&nbsp;</td>
      <td>&nbsp;{empresa}</td>
    </tr>
    <tr>            
      <td class="tituloForm ajustarancho">FMM&nbsp;</td>
      <td>&nbsp;{fmm}</td>
      <td class="tituloForm ajustarancho">Tipo de Operaci&oacute;n&nbsp;</td>
      <td>&nbsp;{producto}</td>
      <td class="tituloForm ajustarancho">Orden&nbsp;</td>
      <td>&nbsp;{orden}</td>
    </tr>
    <tr>
      <td class="tituloForm">Pedido&nbsp;</td>
      <td>&nbsp;{pedido}</td>
      <td class="tituloForm">Cantidad&nbsp;</td>
      <td style="text-align:right;">{cantidad}&nbsp;</td>
      <td class="tituloForm">Cantidad Nacional&nbsp;</td>
      <td style="text-align:right;">{cantidad_nac}&nbsp;</td>
    </tr>
    <tr>
      <td class="tituloForm ajustarancho">Cantidad Extranjera&nbsp;</td>
      <td style="text-align:right;">{cantidad_ext}&nbsp;</td>
      <td class="tituloForm">Peso Neto:&nbsp;</td>
      <td>
        <!-- BEGIN pesoneto -->
        <input type="text" name="pesoneto" id="pesoneto" value="{pesoneto}" size="10" style="text-align:right;" readonly />
        <!-- END pesoneto -->
      </td>
      <td class="tituloForm">Peso Bruto:&nbsp;</td>
      <td>
        <input type="text" name="pesobruto" id="pesobruto" value="" size="10" style="text-align:right;" readonly />
      </td>
    </tr>
    <tr>
      <td class="tituloForm">Bultos:&nbsp;</td>
      <td>
        <input type="text" name="bultos" id="bultos" value="" size="10" style="text-align:right;" readonly />
      </td>
      <td class="tituloForm">Ocultar Peso:&nbsp;</td>
      <td>
        <input type="text" name="ocultarPB" id="ocultarPB" value="" size="10" style="text-align:right;" readonly />
      </td>
      <td class="tituloForm">Ocultar Valor:&nbsp;</td>
      <td>
        <input type="text" name="ocultarVC" id="ocultarVC" value="" size="10" style="text-align:right;" readonly />
      </td>
    </tr>    
    <tr>
      <td class="tituloForm">Observaciones&nbsp;</td>
      <td colspan="5">&nbsp;{observaciones}</td>
    </tr>
  </table><br /><br />
  <input type="hidden" name="obs" id="obs" value="{observaciones}" />
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr>
      <th colspan="11">Detalle del Alistamiento</th>
    </tr>
    <tr>
      <th>Orden</th>
      <th>Arribo</th>
      <th>Referencia</th>
      <th>Fecha Ing.</th>
      <th>Ubicaci&oacute;n</th>
      <th>Piezas Nal.</th>
      <th>Peso Nal.</th>
      <th>Valor CIF</th>
      <th>Piezas Ext.</th>
      <th>Peso Ext.</th>
      <th>Valor FOB</th>
    </tr>
    <!-- BEGIN ROW -->
    <tr>
      <td style="text-align:center;">{orden_detalle}</td>
      <td style="text-align:center;">{arribo}</td>
      <td>[{codigo_referen}] {nombre_referencia}</td>
      <td style="text-align:center;">{fecha_detalle}</td>
      <td style="text-align:center;">{nombre_ubicacion}</td>
      <td style="text-align: right;">{cantidad_nacional}</td>
      <td style="text-align: right;"><span style="display:{verPeson}">{peso_nacional}</span></td>
      <td style="text-align: right;"><span style="display:{verCif}">{valor_cif}</span></td>
      <td style="text-align: right;">{cantidad_extranjera}</td>
      <td style="text-align: right;"><span style="display:{verPesoe}">{peso_extranjera}</span></td>
      <td style="text-align: right;"><span style="display:{verFob}">{valor_fob}</span></td>
    </tr>
    <!-- END ROW -->
  </table><br /><br />
  <table align="right" cellpadding="0" cellspacing="0">
    <tr style="display: {mostrar_botones}">
      <td>
        <button style="font-family: sans-serif;font-size: 12px;" class="submit" type="submit" id="cerrar_operacion" >Cerrar</button>
      </td>
      <td style="width: 5px;"></td>
      <td>
        <button style="font-family: sans-serif;font-size: 12px;" class="submit" type="submit" id="devolver" >Devolver Operaci&oacute;n</button>
      </td>
      <td style="width: 5px;"></td>
      <td>
        <button style="font-family: sans-serif;font-size: 12px;" class="submit" type="submit" id="pcklist-1" >Packing List</button>
      </td>
    </tr>
    <tr style="display: {mostrar_mensaje}">
      <td colspan="2" style="color:#FF0000;">* La operaci&oacute;n no puede ser devuelta debido a que se encuentra cerrada.</td>
      <td style="width: 5px;"></td>
      <td>
        <button style="font-family: sans-serif;font-size: 12px;" class="submit" type="submit" id="pcklist-2" >Packing List</button>
      </td>
    </tr>
  </table>
  <input type="hidden" name="codigoMaestro" id="codigoMaestro" value="{codigo_operacion}" />
  <input type="hidden" name="tipo_mercancia" id="tipo_mercancia" value="{tipo_mercancia}" />
  <input type="hidden" name="n" id="n" value="{n}" />
</fieldset>
<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");    

  $( "#cerrar_operacion" ).button({
    text: true,
    icons: {
      primary: "ui-icon-locked"
    }
  })
	.click(function() {
    if(confirm("\u00BFRealmente desea Cerrar la Operaci\u00f3n?")) {
      $.ajax({
        url: 'index_blank.php?component=Alistamientos&method=cerrarAlistamiento',
        type: "POST",
        data: {
          codigo_operacion: $("#codigo_operacion").attr("value"),
          tipo_mercancia: $("#tipo_mercancia").attr('value')
        },
        success: function(msm) {
          jQuery(document.body).overlayPlayground('close');void(0);
          $('#componente_central').html(msm);
        }
      });
    }
  });

  $( "#devolver" ).button({
    text: true,
    icons: {
      primary: "ui-icon-arrowreturnthick-1-w"
    }
  })
	.click(function() {
    if(confirm("\u00BFRealmente desea Devolver la Operación?")) {
      $.ajax({
        url: 'index_blank.php?component=Alistamientos&method=devolderAlistamiento',
        type: "POST",
        data: {
          codigo_operacion: $("#codigo_operacion").attr("value"),
          tipo_mercancia: $("#tipo_mercancia").attr('value'),
          doc_cliente: $("#doc_cliente").attr("value")
        },
        success: function(msm) {
          alert("Devoluci\u00f3n Realizada con \u00c9xito.");
          jQuery(document.body).overlayPlayground('close');void(0);
          $('#componente_central').html(msm);
        }
      });
    }
  });
  
  $( "#pcklist-"+$("#n").val() ).button({
    text: true,
    icons: {
      primary: "ui-icon-document"
    }
  })
	.click(function() {
    window.open("index_blank.php?component=Alistamientos&method=generarPackingList&codigoMaestro="+$("#codigoMaestro").attr("value"));
    return false;
  });

  var observa = document.getElementById("obs").value;

  //Obtenemos el Peso Bruto
  var pb = observa.split('[');
  let valor = pb[1].split(']')[0];

  const numActual = +valor.replace(/,/g, '')
  const peso = numActual.toLocaleString('en-US',{minimumFractionDigits: 2})
  document.getElementById("pesobruto").value = peso;

  //Obtenemos Bultos
  var nblts = observa.split('{');
  let vblts = nblts[1].split('}')[0];
  document.getElementById("bultos").value = vblts;

  //Obtenemos Checks - Ver/Ocultar Peso/Valor
  var rchks = observa.split('(');
  let vchks = rchks[1].split(')')[0];
  document.getElementById("ocultarPB").value = vchks.substring(0,1);
  document.getElementById("ocultarVC").value = vchks.substring(1,2);
</script>