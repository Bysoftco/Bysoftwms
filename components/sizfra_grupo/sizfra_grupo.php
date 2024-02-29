<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once(COMPONENTS_PATH.'sizfra_grupo/model/sizfra_grupo.php');
require_once(COMPONENTS_PATH.'sizfra_grupo/views/vista.php');
require_once(COMPONENTS_PATH.'sizfra_grupo/views/tmpl/Archivo.php');
require_once(COMPONENTS_PATH.'sizfra_grupo/views/tmpl/EnvioMail.php');

class sizfra_grupo {
  var $vista;
  var $datos;

  function sizfra_grupo() {
    $this->vista = new SizfraVista();
    $this->datos = new SizfraModelo();
  }

  function filtroSizfra($arreglo) {
  	$this->vista->filtroSizfra($arreglo);
    $parameters = Array(
      'BLDAT' => '01112016',
      'BLART' => '90',
      'BUKRS' => '1200',
      'BUDAT' => '01112016',
      'WAERS' => 'COP',
      'XBLNR' => 'TEXTO DE CABECERA',
      'BKTXT' => 'TET DE CABECERA EN DOCUMENTO',
      'BSCHL' => '50',
      'KUNNR' => '0010000095',
      'LIFNR' => '0010001898',
      'HKONT' => '5140950101',
      'UMSKZ' => '',
      'WRBTR' => '120000000',
      'MWSKZ' => 'V3',
      'ZTERM' => 'K004',
      'VALUT' => '01112016',
      'ZFBDT' => '01112016',
      'ZUONR' => 'ASIGNACION',
      'SGTXT' => 'TEXTO POSICION',
      'KOSTL' => '1200M1A101',
      'PRCTR' => '1200M1D001',
      'XREF1' => 'Documento Fiscal',
      'XREF3' => 'Nombre asociado al documento fiscal' 
    );
                                        
    $location_URL = 'http://MQADL360e9.mqa-bc.com:41021/sap/bc/srt/scs/sap/zws_contfinaf?sap-client=200';

    $wsdl = 'http://MQADL360e9.mqa-bc.com:41021/sap/bc/srt/wsdl/flv_10002A111AD1/bndg_url/sap/bc/srt/scs/sap/zws_contfinaf?sap-client=200';
                                          
    $usuario = 'Userin02';
    $pass = 'anicam2016';
                                                                                        
    $client = new SoapClient($wsdl, array(
      'location' => $location_URL,
      'uri'      => "",
      'trace'    => 1,
      'login'    => $usuario,
      'password' => $pass,
      'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP, 
      'encoding' => 'utf-8'
    ));
        
    echo "<script language='javascript'> alert('Ok'); </script>";
    die();                         
                                        
    $wsdl = "http://Userin02:anicam2016@mqadl360e9.mqa-bc.com:41021/sap/bc/srt/wsdl/flv_10002A111AD1/bndg_url/sap/bc/srt/scs/sap/zws_contfinaf?sap-client=200"; //url del servicio
    $clientOptions = array('login' => 'Userin02', 'password' => 'anicam2016');
    echo file_get_contents($wsdl);
        
    echo "<script language='javascript'> alert('Ok'); </script>";
        
    $client = new SoapClient($wsdl, array('login' => "Userin02", 'password' => "anicam2016", 'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP, 'encoding' => 'utf-8' ));
    die();
        
    $result = $client->Z_RFC_004_CONTFINA($parameters);
    //print_r($result);
        
    $soapURL = "http://Userin02:anicam2016@mqadl360e9.mqa-bc.com:41021/sap/bc/srt/wsdl/flv_10002A111AD1/bndg_url/sap/bc/srt/scs/sap/zws_contfinaf?sap-client=200"; //url del servicio        
    $soapParameters = Array('login' => "Userin02", 'password' => "anicam2016") ;
    $soapFunction = "Z_RFC_004_CONTFINA" ;
                        
    echo file_get_contents($soapURL);
    //die(); 
        
    $soapFunctionParameters = Array(
      'BLDAT' => '01112016',
      'BLART' => '90',
      'BUKRS' => '1200',
      'BUDAT' => '01112016',
      'WAERS' => 'COP',
      'XBLNR' => 'TEXTO DE CABECERA',
      'BKTXT' => 'TET DE CABECERA EN DOCUMENTO',
      'BSCHL' => '50',
      'KUNNR' => '0010000095',
      'LIFNR' => '0010001898',
      'HKONT' => '5140950101',
      'UMSKZ' => '',
      'WRBTR' => '120000000',
      'MWSKZ' => 'V3',
      'ZTERM' => 'K004',
      'VALUT' => '01112016',
      'ZFBDT' => '01112016',
      'ZUONR' => 'ASIGNACION',
      'SGTXT' => 'TEXTO POSICION',
      'KOSTL' => '1200M1A101',
      'PRCTR' => '1200M1D001',
      'XREF1' => 'Documento Fiscal',
      'XREF3' => 'Nombre asociado al documento fiscal'
    );
        
    $soapClient = new SoapClient($soapURL, $soapParameters);        
    echo "<script language='javascript'> alert('Ok'); </script>";
    $soapResult = $soapClient->__soapCall($soapFunction, $soapFunctionParameters) ;
        
    if(is_array($soapResult) && isset($soapResult['someFunctionResult'])) {
      // Process result.
    } else {
      // Unexpected result
      if(function_exists("debug_message")) {
        debug_message("Unexpected soapResult for {$soapFunction}: ".print_r($soapResult, TRUE)) ;
      }
    }
  }
	
  function listadoSizfra($arreglo) {
    $arreglo['datos'] = $this->datos->listadoSizfra($arreglo);
        
    //Instancia la clase Archivo
    $datosInterfaz = new Archivo();
    //Creamos el archivo
    $datosInterfaz->crear("integrado/_files/".$arreglo['nombreinterfaz'].".txt");
    
    $this->vista->listadoSizfra($arreglo,$datosInterfaz);
  }
   
  function enviarAdjunto($arreglo) {
		//Envía Mensaje de Correo Interfaz Sizfra
    $arreglo['mensajeTexto'] = $arreglo['mensaje']; 
		$arreglo['asunto_mail'] = $arreglo['asunto'];
    //Verifica internamente si hay archivos adjuntos
		$this->envioMail($arreglo);

		$this->consultaSizfra($arreglo);
  }
  
  function envioMail($arreglo) {
		$arreglo['remite'] = 'blogistic@grupobysoft.com';
		$remite = array('email' => $arreglo['remite'],'nombre' => $arreglo['remite']);
		$destino = array('email'  => $arreglo['destino'],'nombre' => $arreglo['destino']);				

		$mail = new EnvioMail();

    $mail->adjuntarArchivo($arreglo['adjunto']);
		$mail->cuerpo($arreglo['mensajeTexto']);
		$mail->cargarCabecera($destino, $remite, $arreglo['asunto_mail']);
		//Procedimiento de Envío de mail y validación de envío correcto
		$arreglo['info'] = $mail->enviarEmail() ? TRUE : FALSE;
		$this->vista->mostrarMensaje($arreglo);
	}
}
?>