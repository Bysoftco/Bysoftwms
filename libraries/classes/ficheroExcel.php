<?php   
echo'<pre>';var_dump($_POST['datos_a_enviar']);echo'</pre>';

//header("Pragma: ");
header("Pragma: public");

header('Cache-control: ');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);


header ("Pragma: no-cache"); 
header("Expires: 0"); 
header("Content-type: application/force-download");
header('Content-Type: application/vnd.ms-excel;');                 // This should work for IE & Opera 
header("Content-type: application/x-msexcel");                    // This should work for the rest
header("Content-disposition: attachment; filename=Reporte.xls; charset=iso-8859-1");
header("Content-Transfer-Encoding: binary");

ob_clean();
flush();


?>


<?php
$header = '
<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
<style type="text/css">
body {
	padding: 0;
	margin: 0;
	font-family: Verdana;
	font-size: 12px;
	color: #333333;
	background-image: url(../../img/img_fondo_plantilla.png);
	background-color: #fdf9de;
	margin: 0px 0px;
	background-repeat: repeat-x;
	text-decoration: none;
	overflow: auto;
}

.font_login {
	text-align: right;
	font-weight: bold;
	width: 180px;
}

.copyright {
	font-family: verdana;
	font-size: 12px;
	color: #A4A4A4;
}

.fondo_login {
	background-color: #ffffff;
}

.table_login {
	background-color: #ffffff;
	border-width: 1px;
	border-style: outset;
	border-color: #cccccc;
	padding: 0px 2px 2px 10px;
}

#div_login {
	height: 150px;
}

div.centraTabla {
	text-align: center;
}

div.centraTabla table {
	margin: 0 auto;
	text-align: left;
}

input.login {
	border: 1px solid #FDB913;
	font-size: 14px;
	width: 150px;
	height: 20px;
}

input.login:FOCUS {
	background-color: #fdf9de;
	border: 1px solid #FDB913;
	font-size: 14px;
	width: 150px;
	height: 20px;
}

#ejemplo{
    position: fixed;
}

#header {
	background: url(../../img/fondo_header.png);
	background-color: #C2AD25;
	background-repeat: no-repeat;
    position: fixed;
	height: 70px;
    width: 100%;
	top: 0 !importante;
	top: -1px;
	z-index: 22;
}

#header .contenedorHeader{
	width: 950px;
}

/* ESTILOS DEL HEADER */
img {
	border: none;
}

#wrapper,#header,#header .contenedorHeader,#header .menu{
	display: block;
	left: 1px;
}

#header .logo{
	float: left;
	width: 600px;
}

#header .infoUser {
	color: #85781D;
	font-family: Verdana, Arial, sans-serif;
	font-size: 14px;
	font-weight: bold;
	text-align: right;
	text-decoration: none;
	padding-top: 40px;
}

#header .contenedorMenu,#contenedor {
  clear: both;
  background-color:#85781D;
}

#header .logo {
	margin-left: 8px;
	margin-top: 8px;
}

#header .menu{
	height:30px;
	width:1500px;
	background-color: #85781D;
	text-align: left;
}

#footer{
	text-align: center;
	height: 23px;
	width: 100%;
	background-color: #EFE9C1;
	padding: 8px 0 0 0;
	position: fixed;
	bottom: 0 !importante;
	bottom: -1px;
	font-family: verdana;
	font-size: 12px;
	color: #A4A4A4;
	z-index:1;
}

#padding_contenido{
	padding-top: 10px;
}


#componente_central{
	margin: 98px 20px 50px 20px;
}

/*RIESGOS*/
table#tabla_riesgos{
	background-color: #FFFFFF;
}

table#tabla_riesgos tr{
	height: 20px;
	font-size: 12px;
	font-family: sans-serif, Verdana;
}

table#tabla_riesgos .espacio{
	padding:3px;
}

table#tabla_riesgos .titulo{
	padding-left: 3px;
	padding-right: 3px;
	font-weight: bold;
	font-size: 13px;
}

table#tabla_riesgos .subtitulo{
	padding-left: 3px;
	padding-right: 3px;
	font-weight: bold;
}

table#tabla_riesgos a{
	text-decoration: none;
	color: #8C762B;
	font-weight: bold;
}

/* */
table#tabla_espacios{
	background-color: #FFFFFF;
}

table#tabla_espacios tr{
	height: 20px;
	font-size: 12px;
	font-family: sans-serif, Verdana;
}

table#tabla_espacios .espacio{
	padding-left: 3px;
	padding-right: 3px;
}

table#tabla_espacios .titulo{
	padding-left: 3px;
	padding-right: 3px;
	font-weight: bold;
	font-size: 13px;
}

table#tabla_espacios .subtitulo{
	padding-left: 3px;
	padding-right: 3px;
	font-weight: bold;
}

table#tabla_espacios a{
	text-decoration: none;
	color: #8C762B;
	font-weight: bold;
}



/* TABLAS RDA */

table#tabla_rda{
	background-color: #FFFFFF;
}


/* Modificado por Alexander*/
table#tabla_rdaCM{
	background-color: #FFFFFF; 
	border-color: #BBB #BFBA97 #BFBA97 #BBB; 
	border-style: solid; 
	border-width: 2px 2px 2px 2px; 
	border-collapse: collapse; 
	padding: 0; 
	margin: 0 0 0 0;
}

table#tabla_rdaCM tr{
	height: 20px;
	white-space: nowrap;
	font-size: 12px;
	font-family: sans-serif, Verdana;
	border-top:#666 1px solid;
}
/* Fin modificado por Alexander*/

table#tabla_rda tr{
	height: 20px;
	white-space: nowrap;
	font-size: 12px;
	font-family: sans-serif, Verdana;
}

table#tabla_rda .espacio{
	padding-left: 3px;
	padding-right: 3px;
}

table#tabla_rda .titulo{
	padding-left: 3px;
	padding-right: 3px;
	font-weight: bold;
	font-size: 13px;
}

table#tabla_rda .subtitulo{
	padding-left: 3px;
	padding-right: 3px;
	font-weight: bold;
}

table#tabla_rda a{
	text-decoration: none;
	color: #8C762B;
	font-weight: bold;
}

.align_valor{
	text-align: right;
	padding-left: 3px;
	padding-right: 3px;
}

.estilo_fila{
	background-color: #F9D560;
}

.fila_encabezado{
	background-color: #FDB913;
}

.fieldset_rda{
	border-style: none;
}

.legen_fieldset{
	background-color: #AB9F2F;
	color:#ffffff;
	width: 100%;
	padding: 4px;
	border:1px solid #D8D8D8;
	font-weight: bold;
	font-size: 12px;
}

.button, .button:visited { /* botones genéricos */
background: #222 url(http://sites.google.com/site/zavaletaster/Home/overlay.png) repeat-x;
display: inline-block;
padding: 5px 10px 6px;
color: #FFF;
text-decoration: none;
-moz-border-radius: 6px;
-webkit-border-radius: 6px;
-moz-box-shadow: 0 1px 3px rgba(0,0,0,0.6);
-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.6);
text-shadow: 0 -1px 1px rgba(0,0,0,0.25);
border-top: 0px;
border-left: 0px;
border-right: 0px;
border-bottom: 1px solid rgba(0,0,0,0.25);
cursor:pointer;
}

button::-moz-focus-inner,
input[type="reset"]::-moz-focus-inner,
input[type="button"]::-moz-focus-inner,
input[type="submit"]::-moz-focus-inner,
input[type="file"] > input[type="button"]::-moz-focus-inner {
border: none;
}

.button:hover { /* el efecto hover */
background-color: #111
color: #FFF;
}

.button:active{ /* el efecto click */
top: 1px;
}

/* botones pequeños */
.small.button, .small.button:visited {
font-size: 11px ;
}

/* botones medianos */
.button, .button:visited,.medium.button, .medium.button:visited {
font-size: 13px;
font-weight: bold;
line-height: 1;
text-shadow: 0 -1px 1px rgba(0,0,0,0.25);
}

/* botones grandes */
.large.button, .large.button:visited {
font-size:14px;
padding: 8px 14px 9px;
}

/* botones extra grandes */
.super.button, .super.button:visited {
font-size: 14px;
padding: 8px 14px 9px;
}

.pink.button { background-color: #E22092; }
.pink.button:hover{ background-color: #C81E82; }

.green.button, .green.button:visited { background-color: #91BD09; }
.green.button:hover{ background-color: #749A02; }

.red.button, .red.button:visited { background-color: #E62727; }
.red.button:hover{ background-color: #CF2525; }

.orange.button, .orange.button:visited { background-color: #F15D22; }
.orange.button:hover{ background-color: #D45500; }

.blue.button, .blue.button:visited { background-color: #2981E4; }
.blue.button:hover{ background-color: #2575CF; }

.yellow.button, .yellow.button:visited { background-color: #FDB913; }
.yellow.button:hover{ background-color: #FC9200; }

.yellow2.button, .yellow.button:visited { background-color: #85781D; }
.yellow2.button:hover{ background-color: #AB9F2F; }

.regresar{
	height: 20px;
	vertical-align: middle;
	text-align: right;
}

.link a{
	text-decoration: none;
	color: #ffffff;
	font-weight: bold;
}

.link a:HOVER{
	text-decoration: none;
	color: #000000;
	font-weight: bold;
}

.regresar a{
	text-decoration: none;
	color: #8C762B;
	font-weight: bold;
}

.regresar2{
	height: 20px;
	vertical-align: middle;
	text-align: left;
}

.regresar2 a{
	text-decoration: none;
	color: #8C762B;
	font-weight: bold;
	padding-left: 20px;
}

.tablaMenu{
	width:100%;
    text-align: center;
}

.centrado{
 	width: 100%;
 	height: 80%;
 	vertical-align: middle;
}

.tablaMenu tr{
	height: 60px;
}


.tablaMPrincipal tr{
	height: 70px;
	text-align: center;
}

/*ESTILOS DEL ARBOL*/
#ver_arbol{
	height: 80%;
	overflow-y:auto;
}

#arbol{
	background-color: #85781D;
	width: 20%;
	float: left;
	left: -1px;
	height: 100%;
	position: fixed;
	clear: both;
	overflow-y:hidden;
	z-index:-2;
}

.contenido_arbol{
	width: 80%;
	overflow-x:hidden;
	overflow-y:auto;
	float: right;
	height: 80%;
	position: fixed;
	right: 0 !importante;
	right: -1px;
	z-index:-3;
}


.contenido_arbolExpand{
	width: 97%;
	float: right;
	overflow-x:hidden;
	overflow-y:auto;
	height: 80%;
	position: fixed;
	right: 0 !importante;
	right: -1px;
	z-index:-4;
}

#toggle{
	text-align: right;
}

.tituloGrafica{
    color: #B5121B;
    font-family:verdana;
    font-size:14px;
    font-weight:bold;
	height: 50px;
	padding-top: 10px;
}

.ruta{
	color: #B5121B;
    font-family:verdana;
    font-size:12px;
    font-weight:bold;
	padding-top: 10px;
	padding-bottom:20px;
	padding-left:20px;
	white-space: nowrap;
}

.titulo_rojo{
	color: #B5121B;
    font-family:verdana;
    font-size:12px;
    font-weight:bold;
	white-space: nowrap;
	padding-bottom: 10px;
	padding-top:10px;
	text-transform: uppercase;
}

.titulo_ruta{
	color: #B5121B;
    font-family:verdana;
    font-size:12px;
    font-weight:bold;
	white-space: nowrap;
	padding:10px;
	text-transform: uppercase;
	
}

.tablaGrafica{
	text-align: center;
	height: 50px;
	padding-top: 40px;
}

.tabla_datos{
	text-align: center;
	width: 30%;
}

.encabezado_tabla{
	text-align: center;
	background-color: #00AAA6;
}

.espacio_td{
	padding-left: 10px;
	padding-right: 10px;
}

.tabla_datosG{
	text-align: center;
	width: 30%;
	height: 160px;
}

.tabla_datosG td{
	border-color: #4AAA42;
}

.tabla_datosA{
	text-align: center;
	width: 50%;	
}

.tabla_datosA td{
	border-color: #4AAA42;
	height: 50px;
}

.ejemplo{
 text-align: center;
}

.ejemplo td{
	padding-left:20px;
	padding-right:20px;
	border-color: #4AAA42;
	height: 30px;
}

.font_subt{
	font-size:14;
    background-color:#00AAA6;
    font-weight:bold;
    font-family: Verdana
}

.font_tabla{
   font-size:14;
   font-family: Verdana
}

.separarGrafica{
	padding: 30px 5px 30px 5px;
}

#pera{
	margin: 10px 10px 10px 18px;
	text-align: left;
}

.container{
	height: 400px;
}

.containerExpand{
	height: 500px;
}

/*ESTILOS CUADRO MANDO*/

#nivel_superior{
	background-color: #ffc000;
}
#nivel_superior:hover{
	background-color: #d2d2d2;
}

.nivel_superior{
	font-weight: bold;
	text-transform: uppercase;
}

#nivel_medio{
	background-color: #ffd85d;
}
#nivel_medio:hover{
	background-color: #d2d2d2;
}

.nivel_medio{
	font-weight: bold;
	text-transform: uppercase;
}

.nivel_inferior{
	font-weight: bold;
	text-transform: uppercase;
}

.nivel_identado{
	padding-left: 10px;
}
/*FIN ESTILOS CUADRO MANDO*/




/*Estilos agregados por Alexander Huertas*/
#tr_nar_obs{
	background-color: #ffc000;
}

#tr_nar_obs:hover{
	background-color: #d2d2d2;
}
#tr_nar_obs:visited{
	background-color: #d2d2d2;
}
#tr_nar_cla{
	background-color: #ffd85d;
}
#tr_nar_cla:hover{
	background-color: #d2d2d2;
}
#tr_ros_cla{
	background-color: #f2f1ea;
}
#tr_ros_cla:hover{
	background-color: #d2d2d2;
}
#tr_bla{
	background-color: #ffffff;
}
#tr_bla:hover{
	background-color: #d2d2d2;
}
#tr_font_negrilla{
	font-weight: bold;
}

.tr_font_negrilla{
	font-weight: bold;
}

#tr_num_alineacion{
	text-align: right;
}

#tr_num_alineacion2{
	text-align: right;
	padding:4px 4px 4px 4px;
}

#btn_regresar {
	text-decoration:none;
	width:35px ;
	height: 15px;
	background-color:#f2f1ea;
	font-size:9px; 
	padding:2px 2px 2px 2px;
	border:#cccccc 1px solid;
	font-weight: bold;
	font-color:#000000;
	color:#000000;
	} 
	
.legen_fieldset2{
	background-color: #B7B055;
	color:#ffffff;
	width: 98%;
	padding: 4px;
	border:1px solid #D8D8D8;
	font-weight: bold;
	font-size: 12px;
}

#tr_num_alineacion_izqu{
	text-align: left;
	padding:4px 4px 4px 4px;
}

.mostrarN{
	overflow-x: auto;
}

/* Fin de estilos agregados por Alexander Huertas*/


.item_actual{
	font-size:15px;
	color: #85781D;
	font-weight: bold;
	float: right;
}

#tablaIndicador{
	padding-top: 40px;
}

.encabezadoTabla{
	background-color: #990000;
	color:white;
}

.valoresResumen{
	font-weight: bold;
	padding-left: 4px;
	padding-right: 4px;
}

.selectGrafica{
	width: 250px;
	height: 23px;
	font-size: 15px;
	vertical-align: middle;
	font-family: Verdana;
}

.padding{
	padding-right: 20px;
}

#imgCargando{
	visibility:hidden;
	text-align: center;
	font-family:Verdana;
	font-size:15px;
	font-weight:bold;
	color: #000000;
	background-color:#ffffff;
    position:fixed;
    padding-top: 15%;
    width:100%;
    height:100%;
    filter:alpha(opacity=40);
    -moz-opacity:.40;
    opacity:.40;
    z-index: 2000;
}

.tr_ancho_num{
	width: 80px;
}
.tr_ancho_por{
	width: 50px;	
}
.mostrarAcuVar{
	overflow:auto;	
}

#expand{
	height: 20px;
}

.selectFiltro{
	width: 200px;
	height: 25px;
	font-size: 15px;
	font-family: Verdana;
}

.selectTabla{
	width: 300px;
	font-size: 13px;
	font-family: Verdana;
	height: 25px;
	color: #333333;
}

.inputTabla{
	width: 300px;
	font-size: 13px;
	font-family: Verdana;
	height: 20px;
	color: #333333;
	padding-top: 5px;
}



/* CUADROS DE ALERTA DE ERROR */
#content {padding:20px}
#dialog {position:absolute; width:425px; padding:10px; z-index:200; background:#fff}
#dialog-header {display:block; position:relative; width:411px; padding:3px 6px 7px; height:14px; font-size:14px; font-weight:bold}
#dialog-title {float:left}
#dialog-close {float:right; cursor:pointer; margin:3px 3px 0 0; height:11px; width:11px; background:url(../../img/dialog_box/dialog_close.gif) no-repeat}
#dialog-content {display:block; height:140px; padding:40px; color:#666666; font-size:14px; font-weight: bold; font-family: Verdana;}
#dialog-mask {position:absolute; top:0; left:0; min-height:100%; width:100%; background:#FFF; opacity:.50; filter:alpha(opacity=50); z-index:100}
.error {background:#fff url(../../img/dialog_box/error_bg.jpg) bottom right no-repeat; border:1px solid #924949; border-top:none}
.errorheader {background:url(../../img/dialog_box/error_header.gif) repeat-x; color:#6f2c2c; border:1px solid #924949; border-bottom:none}
.warning {background:#fff url(../../img/dialog_box/warning_bg.jpg) bottom right no-repeat; border:1px solid #c5a524; border-top:none}
.warningheader {background:url(../../img/dialog_box/warning_header.gif) repeat-x; color:#957c17; border:1px solid #c5a524; border-bottom:none}
.success {background:#fff url(../../img/dialog_box/success_bg.jpg) bottom right no-repeat; border:1px solid #60a174; border-top:none}
.successheader {background:url(../../img/dialog_box/success_header.gif) repeat-x; color:#3c7f51; border:1px solid #60a174; border-bottom:none}
.prompt {background:#fff url(../../img/dialog_box/prompt_bg.jpg) bottom right no-repeat; border:1px solid #4f6d81; border-top:none}
.promptheader {background:url(../../img/dialog_box/prompt_header.gif) repeat-x; color:#355468; border:1px solid #4f6d81; border-bottom:none}

label.errorCampo{color: red;}
div.errorCampo { display: none; }

input.checkbox { border: hidden; }
input:focus { border: 1px dotted black; }
input.errorCampo { border: 1px dotted red; }




#pagination-digg li          { border:0; margin:0; padding:0; font-size:11px; list-style:none; /* savers */ float:left; }
#pagination-digg a           { border:solid 1px #9aafe5; margin-right:2px; }
#pagination-digg .previous-off,
#pagination-digg .next-off   { border:solid 1px #DEDEDE; color:#888888; display:block; float:left; font-weight:bold; margin-right:2px; padding:3px 4px; }
#pagination-digg .next a,
#pagination-digg .previous a { font-weight:bold; }
#pagination-digg .active     { background:#2e6ab1; color:#FFFFFF; font-weight:bold; display:block; float:left; padding:4px 6px; /* savers */ margin-right:2px; }
#pagination-digg a:link,
#pagination-digg a:visited   { color:#0e509e; display:block; float:left; padding:3px 6px; text-decoration:none; }
#pagination-digg a:hover     { border:solid 1px #0e509e; }
.myinputstyle {
	border: #85781d 2px solid;
	/** remember to change image path **/
	background: url(none) no-repeat #FED671;
	font-family: Verdana;
	font-style: bold;
	font-size: 12px;
	color: #85781d;
}

/** You can use this style for your LABEL elements **/
.mylabelstyle {
	font-family: Verdana;
	font-style: bold;
	font-size: 13px;
	color: #85781d;
}

.tituloMenu{
	color:#fff;
	font-family: Verdana; 
	font-style: bold;
}

.cantidad_cheques{
	width: 30px;
	text-align: center;
}

.valor_cheques{
	text-align: right;
}
</style>
</head>
<body>
';
$footer = '</body></html>';

$html="<table><tr><td align='center'><strong>".$_POST['name_reporte']."</strong></td></tr><tr><td>";

$html.= $_POST['datos_a_enviar'];
$html.="</td></tr></table>";
/*Si quieres agregar imagenes usa la ruta completa como en este caso :P para no recibir errores
  <td><img src=http://www.webcomparte.com/archivos_publicos/imagenes/webcomparte/logo2.png></td>
*/
echo $header.$html.$footer;    
?>  

