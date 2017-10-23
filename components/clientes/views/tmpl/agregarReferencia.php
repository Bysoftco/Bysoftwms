{COMODIN}
Los campos marcados con un asterisco (*) son obligatorios.<br /><br />
<link rel="stylesheet" type="text/css" href="./integrado/cz_estilos/jquery.autocomplete.css" />
<form name="enviar_datos" id="enviar_datos" action="javascript:enviarDatos()" method="post" >
  <table width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr align="center">
      <th height="21" colspan="6">Informaci&oacute;n Referencia</th>
    </tr>
    <tr>
      <td>C&oacute;digo *</td>
      <td><input type="text" style="width:100px" name="id_referencia" id="id_referencia" class="required" value="{id_referencia}" /></td>
      <td>Descripci&oacute;n&nbsp;*&nbsp;</td>
      <td><input type="text" name="nombre_referencia" id="nombre_referencia" class="required" value="{nombre_referencia}"/></td>
      <td>Tipo</td>
      <td><select style="width:auto;" name="tipo_referencia" id="tipo_referencia" class="required">{select_tiporef}</select></td>
    </tr>      
    <tr>
      <td height="26">SubPartida</td>
      <td><input type="text" style="width:100px" name="SKU_Proveedor" id="SKU_Proveedor" value="{SKU_Proveedor}" /></td>
      <td>Unidad Comercial/Venta</td>
      <td><select style="width:auto;" name="unidad_referencia" id="unidad_referencia">{select_unidad}</select></td>
      <td>Unidad Inventario</td>
      <td><select style="width:auto;" name="presenta_venta" id="presenta_venta">{select_tipoemb}</select></td>
    </tr>      
    <tr>
      <td>Unidades Embalaje</td>
      <td><input type="text" style="width:60px" name="embalaje_referencia" id="embalaje_referencia" value="1" /></td>
      <td>M/Lote/C <input type="text" style="width:60px" name="lote_cosecha" id="lote_cosecha" value="{lote_cosecha}" /></td>
      <td>Vence&nbsp;<input type="checkbox" name="vence_referencia" id="vence_referencia" {vence_referencia} />
        Serial&nbsp; 
        <input type="checkbox" name="serial_referencia" id="serial_referencia" {serial_referencia} />
        Min_Stock&nbsp; 
        <input type="checkbox" name="minimo_stock" id="minimo_stock" {minimo_stock} /></td>
      <td>Fecha Vigencia</td>
      <td><input name="vigencia" class="required"  type="text" id="vigencia" style="width:60px" value="{vigencia}">
        <input type="hidden" name="vigencia_aux" id="vigencia_aux" value="{vigencia}" /></td>
    </tr>
    <tr>
      <td>Grupo Items</td>
      <td><select style="width:200px;" name="grupo_items" id="grupo_items">{select_grupo}</select></td>
      <td>Factor de Conversión</td>
      <td><input type="number" style="width:100px" step="0.1" name="factor_conversion" id="factor_conversion" class="required" value="{factor_conversion}" /></td>
      <td>Parte N./Registro N.</td>
      <td><input name="parte_numero" type="text"  class="required"  id="parte_numero" style="width:60px" value="{parte_numero}"></td>
    </tr>
	<tr>
      <td height="24">Ancho</td>
      <td><input type="text" style="width:60px" name="ancho_referencia" id="ancho_referencia" value="{ancho}" /></td>
      <td>Largo</td>
      <td><input type="text" style="width:60px" name="largo_referencia" id="largo_referencia" value="{largo}" /></td>
      <td>Alto</td>
      <td><input type="text" style="width:60px" name="alto_referencia" id="alto_referencia" value="{alto}" /></td>
    </tr>
  </table><br />
  <input type="hidden" name="cliente" id="cliente" value="{cliente}" />
  <input type="hidden" name="accion" id="accion" value="{accion}" />
  <input type="hidden" name="automatica" id="automatica" value="0" />
  <center><input name="aceptar" id="aceptar" class="button small yellow2" type="submit" value="Aceptar" /></center>
</form>
<br /><br />
<script>
	$("#vigencia").datepicker();
		$("#vigencia").datepicker('option', 
		{
	    	dateFormat: 'yy/mm/dd',
			changeYear: true,
			changeMonth: true,
			showOn: 'both',
			buttonImage: 'integrado/imagenes/calendar.png',
			buttonImageOnly: true
		});
		
		$("#vigencia").val($("#vigencia_aux").val());
		
  $(document).ready(function() {
    $("#enviar_datos").validate();
    $("#SKU_Proveedor").autocomplete("./scripts_index.php?clase=Subpartida&metodo=findSupartida", {
		  width: 260,
		  selectFirst: false
    });
    $("#SKU_Proveedor").result(function(event, data, formatted) {
      $("#SKU_Proveedor").val(data[1]);
    });
		
  }); //SKU_Proveedor
  
  function enviarDatos() {
    $.ajax({
      url: 'index_blank.php?component=clientes&method=nuevaReferencia',
      data: $('#enviar_datos').serialize(),
      type: "POST",
      success: function(msm) {
        jQuery(document.body).overlayPlayground('close');void(0);
        $("#referenciasCliente").html(msm);
      }
    });
  }
</script>