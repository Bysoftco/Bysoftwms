{COMODIN}
<link rel="stylesheet" type="text/css" href="integrado/cz_estilos/jquery.autocomplete.css" />
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td>
          <a href="javascript:listarClientes()">LISTAR CLIENTES</a> / NUEVO CLIENTE
        </td>
      </tr>
    </table>
  </div>
</div><br />
<div style="height: 25px"></div>
<div id="infoClienteNuevo">
  <form name="datCliente" id="datCliente" method="post" action="javascript:enviarDatos()">
    <table align="center" width="90%" cellpadding="0" cellspacing="0" id="tabla_general" style="margin: 20px;">
      <tr>
        <th colspan="4">Datos del Cliente</th>
      </tr>
      <tr>
        <td class="tituloForm" width="20%">Tipo de Identificaci&oacute;n *</td>
        <td width="30%">
          <div style="display: {verTexto};">{tipo_identi}</div>
          <select style="display: {verCampo};" name="tipo_documento" id="tipo_documento" class="{required:true}" >{select_tipodoc}</select>
        </td>
        <td class="tituloForm">Tipo Cliente *</td>
        <td>
          <select name="tipo" id="tipo" class="{required:true}" onchange="javascript:claseTipo(this.value)">
            {select_tipocli}
          </select>
        </td>
      </tr>
      <tr>
        <td class="tituloForm">N&uacute;mero de Identificaci&oacute;n *</td>
        <td>
          <div style="display: {verTexto};">{numero_documento}</div>
          <input style="display: {verCampo};" type="text" name="numero_documento" id="numero_documento" class="{required:true} digits" value="{numero_documento}" />
        </td>
        <td class="tituloForm">D&iacute;gito_V</td>
        <td><input type="text" maxlength="1" name="digito_verificacion" id="digito_verificacion" class="{required:true} digits" value="{digito_verificacion}" /></td>
      </tr>
      <tr>
        <td class="tituloForm">Raz&oacute;n Social</td>
        <td><input type="text" name="razon_social" id="razon_social" class="{required:true}" value="{razon_social}" /></td>
        <td class="tituloForm" width="20%">R&eacute;gimen *</td>
        <td width="30%"><select name="regimen" id="regimen" class="{required:true}">{select_regimen}</select></td>
      </tr>
      <tr>
        <td class="tituloForm">Act.Econ&oacute;mica</td>
        <td>
            <input type="text" name="act_econ" id="act_econ" class="{required:true}" value="{nom_actividad}" title="{nom_actividad}" />
            <input type="hidden" name="actividad_economica" id="actividad_economica"  class="{required:true}" value="{cod_actividad}" />
        </td>
        <td class="tituloForm">Autoretenedor</td>
        <td><input type="checkbox" name="autoretenedor" id="autoretenedor" {autoretenedor} /></td>
      </tr>
      <tr>
        <td colspan="4"></td>
      </tr>
      <tr>
        <th colspan="4">Informaci&oacute;n de contacto del cliente</th>
      </tr>
      <tr>
        <td class="tituloForm">Direcci&oacute;n *</td>
        <td><input type="text" name="direccion" id="direccion" class="{required:true}" value="{direccion}" /></td>
        <td class="tituloForm">Celular *</td>
        <td><input type="text" name="telefonos_celulares" id="telefonos_celulares" class="{required:true}" value="{telefonos_celulares}" /></td>
      </tr>
      <tr>
        <td class="tituloForm">Tel&eacute;fono(s) fijo(s) *</td>
        <td><input type="text" name="telefonos_fijos" id="telefonos_fijos" class="{required:true}" value="{telefonos_fijos}" /></td>
        <td class="tituloForm">TeleFax / SAPCODE *</td>
        <td><input type="text" name="telefonos_faxes" id="telefonos_faxes" class="{required:true}" value="{telefonos_faxes}" /></td>
      </tr>
      <tr>
        <td class="tituloForm">Departamento</td>
        <td><select name="departamento" id="departamento" class="{required:true}">{select_departamento} 
          </select></td>
        <td class="tituloForm">Ciudad</td>
        <td><select name="ciudad" id="ciudad" class="{required:true}">{select_municipio} 
          </select></td>
      </tr>
      <tr> 
        <td class="tituloForm">P&aacute;gina Web</td>
        <td><input type="text" name="pagina_web" id="pagina_web" value="{pagina_web}" /></td>
        <td class="tituloForm">Correo Electr&oacute;nico</td>
        <td>
          <input type="email" multiple pattern="^([\w+-.%]+@[\w-.]+\.[A-Za-z]{2,4},*[\W]*)+$" value="{correo_electronico}" name="correo_electronico" id="correo_electronico" />
          <!-- <input type="text" name="correo_electronico" id="correo_electronico" class="email" value="{correo_electronico}" /> -->
        </td>
      </tr>
      <tr>
        <td colspan="4"></td>
      </tr>
      <tr>
        <th colspan="4">Par&aacute;metros Adicionales</th>
      </tr>
      <tr>
        <td class="tituloForm">Tipo Facturaci&oacute;n</td>
        <td><select name="tipo_facturacion" id="tipo_facturacion" class="{required:true}">{select_tipofac}</select></td>
        <td class="tituloForm">D&iacute;as para pago</td>
        <td><input type="text" name="dias_para_facturar" id="dias_para_facturar" class="{required:true} digits" value="{dias_para_facturar}" /></td>
      </tr>
      <tr>
        <td class="tituloForm">Periodicidad (en d&iacute;as)</td>
        <td><input type="text" name="periodicidad" id="periodicidad" class="{required:true} digits" value="{periodicidad}" /></td>
        <td class="tituloForm">D&iacute;as de Gracia</td>
        <td><input type="text" name="dias_gracia" id="dias_gracia" class="{required:true} digits" value="{dias_gracia}" /></td>
      </tr>
      <tr>
        <td class="tituloForm">Circular 170</td>
        <td><input type="checkbox" name="cir170" id="cir170" {cir170} /></td>
        <td class="tituloForm">Comercial</td>
        <td><select name="vendedor" id="vendedor" class="{required:true}">{select_comercial}</select></td>
      </tr>
    </table>
    <center>
      <input type="submit" class="button small yellow2" name="enviar" id="enviar" value="Guardar Cliente" />
    </center>
    <input type="hidden" name="id" id="id" value="{id}" />
  </form>
</div>
<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");
  $('.noSeleccion').css('display', 'none');

  claseTipo($('#tipo').attr('value'));

  $().ready(function() {
    $("#datCliente").validate();
  });

  function listarClientes() {
    $.ajax({
      url: 'index_blank.php?component=clientes&method=listadoClientes',
      async: true,
      type: "POST",
      success: function(msm) {
        $('#componente_central').html(msm);
      }
    });
  }

  function enviarDatos() {
    var id = $('#id').attr('value');
    var documento = $('#numero_documento').attr('value');

    if(documento == '0') {
      alert('El número de documento no puede ser 0');
    } else if(id!=documento) {
      $.ajax({
        url: 'index_blank.php?component=clientes&method=clienteRepetido',
        data: 'documento='+documento,
        async: true,
        type: "POST",
        success: function(msm) {
          if(msm == 'valido') {
            salvarNuevoCliente();
          } else {
            alert('El número de documento que desea asignar\ncorresponde a otro cliente.\nPor favor verifique.');
          }
        }
      });	
    } else {
      salvarNuevoCliente();
    }
  }

  function salvarNuevoCliente() {
    $.ajax({
      url: 'index_blank.php?component=clientes&method=nuevoCliente',
      data: $('#datCliente').serialize(),
      async: true,
      type: "POST",
      success: function(msm) {
        $('#componente_central').html(msm);
      }
    });
  }

  function claseTipo(valor) {
    if(valor!=9) {
      $('#numero_documento').addClass('digits');
    } else {
      $('#numero_documento').removeClass('digits');
    }
  }
  
  $("#act_econ").autocomplete("index_blank.php?component=clientes&method=listadoActividades", {
    width: 260,
    selectFirst: true
  });
    
  $("#act_econ").result(function(event, data, formatted) {
    $("#actividad_economica").val(data[1]);
    $("#act_econ").attr("title", data[0]);
  });
    
  function cargarDocumentos() {
    alert('Cargaremos los documentos del cliente');
  }
 
  $("#departamento").change(function() {
    $.post("./scripts_index.php",{
      clase:'Levante',metodo:'traeCiudades',departamento:$("#departamento").val()
    },function(datos) {
      $("#ciudad").html(datos);
    })
  });
</script>