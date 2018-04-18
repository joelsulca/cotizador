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

    public function get_anio_fabricacion($modelo)
    {
        $query = $this->db
            ->where(array('modelo_id'=>$modelo))
            ->order_by('anio_fabricacion_id', 'desc')
            ->get('modelo_anio_fabricacion');
        return $query->result_array();
    }

    public function get_modelos($marca)
    {
        $query = $this->db->where(array('marca_id'=>$marca))->get('modelo');

        return $query->result_array();
    }

    public function save_cotizacion($data){
        $contacto = array(
            'vehiculo_marca_id' => $data['vehiculo_marca'] ,
            'vehiculo_modelo_id' => $data['vehiculo_modelo'] ,
            'vehiculo_anio_fabricacion_id' => $data['vehiculo_annio'] ,
            'vehiculo_valor_comercial' => $data['vehiculo_valor'] ,
            'conductor_nombres' => $data['conductor_nombres'] ,
            'conductor_apellidos' => $data['conductor_apellidos'] ,
            'conductor_dni' => $data['conductor_dni'] ,
            'conductor_sexo' => $data['conductor_sexo'] ,
            'conductor_correo' => $data['conductor_correo'] ,
            'conductor_celular' => $data['conductor_celular'] ,
            'conductor_fec_nac' => $data['conductor_fec_nac'] ,
        );
        $action = $this->db->insert('cotizacion_contacto', $contacto);
        return $action ? $this->db->insert_id() : null;
    }

    public function get_cotizacion($id)
    {
        $query = $this->db->where(array('id'=>$id))->get('cotizacion_contacto');

        return $query->row_object();
    }
}