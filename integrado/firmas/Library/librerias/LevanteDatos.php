<?php



require_once("MYDB.php");



class Levante extends MYDB {



    function Levante() {

        $this->estilo_error = "ui-state-error";

        $this->estilo_ok = "ui-state-highlight";

    }

    

    //Funcion que consulta el inventario  de  mercancia no costeada

    function getInventario($arregloDatos) {

        

        $sql=" SELECT inv.cantidad as cantidad_nonac,

                    inv.codigo as item,

                    inv.arribo,

                    inv.peso as peso_nonac,

                    inv.valor,

                    inv.fmm,

                    inv.referencia,

                    inv.modelo,

                    inv.posicion,

                    inv.observacion,

                    inv.un_empaque,

                    inv.embalaje,

                    do_asignados.por_cuenta as cliente,

                    do_asignados.do_asignado as orden,

                    embalajes.nombre as nombre_empaque,

                    posiciones.nombre as nombre_posicion,

                    ref.nombre  as nombre_referencia,

                     ref.ref_prove as cod_referencia

                FROM inventario_entradas inv,referencias ref,embalajes,posiciones,do_asignados

                    WHERE inv.referencia	=   ref.codigo

                    AND embalajes.codigo =   inv.un_empaque

                    AND 	inv.posicion= 	posiciones.codigo

                    AND do_asignados.do_asignado=inv.orden

                    AND inv.valor=0

                    ";

        //echo  $sql;

        if(!empty($arregloDatos[doc_filtro])){

          $sql .= " AND do_asignados.doc_tte='$arregloDatos[doc_filtro]' ";

        }

        if(!empty($arregloDatos[cliente])){

          $sql .= " AND do_asignados.por_cuenta='$arregloDatos[cliente]' ";

        }

        $this->query($sql);

        if($this->_lastError) {

                $this->mensaje	="error al consultar Inventario ";

                $this->estilo	=$this->estilo_error;

                return TRUE;

        }

   }

  

    // Funcion que lista la mercancia para ensamble

    function getParaProceso($arregloDatos) 

   {

       //var_dump($arregloDatos);

        $arregloDatos[having]   =" HAVING peso_nonac  > 0 OR peso_naci > 0 ";

        if($arregloDatos[cod_ref]){

            $arregloDatos[where]  .=" AND  ref.codigo ='$arregloDatos[cod_ref]' "; // filtro por referencia

            //$arregloDatos[GroupBy]=" cod_referencia";  // Orden Por numero de levante

        }else{

            $arregloDatos[GroupBy]=" cod_referencia";  // Orden Por numero de levante

        }

        $arregloDatos[GroupBy]=" cod_referencia";  // Orden Por numero de levante

        $this->getInvParaProceso($arregloDatos); 

   }

    // Funcion que lista la mercancia de cualquier movimiento

    function getInvParaProceso($arregloDatos) 

   {

   // var_dump($arregloDatos);

            //echo $arregloDatos[movimiento]."<br>";

       if(!empty($arregloDatos[id_item]))

        {

            //$Where .= " AND inventario_entrada =$arregloDatos[id_item] ";

        }

        

        if(!empty($arregloDatos[cliente]) or !empty($arregloDatos[por_cuenta_filtro]))

        {

            $arregloDatos[where] .= " AND (do_asignados.por_cuenta='$arregloDatos[cliente]' OR   do_asignados.por_cuenta='$arregloDatos[por_cuenta_filtro]')";

        }

        

        

        

       //echo $arregloDatos[where]." :".$arregloDatos[GroupBy]."<br>";

        

        $sql = "SELECT 

	orden,

	doc_tte,

	inventario_entrada,

        inventario_entrada      AS item,

	arribo, 

	nombre_referencia,

	cod_referencia,

        codigo_referencia,

        cant_declaraciones,

	cantidad,

        peso,

        valor,

        modelo,

	SUM(peso_nonac) 	AS peso_nonac,

	SUM(peso_naci) 		AS peso_naci,

	SUM(cantidad_naci) 	AS cantidad_naci,

	SUM(cantidad_nonac) 	AS cantidad_nonac,   

        SUM(fob_nonac) 		AS fob_nonac,

	SUM(cif) 		AS cif,

       

        cod_maestro,

        MIN(num_levante)        AS  num_levante,

        un_grupo

FROM(



	SELECT

	im.codigo,

        

         

        CASE WHEN im.tipo_movimiento in ($arregloDatos[movimiento]) THEN 1

	ELSE 0

	END as movimiento,

        

	do_asignados.do_asignado        AS orden,

	do_asignados.doc_tte     	AS doc_tte,

	ie.arribo,

	ref.nombre              	AS nombre_referencia,

        ref.ref_prove           	AS cod_referencia,

        ref.codigo           	        AS codigo_referencia,

        ie.cant_declaraciones,     

	ie.cantidad                     AS cantidad,

        ie.peso                         AS peso,

        ie.valor                        AS valor,

        ie.modelo                       AS modelo,

        

	im.inventario_entrada, 

        im.cod_maestro,

        im.num_levante,

        im.tipo_movimiento,

        id.grupo                        AS un_grupo,

        

       

        

	CASE WHEN im.tipo_movimiento in ($arregloDatos[movimiento]) THEN peso_nonac

	ELSE 0

	END as peso_nonac,

	

	CASE WHEN im.tipo_movimiento in ($arregloDatos[movimiento]) THEN peso_naci	

	ELSE 0

	END as peso_naci,



	

        CASE WHEN im.tipo_movimiento in ($arregloDatos[movimiento]) THEN cantidad_naci	

	ELSE 0

	END as cantidad_naci,

	


	CASE WHEN im.tipo_movimiento in ($arregloDatos[movimiento]) THEN cantidad_nonac
	ELSE 0

	END as cantidad_nonac,



	

	CASE WHEN im.tipo_movimiento in ($arregloDatos[movimiento]) THEN fob_nonac	

	ELSE 0

	END as fob_nonac,

	

	CASE WHEN im.tipo_movimiento in ($arregloDatos[movimiento]) THEN cif	

	ELSE 0

	END as cif



	



	FROM inventario_movimientos im

                LEFT JOIN inventario_maestro_movimientos imm     ON im.cod_maestro   = imm.codigo

                LEFT JOIN inventario_declaraciones       id      ON im.num_levante   = id.num_levante,

                inventario_entradas ie,arribos,do_asignados,clientes,referencias ref

                WHERE im.inventario_entrada  =ie.codigo

 		AND arribos.arribo           =ie.arribo

        	AND arribos.orden            =do_asignados.do_asignado

		AND clientes.numero_documento=do_asignados.por_cuenta

		AND ie.referencia            =ref.codigo

		

         $arregloDatos[where]



) as inv 



GROUP BY $arregloDatos[GroupBy] 

$arregloDatos[having] 

$arregloDatos[orderBy]";

     //echo  $arregloDatos[having] ." $arregloDatos[donde] $arregloDatos[movimiento]+<BR>"; 

   // inv.inventario_entrada

   

        $this->_lastError=NULL;

        $this->query($sql);

        //echo "$arregloDatos[donde]<BR>".  $arregloDatos[metodo].$sql."<BR>";

        if ($this->_lastError) {

         echo "Error". $arregloDatos[metodo].$sql."<BR>";

            $this->mensaje = "error al consultar Inventario1 ";

            $this->estilo = $this->estilo_error;

            return TRUE;

        }   

    }

       // Funcion que lista el inventario para retirar solo devuelve mercancia disponible

    function getInvParaRetiro($arregloDatos) 

    {  

           // var_dump($arregloDatos);

         $arregloDatos[having]   =" HAVING peso_nonac  > 0 OR peso_naci > 0 ";

        if($arregloDatos[cod_ref]){

            $arregloDatos[where]  .=" AND   ref.codigo  ='$arregloDatos[cod_ref]' "; // filtro por referencia

            //$arregloDatos[GroupBy]=" cod_referencia";  // Orden Por numero de levante

        }else{

            $arregloDatos[GroupBy]=" codigo_referencia ";  // Orden Por numero de levante

        }

        $arregloDatos[GroupBy]=" codigo_referencia ";  // Orden Por REFERENCIA

        

        $this->getInvParaProceso($arregloDatos);

        //var_dump($arregloDatos);

        /*if($arregloDatos[tipo_retiro]==2 OR $arregloDatos[tipo_retiro_filtro]==2 OR $arregloDatos[tipo_retiro_filtro]==8 OR $arregloDatos[tipo_retiro]==8){

          $sqlVerNoNacional = " OR    TRUNCATE(peso_nonac,1)  > 0";

         

        }

        if(!empty($arregloDatos[id_item])){

          $sqlFiltroItem = " AND ie.codigo=$arregloDatos[id_item] ";

        }

        if(!empty($arregloDatos[por_cuenta_filtro])){

          $sqlFiltroCliente = " AND do_asignados.por_cuenta='$arregloDatos[por_cuenta_filtro]' ";

        }

        //Aqui se aplican los filtros de formulario filtro

         if(!empty($arregloDatos[arribo_filtro])){

            $sqlFiltroArribo = " AND   arribos.arribo          ='$arregloDatos[arribo_filtro]' ";

         }

         

         if(!empty($arregloDatos[orden_filtro])){

            $sqlFiltroOrden = " AND   do_asignados.do_asignado ='$arregloDatos[orden_filtro]' ";

         }

         

         if(!empty($arregloDatos[documento_filtro])){

            $sqlFiltroDoc = " AND   do_asignados.doc_tte ='$arregloDatos[documento_filtro]' ";

         }

          $sql = " SELECT DISTINCT

                    do_asignados.do_asignado        AS orden,

                    ie.codigo as inventario_entrada,

                    ie.arribo               as arribo,

                    ie.codigo               as item,

                    ie.cantidad             as cantidad,

                    ie.peso                 as peso,

                    ie.valor                as valor,

                    ie.modelo               as modelo,

                    ie.fmm                  as fmm,

                    ie.embalaje             as embalaje,

                    do_asignados.doc_tte    as doc_tte,

                    ref.nombre              as nombre_referencia,

                    ref.ref_prove           as cod_referencia,

                    si_nacional.peso_naci,

                    si_nacional.cantidad_naci,

                    no_nacional. peso_nonac,

                    no_nacional.cantidad_nonac as cantidad_nonac,

                    fob.cif,

                    fob.fob_nonac

                    

                    

                 FROM do_asignados,referencias ref,clientes,arribos,embalajes,inventario_entradas ie

                 

                    LEFT JOIN (

                                SELECT 

                                    arribos.orden,

                                    max(im.inventario_entrada) as inventario_entrada,

                                    ie.referencia,

                                    SUM(im.peso_naci)           as peso_naci,

                                    SUM(im.cantidad_naci)       as cantidad_naci,

                                    SUM(im.cif)    	as cif

                                FROM  inventario_movimientos im,inventario_entradas ie,arribos

                                WHERE im.inventario_entrada     =ie.codigo

                                    AND arribos.arribo          =ie.arribo

                                    AND tipo_movimiento in(1,2,3,30)

                                GROUP BY arribos.orden,ie.referencia



                              )AS si_nacional

                     ON si_nacional.orden =  	ie.orden AND si_nacional.referencia=ie.referencia

                   

                    LEFT JOIN (

                                SELECT arribos.orden,

                                    max(im.inventario_entrada),

                                    ie.referencia,

                                   SUM(im.peso_nonac)             AS peso_nonac,

                                   SUM(im.cantidad_nonac)         AS cantidad_nonac,

                                   SUM(im.fob_nonac)              AS fob_nonac

                                FROM  inventario_movimientos im,inventario_entradas ie,arribos

                                WHERE im.inventario_entrada     =ie.codigo

                                AND arribos.arribo          =ie.arribo

                                AND tipo_movimiento in(1,2,3,30)

                                GROUP BY arribos.orden,ie.referencia

                                HAVING  TRUNCATE(peso_nonac,1) > 0



                              )AS no_nacional

                   ON no_nacional.orden =  ie.orden  AND  no_nacional.referencia=ie.referencia

         

                   

         LEFT JOIN (

                                SELECT arribos.orden,

                                    max(im.inventario_entrada),

                                    ie.referencia,

                                    SUM(im.fob_nonac)              AS fob_nonac,

                                    SUM(im.cif)              AS cif

                                FROM  inventario_movimientos im,inventario_entradas ie,arribos

                                WHERE im.inventario_entrada     =ie.codigo

                                AND arribos.arribo          =ie.arribo

                                AND tipo_movimiento in(2,3)

                                GROUP BY arribos.orden,ie.referencia

                                



                              )AS fob

                   ON fob.orden =  ie.orden  AND  fob.referencia=ie.referencia

                   

              

                   WHERE 

                   arribos.arribo               =ie.arribo

                   AND arribos.orden            =do_asignados.do_asignado

                   AND ie.referencia            =ref.codigo

                   AND   embalajes.codigo             =ie.un_empaque

                   AND clientes.numero_documento=do_asignados.por_cuenta

                  $sqlFiltroCliente

                  $sqlFiltroItem 

               HAVING  TRUNCATE(peso_naci,1)   	> 0  $sqlVerNoNacional";

         $this->query($sql);

         echo $sql;

        if ($this->_lastError) {

           

            $this->mensaje = "error al consultar Inventario ";

            $this->estilo = $this->estilo_error;

            return TRUE;

        }*/

    }

    // CUANDO SE NACIONALIZA SOLO SE GUARDA EL VALOR NACIONALIZDO SIN RESTAR 

   // Funcion que lista el inventario para retirar

    function getInvParaRetiroX($arregloDatos) {

        var_dump($arregloDatos);

        if($arregloDatos[tipo_retiro]==2 OR $arregloDatos[tipo_retiro_filtro]==8){

          $sqlVerNoNacional = " OR    TRUNCATE(peso_nonac,1)  > 0";

        }

        if(!empty($arregloDatos[id_item])){

          $sqlFiltroItem = " AND ie.codigo=$arregloDatos[id_item] ";

        }

        if(!empty($arregloDatos[por_cuenta_filtro])){

          $sqlFiltroCliente = " AND do_asignados.por_cuenta='$arregloDatos[por_cuenta_filtro]' ";

        }

        //Aqui se aplican los filtros de formulario filtro

         if(!empty($arregloDatos[arribo_filtro])){

            $sqlFiltroArribo = " AND   arribos.arribo          ='$arregloDatos[arribo_filtro]' ";

         }

         

         if(!empty($arregloDatos[orden_filtro])){

            $sqlFiltroOrden = " AND   do_asignados.do_asignado ='$arregloDatos[orden_filtro]' ";

         }

         

         if(!empty($arregloDatos[documento_filtro])){

            $sqlFiltroDoc = " AND   do_asignados.doc_tte ='$arregloDatos[documento_filtro]' ";

         }

        //var_dump($arregloDatos);

        

       $sql = "

            SELECT

                MAX( arribos.orden)         as orden,

                inventario.inventario_entrada,

                MAX(ie.arribo)              as arribo,

                MAX(ie.codigo)              as item,

                MAX(ie.cantidad)            as cantidad,

                MAX(ie.peso)                as peso,

                MAX(ie.valor)               as valor,

                MAX(ie.modelo)              as modelo,

                MAX(ie.fmm)                 as fmm,

                MAX(ie.embalaje)            as embalaje,

                MAX(embalajes.nombre)        as nombre_embalaje,

                MAX(do_asignados.doc_tte)   as doc_tte,

         

                MAX(peso_naci)              as peso_naci,

                MAX(peso_nonaci)            as peso_nonac,

                MAX(cantidad_naci)          as cantidad_naci,

                MAX(cantidad_nonac)         as cantidad_nonac,

                MAX(cif)               as cif,

                MAX(fob_nonac)              as fob_nonac,

                MAX(ref.nombre)             as nombre_referencia,

                MAX(ref.ref_prove)          as cod_referencia

             FROM (	SELECT im.inventario_entrada,

                    SUM(im.peso_naci)        as peso_naci,

                    0                    	as peso_nonaci,

                    SUM(im.cantidad_naci)    as cantidad_naci,

                    0                        as cantidad_nonac,

                    SUM(im.cif)    	as cif,

                    0                        as fob_nonac



                FROM  inventario_movimientos im

                WHERE tipo_movimiento in(2,3)

                GROUP BY im.inventario_entrada



               UNION



                SELECT im.inventario_entrada,

                        0      		     	as peso_naci,

                        SUM(im.peso_nonac)      as peso_nonaci,

                        0    			as cantidad_nonaci,

                        SUM(im.cantidad_nonac)  as cantidad_nonac,

                        0    			as cif,

                        SUM(im.fob_nonac)  as fob_nonac





                FROM  inventario_movimientos im

                WHERE tipo_movimiento in(1,2,3)

                GROUP BY im.inventario_entrada





            )  AS inventario,inventario_entradas ie,arribos,do_asignados,referencias ref,embalajes



        WHERE inventario.inventario_entrada=ie.codigo

        AND   ie.referencia                =ref.codigo

        AND   ie.arribo                    =arribos.arribo

        AND   do_asignados.do_asignado	   =arribos.orden

        AND   embalajes.codigo             =ie.un_empaque

        $sqlFiltroDoc

        $sqlFiltroOrden

        $sqlFiltroArribo

        $sqlFiltroCliente

        $sqlFiltroItem 

         

        GROUP BY ref.ref_prove

        HAVING  TRUNCATE(peso_naci,1)   	> 0  $sqlVerNoNacional

                 ";

       //GROUP BY inventario_entrada

       echo $sql;

       $this->query($sql);

        if ($this->_lastError) {

            echo $sql;

            $this->mensaje = "error al consultar Inventario ";

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

       

   }

   

   

  

   // Este metodo muestra la mercancia un Item en el levante

    function getMercancia($arregloDatos) {  // Candidato a Borrarse reemplazado por getInvParaProceso

        //var_dump($arregloDatos);

       //$arregloDatos[where]  .=" AND   cod_maestro ='$arregloDatos[id_levante]'"; // filtro por referencia

       $arregloDatos[where]  .=" AND  ref.codigo  ='$arregloDatos[cod_ref]'"; // filtro por referencia

       $arregloDatos[GroupBy]=" codigo_referencia ";  // Orden Por Referencia

       //echo $arregloDatos[where];

        $this->getInvParaProceso($arregloDatos)  ;

        //var_dump($arregloDatos);

        /*

        $sql = " SELECT 

                    

                    arribos.orden,

                    max(ie.cant_declaraciones)      as cant_declaraciones,

                    max(doc_tte)                    as doc_tte,

                    TRUNCATE(sum(peso_naci),2)      as peso_naci ,

                    TRUNCATE(sum(peso_nonac),2)     as peso_nonac,

                    TRUNCATE(sum(cantidad_nonac),2) as cantidad_nonac,

                    TRUNCATE(sum(cantidad_naci),2)  as cantidad_naci,

                    TRUNCATE(sum(cif),2)       as cif ,

                    TRUNCATE(sum(fob_nonac),2)      as fob_nonac,

                    ref.nombre                      as nombre_referencia,

                    ref.ref_prove                   as cod_referencia,

                    max(embalajes.codigo)           as cod_empaque,

                    max(embalajes.nombre)           as nombre_empaque,

                    max(ie.embalaje)                as q_embalaje,

                    max(ie.modelo)                  as modelo,

                    max(ie.fmm)                     as fmm,

                    max(ie.cantidad)                as cantidad,

                    max(ie.peso)                    as peso,

                    max(ie.valor)                   as valor

                FROM inventario_movimientos im,inventario_entradas ie,arribos,do_asignados,referencias ref,embalajes

                WHERE im.inventario_entrada =ie.codigo

                AND arribos.arribo          =ie.arribo

                AND arribos.orden           =do_asignados.do_asignado

                AND ie.referencia           =ref.codigo

                AND embalajes.codigo        =ie.un_empaque

            ";



        if ($arregloDatos[doc_filtro]) {

            $sql.=" AND doc_tte='$arregloDatos[doc_filtro]'";

        }

         if ($arregloDatos[cod_ref]) {

            $sql.=" AND ref.codigo='$arregloDatos[cod_ref]'";

        }

        $sql.=" GROUP BY  ref.nombre,ref.codigo";

        //$sql.=" GROUP BY ref.nombre,ref.codigo";

         // echo $sql."<br>";

        $this->query($sql);

        if ($this->_lastError) {

            $this->mensaje = "error al consultar Inventario " . $sql;

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

    }

    //verifica si un arribo tiene mas  de un registro en el inventario

    function findInventario($arregloDatos) 

    {

       $sql="SELECT count(codigo) as num_inv FROM inventario_entradas WHERE arribo   =$arregloDatos[arribo]";

       $this->query($sql);

       

        if($this->_lastError) {



            $this->mensaje_error="error al consultar inventario ";

            $this->estilo	=$this->estilo_error;

            return TRUE;

        }

        $this->fetch();

        if($this->num_inv > 1) 

        {

            $arregloDatos[mensajeEditar]    ="El arribo ya tiene inventario por lo tanto  no se puede modificar, intente deshacer el inventario para modificar o borrar el arribo";

            $arregloDatos[mostrarEditar]    ="none";

            $arregloDatos[estiloEditar]     ="ui-state-highlight";

            return $this->num_inv;

        }*/

        

    }

    

    // Verifica si la mercancia tiene movimientos

      function findMovimientos($arregloDatos) 

       {

           if(!empty($arregloDatos[id_arribo])){$arregloDatos[arribo]=$arregloDatos[id_arribo];}

            //var_dump($arregloDatos);

            $sql="SELECT DISTINCT MAX( im.codigo )               AS id_mercancia

                                   

                 FROM inventario_movimientos im,inventario_entradas ie,arribos,do_asignados,referencias ref

                 WHERE im.inventario_entrada    =ie.codigo

                    AND arribos.arribo          =ie.arribo

                    AND arribos.orden           =do_asignados.do_asignado

                    AND ie.referencia           =ref.codigo

                    AND arribos.arribo          =$arregloDatos[arribo]

                    AND im.tipo_movimiento      > 1";

            //echo $sql;

            $this->query($sql);

            if($this->_lastError) {



                $this->mensaje_error="error al consultar operaciones ";

                $this->estilo	=$this->estilo_error;

                return TRUE;

            }

            $this->fetch();

            if(!empty($this->id_mercancia)) 

            {

                //echo "XXX".$this->id_mercancia;

                $arregloDatos[mensajeEditar]    ="El arribo ya tiene movimientos por lo tanto  no se puede modificar,intente deshacer los movimientos antes de modificar o borrar el arribo";  

                $arregloDatos[mostrarEditar]    ="none";

                $arregloDatos[estiloEditar]     ="ui-state-highlight";

              

            }else{

                //var_dump($arregloDatos);

                // Se verifica si hay inventario

                if($arregloDatos[thisFunction]=="getInventario" OR $arregloDatos[thisFunction]="listaInventario"){

                    

                }else{

                     $this->findInventario(&$arregloDatos); 

                }

                

            }

             

            return $this->id_mercancia;

        }



  

//Lista la mercancia para Levante al sumar los campos peso_nonac calcula valores

    function listaInventario($arregloDatos) { // para borrar reemplazada por getInvParaProceso

      // verificar si se utiliza para un registro

     

       // $arregloDatos[where]  .=" AND   cod_maestro ='$arregloDatos[id_levante]' "; // filtro por movimiento

        $arregloDatos[donde]   =" sendlevante ";

        $arregloDatos[having]   =" HAVING peso_nonac  > 0  ";

        $arregloDatos[GroupBy]  =" codigo_referencia ";  //Por Referencia

        $this->getInvParaProceso($arregloDatos);

       /*

        $sql = " SELECT DISTINCT MAX( im.codigo )               AS id_mercancia,

                    MAX( im.inventario_entrada)    AS item,

                    SUM(im.peso_nonac)             AS peso_nonac,

                    SUM(im.peso_naci)              AS peso_nac,

                    SUM(im.cantidad_nonac)         AS cantidad_nonac,

                    SUM(im.cantidad_naci)          AS cantidad_naci,

                    SUM(im.cif)                    AS cif,

                    SUM(ie.valor)                  AS valor,

                    SUM(im.fob_nonac)              AS fob_nonac,

                    MAX(arribos.arribo)            AS arribo,

                    MAX(do_asignados.do_asignado)  AS do_asignado,

                    ref.nombre                     AS nombre_referencia,

                    ref.codigo                     AS cod_referencia,

                    arribos.orden                  AS orden

                                   

                 FROM inventario_movimientos im,inventario_entradas ie,arribos,do_asignados,referencias ref

                 WHERE im.inventario_entrada    =ie.codigo

                    AND arribos.arribo          =ie.arribo

                    AND arribos.orden           =do_asignados.do_asignado

                    AND ie.referencia           =ref.codigo

                    AND do_asignados.por_cuenta ='$arregloDatos[por_cuenta_filtro]'" ;

        if($arregloDatos[doc_filtro])

        {

            $sql .= "AND do_asignados.doc_tte='$arregloDatos[doc_filtro]'";  

        }

        //Aqui se aplican los filtros de formulario filtro

         if(!empty($arregloDatos[arribo_filtro])){

            $sqlFiltroArribo = " AND   arribos.arribo          ='$arregloDatos[arribo_filtro]' ";

            

         }

         if(!empty($arregloDatos[arribo_filtro])){

            $sqlFiltroArribo = " AND   arribos.arribo          ='$arregloDatos[arribo_filtro]' ";

            

         }

          if(!empty($arregloDatos[orden_filtro])){

            $sqlFiltroArribo = " AND   do_asignados.do_asignado ='$arregloDatos[orden_filtro]' ";

            

         }

         

         

        if($arregloDatos[id_grupo])// para los parciales

        {

           // $sql .= "AND do_asignados.doc_tte='$arregloDatos[doc_filtro]'";  

        }

        // $sql .= "      GROUP BY  inventario_entrada, ref.nombre";

        $sql .= "      GROUP BY  ref.nombre";

        switch($arregloDatos[tipo_movimiento])

        {

            case 2: // Nacionalizacion 

                $sql .= " HAVING  TRUNCATE(peso_nonac,1) > 0 ";

            break;

            case 3: //  Retiro 

                $sql .= " HAVING  TRUNCATE(peso_nac,1)   > 0 ";

            break;

        }

  

       echo $sql;

       

        $this->query($sql);

        if ($this->_lastError) {

            $this->mensaje = "error al consultar Inventario ";

            $this->estilo = $this->estilo_error;

            return TRUE;

        } */

    }

    

    // Agrega registro de mercancia para Proceso

    function addItemProceso($arregloDatos) {

        

     

        $fecha = FECHA;

        $sql = " INSERT INTO inventario_movimientos

                        (fecha,inventario_entrada,tipo_movimiento,peso_naci,peso_nonac,cantidad_naci,cantidad_nonac,cif,fob_nonac,cod_maestro,num_levante)

                VALUES('$fecha',$arregloDatos[id_item],$arregloDatos[tipo_movimiento],$arregloDatos[peso_naci_para],$arregloDatos[peso_nonaci_para],$arregloDatos[cantidad_naci_para],$arregloDatos[cantidad_nonaci_para],$arregloDatos[cif_ret], $arregloDatos[fob_ret] ,$arregloDatos[id_levante],'$arregloDatos[num_levante]')

        ";

        //echo $sql;

        $this->query($sql);

        if ($this->_lastError) {

            $arregloDatos[mensaje] = "error al enviar la mercancia a proceso ";

            $arregloDatos[estilo]  = $this->estilo_error;

            echo $sql;

            return TRUE;

        }

        $arregloDatos[mensaje] = "se envio la mercancia a proceso correctamente  ";

        $arregloDatos[estilo]  = $this->estilo_ok;  

    }

    

   



    

    

     function getCanDeclaraciones($arregloDatos) {

       $this->getInvParaProceso($arregloDatos);  

     }

    // Lista la mercancia en el cuerpo de movimientos de nacionalizacion y retiro

    function getCuerpoLevante($arregloDatos)

    { 

       //echo "xxxxxxxxxxxxxxxxxxxxx";

        if (!empty($arregloDatos[doc_filtro])) { // solo aplica para listar cuerpo nacionalizacion

            //$arregloDatos[where] .=" AND do_asignados.doc_tte ='$arregloDatos[doc_filtro]'";  // filtro para consultar  cuerpo mercancia

        }

       //$arregloDatos[having]                   =" peso_nonac  > 0  ";

       $arregloDatos[where]  =" AND  im.cod_maestro='$arregloDatos[id_levante]'"; // filtro para listar  cuerpo movimientos

       $arregloDatos[GroupBy]=" num_levante";

       $arregloDatos[orderBy]=" ORDER BY un_grupo";

        // var_dump($arregloDatos);

       

       $this->getInvParaProceso($arregloDatos);  

        

        /*

        switch($arregloDatos[tipo_movimiento]/1){

            

            case "2": // nacionalizacion

                

                $esLevante  ="LEFT JOIN inventario_declaraciones   ON inventario_declaraciones.num_levante       =  im.num_levante";

                $where      =" AND im.cod_maestro='$arregloDatos[id_levante]'   AND tipo_movimiento         =2 ";

                $group      =" GROUP BY inventario_entrada,im.num_levante";

                $select     = " MAX(inventario_declaraciones.grupo) as grupo,";

                //echo "where".$where;

            break;

            case "3":

               

                $where      =" AND im.cod_maestro='$arregloDatos[id_levante]'   AND tipo_movimiento         =3 ";

                $group      =" GROUP BY inventario_entrada";

            break;    

        }

       

        $sql = " SELECT $select 

                    inventario_entrada  as id_item,

                    im.num_levante,   

                    SUM(peso_naci)      as peso_naci ,

                    SUM(peso_nonac)     as peso_nonac,

                    SUM(cantidad_nonac) as cantidad_nonac,

                    SUM(cantidad_naci)  as cantidad_naci,

                    SUM(cif)       as cif ,

                    SUM(fob_nonac)      as fob_nonac,

                    MAX(arribos.orden)  as orden,

                    MAX(arribos.arribo) as arribo,

                    MAX(ie.codigo)      as cod_item,

                    MAX(ref.nombre)     as nombre_referencia,

                    MAX(ref.ref_prove)  as cod_referencia

                    

                FROM inventario_entradas ie,arribos,do_asignados,referencias ref,embalajes,inventario_movimientos im

                $esLevante 

                WHERE im.inventario_entrada =ie.codigo

                AND arribos.arribo          =ie.arribo

                AND arribos.orden           =do_asignados.do_asignado

                AND ie.referencia           =ref.codigo

                AND embalajes.codigo        =ie.un_empaque

               $where 

            ";

        // AND im.cod_maestro          =1

        if ($arregloDatos[id_item]) {

             

           // $sql.=" AND im.inventario_entrada=$arregloDatos[id_item]";

        }

        $sql.="    GROUP BY inventario_entrada,im.num_levante ";

       //echo $sql."<br>";

        $this->query($sql);

        if ($this->_lastError) {

            $arregloDatos[mensaje] = " error al consultar Inventario " . $sql;

            $arregloDatos[estilo]  = $this->estilo_error;

            return TRUE;

        }*/

    }

    

    function matriz($arregloDatos) 

    {

         // devuelve los datos de la cabeza del retiro y del levente

            $sql = " SELECT   

                    imm.codigo as num_levante,

                    lev_bultos,

                    imm.fecha,

                    imm.destinatario,

                    imm.obs,

                    clientes.razon_social,

                    clientes.numero_documento as nit,

                    imm.producto,

                    referencias.codigo,

                    referencias.nombre as nombre_producto,

                    imm.cantidad,

                    imm.cantidad_nac,

                    imm.cantidad_ext,

                    imm.doc_tte,

                    imm.peso,

                    imm.valor,

                    imm.unidad,

                    imm.bodega,

                    imm.orden,

                    imm.cierre,

                    imm.fmm,

                    imm.orden,

                    imm.valor,

                    imm.pos_arancelaria

                 FROM inventario_maestro_movimientos imm

                 LEFT JOIN clientes     ON imm.lev_sia   =  clientes.numero_documento

                 LEFT JOIN referencias  ON imm.producto  =  referencias.codigo

                 WHERE  imm.codigo=$arregloDatos[id_levante]

         ";

        $this->query($sql);

        //echo $sql."<br>";

        if ($this->_lastError) {

            $this->mensaje = "error al consultar Maestro de Movimientos " . $sql;

            echo $sql."<br>";

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

    }

    

    function getRetiro($arregloDatos) {

       $sql = " SELECT imm.fecha,

                       imm.destinatario,

                       imm.obs,

                       imm.fmm,

                       camiones.conductor_nombre,

                       camiones.conductor_identificacion,

                       camiones.empresa,

                       camiones.placa

                       

                FROM inventario_maestro_movimientos imm

                LEFT JOIN camiones ON imm.id_camion       =  camiones.codigo

                WHERE imm.codigo=$arregloDatos[id_levante] "; 

       //echo $sql."<br>";

        $this->query($sql);

        if ($this->_lastError) {

            $this->mensaje = "error al consultar Inventario " . $sql;

            echo $sql."<br>";

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

       

    }

      function getRegistrosRetiro($arregloDatos) {

       $sql = " SELECT * FROM inventario_maestro_movimientos

                WHERE codigo=$arregloDatos[id_levante]"; 

       

        $this->query($sql);

        if ($this->_lastError) {

            $this->mensaje = "error al consultar Inventario " . $sql;

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

       

    }

    

    function setCosteo($arregloDatos) {

        if(empty($arregloDatos[fob])){$arregloDatos[fob]=0;}

       $sql = "UPDATE inventario_entradas

               SET valor=$arregloDatos[fob]

               WHERE codigo=$arregloDatos[id_item]"; 

       echo $sql;

        $this->query($sql);

        if ($this->_lastError) {

            $this->mensaje = "error al consultar Inventario " . $sql;

            $this->estilo = $this->estilo_error;

            echo 0;

        }

       echo 1;

    }

    

    // lista la mercancia ya retirada

    function getCuerpoRetiro($arregloDatos) {

        

   

        $sql = " SELECT 

                    im.codigo           as id_retiro,

                    inventario_entrada  as id_item,

                    MAX(tipo_movimiento)as tipo_movimiento,

                    SUM(peso_naci)      as peso_naci ,

                    SUM(peso_nonac)     as peso_nonac,

                    SUM(cantidad_nonac) as cantidad_nonac,

                    SUM(cantidad_naci)  as cantidad_naci,

                    SUM(cif)       as cif ,

                    SUM(fob_nonac)      as fob_nonac,

                    MAX(arribos.orden)  as orden,

                    MAX(arribos.arribo) as arribo,

                    MAX(ie.codigo)      as cod_item,

                    MAX(ref.nombre)     as nombre_referencia,

                    MAX(ref.ref_prove)  as cod_referencia

                    

                FROM inventario_entradas ie,arribos,do_asignados,referencias ref,embalajes,inventario_movimientos im

              

                WHERE im.inventario_entrada =ie.codigo

                AND arribos.arribo          =ie.arribo

                AND arribos.orden           =do_asignados.do_asignado

                AND ie.referencia           =ref.codigo

                AND embalajes.codigo        =ie.un_empaque

                AND im.cod_maestro='$arregloDatos[id_levante]'

                AND im.tipo_movimiento in(3,7,8,9)

                

            ";

    //AND tipo_movimiento         =3 

      

        $sql.=" GROUP BY ref.ref_prove"; //inventario_entrada 

        //echo $sql."<br>";

        $this->query($sql);

        if ($this->_lastError) {

            $this->mensaje = "error al consultar Inventario " . $sql;

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

    }

     function getCabezaProceso($arregloDatos) {

         $this->getCabezaLevante($arregloDatos);

     }
     
   function findConductor($arregloDatos) {

        $sql=" SELECT codigo,nombre
                    FROM ciudades
                    WHERE  nombre like '%$arregloDatos[q]%'";

        $this->query($sql);
        if($this->_lastError) {
                echo $sql;
                        return TRUE;
        }
    }
    
    function findCliente($arregloDatos) {

            $sql=" SELECT numero_documento,razon_social
                        FROM clientes
                        WHERE  razon_social like '%$arregloDatos[q]%'";

            $this->query($sql);
            if($this->_lastError) {
                    echo $sql;
                            return TRUE;
            }
        }

    // devuelve los datos de la cabeza del retiro y del levente

    function getCabezaLevante($arregloDatos) {

        $sql = " SELECT   

                    imm.codigo as num_levante,

                    imm.lev_sia,

                    lev_cant_declaraciones as lev_cant,

                    lev_bultos,

                    imm.fecha,

                    imm.destinatario,

                    imm.obs,

                    imm.fmm,

                    imm.lev_cuenta_grupo,

                    clientes.razon_social,

                    imm.producto,

                    camiones.conductor_nombre,

                    camiones.codigo as id_camion,

                    camiones.placa,

                    referencias.nombre as nombre_producto,

                    imm.cantidad,

                    imm.cantidad_nac,

                    imm.cantidad_ext,

                    imm.doc_tte,

                    imm.peso,

                    imm.valor,

                    imm.unidad,

                    imm.bodega,

                    imm.orden,

                    imm.cierre,

                    imm.pos_arancelaria,
                    imm.tip_movimiento,
                    imm.posicion,
                    imm.pedido,
                    imm.ciudad as codigo_ciudad,
                    ubicaciones.nombre  as nombre_ubicacion,
                    ciudades.nombre as nombre_ciudad

                 FROM inventario_maestro_movimientos imm

                 LEFT JOIN clientes     ON imm.lev_sia   =  clientes.numero_documento
                 LEFT JOIN camiones     ON imm.id_camion =  camiones.codigo
                 LEFT JOIN referencias  ON imm.producto  =  referencias.codigo
                 LEFT JOIN ubicaciones  ON imm.posicion  =  ubicaciones.codigo
                 LEFT JOIN ciudades     ON imm.ciudad    =  ciudades.codigo
                 WHERE  imm.codigo=$arregloDatos[id_levante]

         ";



        //echo $sql;

        $this->query($sql);

        //echo "xxxxxxx".$unDatos->N;

        if ($this->_lastError) {

            $this->mensaje = "error al consultar Inventario " . $sql;

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

    }

    

    function setCabezaLevante($arregloDatos) {

        //var_dump($arregloDatos);

        $total_peso=$arregloDatos[tot_peso_nonac]+$arregloDatos[tot_peso_nac];

        if(empty($arregloDatos[cantidad])){$arregloDatos[cantidad]=0;}

        $sql = " UPDATE inventario_maestro_movimientos

                    SET lev_sia                 ='$arregloDatos[sia]',

                        

                        lev_bultos              ='$arregloDatos[lev_bultos]',

                        lev_cant_declaraciones  ='$arregloDatos[lev_cant]',

                        fmm                     ='$arregloDatos[fmm]',

                        obs                     ='$arregloDatos[obs]',

                        producto                ='$arregloDatos[producto]',

                        unidad                  ='$arregloDatos[unidad]',

                        cantidad                ='$arregloDatos[cantidad]',

                        cantidad_ext            ='$arregloDatos[cantidad_ext]',

                        cantidad_nac            ='$arregloDatos[cantidad_nac]',

                        cierre                  ='$arregloDatos[cierre]',

                        orden                   ='$arregloDatos[do_asignado]',

                        doc_tte                 ='$arregloDatos[doc_tte]',

                        valor                   ='$arregloDatos[valor]',

                        peso                    ='$total_peso',

                        posicion                ='$arregloDatos[posicion]',

                        pos_arancelaria         ='$arregloDatos[pos_arancelaria]',
                        pedido                  ='$arregloDatos[pedido]',
                        ciudad                  ='$arregloDatos[codigo_ciudad]',
                       id_camion   ='$arregloDatos[id_camion]',

                       destinatario='$arregloDatos[destinatario]'

                 WHERE codigo= $arregloDatos[id_levante]

            ";



        //echo $sql;

        $this->_lastError=NULL;

        $this->query($sql);

        if ($this->_lastError) {

            $arregloDatos[mensaje] = "error al actualizar Inventario " . $sql;

            $arregloDatos[estilo] = $this->estilo_error;

            return TRUE;

        }

        $arregloDatos[mensaje]="Se guardo correctamente el registro " ;

        $arregloDatos[estilo]="ui-state-highlight" ;

    }

    

    function cantidadNacionalParcial($arregloDatos) {

        

    }

    

    function setConteoParciales($arregloDatos) {

        //var_dump($arregloDatos);

        

        $sql = " UPDATE inventario_maestro_movimientos

                   SET lev_cuenta_grupo=lev_cuenta_grupo+1

                   WHERE codigo=$arregloDatos[id_levante]

            ";



        //echo $sql;

        $this->query($sql);

        if ($this->_lastError) {

            $this->mensaje = "error al incrementar contador de parciales " . $sql;

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

    }

    

    // obtiene el numero de parciales 

    function getConteoParciales($arregloDatos) 

    {

    

        $sql = " SELECT grupo FROM inventario_declaraciones

                 WHERE cod_maestro           =$arregloDatos[id_levante]

                 AND grupo   =$arregloDatos[grupo_borrado]

            ";

       

   

        $this->query($sql);

       

        if ($this->_lastError) {

            $this->mensaje = "error al consultar numero de parciales " . $sql;

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

    }

    

    // disminuye el numero de parciales en caso que se borren levantes

    function menosConteoParciales($arregloDatos) 

    {

    

        $sql = " UPDATE  inventario_maestro_movimientos

                 SET lev_cuenta_grupo=lev_cuenta_grupo-1

                 WHERE codigo           =$arregloDatos[id_levante]

                 AND lev_cuenta_grupo   =$arregloDatos[grupo_borrado]

            ";

       

      //echo $sql;

        $this->query($sql);

       

        if ($this->_lastError) {

            $this->mensaje = "error al ajustar parciales " . $sql;

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

    }

    

    // disminuye el numero de parciales en caso que se borren levantes

    function updateConteoParciales($arregloDatos) 

    {

    

        $sql = " UPDATE  inventario_maestro_movimientos

                 SET lev_cuenta_grupo=$arregloDatos[num_grupo]

                 WHERE codigo           =$arregloDatos[id_levante]

                 

            ";

       

      //echo $sql;

        $this->query($sql);

       

        if ($this->_lastError) {

            $this->mensaje = "error al ajustar parciales " . $sql;

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

    }

    

    

     // Funcion que retorna el ultimo ID Insertado 

    function getConsecutivo($arregloDatos) 

    {

        $fecha=FECHA;

	$sql=" SELECT LAST_INSERT_ID( ) as consecutivo";

			

        $this->query($sql);

        if($this->_lastError) 

        {

            $arregloDatos[mensaje]=" Error al obtener Consecutivo $sql".$this->_lastError->message;

            $arregloDatos[estilo]=$this->estilo_error;

            return TRUE;

        }

        $this->fetch();

         //echo "READYXXXXXXXXXXXXXXx ".$this->consecutivo;

        return  $this->consecutivo;

        

    }

    

    

    function newLevante($arregloDatos) {

        //var_dump($arregloDatos);

        // se inserta como tipo de retiro el mismo tip-movimiento para formatear

        if($arregloDatos[tipo_retiro_filtro]){

            $arregloDatos[tipo_retiro_filtro]= $arregloDatos[tipo_movimiento];   

        }

        $fecha=FECHA;

        $sql = " INSERT  INTO inventario_maestro_movimientos

                (lev_documento,fecha,tip_movimiento,tipo_retiro)

               VALUES('$arregloDatos[doc_filtro]','$fecha','$arregloDatos[tipo_movimiento]','$arregloDatos[tipo_retiro]');

               

        ";

        //echo $sql;		

        $this->query($sql);

        if ($this->_lastError) {

             $arregloDatos[mensaje] = "error al crear cabdoc del levante ".$sql;

             $arregloDatos[estilo] = $this->estilo_error;

            return TRUE;

        }

      

       return  $this->getConsecutivo($arregloDatos);

       

    }

    

  

    

    function newDeclaracion($arregloDatos) {

        $fecha=FECHA;

        $sql = " INSERT  INTO inventario_declaraciones

                (cod_maestro,

                 fecha,

                 num_declaracion,

                 num_levante,

                 tipo_declaracion,

                 subpartida,

                 modalidad,

                 trm,

                 fob,

                 fletes,

                 aduana,

                 arancel,

                 total,

                 iva,

                 obs,

                 grupo

        )

               VALUES('$arregloDatos[id_levante]',

                      '$fecha',

                      '$arregloDatos[num_declaracion]',

                      '$arregloDatos[num_levante]',

                      '$arregloDatos[tipo_declaracion]',

                      '$arregloDatos[subpartida]',

                      '$arregloDatos[modalidad]',

                      '$arregloDatos[trm]',

                      '$arregloDatos[fob]',  

                      '$arregloDatos[fletes]',

                      '$arregloDatos[aduana]',

                      '$arregloDatos[arancel]',

                      '$arregloDatos[total]',

                      '$arregloDatos[iva]',

                      '$arregloDatos[obs]',

                      '$arregloDatos[grupo]');

               ";

        //echo $sql;		

        $this->query($sql);

        if ($this->_lastError) {

             $arregloDatos[mensaje] = "error al crear cabdoc del levante ";

              

             $arregloDatos[estilo] = $this->estilo_error;

            return TRUE;

        }

        $this->getLevante($arregloDatos);

        $this->fetch();

        return $this->codigo;

    }

    

    // Agrega registro de mercancia retirada

    function addItemRetiro($arregloDatos) 

    {

     

        //si no existe un levante se deja como levante el id del movimiento esto permite borrar movimientos con varios registros 

        if(empty($arregloDatos[num_levante]) ){

            $arregloDatos[num_levante] =$arregloDatos[id_levante] ;

        }

       switch($arregloDatos[tipo_retiro_filtro] ) {

            case 1:// mercancia nacional

                $arregloDatos[peso_nonaci_para]     =0;

                $arregloDatos[cantidad_nonaci_para] =0;

                $arregloDatos[fob_nonaci_para]      =0;

               

                

            break;

            case 2:// reexportacion

                

            break;

            

        }

        $fecha = FECHA;

        $sql = " INSERT INTO inventario_movimientos

                        (fecha,inventario_entrada,tipo_movimiento,peso_naci,peso_nonac,cantidad_naci,cantidad_nonac,cif,fob_nonac,cod_maestro,num_levante)

                VALUES('$fecha',$arregloDatos[id_item],$arregloDatos[tipo_movimiento],-$arregloDatos[peso_naci_para],-$arregloDatos[peso_nonaci_para],-$arregloDatos[cantidad_naci_para],-$arregloDatos[cantidad_nonaci_para],-$arregloDatos[cif_ret],- $arregloDatos[fob_ret] ,$arregloDatos[id_levante],'$arregloDatos[num_levante]')

        ";

        //echo $sql;

        $this->query($sql);

        if ($this->_lastError) {

            $arregloDatos[mensaje] = "error al retirar la mercancia ";//$arregloDatos[mensaje] = "error al retirar la mercancia ";//

            $arregloDatos[estilo]  = $this->estilo_error;

            echo "error".$sql;

            return TRUE;

        }

        $arregloDatos[mensaje] = "se retiro correctamente la mercancia ";

        $arregloDatos[estilo]  = $this->estilo_ok;  

    }

    

    function addItemAdicional($arregloDatos)

    {

         //El valor no tiene sentido restarse

        if(empty($arregloDatos[peso_naci_para])){$arregloDatos[peso_naci_para]=$arregloDatos[peso_naci_aux];} // cuando es el ultimo se inactiva y no pasa

        $fecha = FECHA;

        $sql = " INSERT INTO inventario_movimientos

                        (fecha,inventario_entrada,tipo_movimiento,peso_naci,peso_nonac,cantidad_naci,cantidad_nonac,cif,cod_maestro)

                 VALUES('$fecha',$arregloDatos[id_item],9,-$arregloDatos[peso_naci_para],0,-$arregloDatos[cantidad_naci_para],0,-$arregloDatos[cif],$arregloDatos[id_levante_adicional])

        ";

        

        $this->query($sql);

        if ($this->_lastError) 

        {

            $arregloDatos[mensaje] .= "error al nacionalizar la mercancia ";

            $arregloDatos[estilo]  = $this->estilo_error;

            return TRUE;

        }

        

        $arregloDatos[mensaje] = "se agrego correctamente la mercancia ";

        $arregloDatos[estilo]  = $this->estilo_ok;

    }



   

     function addItemLevante($arregloDatos)

     {

         //El valor no tiene sentido restarse

        if(empty($arregloDatos[peso_naci_para])){$arregloDatos[peso_naci_para]=$arregloDatos[peso_naci_aux];} // cuando es el ultimo se inactiva y no pasa

        $fecha = FECHA;

        $sql = " INSERT INTO inventario_movimientos

                        (fecha,inventario_entrada,tipo_movimiento,peso_naci,peso_nonac,cantidad_naci,cantidad_nonac,cif,fob_nonac,cod_maestro,num_levante)

                 VALUES('$fecha',$arregloDatos[id_item],2,$arregloDatos[peso_naci_para],0,$arregloDatos[cantidad_naci_para],0,$arregloDatos[fob_naci_para],-$arregloDatos[fob],$arregloDatos[id_levante],'$arregloDatos[num_levante]')

        ";

        

        $this->query($sql);

        if ($this->_lastError) 

        {

            $arregloDatos[mensaje] .= "error al nacionalizar la mercancia ";//$arregloDatos[mensaje] = "error al nacionalizar la mercancia ";//

            $arregloDatos[estilo]  = $this->estilo_error;

            return TRUE;

        }

        $arregloDatos[tipo_movimiento]      =30;

        $arregloDatos[peso_nonaci_para]     =$arregloDatos[peso_naci_para];

        $arregloDatos[cantidad_nonaci_para] =$arregloDatos[cantidad_naci_para];

        $arregloDatos[fob_nonaci_para]      =0;

        $arregloDatos[cif_ret]              =0;

        $arregloDatos[fob_ret]              =0;

  

        $arregloDatos[peso_naci_para]       =0;

        $arregloDatos[cantidad_naci_para]   =0;

        $this->addItemRetiro($arregloDatos);

        // Se crea el registro de retiro

        $arregloDatos[mensaje] = "se nacionalizo correctamente la mercancia ";

        $arregloDatos[estilo]  = $this->estilo_ok;

    }



    function getLevante($arregloDatos) {

        $sql = " SELECT codigo FROM inventario_maestro_movimientos

                WHERE 	lev_documento='$arregloDatos[doc_filtro]'

               ";



        $this->query($sql);

        if ($this->_lastError) {

            $this->mensaje = "error al consultar ID levante ";

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

    }

    

    // Funcion que se encarga de acumular el valor CIF

      function getAcomulaCif($arregloDatos) {

          //var_dump($arregloDatos);

        $sql = " UPDATE inventario_movimientos

                 SET cif=cif+$arregloDatos[cif_para]

                 WHERE 	inventario_entrada=$arregloDatos[id_item]

               ";

        //echo $sql;

        //$this->query($sql);

        if ($this->_lastError) {

            $this->mensaje = "error al consultar ID levante ";

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

    }

    

    //devuelve el ultimo id creado en la tabla  inventario_maestro_movimientos

    function getIdRetiro($arregloDatos) {

        $sql = " SELECT max(codigo) as codigo FROM inventario_maestro_movimientos";



        $this->query($sql);

        if ($this->_lastError) {

            $this->mensaje = "error al consultar ID levante ";

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

    }

    

     // Agrega registro de mercancia en la tabla de inventario_entrada

    function addItemInventario($arregloDatos) {

        

     

       $fecha = FECHA;

        $sql = "INSERT inventario_entradas (arribo,orden,fecha,referencia,posicion,un_empaque) VALUES ('$arregloDatos[arribo]','$arregloDatos[orden]',CURDATE(),$arregloDatos[producto_adicional],1,1);";

        //echo "<br>".$sql;

        $this->query($sql);

        if ($this->_lastError) {

            $arregloDatos[mensaje] = "error al enviar la mercancia a proceso ";

            $arregloDatos[estilo]  = $this->estilo_error;

            echo $sql;

            return TRUE;

        }

        $arregloDatos[mensaje] = "se envio la mercancia a proceso correctamente  ";

        $arregloDatos[estilo]  = $this->estilo_ok;  

    }

    

    // Obtiene  inventario_entrada de la tabla inventario_movimientos

     function getParaInsertar($arregloDatos) 

    {

        

        $sql = " SELECT 

                 MAX(arribo) as arribo,

                 MAX(orden) as orden

                 FROM inventario_entradas ie,inventario_movimientos im

                 WHERE im.cod_maestro       ='$arregloDatos[id_levante_adicional]'

                 AND im.inventario_entrada  =ie.codigo";

               //cod_maestro

      //echo $sql;

        $this->query($sql);

        if ($this->_lastError) {

            $this->mensaje = "error al obtener el campo inventario_entrada ";

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

         $this->fetch();

           $this->id_levante;

           $arregloDatos[arribo]=$this->arribo;

           $arregloDatos[orden] =$this->orden;

           

    }

    

   

    

    function delMercanciaLevante($arregloDatos) 

    {

      

        //var_dump($arregloDatos);

        $sql = " DELETE FROM inventario_movimientos

                 WHERE num_levante   ='$arregloDatos[num_levante_del]'";

        

        $this->query($sql);

        if ($this->_lastError) {

            $this->mensaje = "error al borrar mercancia del levante ";

          

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

    }

    

    // Actualiza las cantidades del inventario, segun la matriz de integracion

    function updateMovimiento($arregloDatos) 

    {

      $sql = " UPDATE inventario_movimientos

               SET   cantidad_naci  ='$arregloDatos[cantidad_nac]',

                     cantidad_nonac ='$arregloDatos[cantidad_ext]',

                     peso_naci      ='$arregloDatos[tot_peso_nac]',

                     peso_nonac     ='$arregloDatos[tot_peso_nonac]',

                     fob_nonac      ='$arregloDatos[valor]'

               WHERE inventario_entrada='$arregloDatos[inventario_entrada]'";

               

      // echo   $sql;

      $this->query($sql);

        if ($this->_lastError) {

            $this->mensaje = "error al actualizar el movimiento ";

            echo   $sql;

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

    }

    // Recupera el ID del Nuevo producto  matriz de integracion creado

     function getIdInventario($arregloDatos) 

    {

      $sql = " SELECT codigo 

               FROM inventario_entradas

               WHERE arribo='$arregloDatos[arribo]'";

               

       //echo   $sql;

      $this->query($sql);

        if ($this->_lastError) {

            $this->mensaje = "error al borrar mercancia del levante ";

            

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

         $this->fetch();

         return $this->codigo;

    }

    

    function delMercancia($arregloDatos) 

    {

      

      $sql = " DELETE FROM inventario_movimientos

                WHERE codigo='$arregloDatos[id_retiro_del]' OR (num_levante='$arregloDatos[id_levante]' AND tipo_movimiento=30)";

             

      // echo   $sql;

      $this->query($sql);

        if ($this->_lastError) {

            $this->mensaje = "error al borrar mercancia del levante ";

          

            $this->estilo = $this->estilo_error;

            return TRUE;

        }

    }

    

    function existeCliente($arregloDatos) {

				

        $sql=" SELECT numero_documento,razon_social

                    FROM clientes

                    WHERE  numero_documento = '$arregloDatos[por_cuenta]'";



        $this->query($sql);

        if($this->_lastError) {

                        echo $sql;

                        return TRUE;

        }

			

    }

    

    function existeLevante($arregloDatos) {

				

        $sql=" SELECT num_levante

               FROM inventario_movimientos

               WHERE  num_levante = '$arregloDatos[num_levante]'";

        //echo $sql;

        $this->query($sql);

        if($this->_lastError) {

            echo $sql;

            return TRUE;

        }

			

    }

     

    //Cuenta el numero de declaraciones

    function cuentaDeclaraciones($arregloDatos) 

     {

       $sql=" SELECT COUNT( DISTINCT num_declaracion ) as cantidad,sum(peso_naci) as peso_declaraciones

            FROM inventario_movimientos im, inventario_maestro_movimientos imm, inventario_declaraciones id

            WHERE imm.codigo    = im.cod_maestro

            AND imm.codigo      = id.cod_maestro

            AND tipo_movimiento =2

            AND imm.codigo      =$arregloDatos[id_levante]

            AND id.grupo        =$arregloDatos[cuenta_grupos] "; 

      //echo $sql;

        $this->query($sql);

        if($this->_lastError) {

            echo $sql;

            return TRUE;

        }

        

        $this->fetch();

        if(empty($this->peso_declaraciones)){$this->peso_declaraciones=0; }

        $arregloDatos[cant_declaraciones]=$this->cantidad;

        $arregloDatos[peso_declaraciones]=$this->peso_declaraciones;

     }

     

      //Cuenta el numero de grupos creados

    function ultimoGrupoCreado($arregloDatos) 

     {

       $sql=" SELECT max(grupo) as cantidad

              FROM inventario_declaraciones

              WHERE cod_maestro      =$arregloDatos[id_levante]"; 

        

        $this->query($sql);

        if($this->_lastError) {

            echo $sql;

            return TRUE;

        }

       

        $this->fetch();

        $arregloDatos[cuenta_grupos]=$this->cantidad;

        return  $arregloDatos[cuenta_grupos];

     }

     

    //Cuenta el numero de grupos en el caso que halla parciales

    function ultimoGrupo($arregloDatos) 

     {

       $sql=" SELECT lev_cuenta_grupo as cantidad

              FROM inventario_maestro_movimientos

              WHERE codigo      =$arregloDatos[id_levante]"; 

        $this->query($sql);

        if($this->_lastError) {

            echo

            $sql;

            return TRUE;

        }

       

        $this->fetch();

        $arregloDatos[cuenta_grupos]=$this->cantidad;

        return  $arregloDatos[cuenta_grupos];

        //echo "xxx";

     }

    

  

    

    function getCliente($arregloDatos) {

      $sql=" SELECT numero_documento,razon_social

               FROM clientes

               WHERE  numero_documento = '$arregloDatos[por_cuenta_filtro]'";

        //echo $sql;

        $this->query($sql);

        if($this->_lastError) {

            echo $sql;

            return TRUE;

        }  

    }

    	

        function lista($tabla,$condicion=NULL,$campoCondicion=NULL) 

        {

            $sql="SELECT codigo,nombre

                  FROM $tabla

		  WHERE codigo not in('0')" ;

            if($condicion<> NULL and $condicion <> '%'){

                    $sql.=" AND $campoCondicion in ('$condicion')" ;

            }

	    $sql.="		ORDER BY   nombre	" ;

	

    

            $this->query($sql); 

            if ($this->_lastError) 

            {

                return FALSE;

            }else{

                $arreglo = array();

		while($this->fetch())

                {

                    $arreglo[$this->codigo] =  ucwords(strtolower($this->nombre));

		}

	    }

           

            return $arreglo ;

	}

        

  

    

    function findDocumento($arregloDatos) 

    {

	//var_dump($arregloDatos);		

        $sql = " SELECT DISTINCT 

                        doc_tte,

                        do_asignado,

                 SUM(im.peso_nonac)             AS peso_nonac,

                                 SUM(im.peso_naci)              AS peso_nac                

                                   

                 FROM inventario_movimientos im,inventario_entradas ie,arribos,do_asignados

                 WHERE im.inventario_entrada    =ie.codigo

                    AND arribos.arribo          =ie.arribo

                    AND arribos.orden           =do_asignados.do_asignado

                    AND do_asignados.por_cuenta ='$arregloDatos[cliente]'

                    AND doc_tte like '%$arregloDatos[q]%'" ;

        

         $sql .= "      GROUP BY  doc_tte,do_asignado";

        switch($arregloDatos[tipo_movimiento])

        {

            case 2: // Nacionalizacion 

                //$sql .= " HAVING  TRUNCATE(peso_nonac,1) > 0 ";

            break;

            case 3: //  Retiro 

               // $sql .= " HAVING  TRUNCATE(peso_nac,1)   > 0 ";

            break;

        }

  

        //echo $sql;

        $this->query($sql);

        if($this->_lastError) {

            echo $sql;

            return TRUE;

        }

    }

    

    function listarLevantes($arregloDatos) 

    {

	//var_dump($arregloDatos);		

        $sql = " SELECT DISTINCT 

                        imm.fecha,

                        imm.codigo as cod_mov,

                        imm.lev_documento,

                        im.tipo_movimiento,

                        imm.tipo_retiro,

                        itm.nombre as nombre_movimiento,

                        clientes.numero_documento,

                        clientes.razon_social as nombre_cliente,

                        aduana.	razon_social as nombre_aduana,

                        camiones.placa,

                        camiones.conductor_nombre,

                        im.tipo_movimiento as movimiento_tipo

                        

                                 

                 FROM 

                      inventario_movimientos im,

                      inventario_tipos_movimiento itm,

                      inventario_entradas ie,

                      arribos,

                      do_asignados,

                      clientes,

                      inventario_maestro_movimientos imm

                      LEFT JOIN clientes aduana ON imm.lev_sia  =  aduana.numero_documento

                      LEFT JOIN camiones ON imm.id_camion       =  camiones.codigo

                 WHERE im.cod_maestro=imm.codigo

                 AND   im.tipo_movimiento       =itm.codigo

                 AND   im.inventario_entrada    =ie.codigo

                 AND   arribos.arribo           =ie.arribo

                 AND   arribos.orden            =do_asignados.do_asignado

                 AND   do_asignados.por_cuenta  =clientes.numero_documento

                 AND   im.tipo_movimiento       not in(1,30)";

      

     if(!empty($arregloDatos[tipo_movimiento])){

          $sql .= " AND im.tipo_movimiento=$arregloDatos[tipo_movimiento] ";

      }    

      $sql .= " ORDER BY imm.codigo DESC";

      //echo $sql;

        $this->query($sql);

        if($this->_lastError) {

            echo $sql;

            return TRUE;

        }

    }

    

    //  trae la informacion de un levante   

    function traeLevante($arregloDatos) 

    {

        //var_dump($arregloDatos);

        $sql = "SELECT

                    inventario_movimientos.peso_naci,

                    inventario_movimientos.cif,

                    inventario_movimientos.cantidad_naci,

                    inventario_declaraciones.num_levante,

                    inventario_declaraciones.num_declaracion,

                    inventario_declaraciones.num_declaracion,

                    inventario_declaraciones.fecha,

                    inventario_declaraciones.modalidad,

                    inventario_declaraciones.trm,

                    inventario_declaraciones.fob,

                    inventario_declaraciones.fletes,

                    inventario_declaraciones.aduana,

                    inventario_declaraciones.arancel,

                    inventario_declaraciones.iva,

                    inventario_declaraciones.total,

                    inventario_declaraciones.obs,

                    ref.nombre          as nombre_referencia,

                    ref.ref_prove       as cod_referencia,

                    embalajes.nombre    as nombre_empaque,

                    ie.embalaje         as q_embalaje,

                    ie.modelo                  as modelo,

                    doc_tte

                FROM inventario_declaraciones,inventario_movimientos,inventario_entradas ie,arribos,do_asignados,referencias ref,embalajes 

                WHERE inventario_declaraciones.num_levante= '$arregloDatos[num_levante]'

                AND inventario_movimientos.inventario_entrada =ie.codigo

                AND arribos.arribo          =ie.arribo

                AND inventario_declaraciones.num_levante=inventario_movimientos.num_levante

                AND arribos.orden           =do_asignados.do_asignado

                AND ie.referencia           =ref.codigo

                AND embalajes.codigo        =ie.un_empaque

                AND inventario_movimientos.tipo_movimiento =2";

        

        

        //echo $sql;

        $this->query($sql);

        if($this->_lastError) {

            echo $sql;

            return TRUE;

        }

    }

    

    // trae los valores por grupo,parcial

    function getSumaGrupo($arregloDatos) {

      $sql=" SELECT im.cod_maestro,

                    SUM(im.cantidad_naci) as sum_cant_naci

               FROM inventario_movimientos im,inventario_maestro_movimientos imm,inventario_declaraciones declaracion

               WHERE  im.cod_maestro        = imm.codigo

               AND declaracion.num_levante  =im.num_levante

               AND im.cod_maestro           = '$arregloDatos[id_levante]'

               AND declaracion.grupo        = '$arregloDatos[id_grupo]'

               GROUP BY im.cod_maestro";

        //echo $sql;

        $this->query($sql);

        if($this->_lastError) {

            echo $sql;

            return TRUE;

        }  

    }

    

    function cambiaMovimientoCabeza($arregloDatos) 
   {
        //var_dump($arregloDatos);
      $sql="UPDATE inventario_maestro_movimientos SET tip_movimiento=$arregloDatos[nuevo_estado] WHERE codigo='$arregloDatos[id_levante]'";
      //echo  $sql;
      $this->query($sql);
        if($this->_lastError)
        {
            echo "error".$sql;

            return TRUE;

        }  

    }
    
    function cambiaMovimientoCuerpo($arregloDatos) 
   {
        //var_dump($arregloDatos);
      $sql="UPDATE inventario_movimientos SET tipo_movimiento=$arregloDatos[nuevo_estado] WHERE cod_maestro='$arregloDatos[id_levante]'";
       //echo  $sql;
      $this->query($sql);
        if($this->_lastError)
        {
            echo "error".$sql;

            return TRUE;

        }  

    }

    

    // SELECT CASE WHEN 1>0 THEN 'true' ELSE 'false' END;

    function hayMovimientos($arregloDatos){

        

         $sql=" SELECT max(nombre) as movimiento,max(tipo_movimiento) as tipo_movimiento

                FROM inventario_movimientos,inventario_tipos_movimiento

                WHERE inventario_movimientos.tipo_movimiento =inventario_tipos_movimiento.codigo

                AND inventario_entrada='$arregloDatos[cod_item]'

                AND tipo_movimiento in(3,6,8,9)

                ";

         //echo $sql;

         $this->query($sql);

        if($this->_lastError) {

            echo $sql;

             return $this->N;

        }

        

        //return $this->N;

    }

    function getToolbar($arregloDatos)

    {

        

    }

    



}



?>