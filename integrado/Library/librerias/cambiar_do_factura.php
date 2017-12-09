<? session_start() ;?>
<script>
function actualizar (codigo, fecha, documento) {
window.opener.document.new_do.orden.value=codigo;
window.opener.document.new_do.orden_ver.value=codigo;
//window.opener.document.new_do.fecha_ingreso.value=fecha;
window.opener.document.new_do.documento_transporte_ver.value=documento;
window.opener.document.new_do.documento_transporte.value=documento;
window.close()
}
</script>
<?
//include("../librerias/autentica_acceso.php"); 
include("../librerias/conexion.php");
include("../librerias/sql.php");  
$conexion=conexionBD() ;
$sede=$_SESSION['sede'];
  $sql="SELECT  DISTINCT 
            ordenes.sede,
			ordenes.anio,
			ordenes.fecha,
			ordenes.consecutivo,
			ordenes.codigo,
			ordenes.cliente,
			ordenes.tipo_ingreso,
			ordenes.documento
			
       FROM ordenes,
			clientes cli,
			tipos_operacion tiope
			
	   WHERE  ordenes.cliente = $criterio 
	     AND  ordenes.sede='$sede'
         AND  cli.numero_documento = ordenes.cliente
         AND  ordenes.tipo_ingreso = tiope.codigo
		 AND  ordenes.documento<>''";
		
 	               $result = mysql_query($sql,$conexion);
				   //$result=pg_Exec($conexion,$sql);
				   //$result=mysql_query($sql,$conexion);
	?>
<strong><font color="#000066" size="1" face="Verdana, Arial, Helvetica, sans-serif">DOS encontrados para el cliente</font><font color="#004080" size="1" face="Verdana, Arial, Helvetica, sans-serif"></font></strong><font color="#004080" size="1" face="Verdana, Arial, Helvetica, sans-serif"><? echo nombre_cliente($criterio,$conexion);?></font> 
<table width="100%" border="1">
  <tr bgcolor="#CCCCCC"> 
    <td width="14%"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">DO</font></strong></td>
    <td width="36%"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Documento Transporte</font></strong></td>
    <td width="50%"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Fecha de Ingreso</font></strong></td>
  </tr>
  <?
		//while ($row=pg_fetch_array($result)) {
		while ($row=mysql_fetch_array($result)) {
  ?>
  <tr> 
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row["codigo"]?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:actualizar('<? echo $row["codigo"]?>','<? echo $row["fecha"]?>','<? echo $row["documento"]?>')"><? echo $row["documento"];?></a></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row["fecha"];?></font></td>
  </tr>
  <?
	}
							
  ?>
</table>
<p align="right"><img src="../imagenes/bysoftpie.gif" width="96" height="35" align="absbottom"></p>
