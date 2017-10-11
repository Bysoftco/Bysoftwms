{COMODIN}
<div id="eliminar_{cod}">
  <table width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr>
      <th>Servicio</th>
      <th width="5%"><img class="eliminarServ" style="cursor: pointer;" src="img/acciones/eliminar.png" title="Eliminar Servicio" width="15" height="15" border="0" onclick="javascript:eliminarServicio('{cod}')"  /></th>
    </tr>
  </table>
  <table width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr>
      <td>Servicio *</td>
      <td><select name="servicio[]" id="servicio_{cod}" class="required">{select_servicio}</select></td>
      <td>Base *</td>
      <td><select name="base[]" id="base_{cod}" class="required">{select_base}</select></td>
    </tr>
    <tr>
      <td>Valor Mínimo *</td>
      <td><input type="text" name="valor_minimo[]" id="valor_minimo_{cod}" value="{valor_mminimo}" class="required digits" /></td>
      <td>Tope *</td>
      <td><input type="text" name="tope[]" id="tope_{cod}" value="{tope}" class="required digits" /></td>
    </tr>
    <tr>
      <td>Valor *</td>
      <td><input type="text" name="valor[]" id="valor_{cod}" value="{valor}" class="required digits" /></td>
      <td>Adicional *</td>
      <td><input type="text" name="adicional[]" id="adicional_{cod}" value="{adicional}" /></td>
    </tr>
    <tr>
      <td>D&iacute;as *</td>
      <td><input type="text" name="dias[]" id="dias_{cod}" value="{dias}" class="required digits" /></td>
      <td>Vigencia *</td>
      <td><input type="text" name="vigencia[]" id="vigencia_{cod}" value="{vigencia}" class="required digits" /></td>
    </tr>
  </table>
</div>