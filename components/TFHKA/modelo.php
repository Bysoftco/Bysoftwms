<?php 
// declaramos las clases con los modelos de datos
// declaramos extra
class Extras {
    public $controlInterno1;
    public $controlInterno2;
    public $nombre;
    public $pdf;
    public $valor;
    public $xml;
   }
   // declaramos factura
   class FacturaGeneral
   {
      public $cliente;	
      public $consecutivoDocumento;
      public $consecutivoDocumentoModificado;
      public $detalleDeFactura = array();//es un array de tipo FacturaDetalle 
      public $estadoPago;
      public $extras = array(); 
      public $fechaEmision;
      public $fechaEmisionDocumentoModificado;
      public $fechaVencimiento;
      public $icoterms;
      public $importeTotal;
      public $impuestosGenerales = array();// almacenar todos los tipos de impuestos que estaran en la clase FacturaImpuestos
      public $informacionAdicional;
      public $medioPago;
      public $moneda;
      public $motivoNota;
      public $propina;
      public $rangoNumeracion;
      public $tipoDocumento;
      public $totalDescuentos;
      public $totalSinImpuestos;
      public $uuidDocumentoModificado;
      
      public function __construct(){
        $this->cliente = new Cliente();	
      }
   }
    
   // declaramos la clase 
   class Cliente
   {
       public $apellido;
       public $ciudad;
       public $departamento;
       public $direccion;
       public $email;
       public $nombreRazonSocial;
       public $notificar;
       public $numeroDocumento;
       public $pais;
       public $referencia2;
       public $regimen;
       public $segundoNombre;
       public $subDivision;
       public $telefono;
       public $tipoIdentificacion;
       public $tipoPersona;
   }
    
   class FacturaDetalle 
   {
       public $cantidadUnidades;
       public $codigoProducto;
       public $descripcion;
       public $descuento;
       public $detalleAdicionalNombre;
       public $detalleAdicionalValor;
       public $impuestosDetalles = array(); // arreglo de facturaImpuesto
       public $precioTotal;
       public $precioTotalSinImpuestos;
       public $precioVentaUnitario;
       public $seriales;
       public $unidadMedida;
   }
   
   
   class FacturaImpuestos
   {
      public $baseImponibleTOTALImp;
      public $codigoTOTALImp;
      public $controlInterno;
      public $porcentajeTOTALImp;
      public $valorTOTALImp;
   }

   class uploadAttachment {
       public $archivo;
       public $numeroDocumento;
       public $nombre;
       public $formato;
       public $tokenEmpresa;
       public $tokenPassword;
       public $tipo;
       public $enviar;

   }