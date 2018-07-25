<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizador extends CI_Controller
{

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
        $data = $annos = $this->Cotizador_Model->get_anio_fabricacion($marca, $modelo);
        echo json_encode($data);
    }

    public function ver_correo($contacto_id)
    {
        $this->load->model('Cotizador_Model');
        $contacto_id = $this->contacto_token(substr($contacto_id, 32 - strlen($contacto_id)), substr($contacto_id, 0, 32), 2);
        $cotizacion = $this->Cotizador_Model->get_cotizacion($contacto_id);
        $mail = $this->input->get('mail');

        $this->_send_mail($cotizacion, $mail?$mail:'cliente');
    }

    public function cotizacion($contacto_id)
    {
        $this->load->library('twig');
        $this->load->model('Cotizador_Model');

        $contacto_id = $this->contacto_token(substr($contacto_id, 32 - strlen($contacto_id)), substr($contacto_id, 0, 32), 2);

        $modal = in_array($this->input->get('modal'), array('asistencia', 'cobertura', 'deducible', 'contacto_telefonico')) ?
            $this->input->get('modal') : false;
        $empresa = $this->input->get('empresa');

        if ($contacto_id) {
            $cotizacion = $this->Cotizador_Model->get_cotizacion($contacto_id);

            if ($modal) {
                $this->get_modal($cotizacion, $modal, $empresa);
            } else {
                $empresas_info = $this->Cotizador_Model->get_cotizacion_info_by_auto($cotizacion->vehiculo_marca_id,
                    $cotizacion->vehiculo_modelo_id, $cotizacion->vehiculo_anio_fabricacion_id);
                $data = array('cotizacion' => $cotizacion, 'empresas_info' => $empresas_info);
                $this->twig->display('cotizacion', $data);
            }
        } else {
            $this->redirect("/cotizacion_error/", 'refresh', 200);
        }
    }

    public function contacto()
    {
        $data = $this->input->post();
        if ($this->input->post('vehiculo_uso') === 'option1') {
            $this->load->model('Cotizador_Model');
            $id = $this->Cotizador_Model->save_cotizacion($data);

            $cotizacion = $this->Cotizador_Model->get_cotizacion($id);

            if ($id && $this->_send_mail($cotizacion, false)) {
                $id = $this->contacto_token($id, null, 1);
                $this->redirect("/cotizacion/$id", 'refresh', 200);
            }
        }

        $this->redirect("/cotizacion_error/", 'refresh', 200);
    }

    private function contacto_token($value, $value2, $mode)
    {

        return $mode === 1 ? md5($this->config->item('encryption_key') . $value) . $value : ((md5($this->config->item('encryption_key') . $value) === $value2) ? $value : false);
    }

    public function cotizacion_error()
    {
        $this->load->library('twig');
        $this->twig->display('cotizacion_error');
    }

    private function _send_mail($data, $only_render)
    {
        //$this->load->library('sendgridci');
        $this->load->library('ariansenmailer');
        $this->load->library('twig');

        $nombres = $data->conductor_nombres . " " . $data->conductor_apellidos;
        // for client
        $empresas_info = $this->Cotizador_Model->get_cotizacion_info_by_auto($data->vehiculo_marca_id,
            $data->vehiculo_modelo_id, $data->vehiculo_anio_fabricacion_id);
        $cotizacion_url = base_url('/cotizacion/' . md5($this->config->item('encryption_key') . $data->id) . $data->id);

        $mail = $this->twig->render('mails/cliente', array(
            'cotizacion' => $data,
            'empresas_info' => $empresas_info,
            'cotizacion_url' => $cotizacion_url));
        // solo para prueba
        if($only_render==='cliente')
        {
            echo $mail;
            exit;
        }

        $r1 = false; $r2 = false;

        if(!$only_render)
            $r1 = $this->ariansenmailer->sendHtmlMail($data->conductor_correo,
                $this->config->item('ariansen_mail_client_subject'), $mail);

        // for ariansen
        $mail = $this->twig->render('mails/contacto', array('cotizacion' => $data));
        // solo para prueba
        if($only_render==='contacto')
        {
            echo $mail;
            exit;
        }

        if (!$only_render)
            $r2 = $this->ariansenmailer->sendHtmlMail($this->config->item('ariansen_mail_to'),
                sprintf($this->config->item('ariansen_mail_contact_subject'), $nombres), $mail);

        return ($only_render)? true : ( $r1 & $r2 );
    }

    private function get_modal($cotizacion, $modal, $empresa)
    {
        $this->load->library('twig');
        $data = array('cotizacion' => $cotizacion);

        if ($modal === 'deducible') {
            $info = $this->Cotizador_Model->get_deducible_info($cotizacion->vehiculo_marca_id,
                $cotizacion->vehiculo_modelo_id, $cotizacion->vehiculo_anio_fabricacion_id, $empresa);
            $data['data'] = $info;
        }

        $html = $this->twig->render("modals/$modal", $data);
        echo $html;
    }

    private function redirect($uri, $method, $code)
    {
        if(strpos(base_url(), '/cotizador') === FALSE){
            $uri = "/cotizador$uri";
        }
        redirect($uri, $method, $code);
    }

}
