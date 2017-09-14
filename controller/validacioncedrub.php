<?php

require('plugins/ecuador/view/js/validacion-cedula-ruc-ecuador-master/validadores/php/validaridentificacion.php');

class validacioncedrub extends fs_controller
{

   public function __construct()
   {
      parent::__construct(__CLASS__, 'validacioncedrub', 'ventas',FALSE,FALSE);
   }
   
   protected function private_core()
   {
      $this->share_extenstion();

      if ($_POST['cedrud']) {
         $this->template = FALSE;

         $validador = new ValidarIdentificacion();

         $cedrud = trim($_POST['cedrud']);

         $data['tipfiscal'] = $_POST['tipfiscal'];

         $data['perfisica'] = $_POST['perfisica'];

         // validar CI
         if ($validador->validarCedula($cedrud)) {
            $data['mensaje'] = 'Cédula válida';
            $data['estatus'] = '1';
         } else {
            $data['mensaje'] = 'Cédula incorrecta: '.$validador->getError();
            $data['estatus'] = '0';
         }

         /* validar RUC persona natural
         if ($validador->validarRucPersonaNatural('0926687856001')) {
             echo 'RUC válido';
         } else {
             echo 'RUC incorrecto: '.$validador->getMessage();
         } 1725030306

         // validar RUC sociedad privada
         if ($validador->validarRucSociedadPrivada('0992397535001')) {
             echo 'RUC válido';
         } else {
             echo 'RUC incorrecto: '.$validador->getMessage();
         }

         // validar RUC sociedad ublica
         if ($validador->validarRucSociedadPublica('1760001550001')) {
             echo 'RUC válido';
         } else {
             echo 'RUC incorrecto: '.$validador->getMessage();
         }*/

          
         echo json_encode($data);
      }

   }

   private function share_extenstion()
   {	

   	$text = "<script type='text/javascript' src='plugins/ecuador/view/js/validacioncedrub.js'></script>";

   	  /// metemos la pestaña de Numero de Autorizacion por Javascript
      $fsext1 = new fs_extension();
      $fsext1->name = 'validacioncedrub';
      $fsext1->from = __CLASS__;
      $fsext1->to = 'ventas_clientes';
      $fsext1->type = 'head';
      $fsext1->text = $text;
      $fsext1->save();
   }

}