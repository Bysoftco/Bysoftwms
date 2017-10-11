<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Bysoft &reg; Logistic</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
	<!-- Estilos generales del aplicativo -->
	<link href="template/css/styles.css" rel="stylesheet" type="text/css" />
	<!-- Estilos del menú -->
	<link href="template/css/jqueryslidemenu.css" rel="stylesheet" type="text/css" />
	<!-- Estilos de popups -->
	<link href="template/css/wowwindow.css" rel="stylesheet" type="text/css" />
	
	
	<!-- Scripts generales del aplicativo -->
	<script type="text/javascript" src="{LIB_PATH}javascript/scripts.js"></script>
	<!-- Librerias framework Jquery -->
	<script type="text/javascript" src="{LIB_PATH}javascript/jquery.min.js"></script>
	<script type="text/javascript" src="{LIB_PATH}javascript/jquery.js"></script>
	<!-- Librer�as de php usadas en javascript -->
	<script type="text/javascript" src="{LIB_PATH}javascript/php.js"></script>
	<!-- Javascript del menú principal -->
	<script type="text/javascript" src="{LIB_PATH}javascript/jqueryslidemenu.js"></script>
	<!-- Validar campos formulario -->
	<script src="{LIB_PATH}javascript/jquery.validate.js" type="text/javascript"></script>
	<script src="{LIB_PATH}javascript/jquery.metadata.js" type="text/javascript"></script>
	<!-- Bordes circulares de botones-->
	<script src="{LIB_PATH}javascript/bordes/niftycube.js" type="text/javascript"></script>
	<!-- javascript de popups -->
	<script type="text/javascript" src="{LIB_PATH}javascript/jquery.wowwindow.min.js"></script>
	
	<!-- NO SE USAN 
	<link href="template/css/jquery.treeview.css" rel="stylesheet" type="text/css" />
	<link href="template/css/validationEngine.jquery.css" rel="stylesheet" type="text/css" />
	<link href="template/css/scrollbar_demo.css" rel="stylesheet" type="text/css" />
	<link href="template/css/jquery-ui.custom.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="{LIB_PATH}anychart/js/AnyChart.js"></script>
	<script type="text/javascript" src="{LIB_PATH}javascript/jquery-ui.min.js"></script>
	<script type="text/javascript" src="{LIB_PATH}javascript/validar.js"></script>
	<script type="text/javascript" src="{LIB_PATH}javascript/dialog_box.js"></script>
	<script type="text/javascript" src="{LIB_PATH}javascript/jquery.cookie.js"></script>
	<script type="text/javascript" src="{LIB_PATH}javascript/jquery.treeview.js"></script>
	<script type="text/javascript" src="{LIB_PATH}javascript/tree.js"></script>
	<script type="text/javascript" src="{LIB_PATH}javascript/tree_tpl.js"></script>
	<script src="{LIB_PATH}javascript/AjaxUpload.min.js" type="text/javascript"></script>
	<script src="{LIB_PATH}javascript/jquery.formatCurrency.js" type="text/javascript"></script>
	<script type="text/javascript" src="{LIB_PATH}javascript/validate/jquery.validationEngine-es.js"></script>
	<script src="xml/ejemplo.js" type="text/javascript"></script>
	-->
</head>
<body>
    <!-- Div de m�scara cargando -->
	<div id="imgCargando">
		<img src="img/bar.gif" border="0" />
	</div>
	<div id="wrapper">
		{HEADER}
		<div id="contenedor_externo" class="datos" align="left">
		<!-- BEGIN CONTENEDOR -->
        <div id="componente_central" class="datos" align="left">
        	{COMPONENTE_CENTRAL}
        </div>
		<!-- END CONTENEDOR -->
		</div>
		{FOOTER}
	</div>
<script>
<!--
$(document).ready(function(){
	$(document).ajaxStart(function() {
		$('#imgCargando').css('visibility','visible');
	})
	.ajaxStop(function() {
		$('#imgCargando').css('visibility','hidden');
	});
});
//-->
</script>
</body>
</html>