<?php session_start();


	//include("../conexion.php");
	//$conexion=conexionBD() ;
//Connect to database from here
$link = mysql_connect('localhost', 'root', ''); 
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
//select the database | Change the name of database from here
mysql_select_db('integrado'); 

//get the posted values
$user_name=htmlspecialchars($_POST['user_name'],ENT_QUOTES);
$pass=md5($_POST['password']);
$pass=$_POST['password'];
//now validating the username and password
$sql="SELECT login,clave FROM usuarios WHERE login ='".$user_name."'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

//if username exists
if(mysql_num_rows($result)>0)
{
	//compare the password
	if(strcmp($row['clave'],$pass)==0)
	{
		echo "yes";
		//now set the session from here if needed
		$_SESSION['u_name']=$user_name; 
	}
	else
		echo "no"; 
}
else
	echo "no"; //Invalid Login


?>