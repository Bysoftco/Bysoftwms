{COMODIN}
<fieldset>
  <legend>
    Informaci&oacute;n de Etiqueta
  </legend><br />
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr>
      <th colspan="4">
        Etiqueta del Acondicionamiento No. {codigo_operacion}
        <input type="hidden" name="codigo_operacion" id="codigo_operacion" value="{codigo_operacion}" />
      </th>
    </tr>
    <tr>
      <td style="width: 25%" class="tituloForm">Nombre Cliente</td>
      <td style="width: 25%"><b>{razon_social}</b></td>
      <td class="tituloForm" style="width: 25%">Documento Cliente</td>
      <td style="width: 25%">
        {numero_documento}
        <input type="hidden" name="doc_cliente" id="doc_cliente" value="{numero_documento}" />
      </td>
    </tr>
    <tr>
      <td class="tituloForm" style="width: 25%">Fecha de Etiqueta</td>
      <td style="width: 25%">{fecha}</td>
      <td style="width: 25%" class="tituloForm">Sede</td>
      <td style="width: 25%"><strong>{nombre_sede}</strong></td>
    </tr>
    <tr>
      <td style="width: 25%" class="tituloForm">Destinatario</td>
      <td style="width: 25%">{destinatario}</td>    
      <td class="tituloForm">Ciudad / Direcci&oacute;n</td>
      <td>{ciudad} / {direccion}</td>
    </tr>   
    <tr>
      <td style="width: 25%" class="tituloForm">Conductor</td>
      <td style="width: 25%">{conductor_nombre}</td>
      <td class="tituloForm">Identificaci&oacute;n</td>
      <td>{conductor_identificacion}</td>
    </tr>
    <tr>
      <td style="width: 25%" class="tituloForm">Placa</td>
      <td style="width: 25%">{placa}</td>     
      <td class="tituloForm">Empresa Transportadora</td>
      <td>{empresa}</td> 
    </tr>
    <tr>
      <td class="tituloForm">Cantidad</td>
      <td>{cantidad}</td>
      <td class="tituloForm">Cantidad Nacional</td>
      <td>{cantidad_nac}</td>
    </tr>
    <tr>
      <td class="tituloForm">Cantidad Extranjera</td>
      <td colspan="3">{cantidad_ext}</td>
    </tr>
  </table><br /><br />
  <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr>
      <th colspan="7">Detalle del Acondicionamiento</th>
    </tr>
    <tr>
      <th>C&oacute;digo Ref.</th>
      <th>Referencia</th>
      <th>Ubicaci&oacute;n</th>
      <th>M/L/C</th>
      <th>Piezas Nal.</th>
      <th>Piezas Ext.</th>
      <th style="text-align: center;">
        <input type="checkbox" id="chkTodos" onclick="seleccion()" /> 
      </th>      
    </tr>
    <!-- BEGIN ROW -->
    <tr>
      <td style="text-align:center;">
        {codigo_referen}
      </td>
      <td style="padding-left: 5px;">{nombre_referencia}</td>
      <td style="text-align:center;">{nombre_ubicacion}</td>
      <td style="text-align:center;">{modelo}</td>
      <td style="text-align: right;padding-right: 5px;">
        {cantidad_nacional}
      </td>
      <td style="text-align: right;padding-right: 5px;">
        {cantidad_extranjera}
      </td>
      <td style="text-align: center;">
        <input name="n[]" type="checkbox" id="chkSeleccionado" />
      </td>      
    </tr>
    <!-- END ROW -->
  </table><br /><br />
  <center>
    <button style="font-family: sans-serif;font-size: 12px;" class="submit" type="submit" id="labeler" >Generar Etiqueta</button>
  </center>
  <input type="hidden" name="codigoMaestro" id="codigoMaestro" value="{codigo_operacion}" />
  <input type="hidden" name="tipo_mercancia" id="tipo_mercancia" value="{tipo_mercancia}" />
</fieldset>
<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");
  
  function seleccion() {
    var checkBoxSelector = 'input[id*="chkSeleccionado"]:checkbox';

    //Verifica el Estado del CheckBox = Seleccionado/No Seleccionado
    if(chkTodos.checked) {
      $('#chkTodos').live('click', function () {
        $(checkBoxSelector).attr('checked', true);
      });      
    } else {
      $('#chkTodos').live('click', function () {
        $(checkBoxSelector).attr('checked', false);
      });      
    }   
  }
  
  $( "#labeler" ).button({
    text: true,
    icons: {
      primary: "ui-icon-document"
    }
  })
	.click(function() {
    //Verifica si está seleccionado al menos un registro detalle
    if($("input:checked").length === 0) alert('No se puede generar la Etiqueta del Alistamiento '+$("#codigo_operacion").val()+', debe seleccionar al menos un registro detalle.');
    else {
      var checkBoxSelector = 'input[id*="chkSeleccionado"]:checkbox';
      var codigos = [];
      var n = 0;

      $(checkBoxSelector).each( function() {
        n++;
        if($(this).is(':checked')){
          codigos.push(n);
        }
		  });
      window.open("index_blank.php?component=Alistamientos&method=mostrarEtiqueta&codigoMaestro="+$("#codigoMaestro").attr("value")+"&codigos="+codigos);
      return false;      
    }
  });
</script>