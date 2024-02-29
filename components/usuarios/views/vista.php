<?php
require_once COMPONENTS_PATH.'usuarios/model/usuarios.php';

class UsuariosVista {
	var $template;

  function UsuariosVista() {
		$this->template = new HTML_Template_IT(COMPONENTS_PATH);
	}

	function template_inicial($arreglo,$datos,$paginacion,$filtros) {
		$plantilla = new HTML_Template_IT(COMPONENTS_PATH);
		$plantilla->loadTemplateFile('usuarios/views/tmpl/templateArbol.php');
		$plantilla->setVariable('COMODIN','');
		ob_start();
		$this->listadoUsuarios($arreglo,$datos,$paginacion);
		$contenido = ob_get_contents();
		ob_end_clean();
		$plantilla->setVariable('contenido',$contenido);
		$plantilla->setVariable('menu',$this->filtros($filtros));
		$plantilla->show();
	}

	function listadoUsuarios($arreglo) {
		$this->template->loadTemplateFile('usuarios/views/tmpl/listadoUsuarios.php');
		$this->template->setVariable('COMODIN','');
		$this->template->setVariable('paginacion',$arreglo['datosUsuario']['paginacion']);
		$this->template->setVariable('pagina',$arreglo['pagina']);
		$this->template->setVariable('verAlerta','none');
		
		$this->template->setVariable('orden',isset($arreglo['orden'])?$arreglo['orden']:"");
		$this->template->setVariable('id_orden',isset($arreglo['id_orden'])?$arreglo['id_orden']:"");
		$this->template->setVariable('campoBuscar',isset($arreglo['buscar'])?$arreglo['buscar']:"");
		
		if(isset($arreglo['alerta_accion'])) {
			$this->template->setVariable('alerta_accion',$arreglo['alerta_accion']);
			$this->template->setVariable('verAlerta','block');
		}
		
		$codbagcolor = 1;
		foreach($arreglo['datosUsuario']['datos'] as $value) {
			$this->template->setCurrentBlock("ROW");
			if($codbagcolor==1) {
        $this->template->setVariable('id_tr_estilo','tr_blanco');
        $codbagcolor = 2;
			} else {
        $this->template->setVariable('id_tr_estilo','tr_gris_cla');	
        $codbagcolor = 1;
			}
			$this->template->setVariable('id',$value['id']);
			$this->template->setVariable('usuario',$value['usuario']);
			$this->template->setVariable('perfil',$value['nombre_perfil']);
			$this->template->setVariable('nombres',$value['nombre_usuario']);
			$this->template->setVariable('apellidos',$value['apellido_usuario']);
			$this->template->setVariable('email',$value['mail_usuario']);
			$this->template->setVariable('sede',$value['nombre_sede']);
			
			$estado = '';
			switch($value['estado']) {
				case 'A' : $estado = 'Activo <img src="img/estados/activo.png" width="15" height="15" border="0" />'; break;
				case 'I' : $estado = 'Inactivo'; break;
			}
			$this->template->setVariable('idestado',$value['estado']);
			$this->template->setVariable('estado',$estado);
			$this->template->setVariable('fecha_creacion',$value['fecha_creacion']);
			$this->template->parseCurrentBlock("ROW");
		}
		$this->template->show();
	}

	function agregarUsuario($arreglo) {
		$this->template->loadTemplateFile('usuarios/views/tmpl/editarUsuario.php');
		$this->template->setVariable('COMODIN','');
		$this->template->setVariable('titulo_accion',$arreglo['titulo_tabla']);
		$this->template->setVariable('select_perfil',$arreglo['select_perfil']);
		$this->template->setVariable('edicion_clave',$arreglo['edicion_clave']);
		$this->template->setVariable('id',isset($arreglo['id'])?$arreglo['id']:'0');
		$this->template->setVariable('usuario',isset($arreglo['datosUsuario']['usuario'])?$arreglo['datosUsuario']['usuario']:'');
		$this->template->setVariable('nombre_usuario',isset($arreglo['datosUsuario']['nombre_usuario'])?$arreglo['datosUsuario']['nombre_usuario']:'');
		$this->template->setVariable('apellido_usuario',isset($arreglo['datosUsuario']['apellido_usuario'])?$arreglo['datosUsuario']['apellido_usuario']:'');
		$this->template->setVariable('mail_usuario',isset($arreglo['datosUsuario']['mail_usuario'])?$arreglo['datosUsuario']['mail_usuario']:'');
		$this->template->setVariable('select_sedes',$arreglo['select_sedes']);
		
		//$this->template->setVariable('sede','XXXXXX');
		$sedesUsuario = $this->sedesUsuarios($arreglo);
		$this->template->setVariable('sedesUsuarios',$sedesUsuario);
		
		$this->template->show();
	}
	
	function sedesUsuarios($arreglo) {
		$unaSede = new UsuariosModelo();
		$sedesU = new HTML_Template_IT(COMPONENTS_PATH);
		$sedesU->loadTemplateFile('usuarios/views/tmpl/listarSedes.html');
		$datos = $unaSede->listadoSedes($arreglo);
		
		foreach($datos['datos'] as $key => $value) {
			$sedesU->setCurrentBlock("ROW");
			$sedesU->setVariable('sede',$value['nombre_sede']);
			$sedesU->setVariable('sede_id',$value['sede_id']);
			$sedesU->parseCurrentBlock("ROW");
		}	
		$sedesU->setVariable('COMODIN','');
		return $sedesU->get();
	}	

	function verUsuario($arreglo) {
		$this->template->loadTemplateFile('usuarios/views/tmpl/verUsuario.php');
		$this->template->setVariable('COMODIN','');
		$this->template->setVariable('titulo_accion','Ver Usuario');
		$this->template->setVariable('id',isset($arreglo['id'])?$arreglo['id']:'0');
		$this->template->setVariable('usuario',isset($arreglo['datosUsuario']['usuario'])?$arreglo['datosUsuario']['usuario']:'');
		$this->template->setVariable('perfil',isset($arreglo['datosUsuario']['nombre_perfil'])?$arreglo['datosUsuario']['nombre_perfil']:'');
		$this->template->setVariable('nombre_usuario',isset($arreglo['datosUsuario']['nombre_usuario'])?$arreglo['datosUsuario']['nombre_usuario']:'');
		$this->template->setVariable('apellido_usuario',isset($arreglo['datosUsuario']['apellido_usuario'])?$arreglo['datosUsuario']['apellido_usuario']:'');
		$this->template->setVariable('mail_usuario',isset($arreglo['datosUsuario']['mail_usuario'])?$arreglo['datosUsuario']['mail_usuario']:'');
		$this->template->setVariable('sede',isset($arreglo['datosUsuario']['nombre_sede'])?$arreglo['datosUsuario']['nombre_sede']:'');
		$this->template->show();
	}
}
?>