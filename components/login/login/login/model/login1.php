<?php
class LoginModelo{

	var $arbol;
	
	function LoginModelo(){
		$this->arbol="";
	}
	
	function validar_usuario($arreglo){
		$db = $_SESSION['conexion'];
		
		$query="SELECT us.*,
		 			   se.nombre as nombre_sede,
		 			   se.tipo_sede
		          FROM usuarios us,
		          	   perfiles pe,
		          	   sedes se
		         WHERE us.usuario='$arreglo[usuario]'
		           AND us.clave = md5('$arreglo[clave]')
		           AND us.estado = 'A'
		           AND pe.estado = 'A'
		           AND us.perfil_id = pe.id
		           AND se.codigo = us.sede_id";
				   echo $query;
		
		$db->query($query);
		$total=$db->countRows();
		$rows=$db->fetch();
		
		if($total>0){
			
			$_SESSION['datos_logueo']['usuario']          = $rows->usuario;
			$_SESSION['datos_logueo']['usuario_id']       = $rows->id;
			$_SESSION['datos_logueo']['perfil_id']        = $rows->perfil_id;
			$_SESSION['datos_logueo']['nombre_usuario']   = $rows->nombre_usuario;
			$_SESSION['datos_logueo']['apellido_usuario'] = $rows->apellido_usuario;
			$_SESSION['sede']                             =$rows->sede_id;
			$_SESSION['sede_tipo']			      =$rows->tipo_sede;
			return 'true';
		}
		return 'false';
	}
	
	function armar_menu_principal(){
		$this->crearArbol('id','nombre','id_padre',0,'-');
		return $this->arbol;
	}
	
	function crearArbol($id_field, $show_data, $link_field, $parent, $prefix){
		$db = $_SESSION['conexion'];
		
	    $sql="select m.* 
	            from menu m,
	                 permisos_menu pm
	           where estado='A'
	             AND pm.perfil_id = ".$_SESSION['datos_logueo']['perfil_id']."
	             AND pm.menu_id = m.id
	             AND m.".$link_field."=".$parent." ORDER BY m.orden";
	    $db->query($sql);
	    $arreglo = $db->getArray();
	    
	    $clase='';
	    
	    if($parent==0){
	    	$clase='id="treemenu1"';
	    }
	    
	    if(count($arreglo)>0){
	    	$this->arbol.='<ul>';
	    }
	    foreach($arreglo as $value)
	    {
	    	if($value[$show_data]=='Salir'){
	    		$this->arbol.='<li><a onclick="javascript:logMenu(\''.$value['enlace'].'\')" href="'.$value['enlace'].'">'.$value[$show_data].'</a>';
	    	}
	    	else{
	    		if($value['tipo_menu']!='normal'){
	    			$this->arbol.='
	    				<li><div class="'.$value['tipo_menu'].'"><a onclick="javascript:llamado_popups(\''.$value['enlace'].'\')" href="">'.$value[$show_data].'</a></div>';
	    		}
	    		else{
	    			$this->arbol.='<li><a href="javascript:llamado_ajax(\''.$value['enlace'].'\')">'.$value[$show_data].'</a>';
	    		}
	    	}
	    	$this->crearArbol($id_field,$show_data,$link_field,$value[$id_field],$prefix.$prefix);
	    	$this->arbol.='</li>';
	    } 
		if(count($arreglo)>0){
	    	$this->arbol.='</ul>';
	    }
	}
}
?>