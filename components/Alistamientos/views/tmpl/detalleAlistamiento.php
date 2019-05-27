{COMODIN}
<div style="padding-top: 10px;"></div>
<fieldset>
  <legend>
    Informaci&oacute;n de Alistamiento
  </legend><br />
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr>
      <th colspan="4">
        Descripción del Alistamiento No. {codigo_operacion}
        <input type="hidden" name="codigo_operacion" id="codigo_operacion" value="{codigo_operacion}" />
      </th>
    </tr>
    <tr>
      <td class="tituloForm" style="width: 25%">Documento Cliente</td>
      <td style="width: 25%">
        {numero_documento}
        <input type="hidden" name="doc_cliente" id="doc_cliente" value="{numero_documento}" />
      </td>
      <td style="width: 25%" class="tituloForm">Nombre Cliente</td>
      <td style="width: 25%"><b>{razon_social}</b></td>
    </tr>
    <tr>
      <td class="tituloForm" style="width: 25%">Fecha de Alistamiento</td>
      <td style="width: 25%">{fecha}</td>
      <td style="width: 25%" class="tituloForm">Destinatario</td>
      <td style="width: 25%">{destinatario}</td>
    </tr>
    <tr>
      <td class="tituloForm">Ciudad / Direcci&oacute;n</td>
      <td>{ciudad} / {direccion}</td>      
      <td style="width: 25%" class="tituloForm">Conductor</td>
      <td style="width: 25%">{conductor_nombre}</td>
    </tr>
    <tr>
      <td class="tituloForm">Identificaci&oacute;n</td>
      <td>{conductor_identificacion}</td>
      <td style="width: 25%" class="tituloForm">Placa</td>
      <td style="width: 25%">{placa}</td>      
    </tr>
    <tr>      
      <td class="tituloForm">Empresa Transportadora</td>
      <td>{empresa}</td>      
      <td style="width: 25%" class="tituloForm">FMM</td>
      <td style="width: 25%">{fmm}</td>
    </tr>
    <tr>
      <td class="tituloForm">Tipo de Operación</td>
      <td>{producto}</td>
      <td class="tituloForm">Orden</td>
      <td>{orden}</td>
    </tr>
    <tr>
      <td class="tituloForm">Pedido</td>
      <td>{pedido}</td>
      <td class="tituloForm">Cantidad</td>
      <td>{cantidad}</td>
    </tr>
    <tr>
      <td class="tituloForm">Cantidad Nacional</td>
      <td>{cantidad_nac}</td>
      <td class="tituloForm">Cantidad Extranjera</td>
      <td>{cantidad_ext}</td>
    </tr>
    <tr>
      <td class="tituloForm">Observaciones</td>
      <td colspan="3">{observaciones}</td>
    </tr>
  </table><br /><br />
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr>
      <th colspan="11">Detalle del Alistamiento</th>
    </tr>
    <tr>
      <th>Orden</th>
      <th>Arribo</th>
      <th>Referencia</th>
      <th>Fecha Ing.</th>
      <th>Ubicación</th>
      <th>Piezas Nal.</th>
      <th>Peso Nal.</th>
      <th>Valor CIF</th>
      <th>Piezas Ext.</th>
      <th>Peso Ext.</th>
      <th>Valor FOB</th>
    </tr>
    <!-- BEGIN ROW -->
    <tr>
      <td>{orden_detalle}</td>
      <td style="text-align:center;">{arribo}</td>
      <td>[{codigo_referen}] {nombre_referencia}</td>
      <td style="text-align:center;">{fecha_detalle}</td>
      <td style="text-align:center;">{nombre_ubicacion}</td>
      <td style="text-align: right;">{cantidad_nacional}</td>
      <td style="text-align: right;">{peso_nacional}</td>
      <td style="text-align: right;">{valor_cif}</td>
      <td style="text-align: right;">{cantidad_extranjera}</td>
      <td style="text-align: right;">{peso_extranjera}</td>
      <td style="text-align: right;">{valor_fob}</td>
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
        <button style="font-family: sans-serif;font-size: 12px;" class="submit" type="submit" id="devolver" >Devolver Operación</button>
      </td>
      <td style="width: 5px;"></td>
      <td>
        <button style="font-family: sans-serif;font-size: 12px;" class="submit" type="submit" id="pcklist-1" >Packing List</button>
      </td>
    </tr>
    <tr style="display: {mostrar_mensaje}">
      <td colspan="2" style="color:#FF0000;">* La operación no puede ser devuelta debido a que se encuentra cerrada.</td>
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
    if(confirm("\u00BFRealmente desea Cerrar la Operación?")) {
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
          alert("Devolución Realizada con Éxito.");
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
</script>