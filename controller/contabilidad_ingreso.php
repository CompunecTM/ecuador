<?php
/**
* 
*/

require_once 'plugins/facturacion_base/extras/fbase_controller.php';
require_model('forma_pago.php');

require_model('ingreso.php');

class contabilidad_ingreso extends fs_controller
{
	public $ingreso;
    public $forma_pago;
    public $listar;
    public $id;
    public $tipopago;
    public $status_nueclie;

	function __construct()
	{
		parent::__construct(__CLASS__, 'ingreso', 'contabilidad');
	}

	protected function private_core() {	

		$this->listar='si';

		$this->forma_pago = new forma_pago();

		$this->ingreso = new ingreso();

        $this->cliente = new cliente();

         /**
         * Nuevo cliente
         */
        if (isset($_POST['nuevo_cliente'])) {
            if ($_POST['nuevo_cliente'] != '') {
                $this->cliente_s = FALSE;
                if ($_POST['nuevo_cifnif'] != '') {
                    $this->cliente_s = $this->cliente->get_by_cifnif($_POST['nuevo_cifnif']);
                    if ($this->cliente_s) {
                        $this->new_advice('Ya existe un cliente con ese ' . FS_CIFNIF . '. Se ha seleccionado.');
                    }
                }

                if (!$this->cliente_s) {
                    $this->cliente_s = new cliente();
                    $this->cliente_s->codcliente = $this->cliente_s->get_new_codigo();
                    $this->cliente_s->nombre = $this->cliente_s->razonsocial = $_POST['nuevo_cliente'];
                    $this->cliente_s->tipoidfiscal = $_POST['nuevo_tipoidfiscal'];
                    $this->cliente_s->cifnif = $_POST['nuevo_cifnif'];
                    $this->cliente_s->personafisica = isset($_POST['personafisica']);

                    if (isset($_POST['nuevo_email'])) {
                        $this->cliente_s->email = $_POST['nuevo_email'];
                    }

                    if (isset($_POST['codgrupo'])) {
                        if ($_POST['codgrupo'] != '') {
                            $this->cliente_s->codgrupo = $_POST['codgrupo'];
                        }
                    }

                    if (isset($_POST['nuevo_telefono1'])) {
                        $this->cliente_s->telefono1 = $_POST['nuevo_telefono1'];
                    }

                    if (isset($_POST['nuevo_telefono2'])) {
                        $this->cliente_s->telefono2 = $_POST['nuevo_telefono2'];
                    }

                    if ($this->cliente_s->save()) {
                        if ($this->empresa->contintegrada) {
                            /// forzamos crear la subcuenta
                            $this->cliente_s->get_subcuenta($this->empresa->codejercicio);
                        }

                        $dircliente = new direccion_cliente();
                        $dircliente->codcliente = $this->cliente_s->codcliente;
                        $dircliente->codpais = $this->empresa->codpais;
                        $dircliente->provincia = $this->empresa->provincia;
                        $dircliente->ciudad = $this->empresa->ciudad;

                        if (isset($_POST['nuevo_pais'])) {
                            $dircliente->codpais = $_POST['nuevo_pais'];
                        }

                        if (isset($_POST['nuevo_provincia'])) {
                            $dircliente->provincia = $_POST['nuevo_provincia'];
                        }

                        if (isset($_POST['nuevo_ciudad'])) {
                            $dircliente->ciudad = $_POST['nuevo_ciudad'];
                        }

                        if (isset($_POST['nuevo_codpostal'])) {
                            $dircliente->codpostal = $_POST['nuevo_codpostal'];
                        }

                        if (isset($_POST['nuevo_direccion'])) {
                            $dircliente->direccion = $_POST['nuevo_direccion'];
                        }

                        if ($dircliente->save()) {
                            $this->new_message('Cliente agregado correctamente.');
                            $this->status_nueclie=$this->cliente_s->codcliente;
                        }

                    } else
                        $this->new_error_msg("¡Imposible guardar la dirección del cliente!");
                }
            }
        }

		if(isset($_GET['id'])){
			$this->id=$_GET['id'];
			$this->consulta_id();
		}

		 if (isset($_GET['tipo'])) {
		 	if ($_GET['tipo']=='ingreso'){$this->nuevo_ingreso();}
		 	elseif ($_GET['tipo']=='crear_ingreso') {	
		 		$this->crear_ingreso();
		 	}elseif ($_GET['tipo']=='editar_ingreso') {
		 		$this->new_message('AQUI ESTOY');
		 	}elseif ($this->status_nueclie<>'') {
                $this->nuevo_ingreso();
            }
		 }
    }

	public function listar_pedido() {  

		if (isset($_POST['cliente'])) {  
			$this->listar='no';  
        	return $this->db->select("SELECT * FROM pedidoscli WHERE idalbaran IS NULL AND status = 0 AND codcliente=".$_POST['cliente'].";");
        }else{
        	return false;
        }
    }

    public function nuevo_ingreso(){
    	$this->listar='no';
        if ($this->status_nueclie <> '') {
            return $this->db->select("SELECT * FROM clientes WHERE codcliente =".$this->status_nueclie." ");
        }else{
        	if (isset($_POST['cliente'])) {
        		return $this->db->select("SELECT * FROM clientes WHERE codcliente =".$_POST['cliente']." ");
        	}else{
        		return false;
        	}
        }
    	
    }

    public function crear_ingreso(){

    	$get_datos = array(
    				  'codcliente' 	  => $_POST['codcliente'],
    	 			  'nombrecliente' => $_POST['cliente'],
    	 			  'fecha'		  => $_POST['fecha'],
    	 			  'tipoingreso'   => $_POST['t_ingreso'],
    	 			  'descripcion'   => $_POST['descripcion'],
    	 			  'tipopago'   	  => $_POST['t_pago'],
    	 			  'referencia'    => $_POST['referencia'],
    	 			  'total'         => $_POST['total']);

    	$ing= new ingreso($get_datos);

    	$aux=$ing->save();
    }

    public function editar_ingreso(){

        $get_datos = array(
                      'codcliente'    => $_POST['codcliente'],
                      'fecha'         => $_POST['fecha'],
                      'descripcion'   => $_POST['descripcion'],
                      'tipopago'      => $_POST['t_pago'],
                      'referencia'    => $_POST['referencia']);

        $ing= new ingreso($get_datos);

        $aux=$ing->save();
    }

    public function consulta_id(){
    	$this->listar='no';
    	$row = $this->db->select("SELECT codingreso,codcliente,nombrecliente,DATE_FORMAT(fecha,'%d-%m-%x') as fecha,tipoingreso,descripcion,tipopago,referencia,total FROM ingreso WHERE codingreso =".$this->id." ");
    	$this->tipopago=$row[0]['tipopago'];
    	return $row;
    }

  
}

?>