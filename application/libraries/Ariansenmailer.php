<?php

/**
 * Created by PhpStorm.
 * User: holivares
 * Date: 7/19/2018
 * Time: 11:05 AM
 */
class Ariansenmailer
{

    public function __construct()
    {
        $CI =& get_instance();
        // LOAD CORE EMAIL LIBRARY
        $CI->load->library('email');
    }
    
    public function _send($to_mail, $subject, $message)
    {
        $CI =& get_instance();
        
        $CI->email->from($CI->config->item('ariansen_mail_from'), 
                         $CI->config->item('ariansen_mail_from_displayname'));
        $CI->email->to($to_mail);
        
        $CI->email->subject($subject);
        $CI->email->message($message);
        
        $r = $CI->email->send(FALSE);
        if(!$r){
            log_message("DEBUG", strtoupper(ENVIRONMENT ) . " - EL CORREO NO PUDO SER ENVIADO A $to_mail, EL FLUJO CONTINUA");
        }else{
            log_message("DEBUG", strtoupper(ENVIRONMENT ) . " - CORREO ENVIADO A $to_mail, EL FLUJO CONTINUA");
        }
        if(ENVIRONMENT === "development" ) {
            $r = TRUE; // if fails en development the flow continue
        }
        return $r;
    }
    
    public function send($to_mail, $subject, $message)
    {
        return $this->_send($to_mail, $subject, $message);
    }
    
    public function sendTextMail($to_mail, $subject, $message)
    {
        $CI =& get_instance();
        $CI->email->set_mailtype('text');
        
        return $this->_send($to_mail, $subject, $message);
    }

    public function sendHtmlMail($to_mail, $subject, $message)
    {
        $CI =& get_instance();
        $CI->email->set_mailtype('html');
        
        return $this->_send($to_mail, $subject, $message);
    }
}