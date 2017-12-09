<? 
/*Función que calcula los dias habiles entre la fecha actual y  una fecha anterior dada
Version 1.0
Autor : Bysoft Ltda.

*/



function cuantosDias($fechaEntro)
{ 
  
	$diaEntro		=substr ($fechaEntro, 8,2)/1;
	$mesEntro		=substr ($fechaEntro,5,2)/1;
	$anioEntro		=substr ($fechaEntro,0,4)/1;
	
	$restarUno=0;
	$queDia=calcula_numero_dia_semana($diaEntro,$mesEntro,$anioEntro);
	if($queDia <>5 && $queDia <> 6){
	    $restarUno=1;
	  }
	//Calculo la fecha actual 
	$dia_actual		=date("j",time()); 
	$mes_actual		=date("n",time()); 
	$anio_actual    =date("Y",time());
	$fechaActual=date("Y-m-d");	
	//$fechaActual='2006-01-10';
	//0 lunes,1 martes,2miercoles,3jueves,4viernes,5sabado,6domingo
	    $diasAbiles=0;
		for($a=$anioEntro;$a<=$anioEntro+1;$a++){ 
		   
		   for($m=$mesEntro;$m<=12;$m++){
		     if($m < 10){
				    $m='0'.$m;
				  }
			 $maxdays =  ultimoDia($m,$a); #number of days in the month 
			  for($d=$diaEntro;$d<=$maxdays;$d++){
			      if($d < 10){
				    $d='0'.$d;
				  }
				$diaSemana= calcula_numero_dia_semana($d,$m,$a);
				  
				 if($diaSemana <>5 && $diaSemana <> 6){
				   $diasAbiles=$diasAbiles+1;
				 
				 }
				$fechaComparacion= $a.'-'.$m.'-'.$d; 
				 
				 if($fechaComparacion==$fechaActual){
				  	$d=$maxdays; 
				  	$m=12; 
				  	$a=$anioEntro+1;
					
				 }
			  }
			  $diaEntro=1;
		   }
		  $mesEntro=1; 
	
		}
		 $diasAbiles =$diasAbiles - $restarUno;
		 return $diasAbiles ;
	
	
}


function calcula_numero_dia_semana($dia,$mes,$ano){ 
    $numerodiasemana = date('w', mktime(1,1,2000,$mes,$dia,$ano)); //Fijo Fecha a Enero 01 de 2000
    if ($numerodiasemana == 0) 
       $numerodiasemana = 6; 
    else 
       $numerodiasemana--; 
    return $numerodiasemana; 
} 
function ultimoDia($mes,$ano){ 
   
    $ultimo_dia=28; 
    while (checkdate($mes,$ultimo_dia + 1,$ano)){ 
       $ultimo_dia++; 
    } 
    return $ultimo_dia; 
} 

?> 



 



 

