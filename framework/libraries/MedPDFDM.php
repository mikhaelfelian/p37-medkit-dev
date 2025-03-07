<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
require(APPPATH . '/libraries/fpdf.php');

/**
 * Description of Pdf
 *
 * @author mike
 */
class MedPDFdm extends FPDF {
    private $nm_dokter;
    private $no_sip;

    public function header($dokter, $no_sip) {
        $CI = & get_instance();
        $CI->load->database();

        $setting = $CI->db->get('tbl_pengaturan')->row();
        $gambar1 = FCPATH.'/assets/theme/admin-lte-3/dist/img/kop_es_bw_197x234.png';

        // Logo
        if(file_exists($gambar1)) {
            $this->Image($gambar1, 0.75, 0.25, 2, 2.5); // Adjusted size and position for the logo
        }

        // Clinic Name
        $this->SetFont('Times', 'B', 14);
        $this->SetTextColor(0, 150, 0); // Green color
        $this->Cell(2, 0.7, '', 0, 0); // Space after logo
        $this->MultiCell(8, 0.7, $setting->judul, '', 'C');

        // Address
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(0, 0, 0); // Black color
        $this->Cell(2, 0.5, '', 0, 0); // Space after logo
        $this->Cell(9, 0.5, $setting->alamat, 0, 1, 'L');

        // Contact Info
        $this->Cell(2, 0.5, '', 'B', 0); // Space after logo
        $this->Cell(9, 0.5, $setting->tlp, 'B', 1, 'L');
    }
}
