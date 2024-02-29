<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Packing List</title>
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
      line-height: 5px;
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
      <th colspan="8"><b>LISTA DE EMPAQUE</b></th>
    </tr>
    <tr>
      <td style="width: 12%;" class="tituloForm">Nombre Cliente</td>
      <td style="width: 13%"><b>{razon_social}</b></td>
      <td class="tituloForm" style="width: 12%">Documento Cliente</td>
      <td style="width: 13%">
        {numero_documento}
        <input type="hidden" name="doc_cliente" id="doc_cliente" value="{numero_documento}" />
      </td>
      <td class="tituloForm" style="width: 12%">Fecha de Operaci&oacute;n</td>
      <td style="width: 13%">{fecha}</td>
      <td class="tituloForm" style="width: 12%">FMM</td>
      <td style="width: 13%">{fmm}</td>
    </tr>
    <tr>
      <td class="tituloForm" style="width: 12%">Tipo de Operaci&oacute;n</td>
      <td style="width: 13%">{producto}</td>
      <td class="tituloForm">Orden</td>
      <td>{orden}</td>
      <td class="tituloForm" style="width: 12%">Pedido</td>
      <td style="width: 13%">{pedido}</td>
      <td class="tituloForm" style="width: 12%">Cantidad</td>
      <td style="width: 13%">{cantidad}</td>
    </tr>
    <tr>
      <td class="tituloForm" style="width: 12%">Cantidad Nacional</td>
      <td style="width: 13%">{cantidad_nac}</td>
      <td class="tituloForm" style="width: 12%">Cantidad Extranjera</td>
      <td style="width: 13%">{cantidad_ext}</td>
      <td class="tituloForm" style="width: 12%">Observaciones</td>
      <td style="width: 13%" colspan="3"> {observaciones}</td>
    </tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_packinglist">
    <tr>
      <th><b>Orden</b></th>
      <th><b>C&oacute;digo Ref.</b></th>
      <th><b>Referencia</b></th>
      <th><b>Ubicaci&oacute;n</b></th>
      <th><b>M/L/C</b></th>
      <th><b>Piezas Nal.</b></th>
      <th><b>Peso Nal.</b></th>
      <th><b>Valor CIF</b></th>
      <th><b>Piezas Ext.</b></th>
      <th><b>Peso Ext.</b></th>
      <th><b>Valor FOB</b></th>
    </tr>
    <!-- BEGIN ROW -->
    <tr>
      <td style="padding: 2.5px;">{orden_detalle}</td>
      <td style="text-align: center;padding: 2.5px;">
        <img src="components/Alistamientos/views/tmpl/generar.php?serial={codigo_referen}" />
      </td>
      <td style="padding-left: 5px;">{nombre_referencia}</td>
      <td style="text-align:center;">{nombre_ubicacion}</td>
      <td style="text-align:center;">{modelo}</td>
      <td style="text-align: right;padding-right: 5px;">{cantidad_nacional}</td>
      <td style="text-align: right;padding-right: 5px;">{peso_nacional}</td>
      <td style="text-align: right;padding-right: 5px;">{valor_cif}</td>
      <td style="text-align: right;padding-right: 5px;">{cantidad_extranjera}</td>
      <td style="text-align: right;padding-right: 5px;">{peso_extranjera}</td>
      <td style="text-align: right;padding-right: 5px;">{valor_fob}</td>
    </tr>
    <!-- END ROW -->
    <tr>
      <td colspan="5" style="font-size: 12px;"><b>T O T A L E S</b></td>
      <td style="text-align: right;font-size: 12px;padding-right: 5px;"><b>{tot_piezas_naci}</b></td>
      <td style="text-align: right;font-size: 12px;padding-right: 5px;"><b>{tot_peso_naci}</b></td>
      <td style="text-align: right;font-size: 12px;padding-right: 5px;"><b>{tot_valor_cif}</b></td>
      <td style="text-align: right;font-size: 12px;padding-right: 5px;"><b>{tot_piezas_ext}</b></td>
      <td style="text-align: right;font-size: 12px;padding-right: 5px;"><b>{tot_peso_ext}</b></td>
      <td style="text-align: right;font-size: 12px;padding-right: 5px;"><b>{tot_valor_fob}</b></td>
    </tr>
    <tr>
      <td colspan="11">
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