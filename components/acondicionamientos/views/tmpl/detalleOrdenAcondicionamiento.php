<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <title>Orden Acondicionamiento No. {codigo_operacion}</title>
  <script type="text/javascript">
    print();
  </script>
  <style type="text/css">
    @media print {
      table {page-break-inside: avoid;}
      @page {margin: 0.55cm;}
    }
    #tabla_ordenacondicionar {
      border: 1px solid #000;
      border-radius: 6px;
      -webkit-border-radius: 6px;
      -moz-border-radius: 6px;
    }
    #tabla_ordenacondicionar td, #tabla_packinglist th {
      padding: 5px;
    }
    #tabla_ordenacondicionar thead {
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
    #tabla_ordenacondicionar th {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 12px; /* 14px */
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
    #tabla_ordenacondicionar td {
      line-height: 11px;
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 9px;
      border-left: 1px solid #000;
      border-bottom: 1px solid #000;
    }
    #tabla_packinglist td:hover {
      background-color: #fff;
    }
    .saltarpagina { page-break-before: always; } /* page-break-after también funciona */
		#num_pagina:after { 
			counter-increment: page;
			content: counter(page)
		}
		#tot_pagina:after { 
			counter-increment: pages;
			content: counter(pages)
		}
  </style>
</head>
<body>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_ordenacondicionar">
    <tr>
      <td rowspan="5" style="border:none;width:20%;text-align:center;">
        <img src="integrado/imagenes/cliente.png" width="79" height="37" />
      </td>
    </tr>
    <tr>
      <td colspan="5" style="text-align:center;border-bottom:none;font-size:14px;">
        <b>ORDEN DE ACONDICIONAMIENTO No. {codigo_operacion}</b>
      </td>
      <td rowspan="5" style="width:20%;text-align:center;">
        <img src="integrado/imagenes/bysoft.png" width="79" height="37" />
      </td>
    </tr>
    <tr>
      <th><b>C&oacute;digo</b></th>
      <th><b>Versi&oacute;n</b></th>
      <th colspan="3"><b>P&aacute;gina</b></th>
    </tr>
    <tr>
      <td style="text-align:center;">{codigo_reporte}</td>
      <td></td>
      <td style="text-align:center;"><span>1</span></td>
      <td style="text-align:center;">de</td>
      <td style="text-align:center;"><span>2</span></td>
    </tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_ordenacondicionar">
    <tr>
      <th colspan="8"><b>INFORMACI&Oacute;N GENERAL</b></th>
    </tr>
    <tr>
      <td style="width: 12%;" class="tituloForm">No. Orden</td>
      <td style="width: 13%"><b>{orden}</b></td>
      <td class="tituloForm" style="width: 12%">Fecha de Operaci&oacute;n</td>
      <td style="width: 13%">{fecha}</td>
      <td class="tituloForm" style="width: 12%">Pedido</td>
      <td style="width: 13%"><b>{pedido}</b></td>
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
      <td class="tituloForm" style="width: 12%">Registro INVIMA</td>
      <td style="width: 13%">{reginvima}</td>
      <td class="tituloForm" style="width: 12%">Tipo de Mercancia</td>
      <td style="width: 13%">{nombre_tipo_mercancia}</td>
      <td class="tituloForm" style="width: 12%">Cantidad</td>
      <td style="width: 13%">{cantidad}</td>
      <td class="tituloForm" style="width: 8%">Cantidad Nacional</td>
      <td style="width: 1%">{cantidad_nac}</td>
    </tr>
    <tr>
      <td class="tituloForm" style="width: 12%">Cantidad Extranjera</td>
      <td style="width: 13%">{cantidad_ext}</td>
      <td class="tituloForm" style="width: 12%">Peso</td>
      <td style="width: 13%">{peso}</td>
      <td class="tituloForm" style="width: 12%">Valor</td>
      <td colspan="3" style="width: 13%">{valor}</td>
    </tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_ordenacondicionar">
    <tr>
      <th colspan="13"><b>PROCESOS A REALIZAR</b></th>
    </tr>
    <tr>
      <td style="width: 7%;font-size:10px;" class="tituloForm"><b>PROCESO 1</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:10px;">Colocar Sticker 1</td>
      <td style="width: 7%;font-size:10px;" class="tituloForm"><b>PROCESO 2</b></td>
      <td style="width: 3%;border-left:none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:10px;">Colocar Sticker 2</td>
      <td style="width: 7%;font-size:10px;" class="tituloForm"><b>PROCESO 3</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:10px;">Retirar Etiqueta</td>
      <td style="width: 7%;font-size:10px;" class="tituloForm"><b>PROCESO 4</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:10px;">Colocar Etiqueta</td>
    </tr>
    <tr>
      <td style="width: 7%;font-size:10px;" class="tituloForm"><b>PROCESO 5</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:10px;">Desestuchar</td>
      <td style="width: 7%;font-size:10px;" class="tituloForm"><b>PROCESO 6</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:10px;">Estuchar</td>
      <td style="width: 7%;font-size:10px;" class="tituloForm"><b>PROCESO 7</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:10px;">Colocar Inserto</td>
      <td style="width: 7%;font-size:10px;" class="tituloForm"><b>PROCESO 8</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:10px;">Sello Seguridad</td>
    </tr>
    <tr>
      <td style="width: 7%;font-size:10px;" class="tituloForm"><b>PROCESO 9</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:10px;" colspan="4"></td>
      <td style="width: 7%;font-size:10px;" class="tituloForm"><b>PROCESO 10</b></td>
      <td style="width: 3%;border-left: none;"><img src="img/acciones/checkbox.jpg" /></td>
      <td class="tituloForm" style="width: 12%;font-size:10px;" colspan="4"></td>
    </tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_ordenacondicionar">
    <tr>
      <th><b>Orden</b></th>
      <th><b>Doc.TTE</b></th>
      <th><b>Referencia</b></th>
      <th><b>Nombre Referencia</b></th>
      <th><b>Fecha Exp.</b></th>
      <th><b>M/L/C</b></th>
      <th><b>Fecha Ing.</b></th>
      <th><b>Ubicaci&oacute;n</b></th>
      <th><b>Acondicionadas</b></th>
      <th><b>Rechazadas</b></th>
      <th><b>Devueltas</b></th>
    </tr>
    <!-- BEGIN ROW -->
    <tr>
      <td style="text-align:center;">{orden_detalle}</td>
      <td style="text-align:center;">{doc_tte}</td>
      <td style="text-align: center;">
        <img src="components/acondicionamientos/views/tmpl/generar.php?serial={codigo_referen}" />
      </td>
      <td style="padding-left: 5px;">{nombre_referencia}</td>
      <td style="text-align:center;color: red;">{fecha_expira}</td>
      <td style="text-align:center;">{modelo}</td>
      <td style="text-align:center;">{fecha_detalle}</td>
      <td style="text-align:center;">{nombre_ubicacion}</td>
      <td style="text-align: right;padding-right: 5px;">{acondicionadas}</td>
      <td style="text-align: right;padding-right: 5px;">{rechazadas}</td>
      <td style="text-align: right;padding-right: 5px;">{devueltas}</td>
    </tr>
    <!-- END ROW -->
    <tr>
      <td colspan="8" style="font-size: 10px;"><b>T O T A L E S</b></td>
      <td style="text-align: right;font-size: 10px;padding-right: 5px;"><b>{tot_acondicionadas}</b></td>
      <td style="text-align: right;font-size: 10px;padding-right: 5px;"><b>{tot_rechazadas}</b></td>
      <td style="text-align: right;font-size: 10px;padding-right: 5px;"><b>{tot_devueltas}</b></td>
    </tr>
    <tr>
      <td colspan="2"style="font-size:10px;"><b>RECIBIDO POR</b></td>
      <td colspan="4"></td>
      <td colspan="3"style="font-size:10px;"><b>% RENDIMIENTO (MIN 98%)</b></td>
      <td colspan="4"></td>
    </tr>
    <tr>
      <td colspan="2"style="font-size:10px;"><b>RESPONSABLE DE PRODUCCI&Oacute;N</b></td>
      <td colspan="4"></td>
      <td colspan="3"style="font-size:10px;"><b>APROBADO DT. T&Eacute;CNICA/CALIDAD</b></td>
      <td colspan="4"></td>
    </tr>
    <tr>
      <th colspan="13"><b>OBSERVACIONES</b></th>
    </tr>
    <tr><td colspan="13" style="font-size:10px;height:70px;">{observaciones}</td></tr>
  </table>
  <div class="saltarpagina"> </div>
	<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_ordenacondicionar">
    <tr>
      <td rowspan="5" style="border:none;width:20%;text-align:center;">
        <img src="integrado/imagenes/cliente.png" width="79" height="37" />
      </td>
    </tr>
    <tr>
      <td colspan="5" style="text-align:center;border-bottom:none;font-size:14px;">
        <b>ORDEN DE ACONDICIONAMIENTO No. {codigo_operacion}</b>
      </td>
      <td rowspan="5" style="width:20%;text-align:center;">
        <img src="integrado/imagenes/bysoft.png" width="79" height="37" />
      </td>
    </tr>
    <tr>
      <th><b>C&oacute;digo</b></th>
      <th><b>Versi&oacute;n</b></th>
      <th colspan="3"><b>P&aacute;gina</b></th>
    </tr>
    <tr>
      <td style="text-align:center;">{codigo_reporte}</td>
      <td></td>
      <td style="text-align:center;"><span>2</span></td>
      <td style="text-align:center;">de</td>
      <td style="text-align:center;"><span>2</span></td>
    </tr>
  </table>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_ordenacondicionar">
    <tr>
      <th colspan="8"><b>INFORMACI&Oacute;N GENERAL</b></th>
    </tr>
    <tr>
      <td style="width: 12%;" class="tituloForm">No. Orden</td>
      <td style="width: 13%"><b>{orden}</b></td>
      <td class="tituloForm" style="width: 12%">Fecha de Operaci&oacute;n</td>
      <td style="width: 13%">{fecha}</td>
      <td class="tituloForm" style="width: 12%">Pedido</td>
      <td style="width: 13%"><b>{pedido}</b></td>
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
      <td class="tituloForm" style="width: 12%">Registro INVIMA</td>
      <td style="width: 13%">{reginvima}</td>
      <td class="tituloForm" style="width: 12%">Tipo de Mercancia</td>
      <td style="width: 13%">{nombre_tipo_mercancia}</td>
      <td class="tituloForm" style="width: 12%">Cantidad</td>
      <td style="width: 13%">{cantidad}</td>
      <td class="tituloForm" style="width: 8%">Cantidad Nacional</td>
      <td style="width: 1%">{cantidad_nac}</td>
    </tr>
    <tr>
      <td class="tituloForm" style="width: 12%">Cantidad Extranjera</td>
      <td style="width: 13%">{cantidad_ext}</td>
      <td class="tituloForm" style="width: 12%">Peso</td>
      <td style="width: 13%">{peso}</td>
      <td class="tituloForm" style="width: 12%">Valor</td>
      <td colspan="3" style="width: 13%">{valor}</td>
    </tr>
  </table>
  <canvas id="canvas" width="1200" height="900"></canvas>
  <script>
    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext('2d');

    /**
     * Draws a rounded rectangle using the current state of the canvas. 
     * If you omit the last three params, it will draw a rectangle 
     * outline with a 5 pixel border radius 
     * @param {CanvasRenderingContext2D} ctx
     * @param {Number} x The top left x coordinate
     * @param {Number} y The top left y coordinate 
     * @param {Number} width The width of the rectangle 
     * @param {Number} height The height of the rectangle
     * @param {Number} radius The corner radius. Defaults to 5;
     * @param {Boolean} fill Whether to fill the rectangle. Defaults to false.
     * @param {Boolean} stroke Whether to stroke the rectangle. Defaults to true.
     */
    function roundRect(ctx, x, y, width, height, radius, fill, stroke) { 
      if (typeof stroke == "undefined" ) {
        stroke = true;
      }
      if (typeof radius === "undefined") {
        radius = 5;
      }
      ctx.beginPath();
      ctx.moveTo(x + radius, y);
      ctx.lineTo(x + width - radius, y);
      ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
      ctx.lineTo(x + width, y + height - radius);
      ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
      ctx.lineTo(x + radius, y + height);
      ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
      ctx.lineTo(x, y + radius);
      ctx.quadraticCurveTo(x, y, x + radius, y);
      ctx.closePath();
      if (stroke) {
        ctx.stroke();
      }
      if (fill) {
        ctx.fill();
      }
    }      
    ctx.lineWidth = 4;
    var nrect = 0;
    var registros = {nr};
    var rectWidth = 100;
		var colY = 10;
		var ne = 1;
		for(rectY=15;rectY<=(10*70);rectY=rectY+70) {
			for(rectX=10;rectX<=(7*170);rectX=rectX+170) {
				ctx.fillStyle = "#000";
				ctx.fillText("ETIQUETA-"+ne,(rectX+rectWidth)/2,colY);
				ctx.fillStyle = "#FFF";
				roundRect(ctx, rectX, rectY, 150, 50, 10, true);
				nrect++;
				if(nrect==registros) break;
				ne++;
				rectWidth+=170;
			}
			if(nrect==registros) break;
			colY+=70;rectWidth = 100;
		}
  </script>
</body>
</html>