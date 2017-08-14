<?php
/**
* 
*/

require_once 'plugins/facturacion_base/extras/fbase_controller.php';
require_model('forma_pago.php');
require_model('ingreso.php');
require_model('ingreso_detalle.php');
require_model('subcuenta.php');
require_model('asiento');
require_model('ejercicio.php');
require_model('divisa.php');


class contabilidad_ingreso extends fs_controller
{
	public $ingreso;
    public $forma_pago;
    public $listar;
    public $id;
    public $tipopago;
    public $status_nueclie;
    public $asiento;
    public $ejercicio;
    public $divisa;
    public $importe;
    public $numlineas;

	function __construct()
	{
		parent::__construct(__CLASS__, 'ingreso', 'contabilidad');
	}

	protected function private_core() {	

		$this->listar='si';

		$this->forma_pago = new forma_pago();

		$this->ingreso = new ingreso();

        $this->cliente = new cliente();

        $this->asiento = new asiento();

        $this->ejercicio = new ejercicio();

        $this->divisa = new divisa();

        $this->importe = 0;

        $this->numlineas = 0;

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
		 		$this->editar_ingreso();
		 	}elseif (isset($_POST['delete'])) {
                $this->eliminar_ingreso();
            } elseif ($this->status_nueclie<>'') {
                $this->nuevo_ingreso();
            } 
		}

        if (isset($_GET['subcuenta'])) {
            $this->buscar_subcuenta($_GET['subcuenta']);
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

    	if ($aux=$ing->save()) {
            //$this->new_message('Ingreso creado con exito');

            if($_POST['t_ingreso']=='D'){

                $cuenta = $_POST['cuenta'];
                $debe = $_POST['debe'];
                $haber = $_POST['haber'];


                $id=$this->db->select("SELECT max(codingreso) as codingreso,tipopago FROM ingreso GROUP BY tipopago;");
                

                $this->numlineas=count($cuenta);

                for ($i=0; $i < count($cuenta) ; $i++) { 

                    $monto = 0;
                    $tipo_ingresod= '';

                    if ($debe[$i]>0) {
                        $monto=$debe[$i];
                        $tipo_ingresod='D';
                    }else{
                        $monto=$haber[$i];
                        $tipo_ingresod='H';
                        $this->importe=$this->importe+$haber[$i];
                    }

                    $get_detalle = array(

                        'tipopago'      => $id[0]['tipopago'],
                        'monto'         => $monto,
                        'referencia'    => substr($cuenta[$i],'0','7'),
                        'tipo_ingresod' => $tipo_ingresod,
                        'codingreso'    => $id[0]['codingreso']

                    );

                    $detalle = new ingreso_detalle($get_detalle);

                    if ($aux=$detalle->save()) {
                        $this->new_message('Exito al crear el detalle del ingreso');
                        
                    }else{
                        $this->new_message('Error al crear el detalle del ingreso');
                    }
                }

                $this->nuevo_asiento();            

            } elseif ($_POST['t_ingreso']=='P') {

                foreach ($_POST['checpedido'] as $value) {
                   $this->new_error_msg($value);
                }

            } elseif ($_POST['t_ingreso']=='R') {
               


            }       

        }else{
            $this->new_error_msg('Error al crear el ingreso');

        }



        //header("Location: index.php?page=contabilidad_ingreso");
    }

    private function nuevo_asiento()
    {
        $continuar = TRUE;

        $eje0 = $this->get_ejercicio($_POST['fecha']);
        if (!$eje0) {
            $continuar = FALSE;
        }

        $div0 = $this->divisa->get('USD');
        if (!$div0) {
            $this->new_error_msg('Divisa no encontrada.');
            $continuar = FALSE;
        }

        if ($this->duplicated_petition($_POST['petition_id'])) {
            $this->new_error_msg('Petición duplicada. Has hecho doble clic sobre el botón Guardar
               y se han enviado dos peticiones.');
            $continuar = FALSE;
        }

        if ($continuar) {
            $this->asiento->codejercicio = $eje0->codejercicio;
            $this->asiento->idconcepto = NULL;
            $this->asiento->concepto = 'Ingreso';
            $this->asiento->fecha = $_POST['fecha'];
            $this->asiento->importe = $this->importe;

            $cuenta = $_POST['cuenta'];
            $debe = $_POST['debe'];
            $haber = $_POST['haber'];

            if ($this->asiento->save()) {
                $this->new_message('AQUI ESTOY');
                for ($i = 1; $i <=  $this->numlineas; $i++) {
                    if (isset($cuenta)) {
                        if ($cuenta != '' AND $continuar) {
                            $sub0 = $this->subcuenta->get_by_codigo($cuenta, $eje0->codejercicio);
                            if ($sub0) {
                                $partida = new partida();
                                $partida->idasiento = $this->asiento->idasiento;
                                $partida->coddivisa = $div0->coddivisa;
                                $partida->tasaconv = $div0->tasaconv;
                                $partida->idsubcuenta = $sub0->idsubcuenta;
                                $partida->codsubcuenta = $sub0->codsubcuenta;
                                $partida->debe = floatval($_POST['debe_' . $i]);
                                $partida->haber = floatval($_POST['haber_' . $i]);
                                $partida->idconcepto = $this->asiento->idconcepto;
                                $partida->concepto = $this->asiento->concepto;
                                $partida->documento = $this->asiento->documento;
                                $partida->tipodocumento = $this->asiento->tipodocumento;

                                if (isset($_POST['codcontrapartida_' . $i])) {
                                    if ($_POST['codcontrapartida_' . $i] != '') {
                                        $subc1 = $this->subcuenta->get_by_codigo($_POST['codcontrapartida_' . $i], $eje0->codejercicio);
                                        if ($subc1) {
                                            $partida->idcontrapartida = $subc1->idsubcuenta;
                                            $partida->codcontrapartida = $subc1->codsubcuenta;
                                            $partida->cifnif = $_POST['cifnif_' . $i];
                                            $partida->iva = floatval($_POST['iva_' . $i]);
                                            $partida->baseimponible = floatval($_POST['baseimp_' . $i]);
                                        } else {
                                            $this->new_error_msg('Subcuenta ' . $_POST['codcontrapartida_' . $i] . ' no encontrada.');
                                            $continuar = FALSE;
                                        }
                                    }
                                }

                                if (!$partida->save()) {
                                    $this->new_error_msg('Imposible guardar la partida de la subcuenta ' . $_POST['codsubcuenta_' . $i] . '.');
                                    $continuar = FALSE;
                                }
                            } else {
                                $this->new_error_msg('Subcuenta ' . $_POST['codsubcuenta_' . $i] . ' no encontrada.');
                                $continuar = FALSE;
                            }
                        }
                    }
                }

                if ($continuar) {
                    $this->asiento->concepto = '';

                    $this->new_message("<a href='" . $this->asiento->url() . "'>Asiento</a> guardado correctamente!");
                    $this->new_change('Asiento ' . $this->asiento->numero, $this->asiento->url(), TRUE);

                    if ($_POST['redir'] == 'TRUE') {
                        header('Location: ' . $this->asiento->url());
                    }
                } else {
                    if ($this->asiento->delete()) {
                        $this->new_error_msg("¡Error en alguna de las partidas! Se ha borrado el asiento.");
                    } else
                        $this->new_error_msg("¡Error en alguna de las partidas! Además ha sido imposible borrar el asiento.");
                }
            }
            else {
                $this->new_error_msg("¡Imposible guardar el asiento!");
            }
        }
    }

    private function get_ejercicio($fecha)
    {
        $ejercicio = FALSE;

        $ejercicio = $this->ejercicio->get_by_fecha($fecha);
        if ($ejercicio) {
            $regiva0 = new regularizacion_iva();
            if ($regiva0->get_fecha_inside($fecha)) {
                $this->new_error_msg('No se puede usar la fecha ' . $_POST['fecha'] . ' porque ya hay'
                    . ' una regularización de ' . FS_IVA . ' para ese periodo.');
                $ejercicio = FALSE;
            }
        } else {
            $this->new_error_msg('Ejercicio no encontrado.');
        }

        return $ejercicio;
    }

    public function editar_ingreso(){

        $get_datos = array(
                      'codingreso'    => $_POST['codingreso'],
                      'fecha'         => $_POST['fecha'],
                      'descripcion'   => $_POST['descripcion'],
                      'tipopago'      => $_POST['t_pago'],
                      'referencia'    => $_POST['referencia']);

        $ing= new ingreso($get_datos);

        if ($aux=$ing->save()) {
            $this->new_message('Ingreso editado con exito');
        }else{
            $this->new_error_msg('Error al editar el ingreso');
        }
        header("Location: index.php?page=contabilidad_ingreso");
    }

    public function eliminar_ingreso(){

        $get_datos = array('codingreso'    => $_POST['delete']);

        $ing= new ingreso($get_datos);

         if ($aux=$ing->delete()) {
            $this->new_message('Ingreso eliminado con exito');
        }else{
            $this->new_error_msg('Error al eliminar el ingreso');
        }
        header("Location: index.php?page=contabilidad_ingreso");
    }

    public function consulta_id(){
    	$this->listar='no';
    	$row = $this->db->select("SELECT codingreso,codcliente,nombrecliente,DATE_FORMAT(fecha,'%d-%m-%x') as fecha,tipoingreso,descripcion,tipopago,referencia,total FROM ingreso WHERE codingreso =".$this->id." ");
    	$this->tipopago=$row[0]['tipopago'];
    	return $row;
    }

    private function buscar_subcuenta($aux)
    {
        /// desactivamos la plantilla HTML
        $this->template = FALSE;

        $subcuenta = new subcuenta();
        $eje0 = new ejercicio();
        $ejercicio = $eje0->get_by_fecha($this->today());
        $json = array();
        foreach ($subcuenta->search_by_ejercicio($ejercicio->codejercicio, $aux) as $subc) {
            $json[] = array(
                'value' => $subc->codsubcuenta.' - '.$subc->descripcion,
                'data' => $subc->codsubcuenta,
                'saldo' => $subc->saldo,
                'link' => $subc->url()
            );
        }

        header('Content-Type: application/json');
        echo json_encode(array('query' => $aux, 'suggestions' => $json));
    }
  
}

?>