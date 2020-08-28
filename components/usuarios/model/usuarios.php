<?php 
require_once(CLASSES_PATH.'BDControlador.php');
class UsuariosModelo extends BDControlador{
	
	var $id;
	var $usuario;
	var $perfil_id;
	var $nombre_usuario;
	var $apellido_usuario;
	var $mail_usuario;
	//var $sede_id;
	
	var $table_name = "usuarios";
	var $module_directory= 'usuarios';
	var $object_name = "UsuariosModelo";
	  
	var $campos = array('id', 'usuario', 'perfil_id', 'nombre_usuario', 'apellido_usuario', 'mail_usuario', 'sede_id');
	
	function UsuariosModelo(){
		parent :: Manejador_BD();
	}
	
	function listadoUsuarios($arreglo){
		$db = $_SESSION['conexion'];
		
		$orden = " us.fecha_creacion DESC ";
		$buscar = "";
		if(isset($arreglo['orden']) && !empty($arreglo['orden'])){
			$orden= " $arreglo[orden] $arreglo[id_orden]";
		}
		if(isset($arreglo['buscar']) && !empty($arreglo['buscar'])){
			$buscar= " AND (us.usuario LIKE '%$arreglo[buscar]%' 
						OR us.nombre_usuario LIKE '%$arreglo[buscar]%'
						OR us.apellido_usuario LIKE '%$arreglo[buscar]%'
						OR us.mail_usuario LIKE '%$arreglo[buscar]%'
						OR us.fecha_creacion LIKE '%$arreglo[buscar]%'
						OR pe.nombre LIKE '%$arreglo[buscar]%'
						OR se.nombre LIKE '%$arreglo[buscar]%'
						) ";
		}
		
		$query = "SELECT us.*,
						 pe.nombre as nombre_perfil,
						 se.nombre as nombre_sede
					FROM usuarios us,
					     perfiles pe,
					     sedes se
				   WHERE us.estado<>'E'
				     AND us.perfil_id = pe.id
				     AND se.codigo = us.sede_id
				     $buscar
				ORDER BY $orden";
		
		$db->query($query);
		$mostrar = 15;
		$retornar['paginacion']=$this->paginar($arreglo['pagina'],$db->countRows(),$mostrar);
		
		$limit= ' LIMIT '. ($arreglo['pagina'] -1) * $mostrar . ',' . $mostrar;
		$query.=$limit;
		$db->query($query);
		$retornar['datos']=$db->getArray();
		return $retornar;
	}
	
	function validarRepetido($arreglo){
		$db = $_SESSION['conexion'];

		$query="SELECT * FROM usuarios WHERE usuario = '$arreglo[usuario]' AND sede_id = '$arreglo[sede]' AND estado <> 'E'";
		
		if(isset($arreglo['id']) && !empty($arreglo['id'])){
			$query.=" AND id <> $arreglo[id]";
		}
		$db->query($query);
		return $db->getArray();
	}
	
	function validarClave($arreglo){
		$db = $_SESSION['conexion'];
		$query="SELECT * FROM usuarios WHERE id=$arreglo[id] AND clave=md5('$arreglo[clave]')";
	
		$db->query($query);
		return $db->getArray();
	}
	
	function infoUsuario($arreglo){
		$db = $_SESSION['conexion'];
		$query="SELECT us.id,
					   us.usuario,
					   us.perfil_id,
					   us.nombre_usuario,
					   us.apellido_usuario,
					   us.mail_usuario,
					   us.fecha_creacion,
					   us.estado,
					   us.sede_id,
					   pe.nombre as nombre_perfil,
					   se.nombre as nombre_sede
					   
		          FROM usuarios us,
		               perfiles pe,
		               sedes se
		         WHERE us.id = $arreglo[id]
		           AND us.perfil_id = pe.id
		           AND se.codigo = us.sede_id";
		
		$db->query($query);
		$info = $db->getArray();
		return $info[0];
	}
	
	function editarClave($arreglo){
		$db = $_SESSION['conexion'];
		$query="UPDATE usuarios us
				   SET us.clave = md5('$arreglo[claveNueva]')
		         WHERE us.id = $arreglo[id]";
		
		$db->query($query);
	}
	
	function cambiarEstadoUsuario($arreglo){
		$db = $_SESSION['conexion'];
		$query="UPDATE usuarios
				   SET estado = '$arreglo[estado]'
				 WHERE id=$arreglo[id]";
		$db->query($query);
	}
	
		
	function listadoSedes($arreglo){
		//var_dump($arreglo);
		$db = $_SESSION['conexion'];
		
		$orden = " us.fecha_creacion DESC ";
		$buscar = "";
		if(isset($arreglo['orden']) && !empty($arreglo['orden'])){
			$orden= " $arreglo[orden] $arreglo[id_orden]";
		}
		
		$usuario=$arreglo['datosUsuario']['usuario'];
		$query= "
		SELECT DISTINCT sede_id,nombre AS nombre_sede 
			FROM usuarios,sedes
			WHERE usuarios.sede_id=sedes.codigo
			AND usuario='$usuario'
		";
		
	 	$db->query($query);
		$retornar['datos']=$db->getArray();
		return $retornar;
	}
	
	function existeSede($arreglo){
		$db = $_SESSION['conexion'];
		$query="SELECT sede_id FROM usuarios WHERE sede_id='$arreglo[sede_id]' and  usuario='$arreglo[usuario]'";
		
		$db->query($query);
		$total = $db->countRows();
		return $total;
	}
	
	function creaUsuarioSede($arreglo){
		$clave=$this->getClave($arreglo);
		$db = $_SESSION['conexion'];
		$query="INSERT INTO 	  usuarios(usuario,clave,perfil_id,nombre_usuario,apellido_usuario,mail_usuario,fecha_creacion,sede_id,empresa_id,estado,sesion)
VALUES('$arreglo[usuario]','$clave[clave]',$arreglo[perfil_id],'$arreglo[nombre_usuario]','$arreglo[apellido_usuario]','$arreglo[mail_usuario]',CURDATE(),'$arreglo[sede_id]','','A',0)";
		//echo $query;
		$db->query($query);
	}
	
	function getClave($arreglo){
		$db = $_SESSION['conexion'];
		$query="SELECT clave  FROM usuarios WHERE  usuario='$arreglo[usuario]'";
	
		$db->query($query);
		$info = $db->getArray();
		return $info[0];
		
	}
	
	function borrarSede($arreglo){
		$db = $_SESSION['conexion'];
		$query="DELETE  FROM usuarios WHERE sede_id='$arreglo[sede_id]' and  usuario='$arreglo[usuario]'";
		
		$db->query($query);
		
		
	}
	
}
?>