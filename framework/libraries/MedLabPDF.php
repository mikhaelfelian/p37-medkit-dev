<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php

require(APPPATH . '/libraries/fpdf.php');

/**
 * Description of Pdf
 *
 * @author mike
 */
class MedLabPDF extends FPDF {

    private $nm_dokter;
    private $no_sip;

    public function header($dokter, $no_sip) {
        $CI = & get_instance();
        $CI->load->database();

        $setting = $CI->db->get('tbl_pengaturan')->row();
//        $gambar1 = base_url('assets/theme/admin-lte-3/dist/img/logo-header-es.png');
        $gambar1 = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-header-es.png';

        $this->Ln(0.25);
        $this->SetFont('Arial', 'B', '12');
        $this->SetTextColor(0,146,63,255);
        $this->Cell(10.5, .5, '', '', 0, 'L', FALSE);
        $this->Cell(8.5, .5, 'INSTALASI LABORATORIUM', '', 0, 'L', FALSE);
        $this->Ln(0.5);
        $this->SetFont('Courier', 'B', '14');
        $this->Ln(1.5);

        // Gambar Logo Atas 1
        $this->Image($gambar1, 1, 0.25, 10, 2.3);
    }

    public function footer($dokter, $no_sip) {
        $gambar3 = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-footer.png'; // base_url('assets/theme/admin-lte-3/dist/img/logo-footer.png');

        // Gambar Watermark Bawah
        $this->Image($gambar3, 0, 25.75, 21.5, 7, 'png');
    }
    
    function MultiCellRow($cells, $width, $height, $data, $pdf) {
        $x = $this->GetX();
        $y = $this->GetY();
        $maxheight = 0;

        for ($i = 0; $i < $cells; $i++) {
            $this->MultiCell($width, $height, $data[$i]);
            if ($this->GetY() - $y > $maxheight)
                $maxheight = $this->GetY() - $y;
            $this->SetXY($x + ($width * ($i + 1)), $y);
        }

        for ($i = 0; $i < $cells + 1; $i++) {
            $this->Line($x + $width * $i, $y, $x + $width * $i, $y + $maxheight);
        }

        $this->Line($x, $y, $x + $width * $cells, $y);
        $this->Line($x, $y + $maxheight, $x + $width * $cells, $y + $maxheight);
    }
    
    function GetMultiCellHeight($w, $h, $txt, $border = null, $align = 'J') {
        // Calculate MultiCell with automatic or explicit line breaks height
        // $border is un-used, but I kept it in the parameters to keep the call
        //   to this function consistent with MultiCell()
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $height = 0;
        while ($i < $nb) {
            // Get next character
            $c = $s[$i];
            if ($c == "\n") {
                // Explicit line break
                if ($this->ws > 0) {
                    $this->ws = 0;
                    $this->_out('0 Tw');
                }
                //Increase Height
                $height += $h;
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
                $ls = $l;
                $ns++;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                // Automatic line break
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                    if ($this->ws > 0) {
                        $this->ws = 0;
                        $this->_out('0 Tw');
                    }
                    //Increase Height
                    $height += $h;
                } else {
                    if ($align == 'J') {
                        $this->ws = ($ns > 1) ? ($wmax - $ls) / 1000 * $this->FontSize / ($ns - 1) : 0;
                        $this->_out(sprintf('%.3F Tw', $this->ws * $this->k));
                    }
                    //Increase Height
                    $height += $h;
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
            } else
                $i++;
        }
        // Last chunk
        if ($this->ws > 0) {
            $this->ws = 0;
            $this->_out('0 Tw');
        }
        //Increase Height
        $height += $h;

        return $height;
    }
}
