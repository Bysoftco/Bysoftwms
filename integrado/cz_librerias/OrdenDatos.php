<?php
require_once(DB.'BDControlador.php');
require_once("LevanteDatos.php");	

class Orden extends BDControlador {
  var $db;

	function Orden() {
		$this->db = $_SESSION['conexion'];
		$this->estilo_error = "ui-state-error";
		$this->estilo_ok = "ui-state-highlight";
	}

	function listarOrdenes($arregloDatos) {
		$this->datosOrden($arregloDatos);
	}

	function datosOrden($arregloDatos) {
		$sede = $_SESSION['sede'];
		if(empty($arregloDatos['order_by'])) {
			$arregloDatos['order_by'] = 'do_asignado';
			$arregloDatos['orden'] = ' ASC';
		}

		$sql = "SELECT  do_asignados.do_asignado,
										do_asignados.fecha,
										do_asignados.tipo_documento,
										td.nombre AS tipo_documento_nombre,
										do_asignados.doc_tte,
                    do_asignados.manifiesto,
										do_asignados.factura,
										do_asignados.bodega,
										do_bodegas.sigla,
										do_bodegas.nombre AS bodega_nombre,
										por_cuenta,
										razon_social,
										numero_documento,
                    correo_electronico,
										do_asignados.obs,
										do_asignados.reasignado,
										do_asignados.codigo,
										do_asignados.prefactura,
										do_asignados.tipo_operacion,
										tipop.nombre AS operacion_nombre,
										do_asignados.ind_cons,
										do_asignados.ind_fragil,
										do_asignados.ind_hielo,
										do_asignados.ind_asig,
										do_asignados.ind_venci,
										do_asignados.ind_ubica,
                    gi.codigo AS zfcode
						FROM do_asignados,do_bodegas,tipos_operacion tipop,tipos_documentos_transportes td,clientes
              LEFT JOIN grupo_items gi ON clientes.razon_social = gi.nombre
						WHERE do_asignados.bodega = do_bodegas.codigo
							AND numero_documento = por_cuenta
							AND do_asignados.sede = '$sede'
							AND do_asignados.tipo_operacion = tipop.codigo
							AND do_asignados.tipo_documento = td.codigo";

		if(!empty($arregloDatos['do_asignado'])) {
			$sql .= " AND do_asignados.do_asignado='$arregloDatos[do_asignado]'";
		}
		if(!empty($arregloDatos['codigo'])) {
			$sql .= " AND do_asignados.codigo='$arregloDatos[codigo]'";
		}
		if(!empty($arregloDatos['reasignado'])) {
			$sql .= " AND do_asignados.factura <> 0";
		}
		if(!empty($arregloDatos['paraFacturar'])) {
			$sql .= " AND do_asignados.factura = 0";
		}
		if(!empty($arregloDatos['por_cuenta_filtro'])) {
			$sql .= " AND do_asignados.por_cuenta = '$arregloDatos[por_cuenta_filtro]'";
		}
		if(!empty($arregloDatos['ubicacion_filtro'])) {
			$sql .= " AND do_asignados.bodega = '$arregloDatos[ubicacion_filtro]'";
		}
		if(!empty($arregloDatos['estado_filtro'])) {
			$sql .= " AND do_asignados.estado = '$arregloDatos[estado_filtro]'";
		}
		if(!empty($arregloDatos['accion'])) {  // Si la consulta se lanza desde reapertura
			$sql .= " AND do_asignados.estado in (1,5)";
		}
		if(!empty($arregloDatos['fecha_inicio']) and !empty($arregloDatos['fecha_fin'])) {
			$sql .= " AND do_asignados.fecha >= '$arregloDatos[fecha_inicio]' AND do_asignados.fecha <= '$arregloDatos[fecha_fin]'";
		}
		if(!empty($arregloDatos['doc_filtro'])) {
			$sql .= " AND do_asignados.doc_tte = '$arregloDatos[doc_filtro]'";
		}
		if(!empty($arregloDatos['do_filtro'])) {
			$sql .= " AND do_asignados.do_asignado = '$arregloDatos[do_filtro]' ";
		}
		$sql .= " ORDER BY  $arregloDatos[order_by] $arregloDatos[orden]";
		if($arregloDatos['metodo'] == 'excel') { return $sql; }

		$this->db->query($sql);
		$rows = $this->db->countRows();
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			$this->mensaje = "Error al consultar Las ordenes ";
			$this->estilo	= $this->estilo_error;
			return TRUE;
		}
		if($rows == 0) {
			$this->mensaje = "No hay registros para mostrar ";
			$this->estilo	= $this->estilo_error;
		}
	}

	function findCliente($arregloDatos) {
		$sql = "SELECT numero_documento,razon_social,correo_electronico,digito_verificacion,v.nombre AS nvendedor FROM clientes,vendedores v
      WHERE (razon_social LIKE '%$arregloDatos[q]%' OR  numero_documento  LIKE '%$arregloDatos[q]%')           
              AND (v.codigo = vendedor)";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			// Configuración del Manipulador de Error
			/*set_error_handler("$this->db->tipoError");*/
			echo $sql;
			return TRUE;
		}
	}
  
	function existeZFCode($arregloDatos) {
		$sql = "SELECT codigo FROM grupo_items WHERE (numide = '$arregloDatos[numdoc]')";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		} else {
			$datos = $this->db->fetch();
			return $datos->codigo;
		}
	}

	function existeGrupoItems($arregloDatos) {
		$sql = "SELECT * FROM grupo_items WHERE (numide = '$arregloDatos[por_cuenta]')";

		$this->db->query($sql);
		$rows = $this->db->countRows();
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		}

    //Verifica existencia de Grupo Item
    if($rows!=0) return TRUE;
    else return FALSE;
	}

	function existeCodigoRef($arregloDatos) {
		$sql = "SELECT * FROM referencias WHERE (codigo_ref = '$arregloDatos[documento_title]')";

		$this->db->query($sql);
		$rows = $this->db->countRows();
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		}

    //Verifica existencia del Documento en Referencia
    if($rows!=0) return TRUE;
    else return FALSE;
	}

	function findReferencia($arregloDatos) {
		$sql = "SELECT codigo,nombre
						FROM referencias WHERE ( nombre LIKE '%$arregloDatos[q]%') ";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		}
	}
	
	function findModelo($arregloDatos) {
		$sql = "SELECT modelo AS codigo,modelo AS nombre
						FROM inventario_entradas WHERE (modelo LIKE '%$arregloDatos[q]%') ";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		} else {
			return $this->db->getArray();
		}
	}

	function findClientet($arregloDatos) {
		$sql = "SELECT razon_social,correo_electronico,v.nombre AS nombre_vendedor FROM clientes
              INNER JOIN vendedores v ON (v.codigo = vendedor)
            WHERE (numero_documento = '$arregloDatos[por_cuenta]')";
    
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		} else {
	    return $this->db->fetch();
		}
	}

	function findConductor($arregloDatos) {
		$sql = "SELECT codigo,conductor_nombre,conductor_identificacion,placa FROM camiones WHERE placa LIKE '%$arregloDatos[q]%'";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		} else {
			return $this->db->getArray();
		}
	}
	
	function findPuerto($arregloDatos) {
		$sql = "SELECT puerto,nombrepuerto,pais
						FROM puertos WHERE (nombrepuerto LIKE '%$arregloDatos[q]%') or (pais LIKE '%$arregloDatos[q]%')";
            
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		} else {
			return $this->db->getArray();
		}
	}

	function findCodigoPais($nombre) {
		$sql = "SELECT codigo FROM paises WHERE (nombre = '$nombre')";
            
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		} else {
	    $datos = $this->db->fetch();
	    return $datos->codigo;			
		}
	}
  
	function findPaisCompra($arregloDatos) {
		$sql = "SELECT codigo,nombre
						FROM paises WHERE (nombre LIKE '%$arregloDatos[q]%') ORDER BY nombre";
            
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		} else {
			return $this->db->getArray();
		}
	}

	function findShipper($arregloDatos) {
		$sql = "SELECT razon_social
						FROM clientes WHERE (razon_social LIKE '%$arregloDatos[q]%' AND tipo = 8) ORDER BY razon_social";
            
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		} else {
			return $this->db->getArray();
		}
	}

	function findBascula($arregloDatos) {
		$sede = $_SESSION['sede'];
		$sql = "SELECT nombre
						FROM basculas WHERE (nombre LIKE '%$arregloDatos[q]%' AND sede = '$sede') ORDER BY nombre";
            
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		} else {
			return $this->db->getArray();
		}
	}
            
  function listarFotos($arregloDatos) {
		$sql = "SELECT id, orden, fecha_foto, nombre_foto
						FROM ordenes_anexos WHERE orden = '$arregloDatos[do_asignado]'";
        
		$this->db->query($sql);
		$rows = $this->db->countRows();
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		}
		if($rows == 0) {
			$this->mensajepersonalizado = '&nbsp;No hay fotos registradas';
		}
	}
	
	function guardaFoto($arregloDatos) {
		$fecha = date('Y-m-d');
    
    $sql = "INSERT INTO ordenes_anexos(orden,fecha_foto,nombre_foto) VALUES($arregloDatos[do_asignado],'$fecha','$arregloDatos[nFoto]')";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		}	 
	}
  
  function regBorrar($arregloDatos) {
    $ruta_completa = "integrado/_files/".$arregloDatos['nombre_foto'];
    $id = $arregloDatos['id'];
    $orden = $arregloDatos['do_asignado'];
    
    // Eliminamos físicamente el archivo
    unlink($ruta_completa);

    $sql = "DELETE FROM ordenes_anexos WHERE id='".$id."' and '".$orden."'";
    
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
      return TRUE;
    }
  }
 
  function getTipoOperacion($arregloDatos) {
  	$tipo_sede = $_SESSION['sede_tipo'];
		$restriccion=" AND tipo_sede	= $tipo_sede ";
	
		if ($tipo_sede=='4') { $restriccion=""; }
				
		$sql = "SELECT DISTINCT codigo,nombre FROM tipos_operacion
						WHERE codigo <> 0 AND tipo IN(1,3,4) $restriccion
						ORDER BY nombre";
		
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		}
		$arreglo = array();
		while($obj=$this->db->fetch()) {
			$arreglo[$obj->codigo] = ucwords(strtolower($obj->nombre));
		}

		return $arreglo;
	}
	
	function existeCliente($arregloDatos) {
		$sql = "SELECT numero_documento,razon_social FROM clientes
						WHERE numero_documento = '$arregloDatos[por_cuenta]'";

		$this->db->query($sql);
		$rows = $this->db->countRows();
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		} else {
			return $rows;
		}
	}
	
	function inicializaContadores($arregloDatos) {
		$sql = "UPDATE do_bodegas
						SET do_asignado=doini,anio=$arregloDatos[year],contador=$arregloDatos[contador]
						WHERE codigo='$arregloDatos[codigo_bodega]'";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		}
	}

	function listaUbicaciones($arregloDatos) {
		$sede = $_SESSION['sede'];
		$sql = "SELECT * FROM do_bodegas WHERE sede='$sede'";

		$resultado = $this->db->query($sql); //Ejecuta la consulta
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		}
	}
	
	function existeDocumento($arregloDatos) {
		$anio = date('Y');
		$sede = $_SESSION['sede'];
		$sql = "SELECT doc_tte FROM do_asignados WHERE doc_tte = '$arregloDatos[doc_tte]'
				AND bodega = $arregloDatos[bodega]
				AND YEAR(fecha) = '$anio'
				AND sede= '$sede'";
		
		if(!empty($arregloDatos['id_no_incluir'])) {
			$sql .= " AND codigo <> $arregloDatos[id_no_incluir]";
		}

		$this->db->query($sql);
		$rows = $this->db->countRows();
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		} else {
			return $rows;
		}
	}
        
	function destinatario($arregloDatos) {
		$anio = date('Y');
		$sql = "";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
    }
	}
	
	function updateUnaOrden($arregloDatos) {
		if(empty($arregloDatos['ind_cons'])) { $arregloDatos['ind_cons'] = 'No'; }
		if(empty($arregloDatos['ind_fragil'])) { $arregloDatos['ind_fragil'] = 'No'; }
		if(empty($arregloDatos['ind_hielo'])) { $arregloDatos['ind_hielo'] = 'No'; }
		if(empty($arregloDatos['ind_asig'])) { $arregloDatos['ind_asig'] = 'No'; }
		if(empty($arregloDatos['ind_venci'])) { $arregloDatos['ind_venci'] = 'No'; }
		if(empty($arregloDatos['ind_ubica'])) { $arregloDatos['ind_ubica'] = 'No'; }
		/* Se deshabilita para evitar que aparezca en observaciones la fecha del DO */
		// Desactivado por Fredy Salom - 22/ene/2024
		//$arregloDatos['obs']=$arregloDatos['obs'].' :'.$arregloDatos['fecha_do_aux'];
		/*Se deshabilita el update original para evitar la concatenación de las observaciones anteriores */
		// Desactivado por Fredy Salom - 22/ene/2024
		/*$sql = "UPDATE do_asignados
						SET tipo_operacion=$arregloDatos[tipo_operacion],doc_tte='$arregloDatos[doc_tte]',tipo_documento='$arregloDatos[tipo_documento]',
								manifiesto='$arregloDatos[manifiesto]',obs=CONCAT(obs,' ','$arregloDatos[obs]'),ind_cons='$arregloDatos[ind_cons]',ind_fragil='$arregloDatos[ind_fragil]',
								ind_hielo='$arregloDatos[ind_hielo]',ind_asig='$arregloDatos[ind_asig]',ind_venci='$arregloDatos[ind_venci]',
								ind_ubica='$arregloDatos[ind_ubica]',por_cuenta='$arregloDatos[numero_documento]',fecha='$arregloDatos[fecha_do]'
						WHERE do_asignado = $arregloDatos[do_asignado]"; */
		$sql = "UPDATE do_asignados SET tipo_operacion=$arregloDatos[tipo_operacion],doc_tte='$arregloDatos[doc_tte]',tipo_documento='$arregloDatos[tipo_documento]',manifiesto='$arregloDatos[manifiesto]',obs='$arregloDatos[obs]',ind_cons='$arregloDatos[ind_cons]',ind_fragil='$arregloDatos[ind_fragil]',ind_hielo='$arregloDatos[ind_hielo]',ind_asig='$arregloDatos[ind_asig]',ind_venci='$arregloDatos[ind_venci]',ind_ubica='$arregloDatos[ind_ubica]',por_cuenta='$arregloDatos[numero_documento]',fecha='$arregloDatos[fecha_do]' WHERE do_asignado = $arregloDatos[do_asignado]";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		}
	}
	
	function lista($tabla,$condicion=NULL,$campoCondicion=NULL) {
		$sede = $_SESSION['sede'];
		//if($orden == NULL) $orden = $nombre; - Old Version
		if(is_null($orden)) $orden = $nombre;
		$sql = "SELECT codigo,nombre FROM $tabla WHERE codigo NOT IN('0')" ;
		if($condicion <> NULL and $condicion <> '%') {
			$sql .= " AND $campoCondicion IN($condicion)";
		}
		if($tabla == 'do_bodegas') {
			$sql .= " AND sede ='$sede'";
		}				
		$sql .= "	ORDER BY nombre" ;
		$this->db->query($sql);
		$arreglo = array();
		while($reg=$this->db->fetch()) {
			$arreglo[$reg->codigo] = ucwords(strtolower($reg->nombre));
		}
		return $arreglo;
	}
		
	function dato($tabla,$campoCondicion,$condicion) {
		$sql = "SELECT codigo,nombre FROM $tabla WHERE codigo NOT IN('0')" ;

		if(!empty($campoCondicion)) {
			$sql .= " AND $campoCondicion IN('$condicion')";
		}				
		$sql .= "	ORDER BY nombre";
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			return TRUE;
		} else {
			$datos = $this->db->fetch();
			return $datos->nombre;
		}
	}

	function getConsecutivo($arregloDatos) {
		$sql = "SELECT do_asignado,dofin,doini,anio,contador,area AS ciudad,bodega,nombre AS bodega_nombre, sigla AS abrevbod FROM do_bodegas WHERE codigo = $arregloDatos[bodega]";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			// Configuración del Manipulador de Error
			/*set_error_handler("$this->db->tipoError");
			trigger_error("Error al obtener consecutivo ".$sql,E_USER_NOTICE);*/
			echo $sql;
			$this->mensaje_error = "Error al obtener consecutivo12 ";
			$this->estilo	= $this->estilo_error;
			return TRUE;
		} else {
			$resultado = $this->db->fetch();
			$unConsecutivo = new Orden();
			$unConsecutivo->setConsecutivo($arregloDatos); //Actualiza
			if(intval($resultado->do_asignado)>=intval($resultado->dofin)) {
				// Se Incrementa Contador y se Inicializa el consecutivo para comenzar de Nuevo
				$unUpdateConsecutivo = new Orden();
				$arregloDatos['do_ini'] = $resultado->doini;
				$unUpdateConsecutivo->setContador($arregloDatos);
			}
			$resultado->do_asignado = str_pad($resultado->do_asignado,3,'0',STR_PAD_LEFT);
			$anio = substr($resultado->anio,2,2);
			$mes = date("m");
			$resultado->do_asignado = $anio.$mes.$resultado->ciudad.$resultado->bodega.$resultado->contador.$resultado->do_asignado;
			$resultado->do_asignado_full = $resultado->abrevbod."-".(string)$resultado->do_asignado;
			//Se verifica si hay que crear el centro y subcentro 
			//$unConsecutivo->insertCentros($arregloDatos);
			//$unConsecutivo->insertSubcentros($arregloDatos);
			//return $this->do_asignado;
			return array($resultado->do_asignado,$resultado->do_asignado_full);
		}
	}
	
	function insertCentros($arregloDatos) { //2011
		$sede = $_SESSION['sede'];
		$centro = substr($arregloDatos['do_asignado'],0,4);
			
		$sql = "SELECT codigo FROM centros_costo WHERE codigo = '$centro' AND sede = '$sede'";

		$this->db->query($sql);
		$rows = $this->db->countRows();
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			// Configuración del Manipulador de Error
			/*set_error_handler("$this->db->tipoError"); 
			trigger_error("Error al consultar Centros de Costo",E_USER_NOTICE);*/
			echo $sql;
			$this->mensaje_error = "Error al consultar Centros de Costo ";
			$this->estilo	= $this->estilo_error;
			return TRUE;
		}
		if($rows == 0) {
			$sql = "INSERT centros_costo(codigo,nombre,sede)
							VALUES('$centro','$centro','$sede')";

			$resultado = $this->db->query($sql);
			if(!is_null($resultado)) {
				echo "Error al consultar Centros de Costo";
				$this->mensaje_error = "Error al consultar Centros de Costo ";
				$this->estilo	= $this->estilo_error;
				return TRUE;
			}
		}	
	}
	 
	function insertSubcentros($arregloDatos) { //2011
		$sede = $_SESSION['sede'];
		$subcentro = substr($arregloDatos['do_asignado'],4,3);
		$sql = "SELECT codigo
						FROM subcentro_costo
						WHERE codigo = '$subcentro'
							AND sede = '$sede'";

		$this->db->query($sql);
		$rows = $this->db->countRows();
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			$this->mensaje_error = "Error al consultar Subcentros de Costo ";
			$this->estilo	= $this->estilo_error;
			return TRUE;
		}
		if($rows == 0) {
			$sql = "INSERT subcentro_costo(codigo,nombre,sede)
							VALUES('$subcentro','$subcentro','$sede')";

			$resultado = $this->db->query($sql);
			if(!is_null($resultado)) {
				echo $sql;
				$this->mensaje_error = "Error al crear Subcentros de Costo  ";
				$this->estilo	= $this->estilo_error;
				return TRUE;
			}
		}	
	}

	function setContador($arregloDatos) {
		$sql = "UPDATE do_bodegas SET contador+=1,do_asignado=$arregloDatos[do_ini] WHERE codigo='$arregloDatos[bodega]'";

		$resulta = $this->db->query($sql);
		if(!is_null($resulta)) {
			echo $sql;
			$this->mensaje_error = "Error al actualizar el contador ";
			$this->estilo	= $this->estilo_error;
		}
		return TRUE;
	}

	function setConsecutivo($arregloDatos) {
		$sql = "UPDATE do_bodegas SET do_asignado = do_asignado + 1
			WHERE codigo = $arregloDatos[bodega]";

		$resulta = $this->db->query($sql);
		if(!is_null($resulta)) {
			echo $sql;
			$this->mensaje_error = "Error al actualizar el consecutivo ";
			$this->estilo	= $this->estilo_error;
		}
		return TRUE; 
	}
	
	function existeDo($do,$bodega) {
		$anio = date('Y');
		$sede = $_SESSION['sede'];
		$sql = "SELECT * FROM  do_asignados WHERE do_asignado = '$do'
							AND sede = '$sede' 
							AND bodega = $bodega
							AND year(fecha) = $anio";

		$this->db->query($sql);
		$rows = $this->db->countRows();
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			$this->mensaje_error = "Error al actualizar el consecutivo ";
			$this->estilo	= $this->estilo_error;
			return TRUE;
		} else {
			return $rows;
		}
	}

	function listaArribos($arregloDatos) {
		$sql = "SELECT arribo FROM arribos WHERE orden = $arregloDatos[do_asignado] ORDER BY arribo DESC";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			$this->mensaje = "Error al consultar Los Arribos ";
			$this->estilo	= $this->estilo_error;
			return TRUE;
		}
	}
  
 	function getArribo($arregloDatos) {
    $arregloDatos['nombre_sede'] = $_SESSION['nombre_sede'];
		$sql = "SELECT arribos.*,TIME_FORMAT(arribos.hora_llegada,'%h:%i') AS hora_llegada,posiciones.nombre AS nombre_ubicacion,camiones.conductor_identificacion AS identificacion,camiones.conductor_nombre AS conductor,camiones.placa,transportador.nombre AS nom_transportador,paises.nombre AS parcial,da.tipo_operacion FROM arribos 
				LEFT JOIN posiciones
				ON arribos.ubicacion = posiciones.codigo
				LEFT JOIN camiones
				ON arribos.id_camion = camiones.codigo
				LEFT JOIN transportador
				ON arribos.transportador = transportador.codigo
				LEFT JOIN do_asignados da
				ON arribos.orden = da.do_asignado
        LEFT JOIN paises
        ON arribos.parcial = paises.codigo";

		if(!empty($arregloDatos['arribo'])) {
			$sql .= " WHERE arribo = $arregloDatos[arribo]";
		}		

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			$this->mensaje = "Error al consultar Los Arribos ";
			$this->estilo	= $this->estilo_error;
			return TRUE;
		}
	}

  // Obtiene info para nuevo arribo
 	function newArribo($arregloDatos) {
		$sql = "SELECT arribos.*,posiciones.nombre AS nombre_ubicacion, camiones.conductor_identificacion AS identificacion,camiones.conductor_nombre AS conductor,camiones.placa,transportador.nombre AS nom_transportador,paises.nombre AS paiscompra
				FROM arribos 
				LEFT JOIN posiciones
				ON arribos.ubicacion = posiciones.codigo
				LEFT JOIN camiones
				ON arribos.id_camion = camiones.codigo
				LEFT JOIN transportador
				ON arribos.transportador = transportador.codigo
        LEFT JOIN paises
        ON arribos.parcial = paises.codigo";

		if(!empty($arregloDatos['arribo'])) {
			$sql .= " WHERE arribo = $arregloDatos[arribo]";
		}		

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo $sql;
			$this->mensaje = "Error al consultar Los Arribos ";
			$this->estilo	= $this->estilo_error;
			return TRUE;
		} else {
			$datos = $this->db->fetch();
			$arregloDatos['factura'] = $datos->factura;
			$arregloDatos['manifiesto'] = $datos->manifiesto;
			$arregloDatos['fecha_manifiesto'] = $datos->fecha_manifiesto;
			$arregloDatos['fecha_doc_tt'] = $datos->fecha_doc_tt;
			$arregloDatos['tipo_documento'] = $datos->tipo_documento;
			$arregloDatos['cantidad'] = $datos->cantidad;
			$arregloDatos['peso_bruto'] = $datos->peso_bruto;
			$arregloDatos['repeso'] = $datos->repeso;
			$arregloDatos['metros'] = $datos->metros;
			$arregloDatos['estibas'] = $datos->estibas;
			$arregloDatos['trm'] = $datos->trm;
			$arregloDatos['cif'] = $datos->cif_arribo;
			$arregloDatos['fob'] = $datos->fob;
			$arregloDatos['valor_fob'] = $datos->valor_fob;
			$arregloDatos['seguros'] = $datos->seguros;
			$arregloDatos['otros_gastos'] = $datos->otros_gastos;
			$arregloDatos['fletes'] = $datos->fletes;
			$arregloDatos['dice_contener'] = $datos->dice_contener;
			$arregloDatos['parcial'] = $datos->paiscompra;
			$arregloDatos['fmm'] = $datos->fmm;
			$arregloDatos['transportador'] = $datos->transportador;
			$arregloDatos['agente'] = $datos->agente;
			$arregloDatos['ubicacion'] = $datos->ubicacion;
			$arregloDatos['origen'] = $datos->origen;
			$arregloDatos['destino'] = $datos->destino;
			$arregloDatos['shipper'] = $datos->shipper;
			$arregloDatos['bascula'] = $datos->bascula;
			$arregloDatos['id_camion'] = $datos->id_camion;
			$arregloDatos['planilla'] = $datos->planilla;
			$arregloDatos['planilla_recepcion'] = $datos->planilla_recepcion;
			$arregloDatos['peso_planilla'] = $datos->peso_planilla;
			$arregloDatos['sitio'] = $datos->sitio;
			$arregloDatos['fecha_exp'] = $datos->fecha_exp;
			$arregloDatos['precinto'] = $datos->precinto;
			$arregloDatos['moneda'] = $datos->moneda;
			$arregloDatos['hora_llegada'] = $datos->hora_llegada;
		}
		return $arregloDatos;
	}

	function updateArribo($arregloDatos) {
    //Valida info del Pais de Compra para obtener su código
    if(!is_numeric($arregloDatos['paiscompra'])) {
      $arregloDatos['paiscompra'] = $this->findCodigoPais($arregloDatos['paiscompra']);
    }
		$sql = "UPDATE arribos SET transportador='$arregloDatos[transportador]',
							origen='$arregloDatos[origen]',
							agente='$arregloDatos[agente]',
							destino='$arregloDatos[destino]',
							shipper='$arregloDatos[shipper]',
							planilla='$arregloDatos[planilla]',
							planilla_recepcion='$arregloDatos[planilla_recepcion]',
							repeso='$arregloDatos[repeso]',
							peso_planilla='$arregloDatos[peso_planilla]',
							id_camion='$arregloDatos[id_camion]',
							cantidad='$arregloDatos[cantidad]',
							peso_bruto='$arregloDatos[peso_bruto]',
							bascula='$arregloDatos[bascula]',
							metros='$arregloDatos[metros]',
							estibas='$arregloDatos[estibas]',
							ubicacion='$arregloDatos[ubicacion]',
	            parcial='$arregloDatos[paiscompra]',
							fmm='$arregloDatos[fmm]',
							manifiesto='$arregloDatos[manifiesto]',
							fecha_manifiesto='$arregloDatos[fecha_manifiesto]',
							fecha_doc_tt='$arregloDatos[fecha_doc_tt]',
							fecha_exp='$arregloDatos[fecha_exp]',
							precinto='$arregloDatos[precinto]',
							factura='$arregloDatos[factura]',
	            seguros='$arregloDatos[seguros]',
	            otros_gastos='$arregloDatos[otros_gastos]',
							fletes='$arregloDatos[fletes]',
							trm='$arregloDatos[trm]',
							cif_arribo='$arregloDatos[cif]',
							fob='$arregloDatos[fob]',
							valor_fob='$arregloDatos[valor_fob]',
							moneda='$arregloDatos[moneda]',
							hora_llegada='$arregloDatos[hora_llegada]',
							dice_contener='$arregloDatos[dice_contener]',
							observacion='$arregloDatos[observacion]'
						WHERE arribo = $arregloDatos[arribo]";

		$resultado = $this->db->query($sql);
		$this->mensaje = "Se actualizó correctamente el arribo $arregloDatos[arribo] ";
		$this->estilo	= $this->estilo_ok;
		if(!is_null($resultado)) {
			echo $sql;
			$this->mensaje = "Error al actualizar el Arribo $arregloDatos[arribo]";
			$this->estilo	= $this->estilo_error;
			return TRUE;
		}		
	}

	function addArribo($arregloDatos) {
		$arregloDatos['moneda']=2;

		if($arregloDatos['tipo_operacion']=='24') {
			$arregloDatos['bloquea_moneda']=1; // No se deja modificar	
			$arregloDatos['moneda']=1; // si es Libre disposición LA MONEDA es pesos
		}
		if(empty($arregloDatos['peso_bruto'])) { $arregloDatos['peso_bruto']=0; }
		$fecha = date('Y/m/d');
    $nombre_sede = $_SESSION['nombre_sede'];    

    //Valida creación arribo desde Orden Crear
    if($arregloDatos['flgnewa']) {
      $arregloDatos['paiscompra'] = $this->findCodigoPais($arregloDatos['paiscompra']);

		  $sql = "INSERT INTO arribos(orden,factura,fecha_arribo,manifiesto,fecha_manifiesto,fecha_doc_tt,
                tipo_documento,cantidad,peso_bruto,repeso,metros,estibas,seguros,otros_gastos,trm,cif_arribo,fob,valor_fob,fletes,dice_contener,fmm,transportador,agente,origen,destino,shipper,bascula,id_camion,planilla,planilla_recepcion,peso_planilla,ubicacion,parcial,sitio,fecha_exp,precinto,moneda,hora_llegada)
              VALUES($arregloDatos[do_asignado],'$arregloDatos[factura]','$fecha','$arregloDatos[manifiesto]','$arregloDatos[fecha_manifiesto]','$arregloDatos[fecha_doc_tt]','$arregloDatos[tipo_documento]',$arregloDatos[cantidad],$arregloDatos[peso_bruto],$arregloDatos[repeso],$arregloDatos[metros],$arregloDatos[estibas],$arregloDatos[seguros],$arregloDatos[otros_gastos],$arregloDatos[trm],$arregloDatos[cif],$arregloDatos[fob],$arregloDatos[valor_fob],$arregloDatos[fletes],'$arregloDatos[dice_contener]','$arregloDatos[fmm]','$arregloDatos[transportador]','$arregloDatos[agente]','$arregloDatos[origen]','$arregloDatos[destino]','$arregloDatos[shipper]','$arregloDatos[bascula]',$arregloDatos[id_camion],'$arregloDatos[planilla]','$arregloDatos[planilla_recepcion]',$arregloDatos[peso_planilla],'$arregloDatos[ubicacion]','$arregloDatos[paiscompra]','$nombre_sede','$arregloDatos[fecha_exp]','$arregloDatos[precinto]',$arregloDatos[moneda],'$arregloDatos[hora_llegada]')";
    } else {
      $arregloDatos['parcial'] = substr($arregloDatos['origen'],strpos($arregloDatos['origen'],',')+2,
        strlen($arregloDatos['origen'])-(strpos($arregloDatos['origen'],',')+2));
      $arregloDatos['paiscompra'] = $this->findCodigoPais($arregloDatos['parcial']);
		  $sql = "INSERT INTO arribos(orden,fecha_arribo,manifiesto,origen,destino,ubicacion,
                tipo_documento,parcial,sitio,peso_bruto,bascula,moneda)
              VALUES('$arregloDatos[do_asignado]','$fecha','$arregloDatos[manifiesto]',
                '$arregloDatos[origen]','$arregloDatos[destino]','$arregloDatos[ubicacion]',
                '$arregloDatos[tipo_documento]','$arregloDatos[paiscompra]','$nombre_sede',$arregloDatos[peso_bruto],'$arregloDatos[bascula]',$arregloDatos[moneda])";
    }

		$resultado = $this->db->query($sql);
		$this->mensaje = "Se cre&oacute; el Arribo correctamente ";
		$this->estilo	= $this->estilo_ok;
		if(!is_null($resultado)) {
			echo "Error al intentar crear el Arribo";
			$this->mensaje = "Error al intentar crear el Arribo";
			$this->estilo	= $this->estilo_error;
			return TRUE;
		}
	}

	function delArribo($arregloDatos) {
		$fecha = date('Y/m/d');
		$sql = "DELETE FROM arribos WHERE arribo=$arregloDatos[id_arribo]";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo "Error al intentar borrar el Arribo";
			$this->mensaje = "Error al intentar crear el Arribo";
			$this->estilo	= $this->estilo_error;
			return TRUE;
		}	else {
			$this->mensaje = "Se elimin&oacute; el Arribo correctamente ";
			$this->estilo	= $this->estilo_ok;
		} 
	}

	function addOrden($arregloDatos) {
		$fecha = date('Y/m/d');
		$sede = $_SESSION['sede'];
		$sql = "INSERT INTO do_asignados(sede,doc_tte,bodega,do_asignado,manifiesto,por_cuenta,carga,ingreso,origen,puertorigen,destino,puertodestino,fecha,tipo_operacion,tipo_documento,obs,estado) VALUES('$sede','$arregloDatos[doc_tte]',$arregloDatos[bodega],$arregloDatos[do_asignado],'$arregloDatos[manifiesto]','$arregloDatos[por_cuenta]','$arregloDatos[suelta_conte]','$arregloDatos[consig_asig]','$arregloDatos[origen]','$arregloDatos[puertoini]','$arregloDatos[destino]','$arregloDatos[puertofin]','$fecha','$arregloDatos[tipo_operacion]','$arregloDatos[tipo_documento]','$arregloDatos[obs]',1)";													

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo "Error al intentar crear el DO";
			$this->mensaje = "Error al intentar crear el Do: $arregloDatos[do_asignado] ";
			$this->estilo	= $this->estilo_error;
			return TRUE;
		} else {
			$this->mensaje = "Se cre&oacute; el Do $arregloDatos[do_asignado] correctamente ";
			$this->estilo	= $this->estilo_ok;
			return FALSE;
		}	 
	}
  
  //Creación Código zf_code
	function addGrupoItems($arregloDatos) {
		$sql = "INSERT INTO grupo_items(codigo,nombre) VALUES ('$arregloDatos[zfcode]','$arregloDatos[name]')";

		$this->db->query($sql);
  }

  //Actualiza Código zf_code
	function updGrupoItems($arregloDatos) {
		$sql = "UPDATE grupo_items SET codigo = '$arregloDatos[zfcode]'
            WHERE (numide = '$arregloDatos[numero_documento]')";

		$this->db->query($sql);
  }

  //Creación Automática de Referencia asociada a la creación del DO
	function addReferencia($arregloDatos) {
		$sql = "INSERT INTO referencias(codigo_ref,ref_prove,nombre,cliente,unidad,unidad_venta,presentacion_venta,fecha_expira,min_stock,alto,largo,ancho,serial,tipo,grupo_item,factor_conversion) VALUES('$arregloDatos[doc_tte]','9999999998','DOCUMENTO DE TTE','$arregloDatos[por_cuenta]',3,6,'45',0,0,1,1,1,0,15,'$arregloDatos[zfcode]',1)";

		$resultado = $this->db->query($sql);
		$this->mensaje = "Se cre&oacute; la Referencia $arregloDatos[doc_tte] correctamente ";
		$this->estilo	= $this->estilo_ok;
		if(!is_null($resultado)) {
			printf("Error al intentar crear la Referencia: %d %s\n", $arregloDatos['doc_tte'],$this->db->error);
			$this->mensaje = "Error al intentar crear la Referencia: ".$arregloDatos['doc_tte'];
			$this->estilo	=$this->estilo_error;
			return TRUE;
		}
		return FALSE;  
	}

  //Actualización de Grupo_Item en Referencia
	function updReferencia($arregloDatos) {
		$sql = "UPDATE referencias SET grupo_item = '$arregloDatos[zfcode]' WHERE codigo_ref = '$arregloDatos[doc_tte]'";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo "Error al intentar actualizar la Referencia: $arregloDatos[doc_tte]";
			$this->mensaje = "Error al intentar actualizar la Referencia: $arregloDatos[doc_tte] ";
			$this->estilo	=$this->estilo_error;
			return TRUE;
		} else {
			$this->mensaje = "Se actualiz&oacute; la Referencia $arregloDatos[doc_tte] correctamente ";
			$this->estilo	= $this->estilo_ok;
			return FALSE; 			
		} 
	}

	//Indica en que liquidaciones se tienen en cuenta las Ordenes
	function sqlLiquidacionesAsociadas($arregloDatos) {
		$sql = "($arregloDatos[num_Orden],$arregloDatos[tipo_liquidacion]),";	  

		return $sql;
	}

	function auditoria($usuario,$modulo,$evento,$descripcion,$tipo_error) {
		//$unLog = new Orden();
		$fecha = date('Y/m/d H:i:s');

		$sql = "INSERT INTO nom_auditoria(usuario,fecha,modulo,evento,descripcion,tipo)
						VALUES ('$usuario','$fecha','$modulo','$evento','$descripcion','$tipo_error')";

		$resultado = $this->db->query($sql); 
		if(!is_null($resultado)) {
			echo $sql;
		}
	}

	function formularioCrear($arregloDatos) {}
	function newCamion($arregloDatos) {}

	function existeCamion($arregloDatos) {
		$sql = "SELECT placa FROM camiones WHERE placa='$arregloDatos[placa]' AND conductor_identificacion='$arregloDatos[identificacion]'";

		$this->db->query($sql);
		$rows = $this->db->countRows();
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			echo "Error al consultar Los Arribos";
			$this->mensaje = "Error al consultar Los Arribos ";
			$this->estilo	= $this->estilo_error;
			return TRUE;
		} else {
			echo $rows;
		}
	}

	function addCamion($arregloDatos) {
		$sql = "INSERT INTO camiones(placa,conductor_nombre,conductor_identificacion,empresa,activo)
            VALUES('$arregloDatos[placa]','$arregloDatos[conductor]','$arregloDatos[identificacion]','$arregloDatos[empresa]',1)";

		$resultado = $this->db->query($sql); 
		if(!is_null($resultado)) {
			echo "Error al intentar guardar el registro: $arregloDatos[do_asignado]";
			$this->mensaje = "Error al intentar guardar el registro: $arregloDatos[do_asignado] ";
			$this->estilo	= $this->estilo_error;
			return TRUE;
		}
	}
     
	// Cambia el estado de Un Do
	function setEstado($arregloDatos) {
		$sql = "UPDATE do_asignados SET estado=$arregloDatos[cod_estado]
						WHERE do_asignado='$arregloDatos[do_asignado]'";

		$resultado = $this->db->query($sql); 
		if(!is_null($resultado)) {
			echo "Error al cambiar de estado el Do: $arregloDatos[do_asignado]";
			return FALSE;
		} else {
			echo "Se cambi&oacute; correctamente el estado de el Do ".$arregloDatos[do_asignado];
			return true;
		}
	}
	
	//Registro del correo enviado al usuario
	function registroCorreo($arregloDatos) {
		$fecha = date('Y-m-d');
		$sede = $_SESSION['sede'];
		$arregloDatos['usuario'] = $_SESSION['datos_logueo']['usuario'];
		$usuario = $this->nombreUsuario($arregloDatos);
		$mensaje = str_repeat("=",82)."\n";		
		//Configura el mensaje del correo enviado
		switch($arregloDatos['metodo']) {
			case 'addOrden':
				$mensaje .= "Respetados Señores: ".$arregloDatos['razon_social']."\n\n";
				$mensaje .= "Fue creado el DO: ".$arregloDatos['do_asignado_full']." con la siguiente información:\n";
				$mensaje .= "Fecha Creación: ".$arregloDatos['fecham']."\n";
				$mensaje .= "Documento de Transporte: ".$arregloDatos['doc_tte']."\n";
				$mensaje .= "Manifiesto: ".$arregloDatos['manifiesto']."\n";
				$mensaje .= "Origen: ".$arregloDatos['origen']."\n";
				$mensaje .= "Puerto Origen: ".$arregloDatos['puertori']."\n";
				$mensaje .= "Destino: ".$arregloDatos['destino']."\n";
				$mensaje .= "Puerto Destino: ".$arregloDatos['puertodes']."\n";
				$mensaje .= "Observaciones: ".$arregloDatos['obs']."\n\n";
				$mensaje .= "Pendiente el registro de la carga desde Arribos.\n\n";
        break;
      case 'updateArribo':
				$mensaje .= "Respetados Señores: ".$arregloDatos['razon_social']."\n\n";
        $mensaje .= "A continuación se relaciona la mercancía recibida en nuestra área de almacenamiento\n";
        $mensaje .= $arregloDatos['sitio'].". Tenga en cuenta que le quedan ".$arregloDatos['dias']." días para su nacionalización, contados\n";
        $mensaje .= "a partir de la fecha del manifiesto, si a ello aplica.\n\n";
        $mensaje .= "Fecha Ingreso: ".$fecha."\n";
        $mensaje .= "Orden: ".$arregloDatos['do_asignado_full']."\n";
        $mensaje .= "Arribo: ".$arregloDatos['arribo']."\n";
				$mensaje .= "Documento de Transporte: ".$arregloDatos['doc_tte']."\n";
				$mensaje .= "Manifiesto: ".$arregloDatos['manifiesto']."\n";
        $mensaje .= "Fecha del Manifiesto: ".$arregloDatos['fecha_manifiesto']."\n";
        $mensaje .= "Piezas: ".$arregloDatos['cantidad']."\n";
        $mensaje .= "Peso: ".$arregloDatos['peso_bruto']."\n";
        $mensaje .= "Mercancía: ".$arregloDatos['dice_contener']."\n";
        $mensaje .= "Observación: ".$arregloDatos['observacion']."\n\n";
        break;
      case 'addItemBloquear':
				$mensaje .= "Respetados Señores: ".$arregloDatos['razon_social']."\n\n";
				$mensaje .= "Registro Control para el DO: ".$arregloDatos['do_asignado_full']." con la siguiente información:\n";
				$mensaje .= "Documento de Transporte: ".$arregloDatos['doc_tte']."\n";
        $mensaje .= "Bloqueado: ".$arregloDatos['bloquear']."\n";
				$mensaje .= "Manifiesto: ".$arregloDatos['manifiesto']."\n";
        $mensaje .= "Fecha del Manifiesto: ".$arregloDatos['fecha_manifiesto']."\n";
        $mensaje .= "Entidad: ".$arregloDatos['nombre_entidad']."\n";
        $mensaje .= "Control: ".$arregloDatos['nombre_control']."\n";
        $mensaje .= "Fecha del Control: ".$arregloDatos['fecha']."\n";
        $mensaje .= "Auto: ".$arregloDatos['auto_adm']."\n";
        $mensaje .= "Periodicidad: ".$arregloDatos['periodicidad']."\n";
        $mensaje .= "Observaciones: ".$arregloDatos['obs']."\n\n";        
        break;
      case 'addItemRetiro': 
				$mensaje .= "Respetados Señores: ".$arregloDatos['nombre_cliente']."\n\n";
				$mensaje .= "Registro Retiro ".$arregloDatos['nombre_movimiento']." para el DO: ".$arregloDatos['do_asignado_full']." con la siguiente información:\n";
        $mensaje .= "Fecha del Retiro: ".$arregloDatos['fecha']."\n";
        $mensaje .= "Conductor : ".$arregloDatos['conductor']."\n";
        $mensaje .= "Placa: ".$arregloDatos['placa']."\n";
        $mensaje .= "Destinatario: ".$arregloDatos['destinatario']."\n";
        $mensaje .= "Ciudad y Dirección: ".$arregloDatos['ciudad']." :: ".$arregloDatos['direccion']."\n";
        $mensaje .= "Mercancia: ".$arregloDatos['mercancia']."\n";
				$mensaje .= "Documento de Transporte: ".$arregloDatos['doc_tte']."\n";
        $mensaje .= "Cantidad: ".$arregloDatos['cantidad']."\n";
        $mensaje .= "Peso: ".$arregloDatos['peso']."\n";
        $mensaje .= "Fob/Cif: ".$arregloDatos['valor']."\n\n";        
        break;
		}
    $mensaje .= "Nota: Correo generado automáticamente por el Sistema BysoftWMS. Por favor, no responder.\n";
		$mensaje .= str_repeat("=",82);

		$sql = "INSERT INTO tracking(sede,do_asignado,doc_tte,por_cuenta,fecha,remite,destino,asunto,adjuntos,mensaje,creador,forma) VALUES('$sede',$arregloDatos[do_asignado],'$arregloDatos[doc_tte]','$arregloDatos[por_cuenta]','$fecha','$arregloDatos[remite]','$arregloDatos[email]','$arregloDatos[asunto_mail]','$arregloDatos[adjuntos]','$mensaje','$usuario','Automático')";
    
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) { 
			echo $sql;
			return FALSE;
		} else { return TRUE; }
	}
	
	function nombreUsuario($arregloDatos) {
		$sql = "SELECT nombre_usuario,apellido_usuario
            FROM usuarios
            WHERE usuario = '$arregloDatos[usuario]'" ;

		$this->db->query($sql); 
		$datos = $this->db->fetch();
		return $datos->nombre_usuario." ".$datos->apellido_usuario;
	}
  
	function getActaIngreso($arregloDatos) {
    $arregloDatos['nombre_sede'] = $_SESSION['nombre_sede'];
		$sql = "SELECT arribos.*,ubicaciones.nombre AS nombre_ubicacion,camiones.conductor_identificacion AS identificacion,camiones.conductor_nombre AS conductor,camiones.placa,transportador.nombre AS nom_transportador,paises.nombre AS paiscompra
						FROM arribos 
						LEFT JOIN ubicaciones
						ON arribos.ubicacion = ubicaciones.codigo
						LEFT JOIN camiones
						ON arribos.id_camion = camiones.codigo
						LEFT JOIN transportador
						ON arribos.transportador = transportador.codigo
            LEFT JOIN paises
            ON arribos.parcial = paises.codigo
            WHERE orden = $arregloDatos[do_asignado] AND arribo = $arregloDatos[arribo]";

		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			$this->mensaje = "Error al consultar Los Arribos ";
			$this->estilo	= $this->estilo_error;
			echo "Error al consultar Los Arribos";
			return TRUE;
		}	else {
			return $this->db->getArray();
		}
	}
	
  function setReferencia($arregloDatos) {
		// solo cambia la referencia la primera vez
		$sql = "UPDATE inventario_entradas SET posicion=$arregloDatos[ubicacion] WHERE arribo='$arregloDatos[arribo]' AND peso=0 ";
				
		$resultado = $this->db->query($sql);
		if(!is_null($resultado)) {
			$this->mensaje = "Error al actualizar ubicacion $sql ";
			$this->estilo	= $this->estilo_error;
			echo $this->mensaje;
		}
	}

	function findPosicion($arregloDatos) {
    $sql = "SELECT codigo, nombre FROM posiciones WHERE nombre LIKE '%$arregloDatos[q]%'
            UNION SELECT codigo, nombre FROM posiciones WHERE codigo = 1";
 
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      return TRUE;
    } else {
    	return $this->db->getArray();
    }
  }

  function addReferenciaUbicacion($arregloDatos) {
		// solo cambia la referencia la primera vez
		$sql = "INSERT INTO referencias_ubicacion(item,ubicacion,)VALUES(,$arregloDatos[ubicacion])";
				
		$resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
			$this->mensaje = "Error al actualizar ubicacion $sql ";
			echo $this->mensaje;
		}
	} 
}
?>