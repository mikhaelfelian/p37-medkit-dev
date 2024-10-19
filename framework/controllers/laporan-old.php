<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php

/**
 * Description of laporan
 * wajahku ganteng
 * @author USER
 */
class laporan extends CI_Controller {
    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->library('fpdf');
        $this->load->library('excel/PHPExcel');
    }

    public function data_persediaan(){
        if (akses::aksesLogin() == TRUE) {
            $case  = $this->input->get('case');
            $query = $this->input->get('query');
            $p     = $this->input->get('filter_stok');

            switch ($p){
                case '1';
                    $param = '';
                    break;
                case '2';
                    $param = '>';
                    break;
                case '3';
                    $param = '<';
                    break;
            }

            switch ($case){
                case 'semua':
                    $data['produk'] = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id, kode, kode, produk, jml, id_satuan, harga_beli, harga_jual')->order_by('id','desc')->get('tbl_m_produk')->result();
                    break;

                case 'per_tanggal':
                    $data['produk'] = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id, kode, kode, produk, jml, id_satuan, harga_beli, harga_jual')->where('DATE(tgl_simpan)',$this->input->get('query'))->order_by('id','desc')->get('tbl_m_produk')->result();
                    break;

                case 'per_rentang':
                    $data['produk'] = $this->db->query("SELECT DATE(tgl_simpan) as tgl_simpan, id, kode, kode, produk, jml, id_satuan, harga_beli, harga_jual FROM tbl_m_produk WHERE DATE(tgl_simpan) BETWEEN '".$this->input->get('tgl_awal')."' AND '".$this->input->get('tgl_akhir')."'")->result();
                    break;

                case 'per_stok':
                    $data['produk'] = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id, kode, kode, produk, jml, id_satuan, harga_beli, harga_jual')
                                               ->where('jml '.$param, $query)
                                               ->order_by('id','desc')->get('tbl_m_produk')->result();
                    break;
            }

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_data_produk', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_stok2(){
        if (akses::aksesLogin() == TRUE) {
            $tipe   = $this->input->get('st');
            $stok   = $this->input->get('stok');
            $merk   = $this->input->get('merk');

            switch ($tipe){
                case '1' :
                    $st = '<';
                    break;

                case '2' :
                    $st = '';
                    break;

                case '3' :
                    $st = '>';
                    break;
            }

            $data['merk']   = $this->db->get('tbl_m_merk')->result();

            if(!empty($tipe)){
                $data['stok'] = $this->db
                                 ->where('jml'.(isset($st) ? ' '.$st : ''), $stok)
                                 ->like('id_merk', $merk, (!empty($merk) ? 'none' : ''))
                                 ->get('tbl_m_produk')->result();
            }

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_data_stok2', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_stok(){
        if (akses::aksesLogin() == TRUE) {
            $bulan  = $this->input->get('bulan');
            $tahun  = $this->input->get('tahun');

            $data['stok'] = $this->db->select('pd.id, pd.kode, pd.produk, ph.jml, pd.harga_beli, ph.keterangan, DATE(ph.tgl_simpan) AS tgl_simpan, ph.id_penjualan, ph.id_pembelian, ph.`status`')
                                 ->join('tbl_m_produk pd','ph.id_produk=pd.id')
                                 ->where('MONTH(ph.tgl_simpan)', $bulan)
                                 ->where('YEAR(ph.tgl_simpan)', $tahun)
                                 ->group_by('pd.produk')
                                 ->order_by('ph.tgl_simpan', 'desc')
//                                 ->limit(20)
                                 ->get('tbl_m_produk_hist ph')->result();

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_data_stok', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_penjualan(){
        if (akses::aksesLogin() == TRUE) {
            $case        = $this->input->get('case');
            $sales       = $this->input->get('id_sales');
            $sb          = $this->input->get('sb');
            $tipe        = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');
            $grup        = $this->ion_auth->get_users_groups()->row();
            $id_user     = $this->ion_auth->user()->row()->id;
            $id_grup     = $this->ion_auth->get_users_groups()->row();
            $hal         = $this->input->get('halaman');
            $jml         = $this->input->get('jml');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            switch ($case){
                case 'semua':
                        $sql_jml = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->like('id_user', $sales)
                                        ->like('tbl_trans_jual.metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('id_sales', $sales)
                                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->order_by('no_nota', 'asc')
                                        ->get('tbl_trans_jual')->num_rows();

                    $jml_hal     = (!empty($jml) ? $jml  : $sql_jml);
                    break;

                case 'sales':
                    $sql_jml = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                        ->where('id_user',$_GET['query'])
                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                        ->order_by('no_nota','asc')
                        ->get('tbl_trans_jual')->num_rows();

                    $jml_hal     = (!empty($jml) ? $jml  : $sql_jml);
                    break;

                case 'per_tanggal':
                    if($_GET['metode'] == 'x'){
                        $sql_penj = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
//                                        ->where('tbl_trans_jual.metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'asc')
                                        ->get('tbl_trans_jual');

                        $sql_jml = $sql_penj->num_rows();
                        $jml_hal     = (!empty($jml) ? $jml  : $sql_jml);
                    }else{
                        $sql_jml = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
                                        ->where('tbl_trans_jual.metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'asc')
                                        ->get('tbl_trans_jual')->num_rows();
                            $jml_hal     = (!empty($jml) ? $jml  : $sql_jml);
                    }
                    break;

                case 'per_rentang':
                    if($_GET['metode'] == 'x') {
                        $sql_jml = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tbl_trans_jual.tgl_masuk) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tbl_trans_jual.tgl_masuk) <=', $_GET['tgl_akhir'])
//                                              ->where('status_lap', '1')
//                                              ->where('tbl_trans_jual.metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'asc')
                                        ->get('tbl_trans_jual')->num_rows();

                        $jml_hal = (!empty($jml) ? $jml : $sql_jml);
                    } else {
                        $sql_jml = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tbl_trans_jual.tgl_masuk) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tbl_trans_jual.tgl_masuk) <=', $_GET['tgl_akhir'])
                                        ->where('tbl_trans_jual.metode_bayar' . ($_GET['metode'] > 1 ? ' >' : ''), ($_GET['metode'] > 1 ? '1' : $_GET['metode']))
//                                            ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'asc')
                                        ->get('tbl_trans_jual')->num_rows();
                        $jml_hal = (!empty($jml) ? $jml : $sql_jml);
                    }
                    break;
//
//                case 'per_rentang':
//                    $sql_jml = $this->db->query("SELECT DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan FROM tbl_trans_jual WHERE ".(!empty($sales) ? "id_sales='".$sales."' AND" : "")." DATE(tgl_masuk) >='".$this->input->get('tgl_awal')."' AND DATE(tgl_masuk) <='".$this->input->get('tgl_akhir')."' AND status_lap='1'")->num_rows();
//                    break;
            }

            $config['base_url']               = base_url('laporan/data_penjualan.php'.$this->general->get_all_get());
            $config['total_rows']             = $jml_hal;

            $config['query_string_segment']  = 'halaman';
            $config['page_query_string']     = TRUE;
            $config['per_page']              = $pengaturan->jml_item;
            $config['num_links']             = 2;

            $config['first_tag_open']        = '<li>';
            $config['first_tag_close']       = '</li>';

            $config['prev_tag_open']         = '<li>';
            $config['prev_tag_close']        = '</li>';

            $config['num_tag_open']          = '<li>';
            $config['num_tag_close']         = '</li>';

            $config['next_tag_open']         = '<li>';
            $config['next_tag_close']        = '</li>';

            $config['last_tag_open']         = '<li>';
            $config['last_tag_close']        = '</li>';

            $config['cur_tag_open']          = '<li><a href="#"><b>';
            $config['cur_tag_close']         = '</b></a></li>';

            $config['first_link']            = '&laquo;';
            $config['prev_link']             = '&lsaquo;';
            $config['next_link']             = '&rsaquo;';
            $config['last_link']             = '&raquo;';

            switch ($case){
                case 'semua':
                        $data['penjualan'] = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->like('id_user', $sales)
                                        ->like('tbl_trans_jual.metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('id_sales', $sales)
                                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->order_by('no_nota', 'asc')
                                        ->get('tbl_trans_jual')->result();
                    break;

                case 'sales':
                    $data['penjualan'] = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                        ->where('id_user',$_GET['query'])
                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                        ->order_by('no_nota','asc')
                        ->get('tbl_trans_jual')->result();
                    break;

                case 'per_tanggal':
                    if($_GET['metode'] == 'x'){
                        $sql_penj = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
//                                        ->where('tbl_trans_jual.metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'asc')
                                        ->get('tbl_trans_jual');

                        $data['penjualan'] = $sql_penj->result();
                        $sql_rws = $sql_penj->num_rows();
                    }else{
                        $data['penjualan'] = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
                                        ->where('tbl_trans_jual.metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'asc')
                                        ->get('tbl_trans_jual')->result();
                    }
                    break;

                case 'per_rentang':
                    if($_GET['metode'] == 'x') {
                        if (!empty($hal)) {
                            $data['penjualan'] = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                            ->where('DATE(tbl_trans_jual.tgl_masuk) >=', $_GET['tgl_awal'])
                                            ->where('DATE(tbl_trans_jual.tgl_masuk) <=', $_GET['tgl_akhir'])
//                                              ->where('status_lap', '1')
//                                              ->where('tbl_trans_jual.metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                            ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                            ->like('id_sales', $sales)
                                            ->order_by('no_nota', 'asc')
                                            ->limit($config['per_page'], $hal)
                                            ->get('tbl_trans_jual')->result();
                        } else {
                            $data['penjualan'] = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                            ->where('DATE(tbl_trans_jual.tgl_masuk) >=', $_GET['tgl_awal'])
                                            ->where('DATE(tbl_trans_jual.tgl_masuk) <=', $_GET['tgl_akhir'])
//                                              ->where('status_lap', '1')
//                                              ->where('tbl_trans_jual.metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                            ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                            ->like('id_sales', $sales)
                                            ->order_by('no_nota', 'asc')
                                            ->limit($config['per_page'])
                                            ->get('tbl_trans_jual')->result();
                        }
                    } else {
                        $data['penjualan'] = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tbl_trans_jual.tgl_masuk) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tbl_trans_jual.tgl_masuk) <=', $_GET['tgl_akhir'])
                                        ->where('tbl_trans_jual.metode_bayar' . ($_GET['metode'] > 1 ? ' >' : ''), ($_GET['metode'] > 1 ? '1' : $_GET['metode']))
//                                            ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->like('id_sales', $sales)
                                        ->limit($config['per_page'])
                                        ->order_by('no_nota', 'asc')
                                        ->get('tbl_trans_jual')->result();
                    }
                    break;
            }

            $this->pagination->initialize($config);

            $data['total_rows'] = $config['total_rows'];
            $data['PerPage']    = $config['per_page'];
            $data['pagination'] = $this->pagination->create_links();

            $data['sales']      = $this->db->get('tbl_m_sales')->result();
            $data['platform']   = $this->db->get('tbl_m_platform')->result();

            $output = "?";
            $firstRun = true;

//            echo '<pre>';
//            foreach ($_GET as $key => $val) {
//                if (!$firstRun) {
//                    $output .= "&";
//                } else {
//                    $firstRun = false;
//                }
//
//                if($key != 'halaman'){
//                    $output .= $key . "=" . $val;
//                }
//            }
//                print_r($output);
//                print_r($_GET);
//            echo '</pre>';

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_data_'.(!empty($sb) ? 'piutang' : 'penjualan'), $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_mutasi(){
        if (akses::aksesLogin() == TRUE) {
            $case        = $this->input->get('case');
            $sales       = $this->input->get('id_sales');
            $sb          = $this->input->get('sb');
            $tipe        = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');
            $grup        = $this->ion_auth->get_users_groups()->row();
            $id_user     = $this->ion_auth->user()->row()->id;
            $id_grup     = $this->ion_auth->get_users_groups()->row();
            $hal         = $this->input->get('halaman');
            $jml         = $this->input->get('jml');
            $jml_hal     = (!empty($_GET['jml_hal']) ? $this->input->get('jml_hal') : 0);

            $config['base_url']               = base_url('laporan/data_mutasi.php'.$this->general->get_all_get());
            $config['total_rows']             = $jml_hal;

            $config['query_string_segment']  = 'halaman';
            $config['page_query_string']     = TRUE;
            $config['per_page']              = 500;
            $config['num_links']             = 2;

            $config['first_tag_open']        = '<li>';
            $config['first_tag_close']       = '</li>';

            $config['prev_tag_open']         = '<li>';
            $config['prev_tag_close']        = '</li>';

            $config['num_tag_open']          = '<li>';
            $config['num_tag_close']         = '</li>';

            $config['next_tag_open']         = '<li>';
            $config['next_tag_close']        = '</li>';

            $config['last_tag_open']         = '<li>';
            $config['last_tag_close']        = '</li>';

            $config['cur_tag_open']          = '<li><a href="#"><b>';
            $config['cur_tag_close']         = '</b></a></li>';

            $config['first_link']            = '&laquo;';
            $config['prev_link']             = '&lsaquo;';
            $config['next_link']             = '&rsaquo;';
            $config['last_link']             = '&raquo;';

            switch ($case){
                case 'per_tanggal':
                    $data['penjualan'] = $this->db
                                              ->select('tbl_trans_mutasi.id, tbl_trans_mutasi.tipe, tbl_trans_mutasi.id_gd_asal, tbl_trans_mutasi.id_gd_tujuan, tbl_trans_mutasi.id_user, tbl_trans_mutasi.no_nota, tbl_trans_mutasi.tgl_masuk, tbl_trans_mutasi.keterangan, tbl_trans_mutasi_det.kode, tbl_trans_mutasi_det.produk, tbl_trans_mutasi_det.jml, tbl_trans_mutasi_det.jml_satuan, tbl_trans_mutasi_det.satuan')
                                              ->join('tbl_trans_mutasi', 'tbl_trans_mutasi.id=tbl_trans_mutasi_det.id_mutasi')
                                              ->where('tbl_trans_mutasi.id_user', $this->input->get('id_user'))
                                              ->where('DATE(tbl_trans_mutasi.tgl_masuk)', $this->input->get('query'))
                                              ->limit($config['per_page'],$hal)
                                              ->get('tbl_trans_mutasi_det')->result();
                    break;

                case 'per_rentang':
                    $data['penjualan'] = $this->db
                                              ->select('tbl_trans_mutasi.id, tbl_trans_mutasi.tipe, tbl_trans_mutasi.id_gd_asal, tbl_trans_mutasi.id_gd_tujuan, tbl_trans_mutasi.id_user, tbl_trans_mutasi.no_nota, tbl_trans_mutasi.tgl_masuk, tbl_trans_mutasi.keterangan, tbl_trans_mutasi_det.kode, tbl_trans_mutasi_det.produk, tbl_trans_mutasi_det.jml, tbl_trans_mutasi_det.jml_satuan, tbl_trans_mutasi_det.satuan')
                                              ->join('tbl_trans_mutasi', 'tbl_trans_mutasi.id=tbl_trans_mutasi_det.id_mutasi')
                                              ->where('tbl_trans_mutasi.id_user', $this->input->get('id_user'))
                                              ->where('DATE(tbl_trans_mutasi.tgl_masuk) >=', $this->input->get('tgl_awal'))
                                              ->where('DATE(tbl_trans_mutasi.tgl_masuk) <=', $this->input->get('tgl_akhir'))
                                              ->limit($config['per_page'],$hal)
                                              ->get('tbl_trans_mutasi_det')->result();
                    break;
            }

            $this->pagination->initialize($config);

            $data['total_rows'] = $config['total_rows'];
            $data['PerPage']    = $config['per_page'];
            $data['pagination'] = $this->pagination->create_links();

            $data['sales']    = $this->ion_auth->users()->result();
            $data['platform'] = $this->db->get('tbl_m_platform')->result();

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_data_mutasi', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_piutang(){
        if (akses::aksesLogin() == TRUE) {
            $case  = $this->input->get('case');
            $sales = $this->input->get('id_sales');
            $sb    = $this->input->get('sb');
            $tipe  = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');

            switch ($case){
                case 'semua':
                        $data['penjualan'] = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->like('id_user', $sales)
                                        ->like('tbl_trans_jual.metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();
                    break;

                case 'sales':
                    $data['penjualan'] = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                        ->where('status_bayar !=', '1')
                        ->where('id_user',$_GET['query'])
                        ->order_by('no_nota','desc')
                        ->get('tbl_trans_jual')->result();
                    break;

                case 'per_tanggal':
                    if($_GET['metode'] == 'x'){
                        $data['penjualan'] = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('status_bayar !=', '1')
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
//                                        ->where('tbl_trans_jual.metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();
                    }else{
                        $data['penjualan'] = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('tbl_trans_jual.status_bayar !=', '1')
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
                                        ->where('tbl_trans_jual.metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();
                    }
                    break;

                case 'per_rentang':
                    if($_GET['metode'] == 'x'){
                        $data['penjualan'] = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tbl_trans_jual.tgl_masuk) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tbl_trans_jual.tgl_masuk) <=', $_GET['tgl_akhir'])
                                        ->where('tbl_trans_jual.status_bayar !=', '1')
//                                        ->where('tbl_trans_jual.metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();

                    }else{
                        $data['penjualan'] = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tbl_trans_jual.tgl_masuk) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tbl_trans_jual.tgl_masuk) <=', $_GET['tgl_akhir'])
                                        ->where('tbl_trans_jual.status_bayar !=', '1')
//                                        ->where('tbl_trans_jual.metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();

                    }
                    break;
//
//                case 'per_rentang':
//                    $data['penjualan'] = $this->db->query("SELECT DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan FROM tbl_trans_jual WHERE ".(!empty($sales) ? "id_sales='".$sales."' AND" : "")." DATE(tgl_masuk) >='".$this->input->get('tgl_awal')."' AND DATE(tgl_masuk) <='".$this->input->get('tgl_akhir')."' AND status_lap='1'")->result();
//                    break;
            }

            $data['sales']    = $this->db->get('tbl_m_sales')->result();
            $data['platform'] = $this->db->get('tbl_m_platform')->result();

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_data_piutang', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_penjualan_prod(){
        if (akses::aksesLogin() == TRUE) {
            $case     = $this->input->get('case');
            $tipe     = $this->input->get('tipe');
            $sales    = $this->input->get('sales');
            $tgl_awal = $this->input->get('tgl_awal');
            $tgl_akhr = $this->input->get('tgl_akhir');
            $merk     = $this->input->get('merk');
            $kueri    = $this->input->get('query');
            $idproduk = $this->input->get('id_produk');
            $tipe     = $this->input->get('tipe');

            $grup     = $this->ion_auth->get_users_groups()->row();
            $id_user  = $this->ion_auth->user()->row()->id;
            $id_grup  = $this->ion_auth->get_users_groups()->row();

            $data['sql_merk']   = $this->db->order_by('merk', 'asc')->get('tbl_m_merk')->result();
            $data['sql_kasir']  = $this->db->order_by('id', 'asc')->get('tbl_m_sales')->result();

            switch ($tipe){
                case '1':
                    switch ($case) {
                        case 'per_tanggal':
//                          $data['penjualan'] = $this->db->select('DATE(tbl_trans_jual.tgl_masuk) as tgl_simpan, tbl_trans_jual.id, tbl_trans_jual.no_nota, tbl_trans_jual.kode_nota_blk, tbl_trans_jual.id_pelanggan, tbl_trans_jual.id_user, tbl_trans_jual.kode_nota_blk, tbl_trans_jual_det.kode, tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan, SUM(tbl_trans_jual_det.jml) as jml, SUM(tbl_trans_jual_det.jml_satuan) as jml_satuan, tbl_trans_jual_det.jml * tbl_trans_jual_det.jml_satuan as keterangan, SUM(tbl_trans_jual_det.subtotal) as subtotal, tbl_m_merk.merk')
                            $data['penjualan'] = $this->db->select('DATE(tbl_trans_jual.tgl_masuk) as tgl_simpan, tbl_trans_jual.id, tbl_trans_jual.no_nota, tbl_trans_jual.kode_nota_blk, tbl_trans_jual.id_pelanggan, tbl_trans_jual.id_user, tbl_trans_jual.kode_nota_blk, tbl_trans_jual_det.kode, tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan, SUM(tbl_trans_jual_det.jml) as jml, (tbl_trans_jual_det.jml_satuan) as jml_satuan, tbl_trans_jual_det.jml * tbl_trans_jual_det.jml_satuan as keterangan, sum(tbl_trans_jual_det.subtotal) as subtotal, tbl_m_merk.merk')
                                            ->join('tbl_trans_jual', 'tbl_trans_jual.id=tbl_trans_jual_det.id_penjualan')
                                            ->join('tbl_m_produk', 'tbl_m_produk.kode=tbl_trans_jual_det.kode')
                                            ->join('tbl_m_merk', 'tbl_m_merk.id=tbl_m_produk.id_merk', 'left')
                                            ->where('DATE(tbl_trans_jual.tgl_masuk)', $kueri)
                                            ->like('tbl_m_merk.merk', $merk)
//                                            ->like('tbl_trans_jual.status_pjk', ($id_grup->name == 'owner2' || $id_grup->name == 'admin' ? '1' : ''))
                                            ->order_by('tbl_trans_jual.no_nota', 'desc')
                                            ->group_by('tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan, tbl_trans_jual_det.jml_satuan')
                                            ->get("tbl_trans_jual_det")->result();
                            break;

                        case 'per_rentang':
                            $data['penjualan'] = $this->db->select('DATE(tbl_trans_jual.tgl_masuk) as tgl_simpan, tbl_trans_jual.id, tbl_trans_jual.no_nota, tbl_trans_jual.kode_nota_blk, tbl_trans_jual.id_pelanggan, tbl_trans_jual.id_user, tbl_trans_jual.kode_nota_blk, tbl_trans_jual_det.kode, tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan, SUM(tbl_trans_jual_det.jml) as jml, (tbl_trans_jual_det.jml_satuan) as jml_satuan, sum(tbl_trans_jual_det.subtotal) as subtotal, tbl_m_merk.merk, tbl_m_produk.id as id_produk')
                                            ->join('tbl_trans_jual', 'tbl_trans_jual.id=tbl_trans_jual_det.id_penjualan')
                                            ->join('tbl_m_produk', 'tbl_m_produk.kode=tbl_trans_jual_det.kode')
                                            ->join('tbl_m_merk', 'tbl_m_merk.id=tbl_m_produk.id_merk')
                                            ->where('tbl_trans_jual_det.jml !=', '0')
                                            ->where('tbl_trans_jual.tgl_masuk >=', $tgl_awal)
                                            ->where('DATE(tbl_trans_jual.tgl_masuk) <=', $tgl_akhr)
                                            ->like('tbl_trans_jual.status_pjk', ($id_grup->name == 'owner2' ? '1' : ''), '')
                                            ->like('tbl_m_produk.id_merk', $merk, (!empty($merk) ? 'none' : 'both'))
                                            ->order_by('tbl_trans_jual_det.jml', 'desc')
                                            ->group_by('tbl_trans_jual_det.produk')
                                            ->get("tbl_trans_jual_det")->result();
                            break;
                    }
                    break;

                case '2':
                    switch ($case){
                        case 'per_tanggal':
                            $data['penjualan'] = $this->db->select('tbl_trans_jual_det.kode, tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan, SUM(tbl_trans_jual_det.jml) as jml, (tbl_trans_jual_det.jml_satuan) as jml_satuan, sum(tbl_trans_jual_det.subtotal) as subtotal, tbl_m_merk.merk, tbl_m_produk.id as id_produk')
                                                          ->join('tbl_trans_jual_det', 'tbl_m_produk_hist.kode=tbl_trans_jual_det.kode')
                                                          ->join('tbl_m_produk', 'tbl_m_produk.id=tbl_m_produk_hist.id_produk')
                                                          ->join('tbl_m_merk', 'tbl_m_merk.id=tbl_m_produk.id_merk')
                                                          ->where('tbl_m_produk_hist.id_produk', general::dekrip($idproduk))
                                                          ->where('DATE(tbl_m_produk_hist.tgl_simpan)', $kueri)
                                                          ->get('tbl_m_produk_hist')->result();
                            break;
                    }
                    break;
            }

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_data_penjualan_prod', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_penjualan_kasir(){
        if (akses::aksesLogin() == TRUE) {
            $case       = $this->input->get('case');
            $sales      = $this->input->get('sales');
            $tipe       = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');
            $tgl_awal   = (isset($_GET['tgl_awal']) ? $this->input->get('tgl_awal') : date('Y-m-d'));
            $tgl_akhr   = (isset($_GET['tgl_akhir']) ? $this->input->get('tgl_akhir') : date('Y-m-d'));
            $met_bayar  = $this->input->get('metode');
            $id_user    = (isset($_GET['id_user']) ? $this->input->get('id_user') : $this->ion_auth->user()->row()->id);
            $grup       = $this->ion_auth->get_users_groups($id_user)->row();

            if(!empty($met_bayar)){
                 $data['penjualan'] = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                               ->where('id_user', $id_user)
                                               ->where('DATE(tgl_masuk) >=', $tgl_awal)
                                               ->where('DATE(tgl_masuk) <=', $tgl_akhr)
                                               ->where('metode_bayar', (!empty($met_bayar) ? $met_bayar : ''))
                                               ->order_by('no_nota', 'desc')
                                               ->get('tbl_trans_jual')->result();
            }else{
                 $data['penjualan'] = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                               ->where('id_user', $id_user)
                                               ->where('DATE(tgl_masuk) >=', $tgl_awal)
                                               ->where('DATE(tgl_masuk) <=', $tgl_akhr)
                                               ->order_by('no_nota', 'desc')
                                               ->get('tbl_trans_jual')->result();
            }

//            $data['penjualan'] = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
//                            ->where('id_user', $id_user)
//                            ->where('DATE(tgl_masuk) >=', $tgl_awal)
//                            ->where('DATE(tgl_masuk) <=', $tgl_akhr)
//                            ->get("tbl_trans_jual")->result();

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_data_penjualan_kasir', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_penjualan_kasir_produk(){
        if (akses::aksesLogin() == TRUE) {
            $case       = $this->input->get('case');
            $sales      = $this->input->get('sales');
            $tipe       = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');
            $tgl_awal   = (isset($_GET['tgl_awal']) ? $this->input->get('tgl_awal') : date('Y-m-d'));
            $tgl_akhr   = (isset($_GET['tgl_akhir']) ? $this->input->get('tgl_akhir') : date('Y-m-d'));
            $id_user    = $this->ion_auth->user()->row()->id;

            $where = "(tbl_trans_jual.id_user LIKE '".$id_user."')";
            $data['penjualan'] = $this->db->select('DATE(tbl_trans_jual.tgl_masuk) as tgl_simpan, tbl_trans_jual.id, tbl_trans_jual.no_nota, tbl_trans_jual.kode_nota_blk, tbl_trans_jual.id_pelanggan, tbl_trans_jual.id_user, tbl_trans_jual.kode_nota_blk, tbl_trans_jual_det.kode, tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan, SUM(tbl_trans_jual_det.jml) as jml, SUM(tbl_trans_jual_det.jml_satuan) as jml_satuan, tbl_trans_jual_det.jml * tbl_trans_jual_det.jml_satuan as keterangan, SUM(tbl_trans_jual_det.subtotal) as subtotal')
                                          ->join('tbl_trans_jual', 'tbl_trans_jual.no_nota=tbl_trans_jual_det.no_nota')
//                                          ->join('tbl_m_satuan', 'tbl_m_satuan.id=tbl_trans_jual_det.id_satuan')
                                          ->where($where)
//                                          ->where('tbl_trans_jual.status_grosir', '0')
                                          ->where('DATE(tbl_trans_jual.tgl_simpan) >=', $tgl_awal)
                                          ->where('DATE(tbl_trans_jual.tgl_simpan) <=', $tgl_akhr)
                                          ->order_by('tbl_trans_jual.no_nota','desc')
                                          ->group_by('tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan')
                                          ->get("tbl_trans_jual_det")->result();

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_data_penjualan_produk', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_pembelian(){
        if (akses::aksesLogin() == TRUE) {
            $case  = $this->input->get('case');
            $sales = $this->input->get('sales');
            $sb    = $this->input->get('sb');
            $ht    = $this->input->get('hutang');
            $sp    = $this->input->get('status_ppn');
            $tipe  = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');

            switch ($case){
                case 'per_tanggal':
                    if(!empty($sb)){
                        $data['pembelian'] = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, jml_total, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_supplier')
                                        ->where('status_bayar', $sb)
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
                                        ->like('status_ppn', $sp)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_beli')->result();
                    }else{
                        $data['pembelian'] = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, jml_total, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_supplier')
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
                                        ->like('status_ppn', $sp)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_beli')->result();
                    }
                    break;

                case 'per_rentang':
                    if(!empty($sb)){
                        $data['pembelian'] = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, jml_total, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_supplier')
                                                ->where('status_bayar', $sb)
//                                                ->where('jml_gtotal >', '100')
                                                ->where('DATE(tgl_masuk) >=', $_GET['tgl_awal'])
                                                ->where('DATE(tgl_masuk) <=', $_GET['tgl_akhir'])
                                                ->where('status_ppn'.($sp > 0 ? ' >' : ''), ($sp > 0 ? '0' : $sp))
//                                                ->like('status_ppn', $sp)
                                                ->order_by('no_nota', 'desc')
                                                ->get('tbl_trans_beli')->result();

                    }else{
                        $data['pembelian'] = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, id, no_nota, jml_total, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_supplier')
                                                ->where('DATE(tgl_masuk) >=', $_GET['tgl_awal'])
                                                ->where('DATE(tgl_masuk) <=', $_GET['tgl_akhir'])
                                                ->where('status_ppn'.($sp > 0 ? ' >' : ''), ($sp > 0 ? '0' : $sp))
//                                                ->like('status_ppn', $sp)
                                                ->order_by('no_nota', 'desc')
                                                ->get('tbl_trans_beli')->result();

                    }

//                    $data['pembelian'] = $this->db->query("SELECT no_nota, DATE(tgl_simpan) as tgl_simpan, DATE(tgl_bayar) as tgl_bayar, platform, jml_gtotal, jml_bayar, jml_ongkir, status_nota, metode_bayar, status_bayar FROM tbl_trans_jual WHERE ".(!empty($sales) ? "AND id_user='".$sales."' " : "")." DATE(tgl_simpan) >='".$this->input->get('tgl_awal')."' AND DATE(tgl_simpan) <='".$this->input->get('tgl_akhir')."' AND metode_bayar LIKE '%".$tipe."%'")->result();
                    break;
            }
//                    echo '<pre>';
//                    print_r($data);

            $data['sales']    = $this->db->get('tbl_m_sales')->result();
            $data['kategori'] = $this->db->get('tbl_m_kategori')->result();

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_data_'.($ht == '1' ? 'hutang' : 'pembelian'), $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_retur_penjualan(){
        if (akses::aksesLogin() == TRUE) {
            $case  = $this->input->get('case');
            $sales = $this->input->get('sales');
            $tipe  = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');

            switch ($case){
                case 'per_tanggal':
                        $data['retur'] = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id_pelanggan, id_user, no_retur, no_nota, jml_retur, status_retur')
                                        ->where('DATE(tgl_simpan)', $_GET['query'])
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_retur_jual')->result();
                    break;

                case 'per_rentang':
                        $data['retur'] = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id_pelanggan, id_user, no_retur, no_nota, jml_retur, status_retur')
                                        ->where('DATE(tgl_simpan) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tgl_simpan) <=', $_GET['tgl_akhir'])
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_retur_jual')->result();
                    break;
            }

            $data['sales']    = $this->db->get('tbl_m_sales')->result();
            $data['kategori'] = $this->db->get('tbl_m_kategori')->result();

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_data_retur_penjualan', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_retur_pembelian(){
        if (akses::aksesLogin() == TRUE) {
            $case  = $this->input->get('case');
            $sales = $this->input->get('sales');
            $tipe  = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');

            switch ($case){
                case 'per_tanggal':
                        $data['penbelian'] = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id_pelanggan, id_user, no_nota, jml_retur, status_retur')
                                        ->where('DATE(tgl_simpan)', $_GET['query'])
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_retur_beli')->result();
                    break;

                case 'per_rentang':
                        $data['penbelian'] = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id_pelanggan, id_user, no_nota, jml_retur, status_retur')
                                        ->where('DATE(tgl_simpan) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tgl_simpan) <=', $_GET['tgl_akhir'])
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_retur_beli')->result();
                    break;
            }

            $data['sales']    = $this->db->get('tbl_m_sales')->result();
            $data['kategori'] = $this->db->get('tbl_m_kategori')->result();

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_data_retur_pembelian', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_pemasukan(){
        if (akses::aksesLogin() == TRUE) {
            $case   = $this->input->get('case');
            $status = $this->input->get('status');

            switch ($case){
                case 'semua':
                    if($status == 'semua'){
                       $data['pemasukan'] = $this->db
                           ->select('kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                           ->where('tipe','masuk')
                           ->order_by('id','desc')->get('tbl_akt_kas')->result();

                    }else{
                       $data['pemasukan'] = $this->db
                           ->select('kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                           ->where('tipe','masuk')
                           ->where('status_kas',$status)
                           ->order_by('id','desc')->get('tbl_akt_kas')->result();

                    }
                    break;

                case 'per_tanggal':
                       $data['pemasukan'] = $this->db
                           ->select('kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                           ->where('tipe','masuk')
                           ->where('DATE(tgl_simpan)',$this->input->get('query'))
                           ->order_by('id','desc')->get('tbl_akt_kas')->result();
                    break;

                case 'per_rentang':
                       $data['pemasukan'] = $this->db
                           ->select('kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                           ->where('tipe','masuk')
                           ->where('DATE(tgl_simpan) >=',$this->input->get('tgl_awal'))
                           ->where('DATE(tgl_simpan) <=',$this->input->get('tgl_akhir'))
                           ->order_by('id','desc')->get('tbl_akt_kas')->result();
                    break;
            }

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_data_pemasukan', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_pengeluaran(){
        if (akses::aksesLogin() == TRUE) {
            $case   = $this->input->get('case');
            $status = $this->input->get('status');

            if(akses::hakOwner2() == TRUE OR akses::hakAdminM() == TRUE OR akses::hakAdmin() == TRUE){
                switch ($case){
                    case 'per_tanggal':
                           $data['pengeluaran'] = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->not_like('keterangan','Pembelian')
                               ->where('tipe','keluar')
                               ->where('DATE(tgl_simpan)',$this->input->get('query'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;

                    case 'per_rentang':
                           $data['pengeluaran'] = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->not_like('keterangan','Pembelian')
                               ->where('tipe','keluar')
                               ->where('DATE(tgl_simpan) >=',$this->input->get('tgl_awal'))
                               ->where('DATE(tgl_simpan) <=',$this->input->get('tgl_akhir'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;

                    default:
                           $data['pengeluaran'] = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->not_like('keterangan','Pembelian')
                               ->where('tipe','keluar')
                               ->like('keterangan',$this->input->get('query'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;
                }
            }else{
                switch ($case){
                    case 'per_tanggal':
                           $data['pengeluaran'] = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->where('tipe','keluar')
                               ->where('DATE(tgl_simpan)',$this->input->get('query'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;

                    case 'per_rentang':
                           $data['pengeluaran'] = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->where('tipe','keluar')
                               ->where('DATE(tgl_simpan) >=',$this->input->get('tgl_awal'))
                               ->where('DATE(tgl_simpan) <=',$this->input->get('tgl_akhir'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;

                    default:
                           $data['pengeluaran'] = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->where('tipe','keluar')
                               ->like('keterangan',$this->input->get('query'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;
                }
            }

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_data_pengeluaran', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_lr(){
        if (akses::aksesLogin() == TRUE) {
            $case   = $this->input->get('case');
            $status = $this->input->get('status');
            $query  = $this->input->get('query');
            $tgl_awal    = $this->input->get('tgl_awal');
            $tgl_akhir   = $this->input->get('tgl_akhir');

            switch ($case){
                case 'per_tanggal':
                       $data['persediaan']     = $this->db->select('SUM(nominal) as nominal')->where('DATE(tgl_simpan)', $query)->get('tbl_akt_persediaan')->row();
                       $data['pembelian']      = $this->db->select('SUM(jml_gtotal) as jml_gtotal, SUM(jml_diskon) as jml_diskon, SUM(jml_potongan) as jml_potongan, SUM(jml_retur) as jml_retur')->where('DATE(tgl_simpan)', $query)->where('status_bayar', '1')->get('tbl_trans_beli')->row();
                       $data['penjualan']      = $this->db->select('SUM(jml_gtotal) as jml_subtotal')->where('DATE(tgl_masuk)', $query)->get('tbl_trans_jual')->row();
                       $data['retur_penj']     = $this->db->select('SUM(nominal) as jml_total')->where('status', '1')->where('DATE(tgl_simpan)', $query)->get('tbl_akt_retur')->row();
                       $data['biaya']          = $this->db->select('SUM(nominal) as nominal')->where('DATE(tgl_simpan)', $query)->where('id_jenis !=', '0')->get('tbl_akt_kas')->row();

                       $data['jml_penjualan']  = $data['penjualan']->jml_subtotal;
                       $data['jml_retur_penj'] = $data['retur_penj']->jml_total;
                       $data['tot_penjualan']  = $data['penjualan']->jml_subtotal - $data['retur_penj']->jml_total;

                       $data['jml_persediaan'] = $data['persediaan']->nominal;
                       $data['jml_pembelian']  = $data['pembelian']->jml_gtotal;
                       $data['tot_pembelian']  = $data['persediaan']->nominal + $data['pembelian']->jml_gtotal;

                       $data['jml_biaya']      = $data['biaya']->nominal;

                       $data['lr']             = $data['tot_penjualan'] - ($data['jml_pembelian'] + $data['jml_persediaan'] + $data['jml_biaya']);

                       $data['keterangan']     = 'PER TANGGAL ['.$this->tanggalan->tgl_indo($query).']';
                    break;

                case 'per_rentang':
                       $data['persediaan']     = $this->db->select('SUM(nominal) as nominal')->where('DATE(tgl_simpan) >=', $tgl_awal)->where('DATE(tgl_simpan) <=', $tgl_akhir)->get('tbl_akt_persediaan')->row();
                       $data['pembelian']      = $this->db->select('SUM(jml_gtotal) as jml_gtotal, SUM(jml_diskon) as jml_diskon, SUM(jml_potongan) as jml_potongan, SUM(jml_retur) as jml_retur')->where('DATE(tgl_simpan) >=', $tgl_awal)->where('DATE(tgl_simpan) <=', $tgl_akhir)->where('status_bayar', '1')->get('tbl_trans_beli')->row();
                       $data['penjualan']      = $this->db->select('SUM(jml_gtotal) as jml_subtotal')->where('DATE(tgl_masuk) >=', $tgl_awal)->where('DATE(tgl_masuk) <=', $tgl_akhir)->get('tbl_trans_jual')->row();
                       $data['retur_penj']     = $this->db->select('SUM(nominal) as jml_total')->where('status', '1')->where('DATE(tgl_simpan) >=', $tgl_awal)->where('DATE(tgl_simpan) <=', $tgl_akhir)->get('tbl_akt_retur')->row();
                       $data['biaya']          = $this->db->select('SUM(nominal) as nominal')->where('DATE(tgl_simpan) >=', $tgl_awal)->where('DATE(tgl_simpan) <=', $tgl_akhir)->where('id_jenis !=', '0')->get('tbl_akt_kas')->row();

                       $data['jml_penjualan']  = $data['penjualan']->jml_subtotal;
                       $data['jml_retur_penj'] = $data['retur_penj']->jml_total;
                       $data['tot_penjualan']  = $data['penjualan']->jml_subtotal - $data['retur_penj']->jml_total;

                       $data['jml_persediaan'] = $data['persediaan']->nominal;
                       $data['jml_pembelian']  = $data['pembelian']->jml_gtotal;
                       $data['tot_pembelian']  = $data['persediaan']->nominal + $data['pembelian']->jml_gtotal;

                       $data['jml_biaya']      = $data['biaya']->nominal;

                       $data['lr']             = $data['tot_penjualan'] - ($data['jml_pembelian'] + $data['jml_persediaan'] + $data['jml_biaya']); //($data['tot_penjualan'] - $data['tot_pembelian']) - $data['jml_biaya'];

                       $data['keterangan']     = 'PER RENTANG DARI ['.$this->tanggalan->tgl_indo($tgl_awal).'] s/d ['.$this->tanggalan->tgl_indo($tgl_akhir).']';
                    break;
            }

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_data_lr', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_persediaan_pdf(){
        if (akses::aksesLogin() == TRUE) {
            $case = $this->input->get('case');

            $data['produk'] = '';

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_v_pdf', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_penjualan_pdf(){
        if (akses::aksesLogin() == TRUE) {
            $case = $this->input->get('case');

            $data['produk'] = '';

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_v_pdf', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_piutang_pdf(){
        if (akses::aksesLogin() == TRUE) {
            $case = $this->input->get('case');

            $data['produk'] = '';

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_v_pdf', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_penjualan_prod_pdf(){
        if (akses::aksesLogin() == TRUE) {
            $case = $this->input->get('case');

            $data['produk'] = '';

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_v_prod_pdf', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_penjualan_kasir_pdf(){
        if (akses::aksesLogin() == TRUE) {
            $case = $this->input->get('case');

            $data['produk'] = '';

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_kasir_pdf', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_penjualan_kasir_produk_pdf(){
        if (akses::aksesLogin() == TRUE) {
            $case = $this->input->get('case');

            $data['produk'] = '';

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_produk_pdf', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_pembelian_pdf(){
        if (akses::aksesLogin() == TRUE) {
            $case = $this->input->get('case');

            $data['produk'] = '';

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_v_pdf', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_retur_penjualan_pdf(){
        if (akses::aksesLogin() == TRUE) {
            $case = $this->input->get('case');

            $data['produk'] = '';

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_v_pdf', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_retur_pembelian_pdf(){
        if (akses::aksesLogin() == TRUE) {
            $case = $this->input->get('case');

            $data['produk'] = '';

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_v_pdf', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_pemasukan_pdf(){
        if (akses::aksesLogin() == TRUE) {
            $case = $this->input->get('case');

            $data['produk'] = '';

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_v_pdf', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function data_pengeluaran_pdf(){
        if (akses::aksesLogin() == TRUE) {
            $case = $this->input->get('case');

            $data['produk'] = '';

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/laporan/lap_v_pdf', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function pdf_data_persediaan(){
        if (akses::aksesLogin() == TRUE) {
            $case  = $this->input->get('case');
            $query = $this->input->get('query');
            $p     = $this->input->get('filter_stok');

            switch ($p){
                case '1';
                    $param = '';
                    break;
                case '2';
                    $param = '>';
                    break;
                case '3';
                    $param = '<';
                    break;
            }

            switch ($case){
                case 'semua':
                    $sql = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id, kode, kode, produk, jml, id_satuan, harga_beli, harga_jual')->order_by('id','desc')->get('tbl_m_produk')->result();
                    break;

                case 'per_tanggal':
                    $sql = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id, kode, kode, produk, jml, id_satuan, harga_beli, harga_jual')->where('DATE(tgl_simpan)',$this->input->get('query'))->order_by('id','desc')->get('tbl_m_produk')->result();
                    break;

                case 'per_rentang':
                    $sql = $this->db->query("SELECT DATE(tgl_simpan) as tgl_simpan, id, kode, kode, produk, jml, id_satuan, harga_beli, harga_jual FROM tbl_m_produk WHERE DATE(tgl_simpan) BETWEEN '".$this->input->get('tgl_awal')."' AND '".$this->input->get('tgl_akhir')."'")->result();
                    break;

                case 'per_stok':
                    $sql = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id, kode, kode, produk, jml, id_satuan, harga_beli, harga_jual')
                                               ->where('jml '.$param, $query)
                                               ->order_by('id','desc')->get('tbl_m_produk')->result();
                    break;
            }

            $setting = $this->db->get('tbl_pengaturan')->row();

            $judul = "LAPORAN DATA BARANG";

            $this->fpdf->FPDF('P', 'cm', 'a4');
            $this->fpdf->SetAutoPageBreak('auto');
            $this->fpdf->SetMargins(1, 1, 1);
            $this->fpdf->AliasNbPages();
            $this->fpdf->AddPage();

            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, strtoupper($setting->judul), '0', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '11');
            $this->fpdf->Cell(19, .5, ucwords($setting->alamat), 'B', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, $judul, 0, 1, 'C');
            $this->fpdf->Ln();


            // Fill Colornya
            $this->fpdf->SetFillColor(211, 223, 227);
            $this->fpdf->SetTextColor(0);
//        $this->fpdf->SetDrawColor(128, 0, 0);
            $this->fpdf->SetFont('Arial', 'B', '10');


//        // Header tabel
            $this->fpdf->Cell(1, .5, 'No', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2.5, .5, 'Tgl Masuk', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'Kode', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(9, .5, 'Produk', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(1, .5, 'Stok', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2.5, .5, 'Harga Jual', 1, 0, 'C', TRUE);
            $this->fpdf->Ln();


            $this->fpdf->SetFillColor(235, 232, 228);
            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetFont('Arial', '', '10');

            if (!empty($sql)) {
                $fill = FALSE;
                $no = 1;
                $tot = 0;
                $jml_brg = 0;
                foreach ($sql as $produk) {
                    $tot     = $tot + $produk->harga_jual;
                    $tgl     = explode('-', $produk->tgl_simpan);
                    $jml     = $this->db->select('SUM(stok) as jml')->where('id_produk', $produk->id)->get('tbl_m_produk_stok')->row();
                    $jml_brg = $jml_brg + $jml->jml;

                    $this->fpdf->Cell(1, .5, $no . '. ', 1, 0, 'C', $fill);
                    $this->fpdf->Cell(2.5, .5, $tgl[1] . '/' . $tgl[2] . '/' . $tgl[0], 1, 0, 'C', $fill);
                    $this->fpdf->Cell(3, .5, $produk->kode, 1, 0, 'L', $fill);
                    $this->fpdf->Cell(9, .5, $produk->produk, 1, 0, 'L', $fill);
                    $this->fpdf->Cell(1, .5, (!empty($jml->jml) ? $jml->jml : '0'), 1, 0, 'C', $fill);
                    $this->fpdf->Cell(2.5, .5, general::format_angka($produk->harga_jual), 1, 0, 'R', $fill);
                    $this->fpdf->Ln();

                    $fill = !$fill;
                    $no++;
                }

                $this->fpdf->SetFont('Arial', 'B', '10');
                $this->fpdf->Cell(15.5, .5, 'Total', 1, 0, 'R', $fill);
                $this->fpdf->Cell(1, .5, $jml_brg, 1, 0, 'C', $fill);
                $this->fpdf->Cell(2.5, .5, general::format_angka($tot), 1, 0, 'R', $fill);
                $this->fpdf->Ln();
//                $this->fpdf->Cell(15.5, .5, 'Grand Total', 1, 0, 'R', $fill);
//                $this->fpdf->Cell(3.5, .5, general::format_angka($jml_brg * $tot), 1, 0, 'C', $fill);
//                $this->fpdf->Ln();
            } else {

                $this->fpdf->SetFont('Arial', 'B', '11');
                $this->fpdf->SetFillColor(235, 232, 228);
                $this->fpdf->Cell(19, 1, 'Data Kosong', 1, 0, 'C', TRUE);
                $this->fpdf->Ln(10);
            }

            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetY(-2);
            $this->fpdf->SetFont('Arial', 'i', '9');
            $this->fpdf->Cell(9, .75, 'Copyright (c) ' . date('Y') . ' - ' . $setting->judul, 'T', 0, 'L');
            $this->fpdf->Cell(1, .75, $this->fpdf->PageNo(), 'T', 0, 'L');
            $this->fpdf->Cell(9, .75, 'Dicetak pada : ' . $this->tanggalan->tgl_indo(date('Y-m-d')), 'T', 0, 'R');

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $this->fpdf->Output('lap_data_barang_' . date('YmdHm') . '.pdf', $type);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function pdf_data_piutang(){
        if (akses::aksesLogin() == TRUE) {
            $case  = $this->input->get('case');
            $query = $this->input->get('query');
            $p     = $this->input->get('filter_stok');

            $case  = $this->input->get('case');
            $sales = $this->input->get('id_sales');
            $tipe  = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');

            switch ($case){
                case 'per_tanggal':
                    if($_GET['metode'] == 'x'){
                        $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('status_bayar !=', '1')
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
//                                        ->like('metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();
                    }else{
                        $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('status_bayar !=', '1')
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
//                                        ->like('metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();
                    }
                    break;

                case 'per_rentang':
                    if($_GET['metode'] == 'x'){
                        $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('status_bayar !=', '1')
                                        ->where('DATE(tgl_masuk) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tgl_masuk) <=', $_GET['tgl_akhir'])
//                                        ->where('metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
//                                        ->where('status_lap', '1')
                                        ->like('id_sales', $sales)
//                                        ->where('status_lap', '1')
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();
                    }else{
                        $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('status_bayar !=', '1')
                                        ->where('DATE(tgl_masuk) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tgl_masuk) <=', $_GET['tgl_akhir'])
//                                        ->where('metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
//                                        ->where('status_lap', '1')
                                        ->like('id_sales', $sales)
//                                        ->where('status_lap', '1')
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();
                    }
                    break;
            }

            $setting = $this->db->get('tbl_pengaturan')->row();

            $judul = "LAPORAN DATA PENJUALAN";

            $this->fpdf->FPDF('P', 'cm', 'a4');
            $this->fpdf->SetAutoPageBreak('auto');
            $this->fpdf->SetMargins(1, 1, 1);
            $this->fpdf->AliasNbPages();
            $this->fpdf->AddPage();

            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, strtoupper($setting->judul), '0', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '11');
            $this->fpdf->Cell(19, .5, ucwords($setting->alamat), 'B', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, $judul, 0, 1, 'C');
            $this->fpdf->Ln();


            // Fill Colornya
            $this->fpdf->SetFillColor(211, 223, 227);
            $this->fpdf->SetTextColor(0);
//        $this->fpdf->SetDrawColor(128, 0, 0);
            $this->fpdf->SetFont('Arial', 'B', '10');


//        // Header tabel
            $this->fpdf->Cell(1, .5, 'No', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2, .5, 'Tgl', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'No. Invoice', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'Sales', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(7, .5, 'Metode', 1, 0, 'C', TRUE);
//            $this->fpdf->Cell(2, .5, 'Tempo', 1, 0, 'C', TRUE);
//            $this->fpdf->Cell(2, .5, 'Bayar', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'Nominal', 1, 0, 'C', TRUE);
            $this->fpdf->Ln();


            $this->fpdf->SetFillColor(235, 232, 228);
            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetFont('Arial', '', '10');

            if (!empty($sql)) {
                $fill = FALSE;
                $no = 1;
                $tot = 0;
                $jml_brg = 0;
                foreach ($sql as $penjualan) {
                    $tot     = $tot + $penjualan->jml_gtotal;
                    $tgl     = explode('-', $penjualan->tgl_simpan);
                    $tgl_tmp = explode('-', $penjualan->tgl_keluar);
                    $tgl_byr = explode('-', $penjualan->tgl_bayar);
                    $sales   = $this->db->where('kode', $penjualan->kode_nota_blk)->get('tbl_m_sales')->row();
                    $platform = $this->db->select('tbl_m_platform.id, tbl_m_platform.platform as metode_bayar, tbl_trans_jual_plat.platform, tbl_trans_jual_plat.keterangan')->where('no_nota', $penjualan->no_nota)->join('tbl_m_platform', 'tbl_m_platform.id=tbl_trans_jual_plat.id_platform', 'left')->get('tbl_trans_jual_plat')->row();
                    $nama    = explode(' ', $sales->nama);

                    $this->fpdf->Cell(1, .5, $no . '. ', 'LR', 0, 'C', $fill);
                    $this->fpdf->Cell(2, .5, $tgl[1] . '/' . $tgl[2] . '/' . $tgl[0], 'L', 0, 'C', $fill);
                    $this->fpdf->Cell(3, .5, $penjualan->no_nota, 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(3, .5, ucwords(strtolower($nama[0])), 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(7, .5, ($platform->platform == '-'? 'TUNAI' : ucwords($platform->platform).' '.$platform->keterangan), 'L', 0, 'R', $fill); // ($platform->id > '1' ? ucwords($platform->platform).' '.$platform->keterangan : 'TUNAI')
//                    $this->fpdf->Cell(2, .5, ($penjualan->tgl_keluar != '0000-00-00' ? $tgl_tmp[1] . '/' . $tgl_tmp[2] . '/' . $tgl_tmp[0] : '-'), 'L', 0, 'R', $fill);
//                    $this->fpdf->Cell(2, .5, ($penjualan->tgl_bayar != '0000-00-00' ? $tgl_byr[1] . '/' . $tgl_byr[2] . '/' . $tgl_byr[0] : '-'), 'L', 0, 'R', $fill);
                    $this->fpdf->Cell(3, .5, general::format_angka($penjualan->jml_gtotal), 'LR', 0, 'R', $fill);
                    $this->fpdf->Ln();

                    $fill = !$fill;
                    $no++;
                }

                $this->fpdf->SetFont('Arial', 'B', '10');
                $this->fpdf->Cell(16, .5, 'Total', 1, 0, 'R', $fill);
                $this->fpdf->Cell(3, .5, general::format_angka($tot), 1, 0, 'R', $fill);
//                $this->fpdf->Ln();
//                $this->fpdf->Cell(15.5, .5, 'Grand Total', 1, 0, 'R', $fill);
//                $this->fpdf->Cell(3.5, .5, general::format_angka($jml_brg * $tot), 1, 0, 'C', $fill);
//                $this->fpdf->Ln();
            } else {

                $this->fpdf->SetFont('Arial', 'B', '11');
                $this->fpdf->SetFillColor(235, 232, 228);
                $this->fpdf->Cell(19, 1, 'Data Kosong', 1, 0, 'C', TRUE);
                $this->fpdf->Ln(10);
            }

            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetY(-2);
            $this->fpdf->SetFont('Arial', 'i', '9');
            $this->fpdf->Cell(9, .75, 'Copyright (c) ' . date('Y') . ' - ' . $setting->judul, 'T', 0, 'L');
            $this->fpdf->Cell(1, .75, $this->fpdf->PageNo(), 'T', 0, 'L');
            $this->fpdf->Cell(9, .75, 'Dicetak pada : ' . $this->tanggalan->tgl_indo(date('Y-m-d')), 'T', 0, 'R');

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $this->fpdf->Output('lap_data_barang_' . date('YmdHm') . '.pdf', $type);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function pdf_data_penjualan(){
        if (akses::aksesLogin() == TRUE) {
            $case  = $this->input->get('case');
            $query = $this->input->get('query');
            $p     = $this->input->get('filter_stok');

            $case  = $this->input->get('case');
            $sales = $this->input->get('id_sales');
            $tipe  = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');

            $grup        = $this->ion_auth->get_users_groups()->row();
            $id_user     = $this->ion_auth->user()->row()->id;
            $id_grup     = $this->ion_auth->get_users_groups()->row();

            switch ($case){
                case 'per_tanggal':
                    if($_GET['metode'] == 'x'){
                        $sql = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan, status_grosir')
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
                                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'asc')
                                        ->get('tbl_trans_jual')->result();
                    }else{
                        $sql = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan, status_grosir')
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
                                        ->like('metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'asc')
                                        ->get('tbl_trans_jual')->result();
                    }
                    break;

                case 'per_rentang':
                    if($_GET['metode'] == 'x'){
                        $sql = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan, status_grosir')
                                        ->where('DATE(tgl_masuk) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tgl_masuk) <=', $_GET['tgl_akhir'])
                                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'asc')
                                        ->get('tbl_trans_jual')->result();
                    }else{
                        $sql = $this->db->select('tgl_simpan, tgl_masuk, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan, status_grosir')
                                        ->where('DATE(tgl_masuk) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tgl_masuk) <=', $_GET['tgl_akhir'])
                                        ->where('metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'asc')
                                        ->get('tbl_trans_jual')->result();
                    }
                    break;
            }

            $setting = $this->db->get('tbl_pengaturan')->row();

            $judul = "LAPORAN DATA PENJUALAN";

            $this->fpdf->FPDF('P', 'cm', 'a4');
            $this->fpdf->SetAutoPageBreak('auto');
            $this->fpdf->SetMargins(1, 1, 1);
            $this->fpdf->AliasNbPages();
            $this->fpdf->AddPage();

            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, strtoupper($setting->judul), '0', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '11');
            $this->fpdf->Cell(19, .5, ucwords($setting->alamat), 'B', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, $judul, 0, 1, 'C');
            $this->fpdf->Ln();


            // Fill Colornya
            $this->fpdf->SetFillColor(211, 223, 227);
            $this->fpdf->SetTextColor(0);
//        $this->fpdf->SetDrawColor(128, 0, 0);
            $this->fpdf->SetFont('Arial', 'B', '10');


//        // Header tabel
            $this->fpdf->Cell(1, .5, 'No', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'Tgl', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2.5, .5, 'No. Invoice', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2.5, .5, 'Sales', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(7, .5, 'Metode', 1, 0, 'C', TRUE);
//            $this->fpdf->Cell(2, .5, 'Tempo', 1, 0, 'C', TRUE);
//            $this->fpdf->Cell(2, .5, 'Bayar', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'Nominal', 1, 0, 'C', TRUE);
            $this->fpdf->Ln();


            $this->fpdf->SetFillColor(235, 232, 228);
            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetFont('Arial', '', '10');

            if (!empty($sql)) {
                $fill = FALSE;
                $no = 1;
                $tot = 0;
                $jml_brg = 0;
                foreach ($sql as $penjualan) {
                    $tot     = $tot + $penjualan->jml_gtotal;
                    $wkt     = $this->tanggalan->wkt_indo($penjualan->tgl_simpan);
                    $tgl     = $this->tanggalan->tgl_indo($penjualan->tgl_masuk);
                    $tgl_tmp = $this->tanggalan->tgl_indo($penjualan->tgl_keluar);
                    $tgl_byr = $this->tanggalan->tgl_indo($penjualan->tgl_bayar);
                    $sales   = $this->db->where('id', $penjualan->id_sales)->get('tbl_m_sales')->row();
                    $platform = $this->db->select('tbl_m_platform.id, tbl_m_platform.platform as metode_bayar, tbl_trans_jual_plat.platform, tbl_trans_jual_plat.keterangan, tbl_m_platform.keterangan as plat_ket')->where('no_nota', $penjualan->no_nota)->join('tbl_m_platform', 'tbl_m_platform.id=tbl_trans_jual_plat.id_platform', 'left')->get('tbl_trans_jual_plat')->row();
                    $nama    = explode(' ', $sales->nama);

                    $this->fpdf->Cell(1, .5, $no . '. ', 'LR', 0, 'C', $fill);
                    $this->fpdf->Cell(3, .5, $tgl.' '.$wkt, 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(2.5, .5, $penjualan->no_nota.($penjualan->status_grosir == '1' ? ' [G]' : ''), 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(2.5, .5, ucwords(strtolower($nama[0])), 'L', 0, 'R', $fill);
                    $this->fpdf->Cell(7, .5, (!empty($platform->metode_bayar) ? $platform->metode_bayar : '') . ' ' . ($platform->platform != '-' || !empty($platform->platform) ? ($platform->platform == '-' ? ucwords($platform->plat_ket) : ucwords($platform->platform)) : ''), 'L', 0, 'R', $fill);
//                    $this->fpdf->Cell(7, .5, ($platform->platform == '-'? 'TUNAI' : ucwords($platform->platform).' '.$platform->keterangan), 'L', 0, 'R', $fill); // ($platform->id > '1' ? ucwords($platform->platform).' '.$platform->keterangan : 'TUNAI')
//                    $this->fpdf->Cell(2, .5, ($penjualan->tgl_keluar != '0000-00-00' ? $tgl_tmp[1] . '/' . $tgl_tmp[2] . '/' . $tgl_tmp[0] : '-'), 'L', 0, 'R', $fill);
//                    $this->fpdf->Cell(2, .5, ($penjualan->tgl_bayar != '0000-00-00' ? $tgl_byr[1] . '/' . $tgl_byr[2] . '/' . $tgl_byr[0] : '-'), 'L', 0, 'R', $fill);
                    $this->fpdf->Cell(3, .5, general::format_angka($penjualan->jml_gtotal), 'LR', 0, 'R', $fill);
                    $this->fpdf->Ln();

                    $fill = !$fill;
                    $no++;
                }

                $this->fpdf->SetFont('Arial', 'B', '10');
                $this->fpdf->Cell(16, .5, 'Total', 1, 0, 'R', $fill);
                $this->fpdf->Cell(3, .5, general::format_angka($tot), 1, 0, 'R', $fill);
                $this->fpdf->Ln();
                $this->fpdf->SetFont('Arial', '', '8');
                $this->fpdf->Cell(19, .5, 'Dicetak : '.$this->tanggalan->tgl_indo(date('Y-m-d')).' '.date('H:i'), 0, 0, 'R', FALSE);
//                $this->fpdf->Ln();
            } else {

                $this->fpdf->SetFont('Arial', 'B', '11');
                $this->fpdf->SetFillColor(235, 232, 228);
                $this->fpdf->Cell(19, 1, 'Data Kosong', 1, 0, 'C', TRUE);
                $this->fpdf->Ln(10);
            }

            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetY(-2);
            $this->fpdf->SetFont('Arial', 'i', '9');
            $this->fpdf->Cell(9, .75, 'Copyright (c) ' . date('Y') . ' - ' . $setting->judul, 'T', 0, 'L');
            $this->fpdf->Cell(1, .75, $this->fpdf->PageNo(), 'T', 0, 'L');
            $this->fpdf->Cell(9, .75, 'Dicetak pada : ' . $this->tanggalan->tgl_indo(date('Y-m-d')).' '.date('H:i'), 'T', 0, 'R');

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $this->fpdf->Output('lap_data_penj_' . date('YmdHm') . '.pdf', $type);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_penjualan(){
        if (akses::aksesLogin() == TRUE) {
            $case  = $this->input->get('case');
            $query = $this->input->get('query');
            $p     = $this->input->get('filter_stok');

            $case  = $this->input->get('case');
            $sales = $this->input->get('id_sales');
            $tipe  = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');

            $grup        = $this->ion_auth->get_users_groups()->row();
            $id_user     = $this->ion_auth->user()->row()->id;
            $id_grup     = $this->ion_auth->get_users_groups()->row();

            switch ($case){
                case 'per_tanggal':
                    if($_GET['metode'] == 'x'){
                        $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
                                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();
                    }else{
                        $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
                                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->like('metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();
                    }
                    break;

                case 'per_rentang':
                    if($_GET['metode'] == 'x'){
                        $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tgl_masuk) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tgl_masuk) <=', $_GET['tgl_akhir'])
                                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();
                    }else{
                        $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tgl_masuk) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tgl_masuk) <=', $_GET['tgl_akhir'])
                                        ->where('metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->like('status_pjk', ($id_grup->name == 'owner2' ? '1' : ''))
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();
                    }
                    break;
            }

            $setting = $this->db->get('tbl_pengaturan')->row();

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No.')
                    ->setCellValue('B1', 'Tgl')
                    ->setCellValue('C1', 'No. Invoice')
                    ->setCellValue('D1', 'Sales')
                    ->setCellValue('E1', 'Metode')
                    ->setCellValue('F1', 'Nominal');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(65);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

            if(!empty($sql)){
                $no    = 1;
                $cell  = 2;
                $total = 0;
                foreach ($sql as $penjualan){
                    $platform = $this->db->select('tbl_m_platform.id, tbl_m_platform.platform as metode_bayar, tbl_trans_jual_plat.platform, tbl_trans_jual_plat.keterangan')->where('no_nota', $penjualan->no_nota)->join('tbl_m_platform', 'tbl_m_platform.id=tbl_trans_jual_plat.id_platform', 'left')->get('tbl_trans_jual_plat')->row();
                    $sales    = $this->db->where('id', $penjualan->id_sales)->get('tbl_m_sales')->row();
                    $nama     = explode(' ', $sales->nama);
                    $total    = $total + $penjualan->jml_gtotal;

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$cell.':E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                    $objPHPExcel->getActiveSheet()->getStyle(''.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$cell.':G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$cell, $val,PHPExcel_Cell_DataType::TYPE_STRING);

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo($penjualan->tgl_simpan).' ')
                            ->setCellValue('C'.$cell, $penjualan->no_nota.(!empty($penjualan->kode_nota_blk) ? '/'.$penjualan->kode_nota_blk : ''))
                            ->setCellValue('D'.$cell, $nama[0])
                            ->setCellValue('E'.$cell, ($platform->id == '1' ? 'TUNAI' : $platform->metode_bayar.' '.ucwords($platform->platform).' '.$platform->keterangan))
                            ->setCellValue('F'.$cell, $penjualan->jml_gtotal);

                    $no++;
                    $cell++;
                }

                $sell1     = $cell;

                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1.'')->getFont()->setBold(TRUE);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sell1, '')->mergeCells('A'.$sell1.':E'.$sell1.'')
                        ->setCellValue('F' . $sell1, $total);
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Penjualan');

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



            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
//            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_penjualan_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

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

    public function xls_data_piutang(){
        if (akses::aksesLogin() == TRUE) {
            $case  = $this->input->get('case');
            $query = $this->input->get('query');
            $p     = $this->input->get('filter_stok');

            $case  = $this->input->get('case');
            $sales = $this->input->get('id_sales');
            $tipe  = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');

            switch ($case){
                case 'per_tanggal':
                    if($_GET['metode'] == 'x'){
                        $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
                                        ->where('status_bayar !=', '1')
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();
                    }else{
                        $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
                                        ->where('status_bayar !=', '1')
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();
                    }
                    break;

                case 'per_rentang':
                    if($_GET['metode'] == 'x'){
                        $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tgl_masuk) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tgl_masuk) <=', $_GET['tgl_akhir'])
                                        ->where('status_bayar !=', '1')
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();
                    }else{
                        $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                        ->where('DATE(tgl_masuk) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tgl_masuk) <=', $_GET['tgl_akhir'])
                                        ->where('metode_bayar'.($_GET['metode'] > 1 ? ' >' : ''), $_GET['metode'])
                                        ->where('status_bayar !=', '1')
                                        ->like('id_sales', $sales)
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_jual')->result();
                    }
                    break;
            }

            $setting = $this->db->get('tbl_pengaturan')->row();

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No.')
                    ->setCellValue('B1', 'Tgl')
                    ->setCellValue('C1', 'No. Invoice')
                    ->setCellValue('D1', 'Sales')
                    ->setCellValue('E1', 'Metode')
                    ->setCellValue('F1', 'Nominal');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(65);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

            if(!empty($sql)){
                $no    = 1;
                $cell  = 2;
                $total = 0;
                foreach ($sql as $penjualan){
                    $platform = $this->db->select('tbl_m_platform.id, tbl_m_platform.platform as metode_bayar, tbl_trans_jual_plat.platform, tbl_trans_jual_plat.keterangan')->where('no_nota', $penjualan->no_nota)->join('tbl_m_platform', 'tbl_m_platform.id=tbl_trans_jual_plat.id_platform', 'left')->get('tbl_trans_jual_plat')->row();
                    $sales    = $this->db->where('id', $penjualan->id_sales)->get('tbl_m_sales')->row();
                    $nama     = explode(' ', $sales->nama);
                    $total    = $total + $penjualan->jml_gtotal;

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$cell.':E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                    $objPHPExcel->getActiveSheet()->getStyle(''.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$cell.':G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$cell, $val,PHPExcel_Cell_DataType::TYPE_STRING);

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo($penjualan->tgl_simpan).' ')
                            ->setCellValue('C'.$cell, $penjualan->no_nota.(!empty($penjualan->kode_nota_blk) ? '/'.$penjualan->kode_nota_blk : ''))
                            ->setCellValue('D'.$cell, $nama[0])
                            ->setCellValue('E'.$cell, ($platform->id == '1' ? 'TUNAI' : $platform->metode_bayar.' '.ucwords($platform->platform).' '.$platform->keterangan))
                            ->setCellValue('F'.$cell, $penjualan->jml_gtotal);

                    $no++;
                    $cell++;
                }

                $sell1     = $cell;

                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1.'')->getFont()->setBold(TRUE);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':F'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sell1, '')->mergeCells('A'.$sell1.':E'.$sell1.'')
                        ->setCellValue('F' . $sell1, $total);
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Penjualan');

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



            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
//            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_piutang_'.(isset($_GET['filename']) ? $_GET['filename'] : 'lap').'.xls"');

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

    public function pdf_data_penjualan_prod(){
        if (akses::aksesLogin() == TRUE) {
            $case     = $this->input->get('case');
            $tipe     = $this->input->get('tipe');
            $sales    = $this->input->get('sales');
            $tgl_awal = $this->input->get('tgl_awal');
            $tgl_akhr = $this->input->get('tgl_akhir');
            $merk     = $this->input->get('merk');
            $kueri    = $this->input->get('query');
            $tipe     = $this->input->get('tipe');

            $grup     = $this->ion_auth->get_users_groups()->row();
            $id_user  = $this->ion_auth->user()->row()->id;
            $id_grup  = $this->ion_auth->get_users_groups()->row();

            switch ($tipe){
                case '1':
                    switch ($case) {
                        case 'per_tanggal':
//                          $data['penjualan'] = $this->db->select('DATE(tbl_trans_jual.tgl_masuk) as tgl_simpan, tbl_trans_jual.id, tbl_trans_jual.no_nota, tbl_trans_jual.kode_nota_blk, tbl_trans_jual.id_pelanggan, tbl_trans_jual.id_user, tbl_trans_jual.kode_nota_blk, tbl_trans_jual_det.kode, tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan, SUM(tbl_trans_jual_det.jml) as jml, SUM(tbl_trans_jual_det.jml_satuan) as jml_satuan, tbl_trans_jual_det.jml * tbl_trans_jual_det.jml_satuan as keterangan, SUM(tbl_trans_jual_det.subtotal) as subtotal, tbl_m_merk.merk')
                            $sql = $this->db->select('DATE(tbl_trans_jual.tgl_masuk) as tgl_simpan, tbl_trans_jual.id, tbl_trans_jual.no_nota, tbl_trans_jual.kode_nota_blk, tbl_trans_jual.id_pelanggan, tbl_trans_jual.id_user, tbl_trans_jual.kode_nota_blk, tbl_trans_jual_det.kode, tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan, SUM(tbl_trans_jual_det.jml) as jml, (tbl_trans_jual_det.jml_satuan) as jml_satuan, tbl_trans_jual_det.jml * tbl_trans_jual_det.jml_satuan as keterangan, sum(tbl_trans_jual_det.subtotal) as subtotal, tbl_m_merk.merk')
                                            ->join('tbl_trans_jual', 'tbl_trans_jual.id=tbl_trans_jual_det.id_penjualan')
                                            ->join('tbl_m_produk', 'tbl_m_produk.kode=tbl_trans_jual_det.kode')
                                            ->join('tbl_m_merk', 'tbl_m_merk.id=tbl_m_produk.id_merk', 'left')
                                            ->where('DATE(tbl_trans_jual.tgl_masuk)', $kueri)
                                            ->like('tbl_m_merk.merk', $merk)
                                            ->like('tbl_trans_jual.status_pjk', ($id_grup->name == 'owner2' || $id_grup->name == 'admin' ? '1' : ''))
                                            ->order_by('tbl_trans_jual.no_nota', 'desc')
                                            ->group_by('tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan, tbl_trans_jual_det.jml_satuan')
                                            ->get("tbl_trans_jual_det")->result();
                            break;

                        case 'per_rentang':
                            $sql = $this->db->select('DATE(tbl_trans_jual.tgl_masuk) as tgl_simpan, tbl_trans_jual.id, tbl_trans_jual.no_nota, tbl_trans_jual.kode_nota_blk, tbl_trans_jual.id_pelanggan, tbl_trans_jual.id_user, tbl_trans_jual.kode_nota_blk, tbl_trans_jual_det.kode, tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan, SUM(tbl_trans_jual_det.jml) as jml, (tbl_trans_jual_det.jml_satuan) as jml_satuan, sum(tbl_trans_jual_det.subtotal) as subtotal, tbl_m_merk.merk, tbl_m_produk.id as id_produk')
                                            ->join('tbl_trans_jual', 'tbl_trans_jual.id=tbl_trans_jual_det.id_penjualan')
                                            ->join('tbl_m_produk', 'tbl_m_produk.kode=tbl_trans_jual_det.kode')
                                            ->join('tbl_m_merk', 'tbl_m_merk.id=tbl_m_produk.id_merk')
                                            ->where('tbl_trans_jual_det.jml !=', '0')
                                            ->where('tbl_trans_jual.tgl_masuk >=', $tgl_awal)
                                            ->where('DATE(tbl_trans_jual.tgl_masuk) <=', $tgl_akhr)
                                            ->like('tbl_trans_jual.status_pjk', ($id_grup->name == 'owner2' ? '1' : ''), '')
                                            ->like('tbl_m_produk.id_merk', $merk, (!empty($merk) ? 'none' : 'both'))
                                            ->order_by('tbl_trans_jual_det.jml', 'desc')
                                            ->group_by('tbl_trans_jual_det.produk')
                                            ->get("tbl_trans_jual_det")->result();
                            break;
                    }
                    break;
                case '2':

                    break;
            }

            $setting = $this->db->get('tbl_pengaturan')->row();

            $judul = "LAPORAN DATA PENJUALAN";

            $this->fpdf->FPDF('P', 'cm', 'a4');
            $this->fpdf->SetAutoPageBreak('auto');
            $this->fpdf->SetMargins(1, 1, 1);
            $this->fpdf->AliasNbPages();
            $this->fpdf->AddPage();

            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, strtoupper($setting->judul), '0', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '11');
            $this->fpdf->Cell(19, .5, ucwords($setting->alamat), 'B', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, $judul, 0, 1, 'C');
            $this->fpdf->Ln();


            // Fill Colornya
            $this->fpdf->SetFillColor(211, 223, 227);
            $this->fpdf->SetTextColor(0);
//        $this->fpdf->SetDrawColor(128, 0, 0);
            $this->fpdf->SetFont('Arial', 'B', '10');


//        // Header tabel
            $this->fpdf->Cell(1, .5, 'No', 1, 0, 'C', TRUE);
//            $this->fpdf->Cell(4, .5, 'Merk', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(4, .5, 'Kode', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(9, .5, 'Produk', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2, .5, 'Jml', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'Subtotal', 1, 0, 'C', TRUE);
            $this->fpdf->Ln();


            $this->fpdf->SetFillColor(235, 232, 228);
            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetFont('Arial', '', '10');

            if (!empty($sql)) {
                $fill = FALSE;
                $no = 1;
                $tot = 0;
                $jml_brg = 0;
                foreach ($sql as $penjualan) {
                    $tot     = $tot + $penjualan->subtotal;
                    $satuan  = $this->db->where('id', $penjualan->id_satuan)->get('tbl_m_sales')->row();

                    $this->fpdf->Cell(1, .5, $no . '. ', 'LR', 0, 'C', $fill);
//                    $this->fpdf->Cell(4, .5, $penjualan->merk, 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(4, .5, $penjualan->kode, 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(9, .5, $penjualan->merk.' '.$penjualan->produk, 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(2, .5, $penjualan->jml.(!empty($penjualan->satuan) ? ' '.$penjualan->satuan : ''), 'L', 0, 'R', $fill);
                    $this->fpdf->Cell(3, .5, general::format_angka($penjualan->subtotal), 'LR', 0, 'R', $fill);
                    $this->fpdf->Ln();

                    $fill = !$fill;
                    $no++;
                }

                $this->fpdf->SetFont('Arial', 'B', '10');
                $this->fpdf->Cell(12, .5, '', 'LTB', 0, 'R', $fill);
                $this->fpdf->Cell(4, .5, 'Total Penjualan', 'TB', 0, 'L', $fill);
                $this->fpdf->Cell(3, .5, general::format_angka($tot), 'TLRB', 0, 'R', $fill);
                $this->fpdf->Ln();
//                $this->fpdf->Cell(12, .5, '', 'L', 0, 'R', $fill);
//                $this->fpdf->Cell(4, .5, 'Total Diskon', '', 0, 'L', $fill);
//                $this->fpdf->Cell(3, .5, general::format_angka($nota->diskon), 'LR', 0, 'R', $fill);
//                $this->fpdf->Ln();
//                $this->fpdf->Cell(12, .5, '', 'L', 0, 'R', $fill);
//                $this->fpdf->Cell(4, .5, 'Total Biaya', '', 0, 'L', $fill);
//                $this->fpdf->Cell(3, .5, general::format_angka($nota->biaya), 'LR', 0, 'R', $fill);
//                $this->fpdf->Ln();
//                $this->fpdf->Cell(12, .5, '', 'L', 0, 'R', $fill);
//                $this->fpdf->Cell(4, .5, 'Total Ongkir', '', 0, 'L', $fill);
//                $this->fpdf->Cell(3, .5, general::format_angka($nota->ongkir), 'LR', 0, 'R', $fill);
//                $this->fpdf->Ln();
//                $this->fpdf->Cell(12, .5, '', 'BL', 0, 'R', $fill);
//                $this->fpdf->Cell(4, .5, 'Grand Total Penjualan', 'B', 0, 'L', $fill);
//                $this->fpdf->Cell(3, .5, general::format_angka($nota->jml_gtotal), 'BLR', 0, 'R', $fill);
//                $this->fpdf->Ln();
//                $this->fpdf->Cell(15.5, .5, 'Grand Total', 1, 0, 'R', $fill);
//                $this->fpdf->Cell(3.5, .5, general::format_angka($jml_brg * $tot), 1, 0, 'C', $fill);
//                $this->fpdf->Ln();
            } else {

                $this->fpdf->SetFont('Arial', 'B', '11');
                $this->fpdf->SetFillColor(235, 232, 228);
                $this->fpdf->Cell(19, 1, 'Data Kosong', 1, 0, 'C', TRUE);
                $this->fpdf->Ln(10);
            }

            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetY(-2);
            $this->fpdf->SetFont('Arial', 'i', '9');
            $this->fpdf->Cell(9, .75, 'Copyright (c) ' . date('Y') . ' - ' . $setting->judul, 'T', 0, 'L');
            $this->fpdf->Cell(1, .75, $this->fpdf->PageNo(), 'T', 0, 'L');
            $this->fpdf->Cell(9, .75, 'Dicetak pada : ' . $this->tanggalan->tgl_indo(date('Y-m-d')), 'T', 0, 'R');

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $this->fpdf->Output('lap_data_barang_' . date('YmdHm') . '.pdf', $type);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_penjualan_prod(){
        if (akses::aksesLogin() == TRUE) {
            $case     = $this->input->get('case');
            $query    = $this->input->get('query');
            $tgl_awal = $this->input->get('tgl_awal');
            $tgl_akhr = $this->input->get('tgl_akhir');
            $merk     = $this->input->get('merk');

            $case  = $this->input->get('case');
            $sales = $this->input->get('sales');
            $tipe  = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');

            switch ($case){
                case 'per_tanggal':
                        $sql = $this->db->select('DATE(tbl_trans_jual.tgl_masuk) as tgl_simpan, tbl_trans_jual.id, tbl_trans_jual.no_nota, tbl_trans_jual.kode_nota_blk, tbl_trans_jual.id_pelanggan, tbl_trans_jual.id_user, tbl_trans_jual.kode_nota_blk, tbl_trans_jual_det.kode, tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan, SUM(tbl_trans_jual_det.jml) as jml, SUM(tbl_trans_jual_det.jml_satuan) as jml_satuan, tbl_trans_jual_det.jml * tbl_trans_jual_det.jml_satuan as keterangan, SUM(tbl_trans_jual_det.subtotal) as subtotal, tbl_m_merk.merk')
                                                      ->join('tbl_trans_jual', 'tbl_trans_jual.id=tbl_trans_jual_det.id_penjualan')
                                                      ->join('tbl_m_produk', 'tbl_m_produk.kode=tbl_trans_jual_det.kode')
                                                      ->join('tbl_m_merk', 'tbl_m_merk.id=tbl_m_produk.id_merk','left')
                                                      ->where('DATE(tbl_trans_jual.tgl_masuk)', $_GET['query'])
                                                      ->like('tbl_m_produk.id_merk', $merk, (!empty($merk) ? 'none' : 'both'))
                                                      ->order_by('tbl_trans_jual.no_nota','desc')
                                                      ->group_by('tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan')
                                                      ->get("tbl_trans_jual_det")->result();
                    break;

                case 'per_rentang':
                        $sql = $this->db->select('DATE(tbl_trans_jual.tgl_masuk) as tgl_simpan, tbl_trans_jual.id, tbl_trans_jual.no_nota, tbl_trans_jual.kode_nota_blk, tbl_trans_jual.id_pelanggan, tbl_trans_jual.id_user, tbl_trans_jual.kode_nota_blk, tbl_trans_jual_det.kode, tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan, SUM(tbl_trans_jual_det.jml) as jml, SUM(tbl_trans_jual_det.jml_satuan) as jml_satuan, tbl_trans_jual_det.jml * tbl_trans_jual_det.jml_satuan as keterangan, SUM(tbl_trans_jual_det.subtotal) as subtotal, tbl_m_merk.merk')
                                                      ->join('tbl_trans_jual', 'tbl_trans_jual.id=tbl_trans_jual_det.id_penjualan')
                                                      ->join('tbl_m_produk', 'tbl_m_produk.kode=tbl_trans_jual_det.kode')
                                                      ->join('tbl_m_merk', 'tbl_m_merk.id=tbl_m_produk.id_merk','left')
                                                      ->where('DATE(tbl_trans_jual.tgl_masuk) >=', $tgl_awal)
                                                      ->where('DATE(tbl_trans_jual.tgl_masuk) <=', $tgl_akhr)
                                                      ->like('tbl_m_produk.id_merk', $merk, (!empty($merk) ? 'none' : 'both'))
                                                      ->order_by('tbl_trans_jual.no_nota','desc')
                                                      ->group_by('tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan')
                                                      ->get("tbl_trans_jual_det")->result();
                    break;
            }

            $setting = $this->db->get('tbl_pengaturan')->row();

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No.')
                    ->setCellValue('B1', 'Kode')
                    ->setCellValue('C1', 'Produk')
                    ->setCellValue('D1', 'Jml')
                    ->setCellValue('E1', 'Subtotal');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

            if(!empty($sql)){
                $no    = 1;
                $cell  = 2;
                foreach ($sql as $penjualan){
                    $tot     = $tot + $penjualan->subtotal;
                    $satuan  = $this->db->where('id', $penjualan->id_satuan)->get('tbl_m_sales')->row();

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                    $objPHPExcel->getActiveSheet()->getStyle(''.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$cell.':G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$cell, $val,PHPExcel_Cell_DataType::TYPE_STRING);

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, '')
                            ->setCellValue('B'.$cell, '')
                            ->setCellValue('C'.$cell, '')
                            ->setCellValue('D'.$cell, 'Subtotal')
                            ->setCellValue('E'.$cell, general::format_angka($tot).' ');

                    $no++;
                    $cell++;
                }
            }

            $sell1 = $cell + 1;

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $sell1, $no)
                    ->setCellValue('B' . $sell1, $penjualan->kode)
                    ->setCellValue('C' . $sell1, $penjualan->produk)
                    ->setCellValue('D' . $sell1, $penjualan->jml)
                    ->setCellValue('E' . $sell1, general::format_angka($produk->subtotal) . ' ');

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Penjualan');

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



            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
//            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_penjualan_prod_'.(isset($_GET['filename']) ? $_GET['filename'] : 'laporan').'.xls"');

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

    public function pdf_data_penjualan_kasir(){
        if (akses::aksesLogin() == TRUE) {
            $tgl_awal = (isset($_GET['tgl_awal']) ? $this->input->get('tgl_awal') : date('Y-m-d'));
            $tgl_akhr = (isset($_GET['tgl_akhir']) ? $this->input->get('tgl_akhir') : date('Y-m-d'));
            $met_bayar= (isset($_GET['metode']) ? $this->input->get('metode') : '');
            $id_user  = $this->ion_auth->user()->row()->id;

            if(!empty($met_bayar)){
                $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                ->where('id_user', $id_user)
                                ->where('DATE(tgl_masuk) >=', $tgl_awal)
                                ->where('DATE(tgl_masuk) <=', $tgl_akhr)
                                ->where('metode_bayar', (!empty($met_bayar) ? $met_bayar : ''))
                                ->order_by('no_nota', 'desc')
                                ->get('tbl_trans_jual')->result();
            }else{
                $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, kode_nota_dpn, kode_nota_blk, jml_total, ppn, jml_ppn, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_sales, id_pelanggan')
                                ->where('id_user', $id_user)
                                ->where('DATE(tgl_masuk) >=', $tgl_awal)
                                ->where('DATE(tgl_masuk) <=', $tgl_akhr)
                                ->order_by('no_nota', 'desc')
                                ->get('tbl_trans_jual')->result();
            }

            $setting = $this->db->get('tbl_pengaturan')->row();

            $judul = "REKAP PENJUALAN PER KASIR";

            $this->fpdf->FPDF('P', 'cm', 'a4');
            $this->fpdf->SetAutoPageBreak('auto');
            $this->fpdf->SetMargins(1, 1, 1);
            $this->fpdf->AliasNbPages();
            $this->fpdf->AddPage();

            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, strtoupper($setting->judul), '0', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '11');
            $this->fpdf->Cell(19, .5, ucwords($setting->alamat), 'B', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, $judul, 0, 1, 'C');
            $this->fpdf->Ln();


            // Fill Colornya
            $this->fpdf->SetFillColor(211, 223, 227);
            $this->fpdf->SetTextColor(0);
//        $this->fpdf->SetDrawColor(128, 0, 0);
            $this->fpdf->SetFont('Arial', 'B', '10');


//        // Header tabel
            $this->fpdf->Cell(1, .5, 'No', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2, .5, 'Tgl', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'No. Invoice', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'Kasir', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'Metode', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2, .5, 'Tempo', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2, .5, 'Bayar', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'Nominal', 1, 0, 'C', TRUE);
            $this->fpdf->Ln();


            $this->fpdf->SetFillColor(235, 232, 228);
            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetFont('Arial', '', '10');

            if (!empty($sql)) {
                $fill = FALSE;
                $no = 1;
                $tot = 0;
                $jml_brg = 0;
                foreach ($sql as $penjualan) {
                    $tot     = $tot + $penjualan->jml_gtotal;
                    $tgl     = explode('-', $penjualan->tgl_simpan);
                    $tgl_tmp = explode('-', $penjualan->tgl_keluar);
                    $tgl_byr = explode('-', $penjualan->tgl_bayar);
                    $sales   = $this->db->where('kode', $penjualan->kode_nota_blk)->get('tbl_m_sales')->row();
                    $metode  = $this->db->where('id', $penjualan->metode_bayar)->get('tbl_m_platform')->row();

                    $this->fpdf->Cell(1, .5, $no . '. ', 'LR', 0, 'C', $fill);
                    $this->fpdf->Cell(2, .5, $tgl[1] . '/' . $tgl[2] . '/' . $tgl[0], 'L', 0, 'C', $fill);
                    $this->fpdf->Cell(3, .5, $penjualan->no_nota, 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(3, .5, ucwords(strtolower($sales->nama)), 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(3, .5, (!empty($metode) ? $metode->platform : 'Tunai'), 'L', 0, 'C', $fill);
                    $this->fpdf->Cell(2, .5, ($penjualan->tgl_keluar != '0000-00-00' ? $tgl_tmp[1] . '/' . $tgl_tmp[2] . '/' . $tgl_tmp[0] : '-'), 'L', 0, 'R', $fill);
                    $this->fpdf->Cell(2, .5, ($penjualan->tgl_bayar != '0000-00-00' ? $tgl_byr[1] . '/' . $tgl_byr[2] . '/' . $tgl_byr[0] : '-'), 'L', 0, 'R', $fill);
                    $this->fpdf->Cell(3, .5, general::format_angka($penjualan->jml_gtotal), 'LR', 0, 'R', $fill);
                    $this->fpdf->Ln();

                    $fill = !$fill;
                    $no++;
                }

                $this->fpdf->SetFont('Arial', 'B', '10');
                $this->fpdf->Cell(16, .5, 'Total', 1, 0, 'R', $fill);
                $this->fpdf->Cell(3, .5, general::format_angka($tot), 1, 0, 'R', $fill);
//                $this->fpdf->Ln();
//                $this->fpdf->Cell(15.5, .5, 'Grand Total', 1, 0, 'R', $fill);
//                $this->fpdf->Cell(3.5, .5, general::format_angka($jml_brg * $tot), 1, 0, 'C', $fill);
//                $this->fpdf->Ln();
            } else {

                $this->fpdf->SetFont('Arial', 'B', '11');
                $this->fpdf->SetFillColor(235, 232, 228);
                $this->fpdf->Cell(19, 1, 'Data Kosong', 1, 0, 'C', TRUE);
                $this->fpdf->Ln(10);
            }

            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetY(-2);
            $this->fpdf->SetFont('Arial', 'i', '9');
            $this->fpdf->Cell(9, .75, 'Copyright (c) ' . date('Y') . ' - ' . $setting->judul, 'T', 0, 'L');
            $this->fpdf->Cell(1, .75, $this->fpdf->PageNo(), 'T', 0, 'L');
            $this->fpdf->Cell(9, .75, 'Dicetak pada : ' . $this->tanggalan->tgl_indo(date('Y-m-d')), 'T', 0, 'R');

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $this->fpdf->Output('lap_data_barang_' . date('YmdHm') . '.pdf', $type);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function pdf_data_penjualan_produk(){
        if (akses::aksesLogin() == TRUE) {
            $case     = $this->input->get('case');
            $query    = $this->input->get('query');
            $p        = $this->input->get('filter_stok');

            $case     = $this->input->get('case');
            $sales    = $this->input->get('sales');
            $tipe     = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');

            $tgl_awal = (isset($_GET['tgl_awal']) ? $this->input->get('tgl_awal') : date('Y-m-d'));
            $tgl_akhr = (isset($_GET['tgl_akhir']) ? $this->input->get('tgl_akhir') : date('Y-m-d'));
            $id_user  = $this->ion_auth->user()->row()->id;

            $where = "(tbl_trans_jual.id_user LIKE '".$id_user."')";
            $sql   = $this->db->select('DATE(tbl_trans_jual.tgl_simpan) as tgl_simpan, tbl_trans_jual_det.kode, tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan, SUM(tbl_trans_jual_det.jml) as jml, SUM(tbl_trans_jual_det.jml * tbl_trans_jual_det.jml_satuan) as keterangan, SUM(tbl_trans_jual_det.subtotal) as subtotal, tbl_m_satuan.satuanTerkecil as satKecil')
                                          ->join('tbl_trans_jual', 'tbl_trans_jual.no_nota=tbl_trans_jual_det.no_nota')
                                          ->join('tbl_m_satuan', 'tbl_m_satuan.id=tbl_trans_jual_det.id_satuan')
                                          ->where($where)
                                          ->where('DATE(tbl_trans_jual.tgl_simpan) >=', $tgl_awal)
                                          ->where('DATE(tbl_trans_jual.tgl_simpan) <=', $tgl_akhr)
                                          ->order_by('tbl_trans_jual.no_nota','desc')
                                          ->group_by('tbl_trans_jual_det.produk, tbl_trans_jual_det.satuan')
                                          ->get("tbl_trans_jual_det")->result();

            $setting = $this->db->get('tbl_pengaturan')->row();

            $judul = "REKAP PRODUK TERJUAL";

            $this->fpdf->FPDF('P', 'cm', 'a4');
            $this->fpdf->SetAutoPageBreak('auto');
            $this->fpdf->SetMargins(1, 1, 1);
            $this->fpdf->AliasNbPages();
            $this->fpdf->AddPage();

            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, strtoupper($setting->judul), '0', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '11');
            $this->fpdf->Cell(19, .5, ucwords($setting->alamat), 'B', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, $judul, 0, 1, 'C');
            $this->fpdf->Ln();

            $this->fpdf->SetFont('Arial', '', '10');
            $this->fpdf->Cell(19, .5, 'Penjualan dari '.$this->tanggalan->tgl_indo($tgl_awal).' s/d '.$this->tanggalan->tgl_indo($tgl_akhr), 0, 1, 'L');

            // Fill Colornya
            $this->fpdf->SetFillColor(211, 223, 227);
            $this->fpdf->SetTextColor(0);
//          $this->fpdf->SetDrawColor(128, 0, 0);
            $this->fpdf->SetFont('Arial', 'B', '10');

//          Header tabel
            $this->fpdf->Cell(1, .5, 'No', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'Kode', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(8.5, .5, 'Produk', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3.5, .5, 'Jml', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'Nominal', 1, 0, 'C', TRUE);
            $this->fpdf->Ln();


            $this->fpdf->SetFillColor(235, 232, 228);
            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetFont('Arial', '', '10');

            if (!empty($sql)) {
                $fill = FALSE;
                $no = 1;
                $tot = 0;
                $jml_brg = 0;
                foreach ($sql as $penjualan) {
                    $tot     = $tot + $penjualan->subtotal;

                    $this->fpdf->Cell(1, .5, $no . '.', 'LR', 0, 'C', $fill);
                    $this->fpdf->Cell(3, .5, $penjualan->kode, 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(8.5, .5, $penjualan->produk, 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(1, .5, $penjualan->jml, 'L', 0, 'R', $fill);
                    $this->fpdf->Cell(2.5, .5, $penjualan->satuan.($penjualan->satuan != $penjualan->satKecil ? ' ('.$penjualan->keterangan.' '.$penjualan->satKecil.')' : ''), '', 0, 'L', $fill);
                    $this->fpdf->Cell(3, .5, general::format_angka($penjualan->subtotal), 'LR', 0, 'R', $fill);
                    $this->fpdf->Ln();

                    $fill = !$fill;
                    $no++;
                }

                $this->fpdf->SetFont('Arial', 'B', '10');
                $this->fpdf->Cell(16, .5, 'Total', 1, 0, 'R', $fill);
                $this->fpdf->Cell(3, .5, general::format_angka($tot), 1, 0, 'R', $fill);
//                $this->fpdf->Ln();
//                $this->fpdf->Cell(15.5, .5, 'Grand Total', 1, 0, 'R', $fill);
//                $this->fpdf->Cell(3.5, .5, general::format_angka($jml_brg * $tot), 1, 0, 'C', $fill);
//                $this->fpdf->Ln();
            } else {

                $this->fpdf->SetFont('Arial', 'B', '11');
                $this->fpdf->SetFillColor(235, 232, 228);
                $this->fpdf->Cell(19, 1, 'Data Kosong', 1, 0, 'C', TRUE);
                $this->fpdf->Ln(10);
            }

            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetY(-2);
            $this->fpdf->SetFont('Arial', 'i', '9');
            $this->fpdf->Cell(9, .75, 'Copyright (c) ' . date('Y') . ' - ' . $setting->judul, 'T', 0, 'L');
            $this->fpdf->Cell(1, .75, $this->fpdf->PageNo(), 'T', 0, 'L');
            $this->fpdf->Cell(9, .75, 'Dicetak pada : ' . $this->tanggalan->tgl_indo(date('Y-m-d')), 'T', 0, 'R');

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $this->fpdf->Output('lap_data_barang_' . date('YmdHm') . '.pdf', $type);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function pdf_data_pembelian(){
        if (akses::aksesLogin() == TRUE) {
            $case  = $this->input->get('case');
            $query = $this->input->get('query');
            $p     = $this->input->get('filter_stok');

            $case  = $this->input->get('case');
            $sales = $this->input->get('sales');
            $sb    = $this->input->get('sb');
            $ht    = $this->input->get('hutang');
            $sp    = $this->input->get('status_ppn');
            $tipe  = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');

            switch ($case){
                case 'per_tanggal':
                    if(!empty($sb)){
                        $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, jml_total, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_supplier')
                                        ->where('status_bayar', $sb)
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
                                        ->where('status_ppn'.($sp > 0 ? ' >' : ''), ($sp > 0 ? '0' : $sp))
                                        ->order_by('tgl_simpan', 'asc')
                                        ->get('tbl_trans_beli')->result();
                    }else{
                        $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, jml_total, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_supplier')
                                        ->where('DATE(tgl_masuk)', $_GET['query'])
                                        ->where('status_ppn'.($sp > 0 ? ' >' : ''), ($sp > 0 ? '0' : $sp))
                                        ->order_by('tgl_simpan', 'asc')
                                        ->get('tbl_trans_beli')->result();
                    }
                    break;

                case 'per_rentang':
                    if(!empty($sb)){
                        $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, jml_total, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_supplier')
                                        ->where('status_bayar', $sb)
//                                        ->where('jml_gtotal >', '100')
                                        ->where('DATE(tgl_masuk) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tgl_masuk) <=', $_GET['tgl_akhir'])
                                        ->where('status_ppn'.($sp > 0 ? ' >' : ''), ($sp > 0 ? '0' : $sp))
                                        ->order_by('tgl_simpan', 'asc')
                                        ->get('tbl_trans_beli')->result();
                    }else{
                        $sql = $this->db->select('DATE(tgl_masuk) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, jml_total, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_supplier')
                                        ->where('DATE(tgl_masuk) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tgl_masuk) <=', $_GET['tgl_akhir'])
                                        ->where('status_ppn'.($sp > 0 ? ' >' : ''), ($sp > 0 ? '0' : $sp))
                                        ->order_by('tgl_masuk', 'asc')
                                        ->get('tbl_trans_beli')->result();
                    }
                    break;
            }

            $setting = $this->db->get('tbl_pengaturan')->row();

            $judul = "LAPORAN DATA ".(isset($_GET['hutang']) ? 'HUTANG' : 'PEMBELIAN');

            $this->fpdf->FPDF('P', 'cm', 'a4');
            $this->fpdf->SetAutoPageBreak('auto');
            $this->fpdf->SetMargins(1, 1, 1);
            $this->fpdf->AliasNbPages();
            $this->fpdf->AddPage();

            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, strtoupper($setting->judul), '0', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '11');
            $this->fpdf->Cell(19, .5, ucwords($setting->alamat), 'B', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, $judul, 0, 1, 'C');
            $this->fpdf->Ln();


            // Fill Colornya
            $this->fpdf->SetFillColor(211, 223, 227);
            $this->fpdf->SetTextColor(0);
//        $this->fpdf->SetDrawColor(128, 0, 0);
            $this->fpdf->SetFont('Arial', 'B', '10');


//        // Header tabel
            $this->fpdf->Cell(1, .5, 'No', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2, .5, 'Tgl', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'No. Invoice', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(6, .5, 'Supplier', 1, 0, 'C', TRUE);
//            $this->fpdf->Cell(3, .5, 'Metode', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2, .5, 'Tempo', 1, 0, 'C', TRUE);
            if(!isset($_GET['hutang'])){
                $this->fpdf->Cell(2, .5, 'Bayar', 1, 0, 'C', TRUE);
            }
            $this->fpdf->Cell(3, .5, 'Nominal', 1, 0, 'C', TRUE);
            $this->fpdf->Ln();


            $this->fpdf->SetFillColor(235, 232, 228);
            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetFont('Arial', '', '10');

            if (!empty($sql)) {
                $fill = FALSE;
                $no = 1;
                $tot = 0;
                $jml_brg = 0;
                foreach ($sql as $penjualan) {
                    $tot      = $tot + $penjualan->jml_gtotal;
                    $tgl      = explode('-', $penjualan->tgl_simpan);
                    $tgl_tmp  = explode('-', $penjualan->tgl_keluar);
                    $tgl_byr  = explode('-', $penjualan->tgl_bayar);
                    $supplier = $this->db->where('id', $penjualan->id_supplier)->get('tbl_m_supplier')->row();

                    $this->fpdf->Cell(1, .5, $no . '. ', 'LR', 0, 'C', $fill);
                    $this->fpdf->Cell(2, .5, $tgl[1] . '/' . $tgl[2] . '/' . $tgl[0], 'L', 0, 'C', $fill);
                    $this->fpdf->Cell(3, .5, $penjualan->no_nota, 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(6, .5, ucwords(strtolower($supplier->nama)), 'L', 0, 'L', $fill);
//                    $this->fpdf->Cell(3, .5, (!empty($platform->metode_bayar) ? $platform->metode_bayar : 'Tunai').' '.ucwords($platform->platform).' '.$platform->keterangan, 'L', 0, 'C', $fill);
                    $this->fpdf->Cell(2, .5, $this->tanggalan->tgl_indo($penjualan->tgl_keluar), 'L', 0, 'R', $fill);
                    if(!isset($_GET['hutang'])){
                        $this->fpdf->Cell(2, .5, ($penjualan->tgl_bayar != '0000-00-00' || !empty($penjualan->tgl_bayar) ? $this->tanggalan->tgl_indo($penjualan->tgl_bayar) : '-'), 'L', 0, 'R', $fill);
                    }
                    $this->fpdf->Cell(3, .5, general::format_angka($penjualan->jml_gtotal), 'LR', 0, 'R', $fill);
                    $this->fpdf->Ln();

                    $fill = !$fill;
                    $no++;
                }

                $this->fpdf->SetFont('Arial', 'B', '10');
                $this->fpdf->Cell((!isset($_GET['hutang']) ? 16 : 14), .5, 'Total', 1, 0, 'R', $fill);
                $this->fpdf->Cell(3, .5, general::format_angka($tot), 1, 0, 'R', $fill);
//                $this->fpdf->Ln();
//                $this->fpdf->Cell(15.5, .5, 'Grand Total', 1, 0, 'R', $fill);
//                $this->fpdf->Cell(3.5, .5, general::format_angka($jml_brg * $tot), 1, 0, 'C', $fill);
//                $this->fpdf->Ln();
            } else {

                $this->fpdf->SetFont('Arial', 'B', '11');
                $this->fpdf->SetFillColor(235, 232, 228);
                $this->fpdf->Cell(19, 1, 'Data Kosong', 1, 0, 'C', TRUE);
                $this->fpdf->Ln(10);
            }

            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetY(-1);
            $this->fpdf->SetFont('Arial', 'i', '9');
            $this->fpdf->Cell(9, .75, 'Copyright (c) ' . date('Y') . ' - ' . $setting->judul, 'T', 0, 'L');
            $this->fpdf->Cell(1, .75, $this->fpdf->PageNo(), 'T', 0, 'L');
            $this->fpdf->Cell(9, .75, 'Dicetak pada : ' . $this->tanggalan->tgl_indo(date('Y-m-d')), 'T', 0, 'R');

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $this->fpdf->Output('lap_data_'.(!isset($_GET['hutang']) ? 'pembelian' : 'hutang').'_' . date('YmdHm') . '.pdf', $type);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_pembelian(){
        if (akses::aksesLogin() == TRUE) {
            $case  = $this->input->get('case');
            $query = $this->input->get('query');
            $p     = $this->input->get('filter_stok');

            $case  = $this->input->get('case');
            $sales = $this->input->get('sales');
            $sb    = $this->input->get('sb');
            $ht    = $this->input->get('hutang');
            $sp    = $this->input->get('status_ppn');
            $tipe  = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');

            switch ($case){
                case 'per_tanggal':
                    if(!empty($sb)){
                        $sql = $this->db->select('DATE(tgl_simpan) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, jml_total, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_supplier')
                                        ->where('status_bayar !=', '1')
                                        ->where('DATE(tgl_simpan)', $_GET['query'])
//                                        ->like('status_ppn', $sp)
                                        ->where('status_ppn'.($sp > 0 ? ' >' : ''), ($sp > 0 ? '0' : $sp))
                                        ->order_by('tgl_masuk', 'asc')
                                        ->get('tbl_trans_beli')->result();
                    }else{
                        $sql = $this->db->select('DATE(tgl_simpan) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, jml_total, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_supplier')
                                        ->where('DATE(tgl_simpan)', $_GET['query'])
//                                        ->like('status_ppn', $sp)
                                        ->where('status_ppn'.($sp > 0 ? ' >' : ''), ($sp > 0 ? '0' : $sp))
                                        ->order_by('tgl_masuk', 'asc')
                                        ->get('tbl_trans_beli')->result();
                    }
                    break;

                case 'per_rentang':
                    if(!empty($sb)){
                        $sql = $this->db->select('DATE(tgl_simpan) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, jml_total, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_supplier')
                                        ->where('status_bayar !=', '1')
                                        ->where('DATE(tgl_simpan) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tgl_simpan) <=', $_GET['tgl_akhir'])
//                                        ->like('status_ppn', $sp)
                                        ->where('status_ppn'.($sp > 0 ? ' >' : ''), ($sp > 0 ? '0' : $sp))
                                        ->order_by('tgl_masuk', 'asc')
                                        ->get('tbl_trans_beli')->result();
                    }else{
                        $sql = $this->db->select('DATE(tgl_simpan) as tgl_simpan, tgl_keluar, DATE(tgl_bayar) as tgl_bayar, no_nota, jml_total, jml_gtotal, jml_bayar, jml_kurang, status_nota, metode_bayar, status_bayar, id_user, id_supplier')
                                        ->where('DATE(tgl_simpan) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tgl_simpan) <=', $_GET['tgl_akhir'])
//                                        ->like('status_ppn', $sp)
                                        ->where('status_ppn'.($sp > 0 ? ' >' : ''), ($sp > 0 ? '0' : $sp))
                                        ->order_by('tgl_masuk', 'asc')
                                        ->get('tbl_trans_beli')->result();
                    }
                    break;
            }

            $setting = $this->db->get('tbl_pengaturan')->row();

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(TRUE);

            if(isset($ht)){
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'No.')
                        ->setCellValue('B1', 'Tgl')
                        ->setCellValue('C1', 'No. Invoice')
                        ->setCellValue('D1', 'Supplier')
//                        ->setCellValue('E1', 'Metode')
                        ->setCellValue('E1', 'Tempo')->mergeCells('E1:F1')
                        ->setCellValue('G1', 'Nominal');
            }else{
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'No.')
                        ->setCellValue('B1', 'Tgl')
                        ->setCellValue('C1', 'No. Invoice')
                        ->setCellValue('D1', 'Supplier')
//                        ->setCellValue('E1', 'Metode')
                        ->setCellValue('E1', 'Tempo')
                        ->setCellValue('F1', 'Bayar')
                        ->setCellValue('G1', 'Nominal');
            }

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(70);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

            if(!empty($sql)){
                $no = 1;
                $cell  = 2;
                $total = 0;
                foreach ($sql as $penjualan) {
                    $tot      = $tot + $penjualan->jml_gtotal;
                    $supplier = $this->db->where('id', $penjualan->id_supplier)->get('tbl_m_supplier')->row();
                    $total    = $total + $penjualan->jml_gtotal;

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$cell.':E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                    $objPHPExcel->getActiveSheet()->getStyle('F'.$cell.':G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$cell, $val,PHPExcel_Cell_DataType::TYPE_STRING);

                    if(isset($ht)){
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$cell, $no)
                                ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo($penjualan->tgl_simpan).' ')
                                ->setCellValue('C'.$cell, $penjualan->no_nota)
                                ->setCellValue('D'.$cell, $supplier->nama)
//                                ->setCellValue('E'.$cell, (!empty($platform->metode_bayar) ? $platform->metode_bayar : '') . ' ' . (!empty($platform->platform) ? ucwords($platform->platform).' '.$platform->keterangan : 'TUNAI'))
                                ->setCellValue('E'.$cell, $this->tanggalan->tgl_indo($penjualan->tgl_keluar).' ')->mergeCells('E'.$cell.':F'.$cell)
                                ->setCellValue('G'.$cell, general::format_angka($penjualan->jml_gtotal).' ');
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$cell, $no)
                                ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo($penjualan->tgl_simpan).' ')
                                ->setCellValue('C'.$cell, $penjualan->no_nota)
                                ->setCellValue('D'.$cell, $supplier->nama)
//                                ->setCellValue('E'.$cell, (!empty($platform->metode_bayar) ? $platform->metode_bayar : '') . ' ' . (!empty($platform->platform) ? ucwords($platform->platform).' '.$platform->keterangan : 'TUNAI'))
                                ->setCellValue('E'.$cell, $this->tanggalan->tgl_indo($penjualan->tgl_keluar).' ')
                                ->setCellValue('F'.$cell, $this->tanggalan->tgl_indo($penjualan->tgl_bayar).' ')
                                ->setCellValue('G'.$cell, general::format_angka($penjualan->jml_gtotal).' ');
                    }

                    $no++;
                    $cell++;
                }

                $sell1     = $cell;

                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':G'.$sell1.'')->getFont()->setBold(TRUE);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':G'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sell1, 'TOTAL')->mergeCells('A'.$sell1.':F'.$sell1.'')
                        ->setCellValue('G' . $sell1, general::format_angka($total) . ' ');
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap '.(isset($ht) ? 'Hutang' : 'Pembelian'));

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



            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
//            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="lap_data_'.(!isset($ht) ? 'pembelian' : 'hutang').'_' . date('YmdHm').'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            ob_clean();
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;


//            $judul = "LAPORAN DATA PEMBELIAN";
//
//            $this->fpdf->FPDF('P', 'cm', 'a4');
//            $this->fpdf->SetAutoPageBreak('auto');
//            $this->fpdf->SetMargins(1, 1, 1);
//            $this->fpdf->AliasNbPages();
//            $this->fpdf->AddPage();
//
//            $this->fpdf->SetFont('Arial', 'B', '14');
//            $this->fpdf->Cell(19, .75, strtoupper($setting->judul), '0', 1, 'C');
//            $this->fpdf->Ln(0);
//            $this->fpdf->SetFont('Arial', 'B', '11');
//            $this->fpdf->Cell(19, .5, ucwords($setting->alamat), 'B', 1, 'C');
//            $this->fpdf->Ln(0);
//            $this->fpdf->SetFont('Arial', 'B', '14');
//            $this->fpdf->Cell(19, .75, $judul, 0, 1, 'C');
//            $this->fpdf->Ln();
//
//
//            // Fill Colornya
//            $this->fpdf->SetFillColor(211, 223, 227);
//            $this->fpdf->SetTextColor(0);
////        $this->fpdf->SetDrawColor(128, 0, 0);
//            $this->fpdf->SetFont('Arial', 'B', '10');
//
//
////        // Header tabel
//            $this->fpdf->Cell(1, .5, 'No', 1, 0, 'C', TRUE);
//            $this->fpdf->Cell(2, .5, 'Tgl', 1, 0, 'C', TRUE);
//            $this->fpdf->Cell(3, .5, 'No. Invoice', 1, 0, 'C', TRUE);
//            $this->fpdf->Cell(6, .5, 'Supplier', 1, 0, 'C', TRUE);
////            $this->fpdf->Cell(3, .5, 'Metode', 1, 0, 'C', TRUE);
//            $this->fpdf->Cell(2, .5, 'Tempo', 1, 0, 'C', TRUE);
//            $this->fpdf->Cell(2, .5, 'Bayar', 1, 0, 'C', TRUE);
//            $this->fpdf->Cell(3, .5, 'Nominal', 1, 0, 'C', TRUE);
//            $this->fpdf->Ln();
//
//
//            $this->fpdf->SetFillColor(235, 232, 228);
//            $this->fpdf->SetTextColor(0);
//            $this->fpdf->SetFont('Arial', '', '10');
//
//            if (!empty($sql)) {
//                $fill = FALSE;
//                $no = 1;
//                $tot = 0;
//                $jml_brg = 0;
//                foreach ($sql as $penjualan) {
//                    $tot      = $tot + $penjualan->jml_gtotal;
//                    $tgl      = explode('-', $penjualan->tgl_simpan);
//                    $tgl_tmp  = explode('-', $penjualan->tgl_keluar);
//                    $tgl_byr  = explode('-', $penjualan->tgl_bayar);
//                    $supplier = $this->db->where('id', $penjualan->id_supplier)->get('tbl_m_supplier')->row();
//
//                    $this->fpdf->Cell(1, .5, $no . '. ', 'LR', 0, 'C', $fill);
//                    $this->fpdf->Cell(2, .5, $tgl[1] . '/' . $tgl[2] . '/' . $tgl[0], 'L', 0, 'C', $fill);
//                    $this->fpdf->Cell(3, .5, $penjualan->no_nota, 'L', 0, 'L', $fill);
//                    $this->fpdf->Cell(6, .5, ucwords(strtolower($supplier->nama)), 'L', 0, 'L', $fill);
////                    $this->fpdf->Cell(3, .5, (!empty($platform->metode_bayar) ? $platform->metode_bayar : 'Tunai').' '.ucwords($platform->platform).' '.$platform->keterangan, 'L', 0, 'C', $fill);
//                    $this->fpdf->Cell(2, .5, $this->tanggalan->tgl_indo($penjualan->tgl_keluar), 'L', 0, 'R', $fill);
//                    $this->fpdf->Cell(2, .5, ($penjualan->tgl_bayar != '0000-00-00' || !empty($penjualan->tgl_bayar) ? $this->tanggalan->tgl_indo($penjualan->tgl_bayar) : '-'), 'L', 0, 'R', $fill);
//                    $this->fpdf->Cell(3, .5, general::format_angka($penjualan->jml_gtotal), 'LR', 0, 'R', $fill);
//                    $this->fpdf->Ln();
//
//                    $fill = !$fill;
//                    $no++;
//                }
//
//                $this->fpdf->SetFont('Arial', 'B', '10');
//                $this->fpdf->Cell(16, .5, 'Total', 1, 0, 'R', $fill);
//                $this->fpdf->Cell(3, .5, general::format_angka($tot), 1, 0, 'R', $fill);
////                $this->fpdf->Ln();
////                $this->fpdf->Cell(15.5, .5, 'Grand Total', 1, 0, 'R', $fill);
////                $this->fpdf->Cell(3.5, .5, general::format_angka($jml_brg * $tot), 1, 0, 'C', $fill);
////                $this->fpdf->Ln();
//            } else {
//
//                $this->fpdf->SetFont('Arial', 'B', '11');
//                $this->fpdf->SetFillColor(235, 232, 228);
//                $this->fpdf->Cell(19, 1, 'Data Kosong', 1, 0, 'C', TRUE);
//                $this->fpdf->Ln(10);
//            }
//
//            $this->fpdf->SetTextColor(0);
//            $this->fpdf->SetY(-2);
//            $this->fpdf->SetFont('Arial', 'i', '9');
//            $this->fpdf->Cell(9, .75, 'Copyright (c) ' . date('Y') . ' - ' . $setting->judul, 'T', 0, 'L');
//            $this->fpdf->Cell(1, .75, $this->fpdf->PageNo(), 'T', 0, 'L');
//            $this->fpdf->Cell(9, .75, 'Dicetak pada : ' . $this->tanggalan->tgl_indo(date('Y-m-d')), 'T', 0, 'R');
//
//            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');
//
//            $this->fpdf->Output('lap_data_barang_' . date('YmdHm') . '.pdf', $type);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function pdf_data_retur_penjualan(){
        if (akses::aksesLogin() == TRUE) {
            $case  = $this->input->get('case');
            $query = $this->input->get('query');
            $p     = $this->input->get('filter_stok');

            $case  = $this->input->get('case');
            $sales = $this->input->get('sales');
            $tipe  = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');

            switch ($case){
                case 'per_tanggal':
                        $sql = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id_pelanggan, id_user, no_retur, no_nota, jml_retur, status_retur')
                                        ->where('DATE(tgl_simpan)', $_GET['query'])
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_retur_jual')->result();
                    break;

                case 'per_rentang':
                        $sql = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id_pelanggan, id_user, no_retur, no_nota, jml_retur, status_retur')
                                        ->where('DATE(tgl_simpan) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tgl_simpan) <=', $_GET['tgl_akhir'])
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_retur_jual')->result();
                    break;
            }

            $setting = $this->db->get('tbl_pengaturan')->row();

            $judul = "LAPORAN DATA RETUR PENJUALAN";

            $this->fpdf->FPDF('P', 'cm', 'a4');
            $this->fpdf->SetAutoPageBreak('auto');
            $this->fpdf->SetMargins(1, 1, 1);
            $this->fpdf->AliasNbPages();
            $this->fpdf->AddPage();

            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, strtoupper($setting->judul), '0', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '11');
            $this->fpdf->Cell(19, .5, ucwords($setting->alamat), 'B', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, $judul, 0, 1, 'C');
            $this->fpdf->Ln();


            // Fill Colornya
            $this->fpdf->SetFillColor(211, 223, 227);
            $this->fpdf->SetTextColor(0);
//        $this->fpdf->SetDrawColor(128, 0, 0);
            $this->fpdf->SetFont('Arial', 'B', '10');


//        // Header tabel
            $this->fpdf->Cell(1, .5, 'No', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2, .5, 'Tgl', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(4, .5, 'No. Retur', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(4, .5, 'No. Invoice', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(5, .5, 'Pelanggan', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'Nominal', 1, 0, 'C', TRUE);
            $this->fpdf->Ln();


            $this->fpdf->SetFillColor(235, 232, 228);
            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetFont('Arial', '', '10');

            if (!empty($sql)) {
                $fill = FALSE;
                $no = 1;
                $tot = 0;
                $jml_brg = 0;
                foreach ($sql as $retur) {
                    $tot     = $tot + $retur->jml_retur;
                    $tgl     = explode('-', $retur->tgl_simpan);
                    $tgl_tmp = explode('-', $retur->tgl_keluar);
                    $tgl_byr = explode('-', $retur->tgl_bayar);
                    $penj    = $this->db->where('no_nota', $retur->no_nota)->get('tbl_trans_jual')->row();
                    $plgn    = $this->db->where('id', $retur->id_pelanggan)->get('tbl_m_pelanggan')->row();

                    $this->fpdf->Cell(1, .5, $no . '. ', 'LR', 0, 'C', $fill);
                    $this->fpdf->Cell(2, .5, $tgl[1] . '/' . $tgl[2] . '/' . $tgl[0], 'L', 0, 'C', $fill);
                    $this->fpdf->Cell(4, .5, $retur->no_retur, 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(4, .5, $penj->kode_nota_dpn.$retur->no_nota.'/'.$penj->kode_nota_blk, 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(5, .5, $plgn->nama, 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(3, .5, general::format_angka($retur->jml_retur), 'LR', 0, 'R', $fill);
                    $this->fpdf->Ln();

                    $fill = !$fill;
                    $no++;
                }

                $this->fpdf->SetFont('Arial', 'B', '10');
                $this->fpdf->Cell(16, .5, 'Total', 1, 0, 'R', $fill);
                $this->fpdf->Cell(3, .5, general::format_angka($tot), 1, 0, 'R', $fill);
//                $this->fpdf->Ln();
//                $this->fpdf->Cell(15.5, .5, 'Grand Total', 1, 0, 'R', $fill);
//                $this->fpdf->Cell(3.5, .5, general::format_angka($jml_brg * $tot), 1, 0, 'C', $fill);
//                $this->fpdf->Ln();
            } else {

                $this->fpdf->SetFont('Arial', 'B', '11');
                $this->fpdf->SetFillColor(235, 232, 228);
                $this->fpdf->Cell(19, 1, 'Data Kosong', 1, 0, 'C', TRUE);
                $this->fpdf->Ln(10);
            }

            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetY(-2);
            $this->fpdf->SetFont('Arial', 'i', '9');
            $this->fpdf->Cell(9, .75, 'Copyright (c) ' . date('Y') . ' - ' . $setting->judul, 'T', 0, 'L');
            $this->fpdf->Cell(1, .75, $this->fpdf->PageNo(), 'T', 0, 'L');
            $this->fpdf->Cell(9, .75, 'Dicetak pada : ' . $this->tanggalan->tgl_indo(date('Y-m-d')), 'T', 0, 'R');

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $this->fpdf->Output('lap_data_barang_' . date('YmdHm') . '.pdf', $type);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function pdf_data_retur_pembelian(){
        if (akses::aksesLogin() == TRUE) {
            $case  = $this->input->get('case');
            $query = $this->input->get('query');
            $p     = $this->input->get('filter_stok');

            $case  = $this->input->get('case');
            $sales = $this->input->get('sales');
            $tipe  = (!empty($_GET['kategori']) ? $this->input->get('kategori') : '');

            switch ($case){
                case 'per_tanggal':
                        $sql = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id_pelanggan, id_user, no_retur, no_nota, jml_retur, status_retur')
                                        ->where('DATE(tgl_simpan)', $_GET['query'])
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_retur_beli')->result();
                    break;

                case 'per_rentang':
                        $sql = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id_pelanggan, id_user, no_retur, no_nota, jml_retur, status_retur')
                                        ->where('DATE(tgl_simpan) >=', $_GET['tgl_awal'])
                                        ->where('DATE(tgl_simpan) <=', $_GET['tgl_akhir'])
                                        ->order_by('no_nota', 'desc')
                                        ->get('tbl_trans_retur_beli')->result();
                    break;
            }

            $setting = $this->db->get('tbl_pengaturan')->row();

            $judul = "LAPORAN DATA RETUR PEMBELIAN";

            $this->fpdf->FPDF('P', 'cm', 'a4');
            $this->fpdf->SetAutoPageBreak('auto');
            $this->fpdf->SetMargins(1, 1, 1);
            $this->fpdf->AliasNbPages();
            $this->fpdf->AddPage();

            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, strtoupper($setting->judul), '0', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '11');
            $this->fpdf->Cell(19, .5, ucwords($setting->alamat), 'B', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, $judul, 0, 1, 'C');
            $this->fpdf->Ln();


            // Fill Colornya
            $this->fpdf->SetFillColor(211, 223, 227);
            $this->fpdf->SetTextColor(0);
//        $this->fpdf->SetDrawColor(128, 0, 0);
            $this->fpdf->SetFont('Arial', 'B', '10');


//        // Header tabel
            $this->fpdf->Cell(1, .5, 'No', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2, .5, 'Tgl', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(4, .5, 'No. Retur', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(4, .5, 'No. PO', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(5, .5, 'Supplier', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'Nominal', 1, 0, 'C', TRUE);
            $this->fpdf->Ln();


            $this->fpdf->SetFillColor(235, 232, 228);
            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetFont('Arial', '', '10');

            if (!empty($sql)) {
                $fill = FALSE;
                $no = 1;
                $tot = 0;
                $jml_brg = 0;
                foreach ($sql as $retur) {
                    $tot     = $tot + $retur->jml_retur;
                    $tgl     = explode('-', $retur->tgl_simpan);
                    $tgl_tmp = explode('-', $retur->tgl_keluar);
                    $tgl_byr = explode('-', $retur->tgl_bayar);
                    $plgn    = $this->db->where('id', $retur->id_pelanggan)->get('tbl_m_pelanggan')->row();

                    $this->fpdf->Cell(1, .5, $no, 'LR', 0, 'C', $fill);
                    $this->fpdf->Cell(2, .5, $tgl[1] . '/' . $tgl[2] . '/' . $tgl[0], 'L', 0, 'C', $fill);
                    $this->fpdf->Cell(4, .5, $retur->no_retur, 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(4, .5, $retur->no_nota, 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(5, .5, ucwords(strtolower($plgn->nama)), 'L', 0, 'L', $fill);
                    $this->fpdf->Cell(3, .5, general::format_angka($retur->jml_retur), 'LR', 0, 'R', $fill);
                    $this->fpdf->Ln();

                    $fill = !$fill;
                    $no++;
                }

                $this->fpdf->SetFont('Arial', 'B', '10');
                $this->fpdf->Cell(16, .5, 'Total', 1, 0, 'R', $fill);
                $this->fpdf->Cell(3, .5, general::format_angka($tot), 1, 0, 'R', $fill);
//                $this->fpdf->Ln();
//                $this->fpdf->Cell(15.5, .5, 'Grand Total', 1, 0, 'R', $fill);
//                $this->fpdf->Cell(3.5, .5, general::format_angka($jml_brg * $tot), 1, 0, 'C', $fill);
//                $this->fpdf->Ln();
            } else {

                $this->fpdf->SetFont('Arial', 'B', '11');
                $this->fpdf->SetFillColor(235, 232, 228);
                $this->fpdf->Cell(19, 1, 'Data Kosong', 1, 0, 'C', TRUE);
                $this->fpdf->Ln(10);
            }

            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetY(-2);
            $this->fpdf->SetFont('Arial', 'i', '9');
            $this->fpdf->Cell(9, .75, 'Copyright (c) ' . date('Y') . ' - ' . $setting->judul, 'T', 0, 'L');
            $this->fpdf->Cell(1, .75, $this->fpdf->PageNo(), 'T', 0, 'L');
            $this->fpdf->Cell(9, .75, 'Dicetak pada : ' . $this->tanggalan->tgl_indo(date('Y-m-d')), 'T', 0, 'R');

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $this->fpdf->Output('lap_data_barang_' . date('YmdHm') . '.pdf', $type);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function pdf_data_pemasukan(){
        if (akses::aksesLogin() == TRUE) {
            $case   = $this->input->get('case');
            $status = $this->input->get('status');

            switch ($case){
                case 'per_tanggal':
                       $sql = $this->db
                           ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                           ->where('tipe','masuk')
                           ->where('DATE(tgl_simpan)',$this->input->get('query'))
                           ->order_by('id','desc')->get('tbl_akt_kas')->result();
                    break;

                case 'per_rentang':
                       $sql = $this->db
                           ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                           ->where('tipe','masuk')
                           ->where('DATE(tgl_simpan) >=',$this->input->get('tgl_awal'))
                           ->where('DATE(tgl_simpan) =<',$this->input->get('tgl_akhir'))
                           ->order_by('id','desc')->get('tbl_akt_kas')->result();
                    break;
            }

            $setting = $this->db->query("SELECT * FROM tbl_pengaturan")->row();
            $judul = "LAPORAN DATA PEMASUKAN";

            $this->fpdf->FPDF('P', 'cm', 'a4');
            $this->fpdf->SetAutoPageBreak('auto');
            $this->fpdf->SetMargins(1, 1, 1);
            $this->fpdf->AliasNbPages();
            $this->fpdf->AddPage();

            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, strtoupper($setting->judul), '0', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '11');
            $this->fpdf->Cell(19, .5, $setting->alamat, 'B', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, $judul, 0, 1, 'C');
            $this->fpdf->Ln();


            // Fill Colornya
            $this->fpdf->SetFillColor(211, 223, 227);
            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetFont('Arial', 'B', '10');


//        // Header tabel
            $this->fpdf->Cell(1, .5, 'No', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2.5, .5, 'Tgl', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(12.5, .5, 'Keterangan', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'Nominal', 1, 0, 'C', TRUE);
            $this->fpdf->Ln();


            $this->fpdf->SetFillColor(235, 232, 228);
            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetFont('Arial', '', '10');

            if (!empty($sql)) {
                $fill = FALSE;
                $no = 1;
                $tot = 0;
                foreach ($sql as $pemasukan) {
                    $tot = $tot + $pemasukan->nominal;
                    $tgl     = explode('-', $pemasukan->tgl_simpan);

                    $this->fpdf->Cell(1, .5, $no . '. ', 1, 0, 'C', $fill);
                    $this->fpdf->Cell(2.5, .5,$tgl[1] . '/' . $tgl[2] . '/' . $tgl[0], 1, 0, 'C', $fill);
                    $this->fpdf->Cell(12.5, .5, $pemasukan->keterangan, 1, 0, 'L', $fill);
                    $this->fpdf->Cell(3, .5, general::format_angka($pemasukan->nominal), 1, 0, 'R', $fill);
                    $this->fpdf->Ln();

                    $fill = !$fill;
                    $no++;
                }
                $this->fpdf->SetFont('Arial', 'B', '11');
                $this->fpdf->Cell(16, .5, 'Total', 1, 0, 'R');
                $this->fpdf->Cell(3, .5, general::format_angka($tot), 1, 0, 'R');
            } else {

                $this->fpdf->SetFont('Arial', 'B', '11');
                $this->fpdf->SetFillColor(235, 232, 228);
                $this->fpdf->Cell(19, 1, 'Data Kosong', 1, 0, 'C', TRUE);
                $this->fpdf->Ln(10);
            }

            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetY(-2);
            $this->fpdf->SetFont('Arial', 'i', '9');
            $this->fpdf->Cell(9, .75, 'Copyright (c) ' . date('Y') . ' - ' . $setting->judul, 'T', 0, 'L');
            $this->fpdf->Cell(1, .75, $this->fpdf->PageNo(), 'T', 0, 'L');
            $this->fpdf->Cell(9, .75, 'Dicetak pada : ' . $this->tanggalan->tgl_indo(date('Y-m-d')), 'T', 0, 'R');

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $this->fpdf->Output('lap_data_pemasukan_' . date('YmdHm') . '.pdf', $type);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_pemasukan(){
        if (akses::aksesLogin() == TRUE) {
            $case   = $this->input->get('case');
            $status = $this->input->get('status');

            switch ($case){
                case 'per_tanggal':
                       $sql = $this->db
                           ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                           ->where('tipe','masuk')
                           ->where('DATE(tgl_simpan)',$this->input->get('query'))
                           ->order_by('id','desc')->get('tbl_akt_kas')->result();
                    break;

                case 'per_rentang':
                       $sql = $this->db
                           ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                           ->where('tipe','masuk')
                           ->where('DATE(tgl_simpan) >=',$this->input->get('tgl_awal'))
                           ->where('DATE(tgl_simpan) <=',$this->input->get('tgl_akhir'))
                           ->order_by('id','desc')->get('tbl_akt_kas')->result();
                    break;
            }

            $pengaturan = $this->db->query("SELECT * FROM tbl_pengaturan")->row();
            $judul = "LAPORAN DATA PEMASUKAN";

            $objPHPExcel = new PHPExcel();

            // Header Laporan
            $objPHPExcel->getActiveSheet()->getStyle("A1:D1")->getFont()->setSize(16);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', $pengaturan->judul)->mergeCells('A1:D1');

            $objPHPExcel->getActiveSheet()->getStyle("A2:D2")->getFont()->setSize(13);
            $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFont()->setBold(TRUE);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A2', $pengaturan->alamat)->mergeCells('A2:D2');

            $objPHPExcel->getActiveSheet()->getStyle("A3:D3")->getFont()->setSize(13);
            $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFont()->setBold(TRUE);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3', $judul.' '.$ket)->mergeCells('A3:D3');

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A5:D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A5:D5')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A5', 'No.')
                        ->setCellValue('B5', 'Tgl')
                        ->setCellValue('C5', 'Keterangan')
                        ->setCellValue('D5', 'Nominal');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

            if(!empty($sql)){
                $no = 1;
                $cell  = 6;
                $total = 0;
                foreach ($sql as $penjualan) {
                    $jns    = $this->db->where('id', $penjualan->id_jenis)->get('tbl_akt_kas_jns')->row();
                    $total  = $total + $penjualan->nominal;

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$cell, $val,PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");


                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo($penjualan->tgl_simpan).' ')
                            ->setCellValue('C'.$cell, $penjualan->keterangan)
                            ->setCellValue('D'.$cell, $penjualan->nominal);

                    $no++;
                    $cell++;
                }

                $sell1     = $cell;

                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':E'.$sell1.'')->getFont()->setBold(TRUE);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':E'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sell1, 'TOTAL')->mergeCells('A'.$sell1.':C'.$sell1.'')
                        ->setCellValue('D' . $sell1, general::format_angka($total) . ' ');
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Pemasukan');

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
            $objPHPExcel->getProperties()->setCreator("MIKHAEL FELIAN <mikhaelfelian@gmail.com>;")
                    ->setLastModifiedBy(strtoupper($this->ion_auth->user()->row()->first_name))
                    ->setTitle("Lap Pemasukan")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id atau hubungi 085741220427 untuk info lebih lanjut.")
                    ->setKeywords(strtoupper($pengaturan->judul))
                    ->setCategory("PHPExcel exported apps");

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_pemasukan_'.date('ymd').'.xlsx"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            ob_clean();
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function pdf_data_pengeluaran(){
        if (akses::aksesLogin() == TRUE) {
            $case   = $this->input->get('case');
            $status = $this->input->get('status');

            if(akses::hakOwner2() == TRUE OR akses::hakAdmin() == TRUE){
                switch ($case){
                    case 'per_tanggal':
                           $sql = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->not_like('keterangan','Pembelian')
                               ->where('tipe','keluar')
                               ->where('DATE(tgl_simpan)',$this->input->get('query'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;

                    case 'per_rentang':
                           $sql = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->not_like('keterangan','Pembelian')
                               ->where('tipe','keluar')
                               ->where('DATE(tgl_simpan) >=',$this->input->get('tgl_awal'))
                               ->where('DATE(tgl_simpan) <=',$this->input->get('tgl_akhir'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;

                    default:
                           $sql = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->not_like('keterangan','Pembelian')
                               ->where('tipe','keluar')
                               ->like('keterangan',$this->input->get('query'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;
                }
            }else{
                switch ($case){
                    case 'per_tanggal':
                           $sql = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->where('tipe','keluar')
                               ->where('DATE(tgl_simpan)',$this->input->get('query'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;

                    case 'per_rentang':
                           $sql = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->where('tipe','keluar')
                               ->where('DATE(tgl_simpan) >=',$this->input->get('tgl_awal'))
                               ->where('DATE(tgl_simpan) <=',$this->input->get('tgl_akhir'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;

                    default:
                           $sql = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->where('tipe','keluar')
                               ->like('keterangan',$this->input->get('query'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;
                }
            }

            $setting = $this->db->query("SELECT * FROM tbl_pengaturan")->row();
            $judul = "LAPORAN DATA PENGELUARAN";

            $this->fpdf->FPDF('P', 'cm', 'a4');
            $this->fpdf->SetAutoPageBreak('auto');
            $this->fpdf->SetMargins(1, 1, 1);
            $this->fpdf->AliasNbPages();
            $this->fpdf->AddPage();

            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, $setting->judul, '0', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '11');
            $this->fpdf->Cell(19, .5, $setting->alamat, 'B', 1, 'C');
            $this->fpdf->Ln(0);
            $this->fpdf->SetFont('Arial', 'B', '14');
            $this->fpdf->Cell(19, .75, $judul, 0, 1, 'C');
            $this->fpdf->Ln();


            // Fill Colornya
            $this->fpdf->SetFillColor(211, 223, 227);
            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetFont('Arial', 'B', '10');


//        // Header tabel
            $this->fpdf->Cell(1, .5, 'No', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2.5, .5, 'Tgl', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(7.5, .5, 'Keterangan', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(5, .5, 'Jenis', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(3, .5, 'Nominal', 1, 0, 'C', TRUE);
            $this->fpdf->Ln();


            $this->fpdf->SetFillColor(235, 232, 228);
            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetFont('Arial', '', '10');

            if (!empty($sql)) {
                $fill = FALSE;
                $no = 1;
                $tot = 0;
                foreach ($sql as $pemasukan) {
                    $tot = $tot + $pemasukan->nominal;
                    $tgl = explode('-', $pemasukan->tgl_simpan);
                    $jns = $this->db->where('id', $pemasukan->id_jenis)->get('tbl_akt_kas_jns')->row();

                    $this->fpdf->Cell(1, .5, $no . '. ', 1, 0, 'C', $fill);
                    $this->fpdf->Cell(2.5, .5,$tgl[1] . '/' . $tgl[2] . '/' . $tgl[0], 1, 0, 'C', $fill);
                    $this->fpdf->Cell(7.5, .5, $pemasukan->keterangan, 1, 0, 'L', $fill);
                    $this->fpdf->Cell(5, .5, ($pemasukan->id_jenis == 0 ? '-' : $jns->jenis), 1, 0, 'L', $fill);
                    $this->fpdf->Cell(3, .5, general::format_angka($pemasukan->nominal), 1, 0, 'R', $fill);
                    $this->fpdf->Ln();

                    $fill = !$fill;
                    $no++;
                }
                $this->fpdf->SetFont('Arial', 'B', '11');
                $this->fpdf->Cell(16, .5, 'Total', 1, 0, 'R');
                $this->fpdf->Cell(3, .5, general::format_angka($tot), 1, 0, 'R');
            } else {

                $this->fpdf->SetFont('Arial', 'B', '11');
                $this->fpdf->SetFillColor(235, 232, 228);
                $this->fpdf->Cell(19, 1, 'Data Kosong', 1, 0, 'C', TRUE);
                $this->fpdf->Ln(10);
            }

            $this->fpdf->SetTextColor(0);
            $this->fpdf->SetY(-2);
            $this->fpdf->SetFont('Arial', 'i', '9');
            $this->fpdf->Cell(9, .75, 'Copyright (c) ' . date('Y') . ' - ' . $setting->judul, 'T', 0, 'L');
            $this->fpdf->Cell(1, .75, $this->fpdf->PageNo(), 'T', 0, 'L');
            $this->fpdf->Cell(9, .75, 'Dicetak pada : ' . $this->tanggalan->tgl_indo(date('Y-m-d')), 'T', 0, 'R');

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $this->fpdf->Output('lap_data_pengeluaran_' . date('YmdHm') . '.pdf', $type);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_pengeluaran(){
        if (akses::aksesLogin() == TRUE) {
            $case   = $this->input->get('case');
            $status = $this->input->get('status');

            if(akses::hakOwner2() == TRUE OR akses::hakAdminM() == TRUE OR akses::hakAdmin() == TRUE){
                switch ($case){
                    case 'per_tanggal':
                           $sql = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->not_like('keterangan','Pembelian')
                               ->where('tipe','keluar')
                               ->where('DATE(tgl_simpan)',$this->input->get('query'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;

                    case 'per_rentang':
                           $sql = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->not_like('keterangan','Pembelian')
                               ->where('tipe','keluar')
                               ->where('DATE(tgl_simpan) >=',$this->input->get('tgl_awal'))
                               ->where('DATE(tgl_simpan) <=',$this->input->get('tgl_akhir'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;

                    default:
                           $sql = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->not_like('keterangan','Pembelian')
                               ->where('tipe','keluar')
                               ->like('keterangan',$this->input->get('query'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;
                }
            }else{
                switch ($case){
                    case 'per_tanggal':
                           $sql = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->where('tipe','keluar')
                               ->where('DATE(tgl_simpan)',$this->input->get('query'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;

                    case 'per_rentang':
                           $sql = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->where('tipe','keluar')
                               ->where('DATE(tgl_simpan) >=',$this->input->get('tgl_awal'))
                               ->where('DATE(tgl_simpan) <=',$this->input->get('tgl_akhir'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;

                    default:
                           $sql = $this->db
                               ->select('id_jenis, kode, DATE(tgl_simpan) as tgl_simpan, keterangan, jumlah, nominal, tipe, status_kas')
                               ->where('tipe','keluar')
                               ->like('keterangan',$this->input->get('query'))
                               ->order_by('id','desc')->get('tbl_akt_kas')->result();
                        break;
                }
            }

            $pengaturan = $this->db->query("SELECT * FROM tbl_pengaturan")->row();
            $judul = "LAPORAN DATA PENGELUARAN";

            $objPHPExcel = new PHPExcel();

            // Header Laporan
            $objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFont()->setSize(16);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', $pengaturan->judul)->mergeCells('A1:E1');

            $objPHPExcel->getActiveSheet()->getStyle("A2:E2")->getFont()->setSize(13);
            $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFont()->setBold(TRUE);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A2', $pengaturan->alamat)->mergeCells('A2:E2');

            $objPHPExcel->getActiveSheet()->getStyle("A3:E3")->getFont()->setSize(13);
            $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFont()->setBold(TRUE);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3', $judul.' '.$ket)->mergeCells('A3:E3');

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A5:E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A5:E5')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A5', 'No.')
                        ->setCellValue('B5', 'Tgl')
                        ->setCellValue('C5', 'Keterangan')
                        ->setCellValue('D5', 'Jenis')
                        ->setCellValue('E5', 'Nominal');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);

            if(!empty($sql)){
                $no = 1;
                $cell  = 6;
                $total = 0;
                foreach ($sql as $penjualan) {
                    $jns    = $this->db->where('id', $penjualan->id_jenis)->get('tbl_akt_kas_jns')->row();
                    $total  = $total + $penjualan->nominal;

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$cell.':E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                    $objPHPExcel->getActiveSheet()->getStyle('F'.$cell.':G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$cell, $val,PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");


                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo($penjualan->tgl_simpan).' ')
                            ->setCellValue('C'.$cell, $penjualan->keterangan)
                            ->setCellValue('D'.$cell, $jns->jenis)
                            ->setCellValue('E'.$cell, $penjualan->nominal);

                    $no++;
                    $cell++;
                }

                $sell1     = $cell;

                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':E'.$sell1.'')->getFont()->setBold(TRUE);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':E'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sell1, 'TOTAL')->mergeCells('A'.$sell1.':D'.$sell1.'')
                        ->setCellValue('E' . $sell1, general::format_angka($total) . ' ');
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Lap Pengeluaran');

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
            $objPHPExcel->getProperties()->setCreator("MIKHAEL FELIAN <mikhaelfelian@gmail.com>;")
                    ->setLastModifiedBy(strtoupper($this->ion_auth->user()->row()->first_name))
                    ->setTitle("Lap Pengeluaran")
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://tigerasoft.co.id atau hubungi 085741220427 untuk info lebih lanjut.")
                    ->setKeywords(strtoupper($pengaturan->judul))
                    ->setCategory("PHPExcel exported apps");

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_pengeluaran_'.date('ymd').'.xlsx"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            ob_clean();
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }


    public function ex_data_lr(){
        if (akses::aksesLogin() == TRUE) {
            $case   = $this->input->get('case');
            $status = $this->input->get('status');
            $query  = $this->input->get('query');
            $tgl_awal    = $this->input->get('tgl_awal');
            $tgl_akhir   = $this->input->get('tgl_akhir');

            switch ($case){
                case 'per_tanggal':
                       $persediaan     = $this->db->select('SUM(nominal) as nominal')->where('DATE(tgl_simpan)', $query)->get('tbl_akt_persediaan')->row();
                       $pembelian      = $this->db->select('SUM(jml_gtotal) as jml_gtotal, SUM(jml_diskon) as jml_diskon, SUM(jml_potongan) as jml_potongan, SUM(jml_retur) as jml_retur')->where('DATE(tgl_simpan)', $query)->where('status_bayar', '1')->get('tbl_trans_beli')->row();
                       $penjualan      = $this->db->select('SUM(jml_gtotal) as jml_subtotal')->where('DATE(tgl_simpan)', $query)->get('tbl_trans_jual')->row();
                       $retur_penj     = $this->db->select('SUM(nominal) as jml_total')->where('status', '1')->where('DATE(tgl_simpan)', $query)->get('tbl_akt_retur')->row();
                       $biaya          = $this->db->select('SUM(nominal) as nominal')->where('DATE(tgl_simpan)', $query)->where('id_jenis !=', '0')->get('tbl_akt_kas')->row();

                       $jml_pembelian  = $pembelian->jml_gtotal;
                       $jml_penjualan  = $penjualan->jml_subtotal;
                       $jml_retur_penj = $retur_penj->jml_total;
                       $jml_biaya      = $biaya->nominal;
                       $tot_pemb       = $jml_pembelian;
                       $tot_penj       = $jml_penjualan - $retur_penj;
                       $lr             = $tot_penj - ($jml_pembelian + $jml_biaya + $persediaan->nominal);
//                       $lr             = $jml_penjualan - $jml_retur_penj - $jml_biaya - ($persediaan->nominal + $jml_pembelian);
                    break;

                case 'per_rentang':
                       $persediaan     = $this->db->select('SUM(nominal) as nominal')->where('DATE(tgl_simpan) >=', $tgl_awal)->where('DATE(tgl_simpan) <=', $tgl_akhir)->get('tbl_akt_persediaan')->row();
                       $pembelian      = $this->db->select('SUM(jml_gtotal) as jml_gtotal, SUM(jml_diskon) as jml_diskon, SUM(jml_potongan) as jml_potongan, SUM(jml_retur) as jml_retur')->where('DATE(tgl_simpan) >=', $tgl_awal)->where('DATE(tgl_simpan) <=', $tgl_akhir)->where('status_bayar', '1')->get('tbl_trans_beli')->row();
                       $penjualan      = $this->db->select('SUM(jml_gtotal) as jml_subtotal')->where('DATE(tgl_simpan) >=', $tgl_awal)->where('DATE(tgl_simpan) <=', $tgl_akhir)->get('tbl_trans_jual')->row();
                       $retur_penj     = $this->db->select('SUM(nominal) as jml_total')->where('status', '1')->where('DATE(tgl_simpan) >=', $tgl_awal)->where('DATE(tgl_simpan) <=', $tgl_akhir)->get('tbl_akt_retur')->row();
                       $biaya          = $this->db->select('SUM(nominal) as nominal')->where('DATE(tgl_simpan) >=', $tgl_awal)->where('DATE(tgl_simpan) <=', $tgl_akhir)->where('id_jenis !=', '0')->get('tbl_akt_kas')->row();

                       $jml_pembelian  = $pembelian->jml_gtotal;
                       $jml_penjualan  = $penjualan->jml_subtotal;
                       $jml_retur_penj = $retur_penj->jml_total;
                       $jml_biaya      = $biaya->nominal;
                       $tot_pemb       = $jml_pembelian + $persediaan->nominal;
                       $tot_penj       = $jml_penjualan - $retur_penj;
                       $lr             = $tot_penj - ($jml_pembelian + $jml_biaya + $persediaan->nominal);
//                       $lr             = ($tot_penj - $tot_pemb - $jml_biaya);
//                       $lr             = $jml_penjualan - $jml_retur_penj - $jml_biaya - ($persediaan->nominal + $jml_pembelian);
                    break;
            }

            $objPHPExcel = new PHPExcel();

            // Font size nota
            $objPHPExcel->getActiveSheet()->getStyle('B1:B6')->getFont()->setSize('11')->setName('Times New Roman');
            $objPHPExcel->getActiveSheet()->getStyle('A1:A7')->getFont()->setSize('11')->setName('Times New Roman')->setBold(TRUE);
           // border atas, nama kolom
            $objPHPExcel->getActiveSheet()->getStyle('A7:H7')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);

            $objPHPExcel->getActiveSheet()->getStyle('A1:A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('B1:B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('B1:B7')->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");


            /* CONTENT EXCEL */
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'PERSEDIAAN AWAL =')
                    ->setCellValue('B1', $persediaan->nominal);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A2', 'PEMBELIAN =')
                    ->setCellValue('B2', $pembelian->jml_gtotal);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3', 'PENJUALAN =')
                    ->setCellValue('B3', $penjualan->jml_subtotal);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'RETUR PENJUALAN =')
                    ->setCellValue('B4', number_format($retur_penj->jml_total, 0, ',','.').' - '.number_format($retur_penj->jml_diskon, 0, ',','.'));
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A5', 'BIAYA =')
                    ->setCellValue('B5', $biaya->nominal);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A6', 'LR =')
                    ->setCellValue('B6', $lr);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A8', $ket);

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(22);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);


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
//                    ->setLastModifiedBy("" . ucwords($createBy) . ' [' . strtoupper($namaPerusahaan) . ']')
//                    ->setTitle("Nota Penjualan " . $sql->row()->no_nota . ($sql->row()->cetak == '1' ? ' Copy Customer' : ''))
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://mikhaelfelian.web.id")
                    ->setKeywords("POS")
                    ->setCategory("Untuk mencetak nota dot matrix");



            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="data_lr.xls"');

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





    public function set_lap_persediaan(){
        if (akses::aksesLogin() == TRUE) {
            $all         = $this->input->post('semua');
            $tgl         = $this->input->post('tgl');
            $tgl_rentang = $this->input->post('tgl_rentang');
            $stok        = $this->input->post('stok');
            $param       = $this->input->post('param');

            $rentang     = explode('-', $tgl_rentang);
            $t_awal      = explode('/', str_replace(' ','',$rentang[0]));
            $t_akhir     = explode('/', str_replace(' ','',$rentang[1]));
            $tgl_awal    = $t_awal[2].'-'.$t_awal[0].'-'.$t_awal[1];
            $tgl_akhir   = $t_akhir[2].'-'.$t_akhir[0].'-'.$t_akhir[1];

            $tgl_ini     = (!empty($tgl) ? $tgl : date('m/d/Y'));
            $dino_iki    = explode('/', $tgl_ini);
            $tgl_skrg    = $dino_iki[2].'-'.$dino_iki[0].'-'.$dino_iki[1];

//            redirect(base_url('laporan/data_persediaan.php?filter_tgl='.$tgl_skrg.'&filter_'));

            if(!empty($tgl)){
                redirect(base_url('laporan/data_persediaan.php?case=per_tanggal&query='.$tgl_skrg));
            }elseif(!empty ($all)){
                redirect(base_url('laporan/data_persediaan.php?case=semua'));
            }elseif(!empty ($tgl_rentang)){
                redirect(base_url('laporan/data_persediaan.php?case=per_rentang&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir));
            }elseif(isset($stok)){
                redirect(base_url('laporan/data_persediaan.php?case=per_stok&query='.$stok.'&filter_stok='.$param));
            }else{
                redirect(base_url('laporan/data_persediaan.php'));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_lap_stok(){
        if (akses::aksesLogin() == TRUE) {
            $bulan = $this->input->post('bulan');
            $tahun = $this->input->post('tahun');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('bulan', 'Bulan', 'required');
            $this->form_validation->set_rules('tahun', 'Tahun', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'bulan'     => form_error('bulan'),
                    'tahun'     => form_error('tahun'),
                );

                $this->session->set_flashdata('form_error', $msg_error);
                redirect(base_url('laporan/data_stok.php'));
            } else {
                redirect(base_url('laporan/data_stok.php?bulan='.$bulan.'&tahun='.$tahun));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_lap_penjualan(){
        if (akses::aksesLogin() == TRUE) {
            $all         = $this->input->post('semua');
            $hari_ini    = $this->input->post('hari_ini');
            $tgl         = $this->input->post('tgl');
            $tgl_rentang = $this->input->post('tgl_rentang');
            $sales       = $this->input->post('sales');
            $kategori    = $this->input->post('kategori');
            $metode      = $this->input->post('metode_pemb');

            $rentang     = explode('-', $tgl_rentang);
            $t_awal      = explode('/', str_replace(' ','',$rentang[0]));
            $t_akhir     = explode('/', str_replace(' ','',$rentang[1]));
            $tgl_awal    = $t_awal[2].'-'.$t_awal[0].'-'.$t_awal[1];
            $tgl_akhir   = $t_akhir[2].'-'.$t_akhir[0].'-'.$t_akhir[1];

            $tgl_ini     = (!empty($tgl) ? $tgl : $hari_ini);
            $dino_iki    = explode('/', $tgl_ini);
            $tgl_skrg    = $dino_iki[2].'-'.$dino_iki[0].'-'.$dino_iki[1];

            if(!empty($all)){
                redirect(base_url('laporan/data_penjualan.php?case=semua&kategori='.$kategori));

            }elseif($tgl_skrg != '--'){
                redirect(base_url('laporan/data_penjualan.php?case=per_tanggal&query='.$tgl_skrg.'&kategori='.$kategori.(!empty($sales) ? '&id_sales='.$sales : '').'&metode='.$metode));
            }elseif(!empty($tgl_rentang)){
                redirect(base_url('laporan/data_penjualan.php?case=per_rentang&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.(!empty($sales) ? '&id_sales='.$sales : '').'&metode='.$metode));
            }else{
                redirect(base_url('laporan/data_penjualan.php'));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_lap_mutasi(){
        if (akses::aksesLogin() == TRUE) {
            $tgl         = $this->input->post('tgl');
            $tgl_rentang = $this->input->post('tgl_rentang');
            $sales       = $this->input->post('sales');

            $rentang     = explode('-', $tgl_rentang);
            $tgl_awal    = $this->tanggalan->tgl_indo_sys($rentang[0]);
            $tgl_akhir   = $this->tanggalan->tgl_indo_sys($rentang[1]);
            $tgl_skrg    = $this->tanggalan->tgl_indo_sys($tgl);

            if (empty($tgl_skrg) AND empty($tgl_rentang) AND empty($sales)){
                $this->session->set_flashdata('laporan', '<div class="alert alert-danger">Kolom <b>Tanggal</b> dan <b>Tanggal Rentang</b> serta <b>Petugas</b> tidak boleh kosong semua</div>');
                redirect(base_url('laporan/data_mutasi.php'));

            }elseif (!empty($tgl_skrg) AND !empty($tgl_rentang)) {
                $this->session->set_flashdata('laporan', '<div class="alert alert-danger">Kolom <b>Tanggal</b> dan <b>Tanggal Rentang</b> tidak boleh diisi semua</div>');
                redirect(base_url('laporan/data_mutasi.php'));

            }elseif (empty($sales)) {
                $this->session->set_flashdata('laporan', '<div class="alert alert-danger">Kolom <b>Petugas</b> tidak boleh kosong</div>');
                redirect(base_url('laporan/data_mutasi.php'));

            }else{
                if(!empty($tgl_skrg)){
                   $sql = $this->db
                                    ->select('tbl_trans_mutasi.id, tbl_trans_mutasi.tipe, tbl_trans_mutasi.id_gd_asal, tbl_trans_mutasi.id_gd_tujuan, tbl_trans_mutasi.id_user, tbl_trans_mutasi.no_nota, tbl_trans_mutasi.tgl_masuk, tbl_trans_mutasi.keterangan, tbl_trans_mutasi_det.kode, tbl_trans_mutasi_det.produk, tbl_trans_mutasi_det.jml, tbl_trans_mutasi_det.jml_satuan, tbl_trans_mutasi_det.satuan')
                                    ->join('tbl_trans_mutasi', 'tbl_trans_mutasi.id=tbl_trans_mutasi_det.id_mutasi')
                                    ->where('tbl_trans_mutasi.id_user', $sales)
                                    ->where('DATE(tbl_trans_mutasi.tgl_masuk)', $tgl_skrg)
                                    ->get('tbl_trans_mutasi_det')->num_rows();
                   redirect(base_url('laporan/data_mutasi.php?case=per_tanggal&query='.$tgl_skrg.'&id_user='.$sales.(!empty($sql) ? '&jml_hal='.$sql : '')));
                }else{
                   $sql = $this->db
                                    ->select('tbl_trans_mutasi.id, tbl_trans_mutasi.tipe, tbl_trans_mutasi.id_gd_asal, tbl_trans_mutasi.id_gd_tujuan, tbl_trans_mutasi.id_user, tbl_trans_mutasi.no_nota, tbl_trans_mutasi.tgl_masuk, tbl_trans_mutasi.keterangan, tbl_trans_mutasi_det.kode, tbl_trans_mutasi_det.produk, tbl_trans_mutasi_det.jml, tbl_trans_mutasi_det.jml_satuan, tbl_trans_mutasi_det.satuan')
                                    ->join('tbl_trans_mutasi', 'tbl_trans_mutasi.id=tbl_trans_mutasi_det.id_mutasi')
                                    ->where('tbl_trans_mutasi.id_user', $sales)
                                    ->where('DATE(tbl_trans_mutasi.tgl_masuk) >=', $tgl_awal)
                                    ->where('DATE(tbl_trans_mutasi.tgl_masuk) <=', $tgl_akhir)
                                    ->get('tbl_trans_mutasi_det')->num_rows();
                   redirect(base_url('laporan/data_mutasi.php?case=per_rentang&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&id_user='.$sales.(!empty($sql) ? '&jml_hal='.$sql : '')));
                }
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_lap_piutang(){
        if (akses::aksesLogin() == TRUE) {
            $all         = $this->input->post('semua');
            $hari_ini    = $this->input->post('hari_ini');
            $tgl         = $this->input->post('tgl');
            $tgl_rentang = $this->input->post('tgl_rentang');
            $sales       = $this->input->post('sales');
            $kategori    = $this->input->post('kategori');
            $metode      = $this->input->post('metode_pemb');
            $sb          = $this->input->post('status_bayar');

            $rentang     = explode('-', $tgl_rentang);
            $t_awal      = explode('/', str_replace(' ','',$rentang[0]));
            $t_akhir     = explode('/', str_replace(' ','',$rentang[1]));
            $tgl_awal    = $t_awal[2].'-'.$t_awal[0].'-'.$t_awal[1];
            $tgl_akhir   = $t_akhir[2].'-'.$t_akhir[0].'-'.$t_akhir[1];

            $tgl_ini     = (!empty($tgl) ? $tgl : $hari_ini);
            $dino_iki    = explode('/', $tgl_ini);
            $tgl_skrg    = $dino_iki[2].'-'.$dino_iki[0].'-'.$dino_iki[1];

            if(!empty($all)){
                redirect(base_url('laporan/data_piutang.php?case=semua&kategori='.$kategori));

            }elseif($tgl_skrg != '--'){
                redirect(base_url('laporan/data_piutang.php?case=per_tanggal&query='.$tgl_skrg.'&kategori='.$kategori.(!empty($sales) ? '&id_sales='.$sales : '').'&metode='.$metode));
            }elseif(!empty($tgl_rentang)){
                redirect(base_url('laporan/data_piutang.php?case=per_rentang&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.(!empty($sales) ? '&id_sales='.$sales : '').'&metode='.$metode));
            }else{
                redirect(base_url('laporan/data_piutang.php'));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_lap_penjualan_prod(){
        if (akses::aksesLogin() == TRUE) {
            $tipe        = $this->input->post('tipe');
            $tgl         = $this->input->post('tgl');
            $tgl_rentang = $this->input->post('tgl_rentang');
            $merk        = $this->input->post('merk');
            $produk      = $this->input->post('produk');
            $produkid    = $this->input->post('id_produk');
            $kasir       = $this->input->post('kasir');

            $rentang     = explode('-', $tgl_rentang);
            $tgl_awal    = $this->tanggalan->tgl_indo_sys($rentang[0]);
            $tgl_akhir   = $this->tanggalan->tgl_indo_sys($rentang[1]);

            $tgl_skrg    = $this->tanggalan->tgl_indo_sys($tgl);

            switch ($tipe){
                case '1':
                    if (empty($tgl_skrg) AND empty($tgl_rentang)){
                        $this->session->set_flashdata('laporan', '<div class="alert alert-danger">Kolom <b>Tanggal</b> dan <b>Tanggal Rentang</b> tidak boleh kosong semua</div>');
                        redirect(base_url('laporan/data_penjualan_prod.php'));

                    }elseif (!empty($tgl_skrg) AND !empty($tgl_rentang)) {
                        $this->session->set_flashdata('laporan', '<div class="alert alert-danger">Kolom <b>Tanggal</b> dan <b>Tanggal Rentang</b> tidak boleh diisi semua</div>');
                        redirect(base_url('laporan/data_penjualan_prod.php'));

                    }else{
                        if(!empty($tgl_skrg)){
                           redirect(base_url('laporan/data_penjualan_prod.php?case=per_tanggal&tipe='.$tipe.'&query='.$tgl_skrg.'&merk='.$merk.'&sales='.$kasir));
                        }else{
                           redirect(base_url('laporan/data_penjualan_prod.php?case=per_rentang&tipe='.$tipe.'&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&merk='.$merk.'&sales='.$kasir));
                        }
                    }
                    break;

                case '2':
                    if (empty($tgl_skrg) AND empty($tgl_rentang)){
                        $this->session->set_flashdata('laporan', '<div class="alert alert-danger">Kolom <b>Tanggal</b> dan <b>Tanggal Rentang</b> tidak boleh kosong semua</div>');
                        redirect(base_url('laporan/data_penjualan_prod.php'));

                    }elseif (!empty($tgl_skrg) AND !empty($tgl_rentang)) {
                        $this->session->set_flashdata('laporan', '<div class="alert alert-danger">Kolom <b>Tanggal</b> dan <b>Tanggal Rentang</b> tidak boleh diisi semua</div>');
                        redirect(base_url('laporan/data_penjualan_prod.php'));

                    }else{
                        if(!empty($tgl_skrg)){
                           redirect(base_url('laporan/data_penjualan_prod.php?case=per_tanggal&tipe='.$tipe.'&query='.$tgl_skrg.'&id_produk='.$produkid.'&sales='.$kasir));
                        }else{
                           redirect(base_url('laporan/data_penjualan_prod.php?case=per_rentang&tipe='.$tipe.'&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&id_produk='.$produkid.'&sales='.$kasir));
                        }
                    }
                    break;
            }

//            echo '<pre>';
//            print_r($_POST);
//
//            if(!empty($all)){
//                redirect(base_url('laporan/data_penjualan_prod.php?case=semua&kategori='.$kategori));
//
//            }elseif(!empty($sales)){
//                redirect('page=laporan&act=data_penjualan_prod&case=sales&query='.$sales.'&tipe='.$tipe);
//
//            }elseif($tgl_skrg != '--'){
//                redirect(base_url('laporan/data_penjualan_prod.php?case=per_tanggal&query='.$tgl_skrg.'&merk='.$merk));
//
//            }elseif(!empty($tgl_rentang)){
//                redirect(base_url('laporan/data_penjualan_prod.php?case=per_rentang&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&merk='.$merk));
//
//            }else{
//                redirect(base_url('laporan/data_penjualan_prod.php'));
//            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_lap_penjualan_kasir(){
        if (akses::aksesLogin() == TRUE) {
            $tgl_rentang = $this->input->post('tgl_rentang');
            $met_bayar   = $this->input->post('metode_bayar');

            $rentang     = explode('-', $tgl_rentang);
            $t_awal      = explode('/', str_replace(' ','',$rentang[0]));
            $t_akhir     = explode('/', str_replace(' ','',$rentang[1]));
            $tgl_awal    = $t_awal[2].'-'.$t_awal[0].'-'.$t_awal[1];
            $tgl_akhir   = $t_akhir[2].'-'.$t_akhir[0].'-'.$t_akhir[1];

            $tgl_ini     = (!empty($tgl) ? $tgl : $hari_ini);
            $dino_iki    = explode('/', $tgl_ini);
            $tgl_skrg    = $dino_iki[2].'-'.$dino_iki[0].'-'.$dino_iki[1];

            if(!empty($tgl_rentang)){
                redirect(base_url('laporan/data_penjualan_kasir.php?case=per_rentang&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&tipe='.$tipe.(!empty($met_bayar) ? '&metode='.$met_bayar : '')));

            }else{
                redirect(base_url('laporan/data_penjualan.php'));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_lap_penjualan_produk(){
        if (akses::aksesLogin() == TRUE) {
            $tgl_rentang = $this->input->post('tgl_rentang');
            $merk        = $this->input->post('merk');

            $rentang     = explode('-', $tgl_rentang);
            $t_awal      = explode('/', str_replace(' ','',$rentang[0]));
            $t_akhir     = explode('/', str_replace(' ','',$rentang[1]));
            $tgl_awal    = $t_awal[2].'-'.$t_awal[0].'-'.$t_awal[1];
            $tgl_akhir   = $t_akhir[2].'-'.$t_akhir[0].'-'.$t_akhir[1];

            $tgl_ini     = (!empty($tgl) ? $tgl : $hari_ini);
            $dino_iki    = explode('/', $tgl_ini);
            $tgl_skrg    = $dino_iki[2].'-'.$dino_iki[0].'-'.$dino_iki[1];

            if(!empty($tgl_rentang)){
                redirect(base_url('laporan/data_penjualan_produk.php?case=per_rentang&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&tipe='.$merk));
            }else{
                redirect(base_url('laporan/data_penjualan_produk.php'));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_lap_pembelian(){
        if (akses::aksesLogin() == TRUE) {
            $all         = $this->input->post('semua');
            $hari_ini    = $this->input->post('hari_ini');
            $tgl         = $this->input->post('tgl');
            $tgl_rentang = $this->input->post('tgl_rentang');
            $sales       = $this->input->post('sales');
            $kategori    = $this->input->post('kategori');
            $sb          = $this->input->post('status_bayar');
            $ht          = $this->input->post('status_hutang');
            $ppn         = $this->input->post('status_ppn');

            $rentang     = explode('-', $tgl_rentang);
            $t_awal      = explode('/', str_replace(' ','',$rentang[0]));
            $t_akhir     = explode('/', str_replace(' ','',$rentang[1]));
            $tgl_awal    = $t_awal[2].'-'.$t_awal[0].'-'.$t_awal[1];
            $tgl_akhir   = $t_akhir[2].'-'.$t_akhir[0].'-'.$t_akhir[1];

            $tgl_ini     = (!empty($tgl) ? $tgl : $hari_ini);
            $dino_iki    = explode('/', $tgl_ini);
            $tgl_skrg    = $dino_iki[2].'-'.$dino_iki[0].'-'.$dino_iki[1];

            if($tgl_skrg != '--'){
                redirect(base_url('laporan/data_pembelian.php?case=per_tanggal&query='.$tgl_skrg.'&kategori='.$kategori.(isset($sb) ? '&sb='.$sb : '').($ht == '1' ? '&hutang=1' : '').($ppn != 'x' ? '&status_ppn='.$ppn : '')));

            }elseif(!empty($tgl_rentang)){
                redirect(base_url('laporan/data_pembelian.php?case=per_rentang&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&tipe='.$tipe.(isset($sb) ? '&sb='.$sb : '').($ht == '1' ? '&hutang=1' : '').($ppn != 'x' ? '&status_ppn='.$ppn : '')));

            }else{
                redirect(base_url('laporan/data_pembelian.php'));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function xls_data_stok(){
        if (akses::aksesLogin() == TRUE) {
            $bulan  = $this->input->get('bulan');
            $tahun  = $this->input->get('tahun');

            $sql = $this->db->select('pd.id, pd.kode, pd.produk, ph.jml, pd.harga_beli, ph.keterangan, DATE(ph.tgl_simpan) AS tgl_simpan, ph.id_penjualan, ph.id_pembelian, ph.`status`')
                                 ->join('tbl_m_produk pd','ph.id_produk=pd.id')
                                 ->where('MONTH(ph.tgl_simpan)', $bulan)
                                 ->where('YEAR(ph.tgl_simpan)', $tahun)
                                 ->group_by('pd.produk')
                                 ->order_by('ph.tgl_simpan', 'desc')
//                                 ->limit(20)
                                 ->get('tbl_m_produk_hist ph')->result();

            $setting = $this->db->get('tbl_pengaturan')->row();

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No.')
                    ->setCellValue('B1', 'Tanggal')
                    ->setCellValue('C1', 'Kode')
                    ->setCellValue('D1', 'Produk')
                    ->setCellValue('E1', 'Awal')
                    ->setCellValue('F1', 'Masuk')
                    ->setCellValue('G1', 'Keluar')
                    ->setCellValue('H1', 'Akhir')
                    ->setCellValue('I1', 'Harga Beli')
                    ->setCellValue('J1', 'Nominal');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

            if(!empty($sql)){
                $no    = 1;
                $cell  = 2;
                $total = 0;
                foreach ($sql as $penjualan){
                    $sql_sawl1  = $this->db->select('jml')->where('id_produk', $penjualan->id)->where('status <', '3')->where('MONTH(tgl_simpan) <', $this->input->get('bulan'))->where('YEAR(tgl_simpan)', $this->input->get('tahun'))->order_by('tgl_simpan', 'desc')->get('tbl_m_produk_hist')->row();
                    $sql_sawl2  = $this->db->select_sum('jml')->where('id_produk', $penjualan->id)->where('status', '3')->where('MONTH(tgl_simpan) <', $this->input->get('bulan'))->where('YEAR(tgl_simpan)', $this->input->get('tahun'))->get('tbl_m_produk_hist')->row();
                    $sakhwl     = $sql_sawl1->jml - (!empty($sql_sawl2->jml) ? $sql_sawl2->jml : 0);
                    $sm         = $sakhwl + ($penjualan->status == '1' ? $penjualan->jml : '0');
                    $sk         = ($penjualan->status == 3 ? $penjualan->jml : '0');
                    $sa         = $sm - $sk;
                    $hb         = $penjualan->harga_beli * $sa;
                    $total      = $total + $hb;

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':D'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$cell.':H'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$cell.':J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$cell.':J'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                    $objPHPExcel->getActiveSheet()->getStyle(''.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$cell, $val,PHPExcel_Cell_DataType::TYPE_STRING);

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo($penjualan->tgl_simpan))
                            ->setCellValue('C'.$cell, $penjualan->kode)
                            ->setCellValue('D'.$cell, $penjualan->produk)
                            ->setCellValue('E'.$cell, $sakhwl)
                            ->setCellValue('F'.$cell, $sm)
                            ->setCellValue('G'.$cell, $sk)
                            ->setCellValue('H'.$cell, $sa)
                            ->setCellValue('I'.$cell, $penjualan->harga_beli)
                            ->setCellValue('J'.$cell, $hb);

                    $no++;
                    $cell++;
                }
            }

            $sell1 = $cell;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':J'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':J'.$sell1)->getFont()->setBold(TRUE);
            $objPHPExcel->getActiveSheet()->getStyle('J'.$sell1)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $sell1, 'TOTAL')->mergeCells('A'.$sell1.':I'.$sell1)
                    ->setCellValue('J' . $sell1, $total);

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Stok Akhir '.$this->tanggalan->bulan_ke($this->input->get('bulan')));

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



            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
//            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_stok_'.$_GET['bulan'].$_GET['tahun'].'.xls"');

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

    public function xls_data_stok2(){
        if (akses::aksesLogin() == TRUE) {
            $tipe   = $this->input->get('st');
            $stok   = $this->input->get('stok');
            $merk   = $this->input->get('merk');

            switch ($tipe){
                case '1' :
                    $st = '<';
                    break;

                case '2' :
                    $st = '';
                    break;

                case '3' :
                    $st = '>';
                    break;
            }

            $sql = $this->db
                            ->where('jml'.(isset($st) ? ' '.$st : ''), $stok)
                            ->like('id_merk', $merk, (!empty($merk) ? 'none' : ''))
                            ->get('tbl_m_produk')->result();

            $setting = $this->db->get('tbl_pengaturan')->row();

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No.')
                    ->setCellValue('B1', 'Kode')
                    ->setCellValue('C1', 'Produk')
                    ->setCellValue('D1', 'Stok');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(45);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);

            if(!empty($sql)){
                $no    = 1;
                $cell  = 2;
                $total = 0;
                foreach ($sql as $penjualan){
                    $total      = $total + $hb;

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':C'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                    $objPHPExcel->getActiveSheet()->getStyle('I'.$cell.':J'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
//                    $objPHPExcel->getActiveSheet()->getStyle(''.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$cell, $val,PHPExcel_Cell_DataType::TYPE_STRING);

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $penjualan->kode)
                            ->setCellValue('C'.$cell, $penjualan->produk)
                            ->setCellValue('D'.$cell, $penjualan->jml);

                    $no++;
                    $cell++;
                }
            }

            $sell1 = $cell;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':J'.$sell1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$sell1.':J'.$sell1)->getFont()->setBold(TRUE);
            $objPHPExcel->getActiveSheet()->getStyle('J'.$sell1)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $sell1, 'TOTAL')->mergeCells('A'.$sell1.':I'.$sell1)
                    ->setCellValue('J' . $sell1, $total);

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Stok');

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



            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
//            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_stok.xls"');

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

    public function set_lap_ret_penjualan(){
        if (akses::aksesLogin() == TRUE) {
            $all         = $this->input->post('semua');
            $hari_ini    = $this->input->post('hari_ini');
            $tgl         = $this->input->post('tgl');
            $tgl_rentang = $this->input->post('tgl_rentang');
            $sales       = $this->input->post('sales');
            $kategori    = $this->input->post('kategori');

            $rentang     = explode('-', $tgl_rentang);
            $t_awal      = explode('/', str_replace(' ','',$rentang[0]));
            $t_akhir     = explode('/', str_replace(' ','',$rentang[1]));
            $tgl_awal    = $t_awal[2].'-'.$t_awal[0].'-'.$t_awal[1];
            $tgl_akhir   = $t_akhir[2].'-'.$t_akhir[0].'-'.$t_akhir[1];

            $tgl_ini     = (!empty($tgl) ? $tgl : $hari_ini);
            $dino_iki    = explode('/', $tgl_ini);
            $tgl_skrg    = $dino_iki[2].'-'.$dino_iki[0].'-'.$dino_iki[1];

            if($tgl_skrg != '--'){
                redirect(base_url('laporan/data_retur_penjualan.php?case=per_tanggal&query='.$tgl_skrg.'&kategori='.$kategori));
            }elseif(!empty($tgl_rentang)){
                redirect(base_url('laporan/data_retur_penjualan.php?case=per_rentang&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&tipe='.$tipe));
            }else{
                redirect(base_url('laporan/data_retur_penjualan.php'));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_lap_ret_pembelian(){
        if (akses::aksesLogin() == TRUE) {
            $all         = $this->input->post('semua');
            $hari_ini    = $this->input->post('hari_ini');
            $tgl         = $this->input->post('tgl');
            $tgl_rentang = $this->input->post('tgl_rentang');
            $sales       = $this->input->post('sales');
            $kategori    = $this->input->post('kategori');

            $rentang     = explode('-', $tgl_rentang);
            $t_awal      = explode('/', str_replace(' ','',$rentang[0]));
            $t_akhir     = explode('/', str_replace(' ','',$rentang[1]));
            $tgl_awal    = $t_awal[2].'-'.$t_awal[0].'-'.$t_awal[1];
            $tgl_akhir   = $t_akhir[2].'-'.$t_akhir[0].'-'.$t_akhir[1];

            $tgl_ini     = (!empty($tgl) ? $tgl : $hari_ini);
            $dino_iki    = explode('/', $tgl_ini);
            $tgl_skrg    = $dino_iki[2].'-'.$dino_iki[0].'-'.$dino_iki[1];

            if($tgl_skrg != '--'){
                redirect(base_url('laporan/data_retur_pembelian.php?case=per_tanggal&query='.$tgl_skrg.'&kategori='.$kategori));

            }elseif(!empty($tgl_rentang)){
                redirect(base_url('laporan/data_retur_pembelian.php?case=per_rentang&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&tipe='.$tipe));

            }else{
                redirect(base_url('laporan/data_retur_pembelian.php'));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_lap_pemasukan(){
        if (akses::aksesLogin() == TRUE) {
            $all         = $this->input->post('semua');
            $hari_ini    = $this->input->post('hari_ini');
            $tgl         = $this->input->post('tgl');
            $tgl_rentang = $this->input->post('tgl_rentang');
            $jenis       = $this->input->post('jenis');

            $rentang     = explode('-', $tgl_rentang);
            $t_awal      = explode('/', str_replace(' ','',$rentang[0]));
            $t_akhir     = explode('/', str_replace(' ','',$rentang[1]));
            $tgl_awal    = $t_awal[2].'-'.$t_awal[0].'-'.$t_awal[1];
            $tgl_akhir   = $t_akhir[2].'-'.$t_akhir[0].'-'.$t_akhir[1];

            $tgl_ini     = (!empty($tgl) ? $tgl : $hari_ini);
            $dino_iki    = explode('/', $tgl_ini);
            $tgl_skrg    = $dino_iki[2].'-'.$dino_iki[0].'-'.$dino_iki[1];

            if(!empty($all)){
                redirect(base_url('laporan/data_pemasukan.php?case=semua&status='.$jenis));

            }elseif($tgl_skrg != '--'){
                redirect(base_url('laporan/data_pemasukan.php?case=per_tanggal&query='.$tgl_skrg.'&status='.$jenis));

            }elseif(!empty($tgl_rentang)){
                redirect(base_url('laporan/data_pemasukan.php?case=per_rentang&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&status='.$jenis));

            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_lap_pengeluaran(){
        if (akses::aksesLogin() == TRUE) {
            $all         = $this->input->post('semua');
            $hari_ini    = $this->input->post('hari_ini');
            $tgl         = $this->input->post('tgl');
            $tgl_rentang = $this->input->post('tgl_rentang');
            $jenis       = $this->input->post('jenis');

            $rentang     = explode('-', $tgl_rentang);
            $t_awal      = explode('/', str_replace(' ','',$rentang[0]));
            $t_akhir     = explode('/', str_replace(' ','',$rentang[1]));
            $tgl_awal    = $t_awal[2].'-'.$t_awal[0].'-'.$t_awal[1];
            $tgl_akhir   = $t_akhir[2].'-'.$t_akhir[0].'-'.$t_akhir[1];

            $tgl_ini     = (!empty($tgl) ? $tgl : $hari_ini);
            $dino_iki    = explode('/', $tgl_ini);
            $tgl_skrg    = $dino_iki[2].'-'.$dino_iki[0].'-'.$dino_iki[1];

            if($tgl_skrg != '--'){
                redirect(base_url('laporan/data_pengeluaran.php?case=per_tanggal&query='.$tgl_skrg.'&status='.$jenis));

            }elseif(!empty($tgl_rentang)){
                redirect(base_url('laporan/data_pengeluaran.php?case=per_rentang&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&status='.$jenis));

            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_lap_lr(){
        if (akses::aksesLogin() == TRUE) {
            $all         = $this->input->post('semua');
            $hari_ini    = $this->input->post('hari_ini');
            $tgl         = $this->input->post('tgl');
            $tgl_rentang = $this->input->post('tgl_rentang');
            $jenis       = $this->input->post('jenis');

            $rentang     = explode('-', $tgl_rentang);
            $t_awal      = explode('/', str_replace(' ','',$rentang[0]));
            $t_akhir     = explode('/', str_replace(' ','',$rentang[1]));
            $tgl_awal    = $t_awal[2].'-'.$t_awal[0].'-'.$t_awal[1];
            $tgl_akhir   = $t_akhir[2].'-'.$t_akhir[0].'-'.$t_akhir[1];

            $tgl_ini     = (!empty($tgl) ? $tgl : $hari_ini);
            $dino_iki    = explode('/', $tgl_ini);
            $tgl_skrg    = $dino_iki[2].'-'.$dino_iki[0].'-'.$dino_iki[1];

            if($tgl_skrg != '--'){
                redirect(base_url('laporan/data_lr.php?case=per_tanggal&query='.$tgl_skrg.'&status='.$jenis));
            }elseif(!empty($tgl_rentang)){
                redirect(base_url('laporan/data_lr.php?case=per_rentang&tgl_awal='.$tgl_awal.'&tgl_akhir='.$tgl_akhir.'&status='.$jenis));
            }else{
                redirect(base_url('laporan/data_lr.php'));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

}
