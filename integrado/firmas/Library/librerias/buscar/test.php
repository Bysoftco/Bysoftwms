<?php


	
	$aResults = array();
	
	
	
		
	//include("../conexion.php");
	//$conexion=conexionBD() ;
	 $conexion = mysql_connect("localhost","root");
	  mysql_select_db("integrado", $conexion);//Conexion con la Base de Datos
	  
   	$input = strtolower( $_GET['input'] );
	 $sql="SELECT numero_documento,razon_social FROM clientes WHERE razon_social LIKE '%$input%' LIMIT 10";
	 $result = mysql_query ($sql,$conexion);
	 if($result) {
					// While there are results loop through them - fetching an Object (i like PHP5 btw!).
					while ($row=mysql_fetch_array($result)) {
						// Format the results, im using <li> for the list, you can change it.
						// The onClick function fills the textbox with the result.
						$aResults[] = array( "id"=>($row['numero_documento']) ,"value"=>htmlspecialchars($row['razon_social']), "info"=>htmlspecialchars($row['numero_documento']) );
						// YOU MUST CHANGE: $result->value to $result->your_colum
	         			//echo '<li onClick="fill(\''.$row[numero_documento].'*'.$row[razon_social].'\');">'.$row[razon_social].'</li>';
	         		}
				} else {
					echo 'ERROR: problemas al listar los clientes.';
				}

	//$conexion=conexionBD() ;
	//echo 'input'.$input;
	
	
	
	
	
	
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header ("Pragma: no-cache"); // HTTP/1.0
	
	sleep(2);
	
	if (isset($_REQUEST['json']))
	{
		header("Content-Type: application/json");
	
		echo "{\"results\": [";
		$arr = array();
		for ($i=0;$i<count($aResults);$i++)
		{
			$arr[] = "{\"id\": \"".$aResults[$i]['id']."\", \"value\": \"".$aResults[$i]['value']."\", \"info\":\"".$aResults[$i]['info']."\"}";
		}
		echo implode(", ", $arr);
		echo "]}";
	}
	else
	{
		header("Content-Type: text/xml");

		echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?><results>";
		for ($i=0;$i<count($aResults);$i++)
		{
			echo "<rs id=\"".$aResults[$i]['id']."\" info=\"".$aResults[$i]['info']."\">".$aResults[$i]['value']."</rs>";
		}
		echo "</results>";
	}
?>