<?php date_default_timezone_set("America/Bogota");?>
{COMODIN}
<html>
<head>
  <!--Import Google Icon Font-->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <!--Import materialize.css-->
  <link rel="stylesheet" href="components/felectronica/views/tmpl/estilos.css" type="text/css" media="screen" />
  <!--<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" /> -->
  <link rel="stylesheet" href="components/felectronica/views/tmpl/sweetalert2.min.css" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <style type="text/css">
    #tabla_vfactura {
      border: 1px solid #000;
      border-radius: 6px;
      -webkit-border-radius: 6px;
      -moz-border-radius: 6px;
      color: black; //Color de la Fuente
    }
    #tabla_vfactura td, #tabla_vfactura th {
      padding: 5px;
    }
    #tabla_vfactura thead {
      font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
      padding: .2em 0 .2em .5em;
      text-align: left;
      color: #4B4B4B;
      background-color: #C8C8C8;
      background-image: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#e3e3e3), color-stop(.6,#B3B3B3));
      background-image: -moz-linear-gradient(top, #D6D6D6, #B0B0B0, #B3B3B3 90%);
      border-top: 1px solid #000;
      border-left: 1px solid #000;
      border-bottom: 1px solid #000;
    }
    #tabla_vfactura th {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 14px;
      line-height: 20px;
      font-style: normal;
      font-weight: normal;
      color: white;
      text-align: center;
      text-shadow: black 1px 1px 1px;
      border-top: 1px solid #000;
      border-left: 1px solid #000;
      border-bottom: 1px solid #000;
      //background-color: #A4A4A4;
      background-color: #34495E;
    }
    #tabla_vfactura td {
      line-height: 10px;
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 10px;
      border-left: 1px solid #000;
      border-bottom: 1px solid #000;
    }
    #tabla_vfactura td:hover {
      background-color: #fff;
    }      
  </style>
</head>
<body>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vfactura">
    <tr>
      <td rowspan="5" style="border:none;width:20%;text-align:center;">
        <img src="integrado/imagenes/cliente.png" width="79" height="37" />
      </td>
    </tr>
    <tr>
      <td colspan="2" style="text-align:center;border-bottom:none;font-size:14px;">
        <b>FACTURA DE VENTA No. {facturaw}</b>
      </td>
      <td rowspan="5" style="width:20%;text-align:center;">
        <img src="integrado/imagenes/bysoft.png" width="79" height="37" />
      </td>
    </tr>
    <tr>
      <th><b>Fecha / Hora</b></th>
      <td style="text-align:center;font-size:14px;border-top: 1px solid #000;">{fechafactura} / {horaemision}</td>
    </tr>
  </table>
  <div style="float:left;width:50%;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vfactura">
      <tr>
        <th colspan="2"><b>DATOS DEL EMISOR</b></th>
      </tr>
      <tr>
        <td style="width:30%;"><b>Raz&oacute;n social / Nombre:</b></td>
        <td style="width:70%;">GRUPO BYSOFT SAS</td>
      </tr>
      <tr>
        <td style="width:30%;"><b>NIT:</b></td>
        <td style="width:70%;">901198779</td>
      </tr>     
      <tr>
        <td style="width:30%;"><b>Direcci&oacute;n:</b></td>
        <td style="width:70%;">CLL.59 No.37-63 OF.401 - Bogot&aacute;</td>
      </tr>
      <tr>
        <td style="width:30%;"><b>Tel&eacute;fonos:</b></td>
        <td style="width:70%;">3106973174</td>
      </tr>
    </table>
  </div>
  <div style="float:right;width:50%;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vfactura">
      <tr>
        <th colspan="2"><b>ADQUIRIENTE</b></th>
      </tr>
      <tr>
        <td style="width:30%;"><b>Raz&oacute;n social / Nombre:</b></td>
        <td style="width:70%;">{razonsocial}</td>
      </tr>
      <tr>
        <td style="width:30%;"><b>NIT:</b></td>
        <td style="width:70%;">{nroDoc}</td>
      </tr>     
      <tr>
        <td style="width:30%;"><b>Direcci&oacute;n:</b></td>
        <td style="width:70%;">{direccion} - {ciudad}</td>
      </tr>
      <tr>
        <td style="width:30%;"><b>Tel&eacute;fonos:</b></td>
        <td style="width:70%;">{telefono}</td>
      </tr>
    </table>
  </div>
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vfactura">
    <tr>
      <th><b>No.</b></th>
      <th><b>Cuenta</b></th>
      <th><b>Descripci&oacute;n</b></th>
      <th><b>% IVA</b></th>
      <th><b>Medida</b></th>
      <th><b>Base</b></th>
      <th><b>*</b></th>
      <th><b>Vr.Unitario</b></th>
      <th><b>Cant.</b></th>
      <th><b>Vr.Total</b></th>
    </tr>
    <!-- BEGIN ROW -->
    <tr>
      <td style="text-align:center;">{numero}</td>
      <td style="text-align:center;">{codigo}</td>
      <input type="hidden" name="codigo[]" id="codigo{numero}" value="{codigo}" />      
      <td style="padding-left: 5px;">{descripcion}</td>
      <input type="hidden" name="descripcion[]" id="descripcion{numero}" value="{descripcion}" /> 
      <td style="text-align:center;">{iva_detalle}</td>
      <input type="hidden" name="tasaimpuesto[]" id="tasaimpuesto{numero}" value="{iva_detalle}" />
      <input type="hidden" name="ivaimpuesto[]" id="ivaimpuesto{numero}" value="{iva_item}" />
      <td style="text-align:center;">{medida}</td>
      <td style="text-align: right;padding-right: 5px;">{base}</td>
      <td style="text-align:center;">{porcentaje}</td>
      <td style="text-align: right;padding-right: 5px;">{vrunitario}</td>
      <input type="hidden" name="preciounitario[]" id="preciounitario{numero}" value="{preciounitario}" />
      <td style="text-align: right;padding-right: 5px;">{cantf}</td>
      <input type="hidden" name="cantidad[]" id="cantidad{numero}" value="{cantidad}" />
      <td style="text-align: right;padding-right: 5px;">{vtotal}</td>
      <input type="hidden" name="baseimponible[]" id="baseimponible{numero}" value="{vrtotal}" />
    </tr>
    <!-- END ROW -->
  </table>
  <div style="float:left;width:75%;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vfactura">
      <tr>
        <th colspan="2"><b>OBSERVACIONES</b></th>
      </tr>
      <tr>
        <td style="font-size:10px;height:158px;">{observaciones}</td>
      </tr>
      <tr>
        <th style="width:100%;text-align:left;font-size:11px;"><b>SON:&nbsp;&nbsp;{monto_letras}</b></th>
      </tr>     
    </table>
  </div>
  <div style="float:right;width:25%;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_vfactura">
      <tr>
        <th colspan="2"><b>TOTALES</b></th>
      </tr>
      <tr>
        <td style="width:40%;"><b>SUBTOTAL:</b></td>
        <td style="width:60%;text-align:right;">{subtotalf}</td>
      </tr>
      <tr>
        <td style="width:40%;"><b>IVA:</b></td>
        <td style="width:60%;text-align:right;">{ivaf}</td>
      </tr>     
      <tr>
        <td style="width:40%;"><b>ANTICIPO:</b></td>
        <td style="width:60%;text-align:right;">{valor_anticipof}</td>
      </tr>
      <tr>
        <th style="width:40%;text-align:left;"><b>TOTAL:</b></th>
        <th style="width:60%;text-align:right;">{totalf}</th>
      </tr>
      <tr>
        <td style="width:40%;"><b>RETE FUENTE:</b></td>
        <td style="width:60%;text-align:right;">{rte_fuentef}</td>
      </tr>
      <tr>
        <td style="width:40%;"><b>RETE ICA:</b></td>
        <td style="width:60%;text-align:right;">{rte_icaf}</td>
      </tr>
      <tr>
        <td style="width:40%;"><b>RETE IVA:</b></td>
        <td style="width:60%;text-align:right;">{rte_ivaf}</td>
      </tr>
      <tr>
        <th style="width:40%;text-align:left;"><b>NETO:</b></th>
        <th style="width:60%;text-align:right;">{netof}</th>
      </tr>
    </table>
  </div>
  <!-- Botón para enviar el formulario -->
  <div class="row">
    <div class="col input-field s12 center-align" style="margin-top:25px;">
      <button id="btn_enviarDatos" type="button" onclick="procesarFormulario(this)" class="btn blue darken-1 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Enviar Datos">Enviar <i class="material-icons">send</i></button>
    </div>
  </div>
  <form id="frmFel" name="frmFel" enctype="multipart/form-data">
    <input type="hidden" name="accion" />
    <input type="hidden" name="opcion" />
    <input type="hidden" name="mostrarModal" />
    <!-- Datos de Cliente -->
    <input type="hidden" name="facel" id="facel" value="{facturaw}" />
    <input type="hidden" name="tipoPersona" id="juridico" value="1" />
    <input type="hidden" name="tipoIdentificacion" id="tipoIdentificacion" value="NIT" />
    <input type="hidden" name="tipoDoc" id="tipoDoc" value="31" />
    <input type="hidden" name="razonsocial" id="razonsocial" value="{razonsocial}" />
    <input type="hidden" name="nroDoc" id="nroDoc" value="{nroDoc}" />
    <input type="hidden" name="direccion" id="direccion" value="{direccion}" />
    <input type="hidden" name="correo" id="correo" value="contacto@grupobysoft.com" />
    <input type="hidden" name="telefono" id="telefono" value="{telefono}" />
    <input type="hidden" name="regimen"  id="{idregimen}" value="{regimen}" />
    <input type="hidden" name="departamento" id="departamento" value="{dpto}" />
    <input type="hidden" name="ciudad" id="ciudad" value="{ciudad}" />
    <!-- Datos de Factura -->
    <input type="hidden" name="fechaemision" id="fechaemision" value="{fechaemision}" />
    <input type="hidden" name="horaemision" id="horaemision" value="{horaemision}" />
    <input type="hidden" name="totalimpuesto" id="totalimpuesto" value="{iva}" />
    <input type="hidden" name="moneda" id="moneda" value="COP" />
    <input type="hidden" name="mediopago"  id="{idmediopago}" value="{codmediopago}" />
    <input type="hidden" name="montototal" id="montototal" value="{total}" />
    <input type="hidden" name="totalbaseimponible" id="totalbaseimponible" value="{subtotal}" />
  </form>
  <!--JavaScript at end of body for optimized loading-->
  <script src="components/felectronica/views/tmpl/materialize.js"></script>
  <script src="components/felectronica/views/tmpl/sweetalert2.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.tooltipped').tooltip({delay: 50});
    });
    //Cierre del document ready
    function alerta(titulo,mensaje,tipo,idElemento) {
      if(typeof tipo!== undefined) {
        swal({
          imageUrl: 'integrado/imagenes/bysoft.png',
          imageWidth: 250,
          imageHeight: 125,
          position: 'top-end',
          type: tipo,
          //title: titulo,
          html: mensaje,
          backdrop: "rgba(0,0,0,0.7) center left no-repeat",
          onClose: () => {
            if(idElemento !== undefined ) document.getElementById(idElemento).focus();
          }
        });
      } else {
        swal({
          imageUrl: 'integrado/imagenes/bysoft.png',
          imageWidth: 250,
          imageHeight: 125,
          position: 'top-end',
          //type: tipo,
          //title: titulo,
          text: mensaje,
          backdrop: "rgba(0,0,0,0.7) center left no-repeat",
          onClose: () => {
            if(idElemento !== undefined ) document.getElementById(idElemento).focus();
          }
        });
      }
    }
  
    let timerInterval;
    function alerTimer(titulo,mensaje,tipo,idElemento) {
      swal({
        imageUrl: 'integrado/imagenes/bysoft.png',
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
          if(idElemento !== undefined ) document.getElementById(idElemento).focus();
        }
      });
    }

    function procesarFormulario(boton) {
      var formulario = document.getElementById("frmFel");
      var tipoEnvio = "POST";
      var accion ="Grupo Bysoft";
      var mostrarModal = "no";
      var infocliente = 4;
      
      var cliente = new Object(); factura = new Object(); impugen = new Object(); detfac = new Object();
      
      $("form#frmFel :input").each(function(){//recorrer todos los elementos de entrada del cliente
        var elemento = $(this);
        infocliente++;
        
        if(infocliente <= 19 && infocliente >= 5) {
          if(elemento.attr('name')!='accion' && elemento.attr('name')!='opcion' &&
            elemento.attr('name')!='mostrarModal' && elemento.attr('name')!='facel') {
            cliente[elemento.attr('name')] = elemento.val(); 
          }
        } else {
          if(elemento.attr('name')!='accion' && elemento.attr('name')!='opcion' &&
            elemento.attr('name')!='mostrarModal' && elemento.attr('name')!='facel') {
            factura[elemento.attr('name')] = elemento.val();
          }
        }
      });

      factura.consecutivo = $("#facel").val();
      /*
        Definir el Detalle de Impuesto
      */
      var $baseimponible = [];
      $('input[name="baseimponible[]"]').each(function(){
        $baseimponible.push($(this).val());
      });
      impugen.baseimponible = $baseimponible;
      impugen.codigoTOTALImp = '01';
      var $tasaimpuesto = [];
      $('input[name="tasaimpuesto[]"]').each(function(){
        $tasaimpuesto.push($(this).val());
      });
      impugen.tasaimpuesto = $tasaimpuesto;
      var $ivaimpuesto = [];
      $('input[name="ivaimpuesto[]"]').each(function(){
        $ivaimpuesto.push($(this).val());
      });
      impugen.ivaimpuesto = $ivaimpuesto;
      impugen.totalimpuesto = $('#totalimpuesto').val();
      /*
        Definir Detalle de Factura
      */
      // Registra la cantidad del detalle de factura
      var $cantidad = [];
      $('input[name="cantidad[]"]').each(function(){
        $cantidad.push($(this).val());
      });
      detfac.cantidad = $cantidad;
      // Registra la base imponible del detalle de factura
      detfac.baseimponible = $baseimponible;
      // Registra el código del detalle de factura
      var $codigo = [];
      $('input[name="codigo[]"]').each(function(){
        $codigo.push($(this).val());
      });
      detfac.codigo = $codigo;
      // Registra la descripción del detalle de factura
      var $descripcion = [];
      $('input[name="descripcion[]"]').each(function(){
        $descripcion.push($(this).val());
      });
      detfac.descripcion = $descripcion;
      detfac.descuento = "0.00";
      detfac.totalSinImp = $baseimponible;
      // Registra el preciounitario del detalle de factura
      var $preciounitario = [];
      $('input[name="preciounitario[]"]').each(function(){
        $preciounitario.push($(this).val());
      });
      detfac.preciounitario = $preciounitario;
      detfac.rangoNumeracion = "BYSF-1";
      //detfac.tipoImpuesto = factura.tipoImpuesto; No aparece en Manual de Integración Directa v7.2
      detfac.tipoImpuesto = '01';
      // Registra la tasaimpuesto del detalle de factura
      detfac.tasaimpuesto = $tasaimpuesto;
      //detfac.ivacalc = (parseFloat(factura.cantidad * factura.preciounitario) - parseFloat((factura.cantidad * factura.preciounitario) / (1 + (impugen.tasaimpuesto / 100)))).toFixed(2);
      // Esta fórmula da como resultado = 1.150.000 - (1.150.000/1,19) = 1.150.000 - 966.386,55 = 183.613,45
      //detfac.ivacalc = $('#totalimpuesto').val();
      detfac.ivacalc = $ivaimpuesto;
      detfac.montototal = $('#montototal').val();
      //detfac.serial = factura.serial; //campo tentativo no esta en el SDK de C#
      formulario.accion.value = accion;
      formulario.opcion.value = 4;
      formulario.mostrarModal.value = mostrarModal;
      formulario.method = tipoEnvio;
      formulario.action="components/felectronica/views/tmpl/tfhka/procesar.php";
      formulario.method="post";
      var form = new FormData();
      form.append("consecutivoDocumento",factura.consecutivo);
      $.ajax({
        url:"components/felectronica/views/tmpl/tfhka/procesar.php",
        type:"POST",
        data:{
          cliente:JSON.stringify(cliente),
          factura:JSON.stringify(factura),
          impgen:JSON.stringify(impugen),
          detalle:JSON.stringify(detfac), 
          opcion : formulario.opcion.value
        },
        beforeSend: function() {
          alerta("Grupo Bysoft","Aguarde un Momento mientras enviamos su información","info");
        },
        success: function(respuesta) {
          setTimeout(function() {
            alerta("Grupo Bysoft",respuesta,"success");
            setTimeout( () => {
              $.ajax({
                url:"components/felectronica/views/tmpl/tfhka/adjuntos.php",
                type:"POST",
                cache: false,
                contentType: false,
                processData: false,
                data : form,
                beforeSend:() => {
                  alerTimer("Grupo Bysoft","Aguarde un Momento mientras enviamos los adjuntos","info");
                },
                success: function(respuesta) {
                  setTimeout(function(){alerta("BYSOFT FEL - CO",respuesta,"success");},2000);
                }
              });                
            }, 7000);
          },500);
        }
      })
    }
  </script>
</body>
</html>