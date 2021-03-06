<?php

/*
* This file is part of FacturaSctipts
* Copyright (C) 2017 Carlos Yánez corp.compunec@gmail.com
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Lesser General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU Lesser General Public License for more details.
* 
* You should have received a copy of the GNU Lesser General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

require_once 'plugins/facturacion_base/model/core/factura_cliente.php';



class factura_cliente extends  FacturaScripts\model\factura_cliente
{

    public function new_codigo()
    {

    if (FS_NEW_CODIGO == 'eneboo') {

        /// buscamos el numero inicial para la serie
        $num = 1;
        $serie0 = new \serie();
        $serie = $serie0->get($this->codserie);
        if($serie)
        {

            $num = $serie->numfactura;

        }

        /// buscamos un hueco o el siguiente numero disponible
        $encontrado = FALSE;
        $fecha = $this->fecha;
        $hora = $this->hora;
        $sql = "SELECT ".$this->db->sql_to_int('numero')." as numero,fecha,hora FROM ".$this->table_name
            ." WHERE codserie = ".$this->var2str($this->codserie)
            ." ORDER BY numero ASC;";

        $data = $this->db->select($sql);
        if($data)
        {
            foreach($data as $d)
            {
                if( intval($d['numero']) < $num )
                {
                    /**
                     * El numero de la factura es menor que el inicial.
                     * El usuario ha cambiado el numero inicial despuÃ©s de hacer
                     * facturas.
                     */
                }
                else if( intval($d['numero']) == $num )
                {
                    /// el numero es correcto, avanzamos
                    $num++;
                }
                else
                {
                    /// Hemos encontrado un hueco y debemos usar el nÃºmero y la fecha.
                    $encontrado = TRUE;
                    $fecha = Date('d-m-Y', strtotime($d['fecha']));
                    $hora = Date('H:i:s', strtotime($d['hora']));
                    break;
                }
            }
        }
        if($encontrado)
        {
            $this->numero = $num;
            $this->fecha = $fecha;
            $this->hora = $hora;
        }
        else
        {
            $this->numero = $num;
        }

        $this->codigo = $this->codserie.sprintf('%07s', $this->numero);

        } 
        else 
        {       
            return parent::new_codigo();
        } 
    }  
}