<?php

/// la clase se debe llamar igual que el archivo
class ingreso extends fs_model
{
   public $codingreso;   
   public $codcliente;   
   public $nombrecliente;   
   public $fecha;   
   public $tipoingreso;   
   public $descripcion;   
   public $referencia;   
   public $total;   

   public function __construct($d = FALSE)
   {
      parent::__construct('ingreso'); /// aquí indicamos el NOMBRE DE LA TABLA
      if($d)
      {
         $this->codingreso= $this->insval($d['codingreso']);
         $this->codcliente= $d['codcliente'];
         $this->nombrecliente= $d['nombrecliente']; 
         $this->fecha= $d['fecha'];  
         $this->tipoingreso= $d['tipoingreso'];  
         $this->descripcion= $d['descripcion']; 
         $this->referencia= $d['referencia'];  
         $this->total= $d['total'];   
      }
      else
      {
         $this->codingreso= null;   
         $this->codcliente= null;   
         $this->nombrecliente= null;   
         $this->fecha= null;   
         $this->tipoingreso= null;   
         $this->descripcion= null;   
         $this->referencia= null;   
         $this->total= null;   
      }
   }

   public function install()
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
         $sql = "UPDATE ".$this->table_name." SET (descripcion = ".$this->descripcion.", referencia=".$this->referencia.")  WHERE codingreso = ".$this->var2str($this->codingreso).";";
      }
      else
      {
         $sql = "INSERT INTO ".$this->table_name." 
         (codcliente,nombrecliente,fecha,tipoingreso,descripcion,referencia,total) 
         VALUES ( ".$this->codcliente.",   
                  ".$this->nombrecliente.",  
                  ".$this->fecha.",  
                  ".$this->tipoingreso.",   
                  ".$this->descripcion.",  
                  ".$this->referencia.",  
                  ".$this->total.")";
      }
      
      return $this->db->exec($sql);
   }
   
   public function delete()
   {
      return $this->db->exec("DELETE FROM ".$this->table_name." WHERE codingreso = ".$this->var2str($this->codingreso).";");
   }
}
?>