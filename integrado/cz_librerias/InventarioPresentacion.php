<?php
require_once ("HTML/Template/IT.php");
require_once ("InventarioDatos.php");
require_once ("OrdenLogica.php");

class InventarioPresentacion {
  var $datos;
  var $plantilla;

  function InventarioPresentacion(&$datos) {
    $this->datos = $datos;
    $this->plantilla = new HTML_Template_IT();
  }
  	
  function mantenerDatos($arregloCampos,&$plantilla) {
    $plantilla = $plantilla;

    if(is_array($arregloCampos)) {
      foreach($arregloCampos as $key => $value) {
        $plantilla->setVariable($key , $value);
      }
    }
  }

  //Función que coloca los datos que vienen de la BD
  function setDatos($arregloDatos,&$datos,&$plantilla) {
    foreach($datos as $key => $value) {
      $plantilla->setVariable($key, $value);
    }
  }
	
  function getLista($arregloDatos,$seleccion,&$plantilla) {
    $unaLista = new Inventario();
	
    $lista = $unaLista->lista_medida($arregloDatos['tabla'],$arregloDatos['condicion'],$arregloDatos['campoCondicion']);
    $lista = $unaLista->armSelect($lista,'[seleccione]',$seleccion);

    $plantilla->setVariable($arregloDatos['labelLista'], $lista);
  }
	
  function cargaPlantilla($arregloDatos) {
    $unAplicaciones = new Inventario();
    $formularioPlantilla = new HTML_Template_IT(PLANTILLAS);

		$formularioPlantilla->loadTemplateFile($arregloDatos['plantilla'],false,false);
    $formularioPlantilla->setVariable('comodin',' ');
		$this->mantenerDatos($arregloDatos,$formularioPlantilla);
    $metodo = $arregloDatos['thisFunction'];
    $this->$metodo($arregloDatos,$unAplicaciones,$formularioPlantilla);

		if($arregloDatos['mostrar']) {
      $formularioPlantilla->show();
    } else {
      return $formularioPlantilla->get();
    }
  }

  // Función para convertir arreglo en objeto stdClass
  function converArrObj($Arreglo) {      
      // Crea nuevo objeto stdClass
      $objeto = new stdClass();
       
      // Usa bucle para convertir arreglo en
      // objeto stdClass
      foreach($Arreglo as $clave => $valor) {
          if(is_array($valor)) {
            $valor = converArrObj($valor);
          }
          $objeto->$clave = $valor;
      }
      return $objeto;
  }

  //Arma cada Formulario o función en pantalla
  function setArribo($arregloDatos,$unDatos) {
    $unArribos = new Inventario();
    $unaPlantillas = new HTML_Template_IT(PLANTILLAS);
    $metodo = $arregloDatos['thisFunction'];

    $unArribos->$metodo($arregloDatos);

    $unaPlantillas->loadTemplateFile($arregloDatos['plantilla'],true,true);
    if(!empty($unDatos->mensaje)) {
      $unaPlantillas->setVariable('mensaje', $unArribos->mensaje);
      $unaPlantillas->setVariable('estilo', $unArribos->estilo);
    }

    if(!empty($arregloDatos['mensaje_aux'])) {
      $unaPlantillas->setVariable('mensaje', $arregloDatos['mensaje_aux']);
      $unaPlantillas->setVariable('estilo', $arregloDatos['mensaje_aux']);
    }

    $this->mantenerDatos($arregloDatos,$unaPlantillas);

    $arregloDatos['n'] = 0;
    $datosArribos = $unArribos->db->getArray();
    $rowsx = count($datosArribos);
    $unaPlantillas->setVariable('num_registros',$rowsx);
    foreach($datosArribos as $arribo) {
      $odd = $arregloDatos['n'] % 2 ? 'odd' : '';
      $arregloDatos['n'] = $arregloDatos['n'] + 1;
      $unaPlantillas->setCurrentBlock('ROW');
      // Convertimos el Arreglo a Objeto
      $objArribo = $this->converArrObj($arribo);
      $this->setDatos($arregloDatos,$objArribo,$unaPlantillas);
      $metodo = $arregloDatos['thisFunction'];
      $this->$metodo($arregloDatos,$objArribo,$unaPlantillas);
      $unaPlantillas->setVariable('n', $arregloDatos['n']);
      $unaPlantillas->setVariable('odd', $odd);
      $unaPlantillas->parseCurrentBlock();
    }
    if($rowsx == 0 and empty($unArribos->datos->mensaje_error)) {
      $unaPlantillas->setVariable('mensaje', 'No hay registros para listar');
      $unaPlantillas->setVariable('estilo', 'ui-state-error');
    }

    if($arregloDatos['mostrar']) {
      $unaPlantillas->show();
    } else {
      return $unaPlantillas->get();
    }
  }

  //Arma cada Formulario o función en pantalla
  function setFuncion($arregloDatos,$unDatos) {
    $unDatos = new Inventario();
    $unaPlantilla = new HTML_Template_IT(PLANTILLAS);
    $metodo = $arregloDatos['thisFunction'];

		$unDatos->$metodo($arregloDatos);

    $unaPlantilla->loadTemplateFile($arregloDatos['plantilla'],true,true);
		if(!empty($unDatos->mensaje)) {
      $unaPlantilla->setVariable('mensaje', $unDatos->mensaje);
      $unaPlantilla->setVariable('estilo', $unDatos->estilo);
    }

    if(!empty($arregloDatos['mensaje_aux'])) {
      $unaPlantilla->setVariable('mensaje', $arregloDatos['mensaje_aux']);
      $unaPlantilla->setVariable('estilo', $arregloDatos['mensaje_aux']);
    }

    $this->mantenerDatos($arregloDatos,$unaPlantilla);

    $arregloDatos['n'] = 0;
    $datos = $unDatos->db->getArray();
    $rows = count($datos);
    $unaPlantilla->setVariable('num_registros',$rows);
    foreach($datos as $mercancia) {
      $odd = $arregloDatos['n'] % 2 ? 'odd' : '';
      $arregloDatos['n'] = $arregloDatos['n'] + 1;
      $unaPlantilla->setCurrentBlock('ROW');
      // Convertimos el Arreglo a Objeto
      $objMercancia = $this->converArrObj($mercancia);
      $this->setDatos($arregloDatos,$objMercancia,$unaPlantilla);
      $metodo = $arregloDatos['thisFunction'];
      $this->$metodo($arregloDatos,$objMercancia,$unaPlantilla);
      $unaPlantilla->setVariable('n', $arregloDatos['n']);
      $unaPlantilla->setVariable('odd', $odd);
      $unaPlantilla->parseCurrentBlock();
    }
    if($rows == 0 and empty($unDatos->datos->mensaje_error)) {
      $unaPlantilla->setVariable('mensaje', 'No hay registros para listar');
      $unaPlantilla->setVariable('estilo', 'ui-state-error');
    }

    if($arregloDatos['mostrar']) {
      $unaPlantilla->show();
    } else {
      return $unaPlantilla->get();
    }
  }
	
  function maestro($arregloDatos) {
    $this->plantilla->loadTemplateFile(PLANTILLAS.'inventarioMaestro.html',true,true);

    $this->plantilla->setVariable('mensaje', $this->datos->mensaje);
		$this->plantilla->setVariable('estilo', $this->datos->estilo);
    $arregloDatos['tab_index'] = 2;
    $this->mantenerDatos($arregloDatos,$this->plantilla);
    $unDatos = new Inventario();

    $arregloDatos['id_tab'] = 2;
    $arregloDatos['mostar'] = "0";
    $arregloDatos['plantilla'] = 'inventarioToolbar.html';
    $arregloDatos['thisFunction'] = 'getToolbar';  
    $this->plantilla->setVariable('toolbarInventario', $this->cargaPlantilla($arregloDatos));
    $arregloDatos['mostrar'] = 0;
    $arregloDatos['plantilla'] = 'inventarioListado.html';
    $arregloDatos['thisFunction'] = 'listaInventario';  
    $htmlListado = $this->setFuncion($arregloDatos,$this->datos);
    $this->plantilla->setVariable('listaInventario', $htmlListado);
    $arregloDatos['mostrar'] = 0;
    $arregloDatos['plantilla'] = 'inventarioEncabezado.html';
    $arregloDatos['thisFunction'] = 'encabezadoInventario';  
    $htmlListado = $this->setFuncion($arregloDatos,$this->datos);
    $this->plantilla->setVariable('encabezadoInventario', $htmlListado);
    //var_dump($htmlListado);exit(0);
    $traeOrden = new OrdenLogica();
	
    $htmlDatosOrden	= $traeOrden->unaOrden($arregloDatos);	
    $this->plantilla->setVariable('datosOrden', $htmlDatosOrden);
    $traeOrden = new OrdenLogica();
	
    $arregloDatos['arribo'] = $arregloDatos['id_arribo'];
    $htmlDatosArribo = $traeOrden->unArribo($arregloDatos);	
    $this->plantilla->setVariable('datosArribo', $htmlDatosArribo);

    $this->plantilla->show();
  }
	
  function encabezadoInventario($arregloDatos,&$datos,&$plantilla) {
    //Aqui se Formatean las variables del Encabezado
    $plantilla->setVariable('p_inv_f', number_format($datos->p_inv,DECIMALES,",","."));
    $plantilla->setVariable('p_arribo_f', number_format($datos->p_arribo,DECIMALES,",","."));
    $plantilla->setVariable('dif_p_f', number_format($datos->dif_p,DECIMALES,",","."));
    $plantilla->setVariable('v_inv_f', number_format($datos->v_inv,DECIMALES,",","."));
    $plantilla->setVariable('valor_fob_f', number_format($datos->valor_fob,DECIMALES,",","."));
    $plantilla->setVariable('dif_f_f', number_format($datos->dif_f,DECIMALES,",","."));
  }
        
  function getInventario($arregloDatos,$datos,$plantilla) {
    //Se verifica si el item ya tiene retiros para bloquear la modificación
    $arregloDatos['arribo'] = $datos->arribo;
    $unMovimiento = new Levante();	
    $unMovimiento->findMovimientos($arregloDatos);

    $arregloDatos['tabla'] = 'embalajes';
    $arregloDatos['labelLista']	= 'listaEmbalajes';
    $this->getLista($arregloDatos,$datos->un_empaque,$plantilla); //Hasta aquí
    $unaLista = new Inventario();
	
    $lista = $unaLista->selectUbicacion($arregloDatos);
    $ubicaciones = implode(",", $lista);
    $lista = $unaLista->armaSelectSinTitulo($lista,'[seleccione]',NULL);
    $plantilla->setVariable('listaUbicacion', $lista);
    $plantilla->setVariable('ubicaciones', $ubicaciones);
    $plantilla->setVariable('unrango', '0');
    if($unaLista->rango == 'Si') {
      $plantilla->setVariable('checked', 'checked');
      $plantilla->setVariable('unrango', '1');
      $plantilla->setVariable('inicio', $unaLista->inicio);
      $plantilla->setVariable('fin', $unaLista->fin);
      $plantilla->setVariable('inicio_label', $unaLista->nombre);
      $plantilla->setVariable('fin_label', $unaLista->fin_label);
      $plantilla->setVariable('es_rango', 'Consecutivas');
      $plantilla->setVariable('desde_hasta', " Desde $unaLista->nombre hasta $unaLista->fin_label");
    } else {
      $plantilla->setVariable('es_rango', '');
    }
    $this->mantenerDatos($arregloDatos,$plantilla);
  }
  
  function listaInventario($arregloDatos,$datos,$plantilla) {
    //Aqui se decide que acordión se muestra abierto
    //Se verifica si el item ya tiene retiros para bloquear la modificación
    $unMovimiento = new Levante();
    $unMovimiento->findMovimientos($arregloDatos);

    if($arregloDatos['n'] == 1) {
      $arregloDatos['id_form'] = $arregloDatos['n'];
      $arregloDatos['plantilla'] = 'inventarioInfo.html';
      if($arregloDatos['metodo'] == 'addInventario') {
        $arregloDatos['plantilla'] = 'inventarioForma.html';
      }
      $arregloDatos['id_item'] = $datos->item; 
      $arregloDatos['mostrar'] = 0;      	
      $arregloDatos['thisFunction'] = 'getInventario';
      $arregloDatos['thisFunctionAux'] = NULL;

      //$htmlArribo =	$this->setFuncion($arregloDatos,$datos);
      $htmlArribo = $this->setArribo($arregloDatos,$datos);
      $plantilla->setVariable('htmlUnInventario', $htmlArribo);
    }
  }
  
  function getToolbar($arregloDatos,&$datos,&$plantilla) {
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function getUbicacion($arregloDatos,&$datos,&$plantilla) {
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function filtrox($arregloDatos,&$datos,&$plantilla) {
    $arregloDatos['tabla'] = 'do_bodegas';
    $arregloDatos['labelLista'] = 'listaBodegas';
    $this->getLista($arregloDatos,NULL,$plantilla);
	
    $arregloDatos['tabla'] = 'do_estados';
    $arregloDatos['labelLista'] = 'listaEstados';
    $this->getLista($arregloDatos,NULL,$plantilla);
  }
}
?>