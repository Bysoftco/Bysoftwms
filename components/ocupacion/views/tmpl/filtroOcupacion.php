<style>

  body { font-size: 62.5%; }

  label { display: inline-block; }

  legend { padding: 0.5em; }

  fieldset fieldset label { display: block; }

  #frmocupacionb label { width: 170px; margin-left: 5px;} /*ancho de las etiquetas de los campos*/

  .ui-dialog .ui-state-error { padding: .3em; }

</style>

{COMODIN}

<link type="text/css" href="./integrado/cz_estilos/jquery.autocomplete.css" rel="stylesheet" />

<div id="winocupacion" title="Consulta Ocupación">

  <div id="frmocupacion">

    <p id="msgocupacion">Seleccione uno o varios filtros para delimitar resultados.</p>

    <form name="frmocupacionb" id="frmocupacionb" method="post" action="" style="width:100%;">

      <fieldset class="ui-widget ui-widget-content ui-corner-all">

        <legend class="ui-widget ui-widget-header ui-corner-all">

          <div id="filtroocupacion">Filtro de Ocupaci&oacute;n</div>

        </legend>  

        <p>

          <label>Por Cuenta de:</label>

          <input type="text" name="buscarClienteo" id="buscarClienteo" size="50" value="{cliente}" {soloLectura} />

        </p>

        <p> 

          <label>Nit:</label>

          <input type="text" name="nito" id="nito" value="{nito}" readonly=""/>

        </p>

        <p>

          <label>Documento de Transporte:</label>

          <input type="text" name="doctteo" id="doctteo" />

        </p>

        <p>

          <label>Orden:</label>

          <input type="text" name="doasignadoo" id="doasignadoo" />

        </p>

        <p>

          <label>Ubicaci&oacute;n:</label>

          <input type="text" name="ocupacion" id="ocupacion" />

          <input type="hidden" name="ocupaciono" id="ocupaciono" />

        </p>

        
      <p> 
        <label>Referencia:</label>
        
		<input type="text" name="referencias" id="referencias" size="50" /> 
		<input type="hidden" name="referenciao" id="referenciao" />
		<!--<input type="text" name="referencia_nombre" id="referencia_nombre{item}" value="[{codigo_ref}] {nombre_referencia}" size="70" /> -->
      </p>

      </fieldset>

    </form>

  </div>

</div>

<script>

	$(function() {

    $( "#winocupacion" ).dialog({

      autoOpen: false,

      resizable: false,

      height: 420,

      width: 600,

			modal: true,

			buttons: {

        "Consultar": function() {

          //Parámetros de envío de información

          $.ajax({

            url: 'index_blank.php?component=ocupacion&method=listadoOcupacion',

            type: "POST",

            async: false,

            data: $('#frmocupacionb').serialize(),

            success: function(msm) {

              $("#winocupacion").dialog("close");

              $('#componente_central').html(msm);

            }

          });

        }

      },

		});

  });

  

  // Muestra la Ventana de Filtro

  $( "#winocupacion" ).dialog( "open" );



  // Limpia los campos del formulario

  $("#frmocupacionb")[0].reset();

  $("#frmocupacionb input:hidden").val('').trigger('change');

  

	$(function() {

    $("#buscarClienteo").autocomplete("./index_blank.php?component=ocupacion&method=findCliente", {

      width: 300,

      selectFirst: false

    });



    $("#buscarClienteo").result(function(event, data, formatted) {

      $("#nito").val(data[1]);

      //Busca el Documento de Transporte

      $("#doctteo").autocomplete("./index_blank.php?component=ocupacion&method=findDocumento&cliente="+$("#nito").val(), {

        width: 300,

        selectFirst: false

      });

      

      $("#doctteo").result(function(event, data, formatted) {

        $("#doctteo").val(data[1]);

        $("#doasignadoo").val(data[2]);

      });

    });

  });

  

  $(function() {

    $("#ocupacion").autocomplete("./index_blank.php?component=ocupacion&method=findOcupacion", {

      width: 300,

      selectFirst: false

    });

    

    $("#ocupacion").result(function(event, data, formatted) {

      $("#ocupaciono").val(data[1]);

    });



    $("#referencias").autocomplete("./index_blank.php?component=ocupacion&method=findReferencia", {

      width: 300,

      selectFirst: false

    });

    

    $("#referencias").result(function(event, data, formatted) {

      $("#referenciao").val(data[1]);

    });

  });

</script>