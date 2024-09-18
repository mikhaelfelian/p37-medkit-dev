<?php

class surat extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('email');
    }

    public function kirim($kpd, $dari, $judul, $pesan) {
        $this->email->from($dari);
        $this->email->to($kpd);
        $this->email->subject($judul);
        $this->email->message($pesan);
        $this->email->send();
    }

}
