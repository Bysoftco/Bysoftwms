<?php
require_once(CLASSES_PATH.'BDControlador.php');
class LogAplicacionModelo extends BDControlador{
	
	function LogAplicacionModelo(){
		parent :: Manejador_BD();
	}
	
	function listarLog($arreglo){
		$db = $_SESSION['conexion'];
		
		$query = "SELECT sl.siglog_id,
		                 sl.siglog_id_usuario,
		                 su.sigusu_id as usuario,
		                 su.sigusu_nombres as nombre_usuario,
		                 su.sigusu_apellidos as apellido_usuario,
		                 sl.siglog_fecha_edicion,
		                 sl.siglog_ip_equipo,
		                 sl.siglog_modulo,
		                 sl.siglog_funcion
		            FROM sig_log sl,
		            	 sig_usuarios su
		           WHERE sl.siglog_id_usuario = su.id";
		
		if(isset($arreglo['filtroUsuario']) && !empty($arreglo['filtroUsuario'])){
			$query.=" AND sl.siglog_id_usuario = $arreglo[filtroUsuario] ";
		}
		if(isset($arreglo['filtroModulo']) && !empty($arreglo['filtroModulo'])){
			$query.=" AND sl.siglog_modulo = '$arreglo[filtroModulo]' ";
		}
		if(isset($arreglo['filtroFuncion']) && !empty($arreglo['filtroFuncion'])){
			$query.=" AND sl.siglog_funcion = '$arreglo[filtroFuncion]' ";
		}
		if(isset($arreglo['filtroDesde']) && !empty($arreglo['filtroDesde'])){
			$query.=" AND sl.siglog_fecha_edicion >= '$arreglo[filtroDesde] 00:00:00' ";
		}
		if(isset($arreglo['filtroHasta']) && !empty($arreglo['filtroHasta'])){
			$query.=" AND sl.siglog_fecha_edicion <= '$arreglo[filtroHasta] 23:59:59' ";
		}
		
		
		if(isset($arreglo['orden']) && !empty($arreglo['orden'])){
			$query.=" ORDER BY $arreglo[orden] ";
		}else{
			$query.=" ORDER BY sl.siglog_fecha_edicion DESC ";
		}
		
		$db->query($query);
		$mostrar = 20;
		$totalFilas=$db->countRows();
		//$retornar['paginacion']=$this->paginar($arreglo['page'],$totalFilas,$mostrar);
		
		$totalPag=$totalFilas/$mostrar;
		if($totalPag>(int)$totalPag){$totalPag=(int)$totalPag+1;}
		$retornar['totalPag']=$totalPag;
		
		$limit= ' LIMIT '. ($arreglo['page'] -1) * $mostrar . ',' . $mostrar;
		$query.=$limit;
		$db->query($query);
		$retornar['datos']=$db->getArray();
		return $retornar;
	}
	
	function listarLogMenu($arreglo){
		$db = $_SESSION['conexion'];
		
		$query = "SELECT sl.siglog_id,
		                 sl.siglog_id_usuario,
		                 su.sigusu_id as usuario,
		                 su.sigusu_nombres as nombre_usuario,
		                 su.sigusu_apellidos as apellido_usuario,
		                 sl.siglog_fecha_accion,
		                 sl.siglog_accion
		            FROM sig_log_menu sl,
		            	 sig_usuarios su
		           WHERE sl.siglog_id_usuario = su.id";
	
		if(isset($arreglo['filtroUsuario']) && !empty($arreglo['filtroUsuario'])){
			$query.=" AND sl.siglog_id_usuario = $arreglo[filtroUsuario] ";
		}
		if(isset($arreglo['filtroItem']) && !empty($arreglo['filtroItem'])){
			$query.=" AND sl.siglog_accion = '$arreglo[filtroItem]' ";
		}
		if(isset($arreglo['filtroDesde']) && !empty($arreglo['filtroDesde'])){
			$query.=" AND sl.siglog_fecha_accion >= '$arreglo[filtroDesde] 00:00:00' ";
		}
		if(isset($arreglo['filtroHasta']) && !empty($arreglo['filtroHasta'])){
			$query.=" AND sl.siglog_fecha_accion <= '$arreglo[filtroHasta] 23:59:59' ";
		}
		
		
		if(isset($arreglo['orden']) && !empty($arreglo['orden'])){
			$query.=" ORDER BY $arreglo[orden] ";
		}else{
			$query.=" ORDER BY sl.siglog_fecha_accion DESC ";
		}

		$db->query($query);
		$mostrar = 20;
		$totalFilas=$db->countRows();
		
		$totalPag=$totalFilas/$mostrar;
		if($totalPag>(int)$totalPag){$totalPag=(int)$totalPag+1;}
		$retornar['totalPag']=$totalPag;
		
		$limit= ' LIMIT '. ($arreglo['page'] -1) * $mostrar . ',' . $mostrar;
		$query.=$limit;
		$db->query($query);
		$retornar['datos']=$db->getArray();
		return $retornar;
	}
	
	function seleccionarSQL($arreglo){
		$db = $_SESSION['conexion'];
		
		$query = "SELECT sl.siglog_sql
		            FROM sig_log sl
		           WHERE sl.siglog_id = $arreglo[id]";
		$db->query($query);
		return $db->fetch();
	}
	
	function logMenu($arreglo){
		$db = $_SESSION['conexion'];
		
		$query = "INSERT INTO log_menu
		                     (usuarios_id,
		                      descripcion_log_id
		                     )
		               VALUES(".
		                 	$_SESSION['datos_logueo']['usuario_id'].",
		                 	'$arreglo[accionLog]'
		               )";
		$db->query($query);
	}
	
	function consultaGenerar($arreglo){
		$db = $_SESSION['conexion'];
		
		$query="SELECT sl.siglog_fecha_accion as Fecha_Accion,
		               sl.siglog_accion as Item_del_Menu,
		               su.sigusu_id as Usuario,
		               su.sigusu_nombres as Nombre_Usuario,
		               su.sigusu_apellidos as Apellido_Usuario,
		               sp.nombre as Perfil_Usuario
		               
				  FROM sig_log_menu sl,
				       sig_usuarios su,
				       sig_perfiles sp
				 WHERE sl.siglog_id_usuario = su.id
			 	   AND su.sigusu_perfil = sp.id";
		
		$db->query($query);
		return $db->getArray();
	}
	
	function consultaLog($arreglo){
		$db = $_SESSION['conexion'];
		
		$query="SELECT su.sigusu_id AS Usuario,
					   su.sigusu_nombres AS Nombre_Usuario,
					   su.sigusu_apellidos AS Apellido_Usuario,
					   sl.siglog_fecha_edicion AS Fecha_Edicion,
					   sl.siglog_modulo AS Modulo,
					   sl.siglog_funcion AS Funcion_Aplicada,
					   sl.siglog_sql
		               
				  FROM sig_log sl,
				       sig_usuarios su
				 WHERE sl.siglog_id_usuario = su.id
			   ";
		
		$db->query($query);
		return $db->getArray();
	}
}
?>