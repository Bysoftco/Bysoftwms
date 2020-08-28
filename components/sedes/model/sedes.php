<?php 
require_once(CLASSES_PATH.'BDControlador.php');

class SedesModelo extends BDControlador {
  var $id;
  var $usuario;
	var $clave;
	var $perfil_id;
  var $nombre_usuario;
  var $apellido_usuario;
  var $mail_usuario;
  var $fecha_creacion;
  var $sede_id;
  var $estado;
  
  var $table_name = "usuarios";
  var $module_directory = 'sedes';
  var $object_name = "SedesModelo";
	
  var $campos = array('id','usuario','clave','perfil_id','nombre_usuario','apellido_usuario','mail_usuario','fecha_creacion',
                      'sede_id','estado');

  function SedesModelo() {
    parent :: Manejador_BD();
    $this->arbol = "";
  }
	
  function construir_lista($arreglo) {
    $db = $_SESSION['conexion'];
    
    $query = "SELECT se.codigo, se.nombre AS nombre_sede
              FROM usuarios us, sedes se
              WHERE us.usuario = '$arreglo[usuario_login]'
                AND us.estado = 'A'
                AND se.codigo = us.sede_id";

    if($_SESSION['datos_logueo']['perfil_id']==26){
		$query = "SELECT codigo, nombre as nombre_sede FROM sedes WHERE cdzonafranca=652 ";
	}	
	
	$db->query($query);
    $result = $db->GetArray();
    $array = array();

    foreach ($result as $key => $index) {
      foreach ($index as $keyAux => $value) {
        $je = $keyAux;
        $$je = $value;
      }
      $array[$codigo] = $nombre_sede;
    }
    return $array;
  }
  
  function armarSelect($array, $title = '-', $seleccion = 'NA', $maxCaracteres = 50) {
    $returnValue = "<OPTION VALUE=\"\" SELECTED>$title</OPTION> \n";
    foreach ($array as $key => $value) {
      $selected = ($seleccion == $key) ? ' SELECTED' : '';
      $returnValue.= "<OPTION VALUE=\""
        . $key
        . "\"$selected>"
        . htmlentities(ucwords(substr($value, 0, $maxCaracteres)), ENT_QUOTES) . "</OPTION>\n";
    }
    return $returnValue;
  }
  
	function validar_infousuario($arreglo) {
		$db = $_SESSION['conexion'];
    $usuario = $_SESSION['datos_logueo']['usuario'];
		//AND us.sede_id = '$arreglo[sede]'               
    $query = "SELECT us.*,
		 			      se.nombre as nombre_sede,
		 			      se.tipo_sede,
						  razon_social as nombre_empresa
		          FROM usuarios us
				   LEFT JOIN clientes ON numero_documento=us.empresa_id
				  ,
				  
		            perfiles pe,
		            sedes se
		          WHERE us.usuario='$usuario'
		            AND us.estado = 'A'
		            AND pe.estado = 'A'
		            AND us.perfil_id = pe.id
		            
                AND se.codigo = us.sede_id";

		$db->query($query);
		$total=$db->countRows();
		$rows=$db->fetch();
		
		if($total > 0) {
			$_SESSION['datos_logueo']['usuario'] = $rows->usuario;
			$_SESSION['datos_logueo']['usuario_id'] = $rows->id;
			$_SESSION['datos_logueo']['perfil_id'] = $rows->perfil_id;
			$_SESSION['datos_logueo']['nombre_usuario'] = $rows->nombre_usuario;
			$_SESSION['datos_logueo']['apellido_usuario'] = $rows->nombre_empresa;
			$_SESSION['sede'] = $arreglo[sede];
			$_SESSION['sede_tipo'] = $rows->tipo_sede;
			
			//$_SESSION['datos_logueo']['nombre_empresa']=$rows->nombre_empresa;
			
    		 
			$sql="SELECT * FROM sedes WHERE codigo='$arreglo[sede]'";
			
				 $db->query($sql);
				 $consulta = $db->fetch();
				 $_SESSION['nombre_sede'] = $consulta->nombre;
			$_SESSION['datos_logueo']['nombre_empresa']=$consulta->nombre;
				 
		
		 
			return 'true';
		}
		return 'false';
	}
  
	function armar_menu_principal() {
		$this->crearArbol('id','nombre','id_padre',0,'-');
		return $this->arbol;
	}
	
	function crearArbol($id_field, $show_data, $link_field, $parent, $prefix) {
		$db = $_SESSION['conexion'];
		
    $sql="SELECT m.* 
          FROM menu m, permisos_menu pm
          WHERE estado='A'
            AND pm.perfil_id = ".$_SESSION['datos_logueo']['perfil_id']."
            AND pm.menu_id = m.id
            AND m.".$link_field."=".$parent." ORDER BY m.orden";
            
	    //echo  $sql; die();
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