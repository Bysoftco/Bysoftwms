<? session_start() ?>
<script>
function actualizar (id,nom,regi,autoretenedor) {
//alert(window.opened.document);
window.opener.document.hacerDetalle.nit_tercero.value=id;
window.opener.document.hacerDetalle.tercero.value=nom;
//window.opener.document.new_do.regimen.value=regi;
//window.opener.document.new_do.autoretenedor.value=autoretenedor;
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
  if($parametro==0)//Consulta por Nombre
{

  
  $sql="SELECT  * 
       FROM clientes 
	   WHERE  razon_social like '%".$criterio	."%' ";
 	               //$result = pg_Exec ($conexion, $sql);
				   $result = mysql_query ($sql,$conexion);

					?>
<font size="1" face="Verdana, Arial, Helvetica, sans-serif">Los Clientes Encontrados con 
el Criterio fueron: </font> 
<table width="75%" border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
  <tr bgcolor="efe2ec"> 
    <td width="19%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Identificaci&oacute;n</font></td>
    <td width="81%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Nombre</font></td>
  </tr>
  <?
							//while ($row=pg_fetch_array($result)) {
							while ($row=mysql_fetch_array($result)) {
							?>
  <tr> 
    <td bgcolor="#e0e5f2"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:actualizar('<? echo $row["numero_documento"]?>','<? echo $row["razon_social"]?>','<? echo $row["regimen"]?>','<? echo $row["autoretenedor"]?>')"><? echo $row["numero_documento"]?></a></font></td>
    <td bgcolor="#e0e5f2"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row["razon_social"];?></font></td>
  </tr>
  <?
							}
							
							
							}else {
							 $sql="SELECT  * 
                                  FROM clientes 
	                              WHERE  numero_documento=$criterio ";
								 
 	                             //$result = pg_Exec ($conexion, $sql);
								 $result = mysql_query($sql,$conexion);
								 //$row=pg_fetch_array($result);
								 $row=mysql_fetch_array($result);
							      //$id= $row["numero_documento"];
								  
							?>
  <script>
							id=<? echo $row['numero_documento'] ?>;
							regimen=<? echo $row['regimen'] ?>;
							 autoretenedor =<? echo $row['autoretenedor'] ?>;
							//alert(nom);
                           actualizar (id,'<? echo $row["razon_social"] ?>',regimen,autoretenedor) ;
                            </script>
  <?
							
							}
                     ?>
</table>
<p align="right"><img src="../imagenes/bysoftpie.gif" width="96" height="35" align="absbottom"></p>
