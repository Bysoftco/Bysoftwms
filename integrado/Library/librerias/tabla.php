<!-- Funcion Para Generar Tablas Dinamicamente--> 

   <?php 
   function inicio_tabla($titulo) 
   { 

         
       ?> 
       <head>
        <title>Seguimiento de Documentos</title>
        </head>
        <body bgcolor="#ffffff">
        <center> <font color="#000000" size="3" face="Verdana, Arial, Helvetica, sans-serif" bgcolor="orange"><?php echo  $titulo; ?></font> </center>
        <br>
        <hr>
       
<table align="center" cellpadding="2" cellspacing="3" width="75%" border="1" bordercolor="#ffffff" bgcolor="#ebebf5">
  <?php
   
   } 
   
  ?>
  <!-- ****************************************************************************************-->
  <?php

function fin_tabla() 
   { 

         
   ?>
</table>
       
        <LEFT>
         <?$url="Form_Modificar.php"; ?>  
       
       
      </LEFT>        

   <?php
   
   } 

  ?> 

<!-- ****************************************************************************************-->    

<?php

function inicio_fila() 
   { 

         
   ?> 
       
            
      <TR><!-- Nueva fila> 
              
      <?
   
   } 

  ?> 

<!-- ****************************************************************************************-->     
<?php


 function fin_fila() 
   { 

         
   ?> 
       
        </TR> 
               

   <?php
   
   } 

  ?> 


<!-- ****************************************************************************************-->   

  

<?php

function inicio_col() 
   { 

         
   ?> 
       
    <tr>
               

   <?
   
   } 

  ?> 

<!-- ****************************************************************************************-->   

<?php

function fin_col() 
   { 

         
   ?> 
       
    </tr>
               

   <?
   
   } 

  ?> 

<!-- ****************************************************************************************-->   

<?php


//function campos_fila($campo,$url,$id_documento,$id_actividad,$id_tarea) 
   function campos_fila()
   { 
         $numargs = func_num_args();
		switch($numargs)
		{
		  case 1:
		  $campo=func_get_arg (0);
		  break;
		   case 2:
		     $campo=func_get_arg (0);
		    $url=func_get_arg (1);
		  break;
		   case 3:
		     $campo=func_get_arg (0);
		     $url=func_get_arg (1);
			  $label=func_get_arg (2);
		  break;
		   case 4:
		     $campo=func_get_arg (0);
		     $url=func_get_arg (1);
			  $label=func_get_arg (2);
			 $id_actividad=func_get_arg (3);
		  break;
		  case 4:
		     $campo=func_get_arg (0);
		     $url=func_get_arg (1);
			  $label=func_get_arg (2);
			 $id_actividad=func_get_arg (3);
			 
		  break;
		  case 5:
		     $campo=func_get_arg (0);
		     $url=func_get_arg (1);
			 $label=func_get_arg (2);
			 $id_actividad=func_get_arg (3);
			  $id_tarea=func_get_arg (4);
			  $responsable=func_get_arg (5);
		  break;
		  case 6:
		     $campo=func_get_arg (0);
		     $url=func_get_arg (1);
			  $label=func_get_arg (2);
			 $id_actividad=func_get_arg (3);
			  $id_tarea=func_get_arg (4);
			  $responsable=func_get_arg (5);
			
		  break;
		  case 7:
		     $campo=func_get_arg (0);
		     $url=func_get_arg (1);
			 $label=func_get_arg (2);
			 $id_actividad=func_get_arg (3);
			  $id_tarea=func_get_arg (4);
			  $responsable=func_get_arg (5);
			  $id=func_get_arg (6);
			
		  break;
		    case 8:
		     $campo=func_get_arg (0);
		     $url=func_get_arg (1);
			  $label=func_get_arg (2);
			 $id_actividad=func_get_arg (3);
			  $id_tarea=func_get_arg (4);
			  $responsable=func_get_arg (5);
			  $id=func_get_arg (6);
			  $frecibo=func_get_arg (7);
			
		  break;
		  case 9:
		     $campo=func_get_arg (0);
		     $url=func_get_arg (1);
			  $label=func_get_arg (2);
			 $id_actividad=func_get_arg (3);
			  $id_tarea=func_get_arg (4);
			  $responsable=func_get_arg (5);
			  $id=func_get_arg (6);
			  $frecibo=func_get_arg (7);
			    $color=func_get_arg (8);
			
		  break;
		}
	 
	//echo "responsabletabla".$responsable."documento".$id_d."actividad".$id_a."tarea".$id_t."radicado".$campo."id".$id; 
    if($url=="") {
          ?>
            <td bordercolor="#FFFFFF" bgcolor=<? echo $color ?>  title="<? echo $label ?>">
            
			<font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif" bgcolor="orange"><?php echo  $campo; ?></font> 
			
            </td>
          <?
   }else{
         
   ?> 
       
       <td  bordercolor="#FFFFFF" bgcolor=<? echo $color ?>>
          
          
       
    
      
	  <a href=file:///D|/DIAN1/LIBRERIAS/<?php echo $url?>?rad=<? echo $campo?>&id_r=<? echo $responsable?>&id_a=<? echo $id_actividad ; ?>&id_t=<? echo $id_tarea;?>&id=<? echo $id ;?>&i=<? echo $id ;?>> <?php echo  $campo; ?> </a>
	   </td>
        

         

   <?
      }  
   } 

  ?> 
<!-- ****************************************************************************************-->   
<?php
function titulo_col($titulo) 
   { 

         
   ?> 
       
      <th   bordercolor="#ffffff" ><font color="#0000CC"> 
            
       <b> <font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo  $titulo; ?></font></b>
                              
       </th>
               

   <?
   
   } 

  ?> 
		