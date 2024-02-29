{COMODIN}
<div style="height: 5px;"></div>
<div class="div_barraFija">
	<div id="titulo_ruta">{titulo_accion}</div>
</div><br />

<form name="envioDatos" id="envioDatos" action="javascript:enviarDatos()">
    <fieldset>
	<legend>
		Informaci&oacute;n de Kit
	</legend>
        <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
            <tr>
                <th colspan="2">Armado Kits</th>
            </tr>
            <tr>
                <td style="width: 40%" height="27px" class="tituloForm">Nombre Cliente</td>
                <td>{nombre_cliente}</td>
            </tr>
            <tr>
                <td height="27px" class="tituloForm">Documento Cliente</td>
                <td>
                    {documento_cliente}
                    <input type="hidden" name="cliente" id="cliente" value="{documento_cliente}" />
                </td>
            </tr>
            <tr>
                <td height="27px" class="tituloForm">C&oacute;digo Kit</td>
                <td>
                    {codigo_kit}
                    <input type="hidden" name="codigo_kit" id="codigo_kit" value="{codigo_kit}" />
                </td>
            </tr>
            <tr>
                <td height="27px" class="tituloForm">Nombre Kit</td>
                <td>{nombre_kit}</td>
            </tr>
            <tr>
                <td height="27px" class="tituloForm">Descripci&oacute;n</td>
                <td>{descripcion}</td>
            </tr>
            <tr>
                <td height="27px" class="tituloForm">Fecha Creaci&oacute;n</td>
                <td>{fecha_creacion}</td>
            </tr>
            <tr>
                <td height="27px" class="tituloForm">Piezas disponibles Nacional</td>
                <td>{disponible_nal} Kits</td>
            </tr>
            <tr>
                <td height="27px" class="tituloForm">Piezas disponibles Extranjera</td>
                <td>{disponible_nonal} Kits</td>
            </tr>
            <tr>
                <td height="27px" class="tituloForm">Piezas disponibles Mixta</td>
                <td>{disponible_mixta} Kits</td>
            </tr>
            <tr>
                <th colspan="2">Composici&oacute;n Kits</th>
            </tr>
            <tr>
                <td colspan="2">
                    
                    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
                        <tr>
                            <th>C&oacute;digo de referencia</th>
                            <th>Descripci&oacute;n de referencia</th>
                            <th>Piezas en el Kit</th>
                            <th>Disponible Nacional</th>
                            <th>Disponible Extranjera</th>
                        </tr>
                        <!-- BEGIN ROW -->
                        <tr>
                            <td>{codigo_referencia}</td>
                            <td>{descripcion_referencia}</td>
                            <td align="center">{cantidad}</td>
                            <td align="center">{diponible_nacional}</td>
                            <td align="center">{diponible_no_nacional}</td>
                        </tr>
                        <!-- END ROW -->
                    </table><br /><br />
                </td>
            </tr>
        </table>
    </fieldset>
</form>