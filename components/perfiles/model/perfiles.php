<?php 
require_once(CLASSES_PATH.'BDControlador.php');

class PerfilesModelo extends BDControlador {	
	var $id;
	var $nombre;
	var $descripcion;
	var $estado;
	
	var $table_name = "perfiles";
	var $module_directory= 'perfiles';
	var $object_name = "PerfilesModelo";
	  
	var $campos = array('id','nombre','descripcion','estado');
	  
	function PerfilesModelo() {
		parent :: Manejador_BD();
		$this->estado = 'A';
	}
	
	function listadoPerfiles($arreglo) {
		$db = $_SESSION['conexion'];
		
		$orden = " fecha_creacion DESC ";
		$buscar = "";
		if(isset($arreglo['orden']) && !empty($arreglo['orden'])) {
			$orden= " $arreglo[orden] $arreglo[id_orden]";
		}
		if(isset($arreglo['buscar']) && !empty($arreglo['buscar'])) {
			$buscar= " AND (nombre LIKE '%$arreglo[buscar]%' 
						OR descripcion LIKE '%$arreglo[buscar]%'
						OR fecha_creacion LIKE '%$arreglo[buscar]%'
						) ";
		}

		$query="SELECT * FROM perfiles WHERE estado<>'E' $buscar ORDER BY $orden ";
		$db->query($query);
		$mostrar = 15;
		$retornar['paginacion'] = $this->paginar($arreglo['pagina'],$db->countRows(),$mostrar);
		$limit = ' LIMIT '. ($arreglo['pagina'] -1) * $mostrar . ',' . $mostrar;
		$query .= $limit;
		$db->query($query);
		$retornar['datos'] = $db->getArray();
		return $retornar;
	}
	
	function verPerfil($arreglo) {
		$db = $_SESSION['conexion'];
		$query = "SELECT p.id, p.nombre, p.descripcion, p.estado
		          FROM perfiles p WHERE p.id=$arreglo[id]";

		$db->query($query);
		$datos = $db->getArray();
		return $datos[0];
	}
	
	function armar_menu_principal($arreglo) {
		$this->crearArbol($arreglo,'id','nombre','id_padre',0,'-');
		return $this->arbol;
	}
	
	function crearArbol($arreglo, $id_field, $show_data, $link_field, $parent, $prefix) {
		
		$db = $_SESSION['conexion'];
	
	  $sql = "SELECT m.id,m.id_padre,m.tipo_menu,m.nombre,m.enlace,
		               m.orden,m.estado,pm.menu_id
		        FROM menu m
		        	LEFT JOIN permisos_menu pm ON m.id = pm.menu_id AND pm.perfil_id = $arreglo[id]
		        	LEFT JOIN perfiles p ON pm.perfil_id = p.id
	          WHERE m.estado='A' AND m.".$link_field."=".$parent."
							ORDER BY m.orden";

    $db->query($sql);
    $datos = $db->getArray();

    if(count($arreglo)>0) {
    	$this->arbol.='<ul>';
    }
    foreach($datos as $value) {
      $checked = '';
    	if($value['menu_id']!=NULL) {
    		$checked = 'checked';	
    	}
    	$this->arbol .= '<li><input name="permisos[]" value="'.$value['id'].'" type="checkbox" '.$checked.' '.$arreglo['disabled'].'/>'.$value[$show_data];
    	$this->crearArbol($arreglo, $id_field,$show_data,$link_field,$value[$id_field],$prefix.$prefix);
    	$this->arbol .= '</li>';
	  } 
		if(count($arreglo)>0) {
	    $this->arbol .= '</ul>';
	  }
	}
	
	function guardarGeneralesPerfil($arreglo) {
		$db = $_SESSION['conexion'];

		$query = "UPDATE perfiles SET nombre = '$arreglo[nombre]', descripcion = '$arreglo[descripcion]'
							WHERE id=$arreglo[id]";

		$db->query($query);
	}
	
	function borrarPermisos($arreglo) {
		$db = $_SESSION['conexion'];

		$query = "DELETE FROM permisos_menu
							WHERE perfil_id=$arreglo[id]";

		$db->query($query);
		$this->log($_SESSION['datos_logueo']['usuario_id'], $_SERVER['REMOTE_ADDR'],'Perfiles',base64_encode($query),'borrarPermisos');
	}
	
	function asignarPermisos($cadena) {
		$db = $_SESSION['conexion'];

		$query="INSERT INTO permisos_menu(perfil_id,menu_id) VALUES $cadena";
		$db->query($query);
		$this->log($_SESSION['datos_logueo']['usuario_id'], $_SERVER['REMOTE_ADDR'],'Perfiles',base64_encode($query),'asignarPermisos');
	}
	
	function cambiarEstadoPerfil($arreglo) {
		$db = $_SESSION['conexion'];

		$query = "UPDATE perfiles SET estado = '$arreglo[estado]'
							WHERE id=$arreglo[id]";
		$db->query($query);
	}
}
?>