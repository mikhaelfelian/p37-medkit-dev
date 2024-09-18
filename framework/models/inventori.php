<?php

/**
 * Model Inventori
 *
 * @author mikhael Felian Waskito
 */
class inventori extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function bhn_rusak() {
        $nota = $this->db->query("SELECT * FROM tbl_orderlist WHERE status_order='batal'")->result();
        foreach ($nota as $nota) {
            echo '- ' . $nota->no_nota . '<br/>';
            $nota_det = $this->db->query("SELECT * FROM tbl_orderlist_det WHERE no_nota='" . $nota->no_nota . "'")->result();
            echo '<ul>';
            foreach ($nota_det as $nota_det) {
                echo '<li>' . $nota_det->menu . '</li>';
                $bahan = $this->db->query("SELECT * FROM tbl_menu_item WHERE id_menu='" . $nota_det->id_menu . "'")->result();
                echo '<ul>';
                foreach ($bahan as $bahan) {
                    echo '<li>' . $bahan->bahan . '</li>';
                }
                echo '</ul>';
            }
            echo '</ul>';
        }
    }

}
