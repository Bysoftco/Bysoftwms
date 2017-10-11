<?php
header('Content-Type: text/html; charset=ISO-8859-1');
session_start();
//session_destroy();
define('entrada_valida', true);
require_once('config.php');
require_once(LIB_PATH.'HTML_Template_IT/IT.php');
require_once(CLASSES_PATH.'DatabaseMySQL.php');
require_once(CLASSES_PATH.'adicionales.php');

//LOCAL
$_SESSION['conexion'] = new Database(array('DB_HOST'=>'localhost','DB_SOCK'=>'','DB_USER'=>'root','DB_PASS'=>'bysoft','DB_NAME'=>'nbysoft_db'));

//SERVIDOR
//$_SESSION['conexion'] = new Database(array('DB_HOST'=>'bysoft.us','DB_SOCK'=>'','DB_USER'=>'venus_ubysoft','DB_PASS'=>'pbysoft','DB_NAME'=>'venus_linedb'));

$template_file = 'aplicacion.php';
$component = isset( $_REQUEST['component'] ) ? $_REQUEST['component'] : 'home';
$method = isset( $_REQUEST['method'] ) ? $_REQUEST['method'] : 'home';

if(!isset( $_SESSION['datos_logueo']['usuario'] ) && $component!='login'){
	$template_file = 'login.php';
	$component = 'login';
	$method = 'logueo_aplicacion';
}

ob_start();
$component_path = COMPONENTS_PATH . $component . '/' . $component . '.php';
include $component_path;
$clase = new $component();
$clase->$method($_REQUEST);
$componente_central = ob_get_contents();
ob_end_clean();

ob_start();
$header_path = COMPONENTS_PATH . 'template/template.php';
include $header_path;
$clase = new template();
$clase->armar_header($_REQUEST);
$header = ob_get_contents();
ob_end_clean();

ob_start();
$clase->armar_footer($_REQUEST);
$footer = ob_get_contents();
ob_end_clean();

$template = new HTML_Template_IT();
$template->loadTemplateFile( TEMPLATE_PATH . $template_file );
$template->setVariable('LIB_PATH', LIB_PATH );	

$template->setVariable('HEADER', $header);
$template->setVariable('COMPONENTE_CENTRAL', $componente_central);
$template->setVariable('FOOTER', $footer);
$template->show();
?>