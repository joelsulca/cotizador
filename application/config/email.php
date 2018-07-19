<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol'] = getenv('MAIL_PROTOCOL');
$config['smtp_host'] = getenv('SMTP_HOST');
$config['smtp_user'] = getenv('SMTP_USER');
$config['smtp_pass'] = getenv('SMTP_PASS');
$config['smtp_port'] = getenv('SMTP_PORT');
$config['charset'] = 'utf-8';//'iso-8859-1';
$config['wordwrap'] = TRUE;
$config['priority'] = 1;
$config['mailtype'] = 'html';