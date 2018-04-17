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
            // TODO: send mails
            if($id) {
                redirect("/cotizador/cotizacion/$id", 'refresh', 200);
            }
        }

        redirect("/cotizador/cotizacion_error/", 'refresh', 200);
    }

    public function cotizacion_error()
    {
        $this->load->library('twig');
        $this->twig->display('cotizacion_error');
    }
}
