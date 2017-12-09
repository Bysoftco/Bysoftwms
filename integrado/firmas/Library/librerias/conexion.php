<!-- Funcion que realiza la conexion con SQL SERVER --> 



<?php 

   



   function conexionBD() 

   { 

       



         



	$conexion =mysql_connect ("localhost", "root","bysoft") or die ('I cannot connect to the database because: ' . mysql_error());

		mysql_select_db ("integrado"); 

   

   if (!$conexion) {

        echo "<CENTER>

              Problemas de conexion con la base de datos.

              </CENTER>";

        exit;



      }

   

   return $conexion;

  

   } 

    

  

   ?> 



