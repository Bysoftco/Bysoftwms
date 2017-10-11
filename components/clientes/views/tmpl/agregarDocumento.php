{COMODIN}
<div id="archiDocumento"><p>
  <form class="frmDocumentos" name="frmDocumentos" id="frmDocumentos" method="post" action="">
	  <fieldset class="ui-widget ui-widget-content ui-corner-all">
      <table align="center" width="95%" cellpadding="0" cellspacing="0" id="tabla_general" style="margin: 15px;">
        <tr>
          <th colspan="3">Cargar Documentos Anexos</th>
        </tr>
        <tr>         
          <td class="tituloForm" width="30%">Seleccionar Documentos:</td>
          <td><input id="archivos" type="file" name="archivos[]" multiple="multiple" onchange="realizado('{cliente}');" /></td>
        </tr>
        <tr>
          <td colspan="2" id="cargados">
 						 <!-- Mensaje archivos cargados -->
          </td>
        </tr>
      </table>
	  </fieldset>
  </form></p>
</div>
<script>
  var numOrden = $("#do_asignado").val();

  function realizado(numdoc) {
		var archivos = document.getElementById("archivos");//Damos el valor del input tipo file
		var archivo = archivos.files; //Obtenemos el valor del input (los archivos) en modo de arreglo
    var numdocumento = numdoc;
    
		/* Creamos el objeto que hara la petición AJAX al servidor, debemos de validar 
			 si existe el objeto "XMLHttpRequest" ya que en internet explorer viejito no esta,
			 y si no esta usamos "ActiveXObject" */ 
		if(window.XMLHttpRequest) {
			var Req = new XMLHttpRequest(); 
		} else if(window.ActiveXObject) { 
			var Req = new ActiveXObject("Microsoft.XMLHTTP"); 
		}

		//El objeto FormData nos permite crear un formulario pasándole clave/valor para poder enviarlo, 
		//este tipo de objeto ya tiene la propiedad multipart/form-data para poder subir archivos
		var data = new FormData();

		//Como no sabemos cuántos archivos subirá el usuario, iteramos la variable y al
		//objeto de FormData con el método "append" le pasamos clave/valor, usamos el indice "i" para
		//que no se repita, si no lo usamos solo tendrá el valor de la última iteración
		for(i=0; i<archivo.length; i++) {
			data.append('archivo'+i,archivo[i]);
		}

		//Pasándole la url a la que haremos la petición
		Req.open("POST", "index_blank.php?component=clientes&method=cargarArchivos&numdoc="+numdocumento, true);

		/* Le damos un evento al request, esto quiere decir que cuando termine de hacer la petición,
			 se ejecutara este fragmento de código */ 

		Req.onload = function(Event) {
			//Validamos que el status http sea Ok 
			if(Req.status == 200) {
				//Recibimos la respuesta de php
				var msg = Req.responseText;

        $("#cargados").append(msg);
        
  		  //Proceso de validación y de inserción a la base de datos
		    ProcesaDB(numdocumento);
        
			} else {
				alert("No se recibe una respuesta");
				console.log(Req.status); //Vemos que pasó. 
			} 
		};

		//Enviamos la petición 
		Req.send(data);
	}
  
	function ProcesaDB(numdoc) {
    var numdocumento = numdoc;
		$.ajax({
			url: 'index_blank.php?component=clientes&method=cargarDocumentos',
			data: {
        documento: numdocumento
      },
			async: true,
			type: "POST",
			success: function(msm) {
				jQuery(document.body).overlayPlayground('close');void(0);
        $("#documentosCliente").html(msm);
			}
		});
	}
</script>