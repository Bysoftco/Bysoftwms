<?php
require_once("OrdenDatos.php");
require_once("OrdenPresentacion.php");
require_once("InventarioDatos.php");

class OrdenLogica {
	var $datos;
	var $pantalla;

	function OrdenLogica() {
		$this->datos = new Orden();
		$this->pantalla = new OrdenPresentacion($this->datos);
	} 

	function cargarFotos($arregloDatos) {
		$arregloDatos['mostrar'] = 1;
		$arregloDatos['plantilla'] = 'cargaFotos.html';
		$arregloDatos['thisFunction'] = 'cargarFotos'; 
		$this->pantalla->cargaPlantilla($arregloDatos);
	}
  
	function verFotos($arregloDatos) {
		$ruta = "integrado/_files/";
		if(!empty($arregloDatos['carpeta'])) {
			$ruta = "integrado/firmas/";
		}
		
		foreach ($_FILES as $key) {
    	if($key['error'] == UPLOAD_ERR_OK ) {//Verificamos si se subio correctamente
				$nombre = $key['name']; //Definimos el nombre del archivo en el servidor
      	$temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
      	move_uploaded_file($temporal, $ruta . $nombre); //Movemos el archivo temporal a la ruta especificada
				$estado = true;
			} else {
				echo $key['error']; //Si no se cargo mostramos el error
				$estado = false;
			}
		}
		if($estado) {
			$mensaje = "$nombre";
		} else {
			$mensaje = "El archivo no se pudo subir al servidor, int&eacute;ntalo m&aacute;s tarde";
		}
		echo $mensaje;
	}
 
  function regFotos($arregloDatos) {
    //Guarda la foto en la base de datos
    $arregloDatos['do_asignado'] = $arregloDatos['numOrden'];
    $arregloDatos['nFoto'] = $arregloDatos['nFoto'];
    $arregloDatos['thisFunction'] = 'guardaFoto';
     
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

	//Elimina la foto seleccionada
	function regBorrar($arregloDatos) {
		$this->datos->regBorrar($arregloDatos);

		//Elimina contenido de variables    
		unset($arregloDatos['id']);
		unset($arregloDatos['orden']);
		unset($arregloDatos['nombre_foto']);

		$this->listarFotos($arregloDatos);
  }

	function maestroConsulta($arregloDatos) {
		$arregloDatos['mostrar_plantilla'] = 'ordenListadoReapertura.html';
		$arregloDatos['metodoAux'] = 'maestroConsulta';

		$this->pantalla->maestroConsulta($arregloDatos);
	}

	function maestroReaPertura($arregloDatos) {
		$arregloDatos['mostrar_plantilla'] = 'ordenListado.html';
		$maestroReaPerturaarregloDatos['titulo'] = $this->titulo($arregloDatos);
		$arregloDatos['metodoAux'] = 'maestroReaPertura';

		$this->pantalla->maestroConsulta($arregloDatos);
	}

	function consulta($arregloDatos) {
		$this->pantalla->listaDos($arregloDatos);
	}

	function maestro($arregloDatos) {
		$this->pantalla->maestro($arregloDatos);
	}

	function titulo($arregloDatos) {
		$unDato = new Orden();

		$titulo = '';
		if(!empty($arregloDatos['por_cuenta_filtro'])) {
			$arregloDatos['por_cuenta'] = $arregloDatos['por_cuenta_filtro'];
			//$unDato->existeCliente($arregloDatos);
			//$unDato->fetch();
			$unDato = $unDato->findClientet($arregloDatos);	
			$titulo = $unDato->razon_social;
		}
		if(!empty($arregloDatos['ubicacion_filtro'])) {
			$titulo .= " ubicación ".$unDato->dato('do_bodegas','codigo',$arregloDatos['ubicacion_filtro']);
		}
		if(!empty($arregloDatos['estado_filtro'])) {
			$titulo .= " estado ".$unDato->dato('do_estados','codigo',$arregloDatos['estado_filtro']);
		}
		if(!empty($arregloDatos['fecha_inicio']) and !empty($arregloDatos['fecha_fin'])) {
			$titulo .= " desde ".$arregloDatos['fecha_inicio']." hasta ".$arregloDatos['fecha_fin'];
		}
		if(!empty($arregloDatos['doc_filtro'])) {
			$titulo .= " documento ".$arregloDatos['doc_filtro'];
		}
		if(!empty($arregloDatos['do_filtro'])) {
			$titulo .= " Do ".$arregloDatos['do_filtro'];
		}

		return ucwords(strtolower($titulo));
	}

	//Trae la información SI editable de un arribo
	function getArribo($arregloDatos) {
		if(empty($arregloDatos['mostrar'])) { $arregloDatos['mostrar'] = 1; }
		$arregloDatos['metodo'] = 'consulta';
		$arregloDatos['plantilla'] = 'ordenArriboInfo.html';
		$arregloDatos['thisFunction'] = 'getArribo';
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function getUnaOrden($arregloDatos) {
		if(empty($arregloDatos['mostrar'])) { $arregloDatos['mostrar'] = 1; }
		$arregloDatos['metodo'] = 'datosOrden';
		$arregloDatos['thisFunction'] = 'datosOrden';
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function newCamion($arregloDatos) {
		$arregloDatos['metodo'] = 'addCamion';
		$arregloDatos['plantilla'] = 'camionAddCamion.html';
		$arregloDatos['thisFunction'] = 'newCamion'; 
		$this->pantalla->cargaPlantilla($arregloDatos);
	}

	function addCamion($arregloDatos) {
		$this->datos->addCamion($arregloDatos);
	}

	function existeCamion($arregloDatos) {
		$this->datos->existeCamion($arregloDatos);
	}

	function updateUnaOrden($arregloDatos) {
		$traeDatos = new Orden();
		$traeDatos->updateUnaOrden($arregloDatos);
    //Verifica si el Cliente tiene asignado ZF_Code
    if(!empty($arregloDatos['zfcode'])) {
      //Valida existencia de ZF_Code en GrupoItems
      if(!$traeDatos->existeGrupoItems($arregloDatos)) {
        //Agrega ZF_Code en GrupoItems
        $traeDatos->addGrupoItems($arregloDatos);          
      } else {
        //Actualiza ZF_Code en GrupoItems
        $traeDatos->updGrupoItems($arregloDatos);
      }
      //Valida existencia de Documento en Referencia
      if(!$traeDatos->existeCodigoRef($arregloDatos)) {
        //Creación Automática de Referencia al Cliente
        $traeDatos->addReferencia($arregloDatos);          
      } else {
        //Actualización de Grupo_Item en Referencia
        $traeDatos->updReferencia($arregloDatos);
      }
    }
		$this->getUnaOrden($arregloDatos);
	}

	function editarArribo($arregloDatos) {
		$arregloDatos['mensaje'] = "x";
		if(empty($arregloDatos['mostrar'])) { $arregloDatos['mostrar'] = 1; }
		$arregloDatos['metodo'] = 'consulta';
		$arregloDatos['plantilla'] = 'ordenArribo.html';
		$arregloDatos['thisFunction'] = 'getArribo';
		$this->pantalla->setFuncion($arregloDatos,$this->datos);	
	}

	//Trae la información NO editable de un arribo
	function unArribo($arregloDatos) {
		$arregloDatos['metodo'] = 'consulta';
		$arregloDatos['plantilla'] = 'ordenArriboInfo.html';
		$arregloDatos['thisFunction'] = 'getArribo'; 
		$unArribo = $this->pantalla->setFuncion($arregloDatos,$this->datos);

		return $unArribo;
	}

	function filtro($arregloDatos) {
		$arregloDatos['mostrar'] = 1;
		$arregloDatos['metodo'] = 'consulta';
		$arregloDatos['plantilla'] = 'ordenReporteFiltro.html';
		$arregloDatos['thisFunction'] = 'filtro'; 
		$this->pantalla->cargaPlantilla($arregloDatos);
	}

	function filtroReapertura($arregloDatos) {
		$arregloDatos['mostrarFiltroEstado'] = 'none';
		$arregloDatos['mostrar'] = 1;
		$arregloDatos['metodo'] = 'maestroReapertura';
		$arregloDatos['plantilla'] = 'ordenReporteFiltro.html';
		$arregloDatos['thisFunction'] = 'filtro'; 
		$this->pantalla->cargaPlantilla($arregloDatos);
	}

	function paraUpdate($arregloDatos) {
		$arregloDatos['mostrar'] = 1;
		$arregloDatos['metodo'] = 'updateOrden';
		$arregloDatos['tituloFormulario'] = 'Modificar Orden';
		$arregloDatos['plantilla'] = 'ordenUpdate.html';
		$arregloDatos['thisFunction'] = 'datosOrden';  

		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function paraReapertura($arregloDatos) {
		$arregloDatos['mostrar'] = 1;
		$arregloDatos['metodo'] = 'updateOrden';
		$arregloDatos['tituloFormulario'] = 'Modificar Orden';
		$arregloDatos['plantilla'] = 'ordenReapertura.html';
		$arregloDatos['thisFunction'] = 'datosOrden';  

		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function paraFacturar($arregloDatos) {
		$arregloDatos['mostrar'] = 1;
		$arregloDatos['paraFacturar'] = 1;
		$arregloDatos['plantilla'] = 'ordenListadoParaFacturar.html';
		$arregloDatos['thisFunction'] = 'listarOrdenes';

		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function addOrden($arregloDatos) {
		$traeDatos = new Orden();
		$arregloDatos['estado'] = 1;
		list($arregloDatos['do_asignado'],$arregloDatos['do_asignado_full']) = $traeDatos->getConsecutivo($arregloDatos);
		if(!$traeDatos->addOrden($arregloDatos)) {
			//Una vez registrado el DO en la tabla do_asignados se envía el primer email.
			$arregloDatos['plantilla_mail'] = "mailCreacionDO.html";
			$arregloDatos['asunto_mail'] = "Creación del DO: ".$arregloDatos['do_asignado_full'];
			$this->envioMail($arregloDatos);
      //Verifica si el Cliente tiene asignado ZF_Code
      if(!empty($arregloDatos['zfcode'])) {
        //Valida existencia de ZF_Code en GrupoItems
        if(!$traeDatos->existeGrupoItems($arregloDatos)) {
          //Agrega ZF_Code en GrupoItems
          $traeDatos->addGrupoItems($arregloDatos);
        }
        //Creación Automática de Referencia al Cliente
        $traeDatos->addReferencia($arregloDatos);
      }

			//Creación Inicial del Arribo para su posterior Actualización
			$traeDatos->addArribo($arregloDatos);
		}
		$this->pantalla->ordenArriboMaestro($arregloDatos);
	}

	function getOrden($arregloDatos) {
		$arregloDatos['tab_index'] = 1;
    
		$this->pantalla->ordenArriboMaestro($arregloDatos,$this->datos);
	}

	function unaOrden($arregloDatos) {
		$arregloDatos['plantilla'] = 'ordenDatosInfo.html';//Info de la orden
		$arregloDatos['thisFunction']	='datosOrden';
		$htmlDatosOrden =	$this->pantalla->setFuncion($arregloDatos,$this->datos);

		return $htmlDatosOrden;
	}

	function addArribo($arregloDatos) {
		$datos = new Orden();
		$arregloDatos['tab_index'] = 1;
		//Valida creación arribo desde Orden Crear
		if($arregloDatos['flgnewa']) {
			$arregloDatos = $datos->newArribo($arregloDatos);
			$unaLista = new Orden();
			$lista = $unaLista->lista('transportador','','');
			$lista = $unaLista->armSelect($lista,'[seleccione]',$arregloDatos['transportador']);		
			$arregloDatos['selectTransportador'] = $lista;
		}

		$datos->addArribo($arregloDatos);
		$this->pantalla->ordenArriboMaestro($arregloDatos,$this->datos);
	}

	function delArribo($arregloDatos) {
		$arregloDatos['tab_index'] = 1;
		$this->datos->delArribo($arregloDatos);
		unset($arregloDatos['id_arribo']);

		$this->pantalla->ordenArriboMaestro($arregloDatos,$this->datos);
	}

	function existeDocumento($arregloDatos) {
		$unaConsulta = new Orden();

		$resultado = $unaConsulta->existeDocumento($arregloDatos);
		echo $resultado;
	}

	function existeDo($arregloDatos) {
		$do = $arregloDatos['orden'];

		$unaConsulta = new Orden();
		$unaConsulta = $unaConsulta->existeDo($do,$arregloDatos['bodega']);
		echo $unaConsulta; //# de Registros encontrados
	}

	function existeCliente($arregloDatos) {
		$unaConsulta = new Orden();

		$Filas = $unaConsulta->existeCliente($arregloDatos);
		if($Filas == 0) {
			echo $Filas;
			die();
		}

		$result = $unaConsulta->existeDocumento($arregloDatos);
		if($result > 0) {
			echo 'docExistente';
			die();
		}
		echo 1;
	}

	function findCliente($arregloDatos) {
		$unaConsulta = new Orden();
		$arregloDatos['q'] = strtolower($_GET["q"]);
		$unaConsulta->findCliente($arregloDatos);

		$fila = 0;
		while($obj=$unaConsulta->db->fetch()) {
			$nombre = trim($obj->razon_social);
			$nit = strval($obj->numero_documento)."-".strval($obj->digito_verificacion);
			echo "$nombre|$obj->numero_documento|$obj->correo_electronico|$obj->nvendedor|$nit\n";
			$fila++;
		}
		if($fila == 0) {
			echo "No hay Resultados|0\n";
		}
	}

	function existeZFCode($arregloDatos) {
		$unaConsulta = new Orden();

		$codigo = trim($unaConsulta->existeZFCode($arregloDatos));
		echo "$codigo\n";
	}
	
	function findReferencia($arregloDatos) {
		$unaConsulta = new Orden();

		$arregloDatos['q'] = strtolower($_GET["q"]);
		$unaConsulta->findReferencia($arregloDatos);
		
		$fila = 0;
		while($obj=$unaConsulta->db->fetch()) {
			$nombre = trim($obj->nombre);
			echo "$nombre|$obj->codigo\n";
			$fila++;
		}
		if($fila == 0) {
			echo "No hay Resultados|0\n";
		}
	}
  
	function findModelo($arregloDatos) {
		$unaConsulta = new Orden();
		$arregloDatos['q'] = trim(strtolower($_GET["q"]));
		$unaConsulta = $unaConsulta->findModelo($arregloDatos);
		$Existe = count($unaConsulta);

		if($Existe == 0) echo "No hay Resultados|0\n";
		else {
			foreach($unaConsulta as $value) {
				$codigo = $value['codigo'];
				echo "$codigo\n";				
			}
		}
	}

	function findConductor($arregloDatos) {
		$unaConsulta = new Orden();
		$arregloDatos['q'] = strtolower($_GET["q"]);
		$conductor = $unaConsulta->findConductor($arregloDatos);
		$Existe = count($conductor);

		if($Existe == 0) echo "No hay Resultados|0\n";
		else {
			foreach($conductor as $value) {
				$nombre = '['.trim($value['placa']).'] '.trim($value['conductor_nombre']);
				$placa = trim($value['placa']);
				$idconductor = $value['conductor_identificacion'];
				$nmconductor = $value['conductor_nombre'];
				$codigo = $value['codigo'];
				echo "$nombre|$placa|$idconductor|$nmconductor|$codigo\n";	
			}
		}
	}
	
	function findPuerto($arregloDatos) {
		$unaConsulta = new Orden();
		$arregloDatos['q'] = strtolower($_GET["q"]);
		$puertos = $unaConsulta->findPuerto($arregloDatos);
		$Existe = count($puertos);

		if($Existe == 0) echo "No hay Resultados|0\n";
		else {
			foreach($puertos as $value) {
				$nompuerto = trim($value['nombrepuerto']);
				$pais = trim($value['pais']);
				$nombrepuerto = $nompuerto.', '.$pais;
				$puerto = trim($value['puerto']);
				echo "$nombrepuerto|$nompuerto|$pais|$puerto\n";				
			}
		}
	}
  
 	function findPaisCompra($arregloDatos) {
		$unaConsulta = new Orden();
		$arregloDatos['q'] = strtolower($_GET["q"]);
		$unaConsulta = $unaConsulta->findPaisCompra($arregloDatos);
		$Existe = count($unaConsulta);

		if($Existe == 0) echo "No hay Resultados|0\n";
		else {
			foreach($unaConsulta as $value) {
				$paiscompra = trim($value['nombre']);
				$codigopais = trim($value['codigo']);
				echo "$paiscompra|$codigopais\n";				
			}
		}
	}

 	function findShipper($arregloDatos) {
		$unaConsulta = new Orden();

		$arregloDatos['q'] = strtolower($_GET["q"]);
		$unaConsulta = $unaConsulta->findShipper($arregloDatos);
		$Existe = count($unaConsulta);

		if($Existe == 0) echo "No hay Resultados|0\n";
		else {
			foreach($unaConsulta as $value) {
				$nombre = trim($value['razon_social']);
				echo "$nombre\n";				
			}
		}	
	}	

 	function findBascula($arregloDatos) {
		$unaConsulta = new Orden();

		$arregloDatos['q'] = strtolower($_GET["q"]);
		$unaConsulta = $unaConsulta->findBascula($arregloDatos);
		$Existe = count($unaConsulta);

		if($Existe == 0) echo "No hay Resultados|0\n";
		else {
			foreach($unaConsulta as $value) {
				$nombre = trim($value['nombre']);
				echo "$nombre\n";				
			}
		}	
	}
	
	function findPosicion($arregloDatos) {
    $unaConsulta = new Orden();

    $arregloDatos['q'] = strtolower($_GET["q"]);
    $unaConsulta = $unaConsulta->findPosicion($arregloDatos);
		$Existe = count($unaConsulta);

		if($Existe == 0) echo "No hay Resultados|0\n";
		else {
			foreach($unaConsulta as $value) {
				$nombre = trim($value['nombre']);
				$codigo = $value['codigo'];
				echo "$nombre|$codigo\n";				
			}
		}
  }

	function updateOrden($arregloDatos) {
		$this->datos->updateOrden($arregloDatos);
		$arregloDatos['estilo']	= $this->datos->estilo; $arregloDatos['mensaje'] = $this->datos->mensaje;
		$arregloDatos['do_asignado'] = NULL;
		$arregloDatos['codigo'] = NULL;
		$this->consulta($arregloDatos);
	}

	function creaReapertura($arregloDatos) {
		$arregloDatos['reasignado'] = 'Si';
		$arregloDatos['estado'] = 4;
		$this->datos->addOrden($arregloDatos);
		$arregloDatos['estilo'] = $this->datos->estilo; $arregloDatos['mensaje'] = $this->datos->mensaje;
		$arregloDatos['do_asignado'] = NULL;
		$arregloDatos['codigo'] = NULL;
		$this->maestroReapertura($arregloDatos);
	}

	function excel($arregloDatos) {
		$arregloDatos['do_asignado'] = NULL;
		$arregloDatos['titulo'] = $this->titulo($arregloDatos);
		$arregloDatos['sql'] = $this->datos->datosOrden($arregloDatos);

		$unExcel = new ReporteExcel($arregloDatos);
		$unExcel->generarExcel();
	}

	function nuevoIngreso($arregloDatos) {
		echo 'listo para iniciar';
	}

	function envioMail($arregloDatos) {
		$arregloDatos['remite'] = 'blogistic@grupobysoft.com';
		$remite = array('email' => $arregloDatos['remite'],'nombre' => $arregloDatos['remite']);
		$destino = array('email'  => $arregloDatos['email'],'nombre' => $arregloDatos['email']);

		require_once('EnvioMail.php');
		$mail = new EnvioMail();

    // Verifica si tiene documentos adjuntos
    if($arregloDatos['wadjunto'] == '1') $mail->adjuntarArchivo($arregloDatos['adjuntos']);
		$mail->cuerpo($arregloDatos['plantilla_mail'],$arregloDatos['plantilla_mail'],$arregloDatos);
		$mail->cargarCabecera($destino, $remite, $arregloDatos['asunto_mail']);
		//Procedimiento de Envío de mail y validación de envío correcto
		$arregloDatos['info'] = $mail->enviarEmail() ? TRUE : FALSE;
		$this->pantalla->mostrarMensaje($arregloDatos);
	}

	function updateArribo($arregloDatos) {
		$unaConsulta = new Orden();
		$unInventario = new Orden();
		$unaConsulta->destinatario($arregloDatos);
		// Cálculo de los días para Nacionalizar
		$segundos = strtotime($arregloDatos['fecha_manifiesto']) - strtotime('now');
		$diferencia_dias = 30 - abs(intval($segundos/60/60/24));
		$arregloDatos['dias'] = $diferencia_dias;
		// Validación Fecha de Manifiesto
		if($diferencia_dias < 0)
			echo "<script>alert('Revisar Fecha del Manifiesto, el n\u00famero de d\u00edas para nacionalizar no puede ser mayor a 30 d\u00edas');</script>";
		$unInventario->setReferencia($arregloDatos);// setea la misma referencia en el INVENTARIO
		$this->datos->updateArribo($arregloDatos);
		$arregloDatos['mensaje_aux'] = $this->datos->mensaje;
		$arregloDatos['estilo_aux']	= $this->datos->estilo;
		$arregloDatos['id_form'] = $arregloDatos['id_form']/1;
    // Validamos Nuevo Arribo - Control de Tracking
    if(($arregloDatos['accion'] == 'addArribo')||($arregloDatos['accion'] == 'addOrden')) {
		  // Envía Mensaje de Correo para Tracking
		  $arregloDatos['plantilla_mail'] = "mailVencimientos.html";
		  $arregloDatos['asunto_mail'] = "Registro del Arribo: ".$arregloDatos['arribo'];
      // Verifica internamente si hay archivos adjuntos
		  $this->envioMail($arregloDatos);
    }

		$arregloDatos['mostrar'] = 1;
		$arregloDatos['plantilla'] = 'ordenArriboInfo.html';
		$arregloDatos['thisFunction'] = 'getArribo'; $arregloDatos['thisFunctionAux'] = NULL;
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function getToolbar($arregloDatos) {
		$arregloDatos['plantilla'] = 'Toolbar.html';
		$arregloDatos['thisFunction'] = 'getToolbar';  

    $this->pantalla->cargaPlantilla($arregloDatos);
	}

	function listarFotos($arregloDatos) {
		$arregloDatos['plantilla'] = 'ordenListaFotos.html';
		$arregloDatos['thisFunction'] = 'listarFotos';
		
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function reapertura($arregloDatos) {
		$arregloDatos['cod_estado'] = 5;  // se coloca el estado en reapertura
		$this->datos->setEstado($arregloDatos);
	}
  
	function cargarArchivo($arregloDatos) {
		$ruta = "integrado/_mail/"; $arregloDatos['adjuntos'] = '';    
		foreach($_FILES as $key) {
    	if($key['error'] == UPLOAD_ERR_OK ) {//Verificamos si se subio correctamente
				$nombre = $key['name']; //Definimos el nombre del archivo en el servidor
      	$temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
      	move_uploaded_file($temporal, $ruta . $nombre); //Movemos el archivo temporal a la ruta especificada
        $arregloDatos['adjuntos'] .= $nombre.' ';
				$estado = true;
			} else {
				echo $key['error']; //Si no se cargo mostramos el error
				$estado = false;
			}
		}
		if($estado) {
			$mensaje = $arregloDatos['adjuntos'];
		} else {
			$mensaje = "Este correo no contiene documentos adjuntos.";
		}
		echo $mensaje;
	}
  
  function impresion($arregloDatos) {
    //Configura fecha y hora de impresión
    $arregloDatos['fecha_impresion'] = date('m-d-Y');
    $hora = getdate(time());
    $horas = strlen($hora["hours"]) == 1 ? '0'.$hora["hours"] : $hora["hours"];
    $minutos = strlen($hora["minutes"]) == 1 ? '0'.$hora["minutes"] : $hora["minutes"];
    $arregloDatos['hora_impresion'] = $horas.":".$minutos;    
    $arregloDatos['mostrar'] = 1;
    $arregloDatos['plantilla'] = 'ordenActaIngreso.html';
    $arregloDatos['thisFunction']	= 'getActaIngreso'; 
    $this->pantalla->setImprimir($arregloDatos,$this->datos);
  }  
}
?>