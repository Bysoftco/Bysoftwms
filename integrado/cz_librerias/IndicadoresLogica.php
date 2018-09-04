<?php
//require_once("IndicadoresExcel.php");
require_once("IndicadoresDatos.php");
require_once("IndicadoresPresentacion.php");

	class IndicadoresLogica  {
  		var $datos;
		var $pantalla;
		
            function IndicadoresLogica () {

                $this->datos = &new Indicadores();
                $this->pantalla = &new IndicadoresPresentacion($this->datos);
            }

            function filtroPrincipal($arregloDatos){
                $arregloDatos[mostrar]          =1;
                $arregloDatos[plantilla]        ='IndicadoresPrincipal.html';
                $arregloDatos[thisFunction]     ='filtronPrincipal';
                $this->pantalla->setFuncion($arregloDatos,&$this->datos);
            }
			
		function maestroIndicadores($arregloDatos) {
    		//$arregloDatos['titulo'] = $this->titulo($arregloDatos);
			$arregloDatos['accion'] = 'defectuosa';
			$arregloDatos['metodoEnvia'] = 'maestroDefectuosa';
    		$arregloDatos['metodoAux'] = 'maestroConsulta';
			
    		$this->pantalla->maestroIndicadores($arregloDatos);
  		}
		
		 function filtro($arregloDatos)
		 {
         	$arregloDatos[mostrar]          =1;
            $arregloDatos[plantilla]        ='indicadoresFiltroClientes.html';
            $arregloDatos[thisFunction]     ='filtro';
            $this->pantalla->setFuncion($arregloDatos,&$this->datos);
         }
		 
		  function indicadorCliente($arregloDatos)
		  {
		  	$this->datos->indicadorCliente($arregloDatos);
		  	$this->pantalla->indicadorCliente($arregloDatos);	
         
          }
		
			function titulo($arregloDatos){
                if(!empty($arregloDatos[cliente])) {
                   $arregloDatos[titulo] ="Cliente ".$arregloDatos[cliente];
                }
                
                if(!empty($arregloDatos[fecha_inicio])) {
                   $arregloDatos[titulo] =" Desde ".$arregloDatos[fecha_inicio]." Hasta.$arregloDatos[fecha_fin]";
                }
             }
            
         
            function generaExcel ($arregloDatos) 
            {
                $arregloDatos[excel]=1;
                $arregloDatos['titulo']='Indicadores '.$arregloDatos[titulo];
                $arregloDatos['sql']=$this->datos->getInventario($arregloDatos);
               // echo $arregloDatos['sql'];die();
                $unExcel = new IndicadoresExcel($arregloDatos);
                $unExcel->generarExcel();

            } 
            

	}		
  	
?>
