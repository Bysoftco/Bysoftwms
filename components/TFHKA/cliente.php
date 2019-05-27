<?php 

$opciones = array('trace' => true,
'exceptions' => true);
// $wsdl = "http://service.com:8000/service.php?wsdl";
$wsdl = "http://localhost:51963/service1.svc?wsdl";
$cliente = new SoapClient($wsdl,$opciones);

$parametros = array(
    "boolValue" => true,
    "stringValue" => basename(__FILE__,".php")
);

try{
    $result = $cliente->GetDataUsingDataContract($parametros);
}catch(Exception $ex){
    print_r("<pre>".var_export($ex)."</pre>");
}



