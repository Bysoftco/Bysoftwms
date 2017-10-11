<?php 
class PerfilesVista{
	
	var $template;
	
	function PerfilesVista(){
		$this->template = new HTML_Template_IT();
	}
	
	function listadoPerfiles($arreglo){
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'perfiles/views/tmpl/listadoPerfiles.php' );
		$this->template->setVariable('COMODIN'           , '' );
		$this->template->setVariable('pagina'            , $arreglo['pagina'] );
		$this->template->setVariable('verAlerta'         , 'none');
		$this->template->setVariable('paginacion'        , $arreglo['datosPerfil']['paginacion']);
		
		$this->template->setVariable('orden'             , $arreglo['orden'] );
		$this->template->setVariable('id_orden'          , $arreglo['id_orden'] );
		$this->template->setVariable('campoBuscar'       , $arreglo['buscar'] );
		
		if(isset($arreglo['alerta_accion'])){
			$this->template->setVariable('alerta_accion' , $arreglo['alerta_accion'] );
			$this->template->setVariable('verAlerta'     , 'block');
		}
		
		$codbagcolor=1;
		foreach($arreglo['datosPerfil']['datos'] as $value){
			$this->template->setCurrentBlock("ROW");
			if($codbagcolor==1){
			$this->template->setVariable('id_tr_estilo','tr_blanco');
			$codbagcolor=2;
			}else{
			$this->template->setVariable('id_tr_estilo','tr_gris_cla');	
			$codbagcolor=1;	
			}
			$this->template->setVariable('id'               , $value['id']);
			$this->template->setVariable('nombre'           , $value['nombre']);
			$this->template->setVariable('descripcion'      , $value['descripcion']);
			
			$estado='';
			switch($value['estado']){
				case 'A' : $estado='Activo <img src="img/estados/activo.png" width="15" height="15" border="0" />'; break;
				case 'I' : $estado='Inactivo'; break;
			}
			$this->template->setVariable('idestado'         , $value['estado']);
			$this->template->setVariable('estado'           , $estado);
			$this->template->setVariable('fecha_creacion'   , $value['fecha_creacion']);
			$this->template->parseCurrentBlock("ROW");
		}		
		$this->template->show();
	}
	
	function verPerfil($arreglo){
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'perfiles/views/tmpl/verPerfil.php' );
		$this->template->setVariable('COMODIN', '' );
		$this->template->setVariable('id', $arreglo['datosPerfil']['id']);
		$this->template->setVariable('nombre_perfil', $arreglo['datosPerfil']['nombre']);
		$this->template->setVariable('descripcion', $arreglo['datosPerfil']['descripcion']);
		$this->template->setVariable('menuPermisos', $arreglo['menuPermisos']);
		$this->template->show();
	}
	
	function editarPerfil($arreglo){
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'perfiles/views/tmpl/editarPerfil.php' );
		$this->template->setVariable('COMODIN', '' );
		$this->template->setVariable('titulo_accion', 'Editando Perfil' );
		$this->template->setVariable('id', $arreglo['datosPerfil']['id']);
		$this->template->setVariable('nombre_perfil', $arreglo['datosPerfil']['nombre']);
		$this->template->setVariable('descripcion', $arreglo['datosPerfil']['descripcion']);
		$this->template->setVariable('menuPermisos', $arreglo['menuPermisos']);
		$this->template->show();
	}
	
	function agregarPerfil($arreglo){
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'perfiles/views/tmpl/editarPerfil.php' );
		$this->template->setVariable('COMODIN', '' );
		$this->template->setVariable('titulo_accion', 'Creando Perfil' );
		$this->template->setVariable('menuPermisos', $arreglo['menuPermisos']);
		$this->template->show();
	}
}
?>