<?php 
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'sedes/model/sedes.php';
require_once COMPONENTS_PATH . 'sedes/views/vista.php';

class sedes {
  var $vista;
  var $datos;

  function sedes() {
    $this->vista = new SedesVista();
    $this->datos = new SedesModelo();
  }
	
	function cambiarSede($arreglo) {
		$lista_sede = $this->datos->construir_lista($arreglo);
    $arreglo['select_sede'] = $this->datos->armarSelect($lista_sede,'Seleccione sede...',$_SESSION['sede']);

		$this->vista->cambiarSede($arreglo);
	}
  
  function cambiaSede($arreglo) {
	
    $this->datos->validar_infousuario($arreglo);

    if(isset($_SESSION['datos_logueo']['usuario'])) {
      $menu='<div id="myslidemenu" class="jqueryslidemenu">';
      $menu.=$this->datos->armar_menu_principal();
      $menu.='</div>';
	  if($_SESSION['datos_logueo']['perfil_id']==26){
	  }else{  
      	$_SESSION['menu'] = base64_encode($menu);
	  }
	  
	  
      print('cambiosede');
    }
  }	
}
?>