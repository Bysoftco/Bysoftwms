<?php
session_start();
define('entrada_valida', true);
//Configuración PEAR Sitio Web
ini_set("include_path",'/home1/isamis/php:.:/opt/cpanel/ea-php56/root/usr/share/pear' . ini_get("include_path") );
require_once('config.php');
require_once('HTML/Template/IT.php');
require_once(CLASSES_PATH.'DatabaseMySQL.php');
require_once(CLASSES_PATH.'adicionales.php');

//LOCAL
//$_SESSION['conexion'] = new Database(array('DB_HOST'=>'localhost','DB_SOCK'=>'','DB_USER'=>'root','DB_PASS'=>'73125365@_','DB_NAME'=>'nbysoftdb'));

//SERVIDOR
$_SESSION['conexion'] = new Database(array('DB_HOST'=>'grupobysoft.com','DB_SOCK'=>'','DB_USER'=>'isamis_ulines','DB_PASS'=>'plines','DB_NAME'=>'isamis_linedb'));

$component = isset( $_REQUEST['component'] ) ? $_REQUEST['component'] : 'menus';
$method = isset( $_REQUEST['method'] ) ? $_REQUEST['method'] : 'armar_menu_principal';

if(!isset( $_SESSION['datos_logueo']['usuario'] ) && $component!='login'){
	echo '<script>window.location="index.php";</script>';
	die();
}
$component_path = COMPONENTS_PATH . $component . '/' . $component . '.php';
include $component_path;
$clase = new $component();
$clase->$method($_REQUEST);
?>