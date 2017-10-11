<?php
require_once ("HTML/Template/IT.php");
require_once ("ReempaqueDatos.php");
require_once ("ReempaqueLogica.php");

class ReempaquePresentacion {
  var $datos;
  var $plantilla;
  var $tot_fob_nac = 0;
  var $tot_peso_nac = 0;
  var $tot_cant_nac = 0;
  var $cuenta = 0;

  function ReempaquePresentacion(&$datos) {
    $this->datos = &$datos;
    $this->plantilla = new HTML_Template_IT();
  } 

  function mantenerDatos($arregloCampos,&$plantilla) {
    $plantilla = &$plantilla;
    
    if(is_array($arregloCampos)) {
      foreach($arregloCampos as $key => $value) {
        $plantilla->setVariable($key , $value);
      }
    }
  }

  //Función que coloca los datos que vienen de la BD
  function setDatos($arregloDatos,&$datos,&$plantilla) {
    foreach($datos as $key => $value) {
      $plantilla->setVariable($key , $value);
    }
  }

  function cargaPlantilla($arregloDatos) {
    $unAplicaciones = new Reempaque();
    $formularioPlantilla = new HTML_Template_IT();

    $formularioPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla],false,false);
    $formularioPlantilla->setVariable('comodin',' ');
    $this->mantenerDatos($arregloDatos,&$formularioPlantilla);
    $this->$arregloDatos[thisFunction]($arregloDatos,$this->datos,$formularioPlantilla);
    if($arregloDatos[mostrar]) {
      $formularioPlantilla->show();
    } else {
      return $formularioPlantilla->get();
    }
  }

  //Arma cada Formulario o función en pantalla
  function setFuncion($arregloDatos,$unDatos) {
    $unDatos = new Reempaque();

    if(!empty($arregloDatos[setCharset])) {
      header('Content-type: text/html; charset=iso-8859-1');
    }	
    
    $r = $unDatos->$arregloDatos[thisFunction](&$arregloDatos);
    $unaPlantilla = new HTML_Template_IT();

    $unaPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla],true,true);
    $unaPlantilla->setVariable('comodin', ' ');
    if(!empty($arregloDatos[mensaje])) {
      $unaPlantilla->setVariable('mensaje', $arregloDatos[mensaje]);
      $unaPlantilla->setVariable('estilo', $arregloDatos[estilo]);
    }

    $this->mantenerDatos($arregloDatos, $unaPlantilla);
    $arregloDatos[n] = 0;
    while($unDatos->fetch()) {
      $odd = ($arregloDatos[n] % 2) ? 'odd' : '';
      $arregloDatos[n] = $arregloDatos[n] + 1;
      $unaPlantilla->setCurrentBlock('ROW');
      $this->setDatos($arregloDatos,$unDatos,$unaPlantilla);
      $this->$arregloDatos[thisFunction]($arregloDatos,$unDatos,$unaPlantilla);
      $unaPlantilla->setVariable('n', $arregloDatos[n]);
      $unaPlantilla->setVariable('odd', $odd);
      $unaPlantilla->parseCurrentBlock();
    }
    if($unDatos->N == 0 and empty($unDatos->mensaje)) {
      $unaPlantilla->setVariable('mensaje', '&nbsp;No hay registros para listar'.$arregloDatos[mensaje]);
      $unaPlantilla->setVariable('estilo', 'ui-state-error');
      $unaPlantilla->setVariable('mostrarCuerpo', 'none');
    }
    $unaPlantilla->setVariable('num_registros', $unDatos->N);
    if($arregloDatos[mostrar]) {
      $unaPlantilla->show();
    } else {
      $unaPlantilla->setVariable('cuenta', $this->cuenta);
      return $unaPlantilla->get();
    }
  }

  function maestro($arregloDatos) {
		$this->plantilla->loadTemplateFile(PLANTILLAS .'reempaqueMaestro.html',true,true);
    $this->plantilla->setVariable('comodin'	,' ');

    //Captura y Coloca el Título
    $this->getTitulo(&$arregloDatos);    
		$this->mantenerDatos($arregloDatos,&$this->plantilla);
    $arregloDatos[mostrar] = 0;
    $arregloDatos[plantilla] = 'reempaqueToolbar.html';
    $arregloDatos[thisFunction] = 'getToolbar';  
    $this->plantilla->setVariable('toolbarReempaque',$this->setFuncion($arregloDatos,&$this->datos));
    if(empty($arregloDatos[por_cuenta_filtro])) {
      //Indica que debe abrir el cuadro de Diálogo Movimientos
      $this->plantilla->setVariable('abre_ventana', 1);
    } else {
      //Indica que no debe abrir el cuadro de Diálogo Movimientos 
      $this->plantilla->setVariable('abre_ventana', 0);
      //El método controlarTransaccion de la Lógica envía la plantilla y el método para pintar el TAB de Mercancía
      $arregloDatos[mostrar] = 0;
      $arregloDatos[plantilla] = $arregloDatos[plantillaMercanciaCuerpo];
      $arregloDatos[thisFunction] = $arregloDatos[metodoMercanciaCuerpo];  
      $htmlMercancia = $this->setFuncion($arregloDatos,&$this->datos);
      $this->plantilla->setVariable('htmlMercancia', $htmlMercancia);
      //Pinta el formulario en el TAB Movimiento Reempaque
      $arregloDatos[mostrar] = 0;
      $arregloDatos[plantilla] = $arregloDatos[plantillaCabeza];
      $arregloDatos[thisFunction] = $arregloDatos[metodoCabeza]; 
      $htmlReempaque = $this->setFuncion($arregloDatos,&$this->datos);
      $this->plantilla->setVariable('htmlReempaque', $htmlReempaque);
      //Cuerpo del movimiento
      $arregloDatos[mostrar] = 0;
      $arregloDatos[plantilla] = $arregloDatos[plantillaCuerpo];
      $arregloDatos[thisFunction] = $arregloDatos[metodoCuerpo];
      $htmlReempaque = $this->setFuncion($arregloDatos,&$this->datos);
      $this->plantilla->setVariable('htmlCuerpo', $htmlReempaque);
    }
    $unDatos = new Reempaque();
    $arregloDatos[mostrar] = 0;
    $arregloDatos[plantilla] = $arregloDatos[plantillaFiltro];
    $arregloDatos[thisFunction] = 'filtro';
    $htmlFiltro = $this->cargaPlantilla($arregloDatos);  
    $this->plantilla->setVariable('filtro', $htmlFiltro);
    $this->plantilla->show();
  }

  function filtro($arregloDatos,$unDatos,$plantilla) {
    $unaLista = new Reempaque();
    
    //Carga Cuadro Combinado para el Tipo de Procedimiento
    $lista = $unaLista->lista("tipos_reempaque");
    $lista = armaSelect($lista,'[Seleccionar]',NULL);
    $plantilla->setVariable("listaTiposReempaque", $lista);
  }

  function getCabezaReempaque($arregloDatos,$unDatos,$unaPlantilla) {
  }

  function getCuerpoReempacar($arregloDatos,$unDatos,$unaPlantilla) {
    $this->getCuerpoReempaque($arregloDatos,$unDatos,$unaPlantilla);
  }

  function hayReempaques($arregloDatos,$unDatos,$unaPlantilla) {
    $this->mantenerDatos($arregloDatos,$unaPlantilla);
  }
  
  function getCuerpoReempaque($arregloDatos,$unDatos,$unaPlantilla) {
    //Si hay reempaques no se permite borrar el registro
    $unaConsulta = new Reempaque();
    //$arregloDatos[borrar] cambia x $arregloDatos[reempacar]
    $arregloDatos[reempacar] = 0;
    //$arregloDatos[cod_item] = $unDatos->cod_item;
    $arregloDatos[cod_item] = $unDatos->cod_item;
    $arregloDatos[reempacar] = $unaConsulta->hayReempaques($arregloDatos);
    if($arregloDatos[reempacar] == 0) {
      $arregloDatos[label] ="<a href='#' class='signup borrar_id' title='Reempacar Levante {n}' id='$arregloDatos[n]' cursor><img src='integrado/imagenes/borrar.gif' width='15' height='15' border='1'  ></a>";  
    } else {
      $arregloDatos[label] = "R";
      $arregloDatos[estilo] = "ui-state-highlight";
      $arregloDatos[mensaje] = "&nbsp;Lista de registro(s) a reempacar, presionar (<font color='#FF0000'><strong>X</strong></font>) para deshacer la operaci&oacute;n";
    }
    $this->setValores(&$arregloDatos,$unDatos,$unaPlantilla);
    $this->mantenerDatos($arregloDatos,$unaPlantilla);
  }

  function setValores($arregloDatos,$datos,$plantilla) {
    //Valores absolutos de variables sin formato
    $arregloDatos[cantidad_nonac] = number_format(abs($datos->cantidad_nonac),DECIMALES,".",""); //Se formatea para evitar error de validación Javascript
    $arregloDatos[peso_nonac] = number_format(abs($datos->peso_nonac),DECIMALES,".","");
    $arregloDatos[fob_nonac] = number_format(abs($datos->fob_nonac),DECIMALES,".",""); //Verificar fob_nonac_r

    //Variables de pesos cantidad y fob formateadas
    $arregloDatos[cant_nonac_f] = number_format(abs($datos->cantidad_nonac),DECIMALES,".",",");
    $arregloDatos[peso_nonac_f] = number_format(abs($datos->peso_nonac),DECIMALES,".",",");
    $arregloDatos[fob_nonac_f] = number_format(abs($datos->fob_nonac),DECIMALES,".",","); //Verificar fob_nonac_r
    
    //Cálculo de totales y formatos
    $this->tot_cant_nonac = $this->tot_cant_nonac + abs($datos->cantidad_nonac);
    $this->tot_peso_nonac = $this->tot_peso_nonac + abs($datos->peso_nonac);
    $this->tot_fob_nonac = $this->tot_fob_nonac + abs($datos->fob_nonac);
    
    //Asignación de formatos
    $arregloDatos[tot_cant_nonac_f] = number_format(abs($this->tot_cant_nonac),DECIMALES,".",",");
    $arregloDatos[tot_peso_nonac_f] = number_format(abs($this->tot_peso_nonac),DECIMALES,".",",");
    $arregloDatos[tot_fob_nonac_f] = number_format(abs($this->tot_fob_nonac),DECIMALES,".",",");
  }

  function listaInventario($arregloDatos,$datos,$plantilla) {  
    $this->setValores(&$arregloDatos,$datos,$plantilla);
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function getInvParaReempacar($arregloDatos,$datos,$plantilla) {
    $this->setValores(&$arregloDatos,$datos,$plantilla);
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function getInventario($arregloDatos,$datos,$plantilla) { 
    $this->setValores(&$arregloDatos,$datos,$plantilla);
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function getToolbar($arregloDatos,&$datos,&$plantilla) {
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function traeLevante($arregloDatos,&$datos,&$plantilla) {
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  //Pinta el cuerpo del retiro
  function getRetiro($arregloDatos,&$datos,&$plantilla) {
    $arregloDatos[mostrar] = 0;
    $arregloDatos[plantilla] = 'levanteRemesaCuerpo.html';
    $arregloDatos[thisFunction] = 'getCuerpoReempacar';
    $arregloDatos[cuerpoRetiro] = $this->setFuncion($arregloDatos,&$unDatos); 
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function getTitulo($arregloDatos) {
    switch($arregloDatos[tipo_movimiento]) {
      case 1:
        $arregloDatos[titulo] = "REEMPAQUE";
        break;
      case 4:
      case 5:
        $arregloDatos[titulo] = "NUEVO REEMPAQUE: $arregloDatos[tipo_reempaque_label] ";
        break;
    }
    if(!empty($arregloDatos[por_cuenta_filtro])) {
      $unReempaque = new Reempaque();
      $unReempaque->getCliente($arregloDatos);
      $unReempaque->fetch();
      $arregloDatos[titulo] .= "- [".$unReempaque->numero_documento."] <strong>" . $unReempaque->razon_social . "</strong>";
    }
  } 
}
?>