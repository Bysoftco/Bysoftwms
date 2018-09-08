<?php
/*
	Versi�n: 1.0
	Autor:   Fredy Arevalo
	Fecha:   Mayo 19 de 2006
	Descripci�n:
	
	
*/

require_once("GraficoDatos.php");
require_once("GraficoPresentacion.php");


	
	class GraficoLogica  {
  		var $datos;
		var $pantalla;
		var $colores ; 
		function GraficoLogica () {
    
			$this->datos = &new Grafico();
			$this->colores = array("#9966FF", "#66CCFF","#ffff88", "#FF3300", "#FFCC33","#92A9D3","#FF99FF","#333333","#CCCC66","#FFFF66","#006600","#00FF00","#FFFF33","#7400E8","#99FFFF","#FF3366","#663366"."#FF00FF","#99FF00","#CCFFCC","#CCFF00","#6600CC","#6677CC","#0077CC","#807FCC","#807F00","#8FFFCC","#811FCC","#811F93","#441F93","#441F55");
     		
			$this->pantalla = &new GraficoPresentacion($this->datos,$this->colores);
		} 
	
	
		function dibujarGrafico ($arregloDatos) {
			$this->datos->cantidadCarpetasGrado($arregloDatos);
			$this->pantalla->dibujarGrafico($arregloDatos);
		}
		function indexIndicadores ($arregloDatos) {
			$this->pantalla->indexIndicadores($arregloDatos);
		}
	     
		function indicadoresNomina ($arregloDatos) {
			$this->datos->cantidadEmpleados($arregloDatos);
			$this->pantalla->dibujarGraficos($arregloDatos);
		}	
	
		function cantidadDocentes ($arregloDatos) {
			//$this->datos->cantidadDocentes($arregloDatos);
			$this->pantalla->cantidadDocentes($arregloDatos);
		}	
	
		function graficoTorta ($arregloDatos) {
			
			
			$valores=split('@',$arregloDatos[valores]);
		
			$Graph =& Image_Graph::factory('graph', array(400, 300));
			//$Font =& $Graph->addNew('font', 'Verdana');
			//$Font->setSize(9);
			//$Graph->setFont($Font);
		
			$Graph->add(
    		Image_Graph::vertical(
        	Image_Graph::factory('title', array($arregloDatos[titulo], 9)),
        	Image_Graph::horizontal(
            $Plotarea = Image_Graph::factory('plotarea'),
            $Legend = Image_Graph::factory('legend'),
            70	),5            
    		)
		);
		$Legend->setPlotarea($Plotarea);
		
		// Se pintan Dinamicamente las Porciones del Grafico
		$Dataset1 =& Image_Graph::factory('dataset');
		
		foreach ($valores as $key => $value) {
			if(!empty($value)){	
				$Dataset1->addPoint($key, $value);	
			}
		}
		

	
		$Plot =& $Plotarea->addNew('pie', array(&$Dataset1));
		$Plotarea->hideAxis();
		// create a Y data value marker
		$Marker =& $Plot->addNew('Image_Graph_Marker_Value', IMAGE_GRAPH_PCT_Y_TOTAL);
		// create a pin-point marker type
		$PointingMarker =& $Plot->addNew('Image_Graph_Marker_Pointing_Angular', array(20, &$Marker));
		// and use the marker on the 1st plot
		$Plot->setMarker($PointingMarker);	
		// format value marker labels as percentage values
		$Marker->setDataPreprocessor(Image_Graph::factory('Image_Graph_DataPreprocessor_Formatted', '%0.1f%%'));
		$Plot->Radius = 2;

		$FillArray =& Image_Graph::factory('Image_Graph_Fill_Array');
		$Plot->setFillStyle($FillArray);
		$color=0;
		foreach ($valores as $key => $value) {
			$FillArray->addNew('gradient', array(IMAGE_GRAPH_GRAD_RADIAL, 'white', $this->colores[$color]));
			$color=$color+1;
		}
	
		

		$Plot->explode(5);
		
		$Plot->setStartingAngle(90);
			   
		// output the Graph
		$Graph->done();
		}
		
		
		function barras ($arregloDatos) {
        
			$valores	='2@10@10@20@5';
$titulosX	='Enero@Febrero@Marzo@Abril@Mayo';
$colores	='orange@blue@green@black@red@yellow@orange@brown@pink@violet@purple';
$tituloY	='Cantidad';
$titulo    	=$arregloDatos[tituloGrafico];
//$valores	=split('@',$valores);
	$valores=split('@',$arregloDatos[valores]);
$titulosX	=split('@',$arregloDatos[valores]);
$colores	=split('@',$colores);



$Graph =& Image_Graph::factory('graph', array(400, 250));
		    //$Arial =& $Graph->addNew('font', 'Verdana');
			//$Arial->setSize(8);
			//$Arial->setColor('white');
			//$Graph->setFont($Arial);
   			$Graph->add(
 						Image_Graph::vertical(Image_Graph::factory('title', array($titulo, 11)),Image_Graph::vertical(
            			$Plotarea = Image_Graph::factory('plotarea'),$Legend = Image_Graph::factory('legend'),90),7)
						);
			$Legend->setPlotarea($Plotarea);
			$Graph->setBackground(Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_VERTICAL, 'green', 'lightblue')));
			$Graph->setBorderColor('black');

			$Dataset =& Image_Graph::factory('dataset');
			foreach ($valores as $key => $value) {  //Titulos X
			if(!empty($value)){	
				$Dataset->addPoint($key+1, $value);	
			}
		}
			
			$GridY =& $Plotarea->addNew('line_grid', null, IMAGE_GRAPH_AXIS_Y);
			$GridY->setLineColor('white@0.8');
			//Tipo de Grafico
			$Plot =& $Plotarea->addNew('bar', array(&$Dataset));
   					
			// Colores de Cada Concepto�
			$FillArray =& Image_Graph::factory('Image_Graph_Fill_Array');
			$color=0;
			foreach ($valores as $key => $value) {
				
				$FillArray->addNew('gradient', array(IMAGE_GRAPH_GRAD_VERTICAL, 'white',$colores[$color] ));
				$color=$color+1;
			}
			
			// Se Arma el Grafico   
			$Plot->setFillStyle($FillArray);
			$AxisY =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
			$AxisY->setTitle($tituloY, 'vertical');
			$Graph->done();

	}	
				
		
}		
  	
?>
