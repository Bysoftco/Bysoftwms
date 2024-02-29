<?php
require_once("ReempaqueDatos.php");
require_once("ReempaquePresentacion.php");
//require_once("ReporteExcel.php");

class ReempaqueLogica {
  var $datos;
  var $pantalla;

  function ReempaqueLogica() {
    $this->datos = new Reempaque();
    $this->pantalla = new ReempaquePresentacion($this->datos);
  }

  function reempacar($arregloDatos) {
    $arregloDatos['tab_seleccionado'] = 0;
    $arregloDatos['tipo_movimiento'] = 1;
    //Asigna Plantilla Filtro de Entrada
    $arregloDatos['plantillaFiltro'] = "reempaqueFiltro.html";
    $this->pantalla->maestro($arregloDatos); 
  }

  function controlarTransaccion(&$arregloDatos) {
    //Aquí se decide el tipo de Formulario Para el movimiento Seleccionado
    //El método y plantilla se condicionan por el formulario reempaqueFiltro según selección del combo Tipo de Procedimiento
    switch($arregloDatos['tipo_movimiento']) {
      case 3: //Retiro
        $arregloDatos['plantillaMercanciaCuerpo'] = "levanteListadoMercanciaRetiro.html";
        $arregloDatos['metodoMercanciaCuerpo'] = "getInvParaRetiro";
        $arregloDatos['plantillaCabeza'] = "reempaqueCabezaRetiro.html";
        $arregloDatos['metodoCabeza'] = "getCabezaLevante";
        $arregloDatos['metodoCabezaEnvia'] = "updateRetiroCabeza";
        $arregloDatos['plantillaCuerpo'] = "reempaqueCuerpoRetiro.html";
        $arregloDatos['metodoCuerpo'] = "getCuerpoRetiro";
        //Métodos para enviar formulario después
        $arregloDatos['setMetodo'] = "addItemRetiro";
        $arregloDatos['type_nonac'] = "hidden"; //No deja seleccionar piezas nacionales
        $arregloDatos['v_aux_nonac'] = "0";
        break;
      case 4: // Reempaque 4=Unificar
        $arregloDatos['movimiento'] = "4";
        $arregloDatos['plantillaMercanciaCuerpo'] = "reempaqueListadoUnificar.html";
        $arregloDatos['metodoMercanciaCuerpo'] = "getInvParaReempacar";
        $arregloDatos['plantillaCabeza'] = "reempaqueCabeza.html";
        $arregloDatos['metodoCabeza'] = "getCabezaReempaque";
        $arregloDatos['metodoCabezaEnvia'] = "updateReempacarCabeza";
        $arregloDatos['plantillaCuerpo'] = "reempaqueCuerpoUnificar.html";
        $arregloDatos['metodoCuerpo'] = "getCuerpoReempacar";
        //Métodos para enviar formulario después
        $arregloDatos['setMetodo'] = "addItemReempacar";
        $arregloDatos['reempaque'] = "Unificado";
        break;
      case 5: //Reempaque 5=Integrar
        $arregloDatos['movimiento'] = "5";
        $arregloDatos['plantillaMercanciaCuerpo'] = "reempaqueListadoConsolidar.html";      
        $arregloDatos['metodoMercanciaCuerpo'] = "getInvParaReempacar";
        $arregloDatos['plantillaCabeza'] = "reempaqueCabeza.html";
        $arregloDatos['metodoCabeza'] = "getCabezaReempaque";
        $arregloDatos['metodoCabezaEnvia'] = "updateReempacarCabeza";
        $arregloDatos['plantillaCuerpo'] = "reempaqueCuerpoConsolidar.html";
        $arregloDatos['metodoCuerpo'] = "getCuerpoReempacar";
        //Métodos para enviar formulario después Cuerpo
        $arregloDatos['setMetodo'] = "addItemReempacar";
        $arregloDatos['reempaque'] = "Integrado";
        break;
    }
  }

  function consultaLevante($arregloDatos) {
    $this->controlarTransaccion($arregloDatos);
    $this->pantalla->maestro($arregloDatos);
  }

  /*function newLevante($arregloDatos) {
    $arregloDatos['tipo_movimiento'] = 2;
    $this->datos->getLevante($arregloDatos);
    
    if($this->datos->N > 0) { // Si ya existe el levante
      $this->datos->fetch();
      $arregloDatos[id_levante] = $this->datos->codigo;
      $arregloDatos[tab_seleccionado] = 0; 
    } else {
      $arregloDatos[id_levante] = $this->datos->newLevante($arregloDatos);
      $arregloDatos[tab_seleccionado] = 1; 
    }
    $this->controlarTransaccion($arregloDatos);
    $this->pantalla->maestro($arregloDatos);
  }*/

  function getListaReempacar($arregloDatos) {
    $arregloDatos['mostrar'] = 1;
    $arregloDatos['plantilla'] = ($arregloDatos['tipo_movimiento'] == 5 ? 'reempaqueListadoConsolidar.html':'reempaqueListadoUnificar.html');
    $arregloDatos['thisFunction'] = 'getInvParaReempacar'; 
    $this->pantalla->setFuncion($arregloDatos,$this->datos);   
  }

  function listadoReempacados($arregloDatos) {
    $arregloDatos['mostrar'] = 1;
    $arregloDatos['plantilla'] = 'reempaqueReempacados.html'; //Filtro Listado Reempacados
    $arregloDatos['thisFunction'] = 'listaReempacados'; 
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }    

  function impresion($arregloDatos) {
    $arregloDatos['mostrar'] = 1;
    $arregloDatos['plantilla'] = 'levanteRemesaRetiro.html';
    $arregloDatos['thisFunction']	= 'getRetiro'; 
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function newReempaque($arregloDatos) {
    //Valida el tipo de procedimiento
    $arregloDatos['tipo_movimiento'] = ($arregloDatos['tipo_reempaque_label'] == "INTEGRAR" ? 5 : 4);
    $arregloDatos['id_reempaque'] = $this->datos->newReempaque($arregloDatos); //Crea un nuevo reempaque (inventario_maestro_movimientos)
    $arregloDatos['tab_seleccionado'] = 1; 

    $this->controlarTransaccion($arregloDatos);
    $this->pantalla->maestro($arregloDatos);
  }

  function verReempaque($arregloDatos) {
    $arregloDatos['tab_seleccionado'] = 0; 
    $arregloDatos['tipo_reempaque_label'] = ($arregloDatos['tipo_movimiento'] == 5 ? 'INTEGRAR' : 'UNIFICAR');
    $arregloDatos['id_reempaque'] = $arregloDatos[id_levante];
    
    $this->controlarTransaccion($arregloDatos);
    $this->pantalla->maestro($arregloDatos);
  }

  function addItemLevante($arregloDatos) {
    $this->datos->newDeclaracion($arregloDatos);
    $this->datos->addItemLevante($arregloDatos);
    $this->datos->getAcomulaCif($arregloDatos);
    $this->getMercancia($arregloDatos); 
  }

  function addItemReempacar($arregloDatos) {
    //Inicializa información seleccionada para ajuste al Inventario
    $id_item = $arregloDatos['item']; $peso = $arregloDatos['peso'];
    $cantidad = $arregloDatos['cant']; $fob = $arregloDatos['fob'];
    $cantinicial = $arregloDatos['cant_inicial'];
    //Valida Tipo de Movimiento = 4    
    if($arregloDatos['tipo_movimiento'] == 4) {
      $indice = $arregloDatos['ns']; //Orden seleccionada
      $arregloDatos['tot_cant_nonac'] = $cantidad[$indice-1];
      $arregloDatos['tot_peso_nonac2'] = $peso[$indice-1];
      $arregloDatos['total_fob2'] = $fob[$indice-1];
    }
    //Configura el flg_control a 0 para tipo_movimiento = 1 - Cuadrar variables iniciales de reporte de inventario
    $this->datos->setControla($arregloDatos['arribo'],1);
    //Inserta Mercancia Consolidada y/o Unificada al Inventario de Entrada
    $arregloDatos['id_itemx'] = $this->datos->newEntradaInventario($arregloDatos); //Crea una nueva entrada en inventario
    //Inserta Mercancia Consolidada al Inventario Movimientos
    $this->datos->addItemReempacar($arregloDatos);
    $id_reempaque = $arregloDatos['id_reempaque'];
    //Verifica Tipo de Movimiento para Ajuste y Re-Ajuste de inventario
    if($arregloDatos['tipo_movimiento'] == 5) {
      //Recorre los marcados y se insertan con tipo_movimiento = 30
      foreach($arregloDatos['n'] as $key => $value) {
        //Ajuste al inventario con movimientos tipo = 5
        $this->datos->ajusteItemReempacar($id_item[$value-1],$peso[$value-1],$cantidad[$value-1],
        $fob[$value-1],$id_reempaque,$arregloDatos['tipo_movimiento']);
        //Re-Ajuste al inventario con movimientos tipo = 30
        $this->datos->ajusteItemReempacar($id_item[$value-1],-$peso[$value-1],-$cantidad[$value-1],
        -$fob[$value-1],$id_reempaque,30);
      }
    } else {
      //Configura el Flag de Control a 0 - Ultimo Reempaque
      $this->datos->setControl($id_item[$indice-1],4);     
      //Ajuste al inventario con movimientos tipo = 4
      $this->datos->ajusteItemReempacar($id_item[$indice-1],$peso[$indice-1],$cantidad[$indice-1],
      $fob[$indice-1],$id_reempaque,$arregloDatos['tipo_movimiento']);
      //Re-Ajuste al inventario con movimientos tipo = 30
      $this->datos->ajusteItemReempacar($id_item[$indice-1],-$peso[$indice-1],-$cantinicial[$indice-1],
      -$fob[$indice-1],$id_reempaque,30);
    }
    $arregloDatos['mostrar'] = 1;
    $arregloDatos['id_item'] = NULL;
    $arregloDatos['plantilla'] = ($arregloDatos['tipo_movimiento'] == "5" ? 'reempaqueListadoConsolidar.html':'reempaqueListadoUnificar.html');
    $arregloDatos['thisFunction'] = 'getInvParaReempacar';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
 
  function getCuerpoReempaque($arregloDatos) {
    $this->controlarTransaccion($arregloDatos);
    $arregloDatos['plantilla'] = $arregloDatos['plantillaCuerpo'];
    $arregloDatos['thisFunction'] = $arregloDatos['metodoCuerpo'];
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  //Función Encargada de Pintar el Cuerpo de Inventario Seleccionado para Consolidar y/o Unificar
  function getCuerpoReempacar($arregloDatos) {
    $arregloDatos['mostrar'] = 1;
    $arregloDatos['plantilla'] = ($arregloDatos['tipo_movimiento'] == 5 ? 'reempaqueListadoConsolidar.html':'reempaqueListadoUnificar.html');
    $arregloDatos['thisFunction'] = 'getInvParaReempacar';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  function getCabezaReempaque($arregloDatos) {
    $this->controlarTransaccion($arregloDatos);
    $arregloDatos['plantilla'] = $arregloDatos['plantillaCabeza'];
    $arregloDatos['thisFunction'] = $arregloDatos['metodoCabeza'];
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  function remesaRetiro($arregloDatos) {
    $arregloDatos['plantilla'] = 'levanteRemesaRetiro.html';
    $arregloDatos['thisFunction'] = 'getCuerpoReempaque';
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  function getMercancia($arregloDatos) {
    $arregloDatos['mostrar'] = 1;
    $arregloDatos['plantilla'] = 'levanteListadoMercancia.html';
    $arregloDatos['thisFunction'] = 'listaInventario';  
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  function updateLevanteCabeza($arregloDatos) {
    //Confirma si es parcial para aumentar el grupo
    if($arregloDatos['parcial']) {
      $arregloDatos['num_grupo'] = $this->datos->ultimoGrupoCreado($arregloDatos) + 1;
      $this->datos->updateConteoParciales($arregloDatos);
    } else { //Si no marco como parcial se hace de nuevo el conteo y se actualiza
      $gruposcreados = $this->datos->ultimoGrupoCreado($arregloDatos) ;
      $grupossolicitados = $this->datos->ultimoGrupo($arregloDatos) ;
      if($grupossolicitados > $gruposcreados) { //Se decidió no crear el parcial
        $arregloDatos['num_grupo'] = $gruposcreados; //Se actualiza el número real de grupos
        $this->datos->updateConteoParciales($arregloDatos); 
      }
    }
    $arregloDatos['mostrar'] = 1;
    $this->datos->setCabezaLevante($arregloDatos);
    $arregloDatos['plantilla'] = 'levanteCabeza.html';
    $arregloDatos['thisFunction'] = 'getCabezaLevante';  
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  function updateReempacarCabeza($arregloDatos) {
    $this->controlarTransaccion($arregloDatos); 
    $arregloDatos['mostrar'] = 1;
    $this->datos->setCabezaReempaque($arregloDatos);
    $arregloDatos['plantilla'] = 'reempaqueCabeza.html'; //Ejecuta Reempaque Cabeza para Tipo de Movimiento 4 o 5.
    $arregloDatos['thisFunction'] = 'getCabezaReempaque';  
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  function delReempaque($arregloDatos) {
    $this->controlarTransaccion($arregloDatos); 
    $this->datos->delReempaque($arregloDatos);
    $arregloDatos['id_item'] = $this->datos->getIDReferencia($arregloDatos); //Obtiene el código item del inventario_entradas
    $this->datos->borrarReferencia($arregloDatos); //Elimina referencia tipo 2 en inventario_entradas 
    $arregloDatos['plantilla'] = ($arregloDatos['movimiento'] == "5" ? 'reempaqueCuerpoConsolidar.html':'reempaqueCuerpoUnificar.html');
    $arregloDatos['thisFunction'] = 'getCuerpoReempacar';  
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  function existeCliente($arregloDatos) {
    $unaConsulta = new Reempaque();
    $unaConsulta->existeCliente($arregloDatos);
    $unaConsulta->db->fetch();
    $rows = $unaConsulta->db->countRows();

    if($rows == 0) {
      echo $rows;
      die();
    }
    /*$unaConsulta->existeReempaque($arregloDatos);

    if($unaConsulta->N > 0) {
      echo 10;
      die();
    }*/
    echo 1;
  }

  /*function existeReempaque($arregloDatos) {
    $unaConsulta = new Reempaque();

    $unaConsulta->existeReempaque($arregloDatos);
    echo $unaConsulta->N;
  }*/

  function titulo($arregloDatos) {
    $unDato = new Reempaque();
    $titulo = '';

    if(!empty($arregloDatos['por_cuenta_filtro'])) {
      $arregloDatos['por_cuenta'] = $arregloDatos['por_cuenta_filtro'];
      $unDato->existeCliente($arregloDatos);
      $unDato = $unDato->db->fetch();
      $titulo = $unDato->razon_social;
    }
    if(!empty($arregloDatos['ubicacion_filtro'])) {
      $titulo .= " ubicación ".$arregloDatos['ubicacion_filtro'];
    }

    if(!empty($arregloDatos['estado_filtro'])) {
      $titulo .= " estado ".$arregloDatos['estado_filtro'];
    }
    if(!empty($arregloDatos['fecha_inicio']) and !empty($arregloDatos['fecha_fin'])) {
      $titulo .= " desde ".$arregloDatos['fecha_inicio']." hasta ".$arregloDatos['fecha_fin'];
    }
    if(!empty($arregloDatos['doc_filtro'])) {
      $titulo .= " documento ".$arregloDatos['doc_filtro'];
    }
    if(!empty($arregloDatos['do_filtro'])) {
      $titulo .= " Do ".$arregloDatos[do_filtro];
    }
    return ucwords(strtolower($titulo));
  }

  function imprimeLevante($arregloDatos) {
    $arregloDatos['mostrar'] = 1;
    $arregloDatos['plantilla'] = 'levanteRemesaRetiro.html';
    $arregloDatos['thisFunction'] = 'listaInventario';  
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }
  
  function findDocumento($arregloDatos) {
    $unaConsulta = new Reempaque();
    $arregloDatos['q'] = strtolower($_GET["q"]);
    $unaConsulta->findDocumento($arregloDatos);
    $regs = 0;

    while($obj=$unaConsulta->db->fetch()) {
      $nombre = trim($obj->doc_tte)."-".trim($obj->do_asignado);
      echo "$nombre|$obj->doc_tte\n";
      $regs++;
    }
    if($regs == 0) {
      echo "No hay Resultados|0\n";
    }
  }
}		
?>