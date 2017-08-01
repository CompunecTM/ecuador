<?php
/**
* 
*/

class contabilidad_ingreso extends fs_controller
{

	function __construct()
	{
		parent::__construct(__CLASS__, 'ingreso', 'contabilidad');
	}

	protected function private_core() {
		 if (isset($_POST['tipo'])) {
		 	if ($_POST['tipo']=='ingreso'){$this->nuevo_ingreso();}
		 	elseif ($_POST['tipo']=='crear_ingreso') {
		 		$this->crear_ingreso();
		 	}
		 }
    }

	public function listar_pedido() {  

		if (isset($_POST['cliente'])) {    
        	return $this->db->select("SELECT * FROM pedidoscli WHERE idalbaran IS NULL AND status = 0 AND codcliente=".$_POST['cliente'].";");
        }else{
        	return false;
        }
    }

    public function nuevo_ingreso(){

    	if (isset($_POST['cliente'])) {
    		return $this->db->select("SELECT * FROM clientes WHERE codcliente =".$_POST['cliente']." ");
    	}else{
    		return false;
    	}
    	
    }

    public function crear_ingreso(){

    }



  
}

?>