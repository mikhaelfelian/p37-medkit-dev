<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Configuration Email
|--------------------------------------------------------------------------
|
| Setting that use email
| For online shop
|
*/
$CI =& get_instance();
$CI->load->database();

$setting = $CI->db->query("SELECT * FROM tbl_pengaturan")->row();
$mail    = $CI->db->query("SELECT * FROM tbl_pengaturan_mail WHERE id='".$setting->id_mail."'")->row();

$config['mailtype']     = 'html';
$config['protocol']     = $mail->proto;
$config['smtp_host']    = $mail->host;
$config['smtp_user']    = $mail->user;
$config['smtp_pass']    = $mail->pass;
$config['smtp_port']    = $mail->port;
$config['smtp_timeout'] = $mail->timeout;

