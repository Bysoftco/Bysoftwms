<?php
echo $sql;
/*
**	Paginator 
**	Paginaci�n de resultados de consultas MySql
**
**	Versi�n 1.4
**
**	Nombre de archivo	:
**		paginator.inc.php
**
**	Autor	:
**		Jorge Pinedo Rosas (jpinedo)	<jpinedo@ing.udep.edu.pe>
**		Con la colaboraci�n de los usuarios del foro de PHP de www.forosdelweb.com
**		Especialmente de dooky que poste� el c�digo en el que se basa este script.	
**
**	Versi�n 1.0	(30/11/2003):	-Versi�n inicial.
**	Versi�n 1.1	(12/01/2004):	-Se agreg� la propagaci�n de las variables que llegan al script v�a url ($_GET)
**							 	 en los enlaces de navegaci�n por las p�ginas.
**								-Se optimiz� el conteo del total de registros utilizando el COUNT(*) de MySql.
**	Versi�n 1.3	(10/08/2004):	-Se agreg� la opci�n de limitar el n�mero enlaces que se mostrar�n en la barra 
**								 de navegaci�n. Gracias a la recomendaci�n de Jorge Camargo (andinistas)
**								-Se agreg� la opci�n de elegir si se quiere mostrar los mensajes de error de mysql.
**								-Se agreg� la generaci�n de informaci�n de la p�gina actual en una cadena que contiene
**								 el total de registros de la consulta y el primer y �ltimo registro de la p�gina actual.
** 	Versi�n 1.4	(12/08/2004):	-Se agreg� la opci�n de elegir qu� variables se quiere propagar por url. Se ha utilizado
**								 la misma forma de hacerlo que utiliza la Clase Paginado de webstudio.
**								 (http://www.forosdelweb.com/showthread.php?t=65528). Gracias a la acalmaci�n popular :)
** 	Versi�n 1.4.1 (06/09/2004):	-Corregido el bug en la propagaci�n de variables por GET al renombrar la variable
** 								 "pg" por "_pagi_pg". Esto s�lo ocurre en la versi�n 1.4. Gracias a jean pierre m. por
**								 reportar el bug.
**
**	Descripci�n :
**		Devuelve el resultado de una consulta sql por p�ginas, as� como los enlaces de navegaci�n respectivos.
**		Este script ha sido pensado con fines did�cticos, por eso la gran cantidad de comentarios.	
**
**	Licencia : 
**		GPL con las siguientes extensiones:
** 			*Uselo con el fin que quiera (personal o lucrativo).
**			*Si encuentra el c�digo de utilidad y lo usa, mandeme un mail si lo desea o deje un comentario en la p�gina 
**			 de documentaci�n.
**			*Si mejora el c�digo o encuentra errores, hagamelo saber al mail indicado o deje un comentario en la p�gina 
**			 de documentaci�n.
**
**	Documentaci�n y ejemplo de uso:
**		http://jpinedo.webcindario.com
**-----------------------------------------------------------------------------------------------------------*/
 

/**
 * Variables que se pueden definir antes de incluir el script v�a include():
 * ------------------------------------------------------------------------
 * $_pagi_sql 				OBLIGATORIA.	Cadena. Debe contener una sentencia sql v�lida (y sin la cl�usula "limit").
 
 * $_pagi_cuantos			OPCIONAL.		Entero. Cantidad de registros que contendr� como m�ximo cada p�gina.
 											Por defecto est� en 20.
											
 * $_pagi_nav_num_enlaces	OPCIONAL		Entero. Cantidad de enlaces a los n�meros de p�gina que se mostrar�n como 
 											m�ximo en la barra de navegaci�n.
											Por defecto se muestran todos.
											
 * $_pagi_mostrar_errores	OPCIONAL		Booleano. Define si se muestran o no los errores de MySQL que se puedan producir.
 											Por defecto est� en "true";
											
 * $_pagi_propagar			OPCIONAL		Array de cadenas. Contiene los nombres de las variables que se quiere propagar
 											por el url. Por defecto se propagar�n todas las que ya vengan por el url (GET).
--------------------------------------------------------------------------
*/


/*
 * Verificaci�n de los par�metros obligatorios y opcionales.
 *------------------------------------------------------------------------
 */
 $_pagi_sql = str_replace("*","'",$_pagi_sql);//para evitar error  debido a las comillas interpretadas por el servidor como  eslas 
 $_pagi_sql = str_replace("@","%",$_pagi_sql);//para evitar error  debido a las comillas interpretadas por el servidor como  eslas 


 if(empty($_pagi_sql)){
	//Si no se defini� $_pagi_sql... error!
	//Este error se muestra s� o s� (ya que no es un error de mysql)
	die("<b>Error Paginator : </b>No se ha definido la variable \$_pagi_sql");
 }
 
 if(empty($_pagi_cuantos)){
	//Si no se ha especificado la cantidad de registros por p�gina
	//$_pagi_cuantos ser� por defecto 20
	$_pagi_cuantos = 20;
 }
 
 if(!isset($_pagi_mostrar_errores)){
	//Si no se ha elegido si se mostrar� o no errores
	//$_pagi_errores ser� por defecto true. (se muestran los errores)
	$_pagi_mostrar_errores = true;
 }
//------------------------------------------------------------------------


/*
 * Establecimiento de la p�gina actual.
 *------------------------------------------------------------------------
 */
 if (empty($_GET['_pagi_pg'])){
	//Si no se ha hecho click a ninguna p�gina espec�fica
	//O sea si es la primera vez que se ejecuta el script
    //$_pagi_actual es la pagina actual-->ser� por defecto la primera.
	$_pagi_actual = 1;
 }else{
	//Si se "pidi�" una p�gina espec�fica:
	//La p�gina actual ser� la que se pidi�.
    $_pagi_actual = $_GET['_pagi_pg'];
	//$_pagi_actual=$_pagi_actual."&sql=1";
 }
//------------------------------------------------------------------------


/*
 * Establecimiento del n�mero de p�ginas y del total de registros.
 *------------------------------------------------------------------------
 */
 //Contamos el total de registros en la BD (para saber cu�ntas p�ginas ser�n)
set_time_limit (0);  
 $_pagi_sqlConta = eregi_replace("select (.*) from", "SELECT COUNT(*) FROM", $_pagi_sql);
 $_pagi_result2 = mysql_query($_pagi_sqlConta);
//$cantidad=mysql_num_rows($_pagi_result2);
 //Si ocurri� error y mostrar errores est� activado
 if($_pagi_result2 == false && $_pagi_mostrar_errores == true){
  ?>
<div align="center">
  <table width="75%" border="0">
    <tr bgcolor="#CCFFFF"> 
      <td colspan="2"><font color="#FF0000" face="Verdana, Arial, Helvetica, sans-serif">ERROR</font><font face="Verdana, Arial, Helvetica, sans-serif"><? echo "[401]" ?></font></td>
    </tr>
    <tr> 
      <td width="7%" height="34"><b><font color="#32378C" size="1" face="Verdana, Arial, Helvetica, sans-serif"><font color="#0000FF"><img src="../imagenes/alert_50x50.gif" width="50" height="50" /></font></font></b></td>
      <td width="93%">  Problemas al hacer la consulta.  verfique los parametros que especifico: <b></b>
        <div align="center"></div>
        <div align="center"></div></td>
    </tr>
  </table>
  <?
	die ();
 }
 $_pagi_totalReg = mysql_result($_pagi_result2,0,0);//total de registros
 
 //Calculamos el n�mero de p�ginas (saldr� un decimal)
 //con ceil() redondeamos y $_pagi_totyalPags ser� el n�mero total (entero) de p�ginas que tendremos
 $_pagi_totalPags = ceil($_pagi_totalReg / $_pagi_cuantos);

//------------------------------------------------------------------------


/*
 * Propagaci�n de variables por el URL.
 *------------------------------------------------------------------------
 */
 //La idea es pasar tambi�n en los enlaces las variables hayan llegado por url.
 $_pagi_enlace = $_SERVER['PHP_SELF'];

 $_pagi_query_string = "?";
  //$_pagi_enlace=$_pagi_enlace."&fred=1";
 if(isset($_pagi_propagar)){
 	//Si se defini� el array para elegir qu� variables propagar
	
	if(!is_array($_pagi_propagar)){
		//si $_pagi_propagar no es un array... error!
		die("<b>Error Paginator : </b>La variable \$_pagi_propagar debe ser un array");
	}else{	 
		//Este foreach est� tomado de la Clase Paginado de webstudio
		//(http://www.forosdelweb.com/showthread.php?t=65528)
		foreach($_pagi_propagar as $var){
			$_pagi_query_string.= $var."=".$GLOBALS[$var]."&";
		}
	}
	
 }else{
 	//Si no se defini� qu� variables propagar, se propagan todas las que vienen por URL
	 if(isset($_GET)){
		//Si ya se han pasado variables por url, escribimos el query string concatenando
		//los elementos del array $_GET excepto la variable $_GET['pg'] si es que existe.
		$_pagi_variables = $_GET;
		foreach($_pagi_variables as $_pagi_clave => $_pagi_valor){
			if($_pagi_clave != '_pagi_pg'){
				$_pagi_query_string .= $_pagi_clave."=".$_pagi_valor."&";
			}
		}
	 }
 }

 //A�adimos el query string a la url.
 $_pagi_enlace .= $_pagi_query_string;
 
//------------------------------------------------------------------------


/*
 * Generaci�n de los enlaces de paginaci�n.
 *------------------------------------------------------------------------
 */
 //La variable $_pagi_navegacion contendr� los enlaces a las p�ginas.
 $_pagi_navegacion = '';
 if ($_pagi_actual != 1){
	//Si no estamos en la p�gina 1. Ponemos el enlace "anterior"
	$_pagi_url = $_pagi_actual - 1;//ser� el n�mero de p�gina al que enlazamos
		$_pagi_navegacion .= "<a href='".$_pagi_enlace."_pagi_pg=".$_pagi_url."&sql=".$_pagi_sql."'>&laquo; Anterior</a>&nbsp;";
 }
 
 //La variable $_pagi_nav_num_enlaces sirve para definir cu�ntos enlaces con 
 //n�meros de p�gina se mostrar�n como m�ximo.
 //Ojo: siempre se mostrar� un n�mero impar de enlaces. M�s info en la documentaci�n.
 
 if(!isset($_pagi_nav_num_enlaces)){
	//Si no se defini� la variable $_pagi_nav_num_enlaces
	//Se asume que se mostrar�n todos los n�meros de p�gina en los enlaces.
	$_pagi_nav_desde = 1;//Desde la primera
	$_pagi_nav_hasta = $_pagi_totalPags;//hasta la �ltima
 }else{
	//Si se defini� la variable $_pagi_nav_num_enlaces
	//Calculamos el intervalo para restar y sumar a partir de la p�gina actual
	$_pagi_nav_intervalo = ceil($_pagi_nav_num_enlaces/2) - 1;
	
	//Calculamos desde qu� n�mero de p�gina se mostrar�
	$_pagi_nav_desde = $_pagi_actual - $_pagi_nav_intervalo;
	//Calculamos hasta qu� n�mero de p�gina se mostrar�
	$_pagi_nav_hasta = $_pagi_actual + $_pagi_nav_intervalo;
	
	//Ajustamos los valores anteriores en caso sean resultados no v�lidos
	
	//Si $_pagi_nav_desde es un n�mero negativo
	if($_pagi_nav_desde < 1){
		//Le sumamos la cantidad sobrante al final para mantener el n�mero de enlaces que se quiere mostrar. 
		$_pagi_nav_hasta -= ($_pagi_nav_desde - 1);
		//Establecemos $_pagi_nav_desde como 1.
		$_pagi_nav_desde = 1;
	}
	//Si $_pagi_nav_hasta es un n�mero mayor que el total de p�ginas
	if($_pagi_nav_hasta > $_pagi_totalPags){
		//Le restamos la cantidad excedida al comienzo para mantener el n�mero de enlaces que se quiere mostrar.
		$_pagi_nav_desde -= ($_pagi_nav_hasta - $_pagi_totalPags);
		//Establecemos $_pagi_nav_hasta como el total de p�ginas.
		$_pagi_nav_hasta = $_pagi_totalPags;
		//Hacemos el �ltimo ajuste verificando que al cambiar $_pagi_nav_desde no haya quedado con un valor no v�lido.
		if($_pagi_nav_desde < 1){
			$_pagi_nav_desde = 1;
		}
	}
 }

 for ($_pagi_i = $_pagi_nav_desde; $_pagi_i<=$_pagi_nav_hasta; $_pagi_i++){//Desde p�gina 1 hasta �ltima p�gina ($_pagi_totalPags)
    if ($_pagi_i == $_pagi_actual) {
		//Si el n�mero de p�gina es la actual ($_pagi_actual). Se escribe el n�mero, pero sin enlace y en negrita.
        $_pagi_navegacion .= "<b>&nbsp;$_pagi_i&nbsp;</b>";
    }else{
		//Si es cualquier otro. Se escibe el enlace a dicho n�mero de p�gina.
        $_pagi_navegacion .= "<a href='".$_pagi_enlace."_pagi_pg=".$_pagi_i."&sql=".$_pagi_sql."'>".$_pagi_i."</a>&nbsp;";
    }
 }

 if ($_pagi_actual < $_pagi_totalPags){
	//Si no estamos en la �ltima p�gina. Ponemos el enlace "Siguiente"
    $_pagi_url = $_pagi_actual + 1;//ser� el n�mero de p�gina al que enlazamos
	
   
    $_pagi_navegacion .= "<a href='".$_pagi_enlace."_pagi_pg=".$_pagi_url."&sql=".$_pagi_sqlp ."'>Siguiente &raquo;</a>";
 }

//------------------------------------------------------------------------


/*
 * Obtenci�n de los registros que se mostrar�n en la p�gina actual.
 *------------------------------------------------------------------------
 */
 //Calculamos desde qu� registro se mostrar� en esta p�gina
 //Recordemos que el conteo empieza desde CERO.
 $_pagi_inicial = ($_pagi_actual-1) * $_pagi_cuantos;
 
 //Consulta SQL. Devuelve $cantidad registros empezando desde $_pagi_inicial
   if(!empty($operacion))
   {
   $_pagi_sql.=" ORDER  BY total " ;
    }
 $_pagi_sqlLim = $_pagi_sql." LIMIT $_pagi_inicial,$_pagi_cuantos";


 $_pagi_result = mysql_query($_pagi_sqlLim);
 
 //Si ocurri� error y mostrar errores est� activado
 if($_pagi_result == false && $_pagi_mostrar_errores == true){
 	die ("Error en la consulta limitada. Mysql dijo: <b>".mysql_error()."</b>");
 }

//------------------------------------------------------------------------


/*
 * Generaci�n de la informaci�n sobre los registros mostrados.
 *------------------------------------------------------------------------
 */
 //N�mero del primer registro de la p�gina actual
 $desde = $_pagi_inicial + 1;
 
 //N�mero del �ltimo registro de la p�gina actual
 $hasta = $_pagi_inicial + $_pagi_cuantos;
 if($hasta > $_pagi_totalReg){
 	//Si estamos en la �ltima p�gina
	//El ultimo registro de la p�gina actual ser� igual al n�mero de registros.
 	$hasta = $_pagi_totalReg;
 }
 
 $_pagi_info = "desde el $desde hasta el $hasta de un total de $_pagi_totalReg";

//------------------------------------------------------------------------


/**
 * Variables que quedan disponibles despu�s de incluir el script v�a include():
 * ------------------------------------------------------------------------
 
 * $_pagi_result			Identificador del resultado de la consulta a la BD para los registros de la p�gina actual. 
 							Listo para ser "pasado" por una funci�n como mysql_fetch_row(), mysql_fetch_array(), 
							mysql_fetch_assoc(), etc.
							
 * $_pagi_navegacion		Cadena que contiene la barra de navegaci�n con los enlaces a las diferentes p�ginas.
 							Ejemplo: "<<anterior 1 2 3 4 siguiente>>".
							
 * $_pagi_info				Cadena que contiene informaci�n sobre los registros de la p�gina actual.
 							Ejemplo: "desde el 16 hasta el 30 de un total de 123";				

*/
?>