<?php 
require_once(CLASSES_PATH.'BDControlador.php');

class TrackingModelo extends BDControlador {
  var $id;
  var $sede;
  var $do_asignado;
  var $doc_tte;
	var $por_cuenta;
  var $fecha;
  var $remite;
  var $destino;
  var $asunto;
  var $adjuntos;
  var $mensaje;
	var $creador;
	var $forma;
  
  var $table_name = "tracking";
  var $module_directory = 'tracking';
  var $object_name = "TrackingModelo";
	
  var $campos = array('id','sede','do_asignado','doc_tte','por_cuenta','fecha','remite','destino','asunto','adjuntos','mensaje','creador','forma');

  function TrackingModelo() {
    parent :: Manejador_BD();
  }
	
  function listadoTracking($arreglo) {
    $db = $_SESSION['conexion'];
		$sede = $_SESSION['sede'];
	
    $orden = " fecha+id DESC ";
    $buscar = "";
    if(isset($arreglo['orden']) && !empty($arreglo['orden'])) {
      $orden = " $arreglo[orden] $arreglo[id_orden]";
    }
    if(isset($arreglo['buscar']) && !empty($arreglo['buscar'])) {
      $buscar = " AND (id LIKE '%$arreglo[buscar]%' 
                  OR fecha LIKE '%$arreglo[buscar]%'
									OR cl.razon_social LIKE '%$arreglo[buscar]%'									
                  OR remite LIKE '%$arreglo[buscar]%'
                  OR destino LIKE '%$arreglo[buscar]%'
                  OR doc_tte LIKE '%$arreglo[buscar]%'
                  OR asunto LIKE '%$arreglo[buscar]%'
                  OR creador LIKE '%$arreglo[buscar]%'
                  OR forma LIKE '%$arreglo[buscar]%')";
    }
		
    $query = "SELECT *,cl.razon_social FROM tracking,clientes cl
              WHERE por_cuenta = cl.numero_documento
                AND tracking.sede = '$sede'$buscar";
    							
		//Prepara la condición de filtro
		if(!empty($arreglo['nitt'])) {
      $query .= " AND por_cuenta = '$arreglo[nitt]'";
    }
		if(!empty($arreglo['fechadesdet'])) {
      $query .= " AND fecha >= '$arreglo[fechadesdet]'";
    }
		if(!empty($arreglo['fechahastat'])){
      $query .= " AND fecha <= '$arreglo[fechahastat]'";
    }
		if(!empty($arreglo['doasignadot'])) {
      $query .= " AND do_asignado = '$arreglo[doasignadot]'";
    }
    if(!empty($arreglo['docttet'])) {
      $query .= "AND doc_tte = '$arreglo[docttet]'";
    }
    if(!empty($arreglo['emaildestino'])) {
      $query .= " AND destino = '$arreglo[emaildestino]'";
    }
		//Ordena el Listado
		$query .= " ORDER BY $orden";


    //echo $query;
    $db->query($query);
    $mostrar = 15;
    $retornar['paginacion'] = $this->paginar($arreglo['pagina'],$db->countRows(),$mostrar);
		
    $limit = ' LIMIT '. ($arreglo['pagina'] -1) * $mostrar . ',' . $mostrar;
    $query .= $limit;
    $db->query($query);
    $retornar['datos'] = $db->getArray();
    return $retornar;
  }
  
	function findCliente($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT numero_documento,razon_social,correo_electronico,v.nombre AS nvendedor FROM clientes, vendedores v WHERE (razon_social LIKE '%$arreglo[q]%') AND (v.codigo = vendedor)";

		$db->query($query);
    return $db->getArray();
	}

  function datosTracking($arreglo) {
    $db = $_SESSION['conexion'];
    $sede = $_SESSION['sede'];

    $query = "SELECT *,tracking.do_asignado,cl.razon_social,dob.sigla FROM tracking,clientes cl,do_bodegas dob WHERE id = $arreglo[id] AND por_cuenta = cl.numero_documento AND tracking.sede = '$sede' AND dob.sede = '$sede'";

    $db->query($query);
    return $db->getArray();
  }
  
  function eliminarTracking($arreglo) {
    $db = $_SESSION['conexion'];
    $sede = $_SESSION['sede'];
    
    $query = "DELETE FROM tracking WHERE id = $arreglo[id] AND sede = '$sede'";

    $db->query($query);
  }
}
?>