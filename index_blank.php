<?php
session_start();
define('entrada_valida', true);
require_once('config.php');
require_once(LIB_PATH.'HTML_Template_IT/IT.php');
require_once(CLASSES_PATH.'DatabaseMySQL.php');
require_once(CLASSES_PATH.'adicionales.php');

//LOCAL
$_SESSION['conexion'] = new Database(array('DB_HOST'=>'localhost','DB_SOCK'=>'','DB_USER'=>'root','DB_PASS'=>'73125365','DB_NAME'=>'nbysoft_db'));

//SERVIDOR
//$_SESSION['conexion'] = new Database(array('DB_HOST'=>'grupobysoft.com','DB_SOCK'=>'','DB_USER'=>'isamis_uwbysoft','DB_PASS'=>'pwbysoft','DB_NAME'=>'isamis_wmsbysoft'));

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