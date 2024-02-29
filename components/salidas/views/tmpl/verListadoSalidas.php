<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="./template/css/listados.css"/>
  <title>Listado Salidas de Mercancía</title>
  <script type="text/javascript">
    print();
  </script>
</head>
<body>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_listados">
    <tr>
      <th><b>No.</b></th>
      <th><b>Cliente</b></th>
      <th><b>Orden</b></th>
      <th><b>Arribo</b></th>
      <th><b>Documento TTE</b></th>
      <th><b>FMMI</b></th>
      <th><b>Código Referencia</b></th>
      <th><b>Referencia</b></th>
      <th><b>M/L/C</b></th>
      <th><b>Fecha Retiro</b></th>
      <th><b>Destino</b></th>
      <th><b>Piezas</b></th>
      <th><b>Peso</b></th>
      <th><b>Piezas Nal/Nzo</b></th>
      <th><b>Piezas Ext.</b></th>
    </tr>
    <!-- BEGIN ROW -->
    <tr>
      <td style="text-align: center;">{n}</td>
      <td>[{doc_cliente}] {nombre_cliente}</td>
      <td style="text-align: center;">{orden}</td>
      <td style="text-align: center;">{arribo}</td>
      <td>{doc_transporte}</td>
      <td style="text-align: center;">{fmmi}</td>
      <td style="text-align: center;">{codigo_referencia}</td>
      <td>{nombre_referencia}</td>
      <td>{modelo}</td>
      <td style="text-align: center;">{fecha_retiro}</td>
      <td style="text-align: center;">{destino}</td>
      <td style="text-align: right;">{piezas}</td>
      <td style="text-align: right;">{peso}</td>
      <td style="text-align: right;">{piezas_nal}</td>
      <td style="text-align: right;">{piezas_ext}</td>
    </tr>
    {total_piezas}{total_peso}{total_piezas_nal}{total_piezas_ext}
    <!-- END ROW -->
    <tr>
      <td colspan="11" style="font-size: 12px;"><b>T O T A L E S</b></td>
      <td style="font-size: 12px; text-align: right;"><b>{total_piezas}</b></td>
      <td style="font-size: 12px; text-align: right;"><b>{total_peso}</b></td>
      <td style="font-size: 12px; text-align: right;"><b>{total_piezas_nal}</b></td>
      <td style="font-size: 12px; text-align: right;"><b>{total_piezas_ext}</b></td>
    </tr>
    <tr>
      <td colspan="16">
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