<?php	 /*

	 Versión 1.0

	 Autor Fredy Arevalo

	 Fecha September  11 de 20079

	 Descripción:

	 Clase encargada de Construir la Interfaz Grafica Para el Modulo de Transportador

	 */

require_once ("HTML/Template/IT.php");

require_once ("Funciones.php");





class TransportadorPresentacion {

    

    var $datos;

    var $plantilla;

    var $tot_peso_nac;

    var $p_naci_t;

    var $peso;

    var $p_retiro;

    var $cif_t;
	var $can_nac;
	var $can_ext;
	var $total_cif;
	var $total_fob;

    function TransportadorPresentacion (&$datos) {

        $this->datos = &$datos;

        $this->plantilla = new HTML_Template_IT();

        

    } 



    function mantenerDatos($arregloCampos,&$plantilla)

    {

	 

        $plantilla = &$plantilla;

        if (is_array($arregloCampos)) 

        {

            foreach ($arregloCampos as $key => $value) 

            {

                $plantilla->setVariable($key , $value);

            }

        }

    }

	

    //Funcion que coloca los datos que vienen de la BD

    function setDatos ($arregloDatos,&$datos,&$plantilla){



        foreach ($datos as $key => $value) {



                $plantilla->setVariable($key , $value);

        }



    }

    

    function getLista ($arregloDatos,$seleccion,&$plantilla){



        $unaLista 	= new Transportador();

        $lista		= $unaLista->lista($arregloDatos[tabla],$arregloDatos[condicion],$arregloDatos[campoCondicion]);

        $lista		= armaSelect($lista,'[seleccione]',$seleccion);

        $plantilla->setVariable($arregloDatos[labelLista], $lista);



    }

	

    function cargaPlantilla($arregloDatos){



        $unAplicaciones = new Transportador();

        $formularioPlantilla = new HTML_Template_IT();

        $formularioPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla],false,false);

        $formularioPlantilla->setVariable('comodin'	,' ');

        $this->mantenerDatos($arregloDatos,&$formularioPlantilla);



        $this->$arregloDatos[thisFunction]($arregloDatos,$this->datos,$formularioPlantilla);

        if($arregloDatos[mostrar]){
			$formularioPlantilla->show();
		}else{
			return $formularioPlantilla->get();
		}
    }

	

	// Arma cada Formulario o funcion en pantalla

	function setFuncion($arregloDatos,$unDatos){
		$unDatos = new Transportador();

		if(!empty($arregloDatos[setCharset])){

			header( 'Content-type: text/html; charset=iso-8859-1' );

		}	

		$r=$unDatos->$arregloDatos[thisFunction](&$arregloDatos);
		$unaPlantilla = new HTML_Template_IT();

		$unaPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla],true,true);

                $unaPlantilla->setVariable('comodin'	,' ');

		if(!empty($arregloDatos[mensaje])){

	

                    $unaPlantilla->setVariable('mensaje',$arregloDatos[mensaje]);

                    $unaPlantilla->setVariable('estilo'	,$arregloDatos[estilo]);

			

		}

		
                

		$this->mantenerDatos($arregloDatos,$unaPlantilla);

		$$arregloDatos[n]=0;

		while ($unDatos->fetch()) {

                   

                    if ($arregloDatos[n] % 2 ){$odd='odd';}else{$odd='';}  

                    $arregloDatos[n]=$arregloDatos[n]+1;

                    $unaPlantilla->setCurrentBlock('ROW');

                   

                    $this->setDatos($arregloDatos,$unDatos,$unaPlantilla);



                    $this->$arregloDatos[thisFunction]($arregloDatos,$unDatos,$unaPlantilla);

                    $unaPlantilla->setVariable('n'	,$arregloDatos[n]);

                    $unaPlantilla->setVariable('odd'			, $odd);

                    $unaPlantilla->parseCurrentBlock();

			

		}

		

		if($unDatos->N==0 and empty($unDatos->mensaje))

                {

                  $unaPlantilla->setVariable('mensaje'	,'No hay registros para listar'.$arregloDatos[mensaje]);

		  $unaPlantilla->setVariable('estilo'	,'ui-state-error');

                  $unaPlantilla->setVariable('mostrarCuerpo'	,'none');

		}

		$unaPlantilla->setVariable('num_registros'	,$unDatos->N);

		

		if($arregloDatos[mostrar]){

                    $unaPlantilla->show();

		}else{

                    

                    $unaPlantilla->setVariable('cuenta'	,$this->cuenta);

                    return $unaPlantilla->get();

		}

		

	}

     
   

     function maestro($arregloDatos) {
    if($arregloDatos[tipo_retiro_label] == "Matriz") {
      $this->plantilla->setVariable('tipo_retiro_label', 'Matriz');
    }
    $this->plantilla->loadTemplateFile(PLANTILLAS . 'transportadorMaestro.html', true, true);
    $this->plantilla->setVariable('comodin', ' ');

    $arregloDatos[tab_index] = 2;
    //$this->getTitulo(&$arregloDatos);
    $this->mantenerDatos($arregloDatos, &$this->plantilla);

    $arregloDatos[mostar] = "0";
    $arregloDatos[plantilla] = 'transportadorToolbar.html';
    $arregloDatos[thisFunction] = 'getToolbar';
    $this->plantilla->setVariable('toolbarLevante', $this->setFuncion($arregloDatos, &$this->datos));

    if(empty($arregloDatos[por_cuenta_filtro])) {
       $this->plantilla->setVariable('abre_ventana', 1);
	   $arregloDatos[thisFunction] = 'filtro';
    $arregloDatos[plantilla] = 'transportadorFiltro.html';
    $arregloDatos[mostrar] = 0;
    $htmlFiltro = $this->cargaPlantilla($arregloDatos);
    $this->plantilla->setVariable('filtroFiltro', $htmlFiltro);
	
    } else {
      $this->plantilla->setVariable('abre_ventana', 0);
	 
      // el método controlarTransaccion de la Logica envia la plantilla y el método para pintar el TAB de mercancia
      $arregloDatos[mostrar] = 0;
      $arregloDatos[plantilla] = $arregloDatos[plantillaMercanciaCuerpo];
      $arregloDatos[thisFunction] = $arregloDatos[metodoMercanciaCuerpo];
      $htmlMercancia = $this->setFuncion($arregloDatos, &$this->datos);
      $this->plantilla->setVariable('htmlMercancia', $htmlMercancia);

    
    }



    
    $this->plantilla->show();
  }  

    function getListado($arregloDatos,$unDatos,$plantilla)
	{
	
	}     

     function filtro($arregloDatos,$unDatos,$plantilla)
	{

        $unaLista 	= new Transportador();

        //$lista		= $unaLista->lista("tipos_remesas");

       // $lista		= armaSelect($lista,'[seleccione]',NULL);

       // $plantilla->setVariable("listaTiposRemesa", $lista);

    }


     function  getUnTransportador($arregloDatos,$unDatos){
			//var_dump($unDatos);
     }
	 function getFormaNueva($arregloDatos) {
		
	}
	 

  } 







?>