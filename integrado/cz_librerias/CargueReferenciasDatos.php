<?php

require_once("MYDB.php"); 

class CargueReferencias extends MYDB {

    function CargueReferencias() {
        $this->estilo_error = "ui-state-error";
        $this->estilo_ok = "ui-state-highlight";

        $this->mensaje_error = " error al guardar el registro";
        $this->mensaje_ok = " Se guardo correctamente el registro1x";
    }

    function filtro($arregloDatos) {
        
    }

    function listarCargueReferencias($arregloDatos) {
        
        $sql = "SELECT   Convert(Char(10), tes_CargueReferenciass.Fecha,103) as Fecha
                          ,tip_doc,num_doc,val_doc,cod_alu,ano_ref
                          ,per_ref,tip_ref,Tip_error,des_error,val_ext
                          ,cod_con,digito,cod_suc,cod_cco,cod_cl1,semestr
                          ,ciclo,ano_alu,cod_ban,num_ext,val_cons,impuesto
                          ,fec_proc,descrip,num_ing,Convert(Char(10), tes_CargueReferenciass.fec_ing,103) as Fecha_Recibo,ind_032,id_cxcpl
                 FROM tes_CargueReferenciass where id_cxcpl <>'0' ";
        if (!empty($arregloDatos[fecha_inicio]) && $arregloDatos[fecha_inicio] != 'undefined' && !empty($arregloDatos[fecha_fin]) && $arregloDatos[fecha_fin] != 'undefined') {
            $sql.= "  and  Fecha between '" . $arregloDatos[fecha_inicio] . "' and '" . $arregloDatos[fecha_fin] . "'";
        }
        if (empty($arregloDatos[fecha_inicio]) || $arregloDatos[fecha_inicio] == 'undefined'
                && empty($arregloDatos[fecha_fin]) || $arregloDatos[fecha_fin] == 'undefined'
                && !empty($arregloDatos[codigo_id]) && $arregloDatos[codigo_id] != 'undefined') {
            if(empty($arregloDatos[numero])){
               
                $sql.="  and  id_cxcpl =" . $arregloDatos[codigo_id];
            }
            
        }
        if (!empty($arregloDatos[codigo_id]) && $arregloDatos[codigo_id] != 'undefined') {
            $sql.="  AND  id_cxcpl =" . $arregloDatos[codigo_id];
        }
        if (!empty($arregloDatos[t_num_ext]) && $arregloDatos[t_num_ext] != 'undefined') {
            $sql.="  AND  num_ext ='" . $arregloDatos[t_num_ext] . "'";
        }

        if (!empty($arregloDatos[banco]) && $arregloDatos[banco] != 'undefined') {
            $sql.= "  AND cod_ban='" . $arregloDatos[banco] . "'";
        }
        if (!empty($arregloDatos[numero]) && $arregloDatos[numero] != 'undefined') {
            $sql.="  AND  num_doc ='$arregloDatos[numero]'";
        }
        if (!empty($arregloDatos[valor]) && $arregloDatos[valor] != 'undefined') {
            $sql.="  AND  val_doc =" . $arregloDatos[valor];
        }

        if (!empty($arregloDatos[t_doc]) && $arregloDatos[t_doc] != 'undefined') {
            $sql.="  AND  tip_doc ='" . $arregloDatos[t_doc] . "'";
        }
        if ($arregloDatos[tiperror]!='3') {
            $sql.="  AND  Tip_error ='" . $arregloDatos[tiperror] . "'";
        }
          
        if ($arregloDatos[excel]) {
            return $sql;
        }
       
        $this->query($sql);
        if ($this->_lastError) {

            $arregloDatos[mensaje] = " Error al Consultar el tipo de solicitud ";
            $arregloDatos[estilo] = "ui-state-error";
        }
    }

    function updateCargueReferencias($arregloDatos) {

        $sql = "UPDATE  tes_CargueReferenciass  SET 
             num_doc='" . $arregloDatos[filtronum_doc] . "' 
            , Fecha='" . $arregloDatos[filtroFecha] . "'
            , tip_doc='" . $arregloDatos[filtrotip_doc] . "'
            , val_doc=" . $arregloDatos[filtroval_doc] . "
            , des_error='" . $arregloDatos[filtrodes_error] . "' WHERE id_cxcpl=" . $arregloDatos[codigo_id];

        $this->query($sql);
        if ($this->_lastError) {
            $arregloDatos[mensaje] = " Error al Actualizar el tipo de solicitud ";
            $arregloDatos[estilo] = "ui-state-error";
            return false;
        }
        $sentencia = str_replace("'", "", $sql);
        $this->auditoria('CargueReferencias', "updateCargueReferencias $arregloDatos[filtronum_doc]", $sentencia, 'INFO');
        $arregloDatos[mensaje] = "La actualizacion fue exitosa ";
        $arregloDatos[estilo] = "ui-state-highlight";
        return $this->N;
    }

    function borrarCargueReferencias($arregloDatos) {

        $sql = "DELETE tes_CargueReferenciass
             WHERE id_cxcpl='$arregloDatos[codigo_id]'";
       // hacer un select del num_doc para poder auditar borrado
        $this->query($sql);
        if ($this->_lastError) {

            $arregloDatos[mensaje] = " Error al Consultar el tipo de solicitud ";
            $arregloDatos[estilo] = "ui-state-error";
        }
        $registrom = $arregloDatos[registrom];
        $sentencia = str_replace("'", "", $sql);
        
        $this->auditoria('CargueReferencias', "borrado $arregloDatos[codigo_id]", $sentencia, 'INFO', $registrom);
        $arregloDatos[mensaje] = "Se borro exitosamente el registro";
        $arregloDatos[estilo] = "ui-state-highlight";
        return $this->N;
    }

    function getCargueReferencias($arregloDatos) {
        $sql = "select * 
              from tes_CargueReferenciass 
              where id_cxcpl='$arregloDatos[codigo_id]' ";
        $this->query($sql);
        if ($this->_lastError) {

            $arregloDatos[mensaje] = " Error al Consultar el CargueReferencias";
            $arregloDatos[estilo] = "ui-state-error";
        }

        return $this->N;
    }

    function updateExtConfirmar($arregloDatos) {

        $sql = "UPDATE  tes_CargueReferenciass  SET 
                Tip_error=1, 
                des_error='' 
              WHERE id_cxcpl=" . $arregloDatos[codigo_id];

        $this->query($sql);
        if ($this->_lastError) {
            $arregloDatos[mensaje] = " Error al Actualizar el tipo de solicitud";
            $arregloDatos[estilo] = "ui-state-error";

            return false;
        }
        $sentencia = str_replace("'", "", $sql);
        $this->auditoria('CargueReferencias', "Desconfirmo", $sentencia, 'INFO');
        $arregloDatos[mensaje] = "La actualizacion fue exitosa $arregloDatos[ndoc]";
        $arregloDatos[estilo] = "ui-state-highlight";
    }

    function auditoria($modulo, $evento, $descripcion, $tipo_error, $registrom) {

        $unLog = new CargueReferencias();
        $fecha = FECHAACTUALLINUX;

        $usuario = $_SESSION['usuario'];
        $descripcion = htmlspecialchars_decode($descripcion, ENT_QUOTES);


        $sql = "INSERT INTO gen_auditoria
					 (usuario,
					  fecha,
					  modulo,
					  evento,
					  descripcion,
					  tipo,
                                          registro
					  )
			  VALUES ('$usuario',
			          '$fecha',
					  '$modulo',
					  '$evento',
					  '$descripcion',
					  '$tipo_error',
                                          '$registrom'    
					 ) ";
        //echo $sql;

        $unLog->query($sql);

        if ($unLog->_lastError) {

            echo $unLog->_lastError->getMessage();
        }
    }

    function reporte($arregloDatos) {
        $sql = "select te.* 
                        from tes_CargueReferenciass te,tes_bancos
                        where te.cod_ban=tes_bancos.bancos";
        if (!empty($arregloDatos[desde])) {
            $sql.=" AND te.fec_ing >='$arregloDatos[desde]' AND te.fec_ing <'$arregloDatos[hasta]'";
        }
        if (!empty($arregloDatos[documento])) {
            $arregloDatos[documento] = trim($arregloDatos[documento]);
            $sql.=" AND te.num_doc like '%$arregloDatos[documento]%' ";
        }


        return $sql;
    }

    /*     * FUNCIONES PARA CARGAR BANCOS* */

    function listadoBancos() {

        if ($orden == NULL) {
            $orden = $nombre;
        }
        $sql = "	SELECT bancos, nombre
                        FROM tes_bancos WHERE bancos in ('1317','5012' ,'6103' ,'6113')  ORDER BY   nombre";

        $this->query($sql);
        if ($this->_lastError) {
            return FALSE;
        } else {
            $arreglo = array();
            $arreglo[1] = ucwords(strtolower('Pagos en linea'));
            $arreglo[2] = ucwords(strtolower('Documento Manual'));
            while ($this->fetch()) {
                $arreglo[$this->bancos] = ucwords(strtolower($this->nombre));
            }
        }

        return $arreglo;
    }
     function listadoPagos() {
        $sql="select 
               (cast(Convert(Char(10), Fecha,111) as varchar)+cast(tip_doc as varchar)+cast(num_doc as varchar)) as dato 
               from 
               tes_CargueReferenciass 
               where 
               Fecha between DATEADD(day, -5, getdate() ) and DATEADD(day, -1, getdate() )
               order by Fecha desc";
        $this->query($sql);
        if ($this->_lastError) {
            return FALSE;
        } else {
            $arreglo = array();
            while ($this->fetch()) {
                $arreglo[trim($this->dato)] = ucwords(strtolower(trim($this->dato)));
            }
        }

        return $arreglo;
    }

    function nuevodoc($fecha, $tip_doc, $num_doc, $val_doc, $tip_error, $des_error, $val_ext, $digito, $cod_ban, $tip_docnum_doc, $val_cons, $impuesto, $fec_proc, $descrip, $fec_ing, $ind_032) {
        $sql = "INSERT INTO tes_CargueReferenciass 
                        (
                        Fecha, 
                        tip_doc, 
                        num_doc, 
                        val_doc ,
                        Tip_error, 
                        des_error, 
                        val_ext, 
                        digito, 
                        cod_ban, 
                        num_ext, 
                        val_cons, 
                        impuesto, 
                        fec_proc, 
                        descrip, 
                        fec_ing, 
                        ind_032)

                        VALUES 
                        ('$fecha',
                         '$tip_doc',
                         '$num_doc',
                         $val_doc,
                         '$tip_error',
                         '$des_error',
                         $val_ext,
                         '$digito',
                         '$cod_ban',
                         '$tip_docnum_doc',
                         $val_cons,
                         $impuesto,
                         '$fec_proc',
                         '$descrip',
                         '$fec_ing',
                         $ind_032) ";
        $this->query($sql);
		//echo $sql;
        if ($this->_lastError) {
            echo "<div class=error>" . 'Error al crear el documento<BR>' . $sql . "<BR></div>";
            $this->_lastError = FALSE;
        }
        
        $sqlz = "INSERT INTO tes_CargueReferenciass 
                        (
                        Fecha, 
                        tip_doc, 
                        num_doc, 
                        val_doc ,
                        Tip_error, 
                        des_error, 
                        val_ext, 
                        digito, 
                        cod_ban, 
                        num_ext, 
                        val_cons, 
                        impuesto, 
                        fec_proc, 
                        descrip, 
                        fec_ing, 
                        ind_032)

                        VALUES 
                        ($fecha,
                         $tip_doc,
                         $num_doc,
                         $val_doc,
                         $tip_error,
                         $des_error,
                         $val_ext,
                         $digito,
                         $cod_ban,
                         $tip_docnum_doc,
                         $val_cons,
                         $impuesto,
                         $fec_proc,
                         $descrip,
                         $fec_ing,
                         $ind_032) ";
        
        $this->auditoria('CargueReferencias', "INSERT tes_CargueReferencias", $sqlz, 'INFO','Nuevo registro');
        return TRUE;
    }

    /*     * FIN* */
}

?>