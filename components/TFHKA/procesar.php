<?php
//print("<pre>".var_export($_POST,true)."</pre>");
include_once "webservice_receptor.php";
error_reporting(E_ERROR);
$WebService = new WebService();
$options = array('exceptions' => true, 'trace' => true);
$params;
/**
* opciones :
* 2) Descargar pdf,
* 3) Descargar xml,
* 4) Enviar,
* 5) Enviar Correo,
* 6) Estado Documento,
* 7) Folios Restantes
*/

function codificacionUTF8($cadena = null){
    return mb_convert_encoding($cadena, "UTF-8", mb_detect_encoding($cadena, "UTF-8, ISO-8859-1, ISO-8859-15", true));
}

// recoge los valores enviados desde el formulario
$vclient = isset($_POST['cliente']) ? $_POST['cliente']:"";
$vfactura="";
//obtener el numero de documento a consultar
if(!empty($_POST["nro_docUsuario"]) and empty($_POST["nro_docUsuarioReporte"]) and empty($_POST["nro_docEmail"])){
    $vfactura = $_POST["nro_docUsuario"];
}else if(empty($_POST["nro_docUsuario"]) and !empty($_POST["nro_docUsuarioReporte"]) and empty($_POST["nro_docEmail"])){
    $vfactura = $_POST["nro_docUsuarioReporte"];
}else if(empty($_POST["nro_docUsuario"]) and empty($_POST["nro_docUsuarioReporte"]) and !empty($_POST["nro_docEmail"])){
    $vfactura = $_POST["nro_docEmail"];
}else{
    $vfactura="";
} 

$vimpgen = isset($_POST['impgen']) ? $_POST['impgen']:"";
$vdetalle =isset( $_POST['detalle']) ? $_POST['detalle']:"";
$mostrarModal = isset($_POST["mostrarModal"]) ? $_POST["mostrarModal"] : "no";
//La opcion viene desde el formulario
$opcion = intval($_POST['opcion']);
$todas = $vclient.",".$vfactura.",".$vimpgen.",".$vdetalle.",".$opcion;
$tituloModal = isset($_POST["accion"]) ? $_POST["accion"] : "";

$TokenEnterprise = "798531dc2c6044f88737eae19c3844f89894ce59"; //SE DEBE SETEAR ESTE VALOR (SUMINSTRADO POR TFHKA)
$TokenAutorizacion = "f47bdf3384df404c8f893263b6496005438717b2"; //SE DEBE SETEAR ESTE VALOR (SUMINSTRADO POR TFHKA)


$documento = $vfactura;
$params = array('tokenEmpresa' =>  $TokenEnterprise,'tokenPassword' =>$TokenAutorizacion,'documento' => $documento );

$resultado = "";
$tipoDescarga = ($opcion == 2) ? "pdf" : "xml";
$color="";
$rutaArchivo="";
switch($opcion){
    case 2: case 3: //descargas PDF o XML
    $resultado = $WebService->Descargas(WSDL,$options,$params,$tipoDescarga);
    if($resultado["codigo"]!=200) $color="red";
    if($resultado["codigo"]==200) $color="blue";
    $extension = ($opcion==2) ? ".pdf" : ".xml";
    $rutaArchivo = "reportes/".$documento.$extension;
    if($resultado["codigo"]==200 and ($opcion==2 or $opcion==3)) file_put_contents($rutaArchivo, base64_decode($resultado["documento"]));
    break;
    case 5://Enviar Correo
    $params['correo'] = trim($_POST["correo_usuario"]);
    $resultado = $WebService->enviocorreo(WSDL,$options,$params);
    if($resultado["codigo"]!=200) $color="red";
    if($resultado["codigo"]==200) $color="blue";
    break;
    // case 7: //FoliosRestantes
    // $param1= array('tokenEmpresa' =>  $TokenEnterprise,'tokenPassword' =>$TokenAutorizacion)
    // $resultado = $WebService->foliosrestantes(WSDL,$options,$param1);
    // if($resultado["codigo"]!=200) $color="red";
    // if($resultado["codigo"]==200) $color="blue";
    // break;
    case 6://Estado de Referencia
    $resultado = $WebService->getEstadoDocumento(WSDL,$options,$params);
    if($resultado["codigo"]!=200) $color="red";
    if($resultado["codigo"]==200) $color="blue";
    break;
    case 4://Generar Factura
    //se extraen los objetos enviados del formulario
    $fcliente = json_decode($_POST["cliente"]);
    $impgen = json_decode($_POST["impgen"]);
    $fdetfact = json_decode($_POST["detalle"]);
    $ffactura = json_decode($_POST["factura"]);
    
    //Datos del Cliente Completo
    $factura = new FacturaGeneral();
    //$factura->cliente = new Cliente();
    $factura->cliente->apellido = $fcliente->apellido; 
    $factura->cliente->departamento = $fcliente->departamento;
    $factura->cliente->ciudad = $fcliente->ciudad;
    $factura->cliente->direccion = $fcliente->direccion;
    $factura->cliente->email = $fcliente->correo;
    $factura->cliente->nombreRazonSocial  = ($fcliente->tipoPersona == 2) ? explode(" ",$fcliente->nombre)[0] : $fcliente->razonsocial;
    $factura->cliente->segundoNombre = ($fcliente->tipoPersona == 2) ? explode(" ",$fcliente->nombre)[1] : "";
    $factura->cliente->notificar = "SI";
    $factura->cliente->numeroDocumento = $fcliente->nroDoc;
    $factura->cliente->pais = "CO";
    $factura->cliente->regimen = $fcliente->regimen;
    $factura->cliente->tipoIdentificacion = $fcliente->tipoDoc;
    $factura->cliente->tipoPersona = $fcliente->tipoPersona;
    $factura->cliente->telefono = $fcliente->telefono;
    
    /**
    * Capturar el detalle de la factura
    */

    //Producto #1
    $factDetalle = new FacturaDetalle();
    	$factDetalle->cantidadUnidades = $fdetfact->cantidad;
    	$factDetalle->codigoProducto = $fdetfact->codigo;
    	$factDetalle->descripcion = $fdetfact->descripcion;
    	$factDetalle->descuento = (!is_null($fdetfact->descuento)) ? $fdetfact->descuento : "0.00";
    	$factDetalle->detalleAdicionalNombre = "";//Campo en blanco no requeridos
    	$factDetalle->detalleAdicionalValor = "";//Campo en blanco no requeridos
    	$factDetalle->impuestosDetalles[0] = $fdetfact->ivacalc;
    	$factDetalle->precioTotal = $fdetfact->montototal;
    	$factDetalle->precioTotalSinImpuestos = $fdetfact->totalSinImp;
    	$factDetalle->precioVentaUnitario = $fdetfact->preciounitario;
    	$factDetalle->seriales = "";
    	$factDetalle->unidadMedida = "UNIDAD";
    
    $objFactImp = new FacturaImpuestos();
    	$objFactImp->baseImponibleTOTALImp = $fdetfact->totalSinImp;
    	$objFactImp->codigoTOTALImp = $impgen->codigoTOTALImp;//01 para iva y 03 para ico
    	$objFactImp->controlInterno = "";//Aplica solo para RETENCIÓN EN LA FUENTE POR RENTA Subcodigos: 3 Compras | 5 Honorarios | 6 Servicios Generales | 7 Transporte de carga]
    	$objFactImp->porcentajeTOTALImp = $impgen->tasaimpuesto;
    	$objFactImp->valorTOTALImp = $impgen->totalimpuesto;//valor del impuesto a sumar a la base imponible (preciounitario * cantidad) * tasaimpuesto/100 =>Ejemplo (2500 * 2) * (19 / 100)
    	$factDetalle->impuestosDetalles[0] = $objFactImp;
        $factura->detalleDeFactura [0] = $factDetalle;

    //Producto #2

    $factDetalle1 = new FacturaDetalle();
    	$factDetalle1->cantidadUnidades = $fdetfact->cantidad;
    	$factDetalle1->codigoProducto = $fdetfact->codigo;
    	$factDetalle1->descripcion = $fdetfact->descripcion;
    	$factDetalle1->descuento = (!is_null($fdetfact->descuento)) ? $fdetfact->descuento : "0.00";
    	$factDetalle1->detalleAdicionalNombre = "";//Campo en blanco no requeridos
    	$factDetalle1->detalleAdicionalValor = "";//Campo en blanco no requeridos
    	$factDetalle1->impuestosDetalles[0] = $fdetfact->ivacalc;
    	$factDetalle1->precioTotal = $fdetfact->montototal;
    	$factDetalle1->precioTotalSinImpuestos = $fdetfact->totalSinImp;
    	$factDetalle1->precioVentaUnitario = $fdetfact->preciounitario;
    	$factDetalle1->seriales = "";
    	$factDetalle1->unidadMedida = "UNIDAD";
    
    $objFactImp = new FacturaImpuestos();
    	$objFactImp->baseImponibleTOTALImp = $fdetfact->totalSinImp;
    	$objFactImp->codigoTOTALImp = "03";//$impgen->codigoTOTALImp;//01 para iva y 03 para ico
    	$objFactImp->controlInterno = "";//Aplica solo para RETENCIÓN EN LA FUENTE POR RENTA Subcodigos: 3 Compras | 5 Honorarios | 6 Servicios Generales | 7 Transporte de carga]
    	$objFactImp->porcentajeTOTALImp = "5";
    	$objFactImp->valorTOTALImp = $impgen->totalimpuesto;//valor del impuesto a sumar a la base imponible (preciounitario * cantidad) * tasaimpuesto/100 =>Ejemplo (2500 * 2) * (19 / 100)
    	$factDetalle1->impuestosDetalles[0] = $objFactImp;
    
    
    
    array_push($factura->detalleDeFactura, $factDetalle1); //Incluir el factDetalle1 en Factura.detalleDeFactura

    //IMPUESTOS GENERALES
    
    $objImpGen = new FacturaImpuestos();
    	$objImpGen->baseImponibleTOTALImp = $fdetfact->totalSinImp;
    	$objImpGen->codigoTOTALImp = $impgen->codigoTOTALImp;//01 para iva y 03 para ico
    	$objImpGen->controlInterno = "";//Aplica solo para RETENCIÓN EN LA FUENTE POR RENTA Subcodigos: 3 Compras | 5 Honorarios | 6 Servicios Generales | 7 Transporte de carga]
    	$objImpGen->porcentajeTOTALImp = $impgen->tasaimpuesto;
   		$objImpGen->valorTOTALImp =$impgen->totalimpuesto;
   
   	$factura->impuestosGenerales[0]=$objImpGen;

   	$objImpGen1 = new FacturaImpuestos();
    	$objImpGen1->baseImponibleTOTALImp = $fdetfact->totalSinImp;
   	 	$objImpGen1->codigoTOTALImp = "03";//$impgen->codigoTOTALImp;//01 para iva y 03 para ico
    	$objImpGen1->controlInterno = "";
    	$objImpGen1->porcentajeTOTALImp ="5";
    	$objImpGen1->valorTOTALImp = $impgen->totalimpuesto;
	
	//$factura->impuestosGenerales[1]=$objImpGen1;
	array_push($factura->impuestosGenerales, $objImpGen1);

    $factura->medioPago = $ffactura->mediopago;
    $factura->moneda = $ffactura->moneda;
    $factura->rangoNumeracion = "BYSF-1"; //SE DEBE SETEAR ESTE VALOR (SUMINSTRADO POR TFHKA EN PRUEBAS, POR LA DIAN EN PRODUCCION)
    $factura->tipoDocumento="01";
    $factura->totalDescuentos = $factDetalle->descuento;
    $factura->totalSinImpuestos = "1100";//$factura->impuestosGenerales["baseImponibleTOTALImp"];
    $factura->importeTotal = $factDetalle->precioTotal;
    $factura->uuidDocumentoModificado ="";
    $factura->icoterms="";
	 $factura->propina="0.00";
	 $factura->cliente->subDivision = "";
    $factura->consecutivoDocumento = $ffactura->consecutivo;
    $factura->fechaEmision = date("Y-m-d H:i:s",strtotime($ffactura->fechaemision." ".$ffactura->horaemision));
    $factura->fechaVencimiento = date("Y-m-d H:i:s",strtotime ( '+1 day' , strtotime ( $ffactura->fechaemision." ".$ffactura->horaemision ) ));
    $factura->fechaEmisionDocumentoModificado = "";
	 
	 $params = array(
         'tokenEmpresa' =>  $TokenEnterprise,
         'tokenPassword' =>$TokenAutorizacion,
         'factura' => $factura ,
         'adjuntos' => "0");
	 //Enviar Objeto Factura
	 $resultado = $WebService->enviar(WSDL,$options,$params);
	 //capturar codigo de respuesta del WS del Autofact para dar respuesta al usuario
	if($resultado["codigo"]==200){
			print_r("La factura electrónica ha sido generada satisfactoriamente!!!");
			print_r("En breves momentos consulte la dirección de correo electronica suministrada");
	}else{
		if($resultado["codigo"]==117){
			print_r("La fecha u hora de emisión de la factura no debe ser mayor a la fecha del sistema: {$factura->Emision}");
		}else if($resultado["codigo"]==115){
			print_r("El número consecutivo de la factura ya se encuentra registrada en el sistema");
		}else{
			//Se muestra el resultado Completo para determinar donde se encuentra la falla
			print_r(var_export($resultado,true));
		}
	}
	//se sale del programa porque en el index.php se lee la respuesta obtenida cuando se envía la factura
    exit();
    break;
}

?>

<!DOCTYPE html>
<html>
<head>
<!--Import Google Icon Font-->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!--Import materialize.css-->
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css"  media="screen,projection"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!--Let browser know website is optimized for mobile-->
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta charset="utf-8">
<style>
body {
    display: flex;
    min-height: 100vh;
    flex-direction: column;
}

main {
    flex: 1 0 auto;
}
</style>
</head>
<body>
<nav class="blue lighten-2">
<div class="nav-grapper">
<a href="#!" class="brand-logo"><img src="hka/logo_the_factory.jpg" width="140px" alt=""></a>
</div>
</nav>
<main>
<div id="modal_box" class="modal modal-fixed-footer">
<div class="modal-content">
<nav class="<?=$color?> lighten-2">
<h4 class="center-align"><?=$tituloModal?></h4>
</nav>
<div class="" style="height:80%">
<p><?=$resultado["mensaje"]?></p>
<?php if($resultado["codigo"]==200){?>
    <?php if($opcion==2 or $opcion==3){?>
        <p >Si desea ver su Documento haga clic <a href="<?=$rutaArchivo?>" target="_blank">aqui</a></p>
        <?php }else if($opcion==5){?>
            <p>Consulte o Actualice su Bandeja de entrada o Buzon de Correo: <?php //trim($_POST["correo_usuario"]);?></p>
            <?php }else if($opcion==6) {?>
                <p ><?=$resultado["mensajeDocumento"]?></p>
                <p>Fecha del Documento: <?=date("d-m-Y H:i:s",strtotime($resultado["fechaDocumento"]))?></p>
                <p>Codigo Unico de la Factura: <?=$resultado["cufe"]?></p>
                <?php if(!empty($resultado["acuseComentario"])){?>
                    <p>Observaciones: <?=$resultado["acuseComentario"]?></p>
                <?php }?>
            <?php } ?>
        <?php }else if($resultado["codigo"]<200){?>
                        <p ><?=$resultado["mensajeDocumento"]?></p>
								<p >Revise Los datos del formulario Enviado</p>
								<p>
									<?php print_r("<pre>".var_export($factura,true)."</pre>");?>
								</p>
                        <?php }?>
                        </div>
                        </div>
                        <div class="modal-footer">
                        <a href="javascript:window.location.href='index.html'" class="modal-action modal-close waves-effect waves-red btn blue darken-3">Cerrar</a>
                        </div>
                        </div>
                        </main>
                        <footer class="page-footer blue darken-2">
                        <div class="row">
                        <div class="col s12">
                        <span class="white-text"><img class="z-depth-3" src="hka/logo_the_factory.jpg" width="96px" alt=""></span>
                        <span class="grey-text text-lighten-4 right">Demo SDK Facturación Electronica Colombia<br>Version: 0.0.0.3</span>
                        </div>
                        </div>        
                        </footer>
                        <!--Import jQuery Library-->
                        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                        <!--Import materialize.js-->
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
                        <!-- Start Modal Javascript -->
                        <script type="text/javascript">
                        $(document).ready(function(){
                            //$('#modal_box').modal('open');
                            $(".modal").modal();
                            <?php if($mostrarModal==="si") { ?>
                                $('#modal_box').modal("open");
                                <?php } ?>
                            });
                            </script>
                            <!-- End Moda Javascript -->
                            </body>
                            </html>