{COMODIN}
<div id="infoTracking" style="padding-top: 15px;">
  <form name="datTracking" id="datTracking" method="post" action="javascript:enviarDatos()">
    <fieldset class="ui-corner-all">
      <legend>Informaci&oacute;n de Tracking</legend>
      <table align="center" style="width: 90%;" cellpadding="0" cellspacing="0" id="tabla_general">
        <tr>
          <th style="text-align:left;">Remite:</th>
          <td>
            <input type="text" name="remite" id="remite" value="{remite}"
              style="width:617px;height:12px;text-transform:lowercase;" readonly="" />
          </td>
        </tr>
        <tr>
          <th style="text-align:left;">Destino:</th>
          <td>
            <input type="text" name="destino" id="destino" value="{destino}" 
              style="width:617px;height:12px;text-transform:lowercase;" readonly="" />
          </td>
        </tr>
        <tr>
          <th style="text-align:left;">Asunto:</th>
          <td><input type="text" name="asunto" id="asunto" value="{asunto}"
            style="width:617px;height:12px;text-transform:none;" readonly="" /></td>
        </tr>
        <tr>
          <th style="text-align:left;">Adjunto:</th>
          <td><input type="text" name="adjunto" id="adjunto" value="{adjunto}"
            style="width:617px;height:12px;text-transform:none;" readonly="" /></td>
        </tr>
        <tr>
          <th colspan="2" style="width:617px;text-align:left;">Mensaje:</th>
        </tr>
        <tr>
          <td colspan="2" style="width:617px"><textarea name="mensaje" id="mensaje" rows="15" cols="83%" style="resize:none;"
            readonly="">{mensaje}</textarea></td>
        </tr>
      </table>
		</fieldset>
  </form>
</div>