<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizador extends CI_Controller {

	public function index()
	{
        $this->load->model('Cotizador_Model');
        $this->load->library('twig');
        $marcas = $this->Cotizador_Model->get_marcas();
        $annos = $this->Cotizador_Model->get_anio_fabricacion();


        $data = array('marcas' => $marcas, 'annos'=>$annos);
        $this->twig->display('cotizacion_contacto', $data);
    }

    public function modelos($marca)
    {
        $this->load->model('Cotizador_Model');
        $modelos = $this->Cotizador_Model->get_modelos($marca);
        echo json_encode($modelos);
    }

    public function cotizacion($contacto_id)
    {
        $this->load->library('twig');
        $this->load->model('Cotizador_Model');
        $cotizacion = $this->Cotizador_Model->get_cotizacion($contacto_id) ;
        $data = array('cotizacion' => $cotizacion);
        $this->twig->display('cotizacion', $data);
    }

    public function contacto()
    {
        $data = $this->input->post();
        if($this->input->post('vehiculo_uso') === 'option1' ) {
            $this->load->model('Cotizador_Model');
            $id = $this->Cotizador_Model->save_cotizacion($data) ;

            if($id && $this->_send_mail($data)) {
                redirect("/cotizador/cotizacion/$id", 'refresh', 200);
            }
        }

        //redirect("/cotizador/cotizacion_error/", 'refresh', 200);
    }

    public function cotizacion_error()
    {
        $this->load->library('twig');
        $this->twig->display('cotizacion_error');
    }

    private function _send_mail($data){
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'ariansen.cliente@gmail.com',
            'smtp_pass' => 'Cuzco2018',
            'mailtype'  => 'html',
            'charset'   => 'iso-8859-1'
        );
        $this->load->library('email', $config);

        $this->email->from('ariansen.cliente@gmail.com', 'Ariansen Contacto');
        $this->email->to($data['conductor_correo']);
        //$this->email->bcc('them@their-example.com');

        $this->email->subject('Cotizacion Ariansen');
        $this->email->message('Le enviamos los detalles de su cotizacion.');

        $nombres = $data['conductor_nombres'] . " " . $data['conductor_apellidos'];
        $this->email->from('ariansen.cliente@gmail.com', 'Ariansen Contacto');
        $this->email->to("ariansen1@gmail.com");
        //$this->email->bcc('them@their-example.com');

        $this->email->subject('Cotizacion Ariansen');
        $this->email->message("Le enviamos los detalles de la cotizacion de $nombres");

        return $this->email->send();
    }
}
