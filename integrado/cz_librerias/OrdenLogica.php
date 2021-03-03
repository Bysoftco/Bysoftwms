<?php
require_once("OrdenDatos.php");
require_once("OrdenPresentacion.php");
require_once("ReporteExcel.php");
require_once("InventarioDatos.php");

class OrdenLogica {
	var $datos;
	var $pantalla;

	function OrdenLogica() {
		$this->datos =& new Orden();
		$this->pantalla =& new OrdenPresentacion($this->datos);
	} 

	function cargarFotos($arregloDatos) {
		$arregloDatos[mostrar] = 1;
		$arregloDatos[plantilla] = 'cargaFotos.html';
		$arregloDatos[thisFunction] = 'cargarFotos'; 
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}
  
	function verFotos($arregloDatos) {
		$ruta = "integrado/_files/";
		if(!empty($arregloDatos[carpeta])){
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
			$mensaje = "El archivo no se pudo subir al servidor, intentalo mas tarde";
		}
		echo $mensaje;
	}
 
  function regFotos($arregloDatos) {
    //Guarda la foto en la base de datos
    $arregloDatos[do_asignado] = $arregloDatos[numOrden];
    $arregloDatos[nFoto] = $arregloDatos[nFoto];
    $arregloDatos[thisFunction] = 'guardaFoto';
     
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
		$arregloDatos[mostrar_plantilla] = 'ordenListadoReapertura.html';
		$arregloDatos['metodoAux'] = 'maestroConsulta';

		$this->pantalla->maestroConsulta($arregloDatos);
	}

	function maestroReaPertura($arregloDatos) {
		$arregloDatos[mostrar_plantilla] = 'ordenListado.html';
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
			$arregloDatos[por_cuenta] = $arregloDatos[por_cuenta_filtro];
			$unDato->existeCliente($arregloDatos);
			$unDato->fetch();
			$titulo = $unDato->razon_social;
		}
		if(!empty($arregloDatos[ubicacion_filtro])) {
			$titulo .= " ubicación ".$unDato->dato('do_bodegas','codigo',$arregloDatos[ubicacion_filtro]);
		}
		if(!empty($arregloDatos[estado_filtro])) {
			$titulo .= " estado ".$unDato->dato('do_estados','codigo',$arregloDatos[estado_filtro]);
		}
		if(!empty($arregloDatos[fecha_inicio]) and !empty($arregloDatos[fecha_fin])) {
			$titulo .= " desde ".$arregloDatos[fecha_inicio]." hasta ".$arregloDatos[fecha_fin];
		}
		if(!empty($arregloDatos[doc_filtro])) {
			$titulo .= " documento ".$arregloDatos[doc_filtro];
		}
		if(!empty($arregloDatos[do_filtro])) {
			$titulo .= " Do ".$arregloDatos[do_filtro];
		}

		return ucwords(strtolower($titulo));
	}

	//Trae la información SI editable de un arribo
	function getArribo($arregloDatos) {
		if(empty($arregloDatos[mostrar])) { $arregloDatos[mostrar] = 1; }
		$arregloDatos[metodo] = 'consulta';
		$arregloDatos[plantilla] = 'ordenArriboInfo.html';
		$arregloDatos[thisFunction] = 'getArribo';
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function getUnaOrden($arregloDatos) {
		if(empty($arregloDatos[mostrar])) { $arregloDatos[mostrar] = 1; }
		$arregloDatos[metodo] = 'datosOrden';
		$arregloDatos[thisFunction] = 'datosOrden'; 
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function newCamion($arregloDatos) {
		$arregloDatos[metodo] = 'addCamion';
		$arregloDatos[plantilla] = 'camionAddCamion.html';
		$arregloDatos[thisFunction] = 'newCamion'; 
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function addCamion($arregloDatos) {
		$this->datos->addCamion($arregloDatos);
	}

	function existeCamion($arregloDatos) {
		$this->datos->existeCamion($arregloDatos);
	}

	function updateUnaOrden($arregloDatos) {
		$this->datos->updateUnaOrden($arregloDatos);
    //Verifica si el Cliente tiene asignado ZF_Code
    if(!empty($arregloDatos[zfcode])) {
      //Valida existencia de ZF_Code en GrupoItems
      if(!$this->datos->existeGrupoItems($arregloDatos)) {
        //Agrega ZF_Code en GrupoItems
        $this->datos->addGrupoItems($arregloDatos);          
      } else {
        //Actualiza ZF_Code en GrupoItems
        $this->datos->updGrupoItems($arregloDatos);
      }
      //Valida existencia de Documento en Referencia
      if(!$this->datos->existeCodigoRef($arregloDatos)) {
        //Creación Automática de Referencia al Cliente
        $this->datos->addReferencia($arregloDatos);          
      } else {
        //Actualización de Grupo_Item en Referencia
        $this->datos->updReferencia($arregloDatos);
      }
    }
		$this->getUnaOrden($arregloDatos);
	}

	function editarArribo($arregloDatos) {
		$arregloDatos[mensaje] = "x";
		if(empty($arregloDatos[mostrar])) { $arregloDatos[mostrar] = 1; }
		$arregloDatos[metodo] = 'consulta';
		$arregloDatos[plantilla] = 'ordenArribo.html';
		$arregloDatos[thisFunction] = 'getArribo';
		$this->pantalla->setFuncion($arregloDatos,$this->datos);	
	}

	//Trae la información NO editable de un arribo
	function unArribo($arregloDatos) {
		$arregloDatos[metodo] = 'consulta';
		$arregloDatos[plantilla] = 'ordenArriboInfo.html';
		$arregloDatos[thisFunction] = 'getArribo'; 
		$unArribo = $this->pantalla->setFuncion($arregloDatos,$this->datos);

		return $unArribo;
	}

	function filtro($arregloDatos) {
		$arregloDatos[mostrar] = 1;
		$arregloDatos[metodo] = 'consulta';
		$arregloDatos[plantilla] = 'ordenReporteFiltro.html';
		$arregloDatos[thisFunction] = 'filtro'; 
		$this->pantalla->cargaPlantilla($arregloDatos);
	}

	function filtroReapertura($arregloDatos) {
		$arregloDatos[mostrarFiltroEstado] = 'none';
		$arregloDatos[mostrar] = 1;
		$arregloDatos[metodo] = 'maestroReapertura';
		$arregloDatos[plantilla] = 'ordenReporteFiltro.html';
		$arregloDatos[thisFunction] = 'filtro'; 
		$this->pantalla->cargaPlantilla($arregloDatos);
	}

	function paraUpdate($arregloDatos) {
		$arregloDatos[mostrar] = 1;
		$arregloDatos[metodo] = 'updateOrden';
		$arregloDatos[tituloFormulario] = 'Modificar Orden';
		$arregloDatos[plantilla] = 'ordenUpdate.html';
		$arregloDatos[thisFunction] = 'datosOrden';  

		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function paraReapertura($arregloDatos) {
		$arregloDatos[mostrar] = 1;
		$arregloDatos[metodo] = 'updateOrden';
		$arregloDatos[tituloFormulario] = 'Modificar Orden';
		$arregloDatos[plantilla] = 'ordenReapertura.html';
		$arregloDatos[thisFunction] = 'datosOrden';  

		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function paraFacturar($arregloDatos) {
		$arregloDatos[mostrar] = 1;
		$arregloDatos[paraFacturar] = 1;
		$arregloDatos[plantilla] = 'ordenListadoParaFacturar.html';
		$arregloDatos[thisFunction] = 'listarOrdenes';

		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function addOrden($arregloDatos) {
		$arregloDatos[estado] = 1;
		list($arregloDatos[do_asignado],$arregloDatos[do_asignado_full]) = $this->datos->getConsecutivo($arregloDatos);
		if(!$this->datos->addOrden($arregloDatos)) {
			//Una vez registrado el DO en la tabla do_asignados se envía el primer email.
			$arregloDatos[plantilla_mail] = "mailCreacionDO.html";
			$arregloDatos[asunto_mail] = "Creación del DO: ".$arregloDatos[do_asignado_full];
			$this->envioMail($arregloDatos);
      //Verifica si el Cliente tiene asignado ZF_Code
      if(!empty($arregloDatos[zfcode])) {
        //Valida existencia de ZF_Code en GrupoItems
        if(!$this->datos->existeGrupoItems($arregloDatos)) {
          //Agrega ZF_Code en GrupoItems
          $this->datos->addGrupoItems($arregloDatos);
        }
        //Creación Automática de Referencia al Cliente
        $this->datos->addReferencia($arregloDatos);
      }

			//Creación Inicial del Arribo para su posterior Actualización
			$this->datos->addArribo($arregloDatos);
		}
		$this->pantalla->ordenArriboMaestro($arregloDatos,$this->datos);
	}

	function getOrden($arregloDatos) {
		$arregloDatos[tab_index] = 1;
    
		$this->pantalla->ordenArriboMaestro($arregloDatos,$this->datos);
	}

	function unaOrden($arregloDatos) {
		$arregloDatos[plantilla] = 'ordenDatosInfo.html';   		// Info de la orden
		$arregloDatos[thisFunction]	='datosOrden';
		$htmlDatosOrden =	$this->pantalla->setFuncion($arregloDatos,$unDatos);

		return  $htmlDatosOrden;
	}

	function addArribo($arregloDatos) {
		$arregloDatos[tab_index] = 1;
		//Valida creación arribo desde Orden Crear
		if($arregloDatos[flgnewa]) {
			$arregloDatos = $this->datos->newArribo($arregloDatos);
			$unaLista = new Orden();
			$lista = $unaLista->lista('transportador','','');
			$lista = armaSelect($lista,'[seleccione]',$arregloDatos['transportador']);		
			$arregloDatos[selectTransportador] = $lista;
		}

		$this->datos->addArribo($arregloDatos);
		$this->pantalla->ordenArriboMaestro($arregloDatos,$this->datos);
	}

	function delArribo($arregloDatos) {
		$arregloDatos[tab_index] = 1;
		$this->datos->delArribo($arregloDatos);
		unset($arregloDatos[id_arribo]);

		$this->pantalla->ordenArriboMaestro($arregloDatos,$this->datos);
	}

	function existeDocumento($arregloDatos) {
		$unaConsulta = new Orden();

		$unaConsulta->existeDocumento($arregloDatos);
		echo $unaConsulta->N;
	}

	function existeDo($arregloDatos) {
		$do = $arregloDatos[orden];

		$unaConsulta = new Orden();
		$unaConsulta->existeDo($do,$arregloDatos[bodega]);
		echo $unaConsulta->N;
	}

	function existeCliente($arregloDatos) {
		$unaConsulta = new Orden();

		$unaConsulta->existeCliente($arregloDatos);
		if($unaConsulta->N == 0) {
			echo $unaConsulta->N;
			die();
		}
		$unaConsulta->existeDocumento($arregloDatos);
		if($unaConsulta->N > 0) {
			echo 'docExistente';
			die();
		}
		echo 1;
	}

	function findCliente($arregloDatos) {
		$unaConsulta = new Orden();

		$unaConsulta->findCliente($arregloDatos);
		$arregloDatos[q] = strtolower($_GET["q"]);

		while($unaConsulta->fetch()) {
			$nombre = trim($unaConsulta->razon_social);
			echo "$nombre|$unaConsulta->numero_documento|$unaConsulta->correo_electronico|$unaConsulta->nvendedor\n";
		}
		if($unaConsulta->N == 0) {
			echo "No hay Resultados|0\n";
		}
	}

	function existeZFCode($arregloDatos) {
		$unaConsulta = new Orden();

		$unaConsulta->existeZFCode($arregloDatos);

		while($unaConsulta->fetch()) {
			$codigo = trim($unaConsulta->codigo);
			echo "$codigo\n";
		}
		if($unaConsulta->N == 0) { echo ""; }
	}
	
	function findReferencia($arregloDatos) {
		$unaConsulta = new Orden();

		$unaConsulta->findReferencia($arregloDatos);
		$arregloDatos[q] = strtolower($_GET["q"]);

		while($unaConsulta->fetch()) {
			$nombre = trim($unaConsulta->razon_social);
			echo "$unaConsulta->nombre|$unaConsulta->codigo\n";
		}
		if($unaConsulta->N == 0) {
			echo "No hay Resultados|0\n";
		}
	}
  
	function findModelo($arregloDatos) {
		$unaConsulta = new Orden();
		$arregloDatos[q] = trim(strtoupper($_GET["q"]));
		$unaConsulta->findModelo($arregloDatos);
		$arregloDatos[q] = trim(strtolower($_GET["q"]));

		while($unaConsulta->fetch()) {
			$nombre = trim($unaConsulta->razon_social);
			echo "$unaConsulta->nombre|$unaConsulta->codigo\n";
		}
		if($unaConsulta->N == 0) {
			echo "No hay Resultados|0\n";
		}
	}

	function findConductor($arregloDatos) {
		$unaConsulta = new Orden();

		$unaConsulta->findConductor($arregloDatos);
		$arregloDatos[q] = strtolower($_GET["q"]);

		while($unaConsulta->fetch()) {
			$nombre = '['.trim($unaConsulta->placa).']'.trim($unaConsulta->conductor_nombre);
			echo "$nombre|$unaConsulta->placa|$unaConsulta->conductor_identificacion|$unaConsulta->conductor_nombre|$unaConsulta->codigo\n";
		}
		if($unaConsulta->N == 0) {
			echo "No hay Resultados|0\n";
		}
	}
	
	function findPuerto($arregloDatos) {
		$unaConsulta = new Orden();

		$unaConsulta->findPuerto($arregloDatos);
		$arregloDatos[q] = strtolower($_GET["q"]);

		while($unaConsulta->fetch()) {
			$nombrepuerto = trim($unaConsulta->nombrepuerto).', '.trim($unaConsulta->pais);
      $pais = trim($unaConsulta->pais);
      $nompuerto = trim($unaConsulta->nombrepuerto);
			$puerto = trim($unaConsulta->puerto);
			echo "$nombrepuerto|$nompuerto|$pais|$puerto\n";
		}
		if($unaConsulta->N == 0) {
			echo "No hay Resultados|0\n";
		}	
	}
  
 	function findPaisCompra($arregloDatos) {
		$unaConsulta = new Orden();

		$unaConsulta->findPaisCompra($arregloDatos);
		$arregloDatos[q] = strtolower($_GET["q"]);

		while($unaConsulta->fetch()) {
			$paiscompra = trim($unaConsulta->nombre);
      $codigopais = trim($unaConsulta->codigo);
 			echo "$paiscompra|$codigopais\n";
		}
		if($unaConsulta->N == 0) {
			echo "No hay Resultados|0\n";
		}	
	}

 	function findShipper($arregloDatos) {
		$unaConsulta = new Orden();

		$unaConsulta->findShipper($arregloDatos);
		$arregloDatos[q] = strtolower($_GET["q"]);

		while($unaConsulta->fetch()) {
			$nombre = trim($unaConsulta->razon_social);
 			echo "$nombre\n";
		}
		if($unaConsulta->N == 0) {
			echo "No hay Resultados|0\n";
		}	
	}	
	
	function updateOrden($arregloDatos) {
		$this->datos->updateOrden($arregloDatos);
		$arregloDatos[estilo]	= $this->datos->estilo; $arregloDatos[mensaje] = $this->datos->mensaje;
		$arregloDatos[do_asignado] = NULL;
		$arregloDatos[codigo] = NULL;
		$this->consulta($arregloDatos);
	}

	function creaReapertura($arregloDatos) {
		$arregloDatos[reasignado] = 'Si';
		$arregloDatos[estado] = 4;
		$this->datos->addOrden($arregloDatos);
		$arregloDatos[estilo] = $this->datos->estilo; $arregloDatos[mensaje] = $this->datos->mensaje;
		$arregloDatos[do_asignado] = NULL;
		$arregloDatos[codigo] = NULL;
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
    if($arregloDatos[wadjunto] == '1') $mail->adjuntarArchivo($arregloDatos[adjuntos]);
		$mail->cuerpo($arregloDatos[plantilla_mail],$arregloDatos[plantilla_mail],$arregloDatos);
		$mail->cargarCabecera($destino, $remite, $arregloDatos[asunto_mail]);
		//Procedimiento de Envío de mail y validación de envío correcto
		$arregloDatos[info] = $mail->enviarEmail() ? TRUE : FALSE;
		$this->pantalla->mostrarMensaje($arregloDatos);
	}

	function updateArribo($arregloDatos) {
		$unaConsulta = new Orden();
		$unInventario = new Orden();
		$unaConsulta->destinatario($arregloDatos);
		// Cálculo de los días para Nacionalizar
		$segundos = strtotime($arregloDatos[fecha_manifiesto]) - strtotime('now');
		$diferencia_dias = 30 - abs(intval($segundos/60/60/24));
		$arregloDatos[dias] = $diferencia_dias;
		// Validación Fecha de Manifiesto
		if($diferencia_dias < 0)
			echo "<script>alert('Revisar Fecha del Manifiesto, el n\u00famero de d\u00edas para nacionalizar no puede ser mayor a 30 d\u00edas');</script>";
		$unInventario->setReferencia($arregloDatos);// setea la misma referencia en el INVENTARIO
		$this->datos->updateArribo($arregloDatos);
		$arregloDatos[mensaje_aux] = $this->datos->mensaje;
		$arregloDatos[estilo_aux]	= $this->datos->estilo;
		$arregloDatos[id_form] = $arregloDatos[id_form]/1;
    // Validamos Nuevo Arribo - Control de Tracking
    if(($arregloDatos[accion] == 'addArribo')||($arregloDatos[accion] == 'addOrden')) {
		  // Envía Mensaje de Correo para Tracking
		  $arregloDatos[plantilla_mail] = "mailVencimientos.html";
		  $arregloDatos[asunto_mail] = "Registro del Arribo: ".$arregloDatos[arribo];
      // Verifica internamente si hay archivos adjuntos
		  $this->envioMail($arregloDatos);
    }

		$arregloDatos[mostrar] = 1;
		$arregloDatos[plantilla] = 'ordenArriboInfo.html';
		$arregloDatos[thisFunction] = 'getArribo'; $arregloDatos[thisFunctionAux] = NULL;
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function getToolbar($arregloDatos) {
		$arregloDatos[plantilla] = 'inventarioToolbar.html';
		$arregloDatos[thisFunction] = 'getToolbar';  

		//$this->pantalla->cargaPlantilla($arregloDatos,$this->datos);
    $this->pantalla->cargaPlantilla($arregloDatos);
	}

	function listarFotos($arregloDatos) {
		$arregloDatos[plantilla] = 'ordenListaFotos.html';
		$arregloDatos[thisFunction] = 'listarFotos';  
		
		$this->pantalla->setFuncion($arregloDatos,$this->datos);
	}

	function reapertura($arregloDatos) {
		$arregloDatos[cod_estado] = 5;  // se coloca el estado en reapertura
		$this->datos->setEstado($arregloDatos);
	}
  
	function cargarArchivo($arregloDatos) {
		$ruta = "integrado/_mail/"; $arregloDatos[adjuntos] = '';    
		foreach($_FILES as $key) {
    	if($key['error'] == UPLOAD_ERR_OK ) {//Verificamos si se subio correctamente
				$nombre = $key['name']; //Definimos el nombre del archivo en el servidor
      	$temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
      	move_uploaded_file($temporal, $ruta . $nombre); //Movemos el archivo temporal a la ruta especificada
        $arregloDatos[adjuntos] .= $nombre.' ';
				$estado = true;
			} else {
				echo $key['error']; //Si no se cargo mostramos el error
				$estado = false;
			}
		}
		if($estado) {
			$mensaje = $arregloDatos[adjuntos];
		} else {
			$mensaje = "Este correo no contiene documentos adjuntos.";
		}
		echo $mensaje;
	}
  
  function impresion($arregloDatos) {
    //Configura fecha y hora de impresión
    $arregloDatos[fecha_impresion] = date('m-d-Y');
    $hora = getdate(time());
    $horas = strlen($hora["hours"]) == 1 ? '0'.$hora["hours"] : $hora["hours"];
    $minutos = strlen($hora["minutes"]) == 1 ? '0'.$hora["minutes"] : $hora["minutes"];
    $arregloDatos[hora_impresion] = $horas.":".$minutos;    
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'ordenActaIngreso.html';
    $arregloDatos[thisFunction]	= 'getActaIngreso'; 
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }  
}
?>