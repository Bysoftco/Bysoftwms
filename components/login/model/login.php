<?php
class LoginModelo {
	var $arbol;
	
	function LoginModelo() {
		$this->arbol = "";
	}
	
	function validar_usuario($arreglo) {
		$db = $_SESSION['conexion'];
		//AND us.sede_id = '$arreglo[sede_id]'               
    $query = "SELECT us.*, se.nombre AS nombre_sede, se.tipo_sede,clientes.numero_documento,razon_social as nombre_empresa
		          FROM sedes se,perfiles pe,usuarios us
		          LEFT JOIN clientes ON numero_documento=us.empresa_id
		          WHERE us.usuario = '$arreglo[usuario]'
		            AND us.clave = md5('$arreglo[clave]')
		            AND us.estado = 'A'
		            AND pe.estado = 'A'
		            AND us.perfil_id = pe.id
		            
                AND se.codigo = us.sede_id";

		$db->query($query);
		$total = $db->countRows();
		$rows = $db->fetch();
		
		if($total > 0) {
			// Captura de datos del usuario encontrado
			$_SESSION['datos_logueo']['nombre_empresa'] = $rows->nombre_empresa;
			$_SESSION['datos_logueo']['usuario'] = $rows->usuario;
			$_SESSION['datos_logueo']['usuario_id'] = $rows->id;
			$_SESSION['datos_logueo']['perfil_id'] = $rows->perfil_id;
			$_SESSION['datos_logueo']['nombre_usuario'] = $rows->nombre_usuario;
			$_SESSION['datos_logueo']['apellido_usuario'] = $rows->apellido_usuario;
			$_SESSION['sede'] = $rows->sede_id;
			$_SESSION['sede_tipo'] = $rows->tipo_sede;
      $_SESSION['nombre_sede'] = $rows->nombre_sede;
      $_SESSION['datos_logueo']['sesion'] = $rows->sesion;
	  
	  	if($_SESSION['datos_logueo']['perfil_id']==26){
				 $sql="SELECT * FROM sedes WHERE codigo='$arreglo[sede_id]'";
				 $db->query($sql);
				 $consulta = $db->fetch();
		
		
				 $_SESSION['sede']=$arreglo[sede_id];
				 $_SESSION['nombre_sede']=$consulta->nombre;
				 $_SESSION['datos_logueo']['nombre_empresa'] = $consulta->nombre;
			 }	 
	  
	  
	  
	  
	  
			return 'true';
		}
		return 'false';
	}

	function registra_sesion($arreglo) {
		$db = $_SESSION['conexion'];
		               
    $query = "UPDATE usuarios SET sesion = 1 WHERE (id = $arreglo[id])";

		$db->query($query);
		return 'true';
	}

	function registra_salida($arreglo) {
		$db = $_SESSION['conexion'];
		               
    $query = "UPDATE usuarios SET sesion = 0 WHERE (id = $arreglo[id])";

		$db->query($query);
		return 'true';
	}
	function findSede($arreglo) {
		$db = $_SESSION['conexion'];
		  $q= $_GET["q"];           
    $query = "SELECT * FROM sedes WHERE nombre like '%$q%' ";

		$db->query($query);
		 $arreglo = $db->getArray();
		 foreach($arreglo as $value)
	    {
			$nombre = $value['nombre'];
			//$nombre= $query;
			$codigo = $value['codigo'];
      		echo "$nombre|$codigo\n";
		}
	}

	function armar_menu_principal() {
		$this->crearArbol('id','nombre','id_padre',0,'-');
		return $this->arbol;
	}
	
	function crearArbol($id_field, $show_data, $link_field, $parent, $prefix) {
		$db = $_SESSION['conexion'];
		
    $sql = "SELECT m.* 
            FROM menu m, permisos_menu pm
            WHERE estado='A'
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
  
  function build_list($table, $code, $name_camp, $where = '') {
    $db = $_SESSION['conexion'];

    $sql = "SELECT $code,$name_camp FROM $table $where";

    $db->query($sql);
    $result = $db->GetArray();
    $array = array();

    foreach ($result as $key => $index) {
      foreach ($index as $keyAux => $value) {
        $je = $keyAux;
        $$je = $value;
      }
      $array[$$code] = $$name_camp;
    }
    return $array;
  }
  
  function armSelect($array, $title = '-', $seleccion = 'NA', $maxCaracteres = 50) {
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
}
?>