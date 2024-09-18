<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php

class crud extends CI_Model {

    private $table_name;

    public function __construct() {
        parent::__construct();
        //$this->table_name = 'tb_about';
    }

    function simpan($tabel, $data) {
        $this->db->insert($tabel, $data);
        
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function update($tabel, $field, $kode, $p) {
        $this->db->where($field, $kode);
        $this->db->update($tabel, $p);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function delete($tabel, $field, $kode) {
        $this->db->where($field, $kode);
        $this->db->delete($tabel);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function last_id() {
        $id = $this->db->insert_id();
        return $id;
    }

    
    
    function kmr_label() {
        $sql = $this->db->select('kamar')->where('status', '1')->get('tbl_m_kamar')->result();
        
        foreach ($sql as $kmr) {
            $data[] = $kmr->kamar;
        }
        $label_kmr = json_encode($data);
        return $label_kmr;
    }
    
    function kmr_kapasitas() {
        $sql = $this->db->select('jml_max')->where('status', '1')->get('tbl_m_kamar')->result();
        
        foreach ($sql as $kmr) {
            $data[] = $kmr->jml_max;
        }
        
        $label_kaps = json_encode($data);
        return $label_kaps;
    }

}
