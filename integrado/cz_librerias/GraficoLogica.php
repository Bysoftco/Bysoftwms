<?php
/*
	Versión: 1.0
	Autor:   Fredy Arevalo
	Fecha:   Mayo 19 de 2006
	Descripción:
	
	
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
			$Graph->setBackground(Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_VERTICAL, 'gray', 'lightblue')));
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
   					
			// Colores de Cada Conceptoº
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
	
	function lineas ($arregloDatos) {
	
	$valores		=split('@',$arregloDatos[valores]);
	$valores_retiro	=split('@',$arregloDatos[valores_retiro]);
	
	//https://github.com/pear/Image_Graph/blob/master/docs/examples/line_break.php
	$Graph =& Image_Graph::factory('graph', array(400, 300)); 
	// add a TrueType font
	//$Font =& $Graph->addNew('font', 'Verdana');
	// set the font size to 11 pixels
	//$Font->setSize(10);
	//$Graph->setFont($Font);
	// setup the plotarea, legend and their layout
	$Graph->add(
   Image_Graph::vertical(
      Image_Graph::factory('title', array('Ingresos -Salidas Por cliente', 12)),        
      Image_Graph::vertical(
          Image_Graph::vertical(
            $Plotarea1 = Image_Graph::factory('plotarea') ,
            $Plotarea2 = Image_Graph::factory('plotarea'),
            50
        ),
        $Legend = Image_Graph::factory('legend'),
         88
      ),
      5 
   )
);   
	// link the legend with the plotares  
	$Legend->setPlotarea($Plotarea1);
	$Legend->setPlotarea($Plotarea2);
	// ingresos
	$Dataset =& Image_Graph::factory('dataset');
	foreach ($valores as $key => $value) 
	{  
		$Dataset->addPoint($key+1, $value);	
			
	}
	// salidas

	$Dataset1 =& Image_Graph::factory('dataset');
	foreach ($valores_retiro as $key1 => $value1) 
	{  
		//echo " key1".$key1." value1".$value1."<BR>";
		$Dataset1->addPoint($key1+1, $value1);	
			
	}

	$Plot1 =& $Plotarea1->addNew('line', array(&$Dataset1));
	$Plot1->setLineColor('red');
	$Plot2 =& $Plotarea2->addNew('line', array(&$Dataset));    
	$Plot2->setLineColor('blue'); 
	$Graph->done();
	}
	
	function lineas1($arregloDatos) 
	{
	
		$titulo    	=$arregloDatos[tituloGrafico];
		$valores	=split('@',$arregloDatos[valores]);
		//echo $valores;
		//https://github.com/pear/Image_Graph/blob/master/docs/examples/plot_line.php
		$Graph =& Image_Graph::factory('graph', array(400, 300));
		// add a TrueType font
		//$Font =& $Graph->addNew('font', 'Verdana');
		// set the font size to 11 pixels
		//$Font->setSize(10);
		//$Graph->setFont($Font);
		// setup the plotarea, legend and their layout
		$Graph->add(
   			Image_Graph::vertical(
      		Image_Graph::factory('title', array($titulo, 12)),        
      		Image_Graph::vertical(
         	$Plotarea = Image_Graph::factory('plotarea'),
         	$Legend = Image_Graph::factory('legend'),
         	88
     	 	),
     	 	5
  			 )
		);   

	$Legend->setPlotarea($Plotarea);
	
	
	$Dataset =& Image_Graph::factory('dataset');
	

	foreach ($valores as $key => $value) 
	{  
		
		$Dataset->addPoint($key+1, $value);	
			
	}
	
	$Plot =& $Plotarea->addNew('line', array(&$Dataset));
	$Plot->setLineColor('blue');                  
    $Graph->done();			
	}
	
	
}		
  	
?>
