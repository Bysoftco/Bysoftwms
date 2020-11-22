{COMODIN}
<div id="tab_aplica">
  <div id="header_info_cliente" style="display: none;">
    <div style="padding-top: 15px;"></div>
    <div class="div_barra">
      <div id="titulo_ruta" style="padding-top: 13px;">  
        <table style="border: none;" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td>
              <a href="javascript:listarClientes()">LISTAR CLIENTES</a> / VISTA CLIENTE
            </td>
            <td>&nbsp;-&nbsp;</td><td><div id="nombrar_cliente" style="color: black;"></div></td>
          </tr>
        </table>
      </div>
    </div><br />
  </div>
  <script>
    Nifty("div.borde_circular","transparent");
    Nifty("div.div_barra","top transparent");
    $('.noSeleccion').css('display', 'none');

    $('#{mostrar_header}').css('display','block');
  
    $('#nombrar_cliente').html($('#razon_social').val());
  </script>
</div>