<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <title>Orden Acondicionamiento No. {codigo_operacion}</title>
  <script type="text/javascript">
    print();
  </script>
  <style type="text/css">
    #tabla_packinglist {
      border: 1px solid #000;
      border-radius: 6px;
      -webkit-border-radius: 6px;
      -moz-border-radius: 6px;
    }
    #tabla_packinglist td, #tabla_packinglist th {
      padding: 5px;
    }
    #tabla_packinglist thead {
      font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
      padding: .2em 0 .2em .5em;
      text-align: left;
      color: #4B4B4B;
      background-color: #C8C8C8;
      background-image: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#e3e3e3), color-stop(.6,#B3B3B3));
      background-image: -moz-linear-gradient(top, #D6D6D6, #B0B0B0, #B3B3B3 90%);
      border-top: 1px solid #000;
      border-left: 1px solid #000;
      border-bottom: 1px solid #000;
    }
    #tabla_packinglist th {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 14px;
      line-height: 20px;
      font-style: normal;
      font-weight: normal;
      text-align: center;
      text-shadow: white 1px 1px 1px;
      border-top: 1px solid #000;
      border-left: 1px solid #000;
      border-bottom: 1px solid #000;
      background-color: #A4A4A4;
    }
    #tabla_packinglist td {
      line-height: 11px;
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 10px;
      border-left: 1px solid #000;
      border-bottom: 1px solid #000;
    }
    #tabla_packinglist td:hover {
      background-color: #fff;
    }      
  </style>
</head>
<body>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_packinglist">
    <tr>
      <td rowspan="5" style="border:none;width:20%;text-align:center;"><img src="integrado/imagenes/cliente.png" width="79" height="37" /></td>
      <td colspan="5" style="border-bottom:none;"></td>
    </tr>
    <tr>
      <td colspan="5" style="text-align:center;border-bottom:none;font-size:14px;">
        <b>ORDEN DE ACONDICIONAMIENTO</b>
      </td>
    </tr>
    <tr><td colspan="5"></td></tr>
    <tr>
      <th><b>C&oacute;digo</b></th>
      <th><b>Versi&oacute;n</b></th>
      <th colspan="3"><b>P&aacute;gina</b></th>
    </tr>
    <tr>
      <td style="text-align:center;">PGP-01-F1</td>
      <td></td>
      <td style="text-align:center;">1</td>
      <td style="text-align:center;">de</td>
      <td style="text-align:center;">1</td>
    </tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_packinglist">
    <tr>
      <th colspan="8"><b>INFORMACI&Oacute;N GENERAL</b></th>
    </tr>
    <tr>
      <td style="width: 12%;" class="tituloForm">No. Orden</td>
      <td style="width: 13%"><b>{orden}</b></td>
      <td class="tituloForm" style="width: 12%">Fecha de Operaci&oacute;n</td>
      <td style="width: 13%">{fecha}</td>
      <td class="tituloForm" style="width: 12%">Pedido</td>
      <td style="width: 13%">{pedido}</td>
      <td class="tituloForm" style="width: 8%">No. Gu&iacute;a</td>
      <td style="width: 17%"></td>
    <tr>
      <td style="width: 10%;" class="tituloForm">Nombre Cliente</td>
      <td style="width: 15%"><b>{razon_social}</b></td>
      <td class="tituloForm" style="width: 12%">Documento Cliente</td>
      <td style="width: 13%">
        {numero_documento}
        <input type="hidden" name="doc_cliente" id="doc_cliente" value="{numero_documento}" />
      </td>
      <td class="tituloForm" style="width: 12%">Destinatario</td>
      <td style="width: 13%">{destinatario}</td>
      <td style="width: 8%;" class="tituloForm">Ciudad / Direcci&oacute;n</td>
      <td style="width: 17%"><b>{ciudad} / {direccion}</b></td>
    </tr>      
    <tr>      
      <td class="tituloForm" style="width: 12%">Conductor</td>
      <td style="width: 13%">
        {conductor_nombre}
      </td>
      <td class="tituloForm" style="width: 12%">Identificaci&oacute;n</td>
      <td style="width: 13%">{conductor_identificacion}</td>
      <td class="tituloForm" style="width: 12%">Placa</td>
      <td style="width: 13%">{placa}</td>
      <td class="tituloForm" style="width: 8%">FMM</td>
      <td style="width: 17%">{fmm}</td>
    </tr>
    <tr>
      <td class="tituloForm" style="width: 12%">Documento Transporte</td>
      <td style="width: 13%">{doc_tte}</td>
      <td class="tituloForm" style="width: 12%">Registro INVIMA</td>
      <td style="width: 13%">{reginvima}</td>
      <td class="tituloForm" style="width: 12%">Referencia</td>
      <td style="width: 13%">{codigo_ref}</td>
      <td class="tituloForm" style="width: 8%">Producto</td>
      <td style="width: 1%">{producto}</td>
    </tr>
    <tr>
      <td class="tituloForm" style="width: 12%">Cantidad</td>
      <td style="width: 13%">{cantidad}</td>
      <td class="tituloForm" style="width: 12%">Cantidad Nacional</td>
      <td style="width: 13%">{cantidad_nac}</td>
      <td class="tituloForm" style="width: 12%">Cantidad Extranjera</td>
      <td style="width: 13%">{cantidad_ext}</td>
      <td class="tituloForm" style="width: 8%">Peso</td>
      <td style="width: 17%">{peso}</td>
    </tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_packinglist">
    <tr>
      <th colspan="12"><b>PROCESOS A REALIZAR</b></th>
    </tr>
    <tr>
      <td style="width: 7%;font-size:12px;" class="tituloForm"><b>PROCESO 1</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:12px;">Colocar Sticker 1</td>
      <td style="width: 7%;font-size:12px;" class="tituloForm"><b>PROCESO 2</b></td>
      <td style="width: 3%;border-left:none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:12px;">Colocar Sticker 2</td>
      <td style="width: 7%;font-size:12px;" class="tituloForm"><b>PROCESO 3</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:12px;">Retirar Etiqueta</td>
      <td style="width: 7%;font-size:12px;" class="tituloForm"><b>PROCESO 4</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:12px;">Colocar Etiqueta</td>
    </tr>
    <tr>
      <td style="width: 7%;font-size:12px;" class="tituloForm"><b>PROCESO 5</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:12px;">Desestuchar</td>
      <td style="width: 7%;font-size:12px;" class="tituloForm"><b>PROCESO 6</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:12px;">Estuchar</td>
      <td style="width: 7%;font-size:12px;" class="tituloForm"><b>PROCESO 7</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:12px;">Colocar Inserto</td>
      <td style="width: 7%;font-size:12px;" class="tituloForm"><b>PROCESO 8</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:12px;">Sello Seguridad</td>
    </tr>
    <tr>
      <td style="width: 7%;font-size:12px;" class="tituloForm"><b>PROCESO 9</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:12px;" colspan="4"></td>
      <td style="width: 7%;font-size:12px;" class="tituloForm"><b>PROCESO 10</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:12px;" colspan="4"></td>
    </tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_packinglist">
    <tr>
      <th><b>Orden</b></th>
      <th><b>Referencia</b></th>
      <th><b>Nombre Referencia</b></th>
      <th><b>Fecha Exp.</b></th>
      <th><b>M/L/C</b></th>
      <th><b>Fecha Ing.</b></th>
      <th><b>Ubicaci&oacute;n</b></th>
      <th><b>Movimiento</b></th>
      <th><b>Piezas Nal.</b></th>
      <th><b>Peso Nal.</b></th>
      <th><b>Piezas Ext.</b></th>
      <th><b>Peso Ext.</b></th>
    </tr>
    <!-- BEGIN ROW -->
    <tr>
      <td style="padding: 2.5px;">{orden_detalle}</td>
      <td style="text-align: center;padding: 2.5px;">
        <img src="components/acondicionamientos/views/tmpl/generar.php?serial={codigo_referen}" />
      </td>
      <td style="padding-left: 5px;">{nombre_referencia}</td>
      <td style="text-align:center;color: red;">{fecha_expira}</td>
      <td style="text-align:center;">{modelo}</td>
      <td style="text-align:center;">{fecha_detalle}</td>
      <td style="text-align:center;">{nombre_ubicacion}</td>
      <td style="text-align:center;"><b>{nombre_mcia}</b></td>
      <td style="text-align: right;padding-right: 5px;font-weight: {negrita_nac};">{cantidad_nacional}</td>
      <td style="text-align: right;padding-right: 5px;">{peso_nacional}</td>
      <td style="text-align: right;padding-right: 5px;font-weight: {negrita_ext};">{cantidad_extranjera}</td>
      <td style="text-align: right;padding-right: 5px;">{peso_extranjera}</td>
    </tr>
    <!-- END ROW -->
    <tr>
      <td colspan="8" style="font-size: 12px;"><b>T O T A L E S</b></td>
      <td style="text-align: right;font-size: 12px;padding-right: 5px;"><b>{tot_piezas_naci}</b></td>
      <td style="text-align: right;font-size: 12px;padding-right: 5px;"><b>{tot_peso_naci}</b></td>
      <td style="text-align: right;font-size: 12px;padding-right: 5px;"><b>{tot_piezas_ext}</b></td>
      <td style="text-align: right;font-size: 12px;padding-right: 5px;"><b>{tot_peso_ext}</b></td>
    </tr>
    <tr>
      <td colspan="12"style="font-size:12px;"><b>ENTREGADO POR</b></td>
    </tr>
    <tr>
      <td colspan="12"style="font-size:12px;"><b>RECIBIDO POR</b></td>
    </tr>
    <tr>
      <td colspan="12"style="font-size:12px;"><b>FECHA</b></td>
    </tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_packinglist">
    <tr>
      <th colspan="12"><b>ENTREGA Y CIERRE DE LA ORDEN</b></th>
    </tr>
    <tr>
      <td style="width: 3%;font-size:12px;text-align: center;">EMBALAJE</td>
      <td style="width: 7%;font-size:12px;text-align: center;">TOTAL UND</td>
      <td style="width: 10%;font-size:12px;text-align: center;">FECHA</td>
      <td style="width: 7%;font-size:12px;text-align: center;">ENTREG&Oacute;</td>
      <td style="width: 7%;font-size:12px;text-align: center;">RECIBI&Oacute;</td>
      <td style="width: 7%;font-size:12px;text-align: center;">LIBERADO</td>
      <td style="width: 7%;font-size:12px;text-align: center;">TOTAL ENTREGA</td>
      <td style="width: 7%;font-size:12px;text-align: center;">RESPONSABLE DE PRODUCCI&Oacute;N</td>
    </tr>
    <tr>
      <td style="width: 3%;font-size:12px;text-align: center;"><img src="img/acciones/embalaje.png" /></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 10%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;border-top:none;border-bottom:none;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;border-top:none;border-bottom:none;"></td>
    </tr>
    <tr>
      <td style="width: 3%;font-size:12px;text-align: center;"><img src="img/acciones/embalaje.png" /></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 10%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
    </tr>
    <tr>
      <td style="width: 3%;font-size:12px;text-align: center;"><img src="img/acciones/embalaje.png" /></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 10%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;">% RENDIMIENTO (MIN 98%)</td>
      <td style="width: 7%;font-size:12px;text-align: center;">APROBADO DT. T&Eacute;CNICA/CALIDAD</td>
    </tr>
    <tr>
      <td style="width: 3%;font-size:12px;text-align: center;"><img src="img/acciones/embalaje.png" /></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 10%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;border-top:none;border-bottom:none;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;border-top:none;border-bottom:none;"></td>
    </tr>
    <tr>
      <td style="width: 3%;font-size:12px;text-align: center;"><img src="img/acciones/embalaje.png" /></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 10%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
      <td style="width: 7%;font-size:12px;text-align: center;"></td>
    </tr>
    <tr>
      <th colspan="12"><b>OBSERVACIONES</b></th>
    </tr>
    <tr><td style="font-size:12px;height:70px;" colspan="12">{observaciones}</td></tr>
    <tr>
      <td colspan="12">
        <table width="100%">
          <tr>
            <td style="border:none;width:20%;text-align:center;"><img src="integrado/imagenes/bysoft.png" width="79" height="37" /></td>
            <td width="20%" style="border:none;"></td>
            <td style="border:none;width:20%;text-align:center;"><strong>&reg; www.bysoft.us</strong></td>
            <td width="20%" style="border:none;"></td>
            <td style="border:none;width:20%;text-align:center;"><img src="integrado/imagenes/cliente.png" width="79" height="37" /></td>
          </tr>      
        </table>
      </td>
    </tr>
  </table>
</body>
</html>