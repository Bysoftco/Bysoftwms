{COMODIN}
<div style="padding-top: 10px;"></div>
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general_z" class="display ui-widget ui-widget-content">
  <thead>
    <tr>
      <th>#</th>
      <th>cod_ref</th>
      <th>subpart</th>
      <th>nombre</th>
      <th>observa</th>
      <th>cliente</th>
      <th>p_numero</th>
      <th>unidad</th>
      <th>Embalaje</th>
      <th>U_empaque</th>
      <th>f_expira</th>
      <th>vigencia</th>
      <th>min_stock</th>
      <th>l_cosecha</th>
      <th>alto</th>
      <th>largo</th>
      <th>ancho</th>
      <th>serial</th>
      <th>tipo</th>
      <th>gr_item</th>
      <th>fact_conv</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td style="text-align: center;color:red;">{n}</td>
      <td style="text-align: center;">{codigo_ref}</td>
      <td style="text-align: center;">{subpartidas}</td>
      <td style="text-align: center;">{nombre}</td>
      <td style="text-align: center;">{observaciones}</td>
      <td style="text-align: center;">{cliente}</td>
      <td style="text-align: center;">{parte_numero}</td>
      <td style="text-align: right;">{unidad}</td>
      <td style="text-align: right;">{Embalaje}</td>
      <td style="text-align: center;">{U_empaque}</td>
      <td style="text-align: right;">{fecha_expira}</td>
      <td style="text-align: center;">{vigencia}</td>
      <td style="text-align: right;">{min_stock}</td>
      <td style="text-align: center;">{lote_cosecha}</td>
      <td style="text-align: right;">{alto}</td>
      <td style="text-align: right;">{largo}</td>
      <td style="text-align: right;">{ancho}</td>
      <td style="text-align: right;">{serial}</td>
      <td style="text-align: right;">{tipo}</td>
      <td style="text-align: center;">{grupo_item}</td>
      <td style="text-align: right;">{factor_conversion}</td>
    </tr>
    <!-- END ROW  -->
  </tbody>
</table>
<script>
  $(document).ready(function() {
    $('#tabla_general_z').dataTable({
      "aaSorting": [],
      "oLanguage": {
        "sLengthMenu": "Mostrar _MENU_ registros por p&aacute;gina",
        "sZeroRecords": "No hay registros para mostrar",
        "sInfo": "Mostrando _START_ a _END_ registros de _TOTAL_",
        "sInfoEmpty": "Mostrando 0 a 0 registros de 0",
        "sInfoFiltered": "",
        "sSearch": "Buscar:"
      }
    });
  });	

  $('a[class="tblank"]').click(function() {
    window.open("index_blank.php?component=existencias&method=imprimeListadoExistencias&buscarClientefe="+$("#buscarClientefe").val()+
      "&nitfe="+$("#nitfe").val()+"&fechadesdefe="+$("#fechadesdefe").val()+"&fechahastafe="+$("#fechahastafe").val()+"&doasignadofe="+$("#doasignadofe").val()+
      "&tipoingresofe="+$("#tipoingresofe").val());
    return false;
  });
  
  function Excel() {
    document.frmExcel.submit();
  }
</script>