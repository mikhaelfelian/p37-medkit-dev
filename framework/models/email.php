<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php

class email extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function send_register() {
        $this->email->from($sql->email, $sql->judul);
        $this->email->to($this->input->post('email'));
        $this->email->subject($sql->judul . ' Kode Aktivasi Akun');

        $message = "Kepada, " . ucwords($this->input->post('nama_customer')) . ", \r\n\r\n";
        $message .="Terimakasih telah mendaftar sebagai member di situs kami.\r\n";
        $message .="Untuk mengaktivasi akun anda, silahkan klik link di bawah ini :\r\n\r\n";
        $message .=" ".base_url()."aktivasi-member.php?ref=".$this->encrypt->encode_url($this->input->post('email')) . "&id=" . rand(32, 4098) . "\r\n\r\n";
        $message .="Best Regards, Administrator of ".$sql->judul;

        $this->email->message($message);
        $this->email->send();
    }

}
