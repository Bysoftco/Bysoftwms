{COMODIN}
<div style="height: 10px;"></div>
<div id="envioDatos">
<fieldset class="ui-widget ui-widget-content ui-corner-all">
  <legend class="ui-widget ui-widget-header ui-corner-all">
		M&eacute;todo de Carga de Seriales
	</legend>
	<div class="margenes">
  	Seleccione el m&eacute;todo para la carga de Seriales<br /><br />
		<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_seriales">
			<tr>
				<th colspan="2">DETALLE DE SERIALES</th>
			</tr>
			<tr>
				<td style="width:45%">M&eacute;todo para Cargar Seriales:</td>
				<td>
					<select name="idmetodo" id="idmetodo" onchange="javascript:captura();">
          	{select_metodo}
					</select>
				</td>
      </tr>
      <tr id="farchivo" style="display:none;">
      	<td colspan="2">
        	<p>
          	<div id="subir">
  						<input id="archivos" type="file" name="archivos[]" multiple="multiple" onchange="seleccionado();" />
						</div>
            <div id="cargados">
 						 <!-- Mensaje archivos cargados -->
						</div>
          </p>
				</td>
      </tr>
      <tr id="lectorac" style="display:none;">
        <td colspan="2">
        	<p>
          	<div id="lectora" style="width:auto; display:table-cell; vertical-align:middle;">
            	<div>
								C&oacute;digos de Seriales le&iacute;dos:
								<textarea id="seriales" rows="6" cols="64" style="resize: none; background-color: #CEDAE8;"></textarea>
              </div>
							<div style="text-align:center;"><button type="button" class="submit" id="btnSeriales">Cargar</button></div>
            </div>
          </p>
				</td>
			</tr>
		</table>
	</div>
</fieldset>
</div>
<input type="hidden" name="numorden" id="numorden" value="{numorden}"  />
<input type="hidden" name="codreferencia" id="codreferencia" value="{codreferencia}" />
<input type="hidden" name="numordenfull" id="numordenfull" value="{numordenfull}" />
<div style="height: 20px;"></div>
<script>
	$("#btnSeriales").button({
    text: true,
    icons: {
      primary: "ui-icon-arrowthick-1-n"
    }
  })
  .click(function() {
		ProcesaDB('');
  });
	
  function seleccionado(){ 
		var archivos = document.getElementById("archivos");//Damos el valor del input tipo file
		var archivo = archivos.files; //Obtenemos el valor del input (los arcchivos) en modo de arreglo

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
		Req.open("POST", "index_blank.php?component=seriales&method=cargarArchivo", true);

		/* Le damos un evento al request, esto quiere decir que cuando termine de hacer la petición,
			 se ejecutara este fragmento de código */ 

		Req.onload = function(Event) {
			//Validamos que el status http sea Ok 
			if(Req.status == 200) {
				//Recibimos la respuesta de php
				var msg = Req.responseText;
				
				$("#cargados").append(msg);
        
  		  //Proceso de validación y de inserción a la base de datos
		    ProcesaDB(msg);
        
			} else {
				alert("No se recibe una respuesta");
				console.log(Req.status); //Vemos que paso. 
			} 
		};

		//Enviamos la petición 
		Req.send(data);
	}

	function captura() {
		switch($('#idmetodo').val()) {
			case '1': //Solicita datos del archivo de seriales
				document.getElementById("lectorac").style.display = "none";
				document.getElementById("farchivo").style.display = "";
				break;
			case '2': //Solicita lectora de códigos de seriales
				document.getElementById("farchivo").style.display = "none";
				document.getElementById("lectorac").style.display = "";			
				break;			
			default: //Opción al no seleccionar un método
				document.getElementById("farchivo").style.display = "none";
				document.getElementById("lectorac").style.display = "none";
				break;
		}
	}
	
	function ProcesaDB(nomfile) {
    var nombrefile = nomfile;

		$.ajax({
			url: 'index_blank.php?component=seriales&method=cargarSeriales',
			data: {
				idmetodo: $('#idmetodo').val(),
				numorden: $("#numorden").attr("value"),
				codreferencia: $("#codreferencia").attr("value"),
				numordenfull: $("#numordenfull").attr("value"),
				seriales: $("#seriales").attr("value"),
				nombrefile: nombrefile
			},
			async: true,
			type: "POST",
			success: function(msm) {
				jQuery(document.body).overlayPlayground('close');void(0);
				$('div#ventana_seriales').html(msm);
			}
		});
	}	
</script>