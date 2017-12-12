<?php
require_once("ReporteExcel.php");
require_once("UbicacionDatos.php");
require_once("UbicacionPresentacion.php");

class UbicacionLogica  {
  		var $datos;
		var $pantalla;
		
        function UbicacionLogica () {
			    $this->datos = &new Ubicacion();
                $this->pantalla = &new UbicacionPresentacion($this->datos);
            }
			
			
		function filtro($arregloDatos){
                $arregloDatos[mostrar]          =1;
                $arregloDatos[plantilla]        ='ubicacionFiltro.html';
                $arregloDatos[thisFunction]     ='filtro';
                $this->pantalla->setFuncion($arregloDatos,&$this->datos);
            }
            
             function titulo($arregloDatos){
                if(!empty($arregloDatos[cliente])) {
                   $arregloDatos[titulo] ="Cliente ".$arregloDatos[cliente];
                }
                
                if(!empty($arregloDatos[fecha_inicio])) {
                   $arregloDatos[titulo] =" Desde ".$arregloDatos[fecha_inicio]." Hasta.$arregloDatos[fecha_fin]";
                }
             }
            
            function getUbicacion($arregloDatos){
                $this->titulo(&$arregloDatos);
                $arregloDatos[mostrar]          =1;
                $arregloDatos[plantilla]        ='SubpartidaSubpartidaInventario.html';
                $arregloDatos[thisFunction]     ='inventario';
                $this->pantalla->setFuncion($arregloDatos,&$this->datos);
                
            }
			function getUnaUbicacion($arregloDatos){
                $arregloDatos[mostrar] = 1;
    			$arregloDatos[plantilla] = 'ubicacionFormulario.html';
   				$arregloDatos[thisFunction] = 'getUnaUbicacion';
    			$this->pantalla->setFuncion($arregloDatos,&$this->datos);
                
            }
			function updateUbicacion($arregloDatos){
              $this->datos->updateUbicacion($arregloDatos); 
                
            }
			
		function findUbicacion($arregloDatos) {
		$unaConsulta = new Ubicacion();

		$unaConsulta->findCliente($arregloDatos);
		$arregloDatos[q] = strtolower($_GET["q"]);

		while($unaConsulta->fetch()) {
			$nombre = utf8_encode(trim($unaConsulta->nombre));
			echo "$nombre|$unaConsulta->posiciones|	$nombre\n";
		}
		if($unaConsulta->N == 0) {
			echo "No hay Resultados|0\n";
		}
	}

            function generaExcel ($arregloDatos) 
            {
                $arregloDatos[excel]=1;
                $arregloDatos['titulo']='Ubicacion '.$arregloDatos[titulo];
                $arregloDatos['sql']=$this->datos->getInventario($arregloDatos);
               // echo $arregloDatos['sql'];die();
                $unExcel = new SubpartidaExcel($arregloDatos);
                $unExcel->generarExcel();

            } 
             function maestro($arregloDatos) {
			 	 $this->pantalla->maestro($arregloDatos);
			} 
			function getListado($arregloDatos) {
				                //$this->titulo(&$arregloDatos);
				$arregloDatos[mostar] = "0";
    			$arregloDatos[plantilla] = 'ubicacionToolbar.html';
    			$arregloDatos[thisFunction] = 'getToolbar';
    			$arregloDatos[toolbarLevante]= $this->pantalla->setFuncion($arregloDatos, &$this->datos);				
								
                $arregloDatos[mostrar]          =1;
                $arregloDatos[plantilla]        ='ubicacionListado.html';
                $arregloDatos[thisFunction]     ='getListado';
                $this->pantalla->setFuncion($arregloDatos,&$this->datos);
			 	 //$this->pantalla->maestro($arregloDatos);
			}
			function getFormaNueva($arregloDatos){
				$arregloDatos[plantilla]        ='ubicacionFormNueva.html';
                $arregloDatos[thisFunction]     ='getFormaNueva';
                $this->pantalla->setFuncion($arregloDatos,&$this->datos);
				
			}
			
			function addUbicacion($arregloDatos){
				$this->datos->addUbicacion($arregloDatos);
				
				$arregloDatos[mostar] = "0";
    			$arregloDatos[plantilla] = 'ubicacionToolbar.html';
    			$arregloDatos[thisFunction] = 'getToolbar';
    			$arregloDatos[toolbarLevante]= $this->pantalla->setFuncion($arregloDatos, &$this->datos);
				
				$arregloDatos[mostar] = "1";
				$arregloDatos[plantilla] = 'ubicacionListado.html';
    			$arregloDatos[thisFunction] = 'getListado';
    			echo $this->pantalla->setFuncion($arregloDatos,$this->datos);
			}
			function deleteUbicacion($arregloDatos){
				$this->datos->deleteUbicacion($arregloDatos);
                
				$arregloDatos[mostar] = "0";
    			$arregloDatos[plantilla] = 'ubicacionToolbar.html';
    			$arregloDatos[thisFunction] = 'getToolbar';
    			$arregloDatos[toolbarLevante]= $this->pantalla->setFuncion($arregloDatos, &$this->datos);
					
				$arregloDatos[mostar] = "1";
				$arregloDatos[plantilla] = 'ubicacionListado.html';
    			$arregloDatos[thisFunction] = 'getListado';
    			echo $this->pantalla->setFuncion($arregloDatos,$this->datos);
				 				
			}
          
	}		
  	
?>
