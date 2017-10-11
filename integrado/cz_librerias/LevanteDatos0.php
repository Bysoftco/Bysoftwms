<?php
require_once("MYDB.php");
require_once("OrdenDatos.php");

class Levante extends MYDB {
	function Levante() {
		$this->estilo_error = "ui-state-error";
		$this->estilo_ok = "ui-state-highlight";
		//VERSION 20032017
	}

 	//Función que consulta el inventario de mercancía no costeada
	function getInventario($arregloDatos) {
		$sql = "SELECT inv.cantidad AS cantidad_nonac,
									 inv.codigo AS item,
									 inv.arribo,
									 inv.peso AS peso_nonac,
									 inv.valor,
									 inv.fmm,
									 inv.referencia,
									 inv.modelo,
                   inv.posicion,
                   inv.observacion,
                   inv.un_empaque,
                   inv.embalaje,
                   do_asignados.por_cuenta AS cliente,
                   do_asignados.do_asignado AS orden,
                   embalajes.nombre AS nombre_empaque,
                   posiciones.nombre AS nombre_posicion,
                   ref.nombre AS nombre_referencia,
                   ref.ref_prove AS cod_referencia
						FROM inventario_entradas inv,referencias ref,embalajes,posiciones,do_asignados
						WHERE inv.referencia = ref.codigo
							AND embalajes.codigo = inv.un_empaque
							AND inv.posicion = posiciones.codigo
							AND do_asignados.do_asignado = inv.orden
							AND inv.valor = 0";
		
		if(!empty($arregloDatos[doc_filtro])) {
			$sql .= " AND do_asignados.doc_tte='$arregloDatos[doc_filtro]' ";
		}
		if(!empty($arregloDatos[cliente])) {
			$sql .= " AND do_asignados.por_cuenta='$arregloDatos[cliente]' ";
		}
			
		$this->query($sql);
		if($this->_lastError) {
			$this->mensaje = "Error al consultar Inventario ";
			$this->estilo = $this->estilo_error;
			return TRUE;
		}
	}

	//Función que lista la mercancía para ensamble
	function getParaProceso($arregloDatos) {
		$arregloDatos[having] = " HAVING peso_nonac  > 0 OR peso_naci > 0 ";
		if($arregloDatos[cod_ref]) {
			$arregloDatos[where] .=" AND ref.codigo ='$arregloDatos[cod_ref]' AND ie.orden='$arregloDatos[orden_retiro]'"; // filtro por referencia
		} else {
			$arregloDatos[GroupBy] = " cod_referencia";  // Orden Por número de levante
		}
		$arregloDatos[GroupBy] = "orden, cod_referencia";  // 
		$this->getInvParaProceso($arregloDatos);
	}

	//Función que lista la mercancía de cualquier movimiento
	function getInvParaProceso($arregloDatos) { //24-05-2016
    $sede = $_SESSION['sede'];
		if(!empty($arregloDatos[cliente]) or !empty($arregloDatos[por_cuenta_filtro])) {
			$arregloDatos[where] .= " AND (do_asignados.por_cuenta='$arregloDatos[cliente]' OR   do_asignados.por_cuenta='$arregloDatos[por_cuenta_filtro]') AND do_asignados.sede='$sede'";
		}
    if(!empty($arregloDatos[doc_filtro])) {
      //$arregloDatos[un_doc_filtro]=$arregloDatos[doc_filtro];
    }
    if(!empty($arregloDatos[un_doc_filtro])) {
      $documentos_varios = preg_replace('/\\s+/', "','", $arregloDatos[un_doc_filtro]);
      $arregloDatos[where] .= " AND do_asignados.doc_tte IN ('$documentos_varios')";
    }
    if(!empty($arregloDatos[guia_filtro])) {
      $documentos_varios = str_replace( "*" , "','" , $arregloDatos[guia_filtro] ) ;
      $arregloDatos[where] .= " AND do_asignados.doc_tte IN ('$documentos_varios')";
    }
    if(!empty($arregloDatos[referencia_filtro])) {
      $arregloDatos[where] .= " AND ref.codigo='$arregloDatos[referencia_filtro]' ";
    }
    if(!empty($arregloDatos[modelo_filtro])) {
		  $arregloDatos[where] .= " AND ie.modelo LIKE '%$arregloDatos[modelo_filtro]%' ";
    }	
		if($arregloDatos[tipo_retiro] == 11) { // Matriz
			$arregloDatos[where].=" AND do_asignados.do_asignado IN(SELECT DISTINCT imm.orden 
                            FROM inventario_maestro_movimientos imm,do_asignados da
                            WHERE imm.orden = da.do_asignado
                              AND tip_movimiento = 9
                              AND do_asignados.sede='$sede'
                              AND da.por_cuenta='$arregloDatos[por_cuenta_filtro]')";
		}
		// Si las cifras son negativas se convierte el valor en cero porque significa que ya se retiró toda la mercancía
		$sql = "SELECT orden,
									 doc_tte,
                   doc_tte AS doc_tte_aux,
									 inventario_entrada,
									 inventario_entrada AS item,
									 arribo, 
									 nombre_referencia,
									 cod_referencia,
									 codigo_referencia,
									 cant_declaraciones,
									 cantidad,
									 peso,
									 valor,
									 modelo,
									 SUM(peso_nonac) AS peso_nonac,
									 SUM(peso_naci) AS peso_naci,
									 SUM(cantidad_naci) AS cantidad_naci,
									 SUM(cantidad_nonac) AS cantidad_nonac,   
									 SUM(fob_nonac) AS fob_nonac,
									 SUM(cif) AS cif,
									 cod_maestro,
									 MIN(num_levante) AS num_levante,
									 un_grupo
						FROM (SELECT im.codigo,
										CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN 1 ELSE 0
										END AS movimiento,
										do_asignados.do_asignado AS orden,
										do_asignados.doc_tte AS doc_tte,
										ie.arribo,
										ref.nombre AS nombre_referencia,
										ref.codigo_ref AS cod_referencia,
										ref.codigo AS codigo_referencia,
										ie.cant_declaraciones,     
										ie.cantidad AS cantidad,
										ie.peso AS peso,
										ie.valor AS valor,
										ie.modelo AS modelo,
										im.inventario_entrada, 
										im.cod_maestro,
										im.num_levante,
										im.tipo_movimiento,
										id.grupo AS un_grupo,
										CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN peso_nonac ELSE 0
										END AS peso_nonac,
										CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN peso_naci ELSE 0
										END AS peso_naci,
										CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN cantidad_naci ELSE 0
										END AS cantidad_naci,
										CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN cantidad_nonac ELSE 0
										END AS cantidad_nonac,
										CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN fob_nonac	ELSE 0
										END AS fob_nonac,
										CASE WHEN im.tipo_movimiento IN($arregloDatos[movimiento]) THEN cif	ELSE 0
										END AS cif
									FROM  do_asignados, inventario_entradas ie,arribos,clientes,referencias ref,inventario_movimientos im
									LEFT JOIN inventario_maestro_movimientos imm ON im.cod_maestro = imm.codigo
									LEFT JOIN inventario_declaraciones id ON im.num_levante = id.num_levante
									WHERE im.inventario_entrada = ie.codigo
										AND arribos.arribo = ie.arribo
										AND arribos.orden = do_asignados.do_asignado
										AND clientes.numero_documento = do_asignados.por_cuenta
										AND ie.referencia = ref.codigo
										AND ie.cantidad > 0 $arregloDatos[where]) AS inv 
									GROUP BY $arregloDatos[GroupBy] $arregloDatos[having] $arregloDatos[orderBy]";
									//echo $sql;
		
		$this->_lastError = NULL;
		$this->query($sql);
		if($this->_lastError) {
			echo "Error" . $arregloDatos[metodo] . $sql . "<BR>";
			$this->mensaje = "Error al consultar Inventario1 ";
			$this->estilo = $this->estilo_error;
			return TRUE;
		}
	}

	//Función que lista el inventario para retirar solo devuelve mercancía disponible
	function getInvParaRetiro($arregloDatos) {
	
    $filtro = ($arregloDatos[tipo_retiro]==1) ? " peso_naci > 0" : " peso_nonac > 0 OR peso_naci > 0 ";
		$arregloDatos[having] = " HAVING $filtro ";
		if($arregloDatos[cod_ref]) {
			$arregloDatos[where] .=" AND ref.codigo = '$arregloDatos[cod_ref]' AND arribos.orden = '$arregloDatos[orden_retiro]'"; // filtro por referencia
		}
		$arregloDatos[GroupBy] = " codigo_referencia ";  // Orden Por numero de levante
		$arregloDatos[GroupBy] = " orden,codigo_referencia ";  // Agrupa por orden
		$this->getInvParaProceso($arregloDatos);
	}

	//CUANDO SE NACIONALIZA SOLO SE GUARDA EL VALOR NACIONALIZADO SIN RESTAR 
	//Función que lista el inventario para retirar
	function getInvParaRetiroX($arregloDatos) {
		if($arregloDatos[tipo_retiro] == 2 OR $arregloDatos[tipo_retiro_filtro] == 8) {
			$sqlVerNoNacional = " OR TRUNCATE(peso_nonac,1) > 0";
		}
		if(!empty($arregloDatos[id_item])) {
			$sqlFiltroItem = " AND ie.codigo = $arregloDatos[id_item]";
		}
		if(!empty($arregloDatos[por_cuenta_filtro])) {
			$sqlFiltroCliente = " AND do_asignados.por_cuenta = '$arregloDatos[por_cuenta_filtro]'";
		}
		//Aquí se aplican los filtros del formulario filtro
		if(!empty($arregloDatos[arribo_filtro])) {
			$sqlFiltroArribo = " AND arribos.arribo = '$arregloDatos[arribo_filtro]'";
		}
		if(!empty($arregloDatos[orden_filtro])) {
			$sqlFiltroOrden = " AND do_asignados.do_asignado = '$arregloDatos[orden_filtro]'";
		}
		if(!empty($arregloDatos[documento_filtro])) {
			$sqlFiltroDoc = " AND do_asignados.doc_tte = '$arregloDatos[documento_filtro]'";
		}
    $sql = "SELECT MAX(arribos.orden) AS orden,
									 inventario.inventario_entrada,
									 MAX(ie.arribo) AS arribo,
									 MAX(ie.codigo) AS item,
									 MAX(ie.cantidad) AS cantidad,
									 MAX(ie.peso) AS peso,
									 MAX(ie.valor) AS valor,
									 MAX(ie.modelo) AS modelo,
									 MAX(ie.fmm) AS fmm,
									 MAX(ie.embalaje) AS embalaje,
									 MAX(embalajes.nombre) AS nombre_embalaje,
									 MAX(do_asignados.doc_tte) AS doc_tte,
									 MAX(peso_naci) AS peso_naci,
									 MAX(peso_nonaci) AS peso_nonac,
									 MAX(cantidad_naci) AS cantidad_naci,
									 MAX(cantidad_nonac) AS cantidad_nonac,
									 MAX(cif) AS cif,
									 MAX(fob_nonac) AS fob_nonac,
									 MAX(ref.nombre) AS nombre_referencia,
									 MAX(ref.ref_prove) AS cod_referencia
						FROM (SELECT im.inventario_entrada,
										SUM(im.peso_naci) AS peso_naci,
										0 AS peso_nonaci,
										SUM(im.cantidad_naci) AS cantidad_naci,
										0 AS cantidad_nonac,
										SUM(im.cif) AS cif,
										0 AS fob_nonac
									FROM inventario_movimientos im
									WHERE tipo_movimiento IN(2,3)
									GROUP BY im.inventario_entrada
									UNION SELECT im.inventario_entrada,
													0 AS peso_naci,
													SUM(im.peso_nonac) AS peso_nonaci,
													0 AS cantidad_nonaci,
													SUM(im.cantidad_nonac) AS cantidad_nonac,
													0 AS cif,
													SUM(im.fob_nonac) AS fob_nonac
												FROM inventario_movimientos im
												WHERE tipo_movimiento IN(1,2,3)
												GROUP BY im.inventario_entrada) AS inventario,inventario_entradas ie,arribos,do_asignados,referencias ref,embalajes
						WHERE inventario.inventario_entrada = ie.codigo
							AND ie.referencia = ref.codigo
							AND ie.arribo = arribos.arribo
							AND do_asignados.do_asignado = arribos.orden
							AND embalajes.codigo = ie.un_empaque$sqlFiltroDoc$sqlFiltroOrden$sqlFiltroArribo$sqlFiltroCliente$sqlFiltroItem 
						GROUP BY ref.ref_prove
						HAVING TRUNCATE(peso_naci,1) > 0$sqlVerNoNacional";

		$this->query($sql);
		if($this->_lastError) {
			echo $sql;
			$this->mensaje = "Error al consultar Inventario ";
			$this->estilo = $this->estilo_error;
			return TRUE;
		}
	}

	//Este método muestra la mercancía un Item en el Levante
	function getMercancia($arregloDatos) { //Candidato a Borrarse reemplazado por getInvParaProceso
		if(!empty($arregloDatos[orden_filtro])) {
			$arregloDatos[where] .= " AND do_asignados.do_asignado = '$arregloDatos[orden_filtro]'";
		}
		$arregloDatos[where] .= " AND ref.codigo = '$arregloDatos[cod_ref]'"; //Filtro por referencia
		$arregloDatos[GroupBy] = " codigo_referencia "; //Orden Por Referencia
		$this->getInvParaProceso($arregloDatos);
	}

  //Verifica si la mercancía tiene movimientos
	function findMovimientos($arregloDatos) {
		if(!empty($arregloDatos[id_arribo])) {
			$arregloDatos[arribo] = $arregloDatos[id_arribo];
		}
		$sql = "SELECT DISTINCT MAX(im.codigo) AS id_mercancia
						FROM inventario_movimientos im,inventario_entradas ie,arribos,do_asignados,referencias ref
						WHERE im.inventario_entrada = ie.codigo
							AND arribos.arribo = ie.arribo
							AND arribos.orden = do_asignados.do_asignado
							AND ie.referencia = ref.codigo
							AND arribos.arribo = $arregloDatos[arribo]
							AND im.tipo_movimiento > 1";
			
		$this->query($sql);
		if($this->_lastError) {
			$this->mensaje_error = "Error al consultar operaciones ";
			$this->estilo = $this->estilo_error;
			return TRUE;
		}
		$this->fetch();
		if(!empty($this->id_mercancia)) {
			$arregloDatos[mensajeEditar] = "El arribo ya tiene movimientos por lo tanto no se puede modificar,intente deshacer los movimientos antes de modificar o borrar el arribo";
			$arregloDatos[mostrarEditar] = "none";
			$arregloDatos[estiloEditar] = "ui-state-highlight";
		} else {
			//Se verifica si hay inventario
			if($arregloDatos[thisFunction] == "getInventario" OR $arregloDatos[thisFunction] = "listaInventario") {
			} else {
				$this->findInventario($arregloDatos);
			}
		}
		return $this->id_mercancia;
	}

	//Lista la mercancía para Levante al sumar los campos peso_nonac calcula valores
	function listaInventario($arregloDatos) { //Para borrar reemplazada por getInvParaProceso
	
		//Verificar si se utiliza para un registro
		$arregloDatos[where] .= " AND do_asignados.do_asignado = '$arregloDatos[orden_filtro]'"; //Filtro por orden
		$arregloDatos[donde] = " sendlevante";
		$arregloDatos[having] = " HAVING peso_nonac > 0";
		$arregloDatos[GroupBy] = " codigo_referencia"; //Por Referencia
		$this->getInvParaProceso($arregloDatos);
	}

	//Agrega registro de mercancía para Proceso
	function addItemProceso($arregloDatos) {
    //Captura automática de fecha y hora 
    $fecha = new DateTime();
    $fecha = $fecha->format('Y-m-d H:i');
		$sql = "INSERT INTO inventario_movimientos
							(fecha,inventario_entrada,tipo_movimiento,peso_naci,peso_nonac,cantidad_naci,cantidad_nonac,cif,fob_nonac,cod_maestro,num_levante)
						VALUES('$fecha',$arregloDatos[id_item],$arregloDatos[tipo_movimiento],$arregloDatos[peso_naci_para],$arregloDatos[peso_nonaci_para],
							$arregloDatos[cantidad_naci_para],$arregloDatos[cantidad_nonaci_para],$arregloDatos[cif_ret],$arregloDatos[fob_ret],
							$arregloDatos[id_levante],'$arregloDatos[num_levante]')";
			
		$this->query($sql);
		if($this->_lastError) {
			$arregloDatos[mensaje] = "Error al enviar la mercanc&iacute;a a proceso ";
			$arregloDatos[estilo] = $this->estilo_error;
			echo $sql;
			return TRUE;
		}
		$arregloDatos[mensaje] = "Se envi&oacute; la mercanc&iacute;a a proceso correctamente  ";
		$arregloDatos[estilo] = $this->estilo_ok;
	}

  function addItemMatriz($arregloDatos) {
    if(empty($arregloDatos[fob_ret])) { $arregloDatos[fob_ret] = 0; }
    if(empty($arregloDatos[cif_ret])) { $arregloDatos[cif_ret] = 0; }
    //Captura automática de fecha y hora 
    $fecha = new DateTime();
    $fecha = $fecha->format('Y-m-d H:i');
    $sql = "INSERT INTO inventario_movimientos
              (fecha,inventario_entrada,tipo_movimiento,peso_naci,peso_nonac,cantidad_naci,cantidad_nonac,cif,fob_nonac,cod_maestro,num_levante)
            VALUES('$fecha',$arregloDatos[id_entrada],$arregloDatos[tipo_movimiento],$arregloDatos[peso_nal_pro],$arregloDatos[peso_ext_pro],$arregloDatos[cant_nal_pro],$arregloDatos[cant_ext],$arregloDatos[cif_ret], $arregloDatos[fob_ret] ,$arregloDatos[cod_maestro],'$arregloDatos[id_levante_retiro]')";
    $this->query($sql);
    if($this->_lastError) {
      $arregloDatos[mensaje] = "error al enviar la mercancia a proceso ";
      $arregloDatos[estilo] = $this->estilo_error;
      echo "error".$sql;
      return TRUE;
    }

    $arregloDatos[mensaje] = "se envi&oacute; la mercanc&iacute;a a proceso correctamente ";
    $arregloDatos[estilo] = $this->estilo_ok;
  }

  function getCanDeclaraciones($arregloDatos) {
    $this->getInvParaProceso($arregloDatos);
  }

  // Lista la mercancia en el cuerpo de movimientos de nacionalización y retiro
  function getCuerpoLevante($arregloDatos) {
    $arregloDatos[where] = " AND im.cod_maestro='$arregloDatos[id_levante]'"; // filtro para listar  cuerpo movimientos
    $arregloDatos[GroupBy] = " num_levante";
    $arregloDatos[orderBy] = " ORDER BY un_grupo";
    $this->getInvParaProceso($arregloDatos);
  }

  //Función en desarrollo OJO
  function matrizRetiroCuerpo1($arregloDatos) {
    $sql = "SELECT  im.cantidad_naci,
                    im.cantidad_nonac,
                    imm.cantidad_nac,
                    imm.cantidad_ext,
                    imm.cantidad,
                    imm.orden,
                    imm.pos_arancelaria,
                    im.codigo
            FROM inventario_movimientos im,inventario_maestro_movimientos imm,inventario_entradas ie
            WHERE im.cod_maestro = $arregloDatos[id_levante]
              AND im.inventario_entrada = ie.codigo
              AND ie.orden = imm.orden
              AND imm.tip_movimiento = 9";
    
    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
  }

  function matrizRetiroCuerpoX($arregloDatos) {
    $sql = "SELECT inventario_movimientos.*,
              referencias.nombre AS nombre_referencia,
              referencias.codigo_ref AS codigo_referencia,
              inventario_entradas.orden
            FROM inventario_movimientos,inventario_entradas,referencias
            WHERE cod_maestro
              IN (SELECT DISTINCT codigo
                  FROM inventario_maestro_movimientos
                  WHERE orden
                    IN (SELECT orden
                        FROM `inventario_movimientos`, inventario_entradas
                        WHERE `cod_maestro` = $arregloDatos[id_levante]
                          AND inventario_movimientos.inventario_entrada = inventario_entradas.codigo)
                    
                    AND inventario_entradas.codigo = inventario_movimientos.inventario_entrada
                    AND referencias.codigo = inventario_entradas.referencia
                 ) GROUP BY inventario_movimientos.codigo";
				 //AND inventario_movimientos.tipo_movimiento = 11
				 //OJO SE QUITO LINEA , EL DETALLE PUEDE SER NACIONAL O EXTRANJERO  23/02/2016

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
  }
    
  // Detalle Matriz de integración
  function matrizRetiroCuerpo($arregloDatos) {
    $sql = "SELECT inventario_movimientos.*, 
              referencias.nombre AS nombre_referencia, referencias.codigo_ref AS codigo_referencia, 
              referencias.unidad_venta,unidades_medida.medida AS u_comercial,inventario_entradas.orden,
              do_asignados.tipo_operacion, unidades_medida.medida
            FROM do_asignados,inventario_movimientos, inventario_entradas,referencias 
              LEFT JOIN unidades_medida ON referencias.unidad_venta = unidades_medida.id     
            WHERE num_levante =$arregloDatos[id_levante]
              AND cod_maestro IN(SELECT DISTINCT codigo FROM inventario_maestro_movimientos
                WHERE tip_movimiento =9
                  AND orden IN (SELECT orden
                    FROM `inventario_movimientos`,inventario_entradas
                    WHERE `cod_maestro` = $arregloDatos[id_levante]
                      AND inventario_movimientos.inventario_entrada = inventario_entradas.codigo))
                      AND do_asignados.do_asignado = inventario_entradas.orden       
                      AND tipo_movimiento = 11 
                      AND inventario_movimientos.inventario_entrada = inventario_entradas.codigo
                      AND referencias.codigo = inventario_entradas.referencia
                      AND (peso_naci+peso_nonac) > 0"; 

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
  }

  function matrizRetiroCuerpoF($arregloDatos) {
    $sql = "SELECT inventario_movimientos.*, 
              referencias.nombre AS nombre_referencia, referencias.codigo_ref AS codigo_referencia, 
              referencias.unidad_venta,unidades_medida.medida AS u_comercial,inventario_entradas.orden,
              do_asignados.tipo_operacion
            FROM do_asignados,inventario_movimientos, inventario_entradas,referencias 
              LEFT JOIN unidades_medida ON referencias.unidad_venta = unidades_medida.id     
            WHERE cod_maestro IN(SELECT DISTINCT codigo
              FROM inventario_maestro_movimientos
              WHERE orden IN (SELECT orden
                FROM `inventario_movimientos`,inventario_entradas
                WHERE `cod_maestro` = $arregloDatos[id_levante]
                  AND inventario_movimientos.inventario_entrada = inventario_entradas.codigo))
                  AND do_asignados.do_asignado = inventario_entradas.orden       
                  AND tipo_movimiento =11 
                  AND inventario_movimientos.inventario_entrada = inventario_entradas.codigo
                  AND referencias.codigo = inventario_entradas.referencia";

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
  }

  // Garantiza un solo registro en la cabeza
  function matrizRetiroCabeza($arregloDatos) {
    $sql = "SELECT DISTINCT (SELECT razon_social
              FROM inventario_movimientos im,inventario_entradas ie,arribos,do_asignados,clientes,referencias ref,posiciones p
              WHERE im.inventario_entrada = ie.codigo
                AND arribos.arribo = ie.arribo
                AND arribos.orden = do_asignados.do_asignado
                AND clientes.numero_documento = do_asignados.por_cuenta
                AND ie.referencia = ref.codigo
                AND p.codigo = ie.posicion
                AND im.cod_maestro = $arregloDatos[id_levante]
              GROUP BY razon_social) as razon_social,(SELECT numero_documento
                FROM inventario_movimientos im,inventario_entradas ie,arribos,
                  do_asignados,clientes,referencias ref,posiciones p
                WHERE im.inventario_entrada = ie.codigo
                  AND arribos.arribo = ie.arribo
                  AND arribos.orden = do_asignados.do_asignado
                  AND clientes.numero_documento = do_asignados.por_cuenta
                  AND ie.referencia = ref.codigo
                  AND p.codigo = ie.posicion
                  AND im.cod_maestro = $arregloDatos[id_levante]
                GROUP BY numero_documento) AS nit,(SELECT do_asignados.tipo_documento
                  FROM inventario_movimientos im,inventario_entradas ie,arribos,
                    do_asignados,clientes,referencias ref,posiciones p
                  WHERE im.inventario_entrada = ie.codigo
                    AND arribos.arribo = ie.arribo
                    AND arribos.orden = do_asignados.do_asignado
                    AND clientes.numero_documento = do_asignados.por_cuenta
                    AND ie.referencia = ref.codigo
                    AND p.codigo = ie.posicion
                    AND im.cod_maestro = $arregloDatos[id_levante]
                  GROUP BY do_asignados.tipo_documento) AS tipo_documento,		  
		                imm.doc_tte, imm.orden, imm.fecha, imm.ciudad, imm.unidad,
                    imm.cantidad, imm.peso, imm.valor, imm.pedido, imm. posicion,
                    imm.producto, imm.fmm, imm.codigo AS cod_producto,
                    referencias.codigo_ref AS codigo_pro, referencias.nombre AS nombre_producto
                  FROM inventario_maestro_movimientos imm 
                    LEFT JOIN referencias ON imm.producto = referencias.codigo
                  WHERE imm.codigo IN(SELECT DISTINCT codigo 
						        FROM inventario_maestro_movimientos
                    WHERE orden IN (SELECT orden 
                      FROM inventario_movimientos , inventario_entradas 
                      WHERE cod_maestro = $arregloDatos[id_levante]
            		 				AND inventario_movimientos.inventario_entrada = inventario_entradas.codigo)
                        AND tip_movimiento = 9)";
		
    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
  }
  
  // tienen el problema que duplica la cabeza
  function matrizRetiroCabeza2($arregloDatos) {
    $sql = "SELECT DISTINCT imm.fecha, imm.orden, imm.valor, imm.fmm, imm.pos_arancelaria,
              imm.pedido, ABS(imm.cantidad) AS cantidad, do_asignados.doc_tte,
              do_asignados.tipo_documento, ciudades.nombre AS ciudad,
              clientes.numero_documento AS nit, clientes.razon_social,
              referencias.codigo_ref AS codigo_pro, referencias.nombre AS nombre_producto
            FROM inventario_entradas ie,inventario_maestro_movimientos imm 
              LEFT JOIN clientes ON imm.lev_sia = clientes.numero_documento
              LEFT JOIN referencias ON imm.producto = referencias.codigo
              LEFT JOIN do_asignados ON imm.orden = do_asignados.do_asignado,
                inventario_movimientos im
              LEFT JOIN inventario_maestro_movimientos retiro ON im.cod_maestro = retiro.codigo
              LEFT JOIN ciudades ON retiro.ciudad = ciudades.codigo
            WHERE im.cod_maestro = $arregloDatos[id_levante]
              AND im.inventario_entrada = ie.codigo
              AND ie.orden = imm.orden
              AND imm.tip_movimiento = 9";

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
  }

  function matrizRetiroCabezaCopy($arregloDatos) {
    $sql = "SELECT DISTINCT im.fecha,
                    ABS(im.cantidad_naci) AS cantidad_naci,
                    ABS(im.cantidad_nonac) AS cantidad_nonac,
                    ABS(imm.cantidad_nac) AS cantidad_nac,
                    ABS(imm.cantidad_ext) AS cantidad_ext,
                    ABS(imm.cantidad) AS cantidad,
                    imm.orden,
                    do_asignados.doc_tte,
                    imm.valor,
                    imm.fmm,
                    imm.pos_arancelaria,
                    ciudades.nombre AS ciudad,
                    retiro.ciudad AS cod,
                    clientes.razon_social,
                    clientes.numero_documento AS nit,
                    referencias.codigo_ref AS codigo_pro,
                    referencias.nombre AS nombre_producto
            FROM inventario_entradas ie,inventario_maestro_movimientos imm 
              LEFT JOIN clientes ON imm.lev_sia = clientes.numero_documento
              LEFT JOIN referencias ON imm.producto = referencias.codigo
              LEFT JOIN do_asignados ON imm.orden = do_asignados.do_asignado,
                inventario_movimientos im
              LEFT JOIN inventario_maestro_movimientos retiro ON im.cod_maestro = retiro.codigo
              LEFT JOIN ciudades ON retiro.ciudad = ciudades.codigo
            WHERE im.cod_maestro = $arregloDatos[id_levante]
              AND im.inventario_entrada = ie.codigo
              AND ie.orden = imm.orden
              AND imm.tip_movimiento = 9";

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
  }

  // Se debe agrupar debido a los ajustes ya que esta funcionalidad genera otro recibo
  function getDatosRetiro($arregloDatos) {
    $sql = "SELECT MAX(inventario_maestro_movimientos.fecha) as fecha,
              ABS(SUM(cantidad_naci)) as cantidad_naci, ABS(SUM(peso_naci)) as peso_naci,
              ABS(SUM(cif)) as cif, ABS(SUM(cantidad_nonac)) as cantidad_nonac,
              ABS(SUM(peso_nonac)) as peso_nonac, ABS(SUM(fob_nonac)) as fob_nonac,
              inventario_entradas.orden, referencias.nombre as refe_retito
	           FROM inventario_maestro_movimientos, inventario_entradas, inventario_movimientos,
			         referencias	
			       WHERE inventario_entradas.codigo=inventario_movimientos.inventario_entrada
			         AND inventario_entradas.referencia =referencias.codigo
	             AND inventario_movimientos.cod_maestro=inventario_maestro_movimientos.codigo
			         AND inventario_maestro_movimientos.codigo=$arregloDatos[id_levante]
			         AND referencias.codigo <> 4
			       GROUP BY inventario_entradas.orden,referencias.nombre";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "Error al consultar el detalle del retiro " . $sql;
      echo $sql . "<br>";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  function getDatosAjustes($arregloDatos) {
    $sql = "SELECT MAX(inventario_maestro_movimientos.fecha) as fecha,
              SUM(cantidad_naci) as cantidad_naci, SUM(peso_naci) as peso_naci,
              SUM(cif) as cif, SUM(cantidad_nonac) as cantidad_nonac,
              SUM(peso_nonac) as peso_nonac, SUM(fob_nonac) as fob_nonac,
              inventario_entradas.orden, referencias.nombre as refe_retiro
            FROM inventario_maestro_movimientos, inventario_entradas, inventario_movimientos,
              referencias	
            WHERE inventario_entradas.codigo = inventario_movimientos.inventario_entrada
              AND inventario_entradas.referencia = referencias.codigo
              AND inventario_movimientos.cod_maestro = inventario_maestro_movimientos.codigo
              AND inventario_maestro_movimientos.codigo = $arregloDatos[id_levante]
              AND referencias.codigo = 4
            GROUP BY inventario_entradas.orden,referencias.nombre";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "Error al consultar el detalle del retiro " . $sql;
      echo $sql . "<br>";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }
  
  function matriz($arregloDatos) {
    // Devuelve los datos de la cabeza del Retiro y del Levante
    $sql = "SELECT  imm.codigo AS num_levante,
                    lev_bultos,
                    imm.fecha,
                    imm.destinatario,
                    imm.obs,
                    clientes.razon_social,
                    clientes.numero_documento AS nit,
                    imm.producto,
                    referencias.codigo,
                    referencias.nombre AS nombre_producto,
                    imm.cantidad,
                    imm.cantidad_nac,
                    imm.cantidad_ext,
                    imm.doc_tte,
                    imm.peso,
                    imm.valor,
                    imm.unidad,
                    imm.bodega,
                    imm.orden,
                    imm.cierre,
                    imm.fmm,
                    imm.orden,
                    imm.valor,
                    imm.pos_arancelaria
            FROM inventario_maestro_movimientos imm
              LEFT JOIN clientes ON imm.lev_sia = clientes.numero_documento
              LEFT JOIN referencias ON imm.producto = referencias.codigo
            WHERE imm.codigo = $arregloDatos[id_levante]";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al consultar Maestro de Movimientos " . $sql;
      echo $sql . "<br>";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  function getRetiro($arregloDatos) {
    $sql = "SELECT  imm.fecha,
                    imm.destinatario,
                    imm.obs,
                    imm.fmm,
                    camiones.conductor_nombre,
                    camiones.conductor_identificacion,
                    camiones.empresa,
                    camiones.placa,
                    clientes.razon_social AS nombre_cliente,
                    imm.direccion AS direccion,
                    ciudades.nombre AS ciudad
            FROM inventario_maestro_movimientos imm
              LEFT JOIN camiones ON imm.id_camion = camiones.codigo
              LEFT JOIN do_asignados ON imm.orden = do_asignados.do_asignado
              LEFT JOIN clientes ON do_asignados.por_cuenta = clientes.numero_documento
              LEFT JOIN ciudades ON imm.ciudad = ciudades.codigo
            WHERE imm.codigo = $arregloDatos[id_levante]";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al consultar Inventario " . $sql;
      echo $sql . "<br>";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  function getRegistrosRetiro($arregloDatos) {
    $sql = "SELECT * FROM inventario_maestro_movimientos WHERE codigo = $arregloDatos[id_levante]";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al consultar Inventario " . $sql;
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  function setCosteo($arregloDatos) {
    if(empty($arregloDatos[fob])) {
      $arregloDatos[fob] = 0;
    }

    $sql = "UPDATE inventario_entradas SET valor = $arregloDatos[fob] WHERE codigo = $arregloDatos[id_item]";

    //echo $sql;
    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al consultar Inventario " . $sql;
      $this->estilo = $this->estilo_error;
      echo 0;
    }
    echo 1;
  }

  function getCuerpoMercanciaProceso($arregloDatos) {
    $sql = "SELECT  SUM(im.cantidad_nonac) AS cantidad_nonac,
                    SUM(im.peso_nonac) AS peso_nonac,
                    ABS(SUM(im.cif)) AS cif,
                    ABS(SUM(im.cantidad_naci)) AS cantidad_naci,
                    ABS(SUM(im.peso_naci)) AS peso_naci,
                    ABS(SUM(im.fob_nonac)) AS fob_nonac,
                    imm.codigo AS cod_maestro,
                    ie.orden,
                    ie.codigo AS item,
                    referencias.nombre AS nombre_referencia,
                    referencias.codigo AS codigo_referencia
            FROM inventario_maestro_movimientos imm,inventario_movimientos im,inventario_entradas ie,referencias
            WHERE imm.codigo = im.cod_maestro
              AND ie.codigo = im.inventario_entrada	
              AND imm.tip_movimiento = 9
              AND referencias.codigo = ie.referencia
              AND im.tipo_movimiento IN(9,11)
              AND imm.orden = '$arregloDatos[orden_detalle]'
            GROUP BY im.inventario_entrada,referencias.codigo
            HAVING ABS(SUM(im.cantidad_nonac)) > 0 OR ABS(SUM(im.cantidad_naci)) > 0";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al consultar Inventario " . $sql;
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  // Lista la mercancía ya retirada
  function getCuerpoRetiro($arregloDatos) {
    if(empty($arregloDatos[este_movimiento])) {
      $arregloDatos[este_movimiento] = "3,7,8,9,13";
    }
    $sql = "SELECT  im.codigo                 AS id_retiro,
                    inventario_entrada        AS id_item,
                    MAX(tipo_movimiento)      AS tipo_movimiento,
                    SUM(peso_naci)            AS peso_naci ,
                    SUM(peso_nonac)           AS peso_nonac,
                    SUM(cantidad_nonac)       AS cantidad_nonac,
                    SUM(cantidad_naci)        AS cantidad_naci,
                    SUM(cif)                  AS cif,
                    SUM(fob_nonac)            AS fob_nonac,
                    MAX(arribos.orden)        AS orden,
                    MAX(arribos.arribo)       AS arribo,
                    MAX(ie.codigo)            AS cod_item,
                    MAX(ref.nombre)           AS nombre_referencia,
                    MAX(ref.codigo)           AS cod_referencia,
					MAX(ref.codigo_ref)       AS ref_cliente,
                    MAX(do_asignados.doc_tte) AS doc_tte,
                    MAX(arribos.ubicacion)    AS ubicacion,
                   MAX(imm.fmm) as fmm
            FROM inventario_entradas ie,
			
			 do_asignados,referencias ref,embalajes, arribos,
			  
			 inventario_movimientos im
			 
			  RIGHT JOIN inventario_maestro_movimientos imm ON  im.cod_maestro=imm.codigo
			 
            WHERE im.inventario_entrada = ie.codigo
              AND arribos.arribo = ie.arribo
              AND arribos.orden = do_asignados.do_asignado
              AND ie.referencia = ref.codigo
              AND embalajes.codigo = ie.un_empaque
              AND im.cod_maestro = '$arregloDatos[id_levante]'
              AND im.tipo_movimiento IN($arregloDatos[este_movimiento])";
    
    $sql .= " GROUP BY ie.orden,ref.codigo"; //inventario_entrada 
//echo $sql;
    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al consultar Inventario " . $sql;
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  function getCabezaProceso($arregloDatos) {
    $this->getCabezaLevante($arregloDatos);
  }

  function findBodega($arregloDatos) {
    $sql = "SELECT codigo,nombre FROM bodegas WHERE nombre LIKE '%$arregloDatos[q]%'";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }

  function findPosicion($arregloDatos) {
    $sql = "SELECT subpartida as codigo,descripcion as nombre,arancel
            FROM subpartidas
            WHERE descripcion LIKE '%$arregloDatos[q]%' OR subpartida LIKE '%$arregloDatos[q]%'";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }

  function findCiudad($arregloDatos) {
    $sql = "SELECT codigo,nombre FROM ciudades WHERE nombre LIKE '%$arregloDatos[q]%'";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }

  function findCliente($arregloDatos) {
    $sql = "SELECT numero_documento,razon_social,correo_electronico FROM clientes WHERE razon_social LIKE '%$arregloDatos[q]%'";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }
  
  function findClientet($arregloDatos) {
		$sql = "SELECT razon_social	FROM clientes WHERE (numero_documento = '$arregloDatos')";
    
		$this->query($sql);
		if($this->_lastError) {
			echo $sql;
			return TRUE;
		}
    $this->fetch();
    return $this->razon_social;
	}

  // Devuelve los datos de la cabeza del retiro y del levente
  function getCabezaLevante($arregloDatos) {
    $sql = "SELECT  imm.codigo AS num_levante,imm.lev_sia,lev_cant_declaraciones AS lev_cant,lev_bultos,
                    imm.fecha,imm.destinatario,imm.direccion,imm.obs,imm.fmm,imm.lev_cuenta_grupo,
                    clientes.razon_social,imm.producto,camiones.conductor_nombre,camiones.codigo AS id_camion,
                    camiones.placa,referencias.nombre AS nombre_producto,imm.cantidad,imm.cantidad_nac,
                    imm.cantidad_ext,imm.doc_tte,imm.peso,imm.valor,imm.unidad,imm.bodega,imm.orden,
                    imm.cierre,imm.pos_arancelaria,imm.tip_movimiento,imm.tipo_retiro,imm.posicion,
                    imm.pedido,imm.destinatario,imm.ciudad AS codigo_ciudad,ubicaciones.nombre AS nombre_ubicacion,
                    posiciones.nombre AS nombre_posicion,ciudades.nombre AS nombre_ciudad,bodegas.nombre AS nombre_bodega, 
                    destinatarios.razon_social AS nombre_destinatario
            FROM inventario_maestro_movimientos imm
              LEFT JOIN clientes ON imm.lev_sia = clientes.numero_documento
              LEFT JOIN camiones ON imm.id_camion = camiones.codigo
              LEFT JOIN referencias ON imm.producto = referencias.codigo
              LEFT JOIN ubicaciones ON imm.posicion = ubicaciones.codigo
              LEFT JOIN posiciones ON imm.posicion = posiciones.codigo
              LEFT JOIN ciudades ON imm.ciudad = ciudades.codigo
              LEFT JOIN bodegas ON imm.bodega = bodegas.codigo
              LEFT JOIN clientes AS destinatarios ON imm.destinatario = destinatarios.numero_documento
            WHERE imm.codigo = $arregloDatos[id_levante]";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al consultar Inventario " . $sql;
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  function setCabezaLevante(&$arregloDatos) {
    $total_peso = $arregloDatos[tot_peso_nonac] + $arregloDatos[tot_peso_nac];
    if(empty($arregloDatos[cantidad])) {
      $arregloDatos[cantidad] = 0;
    }

    $sql = "UPDATE inventario_maestro_movimientos
            SET lev_sia                 = '$arregloDatos[sia]',
                lev_bultos              = '$arregloDatos[lev_bultos]',
                lev_cant_declaraciones  = '$arregloDatos[lev_cant]',
                fmm                     = '$arregloDatos[fmm]',
                obs                     = '$arregloDatos[obs]',
                producto                = '$arregloDatos[producto]',
                unidad                  = '$arregloDatos[unidad]',
                cantidad                = '$arregloDatos[cantidad]',
                cantidad_ext            = '$arregloDatos[cantidad_ext]',
                cantidad_nac            = '$arregloDatos[cantidad_nac]',
                cierre                  = '$arregloDatos[cierre]',
                orden                   = '$arregloDatos[do_asignado]',
                doc_tte                 = '$arregloDatos[doc_tte]',
                valor                   = '$arregloDatos[valor]',
                peso                    = '$total_peso',
                posicion                = '$arregloDatos[posicion]',
                bodega                  = '$arregloDatos[id_bodega]',
                pos_arancelaria         = '$arregloDatos[pos_arancelaria]',
                pedido                  = '$arregloDatos[pedido]',
                ciudad                  = '$arregloDatos[codigo_ciudad]',
                id_camion               = '$arregloDatos[id_camion]',
                destinatario            = '$arregloDatos[destinatario]',
                direccion               = '$arregloDatos[direccion]'
            WHERE codigo = $arregloDatos[id_levante]";

    $this->_lastError = NULL;
    $this->query($sql);
    if($this->_lastError) {
      $arregloDatos[mensaje] = "error al actualizar Inventario " . $sql;
      $arregloDatos[estilo] = $this->estilo_error;
      return TRUE;
    }
    $arregloDatos[mensaje] = "Se guardo correctamente el registro ";
    $arregloDatos[estilo] = "ui-state-highlight";
  }

  function cantidadNacionalParcial($arregloDatos) { }

  function setConteoParciales($arregloDatos) {
    $sql = "UPDATE inventario_maestro_movimientos SET lev_cuenta_grupo = lev_cuenta_grupo + 1
            WHERE codigo = $arregloDatos[id_levante]";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al incrementar contador de parciales " . $sql;
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  // Obtiene el número de parciales 
  function getConteoParciales($arregloDatos) {
    $sql = "SELECT grupo FROM inventario_declaraciones
            WHERE cod_maestro = $arregloDatos[id_levante]
              AND grupo = $arregloDatos[grupo_borrado]";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al consultar numero de parciales " . $sql;
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  // Disminuye el número de parciales en caso que se borren Levantes
  function menosConteoParciales($arregloDatos) {
    $sql = "UPDATE inventario_maestro_movimientos SET lev_cuenta_grupo = lev_cuenta_grupo - 1
            WHERE codigo = $arregloDatos[id_levante] AND lev_cuenta_grupo = $arregloDatos[grupo_borrado]";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al ajustar parciales " . $sql;
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  // Disminuye el numero de parciales en caso que se borren Levantes
  function updateConteoParciales($arregloDatos) {
    $sql = "UPDATE inventario_maestro_movimientos SET lev_cuenta_grupo = $arregloDatos[num_grupo]
            WHERE codigo = $arregloDatos[id_levante]";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al ajustar parciales " . $sql;
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  // Función que retorna el último ID Insertado 
  function getConsecutivo($arregloDatos) {
    $fecha = FECHA;
    $sql = "SELECT LAST_INSERT_ID() AS consecutivo";

    $this->query($sql);
    if($this->_lastError) {
      $arregloDatos[mensaje] = " Error al obtener Consecutivo $sql" . $this->_lastError->message;
      $arregloDatos[estilo] = $this->estilo_error;
      return TRUE;
    }
    $this->fetch();
    return $this->consecutivo;
  }

  function newLevante(&$arregloDatos) {
    // se inserta como tipo de retiro el mismo tip-movimiento para formatear
    if($arregloDatos[tipo_retiro_filtro]) {
      $arregloDatos[tipo_retiro_filtro] = $arregloDatos[tipo_movimiento];
    }

    //Captura automática de fecha y hora 
    $fecha = new DateTime();
    $fecha = $fecha->format('Y-m-d H:i');
    $sql = "INSERT INTO inventario_maestro_movimientos(lev_documento,fecha,tip_movimiento,tipo_retiro,orden)
            VALUES('$arregloDatos[doc_filtro]','$fecha','$arregloDatos[tipo_movimiento]','$arregloDatos[tipo_retiro]','$arregloDatos[orden_filtro]')";

    $this->query($sql);
    if($this->_lastError) {
      $arregloDatos[mensaje] = "error al crear cabdoc del levante " . $sql;
      $arregloDatos[estilo] = $this->estilo_error;
      return TRUE;
    }
    
    return $this->getConsecutivo($arregloDatos);
  }

  function newDeclaracion($arregloDatos) {
    $fecha = FECHA;
    $sql = "INSERT INTO inventario_declaraciones(cod_maestro,fecha,num_declaracion,num_levante,tipo_declaracion,
              subpartida,modalidad,trm,fob,fletes,aduana,arancel,total,iva,obs,grupo)
            VALUES('$arregloDatos[id_levante]','$fecha','$arregloDatos[num_declaracion]','$arregloDatos[num_levante]',
              '$arregloDatos[tipo_declaracion]','$arregloDatos[cod_supartida]','$arregloDatos[modalidad]','$arregloDatos[trm]',
              '$arregloDatos[fob]','$arregloDatos[fletes]','$arregloDatos[aduana]','$arregloDatos[arancel]','$arregloDatos[total]',
              '$arregloDatos[iva]','$arregloDatos[obs]','$arregloDatos[grupo]')";

    $this->query($sql);
    if($this->_lastError) {
      $arregloDatos[mensaje] = "error al crear cabdoc del levante ";
      $arregloDatos[estilo] = $this->estilo_error;
      return TRUE;
    }

    $this->getLevante($arregloDatos);
    $this->fetch();
    return $this->codigo;
  }

  //Función que toma el ultimo Do y lo guarda en la tabla maestro para luego cruzar el cliente  
  function updateUltimoDo($arregloDatos) {
    $sql = "UPDATE inventario_maestro_movimientos
              SET orden = '$arregloDatos[orden]'
            WHERE codigo = '$arregloDatos[id_levante]'";

    $this->query($sql);
  }

  // Agrega registro de mercancia retirada
  function addItemRetiro($arregloDatos) {
    //si no existe un levante se deja como levante el id del movimiento esto permite borrar movimientos con varios registros 
    if(empty($arregloDatos[num_levante])) {
      $arregloDatos[num_levante] = $arregloDatos[id_levante];
    }

    switch($arregloDatos[tipo_retiro_filtro]) {
      case 1: // Mercancia Nacional

        $arregloDatos[peso_nonaci_para] = 0;
        $arregloDatos[cantidad_nonaci_para] = 0;
        $arregloDatos[fob_nonaci_para] = 0;
        break;
      case 2: // Reexportación
        break;
    }
	

    //Captura automática de fecha y hora 
    $fecha = new DateTime();
    $fecha = $fecha->format('Y-m-d H:i');
    $sql = "INSERT INTO inventario_movimientos
              (fecha,inventario_entrada,tipo_movimiento,peso_naci,peso_nonac,cantidad_naci,cantidad_nonac,cif,fob_nonac,cod_maestro,num_levante)
            VALUES('$fecha',$arregloDatos[id_item],$arregloDatos[tipo_movimiento],-$arregloDatos[peso_naci_para],-$arregloDatos[peso_nonaci_para],-$arregloDatos[cantidad_naci_para],-$arregloDatos[cantidad_nonaci_para],-$arregloDatos[cif_ret],- $arregloDatos[fob_ret] ,$arregloDatos[id_levante],'$arregloDatos[num_levante]')";

    $this->query($sql);
    if($this->_lastError) {
      $arregloDatos[mensaje] = "error al retirar la mercancia "; //$arregloDatos[mensaje] = "error al retirar la mercancia ";//
      $arregloDatos[estilo] = $this->estilo_error;
      echo "error" . $sql;
      return TRUE;
    }
    $this->updateUltimoDo($arregloDatos);
    $arregloDatos[mensaje] = "se retiro correctamente la mercancia ";
    $arregloDatos[estilo] = $this->estilo_ok;
  }

  function addItemAdicional(&$arregloDatos) {
    //El valor no tiene sentido restarse  peso_nonac
    if(empty($arregloDatos[peso_naci_para])) {
      $arregloDatos[peso_naci_para] = $arregloDatos[peso_naci_aux];
    } // cuando es el ultimo se inactiva y no pasa
	// si es un ajuste negativo debe matenerse el signo, como el insert es negativo se debe invertir
	if (!empty($arregloDatos[tipo_ajuste]) ){ $arregloDatos[peso_naci_para]=$arregloDatos[peso_naci_para]*-1;}
	if (!empty($arregloDatos[tipo_ajuste]) ){ $arregloDatos[cantidad_naci_para]=$arregloDatos[cantidad_naci_para]*-1;}
	if (!empty($arregloDatos[tipo_ajuste]) ){ $arregloDatos[cif]=$arregloDatos[cif]*-1;}
	
    //Captura automática de fecha y hora 
    $fecha = new DateTime();
    $fecha = $fecha->format('Y-m-d H:i');
    $sql = "INSERT INTO inventario_movimientos
              (fecha,inventario_entrada,tipo_movimiento,peso_naci,peso_nonac,cantidad_naci,cantidad_nonac,cif,fob_nonac,cod_maestro)
            VALUES('$fecha',$arregloDatos[id_item],9,-$arregloDatos[peso_naci_para],$arregloDatos[peso_ext_para],-$arregloDatos[cantidad_naci_para],$arregloDatos[cantidad_ext_para],-$arregloDatos[cif],$arregloDatos[fob],$arregloDatos[id_levante_adicional])";

    $this->query($sql);
    if($this->_lastError) {
      $arregloDatos[mensaje] .= "error al agregar adicional $sql ";
	  $arregloDatos[estilo] = $this->estilo_error;
      return TRUE;
    }

    $arregloDatos[mensaje] = "se agrego correctamente la mercancia ";
    $arregloDatos[estilo] = $this->estilo_ok;
  }

  function addItemLevante(&$arregloDatos) {
    //El valor no tiene sentido restarse
    if(empty($arregloDatos[peso_naci_para])) {
      $arregloDatos[peso_naci_para] = $arregloDatos[peso_naci_aux];
    } // cuando es el ultimo se inactiva y no pasa

    //Captura automática de fecha y hora 
    $fecha = new DateTime();
    $fecha = $fecha->format('Y-m-d H:i');
    $sql = "INSERT INTO inventario_movimientos
              (fecha,inventario_entrada,tipo_movimiento,peso_naci,peso_nonac,cantidad_naci,cantidad_nonac,cif,fob_nonac,cod_maestro,num_levante)
            VALUES('$fecha',$arregloDatos[id_item],2,$arregloDatos[peso_naci_para],0,$arregloDatos[cantidad_naci_para],0,$arregloDatos[fob_naci_para],-$arregloDatos[fob],$arregloDatos[id_levante],'$arregloDatos[num_levante]')";

    $this->query($sql);
    if($this->_lastError) {
      $arregloDatos[mensaje] .= "error al nacionalizar la mercancia "; //$arregloDatos[mensaje] = "error al nacionalizar la mercancia ";//
      $arregloDatos[estilo] = $this->estilo_error;
      return TRUE;
    }

    $arregloDatos[tipo_movimiento] = 30;
    $arregloDatos[peso_nonaci_para] = $arregloDatos[peso_naci_para];
    $arregloDatos[cantidad_nonaci_para] = $arregloDatos[cantidad_naci_para];
    $arregloDatos[fob_nonaci_para] = 0;
    $arregloDatos[cif_ret] = 0;
    $arregloDatos[fob_ret] = 0;
    $arregloDatos[peso_naci_para] = 0;
    $arregloDatos[cantidad_naci_para] = 0;
    $this->addItemRetiro($arregloDatos);

    // Se crea el registro de retiro
    $arregloDatos[mensaje] = "se nacionalizo correctamente la mercancia ";
    $arregloDatos[estilo] = $this->estilo_ok;
  }

  function getLevante($arregloDatos) {
    $sql = "SELECT codigo FROM inventario_maestro_movimientos
            WHERE orden = '$arregloDatos[orden_filtro]' AND tip_movimiento = 2";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al consultar ID levante ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  // Función que se encarga de acumular el valor CIF
  function getAcomulaCif($arregloDatos) {
    $sql = "UPDATE inventario_movimientos SET cif = cif + $arregloDatos[cif_para]
            WHERE inventario_entrada = $arregloDatos[id_item]";

    if($this->_lastError) {
      $this->mensaje = "error al consultar ID levante ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  // Devuelve el último id creado en la tabla inventario_maestro_movimientos
  function getIdRetiro($arregloDatos) {
    $sql = "SELECT MAX(codigo) AS codigo FROM inventario_maestro_movimientos";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al consultar ID levante ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  // Agrega registro de mercancia en la tabla de inventario_entrada
  function addItemInventario($arregloDatos) {
    $fecha = FECHA;
    $sql = "INSERT inventario_entradas (arribo,orden,fecha,referencia,posicion,un_empaque)
            VALUES ('$arregloDatos[arribo]','$arregloDatos[orden]',CURDATE(),$arregloDatos[producto_adicional],1,1)";

    $this->query($sql);
    if($this->_lastError) {
      $arregloDatos[mensaje] = "error al enviar la mercancia a proceso ";
      $arregloDatos[estilo] = $this->estilo_error;
      echo $sql;
      return TRUE;
    }
    $arregloDatos[mensaje] = "se envio la mercancia a proceso correctamente  ";
    $arregloDatos[estilo] = $this->estilo_ok;
  }

  // Obtiene inventario_entrada de la tabla inventario_movimientos
  function getParaInsertar(&$arregloDatos) {
    $sql = "SELECT MAX(arribo) AS arribo,MAX(orden) AS orden
            FROM inventario_entradas ie,inventario_movimientos im
            WHERE im.cod_maestro = '$arregloDatos[id_levante_adicional]'
              AND im.inventario_entrada = ie.codigo";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al obtener el campo inventario_entrada ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
    $this->fetch();
    $this->id_levante;
    $arregloDatos[arribo] = $this->arribo;
    $arregloDatos[orden] = $this->orden;
  }

  function delMercanciaLevante($arregloDatos) {
    $sql = "DELETE FROM inventario_movimientos WHERE num_levante = '$arregloDatos[num_levante_del]'";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al borrar mercancia del levante ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  function delMercanciaProceso($arregloDatos) {
    $sql = "DELETE FROM inventario_movimientos WHERE tipo_movimiento = 11
              AND num_levante = $arregloDatos[id_levante]";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al borrar mercancia del levante ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  // Actualiza las cantidades del inventario, segun la Matriz de Integración
  function updateMovimiento($arregloDatos) {
    $sql = "UPDATE inventario_movimientos
            SET cantidad_naci = '$arregloDatos[cantidad_nac]',
                cantidad_nonac = '$arregloDatos[cantidad_ext]',
                peso_naci = '$arregloDatos[tot_peso_nac]',
                peso_nonac = '$arregloDatos[tot_peso_nonac]',
                fob_nonac = '$arregloDatos[valor]'
            WHERE inventario_entrada = '$arregloDatos[inventario_entrada]'";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al actualizar el movimiento ";
      echo $sql;
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  // Recupera el ID del Nuevo producto  matriz de integracion creado
  function getIdInventario($arregloDatos) {
    $sql = "SELECT codigo FROM inventario_entradas WHERE arribo = '$arregloDatos[arribo]'";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al borrar mercancia del levante ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
	//echo   $sql;
    $this->fetch();
    return $this->codigo;
  }

  function delMercancia($arregloDatos) {
    $sql = "DELETE FROM inventario_movimientos
            WHERE codigo = '$arregloDatos[id_retiro_del]'
              OR (num_levante = '$arregloDatos[id_levante]' AND tipo_movimiento=30)
              OR (inventario_entrada=$arregloDatos[id_retiro_del] AND cod_maestro = $arregloDatos[id_levante])";

    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al borrar mercancia del levante ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
    $this->delMercanciaProceso($arregloDatos);
  }

  function existeCliente($arregloDatos) {
    $sql = "SELECT numero_documento,razon_social FROM clientes WHERE numero_documento = '$arregloDatos[por_cuenta]'";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }

  function existeLevante($arregloDatos) {
    $sql = "SELECT num_levante FROM inventario_movimientos WHERE num_levante = '$arregloDatos[num_levante]'";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }

  //Cuenta el número de declaraciones
  function cuentaDeclaraciones(&$arregloDatos) {
    $sql = "SELECT COUNT(DISTINCT num_declaracion) AS cantidad,SUM(peso_naci) AS peso_declaraciones
            FROM inventario_movimientos im,inventario_maestro_movimientos imm,inventario_declaraciones id
            WHERE imm.codigo = im.cod_maestro
              AND imm.codigo = id.cod_maestro
              AND tipo_movimiento = 2
              AND imm.codigo = $arregloDatos[id_levante]
              AND id.grupo = $arregloDatos[cuenta_grupos]";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
    $this->fetch();
    if(empty($this->peso_declaraciones)) {
      $this->peso_declaraciones = 0;
    }
    $arregloDatos[cant_declaraciones] = $this->cantidad;
    $arregloDatos[peso_declaraciones] = $this->peso_declaraciones;
  }

  //Cuenta el número de grupos creados
  function ultimoGrupoCreado($arregloDatos) {
    $sql = "SELECT MAX(grupo) AS cantidad FROM inventario_declaraciones WHERE cod_maestro = $arregloDatos[id_levante]";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
    $this->fetch();
    $arregloDatos[cuenta_grupos] = $this->cantidad;
    return $arregloDatos[cuenta_grupos];
  }

  //Cuenta el número de grupos en el caso que halla parciales
  function ultimoGrupo(&$arregloDatos) {
    $sql = "SELECT lev_cuenta_grupo AS cantidad FROM inventario_maestro_movimientos WHERE codigo = $arregloDatos[id_levante]";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
    $this->fetch();
    $arregloDatos[cuenta_grupos] = $this->cantidad;
    return $arregloDatos[cuenta_grupos];
  }

  function getCliente($arregloDatos) {
    $sql = "SELECT numero_documento,razon_social FROM clientes WHERE numero_documento = '$arregloDatos[por_cuenta_filtro]'";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }

  function lista($tabla, $condicion = NULL, $campoCondicion = NULL) {
    $sql = "SELECT codigo,nombre FROM $tabla WHERE codigo NOT IN('0')";

    if($condicion <> NULL AND $condicion <> '%') {
      $sql .= " AND $campoCondicion IN ('$condicion')";
    }
    $sql .= "	ORDER BY nombre";

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

  function getTipos() {
    $sql = "SELECT codigo,nombre FROM tipos_operacion WHERE codigo IN(24,15) ORDER BY nombre";

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

  function findDocumento($arregloDatos) {
    $sql = "SELECT DISTINCT doc_tte,do_asignado,SUM(im.peso_nonac) AS peso_nonac,SUM(im.peso_naci) AS peso_nac                
            FROM inventario_movimientos im,inventario_entradas ie,arribos,do_asignados
            WHERE im.inventario_entrada = ie.codigo
              AND arribos.arribo = ie.arribo
              AND arribos.orden = do_asignados.do_asignado
              AND do_asignados.por_cuenta = '$arregloDatos[cliente]'
              AND doc_tte LIKE '%$arregloDatos[q]%'
            GROUP BY doc_tte,do_asignado";

    switch($arregloDatos[tipo_movimiento]) {
      case 2: // Nacionalización 
        //$sql .= " HAVING  TRUNCATE(peso_nonac,1) > 0 ";
        break;
      case 3: // Retiro 
        // $sql .= " HAVING  TRUNCATE(peso_nac,1)   > 0 ";
        break;
    }
    
    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }

  function listarLevantes($arregloDatos) {
  //antes im.tipo_movimiento ahora imm_tip_movimiento en el select y en el where
    $sede = $_SESSION['sede'];
    $sql = "SELECT DISTINCT imm.fecha,imm.codigo AS cod_mov,imm.lev_documento,do_asignados.do_asignado,imm.tip_movimiento,
              imm.tipo_retiro,imm.fmm,itm.nombre AS nombre_movimiento,itm.descripcion AS descripcion,clientes.numero_documento,
              clientes.razon_social AS nombre_cliente,aduana.razon_social AS nombre_aduana,camiones.placa,camiones.conductor_nombre,
              im.tipo_movimiento as movimiento_tipo,
			  IF(imm.tip_movimiento= 3, tipos_remesas.nombre , '')as tipo_retiro_nombre
			  
            FROM inventario_movimientos im,inventario_tipos_movimiento itm,inventario_entradas ie,arribos,do_asignados,clientes,
              inventario_maestro_movimientos imm
              LEFT JOIN clientes aduana ON imm.lev_sia = aduana.numero_documento
              LEFT JOIN camiones ON imm.id_camion = camiones.codigo
			  LEFT JOIN tipos_remesas ON imm.tipo_retiro = tipos_remesas.codigo
            WHERE im.cod_maestro = imm.codigo
              AND imm.tip_movimiento = itm.codigo
              AND im.inventario_entrada = ie.codigo
              AND arribos.arribo = ie.arribo
              AND arribos.orden = do_asignados.do_asignado
              AND do_asignados.por_cuenta = clientes.numero_documento
              AND im.tipo_movimiento NOT IN(1,30)
              AND do_asignados.sede = '$sede'";
//echo  $sql;
    switch($arregloDatos[accion]) {
      case 'pedido':
        $arregloDatos[tipo_movimiento] = 7;
        break;
    }

    if(!empty($arregloDatos[por_cuenta_filtro])) {
      $sql .= " AND do_asignados.por_cuenta = '$arregloDatos[por_cuenta_filtro]' ";
    }
    if(!empty($arregloDatos[tipo_movimiento])) {
      $sql .= " AND im.tipo_movimiento = $arregloDatos[tipo_movimiento] ";
    }
    if(!empty($arregloDatos[fecha_inicio]) AND !empty($arregloDatos[fecha_fin])) {
      $sql .= " AND DATE(imm.fecha) >= '$arregloDatos[fecha_inicio]' AND DATE(imm.fecha) <= '$arregloDatos[fecha_fin]' ";
    }
    if(!empty($arregloDatos[doc_filtro])) {
      $sql .= " AND do_asignados.doc_tte = '$arregloDatos[doc_filtro]' ";
    }

    if(!empty($arregloDatos[do_filtro])) {
      $sql .= " AND do_asignados.do_asignado = '$arregloDatos[do_filtro]' ";
    }
    $sql .= " ORDER BY imm.codigo DESC";
 //echo $sql ."<br>";
    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }

  // Trae la información de un Levante   
  function traeLevante($arregloDatos) {
    $sql = "SELECT  inventario_movimientos.peso_naci,
                    inventario_movimientos.cif,
                    inventario_movimientos.cantidad_naci,
                    IF(inventario_movimientos.fob_nonac < 0 ,0,inventario_movimientos.fob_nonac) AS fob_nonac,
                    inventario_movimientos.peso_nonac,
                    inventario_movimientos.cantidad_nonac,
                    inventario_declaraciones.num_levante,
                    inventario_declaraciones.num_declaracion,
                    inventario_declaraciones.fecha,
                    inventario_declaraciones.modalidad,
                    inventario_declaraciones.trm,
                    inventario_declaraciones.fob,
                    inventario_declaraciones.fletes,
                    inventario_declaraciones.aduana,
                    inventario_declaraciones.arancel,
                    inventario_declaraciones.iva,
                    inventario_declaraciones.total,
                    inventario_declaraciones.obs,
                    inventario_declaraciones.tipo_declaracion,
                    inventario_declaraciones.subpartida,
                    ref.nombre AS nombre_referencia,
                    ref.ref_prove AS cod_referencia,
                    embalajes.nombre AS nombre_empaque,
                    ie.embalaje AS q_embalaje,
                    ie.modelo AS modelo,
                    do_asignados.doc_tte,imm.fmm
            FROM inventario_declaraciones,inventario_movimientos,inventario_entradas ie,arribos,do_asignados,referencias ref,embalajes,
                 inventario_maestro_movimientos imm
            WHERE inventario_declaraciones.num_levante = '$arregloDatos[num_levante]'
              AND inventario_declaraciones.cod_maestro = imm.codigo
              AND inventario_movimientos.inventario_entrada = ie.codigo
              AND arribos.arribo = ie.arribo
              AND inventario_declaraciones.num_levante = inventario_movimientos.num_levante
              AND arribos.orden = do_asignados.do_asignado
              AND ie.referencia = ref.codigo
              AND embalajes.codigo = ie.un_empaque
              AND inventario_movimientos.tipo_movimiento = 2";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }

  // Trae los valores por grupo,parcial
  function getSumaGrupo($arregloDatos) {
    $sql = "SELECT im.cod_maestro,SUM(im.cantidad_naci) AS sum_cant_naci
            FROM inventario_movimientos im,inventario_maestro_movimientos imm,inventario_declaraciones declaracion
            WHERE im.cod_maestro = imm.codigo
              AND declaracion.num_levante = im.num_levante
              AND im.cod_maestro = '$arregloDatos[id_levante]'
              AND declaracion.grupo = '$arregloDatos[id_grupo]'
            GROUP BY im.cod_maestro";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }

  function cambiaMovimientoCabeza($arregloDatos) {
    $sql = "UPDATE inventario_maestro_movimientos SET tip_movimiento = $arregloDatos[nuevo_estado]
            WHERE codigo = '$arregloDatos[id_levante]'";

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
  }

  function cambiaMovimientoCuerpo($arregloDatos) {
    $sql = "UPDATE inventario_movimientos SET tipo_movimiento = $arregloDatos[nuevo_estado]
            WHERE cod_maestro = '$arregloDatos[id_levante]'";

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
  }

  function getIdBloqueo($arregloDatos) {
    $sql = " SELECT MAX(codigo) AS id_bloqueo FROM controles_legales WHERE orden = '$arregloDatos[orden_bloqueo]'";

    $this->query($sql);
    if($this->_lastError) {
      echo "error al consultar id de ultimo bloqueo" . $sql;
      return TRUE;
    }
    $this->fetch();
    return $this->id_bloqueo;
  }

  function getEstadoBloqueo($arregloDatos) {
    $sql = "SELECT bloquea FROM controles_legales WHERE codigo = '$arregloDatos[id_bloqueo]'";

    $this->query($sql);
    if($this->_lastError) {
      echo "error al consultar estado  ultimo bloqueo" . $sql;
      return TRUE;
    }
    $this->fetch();
    return $this->bloquea;
  }

  function hayMovimientos($arregloDatos) {
    $sql = "SELECT MAX(nombre) AS movimiento,MAX(tipo_movimiento) AS tipo_movimiento
            FROM inventario_movimientos,inventario_tipos_movimiento
            WHERE inventario_movimientos.tipo_movimiento = inventario_tipos_movimiento.codigo
              AND inventario_entrada = '$arregloDatos[cod_item]'
              AND tipo_movimiento IN(3,6,8,9)";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return $this->N;
    }
  }

  function getToolbar($arregloDatos) { }

  function banderaMatriz1Revizar($arregloDatos) {
    $sql = "SELECT DISTINCT orden
            FROM inventario_movimientos im,inventario_entradas ie
            WHERE im.inventario_entrada=ie.codigo	
              AND im.tipo_movimiento = 9
              AND orden IN (SELECT orden FROM `inventario_movimientos` , inventario_entradas
                            WHERE `cod_maestro` = $arregloDatos[id_levante]
                              AND inventario_movimientos.inventario_entrada = inventario_entradas.codigo)";

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
    return $this->N;
  }
    
  function setCambio($arregloDatos) {
    $sql = "UPDATE inventario_maestro_movimientos
              SET fmm = $arregloDatos[cambio_fmm],valor = $arregloDatos[cambio_valor]
            WHERE orden = $arregloDatos[id_levante]
              AND tip_movimiento = 9";

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
  }

  function banderaMatriz($arregloDatos) {
    $sql = "SELECT DISTINCT orden FROM inventario_maestro_movimientos
            WHERE orden IN (SELECT orden FROM `inventario_movimientos` , inventario_entradas
                            WHERE `cod_maestro` = $arregloDatos[id_levante]
                              AND inventario_movimientos.inventario_entrada = inventario_entradas.codigo)
                              AND tip_movimiento = 9";

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
    return $this->N;
  }

  function thereIs($arregloDatos) {
    $sql = "SELECT * FROM inventario_maestro_movimientos WHERE tip_movimiento = 9 AND orden = '$arregloDatos[orden_retiro]'";

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
    return $this->N;
  }
  
  function getDatosInventario(&$arregloDatos) {
    $sql = "SELECT ABS(peso_nonac) + ABS(peso_naci) AS peso_endoso,ABS(cantidad_naci) + ABS(cantidad_nonac) AS cantidad_endoso,
              ABS(cif) + ABS(fob_nonac) AS valor,manifiesto,transportador,agente,origen,destino,shipper,fecha_manifiesto,planilla,
              repeso,placa,id_camion,hora_llegada,planilla_recepcion,metros,estibas,ubicacion,fecha_doc_tt,factura,fletes,moneda,
              arribos.fmm,sitio,dice_contener,arribos.observacion
            FROM inventario_movimientos,inventario_entradas,arribos
            WHERE tipo_movimiento = 13 AND cod_maestro = $arregloDatos[id_levante]
              AND inventario_movimientos.inventario_entrada = inventario_entradas.codigo
              AND arribos.arribo = inventario_entradas.arribo";

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
    $this->fetch();
    $arregloDatos[peso_endoso] = $this->peso_endoso;
    $arregloDatos[cantidad_endoso] = $this->cantidad_endoso;
    $arregloDatos[manifiesto_endoso] = $this->manifiesto;
    $arregloDatos[transportador_endoso] = $this->transportador;
    $arregloDatos[agente_endoso] = $this->agente;
    $arregloDatos[origen_endoso] = $this->origen;
    $arregloDatos[shipper_endoso] = $this->shipper;
    $arregloDatos[fecha_manifiesto_endoso] = $this->fecha_manifiesto;
    $arregloDatos[planilla_endoso] = $this->planilla;
    $arregloDatos[repeso_endoso] = $this->repeso;
    $arregloDatos[placa_endoso] = $this->placa;
    $arregloDatos[id_camion_endoso] = $this->id_camion;
    $arregloDatos[hora_llegada_endoso] = $this->hora_llegada;
    $arregloDatos[planilla_recepcion_endoso] = $this->planilla_recepcion;
    $arregloDatos[metros_endoso] = $this->metros;
    $arregloDatos[estibas_endoso] = $this->estibas;
    $arregloDatos[ubicacion_endoso] = $this->ubicacion;
    $arregloDatos[fecha_doc_tt_endoso] = $this->fecha_doc_tt;
    $arregloDatos[factura_endoso] = $this->factura;
    $arregloDatos[fletes_endoso] = $this->fletes;
    $arregloDatos[valor_endoso] = $this->valor;
    $arregloDatos[moneda_endoso] = $this->moneda;
    $arregloDatos[fmm_endoso] = $this->fmm;
    $arregloDatos[sitio_endoso] = $this->sitio;
    $arregloDatos[dice_contener_endoso] = $this->dice_contener;
    $arregloDatos[observacion_endoso] = $this->observacion;
    $arregloDatos[destino_endoso] = $this->destino;
  }

  function updateDatosEndoso($arregloDatos) {
    $sql = "UPDATE arribos SET cantidad = $arregloDatos[cantidad_endoso],peso_bruto = $arregloDatos[peso_endoso],
              manifiesto = '$arregloDatos[manifiesto_endoso]',transportador = '$arregloDatos[transportador_endoso]',
              agente = '$arregloDatos[agente_endoso]',origen = '$arregloDatos[origen_endoso]',
              destino = '$arregloDatos[destino_endoso]',shipper = '$arregloDatos[shipper_endoso]',
              planilla = '$arregloDatos[planilla_endoso]',fecha_manifiesto = '$arregloDatos[fecha_manifiesto_endoso]',
              repeso = '$arregloDatos[cantidad_endoso]',peso_planilla = '$arregloDatos[peso_endoso]',
              placa = '$arregloDatos[placa_endoso]',id_camion = '$arregloDatos[id_camion_endoso]',
              hora_llegada = '$arregloDatos[hora_llegada_endoso]',planilla_recepcion = '$arregloDatos[planilla_recepcion_endoso]',
              metros = '$arregloDatos[metros_endoso]',estibas = '$arregloDatos[estibas_endoso]',ubicacion = '$arregloDatos[posicion]',
              fecha_doc_tt = '$arregloDatos[fecha_doc_tt_endoso]',factura = '$arregloDatos[factura_endoso]',
              fletes = '$arregloDatos[fletes_endoso]',valor_fob = '$arregloDatos[valor_endoso]',
              moneda = '$arregloDatos[moneda_endoso]',fmm = '$arregloDatos[fmm_endoso]',
              dice_contener = '$arregloDatos[dice_contener]',observacion = '$arregloDatos[observacion]'
            WHERE arribo = $arregloDatos[arribo]";

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
  }

  function updateDocumento($arregloDatos) {
    $sql = "UPDATE arribos
              SET cantidad = $arregloDatos[cantidad_endoso]
            WHERE arribo = $arregloDatos[arribo]";

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
  }

  function addIDOAnt($arregloDatos) {
    $sql = "UPDATE inventario_maestro_movimientos SET doc_tte = '$arregloDatos[doc_tte]'
            WHERE codigo = $arregloDatos[id_levante]";

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
  }
  
 
  function getDesperdicios($arregloDatos) {
    $sql = "SELECT SUM(inventario_movimientos.peso_naci) AS peso_naci,
              SUM(inventario_movimientos.cantidad_naci) AS cantidad_naci,
              SUM(inventario_movimientos.peso_nonac) AS peso_nonac,
              SUM(inventario_movimientos.cantidad_nonac) AS cantidad_nonac
            FROM do_asignados,inventario_movimientos, inventario_entradas,referencias 
              LEFT JOIN unidades_medida ON referencias.unidad_venta = unidades_medida.id     
            WHERE num_levante = $arregloDatos[id_levante]
              AND cod_maestro IN(SELECT DISTINCT codigo FROM inventario_maestro_movimientos
                WHERE tip_movimiento =9 AND orden IN (SELECT orden
                  FROM `inventario_movimientos`,inventario_entradas
                  WHERE `cod_maestro` = $arregloDatos[id_levante]
                    AND inventario_movimientos.inventario_entrada = inventario_entradas.codigo))
                    AND do_asignados.do_asignado = inventario_entradas.orden       
                    AND tipo_movimiento = 11 
                    AND inventario_movimientos.inventario_entrada = inventario_entradas.codigo
                    AND referencias.codigo = inventario_entradas.referencia
                    AND (peso_naci+peso_nonac) > 0
                    AND referencias.tipo = 60";

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql."<BR>";
      return TRUE;
    }			
  }
  
  //Solo se ajusta en retiro de matriz, no se ajusta en el armado,ensamble del producto de la matriz
  function getAjustes($arregloDatos) {
    $sql = "SELECT SUM(inventario_movimientos.peso_naci) AS peso_naci, 
              SUM(inventario_movimientos.cantidad_naci) AS cantidad_naci,
              SUM(inventario_movimientos.cantidad_nonac) AS cantidad_nonac,
              SUM(inventario_movimientos.peso_nonac) AS peso_nonac
            FROM do_asignados,inventario_movimientos, inventario_entradas,referencias 
              LEFT JOIN unidades_medida ON referencias.unidad_venta = unidades_medida.id     
            WHERE cod_maestro = $arregloDatos[id_levante]
              AND do_asignados.do_asignado = inventario_entradas.orden       
              AND inventario_movimientos.inventario_entrada = inventario_entradas.codigo
              AND referencias.codigo = inventario_entradas.referencia
              AND referencias.codigo=4";

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
  }
  
  function getNacional($arregloDatos) {
    //El indicador 11 Es para que tome los registros que hacen parte de la matriz
    $sql = "SELECT ABS(sum(inventario_movimientos.peso_naci)) AS peso, 
              ABS(sum(inventario_movimientos.cantidad_naci)) AS cantidad
            FROM do_asignados,inventario_movimientos, inventario_entradas,referencias 
              LEFT JOIN unidades_medida ON referencias.unidad_venta = unidades_medida.id     
            WHERE num_levante =$arregloDatos[id_levante] 
              AND cod_maestro IN(SELECT DISTINCT codigo FROM inventario_maestro_movimientos
                WHERE tip_movimiento = 9 AND orden IN (SELECT orden
                  FROM `inventario_movimientos`,inventario_entradas
                  WHERE `cod_maestro` = $arregloDatos[id_levante]
                    AND inventario_movimientos.inventario_entrada = inventario_entradas.codigo))
                    AND do_asignados.do_asignado = inventario_entradas.orden       
                    AND inventario_movimientos.tipo_movimiento in(11) 
                    AND inventario_movimientos.inventario_entrada = inventario_entradas.codigo
                    AND referencias.codigo = inventario_entradas.referencia
                    AND do_asignados.tipo_operacion IN(24,31)";
			  
    //24 31  PRODUCTO QUE INGRESA DIRECTAMENTE COMO NACIONAL
    // AND tipo_operacion in(24,31)

    $this->query($sql);
    if($this->_lastError) {
      echo "error" . $sql;
      return TRUE;
    }
  }

  function sigla($arregloDatos) {
    $sede = $_SESSION['sede'];
    $sql = "SELECT sigla FROM do_bodegas  WHERE (sede = '$sede')";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
    $this->fetch();
    return $this->sigla;
  }
  
    function traeCiudades($arregloDatos) {

        $sql = "select codigo,nombre
                 from ciudades 
                 where departamento='$arregloDatos[departamento]' 
                 order by nombre";

        $this->query($sql);
       
        if ($this->_lastError) {
            $arregloDatos[mensaje] = " Error al consultar las ciudades ";
            $arregloDatos[estilo] = "ui-state-highlight";
            return false;
        }

        while ($this->fetch()) {
            $this->nombre = ucwords(strtolower(utf8_encode($this->nombre)));
            $arreglo .="<option value='$this->codigo'>$this->nombre</option> ";
        }
        if (empty($permisos)) {
            $arreglo .="<option selected value='0'>[Todos]</option> ";
        }

        echo $arreglo;
    }
  
  function tipos_remesas($tipo_retiro) {
    $sede = $_SESSION['sede'];
    $sql = "SELECT nombre FROM tipos_remesas  WHERE (codigo = $tipo_retiro)";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
    $this->fetch();
    return strtoupper($this->nombre);
  }
  
   function gerIFM( $arregloDatos) {
   //var_dump($arregloDatos);
   		 $sql = "SELECT MAX(imm.fmm) AS fmm
				FROM 
				inventario_maestro_movimientos imm,
				inventario_declaraciones id,
				inventario_movimientos im,
				inventario_entradas ie
				WHERE tip_movimiento=2
				AND imm.codigo=id.cod_maestro
				AND im.cod_maestro=imm.codigo
				AND im.inventario_entrada=ie.codigo
				AND ie.orden='$arregloDatos[una_orden]'";
		  $this->query($sql);
       //echo $sql;
        if ($this->_lastError) {
            $arregloDatos[mensaje] = " Error al consultar las ciudades ";
            $arregloDatos[estilo] = "ui-state-highlight";
            return false;
        }
   }
     function getIva( $arregloDatos) {
	 $sql = "select iva from servicios where codigo=191919";
	  $this->query($sql);
	 	if ($this->_lastError) {
            $arregloDatos[mensaje] = " Error al consultar el IVA ";
            $arregloDatos[estilo] = "ui-state-highlight";
            return false;
        }
	 }
	 
	  function getDeclaracion( $arregloDatos) {
	   
	 $sql = "SELECT max(num_declaracion) as num_declaracion   FROM inventario_declaraciones WHERE cod_maestro='$arregloDatos[maestro]'";
	 
	  $this->query($sql);
	  
	 	if ($this->_lastError) {
            $arregloDatos[mensaje] = " Error al consultar declaraciones ";
            $arregloDatos[estilo] = "ui-state-highlight";
            return false;
        }
	 }
	 
	 function borrarUbicaciones( $arregloDatos) {
	 	$fecha = date('Y-m-d');
	 	$sql = "UPDATE referencias_ubicacion INNER JOIN inventario_entradas  ON
				(referencias_ubicacion.item=inventario_entradas.codigo
				AND orden= '$arregloDatos[orden_retiro]' AND referencia= '$arregloDatos[cod_ref]')
  				SET referencias_ubicacion.estado_retiro=1,
				referencias_ubicacion.fecha='$fecha'
				
				";
			//var_dump($arregloDatos);	
	 	$this->query($sql);
		//echo 	$sql."<br>";
		 if ($this->_lastError) {
		 	$arregloDatos[mensaje] = " Error al borrar ubicaciones ";
            $arregloDatos[estilo] = "ui-state-highlight";
            return false;
		 }
		 $this->borrarUbicacionesRetiradasUnMes( $arregloDatos);
	 }
	 
	 function borrarUbicacionesRetiradasUnMes( $arregloDatos) {
	 	$sql ="DELETE FROM referencias_ubicacion WHERE DATEDIFF(NOW() ,fecha)  > 30";
		//echo 	$sql."<br>";
		$this->query($sql);
		if ($this->_lastError) {
			$arregloDatos[mensaje] = " Error al borrar ubicaciones retiradas de un mes o mas ";
            $arregloDatos[estilo] = "ui-state-highlight";
		}
	 }
   
}
?>