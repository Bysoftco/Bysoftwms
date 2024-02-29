<?php 

include_once "modelo.php";
define("WSDL","http://demoemision.thefactoryhka.com.co/ws/obj/v1.1/Service.svc?wsdl");
//define("WSDL","https://demoemision.thefactoryhka.com.co/ws/obj/v1.1/Service.svc?wsdl");
define("WSANEXO","http://demoemision.thefactoryhka.com.co/wsEmision/Service.svc?wsdl");

date_default_timezone_set("America/Bogota");
class WebService{
    /**
    * @author lrodriguez
    * Funcion que permite Cargar Certificado
    * @param string $wsdl
    * @param array $options
    * @param array $params
    * @return array $list
    */
    function loadCertificado($wsdl,$options,$params)
    {
        try {
            
            $soap = new SoapClient($wsdl, $options);
            $dat = $soap->CargarCertificado($params);
            $inter = $dat->CargarCertificadoResult;
            $list = (array) $inter;
            var_dump($list);
        }
        catch(Exception $e) {
            var_dump($soap->__getLastResponse());
        }
        return $list;
    }
    
    /**
    * Funcion para generar descargas en PDF o XML
    * @param string $wsdl
    * @param array $options
    * @param array $params
    * @return array $list
    */
    function Descargas($wsdl,$options,$params,$tipoDescarga){
        //print("<pre>".var_export(func_get_args(),true)."</pre>");
        try{
            $soap = new SoapClient($wsdl, $options);
            if($tipoDescarga=="pdf"){
                $dat = $soap->DescargaPDF($params);
                $inter = $dat->DescargaPDFResult;
            }else if($tipoDescarga=="xml"){
                $dat = $soap->DescargaXML($params);
                $inter = $dat->DescargaXMLResult;
            }
            $list = (array) $inter;
            //var_dump($list);
        } catch(Exception $e) {
            var_dump($soap->__getLastResponse());
        }
        return $list;
    } 
    // declaramos las funciones que se conectan al web service
    
    function enviar($wsdl,$options,$params)
    {
        try {
            set_time_limit(300);
            $soap = new SoapClient($wsdl, $options);
            $dat = $soap->Enviar($params);
            $inter = $dat->EnviarResult;
            $list = (array) $inter;
            //var_dump($list);
        }
        catch(Exception $e) {
            var_dump($soap->__getLastResponse());
        }
        return $list;
        echo $list;
    }
    
    function enviocorreo($wsdl,$options,$params)
    {
        try {
            $soap = new SoapClient($wsdl, $options);
            $dat = $soap->EnvioCorreo($params);
            $inter = $dat->EnvioCorreoResult;
            $list = (array) $inter;
            //var_dump($list);
        }
        catch(Exception $e) {
            $list = (array) $soap->__getLastResponse();
        }
        return $list;
    }
    
    
    function getEstadoDocumento($wsdl,$options,$params)
    {
        try {
            $soap = new SoapClient($wsdl, $options);
            $dat = $soap->EstadoDocumento($params);
            $inter = $dat->EstadoDocumentoResult;
            $list = (array) $inter;
            // var_dump($list);
        }
        catch(Exception $e) {
            var_dump($soap->__getLastResponse());
        }
        return $list;
    }
    
    function foliosrestantes($wsdl,$options,$params){
        $list;
        try {
            $soap = new SoapClient($wsdl, $options);
            $dat = $soap->FoliosRestantes($params);
            $inter = $dat->FoliosRestantesResult;
            $list = (array) $inter; 
            var_dump($list); 
        }
        catch(Exception $e) {
            var_dump($soap->__getLastResponse());
        }
        return $list;
    }
    
    function estructura($wsdl,$options,$params){
        $list;
        try {
            $soap = new SoapClient($wsdl, $options);
            $tipos = $soap->__getTypes();
            var_dump($tipos);
            
        }
        catch(Exception $e) {
            var_dump($soap->__getLastResponse());
        }
        return $list;
    }
    
    function validarxml($wsdl,$options,$params){
        $list;
        try {
            $soap = new SoapClient($wsdl, $options);
            $dat = $soap->ValidarXml($params);
            $inter = $dat->ValidarXmlResult;
            $list = (array) $inter;  
        }
        catch(Exception $e) {
            var_dump($soap->__getLastResponse());
        }
        return $list;
    }

    function EnviarAdjunto($wsdl,$options,$params){
        $list;
        try {
            $soap = new SoapClient(WSANEXO, array('exceptions' => true, 'trace' => true));
            $dat = $soap->EnviarArchivo(array("data" => $params));
            $inter = $dat->EnviarArchivoResult;
            $list = (array) $inter;  
        }
        catch(Exception $e) {
            var_dump($soap->__getLastResponse());
        }
        return $list;
    }
}








