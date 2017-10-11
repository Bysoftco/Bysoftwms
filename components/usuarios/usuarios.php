<?php 
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'usuarios/model/usuarios.php';
require_once COMPONENTS_PATH . 'usuarios/views/vista.php';

class usuarios {
	var $vista;
	var $datos;
	
	function usuarios() {
		$this->vista = new UsuariosVista();
		$this->datos = new UsuariosModelo();
	}
	
	function listadoUsuarios($arreglo) {
		if(!isset($arreglo['pagina']) || empty($arreglo['pagina'])) {
			$arreglo['pagina'] = 1;
		}
		$arreglo['datosUsuario'] = $this->datos->listadoUsuarios($arreglo);
		$this->vista->listadoUsuarios($arreglo);
	}
	
	function agregarUsuario($arreglo) {
		$lista_perfil = $this->datos->build_list("perfiles", "id", "nombre", "WHERE estado<>'E' ORDER BY nombre ");
    $arreglo['select_perfil'] = $this->datos->armSelect($lista_perfil ,'Seleccione Perfil...');
    $lista_sedes = $this->datos->build_list("sedes", "codigo", "nombre");
    $arreglo['select_sedes'] = $this->datos->armSelect($lista_sedes ,'Seleccione Sede...');
		$arreglo['edicion_clave'] = 'none';
    $arreglo['titulo_tabla'] = 'AGREGAR USUARIO';

		$this->vista->agregarUsuario($arreglo);
	}
	
	function editarUsuario($arreglo) {
		$lista_perfil = $this->datos->build_list("perfiles", "id", "nombre", "WHERE estado<>'E' ORDER BY nombre ");
		$arreglo['datosUsuario'] = $this->datos->infoUsuario($arreglo);
    $arreglo['select_perfil'] = $this->datos->armSelect($lista_perfil ,'Seleccione Perfil...', $arreglo['datosUsuario']['perfil_id']);	
    $lista_sedes = $this->datos->build_list("sedes", "codigo", "nombre");
    $arreglo['select_sedes'] = $this->datos->armSelect($lista_sedes ,'Seleccione Sede...',$arreglo['datosUsuario']['sede_id']);
		$arreglo['edicion_clave'] = 'block';
    $arreglo['titulo_tabla'] = 'EDITAR USUARIO';

		$this->vista->agregarUsuario($arreglo);
	}
	
	function editar($arreglo) {
		if(isset($arreglo['edClave'])) {
			$this->datos->editarClave($arreglo);
		}
		if(isset($arreglo['id']) && !empty($arreglo['id'])) {
      $id = $arreglo['id'];
      $arreglo['alerta_accion'] = 'Usuario Editado Con &Eacute;xito';
    } else {
      $id = null;
      $arreglo['alerta_accion'] = 'Usuario Creado Con &Eacute;xito';
    }
		recuperar_Post($this->datos);
		$this->datos->save($id);
		$this->listadoUsuarios($arreglo);
	}
	
	function validarRepetido($arreglo) {
		$datos = $this->datos->validarRepetido($arreglo);
		if(count($datos)>0) {
			print 'invalido';
		} else {
			print 'valido';
		}
	}
	
	function eliminarUsuario($arreglo) {
		if(is_numeric($arreglo['id'])) {
			$deleted = $this->datos->deleted($arreglo['id']);
			if($deleted === false) {
        set_flash('error', 'La informaci&oacute;n ingresada no es correcta');
      } else {
        if($deleted === null) {
          set_flash('error', 'Los datos ingresados para la operaci&oacute;n no son v&aacute;lidos');
        } else {
          set_flash('notice', 'El registro se elimin&oacute; correctamente');
        }
      }
		} else {
      set_flash('error', 'El dato ingresado no es v&aacute;lido.');
    }
		$this->listadoUsuarios($arreglo);
	}
	
	function verUsuarios($arreglo) {
		$arreglo['datosUsuario'] = $this->datos->infoUsuario($arreglo);
		$this->vista->verUsuario($arreglo);
	}
	
	function validarClave($arreglo) {
		$arregloDatos = $this->datos->validarClave($arreglo);
		if(count($arregloDatos)>0) {
			echo 'valido';
		} else {
			echo 'noValido';
		}
	}
	
	function cambiarEstadoUsuario($arreglo) {
		$this->datos->cambiarEstadoUsuario($arreglo);
		$this->listadoUsuarios($arreglo);
	}
  
	function cambiarSede($arreglo) {
		$this->vista->cambiarSede($arreglo);
	}
}
?>