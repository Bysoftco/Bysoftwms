<?php
	
	 $conexion = mysql_connect("localhost","root"); 
 //$conexion = mysql_connect("174.120.117.194","paezasoc_yencyjo","yencyjohana"); // 	Conexion con el servidor
 //$conexion = mysql_connect("174.120.117.194","paezasoc_admin","administracion"); // 	Conexion con el servidor
 //mysql_select_db("paezasoc_procli", $conexion);//Conexion con la Base de Datos
 mysql_select_db("procli", $conexion);//Conexion con la Base de Datos
   
   if (!$conexion) {
        echo "<CENTER>
              Problemas de conexion con la base de datos.
              </CENTER>";
        exit;

     
	
	
	} else {
		// Is there a posted query string?
		if(isset($_POST['queryString'])) {
			$queryString = $_POST['queryString'];
			
			// Is the string length greater than 0?
			
			if(strlen($queryString) >0) {
				// Run the query: We use LIKE '$queryString%'
				// The percentage sign is a wild-card, in my example of countries it works like this...
				// $queryString = 'Uni';
				// Returned data = 'United States, United Kindom';
				
				// YOU NEED TO ALTER THE QUERY TO MATCH YOUR DATABASE.
				// eg: SELECT yourColumnName FROM yourTable WHERE yourColumnName LIKE '$queryString%' LIMIT 10
				$sql="SELECT id,value FROM countries WHERE value LIKE '%$queryString%' LIMIT 10";
				//$query = $db->query("SELECT your_column FROM your_db_table WHERE your_column LIKE '$queryString%' LIMIT 10");
				//echo $sql;
				//die();
				$result = mysql_query ($sql,$conexion);
				if($result) {
					// While there are results loop through them - fetching an Object (i like PHP5 btw!).
					while ($row=mysql_fetch_array($result)) {
						// Format the results, im using <li> for the list, you can change it.
						// The onClick function fills the textbox with the result.
						
						// YOU MUST CHANGE: $result->value to $result->your_colum
	         			echo '<li onClick="fill(\''.$row[value].'\');">'.$row[value].'</li>';
	         		}
				} else {
					echo 'ERROR: There was a problem with the query.';
				}
			} else {
				// Dont do anything.
			} // There is a queryString.
		} else {
			echo 'There should be no direct access to this script!';
		}
	}
?>