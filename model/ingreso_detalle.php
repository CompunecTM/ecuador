<?php

/// la clase se debe llamar igual que el archivo
class ingreso_detalle extends fs_model
{
   private $codingreso_detalle;   
   private $tipopago;   
   private $monto;   
   private $referencia;   
   private $tipo_ingresod;
   private $codingreso; 

   public function __construct($set_datos = FALSE)
   {
      parent::__construct('ingreso_detalle'); /// aquí indicamos el NOMBRE DE LA TABLA
      if($set_datos)
      {
         if(isset($set_datos['codingreso_detalle'])){
             $this->codingreso_detalle=$set_datos['codingreso_detalle'] ;
         }
         if (isset($set_datos['tipopago'])) {
           $this->tipopago= $set_datos['tipopago'];
         }
         if (isset($set_datos['monto'])) {
            $this->monto= $set_datos['monto']; 
         }
         if (isset($set_datos['referencia'])) {
           $this->referencia= $set_datos['referencia'];
         }
         if (isset($set_datos['tipo_ingresod'])) {
            $this->tipo_ingresod= $set_datos['tipo_ingresod']; 
         }
         if (isset($set_datos['codingreso'])) {
            $this->codingreso= $set_datos['codingreso']; 
         }           
      }
      else
      {  
        $this->$codingreso_detalle=null;   
        $this->$tipopago=null;    
        $this->$monto=null;    
        $this->$referencia=null;   
        $this->$tipo_ingresod=null; 
        $this->$codingreso=null; 
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
         return $this->db->select("SELECT * FROM ".$this->table_name." WHERE codingreso_detalle = ".$this->var2str($this->codingreso_detalle).";");
      }
   }

   public function save()
   {
     
     $sql = "INSERT INTO ".$this->table_name." 
     (tipopago,monto,referencia,tipo_ingresod,codingreso) 
     VALUES ( ".$this->var2str($this->tipopago).",  
              ".$this->var2str($this->monto).",  
              ".$this->var2str($this->referencia).",   
              ".$this->var2str($this->tipo_ingresod).",  
              ".$this->var2str($this->codingreso).")";
      
      $this->new_message($sql);
      
      return $this->db->exec($sql);
   }
   
   public function delete()
   {
      return $this->db->exec("DELETE FROM ".$this->table_name." WHERE codingreso = ".$this->var2str($this->codingreso).";");
   }

   public function all(){
      return $this->db->select("SELECT * FROM ".$this->table_name." ORDER BY fecha desc");
   }
}
?>