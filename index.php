<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
ini_set("include_path",'/home1/isamis/php:.:/opt/cpanel/ea-php56/root/usr/share/pear' . ini_get("include_path") );
define('entrada_valida', true);
require_once('config.php');
require_once('HTML/Template/IT.php');
require_once(CLASSES_PATH.'DatabaseMySQL.php');
require_once(CLASSES_PATH.'adicionales.php');

//LOCAL
//$_SESSION['conexion'] = new Database(array('DB_HOST'=>'localhost','DB_SOCK'=>'','DB_USER'=>'root','DB_PASS'=>'73125365@_','DB_NAME'=>'nbysoftdb'));

//SERVIDOR
$_SESSION['conexion'] = new Database(array('DB_HOST'=>'grupobysoft.com','DB_SOCK'=>'','DB_USER'=>'isamis_ulines','DB_PASS'=>'plines','DB_NAME'=>'isamis_linedb'));

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