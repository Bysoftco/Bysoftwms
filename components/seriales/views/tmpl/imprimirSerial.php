<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Imprimir Serial</title>
  <script type="text/javascript">
    print();
  </script>
  <style type="text/css">
    #tabla_impserial {
      border: 1px solid #000;
      border-radius: 6px;
      -webkit-border-radius: 6px;
      -moz-border-radius: 6px;
    }
    #tabla_impserial td, #tabla_impserial th {
      padding: 5px;
    }
    #tabla_impserial thead {
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
    #tabla_impserial th {
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
    #tabla_impserial td {
      line-height: 5px;
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 10px;
      border-left: 1px solid #000;
      border-bottom: 1px solid #000;
    }
    #tabla_impserial td:hover {
      background-color: #fff;
    }
    #serialc {
      width: 300px ;
      margin-left: auto ;
      margin-right: auto ;
    }    
  </style>
</head>
<body>
  <div id="serialc">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_impserial">
      <tr>
        <td style="text-align: center;padding: 2.5px;">{doc_tte} :: {ubicacion}<br /><br />
          <img src="components/seriales/views/tmpl/generar.php?serial={serial}" />
        </td>
      </tr>
    </table>
  </div>
</body>
</html>