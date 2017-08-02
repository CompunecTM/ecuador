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

	function __construct()
	{
		parent::__construct(__CLASS__, 'ingreso', 'contabilidad');
	}

	protected function private_core() {	

		$this->listar='si';

		$this->forma_pago = new forma_pago();

		$this->ingreso = new ingreso();

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
    	if (isset($_POST['cliente'])) {
    		return $this->db->select("SELECT * FROM clientes WHERE codcliente =".$_POST['cliente']." ");
    	}else{
    		return false;
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

    public function consulta_id(){
    	$this->listar='no';
    	$row = $this->db->select("SELECT codingreso,codcliente,nombrecliente,DATE_FORMAT(fecha,'%d-%m-%x') as fecha,tipoingreso,descripcion,tipopago,referencia,total FROM ingreso WHERE codingreso =".$this->id." ");
    	$this->tipopago=$row[0]['tipopago'];
    	return $row;
    }

  
}

?>