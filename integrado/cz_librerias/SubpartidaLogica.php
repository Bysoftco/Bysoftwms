<?php
require_once("ReporteExcel.php");
require_once("SubpartidaDatos.php");
require_once("SubpartidaPresentacion.php");

class SubpartidaLogica  {
  		var $datos;
		var $pantalla;
		
            function SubpartidaLogica () {

                $this->datos = &new Subpartida();
                $this->pantalla = &new SubpartidaPresentacion($this->datos);
            }

           
            
              function filtro($arregloDatos){
                $arregloDatos[plantilla]        ='SubpartidaFiltro.html';
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
            
            function getSubpartida($arregloDatos){
                $this->titulo(&$arregloDatos);
                $arregloDatos[mostrar]          =1;
                $arregloDatos[plantilla]        ='SubpartidaSubpartidaInventario.html';
                $arregloDatos[thisFunction]     ='inventario';
                $this->pantalla->setFuncion($arregloDatos,&$this->datos);
                
            }
			function getUnaSubpartida($arregloDatos){
                $arregloDatos[mostrar] = 1;
    			$arregloDatos[plantilla] = 'subpartidaFormulario.html';
   				$arregloDatos[thisFunction] = 'getUnaSubpartida';
    			$this->pantalla->setFuncion($arregloDatos,&$this->datos);
                
            }
			function updateSubpartida($arregloDatos){
              $this->datos->updateSubpartida($arregloDatos);
            }
			
		function findSupartida($arregloDatos) {
		$unaConsulta = new Subpartida();

		$unaConsulta->findCliente($arregloDatos);
		$arregloDatos[q] = strtolower($_GET["q"]);

		while($unaConsulta->fetch()) {
			$nombre = utf8_encode(trim($unaConsulta->descripcion));
			echo "$nombre|$unaConsulta->subpartida|	$nombre\n";
		}
		if($unaConsulta->N == 0) {
			echo "No hay Resultados|0\n";
		}
	}

            function generaExcel ($arregloDatos) 
            {
                $arregloDatos[excel]=1;
                $arregloDatos['titulo']='Subpartida '.$arregloDatos[titulo];
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
                $arregloDatos[mostrar]          =1;
                $arregloDatos[plantilla]        ='subpartidaListado.html';
                $arregloDatos[thisFunction]     ='getListado';
                $this->pantalla->setFuncion($arregloDatos,&$this->datos);
			 	 //$this->pantalla->maestro($arregloDatos);
			}
			function getFormaNueva($arregloDatos){
				$arregloDatos[plantilla]        ='subpartidaFormNueva.html';
                $arregloDatos[thisFunction]     ='getFormaNueva';
                $this->pantalla->setFuncion($arregloDatos,&$this->datos);
				
			}
			
			function addSubpartida($arregloDatos){
				$this->datos->addSubpartida($arregloDatos);
			}
          
	}		
  	
?>
