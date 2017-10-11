<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Etiqueta de Alistamiento No. {codigo_operacion}</title>
  <script type="text/javascript">
    print();
  </script>
  <style type="text/css">
    #tabla_etiqueta {
      border: 1px solid #000;
      border-radius: 6px;
      -webkit-border-radius: 6px;
      -moz-border-radius: 6px;
    }
    #tabla_etiqueta td, #tabla_etiqueta th {
      padding: 5px;
    }
    #tabla_etiqueta thead {
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
    #tabla_etiqueta th {
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
    #tabla_etiqueta td {
      line-height: 11px;
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 10px;
      border-left: 1px solid #000;
      border-bottom: 1px solid #000;
    }
    #tabla_etiqueta td:hover {
      background-color: #fff;
    }      
  </style>
</head>
<body>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_etiqueta">
    <tr>
      <th colspan="4">
        Etiqueta del Alistamiento No. {codigo_operacion}
      </th>
    </tr>
    <tr>
      <td style="width: 25%" class="tituloForm">Nombre Cliente</td>
      <td style="width: 25%"><strong>{razon_social}</strong></td>
      <td class="tituloForm" style="width: 25%">Documento Cliente</td>
      <td style="width: 25%">
        {numero_documento}
        <input type="hidden" name="doc_cliente" id="doc_cliente" value="{numero_documento}" />
      </td>
    </tr>
    <tr>
      <td class="tituloForm" style="width: 25%">Fecha de Etiqueta</td>
      <td style="width: 25%">{fecha}</td>
      <td style="width: 25%" class="tituloForm">Sede</td>
      <td style="width: 25%"><strong>{nombre_sede}</strong></td>
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
      <td class="tituloForm">Empresa Transportadora</td>
      <td>{empresa}</td> 
    </tr>
    <tr>
      <td class="tituloForm">Cantidad</td>
      <td>{cantidad}</td>
      <td class="tituloForm">Cantidad Nacional</td>
      <td>{cantidad_nac}</td>
    </tr>
    <tr>
      <td class="tituloForm">Cantidad Extranjera</td>
      <td colspan="3">{cantidad_ext}</td>
    </tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_etiqueta">
    <tr>
      <th><b>Código Ref.</b></th>
      <th><b>Referencia</b></th>
      <th><b>Ubicación</b></th>
      <th><b>M/L/C</b></th>
      <th><b>Piezas Nal.</b></th>
      <th><b>Piezas Ext.</b></th>
    </tr>
    <!-- BEGIN ROW -->
    <tr>
      <td style="text-align: center;padding: 2.5px;">
        <img src="components/Alistamientos/views/tmpl/generar.php?serial={codigo_referen}" />
      </td>
      <td style="padding-left: 5px;">{nombre_referencia}</td>
      <td style="text-align:center;">{nombre_ubicacion}</td>
      <td style="text-align:center;">{modelo}</td>
      <td style="text-align: center;padding: 2.5px;">{cantidad_nacional}</td>
      <td style="text-align: center;padding: 2.5px;">{cantidad_extranjera}</td>
    </tr>
    <!-- END ROW -->
    <tr>
      <td colspan="4" style="font-size: 12px;"><b>T O T A L E S</b></td>
      <td style="text-align: center;font-size: 12px;"><b>{tot_piezas_naci}</b></td>
      <td style="text-align: center;font-size: 12px;"><b>{tot_piezas_ext}</b></td>
    </tr>
    <tr>
      <td colspan="6">
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