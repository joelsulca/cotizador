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

    public function get_anio_fabricacion($marca, $modelo)
    {
        $query = $this->db
            ->where(array('modelo_id'=>$modelo,'marca_id'=>$marca))
            ->order_by('anio_fabricacion', 'desc')
            ->get('auto');
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
        $query = $this->db
            ->query("select cc.vehiculo_modelo_id,cc.vehiculo_marca_id, cc.id, cc.conductor_apellidos, cc.conductor_celular, cc.conductor_correo, cc.conductor_dni,
cc.conductor_nombres, cc.conductor_sexo, cc.conductor_fec_nac, cc.fecha_creacion, cc.vehiculo_anio_fabricacion_id, cc.vehiculo_anio_fabricacion_id as anio,
cc.vehiculo_valor_comercial, mm.nombre as marca, m.nombre as modelo
from cotizacion_contacto cc inner join modelo m 
on m.id = cc.vehiculo_modelo_id and m.marca_id = cc.vehiculo_marca_id
inner join marca mm 
on mm.id = cc.vehiculo_marca_id where cc.id = $id");

        return $query->row_object();
    }

    public function get_cotizacion_info_by_auto($marca, $modelo, $anio)
    {
        $query = $this->db
            ->query("select e.nombre, ec.gps, ec.tasa, e.id, e.logo
                from empresa_auto_info_cotizacion ec 
                inner join empresa e
                on e.id = ec.empresa_id 
                where e.activo=1 and ec.vehiculo_anio_fabricacion_id=$anio 
                and ec.vehiculo_marca_id=$marca and ec.vehiculo_modelo_id=$modelo");

        return $query->result_object();
    }

    public function get_deducible_info($marca, $modelo, $anio, $empresa)
    {
        $query = $this->db
            ->query("select d.nombre
                from empresa_auto_info_cotizacion ec 
                inner join empresa e
                on e.id = ec.empresa_id 
                INNER JOIN deducible d 
                on d.riesgo_id = ec.riesgo_id
                where ec.vehiculo_anio_fabricacion_id=$anio
                and ec.vehiculo_marca_id=$marca and ec.vehiculo_modelo_id=$modelo 
                AND ec.empresa_id=$empresa
                order by d.position");

        return $query->result_object();
    }
}