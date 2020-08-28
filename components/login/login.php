<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'login/model/login.php';
require_once COMPONENTS_PATH . 'login/views/vista.php';

class login {
	var $vista;
	var $datos;
	
	function login() {
		$this->vista = new LoginVista();
		$this->datos = new LoginModelo();
	}
	
	function logueo_aplicacion($arreglo) {
    // Carga la información de Sedes
    $lista_sedes = $this->datos->build_list("sedes", "codigo", "nombre");
    $arreglo['select_sedes'] = $this->datos->armSelect($lista_sedes, 'Seleccione Sede...', '11');
    
		$this->vista->logueo_aplicacion($arreglo);
	}
  
	function login_usuario($arreglo) {
		if(empty($arreglo['usuario'])||empty($arreglo['clave'])) {
			print('faltante');
		} else {
		  // Busca usuario en la tabla - verificando su existencia	
		    
			$this->datos->validar_usuario($arreglo);
			if(isset($_SESSION['datos_logueo']['usuario'])) {
        $menu='<div id="myslidemenu" class="jqueryslidemenu">';
        $menu.=$this->datos->armar_menu_principal();
        $menu.='</div>';
        $_SESSION['menu'] = base64_encode($menu);
        // Verifica si es un Tercero
        if($_SESSION['datos_logueo']['perfil_id'] == 23) {
          // Valida tipo de acceso único - No exista sesión activa
				  if($_SESSION['datos_logueo']['sesion'] == 0) {
            // Registra Entrada - Inicio de Sesión
            $arreglo['id'] = $_SESSION['datos_logueo']['usuario_id'];
            $this->datos->registra_sesion($arreglo);
            print('logueado');
          } else print('usractivo');
        } else print('logueado');
			} else print('error');
		}
	}
	function findSede($arregloDatos) {
    	
    	$this->datos->findSede($arregloDatos);
   	 	/*$arregloDatos[q] = strtolower($_GET["q"]);
    	header( 'Content-type: text/html; charset=iso-8859-1' );
    	while($this->datos->fetch()) {
      		$nombre = trim($this->datos->nombre);
      		echo "$nombre|$datos->codigo\n";
    	}
    	if($this->datos->N == 0) {
      		echo "No hay Resultados|0\n";
    	}*/
  }	
	function cerrar_sesion() {
    // Verifica Perfil de Tercero
    if($_SESSION['datos_logueo']['perfil_id'] == 23) {
		  // Registra Salida - Cierre de Sesión
		  $arreglo['id'] = $_SESSION['datos_logueo']['usuario_id'];
		  $this->datos->registra_salida($arreglo);      
    }
		session_destroy();
	}
}
?>