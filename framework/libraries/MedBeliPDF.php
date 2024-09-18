<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php

require(APPPATH . '/libraries/fpdf.php');

/**
 * Description of Pdf
 *
 * @author mike
 */
class MedBeliPDF extends FPDF {

    public $judul;

    public function header($judul) {
        $CI = & get_instance();
        $CI->load->database();

        $setting = $CI->db->get('tbl_pengaturan')->row();
//        $gambar1 = FCPATH.'assets/theme/admin-lte-3/dist/img/'.(!empty($setting->logo_header_kop) ? $setting->logo_header_kop : 'AdminLTELogo.png');
//        $gambar1 = base_url('assets/theme/admin-lte-3/dist/img/logo-header-es.png');
        $gambar1 = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-header-es1.png';
        
        $this->Ln(0.40);
        $this->SetFont('Courier', 'B', '10');
        $this->Cell(2.5, .5, '', '', 0, 'L', FALSE);
        $this->Cell(11.5, .5, $setting->judul, 'LT', 0, 'C', FALSE);
        $this->Cell(5, .5, '', 'RTL', 0, 'C', FALSE);
        $this->Ln(0.5);
        $this->SetFont('Courier', '', '10');
        $this->Cell(2.5, .5, '', '', 0, 'L', FALSE);
        $this->Cell(11.5, .5, $setting->alamat, 'L', 0, 'C', FALSE);
        $this->SetFont('Courier', 'B', '10');
        $this->Cell(5, .5, $this->judul, 'RL', 0, 'C', FALSE);
        $this->Ln(0.5);
        $this->SetFont('Courier', '', '8');
        $this->Cell(2.5, .5, '', '', 0, 'L', FALSE);
        $this->Cell(11.5, .5, (!empty($setting->tlp) ? $setting->tlp : '') . (!empty($setting->email) ? ' - ' . $setting->email : ''), 'LB', 0, 'C', FALSE);
        $this->Cell(5, .5, '', 'LBR', 0, 'C', FALSE);
        $this->Ln(1);

        // Gambar Logo Atas
        $this->Image($gambar1, 1, 0.85, 2.40, 2);
    }

    public function footer($dokter, $no_sip) {
        $gambar3 = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-footer.png';

//        $this->SetY(-7);
//        $this->Cell(4, .5, 'No. SIP', 'B', 0, 'L', FALSE);
        // Gambar Watermark Bawah
        $this->Image($gambar3, 0, 26, 21.5, 7, 'png');
    }
}
