<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 17/04/18
 * Time: 1:34
 */
class Cotizador_Model extends CI_Model
{
    public function get_marcas()
    {
        $query = $this->db->get('marca');
        return $query->result();
    }

    public function get_anio_fabricacion()
    {
        $query = $this->db->get('anio_fabricacion');
        return $query->result();
    }

    public function get_modelos($marca)
    {
        $query = $this->db->where(array('marca_id'=>$marca))->get('modelo');

        return $query->result_array();
    }
}