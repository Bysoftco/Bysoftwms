<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="shortcut icon" type="image/x-icon" href="./favicon.ico">
	<title>Bysoft &reg; Logistic</title>
	<!-- Estilos generales del aplicativo -->
	<link href="template/css/styles.css" rel="stylesheet" type="text/css" />
	<link href="template/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
	<!-- Estilos del menú -->
	<link href="template/css/jqueryslidemenu.css" rel="stylesheet" type="text/css" />
	<!-- Estilos de popups -->
	<link href="template/css/wowwindow.css" rel="stylesheet" type="text/css" />
	<!-- Estilos de integrado -->
	<link type="text/css" href="integrado/cz_estilos/jquery-ui-1.8.10.custom.css" rel="stylesheet" />
  <link rel="stylesheet" href="integrado/cz_estilos/uploadify.css" type="text/css" />
  <!-- Acceso a las librerias de Bootstrap 5.3.0 de estilos -->
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous"> -->

	<!-- Scripts generales del aplicativo -->
	<!-- Librerias framework Jquery -->
	<script type="text/javascript" src="./integrado/cz_javascript/jquery-1.4.4.js"></script>
	<!-- <script src="{LIB_PATH}javascript/jquery-3.5.1.min.js"></script>
	<script src="{LIB_PATH}javascript/jquery-migrate-3.3.0.js"></script> -->
	<script type="text/javascript" src="{LIB_PATH}javascript/scripts.js"></script>
	<!-- <script type="text/javascript" src="./integrado/cz_javascript/jquery-1.3.2.min.js"></script> -->
    
	<!-- Librerías de php usadas en javascript -->
	<script type="text/javascript" src="{LIB_PATH}javascript/php.js"></script>
	<!-- Javascript del menú principal -->
	<script type="text/javascript" src="{LIB_PATH}javascript/jqueryslidemenu.js"></script>
	<script type="text/javascript" src="{LIB_PATH}javascript/lib/jquery.liveFilter.js"></script>
	<!-- Validar campos formulario -->
	<script src="{LIB_PATH}javascript/jquery.validate.js" type="text/javascript"></script>
	<script src="{LIB_PATH}javascript/jquery.metadata.js" type="text/javascript"></script>
	<script src="{LIB_PATH}javascript/jquery.dataTables.js" type="text/javascript"></script>
        
	<!-- Bordes circulares de botones-->
	<script src="{LIB_PATH}javascript/bordes/niftycube.js" type="text/javascript"></script>
  
	<!-- javascript de popups -->
	<script type="text/javascript" src="{LIB_PATH}javascript/jquery.wowwindow.min.js"></script>
	
	<!-- Librerias js de integrado Jquery -->	
	<script type="text/javascript" src="./integrado/cz_javascript/jquery-ui-1.8.10.custom.min.js"></script>
	<script type="text/javascript" src="./integrado/cz_javascript/jquery.ui.datepicker-es.js"></script>
	
	<script type='text/javascript' src='./integrado/cz_javascript/lib/jquery.bgiframe.min.js'></script>
	<script type='text/javascript' src='./integrado/cz_javascript/lib/jquery.ajaxQueue.js'></script>
	<script type='text/javascript' src='./integrado/cz_javascript/lib/thickbox-compressed.js'></script>
	<script type='text/javascript' src='./integrado/cz_javascript/lib/jquery.autocomplete.js'></script>
	
	<script src="./integrado/cz_javascript/external/jquery.bgiframe-2.1.2.js"></script>
	<script src="./integrado/cz_javascript/ui/jquery.ui.core.js"></script>
	<script src="./integrado/cz_javascript/ui/jquery.ui.widget.js"></script>
	<script src="./integrado/cz_javascript/ui/jquery.ui.mouse.js"></script>
	<script src="./integrado/cz_javascript/ui/jquery.ui.button.js"></script>
	<script src="./integrado/cz_javascript/ui/jquery.ui.draggable.js"></script>
	<script src="./integrado/cz_javascript/ui/jquery.ui.position.js"></script>
	<script src="./integrado/cz_javascript/ui/jquery.ui.resizable.js"></script>
	<script src="./integrado/cz_javascript/ui/jquery.ui.dialog.js"></script>
	<script src="./integrado/cz_javascript/ui/jquery.effects.core.js"></script>

  <!-- Script para dar formato moneda a los números -->
  <script type="text/javascript" src="./integrado/cz_javascript/formatoNumero.js"></script>
  
  <!-- Librerías Javascript de Bootstrap 5.3.0 -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script> -->
</head>
<body>
  <!-- Div de máscara cargando -->
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