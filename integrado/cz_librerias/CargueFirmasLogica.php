          <?php

require_once("CargueFirmasDatos.php");
require_once("CargueFirmasPresentacion.php");
require_once("ReporteExcel.php");
require_once("Archivo.php");

class CargueFirmasLogica {

    var $datos;
    var $pantalla;
    var $adjuntados;
    var $no_adjuntados;

    function CargueFirmasLogica() {
        $this->datos = &new CargueFirmas();

        $this->pantalla = &new CargueFirmasPresentacion($this->datos);
    }

    function filtroCargueFirmas($arregloDatos) {
        $arregloDatos[mostrar] = 1;
        $arregloDatos[plantilla] = "CargueFirmasFiltro.html";
        $arregloDatos[thisFunction] = 'filtro';
        $this->pantalla->setFuncion($arregloDatos, $this->datos);
    }


    function titulo($arregloDatos) {
        $vartitulo = "Filtrado por: ";
        // 
        if (!empty($arregloDatos[numero])) {
            $vartitulo.="Numero de Documento " . $arregloDatos[numero];
        }
       
        $arregloDatos[titulo] = $vartitulo;
        return ucwords(strtolower($titulo));
    }

   

    /*     * FUNCIONES PARA CARGAR BANCOS* */

    function maestroFirmas($arregloDatos) {

        $this->pantalla->maestroFirmas($arregloDatos);
    }
	


   
	 function guardaRuta($arregloDatos) {
	 	$this->datos->guardaRuta($arregloDatos);
		echo $arregloDatos[mensaje].'*'.$arregloDatos[estilo];
	 }
	


}

?>