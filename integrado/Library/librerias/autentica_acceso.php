<?php
session_start();
//***************************************************************************************************************************************
//ARCHIVO QUE CONTROLA QUE EL ACCESO A UNA PAGINA ESTE VALIDADO POR UN LOGIN Y UNA CONTRASEÑA
//VERSION 1.0
//ESCRITO POR FREDY AREVALO
//AGOSTO DE 2005
//***************************************************************************************************************************************
//***************************************************************************************************************************************
$autorizado=$_SESSION['usuario'];
$actual=time();
if(!isset($autorizado)||empty($autorizado)){
?>
	<script>
		alert('Debe iniciar una sesión para poder Ingrezar a esta pagina')
		location='../index.php?close=1'
	</script>
<?
}else{}
?>