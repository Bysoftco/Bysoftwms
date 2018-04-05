{COMODIN}
<form style="padding-top: 5px;" name="form_acondicionamiento" id="form_acondicionamiento" method="post" action="javascript:enviarAcondicionamiento()" >
  <fieldset>
    <legend>
      Detalle del Acondicionamiento
    </legend>
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
      <tr>
        <th colspan="13">Registro del Acondicionamiento</th>
      </tr>
      <tr>
        <th rowspan="2">Orden</th>
        <th rowspan="2">Referencia</th>
        <th rowspan="2">Nombre Referencia</th>
        <th colspan="5">P i e z a s</th>
      </tr>
      <tr>
        <th>Acondicionar</th>
        <th>Rechazadas</th>
        <th>Tipo Rechazo</th>
        <th>Devueltas</th>
        <th>Acondicionadas</th>
      </tr>
      <!--  BEGIN ROW -->
      <tr>
        <td style="text-align:center;">{orden_detalle}</td>
        <td style="text-align:center;">
          {codigo_referen}
          <input type="hidden" class="cantidadPiezas" name="codreferencia" value="{codigo_referen}" />
        </td>
        <td style="padding-left:3px;">{nombre_referencia}</td>
        <td style="text-align:center;">
          <input style="text-align:right;padding-right:3px;" name="cantidad_retirar[{codigo_referen}]" id="cantidad_retirar{codigo_referen}" value="{acondicionar}" readonly="" size="8" readonly="" />
        </td>
        <td style="text-align:center;">
          <input class="required number" style="text-align:right;padding-right:3px;" name="cantidad_rechazar[{codigo_referen}]" id="cantidad_rechazar{codigo_referen}" value="0.00" onblur="calcular('{codigo_referen}')" size="8" />
        </td>
        <td style="text-align:center;">
          <select style="width:150px;" name="tipo_rechazo[{codigo_referen}]" id="tipo_rechazo{codigo_referen}">{select_tiporechazo}</select>
        </td>
        <td style="text-align:center;">
          <input class="required number" style="text-align:right;padding-right:3px;" name="cantidad_devueltas[{codigo_referen}]" id="cantidad_devueltas{codigo_referen}" value="0.00" onblur="calcular('{codigo_referen}')" size="8" />
        </td>
        <td style="text-align:center;">
          <input class="required number" style="text-align:right;padding-right:3px;" name="cantidad_acondicionar[{codigo_referen}]" id="cantidad_acondicionar{codigo_referen}" value="{acondicionar}" readonly="" size="8" />
        </td>
      </tr>
      <!-- END ROW -->
    </table>
    <input type="hidden" name="codigoMaestro" id="codigoMaestro" value="{codigo_operacion}" />
    <input type="hidden" name="tipo_mercancia" id="tipo_mercancia" value="{tipo_mercancia}" />
    <input type="hidden" name="doc_cliente" id="doc_cliente" value="{doc_cliente}" />
    <input type="hidden" name="nombre_tipo_mercancia" id="nombre_tipo_mercancia" value="{nombre_tipo_mercancia}" />
    <input type="hidden" name="fecha" id="fecha" value="{fecha}" />
    <input type="hidden" name="id_camion" id="id_camion" value="{id_camion}" />
    <input type="hidden" name="destinatario" id="destinatario" value="{destinatario}" />
    <input type="hidden" name="direccion" id="direccion" value="{direccion}" />
    <input type="hidden" name="fmm" id="fmm" value="{fmm}" />
    <input type="hidden" name="doc_tte" id="doc_tte" value="{doc_tte}" />
    <input type="hidden" name="cod_referencia" id="cod_referencia" value="{cod_referencia}" />
    <input type="hidden" name="pedido" id="pedido" value="{pedido}" />
    <input type="hidden" name="codigo_ciudad" id="codigo_ciudad" value="{codigo_ciudad}" />
    <input type="hidden" name="observaciones" id="observaciones" value="{observaciones}" />
  </fieldset><br />
  <center>
    <input name="enviarAcondicionar" id="enviarAcondicionar" class="button" type="submit" value="Acondicionar" />
	</center>
</form>
<script>
  $().ready(function() {
    $("#form_acondicionamiento").validate();
  });
  
  function calcular(ref) {
    $('#cantidad_retirar'+ref).val(parseFloat($('#cantidad_retirar'+ref).val()).toFixed(2));
    $('#cantidad_rechazar'+ref).val(parseFloat($('#cantidad_rechazar'+ref).val()).toFixed(2));
    $('#cantidad_devueltas'+ref).val(parseFloat($('#cantidad_devueltas'+ref).val()).toFixed(2));
    $('#cantidad_acondicionar'+ref).val(parseFloat($('#cantidad_retirar'+ref).val()).toFixed(2));
    //Validación del valor de Mercancías Acondicionadas
    $('#cantidad_acondicionar'+ref).val(parseFloat($('#cantidad_retirar'+ref).val()-$('#cantidad_rechazar'+ref).val()-$('#cantidad_devueltas'+ref).val()).toFixed(2));
    if($("#cantidad_acondicionar"+ref).val()<0) alert('La cantidad de mercancía Acondicionada no puede ser menor que 0. Revisar la cantidad de Rechazadas o Devueltas.');
  }

  function enviarAcondicionamiento() {
    var recorrer = $(".cantidadPiezas");
    var cont = true;
    for(var x=0; x < recorrer.length; x++) {
      if($("#cantidad_rechazar"+recorrer[x].value).attr("value")!=0 && $("#tipo_rechazo"+recorrer[x].value).attr("value")==1) {
        alert("Debe seleccionar el tipo de rechazo de la mercancia "+recorrer[x].value+", existe(n) "+$("#cantidad_rechazar"+recorrer[x].value)+" para rechazar.");
        cont = false;
        break;
      }
      if($("#cantidad_rechazar"+recorrer[x].value).attr("value")==0 && $("#tipo_rechazo"+recorrer[x].value).attr("value")!=1) {
        alert("Error al seleccionar tipo de rechazo, no existe mercancia para rechazar.");
        cont = false;
        break;
      }      
    }
    if(cont) {
      $.ajax({
        url: 'index_blank.php?component=acondicionamientos&method=registraAcondicionamiento',
        async: true,
        type: "POST",
        data:$('#form_acondicionamiento').serialize(),
        success: function(msm) {
          jQuery(document.body).overlayPlayground('close');void(0);
          $('#componente_central').html(msm);
        }
      });
    }
  }
</script>