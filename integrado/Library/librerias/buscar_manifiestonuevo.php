<? session_start() ;?>
<script>
function actualizar (id,nom,num_do) {
window.opener.document.new_do.num_do.value=id;
//window.opener.document.new_do.nom.value=nom;
//window.opener.document.new_do.num_do.value=num_do;
window.close()
}
</script>
<?
//include("autentica_acceso.php"); 
include("conexion.php");
include("sql.php");  
$conexion=conexionBD() ;
$sede=$_SESSION['sede'];
  $sql="SELECT  * 
       FROM ordenes_ingresos
	   WHERE  manifiesto = $criterio 
	   AND sede='$sede'";
 	               $result = mysql_query($sql,$conexion);
				   //$result=pg_Exec($conexion,$sql);
				   //$result=mysql_query($sql,$conexion);
	?>
<strong><font color="#000066" size="1" face="Verdana, Arial, Helvetica, sans-serif">Manifiestos encontrados para el cliente</font><font color="#004080" size="1" face="Verdana, Arial, Helvetica, sans-serif"></font></strong><font color="#004080" size="1" face="Verdana, Arial, Helvetica, sans-serif"><? echo manifiesto($criterio,$conexion);?></font> 
<table width="88%" border="1">
  <tr bgcolor="#CCCCCC"> 
    <td><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Manifiesto</font></strong></td>
    <!--td><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Fecha de Ingreso</font></strong></td-->
  </tr>
  <?
		//while ($row=pg_fetch_array($result)) {
		while ($row=mysql_fetch_array($result)) {
  ?>
  <tr> 
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:actualizar('<? echo $row["manifiesto"]?>','<? echo $row["razon_social"]?>')"><? echo $row["codigo"]?></a></font></td>
    <td>/<font size="1" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row["fecha"];?></font></td>
  </tr>
  <?
	}
							
  ?>
</table>
						<p align="right"><img src="file:///C|/AppServ/www/zona_franca/imagenes/bysoftpie.gif" width="96" height="35" align="absbottom"></p>
