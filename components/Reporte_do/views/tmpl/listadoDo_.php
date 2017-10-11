{COMODIN}
<div class="div_barra">
    <div id="titulo_ruta">
        Listado de Ordenes
    </div>
    <div id="contenedor_opciones" align="left">
        <table border="0">
            <tr>
                <td align="center">
                    <div class="popupsVer borde_circular noSeleccion">
                        <a href="javascript:verDo()">
                            <img src="img/acciones/ver.png" title="Ver" width="25" height="25" border="0" />
                        </a>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<div style="height: 50px"></div>

<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general_z">
    <thead>
    <tr>
        <th>Orden</th>
        <th>Doc. Transporte</th>
        <th>Cliente</th>
        <th>Manifiesto</th>
        <th>Controles</th>
        <th>Piezas Iniciales</th>
        <th>Peso Inicial</th>
        <th width="10px">Seleccionar</th>
    </tr>
    </thead>
    <tbody>
    <!-- BEGIN ROW  -->
    <tr>
        <td align="center">{orden}</td>
        <td align="left">{doc_transporte}</td>
        <td align="left">[{doc_cliente}] {nombre_cliente}</td>
        <td align="left">{manifiesto}</td>
        <td align="left">{controles}</td>
        <td align="right">{piezas}</td>
        <td align="right">{peso}</td>
        <td align="center"><input type="radio" name="seleccion" onclick="javascript:seleccionado('{orden}')" /></td>
    </tr>
    <!-- END ROW  -->
    </tbody>
</table>
<input type="hidden" name="orden" id="orden" />




<script>
    Nifty("div.borde_circular", "transparent");
    Nifty("div.div_barra", "top transparent");
    $('.noSeleccion').css('display', 'none');


    function verDo(){
        $.ajax({
            url: 'index_blank.php?component=Reporte_do&method=verDetalleDo',
            async: true,
            type: "POST",
            data: 'orden=' + $('#orden').attr('value'),
            success: function(msm) {
                $('#componente_central').html(msm);
            }
        });
    }

    function seleccionado(orden) {
        $("#orden").attr("value", orden);
        $('.noSeleccion').css('display', 'block');
    }
    
    $(document).ready(function(){
        $('#tabla_general_z').dataTable({
            "aaSorting": [],
            "oLanguage": {
                "sLengthMenu": "Mostrar _MENU_ registros por página",
                "sZeroRecords": "No hay registros para mostrar",
                "sInfo": "Mostrando _START_ a _END_ registros de _TOTAL_",
                "sInfoEmpty": "Mostrando 0 a 0 registros de 0",
                "sInfoFiltered": ""
            }
        });
    });
</script>