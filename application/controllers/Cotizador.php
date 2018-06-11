<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizador extends CI_Controller {

    public function index()
    {
        $this->load->model('Cotizador_Model');
        $this->load->library('twig');
        $marcas = $this->Cotizador_Model->get_marcas();

        $data = array('marcas' => $marcas);
        $this->twig->display('cotizacion_contacto', $data);
    }

    public function modelos($marca)
    {
        $this->load->model('Cotizador_Model');
        $modelos = $this->Cotizador_Model->get_modelos($marca);
        echo json_encode($modelos);
    }

    public function annios($marca, $modelo)
    {
        $this->load->model('Cotizador_Model');
        $data =  $annos = $this->Cotizador_Model->get_anio_fabricacion($marca, $modelo);
        echo json_encode($data);
    }

    public function cotizacion($contacto_id)
    {
        $this->load->library('twig');
        $this->load->model('Cotizador_Model');

        $contacto_id = $this->contacto_token( substr($contacto_id, 32-strlen($contacto_id)), substr($contacto_id,0, 32), 2);

        $modal = in_array($this->input->get('modal'), array('asistencia', 'cobertura', 'deducible', 'contacto_telefonico'))?
            $this->input->get('modal'):false;
        $empresa = $this->input->get('empresa');

        if($contacto_id) {
            $cotizacion = $this->Cotizador_Model->get_cotizacion($contacto_id);

            if($modal){
                $this->get_modal($cotizacion, $modal, $empresa);
            }else {
                $empresas_info = $this->Cotizador_Model->get_cotizacion_info_by_auto($cotizacion->vehiculo_marca_id,
                    $cotizacion->vehiculo_modelo_id, $cotizacion->vehiculo_anio_fabricacion_id);
                $data = array('cotizacion' => $cotizacion, 'empresas_info' => $empresas_info);
                $this->twig->display('cotizacion', $data);
            }
        }else{
            redirect("/cotizador/cotizacion_error/", 'refresh', 200);
        }
    }

    public function contacto()
    {
        $data = $this->input->post();
        if($this->input->post('vehiculo_uso') === 'option1' ) {
            $this->load->model('Cotizador_Model');
            $id = $this->Cotizador_Model->save_cotizacion($data) ;

            $cotizacion = $this->Cotizador_Model->get_cotizacion($id) ;

            if($id && $this->_send_mail($cotizacion)) {
                $id = $this->contacto_token($id, null, 1);
                redirect("/cotizador/cotizacion/$id", 'refresh', 200);
            }
        }

        redirect("/cotizador/cotizacion_error/", 'refresh', 200);
    }

    private function contacto_token($value, $value2, $mode)
    {

        return $mode === 1 ? md5($value).$value : ((md5($value) === $value2)? $value : false);
    }

    public function cotizacion_error()
    {
        $this->load->library('twig');
        $this->twig->display('cotizacion_error');
    }

    private function _send_mail($data)
    {
        $this->load->library('sendgridci');
        $this->load->library('twig');

        $nombres = $data->conductor_nombres . " " . $data->conductor_apellidos;
        // for client
        $r1 = $this->sendgridci->sendMail('ariansen.cliente@gmail.com', $data->conductor_correo, "Ariansen Contacto", "Le enviamos los detalles de su cotizacion.");
        // for ariansen
        $mail  = $this->twig->render('mails/contacto', array('cotizacion' => $data));
        $r2 = $this->sendgridci->sendHtmlMail('ariansen.cliente@gmail.com', "ariansen1@gmail.com", "Cotizacion Ariansen de $nombres", $mail);
        return $r1 & $r2;
    }

    private function get_modal($cotizacion, $modal, $empresa){
        $this->load->library('twig');
        $data = array('cotizacion' => $cotizacion);

        if($modal==='deducible'){
            $info = $this->Cotizador_Model->get_deducible_info($cotizacion->vehiculo_marca_id,
                $cotizacion->vehiculo_modelo_id, $cotizacion->vehiculo_anio_fabricacion_id, $empresa);
            $data['data'] = $info;
        }

        $html  = $this->twig->render("modals/$modal", $data);
        echo $html;
    }

}
