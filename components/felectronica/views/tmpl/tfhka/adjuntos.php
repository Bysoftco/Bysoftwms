<?php
ini_set("memory_limit" ,"-1");
error_reporting(E_ERROR);
include_once "webservice_receptor.php";
$byteArr = array();

$urlWsAnexos = "http://demoemision.thefactoryhka.com.co/wsEmision/Service.svc?wsdl";
// $urlWsAnexos ="http://intdemo.dfacture.com.co/wsEmision/Service.svc?wsdl";
$arrContextOptions=array(
    "ssl"=>array( "verify_peer"=>false, 
    "verify_peer_name"=>false,
    'crypto_method' => STREAM_CRYPTO_METHOD_TLS_CLIENT,
    'allow_self_signed' => true
),
'https'=> array(
    'user_agent' => 'PHPSoapClient'
    )
);
$test = array(
    'verifyhost' => false,
    'ssl_method' => SOAP_SSL_METHOD_SSLv3,
    'soap_version' => SOAP_1_1,  
    'stream_context' => stream_context_create($arrContextOptions), 
    'exceptions' => true, 
    'trace' => true
);
$options = array('exceptions' => true, 'trace' => true);
    $contador = 0;
    foreach($_FILES as $ajdunto => $unArchivo){
        $handle = fopen($_FILES[$ajdunto]["tmp_name"],"rb");
        $conten = fread($handle,filesize($_FILES[$ajdunto]["tmp_name"]));

        $params = new uploadAttachment();
        $params->archivo = $conten;
        $params->tokenEmpresa = "798531dc2c6044f88737eae19c3844f89894ce59"; //SE DEBE SETEAR ESTE VALOR (SUMINSTRADO POR TFHKA)
        $params->tokenPassword = "f47bdf3384df404c8f893263b6496005438717b2"; //SE DEBE SETEAR ESTE VALOR (SUMINSTRADO POR TFHKA)
        $params->numeroDocumento = $_POST['consecutivoDocumento'];
        $params->nombre = substr(basename($_FILES[$ajdunto]['name']),0,-4);
        $params->formato = pathinfo($_FILES[$ajdunto]['name'], PATHINFO_EXTENSION);
        $params->tipo = "2";
        $params->enviar = ($contador + 1 == count($_FILES)) ? "1" : "0";
        $objServAnexos = new WebService();
        $resultado = $objServAnexos->EnviarAdjunto($urlWsAnexos,$options,$params);
        //print_r("<pre>".var_export($resultado,true)."</pre>");
        if($resultado["codigo"] == 200) { 
            print_r("Adjunto $ajdunto fue enviado con exito");
        }else{
            print_r("Ha ocurrido un error al enviar el anexo: " + json_encode($resultado));
        }
        $contador++;
        break;
    }