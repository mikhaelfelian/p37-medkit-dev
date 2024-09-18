<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php

require(APPPATH . '/libraries/fpdf.php');

/**
 * Description of Pdf
 *
 * @author mike
 */
class MedRadPDF extends FPDF {

    private $nm_dokter;
    private $no_sip;


    public function header($dokter, $no_sip) {
        $CI = & get_instance();
        $CI->load->database();

        $setting = $CI->db->get('tbl_pengaturan')->row();
        $gambar1 = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-header-es.png';
        
        $this->Ln(0.75);
        $this->SetFont('Arial', 'B', '12');
        $this->SetTextColor(0,146,63,255);
        $this->Cell(10.5, .5, '', '', 0, 'L', FALSE);
        $this->Cell(8.5, .5, 'INSTALASI RADIOLOGI', '', 0, 'L', FALSE);
        $this->Ln(0.5);
        $this->SetFont('Courier', 'B', '14');
        $this->Ln(2);

        # Gambar Logo updated 2023-12-19 23:11
        $this->Image($gambar1, 1, 1, 10, 2.3);
    }

    public function footer($dokter, $no_sip) {
        $gambar3 = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-footer.png';

        // Gambar Watermark Bawah
        $this->Image($gambar3, 0, 26, 22, 7, 'png');
    }
}
