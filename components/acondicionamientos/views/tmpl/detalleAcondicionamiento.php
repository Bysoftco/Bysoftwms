{COMODIN}
<style>
  #izquierda { float: left; width:50% }
  #derecha { float: right; }
</style>
<div style="padding-top: 10px;"></div>
<fieldset>
  <legend>
    Informaci&oacute;n de Acondicionamiento
  </legend><br />
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr>
      <th colspan="4">
        Descripción del Acondicionamiento No. {codigo_operacion}
        <input type="hidden" name="codigo_operacion" id="codigo_operacion" value="{codigo_operacion}" />
      </th>
    </tr>
    <tr>
      <td class="tituloForm" style="width: 25%">Pedido</td>
      <td>{pedido}</td>
      <td class="tituloForm" style="width: 25%">Fecha de Acondicionamiento</td>
      <td style="width: 25%">{fecha}</td>
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
      <td style="width: 25%" class="tituloForm">Destinatario</td>
      <td style="width: 25%">{destinatario}</td>
      <td class="tituloForm">Ciudad / Direcci&oacute;n</td>
      <td>{ciudad} / {direccion}</td>
    </tr>
    <tr>
      <td style="width: 25%" class="tituloForm">Conductor</td>
      <td style="width: 25%">{conductor_nombre}</td>
      <td class="tituloForm">Identificaci&oacute;n</td>
      <td>{conductor_identificacion}</td>
    </tr>
    <tr>
      <td style="width: 25%" class="tituloForm">Placa</td>
      <td style="width: 25%">{placa}</td>
      <td style="width: 25%" class="tituloForm">FMM</td>
      <td style="width: 25%">{fmm}</td>
    </tr>
    <tr>      
      <td class="tituloForm">Registro INVIMA</td>
      <td>{reginvima}</td>
      <td class="tituloForm">Referencia</td>
      <td><b>{producto}</b></td>
    </tr>
    <tr>
      <td class="tituloForm">Documento Transporte</td>
      <td>{doc_tte}</td>
      <td class="tituloForm">Cantidad</td>
      <td>
        <input type="text" name="cantidad" id="cantidad" value="{cantidad}" readonly="" />
      </td>
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
      <th colspan="12">Detalle del Acondicionamiento</th>
    </tr>
    <tr>
      <th>Orden</th>
      <th>Referencia</th>
      <th>Nombre Referencia</th>
      <th>Fecha Exp.</th>
      <th>M/L/C</th>
      <th>Fecha Ing.</th>
      <th>Ubicación</th>
      <th>Movimiento</th>
      <th>Piezas Nal.</th>
      <th>Peso Nal.</th>
      <th>Piezas Ext.</th>
      <th>Peso Ext.</th>
    </tr>
    <!-- BEGIN ROW -->
    <tr>
      <td style="text-align:center;">{orden_detalle}</td>
      <td style="text-align:center;">{codigo_referen}</td>
      <td>{nombre_referencia}</td>
      <td style="text-align:center;color: red;">{fecha_expira}</td>
      <td style="text-align:center;">{modelo}</td>
      <td style="text-align:center;">{fecha_detalle}</td>
      <td style="text-align:center;">{nombre_ubicacion}</td>
      <td style="text-align:center;">{nombre_mcia}</td>
      <td style="text-align: right;">{cantidad_nacional}</td>
      <td style="text-align: right;">{peso_nacional}</td>
      <td style="text-align: right;">{cantidad_extranjera}</td>
      <td style="text-align: right;">{peso_extranjera}</td>
    </tr>
    <input type="hidden" name="peso_naci" id="peso_naci" value="{peso_nacional}" />
    <input type="hidden" name="peso_nonac" id="peso_nonac" value="{peso_extranjera}" />
    <input type="hidden" name="cantidad_naci" id="cantidad_naci" value="{cantidad_nacional}" />
    <input type="hidden" name="cantidad_nonac" id="cantidad_nonac" value="{cantidad_extranjera}" />
    <input type="hidden" name="cif" id="cif" value="{valor_cif}" />
    <input type="hidden" name="fob_nonac" id="fob_nonac" value="{valor_fob}" />
    <!-- END ROW -->
  </table><br /><br />
  <div id="acondiciona">
    <div id="izquierda">
      <table width="100%" align="left" cellpadding="0" cellspacing="0" id="tabla_acondicionar">
        <tr>
          <th>Piezas</th>
          <th>Rechazadas</th>
          <th>Tipo Rechazo</th>
          <th>Devueltas</th>
          <th>Acondicionadas</th>
        </tr>
        <tr>
          <td style="text-align:center;">
            <input type="text" name="piezas" id="piezas" value="{cantidad}" readonly="" />
          </td>
          <td style="text-align:center;">
            <input type="text" id="rechazadas" name="rechazadas" style="text-align: right;" value="0" onchange="calcular()" size="10" />
          </td>
          <td style="text-align:center;">
            <select name="tipo_rechazo" id="tipo_rechazo">{select_tiporechazo}</select>
          </td>
          <td style="text-align:center;">
            <input type="text" id="devueltas" name="devueltas" style="text-align: right;" value="0" onchange="calcular()" size="10" />
          </td>
          <td style="text-align:center;">
            <input type="text" name="acondicionadas" id="acondicionadas" value="{cantidad}" readonly="" />
          </td>
        </tr>
      </table>    
    </div>
    <div id="derecha">
      <table align="right" cellpadding="0" cellspacing="0">
        <tr><td colspan="4" style="height:15px;"></td></tr>
        <tr style="display: {mostrar_botones}">
          <td>
            <button style="font-family: sans-serif;font-size: 12px;" class="submit" type="submit" id="acondicionar" >Acondicionar</button>
          </td>
          <td style="width: 5px;"></td>
          <td>
            <button style="font-family: sans-serif;font-size: 12px;" class="submit" type="submit" id="cerrar_operacion" >Cerrar</button>
          </td>
          <td style="width: 5px;"></td>
          <td>
            <button style="font-family: sans-serif;font-size: 12px;" class="submit" type="submit" id="devolver" >Devolver Operación</button>
          </td>
          <td style="width: 5px;"></td>
          <td>
            <button style="font-family: sans-serif;font-size: 12px;" class="submit" type="submit" id="pcklist-1" >Orden Acondicionamiento</button>
          </td>
        </tr>
        <tr style="display: {mostrar_mensaje}">
          <td colspan="2" style="color:#FF0000;">* La operación no puede ser devuelta debido a que se encuentra cerrada.</td>
          <td style="width: 5px;"></td>
          <td>
            <button style="font-family: sans-serif;font-size: 12px;" class="submit" type="submit" id="pcklist-2" >Orden Acondicionamiento</button>
          </td>
        </tr>
      </table>    
    </div>
  </div>
  <input type="hidden" name="codigoMaestro" id="codigoMaestro" value="{codigo_operacion}" />
  <input type="hidden" name="tipo_mercancia" id="tipo_mercancia" value="{tipo_mercancia}" />
  <input type="hidden" name="inventario_entrada" id="inventario_entrada" value="{inv_entrada}" />
  <input type="hidden" name="fecha" id="fecha" value="{fecha}" />
  <input type="hidden" name="n" id="n" value="{n}" />
</fieldset>
<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");    

  $( "#acondicionar" ).button({
    text: true,
    icons: {
      primary: "ui-icon-newwin"
    }
  })
	.click(function() {
    //Valida si se hizo movimiento de Rechazo o Devolución
    if(parseFloat($("#cantidad").val())!=parseFloat($("#acondicionadas").val())) {
      if(confirm("\u00BFEstá seguro de la cantidad de mercancia a acondicionar?")) {
        //Cálculo del Precio Unitario
        var peso_uni = $("#tipo_mercancia").val()==1?$("#peso_naci").attr('value')/$("#cantidad").attr('value'):$("#peso_nonac").attr('value')/$("#cantidad").attr('value');
        var val_unit = $("#tipo_mercancia").val()==1?$("#cif").attr('value')/$("#cantidad").attr('value'):$("#fob_nonac").attr('value')/$("#cantidad").attr('value');
        $("#cantidad").val($("#acondicionadas").val());
        if($("#tipo_mercancia").val()==1) {
          $("#cantidad_naci").val($("#acondicionadas").val());
        }
        else $("#cantidad_nonac").val($("#acondicionadas").val());
        $.ajax({
          url: 'index_blank.php?component=acondicionamientos&method=registrarAcondicionamiento',
          type: "POST",
          data: {
            codigo_operacion: $("#codigo_operacion").attr("value"),
            tipo_mercancia: $("#tipo_mercancia").attr('value'),
            rechazadas: $("#rechazadas").attr('value'),
            tiporechazo: $("#tipo_rechazo").attr('value'),
            devueltas: $("#devueltas").attr('value'),
            inventario_entrada: $("#inventario_entrada").attr('value'),
            fecha: $("#fecha").attr('value'),
            cantidad: $("#cantidad").attr('value'),
            cantidad_naci: $("#cantidad_naci").attr('value'),
            cantidad_nonac: $("#cantidad_nonac").attr('value'),
            peso_naci: $("#cantidad_naci").attr('value')*peso_uni,
            peso_nonac: $("#cantidad_nonac").attr('value')*peso_uni,
            cif: $("#cantidad_naci").attr('value')*val_unit,
            fob_nonac: $("#cantidad_nonac").attr('value')*val_unit,
            peso_uni: peso_uni,
            val_unit: val_unit
          },
          success: function(msm) {
            jQuery(document.body).overlayPlayground('close');void(0);
            $('#componente_central').html(msm);
          }
        });      
      }
    }
  });

  $( "#cerrar_operacion" ).button({
    text: true,
    icons: {
      primary: "ui-icon-locked"
    }
  })
	.click(function() {
    if(confirm("\u00BFRealmente desea Cerrar la Operación?")) {
      $.ajax({
        url: 'index_blank.php?component=acondicionamientos&method=cerrarAcondicionamiento',
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
        url: 'index_blank.php?component=acondicionamientos&method=devolderAcondicionamiento',
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
    window.open("index_blank.php?component=acondicionamientos&method=generarPackingList&codigoMaestro="+$("#codigoMaestro").attr("value"));
    return false;
  });
  
  function calcular() {
    //Validación del valor de Mercancías Acondicionadas
    $("#acondicionadas").val(($("#piezas").val()-$("#rechazadas").val()-$("#devueltas").val()).toFixed(2));
    if($("#acondicionadas").val()<0) alert('La cantidad de mercancía Acondicionada no puede ser menor que 1. Revisar la cantidad de Rechazadas o Devueltas.');
  }
</script>