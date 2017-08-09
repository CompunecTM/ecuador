<?php

/// la clase se debe llamar igual que el archivo
class ingreso extends fs_model
{
   private $codingreso;   
   private $codcliente;   
   private $nombrecliente;   
   private $fecha;   
   private $tipoingreso;   
   private $descripcion; 
   private $tipopago;  
   private $referencia;   
   private $total;   

   public function __construct($set_datos = FALSE)
   {
      parent::__construct('ingreso'); /// aquí indicamos el NOMBRE DE LA TABLA
      if($set_datos)
      {
         if(isset($set_datos['codingreso'])){
             $this->codingreso=$set_datos['codingreso'] ;
         }
         if (isset($set_datos['codcliente'])) {
           $this->codcliente= $set_datos['codcliente'];
         }
         if (isset($set_datos['nombrecliente'])) {
            $this->nombrecliente= $set_datos['nombrecliente']; 
         }
         if (isset($set_datos['nombrecliente'])) {
           $this->nombrecliente= $set_datos['nombrecliente'];
         }
         if (isset($set_datos['nombrecliente'])) {
            $this->nombrecliente= $set_datos['nombrecliente']; 
         }
         if (isset($set_datos['nombrecliente'])) {
            $this->nombrecliente= $set_datos['nombrecliente']; 
         }
         if (isset($set_datos['fecha'])) {
            $this->fecha= Date('d-m-Y', strtotime($set_datos['fecha'])); 
         }
         if (isset($set_datos['tipoingreso'])) {
            $this->tipoingreso= $set_datos['tipoingreso'];
         }
         if (isset($set_datos['descripcion'])) {
            $this->descripcion= $set_datos['descripcion']; 
         }
         if (isset($set_datos['tipopago'])) {
            $this->tipopago= $set_datos['tipopago'];
         }
         if (isset($set_datos['referencia'])) {
            $this->referencia= $set_datos['referencia'];  
         }
         if (isset($set_datos['total'] )) {
           $this->total= $set_datos['total'];
         }        
           
      }
      else
      {  
         $this->codcliente= null;   
         $this->nombrecliente= null;   
         $this->fecha= Date('d-m-Y');   
         $this->tipoingreso= null;   
         $this->descripcion= null; 
         $this->tipopago= null;  
         $this->referencia= null;   
         $this->total= null;   
      }

   }

   protected function install()
   {
      return '';
   }
   
   public function exists()
   {
      if( is_null($this->codingreso) )
      {
         return FALSE;
      }
      else
      {
         return $this->db->select("SELECT * FROM ".$this->table_name." WHERE codingreso = ".$this->var2str($this->codingreso).";");
      }
   }

   public function save()
   {
      if( $this->exists() )
      {
         $sql = "UPDATE ".$this->table_name." SET fecha = ".$this->var2str($this->fecha).", descripcion = ".$this->var2str($this->descripcion).", tipopago=".$this->var2str($this->tipopago).", referencia=".$this->var2str($this->referencia)."  WHERE codingreso = ".$this->var2str($this->codingreso).";";
         
      }
      else
      {
         $sql = "INSERT INTO ".$this->table_name." 
         (codcliente,nombrecliente,fecha,tipoingreso,descripcion,tipopago,referencia,total) 
         VALUES ( ".$this->var2str($this->codcliente).",   
                  ".$this->var2str($this->nombrecliente).",  
                  ".$this->var2str($this->fecha).",  
                  ".$this->var2str($this->tipoingreso).",   
                  ".$this->var2str($this->descripcion).",  
                  ".$this->var2str($this->tipopago).",
                  ".$this->var2str($this->referencia).",  
                  ".$this->var2str($this->total).")";
      }
      
      return $this->db->exec($sql);
   }
   
   public function delete()
   {
      return $this->db->exec("DELETE FROM ".$this->table_name." WHERE codingreso = ".$this->var2str($this->codingreso).";");
   }

   public function all(){
      return $this->db->select("SELECT * FROM ".$this->table_name." ORDER BY fecha desc");
   }

   public function search($query, $offset = 0)
   {
        $query = mb_strtolower($this->no_html($query), 'UTF8');

        $consulta = "SELECT concat_ws(' - ',codsubcuenta,descripcion) as subcuenta FROM " . $this->table_name . " WHERE  co_subcuentas where concat_ws(' - ',codsubcuenta,descripcion) like '%".$query."%' ";

        $data = $this->db->select_limit($consulta, FS_ITEM_LIMIT, $offset);
        return $this->all_from_data($data);
   }

    protected function buscar_subcuenta($query) {
        /// desactivamos la plantilla HTML
        $this->template = FALSE;

        $json = array();
        foreach ($cli->search($query) as $cli) {
            $nombre = $cli->nombre;
            if ($cli->nombre != $cli->razonsocial) {
                $nombre .= ' (' . $cli->razonsocial . ')';
            }

            $json[] = array('value' => $cli->nombre, 'data' => $cli->codcliente, 'full' => $cli);
        }

        header('Content-Type: application/json');
        echo json_encode(array('query' => $query, 'suggestions' => $json));
    }
}
?>