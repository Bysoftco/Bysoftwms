<? session_start() ?>
<script>
function actualizar (id,nom,regi,autoretenedor,subcentro,regimen) 
{
  window.opener.document.forms[0].id.value=id;
  window.opener.document.forms[0].nom.value=nom;
  window.close()
}

function actualizarSia (id,nom,regi,autoretenedor,subcentro,regimen) 
{ 
  window.opener.document.new_do.id.value=id;
  window.opener.document.new_do.nom.value=nom;
  window.opener.document.new_do.indicador_retener.value=regi; //regi indica si se retiene o no cambio 11/02/2009
  if(regimen==2){tipoCliente=1;}
  else{tipoCliente=0;}
  window.opener.document.new_do.indica_comun.value=tipoCliente; 
  window.opener.document.new_do.subcentro_costo.value=subcentro;
  window.close()
}
</script>
<?



include("../librerias/autentica_acceso.php"); 
include("../librerias/conexion.php");
include("../librerias/sql.php");  
$conexion=conexionBD() ;
$criterio=$_GET[criterio];
$parametro=$_GET[parametro];
$sia = $_GET[sia];

  if($parametro==0)//Consulta por Nombre
{

  $sql="SELECT  * 
       FROM clientes 
	   WHERE  razon_social like '%".$criterio	."%' 
	     AND tipo = 4";
 	          
				   $result = mysql_query ($sql,$conexion);
	
					?>
<font size="1" face="Verdana, Arial, Helvetica, sans-serif">Los Clientes Encontrados con 
el Criterio fueronnnn: </font> 
<table width="75%" border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
  <tr bgcolor="efe2ec"> 
    <td width="19%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Identificaci&oacute;n</font></td>
    <td width="81%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Nombre</font></td>
  </tr>
  <?
							while ($row=mysql_fetch_array($result)) {
							?>
  <tr> 
    <td bgcolor="#e0e5f2"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:actualizar('<? echo $row["numero_documento"]?>','<? echo $row["razon_social"]?>','<? echo $row["tipo"]?>')"><? echo $row["numero_documento"]?></a></font></td>
    <td bgcolor="#e0e5f2"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row["razon_social"];?></font></td>
  </tr>
  <?
							}
							
							}else {
							 $sql="SELECT  clientes.* 
                                     FROM clientes
										  
	                                WHERE clientes.razon_social like '%".$criterio."%'
									  ";
 	                           
								 $result = mysql_query($sql,$conexion);
								 
							?>
<font size="1" face="Verdana, Arial, Helvetica, sans-serif">Los Clientes Encontrados con el Criterio fueronhgjh: </font> 
<table width="75%" border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
  <tr bgcolor="efe2ec"> 
    <td width="19%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Identificaci&oacute;n</font></td>
    <td width="81%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Nombre</font></td>
  </tr>
  
  
   <?
							//while ($row=pg_fetch_array($result)) {
							while ($row=mysql_fetch_array($result)) {
								switch($row["regimen"]){
									case 1://regimen del cliente Gran Contribuyente
										$retiene=1;
									break;			
									case 2://regimen Comun
							 			$retiene=1;
									break;
									case 3://regimen Simplificado
							 			$retiene=0;
									break;
								default:
										$retiene=0;
								}
								$row["regimen"]
							?>
							<? $subcentro=$row["subcentro_costo"];
							   if($_SESSION['sede']==25)
							   $subcentro=$row["subcentro_alterno"];
							?>

<tr> 
    <td bgcolor="#e0e5f2"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:actualizar('<? echo $row["numero_documento"]?>','<? echo $row["razon_social"]?>','<? echo $retiene?>','<? echo $row["autoretenedor"]?>','<? echo $subcentro?>','<? echo $row["regimen"]?>')"><? echo $row["numero_documento"]?></a></font></td>
    <td bgcolor="#e0e5f2"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row["razon_social"];?></font></td>
  </tr>
  
  <? }?>
                     <? }?>
</table>
<p align="right"><img src="../imagenes/bysoftpie.gif" width="96" height="35" align="absbottom"></p>
