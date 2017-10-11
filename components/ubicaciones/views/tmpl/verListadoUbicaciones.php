<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Consulta Ubicaciones</title>
  <script type="text/javascript">
    print();
  </script>
  <style type="text/css">
    #tabla_listaUbicaciones {
      border: 1px solid #000;
      border-radius: 6px;
      -webkit-border-radius: 6px;
      -moz-border-radius: 6px;
    }
    #tabla_listaUbicaciones td, #tabla_listaUbicaciones th {
      padding: 5px;
    }
    #tabla_listaUbicaciones thead {
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
    #tabla_listaUbicaciones th {
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
    #tabla_listaUbicaciones td {
      line-height: 5px;
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 10px;
      border-left: 1px solid #000;
      border-bottom: 1px solid #000;
    }
    #tabla_listaUbicaciones td:hover {
      background-color: #fff;
    }      
  </style>
</head>
<body>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_listaUbicaciones">
    <tr>
      <th><b>Ubicaci&oacute;n</b></th>
      <th><b>Cliente</b></th>
      <th><b>Referencia</b></th>
      <th><b>Documento TTE</b></th>
      <th><b>Orden</b></th>
      <th><b>Piezas</b></th>
      <th><b>Peso</b></th>
    </tr>
    <!-- BEGIN ROW -->
    <tr>
      <td style="text-align: center;padding: 2.5px;">
        <img src="components/ubicaciones/views/tmpl/generar.php?ubicacion={nombre_ubicacion}" />
      </td>
      <td>[{doc_cliente}] {nombre_cliente}</td>
      <td>[{codigo_referencia}] {nombre_referencia}</td>
      <td style="text-align: center;">{doc_transporte}</td>
      <td style="text-align: center;">{orden}</td>
      <td style="text-align: right;">{piezas}</td>
      <td style="text-align: right;">{peso}</td>
    </tr>
    <!-- END ROW -->
    <tr>
      <td colspan="5" style="font-size: 12px;"><b>T O T A L E S</b></td>
      <td style="font-size: 12px; text-align: right;"><b>{total_piezas}</b></td>
      <td style="font-size: 12px; text-align: right;"><b>{total_peso}</b></td>
    </tr>
    <tr>
      <td colspan="14">
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