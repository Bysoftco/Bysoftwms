<? session_start() ?>
<script>
function actualizar (id,nom) {
window.opener.document.new_do.id.value=id;
window.opener.document.new_do.manifiesto.value=manifiesto;
//window.opener.document.new_do.nom.value=nom;
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
 
  $sql="SELECT  manifiesto 
       FROM ordenes_ingresos
	   WHERE  manifiesto like '%".$criterio	."%' ";
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
    <td bgcolor="#e0e5f2"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:actualizar('<? echo $row["manifiesto"]?>','<? echo $row["razon_social"]?>')"><? echo $row["manifiesto"]?></a></font></td>
    <td bgcolor="#e0e5f2"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row["razon_social"];?></font></td>
  </tr>
  <?
							}
							
							
							}else {
							 $sql="SELECT  manifiesto 
                                  FROM ordenes_ingresos
	                              WHERE  manifiesto=$criterio";
 	                             //$result = pg_Exec ($conexion, $sql);
								 $result = mysql_query($sql,$conexion);
								 //$row=pg_fetch_array($result);
								 $row=mysql_fetch_array($result);
							      //$id= $row["numero_documento"];
								  var_dump ($manifiesto);
								    echo $row->manifiesto.'ggg';
								  
								  
							?>
  <script>
							id=<? echo $row["manifiesto"] ?>;
							nom=<? echo $row["manifiesto"] ?>;
							//alert(nom);
                           //actualizar (id,'<? echo $row["razon_social"] ?>') ;
                            </script>
  <?
							
							}
                     ?>
</table>
<p align="right"><img src="../imagenes/bysoftpie.gif" width="96" height="35" align="absbottom"></p>
