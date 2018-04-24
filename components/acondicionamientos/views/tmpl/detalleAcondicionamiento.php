{COMODIN}
<style>
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
      <td class="tituloForm">Código</td>
      <td>
        <input type="text" name="codigo_reporte" id="codigo_reporte" value="{codigo}" />
      </td>
    </tr>
    <tr>
      <td class="tituloForm">Tipo de Mercanc&iacute;a</td>
      <td>{nombre_tipo_mercancia}</td>
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
      <th colspan="13">Detalle del Acondicionamiento</th>
    </tr>
    <tr>
      <th>Orden</th>
      <th>Doc.TTE</th>
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
      <td style="text-align:center;">{doc_tte}</td>
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
  <div id="derecha">
    <table align="right" cellpadding="0" cellspacing="0">
      <tr><td colspan="4" style="height:15px;"></td></tr>
      <tr style="display: {mostrar_botones}">
        <td>
          <span style="color:#FF0000;" id="msgAcondicionar"></span>
        </td>
        <td style="width: 5px;"></td>
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
          <button style="font-family: sans-serif;font-size: 12px;" class="submit" type="submit" id="ordenac-1" >Orden Acondicionamiento</button>
        </td>
      </tr>
      <tr style="display: {mostrar_mensaje}">
        <td colspan="2" style="color:#FF0000;">* La operación no puede ser devuelta debido a que se encuentra cerrada.</td>
        <td style="width: 5px;"></td>
        <td>
          <button style="font-family: sans-serif;font-size: 12px;" class="submit" type="submit" id="ordenac-2" >Orden Acondicionamiento</button>
        </td>
      </tr>
    </table>
  </div>
  <input type="hidden" name="codigoMaestro" id="codigoMaestro" value="{codigo_operacion}" />
  <input type="hidden" name="tipo_mercancia" id="tipo_mercancia" value="{tipo_mercancia}" />
  <input type="hidden" name="nombre_tipo_mercancia" id="nombre_tipo_mercancia" value="{nombre_tipo_mercancia}" />
  <input type="hidden" name="inventario_entrada" id="inventario_entrada" value="{inv_entrada}" />
  <input type="hidden" name="fecha" id="fecha" value="{fecha}" />
  <input type="hidden" name="placa" id="placa" value="{placa}" />
  <input type="hidden" name="id_camion" id="id_camion" value="{id_camion}" />
  <input type="hidden" name="conductor" id="conductor" value="{conductor_nombre}" />
  <input type="hidden" name="destinatario" id="destinatario" value="{destinatario}" />
  <input type="hidden" name="direccion" id="direccion" value="{direccion}" />
  <input type="hidden" name="fmm" id="fmm" value="{fmm}" />
  <input type="hidden" name="doc_tte" id="doc_tte" value="{doc_tte}" />
  <input type="hidden" name="cod_referencia" id="cod_referencia" value="{cod_referencia}" />
  <input type="hidden" name="pedido" id="pedido" value="{pedido}" />
  <input type="hidden" name="ciudad" id="ciudad" value="{ciudad}" />
  <input type="hidden" name="codigo_ciudad" id="codigo_ciudad" value="{codigo_ciudad}" />
  <input type="hidden" name="observaciones" id="observaciones" value="{observaciones}" />
  <input type="hidden" name="n" id="n" value="{n}" />
</fieldset>
<div class="registrosAcondicionar">
  <a id="registrarAcondicionamiento" href="" title="Registro del Acondicionamiento">
  </a>
</div>
<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");
  
  //Habilita/Deshabilita el botón de Acondicionar y el Mensaje
  $("#acondicionar").attr("disabled",{verBoton});
  if({verBoton}) $("#msgAcondicionar").html("");
  else $("#msgAcondicionar").html("* Dar clic en el botón Acondicionar para registrar el Acondicionamiento ");

  $( "#acondicionar" ).button({
    text: true,
    icons: {
      primary: "ui-icon-newwin"
    }
  })
	.click(function() {
    //Visualiza Registros con Disponibilidad para Acondicionar
    registrarAcondicionamiento.click();
  });

  $('.registrosAcondicionar a').wowwindow({
    draggable: true,
    width: 950,
    height: 450,
    overlay: {
      clickToClose: false,
      background: '#000000'
    },
    onclose: function() {
      $('.formError').remove();
    },
    before: function() {
      $.ajax({
        url: 'index_blank.php?component=acondicionamientos&method=registroAcondicionamiento',
        async: true,
        type: "POST",
        data: { 
          codigo_maestro: $('#codigoMaestro').val(),
          tipo_mercancia: $('#tipo_mercancia').val(),
          doc_cliente: $('#doc_cliente').val(),
          nombre_tipo_mercancia: $('#nombre_tipo_mercancia').val(),
          fecha: $('#fecha').val(),
          placa: $('#placa').val(),
          id_camion: $('#id_camion').val(),
          conductor: $('#conductor').val(),
          destinatario: $('#destinatario').val(),
          direccion: $('#direccion').val(),
          fmm: $('#fmm').val(),
          doc_tte: $('#doc_tte').val(),
          cod_referencia: $('#cod_referencia').val(),
          pedido: $('#pedido').val(),
          ciudad: $('#ciudad').val(),
          codigo_ciudad: $('#codigo_ciudad').val(),
          observaciones: $('#observaciones').val()
        },
        success: function(msm) {
          $('#wowwindow-inner').html(msm);
        }
      });
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
        url: 'index_blank.php?component=acondicionamientos&method=devolverAcondicionamiento',
        type: "POST",
        data: {
          codigo_operacion: $("#codigo_operacion").attr("value"),
          tipo_mercancia: $("#tipo_mercancia").attr('value'),
          nombre_tipo_mercancia: $('#nombre_tipo_mercancia').val(),
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
  
  $( "#ordenac-"+$("#n").val() ).button({
    text: true,
    icons: {
      primary: "ui-icon-document"
    }
  })
	.click(function() {
    window.open("index_blank.php?component=acondicionamientos&method=generarOrdenAcondicionamiento&codigoMaestro="
      +$("#codigoMaestro").attr("value")+"&nombre_tipo_mercancia="+$("#nombre_tipo_mercancia").attr("value")
      +"&codigo_reporte="+$("#codigo_reporte").attr("value"));
    return false;
  });
  
  function calcular() {
    //Validación del valor de Mercancías Acondicionadas
    $("#acondicionadas").val(($("#piezas").val()-$("#rechazadas").val()-$("#devueltas").val()).toFixed(2));
    if($("#acondicionadas").val()<0) alert('La cantidad de mercancía Acondicionada no puede ser menor que 1. Revisar la cantidad de Rechazadas o Devueltas.');
  }
</script>