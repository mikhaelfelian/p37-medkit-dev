<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
/**
 * Description of laporan
 *
 * @author mike
 */
class laporan extends CI_Controller {
    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->library('excel/PHPExcel');
    }
    
    public function index() {
        if (akses::aksesLogin() == TRUE) {
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/index', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_cuti(){
        if (akses::aksesLogin() == TRUE) {
            $karyawan   = $this->input->get('id_kary');
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            
            $data['sql_kat_cuti']   = $this->db->get('tbl_m_kategori_cuti')->result();
            $data['sql_kry']        = $this->db->get('tbl_m_karyawan')->result();
            
            if($jml > 0){
                $sql_kary = $this->db->where('id_user', general::dekrip($karyawan))->get('tbl_m_karyawan')->row();
                
                switch ($case){
                    case 'per_tanggal':
                        $data['sql_cuti']     = $this->db
                                                      ->select('tbl_sdm_cuti.id, tbl_sdm_cuti.id_user, tbl_sdm_cuti.tgl_simpan, tbl_sdm_cuti.tgl_masuk, tbl_sdm_cuti.tgl_keluar, tbl_sdm_cuti.no_surat, tbl_sdm_cuti.keterangan, tbl_sdm_cuti.catatan, tbl_sdm_cuti.file_name, tbl_sdm_cuti.file_type, tbl_sdm_cuti.status, tbl_m_karyawan.nama_dpn, tbl_m_karyawan.nama, tbl_m_karyawan.nama_blk, tbl_m_karyawan.tgl_lahir, tbl_m_karyawan.alamat, tbl_m_karyawan.jns_klm, tbl_m_kategori_cuti.tipe')
                                                      ->join('tbl_m_karyawan', 'tbl_m_karyawan.id=tbl_sdm_cuti.id_karyawan')
                                                      ->join('tbl_m_kategori_cuti', 'tbl_m_kategori_cuti.id=tbl_sdm_cuti.id_kategori')
                                                      ->where('tbl_sdm_cuti.id_kategori', $this->input->get('tipe'))
                                                      ->where('DATE(tbl_sdm_cuti.tgl_simpan)', $this->input->get('tgl'))
                                                      ->like('tbl_m_karyawan.id', $sql_kary->id, (!empty($sql_kary->id) ? 'none' : ''))
                                                      ->get('tbl_sdm_cuti')->result();  
                        break;
                    
                    case 'per_rentang':
                        $data['sql_cuti']     = $this->db
                                                      ->select('tbl_sdm_cuti.id, tbl_sdm_cuti.id_user, tbl_sdm_cuti.tgl_simpan, tbl_sdm_cuti.tgl_masuk, tbl_sdm_cuti.tgl_keluar, tbl_sdm_cuti.no_surat, tbl_sdm_cuti.keterangan, tbl_sdm_cuti.catatan, tbl_sdm_cuti.file_name, tbl_sdm_cuti.file_type, tbl_sdm_cuti.status, tbl_m_karyawan.nama_dpn, tbl_m_karyawan.nama, tbl_m_karyawan.nama_blk, tbl_m_karyawan.tgl_lahir, tbl_m_karyawan.alamat, tbl_m_karyawan.jns_klm, tbl_m_kategori_cuti.tipe')
                                                      ->join('tbl_m_karyawan', 'tbl_m_karyawan.id=tbl_sdm_cuti.id_karyawan')
                                                      ->join('tbl_m_kategori_cuti', 'tbl_m_kategori_cuti.id=tbl_sdm_cuti.id_kategori')
                                                      ->where('tbl_sdm_cuti.id_kategori', $this->input->get('tipe'))
                                                      ->where('DATE(tbl_sdm_cuti.tgl_simpan) >=', $this->input->get('tgl_awal'))
                                                      ->where('DATE(tbl_sdm_cuti.tgl_simpan) <=', $this->input->get('tgl_akhir'))
                                                      ->like('tbl_m_karyawan.id', $sql_kary->id, (!empty($sql_kary->id) ? 'none' : ''))
                                                      ->get('tbl_sdm_cuti')->result();     
                        break;
                }
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_cuti', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_periksa(){
        if (akses::aksesLogin() == TRUE) {
            $dokter     = $this->input->get('id_dokter');
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $setting    = $this->db->get('tbl_pengaturan')->row();
            
            $data['sql_doc']    = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            $data['pengaturan'] = $setting;
            
            if($jml > 0){
                $sql_doc = $this->db->where('id_user', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
                
                switch ($case){
                    case 'per_tanggal':
                        $data['sql_periksa']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.id_dokter, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.anamnesa, tbl_trans_medcheck.diagnosa, tbl_trans_medcheck.pemeriksaan, tbl_trans_medcheck.program, tbl_m_pasien.nama_pgl, tbl_m_pasien.no_hp, tbl_m_poli.lokasi, tbl_m_karyawan.nama_dpn, tbl_m_karyawan.nama, tbl_m_karyawan.nama_blk')
                                                          ->join('tbl_m_karyawan', 'tbl_m_karyawan.id_user=tbl_trans_medcheck.id_dokter')
                                                          ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                          ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                          ->where('DATE(tbl_trans_medcheck.tgl_simpan)', $tgl)
                                                      ->get('tbl_trans_medcheck')->result();
                        break;
                    
                    case 'per_rentang':
                        $data['sql_periksa']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.id_dokter, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.anamnesa, tbl_trans_medcheck.diagnosa, tbl_trans_medcheck.pemeriksaan, tbl_trans_medcheck.program, tbl_m_pasien.nama_pgl, tbl_m_pasien.no_hp, tbl_m_poli.lokasi, tbl_m_karyawan.nama_dpn, tbl_m_karyawan.nama, tbl_m_karyawan.nama_blk')
                                                          ->join('tbl_m_karyawan', 'tbl_m_karyawan.id_user=tbl_trans_medcheck.id_dokter')
                                                          ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                          ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                          ->like('tbl_trans_medcheck.id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
                                                          ->where('DATE(tbl_trans_medcheck.tgl_simpan) >=', $tgl_awal)
                                                          ->where('DATE(tbl_trans_medcheck.tgl_simpan) <=', $tgl_akhir)
                                                      ->get('tbl_trans_medcheck')->result();
                        break;
                }
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_periksa', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_remunerasi(){
        if (akses::aksesLogin() == TRUE) {
            $dokter     = $this->input->get('id_dokter');
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $tipe       = $this->input->get('tipe');
            $case       = $this->input->get('case');
            
            $data['sql_doc']    = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            
            if($jml > 0){
                $sql_doc = $this->db->where('id_user', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
                
                switch ($case){
                    case 'per_tanggal':
                        $data['sql_remun']       = $this->db
                                                        ->where('DATE(tgl_simpan)', $tgl)
                                                        ->like('status_produk', (!empty($tipe) ? $tipe : ''))
                                                        ->like('id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
                                                        ->get('v_medcheck_remun')->result();
                        break;
                    
                    case 'per_rentang':
                        $data['sql_remun']       = $this->db
                                                        ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                                        ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                                        ->like('status_produk', (!empty($tipe) ? $tipe : ''))
                                                        ->like('id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
                                                        ->get('v_medcheck_remun')->result();
//                                                          
//                        $data['sql_remun']     = $this->db->select('tbl_trans_medcheck_remun.id, tbl_trans_medcheck_remun.tgl_simpan, tbl_trans_medcheck_remun.harga, tbl_trans_medcheck_remun.remun_nom, tbl_trans_medcheck_remun.remun_subtotal, tbl_trans_medcheck_remun.remun_perc, tbl_m_pasien.nama_pgl, tbl_trans_medcheck.id, tbl_trans_medcheck_remun.id_dokter, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.tipe, tbl_m_poli.lokasi, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.jml')
//                                                          ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_remun.id_medcheck', 'left')
//                                                          ->join('tbl_trans_medcheck_det', 'tbl_trans_medcheck_det.id=tbl_trans_medcheck_remun.id_medcheck_det', 'left')
//                                                          ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
//                                                          ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
//                                                          ->join('tbl_m_produk', 'tbl_m_produk.id=tbl_trans_medcheck_remun.id_item')
//                                                          ->where('DATE(tbl_trans_medcheck_remun.tgl_simpan) >=', $tgl_awal)
//                                                          ->where('DATE(tbl_trans_medcheck_remun.tgl_simpan) <=', $tgl_akhir)
//                                                          ->like('tbl_m_produk.status', (!empty($tipe) ? $tipe : ''))
//                                                          ->like('tbl_trans_medcheck_remun.id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
//                                                          ->get('tbl_trans_medcheck_remun')->result();
//                        
//                        $data['sql_remun_row'] = $this->db->select_sum('tbl_trans_medcheck_remun.remun_nom')
//                                                          ->like('tbl_trans_medcheck_remun.id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
//                                                          ->where('DATE(tbl_trans_medcheck_remun.tgl_simpan) >=', $tgl_awal)
//                                                          ->where('DATE(tbl_trans_medcheck_remun.tgl_simpan) <=', $tgl_akhir)->get('tbl_trans_medcheck_remun')->row();
                        break;
                }
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_remunerasi', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_periksa_wa(){
        if (akses::aksesLogin() == TRUE) {
            $dokter     = $this->input->get('id_dokter');
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $setting    = $this->db->get('tbl_pengaturan')->row();
            
            
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_apresiasi(){
        if (akses::aksesLogin() == TRUE) {
            $dokter     = $this->input->get('id_dokter');
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $tipe       = $this->input->get('tipe');
            $case       = $this->input->get('case');
            
            $data['sql_doc']    = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            
            if($jml > 0){
                $sql_doc = $this->db->where('id_user', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
                
                switch ($case){
                    case 'per_tanggal':
                        $data['sql_apres']  = $this->db->where('DATE(tgl_simpan)', $tgl)
                                                       ->like('status_produk', (!empty($tipe) ? $tipe : ''))
                                                       ->like('id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
                                                       ->get('v_medcheck_apres')->result();
                        break;
                    
                    case 'per_rentang':
                        $data['sql_apres']  = $this->db->where('DATE(tgl_simpan) >=', $tgl_awal)
                                                       ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                                       ->like('status_produk', (!empty($tipe) ? $tipe : ''))
                                                       ->like('id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
                                                       ->get('v_medcheck_apres')->result();
                        break;
                }
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_apresiasi', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_icd(){
        if (akses::aksesLogin() == TRUE) {
            $dokter             = $this->input->get('id_dokter');
            $poli               = $this->input->get('poli');
            $tipe               = $this->input->get('tipe');
            $plat               = $this->input->get('plat');
            $jml                = $this->input->get('jml');
            $tgl                = $this->input->get('tgl');
            $tgl_awal           = $this->input->get('tgl_awal');
            $tgl_akhir          = $this->input->get('tgl_akhir');
            $case               = $this->input->get('case');
            $hal                = $this->input->get('halaman');
            $pasien_id          = $this->input->get('id_pasien');
            $pasien             = $this->input->get('pasien');
            $pengaturan         = $this->db->get('tbl_pengaturan')->row();
            
            $data['sql_doc']    = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            $data['sql_poli']   = $this->db->get('tbl_m_poli')->result();
            $data['sql_plat']   = $this->db->get('tbl_m_platform')->result();
            
            if($jml > 0){
                $data['hasError'] = $this->session->flashdata('form_error');
                
                // Config Pagination
                $config['base_url']              = base_url('laporan/data_icd.php?case='.$case.(!empty($plat) ? '&plat='.$plat : '').(!empty($poli) ? '&poli='.$poli : '').(!empty($tgl) ? '&tgl='.$tgl : '').(!empty($tgl_awal) ? '&tgl_awal='.$tgl_awal : '').(!empty($tgl_akhir) ? '&tgl_akhir='.$tgl_akhir : '').(!empty($jml) ? '&jml='.$jml : ''));
                $config['total_rows']            = $jml;
                
                $config['query_string_segment']  = 'halaman';
                $config['page_query_string']     = TRUE;
                $config['per_page']              = $pengaturan->jml_item;
                $config['num_links']             = 3;
                
                $config['first_tag_open']        = '<li class="page-item">';
                $config['first_tag_close']       = '</li>';
                
                $config['prev_tag_open']         = '<li class="page-item">';
                $config['prev_tag_close']        = '</li>';
                
                $config['num_tag_open']          = '<li class="page-item">';
                $config['num_tag_close']         = '</li>';
                
                $config['next_tag_open']         = '<li class="page-item">';
                $config['next_tag_close']        = '</li>';
                
                $config['last_tag_open']         = '<li class="page-item">';
                $config['last_tag_close']        = '</li>';
                
                $config['cur_tag_open']          = '<li class="page-item"><a href="#" class="page-link text-dark"><b>';
                $config['cur_tag_close']         = '</b></a></li>';
                
                $config['first_link']            = '&laquo;';
                $config['prev_link']             = '&lsaquo;';
                $config['next_link']             = '&rsaquo;';
                $config['last_link']             = '&raquo;';
                $config['anchor_class']          = 'class="page-link"';
            
                $sql_doc = $this->db->where('id', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
                
                switch ($case){
                    case 'per_tanggal':
                        $data['sql_icd'] = $this->db->query(""
                                . "SELECT "
                                . "id, kode, icd, diagnosa_en, COUNT(icd) AS jml "
                                . "FROM tbl_trans_medcheck_icd "
                                . "WHERE DATE(tbl_trans_medcheck_icd.tgl_simpan) = '".$this->tanggalan->tgl_indo_sys($tgl)."' "
                                . "GROUP BY tbl_trans_medcheck_icd.id_icd HAVING  COUNT(id_icd) > 1 "
                                . "ORDER BY COUNT(tbl_trans_medcheck_icd.icd) DESC;"
                                . "")->result();
                        break;
                    
                    case 'per_rentang':
                        $data['sql_icd'] = $this->db->query(""
                                . "SELECT "
                                . "id, kode, icd, diagnosa_en, COUNT(icd) AS jml "
                                . "FROM tbl_trans_medcheck_icd "
                                . "WHERE DATE(tbl_trans_medcheck_icd.tgl_simpan) >= '".$this->tanggalan->tgl_indo_sys($tgl_awal)."' AND DATE(tbl_trans_medcheck_icd.tgl_simpan) <= '".$this->tanggalan->tgl_indo_sys($tgl_akhir)."' "
                                . "GROUP BY tbl_trans_medcheck_icd.id_icd HAVING  COUNT(id_icd) > 1 "
                                . "ORDER BY COUNT(tbl_trans_medcheck_icd.icd) DESC;"
                                . "")->result();
                        break; 
                }
                
                // Initializing Config Pagination
                $this->pagination->initialize($config);
                
                // Pagination Data
                $data['total_rows'] = $config['total_rows'];
                $data['PerPage']    = $config['per_page'];
                $data['pagination'] = $this->pagination->create_links();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_icd', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_icd_pasien(){
        if (akses::aksesLogin() == TRUE) {
            $dokter             = $this->input->get('id_dokter');
            $poli               = $this->input->get('poli');
            $tipe               = $this->input->get('tipe');
            $plat               = $this->input->get('plat');
            $jml                = $this->input->get('jml');
            $tgl                = $this->input->get('tgl');
            $tgl_awal           = $this->input->get('tgl_awal');
            $tgl_akhir          = $this->input->get('tgl_akhir');
            $case               = $this->input->get('case');
            $hal                = $this->input->get('halaman');
            $pasien_id          = $this->input->get('id_pasien');
            $pasien             = $this->input->get('pasien');
            $pengaturan         = $this->db->get('tbl_pengaturan')->row();
            
            $data['sql_doc']    = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            $data['sql_poli']   = $this->db->get('tbl_m_poli')->result();
            $data['sql_plat']   = $this->db->get('tbl_m_platform')->result();
            
            if($jml > 0){
                $data['hasError'] = $this->session->flashdata('form_error');
                
                // Config Pagination
                $config['base_url']              = base_url('laporan/data_icd.php?case='.$case.(!empty($plat) ? '&plat='.$plat : '').(!empty($poli) ? '&poli='.$poli : '').(!empty($tgl) ? '&tgl='.$tgl : '').(!empty($tgl_awal) ? '&tgl_awal='.$tgl_awal : '').(!empty($tgl_akhir) ? '&tgl_akhir='.$tgl_akhir : '').(!empty($jml) ? '&jml='.$jml : ''));
                $config['total_rows']            = $jml;
                
                $config['query_string_segment']  = 'halaman';
                $config['page_query_string']     = TRUE;
                $config['per_page']              = $pengaturan->jml_item;
                $config['num_links']             = 3;
                
                $config['first_tag_open']        = '<li class="page-item">';
                $config['first_tag_close']       = '</li>';
                
                $config['prev_tag_open']         = '<li class="page-item">';
                $config['prev_tag_close']        = '</li>';
                
                $config['num_tag_open']          = '<li class="page-item">';
                $config['num_tag_close']         = '</li>';
                
                $config['next_tag_open']         = '<li class="page-item">';
                $config['next_tag_close']        = '</li>';
                
                $config['last_tag_open']         = '<li class="page-item">';
                $config['last_tag_close']        = '</li>';
                
                $config['cur_tag_open']          = '<li class="page-item"><a href="#" class="page-link text-dark"><b>';
                $config['cur_tag_close']         = '</b></a></li>';
                
                $config['first_link']            = '&laquo;';
                $config['prev_link']             = '&lsaquo;';
                $config['next_link']             = '&rsaquo;';
                $config['last_link']             = '&raquo;';
                $config['anchor_class']          = 'class="page-link"';
            
                $sql_doc = $this->db->where('id', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
                
                switch ($case){
                    case 'per_tanggal':
                        $data['sql_icd'] = $this->db->query(""
                                . "SELECT "
                                . "id, id_medcheck, id_icd, kode, icd, diagnosa_en, COUNT(icd) AS jml "
                                . "FROM tbl_trans_medcheck_icd "
                                . "WHERE DATE(tbl_trans_medcheck_icd.tgl_simpan) = '".$this->tanggalan->tgl_indo_sys($tgl)."' "
                                . "GROUP BY tbl_trans_medcheck_icd.id_icd HAVING  COUNT(id_icd) > 1 "
                                . "ORDER BY COUNT(tbl_trans_medcheck_icd.icd) DESC;"
                                . "")->result();
                        break;
                    
                    case 'per_rentang':
                        $data['sql_icd'] = $this->db->query(""
                                . "SELECT "
                                . "id, id_medcheck, id_icd, kode, icd, diagnosa_en, COUNT(icd) AS jml "
                                . "FROM tbl_trans_medcheck_icd "
                                . "WHERE DATE(tbl_trans_medcheck_icd.tgl_simpan) >= '".$this->tanggalan->tgl_indo_sys($tgl_awal)."' AND DATE(tbl_trans_medcheck_icd.tgl_simpan) <= '".$this->tanggalan->tgl_indo_sys($tgl_akhir)."' "
                                . "GROUP BY tbl_trans_medcheck_icd.id_icd HAVING  COUNT(id_icd) > 1 "
                                . "ORDER BY COUNT(tbl_trans_medcheck_icd.icd) DESC;"
                                . "")->result();
                        break; 
                }
                
                // Initializing Config Pagination
                $this->pagination->initialize($config);
                
                // Pagination Data
                $data['total_rows'] = $config['total_rows'];
                $data['PerPage']    = $config['per_page'];
                $data['pagination'] = ''; // $this->pagination->create_links();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_icd_diagnosa', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_mcu(){
        if (akses::aksesLogin() == TRUE) {
            $dokter             = $this->input->get('id_dokter');
            $poli               = $this->input->get('poli');
            $tipe               = $this->input->get('tipe');
            $plat               = $this->input->get('plat');
            $jml                = $this->input->get('jml');
            $tgl                = $this->input->get('tgl');
            $tgl_awal           = $this->input->get('tgl_awal');
            $tgl_akhir          = $this->input->get('tgl_akhir');
            $case               = $this->input->get('case');
            $hal                = $this->input->get('halaman');
            $pasien_id          = $this->input->get('id_pasien');
            $pasien             = $this->input->get('pasien');
            $pengaturan         = $this->db->get('tbl_pengaturan')->row();
            
            $data['sql_doc']    = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            $data['sql_poli']   = $this->db->get('tbl_m_poli')->result();
            $data['sql_plat']   = $this->db->get('tbl_m_platform')->result();
            
            if($jml > 0){
                $data['hasError'] = $this->session->flashdata('form_error');
                
                // Config Pagination
                $config['base_url']              = base_url('laporan/data_mcu.php?case='.$case.(!empty($tgl) ? '&tgl='.$tgl : '').(!empty($tgl_awal) ? '&tgl_awal='.$tgl_awal : '').(!empty($tgl_akhir) ? '&tgl_akhir='.$tgl_akhir : '').(!empty($jml) ? '&jml='.$jml : ''));
                $config['total_rows']            = $jml;
                
                $config['query_string_segment']  = 'halaman';
                $config['page_query_string']     = TRUE;
                $config['per_page']              = $pengaturan->jml_item;
                $config['num_links']             = 3;
                
                $config['first_tag_open']        = '<li class="page-item">';
                $config['first_tag_close']       = '</li>';
                
                $config['prev_tag_open']         = '<li class="page-item">';
                $config['prev_tag_close']        = '</li>';
                
                $config['num_tag_open']          = '<li class="page-item">';
                $config['num_tag_close']         = '</li>';
                
                $config['next_tag_open']         = '<li class="page-item">';
                $config['next_tag_close']        = '</li>';
                
                $config['last_tag_open']         = '<li class="page-item">';
                $config['last_tag_close']        = '</li>';
                
                $config['cur_tag_open']          = '<li class="page-item"><a href="#" class="page-link text-dark"><b>';
                $config['cur_tag_close']         = '</b></a></li>';
                
                $config['first_link']            = '&laquo;';
                $config['prev_link']             = '&lsaquo;';
                $config['next_link']             = '&rsaquo;';
                $config['last_link']             = '&raquo;';
                $config['anchor_class']          = 'class="page-link"';
            
                $sql_doc = $this->db->where('id', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
                
                switch ($case){
                    case 'per_tanggal':
                            $data['sql_mcu'] =  $this->db->select('tbl_m_pasien.id AS id_pasien, tbl_m_pasien.nama_pgl, tbl_trans_medcheck_resume.id, tbl_trans_medcheck_resume.id_medcheck, tbl_trans_medcheck_resume.id_user, tbl_trans_medcheck_resume.no_surat, tbl_trans_medcheck_resume.saran, tbl_trans_medcheck_resume.kesimpulan')
                                                         ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resume.id_medcheck')
                                                         ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                         ->where('tbl_trans_medcheck.tipe', '5')
                                                         ->where('DATE(tbl_trans_medcheck_resume.tgl_simpan)', $this->tanggalan->tgl_indo_sys($tgl))
//                                                         ->limit($config['per_page'])                          
                                                         ->get('tbl_trans_medcheck_resume')->result();
                            
                            $data['sql_mcu_cek_hdr'] =  $this->db->select('tbl_trans_medcheck_resume_det.id_resume, tbl_trans_medcheck_resume_det.param, COUNT(tbl_trans_medcheck_resume_det.id_resume)')
                                                                ->join('tbl_trans_medcheck_resume', 'tbl_trans_medcheck_resume.id=tbl_trans_medcheck_resume_det.id_resume')
                                                                ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resume_det.id_medcheck')
                                                                ->where('tbl_trans_medcheck.tipe', '5')
                                                                ->where('DATE(tbl_trans_medcheck_resume.tgl_simpan)', $this->tanggalan->tgl_indo_sys($tgl))
                                                                ->order_by('tbl_trans_medcheck_resume_det.id', 'DESC')
//                                                         ->where('id_resume', '1482')
                                                         ->get('tbl_trans_medcheck_resume_det')->row();
                            
                            $data['sql_mcu_hdr'] =  $this->db->select('tbl_trans_medcheck_resume_det.id, tbl_trans_medcheck_resume_det.id_resume, tbl_trans_medcheck_resume_det.id_medcheck, tbl_trans_medcheck_resume_det.param')
                                                         ->where('id_resume',  $data['sql_mcu_cek_hdr']->id_resume)
                                                         ->get('tbl_trans_medcheck_resume_det');
                        break;
                    
                    case 'per_rentang':
//                        if(!empty($hal)){
//                            $data['sql_mcu'] =  $this->db->select('tbl_m_pasien.id AS id_pasien, tbl_m_pasien.nama_pgl, tbl_trans_medcheck_resume.id, tbl_trans_medcheck_resume.id_medcheck, tbl_trans_medcheck_resume.id_user, tbl_trans_medcheck_resume.no_surat')
//                                                         ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resume.id_medcheck')
//                                                         ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
//                                                         ->where('tbl_trans_medcheck.tipe', '5')
//                                                         ->where('DATE(tbl_trans_medcheck_resume.tgl_simpan) >=', $this->tanggalan->tgl_indo_sys($tgl_awal))
//                                                         ->where('DATE(tbl_trans_medcheck_resume.tgl_simpan) <=', $this->tanggalan->tgl_indo_sys($tgl_akhir))
//                                                         ->limit($config['per_page'], $hal)                          
//                                                         ->get('tbl_trans_medcheck_resume')->result(); 
//                        }else{
                            $data['sql_mcu'] =  $this->db->select('tbl_m_pasien.id AS id_pasien, tbl_m_pasien.nama_pgl, tbl_trans_medcheck_resume.id, tbl_trans_medcheck_resume.id_medcheck, tbl_trans_medcheck_resume.id_user, tbl_trans_medcheck_resume.no_surat, tbl_trans_medcheck_resume.saran, tbl_trans_medcheck_resume.kesimpulan')
                                                         ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resume.id_medcheck')
                                                         ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                         ->where('tbl_trans_medcheck.tipe', '5')
                                                         ->where('DATE(tbl_trans_medcheck_resume.tgl_simpan) >=', $this->tanggalan->tgl_indo_sys($tgl_awal))
                                                         ->where('DATE(tbl_trans_medcheck_resume.tgl_simpan) <=', $this->tanggalan->tgl_indo_sys($tgl_akhir))
//                                                         ->limit($config['per_page'])                          
                                                         ->get('tbl_trans_medcheck_resume')->result();
                            
                            $data['sql_mcu_cek_hdr'] =  $this->db->select('tbl_trans_medcheck_resume_det.id_resume, tbl_trans_medcheck_resume_det.param, COUNT(tbl_trans_medcheck_resume_det.id_resume)')
                                                                ->join('tbl_trans_medcheck_resume', 'tbl_trans_medcheck_resume.id=tbl_trans_medcheck_resume_det.id_resume')
                                                                ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resume_det.id_medcheck')
                                                                ->where('tbl_trans_medcheck.tipe', '5')
                                                                ->where('DATE(tbl_trans_medcheck_resume.tgl_simpan) >=', $this->tanggalan->tgl_indo_sys($tgl_awal))
                                                                ->where('DATE(tbl_trans_medcheck_resume.tgl_simpan) <=', $this->tanggalan->tgl_indo_sys($tgl_akhir))
                                                                ->order_by('tbl_trans_medcheck_resume_det.id', 'DESC')
//                                                         ->where('id_resume', '1482')
                                                         ->get('tbl_trans_medcheck_resume_det')->row();
                            
                            $data['sql_mcu_hdr'] =  $this->db->select('tbl_trans_medcheck_resume_det.id, tbl_trans_medcheck_resume_det.id_resume, tbl_trans_medcheck_resume_det.id_medcheck, tbl_trans_medcheck_resume_det.param')
                                                         ->where('id_resume',  $data['sql_mcu_cek_hdr']->id_resume)
                                                         ->get('tbl_trans_medcheck_resume_det');
                            
//                            echo '<pre>';
//                            print_r( $data['sql_mcu_cek_hdr']->result());
//                            echo '</pre>';
                            
//                            $data['sql_mcu'] =  $this->db->select('tbl_m_pasien.id, tbl_m_pasien.nama_pgl, tbl_trans_medcheck_resume_det.id, tbl_trans_medcheck_resume_det.param, tbl_trans_medcheck_resume_det.param_nilai')
//                                                         ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resume_det.id_medcheck')
//                                                         ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
//                                                         ->where('tbl_trans_medcheck.tipe', '5')
//                                                         ->where('DATE(tbl_trans_medcheck_resume_det.tgl_simpan) >=', $this->tanggalan->tgl_indo_sys($tgl_awal))
//                                                         ->where('DATE(tbl_trans_medcheck_resume_det.tgl_simpan) <=', $this->tanggalan->tgl_indo_sys($tgl_akhir))
//                                                         ->limit($config['per_page'])                          
//                                                         ->get('tbl_trans_medcheck_resume_det')->result();                            
//                        }
                        break; 
                }
                
                // Initializing Config Pagination
                $this->pagination->initialize($config);
                
                // Pagination Data
                $data['total_rows'] = $config['total_rows'];
                $data['PerPage']    = $config['per_page'];
                $data['pagination'] = $this->pagination->create_links();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_mcu', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_omset(){
        if (akses::aksesLogin() == TRUE) {
            $dokter                 = $this->input->get('id_dokter');
            $poli                   = $this->input->get('poli');
            $tipe                   = $this->input->get('tipe');
            $plat                   = $this->input->get('plat');
            $jml                    = $this->input->get('jml');
            $tgl                    = $this->input->get('tgl');
            $tgl_awal               = $this->input->get('tgl_awal');
            $tgl_akhir              = $this->input->get('tgl_akhir');
            $case                   = $this->input->get('case');
            $hal                    = $this->input->get('halaman');
            $pasien_id              = $this->input->get('id_pasien');
            $pasien                 = $this->input->get('pasien');
            $pengaturan             = $this->db->get('tbl_pengaturan')->row();
            
            $data['sql_doc']        = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            $data['sql_poli']       = $this->db->get('tbl_m_poli')->result();
            $data['sql_plat']       = $this->db->get('tbl_m_platform')->result();
            $data['sql_penjamin']   = $this->db->where('status', '1')->get('tbl_m_penjamin')->result();
            
            if($jml > 0){
                $data['hasError'] = $this->session->flashdata('form_error');
                
                // Config Pagination
                $config['base_url']              = base_url('laporan/data_omset.php?case='.$case.(!empty($plat) ? '&plat='.$plat : '').(!empty($poli) ? '&poli='.$poli : '').(!empty($tipe) ? '&tipe='.$tipe : '').(!empty($tgl) ? '&tgl='.$tgl : '').(!empty($tgl_awal) ? '&tgl_awal='.$tgl_awal : '').(!empty($tgl_akhir) ? '&tgl_akhir='.$tgl_akhir : '').(!empty($jml) ? '&jml='.$jml : ''));
                $config['total_rows']            = $jml;
                
                $config['query_string_segment']  = 'halaman';
                $config['page_query_string']     = TRUE;
                $config['per_page']              = $pengaturan->jml_item;
                $config['num_links']             = 3;
                
                $config['first_tag_open']        = '<li class="page-item">';
                $config['first_tag_close']       = '</li>';
                
                $config['prev_tag_open']         = '<li class="page-item">';
                $config['prev_tag_close']        = '</li>';
                
                $config['num_tag_open']          = '<li class="page-item">';
                $config['num_tag_close']         = '</li>';
                
                $config['next_tag_open']         = '<li class="page-item">';
                $config['next_tag_close']        = '</li>';
                
                $config['last_tag_open']         = '<li class="page-item">';
                $config['last_tag_close']        = '</li>';
                
                $config['cur_tag_open']          = '<li class="page-item"><a href="#" class="page-link text-dark"><b>';
                $config['cur_tag_close']         = '</b></a></li>';
                
                $config['first_link']            = '&laquo;';
                $config['prev_link']             = '&lsaquo;';
                $config['next_link']             = '&rsaquo;';
                $config['last_link']             = '&raquo;';
                $config['anchor_class']          = 'class="page-link"';
            
                $sql_doc = $this->db->where('id', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
                
                switch ($case){
                    case 'per_tanggal':
                        $data['sql_omset']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_total, tbl_trans_medcheck.jml_diskon, tbl_trans_medcheck.jml_potongan, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.status_bayar, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi')
                                                          ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                          ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                          ->where('tbl_trans_medcheck.status_hps', '0')
                                                          ->where('tbl_trans_medcheck.status_bayar', '1')
                                                          ->where('DATE(tbl_trans_medcheck.tgl_bayar)', $tgl)
                                                          ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                          ->like('tbl_trans_medcheck.tipe_bayar', $tipe_byr, (!empty($tipe_byr) ? 'none' : ''))
                                                          ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                          ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                                          ->like('tbl_trans_medcheck.pasien', $pasien)
//                                                        ->limit($config['per_page'], $hal)
                                                          ->order_by('tbl_trans_medcheck.id', 'DESC') 
                                                          ->get('tbl_trans_medcheck')->result();
                        
                   
                        $data['sql_omset_row'] = $this->db->select('SUM(tbl_trans_medcheck_det.diskon) AS jml_diskon, SUM(tbl_trans_medcheck_det.potongan) AS jml_potongan, SUM(tbl_trans_medcheck_det.subtotal) AS jml_gtotal')
                                                          ->join('tbl_trans_medcheck_det', 'tbl_trans_medcheck_det.id_medcheck=tbl_trans_medcheck.id', 'left')
                                                          ->where('tbl_trans_medcheck.status_hps', '0')
                                                          ->where('tbl_trans_medcheck.status_bayar', '1')
                                                          ->where('DATE(tbl_trans_medcheck.tgl_bayar)', $tgl)
                                                          ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                          ->like('tbl_trans_medcheck.tipe_bayar', $tipe_byr, (!empty($tipe_byr) ? 'none' : ''))
                                                          ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                          ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                                          ->like('tbl_trans_medcheck.pasien', $pasien)
                                                          ->get('tbl_trans_medcheck')->row();
                        break;
                    
                    case 'per_rentang':
                        if(!empty($jml)){
                            $data['sql_omset']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_total, tbl_trans_medcheck.jml_diskon, tbl_trans_medcheck.jml_potongan, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.status_bayar, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
                                                              ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.tipe_bayar', $tipe_byr, (!empty($tipe_byr) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.pasien', $pasien, (!empty($pasien) ? 'none' : ''))
                                                              ->limit($config['per_page'], $hal)
                                                              ->order_by('tbl_trans_medcheck.id', 'DESC')
                                                              ->get('tbl_trans_medcheck')->result();
                        }else{
                            $data['sql_omset']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_diskon, tbl_trans_medcheck.jml_potongan, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.status_bayar, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
                                                              ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.tipe_bayar', $tipe_byr, (!empty($tipe_byr) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.pasien', $pasien, (!empty($pasien) ? 'none' : ''))
                                                              ->limit($config['per_page'])
                                                          ->order_by('tbl_trans_medcheck.id', 'DESC')
                                                          ->get('tbl_trans_medcheck')->result();               
                        }
                   
                        $data['sql_omset_row'] = $this->db->select('SUM(tbl_trans_medcheck_det.diskon) AS jml_diskon, SUM(tbl_trans_medcheck_det.potongan) AS jml_potongan, SUM(tbl_trans_medcheck_det.subtotal) AS jml_gtotal')
                                                          ->join('tbl_trans_medcheck_det', 'tbl_trans_medcheck_det.id_medcheck=tbl_trans_medcheck.id', 'right')
                                                          ->where('tbl_trans_medcheck.status_hps', '0')
                                                          ->where('tbl_trans_medcheck.status_bayar', '1')
                                                          ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
                                                          ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
                                                          ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                          ->like('tbl_trans_medcheck.tipe_bayar', $tipe_byr, (!empty($tipe_byr) ? 'none' : ''))
                                                          ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                          ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                                          ->like('tbl_trans_medcheck.pasien', $pasien)
                                                          ->get('tbl_trans_medcheck')->row();
                        break; 
                }
                
                // Initializing Config Pagination
                $this->pagination->initialize($config);
                
                // Pagination Data
                $data['total_rows'] = $config['total_rows'];
                $data['PerPage']    = $config['per_page'];
                $data['pagination'] = $this->pagination->create_links();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_omset', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_omset_poli(){
        if (akses::aksesLogin() == TRUE) {
            $case                   = $this->input->get('case');
            $hal                    = $this->input->get('halaman');
            $jml                    = $this->input->get('jml');
            $tgl                    = $this->input->get('tgl');
            $tgl_awal               = $this->input->get('tgl_awal');
            $tgl_akhir              = $this->input->get('tgl_akhir');
            $poli                   = $this->input->get('poli');
            $tipe                   = $this->input->get('tipe');
            $status                 = $this->input->get('status');
            $pengaturan             = $this->db->get('tbl_pengaturan')->row();
            $st                     = json_decode(general::dekrip($status));
            
            $data['sql_doc']        = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            $data['sql_poli']       = $this->db->get('tbl_m_poli')->result();
            $data['sql_plat']       = $this->db->get('tbl_m_platform')->result();
            $data['sql_penjamin']   = $this->db->where('status', '1')->get('tbl_m_penjamin')->result();
            
            if($jml > 0){
                $data['hasError'] = $this->session->flashdata('form_error');
                
//                // Config Pagination
//                $config['base_url']              = base_url('laporan/data_omset_poli.php?case='.$case.(!empty($tipe) ? '&tipe='.$tipe : '').(!empty($poli) ? '&poli='.$poli : '').(!empty($status) ? '&status='.$status : '').(!empty($tgl) ? '&tgl='.$tgl : '').(!empty($tgl_awal) ? '&tgl_awal='.$tgl_awal : '').(!empty($tgl_akhir) ? '&tgl_akhir='.$tgl_akhir : '').(!empty($jml) ? '&jml='.$jml : ''));
//                $config['total_rows']            = $jml;
//                
//                $config['query_string_segment']  = 'halaman';
//                $config['page_query_string']     = TRUE;
//                $config['per_page']              = $pengaturan->jml_item;
//                $config['num_links']             = 3;
//                
//                $config['first_tag_open']        = '<li class="page-item">';
//                $config['first_tag_close']       = '</li>';
//                
//                $config['prev_tag_open']         = '<li class="page-item">';
//                $config['prev_tag_close']        = '</li>';
//                
//                $config['num_tag_open']          = '<li class="page-item">';
//                $config['num_tag_close']         = '</li>';
//                
//                $config['next_tag_open']         = '<li class="page-item">';
//                $config['next_tag_close']        = '</li>';
//                
//                $config['last_tag_open']         = '<li class="page-item">';
//                $config['last_tag_close']        = '</li>';
//                
//                $config['cur_tag_open']          = '<li class="page-item"><a href="#" class="page-link text-dark"><b>';
//                $config['cur_tag_close']         = '</b></a></li>';
//                
//                $config['first_link']            = '&laquo;';
//                $config['prev_link']             = '&lsaquo;';
//                $config['next_link']             = '&rsaquo;';
//                $config['last_link']             = '&raquo;';
//                $config['anchor_class']          = 'class="page-link"';
                            
                switch ($case){
                    case 'per_tanggal':
                        if(!empty($jml)){
                            $data['sql_penj']     = $this->db
                                                         ->where('DATE(tgl_simpan)', $tgl)
                                                         ->where_in('status', $st)
                                                         ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                         ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                         ->group_by('id_pasien')
//                                                         ->limit($config['per_page'], $hal)
                                                         ->get('v_medcheck_omset')->result();
                        }else{
                            $data['sql_penj']     = $this->db
                                                         ->where('DATE(tgl_simpan)', $tgl)
                                                         ->where_in('status', $st)
                                                         ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                         ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                         ->group_by('id_pasien')
//                                                         ->limit($config['per_page'])
                                                         ->get('v_medcheck_omset')->result();                        
                        }
                        
                        $data['sql_oms_tot'] = $this->db->select('SUM(subtotal) AS jml_gtotal')
                                                        ->where('DATE(tgl_simpan)', $tgl)
                                                        ->where_in('status', $st)
                                                        ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                        ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                        ->group_by('id_pasien')
                                                        ->get('v_medcheck_omset')->row();
                        break;
                    
                    case 'per_rentang':
                        if(!empty($jml)){
                            $data['sql_penj']     = $this->db
                                                         ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                                         ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                                         ->where_in('status', $st)
                                                         ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                         ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                         ->group_by('id_pasien')
//                                                         ->limit($config['per_page'], $hal)
                                                         ->get('v_medcheck_omset')->result();
                        }else{
                            $data['sql_penj']     = $this->db
                                                         ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                                         ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                                         ->where_in('status', $st)
                                                         ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                         ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                         ->group_by('id_pasien')
//                                                         ->limit($config['per_page'])
                                                         ->get('v_medcheck_omset')->result();
                        }
                        
                        $data['sql_oms_tot'] = $this->db->select('SUM(jml_gtotal) AS jml_gtotal')
                                                        ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                                        ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                                        ->where_in('status', $st)
                                                        ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                        ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                        ->group_by('id_pasien')
                                                        ->get('v_medcheck_omset')->row();
                        break;
                }
                
                // Initializing Config Pagination
//                $this->pagination->initialize($config);

                // Pagination Data
                $data['total_rows'] = $config['total_rows'];
                $data['PerPage']    = $config['per_page'];
                $data['pagination'] = $this->pagination->create_links();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_omset_poli', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
            
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_omset_detail(){
        if (akses::aksesLogin() == TRUE) {
            $dokter             = $this->input->get('id_dokter');
            $poli               = $this->input->get('poli');
            $tipe               = $this->input->get('tipe');
            $status             = $this->input->get('status');
            $jml                = $this->input->get('jml');
            $tgl                = $this->input->get('tgl');
            $tgl_awal           = $this->input->get('tgl_awal');
            $tgl_akhir          = $this->input->get('tgl_akhir');
            $case               = $this->input->get('case');
            $hal                = $this->input->get('halaman');
            
            $pengaturan         = $this->db->get('tbl_pengaturan')->row();
            
            $data['sql_doc']    = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            $data['sql_poli']   = $this->db->get('tbl_m_poli')->result();
            $data['sql_plat']   = $this->db->get('tbl_m_platform')->result();
            
            if($jml > 0){
                $data['hasError'] = $this->session->flashdata('form_error');
                
                // Config Pagination
                $config['base_url']              = base_url('laporan/data_omset_detail.php?case='.$case.(!empty($tipe) ? '&tipe='.$tipe : '').(!empty($poli) ? '&poli='.$poli : '').(!empty($status) ? '&status='.$status : '').(!empty($tgl) ? '&tgl='.$tgl : '').(!empty($tgl_awal) ? '&tgl_awal='.$tgl_awal : '').(!empty($tgl_akhir) ? '&tgl_akhir='.$tgl_akhir : '').(!empty($jml) ? '&jml='.$jml : ''));
                $config['total_rows']            = $jml;
                
                $config['query_string_segment']  = 'halaman';
                $config['page_query_string']     = TRUE;
                $config['per_page']              = $pengaturan->jml_item;
                $config['num_links']             = 3;
                
                $config['first_tag_open']        = '<li class="page-item">';
                $config['first_tag_close']       = '</li>';
                
                $config['prev_tag_open']         = '<li class="page-item">';
                $config['prev_tag_close']        = '</li>';
                
                $config['num_tag_open']          = '<li class="page-item">';
                $config['num_tag_close']         = '</li>';
                
                $config['next_tag_open']         = '<li class="page-item">';
                $config['next_tag_close']        = '</li>';
                
                $config['last_tag_open']         = '<li class="page-item">';
                $config['last_tag_close']        = '</li>';
                
                $config['cur_tag_open']          = '<li class="page-item"><a href="#" class="page-link text-dark"><b>';
                $config['cur_tag_close']         = '</b></a></li>';
                
                $config['first_link']            = '&laquo;';
                $config['prev_link']             = '&lsaquo;';
                $config['next_link']             = '&rsaquo;';
                $config['last_link']             = '&raquo;';
                $config['anchor_class']          = 'class="page-link"';
            
                $sql_doc = $this->db->where('id', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
                
                switch ($case){
                    case 'per_tanggal':
                        if(!empty($jml)){
                            $data['sql_penj']     = $this->db
                                                         ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                         ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                         ->like('status', $status, (!empty($status) ? 'none' : ''))
                                                         ->where('DATE(tgl_simpan)', $tgl)
                                                         ->limit($config['per_page'], $hal)
                                                         ->get('v_medcheck_omset')->result();
                        }else{
                            $data['sql_penj']     = $this->db
                                                         ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                         ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                         ->like('status', $status, (!empty($status) ? 'none' : ''))
                                                         ->where('DATE(tgl_simpan)', $tgl)
                                                         ->limit($config['per_page'])
                                                         ->get('v_medcheck_omset')->result();                        
                        }
                    
                        $data['sql_omset_pas'] = $this->db->select('SUM(subtotal) as subtotal')
                                                          ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                          ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                          ->like('status', $status, (!empty($status) ? 'none' : ''))
                                                          ->where('DATE(tgl_simpan)', $tgl)
                                                          ->group_by('nama_pgl')
                                                          ->get('v_medcheck_omset');
                        break;
                    
                    case 'per_rentang':
                        if(!empty($hal)){
                            $data['sql_penj']     = $this->db->select('SUM(subtotal) as subtotal')
                                                         ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                         ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                         ->like('status', $status, (!empty($status) ? 'none' : ''))
                                                         ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                                         ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                                         ->limit($config['per_page'], $hal)
                                                         ->get('v_medcheck_omset')->result();
                        }else{
                            $data['sql_penj']     = $this->db
                                                         ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                         ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                         ->like('status', $status, (!empty($status) ? 'none' : ''))
                                                         ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                                         ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                                         ->limit($config['per_page'])
                                                         ->get('v_medcheck_omset')->result();
                        }
                    
                        $data['sql_omset_pas'] = $this->db
                                                        ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                        ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                        ->like('status', $status, (!empty($status) ? 'none' : ''))
                                                        ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                                        ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                                        ->group_by('nama_pgl')
                                                        ->get('v_medcheck_omset');
                        break;
                }
                
                // Initializing Config Pagination
                $this->pagination->initialize($config);

                // Pagination Data
                $data['total_rows'] = $config['total_rows'];
                $data['PerPage']    = $config['per_page'];
                $data['pagination'] = $this->pagination->create_links();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_omset_detail', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_omset_jasa(){
        if (akses::aksesLogin() == TRUE) {
            $dokter             = $this->input->get('id_dokter');
            $poli               = $this->input->get('poli');
            $plat               = $this->input->get('plat');
            $jml                = $this->input->get('jml');
            $tgl                = $this->input->get('tgl');
            $tgl_awal           = $this->input->get('tgl_awal');
            $tgl_akhir          = $this->input->get('tgl_akhir');
            $case               = $this->input->get('case');
            $hal                = $this->input->get('halaman');
            $pasien_id          = $this->input->get('id_pasien');
            $pasien             = $this->input->get('pasien');
            $pengaturan         = $this->db->get('tbl_pengaturan')->row();
            
            $data['sql_doc']    = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            $data['sql_poli']   = $this->db->get('tbl_m_poli')->result();
            $data['sql_plat']   = $this->db->get('tbl_m_platform')->result();
            
            if($jml > 0){
                $data['hasError'] = $this->session->flashdata('form_error');
                
                // Config Pagination
                $config['base_url']              = base_url('laporan/data_stok_keluar.php?case='.$case.(!empty($tgl) ? '&tgl='.$tgl : '').(!empty($poli) ? '&poli='.$poli : '').(!empty($tgl_awal) ? '&tgl_awal='.$tgl_awal : '').(!empty($tgl_akhir) ? '&tgl_akhir='.$tgl_akhir : '').(!empty($jml) ? '&jml='.$jml : ''));
                $config['total_rows']            = $jml;
                
                $config['query_string_segment']  = 'halaman';
                $config['page_query_string']     = TRUE;
                $config['per_page']              = $pengaturan->jml_item;
                $config['num_links']             = 3;
                
                $config['first_tag_open']        = '<li class="page-item">';
                $config['first_tag_close']       = '</li>';
                
                $config['prev_tag_open']         = '<li class="page-item">';
                $config['prev_tag_close']        = '</li>';
                
                $config['num_tag_open']          = '<li class="page-item">';
                $config['num_tag_close']         = '</li>';
                
                $config['next_tag_open']         = '<li class="page-item">';
                $config['next_tag_close']        = '</li>';
                
                $config['last_tag_open']         = '<li class="page-item">';
                $config['last_tag_close']        = '</li>';
                
                $config['cur_tag_open']          = '<li class="page-item"><a href="#" class="page-link text-dark"><b>';
                $config['cur_tag_close']         = '</b></a></li>';
                
                $config['first_link']            = '&laquo;';
                $config['prev_link']             = '&lsaquo;';
                $config['next_link']             = '&rsaquo;';
                $config['last_link']             = '&raquo;';
                $config['anchor_class']          = 'class="page-link"';
            
                $sql_doc = $this->db->where('id', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
                
                switch ($case){
                    case 'per_tanggal':
                        if(!empty($jml)){
                            $data['sql_penj']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar)', $tgl)
                                                              ->where('tbl_trans_medcheck_det.status', '2')
                                                              ->like('tbl_trans_medcheck.pasien', $pasien)
                                                              ->like('tbl_trans_medcheck.tipe', $poli, (!empty($poli) ? 'none' : '')) 
                                                              ->limit($config['per_page'], $hal)
                                                          ->get('tbl_trans_medcheck_det')->result(); 
                        }else{
                            $data['sql_penj']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar)', $tgl)
                                                              ->where('tbl_trans_medcheck_det.status', '2')
                                                              ->like('tbl_trans_medcheck.pasien', $pasien)
                                                              ->like('tbl_trans_medcheck.tipe', $poli, (!empty($poli) ? 'none' : '')) 
                                                              ->limit($config['per_page'])
                                                          ->get('tbl_trans_medcheck_det')->result();                            
                        }
                        break;
                    
                    case 'per_rentang':
                        if(!empty($hal)){
                            $data['sql_penj']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
                                                              ->where('tbl_trans_medcheck_det.status', '2')
                                                              ->like('tbl_trans_medcheck.pasien', $pasien)
                                                              ->like('tbl_trans_medcheck.tipe', $poli, (!empty($poli) ? 'none' : '')) 
                                                              ->order_by('tbl_trans_medcheck_det.tgl_simpan', 'ASC')
                                                              ->limit($config['per_page'], $hal)
                                                          ->get('tbl_trans_medcheck_det')->result(); 
                        }else{
                            $data['sql_penj']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
                                                              ->where('tbl_trans_medcheck_det.status', '2')
                                                              ->like('tbl_trans_medcheck.pasien', $pasien)
                                                              ->like('tbl_trans_medcheck.tipe', $poli, (!empty($poli) ? 'none' : '')) 
                                                              ->order_by('tbl_trans_medcheck_det.tgl_simpan', 'ASC')
                                                              ->limit($config['per_page'])
                                                          ->get('tbl_trans_medcheck_det')->result();
                        }
                        break;
                }
                
                // Initializing Config Pagination
                $this->pagination->initialize($config);

                // Pagination Data
                $data['total_rows'] = $config['total_rows'];
                $data['PerPage']    = $config['per_page'];
                $data['pagination'] = $this->pagination->create_links();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_omset_jasa', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_omset_dokter(){
        if (akses::aksesLogin() == TRUE) {
            $dokter             = $this->input->get('id_dokter');
            $poli               = $this->input->get('poli');
            $plat               = $this->input->get('plat');
            $jml                = $this->input->get('jml');
            $tgl                = $this->input->get('tgl');
            $tgl_awal           = $this->input->get('tgl_awal');
            $tgl_akhir          = $this->input->get('tgl_akhir');
            $case               = $this->input->get('case');
            $hal                = $this->input->get('halaman');
            $dokter             = $this->input->get('dokter');
            $pengaturan         = $this->db->get('tbl_pengaturan')->row();
            
            if(!empty($jml)){
                $jml_hal = $jml;
            }else{
                $jml_hal = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal')
                                ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                ->where('tbl_trans_medcheck.status_bayar', '1')
                                ->where('tbl_trans_medcheck_det.id_dokter', general::dekrip($dokter))
                                ->where('DATE(tbl_trans_medcheck_det.tgl_masuk) >=', $tgl_awal)
                                ->where('DATE(tbl_trans_medcheck_det.tgl_masuk) <=', $tgl_akhir)
                                ->where('tbl_trans_medcheck_det.status !=', '4')
                                ->order_by('tbl_trans_medcheck_det.tgl_simpan', 'ASC')
                                ->get('tbl_trans_medcheck_det')->num_rows();
            }
            
            $data['sql_doc']    = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            $data['sql_poli']   = $this->db->get('tbl_m_poli')->result();
            $data['sql_plat']   = $this->db->get('tbl_m_platform')->result();
            
            $data['hasError'] = $this->session->flashdata('form_error');
            
            // Config Pagination
            $config['base_url']              = base_url('laporan/data_omset_dokter.php?case='.$case.(!empty($dokter) ? '&dokter='.$dokter : '').(!empty($tgl) ? '&tgl='.$tgl : '').(!empty($tgl_awal) ? '&tgl_awal='.$tgl_awal : '').(!empty($tgl_akhir) ? '&tgl_akhir='.$tgl_akhir : '').(!empty($jml_hal) ? '&jml='.$jml_hal : ''));
            $config['total_rows']            = $jml_hal;
            
            $config['query_string_segment']  = 'halaman';
            $config['page_query_string']     = TRUE;
            $config['per_page']              = $pengaturan->jml_item;
            $config['num_links']             = 3;
            
            $config['first_tag_open']        = '<li class="page-item">';
            $config['first_tag_close']       = '</li>';
            
            $config['prev_tag_open']         = '<li class="page-item">';
            $config['prev_tag_close']        = '</li>';
            
            $config['num_tag_open']          = '<li class="page-item">';
            $config['num_tag_close']         = '</li>';
            
            $config['next_tag_open']         = '<li class="page-item">';
            $config['next_tag_close']        = '</li>';
            
            $config['last_tag_open']         = '<li class="page-item">';
            $config['last_tag_close']        = '</li>';
            
            $config['cur_tag_open']          = '<li class="page-item"><a href="#" class="page-link text-dark"><b>';
            $config['cur_tag_close']         = '</b></a></li>';
            
            $config['first_link']            = '&laquo;';
            $config['prev_link']             = '&lsaquo;';
            $config['next_link']             = '&rsaquo;';
            $config['last_link']             = '&raquo;';
            $config['anchor_class']          = 'class="page-link"';
        
            $sql_doc = $this->db->where('id', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
            
            switch ($case){
                case 'per_tanggal':
                    if(!empty($jml)){
                        $data['sql_penj']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal')
                                                          ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                          ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                          ->where('tbl_trans_medcheck.status_bayar', '1')
                                                          ->where('DATE(tbl_trans_medcheck.tgl_bayar)', $tgl)
                                                          ->where('tbl_trans_medcheck_det.status', '2')
                                                          ->like('tbl_trans_medcheck.pasien', $pasien)
                                                          ->like('tbl_trans_medcheck.tipe', $poli, (!empty($poli) ? 'none' : '')) 
                                                          ->limit($config['per_page'], $hal)
                                                      ->get('tbl_trans_medcheck_det')->result(); 
                    }else{
                        $data['sql_penj']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal')
                                                          ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                          ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                          ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                          ->where('tbl_trans_medcheck.status_bayar', '1')
                                                          ->where('tbl_trans_medcheck_det.id_dokter', general::dekrip($dokter))
                                                          ->where('DATE(tbl_trans_medcheck_det.tgl_masuk)', $tgl)
                                                          ->where('tbl_trans_medcheck_det.status !=', '4')
                                                          ->order_by('tbl_trans_medcheck_det.tgl_simpan', 'ASC')
                                                          ->limit($config['per_page'])
                                                        ->get('tbl_trans_medcheck_det')->result();                          
                    }
                    break;
                
                case 'per_rentang':
                    if(!empty($hal)){
                        $data['sql_penj']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal')
                                                          ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                          ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                          ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                          ->where('tbl_trans_medcheck.status_bayar', '1')
                                                          ->where('tbl_trans_medcheck_det.id_dokter', general::dekrip($dokter))
                                                          ->where('DATE(tbl_trans_medcheck_det.tgl_masuk) >=', $tgl_awal)
                                                          ->where('DATE(tbl_trans_medcheck_det.tgl_masuk) <=', $tgl_akhir)
                                                          ->where('tbl_trans_medcheck_det.status !=', '4')
                                                          ->order_by('tbl_trans_medcheck_det.tgl_simpan', 'ASC')
                                                          ->limit($config['per_page'], $hal)
                                                        ->get('tbl_trans_medcheck_det')->result(); 
                    }else{
                        $data['sql_penj']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal')
                                                          ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                          ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                          ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                          ->where('tbl_trans_medcheck.status_bayar', '1')
                                                          ->where('tbl_trans_medcheck_det.id_dokter', general::dekrip($dokter))
                                                          ->where('DATE(tbl_trans_medcheck_det.tgl_masuk) >=', $tgl_awal)
                                                          ->where('DATE(tbl_trans_medcheck_det.tgl_masuk) <=', $tgl_akhir)
                                                          ->where('tbl_trans_medcheck_det.status !=', '4')
                                                          ->order_by('tbl_trans_medcheck_det.tgl_simpan', 'ASC')
                                                          ->limit($config['per_page'])
                                                      ->get('tbl_trans_medcheck_det')->result();
                    }
                    break;
            }
            
            // Initializing Config Pagination
            $this->pagination->initialize($config);
//
            // Pagination Data
            $data['total_rows'] = $config['total_rows'];
            $data['PerPage']    = $config['per_page'];
            $data['pagination'] = $this->pagination->create_links();
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_omset_dokter', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_pembelian(){
        if (akses::aksesLogin() == TRUE) {
            $supplier   = $this->input->get('id_supplier');
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
            $data['sql_supp']      = $this->db->get('tbl_m_supplier')->result();
//            $data['sql_supp_rw']   = $this->db->where('id', general::dekrip($supplier))->get('tbl_m_supplier')->row();
            
            if($jml > 0){
                $data['hasError'] = $this->session->flashdata('form_error');
                
                // Config Pagination
                $config['base_url']              = base_url('laporan/data_pembelian.php?case='.$case.(!empty($tgl) ? '&tgl='.$tgl : '').(!empty($tgl_awal) ? '&tgl_awal='.$tgl_awal : '').(!empty($tgl_akhir) ? '&tgl_akhir='.$tgl_akhir : '').(!empty($jml) ? '&jml='.$jml : ''));
                $config['total_rows']            = $jml;
                
                $config['query_string_segment']  = 'halaman';
                $config['page_query_string']     = TRUE;
                $config['per_page']              = $pengaturan->jml_item;
                $config['num_links']             = 3;
                
                $config['first_tag_open']        = '<li class="page-item">';
                $config['first_tag_close']       = '</li>';
                
                $config['prev_tag_open']         = '<li class="page-item">';
                $config['prev_tag_close']        = '</li>';
                
                $config['num_tag_open']          = '<li class="page-item">';
                $config['num_tag_close']         = '</li>';
                
                $config['next_tag_open']         = '<li class="page-item">';
                $config['next_tag_close']        = '</li>';
                
                $config['last_tag_open']         = '<li class="page-item">';
                $config['last_tag_close']        = '</li>';
                
                $config['cur_tag_open']          = '<li class="page-item"><a href="#" class="page-link text-dark"><b>';
                $config['cur_tag_close']         = '</b></a></li>';
                
                $config['first_link']            = '&laquo;';
                $config['prev_link']             = '&lsaquo;';
                $config['next_link']             = '&rsaquo;';
                $config['last_link']             = '&raquo;';
                $config['anchor_class']          = 'class="page-link"';
            
                $sql_supp = $this->db->where('id', general::dekrip($supplier))->get('tbl_m_supplier')->row();
                                
                switch ($case){
                    case 'per_tanggal':
                        if(!empty($hal)){
                            $data['sql_pembelian']     = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.tgl_masuk, tbl_trans_beli.no_nota, tbl_trans_beli.jml_dpp, tbl_trans_beli.ppn, tbl_trans_beli.jml_ppn, tbl_trans_beli.jml_diskon, tbl_trans_beli.jml_gtotal, tbl_m_supplier.nama')
                                                              ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli.id_supplier')
                                                              ->where('DATE(tbl_trans_beli.tgl_masuk)', $this->tanggalan->tgl_indo_sys($tgl))
                                                              ->like('tbl_m_supplier.nama', $sql_supp->nama)
                                                              ->limit($config['per_page'], $hal)
                                                              ->get('tbl_trans_beli')->result();                           
                        }else{
                            $data['sql_pembelian']     = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.tgl_masuk, tbl_trans_beli.no_nota, tbl_trans_beli.jml_dpp, tbl_trans_beli.ppn, tbl_trans_beli.jml_ppn, tbl_trans_beli.jml_diskon, tbl_trans_beli.jml_gtotal, tbl_m_supplier.nama')
                                                              ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli.id_supplier')
                                                              ->where('DATE(tbl_trans_beli.tgl_masuk)', $this->tanggalan->tgl_indo_sys($tgl))
                                                              ->like('tbl_m_supplier.nama', $sql_supp->nama)
                                                              ->limit($config['per_page'])
                                                              ->get('tbl_trans_beli')->result();
                        }
                        break;
                    
                    case 'per_rentang':
                        if(!empty($hal)){
                            $data['sql_pembelian']     = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.tgl_masuk, tbl_trans_beli.no_nota, tbl_trans_beli.jml_dpp, tbl_trans_beli.ppn, tbl_trans_beli.jml_ppn, tbl_trans_beli.jml_diskon, tbl_trans_beli.jml_gtotal, tbl_m_supplier.nama')
                                                              ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli.id_supplier')
                                                              ->where('DATE(tbl_trans_beli.tgl_masuk) >=', $this->tanggalan->tgl_indo_sys($tgl_awal))
                                                              ->where('DATE(tbl_trans_beli.tgl_masuk) <=', $this->tanggalan->tgl_indo_sys($tgl_akhir))
                                                              ->like('tbl_m_supplier.nama', $sql_supp->nama)
                                                              ->limit($config['per_page'], $hal)
                                                              ->get('tbl_trans_beli')->result();                             
                        }else{
                            $data['sql_pembelian']     = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.tgl_masuk, tbl_trans_beli.no_nota, tbl_trans_beli.jml_dpp, tbl_trans_beli.ppn, tbl_trans_beli.jml_ppn, tbl_trans_beli.jml_diskon, tbl_trans_beli.jml_gtotal, tbl_m_supplier.nama')
                                                              ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli.id_supplier')
                                                              ->where('DATE(tbl_trans_beli.tgl_masuk) >=', $this->tanggalan->tgl_indo_sys($tgl_awal))
                                                              ->where('DATE(tbl_trans_beli.tgl_masuk) <=', $this->tanggalan->tgl_indo_sys($tgl_akhir))
                                                              ->like('tbl_m_supplier.nama', $sql_supp->nama)
                                                              ->limit($config['per_page'])
                                                              ->get('tbl_trans_beli')->result();  
                        }
                        break;
                }
                
                # Initializing Config Pagination
                $this->pagination->initialize($config);
                
                # Pagination Data
                $data['total_rows'] = $config['total_rows'];
                $data['PerPage']    = $config['per_page'];
                $data['pagination'] = $this->pagination->create_links();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_pembelian', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_stok(){
        if (akses::aksesLogin() == TRUE) {
            $dokter     = $this->input->get('id_dokter');
            $jml        = $this->input->get('jml');
            $hal        = $this->input->get('halaman');
            $case       = $this->input->get('case');
            $stok       = $this->input->get('stok');
            $tipe       = $this->input->get('tipe');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
            if($jml > 0){
                $data['hasError'] = $this->session->flashdata('form_error');
                
                # Config Pagination
                $config['base_url']              = base_url('laporan/data_stok.php?tipe='.$tipe.(!empty($stok) ? '&stok='.$stok : '').(!empty($jml) ? '&jml='.$jml : ''));
                $config['total_rows']            = $jml;
                
                $config['query_string_segment']  = 'halaman';
                $config['page_query_string']     = TRUE;
                $config['per_page']              = $pengaturan->jml_item;
                $config['num_links']             = 3;
                
                $config['first_tag_open']        = '<li class="page-item">';
                $config['first_tag_close']       = '</li>';
                
                $config['prev_tag_open']         = '<li class="page-item">';
                $config['prev_tag_close']        = '</li>';
                
                $config['num_tag_open']          = '<li class="page-item">';
                $config['num_tag_close']         = '</li>';
                
                $config['next_tag_open']         = '<li class="page-item">';
                $config['next_tag_close']        = '</li>';
                
                $config['last_tag_open']         = '<li class="page-item">';
                $config['last_tag_close']        = '</li>';
                
                $config['cur_tag_open']          = '<li class="page-item"><a href="#" class="page-link text-dark"><b>';
                $config['cur_tag_close']         = '</b></a></li>';
                
                $config['first_link']            = '&laquo;';
                $config['prev_link']             = '&lsaquo;';
                $config['next_link']             = '&rsaquo;';
                $config['last_link']             = '&raquo;';
                $config['anchor_class']          = 'class="page-link"';
                       

                switch ($tipe) {
                    case '0' :
                        $st = '<';
                
                        if (isset($jml)) {
                            $data['sql_stok'] = $this->db->select('*')
                                            ->where('status_subt', '0')
                                            ->limit($config['per_page'], $hal)
                                            ->get('tbl_m_produk')->result();
                        } else {
                            $data['sql_stok'] = $this->db->select('*')
                                            ->where('status_subt', '0')
                                            ->limit($config['per_page'])
                                            ->get('tbl_m_produk')->result();
                        }
                        break;
                        
                    case '1' :
                        $st = '<';
                
                        if (isset($jml)) {
                            $data['sql_stok'] = $this->db->select('*')
                                            ->where('status_subt', '1')
                                            ->limit($config['per_page'], $hal)
                                            ->get('tbl_m_produk')->result();
                        } else {
                            $data['sql_stok'] = $this->db->select('*')
                                            ->where('status_subt', '1')
                                            ->limit($config['per_page'])
                                            ->get('tbl_m_produk')->result();
                        }
                        break;

                    case '2' :
                        $st = '';
                
                        if (isset($jml)) {
                            $data['sql_stok'] = $this->db->select('*')
                                            ->limit($config['per_page'], $hal)
                                            ->get('tbl_m_produk')->result();
                        } else {
                            $data['sql_stok'] = $this->db->select('*')
                                            ->limit($config['per_page'])
                                            ->get('tbl_m_produk')->result();
                        }
                        break;
                }

                // Initializing Config Pagination
                $this->pagination->initialize($config);
                
                // Pagination Data
                $data['total_rows'] = $config['total_rows'];
                $data['PerPage']    = $config['per_page'];
                $data['pagination'] = $this->pagination->create_links();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_stok', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_stok_masuk(){
        if (akses::aksesLogin() == TRUE) {
            $dokter     = $this->input->get('id_dokter');
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
            $data['sql_doc'] = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            
            if($jml > 0){
                $data['hasError'] = $this->session->flashdata('form_error');
                
                // Config Pagination
                $config['base_url']              = base_url('laporan/data_stok_masuk.php?case='.$case.(!empty($tgl) ? '&tgl='.$tgl : '').(!empty($tgl_awal) ? '&tgl_awal='.$tgl_awal : '').(!empty($tgl_akhir) ? '&tgl_akhir='.$tgl_akhir : '').(!empty($jml) ? '&jml='.$jml : ''));
                $config['total_rows']            = $jml;
                
                $config['query_string_segment']  = 'halaman';
                $config['page_query_string']     = TRUE;
                $config['per_page']              = $pengaturan->jml_item;
                $config['num_links']             = 3;
                
                $config['first_tag_open']        = '<li class="page-item">';
                $config['first_tag_close']       = '</li>';
                
                $config['prev_tag_open']         = '<li class="page-item">';
                $config['prev_tag_close']        = '</li>';
                
                $config['num_tag_open']          = '<li class="page-item">';
                $config['num_tag_close']         = '</li>';
                
                $config['next_tag_open']         = '<li class="page-item">';
                $config['next_tag_close']        = '</li>';
                
                $config['last_tag_open']         = '<li class="page-item">';
                $config['last_tag_close']        = '</li>';
                
                $config['cur_tag_open']          = '<li class="page-item"><a href="#" class="page-link text-dark"><b>';
                $config['cur_tag_close']         = '</b></a></li>';
                
                $config['first_link']            = '&laquo;';
                $config['prev_link']             = '&lsaquo;';
                $config['next_link']             = '&rsaquo;';
                $config['last_link']             = '&raquo;';
                $config['anchor_class']          = 'class="page-link"';
            
                $sql_doc = $this->db->where('id', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
                
                switch ($case){
                    case 'per_tanggal':
                        if(!empty($jml)){
                            $data['sql_penj']     = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.tgl_simpan, tbl_trans_beli.no_rm, tbl_trans_beli.no_nota, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_trans_beli_det.id AS id_beli_det, tbl_trans_beli_det.kode, tbl_trans_beli_det.item, tbl_trans_beli_det.harga, tbl_trans_beli_det.jml, tbl_trans_beli_det.subtotal')
                                                              ->join('tbl_trans_beli', 'tbl_trans_beli.id=tbl_trans_beli_det.id_beli')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_beli.id_pasien')
                                                              ->where('tbl_trans_beli.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_beli.tgl_masuk)', $tgl)
                                                              ->where('tbl_trans_beli_det.status', '4')
                                                              ->limit($config['per_page'], $hal)
                                                          ->get('tbl_trans_beli_det')->result(); 
                        }else{
                            $data['sql_penj']     = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.tgl_simpan, tbl_trans_beli.no_rm, tbl_trans_beli.no_nota, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_trans_beli_det.id AS id_beli_det, tbl_trans_beli_det.kode, tbl_trans_beli_det.item, tbl_trans_beli_det.harga, tbl_trans_beli_det.jml, tbl_trans_beli_det.subtotal')
                                                              ->join('tbl_trans_beli', 'tbl_trans_beli.id=tbl_trans_beli_det.id_beli')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_beli.id_pasien')
                                                              ->where('tbl_trans_beli.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_beli.tgl_masuk)', $tgl)
                                                              ->where('tbl_trans_beli_det.status', '4')
                                                              ->limit($config['per_page'])
                                                          ->get('tbl_trans_beli_det')->result();                            
                        }
                        break;
                    
                    case 'per_rentang':
                        if(!empty($jml)){
                            $data['sql_penj']     = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.tgl_simpan, tbl_trans_beli.no_nota, tbl_trans_beli.supplier, tbl_trans_beli_det.id AS id_beli_det, tbl_trans_beli_det.kode, tbl_trans_beli_det.produk AS item, tbl_trans_beli_det.harga, tbl_trans_beli_det.jml, tbl_trans_beli_det.satuan, tbl_trans_beli_det.subtotal')
                                                              ->join('tbl_trans_beli', 'tbl_trans_beli.id=tbl_trans_beli_det.id_pembelian')
                                                              ->where('DATE(tbl_trans_beli.tgl_masuk) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_beli.tgl_masuk) <=', $tgl_akhir)
                                                              ->limit($config['per_page'], $hal)
                                                          ->get('tbl_trans_beli_det')->result(); 
                        }else{
                            $data['sql_penj']     = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.tgl_simpan, tbl_trans_beli.no_nota, tbl_trans_beli.supplier, tbl_trans_beli_det.id AS id_beli_det, tbl_trans_beli_det.kode, tbl_trans_beli_det.produk AS item, tbl_trans_beli_det.harga, tbl_trans_beli_det.jml, tbl_trans_beli_det.satuan, tbl_trans_beli_det.subtotal')
                                                              ->join('tbl_trans_beli', 'tbl_trans_beli.id=tbl_trans_beli_det.id_pembelian')
                                                              ->where('DATE(tbl_trans_beli.tgl_masuk) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_beli.tgl_masuk) <=', $tgl_akhir)
                                                              ->limit($config['per_page'])
                                                          ->get('tbl_trans_beli_det')->result();
                        }
                        break;
                }
                
                // Initializing Config Pagination
                $this->pagination->initialize($config);
                
                // Pagination Data
                $data['total_rows'] = $config['total_rows'];
                $data['PerPage']    = $config['per_page'];
                $data['pagination'] = $this->pagination->create_links();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_stok_masuk', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_stok_telusur(){
        if (akses::aksesLogin() == TRUE) {
            $dokter     = $this->input->get('id_dokter');
            $id         = $this->input->get('id');
            $id_gdg     = $this->input->get('id_gudang');
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('act');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
            $data['sql_item']       = $this->db->where('status_subt', '1')->get('tbl_m_produk')->result();
            $data['sql_gudang']     = $this->db->get('tbl_m_gudang')->result();
            
            if(!empty($id)){
                $data['sql_gudang_rw']  = $this->db->where('id', $id_gdg)->get('tbl_m_gudang')->row();
                $data['sql_stok']       = $this->db->where('id', general::dekrip($id))->get('tbl_m_produk')->row();

                switch ($case){
                    case 'per_tanggal':
                        $data['gd_aktif']   = $this->db->where('id', $id_gdg)->get('tbl_m_gudang')->row();
                        $data['stok_so']    = $this->db->where('id_produk', $data['sql_stok']->id)->where('status', '6')->where('id_gudang', $id_gdg)->where('DATE(tgl_simpan) <=', $tgl)->order_by('id', 'DESC')->get('tbl_m_produk_hist')->row();
                        $data['stok_mts']   = $this->db->where('id_produk', $data['sql_stok']->id)->where('status', '8')->where('DATE(tgl_simpan) <=', $tgl)->limit(1)->order_by('id', 'DESC')->get('tbl_m_produk_hist')->row();
                        $data['stok_msk']   = $this->db->select_sum('jml')->where('id_produk', $data['sql_stok']->id)->where('status', '1')->where('DATE(tgl_simpan)', $tgl)->get('tbl_m_produk_hist')->row()->jml;
                        $data['stok_klr']   = $this->db->select_sum('jml')->where('id_produk', $data['sql_stok']->id)->where('status', '4')->where('DATE(tgl_simpan)', $tgl)->get('tbl_m_produk_hist')->row()->jml;

                        $data['tot_msk']    = ($data['gd_aktif']->status == '1' ? $data['stok_so']->jml + $data['stok_msk']->jml + $data['stok_mts']->jml : $data['stok_so']->jml + $data['stok_msk']->jml);
                        $data['tot_klr']    = ($data['gd_aktif']->status == '1' ? $data['stok_klr'] : $data['stok_mts']->jml);

                        $data['sql_stok_hist']   = $this->db
                                                        ->where('status', '4')
                                                        ->where('id_produk', $data['sql_stok']->id)
                                                        ->where('id >=', $data['stok_so']->id)
                                                        ->where('DATE(tgl_simpan)', $tgl)
                                                        ->like('id_gudang', $id_gdg)
                                                        ->group_by('tgl_simpan, id_penjualan, id_pembelian, id_pembelian_det, keterangan')
                                                        ->get('v_produk_hist')->result();
                        
                        $data['sql_stok_msk']   = $this->db
                                                       ->where('status', '1')
                                                       ->where('id_produk', $data['sql_stok']->id)
                                                       ->where('DATE(tgl_simpan)', $tgl)
                                                       ->like('id_gudang', $id_gdg)
                                                       ->group_by('tgl_simpan, id_penjualan, id_pembelian, id_pembelian_det, keterangan')
                                                       ->get('v_produk_hist')->result();
                        
//                        $data['sql_stok_hist']   = $this->db
//                                                        ->where('status', '4')
//                                                        ->where('id_produk', $data['sql_stok']->id)
//                                                        ->where('id >=', $data['stok_so']->id)
//                                                        ->where('DATE(tgl_simpan)', $tgl)
//                                                        ->like('id_gudang', $id_gdg)
//                                                        ->get('tbl_m_produk_hist')->result();
//                        $data['sql_stok_msk']   = $this->db
//                                                       ->where('status', '1')
//                                                       ->where('id_produk', $data['sql_stok']->id)
//                                                       ->where('DATE(tgl_simpan)', $tgl)
//                                                       ->like('id_gudang', $id_gdg)
//                                                       ->get('tbl_m_produk_hist')->result();
                    break;
                
                    case 'per_rentang':
                        $data['gd_aktif']   = $this->db->where('id', $id_gdg)->get('tbl_m_gudang')->row();
                        $data['stok_mts']   = $this->db->where('id_produk', $data['sql_stok']->id)->where('status', '8')->where('DATE(tgl_simpan) <=', $tgl_akhir)->limit(1)->order_by('id', 'DESC')->get('v_produk_hist')->row();
                        $data['stok_so']    = $this->db->where('id_produk', $data['sql_stok']->id)->where('id_gudang', $id_gdg)->where('status', '6')->where('DATE(tgl_simpan) <=', $tgl_akhir)->limit(1)->order_by('id', 'DESC')->get('v_produk_hist')->row();
                        
                        if(!empty($data['stok_so'])){
                            $data['stok_msk']   = $this->db->select_sum('jml')->where('id_produk', $data['sql_stok']->id)->where('status', '1')->where('DATE(tgl_simpan) >=', $tgl_awal)->where('DATE(tgl_simpan) <=', $tgl_akhir)->like('id_gudang', $id_gdg)->get('v_produk_hist')->row();
                            $data['stok_klr']   = $this->db->select_sum('jml')->where('id_produk', $data['sql_stok']->id)->where('id >=', $data['stok_so']->id)->where('status', '4')->where('DATE(tgl_simpan) >=', $tgl_awal)->where('DATE(tgl_simpan) <=', $tgl_akhir)->like('id_gudang', $id_gdg)->get('v_produk_hist')->row()->jml;

//                          $data['tot_msk']    = $data['stok_so']->jml + $data['stok_msk']->jml + $data['stok_mts']->jml;
                            $data['tot_msk']    = ($data['gd_aktif']->status == '1' ? $data['stok_so']->jml + $data['stok_msk']->jml + $data['stok_mts']->jml : $data['stok_so']->jml + $data['stok_msk']->jml);
                            $data['tot_klr']    = ($data['gd_aktif']->status == '1' ? $data['stok_klr'] : $data['stok_mts']->jml);
                        
                            $data['sql_stok_hist']  = $this->db
                                                           ->where('status', '4')
                                                           ->where('id_produk', $data['sql_stok']->id)
                                                           ->where('id >=', $data['stok_so']->id)
                                                           ->where('DATE(tgl_masuk) >=', $tgl_awal)
                                                           ->where('DATE(tgl_masuk) <=', $tgl_akhir)
                                                           ->like('id_gudang', $id_gdg)
                                                           ->group_by('tgl_simpan, id_penjualan, id_pembelian, id_pembelian_det, keterangan')
                                                           ->get('v_produk_hist')->result();

                            $data['sql_stok_msk']   = $this->db
                                                           ->where('status', '1')
                                                           ->where('id_produk', $data['sql_stok']->id)
                                                           ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                                           ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                                           ->like('id_gudang', $id_gdg)
                                                           ->group_by('tgl_simpan, id_penjualan, id_pembelian, id_pembelian_det, keterangan')
                                                           ->get('v_produk_hist')->result();
//                        
//                            $data['sql_stok_hist']  = $this->db
//                                                           ->where('status', '4')
//                                                           ->where('id_produk', $data['sql_stok']->id)
//                                                           ->where('id >=', $data['stok_so']->id)
//                                                           ->where('DATE(tgl_masuk) >=', $tgl_awal)
//                                                           ->where('DATE(tgl_masuk) <=', $tgl_akhir)
//                                                           ->like('id_gudang', $id_gdg)
//                                                           ->get('tbl_m_produk_hist')->result();
//
//                            $data['sql_stok_msk']   = $this->db
//                                                           ->where('status', '1')
//                                                           ->where('id_produk', $data['sql_stok']->id)
//                                                           ->where('DATE(tgl_simpan) >=', $tgl_awal)
//                                                           ->where('DATE(tgl_simpan) <=', $tgl_akhir)
//                                                           ->like('id_gudang', $id_gdg)
//                                                           ->get('tbl_m_produk_hist')->result();
                        }else{
                            $data['tot_msk']    = ($data['gd_aktif']->status == '1' ? $data['stok_so']->jml + $data['stok_msk']->jml + $data['stok_mts']->jml : $data['stok_so']->jml + $data['stok_msk']->jml);
                            $data['tot_klr']    = ($data['gd_aktif']->status == '1' ? $data['stok_klr'] : $data['stok_mts']->jml);
                            
                            $data['sql_stok_hist']  = $this->db
                                                           ->where('status', '4')
                                                           ->where('id_produk', $data['sql_stok']->id)
//                                                           ->where('id >=', $data['stok_so']->id)
                                                           ->where('DATE(tgl_masuk) >=', $tgl_awal)
                                                           ->where('DATE(tgl_masuk) <=', $tgl_akhir)
                                                           ->like('id_gudang', $id_gdg)
                                                           ->group_by('tgl_simpan, id_penjualan, id_pembelian, id_pembelian_det, keterangan')
                                                           ->get('v_produk_hist')->result();

                            $data['sql_stok_msk']   = $this->db
                                                           ->where('status', '1')
                                                           ->where('id_produk', $data['sql_stok']->id)
                                                           ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                                           ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                                           ->like('id_gudang', $id_gdg)
                                                           ->group_by('tgl_simpan, id_penjualan, id_pembelian, id_pembelian_det, keterangan')
                                                           ->get('v_produk_hist')->result();
                        }
                    break;
                }
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */

            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_stok_telusur', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_stok_keluar(){
        if (akses::aksesLogin() == TRUE) {
            $dokter     = $this->input->get('id_dokter');
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
            $data['sql_doc'] = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            
            if($jml > 0){
                $data['hasError'] = $this->session->flashdata('form_error');
                
                // Config Pagination
                $config['base_url']              = base_url('laporan/data_stok_keluar.php?case='.$case.(!empty($tgl) ? '&tgl='.$tgl : '').(!empty($tgl_awal) ? '&tgl_awal='.$tgl_awal : '').(!empty($tgl_akhir) ? '&tgl_akhir='.$tgl_akhir : '').(!empty($jml) ? '&jml='.$jml : ''));
                $config['total_rows']            = $jml;
                
                $config['query_string_segment']  = 'halaman';
                $config['page_query_string']     = TRUE;
                $config['per_page']              = $pengaturan->jml_item;
                $config['num_links']             = 3;
                
                $config['first_tag_open']        = '<li class="page-item">';
                $config['first_tag_close']       = '</li>';
                
                $config['prev_tag_open']         = '<li class="page-item">';
                $config['prev_tag_close']        = '</li>';
                
                $config['num_tag_open']          = '<li class="page-item">';
                $config['num_tag_close']         = '</li>';
                
                $config['next_tag_open']         = '<li class="page-item">';
                $config['next_tag_close']        = '</li>';
                
                $config['last_tag_open']         = '<li class="page-item">';
                $config['last_tag_close']        = '</li>';
                
                $config['cur_tag_open']          = '<li class="page-item"><a href="#" class="page-link text-dark"><b>';
                $config['cur_tag_close']         = '</b></a></li>';
                
                $config['first_link']            = '&laquo;';
                $config['prev_link']             = '&lsaquo;';
                $config['next_link']             = '&rsaquo;';
                $config['last_link']             = '&raquo;';
                $config['anchor_class']          = 'class="page-link"';
            
                $sql_doc = $this->db->where('id', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
                
                switch ($case){
                    case 'per_tanggal':
                        if(!empty($jml)){
                            $data['sql_penj']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_masuk)', $tgl)
                                                              ->where('tbl_trans_medcheck_det.status', '4')
                                                              ->limit($config['per_page'], $hal)
                                                          ->get('tbl_trans_medcheck_det')->result(); 
                        }else{
                            $data['sql_penj']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_masuk)', $tgl)
                                                              ->where('tbl_trans_medcheck_det.status', '4')
                                                              ->limit($config['per_page'])
                                                          ->get('tbl_trans_medcheck_det')->result();                            
                        }
                        break;
                    
                    case 'per_rentang':
                        if(!empty($hal)){
                            $data['sql_penj']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_masuk) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck.tgl_masuk) <=', $tgl_akhir)
                                                              ->where('tbl_trans_medcheck_det.status', '4')
                                                              ->order_by('tbl_trans_medcheck_det.tgl_simpan', 'ASC')
                                                              ->limit($config['per_page'], $hal)
                                                          ->get('tbl_trans_medcheck_det')->result(); 
                        }else{
                            $data['sql_penj']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.metode, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.tgl_simpan, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal, tbl_m_kategori.keterangan AS kategori')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck_det.tgl_simpan) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck_det.tgl_simpan) <=', $tgl_akhir)
                                                              ->where('tbl_trans_medcheck_det.status', '4')
                                                              ->order_by('tbl_trans_medcheck_det.tgl_simpan', 'ASC')
                                                              ->limit($config['per_page'])
                                                          ->get('tbl_trans_medcheck_det')->result();
                        }
                        break;
                }
                
                // Initializing Config Pagination
                $this->pagination->initialize($config);
                
                // Pagination Data
                $data['total_rows'] = $config['total_rows'];
                $data['PerPage']    = $config['per_page'];
                $data['pagination'] = $this->pagination->create_links();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_stok_keluar', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
        
    public function data_stok_mutasi(){
        if (akses::aksesLogin() == TRUE) {
            $dokter     = $this->input->get('id_dokter');
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
            $data['sql_doc'] = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            
            if($jml > 0){
                $data['hasError'] = $this->session->flashdata('form_error');
                
                // Config Pagination
                $config['base_url']              = base_url('laporan/data_stok_mutasi.php?case='.$case.(!empty($tgl) ? '&tgl='.$tgl : '').(!empty($tgl_awal) ? '&tgl_awal='.$tgl_awal : '').(!empty($tgl_akhir) ? '&tgl_akhir='.$tgl_akhir : '').(!empty($jml) ? '&jml='.$jml : ''));
                $config['total_rows']            = $jml;
                
                $config['query_string_segment']  = 'halaman';
                $config['page_query_string']     = TRUE;
                $config['per_page']              = $pengaturan->jml_item;
                $config['num_links']             = 3;
                
                $config['first_tag_open']        = '<li class="page-item">';
                $config['first_tag_close']       = '</li>';
                
                $config['prev_tag_open']         = '<li class="page-item">';
                $config['prev_tag_close']        = '</li>';
                
                $config['num_tag_open']          = '<li class="page-item">';
                $config['num_tag_close']         = '</li>';
                
                $config['next_tag_open']         = '<li class="page-item">';
                $config['next_tag_close']        = '</li>';
                
                $config['last_tag_open']         = '<li class="page-item">';
                $config['last_tag_close']        = '</li>';
                
                $config['cur_tag_open']          = '<li class="page-item"><a href="#" class="page-link text-dark"><b>';
                $config['cur_tag_close']         = '</b></a></li>';
                
                $config['first_link']            = '&laquo;';
                $config['prev_link']             = '&lsaquo;';
                $config['next_link']             = '&rsaquo;';
                $config['last_link']             = '&raquo;';
                $config['anchor_class']          = 'class="page-link"';
            
                $sql_doc = $this->db->where('id', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
                
                switch ($case){
                    case 'per_tanggal':
                        if(!empty($jml)){
                            $data['sql_penj']     = $this->db->where('DATE(tgl_simpan)', $tgl)
                                                             ->limit($config['per_page'], $hal)
                                                             ->get('v_laporan_stok')->result(); 
                        }else{
                            $data['sql_penj']     = $this->db->where('DATE(tgl_simpan)', $tgl)
                                                             ->limit($config['per_page'])
                                                             ->get('v_laporan_stok')->result();                          
                        }
                        break;
                    
                    case 'per_rentang':
                        if(!empty($hal)){
                            $data['sql_penj']     = $this->db
//                                                             ->where('DATE(tgl_simpan) >=', $tgl_awal)
//                                                             ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                                             ->limit($config['per_page'], $hal)
                                                             ->get('v_produk_stok')->result(); 
                        }else{
                            $data['sql_penj']     = $this->db
//                                                             ->where('DATE(tgl_simpan) >=', $tgl_awal)
//                                                             ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                                             ->limit($config['per_page'])
                                                             ->get('v_produk_stok')->result(); 
                        }
                        break;
                }
                
                // Initializing Config Pagination
                $this->pagination->initialize($config);
                
                // Pagination Data
                $data['total_rows'] = $config['total_rows'];
                $data['PerPage']    = $config['per_page'];
                $data['pagination'] = $this->pagination->create_links();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_stok_mutasi', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_stok_keluar_resep(){
        if (akses::aksesLogin() == TRUE) {
            $poli               = $this->input->get('poli');
            $plat               = $this->input->get('plat');
            $jml                = $this->input->get('jml');
            $tgl                = $this->input->get('tgl');
            $tgl_awal           = $this->input->get('tgl_awal');
            $tgl_akhir          = $this->input->get('tgl_akhir');
            $case               = $this->input->get('case');
            $hal                = $this->input->get('halaman');
            $dokter             = $this->input->get('dokter');
            $pengaturan         = $this->db->get('tbl_pengaturan')->row();
            
            if(!empty($jml)){
                $jml_hal = $jml;
            }else{
//                $jml_hal = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_resep_det.kode, tbl_trans_medcheck_resep_det.item, tbl_trans_medcheck_resep_det.dosis, tbl_trans_medcheck_resep_det.dosis_ket, tbl_trans_medcheck_resep_det.keterangan, tbl_trans_medcheck_resep_det.harga, tbl_trans_medcheck_resep_det.jml')
//                                ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resep_det.id_medcheck')
//                                ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
//                                ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_resep_det.id_item_kat')
//                                ->where('tbl_trans_medcheck.status_bayar', '1')
//                                ->where('tbl_trans_medcheck_resep_det.id_user', $dokter)
//                                ->where('DATE(tbl_trans_medcheck_resep_det.tgl_simpan) >=', $tgl_awal)
//                                ->where('DATE(tbl_trans_medcheck_resep_det.tgl_simpan) <=', $tgl_akhir)
//                                ->order_by('tbl_trans_medcheck_resep_det.tgl_simpan', 'ASC')
//                                ->get('tbl_trans_medcheck_resep_det')->num_rows();
            }
            
            $data['sql_doc']    = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            $data['sql_poli']   = $this->db->get('tbl_m_poli')->result();
            $data['sql_plat']   = $this->db->get('tbl_m_platform')->result();
            
            $data['hasError'] = $this->session->flashdata('form_error');
            
            // Config Pagination
            $config['base_url']              = base_url('laporan/data_stok_keluar_resep.php?case='.$case.(!empty($dokter) ? '&dokter='.$dokter : '').(!empty($tgl) ? '&tgl='.$tgl : '').(!empty($tgl_awal) ? '&tgl_awal='.$tgl_awal : '').(!empty($tgl_akhir) ? '&tgl_akhir='.$tgl_akhir : '').(!empty($jml_hal) ? '&jml='.$jml_hal : ''));
            $config['total_rows']            = $jml_hal;
            
            $config['query_string_segment']  = 'halaman';
            $config['page_query_string']     = TRUE;
            $config['per_page']              = $pengaturan->jml_item;
            $config['num_links']             = 3;
            
            $config['first_tag_open']        = '<li class="page-item">';
            $config['first_tag_close']       = '</li>';
            
            $config['prev_tag_open']         = '<li class="page-item">';
            $config['prev_tag_close']        = '</li>';
            
            $config['num_tag_open']          = '<li class="page-item">';
            $config['num_tag_close']         = '</li>';
            
            $config['next_tag_open']         = '<li class="page-item">';
            $config['next_tag_close']        = '</li>';
            
            $config['last_tag_open']         = '<li class="page-item">';
            $config['last_tag_close']        = '</li>';
            
            $config['cur_tag_open']          = '<li class="page-item"><a href="#" class="page-link text-dark"><b>';
            $config['cur_tag_close']         = '</b></a></li>';
            
            $config['first_link']            = '&laquo;';
            $config['prev_link']             = '&lsaquo;';
            $config['next_link']             = '&rsaquo;';
            $config['last_link']             = '&raquo;';
            $config['anchor_class']          = 'class="page-link"';
        
            $sql_doc = $this->db->where('id', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
            
            switch ($case){
                case 'per_tanggal':
                    if(!empty($hal)){
                        $data['sql_penj']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_resep_det.kode, tbl_trans_medcheck_resep_det.item, tbl_trans_medcheck_resep_det.dosis, tbl_trans_medcheck_resep_det.dosis_ket, tbl_trans_medcheck_resep_det.keterangan, tbl_trans_medcheck_resep_det.harga, tbl_trans_medcheck_resep_det.jml, tbl_trans_medcheck_resep_det.satuan')
                                                        ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resep_det.id_medcheck')
                                                        ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                        ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_resep_det.id_item_kat')
                                                        ->where('tbl_trans_medcheck.status_bayar', '1')
                                                        ->where('tbl_trans_medcheck_resep_det.id_user', general::dekrip($dokter))
                                                        ->where('DATE(tbl_trans_medcheck_resep_det.tgl_simpan)', $tgl)
                                                        ->order_by('tbl_trans_medcheck_resep_det.tgl_simpan', 'ASC')
                                                        ->limit($config['per_page'], $hal)
                                                        ->get('tbl_trans_medcheck_resep_det')->result(); 
                    }else{
                        $data['sql_penj']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_resep_det.kode, tbl_trans_medcheck_resep_det.item, tbl_trans_medcheck_resep_det.dosis, tbl_trans_medcheck_resep_det.dosis_ket, tbl_trans_medcheck_resep_det.keterangan, tbl_trans_medcheck_resep_det.harga, tbl_trans_medcheck_resep_det.jml, tbl_trans_medcheck_resep_det.satuan')
                                                        ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resep_det.id_medcheck')
                                                        ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                        ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_resep_det.id_item_kat')
                                                        ->where('tbl_trans_medcheck.status_bayar', '1')
                                                        ->where('tbl_trans_medcheck_resep_det.id_user', general::dekrip($dokter))
                                                        ->where('DATE(tbl_trans_medcheck_resep_det.tgl_simpan)', $tgl)
                                                        ->order_by('tbl_trans_medcheck_resep_det.tgl_simpan', 'ASC')
                                                        ->limit($config['per_page'])
                                                        ->get('tbl_trans_medcheck_resep_det')->result();                          
                    }
                    break;
                
                case 'per_rentang':
                    if(!empty($hal)){
                        $data['sql_penj']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_resep_det.kode, tbl_trans_medcheck_resep_det.item, tbl_trans_medcheck_resep_det.dosis, tbl_trans_medcheck_resep_det.dosis_ket, tbl_trans_medcheck_resep_det.keterangan, tbl_trans_medcheck_resep_det.harga, tbl_trans_medcheck_resep_det.jml')
                                                        ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resep_det.id_medcheck')
                                                        ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                        ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_resep_det.id_item_kat')
                                                        ->where('tbl_trans_medcheck.status_bayar', '1')
                                                        ->where('tbl_trans_medcheck_resep_det.id_user', general::dekrip($dokter))
                                                        ->where('DATE(tbl_trans_medcheck_resep_det.tgl_simpan) >=', $tgl_awal)
                                                        ->where('DATE(tbl_trans_medcheck_resep_det.tgl_simpan) <=', $tgl_akhir)
                                                        ->order_by('tbl_trans_medcheck_resep_det.tgl_simpan', 'ASC')
                                                        ->limit($config['per_page'], $hal)
                                                        ->get('tbl_trans_medcheck_resep_det')->result();
                    }else{
                        $data['sql_penj']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_resep_det.kode, tbl_trans_medcheck_resep_det.item, tbl_trans_medcheck_resep_det.dosis, tbl_trans_medcheck_resep_det.dosis_ket, tbl_trans_medcheck_resep_det.keterangan, tbl_trans_medcheck_resep_det.harga, tbl_trans_medcheck_resep_det.jml')
                                                        ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resep_det.id_medcheck')
                                                        ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                        ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_resep_det.id_item_kat')
                                                        ->where('tbl_trans_medcheck.status_bayar', '1')
                                                        ->where('tbl_trans_medcheck_resep_det.id_user', general::dekrip($dokter))
                                                        ->where('DATE(tbl_trans_medcheck_resep_det.tgl_simpan) >=', $tgl_awal)
                                                        ->where('DATE(tbl_trans_medcheck_resep_det.tgl_simpan) <=', $tgl_akhir)
                                                        ->order_by('tbl_trans_medcheck_resep_det.tgl_simpan', 'ASC')
                                                        ->limit($config['per_page'])
                                                        ->get('tbl_trans_medcheck_resep_det')->result();
                    }
                    break;
            }
            
            
            // Initializing Config Pagination
            $this->pagination->initialize($config);
//
            // Pagination Data
            $data['total_rows'] = $config['total_rows'];
            $data['PerPage']    = $config['per_page'];
            $data['pagination'] = $this->pagination->create_links();
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_stok_keluar_resep', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_pasien(){
        if (akses::aksesLogin() == TRUE) {
            $jml                = $this->input->get('jml');
            $tgl                = $this->input->get('tgl');
            $bln                = $this->input->get('bln');
            $hal                = $this->input->get('halaman');
            $case               = $this->input->get('case');
            $pengaturan         = $this->db->get('tbl_pengaturan')->row();
            
            if($jml > 0){
                $data['hasError'] = $this->session->flashdata('form_error');
                
                // Config Pagination
                $config['base_url']              = base_url('laporan/data_pasien.php?case='.$case.(!empty($tgl) ? '&tgl='.$tgl : '').(!empty($bln) ? '&bln='.$bln : '').(!empty($jml) ? '&jml='.$jml : ''));
                $config['total_rows']            = $jml;
                
                $config['query_string_segment']  = 'halaman';
                $config['page_query_string']     = TRUE;
                $config['per_page']              = $pengaturan->jml_item;
                $config['num_links']             = 3;
                
                $config['first_tag_open']        = '<li class="page-item">';
                $config['first_tag_close']       = '</li>';
                
                $config['prev_tag_open']         = '<li class="page-item">';
                $config['prev_tag_close']        = '</li>';
                
                $config['num_tag_open']          = '<li class="page-item">';
                $config['num_tag_close']         = '</li>';
                
                $config['next_tag_open']         = '<li class="page-item">';
                $config['next_tag_close']        = '</li>';
                
                $config['last_tag_open']         = '<li class="page-item">';
                $config['last_tag_close']        = '</li>';
                
                $config['cur_tag_open']          = '<li class="page-item"><a href="#" class="page-link text-dark"><b>';
                $config['cur_tag_close']         = '</b></a></li>';
                
                $config['first_link']            = '&laquo;';
                $config['prev_link']             = '&lsaquo;';
                $config['next_link']             = '&rsaquo;';
                $config['last_link']             = '&raquo;';
                $config['anchor_class']          = 'class="page-link"';
            
                $sql_doc = $this->db->where('id', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
                
                switch ($case){
                    default:
                        if (!empty($hal)) {
                            $data['sql_pasien']     = $this->db->select('tbl_m_pasien.id, tbl_m_pasien.kode_dpn, tbl_m_pasien.kode, tbl_m_pasien.nama, tbl_m_pasien.no_hp, tbl_m_pasien.tgl_lahir, DAY(tbl_m_pasien.tgl_lahir) AS hari, MONTH(tbl_m_pasien.tgl_lahir) AS bulan')
    //                                                            ->where('tbl_m_pasien.no_hp !=', '')
    //                                                            ->where('DAY(tbl_m_pasien.tgl_lahir)', $tgl)
    //                                                            ->where('MONTH(tbl_m_pasien.tgl_lahir)', $bln)
                                                                ->limit($config['per_page'], $hal)
                                                                ->order_by('tbl_m_pasien.nama', 'ASC') 
                                                                ->get('tbl_m_pasien')->result();                            
                        }else{
                            $data['sql_pasien']     = $this->db->select('tbl_m_pasien.id, tbl_m_pasien.kode_dpn, tbl_m_pasien.kode, tbl_m_pasien.nama, tbl_m_pasien.no_hp, tbl_m_pasien.tgl_lahir, DAY(tbl_m_pasien.tgl_lahir) AS hari, MONTH(tbl_m_pasien.tgl_lahir) AS bulan')
    //                                                            ->where('tbl_m_pasien.no_hp !=', '')
    //                                                            ->where('DAY(tbl_m_pasien.tgl_lahir)', $tgl)
    //                                                            ->where('MONTH(tbl_m_pasien.tgl_lahir)', $bln)
                                                                ->limit($config['per_page'])
                                                                ->order_by('tbl_m_pasien.nama', 'ASC') 
                                                                ->get('tbl_m_pasien')->result();                            
                        }
                        break;
                    
                    case 'per_tanggal':
                        $data['sql_pasien']     = $this->db->select('tbl_m_pasien.id, tbl_m_pasien.kode_dpn, tbl_m_pasien.kode, tbl_m_pasien.nama, tbl_m_pasien.no_hp, tbl_m_pasien.tgl_lahir, DAY(tbl_m_pasien.tgl_lahir) AS hari, MONTH(tbl_m_pasien.tgl_lahir) AS bulan')
                                                            ->where('tbl_m_pasien.no_hp !=', '')
                                                            ->where('DAY(tbl_m_pasien.tgl_lahir)', $tgl)
                                                            ->where('MONTH(tbl_m_pasien.tgl_lahir)', $bln)
                                                            ->limit($config['per_page'], $hal)
                                                            ->order_by('tbl_m_pasien.nama', 'ASC') 
                                                            ->get('tbl_m_pasien')->result();
                        break;
                }
                
                // Initializing Config Pagination
                $this->pagination->initialize($config);
                
                // Pagination Data
                $data['total_rows'] = $config['total_rows'];
                $data['PerPage']    = $config['per_page'];
                $data['pagination'] = $this->pagination->create_links();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_pasien', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_pasien_kunj(){
        if (akses::aksesLogin() == TRUE) {
            $jml                = $this->input->get('jml');
            $tgl                = $this->input->get('tgl');
            $tgl_awal           = $this->input->get('tgl_awal');
            $tgl_akhir          = $this->input->get('tgl_akhir');
            $poli               = $this->input->get('poli');
            $tipe               = $this->input->get('tipe');
            $hal                = $this->input->get('halaman');
            $case               = $this->input->get('case');
            $pengaturan         = $this->db->get('tbl_pengaturan')->row();
            
            $data['sql_poli']   = $this->db->get('tbl_m_poli')->result();
            $data['sql_plat']   = $this->db->get('tbl_m_platform')->result();
            
            switch ($case){                
                case 'per_tanggal':
                    $data['sql_pasien'] = $this->db
                                                   ->where('DATE(tgl_simpan)', $tgl)
                                                   ->like('id_poli', $poli)
                                                   ->like('tipe', $tipe)
                                                   ->get('v_medcheck_visit')->result();
                    $data['sql_pasien_row'] = $this->db->select('SUM(jml_kunjungan) AS jml_kunjungan, SUM(jml_gtotal) AS jml_gtotal')
                                                   ->where('DATE(tgl_simpan)', $tgl)
                                                   ->like('id_poli', $poli)
                                                   ->like('tipe', $tipe)
                                                   ->get('v_medcheck_visit')->row();
                    break;

                case 'per_rentang':
                    $data['sql_pasien'] = $this->db
                                                   ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                                   ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                                   ->like('id_poli', $poli)
                                                   ->like('tipe', $tipe)
                                                   ->get('v_medcheck_visit')->result();
                    
                    $data['sql_pasien_row'] = $this->db->select('SUM(jml_kunjungan) AS jml_kunjungan, SUM(jml_gtotal) AS jml_gtotal')
                                                   ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                                   ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                                   ->like('id_poli', $poli)
                                                   ->like('tipe', $tipe)
                                                   ->get('v_medcheck_visit')->row();
                    break;
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_pasien_kunj', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_karyawan_ultah(){
        if (akses::aksesLogin() == TRUE) {
            $jml                = $this->input->get('jml');
            $tgl                = $this->input->get('tgl');
            $bln                = $this->input->get('bln');
            $hr_awal            = $this->input->get('hr_awal');
            $bln_awal           = $this->input->get('bln_awal');
            $hr_akhir           = $this->input->get('hr_akhir');
            $bln_akhir          = $this->input->get('bln_akhir');
            $hal                = $this->input->get('halaman');
            $case               = $this->input->get('case');
            $pengaturan         = $this->db->get('tbl_pengaturan')->row();
            
            if($jml > 0){
                $data['hasError'] = $this->session->flashdata('form_error');
            
                $sql_doc = $this->db->where('id', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
                
                switch ($case){                    
                    case 'per_tanggal':
                        $data['sql_karyawan']     = $this->db->select('tbl_m_karyawan.id, tbl_m_karyawan.nik, tbl_m_karyawan.kode, tbl_m_karyawan.nama, tbl_m_karyawan.no_hp, tbl_m_karyawan.tgl_lahir, DAY(tbl_m_karyawan.tgl_lahir) AS hari, MONTH(tbl_m_karyawan.tgl_lahir) AS bulan')
                                                            ->where('tbl_m_karyawan.no_hp !=', '')
                                                            ->where('DAY(tbl_m_karyawan.tgl_lahir)', $tgl)
                                                            ->where('MONTH(tbl_m_karyawan.tgl_lahir)', $bln)
                                                            ->order_by('tbl_m_karyawan.nama', 'ASC') 
                                                            ->get('tbl_m_karyawan')->result();
                        break;
                    
                    case 'per_rentang':
                        $data['sql_karyawan']     = $this->db->select('tbl_m_karyawan.id, tbl_m_karyawan.nik, tbl_m_karyawan.kode, tbl_m_karyawan.nama, tbl_m_karyawan.no_hp, tbl_m_karyawan.tgl_lahir, DAY(tbl_m_karyawan.tgl_lahir) AS hari, MONTH(tbl_m_karyawan.tgl_lahir) AS bulan')
                                                            ->where('tbl_m_karyawan.no_hp !=', '')
                                                            ->where('DAY(tbl_m_karyawan.tgl_lahir) >=', $hr_awal)
                                                            ->where('MONTH(tbl_m_karyawan.tgl_lahir) >=', $bln_awal)
                                                            ->where('DAY(tbl_m_karyawan.tgl_lahir) <=', $hr_akhir)
                                                            ->where('MONTH(tbl_m_karyawan.tgl_lahir) <=', $bln_akhir)
//                                                            ->limit($config['per_page'], $hal)
                                                            ->order_by('tbl_m_karyawan.nama', 'ASC') 
                                                            ->get('tbl_m_karyawan')->result();
                        break;
                }
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_karyawan_ultah', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_tracer(){
        if (akses::aksesLogin() == TRUE) {
            $dokter     = $this->input->get('id_dokter');
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $tipe       = $this->input->get('tipe');
            $case       = $this->input->get('case');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
            $data['hasError'] = $this->session->flashdata('form_error');
        
            $sql_doc = $this->db->where('id', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
            
            switch ($case) {
                case 'per_tanggal':
                    $data['sql_tracer'] = $this->db
                                    ->where('DATE(tgl_simpan)', $tgl)
                                    ->get('v_medcheck_tracer')->result();
                    break;

                case 'per_rentang':
                    $data['sql_tracer'] = $this->db
                                               ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                               ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                               ->get('v_medcheck_tracer')->result();
                    break;
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/laporan/sidebar_lap';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_tracer', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    
    
    public function set_data_cuti(){
        if (akses::aksesLogin() == TRUE) {
            $kry     = $this->input->post('karyawan');
            $tgl     = $this->input->post('tgl');
            $tgl_rtg = $this->input->post('tgl_rentang');
            $tipe    = $this->input->post('tipe');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
//            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
//            $this->form_validation->set_rules('dokter', 'Dokter', 'required');
//            
//            if ($this->form_validation->run() == FALSE) {
//                $msg_error = array(
//                    'dokter'   => form_error('dokter'),
//                );
//                
//                redirect(base_url('laporan/data_remunerasi.php'));
//            } else {
                $sql_kry = $this->db->where('id_user', $kry)->get('tbl_m_karyawan')->row();
                
                $tgl_rentang    = explode('-', $tgl_rtg);
                $tgl_awal       = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
                $tgl_akhir      = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
                $tgl_masuk      = $this->tanggalan->tgl_indo_sys($tgl);

                if(!empty($tgl)){
                    $sql = $this->db->like('id_karyawan', (!empty($sql_kry->id) ? $sql_kry->id : ''))->where('DATE(tgl_simpan)', $tgl_masuk)->get('tbl_sdm_cuti');
                
                    redirect(base_url('laporan/data_cuti.php?case=per_tanggal&id_kary='.general::enkrip($sql_kry->id_user).'&tgl='.$tgl_masuk.'&tipe='.$tipe.'&jml='.$sql->num_rows()));
                }elseif($tgl_rtg){
                    $sql = $this->db
                                ->like('id_karyawan', (!empty($sql_kry->id) ? $sql_kry->id : ''))
                                ->where('DATE(tgl_masuk) >=', $tgl_awal)->where('DATE(tgl_simpan) <=', $tgl_akhir)->get('tbl_sdm_cuti');
                    
                    redirect(base_url('laporan/data_cuti.php?case=per_rentang&id_kary='.general::enkrip($sql_kry->id_user).'&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&tipe='.$tipe.'&jml='.$sql->num_rows()));
                }else{
                    redirect(base_url('laporan/data_cuti.php'));
                }
                    
//                echo '<pre>';
//                print_r($sql->row());
//                echo '</pre>';
//                echo '<pre>';
//                print_r($sql_kry);
//                echo '</pre>';
//            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_periksa(){
        if (akses::aksesLogin() == TRUE) {
            $dokter  = $this->input->post('dokter');
            $tgl     = $this->input->post('tgl');
            $tgl_rtg = $this->input->post('tgl_rentang');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
//            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
//            $this->form_validation->set_rules('dokter', 'Dokter', 'required');
//            
//            if ($this->form_validation->run() == FALSE) {
//                $msg_error = array(
//                    'dokter'   => form_error('dokter'),
//                );
//                
//                redirect(base_url('laporan/data_remunerasi.php'));
//            } else {
                $sql_doc = $this->db->where('id_user', $dokter)->get('tbl_m_karyawan')->row();
                
                $tgl_rentang    = explode('-', $tgl_rtg);
                $tgl_awal       = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
                $tgl_akhir      = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
                $tgl_masuk      = $this->tanggalan->tgl_indo_sys($tgl);

                if(!empty($tgl)){
                    $sql = $this->db->like('id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))->where('DATE(tgl_simpan)', $tgl_masuk)->get('tbl_trans_medcheck');
//                
                    redirect(base_url('laporan/data_periksa.php?case=per_tanggal&id_dokter='.general::enkrip($sql_doc->id_user).'&tgl='.$tgl_masuk.'&jml='.$sql->num_rows()));
                }elseif($tgl_rtg){
                    $sql = $this->db
                                ->like('id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
                                ->where('DATE(tgl_simpan) >=', $tgl_awal)->where('DATE(tgl_simpan) <=', $tgl_akhir)->order_by('id', 'DESC')->get('tbl_trans_medcheck');
                    
                    redirect(base_url('laporan/data_periksa.php?case=per_rentang&id_dokter='.general::enkrip($sql_doc->id_user).'&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&jml='.$sql->num_rows()));
                }
                    
//                echo '<pre>';
//                print_r($sql->result());
//                echo '</pre>';
//            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_remunerasi(){
        if (akses::aksesLogin() == TRUE) {
            $tipe    = $this->input->post('tipe');
            $dokter  = $this->input->post('dokter');
            $tgl     = $this->input->post('tgl');
            $tgl_rtg = $this->input->post('tgl_rentang');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
//            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
//            $this->form_validation->set_rules('dokter', 'Dokter', 'required');
//            
//            if ($this->form_validation->run() == FALSE) {
//                $msg_error = array(
//                    'dokter'   => form_error('dokter'),
//                );
//                
//                redirect(base_url('laporan/data_remunerasi.php'));
//            } else {
                $sql_doc = $this->db->where('id_user', $dokter)->get('tbl_m_karyawan')->row();
                
                $tgl_rentang    = explode('-', $tgl_rtg);
                $tgl_awal       = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
                $tgl_akhir      = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
                $tgl_masuk      = $this->tanggalan->tgl_indo_sys($tgl);

//                if(!empty($tipe)){
                    if(!empty($tgl)){
                        $sql = $this->db
                                    ->where('DATE(tgl_simpan)', $tgl_masuk)
                                    ->like('status_produk', (!empty($tipe) ? $tipe : ''))                                                          
                                    ->like('id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
                                    ->get('v_medcheck_remun');
                        
                        redirect(base_url('laporan/data_remunerasi.php?case=per_tanggal&tipe='.$tipe.'&id_dokter='.general::enkrip($sql_doc->id_user).'&tgl='.$tgl_masuk.'&jml='.$sql->num_rows()));
                    }elseif($tgl_rtg){
                        $sql = $this->db
                                    ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                    ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                    ->like('status_produk', (!empty($tipe) ? $tipe : ''))                                                          
                                    ->like('id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
                                    ->get('v_medcheck_remun');

                        redirect(base_url('laporan/data_remunerasi.php?case=per_rentang&tipe='.$tipe.'&id_dokter='.general::enkrip($sql_doc->id_user).'&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&jml='.$sql->num_rows()));
                    }
//                }else{
//                    redirect(base_url('laporan/data_remunerasi.php'));
//                }
                    
//                echo '<pre>';
//                print_r($sql->result());
//                echo '</pre>';
//            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_apresiasi(){
        if (akses::aksesLogin() == TRUE) {
            $dokter     = $this->input->post('dokter');
            $tgl        = $this->input->post('tgl');
            $tipe       = $this->input->post('tipe');
            $tgl_rtg    = $this->input->post('tgl_rentang');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
//            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
//            $this->form_validation->set_rules('dokter', 'Dokter', 'required');
//            
//            if ($this->form_validation->run() == FALSE) {
//                $msg_error = array(
//                    'dokter'   => form_error('dokter'),
//                );
//                
//                redirect(base_url('laporan/data_apresiasi.php'));
//            } else {
                $sql_doc = $this->db->where('id_user', $dokter)->get('tbl_m_karyawan')->row();
                
                $tgl_rentang    = explode('-', $tgl_rtg);
                $tgl_awal       = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
                $tgl_akhir      = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
                $tgl_masuk      = $this->tanggalan->tgl_indo_sys($tgl);

                if(!empty($tgl)){
                        $sql= $this->db->where('DATE(tgl_simpan)', $tgl_masuk)
                                       ->like('status_produk', (!empty($tipe) ? $tipe : ''))
                                       ->like('id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
                                       ->get('v_medcheck_apres');
                        
                    redirect(base_url('laporan/data_apresiasi.php?case=per_tanggal&tipe='.$tipe.'&id_dokter='.general::enkrip($sql_doc->id_user).'&tgl='.$tgl_masuk.'&jml='.$sql->num_rows()));
                }elseif($tgl_rtg){
                        $sql= $this->db->where('DATE(tgl_simpan) >=', $tgl_awal)
                                       ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                       ->like('status_produk', (!empty($tipe) ? $tipe : ''))
                                       ->like('id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
                                       ->get('v_medcheck_apres');
                        
                    redirect(base_url('laporan/data_apresiasi.php?case=per_rentang&tipe='.$tipe.'&id_dokter='.general::enkrip($sql_doc->id_user).'&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&jml='.$sql->num_rows()));
                }
                    
//                echo '<pre>';
//                print_r($sql);
//                echo '</pre>';
//            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_icd(){
        if (akses::aksesLogin() == TRUE) {
            $tgl     = $this->input->post('tgl');
            $tgl_rtg = $this->input->post('tgl_rentang');
            $poli    = $this->input->post('poli');
            $plat    = $this->input->post('platform');
            $idp     = $this->input->post('id_pasien');
            $pasien  = $this->input->post('pasien');
            $tipe    = $this->input->post('tipe');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $sql_doc = $this->db->where('id', $dokter)->get('tbl_m_karyawan')->row();

            $tgl_rentang = explode('-', $tgl_rtg);
            $tgl_awal = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $tgl_masuk = $this->tanggalan->tgl_indo_sys($tgl);

            if (!empty($tgl)) {
                $sql = $this->db->select('tbl_m_pasien.nama_pgl')
                                ->join('tbl_m_icd', 'tbl_m_icd.id=tbl_trans_medcheck_icd.id_icd')
                                ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_icd.id_medcheck')
                                ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                ->where('tbl_trans_medcheck.status_hps', '0')
                                ->where('tbl_trans_medcheck.status_bayar', '1')
                                ->where('DATE(tbl_trans_medcheck_icd.tgl_simpan)', $this->tanggalan->tgl_indo_sys($tgl))
                                ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
//                                ->like('tbl_trans_medcheck.pasien', $pasien)
                                ->get('tbl_trans_medcheck_icd');
                
                # Lempar ke halaman laporan
                redirect(base_url('laporan/data_icd.php?case=per_tanggal&poli='.$poli.(!empty($pasien) ? '&id_pasien='.$idp.'&pasien='.$pasien : '').(!empty($plat) ? '&plat='.$plat : '').(!empty($tipe) ? '&tipe='.$tipe : '').'&tgl='.$tgl_masuk.'&jml=' . $sql->num_rows()));
            } elseif ($tgl_rtg) {
                $sql = $this->db->select('tbl_m_pasien.nama_pgl')
                                ->join('tbl_m_icd', 'tbl_m_icd.id=tbl_trans_medcheck_icd.id_icd')
                                ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_icd.id_medcheck')
                                ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                ->where('tbl_trans_medcheck.status_hps', '0')
                                ->where('tbl_trans_medcheck.status_bayar', '1')
                                ->where('DATE(tbl_trans_medcheck_icd.tgl_simpan) >=', $this->tanggalan->tgl_indo_sys($tgl_awal))
                                ->where('DATE(tbl_trans_medcheck_icd.tgl_simpan) <=', $this->tanggalan->tgl_indo_sys($tgl_akhir))
                                ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
//                                ->like('tbl_trans_medcheck.pasien', $pasien)
                                ->get('tbl_trans_medcheck_icd');
                
                # Lempar ke halaman laporan
                redirect(base_url('laporan/data_icd.php?case=per_rentang&poli='.$poli.(!empty($pasien) ? '&id_pasien='.$idp.'&pasien='.$pasien : '').(!empty($plat) ? '&plat='.$plat : '').(!empty($tipe) ? '&tipe='.$tipe : '').'&tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir . '&jml=' . $sql->num_rows()));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_icd_pasien(){
        if (akses::aksesLogin() == TRUE) {
            $tgl     = $this->input->post('tgl');
            $tgl_rtg = $this->input->post('tgl_rentang');
            $poli    = $this->input->post('poli');
            $plat    = $this->input->post('platform');
            $idp     = $this->input->post('id_pasien');
            $pasien  = $this->input->post('pasien');
            $tipe    = $this->input->post('tipe');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $sql_doc = $this->db->where('id', $dokter)->get('tbl_m_karyawan')->row();

            $tgl_rentang = explode('-', $tgl_rtg);
            $tgl_awal = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $tgl_masuk = $this->tanggalan->tgl_indo_sys($tgl);

            if (!empty($tgl)) {
                $sql = $this->db->select('tbl_m_pasien.nama_pgl')
                                ->join('tbl_m_icd', 'tbl_m_icd.id=tbl_trans_medcheck_icd.id_icd')
                                ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_icd.id_medcheck')
                                ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                ->where('tbl_trans_medcheck.status_hps', '0')
                                ->where('tbl_trans_medcheck.status_bayar', '1')
                                ->where('DATE(tbl_trans_medcheck_icd.tgl_simpan)', $this->tanggalan->tgl_indo_sys($tgl))
                                ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
//                                ->like('tbl_trans_medcheck.pasien', $pasien)
                                ->get('tbl_trans_medcheck_icd');
                
                # Lempar ke halaman laporan
                redirect(base_url('laporan/data_icd.php?case=per_tanggal&poli='.$poli.(!empty($pasien) ? '&id_pasien='.$idp.'&pasien='.$pasien : '').(!empty($plat) ? '&plat='.$plat : '').(!empty($tipe) ? '&tipe='.$tipe : '').'&tgl='.$tgl_masuk.'&jml=' . $sql->num_rows()));
            } elseif ($tgl_rtg) {
                $sql = $this->db->select('tbl_m_pasien.nama_pgl')
                                ->join('tbl_m_icd', 'tbl_m_icd.id=tbl_trans_medcheck_icd.id_icd')
                                ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_icd.id_medcheck')
                                ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                ->where('tbl_trans_medcheck.status_hps', '0')
                                ->where('tbl_trans_medcheck.status_bayar', '1')
                                ->where('DATE(tbl_trans_medcheck_icd.tgl_simpan) >=', $this->tanggalan->tgl_indo_sys($tgl_awal))
                                ->where('DATE(tbl_trans_medcheck_icd.tgl_simpan) <=', $this->tanggalan->tgl_indo_sys($tgl_akhir))
                                ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
//                                ->like('tbl_trans_medcheck.pasien', $pasien)
                                ->get('tbl_trans_medcheck_icd');
                
                # Lempar ke halaman laporan
                redirect(base_url('laporan/data_diagnosa.php?case=per_rentang&poli='.$poli.(!empty($pasien) ? '&id_pasien='.$idp.'&pasien='.$pasien : '').(!empty($plat) ? '&plat='.$plat : '').(!empty($tipe) ? '&tipe='.$tipe : '').'&tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir . '&jml=' . $sql->num_rows()));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_mcu(){
        if (akses::aksesLogin() == TRUE) {
            $tgl            = $this->input->post('tgl');
            $tgl_rtg        = $this->input->post('tgl_rentang');
            $poli           = $this->input->post('poli');
            $plat           = $this->input->post('platform');
            $idp            = $this->input->post('id_pasien');
            $pasien         = $this->input->post('pasien');
            $tipe           = $this->input->post('tipe');
            
            $pengaturan     = $this->db->get('tbl_pengaturan')->row();
            $sql_doc        = $this->db->where('id', $dokter)->get('tbl_m_karyawan')->row();

            $tgl_rentang    = explode('-', $tgl_rtg);
            $tgl_awal       = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir      = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $tgl_masuk      = $this->tanggalan->tgl_indo_sys($tgl);

            if (!empty($tgl)) {
                $sql = $this->db->select('tbl_m_pasien.id, tbl_m_pasien.nama, tbl_trans_medcheck_resume_det.id, tbl_trans_medcheck_resume_det.param, tbl_trans_medcheck_resume_det.param_nilai')
                                ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resume_det.id_medcheck')
                                ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                ->where('tbl_trans_medcheck.tipe', '5')
                                ->where('DATE(tbl_trans_medcheck_resume_det.tgl_simpan)', $this->tanggalan->tgl_indo_sys($tgl_masuk))
                                ->get('tbl_trans_medcheck_resume_det');
                
                # Lempar ke halaman laporan
                redirect(base_url('laporan/data_mcu.php?case=per_tanggal&tgl=' . $tgl_masuk . '&jml=' . $sql->num_rows()));
            } elseif ($tgl_rtg) {
                $sql = $this->db->select('tbl_m_pasien.id AS id_pasien, tbl_m_pasien.nama_pgl, tbl_trans_medcheck_resume.id, tbl_trans_medcheck_resume.id_medcheck, tbl_trans_medcheck_resume.id_user, tbl_trans_medcheck_resume.no_surat')
                                                         ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resume.id_medcheck')
                                                         ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                         ->where('tbl_trans_medcheck.tipe', '5')
                                                         ->where('DATE(tbl_trans_medcheck_resume.tgl_simpan) >=', $this->tanggalan->tgl_indo_sys($tgl_awal))
                                                         ->where('DATE(tbl_trans_medcheck_resume.tgl_simpan) <=', $this->tanggalan->tgl_indo_sys($tgl_akhir))                      
                                                         ->get('tbl_trans_medcheck_resume');
                
                # Lempar ke halaman laporan
                redirect(base_url('laporan/data_mcu.php?case=per_rentang&tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir . '&jml=' . $sql->num_rows()));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_pembelian(){
        if (akses::aksesLogin() == TRUE) {
            $tgl     = $this->input->post('tgl');
            $tgl_rtg = $this->input->post('tgl_rentang');
            $idp     = $this->input->post('id_supplier');
            $supplier= $this->input->post('supplier');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $sql_supp = $this->db->where('id', $supplier)->get('tbl_m_supplier')->row();

            $tgl_rentang = explode('-', $tgl_rtg);
            $tgl_awal = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $tgl_masuk = $this->tanggalan->tgl_indo_sys($tgl);

            if (!empty($tgl)) {
                $sql = $this->db->select('tbl_trans_beli.id')
                                ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli.id_supplier')
                                ->where('DATE(tbl_trans_beli.tgl_masuk)', $this->tanggalan->tgl_indo_sys($tgl))
                                ->like('tbl_m_supplier.nama', $sql_supp->nama)
                                ->get('tbl_trans_beli');
                
                # Lempar ke halaman laporan
                redirect(base_url('laporan/data_pembelian.php?case=per_tanggal&'.(!empty($supplier) ? 'id_supplier='.general::enkrip($sql_supp->id).'&supplier='.$sql_supp->nama.'&' : '').'tgl='.$tgl_masuk.'&jml=' . $sql->num_rows()));
            } elseif ($tgl_rtg) {
                $sql = $this->db->select('tbl_trans_beli.id')
                                ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli.id_supplier')
                                ->where('DATE(tbl_trans_beli.tgl_masuk) >=', $this->tanggalan->tgl_indo_sys($tgl_awal))
                                ->where('DATE(tbl_trans_beli.tgl_masuk) <=', $this->tanggalan->tgl_indo_sys($tgl_akhir))
                                ->like('tbl_m_supplier.nama', $sql_supp->nama)
                                ->get('tbl_trans_beli');
                
                # Lempar ke halaman laporan
                redirect(base_url('laporan/data_pembelian.php?case=per_rentang&'.(!empty($supplier) ? 'id_supplier='.general::enkrip($sql_supp->id).'&supplier='.$sql_supp->nama.'&' : '').'tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&jml=' . $sql->num_rows()));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_omset(){
        if (akses::aksesLogin() == TRUE) {
            $tgl     = $this->input->post('tgl');
            $tgl_rtg = $this->input->post('tgl_rentang');
            $poli    = $this->input->post('poli');
            $plat    = $this->input->post('platform');
            $pjm     = $this->input->post('penjamin');
            $idp     = $this->input->post('id_pasien');
            $pasien  = $this->input->post('pasien');
            $tipe    = $this->input->post('tipe');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $sql_doc = $this->db->where('id', $dokter)->get('tbl_m_karyawan')->row();

            $tgl_rentang = explode('-', $tgl_rtg);
            $tgl_awal = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $tgl_masuk = $this->tanggalan->tgl_indo_sys($tgl);

            if (!empty($tgl)) {
                $sql = $this->db->select('tbl_trans_medcheck.id')
                                ->where('tbl_trans_medcheck.status_hps', '0')
                                ->where('tbl_trans_medcheck.status_bayar', '1')
                                ->where('DATE(tbl_trans_medcheck.tgl_bayar)', $this->tanggalan->tgl_indo_sys($tgl))
                                ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                ->like('tbl_trans_medcheck.tipe_bayar', $pjm, (!empty($pjm) ? 'none' : ''))
                                ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                ->like('tbl_trans_medcheck.pasien', $pasien)
                                ->get('tbl_trans_medcheck');
                
                # Lempar ke halaman laporan
                redirect(base_url('laporan/data_omset.php?case=per_tanggal&poli='.$poli.(!empty($pasien) ? '&id_pasien='.$idp.'&pasien='.$pasien : '').(!empty($plat) ? '&plat='.$plat : '').(!empty($tipe) ? '&tipe='.$tipe : '').'&tgl='.$tgl_masuk.'&jml=' . $sql->num_rows()));
            } elseif ($tgl_rtg) {
                $sql = $this->db->select('tbl_trans_medcheck.id')
                                ->where('tbl_trans_medcheck.status_hps', '0')
                                ->where('tbl_trans_medcheck.status_bayar', '1')
                                ->where('DATE(tbl_trans_medcheck.tgl_masuk) >=', $this->tanggalan->tgl_indo_sys($tgl_awal))
                                ->where('DATE(tbl_trans_medcheck.tgl_masuk) <=', $this->tanggalan->tgl_indo_sys($tgl_akhir))
                                ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                ->like('tbl_trans_medcheck.tipe_bayar', $pjm, (!empty($pjm) ? 'none' : ''))
                                ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                ->like('tbl_trans_medcheck.pasien', $pasien)
                                ->get('tbl_trans_medcheck');
                
                # Lempar ke halaman laporan
                redirect(base_url('laporan/data_omset.php?case=per_rentang&poli='.$poli.(!empty($pasien) ? '&id_pasien='.$idp.'&pasien='.$pasien : '').(!empty($plat) ? '&plat='.$plat : '').(!empty($tipe) ? '&tipe='.$tipe : '').(!empty($pjm) ? '&tipe_bayar='.$pjm : '').'&tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir . '&jml=' . $sql->num_rows()));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_omset_poli(){
        if (akses::aksesLogin() == TRUE) {
            $tgl            = $this->input->post('tgl');
            $tgl_rtg        = $this->input->post('tgl_rentang');
            $poli           = $this->input->post('poli');
            $tipe           = $this->input->post('tipe');
            $status         = $this->input->post('status');                
            $pengaturan     = $this->db->get('tbl_pengaturan')->row();

            $tgl_rentang    = explode('-', $tgl_rtg);
            $tgl_awal       = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir      = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $tgl_masuk      = $this->tanggalan->tgl_indo_sys($tgl);

            $p = json_encode($_POST['status']);

            if (!empty($tgl)){
                $sql = $this->db
                            ->where('DATE(tgl_simpan)', $tgl_masuk)
                            ->where_in('status', $status)
                            ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                            ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                            ->group_by('id_pasien')
                            ->get('v_medcheck_omset');
                
                redirect(base_url('laporan/data_omset_poli.php?case=per_tanggal'.(!empty($tipe) ? '&tipe='.$tipe : '').(!empty($poli) ? '&poli='.$poli : '').(!empty($status) ? '&status='.general::enkrip($p) : '').'&tgl=' . $tgl_masuk . '&jml=' . $sql->num_rows()));
            } elseif ($tgl_rtg) {
                $sql = $this->db
                            ->where('DATE(tgl_simpan) >=', $tgl_awal)
                            ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                            ->where_in('status', $status)
                            ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                            ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                            ->group_by('id_pasien')
                            ->get('v_medcheck_omset');

                redirect(base_url('laporan/data_omset_poli.php?case=per_rentang'.(!empty($tipe) ? '&tipe='.$tipe : '').(!empty($poli) ? '&poli='.$poli : '').(!empty($status) ? '&status='.general::enkrip($p) : '').'&tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir . '&jml=' . $sql->num_rows()));
            }
            
//            echo '<pre>';
//            print_r($sql->result());
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_omset_detail(){
        if (akses::aksesLogin() == TRUE) {
            $tgl     = $this->input->post('tgl');
            $tgl_rtg = $this->input->post('tgl_rentang');
            $poli    = $this->input->post('poli');
            $tipe    = $this->input->post('tipe');
            $status  = $this->input->post('status');
            
            $pengaturan     = $this->db->get('tbl_pengaturan')->row();

            $tgl_rentang    = explode('-', $tgl_rtg);
            $tgl_awal       = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir      = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $tgl_masuk      = $this->tanggalan->tgl_indo_sys($tgl);

            if (!empty($tgl)) {
                $sql = $this->db
                            ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                            ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                            ->like('status', $status, (!empty($status) ? 'none' : ''))
                            ->where('DATE(tgl_simpan)', $tgl_masuk)
                            ->get('v_medcheck_omset');
                
                redirect(base_url('laporan/data_omset_detail.php?case=per_tanggal'.(!empty($tipe) ? '&tipe='.$tipe : '').(!empty($poli) ? '&poli='.$poli : '').(!empty($status) ? '&status='.$status : '').'&tgl=' . $tgl_masuk . '&jml=' . $sql->num_rows()));
            } elseif ($tgl_rtg) {
                $sql = $this->db
                            ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                            ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                            ->like('status', $status, (!empty($status) ? 'none' : ''))
                            ->where('DATE(tgl_simpan) >=', $tgl_awal)
                            ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                            ->get('v_medcheck_omset');

                redirect(base_url('laporan/data_omset_detail.php?case=per_rentang'.(!empty($tipe) ? '&tipe='.$tipe : '').(!empty($poli) ? '&poli='.$poli : '').(!empty($status) ? '&status='.$status : '').'&tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir . '&jml=' . $sql->num_rows()));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_omset_jasa(){
        if (akses::aksesLogin() == TRUE) {
            $tgl     = $this->input->post('tgl');
            $tgl_rtg = $this->input->post('tgl_rentang');
            $poli    = $this->input->post('poli');
            $plat    = $this->input->post('platform');
            $idp     = $this->input->post('id_pasien');
            $pasien  = $this->input->post('pasien');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $sql_doc = $this->db->where('id', $dokter)->get('tbl_m_karyawan')->row();

            $tgl_rentang = explode('-', $tgl_rtg);
            $tgl_awal = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $tgl_masuk = $this->tanggalan->tgl_indo_sys($tgl);

            if (!empty($tgl)) {
                $sql = $this->db->where('DATE(tgl_bayar)', $tgl_masuk)->where('status_hps', '0')->where('status_bayar', '1')->like('metode', $plat, (!empty($plat) ? 'none' : ''))->like('pasien', $pasien, (!empty($pasien) ? 'none' : ''))->like('tipe', $poli, (!empty($poli) ? 'none' : ''))->get('tbl_trans_medcheck');
                redirect(base_url('laporan/data_omset_jasa.php?case=per_tanggal&poli='.$poli.(!empty($pasien) ? '&id_pasien='.$idp.'&pasien='.$pasien : '').(!empty($plat) ? '&plat='.$plat : '').'&tgl=' . $tgl_masuk . '&jml=' . $sql->num_rows()));
            } elseif ($tgl_rtg) {
                $sql = $this->db
                        ->like('pasien', $pasien, (!empty($pasien) ? 'none' : ''))
                        ->like('tipe', $poli, (!empty($poli) ? 'none' : ''))
                        ->like('metode', $plat, (!empty($plat) ? 'none' : ''))
                        ->where('status_hps', '0')
                        ->where('status_bayar', '1')
                        ->where('DATE(tgl_bayar) >=', $tgl_awal)
                        ->where('DATE(tgl_bayar) <=', $tgl_akhir)
                        ->get('tbl_trans_medcheck');
                
                redirect(base_url('laporan/data_omset_jasa.php?case=per_rentang&poli='.$poli.(!empty($pasien) ? '&id_pasien='.$idp.'&pasien='.$pasien : '').(!empty($plat) ? '&plat='.$plat : '').'&tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir . '&jml=' . $sql->num_rows()));
            }

//                echo '<pre>';
//                print_r($sql);
//                echo '</pre>';
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_omset_dokter(){
        if (akses::aksesLogin() == TRUE) {
            $tgl     = $this->input->post('tgl');
            $tgl_rtg = $this->input->post('tgl_rentang');
            $poli    = $this->input->post('poli');
            $plat    = $this->input->post('platform');
            $dokter  = $this->input->post('dokter');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $sql_doc = $this->db->where('id_user', $dokter)->get('tbl_m_karyawan')->row();

            $tgl_rentang = explode('-', $tgl_rtg);
            $tgl_awal = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $tgl_masuk = $this->tanggalan->tgl_indo_sys($tgl);

            if (!empty($tgl)) {
                $sql = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal')
                                ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                ->where('tbl_trans_medcheck.status_bayar', '1')
                                ->where('tbl_trans_medcheck_det.id_dokter', $dokter)
                                ->where('DATE(tbl_trans_medcheck_det.tgl_masuk)', $tgl_masuk)
                                ->where('tbl_trans_medcheck_det.status !=', '4')
                                ->order_by('tbl_trans_medcheck_det.tgl_simpan', 'ASC')
                                ->get('tbl_trans_medcheck_det');
                redirect(base_url('laporan/data_omset_dokter.php?case=per_tanggal&dokter='.general::enkrip($dokter).'&tgl=' . $tgl_masuk . '&jml=' . $sql->num_rows()));
            } elseif ($tgl_rtg) {
                $sql = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal')
                                ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                ->where('tbl_trans_medcheck.status_bayar', '1')
                                ->where('tbl_trans_medcheck_det.id_dokter', $dokter)
                                ->where('DATE(tbl_trans_medcheck_det.tgl_masuk) >=', $tgl_awal)
                                ->where('DATE(tbl_trans_medcheck_det.tgl_masuk) <=', $tgl_akhir)
                                ->where('tbl_trans_medcheck_det.status !=', '4')
                                ->order_by('tbl_trans_medcheck_det.tgl_simpan', 'ASC')
                                ->get('tbl_trans_medcheck_det');
                
                redirect(base_url('laporan/data_omset_dokter.php?case=per_rentang&dokter='.general::enkrip($dokter).'&tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir . '&jml=' . $sql->num_rows()));
            }

//            echo $dokter;
//                echo '<pre>';
//                print_r($sql->num_rows());
//                echo '</pre>';
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_stok(){
        if (akses::aksesLogin() == TRUE) {
            $st     = $this->input->post('st');
            $stok   = $this->input->post('stok');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('st', 'ST', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'st'            => form_error('st'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('laporan/data_stok.php'));
            } else {
                switch ($st) {
                    case '0' :
                        $stp = '';
                        $sql_stok = $this->db
                                 ->where('status_subt', '0')
                                 ->get('tbl_m_produk');
                        break;
                    
                    case '1' :
                        $stp = '<';
                        $sql_stok = $this->db
                                 ->where('status_subt', '1')
//                                 ->where('jml'.(isset($stp) ? ' '.$stp : ''), $stok)
                                 ->get('tbl_m_produk');
                        break;

                    case '2' :
                        $stp = '';
                        $sql_stok = $this->db
//                                 ->where('status_subt', '1')
//                                 ->where('jml'.(isset($stp) ? ' '.$stp : ''), $stok)
                                 ->get('tbl_m_produk');
                        break;

                    case '3' :
                        $stp = '>';
                        $sql_stok = $this->db
                                 ->where('status', '4')
                                 ->where('jml'.(isset($stp) ? ' '.$stp : ''), $stok)
                                 ->get('tbl_m_produk');
                        break;
                }
                
                
                redirect(base_url('laporan/data_stok.php?tipe='.$st.'&stok='.$stok.'&jml='.$sql_stok->num_rows()));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_stok_masuk(){
        if (akses::aksesLogin() == TRUE) {
            $tgl     = $this->input->post('tgl');
            $tgl_rtg = $this->input->post('tgl_rentang');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $sql_doc = $this->db->where('id', $dokter)->get('tbl_m_karyawan')->row();

            $tgl_rentang = explode('-', $tgl_rtg);
            $tgl_awal = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $tgl_masuk = $this->tanggalan->tgl_indo_sys($tgl);

            if (!empty($tgl)) {
                $sql = $this->db->select('tbl_trans_beli_det.kode')
                        ->join('tbl_trans_beli', 'tbl_trans_beli.id=tbl_trans_beli_det.id_pembelian')
                        ->where('DATE(tbl_trans_beli.tgl_masuk)', $tgl_masuk)
                        ->get('tbl_trans_beli_det');
                
                redirect(base_url('laporan/data_stok_masuk.php?case=per_tanggal&tgl=' . $tgl_masuk . '&jml=' . $sql->num_rows()));
            } elseif ($tgl_rtg) {
                $sql = $this->db->select('tbl_trans_beli_det.kode')
                        ->join('tbl_trans_beli', 'tbl_trans_beli.id=tbl_trans_beli_det.id_pembelian')
                        ->where('DATE(tbl_trans_beli.tgl_masuk) >=', $tgl_awal)
                        ->where('DATE(tbl_trans_beli.tgl_masuk) <=', $tgl_akhir)
                        ->get('tbl_trans_beli_det');
                redirect(base_url('laporan/data_stok_masuk.php?case=per_rentang&tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir . '&jml=' . $sql->num_rows()));
            }

//                echo '<pre>';
//                print_r($sql);
//                echo '</pre>';
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_stok_telusur(){
        if (akses::aksesLogin() == TRUE) {
            $item           = $this->input->post('item');
            $tgl            = $this->input->post('tgl');
            $tgl_rtg        = $this->input->post('tgl_rentang');            
            $gdg            = $this->input->post('gudang');            
            $pengaturan     = $this->db->get('tbl_pengaturan')->row();

            $tgl_rentang    = explode('-', $tgl_rtg);
            $tgl_awal       = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir      = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $tgl_masuk      = $this->tanggalan->tgl_indo_sys($tgl);
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('item', 'Item', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'item' => form_error('item'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('laporan/data_stok_telusur.php'));
            } else {
                
                if(!empty($tgl)){
                    
                    redirect(base_url('laporan/data_stok_telusur.php?act=per_tanggal&id='.general::enkrip($item).'&tgl='.$tgl_masuk.'&id_gudang='.$gdg));
                }elseif($tgl_rtg){
                    
                    redirect(base_url('laporan/data_stok_telusur.php?act=per_rentang&id='.general::enkrip($item).'&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&id_gudang='.$gdg));
                }else{
                    redirect(base_url('laporan/data_stok_telusur.php'));
                }
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_stok_keluar(){
        if (akses::aksesLogin() == TRUE) {
            $tgl     = $this->input->post('tgl');
            $tgl_rtg = $this->input->post('tgl_rentang');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $sql_doc = $this->db->where('id', $dokter)->get('tbl_m_karyawan')->row();

            $tgl_rentang = explode('-', $tgl_rtg);
            $tgl_awal = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $tgl_masuk = $this->tanggalan->tgl_indo_sys($tgl);

            if (!empty($tgl)) {
                $sql = $this->db->select('tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item')
                        ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                        ->where('tbl_trans_medcheck.status_hps', '0')
                        ->where('tbl_trans_medcheck.status_bayar', '1')
                        ->where('DATE(tbl_trans_medcheck_det.tgl_simpan)', $tgl)
                        ->where('tbl_trans_medcheck_det.status', '4')
                        ->get('tbl_trans_medcheck_det');
                
                redirect(base_url('laporan/data_stok_keluar.php?case=per_tanggal&tgl=' . $tgl_masuk . '&jml=' . $sql->num_rows()));
            } elseif ($tgl_rtg) {
                $sql = $this->db->select('tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item')
                        ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                        ->where('tbl_trans_medcheck.status_hps', '0')
                        ->where('tbl_trans_medcheck.status_bayar', '1')
                        ->where('DATE(tbl_trans_medcheck_det.tgl_simpan) >=', $tgl_awal)
                        ->where('DATE(tbl_trans_medcheck_det.tgl_simpan) <=', $tgl_akhir)
                        ->where('tbl_trans_medcheck_det.status', '4')
                        ->get('tbl_trans_medcheck_det');
                redirect(base_url('laporan/data_stok_keluar.php?case=per_rentang&tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir . '&jml=' . $sql->num_rows()));
            }

//                echo '<pre>';
//                print_r($sql);
//                echo '</pre>';
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_stok_mutasi(){
        if (akses::aksesLogin() == TRUE) {
            $tgl            = $this->input->post('tgl');
            $tgl_rtg        = $this->input->post('tgl_rentang');
            
            $pengaturan     = $this->db->get('tbl_pengaturan')->row();

            $sql_doc        = $this->db->where('id', $dokter)->get('tbl_m_karyawan')->row();

            $tgl_rentang    = explode('-', $tgl_rtg);
            $tgl_awal       = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir      = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $tgl_masuk      = $this->tanggalan->tgl_indo_sys($tgl);

            if (!empty($tgl)) {
                $sql = $this->db->where('DATE(tgl_simpan)', $tgl)
                                ->get('v_laporan_stok');
                
                redirect(base_url('laporan/data_stok_mutasi.php?case=per_tanggal&tgl=' . $tgl_masuk . '&jml=' . $sql->num_rows()));
            } elseif ($tgl_rtg) {
                $sql = $this->db
//                                ->where('DATE(tgl_simpan) >=', $tgl_awal)
//                                ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                ->get('v_produk_stok');
                redirect(base_url('laporan/data_stok_mutasi.php?case=per_rentang&tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir . '&jml=' . $sql->num_rows()));
            }

//                echo '<pre>';
//                print_r($sql);
//                echo '</pre>';
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_stok_keluar_resep(){
        if (akses::aksesLogin() == TRUE) {
            $tgl     = $this->input->post('tgl');
            $tgl_rtg = $this->input->post('tgl_rentang');
            $dokter  = $this->input->post('dokter');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $sql_doc = $this->db->where('id_user', $dokter)->get('tbl_m_karyawan')->row();

            $tgl_rentang = explode('-', $tgl_rtg);
            $tgl_awal = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $tgl_masuk = $this->tanggalan->tgl_indo_sys($tgl);

            if (!empty($tgl)) {
                $sql = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_resep_det.kode, tbl_trans_medcheck_resep_det.item, tbl_trans_medcheck_resep_det.dosis, tbl_trans_medcheck_resep_det.dosis_ket, tbl_trans_medcheck_resep_det.keterangan, tbl_trans_medcheck_resep_det.harga, tbl_trans_medcheck_resep_det.jml')
                                ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resep_det.id_medcheck')
                                ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_resep_det.id_item_kat')
                                ->where('tbl_trans_medcheck.status_bayar', '1')
                                ->where('tbl_trans_medcheck_resep_det.id_user', $dokter)
                                ->where('DATE(tbl_trans_medcheck_resep_det.tgl_simpan)', $tgl_masuk)
                                ->order_by('tbl_trans_medcheck_resep_det.tgl_simpan', 'ASC')
                                ->get('tbl_trans_medcheck_resep_det');
                redirect(base_url('laporan/data_stok_keluar_resep.php?case=per_tanggal&dokter='.general::enkrip($dokter).'&tgl=' . $tgl_masuk . '&jml=' . $sql->num_rows()));
            } elseif ($tgl_rtg) {
                $sql = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_resep_det.kode, tbl_trans_medcheck_resep_det.item, tbl_trans_medcheck_resep_det.dosis, tbl_trans_medcheck_resep_det.dosis_ket, tbl_trans_medcheck_resep_det.keterangan, tbl_trans_medcheck_resep_det.harga, tbl_trans_medcheck_resep_det.jml')
                                ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resep_det.id_medcheck')
                                ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_resep_det.id_item_kat')
                                ->where('tbl_trans_medcheck.status_bayar', '1')
                                ->where('tbl_trans_medcheck_resep_det.id_user', $dokter)
                                ->where('DATE(tbl_trans_medcheck_resep_det.tgl_simpan) >=', $tgl_awal)
                                ->where('DATE(tbl_trans_medcheck_resep_det.tgl_simpan) <=', $tgl_akhir)
                                ->order_by('tbl_trans_medcheck_resep_det.tgl_simpan', 'ASC')
                                ->get('tbl_trans_medcheck_resep_det');
                
                redirect(base_url('laporan/data_stok_keluar_resep.php?case=per_rentang&dokter='.general::enkrip($dokter).'&tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir . '&jml=' . $sql->num_rows()));
            }

//            echo $dokter;
//                echo '<pre>';
//                print_r($sql->result());
//                echo '</pre>';
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    

    public function set_data_pasien(){
        if (akses::aksesLogin() == TRUE) {
            $tgl        = $this->input->post('tgl');
            $tipe       = $this->input->post('tipe');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $tgln       = explode('-', $tgl);
            $tgl_awal   = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);

            if (!empty($tgl)) {
                $sql = $this->db->where('tbl_m_pasien.no_hp !=', '')
                                ->where('DAY(tbl_m_pasien.tgl_lahir)', $tgln[0])
                                ->where('MONTH(tbl_m_pasien.tgl_lahir)', $tgln[1])
                                ->get('tbl_m_pasien');
                
                # Lempar ke halaman laporan
                redirect(base_url('laporan/data_pasien.php?case=per_tanggal'.'&tgl='.$tgln[0].'&bln='.$tgln[1].'&jml=' . $sql->num_rows()));
            }else{                
                $sql = $this->db->get('tbl_m_pasien');
                
                # Lempar ke halaman laporan
                redirect(base_url('laporan/data_pasien.php?jml=' . $sql->num_rows()));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_data_pasien_kunj(){
        if (akses::aksesLogin() == TRUE) {
            $tgl        = $this->input->post('tgl');
            $tgl_rtg    = $this->input->post('tgl_rentang');
            $poli       = $this->input->post('poli');
            $tipe       = $this->input->post('tipe');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $tgl_rentang    = explode('-', $tgl_rtg);
            $tgl_awal       = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir      = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $tgl_masuk      = $this->tanggalan->tgl_indo_sys($tgl);

            if (!empty($tgl)) {
                $sql = $this->db->where('DATE(tgl_simpan)', $tgl_masuk)
                                ->like('id_poli', $poli)
                                ->like('tipe', $tipe)
                                ->get('v_medcheck_visit');
                
                redirect(base_url('laporan/data_visit_pasien.php?case=per_tanggal&tgl=' . $tgl_masuk.(!empty($poli) ? '&poli='.$poli : '').(!empty($tipe) ? '&tipe='.$tipe : '') . '&jml=' . $sql->num_rows()));
            } elseif ($tgl_rtg) {
                $sql = $this->db->where('DATE(tgl_simpan) >=', $tgl_awal)
                                ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                ->like('id_poli', $poli)
                                ->like('tipe', $tipe)
                                ->get('v_medcheck_visit');

                redirect(base_url('laporan/data_visit_pasien.php?case=per_rentang&tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir.(!empty($poli) ? '&poli='.$poli : '').(!empty($tipe) ? '&tipe='.$tipe : ''). '&jml=' . $sql->num_rows()));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_data_karyawan_ultah(){
        if (akses::aksesLogin() == TRUE) {
            $tgl        = $this->input->post('tgl');
            $tgl_rtg    = $this->input->post('tgl_rentang');
            $tipe       = $this->input->post('tipe');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $tgln       = explode('-', $tgl);
            $tgl_rentang= explode('-', $tgl_rtg);
            $tgl_awal   = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir  = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $day1       = explode('-', $tgl_awal);
            $day2       = explode('-', $tgl_akhir);
            

            if (!empty($tgl)) {
                $sql = $this->db->where('tbl_m_karyawan.no_hp !=', '')
                                ->where('DAY(tbl_m_karyawan.tgl_lahir)', $tgln[0])
                                ->where('MONTH(tbl_m_karyawan.tgl_lahir)', $tgln[1])
                                ->get('tbl_m_karyawan');
                
                # Lempar ke halaman laporan
                redirect(base_url('laporan/data_karyawan_ultah.php?case=per_tanggal'.'&tgl='.$tgln[0].'&bln='.$tgln[1].'&jml=' . $sql->num_rows()));
            }else{
                $sql = $this->db->where('tbl_m_karyawan.no_hp !=', '')
                                ->where('DAY(tbl_m_karyawan.tgl_lahir) >=', $day1[2])
                                ->where('MONTH(tbl_m_karyawan.tgl_lahir) >=', $day1[1])
                                ->where('DAY(tbl_m_karyawan.tgl_lahir) <=', $day2[2])
                                ->where('MONTH(tbl_m_karyawan.tgl_lahir) <=', $day2[1])
                                ->get('tbl_m_karyawan');
                
                # Lempar ke halaman laporan
                redirect(base_url('laporan/data_karyawan_ultah.php?case=per_rentang&hr_awal='.$day1[2].'&hr_akhir='.$day2[2].'&bln_awal='.$day1[1].'&bln_akhir='.$day2[1].'&jml=' . $sql->num_rows()));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_data_tracer(){
        if (akses::aksesLogin() == TRUE) {
            $tipe           = $this->input->post('tipe');
            $tgl            = $this->input->post('tgl');
            $tgl_rtg        = $this->input->post('tgl_rentang');
            
            $pengaturan     = $this->db->get('tbl_pengaturan')->row();

            $tgln           = explode('-', $tgl);
            $tgl_rentang    = explode('-', $tgl_rtg);
            $tgl_awal       = $this->tanggalan->tgl_indo_sys($tgl_rentang[0]);
            $tgl_akhir      = $this->tanggalan->tgl_indo_sys($tgl_rentang[1]);
            $tgl_masuk      = $this->tanggalan->tgl_indo_sys($tgl);
            

            if(!empty($tgl)) {
                $sql = $this->db
                        ->where('DATE(tgl_simpan)', $tgl_masuk)
                        ->get('v_medcheck_tracer');

                redirect(base_url('laporan/data_tracer.php?case=per_tanggal&tgl=' . $tgl_masuk . '&jml=' . $sql->num_rows()));
            } elseif ($tgl_rtg) {
                $sql = $this->db
                        ->where('DATE(tgl_simpan) >=', $tgl_awal)
                        ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                        ->get('v_medcheck_tracer');
                
                redirect(base_url('laporan/data_tracer.php?case=per_rentang&tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir . '&jml=' . $sql->num_rows()));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    
    


    public function xls_data_remunerasi(){
        if (akses::aksesLogin() == TRUE) {
            $dokter     = $this->input->get('id_dokter');
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $tipe       = $this->input->get('tipe');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            if($jml > 0){
                $sql_doc = $this->db->where('id_user', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
                
                switch ($case){
                    case 'per_tanggal':
                        $sql_remun      = $this->db
                                               ->where('DATE(tgl_simpan)', $tgl)
                                               ->like('status_produk', (!empty($tipe) ? $tipe : ''))
                                               ->like('id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
                                               ->get('v_medcheck_remun')->result();
                        break;
                    
                    case 'per_rentang':
                        $sql_remun      = $this->db
                                               ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                               ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                               ->like('status_produk', (!empty($tipe) ? $tipe : ''))
                                               ->like('id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
                                               ->get('v_medcheck_remun')->result();
                        
//                        $sql_remun     = $this->db->select('tbl_trans_medcheck_remun.id, tbl_trans_medcheck_remun.tgl_simpan, tbl_trans_medcheck_remun.harga, tbl_trans_medcheck_remun.remun_nom, tbl_trans_medcheck_remun.remun_subtotal, tbl_trans_medcheck_remun.remun_perc, tbl_m_pasien.nama_pgl, tbl_trans_medcheck.id, tbl_trans_medcheck_remun.id_dokter, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.tipe, tbl_m_poli.lokasi, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.jml')
//                                                          ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_remun.id_medcheck')
//                                                          ->join('tbl_trans_medcheck_det', 'tbl_trans_medcheck_det.id=tbl_trans_medcheck_remun.id_medcheck_det')
//                                                          ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
//                                                          ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
//                                                          ->like('tbl_trans_medcheck_remun.id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
//                                                          ->where('DATE(tbl_trans_medcheck_remun.tgl_simpan) >=', $tgl_awal)
//                                                          ->where('DATE(tbl_trans_medcheck_remun.tgl_simpan) <=', $tgl_akhir)
//                                                      ->get('tbl_trans_medcheck_remun')->result();
//                        
//                        $sql_remun_row = $this->db->select_sum('tbl_trans_medcheck_remun.remun_nom')
//                                                          ->where('tbl_trans_medcheck_remun.id_dokter', $sql_doc->id_user)
//                                                          ->where('DATE(tbl_trans_medcheck_remun.tgl_simpan) >=', $tgl_awal)
//                                                          ->where('DATE(tbl_trans_medcheck_remun.tgl_simpan) <=', $tgl_akhir)->get('tbl_trans_medcheck_remun')->row();
                        break;
                }
            }

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'No.')
                    ->setCellValue('B4', 'Tgl')
                    ->setCellValue('C4', 'No. Faktur')
                    ->setCellValue('D4', 'Dokter')
                    ->setCellValue('E4', 'Tindakan')
                    ->setCellValue('F4', 'Pasien')
                    ->setCellValue('G4', 'Jml')
                    ->setCellValue('H4', 'Harga')
                    ->setCellValue('I4', 'Remunerasi')
                    ->setCellValue('J4', 'Subtotal');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(14);

            if(!empty($sql_remun)){
                $no    = 1;
                $cell  = 5;
                $total = 0;
                foreach ($sql_remun as $penjualan){
                    $dokter   = $this->db->where('id_user', $penjualan->id_dokter)->get('tbl_m_karyawan')->row();
                    $remun    = ($penjualan->remun_tipe == '2' ? $penjualan->remun_nom : (($penjualan->remun_perc / 100) * $penjualan->harga));
                    $total    = $total + $penjualan->remun_subtotal;

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':F'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$cell.':J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$cell.':J'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
               
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo5($penjualan->tgl_simpan))
                            ->setCellValue('C'.$cell, '#'.$penjualan->no_rm)
                            ->setCellValue('D'.$cell, (!empty($dokter->nama_dpn) ? $dokter->nama_dpn.' ' : '').$dokter->nama.(!empty($dokter->nama_blk) ? ', '.$dokter->nama_blk : ''))
                            ->setCellValue('E'.$cell, $penjualan->item)
                            ->setCellValue('F'.$cell, $penjualan->nama_pgl)
                            ->setCellValue('G'.$cell, $penjualan->jml)
                            ->setCellValue('H'.$cell, $penjualan->harga)
                            ->setCellValue('I'.$cell, $remun)
                            ->setCellValue('J'.$cell, $penjualan->remun_subtotal);
                    
                    $no++;
                    $cell++;
                }

                $sell1     = $cell;
                
                $objPHPExcel->getActiveSheet()->getStyle('J'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':J'.$sell1.'')->getFont()->setBold(TRUE);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':J'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sell1, 'TOTAL REMUNERASI')->mergeCells('A'.$sell1.':I'.$sell1.'')
                        ->setCellValue('J' . $sell1, $total);
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('LAP REMUNERASI');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");


            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_remunerasi_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_apresiasi(){
        if (akses::aksesLogin() == TRUE) {
            $dokter     = $this->input->get('id_dokter');
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $tipe       = $this->input->get('tipe');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            if($jml > 0){
                $sql_doc = $this->db->where('id_user', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
                
                switch ($case){
                    case 'per_tanggal':
                        $sql_apres     = $this->db->where('DATE(tgl_simpan)', $tgl)
                                                  ->like('status_produk', (!empty($tipe) ? $tipe : ''))
                                                  ->like('id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
                                                  ->get('v_medcheck_apres')->result();
                        break;
                    
                    case 'per_rentang':
                        $sql_apres     = $this->db->where('DATE(tgl_simpan) >=', $tgl_awal)
                                                  ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                                  ->like('status_produk', (!empty($tipe) ? $tipe : ''))
                                                  ->like('id_dokter', (!empty($sql_doc->id_user) ? $sql_doc->id_user : ''))
                                                  ->get('v_medcheck_apres')->result();
                        break;
                }
            }

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'No.')
                    ->setCellValue('B4', 'Tgl')
                    ->setCellValue('C4', 'No. Faktur')
                    ->setCellValue('D4', 'Dokter')
                    ->setCellValue('E4', 'Tindakan')
                    ->setCellValue('F4', 'Pasien')
                    ->setCellValue('G4', 'Jml')
                    ->setCellValue('H4', 'Harga')
                    ->setCellValue('I4', 'Apresiasi')
                    ->setCellValue('J4', 'Subtotal');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(14);

            if(!empty($sql_apres)){
                $no    = 1;
                $cell  = 5;
                $total = 0;
                foreach ($sql_apres as $penjualan){
                    $dokter   = $this->db->where('id_user', $penjualan->id_dokter)->get('tbl_m_karyawan')->row();
                    $apres    = ($penjualan->apres_tipe == '2' ? $penjualan->apres_nom : (($penjualan->apres_perc / 100) * $penjualan->harga));
                    $total    = $total + $penjualan->apres_subtotal;

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':F'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$cell.':J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$cell.':J'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
               
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo5($penjualan->tgl_simpan))
                            ->setCellValue('C'.$cell, '#'.$penjualan->no_rm)
                            ->setCellValue('D'.$cell, (!empty($dokter->nama_dpn) ? $dokter->nama_dpn.' ' : '').$dokter->nama.(!empty($dokter->nama_blk) ? ', '.$dokter->nama_blk : ''))
                            ->setCellValue('E'.$cell, $penjualan->item)
                            ->setCellValue('F'.$cell, $penjualan->nama_pgl)
                            ->setCellValue('G'.$cell, $penjualan->jml)
                            ->setCellValue('H'.$cell, $penjualan->harga)
                            ->setCellValue('I'.$cell, $apres)
                            ->setCellValue('J'.$cell, $penjualan->apres_subtotal);
                    
                    $no++;
                    $cell++;
                }

                $sell1     = $cell;
                
                $objPHPExcel->getActiveSheet()->getStyle('J'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':J'.$sell1.'')->getFont()->setBold(TRUE);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':J'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sell1, 'TOTAL APRESIASI')->mergeCells('A'.$sell1.':I'.$sell1.'')
                        ->setCellValue('J' . $sell1, $total);
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('LAP APRESIASI');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");



            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_apresiasi_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    

    public function xls_data_cuti(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $kary       = $this->input->get('id_kary');
            $tipe       = $this->input->get('tipe');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();
            $sql_kary   = $this->db->where('id_user', general::dekrip($kary))->get('tbl_m_karyawan')->row();

            
            switch ($case){
                case 'per_tanggal':
                    $sql = $this->db
                                    ->select('tbl_sdm_cuti.id, tbl_sdm_cuti.id_user, tbl_sdm_cuti.tgl_simpan, tbl_sdm_cuti.tgl_masuk, tbl_sdm_cuti.tgl_keluar, tbl_sdm_cuti.no_surat, tbl_sdm_cuti.keterangan, tbl_sdm_cuti.catatan, tbl_sdm_cuti.file_name, tbl_sdm_cuti.file_type, tbl_sdm_cuti.status, tbl_m_karyawan.nama_dpn, tbl_m_karyawan.nama, tbl_m_karyawan.nama_blk, tbl_m_karyawan.tgl_lahir, tbl_m_karyawan.alamat, tbl_m_karyawan.jns_klm, tbl_m_kategori_cuti.tipe')
                                    ->join('tbl_m_karyawan', 'tbl_m_karyawan.id=tbl_sdm_cuti.id_karyawan')
                                    ->join('tbl_m_kategori_cuti', 'tbl_m_kategori_cuti.id=tbl_sdm_cuti.id_kategori')
                                    ->where('tbl_sdm_cuti.id_kategori', $tipe)
                                    ->where('DATE(tbl_sdm_cuti.tgl_simpan)', $this->input->get('tgl'))
                                    ->like('tbl_m_karyawan.id', $sql_kary->id, (!empty($sql_kary->id) ? 'none' : ''))
                                    ->get('tbl_sdm_cuti')->result();
                    break;

                case 'per_rentang':
                    $sql = $this->db
                                    ->select('tbl_sdm_cuti.id, tbl_sdm_cuti.id_user, tbl_sdm_cuti.tgl_simpan, tbl_sdm_cuti.tgl_masuk, tbl_sdm_cuti.tgl_keluar, tbl_sdm_cuti.no_surat, tbl_sdm_cuti.keterangan, tbl_sdm_cuti.catatan, tbl_sdm_cuti.file_name, tbl_sdm_cuti.file_type, tbl_sdm_cuti.status, tbl_m_karyawan.nama_dpn, tbl_m_karyawan.nama, tbl_m_karyawan.nama_blk, tbl_m_karyawan.tgl_lahir, tbl_m_karyawan.alamat, tbl_m_karyawan.jns_klm, tbl_m_kategori_cuti.tipe')
                                    ->join('tbl_m_karyawan', 'tbl_m_karyawan.id=tbl_sdm_cuti.id_karyawan')
                                    ->join('tbl_m_kategori_cuti', 'tbl_m_kategori_cuti.id=tbl_sdm_cuti.id_kategori')
                                    ->where('tbl_sdm_cuti.id_kategori', $tipe)
                                    ->where('DATE(tbl_sdm_cuti.tgl_simpan) >=', $this->input->get('tgl_awal'))
                                    ->where('DATE(tbl_sdm_cuti.tgl_simpan) <=', $this->input->get('tgl_akhir'))
                                    ->like('tbl_m_karyawan.id', $sql_kary->id, (!empty($sql_kary->id) ? 'none' : ''))
                                    ->get('tbl_sdm_cuti')->result();
                    break;
            }

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'NO')
                    ->setCellValue('B1', 'NO SURAT')
                    ->setCellValue('C1', 'NAMA')
                    ->setCellValue('D1', 'ALASAN')
                    ->setCellValue('E1', 'WAKTU');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(80);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(80);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(80);

            if(!empty($sql)){
                $no    = 1;
                $cell  = 2;
                $total = 0;
                foreach ($sql as $cuti){
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':D'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, (!empty($cuti->no_surat) ? $cuti->no_surat : ''))
                            ->setCellValue('C'.$cell, (!empty($cuti->nama_dpn) ? $cuti->nama_dpn.' ' : '').$cuti->nama.(!empty($cuti->nama_blk) ? ', '.$cuti->nama_blk : ''))
                            ->setCellValue('D'.$cell, $cuti->keterangan)
                            ->setCellValue('E'.$cell, $this->tanggalan->jml_hari($cuti->tgl_masuk, $cuti->tgl_keluar).' Hari');

                    $no++;
                    $cell++;
                }

                $sell1     = $cell;
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Cuti');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");



            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_rekap_cuti_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function htm_data_omset(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $poli       = $this->input->get('poli');
            $tipe       = $this->input->get('tipe');
            $plat       = $this->input->get('plat');
            $pasien_id  = $this->input->get('id_pasien');
            $pasien     = $this->input->get('pasien');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            switch ($case) { 
                case 'per_tanggal':
                    $data['sql_omset'] = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_akun, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.pasien, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.metode, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi, tbl_m_kategori.keterangan AS kategori, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.id_item, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.kode AS kode_item, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.resep, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.disk1, tbl_trans_medcheck_det.disk2, tbl_trans_medcheck_det.disk3, tbl_trans_medcheck_det.diskon, tbl_trans_medcheck_det.potongan, tbl_trans_medcheck_det.subtotal, tbl_trans_medcheck_det.resep, CONCAT(tbl_m_pasien.kode_dpn,\'\',tbl_m_pasien.kode) AS "kode_pasien"', false)
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar)', $tgl)
                                                              ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))                                                              
                                                              ->like('tbl_trans_medcheck.pasien', $pasien)                                                              
                                                ->order_by('tbl_trans_medcheck.id', 'DESC')
                                                ->get('tbl_trans_medcheck_det')->result();
                        
                        $data['sql_omset_row'] = $this->db->select('SUM(tbl_trans_medcheck_det.jml * tbl_trans_medcheck_det.harga) AS jml_total, SUM(tbl_trans_medcheck_det.diskon) AS diskon, SUM(tbl_trans_medcheck_det.potongan) AS potongan, SUM(tbl_trans_medcheck_det.subtotal) AS jml_gtotal')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar)', $tgl)
                                                              ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.pasien', $pasien)
                                                        ->get('tbl_trans_medcheck_det')->row();
                    break;

                case 'per_rentang':
                        $data['sql_omset']    = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_akun, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.pasien, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.metode, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi, tbl_m_kategori.keterangan AS kategori, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.id_item, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.kode AS kode_item, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.resep, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.disk1, tbl_trans_medcheck_det.disk2, tbl_trans_medcheck_det.disk3, tbl_trans_medcheck_det.diskon, tbl_trans_medcheck_det.potongan, tbl_trans_medcheck_det.subtotal, tbl_trans_medcheck_det.resep, CONCAT(tbl_m_pasien.kode_dpn,\'\',tbl_m_pasien.kode) AS "kode_pasien"', false)
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
                                                              ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.pasien', $pasien) 
                                                ->order_by('tbl_trans_medcheck.id', 'DESC')
                                                ->get('tbl_trans_medcheck_det')->result();
                        
                        $data['sql_omset_row'] = $this->db->select('SUM(tbl_trans_medcheck_det.jml * tbl_trans_medcheck_det.harga) AS jml_total, SUM(tbl_trans_medcheck_det.diskon) AS diskon, SUM(tbl_trans_medcheck_det.potongan) AS potongan, SUM(tbl_trans_medcheck_det.subtotal) AS jml_gtotal')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
                                                              ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.pasien', $pasien)
                                                        ->get('tbl_trans_medcheck_det')->row();
                    break;
            }
            
            /* Load view tampilan */
//            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_omset_htm_zahir', $data);
//            $this->load->view('admin-lte-3/6_bawah', $data);

//            $objPHPExcel = new PHPExcel();
//
//            // Header Tabel Nota
//            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(TRUE);
//
//            $objPHPExcel->setActiveSheetIndex(0)
//                    ->setCellValue('A4', 'No.')
//                    ->setCellValue('B4', 'Tgl')
//                    ->setCellValue('C4', 'Pasien')
//                    ->setCellValue('D4', 'Tipe')
//                    ->setCellValue('E4', 'Dokter')
//                    ->setCellValue('F4', 'No. Faktur')
//                    ->setCellValue('G4', 'Qty')
//                    ->setCellValue('H4', 'Kode')
//                    ->setCellValue('I4', 'Item')
//                    ->setCellValue('J4', 'Group')
//                    ->setCellValue('K4', 'Harga')
//                    ->setCellValue('L4', 'Subtotal')
//                    ->setCellValue('M4', 'Jasa Dokter')
//                    ->setCellValue('N4', 'Total Jasa');
//
//            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(65);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(45);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(35);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(14);
//
//            if(!empty($sql_omset)){
//                $no    = 1;
//                $cell  = 5;
//                $total = 0;
//                foreach ($sql_omset as $penjualan){
//                    $remun   = $this->db->where('id_medcheck_det', $penjualan->id_medcheck_det)->get('tbl_trans_medcheck_remun')->row();
//                    $dokter  = $this->db->where('id_user', $penjualan->id_dokter)->get('tbl_m_karyawan')->row();
//                    $item    = $this->db->where('id', $penjualan->id_item)->get('tbl_m_produk')->row();
//                    $remun_nom   = ($remun->remun_tipe == '2' ? $remun->remun_nom : (($remun->remun_perc / 100) * $penjualan->harga));
//                    $total   = $total + $penjualan->subtotal;
//                    $subtot  = $penjualan->harga * $penjualan->jml;
//
//                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                    $objPHPExcel->getActiveSheet()->getStyle('I'.$cell)->getAlignment()->setWrapText(true);
//
//                    $rsp = "\n";
//                    foreach (json_decode($penjualan->resep) as $resep){
//                        $rsp .= ' - '.$resep->item.' ['.$resep->jml.' '.$resep->satuan.']'."\n"; 
//                    }
//                    
//                    $objPHPExcel->setActiveSheetIndex(0)
//                            ->setCellValue('A'.$cell, $no)
//                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo5($penjualan->tgl_simpan))
//                            ->setCellValue('C'.$cell, $penjualan->nama_pgl)
//                            ->setCellValue('D'.$cell, general::status_rawat2($penjualan->tipe))
//                            ->setCellValue('E'.$cell, $dokter->nama)
//                            ->setCellValue('F'.$cell, $penjualan->no_rm)
//                            ->setCellValue('G'.$cell, (float)$penjualan->jml)
//                            ->setCellValue('H'.$cell, $item->kode)
//                            ->setCellValue('I'.$cell, $penjualan->item.(!empty($penjualan->resep) ? $rsp : ''))
//                            ->setCellValue('J'.$cell, $penjualan->kategori)
//                            ->setCellValue('K'.$cell, $penjualan->harga)
//                            ->setCellValue('L'.$cell, $subtot)
//                            ->setCellValue('M'.$cell, $remun_nom)
//                            ->setCellValue('N'.$cell, $remun->remun_subtotal);
//
//                    $no++;
//                    $cell++;
//                }
//
//                $sell1     = $cell;
//                
//                $objPHPExcel->getActiveSheet()->getStyle('L'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1.'')->getFont()->setBold(TRUE);
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $objPHPExcel->setActiveSheetIndex(0)
//                        ->setCellValue('A' . $sell1, '')->mergeCells('A'.$sell1.':K'.$sell1.'')
//                        ->setCellValue('L' . $sell1, $sql_omset_row->jml_gtotal);
//            }
//
//            // Rename worksheet
//            $objPHPExcel->getActiveSheet()->setTitle('Lap Omset');
//
//            /** Page Setup * */
//            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
//            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
//
//            /* -- Margin -- */
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setTop(0.25);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setRight(0);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setLeft(0);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setFooter(0);
//

//            /** Page Setup * */
//            // Set document properties
//            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
//                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
//                    ->setTitle("Stok")
//                    ->setSubject("Aplikasi Bengkel POS")
//                    ->setDescription("Kunjungi http://tigerasoft.co.id")
//                    ->setKeywords("Pasifik POS")
//                    ->setCategory("Untuk mencetak nota dot matrix");
//
//
//
//            // Redirect output to a client’s web browser (Excel5)
//            header('Content-Type: application/vnd.ms-excel');
//            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//            header('Content-Disposition: attachment;filename="data_omset_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');
//
//            // If you're serving to IE over SSL, then the following may be needed
//            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
//            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
//            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//            header('Pragma: public'); // HTTP/1.0
//
//            ob_clean();
//            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//            $objWriter->save('php://output');
//            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function htm_data_omset_jasa(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $poli       = $this->input->get('poli');
            $tipe       = $this->input->get('tipe');
            $plat       = $this->input->get('plat');
            $pasien_id  = $this->input->get('id_pasien');
            $pasien     = $this->input->get('pasien');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            switch ($case) {
                case 'per_tanggal':
                    $data['sql_omset'] = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_akun, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.pasien, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.metode, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi, tbl_m_kategori.keterangan AS kategori, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.id_item, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.kode AS kode_item, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.resep, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.disk1, tbl_trans_medcheck_det.disk2, tbl_trans_medcheck_det.disk3, tbl_trans_medcheck_det.potongan, tbl_trans_medcheck_det.subtotal, tbl_trans_medcheck_det.resep, CONCAT(tbl_m_pasien.kode_dpn,\'\',tbl_m_pasien.kode) AS "kode_pasien"', false)
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('tbl_trans_medcheck_det.status', '2')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar)', $tgl)
                                                              ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.tipe', $poli, (!empty($poli) ? 'none' : ''))                                                              
                                                              ->like('tbl_trans_medcheck.pasien', $pasien, (!empty($pasien) ? 'none' : ''))                                                              
                                                ->order_by('tbl_trans_medcheck.id', 'DESC')
                                                ->get('tbl_trans_medcheck_det')->result();
                    break;

                case 'per_rentang':
                        $data['sql_omset']    = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_akun, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.pasien, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.metode, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi, tbl_m_kategori.keterangan AS kategori, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.id_item, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.kode AS kode_item, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.resep, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.disk1, tbl_trans_medcheck_det.disk2, tbl_trans_medcheck_det.disk3, tbl_trans_medcheck_det.potongan, tbl_trans_medcheck_det.subtotal, tbl_trans_medcheck_det.resep, CONCAT(tbl_m_pasien.kode_dpn,\'\',tbl_m_pasien.kode) AS "kode_pasien"', false)
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('tbl_trans_medcheck_det.status', '2')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
                                                              ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.tipe', $poli, (!empty($poli) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.pasien', $pasien, (!empty($pasien) ? 'none' : '')) 
                                                ->order_by('tbl_trans_medcheck.id', 'DESC')
                                                ->get('tbl_trans_medcheck_det')->result();
                    break;
            }
            
            /* Load view tampilan */
//            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_omset_htm_jasa', $data);
//            $this->load->view('admin-lte-3/6_bawah', $data);

//            $objPHPExcel = new PHPExcel();
//
//            // Header Tabel Nota
//            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(TRUE);
//
//            $objPHPExcel->setActiveSheetIndex(0)
//                    ->setCellValue('A4', 'No.')
//                    ->setCellValue('B4', 'Tgl')
//                    ->setCellValue('C4', 'Pasien')
//                    ->setCellValue('D4', 'Tipe')
//                    ->setCellValue('E4', 'Dokter')
//                    ->setCellValue('F4', 'No. Faktur')
//                    ->setCellValue('G4', 'Qty')
//                    ->setCellValue('H4', 'Kode')
//                    ->setCellValue('I4', 'Item')
//                    ->setCellValue('J4', 'Group')
//                    ->setCellValue('K4', 'Harga')
//                    ->setCellValue('L4', 'Subtotal')
//                    ->setCellValue('M4', 'Jasa Dokter')
//                    ->setCellValue('N4', 'Total Jasa');
//
//            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(65);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(45);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(35);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(14);
//
//            if(!empty($sql_omset)){
//                $no    = 1;
//                $cell  = 5;
//                $total = 0;
//                foreach ($sql_omset as $penjualan){
//                    $remun   = $this->db->where('id_medcheck_det', $penjualan->id_medcheck_det)->get('tbl_trans_medcheck_remun')->row();
//                    $dokter  = $this->db->where('id_user', $penjualan->id_dokter)->get('tbl_m_karyawan')->row();
//                    $item    = $this->db->where('id', $penjualan->id_item)->get('tbl_m_produk')->row();
//                    $remun_nom   = ($remun->remun_tipe == '2' ? $remun->remun_nom : (($remun->remun_perc / 100) * $penjualan->harga));
//                    $total   = $total + $penjualan->subtotal;
//                    $subtot  = $penjualan->harga * $penjualan->jml;
//
//                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                    $objPHPExcel->getActiveSheet()->getStyle('I'.$cell)->getAlignment()->setWrapText(true);
//
//                    $rsp = "\n";
//                    foreach (json_decode($penjualan->resep) as $resep){
//                        $rsp .= ' - '.$resep->item.' ['.$resep->jml.' '.$resep->satuan.']'."\n"; 
//                    }
//                    
//                    $objPHPExcel->setActiveSheetIndex(0)
//                            ->setCellValue('A'.$cell, $no)
//                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo5($penjualan->tgl_simpan))
//                            ->setCellValue('C'.$cell, $penjualan->nama_pgl)
//                            ->setCellValue('D'.$cell, general::status_rawat2($penjualan->tipe))
//                            ->setCellValue('E'.$cell, $dokter->nama)
//                            ->setCellValue('F'.$cell, $penjualan->no_rm)
//                            ->setCellValue('G'.$cell, (float)$penjualan->jml)
//                            ->setCellValue('H'.$cell, $item->kode)
//                            ->setCellValue('I'.$cell, $penjualan->item.(!empty($penjualan->resep) ? $rsp : ''))
//                            ->setCellValue('J'.$cell, $penjualan->kategori)
//                            ->setCellValue('K'.$cell, $penjualan->harga)
//                            ->setCellValue('L'.$cell, $subtot)
//                            ->setCellValue('M'.$cell, $remun_nom)
//                            ->setCellValue('N'.$cell, $remun->remun_subtotal);
//
//                    $no++;
//                    $cell++;
//                }
//
//                $sell1     = $cell;
//                
//                $objPHPExcel->getActiveSheet()->getStyle('L'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1.'')->getFont()->setBold(TRUE);
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $objPHPExcel->setActiveSheetIndex(0)
//                        ->setCellValue('A' . $sell1, '')->mergeCells('A'.$sell1.':K'.$sell1.'')
//                        ->setCellValue('L' . $sell1, $sql_omset_row->jml_gtotal);
//            }
//
//            // Rename worksheet
//            $objPHPExcel->getActiveSheet()->setTitle('Lap Omset');
//
//            /** Page Setup * */
//            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
//            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
//
//            /* -- Margin -- */
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setTop(0.25);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setRight(0);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setLeft(0);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setFooter(0);
//

//            /** Page Setup * */
//            // Set document properties
//            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
//                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
//                    ->setTitle("Stok")
//                    ->setSubject("Aplikasi Bengkel POS")
//                    ->setDescription("Kunjungi http://tigerasoft.co.id")
//                    ->setKeywords("Pasifik POS")
//                    ->setCategory("Untuk mencetak nota dot matrix");
//
//
//
//            // Redirect output to a client’s web browser (Excel5)
//            header('Content-Type: application/vnd.ms-excel');
//            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//            header('Content-Disposition: attachment;filename="data_omset_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');
//
//            // If you're serving to IE over SSL, then the following may be needed
//            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
//            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
//            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//            header('Pragma: public'); // HTTP/1.0
//
//            ob_clean();
//            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//            $objWriter->save('php://output');
//            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function htm_data_omset_dokter(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $poli       = $this->input->get('poli');
            $tipe       = $this->input->get('tipe');
            $plat       = $this->input->get('plat');
            $dokter     = $this->input->get('dokter');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            switch ($case) {
                case 'per_tanggal':
                    $data['sql_omset'] = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_akun, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.pasien, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.metode, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi, tbl_m_kategori.keterangan AS kategori, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.id_item, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.kode AS kode_item, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.resep, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.disk1, tbl_trans_medcheck_det.disk2, tbl_trans_medcheck_det.disk3, tbl_trans_medcheck_det.potongan, tbl_trans_medcheck_det.subtotal, tbl_trans_medcheck_det.resep, CONCAT(tbl_m_pasien.kode_dpn,\'\',tbl_m_pasien.kode) AS "kode_pasien"', false)
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('tbl_trans_medcheck_det.status', '2')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar)', $tgl)
//                                                              ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
//                                                              ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
//                                                              ->like('tbl_trans_medcheck.tipe', $poli, (!empty($poli) ? 'none' : ''))                                                              
                                                              ->where('tbl_trans_medcheck_det.id_dokter', general::dekrip($dokter))                                                              
                                                ->order_by('tbl_trans_medcheck.id', 'DESC')
                                                ->get('tbl_trans_medcheck_det')->result();
                    break;

                case 'per_rentang':
                        $data['sql_omset']    = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_akun, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.pasien, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.metode, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi, tbl_m_kategori.keterangan AS kategori, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.id_item, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.kode AS kode_item, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.resep, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.disk1, tbl_trans_medcheck_det.disk2, tbl_trans_medcheck_det.disk3, tbl_trans_medcheck_det.potongan, tbl_trans_medcheck_det.subtotal, tbl_trans_medcheck_det.resep, CONCAT(tbl_m_pasien.kode_dpn,\'\',tbl_m_pasien.kode) AS "kode_pasien"', false)
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('tbl_trans_medcheck_det.status', '2')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
                                                              ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.tipe', $poli, (!empty($poli) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.pasien', $pasien, (!empty($pasien) ? 'none' : '')) 
                                                ->order_by('tbl_trans_medcheck.id', 'DESC')
                                                ->get('tbl_trans_medcheck_det')->result();
                    break;
            }
            
            /* Load view tampilan */
//            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_omset_htm_dokter', $data);
//            $this->load->view('admin-lte-3/6_bawah', $data);

//            $objPHPExcel = new PHPExcel();
//
//            // Header Tabel Nota
//            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(TRUE);
//
//            $objPHPExcel->setActiveSheetIndex(0)
//                    ->setCellValue('A4', 'No.')
//                    ->setCellValue('B4', 'Tgl')
//                    ->setCellValue('C4', 'Pasien')
//                    ->setCellValue('D4', 'Tipe')
//                    ->setCellValue('E4', 'Dokter')
//                    ->setCellValue('F4', 'No. Faktur')
//                    ->setCellValue('G4', 'Qty')
//                    ->setCellValue('H4', 'Kode')
//                    ->setCellValue('I4', 'Item')
//                    ->setCellValue('J4', 'Group')
//                    ->setCellValue('K4', 'Harga')
//                    ->setCellValue('L4', 'Subtotal')
//                    ->setCellValue('M4', 'Jasa Dokter')
//                    ->setCellValue('N4', 'Total Jasa');
//
//            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(65);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(45);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(35);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(14);
//
//            if(!empty($sql_omset)){
//                $no    = 1;
//                $cell  = 5;
//                $total = 0;
//                foreach ($sql_omset as $penjualan){
//                    $remun   = $this->db->where('id_medcheck_det', $penjualan->id_medcheck_det)->get('tbl_trans_medcheck_remun')->row();
//                    $dokter  = $this->db->where('id_user', $penjualan->id_dokter)->get('tbl_m_karyawan')->row();
//                    $item    = $this->db->where('id', $penjualan->id_item)->get('tbl_m_produk')->row();
//                    $remun_nom   = ($remun->remun_tipe == '2' ? $remun->remun_nom : (($remun->remun_perc / 100) * $penjualan->harga));
//                    $total   = $total + $penjualan->subtotal;
//                    $subtot  = $penjualan->harga * $penjualan->jml;
//
//                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                    $objPHPExcel->getActiveSheet()->getStyle('I'.$cell)->getAlignment()->setWrapText(true);
//
//                    $rsp = "\n";
//                    foreach (json_decode($penjualan->resep) as $resep){
//                        $rsp .= ' - '.$resep->item.' ['.$resep->jml.' '.$resep->satuan.']'."\n"; 
//                    }
//                    
//                    $objPHPExcel->setActiveSheetIndex(0)
//                            ->setCellValue('A'.$cell, $no)
//                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo5($penjualan->tgl_simpan))
//                            ->setCellValue('C'.$cell, $penjualan->nama_pgl)
//                            ->setCellValue('D'.$cell, general::status_rawat2($penjualan->tipe))
//                            ->setCellValue('E'.$cell, $dokter->nama)
//                            ->setCellValue('F'.$cell, $penjualan->no_rm)
//                            ->setCellValue('G'.$cell, (float)$penjualan->jml)
//                            ->setCellValue('H'.$cell, $item->kode)
//                            ->setCellValue('I'.$cell, $penjualan->item.(!empty($penjualan->resep) ? $rsp : ''))
//                            ->setCellValue('J'.$cell, $penjualan->kategori)
//                            ->setCellValue('K'.$cell, $penjualan->harga)
//                            ->setCellValue('L'.$cell, $subtot)
//                            ->setCellValue('M'.$cell, $remun_nom)
//                            ->setCellValue('N'.$cell, $remun->remun_subtotal);
//
//                    $no++;
//                    $cell++;
//                }
//
//                $sell1     = $cell;
//                
//                $objPHPExcel->getActiveSheet()->getStyle('L'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1.'')->getFont()->setBold(TRUE);
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $objPHPExcel->setActiveSheetIndex(0)
//                        ->setCellValue('A' . $sell1, '')->mergeCells('A'.$sell1.':K'.$sell1.'')
//                        ->setCellValue('L' . $sell1, $sql_omset_row->jml_gtotal);
//            }
//
//            // Rename worksheet
//            $objPHPExcel->getActiveSheet()->setTitle('Lap Omset');
//
//            /** Page Setup * */
//            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
//            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
//
//            /* -- Margin -- */
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setTop(0.25);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setRight(0);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setLeft(0);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setFooter(0);
//

//            /** Page Setup * */
//            // Set document properties
//            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
//                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
//                    ->setTitle("Stok")
//                    ->setSubject("Aplikasi Bengkel POS")
//                    ->setDescription("Kunjungi http://tigerasoft.co.id")
//                    ->setKeywords("Pasifik POS")
//                    ->setCategory("Untuk mencetak nota dot matrix");
//
//
//
//            // Redirect output to a client’s web browser (Excel5)
//            header('Content-Type: application/vnd.ms-excel');
//            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//            header('Content-Disposition: attachment;filename="data_omset_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');
//
//            // If you're serving to IE over SSL, then the following may be needed
//            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
//            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
//            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//            header('Pragma: public'); // HTTP/1.0
//
//            ob_clean();
//            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//            $objWriter->save('php://output');
//            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
               
    public function htm_data_omset_global(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $poli       = $this->input->get('poli');
            $tipe       = $this->input->get('tipe');
            $plat       = $this->input->get('plat');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            switch ($case) {
                case 'per_tanggal':
                    $data['sql_omset'] = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi, tbl_m_kategori.keterangan AS kategori, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.id_item, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.disk1, tbl_trans_medcheck_det.disk2, tbl_trans_medcheck_det.disk3, tbl_trans_medcheck_det.potongan, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.subtotal, tbl_trans_medcheck_det.resep')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar)', $tgl)
                                                              ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                ->order_by('tbl_trans_medcheck.id', 'DESC')
                                                ->get('tbl_trans_medcheck_det')->result();
                        
                        $data['sql_omset_row'] = $this->db->select('SUM(tbl_trans_medcheck_det.jml * tbl_trans_medcheck_det.harga) AS jml_total, SUM(tbl_trans_medcheck_det.diskon) AS diskon, SUM(tbl_trans_medcheck_det.potongan) AS potongan, SUM(tbl_trans_medcheck_det.subtotal) AS jml_gtotal')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar)', $tgl)
//                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
                                                              ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.pasien', $pasien)
                                                        ->get('tbl_trans_medcheck_det')->row();
                    break;

                case 'per_rentang':
                        $data['sql_omset']     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi, tbl_m_kategori.keterangan AS kategori, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.id_item, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.disk1, tbl_trans_medcheck_det.disk2, tbl_trans_medcheck_det.disk3, tbl_trans_medcheck_det.potongan, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.subtotal, tbl_trans_medcheck_det.resep')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck.tgl_masuk) <=', $tgl_akhir)
                                                              ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                ->order_by('tbl_trans_medcheck.id', 'DESC')
                                                ->get('tbl_trans_medcheck_det')->result();
                    
                        
                        $data['sql_omset_row'] = $this->db->select('SUM(tbl_trans_medcheck_det.jml * tbl_trans_medcheck_det.harga) AS jml_total, SUM(tbl_trans_medcheck_det.diskon) AS diskon, SUM(tbl_trans_medcheck_det.potongan) AS potongan, SUM(tbl_trans_medcheck_det.subtotal) AS jml_gtotal')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
                                                              ->like('tbl_trans_medcheck.tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.metode', $plat, (!empty($plat) ? 'none' : ''))
                                                              ->like('tbl_trans_medcheck.pasien', $pasien)
                                                        ->get('tbl_trans_medcheck_det')->row();
                    break;
            }
            
            /* Load view tampilan */
//            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/includes/laporan/data_omset_htm', $data);
//            $this->load->view('admin-lte-3/6_bawah', $data);

//            $objPHPExcel = new PHPExcel();
//
//            // Header Tabel Nota
//            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(TRUE);
//
//            $objPHPExcel->setActiveSheetIndex(0)
//                    ->setCellValue('A4', 'No.')
//                    ->setCellValue('B4', 'Tgl')
//                    ->setCellValue('C4', 'Pasien')
//                    ->setCellValue('D4', 'Tipe')
//                    ->setCellValue('E4', 'Dokter')
//                    ->setCellValue('F4', 'No. Faktur')
//                    ->setCellValue('G4', 'Qty')
//                    ->setCellValue('H4', 'Kode')
//                    ->setCellValue('I4', 'Item')
//                    ->setCellValue('J4', 'Group')
//                    ->setCellValue('K4', 'Harga')
//                    ->setCellValue('L4', 'Subtotal')
//                    ->setCellValue('M4', 'Jasa Dokter')
//                    ->setCellValue('N4', 'Total Jasa');
//
//            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(65);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(45);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(35);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(14);
//
//            if(!empty($sql_omset)){
//                $no    = 1;
//                $cell  = 5;
//                $total = 0;
//                foreach ($sql_omset as $penjualan){
//                    $remun   = $this->db->where('id_medcheck_det', $penjualan->id_medcheck_det)->get('tbl_trans_medcheck_remun')->row();
//                    $dokter  = $this->db->where('id_user', $penjualan->id_dokter)->get('tbl_m_karyawan')->row();
//                    $item    = $this->db->where('id', $penjualan->id_item)->get('tbl_m_produk')->row();
//                    $remun_nom   = ($remun->remun_tipe == '2' ? $remun->remun_nom : (($remun->remun_perc / 100) * $penjualan->harga));
//                    $total   = $total + $penjualan->subtotal;
//                    $subtot  = $penjualan->harga * $penjualan->jml;
//
//                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                    $objPHPExcel->getActiveSheet()->getStyle('I'.$cell)->getAlignment()->setWrapText(true);
//
//                    $rsp = "\n";
//                    foreach (json_decode($penjualan->resep) as $resep){
//                        $rsp .= ' - '.$resep->item.' ['.$resep->jml.' '.$resep->satuan.']'."\n"; 
//                    }
//                    
//                    $objPHPExcel->setActiveSheetIndex(0)
//                            ->setCellValue('A'.$cell, $no)
//                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo5($penjualan->tgl_simpan))
//                            ->setCellValue('C'.$cell, $penjualan->nama_pgl)
//                            ->setCellValue('D'.$cell, general::status_rawat2($penjualan->tipe))
//                            ->setCellValue('E'.$cell, $dokter->nama)
//                            ->setCellValue('F'.$cell, $penjualan->no_rm)
//                            ->setCellValue('G'.$cell, (float)$penjualan->jml)
//                            ->setCellValue('H'.$cell, $item->kode)
//                            ->setCellValue('I'.$cell, $penjualan->item.(!empty($penjualan->resep) ? $rsp : ''))
//                            ->setCellValue('J'.$cell, $penjualan->kategori)
//                            ->setCellValue('K'.$cell, $penjualan->harga)
//                            ->setCellValue('L'.$cell, $subtot)
//                            ->setCellValue('M'.$cell, $remun_nom)
//                            ->setCellValue('N'.$cell, $remun->remun_subtotal);
//
//                    $no++;
//                    $cell++;
//                }
//
//                $sell1     = $cell;
//                
//                $objPHPExcel->getActiveSheet()->getStyle('L'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1.'')->getFont()->setBold(TRUE);
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $objPHPExcel->setActiveSheetIndex(0)
//                        ->setCellValue('A' . $sell1, '')->mergeCells('A'.$sell1.':K'.$sell1.'')
//                        ->setCellValue('L' . $sell1, $sql_omset_row->jml_gtotal);
//            }
//
//            // Rename worksheet
//            $objPHPExcel->getActiveSheet()->setTitle('Lap Omset');
//
//            /** Page Setup * */
//            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
//            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
//
//            /* -- Margin -- */
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setTop(0.25);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setRight(0);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setLeft(0);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setFooter(0);
//

//            /** Page Setup * */
//            // Set document properties
//            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
//                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
//                    ->setTitle("Stok")
//                    ->setSubject("Aplikasi Bengkel POS")
//                    ->setDescription("Kunjungi http://tigerasoft.co.id")
//                    ->setKeywords("Pasifik POS")
//                    ->setCategory("Untuk mencetak nota dot matrix");
//
//
//
//            // Redirect output to a client’s web browser (Excel5)
//            header('Content-Type: application/vnd.ms-excel');
//            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//            header('Content-Disposition: attachment;filename="data_omset_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');
//
//            // If you're serving to IE over SSL, then the following may be needed
//            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
//            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
//            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//            header('Pragma: public'); // HTTP/1.0
//
//            ob_clean();
//            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//            $objWriter->save('php://output');
//            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
               
    public function htm_data_pembelian() {
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $plat       = $this->input->get('plat');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();
            
            $sql_supp = $this->db->where('id', general::dekrip($supplier))->get('tbl_m_supplier')->row();

            switch ($case) {
                case 'per_tanggal':
                        $data['sql_pembelian'] = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.tgl_masuk, tbl_trans_beli.no_nota, tbl_trans_beli.jml_dpp, tbl_trans_beli.ppn, tbl_trans_beli.jml_ppn, tbl_trans_beli.jml_diskon, tbl_trans_beli.jml_gtotal, tbl_m_supplier.nama')
                                        ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli.id_supplier')
                                        ->where('DATE(tbl_trans_beli.tgl_masuk)', $this->tanggalan->tgl_indo_sys($tgl))
                                        ->like('tbl_m_supplier.nama', $sql_supp->nama)
                                        ->get('tbl_trans_beli')->result();
                    break;

                case 'per_rentang':
                        $data['sql_pembelian'] = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.tgl_masuk, tbl_trans_beli.no_nota, tbl_trans_beli.jml_dpp, tbl_trans_beli.ppn, tbl_trans_beli.jml_ppn, tbl_trans_beli.jml_diskon, tbl_trans_beli.jml_gtotal, tbl_m_supplier.nama')
                                        ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli.id_supplier')
                                        ->where('DATE(tbl_trans_beli.tgl_masuk) >=', $this->tanggalan->tgl_indo_sys($tgl_awal))
                                        ->where('DATE(tbl_trans_beli.tgl_masuk) <=', $this->tanggalan->tgl_indo_sys($tgl_akhir))
                                        ->like('tbl_m_supplier.nama', $sql_supp->nama)
                                        ->get('tbl_trans_beli')->result();
                    break;
            }

            /* Load view tampilan */
            $this->load->view('admin-lte-3/includes/laporan/data_pembelian_htm', $data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    

    
    public function htm_data_mcu(){
        if (akses::aksesLogin() == TRUE) {
            $dokter             = $this->input->get('id_dokter');
            $poli               = $this->input->get('poli');
            $tipe               = $this->input->get('tipe');
            $plat               = $this->input->get('plat');
            $jml                = $this->input->get('jml');
            $tgl                = $this->input->get('tgl');
            $tgl_awal           = $this->input->get('tgl_awal');
            $tgl_akhir          = $this->input->get('tgl_akhir');
            $case               = $this->input->get('case');
            $hal                = $this->input->get('halaman');
            $pasien_id          = $this->input->get('id_pasien');
            $pasien             = $this->input->get('pasien');
            $pengaturan         = $this->db->get('tbl_pengaturan')->row();
            
            switch ($case) {
                case 'per_tanggal':
                            $data['sql_mcu'] = $this->db->select('tbl_m_pasien.id AS id_pasien, tbl_m_pasien.nama_pgl, tbl_trans_medcheck_resume.id, tbl_trans_medcheck_resume.id_medcheck, tbl_trans_medcheck_resume.id_user, tbl_trans_medcheck_resume.no_surat, tbl_trans_medcheck_resume.saran, tbl_trans_medcheck_resume.kesimpulan')
                                                        ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resume.id_medcheck')
                                                        ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                        ->where('tbl_trans_medcheck.tipe', '5')
                                                        ->where('DATE(tbl_trans_medcheck_resume.tgl_simpan)', $this->tanggalan->tgl_indo_sys($tgl))
//                                                         ->limit($config['per_page'])                          
                                                        ->get('tbl_trans_medcheck_resume')->result();

                    $data['sql_mcu_cek_hdr']    = $this->db->select('tbl_trans_medcheck_resume_det.id_resume, tbl_trans_medcheck_resume_det.param, COUNT(tbl_trans_medcheck_resume_det.id_resume)')
                                                        ->join('tbl_trans_medcheck_resume', 'tbl_trans_medcheck_resume.id=tbl_trans_medcheck_resume_det.id_resume')
                                                        ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resume_det.id_medcheck')
                                                        ->where('tbl_trans_medcheck.tipe', '5')
                                                        ->where('DATE(tbl_trans_medcheck_resume.tgl_simpan)', $this->tanggalan->tgl_indo_sys($tgl))
                                                        ->order_by('tbl_trans_medcheck_resume_det.id', 'DESC')
                                                        ->get('tbl_trans_medcheck_resume_det')->row();

                    $data['sql_mcu_hdr']        = $this->db->select('tbl_trans_medcheck_resume_det.id, tbl_trans_medcheck_resume_det.id_resume, tbl_trans_medcheck_resume_det.id_medcheck, tbl_trans_medcheck_resume_det.param')
                                                    ->where('id_resume', $data['sql_mcu_cek_hdr']->id_resume)
                                                    ->get('tbl_trans_medcheck_resume_det');
                    break;

                case 'per_rentang':
                            $data['sql_mcu'] =  $this->db->select('tbl_m_pasien.id AS id_pasien, tbl_m_pasien.nama_pgl, tbl_trans_medcheck_resume.id, tbl_trans_medcheck_resume.id_medcheck, tbl_trans_medcheck_resume.id_user, tbl_trans_medcheck_resume.no_surat, tbl_trans_medcheck_resume.saran, tbl_trans_medcheck_resume.kesimpulan')
                                                         ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resume.id_medcheck')
                                                         ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                         ->where('tbl_trans_medcheck.tipe', '5')
                                                         ->where('DATE(tbl_trans_medcheck_resume.tgl_simpan) >=', $this->tanggalan->tgl_indo_sys($tgl_awal))
                                                         ->where('DATE(tbl_trans_medcheck_resume.tgl_simpan) <=', $this->tanggalan->tgl_indo_sys($tgl_akhir))
//                                                         ->limit($config['per_page'])                          
                                                         ->get('tbl_trans_medcheck_resume')->result();
                            
                            $data['sql_mcu_cek_hdr'] =  $this->db->select('tbl_trans_medcheck_resume_det.id_resume, tbl_trans_medcheck_resume_det.param, COUNT(tbl_trans_medcheck_resume_det.id_resume)')
                                                                ->join('tbl_trans_medcheck_resume', 'tbl_trans_medcheck_resume.id=tbl_trans_medcheck_resume_det.id_resume')
                                                                ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resume_det.id_medcheck')
                                                                ->where('tbl_trans_medcheck.tipe', '5')
                                                                ->where('DATE(tbl_trans_medcheck_resume.tgl_simpan) >=', $this->tanggalan->tgl_indo_sys($tgl_awal))
                                                                ->where('DATE(tbl_trans_medcheck_resume.tgl_simpan) <=', $this->tanggalan->tgl_indo_sys($tgl_akhir))
                                                                ->order_by('tbl_trans_medcheck_resume_det.id', 'DESC')
//                                                         ->where('id_resume', '1482')
                                                         ->get('tbl_trans_medcheck_resume_det')->row();
                            
                            $data['sql_mcu_hdr'] =  $this->db->select('tbl_trans_medcheck_resume_det.id, tbl_trans_medcheck_resume_det.id_resume, tbl_trans_medcheck_resume_det.id_medcheck, tbl_trans_medcheck_resume_det.param')
                                                         ->where('id_resume',  $data['sql_mcu_cek_hdr']->id_resume)
                                                         ->get('tbl_trans_medcheck_resume_det');
                    break;
            }


            /* Load view tampilan */
            /* Load view tampilan */
            $this->load->view('admin-lte-3/includes/laporan/data_mcu_htm', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_icd(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            switch ($case) {
                case 'per_tanggal':
                    $sql_omset = $this->db->query(""
                                . "SELECT "
                                . "id, kode, icd, diagnosa_en, COUNT(icd) AS jml "
                                . "FROM tbl_trans_medcheck_icd "
                                . "WHERE DATE(tbl_trans_medcheck_icd.tgl_simpan) = '".$this->tanggalan->tgl_indo_sys($tgl)."' "
                                . "GROUP BY tbl_trans_medcheck_icd.id_icd HAVING  COUNT(id_icd) > 1 "
                                . "ORDER BY COUNT(tbl_trans_medcheck_icd.icd) DESC;"
                                . "")->result();
                    break;

                case 'per_rentang':
                        $sql_omset     = $this->db->query(""
                                . "SELECT "
                                . "id, kode, icd, diagnosa_en, COUNT(icd) AS jml "
                                . "FROM tbl_trans_medcheck_icd "
                                . "WHERE DATE(tbl_trans_medcheck_icd.tgl_simpan) >= '".$this->tanggalan->tgl_indo_sys($tgl_awal)."' AND DATE(tbl_trans_medcheck_icd.tgl_simpan) <= '".$this->tanggalan->tgl_indo_sys($tgl_akhir)."' "
                                . "GROUP BY tbl_trans_medcheck_icd.id_icd HAVING  COUNT(id_icd) > 1 "
                                . "ORDER BY COUNT(tbl_trans_medcheck_icd.icd) DESC;"
                                . "")->result();
                    break;
            }

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'NO')
                    ->setCellValue('B1', 'ICD')
                    ->setCellValue('C1', '')
                    ->setCellValue('D1', 'JML DIAGNOSA');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(80);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(80);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(80);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(80);

            if(!empty($sql_omset)){
                $no    = 1;
                $cell  = 2;
                $total = 0;
                foreach ($sql_omset as $omset){
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':D'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $omset->icd.(!empty($omset->diagnosa_en) ? ' >>' : ''))
                            ->setCellValue('C'.$cell, $omset->diagnosa_en)
                            ->setCellValue('D'.$cell, $omset->jml.' Diagnosa');

                    $no++;
                    $cell++;
                }

                $sell1     = $cell;
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap ICD');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");



            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_icd_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_pembelian(){
        if (akses::aksesLogin() == TRUE) {
            $supplier   = $this->input->get('id_supplier');
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            $sql_supp = $this->db->where('id', general::dekrip($supplier))->get('tbl_m_supplier')->row();
            
            switch ($case) {
                    case 'per_tanggal':
                        $sql_pembelian     = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.tgl_masuk, tbl_trans_beli.no_nota, tbl_trans_beli_det.kode, tbl_trans_beli_det.produk, tbl_trans_beli_det.harga, tbl_trans_beli_det.jml, tbl_trans_beli_det.diskon, tbl_trans_beli_det.subtotal, tbl_m_supplier.nama')
                                                          ->join('tbl_trans_beli', 'tbl_trans_beli.id=tbl_trans_beli_det.id_pembelian')
                                                          ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli.id_supplier')
                                                          ->where('DATE(tbl_trans_beli.tgl_masuk)', $this->tanggalan->tgl_indo_sys($tgl))
                                                          ->like('tbl_m_supplier.nama', $sql_supp->nama)
                                                          ->get('tbl_trans_beli_det')->result();
                        break;
                    
                    case 'per_rentang':
                        $sql_pembelian     = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.tgl_masuk, tbl_trans_beli.no_nota, tbl_trans_beli_det.kode, tbl_trans_beli_det.produk, tbl_trans_beli_det.harga, tbl_trans_beli_det.jml, tbl_trans_beli_det.diskon, tbl_trans_beli_det.subtotal, tbl_m_supplier.nama')
                                                          ->join('tbl_trans_beli', 'tbl_trans_beli.id=tbl_trans_beli_det.id_pembelian')
                                                          ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli.id_supplier')
                                                          ->where('DATE(tbl_trans_beli.tgl_masuk) >=', $this->tanggalan->tgl_indo_sys($tgl_awal))
                                                          ->where('DATE(tbl_trans_beli.tgl_masuk) <=', $this->tanggalan->tgl_indo_sys($tgl_akhir))
                                                          ->like('tbl_m_supplier.nama', $sql_supp->nama)
                                                          ->get('tbl_trans_beli')->result();
                        
                        break;
            }

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A4:H4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:H4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:H4')->getFont()->setBold(TRUE);
//            $objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(40);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'No.')
                    ->setCellValue('B4', 'Tgl')
                    ->setCellValue('C4', 'No. Faktur')
                    ->setCellValue('D4', 'Supplier')
                    ->setCellValue('E4', 'Item')
                    ->setCellValue('F4', 'Harga')
                    ->setCellValue('G4', 'Jml')
                    ->setCellValue('H4', 'Subtotal');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(7);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

            if(!empty($sql_pembelian)){
                $no    = 1;
                $cell  = 5;
                $total = 0;
                foreach ($sql_pembelian as $item){
                    $total      = $total + $subtot;

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$cell.':E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$cell.':H'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$cell.':H'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
               
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo($item->tgl_masuk))
                            ->setCellValue('C'.$cell, $item->no_nota)
                            ->setCellValue('D'.$cell, $item->nama)
                            ->setCellValue('E'.$cell, $item->produk)
                            ->setCellValue('F'.$cell, $item->harga)
                            ->setCellValue('G'.$cell, (float)$item->jml)
                            ->setCellValue('H'.$cell, $item->subtotal);

                    $no++;
                    $cell++;
                }

                $sell1     = $cell;
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Pembelian');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");



            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_pembelian_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_omset(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            switch ($case) {
                case 'per_tanggal':
                    $sql_omset = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_akun, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.metode, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi, tbl_m_kategori.keterangan AS kategori, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.id_item, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.kode AS kode_item, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.resep, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal, tbl_trans_medcheck_det.resep, CONCAT(tbl_m_pasien.kode_dpn,\'\',tbl_m_pasien.kode) AS "kode_pasien"', false)
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar)', $tgl)
                                                ->get('tbl_trans_medcheck_det')->result();
                    break;

                case 'per_rentang':
                        $sql_omset     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_akun, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.metode, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi, tbl_m_kategori.keterangan AS kategori, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.id_item, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.kode AS kode_item, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.resep, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal, tbl_trans_medcheck_det.resep, CONCAT(tbl_m_pasien.kode_dpn,\'\',tbl_m_pasien.kode) AS "kode_pasien"', false)
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
                                                ->get('tbl_trans_medcheck_det')->result();
                    break;
            }

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
//            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'date')
                    ->setCellValue('B1', 'number')
                    ->setCellValue('C1', 'description')
                    ->setCellValue('D1', 'customer.code')
                    ->setCellValue('E1', 'orders[0].number')
                    ->setCellValue('F1', 'currency.code')
                    ->setCellValue('G1', 'exchange_rate')
                    ->setCellValue('H1', 'department.code')
                    ->setCellValue('I1', 'project.code')
                    ->setCellValue('J1', 'warehouse.code')
                    ->setCellValue('K1', 'line_items.product.code')
                    ->setCellValue('L1', 'line_items.account.code')
                    ->setCellValue('M1', 'line_items.unit.code')
                    ->setCellValue('N1', 'line_items.quantity')
                    ->setCellValue('O1', 'line_items.unit_price')
                    ->setCellValue('P1', 'line_items.discount.rate')
                    ->setCellValue('Q1', 'line_items.discount.amount')
                    ->setCellValue('R1', 'line_items.description')
                    ->setCellValue('S1', 'line_items.taxes[0].code')
                    ->setCellValue('T1', 'line_items.department.code')
                    ->setCellValue('U1', 'line_items.project.code')
                    ->setCellValue('V1', 'line_items.warehouse.code')
                    ->setCellValue('W1', 'line_items.note')
                    ->setCellValue('X1', 'payments[0].is_cash')
                    ->setCellValue('Y1', 'payments[0].account.code')
                    ->setCellValue('Z1', 'status')
                    ->setCellValue('AA1', 'term_of_payments[0].discount_days')
                    ->setCellValue('AB1', 'term_of_payments[0].due_date')
                    ->setCellValue('AC1', 'term_of_payments[0].due_days')
                    ->setCellValue('AD1', 'term_of_payments[0].early_discount_rate')
                    ->setCellValue('AE1', 'term_of_payments[0].late_charge_rate')
                    ->setCellValue('AF1', 'document.number')
                    ->setCellValue('AG1', 'document.date')
                    ->setCellValue('AH1', 'parent_memo.number')
                    ->setCellValue('AI1', 'employees[0].contact.code')
                    ->setCellValue('AJ1', 'delivery_dates')
                    ->setCellValue('AK1', 'others[0].amount_origin');
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A2', 'date')
                    ->setCellValue('B2', 'number')
                    ->setCellValue('C2', 'description')
                    ->setCellValue('D2', 'customer.code')
                    ->setCellValue('E2', 'orders[0].number')
                    ->setCellValue('F2', 'currency.code')
                    ->setCellValue('G2', 'exchange_rate')
                    ->setCellValue('H2', 'department.code')
                    ->setCellValue('I2', 'project.code')
                    ->setCellValue('J2', 'warehouse.code')
                    ->setCellValue('K2', 'line_items.product.code')
                    ->setCellValue('L2', 'line_items.account.code')
                    ->setCellValue('M2', 'line_items.unit.code')
                    ->setCellValue('N2', 'line_items.quantity')
                    ->setCellValue('O2', 'line_items.unit_price')
                    ->setCellValue('P2', 'line_items.discount.rate')
                    ->setCellValue('Q2', 'line_items.discount.amount')
                    ->setCellValue('R2', 'line_items.description')
                    ->setCellValue('S2', 'line_items.taxes[0].code')
                    ->setCellValue('T2', 'line_items.department.code')
                    ->setCellValue('U2', 'line_items.project.code')
                    ->setCellValue('V2', 'line_items.warehouse.code')
                    ->setCellValue('W2', 'line_items.note')
                    ->setCellValue('X2', 'payments[0].is_cash')
                    ->setCellValue('Y2', 'payments[0].account.code')
                    ->setCellValue('Z2', 'status')
                    ->setCellValue('AA2', 'term_of_payments[0].discount_days')
                    ->setCellValue('AB2', 'term_of_payments[0].due_date')
                    ->setCellValue('AC2', 'term_of_payments[0].due_days')
                    ->setCellValue('AD2', 'term_of_payments[0].early_discount_rate')
                    ->setCellValue('AE2', 'term_of_payments[0].late_charge_rate')
                    ->setCellValue('AF2', 'document.number')
                    ->setCellValue('AG2', 'document.date')
                    ->setCellValue('AH2', 'parent_memo.number')
                    ->setCellValue('AI2', 'employees[0].contact.code')
                    ->setCellValue('AJ2', 'delivery_dates')
                    ->setCellValue('AK2', 'others[0].amount_origin');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(80);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(100);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(150);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(120);

            if(!empty($sql_omset)){
                $no    = 1;
                $cell  = 5;
                $total = 0;
                foreach ($sql_omset as $omset){
                    $sql_plat = $this->db->select('tbl_trans_medcheck_plat.id, tbl_m_platform.platform AS metode, tbl_trans_medcheck_plat.platform, tbl_trans_medcheck_plat.keterangan, tbl_trans_medcheck_plat.nominal')
                                         ->where('tbl_trans_medcheck_plat.id_medcheck', $omset->id)
                                         ->join('tbl_m_platform', 'tbl_m_platform.id=tbl_trans_medcheck_plat.id_platform')
                                         ->get('tbl_trans_medcheck_plat');
                    

                    foreach($sql_plat->result() as $plat){
                        $plat = ($plat->id_platform == '1' ? 'CASH' : '').' ';
                        //$plat->metode
                    }

//                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                    $objPHPExcel->getActiveSheet()->getStyle('I'.$cell)->getAlignment()->setWrapText(true);

                    
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo5($penjualan->tgl_simpan))
                            ->setCellValue('C'.$cell, $penjualan->nama_pgl)
                            ->setCellValue('D'.$cell, general::status_rawat2($penjualan->tipe))
                            ->setCellValue('E'.$cell, $dokter->nama)
                            ->setCellValue('F'.$cell, $penjualan->no_rm)
                            ->setCellValue('G'.$cell, (float)$penjualan->jml)
                            ->setCellValue('H'.$cell, $item->kode)
                            ->setCellValue('I'.$cell, $penjualan->item.(!empty($penjualan->resep) ? $rsp : ''))
                            ->setCellValue('J'.$cell, $penjualan->kategori)
                            ->setCellValue('K'.$cell, $penjualan->harga)
                            ->setCellValue('L'.$cell, $subtot)
                            ->setCellValue('M'.$cell, $remun_nom)
                            ->setCellValue('N'.$cell, $remun->remun_subtotal);

                    $no++;
                    $cell++;
                }

                $sell1     = $cell;
                
                $objPHPExcel->getActiveSheet()->getStyle('L'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1.'')->getFont()->setBold(TRUE);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sell1, '')->mergeCells('A'.$sell1.':K'.$sell1.'')
                        ->setCellValue('L' . $sell1, $sql_omset_row->jml_gtotal);
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Omset');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");



            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_omset_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_omset_poli(){
        if (akses::aksesLogin() == TRUE) {
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $tipe       = $this->input->get('tipe');
            $poli       = $this->input->get('poli');
            $status     = $this->input->get('status');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            $st         = json_decode(general::dekrip($status));

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            switch ($case) {
                case 'per_tanggal':
                    $sql_omset      = $this->db->where('DATE(tgl_simpan)', $tgl)
                                               ->where_in('status', $st)
                                               ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                               ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                               ->group_by('id_pasien')
                                               ->get('v_medcheck_omset')->result();
                    
                    $sql_omset_pas  = $this->db->select('SUM(jml_gtotal) AS jml_gtotal')
                                               ->where('DATE(tgl_simpan)', $tgl)
                                               ->where_in('status', $st)
                                               ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                               ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                               ->group_by('id_pasien')
                                               ->get('v_medcheck_omset')->row();
                    break;

                case 'per_rentang':
                    $sql_omset      = $this->db
                                               ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                               ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                               ->where_in('status', $st)
                                               ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                               ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                               ->group_by('id_pasien')
                                               ->get('v_medcheck_omset')->result();
                    
                    $sql_omset_pas  = $this->db->select('SUM(jml_gtotal) AS jml_gtotal')
                                               ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                               ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                               ->where_in('status', $st)
                                               ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                               ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                               ->group_by('id_pasien')
                                               ->get('v_medcheck_omset')->row();
                    break;
            }
            

            $objPHPExcel = new PHPExcel();
            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:S4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:S4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:S4')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'LAPORAN OMSET PER POLI')->mergeCells('A1:J1');
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A2', $pengaturan->judul)->mergeCells('A2:J2');

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'No.')
                    ->setCellValue('B4', 'Tanggal')
                    ->setCellValue('C4', 'Pasien')
                    ->setCellValue('D4', 'Tipe')
                    ->setCellValue('E4', 'Poli')
                    ->setCellValue('F4', 'Dokter')
                    ->setCellValue('G4', 'No. Faktur')
                    ->setCellValue('H4', 'Grand Total');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(45);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(8);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);

            if(!empty($sql_omset)){
                $no         = 1;
                $cell       = 5;
                $total      = 0;
                $total_itm  = 0;
                foreach ($sql_omset as $omset){
                    $sql_poli   = $this->db->where('id', $omset->id_poli)->get('tbl_m_poli')->row();
                    $sql_kary   = $this->db->where('id_user', $omset->id_dokter)->get('tbl_m_karyawan')->row();
                    
                    $total      = $total + $omset->subtotal;
                    $total_itm  = $total_itm + $omset->jml;
                    
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell.':B'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$cell.':F'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$cell.':H'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$cell.':H'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");

                    $subtotal = $omset->harga * $omset->jml;
                    
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo($omset->tgl_simpan))
                            ->setCellValue('C'.$cell, $omset->pasien)
                            ->setCellValue('D'.$cell, general::status_rawat2($omset->tipe))
                            ->setCellValue('E'.$cell, $sql_poli->lokasi)
                            ->setCellValue('F'.$cell, (!empty($sql_kary->nama_dpn) ? $sql_kary->nama_dpn.' ' : '').(!empty($sql_kary->nama) ? $sql_kary->nama : '').(!empty($sql_kary->nama_blk) ? ', '.$sql_kary->nama_blk : ''))
                            ->setCellValue('G'.$cell, $omset->no_rm)
                            ->setCellValue('H'.$cell, $omset->jml_gtotal);

                    $no++;
                    $cell++;
                }

                $sell1     = $cell + 1;
                $sell2     = $sell1 + 1;
                $sell3     = $sell2 + 1;
                

//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':B'.$sell1.'')->getFont()->setBold(TRUE);
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                $objPHPExcel->getActiveSheet()->getStyle('C'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $objPHPExcel->getActiveSheet()->getStyle('H'.$sell1)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                $objPHPExcel->setActiveSheetIndex(0)
//                        ->setCellValue('A' . $sell1, 'TOTAL OMSET')->mergeCells('A'.$sell1.':G'.$sell1.'')
//                        ->setCellValue('H' . $sell1, $total);
//                
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell2.':B'.$sell2.'')->getFont()->setBold(TRUE);
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                $objPHPExcel->getActiveSheet()->getStyle('C'.$sell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $objPHPExcel->getActiveSheet()->getStyle('C'.$sell2)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                $objPHPExcel->setActiveSheetIndex(0)
//                        ->setCellValue('A' . $sell2, 'TOTAL KUNJ PASIEN')->mergeCells('A'.$sell2.':B'.$sell2.'')
//                        ->setCellValue('C' . $sell2, $sql_omset_pas->num_rows())->mergeCells('C'.$sell2.':D'.$sell2.'');
                
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell3.':B'.$sell3.'')->getFont()->setBold(TRUE);
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell3)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                $objPHPExcel->getActiveSheet()->getStyle('C'.$sell3)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $objPHPExcel->getActiveSheet()->getStyle('C'.$sell3)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                $objPHPExcel->setActiveSheetIndex(0)
//                        ->setCellValue('A' . $sell3, 'TOTAL ITEM')->mergeCells('A'.$sell3.':B'.$sell3.'')
//                        ->setCellValue('C' . $sell3, $total_itm)->mergeCells('C'.$sell3.':D'.$sell3.'');
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Omset');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");



            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_omset_poli_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_omset_detail(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $tipe       = $this->input->get('tipe');
            $poli       = $this->input->get('poli');
            $status     = $this->input->get('status');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            switch ($case) {
                case 'per_tanggal':
                    $sql_omset = $this->db
                                      ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                      ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                      ->like('status', $status, (!empty($status) ? 'none' : ''))
                                      ->where('DATE(tgl_simpan)', $tgl)
                                      ->get('v_medcheck_omset');
                    
                    $sql_omset_pas = $this->db
                                          ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                          ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                          ->like('status', $status, (!empty($status) ? 'none' : ''))
                                          ->where('DATE(tgl_simpan)', $tgl)
                                          ->group_by('nama_pgl')
                                          ->get('v_medcheck_omset');
                    break;

                case 'per_rentang':
                        $sql_omset     = $this->db
                                              ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                              ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                              ->like('status', $status, (!empty($status) ? 'none' : ''))
                                              ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                              ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                              ->get('v_medcheck_omset');
                    
                        $sql_omset_pas = $this->db
                                              ->like('id_poli', $poli, (!empty($poli) ? 'none' : ''))
                                              ->like('tipe', $tipe, (!empty($tipe) ? 'none' : ''))
                                              ->like('status', $status, (!empty($status) ? 'none' : ''))
                                              ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                              ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                              ->group_by('nama_pgl')
                                              ->get('v_medcheck_omset');
                    break;
            }

            $objPHPExcel = new PHPExcel();
            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:S4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:S4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:S4')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'LAPORAN OMSET DETAIL')->mergeCells('A1:J1');
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A2', $pengaturan->judul)->mergeCells('A2:J2');

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'No.')
                    ->setCellValue('B4', 'Tanggal')
                    ->setCellValue('C4', 'Tipe')
                    ->setCellValue('D4', 'Poli')
                    ->setCellValue('E4', 'Pasien')
                    ->setCellValue('F4', 'Item')
                    ->setCellValue('G4', 'Jumlah')
                    ->setCellValue('H4', 'Harga')
                    ->setCellValue('I4', 'Subtotal')
                    ->setCellValue('J4', 'Jenis');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(11);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(11);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

            if(!empty($sql_omset)){
                $no         = 1;
                $cell       = 5;
                $total      = 0;
                $total_itm  = 0;
                foreach ($sql_omset->result() as $omset){
                    $total      = $total + $omset->subtotal;
                    $total_itm  = $total_itm + $omset->jml;
                    
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell.':B'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$cell.':F'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$cell.':I'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$cell.':I'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo($omset->tgl_simpan))
                            ->setCellValue('C'.$cell, general::status_rawat2($omset->tipe))
                            ->setCellValue('D'.$cell, $omset->poli)
                            ->setCellValue('E'.$cell, $omset->nama_pgl)
                            ->setCellValue('F'.$cell, $omset->item)
                            ->setCellValue('G'.$cell, $omset->jml)
                            ->setCellValue('H'.$cell, $omset->harga)
                            ->setCellValue('I'.$cell, $omset->subtotal)
                            ->setCellValue('J'.$cell, general::tipe_item($omset->status));

                    $no++;
                    $cell++;
                }

                $sell1     = $cell + 1;
                $sell2     = $sell1 + 1;
                $sell3     = $sell2 + 1;
                

                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':B'.$sell1.'')->getFont()->setBold(TRUE);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$sell1)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sell1, 'TOTAL OMSET')->mergeCells('A'.$sell1.':B'.$sell1.'')
                        ->setCellValue('C' . $sell1, $total)->mergeCells('C'.$sell1.':D'.$sell1.'');
                
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell2.':B'.$sell2.'')->getFont()->setBold(TRUE);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$sell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$sell2)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sell2, 'TOTAL KUNJ PASIEN')->mergeCells('A'.$sell2.':B'.$sell2.'')
                        ->setCellValue('C' . $sell2, $sql_omset_pas->num_rows())->mergeCells('C'.$sell2.':D'.$sell2.'');
                
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell3.':B'.$sell3.'')->getFont()->setBold(TRUE);
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell3)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                $objPHPExcel->getActiveSheet()->getStyle('C'.$sell3)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $objPHPExcel->getActiveSheet()->getStyle('C'.$sell3)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                $objPHPExcel->setActiveSheetIndex(0)
//                        ->setCellValue('A' . $sell3, 'TOTAL ITEM')->mergeCells('A'.$sell3.':B'.$sell3.'')
//                        ->setCellValue('C' . $sell3, $total_itm)->mergeCells('C'.$sell3.':D'.$sell3.'');
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Omset');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");



            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_omset_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_omset_jasa(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pasien_id  = $this->input->get('id_pasien');
            $pasien     = $this->input->get('pasien');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            switch ($case) {
                case 'per_tanggal':
                    $sql_omset = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_akun, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.metode, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi, tbl_m_kategori.keterangan AS kategori, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.id_item, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.kode AS kode_item, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.resep, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal, tbl_trans_medcheck_det.resep, CONCAT(tbl_m_pasien.kode_dpn,\'\',tbl_m_pasien.kode) AS "kode_pasien"', false)
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar)', $tgl)
                                                              ->where('tbl_trans_medcheck_det.status', '2')
                                                              ->like('tbl_trans_medcheck.pasien', $pasien)
                                                ->get('tbl_trans_medcheck_det')->result();
                    break;

                case 'per_rentang':
                        $sql_omset     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_akun, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.metode, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi, tbl_m_kategori.keterangan AS kategori, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.id_item, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.kode AS kode_item, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.resep, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal, tbl_trans_medcheck_det.resep, CONCAT(tbl_m_pasien.kode_dpn,\'\',tbl_m_pasien.kode) AS "kode_pasien"', false)
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
                                                              ->where('tbl_trans_medcheck_det.status', '2')
                                                              ->like('tbl_trans_medcheck.pasien', $pasien)
                                                ->get('tbl_trans_medcheck_det')->result();
                    break;
            }

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
//            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'date')
                    ->setCellValue('B1', 'number')
                    ->setCellValue('C1', 'description')
                    ->setCellValue('D1', 'customer.code')
                    ->setCellValue('E1', 'orders[0].number')
                    ->setCellValue('F1', 'currency.code')
                    ->setCellValue('G1', 'exchange_rate')
                    ->setCellValue('H1', 'department.code')
                    ->setCellValue('I1', 'project.code')
                    ->setCellValue('J1', 'warehouse.code')
                    ->setCellValue('K1', 'line_items.product.code')
                    ->setCellValue('L1', 'line_items.account.code')
                    ->setCellValue('M1', 'line_items.unit.code')
                    ->setCellValue('N1', 'line_items.quantity')
                    ->setCellValue('O1', 'line_items.unit_price')
                    ->setCellValue('P1', 'line_items.discount.rate')
                    ->setCellValue('Q1', 'line_items.discount.amount')
                    ->setCellValue('R1', 'line_items.description')
                    ->setCellValue('S1', 'line_items.taxes[0].code')
                    ->setCellValue('T1', 'line_items.department.code')
                    ->setCellValue('U1', 'line_items.project.code')
                    ->setCellValue('V1', 'line_items.warehouse.code')
                    ->setCellValue('W1', 'line_items.note')
                    ->setCellValue('X1', 'payments[0].is_cash')
                    ->setCellValue('Y1', 'payments[0].account.code')
                    ->setCellValue('Z1', 'status')
                    ->setCellValue('AA1', 'term_of_payments[0].discount_days')
                    ->setCellValue('AB1', 'term_of_payments[0].due_date')
                    ->setCellValue('AC1', 'term_of_payments[0].due_days')
                    ->setCellValue('AD1', 'term_of_payments[0].early_discount_rate')
                    ->setCellValue('AE1', 'term_of_payments[0].late_charge_rate')
                    ->setCellValue('AF1', 'document.number')
                    ->setCellValue('AG1', 'document.date')
                    ->setCellValue('AH1', 'parent_memo.number')
                    ->setCellValue('AI1', 'employees[0].contact.code')
                    ->setCellValue('AJ1', 'delivery_dates')
                    ->setCellValue('AK1', 'others[0].amount_origin');
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A2', 'date')
                    ->setCellValue('B2', 'number')
                    ->setCellValue('C2', 'description')
                    ->setCellValue('D2', 'customer.code')
                    ->setCellValue('E2', 'orders[0].number')
                    ->setCellValue('F2', 'currency.code')
                    ->setCellValue('G2', 'exchange_rate')
                    ->setCellValue('H2', 'department.code')
                    ->setCellValue('I2', 'project.code')
                    ->setCellValue('J2', 'warehouse.code')
                    ->setCellValue('K2', 'line_items.product.code')
                    ->setCellValue('L2', 'line_items.account.code')
                    ->setCellValue('M2', 'line_items.unit.code')
                    ->setCellValue('N2', 'line_items.quantity')
                    ->setCellValue('O2', 'line_items.unit_price')
                    ->setCellValue('P2', 'line_items.discount.rate')
                    ->setCellValue('Q2', 'line_items.discount.amount')
                    ->setCellValue('R2', 'line_items.description')
                    ->setCellValue('S2', 'line_items.taxes[0].code')
                    ->setCellValue('T2', 'line_items.department.code')
                    ->setCellValue('U2', 'line_items.project.code')
                    ->setCellValue('V2', 'line_items.warehouse.code')
                    ->setCellValue('W2', 'line_items.note')
                    ->setCellValue('X2', 'payments[0].is_cash')
                    ->setCellValue('Y2', 'payments[0].account.code')
                    ->setCellValue('Z2', 'status')
                    ->setCellValue('AA2', 'term_of_payments[0].discount_days')
                    ->setCellValue('AB2', 'term_of_payments[0].due_date')
                    ->setCellValue('AC2', 'term_of_payments[0].due_days')
                    ->setCellValue('AD2', 'term_of_payments[0].early_discount_rate')
                    ->setCellValue('AE2', 'term_of_payments[0].late_charge_rate')
                    ->setCellValue('AF2', 'document.number')
                    ->setCellValue('AG2', 'document.date')
                    ->setCellValue('AH2', 'parent_memo.number')
                    ->setCellValue('AI2', 'employees[0].contact.code')
                    ->setCellValue('AJ2', 'delivery_dates')
                    ->setCellValue('AK2', 'others[0].amount_origin');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(80);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(100);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(150);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(120);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(120);

            if(!empty($sql_omset)){
                $no    = 1;
                $cell  = 5;
                $total = 0;
                foreach ($sql_omset as $omset){
                    $sql_plat = $this->db->select('tbl_trans_medcheck_plat.id, tbl_m_platform.platform AS metode, tbl_trans_medcheck_plat.platform, tbl_trans_medcheck_plat.keterangan, tbl_trans_medcheck_plat.nominal')
                                         ->where('tbl_trans_medcheck_plat.id_medcheck', $omset->id)
                                         ->join('tbl_m_platform', 'tbl_m_platform.id=tbl_trans_medcheck_plat.id_platform')
                                         ->get('tbl_trans_medcheck_plat');
                    

                    foreach($sql_plat->result() as $plat){
                        $plat = ($plat->id_platform == '1' ? 'CASH' : '').' ';
                        //$plat->metode
                    }

//                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                    $objPHPExcel->getActiveSheet()->getStyle('I'.$cell)->getAlignment()->setWrapText(true);

                    
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo5($penjualan->tgl_simpan))
                            ->setCellValue('C'.$cell, $penjualan->nama_pgl)
                            ->setCellValue('D'.$cell, general::status_rawat2($penjualan->tipe))
                            ->setCellValue('E'.$cell, $dokter->nama)
                            ->setCellValue('F'.$cell, $penjualan->no_rm)
                            ->setCellValue('G'.$cell, (float)$penjualan->jml)
                            ->setCellValue('H'.$cell, $item->kode)
                            ->setCellValue('I'.$cell, $penjualan->item.(!empty($penjualan->resep) ? $rsp : ''))
                            ->setCellValue('J'.$cell, $penjualan->kategori)
                            ->setCellValue('K'.$cell, $penjualan->harga)
                            ->setCellValue('L'.$cell, $subtot)
                            ->setCellValue('M'.$cell, $remun_nom)
                            ->setCellValue('N'.$cell, $remun->remun_subtotal);

                    $no++;
                    $cell++;
                }

                $sell1     = $cell;
                
                $objPHPExcel->getActiveSheet()->getStyle('L'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1.'')->getFont()->setBold(TRUE);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sell1, '')->mergeCells('A'.$sell1.':K'.$sell1.'')
                        ->setCellValue('L' . $sell1, $sql_omset_row->jml_gtotal);
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Omset');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");



            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_omset_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_omset_backup(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            switch ($case) {
                case 'per_tanggal':
                    $sql_omset = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi, tbl_m_kategori.keterangan AS kategori, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.id_item, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.subtotal, tbl_trans_medcheck_det.resep')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar)', $tgl)
                                                ->get('tbl_trans_medcheck_det')->result();
                    break;

                case 'per_rentang':
                        $sql_omset     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi, tbl_m_kategori.keterangan AS kategori, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.id_item, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.subtotal, tbl_trans_medcheck_det.resep')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
                                                ->get('tbl_trans_medcheck_det')->result();
//
                        $sql_omset_row = $this->db->select('SUM(tbl_trans_medcheck_det.jml * tbl_trans_medcheck_det.harga) AS jml_gtotal')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
                                                ->get('tbl_trans_medcheck_det')->row();
                    break;
            }

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'No.')
                    ->setCellValue('B4', 'Tgl')
                    ->setCellValue('C4', 'Pasien')
                    ->setCellValue('D4', 'Tipe')
                    ->setCellValue('E4', 'Dokter')
                    ->setCellValue('F4', 'No. Faktur')
                    ->setCellValue('G4', 'Qty')
                    ->setCellValue('H4', 'Kode')
                    ->setCellValue('I4', 'Item')
                    ->setCellValue('J4', 'Group')
                    ->setCellValue('K4', 'Harga')
                    ->setCellValue('L4', 'Subtotal')
                    ->setCellValue('M4', 'Jasa Dokter')
                    ->setCellValue('N4', 'Total Jasa');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(65);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(45);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(35);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(14);

            if(!empty($sql_omset)){
                $no    = 1;
                $cell  = 5;
                $total = 0;
                foreach ($sql_omset as $penjualan){
                    $remun   = $this->db->where('id_medcheck_det', $penjualan->id_medcheck_det)->get('tbl_trans_medcheck_remun')->row();
                    $dokter  = $this->db->where('id_user', $penjualan->id_dokter)->get('tbl_m_karyawan')->row();
                    $item    = $this->db->where('id', $penjualan->id_item)->get('tbl_m_produk')->row();
                    $remun_nom   = ($remun->remun_tipe == '2' ? $remun->remun_nom : (($remun->remun_perc / 100) * $penjualan->harga));
                    $total   = $total + $penjualan->subtotal;
                    $subtot  = $penjualan->harga * $penjualan->jml;

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$cell)->getAlignment()->setWrapText(true);

                    $rsp = "\n";
                    foreach (json_decode($penjualan->resep) as $resep){
                        $rsp .= ' - '.$resep->item.' ['.$resep->jml.' '.$resep->satuan.']'."\n"; 
                    }
                    
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo5($penjualan->tgl_simpan))
                            ->setCellValue('C'.$cell, $penjualan->nama_pgl)
                            ->setCellValue('D'.$cell, general::status_rawat2($penjualan->tipe))
                            ->setCellValue('E'.$cell, $dokter->nama)
                            ->setCellValue('F'.$cell, $penjualan->no_rm)
                            ->setCellValue('G'.$cell, (float)$penjualan->jml)
                            ->setCellValue('H'.$cell, $item->kode)
                            ->setCellValue('I'.$cell, $penjualan->item.(!empty($penjualan->resep) ? $rsp : ''))
                            ->setCellValue('J'.$cell, $penjualan->kategori)
                            ->setCellValue('K'.$cell, $penjualan->harga)
                            ->setCellValue('L'.$cell, $subtot)
                            ->setCellValue('M'.$cell, $remun_nom)
                            ->setCellValue('N'.$cell, $remun->remun_subtotal);

                    $no++;
                    $cell++;
                }

                $sell1     = $cell;
                
                $objPHPExcel->getActiveSheet()->getStyle('L'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1.'')->getFont()->setBold(TRUE);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sell1, '')->mergeCells('A'.$sell1.':K'.$sell1.'')
                        ->setCellValue('L' . $sell1, $sql_omset_row->jml_gtotal);
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Omset');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");



            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_omset_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function csv_data_omset(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();
            
            switch ($case) {
                case 'per_tanggal':
                    $sql_omset = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_akun, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.metode, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi, tbl_m_kategori.keterangan AS kategori, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.id_item, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.kode AS kode_item, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.resep, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal, tbl_trans_medcheck_det.resep, CONCAT(tbl_m_pasien.kode_dpn,\'\',tbl_m_pasien.kode) AS "kode_pasien"', false)
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar)', $tgl)
                                                ->get('tbl_trans_medcheck_det')->result();
                    break;

                case 'per_rentang':
                        $sql_omset     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_akun, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.jml_bayar, tbl_trans_medcheck.jml_kembali, tbl_trans_medcheck.metode, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_poli.lokasi, tbl_m_kategori.keterangan AS kategori, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.id_item, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.kode AS kode_item, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.resep, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal, tbl_trans_medcheck_det.resep, CONCAT(tbl_m_pasien.kode_dpn,\'\',tbl_m_pasien.kode) AS "kode_pasien"', false)
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
                                                ->get('tbl_trans_medcheck_det')->result();
//
//                        $sql_omset_row = $this->db->select('SUM(tbl_trans_medcheck_det.jml * tbl_trans_medcheck_det.harga) AS jml_gtotal')
//                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
//                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
//                                                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
//                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
//                                                              ->where('tbl_trans_medcheck.status_hps', '0')
//                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
//                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) >=', $tgl_awal)
//                                                              ->where('DATE(tbl_trans_medcheck.tgl_bayar) <=', $tgl_akhir)
//                                                ->get('tbl_trans_medcheck_det')->row();
                    break;
            }            
            
            $header1 = array(
                'date',
                'number',
                'description',
                'customer.code',
                'orders[0].number',
                'currency.code',
                'exchange_rate',
                'department.code',
                'project.code',
                'warehouse.code',
                'line_items.product.code',
                'line_items.account.code',
                'line_items.unit.code',
                'line_items.quantity',
                'line_items.unit_price',
                'line_items.discount.rate',
                'line_items.discount.amount',
                'line_items.description',
                'line_items.taxes[0].code',
                'line_items.department.code',
                'line_items.project.code',
                'line_items.warehouse.code',
                'line_items.note',
                'payments[0].is_cash',
                'payments[0].account.code',
                'status',
                'term_of_payments[0].discount_days',
                'term_of_payments[0].due_date',
                'term_of_payments[0].due_days',
                'term_of_payments[0].early_discount_rate',
                'term_of_payments[0].late_charge_rate',
                'document.number',
                'document.date',
                'parent_memo.number',
                'employees[0].contact.code',
                'delivery_dates',
                'others[0].amount_origin'
            );            
            $header2 = array(
                'Transaction Date (Format: yyyy-mm-dd, Ex: 2020-01-30)',
                'Reference No.',
                'Description',	
                'Customer Code',
                'Order No',
                'Currency Code',
                'Exchange Rate', 
                'Department Code',
                'Project Code',	
                'Warhouse Code',
                'Item Product Code',
                'Item service (Account Code)',	
                'Item Unit Code',
                'Item Quantity','Item Price',
                'Item Discount',	
                'Item Discount Amount',	
                'Item Description',	
                'Item Tax Code',	
                'Item Department Code',	
                'Item Project Code','Item Warhouse Code',
                'Item Note',
                'Cash',	
                'Payment Account Cash',	
                'Status','Discount Days',
                'Due Date',	
                'Due Days',	
                'Early Discount Rate',	
                'Late Charge Rate',	
                'Document Number',	
                'Document Date',	
                'Salesman',	
                'Memo Of Credit / Debit',	
                'Delivery Date',	
                'Biaya Lain'
                );
            
            # Set headers to force file download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="sales-invoice-'.date('dmYH').'.csv"');
            
            # Open a new output stream for writing the CSV data
            $output = fopen('php://output', 'w');
            
            # Write CSV header row
            fputcsv($output, $header1);
            
            # Write CSV header row
            fputcsv($output, $header2);
            
            # Get Result From database
            if(!empty($sql_omset)){
                foreach ($sql_omset as $omset){
                    $sql_plat = $this->db->select('tbl_trans_medcheck_plat.id, tbl_m_platform.platform AS metode, tbl_trans_medcheck_plat.platform, tbl_trans_medcheck_plat.keterangan, tbl_trans_medcheck_plat.nominal')
                                        ->where('tbl_trans_medcheck_plat.id_medcheck', $omset->id)
                                        ->join('tbl_m_platform', 'tbl_m_platform.id=tbl_trans_medcheck_plat.id_platform')
                                        ->get('tbl_trans_medcheck_plat');
                    
                    $no_nota = strtoupper(date('M').date('d').date('y').'001');
                    
                    foreach($sql_plat->result() as $plat){
                        $plat = ($plat->id_platform == '1' ? 'CASH' : '').' ';
                        //$plat->metode
                    }
                    
                    $data = array(
                        $this->tanggalan->tgl_indo7($omset->tgl_simpan),
                        $omset->no_akun,
                        $plat,
                        ($omset->metode == '1' ? 'UMUM' : ''),
                        '',
                        'IDR',
                        '1',
                        '99',
                        'N/A',
                        '99',
                        $omset->kode_item,
                        '',
                        (!empty($omset->satuan) ? strtolower(ucfirst($omset->satuan)) : 'Pcs'),
                        (float)$omset->jml,
                        (float)$omset->harga,
                        '',
                        '',
                        $omset->item,
                        '',
                        '99',
                        'N/A',
                        '99',
                        '',
                        ($omset->metode == '1' ? 'TRUE' : 'FALSE'),
                        '110099020',
                        'draft',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        $this->tanggalan->tgl_indo7($omset->tgl_masuk),
                        '',
                        '',
                        $this->tanggalan->tgl_indo7($omset->tgl_masuk)
                    );
                    
                    # Tulis di file CSV-nya
                    fputcsv($output, $data);
                    
                    if(!empty($omset->resep)){
                        foreach (json_decode($omset->resep) as $resep){
                            $data_resep = array(
                                $this->tanggalan->tgl_indo7($omset->tgl_simpan),
                                $no_nota,
                                $plat,
                                $omset->kode_pasien,
                                '',
                                'IDR',
                                '1',
                                '99',
                                'N/A',
                                '99',
                                $resep->kode,
                                '',
                                (!empty($resep->satuan) ? strtolower(ucfirst($resep->satuan)) : 'Pcs'),
                                (float) $resep->jml,
                                (float) $resep->harga,
                                '',
                                '',
                                $resep->item,
                                '',
                                '99',
                                'N/A',
                                '99',
                                '',
                                ($omset->metode == '1' ? 'TRUE' : 'FALSE'),
                                '110099020',
                                'draft',
                                '',
                                '',
                                '',
                                '',
                                '',
                                '',
                                $this->tanggalan->tgl_indo7($omset->tgl_masuk),
                                '',
                                '',
                                $this->tanggalan->tgl_indo7($omset->tgl_masuk)
                            );
                            
                            # Tulis resep
                            fputcsv($output, $data_resep);
                        }
                    }
                }
            }
            
            # Close the output stream
            fclose($output);

//            $objPHPExcel = new PHPExcel();
//
//            // Header Tabel Nota
//            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(TRUE);
//
//            $objPHPExcel->setActiveSheetIndex(0)
//                    ->setCellValue('A4', 'No.')
//                    ->setCellValue('B4', 'Tgl')
//                    ->setCellValue('C4', 'Pasien')
//                    ->setCellValue('D4', 'Tipe')
//                    ->setCellValue('E4', 'Dokter')
//                    ->setCellValue('F4', 'No. Faktur')
//                    ->setCellValue('G4', 'Qty')
//                    ->setCellValue('H4', 'Kode')
//                    ->setCellValue('I4', 'Item')
//                    ->setCellValue('J4', 'Group')
//                    ->setCellValue('K4', 'Harga')
//                    ->setCellValue('L4', 'Subtotal')
//                    ->setCellValue('M4', 'Jasa Dokter')
//                    ->setCellValue('N4', 'Total Jasa');
//
//            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(65);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(45);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(35);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(14);
//
//            if(!empty($sql_omset)){
//                $no    = 1;
//                $cell  = 5;
//                $total = 0;
//                foreach ($sql_omset as $penjualan){
//                    $remun   = $this->db->where('id_medcheck_det', $penjualan->id_medcheck_det)->get('tbl_trans_medcheck_remun')->row();
//                    $dokter  = $this->db->where('id_user', $penjualan->id_dokter)->get('tbl_m_karyawan')->row();
//                    $item    = $this->db->where('id', $penjualan->id_item)->get('tbl_m_produk')->row();
//                    $remun_nom   = ($remun->remun_tipe == '2' ? $remun->remun_nom : (($remun->remun_perc / 100) * $penjualan->harga));
//                    $total   = $total + $penjualan->subtotal;
//                    $subtot  = $penjualan->harga * $penjualan->jml;
//
//                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                    $objPHPExcel->getActiveSheet()->getStyle('I'.$cell)->getAlignment()->setWrapText(true);
//
//                    $rsp = "\n";
//                    foreach (json_decode($penjualan->resep) as $resep){
//                        $rsp .= ' - '.$resep->item.' ['.$resep->jml.' '.$resep->satuan.']'."\n"; 
//                    }
//                    
//                    $objPHPExcel->setActiveSheetIndex(0)
//                            ->setCellValue('A'.$cell, $no)
//                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo5($penjualan->tgl_simpan))
//                            ->setCellValue('C'.$cell, $penjualan->nama_pgl)
//                            ->setCellValue('D'.$cell, general::status_rawat2($penjualan->tipe))
//                            ->setCellValue('E'.$cell, $dokter->nama)
//                            ->setCellValue('F'.$cell, $penjualan->no_rm)
//                            ->setCellValue('G'.$cell, (float)$penjualan->jml)
//                            ->setCellValue('H'.$cell, $item->kode)
//                            ->setCellValue('I'.$cell, $penjualan->item.(!empty($penjualan->resep) ? $rsp : ''))
//                            ->setCellValue('J'.$cell, $penjualan->kategori)
//                            ->setCellValue('K'.$cell, $penjualan->harga)
//                            ->setCellValue('L'.$cell, $subtot)
//                            ->setCellValue('M'.$cell, $remun_nom)
//                            ->setCellValue('N'.$cell, $remun->remun_subtotal);
//
//                    $no++;
//                    $cell++;
//                }
//
//                $sell1     = $cell;
//                
//                $objPHPExcel->getActiveSheet()->getStyle('L'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1.'')->getFont()->setBold(TRUE);
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $objPHPExcel->setActiveSheetIndex(0)
//                        ->setCellValue('A' . $sell1, '')->mergeCells('A'.$sell1.':K'.$sell1.'')
//                        ->setCellValue('L' . $sell1, $sql_omset_row->jml_gtotal);
//            }
//
//            // Rename worksheet
//            $objPHPExcel->getActiveSheet()->setTitle('Lap Omset');
//
//            /** Page Setup * */
//            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
//            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
//
//            /* -- Margin -- */
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setTop(0.25);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setRight(0);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setLeft(0);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setFooter(0);
//
//
//            /** Page Setup * */
//            // Set document properties
//            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
//                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
//                    ->setTitle("Stok")
//                    ->setSubject("Aplikasi Bengkel POS")
//                    ->setDescription("Kunjungi http://tigerasoft.co.id")
//                    ->setKeywords("Pasifik POS")
//                    ->setCategory("Untuk mencetak nota dot matrix");
//
//
//
//            // Redirect output to a client’s web browser (Excel5)
//            header('Content-Type: application/vnd.ms-excel');
//            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//            header('Content-Disposition: attachment;filename="data_omset_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');
//
//            // If you're serving to IE over SSL, then the following may be needed
//            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
//            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
//            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//            header('Pragma: public'); // HTTP/1.0
//
//            ob_clean();
//            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//            $objWriter->save('php://output');
//            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_stok(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tipe       = $this->input->get('tipe');
            $stok       = $this->input->get('stok');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
                switch ($tipe) {
                    case '0' :
                        $stp = '<';
                        $sql_stok = $this->db
                                 ->where('status_subt', '0')
                                 ->get('tbl_m_produk')->result();
                        break;
                    
                    case '1' :
                        $stp = '<';
                        $sql_stok = $this->db
                                 ->where('status_subt', '1')
                                 ->get('tbl_m_produk')->result();
                        break;

                    case '2' :
                        $stp = '';
                        $sql_stok = $this->db
                                 ->get('tbl_m_produk')->result();
                        break;
                }
                            

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A4:L4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:L4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:L4')->getFont()->setBold(TRUE);
//            $objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(40);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'No.')
                    ->setCellValue('B4', 'Tgl')
                    ->setCellValue('C4', 'Kategori')
                    ->setCellValue('D4', 'Kode')
                    ->setCellValue('E4', 'Item')
                    ->setCellValue('F4', 'Jml')
                    ->setCellValue('G4', 'Harga')
                    ->setCellValue('H4', 'Nilai Stok')
                    ->setCellValue('I4', 'Remun Perc')
                    ->setCellValue('J4', 'Remun Nom')
                    ->setCellValue('K4', 'Apres Perc')
                    ->setCellValue('L4', 'Apres Nom');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(19);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);

            if(!empty($sql_stok)){
                $no    = 1;
                $cell  = 5;
                $total = 0;
                foreach ($sql_stok as $item){
                    $sql_kat    = $this->db->where('id', $item->id_kategori)->get('tbl_m_kategori')->row();
                    $subtot     = $item->harga_jual * $item->jml;
                    $total      = $total + $subtot;

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                    $objPHPExcel->getActiveSheet()->getStyle('E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$cell.':L'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$cell.':L'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
               
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo5($item->tgl_modif))
                            ->setCellValue('C'.$cell, $sql_kat->keterangan)
                            ->setCellValue('D'.$cell, $item->kode)
                            ->setCellValue('E'.$cell, $item->produk)
                            ->setCellValue('F'.$cell, (float)$item->jml)
                            ->setCellValue('G'.$cell, $item->harga_jual)
                            ->setCellValue('H'.$cell, $subtot)
                            ->setCellValue('I'.$cell, $item->remun_perc)
                            ->setCellValue('J'.$cell, $item->remun_nom)
                            ->setCellValue('K'.$cell, $item->apres_perc)
                            ->setCellValue('L'.$cell, $item->apres_nom);

                    $no++;
                    $cell++;
                }

                $sell1     = $cell;
                
//                $objPHPExcel->getActiveSheet()->getStyle('F'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1.'')->getFont()->setBold(TRUE);
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $objPHPExcel->setActiveSheetIndex(0)
//                        ->setCellValue('A' . $sell1, 'Total Persediaan')->mergeCells('A'.$sell1.':E'.$sell1.'')
//                        ->setCellValue('F' . $sell1, $total);
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Stok');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");


            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_stok_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_stok2(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tipe       = $this->input->get('tipe');
            $stok       = $this->input->get('stok');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
                switch ($tipe) {
                    case '0' :
                        $stp = '<';
                        $sql_stok = $this->db
                                 ->where('status_subt', '1')
                                 ->get('tbl_m_produk')->result();
                        break;
                    
                    case '1' :
                        $stp = '<';
                        $sql_stok = $this->db
                                 ->where('status_subt', '1')
                                 ->where('jml'.(isset($stp) ? ' '.$stp : ''), $stok)
                                 ->get('tbl_m_produk')->result();
                        break;

                    case '2' :
                        $stp = '';
                        $sql_stok = $this->db
                                 ->where('status_subt', '1')
                                 ->where('jml'.(isset($stp) ? ' '.$stp : ''), $stok)
                                 ->get('tbl_m_produk')->result();
                        break;

                    case '3' :
                        $stp = '>=';
                        $sql_stok = $this->db
                                 ->where('status_subt', '1')
                                 ->where('jml'.(isset($stp) ? ' '.$stp : ''), $stok)
                                 ->get('tbl_m_produk')->result();
                        break;
                }
                            

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getFont()->setBold(TRUE);
//            $objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(40);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'No.')
                    ->setCellValue('B4', 'Tgl')
                    ->setCellValue('C4', 'Kode')
                    ->setCellValue('D4', 'Item')
                    ->setCellValue('E4', 'Jml')
                    ->setCellValue('F4', 'Harga')
                    ->setCellValue('G4', 'Nilai Stok');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(19);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

            if(!empty($sql_stok)){
                $no    = 1;
                $cell  = 5;
                $total = 0;
                foreach ($sql_stok as $item){
                    $subtot     = $item->harga_jual * $item->jml;
                    $total      = $total + $subtot;

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':D'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$cell.':G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$cell.':G'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
               
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo5($item->tgl_modif))
                            ->setCellValue('C'.$cell, $item->kode)
                            ->setCellValue('D'.$cell, $item->produk)
                            ->setCellValue('E'.$cell, (float)$item->jml)
                            ->setCellValue('F'.$cell, $item->harga_jual)
                            ->setCellValue('G'.$cell, $subtot);

                    $no++;
                    $cell++;
                }

                $sell1     = $cell;
                
//                $objPHPExcel->getActiveSheet()->getStyle('F'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1.'')->getFont()->setBold(TRUE);
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $objPHPExcel->setActiveSheetIndex(0)
//                        ->setCellValue('A' . $sell1, 'Total Persediaan')->mergeCells('A'.$sell1.':E'.$sell1.'')
//                        ->setCellValue('F' . $sell1, $total);
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Stok');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");


            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_stok_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function csv_data_stok_gomed(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tipe       = $this->input->get('tipe');
            $stok       = $this->input->get('stok');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            switch ($tipe) {
                case '1' :
                    $stp = '<';
                    break;

                case '2' :
                    $stp = '';
                    break;

                case '3' :
                    $stp = '>=';
                    break;
            }

            $sql_stok = $this->db
                            ->where('status', '4')
                            ->where('status_subt', '1')
                            ->where('jml' . (isset($stp) ? ' ' . $stp : ''), $stok)
                            ->where('harga_jual >', '0')
                            ->order_by('id', 'ASC')
                            ->get('tbl_m_produk')->result();

            // Set headers to force file download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="combined_esensia_'.date('dmYH').'.csv"');

            # Open a new output stream for writing the CSV data
            $output = fopen('php://output', 'w');

            # Write CSV header row
            fputcsv($output, array('NO', 'TGL', 'KODE', 'ITEM', 'JML', 'HARGA', 'NILAI STOK'));

            # Ambil dari db
            $no    = 1;
            $cell  = 5;
            $total = 0;
            foreach ($sql_stok as $item){
                $subtot     = $item->harga_jual * $item->jml;
                $total      = $total + $subtot;   
                
                $data = array(
                    $no,
                    date('Y-m-d H:i'),
                    $item->kode,
                    $item->produk,
                    (float)$item->jml,
                    $item->harga_jual,
                    $subtot
                );
                
                fputcsv($output, $data);
                
                $no++;
            }
            
            # Close the output stream
            fclose($output);
            
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_stok_gomed(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tipe       = $this->input->get('tipe');
            $stok       = $this->input->get('stok');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
                switch ($tipe) {
                    case '0' :
                        $sql_stok = $this->db
                                    ->where('status_subt', '1')
                                    ->order_by('id', 'ASC')
                                    ->get('tbl_m_produk')->result();
                        break;
                    
                    case '1' :
                        $stp = '<';
                        $sql_stok = $this->db
                                    ->where('status_subt', '1')
                                    ->where('jml' . (isset($stp) ? ' ' . $stp : ''), $stok)
                                    ->order_by('id', 'ASC')
                                    ->get('tbl_m_produk')->result();
                        break;

                    case '2' :
                        $stp = '';
                        $sql_stok = $this->db
                                    ->where('status_subt', '1')
                                    ->where('jml' . (isset($stp) ? ' ' . $stp : ''), $stok)
                                    ->order_by('id', 'ASC')
                                    ->get('tbl_m_produk')->result();
                        break;

                    case '3' :
                        $stp = '>=';
                        $sql_stok = $this->db
                                    ->where('status_subt', '1')
                                    ->where('jml' . (isset($stp) ? ' ' . $stp : ''), $stok)
                                    ->order_by('id', 'ASC')
                                    ->get('tbl_m_produk')->result();
                    break;
                }
            

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getFont()->setBold(TRUE);
//            $objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(40);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'Tgl')
                    ->setCellValue('B4', 'Kode')
                    ->setCellValue('C4', 'Item')
                    ->setCellValue('D4', 'Stok')
                    ->setCellValue('E4', 'Satuan Jual')
                    ->setCellValue('F4', 'Harga Jual')
                    ->setCellValue('G4', 'Nilai Stok');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

            if(!empty($sql_stok)){
                $no    = 1;
                $cell  = 5;
                $total = 0;
                foreach ($sql_stok as $item){
                    $subtot     = $item->harga_jual * $item->jml;
                    $total      = $total + $subtot;

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':C'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$cell.':G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$cell.':G'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
               
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, date('Y-m-d H:i'))
                            ->setCellValue('B'.$cell, $item->kode)
                            ->setCellValue('C'.$cell, $item->produk)
                            ->setCellValue('D'.$cell, (float)$item->jml)
                            ->setCellValue('E'.$cell, 'UNIT')
                            ->setCellValue('F'.$cell, $item->harga_jual)
                            ->setCellValue('G'.$cell, $subtot);

                    $no++;
                    $cell++;
                }

                $sell1     = $cell;
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Export GoMed');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");



            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="combined_esensia_'.date('dmYH').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            ob_clean();
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_stok_keluar(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            switch ($case) {
                case 'per_tanggal':
                    $sql_omset = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck_det.tgl_simpan)', $tgl)
                                                              ->where('tbl_trans_medcheck_det.status', '4')
                                                              ->order_by('DATE(tbl_trans_medcheck_det.tgl_simpan)', 'ASC')
                                                          ->get('tbl_trans_medcheck_det')->result(); 
                    break;

                case 'per_rentang':
                        $sql_omset     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.metode, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.tgl_simpan, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.satuan, tbl_trans_medcheck_det.subtotal, tbl_m_kategori.keterangan AS kategori')
                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
                                                              ->where('tbl_trans_medcheck.status_hps', '0')
                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
                                                              ->where('DATE(tbl_trans_medcheck_det.tgl_simpan) >=', $tgl_awal)
                                                              ->where('DATE(tbl_trans_medcheck_det.tgl_simpan) <=', $tgl_akhir)
                                                              ->where('tbl_trans_medcheck_det.status', '4')
                                                              ->order_by('tbl_trans_medcheck_det.tgl_simpan', 'ASC')
                                                          ->get('tbl_trans_medcheck_det')->result();
                    break;
            }

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A4:N4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:N4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:N4')->getFont()->setBold(TRUE);
            $objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(40);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'No.')
                    ->setCellValue('B4', 'TGL')
                    ->setCellValue('C4', 'TIPE')
                    ->setCellValue('D4', 'ITEM')
                    ->setCellValue('E4', 'PASIEN')
                    ->setCellValue('F4', 'QTY')
                    ->setCellValue('G4', 'SATUAN')
                    ->setCellValue('H4', 'HARGA')
                    ->setCellValue('I4', 'SUBTOTAL');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(14);

            if(!empty($sql_omset)){
                $no    = 1;
                $cell  = 5;
                $total = 0;
                foreach ($sql_omset as $penjualan){
                    $sql_so     = $this->db->where('id_user', $penjualan->id_dokter)->get('tbl_m_karyawan')->row();
                    $dokter     = $this->db->where('id_user', $penjualan->id_dokter)->get('tbl_m_karyawan')->row();
                    $platform   = $this->db->where('id', $penjualan->metode)->get('tbl_m_platform')->row();
                    $sub_js     = $remun->remun_nom * $penjualan->jml;
                    $total      = $total + $penjualan->subtotal;
                    $subtot     = $penjualan->harga * $penjualan->jml;

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$cell.':I'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$cell, $val,PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$cell.':I'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
               
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo5($penjualan->tgl_simpan))
                            ->setCellValue('C'.$cell, 'Stok Keluar')
                            ->setCellValue('D'.$cell, $penjualan->item)
                            ->setCellValue('E'.$cell, $penjualan->nama_pgl)
                            ->setCellValue('F'.$cell, (float)$penjualan->jml)
                            ->setCellValue('G'.$cell, $penjualan->satuan)
                            ->setCellValue('H'.$cell, $penjualan->harga)
                            ->setCellValue('I'.$cell, $penjualan->subtotal);

                    $no++;
                    $cell++;
                }

                $sell1     = $cell;
                
                $objPHPExcel->getActiveSheet()->getStyle('L'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1.'')->getFont()->setBold(TRUE);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sell1, '')->mergeCells('A'.$sell1.':K'.$sell1.'')
                        ->setCellValue('L' . $sell1, $sql_omset_row->jml_gtotal);
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Omset');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");


            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_stok_keluar_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            ob_clean();
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_stok_mutasi(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            switch ($case) {
                case 'per_tanggal':
                    $sql_stok  = $this->db->where('DATE(tgl_simpan)', $tgl)
                                          ->get('v_laporan_stok')->result(); 
                    break;

                case 'per_rentang':
                    $sql_stok  = $this->db->where('DATE(tgl_simpan) >=', $tgl_awal)
                                          ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                          ->get('v_laporan_stok')->result(); 
                    break;
            }

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A4:N4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:N4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:N4')->getFont()->setBold(TRUE);
            $objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(40);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'No.')
                    ->setCellValue('B4', 'ITEM')
                    ->setCellValue('C4', 'STOK MASUK')
                    ->setCellValue('D4', 'STOK KELUAR')
                    ->setCellValue('E4', 'STOK SISA');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13);

            if(!empty($sql_stok)){
                $no    = 1;
                $cell  = 5;
                $total = 0;
                foreach ($sql_stok as $stok){
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$cell.':E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                    $objPHPExcel->getActiveSheet()->getStyle('C'.$cell.':E'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $stok->item)
                            ->setCellValue('C'.$cell, $stok->stok)
                            ->setCellValue('D'.$cell, $stok->laku)
                            ->setCellValue('E'.$cell, $stok->sisa_stok);

                    $no++;
                    $cell++;
                }
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Stok Mutasi');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");


            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="data_stok_mutasi_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            ob_clean();
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_stok_keluar_resep(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $dokter     = $this->input->get('dokter');
            $case       = $this->input->get('case');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            switch ($case) {
                case 'per_tanggal':
                    $sql_omset = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_resep_det.kode, tbl_trans_medcheck_resep_det.item, tbl_trans_medcheck_resep_det.dosis, tbl_trans_medcheck_resep_det.dosis_ket, tbl_trans_medcheck_resep_det.keterangan, tbl_trans_medcheck_resep_det.harga, tbl_trans_medcheck_resep_det.jml, tbl_trans_medcheck_resep_det.satuan')
                                                        ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resep_det.id_medcheck')
                                                        ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                        ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_resep_det.id_item_kat')
                                                        ->where('tbl_trans_medcheck.status_bayar', '1')
                                                        ->where('tbl_trans_medcheck_resep_det.id_user', general::dekrip($dokter))
                                                        ->where('DATE(tbl_trans_medcheck_resep_det.tgl_simpan)', $tgl)
                                                        ->order_by('tbl_trans_medcheck_resep_det.tgl_simpan', 'ASC')
                                                        ->get('tbl_trans_medcheck_resep_det')->result();
                    break;

                case 'per_rentang':
                        $sql_omset     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tipe_bayar, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.alamat, tbl_m_pasien.alamat_dom, tbl_m_pasien.instansi, tbl_m_pasien.instansi_alamat, tbl_trans_medcheck_resep_det.kode, tbl_trans_medcheck_resep_det.item, tbl_trans_medcheck_resep_det.dosis, tbl_trans_medcheck_resep_det.dosis_ket, tbl_trans_medcheck_resep_det.keterangan, tbl_trans_medcheck_resep_det.harga, tbl_trans_medcheck_resep_det.jml, tbl_trans_medcheck_resep_det.satuan')
                                                        ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resep_det.id_medcheck')
                                                        ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                                        ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_resep_det.id_item_kat')
                                                        ->where('tbl_trans_medcheck.status_bayar', '1')
                                                        ->where('tbl_trans_medcheck_resep_det.id_user', general::dekrip($dokter))
                                                        ->where('DATE(tbl_trans_medcheck_resep_det.tgl_simpan) >=', $tgl_awal)
                                                        ->where('DATE(tbl_trans_medcheck_resep_det.tgl_simpan) <=', $tgl_akhir)
                                                        ->order_by('tbl_trans_medcheck_resep_det.tgl_simpan', 'ASC')
                                                        ->get('tbl_trans_medcheck_resep_det')->result();
                    break;
            }
            
            $sql_dokter = $this->db->where('id_user', general::dekrip($dokter))->get('tbl_m_karyawan')->row();
            
            $judul = "LAPORAN PENGGUNAAN RESEP PER DOKTER";
            
            $objPHPExcel = new PHPExcel();
            
            // Header Laporan
            $objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFont()->setSize(16);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(TRUE);
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', $pengaturan->judul)->mergeCells('A1:I1');
            
            $objPHPExcel->getActiveSheet()->getStyle("A2:I2")->getFont()->setSize(13);
            $objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFont()->setBold(TRUE);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A2', $pengaturan->alamat)->mergeCells('A2:I2');
            
            $objPHPExcel->getActiveSheet()->getStyle("A3:I3")->getFont()->setSize(13);
            $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFont()->setBold(TRUE);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3', $judul.' '.$ket)->mergeCells('A3:I3');
            
            $objPHPExcel->getActiveSheet()->getStyle("A4:I4")->getFont()->setSize(14);
            $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->getFont()->setBold(TRUE);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', (!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').strtoupper($sql_dokter->nama).(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk : ''))->mergeCells('A4:I4');

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A6:N6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A6:N6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A6:N6')->getFont()->setBold(TRUE);
            $objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(36);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A6', 'No.')
                    ->setCellValue('B6', 'TGL')
                    ->setCellValue('C6', 'ID')
                    ->setCellValue('D6', 'PASIEN')
                    ->setCellValue('E6', 'ITEM')
                    ->setCellValue('F6', 'QTY')
                    ->setCellValue('G6', 'SATUAN')
                    ->setCellValue('H6', 'HARGA')
                    ->setCellValue('I6', 'SUBTOTAL');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(55);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(45);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);

            if(!empty($sql_omset)){
                $no    = 1;
                $cell  = 7;
                $total = 0;
                foreach ($sql_omset as $penjualan){
                    $subtot     = $penjualan->harga * $penjualan->jml;

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell.':C'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$cell.':G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$cell.':I'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
               
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo5($penjualan->tgl_simpan))
                            ->setCellValue('C'.$cell, $penjualan->no_rm)
                            ->setCellValue('D'.$cell, $penjualan->nama_pgl)
                            ->setCellValue('E'.$cell, $penjualan->item)
                            ->setCellValue('F'.$cell, (float)$penjualan->jml)
                            ->setCellValue('G'.$cell, $penjualan->satuan)
                            ->setCellValue('H'.$cell, $penjualan->harga)
                            ->setCellValue('I'.$cell, $subtot);

                    $no++;
                    $cell++;
                }

//                $sell1     = $cell;
//                
//                $objPHPExcel->getActiveSheet()->getStyle('L'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1.'')->getFont()->setBold(TRUE);
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $objPHPExcel->setActiveSheetIndex(0)
//                        ->setCellValue('A' . $sell1, '')->mergeCells('A'.$sell1.':K'.$sell1.'')
//                        ->setCellValue('L' . $sell1, $sql_omset_row->jml_gtotal);
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Penggunaan Resep');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");


            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_stok_keluar_resep_'.str_replace(' ', '', (!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').strtoupper($sql_dokter->nama).(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk : '')).'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_stok_telusur(){
        if (akses::aksesLogin() == TRUE) {
            $id        = $this->input->get('id');
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('act');
            $hal        = $this->input->get('halaman');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            switch ($case) {
                case 'per_tanggal':
                    $sql_stok     = $this->db
                                         ->where('id_produk', general::dekrip($id))
                                         ->where('DATE(tgl_masuk)', $tgl)
                                         ->group_by('tgl_simpan, id_penjualan, id_pembelian, id_pembelian_det, keterangan')
                                         ->order_by('tgl_masuk', 'asc')
                                         ->get('v_produk_hist')->result();
                    break;

                case 'per_rentang':
                    $sql_stok     = $this->db
                                         ->where('id_produk', general::dekrip($id))
                                         ->where('DATE(tgl_masuk) >=', $tgl_awal)
                                         ->where('DATE(tgl_masuk) <=', $tgl_akhir)
                                         ->group_by('tgl_simpan, id_penjualan, id_pembelian, id_pembelian_det, keterangan')
                                         ->order_by('tgl_masuk', 'asc')
                                         ->get('v_produk_hist')->result();
                    break;
            }

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A4:N4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:N4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A4:N4')->getFont()->setBold(TRUE);
            $objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(40);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'No.')
                    ->setCellValue('B4', 'TGL')
                    ->setCellValue('C4', 'ITEM')
                    ->setCellValue('D4', 'KETERANGAN')
                    ->setCellValue('E4', 'STOK MASUK')
                    ->setCellValue('F4', 'STOK KELUAR');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(65);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(40);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(14);

            if(!empty($sql_stok)){
                $no    = 1;
                $cell  = 5;
                $total = 0;
                foreach ($sql_stok as $hist){
                    $sql_nota_dt    = $this->db->where('id', $hist->id_pembelian_det)->get('tbl_trans_beli_det')->row();
                    
//                    $sql_so     = $this->db->where('id_user', $hist->id_dokter)->get('tbl_m_karyawan')->row();
//                    $dokter     = $this->db->where('id_user', $hist->id_dokter)->get('tbl_m_karyawan')->row();
//                    $platform   = $this->db->where('id', $hist->metode)->get('tbl_m_platform')->row();
//                    $sub_js     = $remun->remun_nom * $hist->jml;
//                    $total      = $total + $hist->subtotal;
//                    $subtot     = $hist->harga * $hist->jml;

//                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                    $objPHPExcel->getActiveSheet()->getStyle('F'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                    $objPHPExcel->getActiveSheet()->getStyle('J'.$cell.':K'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                    $objPHPExcel->getActiveSheet()->getStyle('L'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$cell, $val,PHPExcel_Cell_DataType::TYPE_STRING);
//                    $objPHPExcel->getActiveSheet()->getStyle('J'.$cell.':K'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
               
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo5($hist->tgl_masuk))
                            ->setCellValue('C'.$cell, $hist->produk)
                            ->setCellValue('D'.$cell, $hist->keterangan.(!empty($sql_nota_dt->kode_batch) ? ' / ['.$sql_nota_dt->kode_batch.']' : ''))
                            ->setCellValue('E'.$cell, ($hist->status == '1' || $hist->status == '2' || $hist->status == '6' ? (float)$hist->jml : '0'))
                            ->setCellValue('F'.$cell, ($hist->status == '4' ? (float)$hist->jml : '0'));

                    $no++;
                    $cell++;
                }
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Omset');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");


            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_stok_riwayat_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_pasien(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $bln        = $this->input->get('bln');
            $case       = $this->input->get('case');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
            switch ($case) {
                case 'per_tanggal':
                    $sql_pasien     = $this->db->select('tbl_m_pasien.id, tbl_m_pasien.kode_dpn, tbl_m_pasien.kode, tbl_m_pasien.nama, tbl_m_pasien.no_hp, tbl_m_pasien.tgl_lahir, DAY(tbl_m_pasien.tgl_lahir) AS hari, MONTH(tbl_m_pasien.tgl_lahir) AS bulan')
                                               ->where('tbl_m_pasien.no_hp !=', '')
                                               ->where('DAY(tbl_m_pasien.tgl_lahir)', $tgl)
                                               ->where('MONTH(tbl_m_pasien.tgl_lahir)', $bln)
                                               ->order_by('tbl_m_pasien.nama', 'ASC') 
                                               ->get('tbl_m_pasien')->result();
                    break;
            }

            $objPHPExcel = new PHPExcel();

            # Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Whatsapp Number')
                    ->setCellValue('B1', 'Name')
                    ->setCellValue('C1', 'Col1')
                    ->setCellValue('D1', 'Tgl Lahir')
                    ->setCellValue('E1', 'Col3');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(55);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);

            if(!empty($sql_pasien)){
                $no    = 1;
                $cell  = 2;
                $total = 0;
                foreach ($sql_pasien as $pasien){
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getNumberFormat()->setFormatCode('#');
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell.':C'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$cell.':E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, (!empty($pasien->no_hp) ? "62".substr($pasien->no_hp, 1) : ''))
                            ->setCellValue('B'.$cell, $pasien->nama)
                            ->setCellValue('C'.$cell, $this->tanggalan->bulan_ke($pasien->bulan))
                            ->setCellValue('D'.$cell, $this->tanggalan->tgl_indo8($pasien->tgl_lahir))
                            ->setCellValue('E'.$cell, $pasien->bulan);

                    $no++;
                    $cell++;
                }
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Data Pasien');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");

//            ob_start();
            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
//             header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_pasien_wa_lap.xlsx"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
//            ob_end_flush();
            
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_pasien_kunj(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $bln        = $this->input->get('bln');
            $tipe       = $this->input->get('tipe');
            $poli       = $this->input->get('poli');
            $case       = $this->input->get('case');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
            switch ($case){                
                case 'per_tanggal':
                    $sql_pasien = $this->db
                                                   ->where('DATE(tgl_simpan)', $tgl)
                                                   ->like('id_poli', $poli)
                                                   ->like('tipe', $tipe)
                                                   ->get('v_medcheck_visit')->result();
                    break;

                case 'per_rentang':
                    $sql_pasien = $this->db
                                                   ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                                   ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                                   ->like('id_poli', $poli)
                                                   ->like('tipe', $tipe)
                                                   ->get('v_medcheck_visit')->result();
                    break;
            }

            $objPHPExcel = new PHPExcel();

            # Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No.')
                    ->setCellValue('B1', 'RM')
                    ->setCellValue('C1', 'Pasien')
                    ->setCellValue('D1', 'Kunjungan')
                    ->setCellValue('E1', 'Omset');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(55);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(11);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);

            if(!empty($sql_pasien)){
                $no    = 1;
                $cell  = 2;
                $total_kunj = 0;
                $total_oms  = 0;
                foreach ($sql_pasien as $pasien){
                    $total_kunj = $total_kunj + $pasien->jml_kunjungan;
                    $total_oms  = $total_oms + $pasien->jml_gtotal;
                    
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell.':B'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $pasien->kode)
                            ->setCellValue('C'.$cell, $pasien->nama)
                            ->setCellValue('D'.$cell, $pasien->jml_kunjungan)
                            ->setCellValue('E'.$cell, $pasien->jml_gtotal);

                    $no++;
                    $cell++;
                }
                
                $sell = $cell;
                
                $objPHPExcel->getActiveSheet()->getStyle('E'.$sell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell.':B'.$sell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$sell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$sell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$sell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$sell, '')
                        ->setCellValue('B'.$sell, '')
                        ->setCellValue('C'.$sell, 'TOTAL')
                        ->setCellValue('D'.$sell, $total_kunj)
                        ->setCellValue('E'.$sell, $total_oms);
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Data Kunjungan Pasien');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");

//            ob_start();
            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
//             header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_pasien_kunj_lap.xlsx"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
//            ob_end_flush();
            
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_pasien2(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $bln        = $this->input->get('bln');
            $case       = $this->input->get('case');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
            switch ($case) {
                    default:
                    $sql_pasien     = $this->db->select('tbl_m_pasien.id, tbl_m_pasien.kode_dpn, tbl_m_pasien.kode, tbl_m_pasien.nama, tbl_m_pasien.no_hp, tbl_m_pasien.tgl_lahir, DAY(tbl_m_pasien.tgl_lahir) AS hari, MONTH(tbl_m_pasien.tgl_lahir) AS bulan')
//                                               ->where('tbl_m_pasien.no_hp !=', '')
//                                               ->where('DAY(tbl_m_pasien.tgl_lahir)', $tgl)
//                                               ->where('MONTH(tbl_m_pasien.tgl_lahir)', $bln)
                                               ->order_by('tbl_m_pasien.nama', 'ASC') 
                                               ->get('tbl_m_pasien')->result();
                    break;
                
                case 'per_tanggal':
                    $sql_pasien     = $this->db->select('tbl_m_pasien.id, tbl_m_pasien.kode_dpn, tbl_m_pasien.kode, tbl_m_pasien.nama, tbl_m_pasien.no_hp, tbl_m_pasien.tgl_lahir, DAY(tbl_m_pasien.tgl_lahir) AS hari, MONTH(tbl_m_pasien.tgl_lahir) AS bulan')
                                               ->where('tbl_m_pasien.no_hp !=', '')
                                               ->where('DAY(tbl_m_pasien.tgl_lahir)', $tgl)
                                               ->where('MONTH(tbl_m_pasien.tgl_lahir)', $bln)
                                               ->order_by('tbl_m_pasien.nama', 'ASC') 
                                               ->get('tbl_m_pasien')->result();
                    break;
            }

            $objPHPExcel = new PHPExcel();

            # Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'No. RM')
                    ->setCellValue('C1', 'Pasien')
                    ->setCellValue('D1', 'Alamat')
                    ->setCellValue('E1', 'Tgl Lahir')
                    ->setCellValue('F1', 'No. HP');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(55);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);

            if(!empty($sql_pasien)){
                $no    = 1;
                $cell  = 2;
                $total = 0;
                foreach ($sql_pasien as $pasien){
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getNumberFormat()->setFormatCode('#');
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell.':C'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$cell.':E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, '')
                            ->setCellValue('B'.$cell, $pasien->kode_dpn.$pasien->kode)
                            ->setCellValue('C'.$cell, $pasien->nama)
                            ->setCellValue('D'.$cell, $pasien->alamat)
                            ->setCellValue('E'.$cell, $this->tanggalan->tgl_indo8($pasien->tgl_lahir))
                            ->setCellValue('F'.$cell, (!empty($pasien->no_hp) ? "62".substr($pasien->no_hp, 1) : ''));

                    $no++;
                    $cell++;
                }
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Data Pasien');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");

//            ob_start();
            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
//             header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_pasien.xlsx"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
//            ob_end_flush();
            
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_karyawan_ultah(){
        if (akses::aksesLogin() == TRUE) {
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $bln        = $this->input->get('bln');
            $hr_awal    = $this->input->get('hr_awal');
            $bln_awal   = $this->input->get('bln_awal');
            $hr_akhir   = $this->input->get('hr_akhir');
            $bln_akhir  = $this->input->get('bln_akhir');
            $case       = $this->input->get('case');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
            switch ($case) {
                    case 'per_tanggal':
                        $sql_karyawan     = $this->db->select('tbl_m_karyawan.id, tbl_m_karyawan.nik, tbl_m_karyawan.kode, tbl_m_karyawan.nama, tbl_m_karyawan.no_hp, tbl_m_karyawan.tgl_lahir, DAY(tbl_m_karyawan.tgl_lahir) AS hari, MONTH(tbl_m_karyawan.tgl_lahir) AS bulan')
                                                            ->where('tbl_m_karyawan.no_hp !=', '')
                                                            ->where('DAY(tbl_m_karyawan.tgl_lahir)', $tgl)
                                                            ->where('MONTH(tbl_m_karyawan.tgl_lahir)', $bln)
                                                            ->order_by('tbl_m_karyawan.nama', 'ASC') 
                                                            ->get('tbl_m_karyawan')->result();
                        break;
                    
                    case 'per_rentang':
                        $sql_karyawan     = $this->db->select('tbl_m_karyawan.id, tbl_m_karyawan.nik, tbl_m_karyawan.kode, tbl_m_karyawan.nama, tbl_m_karyawan.no_hp, tbl_m_karyawan.tgl_lahir, DAY(tbl_m_karyawan.tgl_lahir) AS hari, MONTH(tbl_m_karyawan.tgl_lahir) AS bulan')
                                                            ->where('tbl_m_karyawan.no_hp !=', '')
                                                            ->where('DAY(tbl_m_karyawan.tgl_lahir) >=', $hr_awal)
                                                            ->where('MONTH(tbl_m_karyawan.tgl_lahir) >=', $bln_awal)
                                                            ->where('DAY(tbl_m_karyawan.tgl_lahir) <=', $hr_akhir)
                                                            ->where('MONTH(tbl_m_karyawan.tgl_lahir) <=', $bln_akhir)
//                                                            ->limit($config['per_page'], $hal)
                                                            ->order_by('tbl_m_karyawan.nama', 'ASC') 
                                                            ->get('tbl_m_karyawan')->result();
                        break;
            }

            $objPHPExcel = new PHPExcel();

            # Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Whatsapp Number')
                    ->setCellValue('B1', 'Name')
                    ->setCellValue('C1', 'Col1')
                    ->setCellValue('D1', 'Tgl Lahir')
                    ->setCellValue('E1', 'Col3');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(55);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);

            if(!empty($sql_karyawan)){
                $no    = 1;
                $cell  = 2;
                $total = 0;
                foreach ($sql_karyawan as $karyawan){
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getNumberFormat()->setFormatCode('#');
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell.':C'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$cell.':E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, (!empty($karyawan->no_hp) ? "62".substr($karyawan->no_hp, 1) : ''))
                            ->setCellValue('B'.$cell, $karyawan->nama)
                            ->setCellValue('C'.$cell, $this->tanggalan->bulan_ke($karyawan->bulan))
                            ->setCellValue('D'.$cell, $this->tanggalan->tgl_indo8($karyawan->tgl_lahir))
                            ->setCellValue('E'.$cell, $karyawan->bulan);

                    $no++;
                    $cell++;
                }
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Data Karyawan');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");

//            ob_start();
            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
//             header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_karyawan_wa_lap.xlsx"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
//            ob_end_flush();
            
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function xls_data_tracer(){
        if (akses::aksesLogin() == TRUE) {
            $dokter     = $this->input->get('id_dokter');
            $jml        = $this->input->get('jml');
            $tgl        = $this->input->get('tgl');
            $tgl_awal   = $this->input->get('tgl_awal');
            $tgl_akhir  = $this->input->get('tgl_akhir');
            $case       = $this->input->get('case');
            $tipe       = $this->input->get('tipe');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $grup       = $this->ion_auth->get_users_groups()->row();
            $id_user    = $this->ion_auth->user()->row()->id;
            $id_grup    = $this->ion_auth->get_users_groups()->row();

            
            switch ($case) {
                case 'per_tanggal':
                    $sql_tracer = $this->db
                                    ->where('DATE(tgl_simpan)', $tgl)
                                    ->get('v_medcheck_tracer')->result();
                    break;

                case 'per_rentang':
                    $sql_tracer = $this->db
                                    ->where('DATE(tgl_simpan) >=', $tgl_awal)
                                    ->where('DATE(tgl_simpan) <=', $tgl_akhir)
                                    ->get('v_medcheck_tracer')->result();
                    break;
            }

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:S5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:S5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:S5')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'LAPORAN TRACER PENGUNJUNG')->mergeCells('A1:S1');
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A2', $pengaturan->judul)->mergeCells('A2:S2');
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'No.')->mergeCells('A4:A5')
                    ->setCellValue('B4', 'Pasien')->mergeCells('B4:B5')
                    ->setCellValue('C4', 'Tanggal')->mergeCells('C4:C5')
                    ->setCellValue('D4', 'Pendaftaran')->mergeCells('D4:D5')
                    ->setCellValue('E4', 'PX Dokter')->mergeCells('E4:E5')
                    ->setCellValue('F4', 'Laborat')->mergeCells('F4:H4')
                    ->setCellValue('I4', 'Radiologi')->mergeCells('I4:K4')
                    ->setCellValue('L4', 'Farmasi')->mergeCells('L4:N4')
                    ->setCellValue('O4', 'Rawat Inap')->mergeCells('O4:Q4')
                    ->setCellValue('R4', 'Selesai')->mergeCells('R4:R5')
                    ->setCellValue('S4', 'Total')->mergeCells('S4:S5');
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A5', '')
                    ->setCellValue('B5', '')
                    ->setCellValue('C5', '')
                    ->setCellValue('D5', '')
                    ->setCellValue('E5', '')
                    ->setCellValue('F5', 'Masuk')
                    ->setCellValue('G5', 'Keluar')
                    ->setCellValue('H5', 'Total')
                    ->setCellValue('I5', 'Masuk')
                    ->setCellValue('J5', 'Keluar')
                    ->setCellValue('K5', 'Total')
                    ->setCellValue('L5', 'Masuk')
                    ->setCellValue('M5', 'Keluar')
                    ->setCellValue('N5', 'Total')
                    ->setCellValue('O5', 'Masuk')
                    ->setCellValue('P5', 'Keluar')
                    ->setCellValue('Q5', 'Total')
                    ->setCellValue('R5', '')
                    ->setCellValue('S5', '');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(11);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(8);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(8);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);

            if(!empty($sql_tracer)){
                $no    = 1;
                $cell  = 6;
                $total = 0;
                foreach ($sql_tracer as $trace){
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$cell.':P'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('Q'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('R'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('S'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $trace->nama_pgl)
                            ->setCellValue('C'.$cell, $this->tanggalan->tgl_indo($trace->tanggal))
                            ->setCellValue('D'.$cell, $this->tanggalan->wkt_indo($trace->wkt_daftar))
                            ->setCellValue('E'.$cell, $this->tanggalan->wkt_indo($trace->wkt_periksa))
                            ->setCellValue('F'.$cell, $this->tanggalan->wkt_indo($trace->wkt_sampling_msk))
                            ->setCellValue('G'.$cell, $this->tanggalan->wkt_indo($trace->wkt_sampling_klr))
                            ->setCellValue('H'.$cell, $this->tanggalan->usia_wkt($trace->wkt_sampling_msk, $trace->wkt_sampling_klr))
                            ->setCellValue('I'.$cell, $this->tanggalan->wkt_indo($trace->wkt_rad_msk))
                            ->setCellValue('J'.$cell, $this->tanggalan->wkt_indo($trace->wkt_rad_klr))
                            ->setCellValue('K'.$cell, $this->tanggalan->usia_wkt($trace->wkt_rad_msk, $trace->wkt_rad_klr))
                            ->setCellValue('L'.$cell, $this->tanggalan->wkt_indo($trace->wkt_farmasi_msk))
                            ->setCellValue('M'.$cell, $this->tanggalan->wkt_indo($trace->wkt_farmasi_klr))
                            ->setCellValue('N'.$cell, $this->tanggalan->usia_wkt($trace->wkt_farmasi_msk, $trace->wkt_farmasi_klr))
                            ->setCellValue('O'.$cell, $this->tanggalan->tgl_indo5($trace->wkt_ranap))
                            ->setCellValue('P'.$cell, $this->tanggalan->tgl_indo5($trace->wkt_ranap_keluar))
                            ->setCellValue('Q'.$cell, $this->tanggalan->usia_wkt($trace->wkt_ranap, $trace->wkt_ranap_keluar))
                            ->setCellValue('R'.$cell, $this->tanggalan->tgl_indo5($trace->wkt_selesai))
                            ->setCellValue('S'.$cell, $this->tanggalan->usia_wkt($trace->wkt_daftar, $trace->wkt_selesai));

                    $no++;
                    $cell++;
                }

                $sell1     = $cell;
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('LAP TRACER');

            /** Page Setup * */
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            /* -- Margin -- */
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setTop(0.25);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setRight(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setLeft(0);
            $objPHPExcel->getActiveSheet()
                    ->getPageMargins()->setFooter(0);


            /** Page Setup * */
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
                    ->setTitle("Stok")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");


            ob_end_clean();
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_tracer_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    
    
    

    
    public function json_pasien() {
        if (akses::aksesLogin() == TRUE) {
            $term  = $this->input->get('term');
            $sql   = $this->db->select('id, kode, kode_dpn, nik, nama_pgl, alamat, jns_klm, tgl_lahir')
                              ->like('nama',$term)
                              ->or_like('nik',$term)
                              ->or_like('alamat',$term)
                              ->limit(10)->get('tbl_m_pasien')->result();
            
            if(!empty($sql)){
                foreach ($sql as $sql){
                    $produk[] = array(
                        'id'         => $sql->id,
                        'id_pas'     => general::enkrip($sql->id),
                        'kode'       => $sql->kode_dpn.$sql->kode,
                        'nik'        => $sql->nik,
                        'nama'       => $sql->nama_pgl,
                        'nama2'      => $sql->nama,
                        'tgl_lahir'  => $sql->tgl_lahir,
                        'jns_klm'    => $sql->jns_klm,
                        'alamat'     => $sql->alamat,
                    );
                }
                
                if(!empty($term)){
                    echo json_encode($produk);
                }
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    
    
    
//    public function xls_data_stok_keluar(){
//        if (akses::aksesLogin() == TRUE) {
//            $jml        = $this->input->get('jml');
//            $tgl        = $this->input->get('tgl');
//            $tgl_awal   = $this->input->get('tgl_awal');
//            $tgl_akhir  = $this->input->get('tgl_akhir');
//            $case       = $this->input->get('case');
//            $hal        = $this->input->get('halaman');
//            $pengaturan = $this->db->get('tbl_pengaturan')->row();
//
//            $grup       = $this->ion_auth->get_users_groups()->row();
//            $id_user    = $this->ion_auth->user()->row()->id;
//            $id_grup    = $this->ion_auth->get_users_groups()->row();
//
//            
//            switch ($case) {
//                case 'per_tanggal':
//                    $sql_omset = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_simpan, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal')
//                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
//                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
//                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
//                                                              ->where('DATE(tbl_trans_medcheck_det.tgl_simpan)', $tgl)
//                                                              ->where('tbl_trans_medcheck_det.status', '4')
//                                                              ->order_by('DATE(tbl_trans_medcheck_det.tgl_simpan)', 'ASC')
//                                                          ->get('tbl_trans_medcheck_det')->result(); 
//                    break;
//
//                case 'per_rentang':
//                        $sql_omset     = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.metode, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.tipe, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_trans_medcheck_det.id AS id_medcheck_det, tbl_trans_medcheck_det.tgl_simpan, tbl_trans_medcheck_det.id_dokter, tbl_trans_medcheck_det.kode, tbl_trans_medcheck_det.item, tbl_trans_medcheck_det.harga, tbl_trans_medcheck_det.jml, tbl_trans_medcheck_det.subtotal, tbl_m_kategori.keterangan AS kategori')
//                                                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_det.id_medcheck')
//                                                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
//                                                              ->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')
//                                                              ->where('tbl_trans_medcheck.status_hps', '0')
//                                                              ->where('tbl_trans_medcheck.status_bayar', '1')
//                                                              ->where('DATE(tbl_trans_medcheck_det.tgl_simpan) >=', $tgl_awal)
//                                                              ->where('DATE(tbl_trans_medcheck_det.tgl_simpan) <=', $tgl_akhir)
//                                                              ->where('tbl_trans_medcheck_det.status', '4')
//                                                              ->order_by('tbl_trans_medcheck_det.tgl_simpan', 'ASC')
//                                                          ->get('tbl_trans_medcheck_det')->result();
//                    break;
//            }
//
//            $objPHPExcel = new PHPExcel();
//
//            // Header Tabel Nota
//            $objPHPExcel->getActiveSheet()->getStyle('A4:N4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//            $objPHPExcel->getActiveSheet()->getStyle('A4:N4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//            $objPHPExcel->getActiveSheet()->getStyle('A4:N4')->getFont()->setBold(TRUE);
//            $objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(40);
//
//            $objPHPExcel->setActiveSheetIndex(0)
//                    ->setCellValue('A4', 'No.')
//                    ->setCellValue('B4', 'Tgl')
//                    ->setCellValue('C4', 'Tipe')
//                    ->setCellValue('D4', 'Pasien')
//                    ->setCellValue('E4', 'No. Faktur')
//                    ->setCellValue('F4', 'Qty')
//                    ->setCellValue('G4', 'Kode')
//                    ->setCellValue('H4', 'Item')
//                    ->setCellValue('I4', 'Group')
//                    ->setCellValue('J4', 'Harga')
//                    ->setCellValue('K4', 'Subtotal')
//                    ->setCellValue('L4', 'Jenis')
//                    ->setCellValue('M4', 'Kode Jenis');
//
//            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(5);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(40);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(14);
//            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(14);
//
//            if(!empty($sql_omset)){
//                $no    = 1;
//                $cell  = 5;
//                $total = 0;
//                foreach ($sql_omset as $penjualan){
//                    $dokter     = $this->db->where('id_user', $penjualan->id_dokter)->get('tbl_m_karyawan')->row();
//                    $platform   = $this->db->where('id', $penjualan->metode)->get('tbl_m_platform')->row();
//                    $sub_js     = $remun->remun_nom * $penjualan->jml;
//                    $total      = $total + $penjualan->subtotal;
//                    $subtot     = $penjualan->harga * $penjualan->jml;
//
//                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell.':C'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                    $objPHPExcel->getActiveSheet()->getStyle('E'.$cell.':G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                    $objPHPExcel->getActiveSheet()->getStyle('J'.$cell.':K'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                    $objPHPExcel->getActiveSheet()->getStyle('L'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
////                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
////                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':N'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
////                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$cell, $val,PHPExcel_Cell_DataType::TYPE_STRING);
//                    $objPHPExcel->getActiveSheet()->getStyle('J'.$cell.':K'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//               
//                    $objPHPExcel->setActiveSheetIndex(0)
//                            ->setCellValue('A'.$cell, $no)
//                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo5($penjualan->tgl_simpan))
//                            ->setCellValue('C'.$cell, general::status_rawat2($penjualan->tipe))
//                            ->setCellValue('D'.$cell, $penjualan->nama_pgl)
//                            ->setCellValue('E'.$cell, $penjualan->no_rm)
//                            ->setCellValue('F'.$cell, (float)$penjualan->jml)
//                            ->setCellValue('G'.$cell, $penjualan->kode)
//                            ->setCellValue('H'.$cell, $penjualan->item)
//                            ->setCellValue('I'.$cell, $penjualan->kategori)
//                            ->setCellValue('J'.$cell, $penjualan->harga)
//                            ->setCellValue('K'.$cell, $subtot)
//                            ->setCellValue('L'.$cell, $platform->platform)
//                            ->setCellValue('M'.$cell, '');
//
//                    $no++;
//                    $cell++;
//                }
//
//                $sell1     = $cell;
//                
//                $objPHPExcel->getActiveSheet()->getStyle('L'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1.'')->getFont()->setBold(TRUE);
//                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $objPHPExcel->setActiveSheetIndex(0)
//                        ->setCellValue('A' . $sell1, '')->mergeCells('A'.$sell1.':K'.$sell1.'')
//                        ->setCellValue('L' . $sell1, $sql_omset_row->jml_gtotal);
//            }
//
//            // Rename worksheet
//            $objPHPExcel->getActiveSheet()->setTitle('Lap Omset');
//
//            /** Page Setup * */
//            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
//            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
//
//            /* -- Margin -- */
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setTop(0.25);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setRight(0);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setLeft(0);
//            $objPHPExcel->getActiveSheet()
//                    ->getPageMargins()->setFooter(0);
//
//
//            /** Page Setup * */
//            // Set document properties
//            $objPHPExcel->getProperties()->setCreator("Mikhael Felian Waskito")
//                    ->setLastModifiedBy($this->ion_auth->user()->row()->username)
//                    ->setTitle("Stok")
//                    ->setSubject("Aplikasi Bengkel POS")
//                    ->setDescription("Kunjungi http://tigerasoft.co.id")
//                    ->setKeywords("Pasifik POS")
//                    ->setCategory("Untuk mencetak nota dot matrix");
//
//
//
//            // Redirect output to a client’s web browser (Excel5)
//            header('Content-Type: application/vnd.ms-excel');
//            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//            header('Content-Disposition: attachment;filename="data_stok_keluar_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');
//
//            // If you're serving to IE over SSL, then the following may be needed
//            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
//            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
//            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//            header('Pragma: public'); // HTTP/1.0
//
//            ob_clean();
//            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//            $objWriter->save('php://output');
//            exit;
//        }else{
//            $errors = $this->ion_auth->messages();
//            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
//            redirect();
//        }
//    }
}
