<?php // if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
require('fpdf.php');

if ($_SERVER['HTTP_HOST'] == 'locahost') {
    $host = 'http://' . $_SERVER['HTTP_HOST'] . '/administrasi/';
} else {
    $host = 'http://' . $_SERVER['HTTP_HOST'].'/';
}

/**
 * Description of pdf
 *
 * @author miki
 */
class pdf extends FPDF {

// Page header
    function header() {
        // Logo
        $this->Image($host.'assets/logo.jpg', 10, 6, 30);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30, 10, 'Title', 1, 0, 'C');
        // Line break
        $this->Ln(20);
    }

    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}
