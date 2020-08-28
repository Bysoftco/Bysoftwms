<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'login/model/login.php';
require_once COMPONENTS_PATH . 'login/views/vista.php';

class login{
	
	var $vista;
	var $datos;
	
	function login(){
		$this->vista = new LoginVista();
		$this->datos = new LoginModelo();
	}
	
	function logueo_aplicacion($array){
		$this->vista->logueo_aplicacion();
	}
	
	function login_usuario($arreglo){
		if(empty($arreglo['usuario'])||empty($arreglo['clave'])){
			print('faltante');
		}
		else{
			$this->datos->validar_usuario($arreglo);
			if(isset($_SESSION['datos_logueo']['usuario'])){
				$menu='<div id="myslidemenu" class="jqueryslidemenu">';
				$menu.=$this->datos->armar_menu_principal();
				$menu.='</div>';
				$_SESSION['menu'] = base64_encode($menu);
				print('logueado');
			}
			else{
				print('error');
			}
		}
	}
	
	function cerrar_sesion(){
		session_destroy();
	}
}
?>
