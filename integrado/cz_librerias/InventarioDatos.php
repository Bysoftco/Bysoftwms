<?php
require_once("MYDB.php"); 
require_once("LevanteDatos.php"); 	

class Inventario extends MYDB {

  function Inventario() { 
    $this->estilo_error = "ui-state-error";
    $this->estilo_ok = "ui-state-highlight";
	////VERSION 06052017
  }

  function listaInventario($arregloDatos) {
    $sql = "SELECT codigo AS item,arribo,':' AS una_referencia FROM inventario_entradas
            WHERE arribo = '$arregloDatos[id_arribo]'";
            
    if(!empty($arregloDatos['una_referencia'])) {
		  $sql .= " AND inventario_entradas.referencia IN($arregloDatos[una_referencia])";	
    }		
    $sql .= " ORDER BY codigo DESC";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "Error al consultar Inventario ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  function encabezadoInventario($arregloDatos) {
    $sql = "SELECT arribos.peso_bruto AS p_arribo,
              SUM(ie.peso) AS p_inv, arribos.peso_bruto-sum(ie.peso) dif_p,
              MAX(arribos.valor_fob) valor_fob,
              SUM(ie.valor) AS v_inv,
              arribos.valor_fob-sum(ie.valor) AS dif_f
            FROM arribos, inventario_entradas ie
            WHERE arribos.arribo = '$arregloDatos[id_arribo]'
              AND arribos.arribo = ie.arribo
            GROUP BY arribos.arribo";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "Error al consultar Inventario ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  function getInventario($arregloDatos) {
    $sql = "SELECT inv.cantidad, inv.codigo AS item, inv.arribo, inv.orden, inv.peso, inv.valor, inv.fmm, inv.referencia, inv.modelo,
              inv.posicion, inv.observacion, inv.un_empaque, inv.embalaje, do_asignados.por_cuenta AS cliente,
              embalajes.nombre AS nombre_empaque, posiciones.nombre AS nombre_posicion, ref.nombre AS nombre_referencia,
              inv.fecha_expira, ref.fecha_expira AS chkfecha_expira, ref.serial AS chkserial, ref.codigo_ref 
            FROM inventario_entradas inv,referencias ref,embalajes,posiciones,do_asignados
            WHERE inv.referencia = ref.codigo
              AND embalajes.codigo = inv.un_empaque
              AND inv.posicion = posiciones.codigo
              AND do_asignados.do_asignado = inv.orden
              AND inv.codigo = $arregloDatos[id_item]";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "Error al consultar Inventario ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  function lista($tabla,$condicion = NULL,$campoCondicion = NULL) {
    $sede = $_SESSION['sede'];
    
    if($orden == NULL) $orden = $nombre;
    
    $sql = "SELECT codigo,nombre
            FROM $tabla
            WHERE codigo NOT IN('0')";
    if($condicion <> NULL and $condicion <> '%') $sql .= " AND $campoCondicion IN ($condicion)";
    if($tabla == 'do_bodegas') $sql .= " AND sede = '$sede'";
    $sql .= "	ORDER BY nombre";
		//echo $sql;
    $this->query($sql); 
    if($this->_lastError) {
      return FALSE;
    } else {
      $arreglo = array();
      while($this->fetch()) {
        $arreglo[$this->codigo] = ucwords(strtolower($this->nombre));
      }
    }

    return $arreglo;
  }

  function saveItem($arregloDatos) {
    $sql = "UPDATE inventario_entradas
              SET cantidad = $arregloDatos[cantidad],
              peso = $arregloDatos[peso],
              referencia = '$arregloDatos[referencia]',
              valor = '$arregloDatos[valor]',
              fmm = '$arregloDatos[fmm]',
              modelo = '$arregloDatos[modelo]',
              posicion = '$arregloDatos[posicion]',
              observacion	= '$arregloDatos[observacion]',
              un_empaque = '$arregloDatos[un_empaque]',
              embalaje = '$arregloDatos[embalaje]',
              fecha_expira = '$arregloDatos[fechaexpira]'
            WHERE codigo = '$arregloDatos[id_item]'";
    //echo $sql;
    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al consultar Inventario ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  function addMovimiento($arregloDatos) {
  }

  function addInventario($arregloDatos) {
    if($arregloDatos[origen]=='dividir') {// se esta dividiendo el item por lo cual se heredan los datos del padre 10/09/2016
      $campos_adicional = ",modelo,fmm,embalaje";
      $valor_adicional = ",'$arregloDatos[modelo]','$arregloDatos[fmm]','$arregloDatos[embalaje]'";
    } else {
      $arregloDatos[referencia] = 1;
      $arregloDatos[un_empaque] = 1;
    }
    if(empty($arregloDatos[peso_bruto])) $arregloDatos[peso_bruto] = 0;
    if(empty($arregloDatos[cantidad])) $arregloDatos[cantidad] = 0;
    if(empty($arregloDatos[valor_fob])) $arregloDatos[valor_fob] = 0;
    if(empty($arregloDatos[fechaexpira])) $arreglo[fechaexpira] = '0000-00-00';
    $fecha = date('Y/m/d');
    $sql = "INSERT INTO inventario_entradas(orden, arribo, fecha, referencia, posicion, un_empaque, cantidad, peso, valor, fecha_expira)
						VALUES('$arregloDatos[do_asignado]','$arregloDatos[id_arribo]','$fecha',1,1,1,$arregloDatos[cantidad],$arregloDatos[peso_bruto],
										$arregloDatos[valor_fob], '$arregloDatos[fechaexpira]')";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      $this->mensaje = "Error al crear registro de Inventario ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }	   
  }

  function selectUbicacion($arregloDatos) {
    $sql = "SELECT posiciones.codigo,posiciones.nombre,ru.rango ,ru.inicio,ru.fin,posiciones_fin.nombre as fin_label
            FROM referencias_ubicacion ru
			RIGHT JOIN  posiciones AS posiciones_fin ON ru.fin=posiciones_fin.codigo,
			posiciones
            WHERE ru.ubicacion = posiciones.codigo
              AND item = $arregloDatos[id_item]";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      $this->mensaje = "Error al crear registro de Inventario ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }

    $arreglo = array();
    while($this->fetch()) {
      $arreglo[$this->codigo] = ucwords(strtolower($this->nombre));
    }
    
    return $arreglo;
  }

  function delUbicacion($arregloDatos) {
    $sql = "DELETE FROM referencias_ubicacion WHERE item = $arregloDatos[id_item]";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      $this->mensaje = "Error al crear registro de Inventario ";
      $this->estilo = $this->estilo_error;

      return TRUE;
    }
  }

  function getLastItem($arregloDatos) {
    $sql = "SELECT MAX(codigo) AS item FROM inventario_entradas WHERE arribo = $arregloDatos[arribo]";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      $this->mensaje = "Error al crear registro de Inventario ";
      $this->estilo	= $this->estilo_error;
      return TRUE;
    }
  }

  function addUbicacion($arregloDatos) {
    if(empty($arregloDatos[inicio])) { 
      $arregloDatos[inicio] = 0;
    }
    if(empty($arregloDatos[fin])) { 
      $arregloDatos[fin] = 0;
    }
    if(!empty($arregloDatos[rango])) { 
      $arregloDatos[posicion] = $arregloDatos[inicio];
  	}
    $sql = "INSERT INTO referencias_ubicacion(item, ubicacion, rango,inicio,fin)
            VALUES('$arregloDatos[id_item]','$arregloDatos[posicion]','$arregloDatos[rango]','$arregloDatos[inicio]','$arregloDatos[fin]')";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      $this->mensaje = "Error al crear registro de Inventario ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }	   
  }

  function delInventario($arregloDatos) {
    $sql = "DELETE FROM inventario_entradas WHERE codigo = '$arregloDatos[id_item]'";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "Error al consultar Inventario ";
      $this->estilo	= $this->estilo_error;
      return TRUE;
    }
  }

  function findPosicion($arregloDatos) {
    $sql = "SELECT codigo, nombre FROM posiciones WHERE nombre LIKE '%$arregloDatos[q]%'
            UNION SELECT codigo, nombre FROM posiciones WHERE codigo = 1";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }

  function findReferencia($arregloDatos) {
   $arregloDatos[qmin]=strtolower($arregloDatos[q]);
   $arregloDatos[qmay]=strtoupper($arregloDatos[q]);
    $sql = "SELECT codigo, nombre, fecha_expira, serial, codigo_ref FROM referencias
            WHERE (nombre LIKE '%$arregloDatos[q]%' OR codigo_ref LIKE '%$arregloDatos[q]%' OR codigo_ref LIKE '%$arregloDatos[qmin]%' OR codigo_ref LIKE '%$arregloDatos[qmay]%')
              AND cliente = '$arregloDatos[id_cliente]'
           ";
	if($arregloDatos[filtro_arribo]){// si la invoca referencias solo busca las referencias de 1 arribo
		$sql .= " AND codigo IN( SELECT referencia FROM inventario_entradas where arribo=$arregloDatos[arribo])";
	}		
	$sql .= "  UNION SELECT codigo, nombre, fecha_expira, serial, codigo_ref FROM referencias WHERE codigo IN(1,2)";
   //echo $sql;
    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }
  
  function getPesoRetirado($arregloDatos) {  // 07/09/2016  se obtiene el peso retirado
  	$sql = "SELECT SUM(ABS(im.peso_naci))+ SUM(ABS(im.peso_nonac)) AS peso,
			SUM(ABS(im.cantidad_naci))+ SUM(ABS(im.cantidad_nonac)) AS cantidad,
			SUM(ABS(im.cif))+ SUM(ABS(im.fob_nonac)) AS valor
			 FROM inventario_movimientos im,inventario_entradas ie,arribos,do_asignados,referencias ref
			 WHERE im.inventario_entrada = ie.codigo
				AND arribos.arribo = ie.arribo
				AND arribos.orden = do_asignados.do_asignado
				AND ie.referencia = ref.codigo
				AND im.inventario_entrada = $arregloDatos[id_item]
				AND im.tipo_movimiento in(2,3,8,7,11,13,5)";
	$this->query($sql);
	if($this->_lastError) {
	}					
  }
  
  function setNuevoPeso($arregloDatos) {  // 07/09/2016  se actualiza peso del item cuando se divide despues de retiros
  	$sql = "UPDATE inventario_entradas SET peso=$arregloDatos[peso],
				      cantidad=$arregloDatos[cantidad],valor=$arregloDatos[valor],
              fecha_expira=$arregloDatos[fechaexpira]
            WHERE codigo=$arregloDatos[id_item]";
	//echo $arregloDatos[id_item];
	//echo 	$sql.'<br>';
	$this->query($sql);
	if($this->_lastError) {
	}
  }
  
   function setAjustaInventario($arregloDatos) {  // 05-11-2016 ajusta el inventario
   // El ingreso del inventario original se debe ajustar dado que se dividio la mercancia
  	$sql = "UPDATE inventario_movimientos SET peso_nonac=$arregloDatos[peso],
				      cantidad_nonac=$arregloDatos[cantidad],
				      fob_nonac=$arregloDatos[valor]
            WHERE inventario_entrada=$arregloDatos[id_item] AND tipo_movimiento=1";
	//echo 	$sql.'<br>';
	$this->query($sql);
	if($this->_lastError) {
	}
  }
}  
?>