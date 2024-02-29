<?php
require_once("InventarioDatos.php");
require_once("MovimientoDatos.php");
require_once("OrdenDatos.php");
require_once("InventarioPresentacion.php");
//require_once("ReporteExcel.php");

class InventarioLogica {
  var $datos;
  var $pantalla;

  function InventarioLogica() {
    $this->datos = new Inventario();
    $this->pantalla = new InventarioPresentacion($this->datos);
  } 

  function titulo($arregloDatos) {
    $unDato = new Inventario();
    $titulo = '';

    if(!empty($arregloDatos['por_cuenta_filtro'])) {
      $arregloDatos['por_cuenta'] = $arregloDatos['por_cuenta_filtro'];
      $unDato->existeCliente($arregloDatos);
      $unDato = $unDato->db->fetch();
      $titulo = $unDato->razon_social;
    }

    if(!empty($arregloDatos['ubicacion_filtro'])) {
      $titulo .= " ubicaci&oacute;n " . $unDato->dato('do_bodegas','codigo',$arregloDatos['ubicacion_filtro']);
    }

    return ucwords(strtolower($titulo));
  }

  function maestro($arregloDatos) {
    //Si no existe inventario se crea el primer registro por default
    $this->datos->listaInventario($arregloDatos);
    $this->datos->db->fetch();
    $rows = $this->datos->db->countRows();

    if($rows==0) {
      //Se traen todos los  datos del Arribo y se crea el primer Item del Inventario con los datos del Arribo
      $unArribo = new Orden();

      $arregloDatos['arribo'] = $arregloDatos['id_arribo'];
      $unArribo->getArribo($arregloDatos);
      $unArribo = $unArribo->db->fetch();
      
      $arregloDatos['peso_bruto'] = $unArribo->peso_bruto;
      $arregloDatos['cantidad'] = $unArribo->cantidad;
      $arregloDatos['valor_fob'] = $unArribo->valor_fob;
      $arregloDatos['ubicacion'] = $unArribo->ubicacion;
      
      $this->datos->addInventario($arregloDatos);
      $this->datos->getLastItem($arregloDatos);
      $row = $this->datos->db->fetch();

      $arregloDatos['id_item'] = $row->item;
      $arregloDatos['posicion'] = $arregloDatos['ubicacion'];
      $this->datos->addUbicacion($arregloDatos);	
    }

    $arregloDatos['mensaje'] = "x";
    $this->pantalla->maestro($arregloDatos);
  }

  function setNuevoPeso($arregloDatos) { // 07/09/2016 Funcion que actualiza un item de inventario con el peso retirado y devuelve el saldo que crea el nuevo item cuando se divide
    $this->datos->getPesoRetirado($arregloDatos);
    $row = $this->datos->db->fetch();
    $arregloDatos['peso']	=$row->datos->peso;
    $arregloDatos['cantidad']=$row->datos->cantidad;
    $arregloDatos['valor']=$row->datos->valor;
    if(empty($arregloDatos['peso'])) { $arregloDatos['peso']=0; }
    if(empty($arregloDatos['cantidad'])) { $arregloDatos['peso']=0; }
    if(empty($arregloDatos['valor'])) { $arregloDatos['valor']=0; }	
    $this->datos->setNuevoPeso($arregloDatos); // ajusta el ingreso
    $unSql= new Inventario();
    $unSql->setAjustaInventario($arregloDatos);// ajusta el inventario
    echo $arregloDatos['saldo']=$arregloDatos['peso_total']-$arregloDatos['peso'];
  }

  function addInventario($arregloDatos) {
    //Cálculo de valores para que el item se cree con Saldos
    $arregloDatos['mensaje'] = "x";
    $this->datos->addInventario($arregloDatos);
    $this->pantalla->maestro($arregloDatos);
  }
  
  function findInventario($arregloDatos) {
    $arregloDatos['mensaje'] = "x";
    $this->pantalla->maestro($arregloDatos);
  }

  function findReferencia($arregloDatos) {
    $unaConsulta = new Inventario();

    $arregloDatos['q'] = strtolower($_GET["q"]);
    $unaConsulta->findReferencia($arregloDatos);

    $rows = 0;
    while($obj=$unaConsulta->db->fetch()) {
      $codref = trim($obj->codigo_ref);
      $nombre ='['.trim($obj->codigo_ref).'] ' .trim($obj->nombre);
      echo "$nombre|$obj->codigo|$obj->fecha_expira|$obj->serial|$codref|$obj->parte_numero|$obj->vigencia|$obj->lote_cosecha\n";
      $rows++;
    }

    if($rows == 0) { echo "No hay Resultados|0\n"; }
  }

  function findPosicion($arregloDatos) {
    $unaConsulta = new Inventario();

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

  function findPosicionPistola($arregloDatos) {
    $unaConsulta = new Inventario();

    $arregloDatos['q'] = strtolower($_GET["q"]);
    $unaConsulta->findPosicionPistola($arregloDatos);
    $rows = 0; 
    while($obj=$unaConsulta->db->fetch()) {
      $nombre = trim($obj->nombre);
      echo "$nombre|$obj->codigo\n";
      $rows++;
    }

    if($rows == 0) { echo "No hay Resultados|0\n"; }
  }

  function getEncabezado($arregloDatos) {
    $arregloDatos['mensaje'] = "x";
    $arregloDatos['mostrar'] = 1;
    $arregloDatos['plantilla'] = 'inventarioEncabezado.html';
    $arregloDatos['thisFunction'] = 'encabezadoInventario';  

    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  function getToolbar($arregloDatos) {
    $arregloDatos['plantilla'] = 'ordenArriboToolbar.html';
    $arregloDatos['thisFunction'] = 'getToolbar';  

    $this->pantalla->cargaPlantilla($arregloDatos);
  }

  function getOrdenToolbar($arregloDatos) {
    $arregloDatos['mostrar'] = 0;
    $arregloDatos['mensaje'] = "x";
    $arregloDatos['plantilla'] = 'ordenToolbar.html';
    $arregloDatos['thisFunction'] = 'getToolbar';  

		echo $this->pantalla->cargaPlantilla($arregloDatos);
  }	

  function editarInventario($arregloDatos) {
    $arregloDatos['mensaje'] = "x";

    if(empty($arregloDatos['mostrar'])) $arregloDatos['mostrar'] = 1;
    $arregloDatos['metodo'] = 'consulta';
    $arregloDatos['plantilla'] = 'inventarioForma.html';
    $arregloDatos['thisFunction'] = 'getInventario'; 

		$this->pantalla->setFuncion($arregloDatos,$this->datos);	
  }

  //Guarda el formulario del Inventario
  function saveItem($arregloDatos) {
    $this->datos->delUbicacion($arregloDatos);

    if(is_array($arregloDatos['posxy']) && empty($arregloDatos['rango'])) {
	
      foreach($arregloDatos['posxy'] as $key=>$value) {
        $arregloDatos['posicion'] = $value;
		
        $this->datos->addUbicacion($arregloDatos);
      }
    } else {
      $this->datos->addUbicacion($arregloDatos);
    }	
	
    if(!empty($arregloDatos['rango']) ) {  // si el usuario especifica  rango

    	if($arregloDatos['inicio'] < $arregloDatos['fin']) {
    		$inicio = $arregloDatos['inicio'];
    		$fin = $arregloDatos['fin'];
    	} else {
    		$inicio = $arregloDatos['fin'];
    		$fin = $arregloDatos['inicio'];
    	}
    	
    	$this->datos->delUbicacion($arregloDatos);
			
  		for($i=$inicio;$i<=$fin;$i++) {
        // se inserta en la tabla de ubicaciones			
        $arregloDatos['posicion']=$i;
  			$this->datos->addUbicacion($arregloDatos);
  		}	
    }
    $arregloDatos['id_form'] = intval($arregloDatos['id_form']);
    $this->datos->saveItem($arregloDatos);
    $this->getItem($arregloDatos);
  }

  //Trae la información SI editable de un arribo
  function getItem($arregloDatos) {
    $arregloDatos['mensaje'] = "x";

    if(empty($arregloDatos['mostrar'])) $arregloDatos[mostrar] = 1;
    $arregloDatos['metodo'] = 'consulta';
    $arregloDatos['plantilla'] = 'inventarioInfo.html';
    $arregloDatos['thisFunction'] = 'getInventario'; 

    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  //Trae las Ubicaciones
  function getUbicaciones($arregloDatos) {
    $arregloDatos['mensaje'] = "x";

    if(empty($arregloDatos['mostrar'])) $arregloDatos[mostrar] = 1;
    $arregloDatos['metodo'] = 'consulta';
    $arregloDatos['plantilla'] = 'inventarioUbicacion.html';
    $arregloDatos['thisFunction'] = 'getInventario'; 

    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  function delInventario($arregloDatos) {
    $arregloDatos['mensaje'] = "x";
    $arregloDatos['tab_index'] = 2;

    $this->datos->delInventario($arregloDatos);
    $this->pantalla->maestro($arregloDatos);
  }	
}		
?>