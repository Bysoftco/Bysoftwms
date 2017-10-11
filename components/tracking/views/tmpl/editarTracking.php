{COMODIN}
<link rel="stylesheet" type="text/css" href="./integrado/cz_estilos/jquery.autocomplete.css" />
<div id="infoTracking" style="padding-top: 15px;">
  <form name="datTracking" id="datTracking" method="post" action="javascript:enviarDatos()">
    <fieldset class="ui-corner-all">
      <legend>Informaci&oacute;n de Tracking</legend>
      <p>&nbsp;Los campos marcados con un asterisco (<font color="#FF0000">*</font>) son obligatorios.</p>
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
          	<input type="text" name="email" id="email" value="{destino}" 
            	style="width:617px;height:12px;text-transform:lowercase;" />
          </td>
				</tr>
        <tr>
        	<th style="text-align:left;">Asunto:</th>
          <td><input type="text" name="asunto" id="asunto" value="{asunto}" style="width:617px;height:12px;text-transform:none;"/></td>
        </tr>
        <tr>
        	<th style="text-align:left;">Adjuntos:</th>
          <td>
            <input type="text" name="adjunto" id="adjunto" value="{adjunto}" style="width:617px;height:12px;text-transform:none;"/>
          </td>
        </tr>
        <tr>
        	<th colspan="2" style="text-align:left;">Mensaje <font color="#FF0000">*</font>&nbsp;:</th>
        </tr>
        <tr>
        	<td colspan="2" style="width:617px">
            <textarea name="mensaje" id="mensaje" rows="15" cols="83%" style="resize:none;" class="{required:true}">{mensaje}</textarea>
          </td>
        </tr>
			</table>
		</fieldset>
    <p><center>
      <input type="submit" class="button small yellow2" name="enviar" id="enviar" value="Enviar" />
      <input id="archivos" type="file" name="archivos[]" multiple="multiple" onchange="seleccionar();" />
    </center></p>
    <input type="hidden" name="sedex" id="sedex" value="{sede}" />
    <input type="hidden" name="do_asignado" id="do_asignado" value="{do_asignado}" />
    <input type="hidden" name="doc_tte" id="doc_tte" value="{doc_tte}" />
    <input type="hidden" name="por_cuenta" id="por_cuenta" value="{por_cuenta}" />
    <input type="hidden" name="razon_social" id="razon_social" value="{razon_social}" />
		<input type="hidden" name="remite" id="remite" value="{remite}" />
    <input type="hidden" name="creador" id="creador" value="{creador}" />
    <input type="hidden" name="wadjunto" id="wadjunto" value="0" />
  </form>
</div>
<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");
  $('.noSeleccion').css('display', 'none');
  $('.noElimina').css('display', 'none');
  	
  $().ready(function() {
    $("#datTracking").validate();
  });
	
  function listarTracking() {
    $.ajax({
      url: 'index_blank.php?component=tracking&method=listadoTracking',
      async: true,
      type: "POST",
      success: function(msm) {
        $('#componente_central').html(msm);
      }
    });
  }

  function enviarDatos() {
		var fecha = new Date;
		var dd = fecha.getDate();
		var mm = fecha.getMonth()+1;
		var aaaa = fecha.getFullYear();
		
		dd = dd < 10 ? '0' + dd : dd;
		mm = mm < 10 ? '0' + mm : mm;
		var fecha = aaaa + '-' + mm + '-' + dd;

    $.ajax({
      url: 'index_blank.php?component=tracking&method=nuevoTracking',
      data: {
				id: $("#id").val(),
				sede : $("#sedex").val(),
				do_asignado: $("#do_asignado").val(),
        doc_tte: $("#doc_tte").val(),
				por_cuenta: $("#por_cuenta").val(),
        razon_social: $("#razon_social").val(),
				fecha: fecha,
				remite: $("#remite").val(),
				destino: $("#email").val(),
				asunto: $("#asunto").val(),
        adjuntos: $("#adjunto").val(),
				mensaje: $("#mensaje").val(),
				creador: $("#creador").val(),
				forma: 'Manual',
        wadjunto: $("#wadjunto").val()
			},
      async: true,
      type: "POST",
      success: function(msm) {
				jQuery(document.body).overlayPlayground('close');void(0);
        $('#componente_central').html(msm);
      }
    });
  }
  
  function seleccionar() {
		var archivos = document.getElementById("archivos");//Damos el valor del input tipo file
		var archivo = archivos.files; //Obtenemos el valor del input (los archivos) en modo de arreglo

		/* Creamos el objeto que hara la petición AJAX al servidor, debemos de validar 
			 si existe el objeto “ XMLHttpRequest” ya que en internet explorer viejito no esta,
			 y si no esta usamos “ActiveXObject” */ 
		if(window.XMLHttpRequest) {
			var Req = new XMLHttpRequest(); 
		} else if(window.ActiveXObject) { 
			var Req = new ActiveXObject("Microsoft.XMLHTTP"); 
		}

		//El objeto FormData nos permite crear un formulario pasandole clave/valor para poder enviarlo, 
		//este tipo de objeto ya tiene la propiedad multipart/form-data para poder subir archivos
		var data = new FormData();

		//Como no sabemos cuantos archivos subira el usuario, iteramos la variable y al
		//objeto de FormData con el metodo "append" le pasamos clave/valor, usamos el indice "i" para
		//que no se repita, si no lo usamos solo tendra el valor de la ultima iteración
		for(i=0; i<archivo.length; i++) {
			data.append('archivo'+i,archivo[i]);
		}

		//Pasándole la url a la que haremos la petición
		Req.open("POST", "index_blank.php?component=tracking&method=cargarArchivo", true);

		/* Le damos un evento al request, esto quiere decir que cuando termine de hacer la petición,
			 se ejecutara este fragmento de código */ 

		Req.onload = function(Event) {
			//Validamos que el status http sea Ok 
			if(Req.status == 200) {
				//Recibimos la respuesta de php
				var msg = Req.responseText;
        
        $("#adjunto").attr('value',msg.replace(/\s+$/,''));
        alert("Fue adjuntado al correo el documento: { "+msg+" }");
        $("#wadjunto").attr('value','1');
			} else {
				alert("No se recibe una respuesta");
				console.log(Req.status); //Vemos que paso. 
			} 
		};

		//Enviamos la petición 
		Req.send(data);
  }
</script>