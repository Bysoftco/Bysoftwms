<?php
/*
  Versión 1.0
  Autor Fredy Arevalo
  Fecha September  11 de 2007
  Descripción:
    Clase encargada de Construir la Interfaz Grafica Para el Modulo de Indicadores
*/

require_once("HTML/Template/IT.php");
require_once("Funciones.php");




class IndicadoresPresentacion {
  var $datos;
  var $plantilla;
  

  function IndicadoresPresentacion(&$datos) {
    $this->datos = $datos;
    $this->plantilla = new HTML_Template_IT();
	$this->colores = array("#9966FF", "#66CCFF","#ffff88", "#FF3300", "#FFCC33","#92A9D3","#FF99FF","#333333","#CCCC66","#FFFF66","#006600","#00FF00","#FFFF33","#7400E8","#99FFFF","#FF3366","#663366"."#FF00FF","#99FF00","#CCFFCC","#CCFF00","#6600CC","#6677CC","#0077CC","#807FCC","#807F00","#8FFFCC","#811FCC","#811F93","#441F93","#441F55");
  } 

  function mantenerDatos($arregloCampos,&$plantilla) {
    $plantilla = $plantilla;
    if(is_array($arregloCampos)) {
      foreach($arregloCampos as $key => $value) {
        $plantilla->setVariable($key,$value);
      }
    }
  }

  //Función que coloca los datos que vienen de la BD
  function setDatos($arregloDatos,&$datos,&$plantilla){
    foreach($datos as $key => $value) {
      $plantilla->setVariable($key,$value);
    }
  }

  function getLista($arregloDatos,$seleccion,&$plantilla) {
    $unaLista = new Indicadores();
    $lista		= $unaLista->lista($arregloDatos[tabla],$arregloDatos[condicion],$arregloDatos[campoCondicion]);
    $lista		= armaSelect($lista,'[seleccione]',$seleccion);
    $plantilla->setVariable($arregloDatos[labelLista], $lista);
  }

  function cargaPlantilla($arregloDatos) {
    $unAplicaciones = new Indicadores();
    $formularioPlantilla = new HTML_Template_IT();
    
    $formularioPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla],false,false);
    $formularioPlantilla->setVariable('comodin'	,' ');
    $this->mantenerDatos($arregloDatos,$formularioPlantilla);
    $this->$arregloDatos[thisFunction]($arregloDatos,$this->datos,$formularioPlantilla);
    if($arregloDatos[mostrar]) {
      $formularioPlantilla->show();
    } else {
      return $formularioPlantilla->get();
    }
  }

  // Arma cada Formulario o función en pantalla
  function setFuncion($arregloDatos,&$unDatos) {
    $unDatos = new Indicadores();
    /*if(!empty($arregloDatos[setCharset])) {
      header( 'Content-type: text/html; charset=iso-8859-1' );
    }*/	

		$r = $unDatos->$arregloDatos[thisFunction]($arregloDatos);

    $unaPlantilla = new HTML_Template_IT();
    $unaPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla],true,true);
    $unaPlantilla->setVariable('comodin'	,' ');

    //Carga información del Perfil
    $arregloDatos[perfil] = $_SESSION['datos_logueo']['perfil_id'];
    //Valida el Perfil para identificar el Tercero
    if($arregloDatos[perfil] == 23) {
      $arregloDatos[soloLectura] = "readonly=''";
      //Carga información del usuario
      $arregloDatos[usuario] = $_SESSION['datos_logueo']['usuario'];
      $arregloDatos[cliente] = $this->datos->findClientet($arregloDatos[usuario]);
    } else {
      $arregloDatos[soloLectura] = "";
      $arregloDatos[usuario] = "";
      $arregloDatos[cliente] = "";
    }

		if(!empty($arregloDatos[mensaje])) {
      $unaPlantilla->setVariable('mensaje',$arregloDatos[mensaje]);
      $unaPlantilla->setVariable('estilo'	,$arregloDatos[estilo]);
    }
    $this->mantenerDatos($arregloDatos,$unaPlantilla);
    $$arregloDatos[n] = 0;

		while($unDatos->fetch()) {
      if($arregloDatos[n] % 2 ){ $odd = 'odd'; } else { $odd = ''; }
      $arregloDatos[n] = $arregloDatos[n] + 1;
      $unaPlantilla->setCurrentBlock('ROW');
      $this->setDatos($arregloDatos,$unDatos,$unaPlantilla);
      $this->$arregloDatos[thisFunction]($arregloDatos,$unDatos,$unaPlantilla);
      $unaPlantilla->setVariable('n',$arregloDatos[n]);
      $unaPlantilla->setVariable('odd',$odd);
      $unaPlantilla->parseCurrentBlock();
    }
	if(!empty($arregloDatos[thisFunctionAux])){
		//$this->$arregloDatos[thisFunctionAux]($arregloDatos,$unDatos,$unaPlantilla);
	}

    if($unDatos->N == 0 and empty($unDatos->mensaje)) {
      $unaPlantilla->setVariable('mensaje','No hay registros para listar'.$arregloDatos[mensaje]);
      $unaPlantilla->setVariable('estilo','ui-state-error');
      $unaPlantilla->setVariable('mostrarCuerpo','none');
    }

		$unaPlantilla->setVariable('num_registros',$unDatos->N);
    if($arregloDatos[mostrar]) {
      $unaPlantilla->show();
    } else {
      $unaPlantilla->setVariable('cuenta',$this->cuenta);
      return $unaPlantilla->get();
    }
  }



  function filtro($arregloDatos,$unDatos,$plantilla) {
  	var_dump($arregloDatos);
   
  }
  
  
  function maestroIndicadores($arregloDatos) {
		$this->plantilla->loadTemplateFile(PLANTILLAS . 'indicadoresMaestro.html', true, true);
  		$this->plantilla->setVariable('comodin'	,' ');
		
		// se construye el grafico
		
		$this->plantilla->show();
  }	
  
  //Funcion encargada de Construir los Parametros para Un Grafico , pinta y pasa los for
	 function grafico($arregloDatos){
	 	$unGrafico  =   new HTML_Template_IT();	
		$unGrafico->loadTemplateFile(PLANTILLAS . 'indicadoresGraficos.htm',true,true);
	 	$total=0;
		$color=0;
		$i=0;
		$colores	='orange@blue@yeyow@black@red';
		$colores	=split('@',$colores);
		while ($this->datos->fetch()) {
			
			$unGrafico->setCurrentBlock('ROW');
			$valores[$i] = $this->datos->valores;
			$i=$i+1;
			$total=$total+$this->datos->valores;
			$unGrafico->setVariable('convencion'   , ucwords(strtolower($this->datos->datos)));
			if(empty($this->colores[$color])){
				$this->colores[$color]='black';
			}
			$unGrafico->setVariable('color'   , $this->colores[$color]);
			$color=$color+1;
			$unGrafico->setVariable('valores'   ,  number_format($this->datos->valores,DECIMALES,",","."));
			
			$unGrafico->parseCurrentBlock();		
		}
         	if(is_array($valores)){
         		$valores		 = implode ("@", $valores);   
          	}
			
			$unGrafico->setVariable('tituloGrafico'  , $arregloDatos[tituloGrafico]);
			$unGrafico->setVariable('datos'   , $datos.'@');
			$unGrafico->setVariable('valores' , $valores.'@');
			$unGrafico->setVariable('total'   ,  number_format($total,DECIMALES,",","."));
			$unGrafico->setVariable('descripcion'   ,$arregloDatos[descripcion] );
			$unGrafico->setVariable('tipoGrafico'   ,$arregloDatos[tipoGrafico] );
	    	return $unGrafico->get();
	 }
	 
	 
	 function indicadorCliente($arregloDatos){
	 	
		$this->plantilla->loadTemplateFile(PLANTILLAS . 'indicadoresGraficos.html', true, true);
  		$this->plantilla->setVariable('comodin'	,' ');
		$colores	='orange@blue@green@black@red@yellow@orange@brown@pink@violet@purple';
		$colores	=split('@',$colores);
		// se construye el grafico
		$arregloDatos[tituloGrafico]=" Comportamiento Facturación";
		$arregloDatos[titulo]=$arregloDatos[tituloGrafico].' '.$arregloDatos[titulo];
		
		$color=0;
		$valores_todos="";
		$n=0;
		$total=0;
		
		
		
		while ($this->datos->fetch()) {
			$n=$n+1;
			$this->plantilla->setCurrentBlock('ROW');
			$this->plantilla->setVariable('color'   , $colores[$color]);
			$this->plantilla->setVariable('valores' , number_format($this->datos->valores,0,',','.'));
			$this->plantilla->setVariable('convencion' , $this->datos->datos);
			$this->plantilla->setVariable('n'   , $n);
			$total=$total+$this->datos->valores;
			$color=$color+1;
			$valores_todos.=$this->datos->valores."@";
			$this->plantilla->parseCurrentBlock();	
		}
		$this->plantilla->setVariable('tituloGrafico' , $arregloDatos[tituloGrafico]);
		$this->plantilla->setVariable('tipoGrafico' , $arregloDatos[tipoGrafico]);
		$this->plantilla->setVariable('valores_todos' , $valores_todos);
		$this->plantilla->setVariable('total' , number_format($total,0,',','.'));
		$this->plantilla->show();
		
  	 }
	 
	  function indicadorIngresos($arregloDatos){
	 	
		$this->plantilla->loadTemplateFile(PLANTILLAS . 'indicadoresGraficosMes.html', true, true);
  		$this->plantilla->setVariable('comodin'	,' ');
		$colores	='orange@blue@green@black@red@yellow@orange@brown@pink@violet@purple';
		$colores	=split('@',$colores);
		$arregloDatos[tituloGrafico]=" Ingresos por cliente";
		// se construye el grafico
		$color=0;
		$valores_todos="";
		$n=0;
		$total=0;
		
		while ($this->datos->fetch()) {
		
			$n=$n+1;
			$this->plantilla->setCurrentBlock('ROW');
			$this->plantilla->setVariable('color'   , $colores[$color]);
			$this->plantilla->setVariable('valores' , number_format($this->datos->valores,0,',','.'));
			$this->plantilla->setVariable('enero_valor' , number_format($this->datos->enero,0,',','.'));
			$this->plantilla->setVariable('febrero_valor' , number_format($this->datos->febrero,0,',','.'));
			$this->plantilla->setVariable('marzo_valor' , number_format($this->datos->marzo,0,',','.'));
			$this->plantilla->setVariable('abril_valor' , number_format($this->datos->abril,0,',','.'));
			$this->plantilla->setVariable('mayo_valor' , number_format($this->datos->mayo,0,',','.'));
			$this->plantilla->setVariable('junio_valor' , number_format($this->datos->junio,0,',','.'));
			$this->plantilla->setVariable('julio_valor' , number_format($this->datos->julio,0,',','.'));
			$this->plantilla->setVariable('agosto_valor' , number_format($this->datos->agosto,0,',','.'));
			$this->plantilla->setVariable('septiembre_valor' , number_format($this->datos->septiembre,0,',','.'));
			$this->plantilla->setVariable('octubre_valor' , number_format($this->datos->octubre,0,',','.'));
			$this->plantilla->setVariable('noviembre_valor' , number_format($this->datos->noviembre,0,',','.'));
			$this->plantilla->setVariable('diciembre_valor' , number_format($this->datos->diciembre,0,',','.'));
			$this->plantilla->setVariable('convencion' , $this->datos->datos);
			$this->plantilla->setVariable('n'   , $n);
			$this->total=$this->datos->enero+$this->datos->febrero+$this->datos->marzo+$this->datos->abril+$this->datos->mayo+$this->datos->junio;
			+$this->datos->julio+$this->datos->agosto+$this->datos->septiembre+$this->datos->septiembre+$this->datos->octubre+$this->datos->noviembre+$this->datos->diciembre;
			$color=$color+1;
			$valores_todos.=$this->datos->enro."@".$this->datos->febrero1."@".$this->datos->marzo."@".$this->datos->abril."@".$this->datos->mayo."@".$this->datos->junio."@".$this->datos->julio."@".$this->datos->agosto."@".$this->datos->septiembre."@".$this->datos->octubre."@".$this->datos->noviembre."@".$this->datos->diciembre."@";
			
			$this->plantilla->parseCurrentBlock();	
		}
		$this->plantilla->setVariable('tituloGrafico' , $arregloDatos[tituloGrafico]);
		$this->plantilla->setVariable('tipoGrafico' , $arregloDatos[tipoGrafico]);
		$this->plantilla->setVariable('valores_todos' , $valores_todos);
		$this->plantilla->setVariable('total' , number_format($this->total,0,',','.'));
		$this->plantilla->show();
		
  	 }
   
  		
} 
?>