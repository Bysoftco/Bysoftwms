<?php 
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH.'perfiles/model/perfiles.php';
require_once COMPONENTS_PATH.'perfiles/views/vista.php';

class perfiles {	
	var $vista;
	var $datos;
	
	function perfiles() {
		$this->vista = new PerfilesVista();
		$this->datos = new PerfilesModelo();
	}
	
	function listadoPerfiles($arreglo) {
		if(!isset($arreglo['pagina']) || empty($arreglo['pagina'])) {
			$arreglo['pagina'] = 1;
		}
		$arreglo['datosPerfil'] = $this->datos->listadoPerfiles($arreglo);
		$this->vista->listadoPerfiles($arreglo);
	}
	
	function verPerfiles($arreglo) {
		$arreglo['disabled'] = 'disabled';
		$arreglo['datosPerfil'] = $this->datos->verPerfil($arreglo);
		$arreglo['menuPermisos'] = $this->datos->armar_menu_principal($arreglo);
		$this->vista->verPerfil($arreglo);
	}
	
	function editarPerfiles($arreglo) {
		$arreglo['disabled'] = '';
		$arreglo['datosPerfil'] = $this->datos->verPerfil($arreglo);
		$arreglo['menuPermisos'] = $this->datos->armar_menu_principal($arreglo);
		$this->vista->editarPerfil($arreglo);
	}
	
	function editarPerfil($arreglo) {
		if(isset($arreglo['id']) && is_numeric($arreglo['id'])) {
    	$id = $arreglo['id'];
    	$this->datos->borrarPermisos($arreglo);
			$arreglo['alerta_accion'] = 'Perfil Editado con &Eacute;xito';
	  } else {
	    $id = null;
			$arreglo['alerta_accion'] = 'Perfil Creado Con &Eacute;xito';
	  }
		recuperar_Post($this->datos);
		$result = $this->datos->save($id);
		$coma = '';
		$cadena = '';
		foreach ($arreglo['permisos'] as $value){
			$cadena .= $coma.'('.$result.', '.$value.')';
			$coma = ',';
		}
		$this->datos->asignarPermisos($cadena);
		$this->listadoPerfiles($arreglo);
	}
	
	function agregarPerfil($arreglo) {
		$arreglo['disabled'] = '';
		$arreglo['menuPermisos'] = $this->datos->armar_menu_principal($arreglo);
		$this->vista->agregarPerfil($arreglo);
	}
	
	function eliminarPerfil($arreglo) {
		if(is_numeric($arreglo['id'])) {
			$deleted = $this->datos->deleted($arreglo['id']);
			if($deleted === false) {
        set_flash('error','La información ingresada no es correcta');
	    } elseif($deleted === null) {
          set_flash('error','Los datos ingresados para la operación no son válidos');
      } else {
				set_flash('notice','El registro se eliminó correctamente');
	    }
		} else {
      set_flash('error','El dato ingresado no es válido.');
    }
		$this->listadoPerfiles($arreglo);
	}
	
	function cambiarEstadoPerfil($arreglo) {
		$this->datos->cambiarEstadoPerfil($arreglo);
		$this->listadoPerfiles($arreglo);
	}
}
?>