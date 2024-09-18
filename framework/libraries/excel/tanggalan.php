<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tanggalan
 *
 * @author mike
 */
class tanggalan {

    function tgl_indo($tgl) {
        $tanggal = substr($tgl, 8, 2);
        $bulan = $this->getBulan(substr($tgl, 5, 2));
        $tahun = substr($tgl, 0, 4);
        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }

    function semester($tgl) {
        $tanggal = substr($tgl, 8, 2);
        $bulan = substr($tgl, 5, 2);

        if ($bulan >= 1 AND $bulan <= 6) {
            return 'Gasal';
        } elseif ($bulan >= 7 AND $bulan <= 12) {
            return 'Genap';
        } else {
            log('Error, Divide of Semester');
        }
    }

    function tahun_ajaran($tgl) {
        $tanggal = substr($tgl, 8, 2);
        $tahun = substr($tgl, 0, 4);
        $tahun_lalu = $tahun - 1;
        return $tahun_lalu . '/' . $tahun;
    }

    function getBulan($bln) {
        switch ($bln) {
            case 1:
                return "Januari";
                break;
            case 2:
                return "Februari";
                break;
            case 3:
                return "Maret";
                break;
            case 4:
                return "April";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Juni";
                break;
            case 7:
                return "Juli";
                break;
            case 8:
                return "Agustus";
                break;
            case 9:
                return "September";
                break;
            case 10:
                return "Oktober";
                break;
            case 11:
                return "November";
                break;
            case 12:
                return "Desember";
                break;
        }
    }

    function hari_ini() {
        $seminggu = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
        $hari = date("w");
        $hari_ini = $seminggu[$hari];
        return $hari_ini;
    }

    function bulan_ke($bln) {
        switch ($bln) {
            case 1:
                return "Januari";
                break;
            case 2:
                return "Februari";
                break;
            case 3:
                return "Maret";
                break;
            case 4:
                return "April";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Juni";
                break;
            case 7:
                return "Juli";
                break;
            case 8:
                return "Agustus";
                break;
            case 9:
                return "September";
                break;
            case 10:
                return "Oktober";
                break;
            case 11:
                return "November";
                break;
            case 12:
                return "Desember";
                break;
        }
    }

}