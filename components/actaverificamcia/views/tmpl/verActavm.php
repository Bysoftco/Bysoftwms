{COMODIN}
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <style type="text/css">
    #tabla_vacta {
      border: 1px solid #000;
      border-radius: 6px;
      -webkit-border-radius: 6px;
      -moz-border-radius: 6px;
      color: black; //Color de la Fuente
    }
    #tabla_vacta td, #tabla_vacta th {
      padding: 5px;
    }
    #tabla_vacta thead {
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
    #tabla_vacta th {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 14px;
      line-height: 20px;
      font-style: normal;
      font-weight: normal;
      color: white;
      text-align: center;
      text-shadow: black 1px 1px 1px;
      border-top: 1px solid #000;
      border-left: 1px solid #000;
      border-bottom: 1px solid #000;
      //background-color: #A4A4A4;
      background-color: #34495E;
    }
    #tabla_vacta td {
      line-height: 14px;
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 12px;
      border-left: 1px solid #000;
      border-bottom: 1px solid #000;
    }
    #tabla_vacta td:hover {
      background-color: #fff;
    }
    /* Elimina los bordes del input */
    #tabla_vacta input {
      background-color:transparent;
      border: 0px solid;
      height:14px;
      width:100px;
    }
    #tabla_vacta input:focus {
      outline:none;
    }
  </style>
</head>
<body>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
    <tr>
      <td rowspan="3" style="border:none;width:10%;text-align:center;">
        <img src="integrado/imagenes/cliente.png" width="79" height="37" />
      </td>
      <td rowspan="3" style="text-align:center;border-bottom:none;font-size:12px;">
        <b>ACTA DE VERIFICACION DE MERCANCIAS E INSPECCION UNIDADES DE CARGA</b>
      </td>
      <td style="width:13%;">Fecha Actualizacion</td>
      <td><input type="text" name="fecha_do" id="fecha_do" value="{fecha_do}" /></td>
      <td rowspan="3" style="width:10%;text-align:center;border-bottom:none;">
        <img src="integrado/imagenes/bysoft.png" width="79" height="37" />
      </td>
    <tr><td>Version</td><td><input type="text" name="version" id="version" value="3" /></td></tr>
    <tr><td colspan="2" style="border-bottom:none;text-align:center;">HOJA 1 DE 2</td></tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
    <tr>
      <td style="width:15%;"><b>DOC.SOPORTE:</b></td>
      <td style="width:17%;">{doc_tte}</td>
      <td style="width:7%;"><b>INGRESO:</b></td>
      <td style="width:15%;">{arribo}</td>
      <td style="width:7%;"><b>SALIDA:</b></td>
      <td style="width:39%;"><input type="text" name="fsalida" id="fsalida" value="{salida}" /></td>
    </tr>
    <tr>
      <td style="width:15%;"><b>FECHA VERIFICACION:</b></td>
      <td style="width:17%;"><input type="text" name="fverifica" id="fverifica" value="{salida}" /></td>
      <td style="width:7%;"><b>ORDEN:</b></td>
      <td style="width:15%;">{orden}</td>
      <td style="width:7%;"><b>CLIENTE:</b></td>
      <td style="width:39%;">{cliente}</td>
    </tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
    <tr>
      <th><b>No.</b></th>
      <th><b>DESCRIPCIÓN DE LA MERCANCIA</b></th>
      <th><b>CANT.</b></th>
      <th><b>EMBALAJE</b></th>
      <th><b>NOVEDAD</b></th>
    </tr>
    <tr>
      <td style="text-align:center;"><b>1</b></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td style="text-align:center;"><b>2</b></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td style="text-align:center;"><b>3</b></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td style="text-align:center;"><b>4</b></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td style="text-align:center;"><b>5</b></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td style="text-align:center;"><b>*</b></td>
      <td colspan="5" style="text-align:center;"><b>CONTINUAR AL RESPALDO</b></td>
    </tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
    <tr>
      <th><b>No.</b></th>
      <th><b>COMENTARIO A LA CARGA</b></th>
      <th><b>N/A</b></th>
      <th><b>SI</b></th>
      <th><b>NO</b></th>
      <th style="width:45%"><b>OBSERVACIONES</b></th>
    </tr>
    <tr>
      
    <td style="text-align:center;"><b>1</b></td>
      
    <td>CARGA DEBIDAMENTE EMBALADA?</td>
      <td></td>
      <td style="text-align:center;"><b>{ccarga1_si}</b></td>
      <td style="text-align:center;"><b>{ccarga1_no}</b></td>
      <td></td>
    </tr>
    <tr>
      <td style="text-align:center;"><b>2</b></td>
      <td>SE EVIDENCIA HUMEDAD O DERRAMES?</td>
      <td></td>
      <td style="text-align:center;"><b>{ccarga2_si}</b></td>
      <td style="text-align:center;"><b>{ccarga2_no}</b></td>
      <td></td>
    </tr>
    <tr>
      <td style="text-align:center;"><b>3</b></td>
      <td>PRESENTA RASGADURAS, ENMENDADURAS O AVERIAS?</td>
      <td></td>
      <td style="text-align:center;"><b>{ccarga3_si}</b></td>
      <td style="text-align:center;"><b>{ccarga3_no}</b></td>
      <td></td>
    </tr>
    <tr>
      <td style="text-align:center;"><b>4</b></td>
      <td>CARGA DEBIDAMENTE MARCADA Y/O ETIQUETADA?</td>
      <td></td>
      <td style="text-align:center;"><b>{ccarga4_si}</b></td>
      <td style="text-align:center;"><b>{ccarga4_no}</b></td>
      <td></td>
    </tr>
    <tr>
      <td style="text-align:center;"><b>5</b></td>
      <td>PRESENTA DEFORMACIONES?</td>
      <td></td>
      <td style="text-align:center;"><b>{ccarga5_si}</b></td>
      <td style="text-align:center;"><b>{ccarga5_no}</b></td>
      <td></td>
    </tr>
    <tr>
      <td style="text-align:center;"><b>6</b></td>
      <td>CANTIDADES ACORDES A DOCUMENTOS?</td>
      <td></td>
      <td style="text-align:center;"><b>{ccarga6_si}</b></td>
      <td style="text-align:center;"><b>{ccarga6_no}</b></td>
      <td></td>
    </tr>
    <tr>
      <td style="text-align:center;"><b>7</b></td>
      <td>OTRA INCONSISTENCIA O CUIDADO ESPECIAL?¿CUÁL?</td>
      
      
      
      
    </tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
    <th><b>INSPECCION DE UNIDAD DE CARGA</b></th>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
    <tr>
      <td style="width:17%;"><b>NOMBRE DEL CONDUCTOR:</b></td>
      <td style="width:37%;">{conductor}</td>
      <td style="width:7%;"><b>C.C.:</b></td>
      <td style="width:15%;">{cc}</td>
      <td style="width:7%;"><b>PLACA:</b></td>
      <td style="width:17%;"><input type="text" name="placa" id="placa" value="{placa}" /></td>
    </tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
    <tr>
      <td style="width:10%;"><b>PRECINTO No.:</b></td>
      <td style="width:50%;"></td>
      <td style="width:25%;"><b>ACORDE A DOCUMENTO SOPORTE:&nbsp;&nbsp;&nbsp;SI</b></td>
      <td style="width:5%;"></td>
      <td style="width:5%;"><b>NO</b></td>
      <td style="width:5%;"></td>
    </tr>
  </table>
  <div style="float:left;width:50%;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
      <tr>
        <th><b>APLICA</b></th>
        <td></td>
        <th><b>NO APLICA</b></th>
        <td></td>
      </tr>
    </table>
  </div>
  <div style="float:right;width:50%;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
      <tr>
        <th><b>APLICA</b></th>
        <td></td>
        <th><b>NO APLICA</b></th>
        <td></td>
      </tr>
    </table>
  </div>
  <div style="float:left;width:50%;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
      <tr>
        <th><b>INSPECCION DE FURGÓN O REMOLQUE</b></th>
        <th><b>1</b></th>
        <th><b>2</b></th>
        <th><b>3</b></th>
        <th><b>4</b></th>
      </tr>
      <tr>
        <td><b>1.</b> PARED DELANTERA</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>2.</b> LADO IZQUIERDO</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>3.</b> LADO DERECHO</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>4.</b> PISO</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>5.</b> TECHO INTERIOR / EXTERIOR</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>6.</b> PUERTAS INTERIORES / EXTERIORES / MECANISMO DE CIERRE</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>7.</b> EXTERIOR/SECCIÓN INFERIOR</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>8.</b> AREA DE LA QUINTA RUEDA - INSPECCIONAR EL COMPARTIMIENTO NATURAL / PLACA DEL PATIN</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>9.</b> PATA MECANICA</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>10.</b> PARTE INFERIOR - LLANTAS PARACHOQUES</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </table>
  </div>
  <div style="float:right;width:50%;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
      <tr>
        <th><b>INSPECCION DEL CAMION O TRACTOR</b></th>
        <th><b>1</b></th>
        <th><b>2</b></th>
        <th><b>3</b></th>
        <th><b>4</b></th>
      </tr>
      <tr>
        <td><b>1.</b> PARACHOQUES, NEUMATICOS Y LLANTAS</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>2.</b> PUERTAS Y COMPARTIMIENTOS DE HERRAMIENTAS</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>3.</b> CAJA DE LA BATERIA</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>4.</b> RESPIRADEROS</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>5.</b> TANQUES DE COMBUSTIBLES</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>6.</b> COMPARTIMIENTOS DEL INTERIOR DE LA CABINA - LITERA</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>7.</b> SECCION DE PASAJEROS Y TECHO</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td colspan="5" style="height:28px;">OBSERVACIONES</td>
      </tr>
      <tr>
        <td colspan="5" style="height:14px;"></td>
      </tr>
      <tr>
        <td colspan="5" style="height:14px;"></td>
      </tr>
    </table>
  </div>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
    <tr>
      <td colspan="13" style="border-bottom:none;"><b>CONVENCIONES:</b></td>
    </tr>
    <tr>
      <td style="border-bottom:none;"></td>
      <td style="text-align:center;border-top: 1px solid #000;"><b>1</b></td>
      <td style="text-align:center;border-top: 1px solid #000;">MAL ESTADO</td>
      <td style="border-bottom:none;"></td>
      <td style="text-align:center;border-top: 1px solid #000;"><b>2</b></td>
      <td style="text-align:center;border-top: 1px solid #000;">REGULAR</td>
      <td style="border-bottom:none;"></td>
      <td style="text-align:center;border-top: 1px solid #000;"><b>3</b></td>
      <td style="text-align:center;border-top: 1px solid #000;">BUENO</td>
      <td style="border-bottom:none;"></td>
      <td style="text-align:center;border-top: 1px solid #000;"><b>4</b></td>
      <td style="text-align:center;border-top: 1px solid #000;">EXCELENTE</td>
      <td style="border-bottom:none;"></td>
    </tr>
    <tr><td colspan="13" style="border-top:none;"></td></tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
    <tr>
      <td style="width:23%;"><b>INSPECCION DE CONTENEDOR No.:</b></td>
      <td style="width:27%;"></td>
      <td style="width:10%;"><b>CAPACIDAD:</b></td>
      <td style="width:15%;"></td>
      <td style="width:7%;"><b>APLICA</b></td>
      <td style="width:5%;"></td>
      <td style="width:8%;"><b>NO APLICA</b></td>
      <td style="width:5%;"></td>
    </tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
    <tr>
      <td style="width:10%;"><b>PRECINTO No.:</b></td>
      <td style="width:50%;"></td>
      <td style="width:25%;"><b>ACORDE A DOCUMENTO SOPORTE:&nbsp;&nbsp;&nbsp;SI</b></td>
      <td style="width:5%;"></td>
      <td style="width:5%;"><b>NO</b></td>
      <td style="width:5%;"></td>
    </tr>
  </table>
  <div style="float:left;width:50%;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
      <tr>
        <th><b>INSPECCION DE CONTENEDOR EXTERNO</b></th>
        <th><b>1</b></th>
        <th><b>2</b></th>
        <th><b>3</b></th>
        <th><b>4</b></th>
      </tr>
      <tr>
        <td><b>1.</b> FRENTE</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>2.</b> COSTADO IZQUIERDO</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>3.</b> COSTADO DERECHO</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>4.</b> BASE TECHO</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>5.</b> PUERTAS</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>6.</b> SEGUROS</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>7.</b> DADOS</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </table>
  </div>
  <div style="float:right;width:50%;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
      <tr>
        <th><b>INSPECCION DE CONTENEDOR INTERNO</b></th>
        <th><b>1</b></th>
        <th><b>2</b></th>
        <th><b>3</b></th>
        <th><b>4</b></th>
      </tr>
      <tr>
        <td><b>1.</b> PARED FONDO</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>2.</b> PARED IZQUIERDA</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>3.</b> PARED DERECHA</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>4.</b> PISO</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>5.</b> TECHO</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>6.</b> PUERTAS</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>7.</b> TUERCAS REMACHES</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </table>
  </div>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
    <tr>
      <td colspan="13" style="border-bottom:none;"><b>CONVENCIONES:</b></td>
    </tr>
    <tr>
      <td style="border-bottom:none;"></td>
      <td style="text-align:center;border-top: 1px solid #000;"><b>1</b></td>
      <td style="text-align:center;border-top: 1px solid #000;">MAL ESTADO</td>
      <td style="border-bottom:none;"></td>
      <td style="text-align:center;border-top: 1px solid #000;"><b>2</b></td>
      <td style="text-align:center;border-top: 1px solid #000;">REGULAR</td>
      <td style="border-bottom:none;"></td>
      <td style="text-align:center;border-top: 1px solid #000;"><b>3</b></td>
      <td style="text-align:center;border-top: 1px solid #000;">BUENO</td>
      <td style="border-bottom:none;"></td>
      <td style="text-align:center;border-top: 1px solid #000;"><b>4</b></td>
      <td style="text-align:center;border-top: 1px solid #000;">EXCELENTE</td>
      <td style="border-bottom:none;"></td>
    </tr>
    <tr><td colspan="13" style="border-top:none;"></td></tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
    <tr>
      <td style="border-bottom:none;width:10%;"><b>OBSERVACIONES:</b></td>
      <td style="width:90%;border-left: none;"></td>
    </tr>
    <tr><td colspan="2" style="height:14px;"></td></tr>
    <tr><td colspan="2" style="height:14px;"></td></tr>
    <tr><td colspan="2" style="height:14px;"></td></tr>
  </table>
  <div style="float:left;width:50%;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
      <tr>
        <td colspan="5"style="border-bottom:none;"><b>FUNCIONARIO RESPONSABLE DE LA VERIFICACION:</b></td>
      </tr>
      <tr>
        <td colspan="5" style="height:14px;border-bottom:none;"></td>
      </tr>
      <tr>
        <td style="width:5%;height:14px;border-top:none;border-bottom:none;"></td>
        <td style="width:20%;height:14px;border-top: 1px solid #000;border-left:none;border-bottom:none;"></td>
        <td style="width:5%;height:14px;border-top:none;border-left:none;border-bottom:none;"></td>
        <td style="width:10%;height:14px;border-top:none;border-left:none;border-bottom:none;"></td>
        <td style="width:10%;height:14px;border-left:none;border-bottom:none;"></td>
      </tr>
      <tr>
        <td colspan="5" style="height:14px;border-bottom:none;"></td>
      </tr>
      <tr style="height:14px;">
        <td style="width:5%;height:14px;"></td>
        <td style="width:20%;height:14px;border-top: 1px solid #000;border-left:none;text-align:center;">NOMBRE Y CEDULA</td>
        <td style="width:5%;height:14px;border-left:none;"></td>
        <td style="width:15%;height:14px;border-top: 1px solid #000;border-left:none;text-align:center;">FIRMA</td>
        <td style="width:5%;height:14px;border-left:none;"></td>
      </tr>
    </table>
  </div>
  <div style="float:right;width:50%;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vacta">
      <tr>
        <td colspan="5" style="border-bottom:none;"><b>CONDUCTOR:</b></td>
      </tr>
      <tr>
        <td colspan="5" style="height:14px;border-bottom:none;"></td>
      </tr>
      <tr>
        <td style="width:5%;height:14px;border-top:none;border-bottom:none;"></td>
        <td style="width:20%;height:14px;border-top: 1px solid #000;border-left:none;border-bottom:none;"></td>
        <td style="width:5%;height:14px;border-top:none;border-left:none;border-bottom:none;"></td>
        <td style="width:10%;height:14px;border-top:none;border-left:none;border-bottom:none;"></td>
        <td style="width:10%;height:14px;border-left:none;border-bottom:none;"></td>
      </tr>
      <tr>
        <td colspan="5" style="height:14px;border-bottom:none;"></td>
      </tr>
      <tr style="height:14px;">
        <td style="width:5%;height:14px;"></td>
        <td style="width:20%;height:14px;border-top: 1px solid #000;border-left:none;text-align:center;">NOMBRE Y CEDULA</td>
        <td style="width:5%;height:14px;border-left:none;"></td>
        <td style="width:15%;height:14px;border-top: 1px solid #000;border-left:none;text-align:center;">FIRMA</td>
        <td style="width:5%;height:14px;border-left:none;"></td>
      </tr>
    </table>
  </div><div style="width:100%;height:135px;"></div>
  <div style="text-align:center;width:100%;">
    <button style="font-family: sans-serif;font-size: 12px;" class="submit" type="submit" id="btnActa" >Imprimir</button>
  </div><br />
  <input type="hidden" name="doctte" id="doctte" value="{doc_tte}" />
  <input type="hidden" name="arribo" id="arribo" value="{arribo}" />
  <input type="hidden" name="orden" id="orden" value="{orden}" />
  <input type="hidden" name="clientex" id="clientex" value="{cliente}" />
  <input type="hidden" name="conductor" id="conductor" value="{conductor}" />
  <input type="hidden" name="cc" id="cc" value="{cc}" />
  <input type="hidden" name="placa" id="placa" value="{placa}" />
  <input type="hidden" name="ccarga1_si" id="ccarga1_si" value="{ccarga1_si}" />
  <input type="hidden" name="ccarga1_no" id="ccarga1_no" value="{ccarga1_no}" />
  <input type="hidden" name="ccarga2_si" id="ccarga2_si" value="{ccarga2_si}" />
  <input type="hidden" name="ccarga2_no" id="ccarga2_no" value="{ccarga2_no}" />
  <input type="hidden" name="ccarga3_si" id="ccarga3_si" value="{ccarga3_si}" />
  <input type="hidden" name="ccarga3_no" id="ccarga3_no" value="{ccarga3_no}" />
  <input type="hidden" name="ccarga4_si" id="ccarga4_si" value="{ccarga4_si}" />
  <input type="hidden" name="ccarga4_no" id="ccarga4_no" value="{ccarga4_no}" />
  <input type="hidden" name="ccarga5_si" id="ccarga5_si" value="{ccarga5_si}" />
  <input type="hidden" name="ccarga5_no" id="ccarga5_no" value="{ccarga5_no}" />
  <input type="hidden" name="ccarga6_si" id="ccarga6_si" value="{ccarga6_si}" />
  <input type="hidden" name="ccarga6_no" id="ccarga6_no" value="{ccarga6_no}" />  
  <script>
    $("#btnActa").button({
      text: true,
      icons: {
        primary: "ui-icon-print"
      }
    })
    .click(function() {
      window.open("index_blank.php?component=actaverificamcia&method=imprimirActa&fecham="
        +$("#fecha_do").attr("value")+"&version="+$("#version").attr("value")+"&doctte="
        +$("#doctte").attr("value")+"&arribo="+$("#arribo").attr("value")+"&salida="
        +$("#fsalida").attr("value")+"&orden="+$("#orden").attr("value")+"&cliente="
        +$("#clientex").attr("value")+"&conductor="+$("#conductor").attr("value")
        +"&idcc="+$("#cc").attr("value")+"&placa="+$("#placa").attr("value")
        +"&ccarga1_si="+$("#ccarga1_si").attr("value")+"&ccarga1_no="+$("#ccarga1_no").attr("value")
        +"&ccarga2_si="+$("#ccarga2_si").attr("value")+"&ccarga2_no="+$("#ccarga2_no").attr("value")
        +"&ccarga3_si="+$("#ccarga3_si").attr("value")+"&ccarga3_no="+$("#ccarga3_no").attr("value")
        +"&ccarga4_si="+$("#ccarga4_si").attr("value")+"&ccarga4_no="+$("#ccarga4_no").attr("value")
        +"&ccarga5_si="+$("#ccarga5_si").attr("value")+"&ccarga5_no="+$("#ccarga5_no").attr("value")
        +"&ccarga6_si="+$("#ccarga6_si").attr("value")+"&ccarga6_no="+$("#ccarga6_no").attr("value"));
      return false;
    });
  </script>
</body>
</html>