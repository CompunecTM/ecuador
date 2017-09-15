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

       if (isset($_POST['cedrud'])) {
         $this->template = FALSE;

         $validador = new ValidarIdentificacion();

         $cedrud = trim($_POST['cedrud']);

         if ($_POST['perfisica'] == 'true') {
            // validar CI
            $data['men'] =$_POST['perfisica'].' '.$_POST['tipfiscal'];
            if ($_POST['tipfiscal'] == 'Cedula') {
               if ($validador->validarCedula($cedrud)) {
                  $data['mensaje'] = 'Cédula válida';
                  $data['estatus'] = '1';
               } else {
                  $data['mensaje'] = 'Cédula incorrecta: '.$validador->getError();
                  $data['estatus'] = '0';
               }
            }elseif ($_POST['tipfiscal'] == 'R.U.C') {
               //validar RUC persona natural
               if ($validador->validarRucPersonaNatural($cedrud)) {
                  $data['mensaje'] = 'RUC válido';
                  $data['estatus'] = '1';
               } else {
                  $data['mensaje'] ='RUC incorrecto: '.$validador->getError();
                  $data['estatus'] = '0';
               }
            }
           
         }else{

            if ($_POST['pribpubli'] == 'true') {
               // validar RUC sociedad privada
               if ($validador->validarRucSociedadPrivada($cedrud)) {
                  $data['mensaje'] = 'RUC privado válido';
                  $data['estatus'] = '1';
               } else {
                  $data['mensaje'] ='RUC privado incorrecto: '.$validador->getError();
                  $data['estatus'] = '0';
               }
            }else{
               // validar RUC sociedad publica
               if ($validador->validarRucSociedadPublica($cedrud)) {
                  $data['mensaje'] = 'RUC publico válido';
                  $data['estatus'] = '1';
               } else {
                  $data['mensaje'] ='RUC publico incorrecto: '.$validador->getError();
                  $data['estatus'] = '0';
               }
            }
         }
         echo json_encode($data);
      }

   }

   private function share_extenstion()
   {	

   	$text = "<script type='text/javascript' src='plugins/ecuador/view/js/validacioncedrub.js'></script>";

   	  /// Validacion de cedula, rud a la pantalla del listado de clientes
      $fsext1 = new fs_extension();
      $fsext1->name = 'validacioncedrub1';
      $fsext1->from = __CLASS__;
      $fsext1->to = 'ventas_clientes';
      $fsext1->type = 'head';
      $fsext1->text = $text;
      $fsext1->save();

        /// Validacion de cedula, rud a la pantalla cliente al editar datos del mismo
      $fsext2 = new fs_extension();
      $fsext2->name = 'validacioncedrub2';
      $fsext2->from = __CLASS__;
      $fsext2->to = 'ventas_cliente';
      $fsext2->type = 'head';
      $fsext2->text = $text;
      $fsext2->save();

      /// Validacion de cedula, rud a la pantalla cliente al editar datos del mismo
      $fsext3 = new fs_extension();
      $fsext3->name = 'validacioncedrub3';
      $fsext3->from = __CLASS__;
      $fsext3->to = 'nueva_venta';
      $fsext3->type = 'head';
      $fsext3->text = $text;
      $fsext3->save();

      /// Validacion de cedula, rud a la pantalla cliente al editar datos del mismo
      $fsext4 = new fs_extension();
      $fsext4->name = 'validacioncedrub4';
      $fsext4->from = __CLASS__;
      $fsext4->to = 'nuevo_servicio';
      $fsext4->type = 'head';
      $fsext4->text = $text;
      $fsext4->save();

      /// Validacion de cedula, rud a la pantalla cliente al editar datos del mismo
      $fsext5 = new fs_extension();
      $fsext5->name = 'validacioncedrub5';
      $fsext5->from = __CLASS__;
      $fsext5->to = 'nueva_compra';
      $fsext5->type = 'head';
      $fsext5->text = $text;
      $fsext5->save();

      /// Validacion de cedula, rud a la pantalla cliente al editar datos del mismo
      $fsext6 = new fs_extension();
      $fsext6->name = 'validacioncedrub6';
      $fsext6->from = __CLASS__;
      $fsext6->to = 'compras_proveedores';
      $fsext6->type = 'head';
      $fsext6->text = $text;
      $fsext6->save();

      /// Validacion de cedula, rud a la pantalla cliente al editar datos del mismo
      $fsext7 = new fs_extension();
      $fsext7->name = 'validacioncedrub7';
      $fsext7->from = __CLASS__;
      $fsext7->to = 'compras_proveedor';
      $fsext7->type = 'head';
      $fsext7->text = $text;
      $fsext7->save();
   }

}