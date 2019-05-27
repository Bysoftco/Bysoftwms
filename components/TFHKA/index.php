<?php date_default_timezone_set("America/Bogota");?>
<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css"> -->
    <!-- <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css"  media="screen,projection"/> -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.24.3/sweetalert2.min.css">
    
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
        
        .tabs .tab a
        {
            font-weight: 700; /*para cambiar el grosor de la letra*/
            color: #2196f3; /*cambiamos el color de la letra*/
        }
        .tabs .tab a:hover /*Aca aplicamos los estilos que queremos que se ejecuten en el hover*/
        {
            color: #8bccff;
        }
        ul.tabs li.indicator /*para cambiar los estilos de la linea que indica cuando la pestaña esta activa*/
        {
            background-color: #2196f3; /*cambiamos el color*/
            height: 3px; /* tambien cambiamos el tamaño si queremos_*/
            
        }
        
        .tabs .tab a.active /*_tmbien podemos de cambiar el estilo de el elemento activo*/
        {
            font-weight: 700;
            color: #063ceb;
        }
    </style>
</head>

<body>
    <nav class="blue lighten-2">
        <div class="nav-grapper">
            <a href="#!" class=""><img src="hka/tfhka_col_.png" width="140px" alt=""></a>
        </div>
    </nav>
    <br>
    <main class="container">
        <div class="row" style="margin-top:1rem;">
            <div class="col s12">
                <div class="valign-wrapper" style="justify-content: center;"><span class="flow-text"><img src="hka/tfhka_col_.png" width="192px"  alt="TFHKA DEMO SDK"></span> <img src="colombia.png" width="48px" class="responsive-img" alt="TFHKA DEMO SDK FELCO"></div>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card z-depth-2">
                    <div class="card-content">
                        <div class="row">
                            <div class="col s12">
                                <ul id="tab" class="tabs">
                                    <li class="tab col s4"><a id="tab_1" href="#frmCliente"><i class="material-icons">account_box</i>Cliente</a></li>
                                    <li class="tab col s4"><a id="tab_2" href="#frmFactura"><i class="material-icons">assignment</i>Factura</a></li>
                                    <li class="tab col s4"><a id="tab_3" href="#frmReporte"><i class="material-icons">print</i>Reportes</a></li>
                                </ul>
                            </div>
                            <form id="frmFel" name="frmFel" enctype="multipart/form-data" autocomplete='on'>
                                <input type="hidden" name="accion">
                                <input type="hidden" name="opcion">
                                <input type="hidden" name="mostrarModal">
                                <div id="frmCliente" class="col s12">
                                    <!--Tipo de Persona-->
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <span>Tipo de Persona</span>
                                            <span>
                                                <label>
                                                    <input name="tipoPersona" type="radio" id="natural" value="2" checked />
                                                    <span>Natural</span>
                                                </label>
                                            </span>
                                            <span>
                                                <label>
                                                    <input name="tipoPersona" type="radio" id="juridico" value="3" />
                                                    <span>Juridica</span>
                                                </label>
                                            </span>
                                        </div>
                                        <div class="input-field col s6">
                                            <select name="tipoDoc" id="tipoDoc">
                                                <option value="0" selected disabled>Seleccione una opción</option>
                                                <option value="13">Cédula de Ciudadania</option>
                                                <option value="22">Cédula de Extranjería</option>
                                                <option value="42">Documento de Identificación</option>
                                                <option value="31">NIT</option>
                                                <option value="41" selected>Pasaporte</option>
                                                <option value="11">Registro Civil</option>
                                                <option value="12">Tarjeta de Identidad</option>
                                                <option value="21">Tarjeta de Extranjería</option>
                                            </select>
                                            <label for="tipoDoc">Tipo de Identificación</label>
                                        </div>
                                    </div>
                                    <!--/Tipo de Persona-->
                                    <!--Nombre o razon social y numero de Documento-->
                                    <div class="row">
                                        <div id="div_razonsocial" class="col s8" style="display:none">
                                            <div class="row">
                                                <div class="col s12 input-field">
                                                    <input type="text" name="razonsocial" id="razonsocial">
                                                    <label for="razonsocial">Razon Social</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="div_nombrecompleto" class="col s8 input-field">
                                            <div class="row">
                                                <div class="col s6 input-field">
                                                    <input type="text" name="nombre" id="nombre" value="Lester">
                                                    <label for="nombre">Nombre</label>
                                                </div>
                                                <div class="col s6 input-field">
                                                    <input type="text" name="apellido" id="apellido" value='Rodriguez'>
                                                    <label for="apellido">Apellido</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s4">
                                            <div class="row">
                                                <div class="col s12 input-field">
                                                    <input type="text" name="nroDoc" id="nroDoc" value="068005098">
                                                    <label for="nroDoc">Nro. de Documento</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/Nombre o razon social y numero de Documento-->
                                    <!--Direccion y Correo electronico-->
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input type="text" name="direccion" id="direccion" value='Cr. 71 #78-46'>
                                            <label for="">Direcci&oacute;n</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input type="email" name="correo" id="correo" value="" onchange="return validarDireccionEmail(event);">
                                            <label for="">Correo Electronico</label>
                                        </div>
                                    </div>
                                    <!--/Direccion y Correo electronico-->
                                    <!--Telefono y Regimen-->
                                    <div class="row">
                                        <div class="col s6 input-field">
                                            <input type="text" name="telefono" id="telefono" value='3197218214'>
                                            <label for="telefono">Tel&eacute;fono</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <span>Regimen</span>
                                            <span>
                                                <label>
                                                    <input name="regimen" type="radio" id="comun" value="2" checked />
                                                    <span>Com&uacute;n</span>
                                                </label>
                                            </span>
                                            <span>
                                                <label>
                                                    <input name="regimen" type="radio" id="simplificado" value="0" />
                                                    <span>Simplificado</span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <!--/Telefono y regimen-->
                                    <!--Departamento y Ciudad-->
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input type="text" name="departamento" id="departamento">
                                            <label for="departamento">Departamento</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input type="text" name="ciudad" id="ciudad">
                                            <label for="ciudad">Ciudad</label>
                                        </div>
                                    </div>
                                    <!--/Departamento y Ciudad-->
                                    <div class="row">
                                        <div class="col s12">
                                            <a class="btn blue lighten-2 right tooltipped" onclick="javascript:cambiarTab('frmFactura');" data-position="bottom" data-delay="50" data-tooltip="Continuar"><i class="material-icons">chevron_right</i></a>
                                        </div>
                                    </div>
                                </div>
                                <div id="frmFactura" class="col s12">
                                    <!--Codigo y Base Imponible-->
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input id="codigo" name="codigo" type="text" value='ASDF505'>
                                            <label for="codigo">Codigo</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input id="descripcion" name="descripcion" type="text" value='LAPICERO'>
                                            <label for="descripcion">Descripcion</label>
                                        </div>
                                    </div>
                                    <!--/Codigo y Base Imponible-->
                                    <!--Decripcion y Total impuestos-->
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input id="preciounitario" name="preciounitario" type="text" onKeyPress="return soloNumeros(event);">
                                            <label for="preciounitario">Precio Venta Unitario</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input id="cantidad" name="cantidad" onkeyup="montoTotal();" onKeyPress="return soloNumeros(event);" type="text">
                                            <label for="cantidad">Cantidades</label>
                                        </div>
                                    </div>
                                    <!--/Descripcion y Total Impuesto-->
                                    <!--Cantidad y Fecha de emision-->
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input id="baseimponible" name="baseimponible" onkeypress="return soloNumeros(event);" type="text">
                                            <label for="baseimponible">Base Imponible</label>
                                        </div>
                                        <div class="col s6">
                                            <div class="row">
                                                <div class="col s12">
                                                    <div class="row">
                                                        <div class="input-field  col s6">
                                                            <input id="fechaemision" name="fechaemision" class="datepicker" type="text">
                                                            <label for="fechaemision">Fecha de Emision</label>
                                                        </div>
                                                        <div class="input-field  col s6">
                                                            <input id="horaemision" name="horaemision" class="timepicker" type="text">
                                                            <label for="horaemision">Hora de Emision</label>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/Cantidad y Fecha de Emision-->
                                    <!--Precio Unitario y Medio de Pago-->
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <span>Tipo de Impuesto</span><br><br>
                                            <p>
                                                <span>
                                                    <label>
                                                        <input name="tipoImpuesto" type="radio" id="iva" value="01" checked />
                                                        <span>IVA</span>
                                                    </label>
                                                </span>
                                                <span>
                                                    <label>
                                                        <input name="tipoImpuesto" type="radio" id="ico" value="03" />
                                                        <span>ICO</span>
                                                    </label>
                                                </span>
                                            </p>
                                        </div>
                                        <div class="input-field col s6">
                                            <span>Tipo de Impuesto</span><br><br>
                                            <p class="left-align">
                                                <label>
                                                    <input name="impuesto" type="radio" id="excluido" data-valor="0" value="5" />
                                                    <span>Excluido 0%</span>
                                                </label>
                                            </p>
                                            <p class="left-align">
                                                <label>
                                                    <input name="impuesto" type="radio" id="exento" data-valor="0" value="1" />
                                                    <span>Exento 0%</span>
                                                </label>
                                            </p>
                                            <p class="left-align">
                                                <label>
                                                    <input name="impuesto" type="radio" id="dif_5" data-valor="0.05" value="3" />
                                                    <span>Tasa Diferencial 5%</span>
                                                </label>
                                            </p>
                                            <p class="left-align">
                                                <label>
                                                    <input name="impuesto" type="radio" id="dif_10" data-valor="0.1" value="4" />
                                                    <span>Tasa Diferencial 10%</span>
                                                </label>
                                            </p>
                                            <p class="left-align">
                                                <label>
                                                    <input name="impuesto" type="radio" id="general" value="6" data-valor="0.19" checked/>
                                                    <span>General 19%</span>
                                                </label>
                                            </p>
                                        </div>
                                    </div>
                                    <!--/Precio Unitario y Medio de Pago-->
                                    <!--Moneda y Tipo de Impuesto-->
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input id="totalimpuesto" name="totalimpuesto" type="text">
                                            <label for="totalimpuesto">Total Impuestos</label>
                                        </div> 
                                        <div class="input-field col s6">
                                            <select name="moneda" id="moneda" disabled>
                                                <option value="COP" selected>Peso Colombiano</option>
                                                <option value="USD">Dolar Estado Unidense</option>
                                                <option value="VEF">Bolivares Fuertes</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--/Moneda y Tipo de Impuesto-->
                                    <!--Porcentaje de Impuesto y Monto total-->
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <span>Forma de Pago</span><br><br>
                                            <span>
                                                <label>
                                                    <input name="mediopago" type="radio" id="cheque" value="20" />
                                                    <span>Cheque</span>
                                                </label>
                                            </span>
                                            <span>
                                                <label>
                                                    <input name="mediopago" type="radio" id="deposito" value="42" />
                                                    <span>Deposito</span>
                                                </label>
                                            </span>
                                            <span class="left-align">
                                                <label>
                                                    <input name="mediopago" type="radio" id="efectivo" value="10" checked/>
                                                    <span>Efectivo</span>
                                                </label>
                                            </span>
                                            <span class="left-align">
                                                <label>
                                                    <input name="mediopago" type="radio" id="transferencia" value="41" />
                                                    <span>Transferencia</span>
                                                </label>
                                            </span>
                                        </div>
                                        <div class="input-field col s6">
                                            <input id="montototal" name="montototal" type="text">
                                            <label for="montototal">Monto Total</label>
                                        </div>
                                    </div>
                                    <!-- Fila para agregar adjuntos -->
                                    <div class="row">
                                        <div class="col s12 right-align">
                                            <button type='button' class="btn-floating blue lighten-2 tooltipped" onclick="agregarAdjuntos('#adjuntos')" data-position="bottom" data-delay="50" data-tooltip="Añadir mas archivos"><i class="material-icons">add</i></button>
                                        </div>
                                    </div>
                                    <div class="row" id="adjuntos">
                                        <div class="file-field input-field">
                                            <div class="btn blue darken-2">
                                                <span><i class="material-icons">attach_file</i> Seleccione</span>
                                                <input type="file" name="adjunto" id="adjunto">
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input class="file-path validate" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <!--/Porcntaje de impuesto y monto total-->
                                    <div class="row">
                                        <div class="col input-field s12 center-align">
                                            <button id="btn_editarRegistro" type="button" onclick="procesarFormulario(this)" class="btn blue lighten-2 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Editar">Editar <i class="material-icons">edit</i></button>
                                            <button id="btn_enviarDatos" type="button" onclick="procesarFormulario(this)" class="btn blue darken-1 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Enviar Datos">Enviar <i class="material-icons">send</i></button>
                                        </div>
                                    </div>
                                </div>
                                <div id="frmReporte" class="col s12">
                                    <div class="row">
                                        <div class="col s6">
                                            <h6>Estado de Documento</h6>
                                            <div class="input-field">
                                                <input type="text" name="nro_docUsuario" id="nro_docUsuario">
                                                <label for="nro_docUsuario">Consultar Documento</label>
                                                <button type="button" id="btn_buscarDocumento" onclick="procesarFormulario(this)" class="btn blue lighten-1"><i class="material-icons">search</i></button>
                                            </div>
                                        </div>
                                        <div class="col s6">
                                            <h6>Folios Restantes</h6>
                                            <div class="input-field">
                                                <label for="">Buscar Folios</label>
                                                <button type="button" id="btn_buscarFolios" onclick="procesarFormulario(this)" class="btn blue lighten-1" style="margin-left: 120px"><i class="material-icons">search</i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s6">
                                            <h6>Enviar Correo</h6>
                                            <div class="input-field">
                                                <input type="text" id="nro_docEmail" name="nro_docEmail">
                                                <label for="">Nro de Documento</label>
                                            </div>
                                            <div class="input-field">
                                                <input type="email" id="correo_usuario" name="correo_usuario">
                                                <label for="">Email</label>
                                            </div>
                                            <div class="input-field">
                                                <button type="button" id="btn_enviarEmail" onclick="procesarFormulario(this)" class="btn blue lighten-1"><i class="material-icons">email</i></button>
                                            </div>
                                        </div>
                                        <div class="col s6">
                                            <h6>Descarga</h6>
                                            <div class="input-field">
                                                <input type="text" id="nro_docUsuarioReporte" name="nro_docUsuarioReporte">
                                                <label for="">Nro de Documento</label>
                                            </div>
                                            <div class="input-field">
                                                <span>
                                                    <label>
                                                        <input name="tipoFormatoRpt" type="radio" id="pdf" value="2" checked />
                                                        <span><img src="pdf.png" alt="" width="48px"></span>
                                                    </label>
                                                </span>
                                                <span>
                                                    <label>
                                                        <input name="tipoFormatoRpt" type="radio" id="xml" value="3" />
                                                        <span><img src="xml.png" alt="" width="48px"></span>
                                                    </label>
                                                </span>
                                            </div>
                                            <div class="input-field">
                                                <button type="button" id="btn_buscarReporte" onclick="procesarFormulario(this)" class="btn blue lighten-1"><i class="material-icons">cloud_download</i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="page-footer blue darken-2">
    <div class="row">
        <div class="col s12">
            <span class="white-text"><img class="z-depth-3" src="hka/tfhka_col_.png" width="96px" alt=""></span>
            <span class="grey-text text-lighten-4 right">Demo SDK Facturación Electronica Colombia<br>Version: 0.0.0.3</span>
        </div>
    </div>
    
</footer>

<!--JavaScript at end of body for optimized loading-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script> -->
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.24.3/sweetalert2.min.js"></script>

<script>
    $(document).ready(function(){
        
        $("input[name='tipoPersona']").click(function(){
            //mostrar Razon social o nombre completo segun el radiobuttin seleccionado
            var id_radiobutton = $(this).attr('id');
            if(id_radiobutton=="natural" && $(this).prop("checked")){
                $("#div_razonsocial").hide('500');
                $("#div_nombrecompleto").show('1000');
            }else{
                $("#div_nombrecompleto").hide('500');
                $("#div_razonsocial").show('1000');
            }
        });
        $('.tooltipped').tooltip({delay: 50});
        $( "#departamento" ).autocomplete({
            data: {
                "Amazonas": null,
                "Antioquia": null,
                "Arauca": null,
                "Atlántico": null,
                "Bogotá": null,
                "Bolívar": null,
                "Boyacá": null,
                "Caldas": null,
                "Caquetá": null,
                "Casanare": null,
                "Cauca": null,
                "Cesar": null,
                "Chocó": null,
                "Córdoba": null,
                "Cundinamarca": null,
                "Guainía": null,
                //Fin primera mitad de 33 departamentos
                "Guaviare": null,
                "Huila": null,
                "La Guajira": null,
                "Magdalena": null,
                "Meta": null,
                "Nariño": null,
                "Norte de Santander": null,
                "Putumayo": null,
                "Quindío": null,
                "Risaralda": null,
                "San Andrés y Providencia": null,
                "Santander": null,
                "Sucre": null,
                "Tolima": null,
                "Valle del Cauca": null,
                "Vaupés": null,
                "Vichada": null,
            },
        });
        $( "#ciudad" ).autocomplete({
            data: {
                "Bogotá": null,
                "Medellín": null,
                "Cali": null,
                "Barranquilla": null,
                "Cartagena de Indias": null,
                "Soledad": null,
                "Cúcuta": null,
                "Soacha": null,
                "Ibagué": null,
                "Bucaramanga": null,
                "Villavicencio": null,
                "Santa Marta": null,
                "Bello": null,
                "Valledupar": null,
                "Pereira": null,
                "Buenaventura": null,
                "Pasto": null,
                "Manizales": null,
                "Montería": null,
                "Neiva": null,
                
            },
        });
        
        setTimeout(function(){
            $('ul.tabs').tabs();
        },1000);
        $("select").formSelect();
        
    });
    //Cierre del document ready
    $(".datepicker").datepicker({
        format:"dd-mm-yyyy"
    });
    $(".timepicker").timepicker({
        twelveHour:false
    }).on('change', function() {
        let receivedVal = $(this).val();
        $(this).val(receivedVal + ":00");
    });
    
    function alerta(titulo,mensaje,tipo,idElemento){
        if(typeof tipo!== undefined){
            swal({
                imageUrl: 'hka/logo_the_factory.jpg',
                imageWidth: 250,
                imageHeight: 125,
                position: 'top-end',
                type: tipo,
                //title: titulo,
                html: mensaje,
                backdrop: "rgba(0,0,0,0.7) center left no-repeat",
                onClose: () => {
                    if(idElemento !==undefined ) document.getElementById(idElemento).focus();
                }
            });
        }else{
            swal({
                imageUrl: 'hka/logo_the_factory.jpg',
                imageWidth: 250,
                imageHeight: 125,
                position: 'top-end',
                //type: tipo,
                //title: titulo,
                text: mensaje,
                backdrop: "rgba(0,0,0,0.7) center left no-repeat",
                onClose: () => {
                    if(idElemento !==undefined ) document.getElementById(idElemento).focus();
                }
            });
        }
    }
    let timerInterval;
    function alerTimer(titulo,mensaje,tipo,idElemento){
        swal({
            imageUrl: 'hka/logo_the_factory.jpg',
            imageWidth: 250,
            imageHeight: 125,
            position: 'top-end',
            type: tipo,
            title: titulo,
            html: mensaje + ' <strong></strong>.',
            timer: 3000,
            onOpen: () => {
                swal.showLoading()
                timerInterval = setInterval(() => {
                swal.getContent().querySelector('strong')
                    .textContent = swal.getTimerLeft()
                }, 100)
            },
            onClose: () => {
                clearInterval(timerInterval);
                if(idElemento !==undefined ) document.getElementById(idElemento).focus();
            }
        });
    }
    function cambiarTab(ID){
        var tab = document.getElementById('tab');
        var instance = M.Tabs.getInstance(tab);
        instance.select(ID);
    }
    
    function procesarFormulario(boton){
        var formulario = document.getElementById("frmFel");
        //alert(JSON.stringify(cliente)+"\n\n"+JSON.stringify(factura));
        //alerta("DFACTURE",JSON.stringify(cliente)+"\n\n"+JSON.stringify(factura),"info");
        var tipoEnvio = "";
        var accion ="";
        var mostrarModal = "";
        var enviarFormulario = false;
        switch (boton.id) {
            case "btn_buscarDocumento":
            case "btn_buscarFolios":
            formulario.opcion.value=7;
            break;
            case "btn_buscarReporte":
            tipoEnvio="GET";
            mostrarModal = "si";  
            if(boton.id== "btn_buscarDocumento") accion="Estado de Documento"; 
            if(boton.id== "btn_buscarFolios") accion="Estado de Portafolios";
            if(boton.id== "btn_buscarReporte") accion="Reporte";
            if(boton.id=="btn_buscarDocumento"){
                formulario.opcion.value = 6;
            }else{
                formulario.opcion.value = (document.getElementById("pdf").checked)  ? document.getElementById("pdf").value : document.getElementById("xml").value ;
            } 
            //alert(formulario.opcion.value);
            //if(boton.id== "btn_buscarReporte") mostrarModal ="no";
            break;  
            case "btn_enviarEmail":
            case "btn_enviarDatos":
            tipoEnvio="POST";
            if(boton.id== "btn_enviarEmail") accion="Correo";
            if(boton.id== "btn_enviarEmail") formulario.opcion.value=5;
            if(boton.id== "btn_enviarEmail") mostrarModal="si";
            
            if(boton.id== "btn_enviarDatos") accion="Demo THFKA FEL Colombia";
            if(boton.id== "btn_enviarDatos") mostrarModal="no";
            if(boton.id== "btn_enviarDatos") formulario.opcion.value=4;
            if(formulario.opcion.value==4){
                var frmCliente = $(formulario).find("#frmCliente");//procesar solo los datos del cliente;
                var frmFactura = $(formulario).find("#frmFactura");//procesar solo los datos del factura;
                /*
                Recorrer Los datos del Cliente
                */
                var cliente = new Object(); factura = new Object(); impugen = new Object(); detfac = new Object();
                
                frmCliente.find(":input").each(function(){//recorrer todos los elementos de entrada de la seccion del cliente
                    var elemento = $(this);
                    if(elemento.attr('type')!=="button"){//validar que el tipo de entrada no sean botones
                        if(elemento.attr('type')==="radio"){
                            var radios = document.getElementsByName($(this).attr('name'));
                            for(var i = 0; i< radios.length; i++){
                                if(radios[i].checked){
                                    cliente[elemento.attr('name')] = radios[i].value;
                                }
                            }
                        }else{
                            if(elemento.attr('id') === undefined && elemento.attr('name')===undefined){
                                //alert(elemento.attr('id')+"<-->"+elemento.attr('name')+"<-->"+elemento.val());
                                cliente['tipoIdentificacion'] = elemento.val();
                            }else{
                                cliente[elemento.attr('name')] = elemento.val();//construir el objeto cliente

                            }
                        }

                    }
                });
                //alert(JSON.stringify(cliente));
                frmFactura.find(":input").each(function(){//recorrer todos los elementos de entrada de la seccion de la factura
                    var elemento = $(this);//capturar todos los elementos de entrada de la seccion factura
                    if(elemento.attr("type")==="radio"){
                        var radios = document.getElementsByName($(this).attr('name'));
                        for (var i = 0; i < radios.length; i++) {
                            if(radios[i].checked){
                                if(elemento.attr('name')==="impuesto"){
                                    factura[elemento.attr('name')] = $(radios[i]).attr('data-valor');
                                }else{
                                    factura[elemento.attr('name')] = radios[i].value;
                                }
                            }
                        }
                    }else{
                        if(elemento.prop('tagName').toLowerCase()!=="button"){//validar que el tipo de entrada no sean botones
                            factura[elemento.attr('name')] = elemento.val();//construir el objeto factura
                        }
                    }
                });
                
                factura.consecutivo = prompt("indique el numero de consecutivo de factura");
                /*
                Definir el Detalle de Impuesto
                */
                impugen.baseimponible = factura.baseimponible;
                impugen.codigoTOTALImp = '01';
                impugen.tasaimpuesto = 19.00;
                impugen.totalimpuesto = factura.totalimpuesto;
                /*
                Definir Detalle de Factura
                */
                detfac.cantidad = factura.cantidad;
                detfac.baseimponible = factura.baseimponible;
                detfac.codigo = factura.codigo;
                detfac.descripcion = factura.descripcion;
                detfac.descuento = "0.00";
                detfac.totalSinImp = factura.baseimponible;
                detfac.preciounitario = factura.preciounitario;
                detfac.rangoNumeracion = "PAGF-1";
                detfac.baseimponible = factura.baseimponible;
                detfac.tipoImpuesto = factura.tipoImpuesto;
                detfac.tasaimpuesto = impugen.tasaimpuesto;
                detfac.ivacalc = (parseFloat(factura.cantidad * factura.preciounitario) - parseFloat((factura.cantidad * factura.preciounitario) / (1 + (impugen.tasaimpuesto / 100)))).toFixed(2);
                detfac.montototal = factura.montototal;
                //detfac.serial = factura.serial; //campo tentativo no esta en el SDK de C#
                
            }
            break;
        }
        formulario.accion.value = accion;
        formulario.mostrarModal.value = mostrarModal;
        formulario.method = tipoEnvio;
        formulario.action="procesar.php";
        formulario.method="post";
        if(boton.id=="btn_buscarReporte"){
            if(formulario.nro_docUsuarioReporte.value.trim().length==0){
                alerta("DFacture","Debe indicar el numero de Documento a Consultar","error","nro_docUsuarioReporte");
                // alert("Debe Indicar el Número de Reporte a Consultar");
                //formulario.nro_docUsuarioReporte.focus();
            }else{
                enviarFormulario = true;
            }
        }else if(boton.id=="btn_buscarDocumento"){
            if(formulario.nro_docUsuario.value.trim().length==0){
                alerta("DFacture","Debe indicar el numero de Documento a Consultar","error","nro_docUsuario");
            }else{
                enviarFormulario = true;
            }
        }else if(boton.id=="btn_enviarEmail"){
            if(formulario.nro_docEmail.value.trim().length>0 && formulario.correo_usuario.value.trim().length>0){
                enviarFormulario = true;
            }else{
                if(formulario.nro_docEmail.value.trim().length==0 && formulario.correo_usuario.value.trim().length>0){
                    alerta("DFacture","Debe indicar el numero de Documento a Consultar","error","nro_docEmail");
                }else{
                    alerta("DFacture","Debe indicar un correo electronico","error","correo_usuario");
                }
            }
        }else if(boton.id=="btn_enviarDatos"){
            enviarFormulario = true;
        }
        if(enviarFormulario && formulario.opcion.value!=4) formulario.submit();
        if(enviarFormulario && formulario.opcion.value==4){
            alert($(formulario).find("input[type=file]").length);
            // alerta("FEL",document.frmFel.files.length,"info");
            var form = new FormData();
            if($(formulario).find("input[type=file]").length >=1){
                $(formulario).find("input[type=file]").each(function(){
                    form.append("adjunto" + $(this).index(), this.files[0]);
                });
                form.append("consecutivoDocumento","PAGF"+factura.consecutivo);
                $.ajax({
                    url:"procesar.php",
                    type:"POST",
                    data:{
                      cliente:JSON.stringify(cliente),
                      factura:JSON.stringify(factura),
                      impgen:JSON.stringify(impugen),
                      detalle:JSON.stringify(detfac), 
                      opcion : formulario.opcion.value},
                    beforeSend:function(){
                        alerta("DFacturacion","Aguarde un Momento mientras enviamos su informacion","info");
                    },
                    success:function(respuesta){
                        setTimeout(function(){
                            alerta("TFHKA FEL - CO",respuesta,"success");
                            setTimeout( () => {
                                $.ajax({
                                    url:"adjuntos.php",
                                    type:"POST",
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data : form,
                                    beforeSend:() => {
                                        alerTimer("TFHKA FEL - CO","Aguarde un Momento mientras enviamos los adjuntos","info");
                                    },
                                    success:function(respuesta){
                                        setTimeout(function(){alerta("TFHKA FEL - CO",respuesta,"success");},2000);
                                    }
                                });                
                            }, 7000);
                        },500);
                    }
                })
                
            }
        } 
    }
    
    function montoTotal(){
        var preciounitario = document.getElementById("preciounitario");
        var cantidad = document.getElementById("cantidad");
        var montoTotal =document.getElementById('montototal');
        var totalimpuesto = document.getElementById("totalimpuesto");
        var impuestos = document.getElementsByName("impuesto");
        var impuestoAplicado = 0;
        if(cantidad.value!==""){
            for(var i = 0; i<impuestos.length; i++){
                if(impuestos[i].checked){
                    impuestoAplicado = impuestos[i].getAttribute("data-valor");
                }
            }
            let baseimponible = parseFloat(parseFloat(preciounitario.value) * parseInt(cantidad.value)).toFixed(2);
            let totalImpuesto = parseFloat(impuestoAplicado) * parseFloat(baseimponible);
            montoTotal.value = parseFloat(parseFloat(baseimponible) + parseFloat(totalImpuesto)).toFixed(2);
            totalimpuesto.value=totalImpuesto;
            document.frmFel.baseimponible.value = parseFloat(baseimponible).toFixed(2);
            montoTotal.focus();
            montoTotal.blur();
            totalimpuesto.focus();
            totalimpuesto.blur();
            document.frmFel.baseimponible.focus();
            document.frmFel.baseimponible.blur();
        }else{
            alerta("DFACTURE","Debe indicar un monto valido","error","cantidad");
        }
    }
    
    function soloNumeros(e){
        var tecla = (document.all) ? e.keyCode : e.which;
        //Tecla de retroceso para borrar, siempre la permite
        if (tecla==8){
            return true;
        }
        patron =/[0-9]/;
        //patron = '^[0-9]+([,][0-9]+)?$';
        tecla_final = String.fromCharCode(tecla);
        if(!patron.test(tecla_final)) alerta("DFACTURE","Este campo solo acepta numeros","error",e.target.id);
        return patron.test(tecla_final);
    }

    function getValueSelectMD(idselect){
        var select = document.getElementById(idselect);
        var instance = M.FormSelect.getInstance(select);
        return instance.getSelectedValues();
    }

    function validarDireccionEmail(e){
        var valor = e.target.value.trim();
        var patron = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
        if(!patron.test(valor)) alerta("DFACTURE","Debe Indicar una direccion de correo valida","error",e.target.id);
        return patron.test(valor);
    }

    function agregarAdjuntos(seccion){
        if($(".file-field").length >= 1){
             var adjunto = "adjunto" + parseInt($(".file-field").length + 1);
             var html = "<div id='div_"+adjunto+"' class=\"file-field input-field\">";
                html +="<div class=\"btn blue darken-2\">";
                html += "<span><i class=\"material-icons\">attach_file</i> Seleccione</span>";
                html +="<input type=\"file\" name="+adjunto+" id="+adjunto+"/>";
                html +="</div>";
                html +="<div class=\"file-path-wrapper\">";
                html +="<div class='col s11'><input class=\"file-path validate\" type=\"text\"></div><div class='col s1'><button type='button' class='btn-floating blue darken-2' onclick='eliminarAdjunto(this)'><i class='material-icons'>delete_forever</i></button></div>";                            
                html +="</div>";                            
                html +="</div>";
            $(seccion).append(html);
        }
    }

    function eliminarAdjunto(elemento){
        $(elemento).parent().parent().parent().remove();
    }
    
</script>
</body>
</html>