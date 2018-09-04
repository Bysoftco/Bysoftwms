<?php

require_once ("HTML/Template/IT.php");



class GraficoPresentacion {
    
    var $datos;
    var $plantilla;
	var $colores;
  
    function GraficoPresentacion (&$datos,&$colores) {
        $this->datos = &$datos;
		$this->colores =&$colores;
		$this->plantilla = new HTML_Template_IT();
    } 
	
	
	function setAtributos(){	
   
        $this->plantilla->setVariable('nombre'	      , $this->datos->nombre);
     
	
     }
	 
	 /*Metodo encargado de pintar La Pagina donde se colocan los Graficos
	 */
	 function dibujarGrafico($arregloDatos){	
    	
		$this->plantilla->loadTemplateFile(PLANTILLAS . 'graficosEstadistica.html',true,true);
		$this->plantilla->setCurrentBlock('GRAFICO');	
		$arregloDatos[titulo]='Cantidad de Carpetas de Grado Por Estado';
		$arregloDatos[descripcion]='Este es el número de Carpetas que están esperando ser revisadas  en cada tarea';
		$arregloDatos[tipoGrafico]='graficoTorta';
		$grafico=$this->grafica($arregloDatos);
		$this->plantilla->setVariable('grafico'  , $grafico);
		$this->plantilla->parseCurrentBlock();
		
		$this->datos->carpetasGradoSede($arregloDatos);
		$this->plantilla->setCurrentBlock('GRAFICO');
		$arregloDatos[titulo]='Cantidad de Carpetas de Grado Por Sede';
		$arregloDatos[descripcion]='Este es el número de Carpetas  de cada Sede que están en el flujo ';
		$grafico=$this->grafica($arregloDatos);
		$this->plantilla->setVariable('grafico'  , $grafico);
		$this->plantilla->parseCurrentBlock();
		
		$this->plantilla->setVariable('titulogeneral'  , 'Estadisticas Proceso Carpetas de Grado');
		$this->plantilla->show();
		
     
	 }
	  
	 /* Funcion que se encarga de armar la estructura para cada grafico que se necesita generar en los indicadores
	 relacionados con personal docente por cada grafico a generar se adiciona un bloque en esta funcion*/
	 function cantidadDocentes($arregloDatos){
	 	
		$this->plantilla->loadTemplateFile(PLANTILLAS . 'graficosEstadistica.html',true,true);
		$this->plantilla->setCurrentBlock('GRAFICO');	
		$this->plantilla->setVariable('titulogeneral'  , 'Indicadores Relacionados con Personal Docente');
		//--------------------------------------------------------
		if(!empty($arregloDatos[chekGrafico1])){
			$arregloDatos[titulo]			='Profesores de Planta y cátedra Nacional';
			$arregloDatos[descripcion]		='Total de profesores de planta y cátedra  a Nivel Nacional, decanos, directores de programa, coordinadores e investigadores';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico1];
			$arregloDatos[tituloGrafico]	='Profesores de Planta y cátedra Nacional';
			$this->datos->cantidadadDocentes($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();
		}			
		//--------------------------------------------------------
		if(!empty($arregloDatos[chekGrafico2])){
			$arregloDatos[descripcion]		='Total de profesores de planta y cátedra  solo Bogotá, decanos, directores de programa, coordinadores e investigadores';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico2];
			$arregloDatos[tituloGrafico]	='Total de profesores de planta y cátedra  solo Bogotá';
			$arregloDatos[filtro]='bogota';
			$this->datos->cantidadadDocentes($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();		
		}	
		//--------------------------------------------------------
		if(!empty($arregloDatos[chekGrafico3])){
			$arregloDatos[titulo]			='Profesores de Planta y cátedra Sedes';
			$arregloDatos[descripcion]		='Total de profesores de planta y cátedra  en las Sedes, decanos, directores de programa, coordinadores e investigadores';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico3];
			$arregloDatos[tituloGrafico]	='Profesores de Planta y cátedra';
			$arregloDatos[filtro]='sedes';
			$this->datos->cantidadadDocentes($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();
		}			
		//--------------------------------------------------------
		if(!empty($arregloDatos[chekGrafico4])){
			$arregloDatos[titulo]			='Dedicacion de Profesores de Planta y cátedra a nivel Nacional ';
			$arregloDatos[descripcion]		='Total de profesores de planta y cátedra por Dedicación a nivel Nacional, decanos, directores de programa, coordinadores e investigadores';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico4];
			$arregloDatos[tituloGrafico]	='Dedicacion de Profesores de Planta y cátedra a nivel Nacional';
			$arregloDatos[filtro]='nacional';
			$this->datos->dedicacionDocentes($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();	
		 }		
		//--------------------------------------------------------
		if(!empty($arregloDatos[chekGrafico5])){
			$arregloDatos[titulo]			=' Dedicacion de Profesores de Planta y cátedra a nivel Bogotá  ';
			$arregloDatos[descripcion]		='Total de profesores de planta y cátedra por Dedicación a nivel Bogotá , decanos, directores de programa, coordinadores e investigadores';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico5];
			$arregloDatos[tituloGrafico]	='Dedicacion de Profesores de Planta y cátedra a nivel Bogotá';
			$arregloDatos[filtro]='bogota';
			$this->datos->dedicacionDocentes($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();
		 }				
		//--------------------------------------------------------
		if(!empty($arregloDatos[chekGrafico6])){
			$arregloDatos[titulo]			=' Dedicacion de Profesores de Planta y cátedra Sedes  ';
			$arregloDatos[descripcion]		='Total de profesores de planta y cátedra por Dedicación en las Sedes , decanos, directores de programa, coordinadores e investigadores';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico6];
			$arregloDatos[tituloGrafico]	='Dedicacion de Profesores de Planta y cátedra Sedes';
			$arregloDatos[filtro]='sedes';
			$this->datos->dedicacionDocentes($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();
		}		
			if(!empty($arregloDatos[chekGrafico7])){
			$arregloDatos[titulo]			=' Dedicacion de Profesores de Planta y cátedra Sedes  ';
			$arregloDatos[descripcion]		='';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico7];
			$arregloDatos[tituloGrafico]	='Profesores de Planta por Rango de Edad  a nivel Nacional';
			$arregloDatos[filtro]='nacional';
			$this->datos->edadesEmpleados($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();
		}		
		if(!empty($arregloDatos[chekGrafico8])){
		   $arregloDatos[titulo]			=' Dedicacion de Profesores de Planta y cátedra Sedes  ';
			$arregloDatos[descripcion]		='';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico8];
			$arregloDatos[tituloGrafico]	='Profesores de Planta por Rango de Edad  en Bogotá';
			$arregloDatos[filtro]='bogota';
			$this->datos->edadesEmpleados($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();
		}		
		if(!empty($arregloDatos[chekGrafico9])){
		   $arregloDatos[titulo]			=' Dedicacion de Profesores de Planta y cátedra Sedes  ';
			$arregloDatos[descripcion]		='';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico9];
			$arregloDatos[tituloGrafico]	='Profesores de Planta por Rango de Edad  Sedes';
			$arregloDatos[filtro]='sedes';
			$this->datos->edadesEmpleados($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();
		}		
		//-------------------------------------------------------
		   if(!empty($arregloDatos[chekGrafico10])){
			$arregloDatos[titulo]			='Profesores de Planta Según Nivel de Formación a nivel nacional  ';
			$arregloDatos[descripcion]		='';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico10];
			$arregloDatos[tituloGrafico]	='Profesores de Planta Según Nivel de Formación a nivel nacional';
			$arregloDatos[filtro]='nacional';
			$this->datos->estudiosEmpleados($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();
		 }	
		 //-------------------------------------------------------
		   if(!empty($arregloDatos[chekGrafico11])){
			$arregloDatos[titulo]			='Profesores de Planta Según Nivel de Formación en Bogotá   ';
			$arregloDatos[descripcion]		='';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico11];
			$arregloDatos[tituloGrafico]	='Profesores de Planta Según Nivel de Formación en Bogotá ';
			$arregloDatos[filtro]='bogota';
			$this->datos->estudiosEmpleados($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();
		 }	
		  if(!empty($arregloDatos[chekGrafico12])){
			$arregloDatos[titulo]			='Profesores de Planta Según Nivel de Formación en las Sedes  ';
			$arregloDatos[descripcion]		='';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico12];
			$arregloDatos[tituloGrafico]	='Profesores de Planta Según Nivel de Formación en las Sedes ';
			$arregloDatos[filtro]='sedes';
			$this->datos->estudiosEmpleados($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();
		 }	
		  if(!empty($arregloDatos[chekGrafico13])){
		 	$arregloDatos[titulo]			='Profesores de Planta por area del conocimiento a nivel nacional ';
			$arregloDatos[descripcion]		='';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico13];
			$arregloDatos[tituloGrafico]	='Profesores de Planta por area del conocimiento a nivel nacional ';
			$arregloDatos[filtro]='nacional';
			$this->datos->areasConocimiento($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();
		 }
		 if(!empty($arregloDatos[chekGrafico14])){
		 	$arregloDatos[titulo]			='Profesores de Planta por area del conocimiento a nivel Bogotá ';
			$arregloDatos[descripcion]		='';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico14];
			$arregloDatos[tituloGrafico]	='Profesores de Planta por area del conocimiento a nivel Bogotá  ';
			$arregloDatos[filtro]='bogota';
			$this->datos->areasConocimiento($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();
		 }
		 if(!empty($arregloDatos[chekGrafico15])){
		 	$arregloDatos[titulo]			='Profesores de Planta por area del conocimiento en las Sedes ';
			$arregloDatos[descripcion]		='';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico15];
			$arregloDatos[tituloGrafico]	='Profesores de Planta por area del conocimiento en las Sedes ';
			$arregloDatos[filtro]='sedes';
			$this->datos->areasConocimiento($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();
		 }
		 if(!empty($arregloDatos[chekGrafico16])){
		 	$arregloDatos[titulo]			='Personal Administrativo Por Niveles a Nivel Nacional';
			$arregloDatos[descripcion]		='';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico16];
			$arregloDatos[tituloGrafico]	='Personal Administrativo Por Niveles a Nivel Nacional ';
			$arregloDatos[filtro]='nacional';
			$this->datos->cantidadNivel($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();
		 }
		 if(!empty($arregloDatos[chekGrafico17])){
		 	$arregloDatos[titulo]			='Personal Administrativo Por Niveles en Bogotá ';
			$arregloDatos[descripcion]		='';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico17];
			$arregloDatos[tituloGrafico]	='Personal Administrativo Por Niveles en Bogotá ';
			$arregloDatos[filtro]='bogota';
			$this->datos->cantidadNivel($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();
		 }
		
		   if(!empty($arregloDatos[chekGrafico18])){
		 	$arregloDatos[titulo]			='Personal Administrativo en las Sedes';
			$arregloDatos[descripcion]		='';
			$arregloDatos[tipoGrafico]		=$arregloDatos[lisGrafico18];
			$arregloDatos[tituloGrafico]	='Personal Administrativo en las Sedes ';
			$arregloDatos[filtro]='sedes';
			$this->datos->cantidadNivel($arregloDatos);
			$this->plantilla->setCurrentBlock('GRAFICO');
			$grafico=$this->grafica($arregloDatos);
			$this->plantilla->setVariable('grafico'  , $grafico);
			$this->plantilla->parseCurrentBlock();
		 }
		$this->plantilla->show();	
	 }
	 
	 function dibujarGraficos($arregloDatos){
	 	$arregloDatos[titulo]			='Indicadores de Nomina';
		$arregloDatos[tipoGrafico]		='barras';
		$arregloDatos[descripcion]		="Este es el número de Empleados Por Mes (año ".$arregloDatos[anio].")";
		$arregloDatos[tituloGrafico]	='Cantidad de empleados en Nomina';	
		$this->plantilla->loadTemplateFile(PLANTILLAS . 'graficosEstadistica.html',true,true);
		$this->plantilla->setCurrentBlock('GRAFICO');	
		$this->plantilla->setVariable('titulogeneral'  , $arregloDatos[titulo]);
	
		$grafico=$this->grafica($arregloDatos);
		$this->plantilla->setVariable('grafico'  , $grafico);
		$this->plantilla->parseCurrentBlock();
		//--------------------------------------------------------
		$arregloDatos[tituloGrafico]	='Valor de la Nomina';
		$arregloDatos[descripcion]		="Este es el valor de la nomina en cada mes (año ".$arregloDatos[anio].")";
		$this->datos->valorNomina($arregloDatos);
		$this->plantilla->setCurrentBlock('GRAFICO');
		$grafico=$this->grafica($arregloDatos);
		$this->plantilla->setVariable('grafico'  , $grafico);
		$this->plantilla->parseCurrentBlock();		
		
		//--------------------------------------------------------
		
		$arregloDatos[tituloGrafico]	='Sedes con mayor participación';
		$arregloDatos[tipoGrafico]		='graficoTorta';
		$arregloDatos[descripcion]		="Estas son las 10 sedes con mayor participación en la Nomina (año ".$arregloDatos[anio].")";
		$this->datos->nominaSedes($arregloDatos);
		$this->plantilla->setCurrentBlock('GRAFICO');
		$grafico=$this->grafica($arregloDatos);
		$this->plantilla->setVariable('grafico'  , $grafico);
		$this->plantilla->parseCurrentBlock();	
			
		$this->plantilla->show();	
	 }
	 
	  function indexIndicadores($arregloDatos){
	 	$this->plantilla->loadTemplateFile(PLANTILLAS . 'graficaIndicadoresIndex.html',false,false);
		$meses= traerMeses();
		$anios=traerAnios();
		$selectMes		= armaSelect($meses,NULL,'[Mes]');
		$selectAnios    = armaSelect($anios,2007,'[Todos]');
		$this->plantilla->setVariable('selectMes' , $selectMes);
		$this->plantilla->setVariable('selectAnio' , $selectAnios);
		$this->plantilla->show();	
	 }
	 
	//Funcion encargada de Construir los Parametros para Un Grafico , pinta y pasa los for
	 function grafica($arregloDatos){
	 	$unGrafico  =   new HTML_Template_IT();	
		$unGrafico->loadTemplateFile(PLANTILLAS . 'graficosGrafica.html',true,true);
	 
	 	$total=0;
		$color=0;
		$i=0;
		while ($this->datos->fetch()) {
			
			$unGrafico->setCurrentBlock('ROW');
			$valores[$i] = $this->datos->valores;
			$i=$i+1;
			$total=$total+$this->datos->valores;
			$unGrafico->setVariable('convencion'   , ucwords(strtolower($this->datos->datos)));
			$unGrafico->setVariable('color'   , $this->colores[$color]);
			$color=$color+1;
			$unGrafico->setVariable('valores'   ,  number_format($this->datos->valores,DECIMALES,",","."));
			
			$unGrafico->parseCurrentBlock();		
		}
	
		$valores		 = implode ("@", $valores);
	//echo 'XXXXX'.$valores;
		$unGrafico->setVariable('tituloGrafico'  , $arregloDatos[tituloGrafico]);
		$unGrafico->setVariable('datos'   , $datos.'@');
		$unGrafico->setVariable('valores' , $valores.'@');
		$unGrafico->setVariable('total'   ,  number_format($total,DECIMALES,",","."));
		$unGrafico->setVariable('descripcion'   ,$arregloDatos[descripcion] );
		$unGrafico->setVariable('tipoGrafico'   ,$arregloDatos[tipoGrafico] );
	    return $unGrafico->get();
	 }
	
} 



?>