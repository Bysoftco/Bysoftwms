<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Listado Mercanc&iacute;as Rechazadas</title>
  <script type="text/javascript">
    print();
  </script>
  <style type="text/css">
    #tabla_listaRechazada {
      border: 1px solid #000;
      border-radius: 6px;
      -webkit-border-radius: 6px;
      -moz-border-radius: 6px;
    }
    #tabla_listaRechazada td, #tabla_listaRechazada th {
      padding: 5px;
    }
    #tabla_listaRechazada thead {
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
    #tabla_listaRechazada th {
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
    #tabla_listaRechazada td {
      line-height: 10px;
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 10px;
      border-left: 1px solid #000;
      border-bottom: 1px solid #000;
    }
    #tabla_listaRechazada td:hover {
      background-color: #fff;
    }      
  </style>
</head>
<body>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_listaRechazada">
    <tr>
      <th><b>No.</b></th>
      <th><b>Cliente</b></th>
      <th><b>Orden</b></th>
      <th><b>Documento TTE</b></th>
      <th><b>Referencia</b></th>
      <th><b>Nombre Referencia</b></th>
      <th><b>M/L/C</b></th>
      <th><b>Fecha Rechazo</b></th>
      <th><b>Ubicación</b></th>
      <th><b>Tipo Rechazo</b></th>
      <th><b>Piezas Nal.</b></th>
      <th><b>Peso Nal.</b></th>
      <th><b>Piezas Ext.</b></th>
      <th><b>Peso Ext.</b></th>
    </tr>
    <!-- BEGIN ROW -->
    <tr>
      <td style="text-align: center;">{n}</td>
      <td>[{doc_cliente}] {nombre_cliente}</td>
      <td style="text-align: center;">{orden}</td>
      <td style="text-align: center;">{doc_transporte}</td>
      <td style="text-align: center;">{codigo_referencia}</td>
      <td>{nombre_referencia}</td>
      <td style="text-align: center;">{modelo}</td>
      <td style="text-align: center;">{fecha_rechazo}</td>
      <td style="text-align: center;">{nombre_ubicacion}</td>
      <td style="text-align: center;">{tipo_rechazo}</td>
      <td style="text-align: right;">{piezas_nal}</td>
      <td style="text-align: right;">{peso_nal}</td>
      <td style="text-align: right;">{piezas_ext}</td>
      <td style="text-align: right;">{peso_ext}</td>
    </tr>
    {total_piezas_nal}{total_peso_nal}
    {total_piezas_ext}{total_peso_ext}
    <!-- END ROW -->
    <tr>
      <td colspan="10" style="font-size: 12px;"><b>T O T A L E S</b></td>
      <td style="font-size: 12px; text-align: right;"><b>{total_piezas_nal}</b></td>
      <td style="font-size: 12px; text-align: right;"><b>{total_peso_nal}</b></td>
      <td style="font-size: 12px; text-align: right;"><b>{total_piezas_ext}</b></td>
      <td style="font-size: 12px; text-align: right;"><b>{total_peso_ext}</b></td>
    </tr>
    <tr>
      <td colspan="13">
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