{COMODIN}
<fieldset>
    <legend>
        Alistamiento de Kits
    </legend><br />
    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
        <tr>
            <th colspan="4">
                Alistamiento de Kits No. {codigo_operacion}
                <input type="hidden" name="codigo_operacion" id="codigo_operacion" value="{codigo_operacion}" />
            </th>
        </tr>
        <tr>
            <td class="tituloForm" style="width: 25%">Documento Cliente</td>
            <td style="width: 25%">
                {numero_documento}
                <input type="hidden" name="doc_cliente" id="doc_cliente" value="{numero_documento}" />
            </td>
            <td style="width: 25%" class="tituloForm">Nombre Cliente</td>
            <td style="width: 25%">{razon_social}</td>
        </tr>
        <tr>
            <td class="tituloForm" style="width: 25%">Fecha de Alistamiento</td>
            <td style="width: 25%">{fecha}</td>
            <td style="width: 25%" class="tituloForm">FMM</td>
            <td style="width: 25%">{fmm}</td>
        </tr>
        <tr>
            <td class="tituloForm">Nombre Kit</td>
            <td>[{codigo_kit}] {producto}</td>
            <td class="tituloForm">Orden</td>
            <td>{orden}</td>
        </tr>
        <tr>
            <td class="tituloForm">Unidad de Empaque</td>
            <td>{unidad}</td>
            <td class="tituloForm">Cantidad</td>
            <td>{cantidad}</td>
        </tr>
        <tr>
            <td class="tituloForm">Piezas Nacionales</td>
            <td>{cantidad_nac}</td>
            <td class="tituloForm">Piezas Extranjeras</td>
            <td>{cantidad_ext}</td>
        </tr>
        <tr>
            <td class="tituloForm">Observaciones</td>
            <td colspan="3">{observaciones}</td>
        </tr>
    </table><br /><br />

    <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
        <tr>
            <th colspan="10">Detalle del Alistamiento</th>
        </tr>
        <tr>
            <th>Orden</th>
            <th>Arribo</th>
            <th>Referencia</th>

            <th>Fecha</th>
            <th>Piezas Nacionales</th>
            <th>Peso Nacional</th>
            <th>Valor CIF</th>
            <th>Piezas Extranjeras</th>
            <th>Peso Extranjero</th>
            <th>Valor FOB</th>
        </tr>
        <!-- BEGIN ROW -->
        <tr>
            <td>{orden_detalle}</td>
            <td>{arribo}</td>
            <td>[{codigo_referen}] {nombre_referencia}</td>

            <td>{fecha_detalle}</td>
            <td style="text-align: right;">{cantidad_nacional}</td>
            <td style="text-align: right;">{peso_nacional}</td>
            <td style="text-align: right;">{valor_cif}</td>
            <td style="text-align: right;">{cantidad_extranjera}</td>
            <td style="text-align: right;">{peso_extranjera}</td>
            <td style="text-align: right;">{valor_fob}</td>
        </tr>
        <!-- END ROW -->
    </table><br /><br />
    <table align="right" cellpadding="0" cellspacing="0">
        <tr style="display: {mostrar_botones}">
            <td><input type="button" value="Cerrar" id="cerrar_operacion" class="button" /></td>
            <td><input type="button" value="Devolver Operación" id="devolver" class="button" /></td>
        </tr>
        <tr style="display: {mostrar_mensaje}">
            <td colspan="2">* La operación no puede ser devuelta debido a que se encuentra cerrada.</td>
        </tr>
    </table>
</fieldset>

<script>

$("#cerrar_operacion").click(function(){
    
    if(confirm("Realmente desea cerrar la operación?")){
        $.ajax({
            url: 'index_blank.php?component=Kits&method=cerrarKit',
            type: "POST",
            data: 'codigo_operacion='+$("#codigo_operacion").attr("value"),
            success: function(msm){
                jQuery(document.body).overlayPlayground('close');void(0);
                $('#componente_central').html(msm);
            }
        });
    }
});

$("#devolver").click(function(){
    
    if(confirm("Realmente desea devolver la operación?")){
        $.ajax({
            url: 'index_blank.php?component=Kits&method=devolderAlistamiento',
            type: "POST",
            data: 'codigo_operacion='+$("#codigo_operacion").attr("value")+"&doc_cliente="+$("#doc_cliente").attr("value"),
            success: function(msm){
                alert("Devolución Realizada con Éxito.");
                jQuery(document.body).overlayPlayground('close');void(0);
                $('#componente_central').html(msm);
            }
        });
    }
});

</script>