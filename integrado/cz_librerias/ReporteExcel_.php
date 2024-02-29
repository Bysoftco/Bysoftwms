<?php
require_once("PEAR.php");
require_once('DB.php');
require_once('Spreadsheet/Excel/Writer.php');

 
/*
 * ReporteExcel
 *   
 **/
class ReporteExcel {
	
	var $sentencia;
	var $resultado;
	var $workbook;
	var $worksheet;
	var $rs;
	var $arrayFormatColumnName;
	var $arrayFormatNumber;
	var $arrayFormatTitle; 
	var $arrayFormatString;
	var $titulo;
	var $consulta;
	var $titulo_columna;
	function ReporteExcel($arregloCampos){
	
		$this->titulo_columna= $arregloCampos['titulo_columna'];
		$this->sentencia 	= $arregloCampos['sql'];
		$this->titulo = isset($arregloCampos['titulo'])?$arregloCampos['titulo']:'Reporte';
		$arregloDatos = array();
		$this->arrayFormatTitle = 	    array('Align' => 'left',
									   		  'Bold' => '1',
										      'Size' =>'11',
									   		  'Border' => '1',
											  'BorderColor' => 'black',
											  'FontFamily' => 'Verdana');
		$this->arrayFormatColumnName =  array('Align' => 'center',
									   		  'Bold' => '1',
									   		  'Border' => '1',
											  'BorderColor' => 'black',
											  'FontFamily' => 'Verdana');
		$this->arrayFormatNumber = 	    array('Align' => 'center',
								   		 	  'Border' => '1',
										      'BorderColor' => 'black',
										      'FontFamily' => 'Verdana');
		$this->arrayFormatString =      array('Align' => 'left',
								   		      'Border' => '1',
										      'BorderColor' => 'black',
										      'FontFamily' => 'Verdana');	
		$this->workbook = new Spreadsheet_Excel_Writer();	
		
		//$cadenaDeConexion ="mysql://root:bysoft@localhost/nbysoft_db";
		$cadenaDeConexion ="mysql://isamis_uwbysoft:pwbysoft@grupobysoft.com/isamis_wmsbysoft";
		//$cadenaDeConexion ="mysql://comapan_bysoft:byorden@localhost/comapan_receta";
		//$cadenaDeConexion ="mysql://bysoft_alconta:alcomexby@supremecenter12.co.uk/bysoft_alconta";
		if (DB::isError($this->rs =  DB::connect($cadenaDeConexion, FALSE))) {
	    	trigger_error($this->rs->getMessage(), E_USER_ERROR);
		}
		//echo 'test1';die();
	}   
		
	function generarExcel(){
		
		$this->workbook->setTempDir(TMP_DIR);
		$this->workbook->send('reporte.xls');
		$this->worksheet =& $this->workbook->addWorksheet('Hoja1');
		$formatTitle =& $this->workbook->addFormat($this->arrayFormatTitle);	
		$formatColumnName =& $this->workbook->addFormat($this->arrayFormatColumnName);	
		$formatNumber =& $this->workbook->addFormat($this->arrayFormatNumber);
		$formatString =& $this->workbook->addFormat($this->arrayFormatString);									  
		$this->worksheet->setRow(0,15);
		$this->worksheet->writeString(0, 0, $this->titulo, $formatTitle);
		set_time_limit (0); 
		$this->resultado = $this->rs->query($this->sentencia);	
		$stmt = $this->resultado->result;
		//$ncol = mysql_field_length($stmt);
		$ncol = mysql_num_fields($stmt);
		
		//Fijamos el tamaño para las columnas
		$this->worksheet->setColumn(0,$ncol,20);
        for ($i = 0; $i <= $ncol-1; $i++) {
           
			$column_name =mysql_field_name ( $stmt, $i);
			$column_type = mysql_field_type ($stmt, $i);
			
			$arregloDatos[$i]['column_name'] = $column_name;
			if(is_array($this->titulo_columna)){    // En caso que se envie un arreglo con los titulos que se quieren Mostrar
				foreach ($this->titulo_columna as $key => $value) {
					
					if($key==substr($column_name, 1 ,strlen($column_name) )){
						$column_name=$value;
					}
				}
			
			}
			$arregloDatos[$i]['column_type'] = $column_type;
			$this->worksheet->writeString(1, $i, $column_name, $formatColumnName);
        }
		$nrows = 1;
		
		while($registro  = $this->resultado->fetchRow(DB_FETCHMODE_ASSOC)){
			$nrows++;
			for ($i = 0; $i <= $ncol-1; $i++){
					//echo 'tipo'.$arregloDatos[$i]['column_type'];
					
					//$this->worksheet->writeString($nrows, $i-1, $registro[$arregloDatos[$i]['column_name']],$formatString);

				switch ($arregloDatos[$i]['column_type']) {
				  
                	case 'int':
                
						$this->worksheet->writeNumber($nrows, $i, $registro[$arregloDatos[$i]['column_name']],$formatNumber);
					break;
					case 'string':
                
						$this->worksheet->writeString($nrows, $i, $registro[$arregloDatos[$i]['column_name']],$formatString);
					break;
					case 'real':
                
						$this->worksheet->writeNumber($nrows, $i, $registro[$arregloDatos[$i]['column_name']],$formatNumber);
					break;
					case 'money':
                
						$this->worksheet->writeNumber($nrows, $i, $registro[$arregloDatos[$i]['column_name']],$formatNumber);
					break;
				
					case 'char': 
                	
						$this->worksheet->writeString($nrows, $i, $registro[$arregloDatos[$i]['column_name']],$formatString);
					break;
					default :
					$this->worksheet->writeString($nrows, $i, $registro[$arregloDatos[$i]['column_name']],$formatString);
					
				}//end switch		
			}//end for
		}//end file
		$this->workbook->close();
	}
	
} // end GeneracionExcel 
?>