<?php
class UsuariosVista {
  function UsuariosVista() {
		$this->template = new HTML_Template_IT();
	}

	function template_inicial($arreglo, $datos, $paginacion, $filtros) {
		$plantilla = new HTML_Template_IT();
		$plantilla->loadTemplateFile(COMPONENTS_PATH . 'usuarios/views/tmpl/templateArbol.php');
		$plantilla->setVariable('COMODIN', '');
		ob_start();
		$this->listadoUsuarios($arreglo, $datos, $paginacion);
		$contenido = ob_get_contents();
		ob_end_clean();
		$plantilla->setVariable('contenido',$contenido);
		$plantilla->setVariable('menu',$this->filtros($filtros));
		$plantilla->show();
	}

	function listadoUsuarios($arreglo) {
		$this->template->loadTemplateFile(COMPONENTS_PATH . 'usuarios/views/tmpl/listadoUsuarios.php');
		$this->template->setVariable('COMODIN', '');
		$this->template->setVariable('paginacion',$arreglo['datosUsuario']['paginacion']);
		$this->template->setVariable('pagina',$arreglo['pagina']);
		$this->template->setVariable('verAlerta','none');
		
		$this->template->setVariable('orden',$arreglo['orden']);
		$this->template->setVariable('id_orden',$arreglo['id_orden']);
		$this->template->setVariable('campoBuscar',$arreglo['buscar']);
		
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
		$this->template->loadTemplateFile(COMPONENTS_PATH . 'usuarios/views/tmpl/editarUsuario.php');
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
		$this->template->show();
	}

	function verUsuario($arreglo) {
		$this->template->loadTemplateFile(COMPONENTS_PATH . 'usuarios/views/tmpl/verUsuario.php');
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