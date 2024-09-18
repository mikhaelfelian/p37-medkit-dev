<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php

/**
 * Description of gudang
 *
 * @author Mike
 */
class gudang2 extends CI_Controller {
    //put your code here    
    function __construct() {
        parent::__construct();
        $this->load->library('cart'); 
        $this->load->library('fpdf');
        $this->load->library('excel/PHPExcel');
    }
    
    public function data_gudang_list() {
        if (akses::aksesLogin() == TRUE) {
            $hal             = $this->input->get('halaman');
            $filter_kode     = $this->input->get('filter_kode');
            $filter_brcode   = $this->input->get('filter_barcode');
            $filter_produk   = $this->input->get('filter_produk');
            $filter_stok     = $this->input->get('filter_stok');
            $filter_merk     = $this->input->get('filter_merk');
            $filter_lokasi   = $this->input->get('filter_lokasi');
            $sort_type       = $this->input->get('sort_type');
            $sort_order      = $this->input->get('sort_order');
            $jml             = $this->input->get('jml');
            $jml_hal         = (!empty($jml) ? $jml  : $this->db->count_all('tbl_m_produk'));
            $pengaturan      = $this->db->get('tbl_pengaturan')->row();
            $where           = "(tbl_m_produk.kode LIKE '%".$filter_kode."%' OR tbl_m_produk.barcode LIKE '%".$filter_kode."%')";
            
            $data['hasError'] = $this->session->flashdata('form_error');
                        
            $config['base_url']              = base_url('gudang/data_stok_list.php?'.(!empty($filter_kode) ? '&filter_kode='.$filter_kode : '').(!empty($filter_brcode) ? '&filter_barcode='.$filter_brcode : '').(!empty($filter_merk) ? '&filter_merk='.$filter_merk : '').(!empty($filter_lokasi) ? '&filter_lokasi='.$filter_lokasi : '').(!empty($filter_produk) ? '&filter_produk='.$filter_produk : '').(!empty($filter_stok) ? '&filter_stok='.$filter_stok : '').(!empty($filter_hpp) ? '&filter_hpp='.$filter_hpp : '').(!empty($filter_harga) ? '&filter_harga='.$filter_harga : '').(!empty($sort_order) ? '&sort_order='.$sort_order : '').(!empty($jml) ? '&jml='.$jml : ''));
            $config['total_rows']            = $jml_hal;
            
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
            
            
            if(!empty($hal)){
                if (!empty($query)) {
                    $data['kategori'] = $this->db->limit($config['per_page'],$hal)
                                               ->like('gudang', $query)
//                                               ->or_like('keterangan', $query)
                                               ->order_by('id','asc')
                                               ->get('tbl_m_gudang')->result();
                } else {
                    $data['kategori'] = $this->db->limit($config['per_page'],$hal)
                                               ->order_by('id','asc')
                                               ->get('tbl_m_gudang')->result();
                }
            }else{
                if (!empty($query)) {
                    $data['kategori'] = $this->db->limit($config['per_page'],$hal)
                                               ->like('gudang', $query)
//                                               ->or_like('keterangan', $query)
                                               ->order_by('id','asc')
                                               ->get('tbl_m_gudang')->result();
                } else {
                    $data['kategori'] = $this->db->limit($config['per_page'])->order_by('id','asc')->get('tbl_m_gudang')->result();
                }
            }
            
            $this->pagination->initialize($config);
            
            $data['total_rows']     = $config['total_rows'];
            $data['PerPage']        = $config['per_page'];
            $data['filter_kode']    = $filter_kode;
            $data['filter_produk']  = $filter_produk;
            $data['filter_jml']     = $jml;
            $data['pagination']     = $this->pagination->create_links();
            $data['cetak']          = '<button type="button" onclick="window.location.href = \''.base_url('gudang/cetak_data_stok.php?'.(!empty($filter_merk) ? 'filter_merk='.$filter_merk : '').(!empty($filter_kode) ? 'filter_kode='.$filter_kode : '').(!empty($filter_produk) ? '&filter_produk='.$filter_produk : '').(!empty($filter_hpp) ? '&filter_hpp='.$filter_hpp : '').(!empty($filter_harga) ? '&filter_harga='.$filter_harga : '').(!empty($jml) ? '&jml='.$jml : '')).'\'" class="btn btn-warning btn-flat"><i class="fa fa-print"></i> Cetak</button>';

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
//            $this->load->view('admin-lte-2/3_navbar', $data);
            $this->load->view('admin-lte-2/includes/gudang/data_gudang_list', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_stok_list() {
        if (akses::aksesLogin() == TRUE) {
            // $this->db->query("UPDATE tbl_m_produk_hist SET `status` ='4' WHERE keterangan LIKE 'Penj%' AND `status`='3' AND id_gudang='1'");
			
            $hal             = $this->input->get('halaman');
            $filter_kode     = $this->input->get('filter_kode');
            $filter_brcode   = $this->input->get('filter_barcode');
            $filter_produk   = $this->input->get('filter_produk');
            $filter_stok     = $this->input->get('filter_stok');
            $filter_merk     = $this->input->get('filter_merk');
            $filter_lokasi   = $this->input->get('filter_lokasi');
            $sort_type       = $this->input->get('sort_type');
            $sort_order      = $this->input->get('sort_order');
            $jml             = $this->input->get('jml');
            $jml_hal         = (!empty($jml) ? $jml  : $this->db->count_all('tbl_m_produk'));
            $pengaturan      = $this->db->get('tbl_pengaturan')->row();
            $where           = "(tbl_m_produk.kode LIKE '%".$filter_kode."%' OR tbl_m_produk.barcode LIKE '%".$filter_kode."%')";
            
            $data['hasError'] = $this->session->flashdata('form_error');
                        
            $config['base_url']              = base_url('gudang/data_stok_list.php?'.(!empty($filter_kode) ? '&filter_kode='.$filter_kode : '').(!empty($filter_brcode) ? '&filter_barcode='.$filter_brcode : '').(!empty($filter_merk) ? '&filter_merk='.$filter_merk : '').(!empty($filter_lokasi) ? '&filter_lokasi='.$filter_lokasi : '').(!empty($filter_produk) ? '&filter_produk='.$filter_produk : '').(!empty($filter_stok) ? '&filter_stok='.$filter_stok : '').(!empty($filter_hpp) ? '&filter_hpp='.$filter_hpp : '').(!empty($filter_harga) ? '&filter_harga='.$filter_harga : '').(!empty($sort_order) ? '&sort_order='.$sort_order : '').(!empty($jml) ? '&jml='.$jml : ''));
            $config['total_rows']            = $jml_hal;
            
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
            
            
            if(!empty($hal)){
                if (!empty($jml)) {
                    if(!empty($filter_stok)){
                       $data['barang'] = $this->db->limit($config['per_page'],$hal)
                                                  ->where('jml <', $filter_stok)
                                                  ->order_by(!empty($sort_type) ? $sort_type : 'produk', (!empty($sort_order) ? $sort_order : 'asc'))
                                                  ->get('tbl_m_produk')->result();
                    }else{
                       $data['barang'] = $this->db->limit($config['per_page'],$hal)
//                                                  ->where($where)
                                                   ->like('kode', $filter_kode)
                                                   ->like('barcode', $filter_brcode, (!empty($filter_brcode) ? 'none' : ''))
                                                  ->like('produk', $filter_produk)
                                                  ->like('id_merk', $filter_merk, (!empty($filter_merk) ? 'none' : ''))
                                                  ->like('id_lokasi', $filter_lokasi, (!empty($filter_lokasi) ? 'none' : ''))
//                                                  ->like('jml', $filter_stok, (!empty($filter_stok) ? 'none' : ''))
                                                  ->order_by(!empty($sort_type) ? $sort_type : 'produk', (!empty($sort_order) ? $sort_order : 'asc'))
                                                  ->get('tbl_m_produk')->result();
                       }
                } else {
                    $data['barang'] = $this->db->limit($config['per_page'],$hal)->order_by('produk', (!empty($sort_order) ? $sort_order : 'asc'))->get('tbl_m_produk')->result();
                }
            }else{
                if (!empty($jml)) {
                    if(!empty($filter_stok)){
                        $data['barang'] = $this->db->limit($config['per_page'],$hal)
                                                   ->where('jml <', $filter_stok)
                                                   ->order_by(!empty($sort_type) ? $sort_type : 'produk', (!empty($sort_order) ? $sort_order : 'asc'))
                                                   ->get('tbl_m_produk')->result();
                    }else{
                        $data['barang'] = $this->db->limit($config['per_page'],$hal)
//                                                   ->where($where)
                                                   ->like('kode', $filter_kode)
                                                   ->like('barcode', $filter_brcode, (!empty($filter_brcode) ? 'none' : ''))
                                                   ->like('produk', $filter_produk)
                                                   ->like('id_merk', $filter_merk, (!empty($filter_merk) ? 'none' : ''))
                                                   ->like('id_lokasi', $filter_lokasi, (!empty($filter_lokasi) ? 'none' : ''))
//                                                   ->like('jml', $filter_stok, (!empty($filter_stok) ? 'none' : ''))
                                                   ->order_by(!empty($sort_type) ? $sort_type : 'produk', (!empty($sort_order) ? $sort_order : 'asc'))
                                                   ->get('tbl_m_produk')->result();
                    }
                } else {
                    $data['barang'] = $this->db->limit($config['per_page'])
                                               ->order_by(!empty($sort_type) ? $sort_type : 'produk', (!empty($sort_order) ? $sort_order : 'asc'))
                                               ->get('tbl_m_produk')->result();
                }
            }
            
            $this->pagination->initialize($config);
            
            $data['total_rows']     = $config['total_rows'];
            $data['PerPage']        = $config['per_page'];
            $data['filter_kode']    = $filter_kode;
            $data['filter_produk']  = $filter_produk;
            $data['filter_jml']     = $jml;
            $data['pagination']     = $this->pagination->create_links();
            $data['cetak']          = '<button type="button" onclick="window.location.href = \''.base_url('gudang/cetak_data_stok.php?'.(!empty($filter_merk) ? 'filter_merk='.$filter_merk : '').(!empty($filter_kode) ? 'filter_kode='.$filter_kode : '').(!empty($filter_produk) ? '&filter_produk='.$filter_produk : '').(!empty($filter_hpp) ? '&filter_hpp='.$filter_hpp : '').(!empty($filter_harga) ? '&filter_harga='.$filter_harga : '').(!empty($jml) ? '&jml='.$jml : '')).'\'" class="btn btn-warning btn-flat"><i class="fa fa-print"></i> Cetak</button>';

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
//            $this->load->view('admin-lte-2/3_navbar', $data);
            $this->load->view('admin-lte-2/includes/gudang/data_stok_list', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_stok_tambah() {
        if (akses::aksesLogin() == TRUE) {
            $id   = $this->input->get('id');
            
            $data['barang']      = $this->db->where('id', general::dekrip($id))->get('tbl_m_produk')->row();           
            $data['barang_stok'] = $this->db->select('SUM(jml * jml_satuan) as jml')->where('id_produk', general::dekrip($id))->get('tbl_m_produk_stok')->row();           
            $data['barang_sat']  = $this->db->select('*')->where('id_produk', general::dekrip($id))->where('jml !=', '0')->get('tbl_m_produk_satuan')->result();           
            $data['satuan']      = $this->db->get('tbl_m_satuan')->result();
            $data['gudang_ls']   = $this->db->get('tbl_m_gudang')->result();
            $data['gudang']      = $this->db->select('tbl_m_produk_stok.id, tbl_m_produk_stok.id_produk, tbl_m_produk_stok.jml, tbl_m_produk_stok.satuanKecil as satuan, tbl_m_gudang.gudang, tbl_m_gudang.status')->join('tbl_m_gudang', 'tbl_m_gudang.id=tbl_m_produk_stok.id_gudang')->where('tbl_m_produk_stok.id_produk', general::dekrip($id))->get('tbl_m_produk_stok')->result();
  
            if(akses::hakOwner2() == TRUE){
                $data['barang_hist'] = $this->db->select('DATE(tgl_simpan) as tgl_simpan, tgl_masuk, id, id_user, id_gudang, id_penjualan, id_produk, no_nota, kode, jml, jml_satuan, satuan, nominal, keterangan, status')->order_by('tgl_simpan, status', 'asc')->where('id_produk', general::dekrip($id))->where('sp', '0')->not_like('keterangan', 'Pembelian')->get('tbl_m_produk_hist')->result();
            }else{
                $data['barang_hist'] = $this->db->select('DATE(tgl_simpan) as tgl_simpan, tgl_masuk, id, id_user, id_gudang, id_pembelian, id_penjualan, id_produk, no_nota, kode, jml, jml_satuan, nominal, satuan, keterangan, status')->order_by('tgl_simpan, status', 'asc')->where('id_produk', general::dekrip($id))->where('sp', '0')->get('tbl_m_produk_hist')->result();
            }
            
            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
//            $this->load->view('admin-lte-2/3_navbar', $data);
            $this->load->view('admin-lte-2/includes/gudang/data_stok_tambah', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_stok_det() {
        if (akses::aksesLogin() == TRUE) {
            $id   = $this->input->get('id');
            
            $data['barang'] = $this->db->where('id', general::dekrip($id))->get('tbl_m_produk')->row();
            $data['satuan'] = $this->db->get('tbl_m_satuan')->result();
            $data['barang_log']  = $this->db->select('DATE(tgl_simpan) as tgl_simpan, jml, harga, satuan, keterangan')->where('id_produk', general::dekrip($id))->get('tbl_m_produk_saldo')->result();

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
//            $this->load->view('admin-lte-2/3_navbar', $data);
            $this->load->view('admin-lte-2/includes/gudang/data_stok_detail', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_stok_update() {
        if (akses::aksesLogin() == TRUE) {
            $id      = $this->input->post('id');
            $kode    = $this->input->post('kode');
            $barang  = $this->input->post('barang');
            $jml     = $this->input->post('jml');
            $satuan  = $this->input->post('satuan');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('kode', 'Kode Barang', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'kode'     => form_error('kode'),
                );

                $this->session->set_flashdata('form_error', $msg_error);
                redirect(base_url('master/data_barang_tambah.php?id='.$id));
            } else {
                $sql_cek_br = $this->db->where('id', general::dekrip($id))->get('tbl_m_produk')->row();
                $sql_cek_gd = $this->db->where('status', '1')->get('tbl_m_gudang')->row();
                $sql_stk_gd = $this->db->where('id_produk', general::dekrip($id))->get('tbl_m_produk_stok');
                
                $data_stok = array(
                    'tgl_modif'   => date('Y-m-d H:i:s'),
                    'jml'         => $jml,
                );
				
                if ($sql_stk_gd->num_rows() > 0) {
                    foreach ($sql_stk_gd->result() as $gds) {
                        if ($gds->id_gudang == $sql_cek_gd->id) {
                            $jml_sa = $jml - $gds->jml;

                            crud::update('tbl_m_produk_stok', 'id', $gds->id, array('jml'=>$jml_sa));
                        }
                    }
                } else {
                    crud::simpan('tbl_m_produk_stok', $data_stok);
                }

                $this->session->set_flashdata('gudang', '<div class="alert alert-success">Data stok disimpan</div>');
                crud::update('tbl_m_produk', 'id', general::dekrip($id), $data_stok);
                
                if(akses::hakSA() == TRUE OR akses::hakOwner() == TRUE){
                    redirect(base_url('gudang/data_stok_tambah.php?id='.$id));
                }else{
                    redirect(base_url('master/data_barang_tambah.php?id='.$id.'&route=gudang/data_stok_list'));
                }
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_stok_update_gd() {
        if (akses::aksesLogin() == TRUE) {
            $id      = $this->input->post('id');
            $kode    = $this->input->post('kode');
            $barang  = $this->input->post('barang');
            $jml     = $this->input->post('jml');
            $satuan  = $this->input->post('satuan');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'Kode Barang', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'     => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);
                redirect(base_url('master/data_barang_tambah.php?id='.$id));
            } else {
                foreach ($_POST['jml'] as $key => $pos){                    
                    crud::update('tbl_m_produk_stok', 'id', $key, array('jml'=>$_POST['jml'][$key]));
                }
                
                $sql_stk_gd = $this->db->select('SUM(jml * jml_satuan) as jml')->where('id_produk', general::dekrip($id))->get('tbl_m_produk_stok')->row();
                $data_stok = array(
                    'tgl_modif'   => date('Y-m-d H:i:s'),
                    'jml'         => $sql_stk_gd->jml,
                );
                
                $this->session->set_flashdata('gudang', '<div class="alert alert-success">Data stok disimpan</div>');
                crud::update('tbl_m_produk', 'id', general::dekrip($id), $data_stok);
                
                if(akses::hakSA() == TRUE OR akses::hakOwner() == TRUE){
                    redirect(base_url('gudang/data_stok_tambah.php?id='.$id));
                }else{
                    redirect(base_url('master/data_barang_tambah.php?id='.$id.'&route=gudang/data_stok_list'));
                }

//                if ($sql_stk_gd->num_rows() > 0) {
//                    foreach ($sql_stk_gd->result() as $gds) {
//                        if ($gds->id_gudang == $sql_cek_gd->id) {
//                            $jml_sa = $jml - $gds->jml;
//
//                            crud::update('tbl_m_produk_stok', 'id', $gds->id, array('jml'=>$jml_sa));
//                        }
//                    }
//                } else {
//                    crud::simpan('tbl_m_produk_stok', $data_stok);
//                }
//
//                $this->session->set_flashdata('gudang', '<div class="alert alert-success">Data stok disimpan</div>');
//                crud::update('tbl_m_produk', 'id', general::dekrip($id), $data_stok);
//                
//                if(akses::hakSA() == TRUE OR akses::hakOwner() == TRUE){
//                    redirect(base_url('gudang/data_stok_tambah.php?id='.$id));
//                }else{
//                    redirect(base_url('master/data_barang_tambah.php?id='.$id.'&route=gudang/data_stok_list'));
//                }
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_stok_update_br() {
        if (akses::aksesLogin() == TRUE) {
            $id      = $this->input->post('id');
            $kode    = $this->input->post('kode');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('kode', 'Kode Barang', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'kode'     => form_error('kode'),
                );

                $this->session->set_flashdata('form_error', $msg_error);
                redirect(base_url('master/data_barang_tambah.php?id='.$id));
            } else {
                $sql_brg   = $this->db->where('kode', $kode)->or_where('barcode', $kode)->get('tbl_m_produk')->row();
                $jml_akhir = $sql_brg->jml + 1;
                
                $data_penj = array(
                    'tgl_modif'   => date('Y-m-d H:i:s'),
                    'jml'         => $jml_akhir,
                );
                
//                $this->session->set_flashdata('gudang', '<div class="alert alert-success">Data stok disimpan</div>');
                crud::update('tbl_m_produk', 'id', general::dekrip($id),$data_penj);
                redirect(base_url('gudang/data_stok_tambah.php?id='.$id));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_stok_hapus_hist() {
        if (akses::aksesLogin() == TRUE) {
            $id  = $this->input->get('id');
            $uid = $this->input->get('uid');
            $rut = $this->input->get('route');
            
            if(!empty($id)){
                $sql_prod = $this->db->where('id', general::dekrip($uid))->get('tbl_m_produk')->row();
                $sql_hist = $this->db->where('id', general::dekrip($id))->get('tbl_m_produk_hist')->row();
                $sql_stok = $this->db->where('id_gudang', $sql_hist->id_gudang)->where('id_produk', $sql_hist->id_produk)->get('tbl_m_produk_stok')->row();
                $sql_det  = $this->db->where('id_pembelian', $sql_hist->id_pembelian)->where('id_produk', $sql_hist->id_produk)->get('tbl_trans_beli_det')->row();
                $sql_mts  = $this->db->select('tbl_trans_mutasi.id, tbl_trans_mutasi.id_gd_asal, tbl_trans_mutasi.id_gd_tujuan')->join('tbl_trans_mutasi', 'tbl_trans_mutasi.id=tbl_trans_mutasi_det.id_mutasi')->where('tbl_trans_mutasi_det.kode', $sql_prod->kode)->get('tbl_trans_mutasi_det')->row();

                switch ($sql_hist->status){
                    case '1':
                        $stok = $sql_stok->jml - $sql_hist->jml;
                        break;
                    
                    case '2':
                        $stok = $sql_stok->jml - $sql_hist->jml;
                        break;
                    
                    case '3':
                        $stok = $sql_stok->jml - $sql_hist->jml;
                        break;
                    
                    case '4':
                        $stok = $sql_stok->jml + $sql_hist->jml;
                        break;
                    
                    case '5':
                        $stok = $sql_stok->jml + $sql_hist->jml;
                        break;
                    
                    case '6':
                        $stok = $sql_stok->jml + $sql_hist->jml;
                        break;
                    
                    case '7':
                        $stok = $sql_stok->jml + $sql_hist->jml;
                        break;
                    
                    case '8':
                        $stok_asal = $this->db->where('id_produk', $sql_hist->id_produk)->where('id_gudang', $sql_mts->id_gd_asal)->get('tbl_m_produk_stok')->row()->jml + $sql_hist->jml;
                        $this->db->where('id_produk', $sql_hist->id_produk)->where('id_gudang', $sql_mts->id_gd_asal)->update('tbl_m_produk_stok', array('tgl_modif'=>date('Y-m-d H:i:s'),'jml'=>$stok_asal));
                        $stok = $sql_stok->jml - $sql_hist->jml;
                        break;
                }
                
                $jml_diterima = $sql_det->jml_diterima - ($sql_hist->jml * $sql_hist->jml_satuan);

                crud::update('tbl_trans_beli','id', $sql_hist->id_pembelian, array('status_penerimaan'=>'0'));
                crud::update('tbl_trans_beli_det','id', $sql_det->id, array('jml_diterima'=>$jml_diterima));    
                $this->db->where('id_produk', $sql_hist->id_produk)->where('id_gudang', $sql_hist->id_gudang)->update('tbl_m_produk_stok', array('jml'=>$stok));
                crud::delete('tbl_m_produk_hist','id',general::dekrip($id));
                
                $sql_sum = $this->db->select_sum('jml')->where('id_produk', $sql_hist->id_produk)->get('tbl_m_produk_stok')->row();
                $stk_sum = $sql_sum->jml;
                
                crud::update('tbl_m_produk', 'id', $sql_hist->id_produk, array('tgl_modif'=>date('Y-m-d H:i:s'), 'jml'=>$stk_sum));
            }
            
            redirect(base_url((!empty($rut) ? $rut.'?id='.general::enkrip($sql_hist->id_pembelian) : 'gudang/data_stok_tambah.php?id='.$uid)));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_stok_trm_simpan() {
        if (akses::aksesLogin() == TRUE) {
            $id      = $this->input->post('id');
            $tgl_trm = $this->tanggalan->tgl_indo_sys($this->input->post('tgl_terima'));
            $no_po   = $this->input->post('no_po');
            $jml     = $this->input->post('jml');
            $satuan  = $this->input->post('satuan');
            $ket     = $this->input->post('keterangan');
            $gd      = $this->input->post('gudang');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID Barang', 'required');
            $this->form_validation->set_rules('gudang', 'Gudang', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'     => form_error('id'),
                    'gd'     => form_error('gudang'),
                );

                $this->session->set_flashdata('form_error', $msg_error);
                redirect(base_url('gudang/data_stok_tambah.php?id='.$id));
            } else {
                $sql_cek        = $this->db->where('id', general::dekrip($id))->get('tbl_m_produk')->row();
                $sql_stk_gd     = $this->db->where('id_produk', $sql_cek->id)->where('id_gudang', $gd)->get('tbl_m_produk_stok');
                $sa             = $sql_cek->jml + $jml;
                $sg             = $sql_stk_gd->row()->jml + $jml;
                
                $data_penj_hist = array(
                    'id_produk'   => general::dekrip($id),
                    'id_user'     => $this->ion_auth->user()->row()->id,
                    'id_gudang'   => $gd,
                    'tgl_simpan'  => $tgl_trm.' '.date('H:i:s'),
                    'tgl_masuk'   => $tgl_trm,
                    'kode'        => $sql_cek->kode,
                    'jml'         => $jml,
                    'jml_satuan'  => '1',
                    'satuan'      => $satuan,
                    'keterangan'  => (!empty($ket) ? $ket : '-'),
                    'status'      => '2',
                );
                
                $data_gudang = array(
                    'id_produk'   => general::dekrip($id),
                    'id_user'     => $this->ion_auth->user()->row()->id,
                    'tgl_simpan'  => $tgl_trm.' '.date('H:i:s'),
                    'tgl_masuk'   => $tgl_trm,
                    'kode'        => $sql_cek->kode,
                    'jml'         => $jml,
                    'jml_satuan'  => '1',
                    'satuan'      => $satuan,
                    'keterangan'  => (!empty($ket) ? $ket : '-'),
                    'status'      => '2',
                );

                
                $data_stok_gd   = array(
                    'tgl_modif'     => date('Y-m-d H:i:s'), 
                    'id_produk'     => $sql_cek->id, 
                    'id_gudang'     => $gd, 
                    'jml'           => $sg,
                    'satuan'        => $satuan,
                    'status'        => '0',
                );
                
                if($sql_stk_gd->num_rows() > 0){
                    crud::update('tbl_m_produk_stok', 'id', $sql_stk_gd->row()->id, $data_stok_gd);
                }else{
                    crud::simpan('tbl_m_produk_stok', $data_stok_gd);
                }
                $sql_stk_gd2    = $this->db->select('SUM(jml * jml_satuan) as jml')->where('id_produk', $sql_cek->id)->get('tbl_m_produk_stok')->row();
                $data_stok      = array('tgl_modif' => date('Y-m-d H:i:s'), 'jml' => $sql_stk_gd2->jml);
                
                crud::update('tbl_m_produk', 'id', general::dekrip($id), $data_stok);
                crud::simpan('tbl_m_produk_hist', $data_penj_hist);

                $this->session->set_flashdata('gudang', '<div class="alert alert-success">Data stok disimpan</div>');
                redirect(base_url('gudang/data_stok_tambah.php?id='.$id));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_stok_import() {
        if (akses::aksesLogin() == TRUE) {
            $id   = $this->input->get('id');
            
            $data['stok'] = $this->db->where('id', general::dekrip($id))->get('tbl_m_produk')->row();
            $data['satuan'] = $this->db->get('tbl_m_satuan')->result();

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
//            $this->load->view('admin-lte-2/3_navbar', $data);
            $this->load->view('admin-lte-2/includes/gudang/data_stok_import', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_stok_upload2() {
        if (akses::aksesLogin() == TRUE) {
            $this->load->helper('file');
            
            if (!empty($_FILES['fupload']['name'])) {
                $folder = realpath('.file/import');
                $config['upload_path']      = './file/import';
                $config['allowed_types']    = 'xls|xlsx';
                $config['remove_spaces']    = TRUE;
                $config['overwrite']        = TRUE;
                $this->load->library('upload', $config);
                
                if (!$this->upload->do_upload('fupload')) {
                    $this->session->set_flashdata('gudang', 'Error : <b>' . $this->upload->display_errors() . '</b>.');
                    redirect(base_url('gudang/data_stok_import.php?err='.$this->upload->display_errors()));
                }else{
                    $f           = $this->upload->data();
                    $path        = realpath('./file/import') . '/';                    
                    $objPHPExcel = PHPExcel_IOFactory::load($path.$f['orig_name']);
                    
                    $data_file = array(
                        'tgl_simpan'  => date('Y-m-d H:i:s'),
                        'id_user'     => $this->ion_auth->user()->row()->id,
                        'keterangan'  => 'Stok Opname By Excel ['.$f['orig_name'].']; Uploaded By '.$this->ion_auth->user()->row()->first_name,
                        'nm_file'     => $f['orig_name'],
                        'status'      => '1',
                    );
                    crud::simpan('tbl_util_so', $data_file);
                    $last_id = crud::last_id();
                    
//                    echo '<pre>';
//                    print_r($data_file);
//                    echo '</pre>';

                    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                        $worksheetTitle     = $worksheet->getTitle();
                        $highestRow         = $worksheet->getHighestRow();
                        $highestColumn      = $worksheet->getHighestColumn();
                        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                        
                        for ($row = 2; $row <= $highestRow; ++ $row) {
                            $val = array();
                            
                            for ($col = 0; $col < $highestColumnIndex; ++$col) {
                                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                $val[] = $cell->getValue();
                            }
                            
//                            $aid = str_replace(' ', '', $val[1]);
//                            $sql_cek     = $this->db->where('kode', $aid)->get('tbl_m_produk')->row();
//                            $sql_cek_hst = $this->db->select('MAX(id) as id')->get('tbl_m_produk_hist')->row();
//                            $sql_cek_sm = $this->db->select('SUM(jml) as stok_masuk')->where('id_produk', $sql_cek->id)->where('status !=', '3')->get('tbl_m_produk_hist')->row();
//                            $sql_cek_sk = $this->db->select('SUM(jml) as stok_keluar')->where('id_produk', $sql_cek->id)->where('status', '3')->get('tbl_m_produk_hist')->row();
//                            $sql_cek_sat = $this->db->where('id', $sql_cek->id_satuan)->get('tbl_m_satuan')->row();
////                            $sql_cek_mrk = $this->db->where('id', $sql_cek->id_merk)->get('tbl_m_merk')->row();
                            
                            $data_produk = array(
                                'id_so'         => $last_id,
                                'id_user'       => $this->ion_auth->user()->row()->id,
                                'tgl_simpan'    => date('Y-m-d H:i:s'),
                                'kode'          => $val[1],
                                'barcode'       => $val[2],
                                'produk'        => $val[3],
                                'jml'           => $val[4],
                                'merk'          => $val[8],
                                'tgl_masuk'     => date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val[9])),
                            );
                            crud::simpan('tbl_util_so_det', $data_produk);
                            
//                            $jml_akhir   = $sql_cek->jml + $val[4];
////                            $jml_akhir   = $sql_cek_sm->stok_masuk - $sql_cek_sk->stok_keluar; //$val[4] - $sql_cek->jml;
//                            $jml_stok    = ($jml_akhir >= $val[4] ? $jml_akhir : $val[4]); //$val[4] - $sql_cek->jml;
//                            $kode        = $sql_cek->id + 1;
                            
////                            if(empty($sql_cek->harga_grosir)){
//                                $produk = array(
//                                    'tgl_modif'     => date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val[9])).' '.date('H:i:s'),
//                                    'jml'           => (!empty($val[4]) ? (int)$val[4] : '0'),
//                                    'harga_beli'    => (!empty($val[5]) ? str_replace(array('.',',','\''),'',$val[5]) : '0'),
//                                    'harga_jual'    => (!empty($val[6]) ? str_replace(array('.',',','\''),'',$val[6]) : '0'),
//                                    'harga_grosir'  => (!empty($val[7]) ? str_replace(array('.',',','\''),'',$val[7]) : '0'),
//                                );
//                                
//                                $merk = array(
//                                    'tgl_simpan'    => date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val[8])).' '.date('H:i:s'),
//                                    'merk'          => (!empty($val[8]) ? $val[8] : ''),
//                                );
//                                
//                                $produk_hist = array(
//                                    'id_produk'   => $sql_cek->id,
//                                    'id_user'     => $this->ion_auth->user()->row()->id,
//                                    'tgl_simpan'  => date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val[8])).' '.date('H:i:s'),
//                                    'tgl_masuk'   => date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val[8])),
//                                    'kode'        => 'SO'.sprintf('%05s', $kode),
//                                    'jml'         => (!empty($val[4]) ? $val[4] : '0'),
//                                    'jml_satuan'  => 1,
//                                    'satuan'      => $sql_cek_sat->satuanTerkecil,
//                                    'keterangan'  => 'Import Stok By Excel ['.$f['orig_name'].']; Uploaded By '.$this->ion_auth->user()->row()->first_name,
////                                    'keterangan'  => 'Jml Selisih : <b>'.$jml_akhir.'</b>. Stok Opname By Excel [filename:'.$f['orig_name'].']; Uploaded By '.$this->ion_auth->user()->row()->first_name,
//                                    'status'      => 2,
//                                );
//                                
//                                echo $aid;
//                                echo '<pre>';
//                                print_r($val);
//                                echo '</pre>';
//                                echo '<pre>';
//                                print_r($data_produk);
//                                echo '</pre>';
//                                echo '<pre>';
//                                print_r($produk);
//                                echo '</pre>';
//                                echo '<pre>';
//                                print_r($merk);
//                                echo '</pre>';
//                                echo '<pre>';
//                                print_r($produk_hist);
//                                echo '</pre>';
//
//                                crud::update('tbl_m_produk', 'kode', $aid, $produk);
//                           
//                                if(!empty($val[4])){
//                                    crud::simpan('tbl_m_produk_hist', $produk_hist);
//                                }
// 
//                                if($sql_cek->id_merk == '0'){
//                                    crud::simpan('tbl_m_merk', $merk);
//                                }
//                            }
                        }
                    }

                    $this->session->set_flashdata('gudang', 'Success : <b>' . $this->upload->display_errors() . '</b>.');
                    unlink($path.$f['orig_name']);
                    redirect(base_url('gudang/data_stok_import.php'.(isset($last_id) ? '?id='.general::enkrip($last_id) : '')));
                }
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_stok_upload() {
        if (akses::aksesLogin() == TRUE) {
            $this->load->helper('file');
            
            if (!empty($_FILES['fupload']['name'])) {
                $folder = realpath('./file/import');
                $config['upload_path']      = './file/import';
                $config['allowed_types']    = 'xls|xlsx';
                $config['remove_spaces']    = TRUE;
                $config['overwrite']        = TRUE;
                $this->load->library('upload', $config);
                
                if (!$this->upload->do_upload('fupload')) {
                    $this->session->set_flashdata('gudang', 'Error : <b>' . $this->upload->display_errors() . '</b>.');
                    redirect(base_url('gudang/data_stok_import.php?err='.$this->upload->display_errors()));
                }else{
                    $f           = $this->upload->data();
                    $path        = realpath('./file/import') . '/';                    
                    $objPHPExcel = PHPExcel_IOFactory::load($path.$f['orig_name']);
                                       
                    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                        $worksheetTitle     = $worksheet->getTitle();
                        $highestRow         = $worksheet->getHighestRow();
                        $highestColumn      = $worksheet->getHighestColumn();
                        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                        
                        $i = 1;
                        for ($row = 2; $row <= $highestRow; ++ $row) {
                            $val = array();
                            
                            for ($col = 0; $col < $highestColumnIndex; ++$col) {
                                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                $val[] = $cell->getValue();
                            }
                            
                            $aid = str_replace(' ', '', $val[1]);
                            $sql_cek_merk = $this->db->where('merk', $val[8])->get('tbl_m_merk');
                            $sql_cek_sat  = $this->db->where('satuanTerkecil', $val[9])->get('tbl_m_satuan');
                            $sql_cek_brg  = $this->db->where('produk', $val[3])->get('tbl_m_produk');
                            $sql_cek_ibrg = $this->db->select_max('id')->get('tbl_m_produk')->row();
                            
                            // Data Merk
                            if($sql_cek_merk->num_rows() == 0){
                                $merk = array(
                                    'tgl_simpan'    => date('Y-m-d H:i:s'),
                                    'merk'          => (!empty($val[8]) ? $val[8] : '')
                                );
                                
                                crud::simpan('tbl_m_merk', $merk);
                                $id_merk = crud::last_id();
                            }else{
                                $id_merk = $sql_cek_merk->row()->id;
                            }
                            
                            if($sql_cek_brg->num_rows() == 0){
                                // Data Produk
                                $produk = array(
                                    'tgl_simpan'    => date('Y-m-d H:i:s'),
                                    'id_merk'       => $id_merk,
                                    'id_satuan'     => (!empty($val[9]) ? (int)$sql_cek_sat->row()->id : '0'),
                                    'id_kategori'   => 0,
                                    'id_lokasi'     => 0,
                                    'kode'          => (!empty($val[1]) ? ($sql_cek_brg->num_rows() == 0 ? $aid.'-'.date('ymd').'-'.($sql_cek_ibrg->id + $i) : $aid) : ''),
                                    'barcode'       => (!empty($val[2]) ? trim($val[2]) : ''),
                                    'produk'        => (!empty($val[3]) ? trim($val[3]) : ''),
                                    'jml'           => (!empty($val[4]) ? (int)$val[4] : '0'),
                                    'harga_beli'    => (!empty($val[5]) ? str_replace(',', '.', str_replace('.','',$val[5])) : '0'),
                                    'harga_jual'    => (!empty($val[6]) ? str_replace(',', '.', str_replace('.','',$val[6])) : '0'),
                                    'harga_grosir'  => (!empty($val[7]) ? str_replace(',', '.', str_replace('.','',$val[7])) : '0'),
                                );
                                
                                crud::simpan('tbl_m_produk', $produk);
                                $id_produk = crud::last_id();
                            
                                $produk_hist = array(
                                    'id_produk'   => $id_produk,
                                    'id_user'     => $this->ion_auth->user()->row()->id,
                                    'tgl_simpan'  => date('Y-m-d H:i:s'),
                                    'tgl_masuk'   => date('Y-m-d'),
                                    'kode'        => (!empty($val[1]) ? $aid : ''),
                                    'jml'         => (!empty($val[4]) ? $val[4] : '0'),
                                    'jml_satuan'  => 1,
                                    'satuan'      => 'PCS',
                                    'keterangan'  => 'Stok Awal By Excel ['.$f['orig_name'].']; Uploaded By '.$this->ion_auth->user()->row()->first_name,
                                    'status'      => 2,
                                );
                                crud::simpan('tbl_m_produk_hist', $produk_hist);
                            }else{
//                                // Data Produk
//                                $produk = array(
//                                    'tgl_modif'     => date('Y-m-d H:i:s'),
//                                    'harga_beli'    => (!empty($val[5]) ? str_replace(',', '.', str_replace('.','',$val[5])) : '0'),
//                                    'harga_jual'    => (!empty($val[6]) ? str_replace(',', '.', str_replace('.','',$val[6])) : '0'),
//                                    'harga_grosir'  => (!empty($val[7]) ? str_replace(',', '.', str_replace('.','',$val[7])) : '0'),
//                                );
//                                
//                                crud::update('tbl_m_produk', 'id', $sql_cek_brg->row()->id, $produk);
                                $id_produk = $sql_cek_brg->row()->id;
                            }
                            
                            if($sql_cek_brg->num_rows() == 0){
                                // Data Satuan
                                $sql_satuan = $this->db->get('tbl_m_satuan')->result();
                                foreach ($sql_satuan as $sat_jual){
                                    $prod_satuan = array(
                                            'id_produk' => $id_produk,
                                            'id_satuan' => $sat_jual->id,
                                            'satuan'    => $sat_jual->satuanTerkecil,
                                            'jml'       => $sat_jual->jml,
                                            'harga'     => ($sat_jual->satuanTerkecil == trim($val[9]) ? str_replace(',', '.', str_replace('.','',$val[6])) : '0'),
                                            'status'    => $sat_jual->status,
                                    );
                             
                                    crud::simpan('tbl_m_produk_satuan', $prod_satuan);
                                }
                            }else{
                                // Data Satuan
                                $sql_satuan = $this->db->where('id_produk', $sql_cek_brg->row()->id)->where('satuan', $val[9])->get('tbl_m_produk_satuan')->result();
                                foreach ($sql_satuan as $sat_jual){                                    
                                    $prod_satuan = array(
                                        'jml'       => $sat_jual->jml,
                                        'harga'     => str_replace(',', '.', str_replace('.','',$val[6])),
                                        'status'    => $sat_jual->status,
                                    );

                                    crud::update('tbl_m_produk_satuan', 'id', $sat_jual->id, $prod_satuan);
                                    
                                    $sql_cek_ibrg2= $this->db->where('id', $sql_cek_brg->row()->id)->get('tbl_m_produk')->row();
                                    $sql_cek_isat = $this->db->where('id', $sql_cek_ibrg2->id_satuan)->get('tbl_m_satuan')->row();
                                    
                                    if($sql_cek_isat->satuanTerkecil == $sat_jual->satuan){
                                        // Data Produk
                                        $produk_upd = array(
                                            'tgl_modif'     => date('Y-m-d H:i:s'),
                                            'harga_beli'    => (!empty($val[5]) ? str_replace(',', '.', str_replace('.', '', $val[5])) : '0'),
                                            'harga_jual'    => str_replace(',', '.', str_replace('.', '', $val[6])),
                                            'harga_grosir'  => (!empty($val[7]) ? str_replace(',', '.', str_replace('.', '', $val[7])) : '0'),
                                        );
                                        crud::update('tbl_m_produk', 'id', $sql_cek_brg->row()->id, $produk_upd);
                                    }
                                }
                            }
                            
//                            // Kode Barang
                            if($sql_cek_brg->num_rows() == 0){
                                $prod_kode = array(
                                    'kode'          => $aid.'-'.date('ymd').'-'.$id_produk,
                                );
                                crud::update('tbl_m_produk', 'id', $id_produk, $prod_kode);
                            }
                            
                            $i++;
                        }
                    }

                    $this->session->set_flashdata('gudang', '<div class="alert alert-success">Success : <b>' . $f['orig_name'] . '</b>.</div>');
                    unlink($path.$f['orig_name']);
                    redirect(base_url('gudang/data_stok_import.php?msg=Upload succeded'));
                }
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    

    public function data_opname_list() {
        if (akses::aksesLogin() == TRUE) {
            $query      = $this->input->get('q');
            $hal        = $this->input->get('halaman');
            $jml        = $this->input->get('jml');
            $jml_hal    = (!empty($jml) ? $jml  : $this->db->count_all('tbl_util_so'));
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
            $data['hasError'] = $this->session->flashdata('form_error');
                        
            $config['base_url']               = base_url('gudang/data_opname_list.php?'.(isset($_GET['q']) ? '&q='.$_GET['q'].'&jml='.$jml_hal : ''));
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
            
            
            if(!empty($hal)){
                if (!empty($query)) {
                    $data['opname'] = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id, id_user, keterangan, nm_file, dl_file, reset')->limit($config['per_page'],$hal)
                                               ->limit($config['per_page'],$hal)
                                               ->like('keterangan', $query)
                                               ->or_like('nm_file', $query)
                                               ->order_by('tgl_simpan','desc')
                                               ->get('tbl_util_so')->result();
                } else {
                    $data['opname'] = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id, id_user, keterangan, nm_file, dl_file, reset')->limit($config['per_page'],$hal)
                                               ->limit($config['per_page'],$hal)
                                               ->order_by('tgl_simpan','desc')
                                               ->get('tbl_util_so')->result();
                }
            }else{
                if (!empty($query)) {
                    $data['opname'] = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id, id_user, keterangan, nm_file, dl_file, reset')->limit($config['per_page'],$hal)
                                               ->limit($config['per_page'])
                                               ->like('keterangan', $query)
                                               ->or_like('nm_file', $query)
                                               ->order_by('tgl_simpan','desc')
                                               ->get('tbl_util_so')->result();
                } else {
                    $data['opname'] = $this->db->select('DATE(tgl_simpan) as tgl_simpan, id, id_user, keterangan, nm_file, dl_file, reset')->limit($config['per_page'])->order_by('tgl_simpan','desc')->get('tbl_util_so')->result();
                }
            }
            
            $this->pagination->initialize($config);
            
            $data['total_rows'] = $config['total_rows'];
            $data['PerPage']    = $config['per_page'];
            $data['pagination'] = $this->pagination->create_links();

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
//            $this->load->view('admin-lte-2/3_navbar', $data);
            $this->load->view('admin-lte-2/includes/gudang/data_opname_list', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_opname_tambah() {
        if (akses::aksesLogin() == TRUE) {
            $id   = $this->input->get('id');
            
            $data['stok']           = $this->db->where('id', general::dekrip($id))->get('tbl_m_produk')->row();
            $data['satuan']         = $this->db->get('tbl_m_satuan')->result();
            $data['gudang_ls']      = $this->db->get('tbl_m_gudang')->result();

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
//            $this->load->view('admin-lte-2/3_navbar', $data);
            $this->load->view('admin-lte-2/includes/gudang/data_opname_tambah', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_opname_det() {
        if (akses::aksesLogin() == TRUE) {
            $id   = $this->input->get('id');
            
            $data['barang']      = $this->db->where('id', general::dekrip($id))->get('tbl_util_so')->row();
            $data['barang_log']  = $this->db->select('*')->where('id_so', general::dekrip($id))->get('tbl_util_so_det')->result();

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
//            $this->load->view('admin-lte-2/3_navbar', $data);
            $this->load->view('admin-lte-2/includes/gudang/data_opname_detail', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_opname_upload() {
        if (akses::aksesLogin() == TRUE) {
            $this->load->helper('file');
            
            if (!empty($_FILES['fupload']['name']) AND !empty($_POST['gudang'])) {
                $folder  = realpath('./file/import');
                $tanggal = date('YmdHis');
                        
                $config['upload_path']      = './file/import';
                $config['allowed_types']    = 'xls|xlsx';
                $config['file_name']        = 'SO_'.$tanggal;
                $config['remove_spaces']    = TRUE;
                $config['overwrite']        = TRUE;
                $this->load->library('upload', $config);
                
                if (!$this->upload->do_upload('fupload')) {
                    $this->session->set_flashdata('gudang', 'Error : <b>' . $this->upload->display_errors() . '</b>.');
                    redirect(base_url('gudang/data_opname_tambah.php?err='.$this->upload->display_errors()));
                }else{
                    $f           = $this->upload->data();
                    $path        = realpath('./file/import') . '/';                    
                    $objPHPExcel = PHPExcel_IOFactory::load($path.$f['orig_name']);
                    $client_name = str_replace(array(' ','-'), '_', $f['client_name']);

                    $data_file = array(
//                        'tgl_simpan'  => date('Y-m-d H:i:s'),
                        'id_user'     => $this->ion_auth->user()->row()->id,
                        'id_gudang'   => $this->input->post('gudang'),
                        'keterangan'  => 'Stok Opname By Excel ['.$client_name.']; Uploaded By '.$this->ion_auth->user()->row()->first_name,
                        'nm_file'     => $client_name,
                        'dl_file'     => $f['orig_name'],
                        'status'      => '1',
                    );
                    crud::simpan('tbl_util_so', $data_file);
                    $last_id = crud::last_id();
                    
//                    echo '<pre>';
//                    print_r($data_file);
//                    echo '</pre>';
//                    
//                    echo '<pre>';
//                    print_r($f);
//                    echo '</pre>';

                    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                        $worksheetTitle     = $worksheet->getTitle();
                        $highestRow         = $worksheet->getHighestRow();
                        $highestColumn      = $worksheet->getHighestColumn();
                        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                        
                        for ($row = 2; $row <= $highestRow; ++ $row) {
                            $val = array();
                            
                            for ($col = 0; $col < $highestColumnIndex; ++$col) {
                                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                $val[] = $cell->getValue();
                            }
                            
                            $sql_prod = $this->db->where('kode', $val[1])->or_where('produk', $val[3])->get('tbl_m_produk');
                            $sql_produk = $sql_prod->row();
                            
                            $jml_chr = strlen($this->tanggalan->tgl_indo_sys2($val[9]));
                            $tgl_so  = ($jml_chr == 10 ? $this->tanggalan->tgl_indo_sys2($val[9]) : date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val[9])));
                            
                            $data_produk = array(
                                'id_so'         => $last_id,
                                'id_produk'     => $sql_produk->id,
                                'id_user'       => $this->ion_auth->user()->row()->id,
                                'tgl_simpan'    => trim($tgl_so).' '.date('H:i:s'),
                                'kode'          => (!empty($val[1]) ? trim($val[1]) : $sql_produk->kode),
                                'barcode'       => (!empty($val[2]) ? trim($val[2]) : $sql_produk->barcode),
                                'produk'        => (!empty($val[3]) ? trim($val[3]) : $sql_produk->produk),
                                'jml'           => trim($val[4]),
                                'merk'          => (!empty($val[8]) ? trim($val[8]) : ''),
                                'tgl_masuk'     => trim($tgl_so),
                            );
//                            
//                            echo '<pre>';
//                            print_r($data_produk);
                            
                            if($sql_prod->num_rows() > 0){
                                crud::simpan('tbl_util_so_det', $data_produk);
                            }
                        }
                    }
                    
                    crud::update('tbl_util_so', 'id', $last_id, array('tgl_simpan'=>trim($tgl_so).' '.date('H:i:s')));

                    $this->session->set_flashdata('gudang', 'Success : <b>' . $this->upload->display_errors() . '</b>.');
//                    unlink($path.$f['orig_name']);
                    redirect(base_url('gudang/data_opname_simpan.php'.(isset($last_id) ? '?id='.general::enkrip($last_id) : '')));
                }
            }else{
                $this->session->set_flashdata('gudang', '<div class="alert alert-danger">Error : <b>File name kosong atau gudang tujuan belum di set</b>.</div>');
                redirect(base_url('gudang/data_opname_tambah.php'));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_opname_simpan() {
        if (akses::aksesLogin() == TRUE) {
            $id   = $this->input->get('id');
            
            $sql_op      = $this->db->select('*')->where('id', general::dekrip($id))->get('tbl_util_so')->row();
            $sql_op_det  = $this->db->select('id, id_produk, kode, produk, jml, DATE(tgl_simpan) as tgl_simpan, tgl_masuk')->where('id_so', general::dekrip($id))->get('tbl_util_so_det');

            if($sql_op_det->num_rows() > 0){
                $no = 1;
                foreach ($sql_op_det->result() as $brg){
                    $sql_brg    = $this->db->where('id', $brg->id_produk)->get('tbl_m_produk')->row();
                    $sql_sat    = $this->db->where('id', $sql_brg->id_satuan)->get('tbl_m_satuan')->row();
                    $sql_gd     = $this->db->where('id_produk', $brg->id_produk)->where('id_gudang', $sql_op->id_gudang)->get('tbl_m_produk_stok');
                    $cek_sm     = $this->db->query("SELECT SUM(jml) as jml FROM tbl_m_produk_hist WHERE id_produk='".$brg->id_produk."' AND status='1' AND DATE(tgl_simpan) >= '".$brg->tgl_masuk."'")->row();
                    $jml_akhir  = $this->db->select('SUM(jml * jml_satuan) as jml')->where('id_produk', $brg->id_produk)->get('tbl_m_produk_stok')->row();
//                    $cek_sm     = $this->db->query("SELECT SUM(jml) as jml FROM tbl_m_produk_hist WHERE id_produk='".$brg->id_produk."' AND status='1'")->row();
//                    $cek_sm     = $this->db->select('SUM(jml) as jml')->where('id_produk', $brg->id_produk)->where('status', '1')->where('DATE(tgl_simpan) >=', $brg->tgl_masuk)->row();
                    $cek_sk     = $this->db->query("SELECT SUM(jml * jml_satuan) as jml FROM tbl_m_produk_hist WHERE id_produk='".$brg->id_produk."' AND status='3' AND DATE(tgl_simpan) >= '".$brg->tgl_masuk."'")->row();
//                    $cek_sk     = $this->db->query("SELECT SUM(jml * jml_satuan) as jml FROM tbl_m_produk_hist WHERE id_produk='".$brg->id_produk."' AND status='3'")->row();
//                    $cek_sk     = $this->db->select('SUM(jml * jml_satuan) as jml')->where('id_produk', $brg->id_produk)->where('status', '3')->where('DATE(tgl_simpan) >=', $brg->tgl_masuk)->row();
                    $stok_gd    = $brg->jml;
                                    
                    
                    $data_prod_st = array(
                        'tgl_simpan'  => date('Y-m-d H:i:s'),
                        'tgl_modif'   => date('Y-m-d H:i:s'),
                        'id_produk'   => $sql_brg->id,
                        'id_gudang'   => $sql_op->id_gudang,
                        'id_user'     => $this->ion_auth->user()->row()->id,
                        'jml'         => (int)$stok_gd,
                        'jml_satuan'  => 1,
                        'satuan'      => $sql_sat->satuanTerkecil,
                        'satuanKecil' => $sql_sat->satuanTerkecil,
                        'status'      => '0',
                    );                
                    
                    $data_hist = array(
                        'tgl_simpan'  => $brg->tgl_simpan,
                        'tgl_masuk'   => $brg->tgl_simpan,
                        'id_produk'   => $brg->id_produk,
                        'id_gudang'   => $sql_op->id_gudang,
                        'kode'        => $brg->kode,
                        'jml'         => $brg->jml,
                        'jml_satuan'  => 1,
                        'satuan'      => (!empty($sql_sat->satuanTerkecil) ? $sql_sat->satuanTerkecil : 'PCS'),
                        'keterangan'  => $sql_op->keterangan,
                        'status'      => '6',
                    );
                    
                    if($sql_gd->num_rows() > 0){
                        crud::update('tbl_m_produk_stok', 'id', $sql_gd->row()->id, $data_prod_st);
                    }else{
                        crud::simpan('tbl_m_produk_stok', $data_prod_st);
                    }
                    
                    $stok_akhir = ($cek_sm->jml + $brg->jml) - $cek_sk->jml;
                    $data_prod = array(
                        'tgl_modif' => date('Y-m-d H:i:s'),
                        'jml'       => $jml_akhir->jml,
                    );
                    
                    crud::update('tbl_m_produk', 'id', $brg->id_produk, $data_prod);
                    crud::simpan('tbl_m_produk_hist', $data_hist);
                        
//                        echo 'No : '.$no;
//                        echo br();
//                        echo 'Produk : '.anchor(base_url('gudang/data_stok_tambah.php?id='.general::enkrip($brg->id_produk)), $brg->produk, 'target="_blank"');
//                        echo br();
//                        echo 'Stok Masuk : '.($cek_sm->jml + $brg->jml);
//                        echo br();
//                        echo 'Stok Keluar : '.($cek_sk->jml);
//                        echo br();
//                        echo ' <b>Stok Akhir : '.$stok_akhir.'</b>';
//                        echo br(2);
//                        
//                        echo '<pre>';
//                        print_r($data_prod);
//                        echo '</pre>';
    
                    $no++;
                }
            }else{
                $path        = realpath('./file/import') . '/';
                unlink($path.$sql_op->nm_file);
                crud::delete('tbl_util_so', 'id', $sql_op->id);
            }
            
            redirect(base_url('gudang/data_opname_det.php?id='.$id));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function data_opname_dl() {
        if (akses::aksesLogin() == TRUE) {
            $this->load->helper('download');
            $id   = $this->input->get('id');
            $file = $this->input->get('file');
            
            $sql_op      = $this->db->where('id', general::dekrip($id))->get('tbl_util_so')->row();
            $path        = realpath('./file/import').'/';
            $data        = file_get_contents($path.$sql_op->dl_file);

            force_download($file, $data);            
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
	
    public function data_mutasi_list() {
        if (akses::aksesLogin() == TRUE) {
            /* -- Grup hak akses -- */
            $role        = $this->input->get('role');
            $grup        = $this->ion_auth->get_users_groups()->row();
            $id_user     = $this->ion_auth->user()->row()->id;
            $id_grup     = $this->ion_auth->get_users_groups()->row();
            $pengaturan  = $this->db->get('tbl_pengaturan')->row();

            /* -- Blok Filter -- */
            $query   = $this->input->get('q');
            $hal     = $this->input->get('halaman'); 
            $nt      = $this->input->get('filter_nota');
            $fn      = explode('/', $nt);
            $tg      = $this->input->get('filter_tgl');
            $tb      = $this->input->get('filter_tgl_bayar');
            $tp      = $this->input->get('filter_tgl_tempo');
            $lk      = $this->input->get('filter_lokasi');
            $cs      = $this->input->get('filter_cust');
            $sn      = $this->input->get('filter_status');
            $sl      = $this->input->get('filter_sales');
            $jml     = $this->input->get('jml');
//            $jml_sql = ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'admin' ? $this->db->get('tbl_trans_jual')->num_rows() : $this->db->where('id_user', $id_user)->where('tgl_masuk', date('Y-m-d'))->get('tbl_trans_jual')->num_rows());
            
            if(!empty($jml)){
                $jml_hal = $jml;
            }else{
                $jml_hal = $this->db->select('id, id_app, no_nota')
                                ->where('status_nota !=', '4')
                                ->like('no_nota', $fn[0])
                                ->like('DATE(tgl_keluar)', $tp)
                                ->like('id_user', ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'admin' ? '' : $id_user), ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'admin' ? '' : 'none'))
                                ->like('tgl_masuk', ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'admin' ? '' : date('Y-m-d')))
                                ->order_by('tgl_simpan','desc')
                                ->get('tbl_trans_mutasi')->num_rows();
            }
            /* -- End Blok Filter -- */

            /* -- Form Error -- */
            $data['hasError']                = $this->session->flashdata('form_error');

            /* -- Blok Pagination -- */
            $config['base_url']              = base_url('gudang/data_mutasi.php?filter_nota='.$nt.'&filter_tgl='.$tg.'&jml='.$jml);
            $config['total_rows']            = $jml_hal;

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
            /* -- End Blok Pagination -- */

            if(!empty($hal)){
                   $data['penj'] = $this->db->select('id, id_app, no_nota, kode_nota_dpn, kode_nota_blk, kode_nota_dpn, kode_nota_blk, DATE(tgl_masuk) as tgl_masuk, DATE(tgl_keluar) as tgl_keluar, id_user, keterangan, id_gd_asal, id_gd_tujuan, tipe, status_nota')
                           ->where('status_nota !=', '4')
                           ->limit($config['per_page'],$hal)
                           ->like('no_nota', $fn[0])
                           ->like('DATE(tgl_keluar)', $tp)
//                           ->like('id_user', ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'admin' ? '' : $id_user))
//                           ->like('tgl_masuk', ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'admin' ? '' : date('Y-m-d')))
                           ->order_by('tgl_simpan','desc')
                           ->get('tbl_trans_mutasi')->result();
            }else{
                   $data['penj'] = $this->db->select('id, id_app, no_nota, kode_nota_dpn, kode_nota_blk, kode_nota_dpn, kode_nota_blk, DATE(tgl_masuk) as tgl_masuk, DATE(tgl_keluar) as tgl_keluar, id_user, keterangan, id_gd_asal, id_gd_tujuan, tipe, status_nota')
                           ->where('status_nota !=', '4')
                           ->limit($config['per_page'])
                           ->like('no_nota', $fn[0])
                           ->like('DATE(tgl_keluar)', $tp)
//                           ->like('id_user', ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'admin' ? '' : $id_user))
//                           ->like('tgl_masuk', ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'admin' ? '' : date('Y-m-d')))
                           ->order_by('tgl_simpan','desc')
                           ->get('tbl_trans_mutasi')->result();
            }
            
            $this->pagination->initialize($config);
            
            /* Blok pagination */
            $data['total_rows'] = $config['total_rows'];
            $data['PerPage']    = $config['per_page'];
            $data['pagination'] = $this->pagination->create_links();
            $data['cetak']      = '<button type="button" onclick="window.location.href = \''.base_url('transaksi/cetak_data_penj.php?'.(!empty($nt) ? 'filter_nota='.$nt : '').(!empty($tg) ? '&filter_tgl='.$tg : '').(!empty($tp) ? '&filter_tgl_tempo='.$tp : '').(!empty($cs) ? '&filter_cust='.$cs : '').(!empty($sl) ? '&filter_sales='.$sl : '').(!empty($jml) ? '&jml='.$jml : '')).'\'" class="btn btn-warning"><i class="fa fa-print"></i> Cetak</button>';
            /* --End Blok pagination-- */

            /* Load view tampilan */
            $this->load->view('admin-lte-2/1_atas',$data);
            $this->load->view('admin-lte-2/2_header',$data);
            $this->load->view('admin-lte-2/includes/gudang/data_mutasi_list',$data);
            $this->load->view('admin-lte-2/5_footer',$data);
            $this->load->view('admin-lte-2/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function trans_mutasi() {
        if (akses::aksesLogin() == TRUE) {
            $setting              = $this->db->get('tbl_pengaturan')->row();
            $id                   = $this->input->get('id');
            $id_produk            = $this->input->get('id_produk');
            $userid               = $this->ion_auth->user()->row()->id;

            $data['no_nota']      = general::no_nota('', 'tbl_trans_mutasi', 'no_nota');
            $data['kasir']        = $this->ion_auth->user()->row()->id;
            $data['kasir_id']     = $this->ion_auth->user()->row()->id;
            $data['sess_jual']    = $this->session->userdata('trans_mutasi');
            $data['gudang']       = $this->db->where('status !=', '3')->get('tbl_m_gudang')->result();
            
            if(!empty($data['sess_jual'])){
                $data['sql_kategori']   = $this->db->where('id_pelanggan', $data['sess_jual']['id_pelanggan'])->where('id_kategori', $data['sess_jual']['id_kategori'])->get('tbl_m_pelanggan_diskon')->row();
                $data['sql_produk']     = $this->db->where('id', general::dekrip($id_produk))->get('tbl_m_produk')->row();
                $data['sql_penj']       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_mutasi')->row();
                $data['sql_penj_det']   = $this->cart->contents();
                $data['sql_satuan']     = $this->db->where('id', $data['sql_produk']->id_satuan)->get('tbl_m_satuan')->row();
                $data['sql_produk_stk'] = $this->db->join('tbl_m_gudang', 'tbl_m_gudang.id=tbl_m_produk_stok.id_gudang')->where('id_produk', general::dekrip($id_produk))->get('tbl_m_produk_stok')->result();
                $data['sql_produk_sat']	= $this->db->where('id_produk', general::dekrip($id_produk))->where('jml !=', '0')->order_by('jml', 'desc')->get('tbl_m_produk_satuan')->result();
            }
            
            $this->load->view('admin-lte-2/1_atas',$data);
            $this->load->view('admin-lte-2/2_header',$data);
            $this->load->view('admin-lte-2/includes/gudang/trans_mutasi',$data);
            $this->load->view('admin-lte-2/5_footer',$data);
            $this->load->view('admin-lte-2/6_bawah',$data);
            
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function trans_mutasi_det() {
        if (akses::aksesLogin() == TRUE) {
            $setting               = $this->db->get('tbl_pengaturan')->row();
            $id                    = $this->input->get('id');
            
            $data['sql_penj']      = $this->db->where('id', general::dekrip($id))->get('tbl_trans_mutasi')->row();
            $data['sql_penj_det']  = $this->db->where('id_mutasi', $data['sql_penj']->id)->get('tbl_trans_mutasi_det')->result();

            $this->load->view('admin-lte-2/1_atas',$data);
            $this->load->view('admin-lte-2/2_header',$data);
            $this->load->view('admin-lte-2/includes/gudang/trans_mutasi_det',$data);
            $this->load->view('admin-lte-2/5_footer',$data);
            $this->load->view('admin-lte-2/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_nota_mutasi() {
        if (akses::aksesLogin() == TRUE) {
            $no_nota    = $this->input->post('no_nota');
            $kode_fp    = $this->input->post('kode_fp');
            $tgl_masuk  = $this->input->post('tgl_masuk');
            $tgl_tempo  = $this->input->post('tgl_tempo');
            $gd_asal    = $this->input->post('gd_asal');
            $gd_tujuan  = $this->input->post('gd_tujuan');
            $ket        = $this->input->post('ket');
            $tipe       = $this->input->post('tipe');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            $pengaturan2= $this->db->where('id', $this->ion_auth->user()->row()->id_app)->get('tbl_pengaturan_cabang')->row();

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('no_nota', 'no_nota', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'no_nota' => form_error('no_nota'),
                );

                $this->session->set_flashdata('form_error', $msg_error);
                
                redirect(base_url('gudang/trans_mutasi.php'));
            } else {
                $sql_diskon = $this->db->where('id_pelanggan', $plgn)->where('id_kategori', $kategori)->get('tbl_m_pelanggan_diskon')->row();
                
                $sql_nota   = $this->db->get('tbl_trans_mutasi');
                $noUrut     = $sql_nota->num_rows() + 1;
            
                $data = array(
                    'id_app'       => strtoupper($pengaturan2->id),
                    'tgl_simpan'   => date('Y-m-d H:i:s'),
                    'tgl_masuk'    => $this->tanggalan->tgl_indo_sys($tgl_masuk),
                    'id_user'      => $this->ion_auth->user()->row()->id,
                    'id_gd_asal'   => $gd_asal,
                    'id_gd_tujuan' => $gd_tujuan,
                    'keterangan'   => $ket,
                    'tipe'         => $tipe,
                );
				
				if($gd_asal == $gd_tujuan AND $tipe == '1'){
					$this->session->set_flashdata('gudang', '<div class="alert alert-danger">Gudang asal dan gudang tujuan tidak boleh sama !!</div>');
					redirect(base_url('gudang/trans_mutasi.php'));
				}else{
					$this->session->set_userdata('trans_mutasi', $data);
					redirect(base_url('gudang/trans_mutasi.php?id='.general::enkrip($noUrut)));
				}
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function cart_nota_mutasi_simpan() {
        if (akses::aksesLogin() == TRUE) {
            $no_nota  = $this->input->post('no_nota');
            $id_brg   = $this->input->post('id_barang');
            $kode     = $this->input->post('kode');
            $satuan   = $this->input->post('satuan');
            $qty      = $this->input->post('jml');
            $diskon1  = $this->input->post('disk1');
            $diskon2  = $this->input->post('disk2');
            $diskon3  = $this->input->post('disk3');
            $nomor    = $this->input->post('nomor');
            $hrg_ds   = $this->input->post('harga_ds');
            $harga    = str_replace('.', '', $this->input->post('harga'));
            $potongan = str_replace('.', '', $this->input->post('potongan'));
            $status_gd= $this->ion_auth->user()->row()->status_gudang;

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('kode', 'Kode', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'kode' => form_error('kode'),
                );

                $this->session->set_flashdata('form_error', $msg_error);
                
                redirect(base_url('transaksi/trans_jual.php?id='.general::enkrip($no_nota)));
            } else {
                $sess_jual   = $this->session->userdata('trans_mutasi');
                $sql_brg     = $this->db->where('id', general::dekrip($id_brg))
                                        ->get('tbl_m_produk')->row();
                $sql_brg_nom = $this->db->where('id_produk', $sql_brg->id)
                                        ->where('harga', $harga)
                                        ->get('tbl_m_produk_nominal')->row();
                $sql_gudang  = $this->db->where('id', $sess_jual['id_gd_asal'])->get('tbl_m_gudang')->row(); // cek gudang aktif
                $sql_stok    = $this->db->where('id_produk', $sql_brg->id)->where('id_gudang', $sql_gudang->id)->get('tbl_m_produk_stok')->row(); // cek posisi stok
                
                $sql_satuan  = $this->db->where('id_produk', $sql_brg->id)->where('satuan', $satuan)->get('tbl_m_produk_satuan')->row();
                $sql_satuan3 = $this->db->where('id', $sql_brg->id_satuan)->get('tbl_m_satuan')->row();
                $harga_jual  = (!empty($harga) ? $harga : $sql_satuan->harga); //(!empty($sql_satuan->harga) ? $sql_satuan->harga : $harga)
                
                if(!empty($hrg_ds)){
                    $sql_sat= $this->db->where('id_produk', $sql_brg->id)->where('jml !=', 0)->where('harga !=', 0)->order_by('jml', 'DESC')->get('tbl_m_produk_satuan');
                    
                    $n = 0;
                    foreach ($sql_sat->result() as $sat3){
                        if($sat3->satuan == $satuan){
                            $limit       = $n + 1;
                            $sql_satuan2 = $this->db->where('id_produk', $sql_brg->id)->where('jml !=', 0)->where('harga !=', 0)->order_by('jml', 'DESC')->limit(1,$limit)->get('tbl_m_produk_satuan')->row();
                            
                            $sat_brg    = (!empty($sql_satuan2->satuan) ? $sql_satuan2->satuan : $sql_satuan3->satuanTerkecil);
                            $sat_jual   = (!empty($sql_satuan2->jml) ? $sql_satuan2->jml : 1);
                        }
                        $n++;
                    }
                    
                }else{
                    $sat_brg    = (!empty($satuan) ? $satuan : $sql_satuan3->satuanTerkecil); // (!empty($hrg_ds) ? $sql_satuan2->row()->satuan : );
                    $sat_jual   = (!empty($satuan) ? $sql_satuan->jml : 1); // (!empty($hrg_ds) ? $sql_satuan2->row()->jml : );
                }
                        
                $jml         = $qty;
                $subtotal    = $harga_j * $jml;
                
                
                // Cek di keranjang
                foreach ($this->cart->contents() as $cart){                    
                    // Cek ada datanya kagak?
                    if($sql_brg->kode == $cart['options']['kode'] AND $cart['options']['satuan'] == $satuan){
                        $jml_subtotal      = ($cart['qty'] + $qty);                        
                        $jml_qty           = ($cart['qty'] + $qty);                        
                        
                        if($sql_stok->jml < $jml_subtotal){
                            $this->session->set_flashdata('transaksi', '<div class="alert alert-danger">Jumlah barang, tidak tersedia</div>');
                            redirect(base_url('transaksi/trans_jual.php?id='.$no_nota));
                        }else{
                            $this->cart->update(array('rowid'=>$cart['rowid'], 'qty'=>0));
                        }
                    }
                }
                
                // Cek jml unit dlm satuan terkecil
                $jml_unit = ((isset($jml_qty) ? (int)$jml_qty : $qty) * $sat_jual);
                
                if($sql_stok->jml < $jml_unit AND $sess_jual['tipe'] != '2'){
                    $this->session->set_flashdata('transaksi', '<div class="alert alert-danger">Jumlah barang, tidak tersedia</div>');
                }else{
                    $keranjang = array(
                        'id'      => rand(1,1024).$sql_brg->id,
                        'qty'     => (!empty($jml_qty) ? $jml_qty : $qty),
                        'price'   => '1', // $disk3 => Ambil dari variable harga
                        'name'    => ($sql_brg->status_brg_dep == 1 ? str_replace(array('\'','\\','/'), ' ', $sql_brg->produk.'-['.$nomor.']') : str_replace(array('\'','\\','/'), ' ', $sql_brg->produk)),
                        'options' => array(
                            'no_nota'   => general::dekrip($no_nota),
                            'id_barang' => $sql_brg->id,
                            'id_satuan' => $sql_brg->id_satuan,
                            'id_nominal'=> $sql_brg_nom->id,
                            'satuan'    => $sat_brg,
                            'satuan_ket'=> ($sat_jual != 1 ? ' ('.(!empty($jml_subtotal) ? $jml_qty : $qty) * $sql_satuan->jml.' '.$sql_satuan3->satuanTerkecil.')' : ''),
                            'jml'       => $qty,
                            'jml_satuan'=> ($sat_jual == '0' ? '1' : $sat_jual),
                            'kode'      => $sql_brg->kode,
                            'harga'     => $harga,
                            'disk1'     => (float)$diskon1,
                            'disk2'     => (float)$diskon2,
                            'disk3'     => (float)$diskon3,
                            'potongan'  => (float)$potongan,
                        )
                    );
                    
                    $this->cart->insert($keranjang);
                }
                
                redirect(base_url('gudang/trans_mutasi.php?id='.$no_nota));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function cart_nota_mutasi_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id    = $this->input->get('id');
            $nota  = $this->input->get('no_nota');
            $rute  = $this->input->get('route');
            
            if(!empty($id)){
                $cart = array(
                    'rowid' => general::dekrip($id),
                    'qty'   => 0
                );
                $this->cart->update($cart);
            }
            
            redirect(base_url('gudang/'.(!empty($rute) ? $rute : 'trans_mutasi.php').'?id='.$nota));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_nota_mutasi_batal() {
        if (akses::aksesLogin() == TRUE) {
            $id   = $this->input->get('id');
                        
            $this->session->unset_userdata('trans_mutasi');
            $this->cart->destroy();
            redirect(base_url('gudang/trans_mutasi.php'));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_nota_mutasi_proses() {
        if (akses::aksesLogin() == TRUE) {
            $no_nota    = $this->input->post('no_nota');
            $act        = $this->input->post('act');
            $rute       = $this->input->post('route');
            $cetak      = $this->input->post('cetak');
            $status_gd  = $this->ion_auth->user()->row()->status_gudang;
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

//            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
//
//            $this->form_validation->set_rules('no_nota', 'no_nota', 'required');
//
//            if ($this->form_validation->run() == FALSE) {
//                $msg_error = array(
//                    'no_nota' => form_error('no_nota'),
//                );
//
//                $this->session->set_flashdata('form_error', $msg_error);
//                
//                redirect(base_url('transaksi/trans_jual.php'));
//            } else {
                $trans_jual     = $this->session->userdata('trans_mutasi');
                $trans_jual_det = $this->cart->contents();
                                                
                // Kode Nomor Nota
                $sql_nota       = $this->db->get('tbl_trans_mutasi');
                $noUrut         = $sql_nota->num_rows() + 1;
                $nomer_nota     = $sql_nota->num_rows() + 1;
                $nota           = sprintf("%05s", $noUrut);
                
                // Simpan penjualan ke tabel
                $data_penj = array(
                    'id_app'        => $this->ion_auth->user()->row()->id_app,
                    'no_nota'       => $nota,
                    'tgl_simpan'    => $trans_jual['tgl_simpan'],
                    'tgl_modif'     => '0000-00-00',
                    'tgl_masuk'     => $trans_jual['tgl_masuk'],
                    'id_gd_asal'    => $trans_jual['id_gd_asal'],
                    'id_gd_tujuan'  => $trans_jual['id_gd_tujuan'],
                    'id_user'       => $trans_jual['id_user'],
                    'keterangan'    => $trans_jual['keterangan'],
                    'tipe'          => $trans_jual['tipe'],
                );
                                
                crud::simpan('tbl_trans_mutasi', $data_penj);
                $last_id = crud::last_id();
                
                $tot_gtotal_real = 0;
                $tot_diskon_real = 0;
                foreach ($trans_jual_det as $cart){
                     $sql_brg         = $this->db->where('kode', $cart['options']['kode'])->get('tbl_m_produk')->row();
                     $sql_brg_sat     = $this->db->where('id', $sql_brg->id_satuan)->get('tbl_m_satuan')->row();
                     $sql_brg_dep     = $this->db->where('id_produk', $sql_brg->id)->get('tbl_m_produk_deposit')->row();
                     $sql_brg_nom     = $this->db->where('id', $cart['options']['id_nominal'])->order_by('id', 'DESC')->get('tbl_m_produk_nominal')->row();
                     $sql_disk        = $this->db->where('id_pelanggan', $trans_jual['id_pelanggan'])->where('kode', $cart['options']['kode'])->get('tbl_trans_jual_diskon');
                     
                     $sql_gudang_asl  = $this->db->where('id_gudang', $trans_jual['id_gd_asal'])->where('id_produk', $sql_brg->id)->get('tbl_m_produk_stok')->row(); // Cek gudang aktif dari gudang utama
                     $sql_gudang_7an  = $this->db->where('id_gudang', $trans_jual['id_gd_tujuan'])->where('id_produk', $sql_brg->id)->get('tbl_m_produk_stok')->row(); // Cek gudang aktif dari gudang utama
//                     $sql_gudang_stok = $this->db->where('id_gudang', $sql_gudang->id)->where('id_produk', $sql_brg->id)->get('tbl_m_produk_stok')->row(); // ambil data dari stok tsb
                     
                     /* Cek lagi, sisa stok dari db */
                     if($sql_gudang_asl->jml < 0 AND $trans_jual['tipe'] != '1'){
                        /* Hapus dulu dari database */
                        crud::delete('tbl_trans_mutasi', 'id', $last_id);
                        
                        $this->session->set_flashdata('transaksi', '<div class="alert alert-danger">Stok <b>'.$sql_brg->produk.'</b> dari gudang '.$sql_gudang->gudang.' tidak mencukupi !. Stok tersedia di sistem <b>'.$sql_gudang_stok->jml.' '.$sql_brg_sat->satuanTerkecil.'</b></div>');
                        redirect(base_url('gudang/'.(!empty($rute) ? $rute : 'trans_mutasi').'.php?id='.general::enkrip($last_id)));
                     }else{
                         
                        switch ($trans_jual['tipe']){
                             /* Pindah gudang */
                            case '1':
                                $jml_akhir_stk = $sql_gudang_asl->jml - ($cart['options']['jml_satuan'] * $cart['options']['jml']);
                                $jml_akhir_7an = $sql_gudang_7an->jml + ($cart['options']['jml_satuan'] * $cart['options']['jml']);
				$sql_gudang_ck = $this->db->where('id_produk', $sql_brg->id)->where('id_gudang', $trans_jual['id_gd_tujuan'])->get('tbl_m_produk_stok');
								
                                $data_stk = array(
                                   'tgl_simpan'  => date('Y-m-d H:i'),
                                   'id_produk'   => $sql_brg->id,
                                   'id_gudang'   => $trans_jual['id_gd_tujuan'],
                                   'id_user'     => $this->ion_auth->user()->row()->id,
                                   'jml'         => (int)($cart['options']['jml'] * $cart['options']['jml_satuan']),
                                   'satuan'      => $sql_brg_sat->satuan,
                                   'satuanKecil' => $sql_brg_sat->satuan,
                                   'status'      => '0',
                                );
								
                                if ($sql_gudang_ck->num_rows() > 0) {
                                    crud::update('tbl_m_produk_stok', 'id', $sql_gudang_7an->id, array('jml' => $jml_akhir_stk));
                                } else {
                                    crud::simpan('tbl_m_produk_stok', $data_stk);
                                }

                                crud::update('tbl_m_produk_stok','id', $sql_gudang_asl->id, array('jml'=>$jml_akhir_stk));
                                crud::update('tbl_m_produk_stok','id', $sql_gudang_7an->id, array('jml'=>$jml_akhir_7an));
                                
                                $status = '8';
                                $ket    = 'Mutasi stok antar gudang';
                                break;
                            
                            /* Stok Masuk */
                            case '2':
                                $jml_akhir     = $sql_brg->jml + ($cart['options']['jml_satuan'] * $cart['options']['jml']);
                                $jml_akhir_stk = $sql_gudang_7an->jml + ($cart['options']['jml_satuan'] * $cart['options']['jml']);                             
								$sql_gudang_ck = $this->db->where('id_produk', $sql_brg->id)->where('id_gudang', $trans_jual['id_gd_tujuan'])->get('tbl_m_produk_stok');
								
                                // Simpan sisa barang setelah di kurangi
                                $data_brg = array(
                                   'tgl_modif' => date('Y-m-d H:i'),
                                   'jml'       => (int)$jml_akhir
                                );
                                
                                $data_stk = array(
                                   'tgl_simpan' => date('Y-m-d H:i'),
                                   'id_produk' => $sql_brg->id,
                                   'id_gudang' => $trans_jual['id_gd_tujuan'],
                                   'id_user'   => $this->ion_auth->user()->row()->id,
                                   'jml'       => (int)$jml_akhir_stk,
                                   'satuan'    => $sql_brg_sat->satuan,
                                   'satuanKecil' => $sql_brg_sat->satuan,
                                   'status' => '0',
                                );
                                
                                crud::update('tbl_m_produk','id', $sql_brg->id, array('jml'=>$jml_akhir));
                                
								if($sql_gudang_ck->num_rows() > 0){
									crud::update('tbl_m_produk_stok','id', $sql_gudang_7an->id, array('jml'=>$jml_akhir_stk));
								}else{
									crud::simpan('tbl_m_produk_stok', $data_stk);
								}
                                
                                $status = '2';
                                $ket    = 'Mutasi stok masuk';
                                break;
                            
                             /* Stok Keluar */
                            case '3':
                                $jml_akhir     = $sql_brg->jml - ($cart['options']['jml_satuan'] * $cart['options']['jml']);
                                $jml_akhir_stk = $sql_gudang_asl->jml - ($cart['options']['jml_satuan'] * $cart['options']['jml']);
								
                                                        
                                // Simpan sisa barang setelah di kurangi
                                $data_brg = array(
                                   'tgl_modif' => date('Y-m-d H:i'),
                                   'jml'       => (int)$jml_akhir
                                );
                                
                                $data_stk = array(
                                   'tgl_modif' => date('Y-m-d H:i'),
                                   'jml'       => (int)$jml_akhir_stk
                                );
                                
                                crud::update('tbl_m_produk','id', $sql_brg->id, array('jml'=>$jml_akhir));
                                crud::update('tbl_m_produk_stok','id', $sql_gudang_7an->id, array('jml'=>$jml_akhir_stk));
                                
                                $status = '7';
                                $ket    = 'Mutasi stok keluar';
                                break;
                        }
                     
                        $data_penj_det = array(
                            'id_mutasi'    => $last_id,
                            'no_nota'      => $nota,
                            'tgl_simpan'   => $trans_jual['tgl_simpan'],
                            'id_satuan'    => $cart['options']['id_satuan'],
                            'satuan'       => $cart['options']['satuan'],
                            'keterangan'   => $cart['options']['satuan_ket'],
                            'kode'         => $cart['options']['kode'],
                            'produk'       => $cart['name'],
                            'jml'          => (int)$cart['qty'],
                            'jml_satuan'   => (int)$cart['options']['jml_satuan']
                        );
                        
                        /* Catat log barang keluar ke tabel */
                        $data_penj_hist = array(
                            'tgl_simpan'   => $trans_jual['tgl_masuk'].' '.date('H:i'),
                            'tgl_masuk'    => $trans_jual['tgl_masuk'],
                            'id_gudang'    => $trans_jual['id_gd_tujuan'],
                            'id_produk'    => $sql_brg->id,
                            'id_user'      => $this->ion_auth->user()->row()->id,
                            'id_penjualan' => $last_id,
                            'no_nota'      => $nota,
                            'kode'         => $cart['options']['kode'],
                            'keterangan'   => $ket,
                            'jml'          => (int)$cart['qty'],
                            'jml_satuan'   => (int)$cart['options']['jml_satuan'],
                            'satuan'       => $cart['options']['satuan'],
                            'nominal'      => 0,
                            'status'       => $status
                        );
                        
                        crud::simpan('tbl_trans_mutasi_det', $data_penj_det);
                        crud::simpan('tbl_m_produk_hist', $data_penj_hist);
                        
//                        echo '<pre>';
//                        print_r($data_brg);
//                        echo '<pre>';
//                        
//                        echo '<pre>';
//                        print_r($data_stk);
//                        echo '<pre>';
//                        
//                        echo '<pre>';
//                        print_r($data_penj_det);
//                        echo '<pre>';
//                        
//                        echo '<pre>';
//                        print_r($data_penj_hist);
//                        echo '<pre>';
                        /* -- END -- */
                     }
                     /* -- END -- */
                }

                /* -- Hapus semua session -- */
                $this->session->unset_userdata('trans_mutasi');
                $this->cart->destroy();
                /* -- Hapus semua session -- */

                $this->session->set_flashdata('gudang', '<div class="alert alert-success">Transaksi berhasil disimpan</div>');
                redirect(base_url('gudang/trans_mutasi_det.php?id='.general::enkrip($last_id)));
//            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    


    public function trans_mutasi_print_ex_do() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $aid        = general::dekrip($id);
            $sql        = $this->db->select('id, id_app, no_nota, kode_nota_dpn, kode_nota_blk, kode_nota_dpn, kode_nota_blk, DATE(tgl_masuk) as tgl_masuk, DATE(tgl_keluar) as tgl_keluar, id_user, id_gd_asal, id_gd_tujuan, keterangan, status_nota')->where('tbl_trans_mutasi.id', $aid)->get('tbl_trans_mutasi')->row();
            $sql_det    = $this->db->select('tbl_trans_mutasi_det.id, tbl_trans_mutasi_det.kode, tbl_trans_mutasi_det.produk, tbl_trans_mutasi_det.jml, tbl_trans_mutasi_det.jml_satuan, tbl_trans_mutasi_det.satuan, tbl_trans_mutasi_det.keterangan, tbl_m_satuan.satuanTerkecil as sk, tbl_m_satuan.satuanBesar as sb')->join('tbl_m_satuan', 'tbl_m_satuan.id=tbl_trans_mutasi_det.id_satuan')->where('tbl_trans_mutasi_det.id_mutasi', $aid)->get('tbl_trans_mutasi_det');
            $sql_gd_asl = $this->db->where('id', $sql->id_gd_asal)->get('tbl_m_gudang')->row();
            $sql_gd_7an = $this->db->where('id', $sql->id_gd_tujuan)->get('tbl_m_gudang')->row();
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
                                    
            $objPHPExcel = new PHPExcel();            

            // Font size nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize('13')->setName('Times New Roman');
            $objPHPExcel->getActiveSheet()->getStyle('F1:H1')->getFont()->setSize('11')->setName('Times New Roman');
            $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getFont()->setSize('11')->setName('Times New Roman');
            $objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getFont()->setSize('11')->setName('Times New Roman');
            $objPHPExcel->getActiveSheet()->getStyle('A4:H4')->getFont()->setSize('11')->setName('Times New Roman');
            $objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getFont()->setSize('11')->setName('Times New Roman');
            $objPHPExcel->getActiveSheet()->getStyle('A6:H6')->getFont()->setSize('11')->setName('Times New Roman');
            $objPHPExcel->getActiveSheet()->getStyle('A7:H7')->getFont()->setSize('11')->setName('Times New Roman')->setBold(TRUE);
           // border atas, nama kolom
            $objPHPExcel->getActiveSheet()->getStyle('A7:H7')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
            
            /* CONTENT EXCEL */            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', strtoupper($pengaturan->judul))->mergeCells('A1:E1')
                    ->setCellValue('F1', ucwords(strtolower($pengaturan->kota)).', '.$this->tanggalan->tgl_indo($sql->tgl_masuk));
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A2', strtoupper($pengaturan->alamat))
                    ->setCellValue('F2', 'Gudang Asal')
                    ->setCellValue('G2', ': '.(!empty($sql_gd_asl->gudang) ? strtoupper($sql_gd_asl->gudang) : '-'))->mergeCells('G2:H2');
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3', strtoupper($pengaturan->kota))->mergeCells('A2:E2')
                    ->setCellValue('F3', 'Gudang Tujuan')
                    ->setCellValue('G3', ': '.(!empty($sql_gd_7an->gudang) ? strtoupper($sql_gd_7an->gudang) : '-'))->mergeCells('G3:H3');
            $objPHPExcel->getActiveSheet()->getStyle('F4')->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle('F4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
            $objPHPExcel->getActiveSheet()->getStyle('F4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F4', ucfirst($sql->keterangan))->mergeCells('F4:H6');
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A5', strtoupper($member->nama_toko.(!empty($member->lokasi) ? ' - '.$member->lokasi : '')))->mergeCells('A5:E5')
                    ->setCellValue('F5', '');
            
            $objPHPExcel->getActiveSheet()->getStyle('D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A6', 'MUTASI STOK')->mergeCells('A6:E6');

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getRowDimension('7')->setRowHeight(18.75);
            $objPHPExcel->getActiveSheet()->getStyle('A7:H7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B7:C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D7:E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('G7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('H7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A7', 'No.')
                    ->setCellValue('B7', 'Banyaknya')->mergeCells('B7:C7')
                    ->setCellValue('D7', 'Nama Barang')->mergeCells('D7:F7')
                    ->setCellValue('G7', 'Kode')->mergeCells('G7:H7');
            
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(7);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);   
            
            // Detail barang
            $no    = 1;
            $cell  = 8;
            $cel   = 8;
            foreach ($sql_det->result() as $items){
                $produk  = $this->db->where('kode', $items->kode)->get('tbl_m_produk')->row();
                
                // Format Angka
//                $objPHPExcel->getActiveSheet()->getStyle('G' . $cell.':H'.$cell)->getNumberFormat()->setFormatCode("#.##0");
//                $objPHPExcel->getActiveSheet()->getStyle('G' . $cell.':H'.$cell)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $objPHPExcel->getActiveSheet()->getRowDimension($cell)->setRowHeight(18.75);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$cell.':H'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
                $objPHPExcel->getActiveSheet()->getStyle('F'.$cell)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                
                // Format Alignment
                $objPHPExcel->getActiveSheet()->getStyle('A'.$cell.':H'.$cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                
                $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$cell.':E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                
                $kiri = substr($items->kode, 0, 1);
                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, (!empty($items->keterangan) ? $items->jml : $items->jml))
                            ->setCellValue('C'.$cell, $items->satuan)
                            ->setCellValue('D'.$cell, $items->produk)->mergeCells('D'.$cell.':F'.$cell)
                            ->setCellValue('G'.$cell, ' '.$items->kode)->mergeCells('G'.$cell.':H'.$cell); //number_format($items->subtotal,'0','.',',')
                
                $no++;
                $cell++;
            }
            
            // Maximal baris
            if($sql_det->num_rows() >= 40){
//                $jmlbaris = 8;
                $sell = $cell;
            }else{
                $jmlbaris = 33 - (int) $cell;
                
                // Baris kosong space nota
                for ($i = 0; $i <= $jmlbaris; $i++) {
                    $sell = $cell + $i;
                    
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $sell, '')
                            ->setCellValue('B' . $sell, '')
                            ->setCellValue('C' . $sell, '')
                            ->setCellValue('D' . $sell, '')
                            ->setCellValue('E' . $sell, '');
                } 
            }
                        
//          Font Nota Detail            
            $objPHPExcel->getActiveSheet()->getStyle('A8:H'.$cell)->getFont()->setSize('12')->setName('Times New Roman');
                      
            
            
            // Hitung sell bawah
            $sell2     = $sell + 1;
            $sellbwh1  = $sell2 + 1;
            $sellbwh2  = $sellbwh1 + 1;
            $sellbwh3  = $sellbwh2 + 1;
            $sellbwh4  = $sellbwh3 + 1;
            $sellbwh5  = $sellbwh4 + 1;
            $sellbwh6  = $sellbwh5 + 1;
            $sellbwh7  = $sellbwh6 + 1;
            $sellbwh8  = $sellbwh7 + 1;
            $sellbwh9  = $sellbwh8 + 1;
            $sellbwh10 = $sellbwh9 + 1;
            
            // border bawah
            $objPHPExcel->getActiveSheet()->getStyle('A'.$sell2.':H'.$sell2)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$sell2.':H'.$sellbwh10)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            
            // Subtotal, ppn, grand total
            $objPHPExcel->getActiveSheet()->getStyle('A'.$sell2.':F'.$sell2)->getFont()->setSize('12')->setName('Times New Roman');
            $objPHPExcel->getActiveSheet()->getStyle('G'.$sell2.':H'.$sell2)->getFont()->setSize('12')->setName('Times New Roman');
            
            $objPHPExcel->getActiveSheet()->getStyle('A'.$sell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$sell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$sell2.':H'.$sell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $sell2)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
            $objPHPExcel->getActiveSheet()->getRowDimension($sell2)->setRowHeight(18.75);
//            $objPHPExcel->getActiveSheet()->getStyle('H' . $sell2)->getNumberFormat()->setFormatCode("_(\"Rp\"* #,##0_);_(\"Rp\"* \(#,##0\);_(\"Rp\"* \"-\"??_);_(@_)");
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $sell2, 'Tanda Terima')->mergeCells('A' . $sell2.':C' . $sell2)
                    ->setCellValue('D' . $sell2, '')->mergeCells('D' . $sell2.':F' . $sell2)
                    ->setCellValue('G' . $sell2, 'Petugas')->mergeCells('G' . $sell2.':H' . $sell2);

            $objPHPExcel->getActiveSheet()->getStyle('G'.$sellbwh7.':H'.$sellbwh7)->getFont()->setSize('11')->setName('Times New Roman');
            $objPHPExcel->getActiveSheet()->getStyle('G'.$sellbwh7.':H'.$sellbwh7)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $sellbwh7)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
            $objPHPExcel->getActiveSheet()->getRowDimension($sellbwh7)->setRowHeight(18.75);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $sellbwh7, '( ............................. )')->mergeCells('A' . $sellbwh7.':C' . $sellbwh7)
                    ->setCellValue('G' . $sellbwh7, '( '.strtoupper($this->ion_auth->user($sql->id_user)->row()->first_name).' )')->mergeCells('G' . $sellbwh7.':H' . $sellbwh7);
            
            // border penutup
            $objPHPExcel->getActiveSheet()->getStyle('A'.$sellbwh10)->getFont()->setSize('10')->setName('Times New Roman');
            $objPHPExcel->getActiveSheet()->getStyle('A'.$sellbwh10.':H'.$sellbwh10)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUMDASHED);
            /* END CONTENT EXCEL -- */
            
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('SJ-'.$sql->no_nota);

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
//                    ->setLastModifiedBy("" . ucwords($createBy) . ' [' . strtoupper($namaPerusahaan) . ']')
//                    ->setTitle("Nota Penjualan " . $sql->row()->no_nota . ($sql->row()->cetak == '1' ? ' Copy Customer' : ''))
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://mikhaelfelian.web.id")
                    ->setKeywords("POS")
                    ->setCategory("Untuk mencetak nota dot matrix");



            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="sj-'.strtolower($sql->no_nota).'.xls"');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 15 Feb 1992 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            
            ob_clean();
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');            
            exit;
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function cetak_data_stok(){
        if (akses::aksesLogin() == TRUE) {
            $case = $this->input->get('case');
            
            $data['produk'] = '';

            $this->load->view('admin-lte-2/1_atas', $data);
            $this->load->view('admin-lte-2/2_header', $data);
            $this->load->view('admin-lte-2/includes/gudang/cetak_data_stok', $data);
            $this->load->view('admin-lte-2/5_footer', $data);
            $this->load->view('admin-lte-2/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();            
        }
    }
    
    public function pdf_data_stok(){
        if (akses::aksesLogin() == TRUE) {
            $filter_merk     = $this->input->get('filter_merk');
            $filter_kode     = $this->input->get('filter_kode');
            $filter_produk   = $this->input->get('filter_produk');
            $filter_hpp      = $this->input->get('filter_hpp');
            $filter_harga    = $this->input->get('filter_harga');
            $jml             = $this->input->get('jml');
            
            
            $sql    = $this->db->select('DATE(tgl_modif) as tgl_modif, kode, produk, jml, harga_jual')
                               ->like('kode', $filter_kode, 'both')
                               ->like('produk', $filter_produk, 'both')
                               ->like('harga_beli', $filter_hpp, 'both')
                               ->like('harga_jual', $filter_harga, 'both')
                               ->like('id_merk', $filter_merk, 'both')
                               ->order_by('produk', 'asc')
                               ->get('tbl_m_produk')->result();
            
            $setting = $this->db->get('tbl_pengaturan')->row();

            $judul = "LAPORAN DATA STOK";

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
            $this->fpdf->Cell(1.5, .5, 'No', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2.5, .5, 'Tgl Masuk', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(4, .5, 'Kode', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(9, .5, 'Produk', 1, 0, 'C', TRUE);
            $this->fpdf->Cell(2, .5, 'Stok', 1, 0, 'C', TRUE);
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
                    $jml     = $this->db->select('SUM(stok) as jml')->where('id_produk', $produk->id)->get('tbl_m_produk_stok')->row();
                    $jml_brg = $jml_brg + (!empty($jml->jml) ? $jml->jml : $produk->jml);

                    $this->fpdf->Cell(1.5, .5, $no, 1, 0, 'C', $fill);
                    $this->fpdf->Cell(2.5, .5, ($produk->tgl_modif != '0000-00-00' ? $this->tanggalan->tgl_indo($produk->tgl_modif) : ''), 1, 0, 'C', $fill);
                    $this->fpdf->Cell(4, .5, $produk->kode, 1, 0, 'L', $fill);
                    $this->fpdf->Cell(9, .5, $produk->produk, 1, 0, 'L', $fill);
                    $this->fpdf->Cell(2, .5, (!empty($jml->jml) ? $jml->jml : $produk->jml), 1, 0, 'C', $fill);
                    $this->fpdf->Ln();

                    $fill = !$fill;
                    $no++;
                }

                $this->fpdf->SetFont('Arial', 'B', '10');
                $this->fpdf->Cell(17, .5, 'Total', 1, 0, 'R', $fill);
                $this->fpdf->Cell(2, .5, $jml_brg, 1, 0, 'C', $fill);
//                $this->fpdf->Cell(2.5, .5, general::format_angka($tot), 1, 0, 'R', $fill);
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

            $this->fpdf->Output('lap_data_stok_' . date('YmdHm') . '.pdf', $type);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();            
        }
    }
    
    
    
    public function data_po_list() {
        if (akses::aksesLogin() == TRUE) {
            /* -- Grup hak akses -- */
            $grup        = $this->ion_auth->get_users_groups()->row();
            $id_user     = $this->ion_auth->user()->row()->id;
            $pengaturan  = $this->db->get('tbl_pengaturan')->row();

            /* -- Blok Filter -- */
            $query   = $this->input->get('q');
            $hal     = $this->input->get('halaman');
            $jml     = $this->input->get('jml');

            $nt = $this->input->get('filter_nota');
            $tg = $this->input->get('filter_tgl');
            $lk = $this->input->get('filter_lokasi');
            $sl = $this->input->get('filter_supplier');
            $sn = $this->input->get('filter_status');
            $sb = $this->input->get('filter_bayar');
            /* -- End Blok Filter -- */

            /* -- jml halaman pada list -- */
            if(akses::hakSA() == TRUE OR akses::hakAdmin() == TRUE){
                $jml_hal = (!empty($jml) ? $jml  : $this->db->like('no_nota', $nt)->like('DATE(tgl_masuk)', $tg)->like('id_user', $sl)->like('status_nota', $sn)->get('tbl_trans_beli')->num_rows());
            }else{
                /* -- Hitung jumlah halaman -- */
                $jml_hal = (!empty($jml) ? $jml  : $this->db->where('id_user', $id_user)->like('no_nota', $nt)->like('DATE(tgl_masuk)', $tg)->like('status_nota', $sn)->get('tbl_trans_beli')->num_rows());
            }

            /* -- Form Error -- */
            $data['hasError']                = $this->session->flashdata('form_error');

            /* -- Blok Pagination -- */
            $config['base_url']              = base_url('gudang/data_po_list.php?filter_nota='.$tg.'&filter_tgl='.$tg.'&filter_sales='.$sl.'&filter_status='.$sn.'&jml='.$jml);
            $config['total_rows']            = $jml_hal;

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
            /* -- End Blok Pagination -- */
            

            if(!empty($hal)){
                   $data['penj'] = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.no_nota, DATE(tbl_trans_beli.tgl_masuk) as tgl_masuk, DATE(tbl_trans_beli.tgl_bayar) as tgl_bayar, DATE(tbl_trans_beli.tgl_keluar) as tgl_keluar, tbl_trans_beli.jml_total, tbl_trans_beli.jml_retur, tbl_trans_beli.jml_gtotal, tbl_trans_beli.id_user, tbl_trans_beli.id_supplier, tbl_trans_beli.status_nota, tbl_trans_beli.status_bayar, tbl_trans_beli.status_penerimaan')
                           ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli.id_supplier', 'left')
                           ->limit($config['per_page'],$hal)
                           ->like('tbl_m_supplier.nama', $sl)
                           ->like('tbl_trans_beli.no_nota', $nt)
                           ->like('DATE(tbl_trans_beli.tgl_masuk)', $tg)
                           ->like('tbl_trans_beli.status_bayar', $sb)
                           ->order_by('tbl_trans_beli.tgl_masuk','desc')
                           ->get('tbl_trans_beli')->result();
            }else{
                   $data['penj'] = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.no_nota, DATE(tbl_trans_beli.tgl_masuk) as tgl_masuk, DATE(tbl_trans_beli.tgl_bayar) as tgl_bayar, DATE(tbl_trans_beli.tgl_keluar) as tgl_keluar, tbl_trans_beli.jml_total, tbl_trans_beli.jml_retur, tbl_trans_beli.jml_gtotal, tbl_trans_beli.id_user, tbl_trans_beli.id_supplier, tbl_trans_beli.status_nota, tbl_trans_beli.status_bayar, tbl_trans_beli.status_penerimaan')
                           ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli.id_supplier', 'left')
                           ->limit($config['per_page'])
                           ->like('tbl_m_supplier.nama', $sl)
                           ->like('tbl_trans_beli.no_nota', $nt)
                           ->like('DATE(tbl_trans_beli.tgl_masuk)', $tg)
                           ->like('tbl_trans_beli.status_bayar', $sb)
                           ->order_by('tbl_trans_beli.tgl_masuk','desc')
                           ->get('tbl_trans_beli')->result();
            }
            
            $this->pagination->initialize($config);
            
            /* Blok pagination */
            $data['total_rows'] = $config['total_rows'];
            $data['PerPage']    = $config['per_page'];
            $data['pagination'] = $this->pagination->create_links();
            /* --End Blok pagination-- */

            /* Load view tampilan */
            $this->load->view('admin-lte-2/1_atas',$data);
            $this->load->view('admin-lte-2/2_header',$data);
            $this->load->view('admin-lte-2/includes/gudang/data_po_list',$data);
            $this->load->view('admin-lte-2/5_footer',$data);
            $this->load->view('admin-lte-2/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function trans_po_det() {
        if (akses::aksesLogin() == TRUE) {
            $setting               = $this->db->get('tbl_pengaturan')->row();
            $id                    = $this->input->get('id');
            
            $data['sql_beli']      = $this->db->where('id', general::dekrip($id))->get('tbl_trans_beli')->row();
            $data['sql_beli_det']  = $this->db->select('DATE(tgl_terima) as tgl_terima, id, id_user, id_produk, kode, produk, jml, jml_satuan, jml_diterima, satuan, keterangan, status_brg')->where('id_pembelian', $data['sql_beli']->id)->get('tbl_trans_beli_det')->result();
            $data['sql_beli_plat'] = $this->db->select('id, DATE(tgl_simpan) as tgl_simpan, HOUR(tgl_simpan) as jam, MINUTE(tgl_simpan) as menit, id_platform, platform, nominal, keterangan')->where('tbl_trans_beli_plat.id_pembelian', $data['sql_beli']->id)->get('tbl_trans_beli_plat')->result();
            $data['sql_supplier']  = $this->db->where('id', $data['sql_beli']->id_supplier)->get('tbl_m_supplier')->row();

            $this->load->view('admin-lte-2/1_atas',$data);
            $this->load->view('admin-lte-2/2_header',$data);
            $this->load->view('admin-lte-2/includes/gudang/trans_po_det',$data);
            $this->load->view('admin-lte-2/5_footer',$data);
            $this->load->view('admin-lte-2/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function trans_po_terima() {
        if (akses::aksesLogin() == TRUE) {
            $setting               = $this->db->get('tbl_pengaturan')->row();
            $id                    = $this->input->get('id');
            
            $data['sql_beli']      = $this->db->where('id', general::dekrip($id))->get('tbl_trans_beli')->row();
            $data['sql_beli_det']  = $this->db->select('DATE(tgl_terima) as tgl_terima, id, id_user, id_satuan, id_produk, kode, produk, jml, jml_satuan, jml_diterima, satuan, keterangan, status_brg')->where('id_pembelian', $data['sql_beli']->id)->get('tbl_trans_beli_det')->result();
            $data['sql_beli_plat'] = $this->db->select('id, DATE(tgl_simpan) as tgl_simpan, HOUR(tgl_simpan) as jam, MINUTE(tgl_simpan) as menit, id_platform, platform, nominal, keterangan')->where('tbl_trans_beli_plat.id_pembelian', $data['sql_beli']->id)->get('tbl_trans_beli_plat')->result();
            $data['sql_supplier']  = $this->db->where('id', $data['sql_beli']->id_supplier)->get('tbl_m_supplier')->row();
            $data['sql_gudang']    = $this->db->get('tbl_m_gudang')->result();

            $this->load->view('admin-lte-2/1_atas',$data);
            $this->load->view('admin-lte-2/2_header',$data);
            $this->load->view('admin-lte-2/includes/gudang/trans_po_terima',$data);
            $this->load->view('admin-lte-2/5_footer',$data);
            $this->load->view('admin-lte-2/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_po_terima() {
        if (akses::aksesLogin() == TRUE) {
            $id      = $this->input->post('id');
            $nota    = $this->input->post('no_nota');
            $tgl_trm = $this->input->post('tgl_terima');
            $jml_trm = $this->input->post('jml_terima');
            $gudang  = $this->input->post('gudang');
            $setting = $this->db->get('tbl_pengaturan')->row();

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'Kode Barang', 'required');
            $this->form_validation->set_rules('gudang', 'Gudang', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'     => form_error('id'),
                    'gd'     => form_error('gudang'),
                );

                $this->session->set_flashdata('form_error', $msg_error);
                redirect(base_url('gudang/trans_po_terima.php?id='.$nota));
            } else {
                $sql_cek        = $this->db->where('id', general::dekrip($id))->get('tbl_trans_beli_det')->row();
                $sql_bli        = $this->db->where('id', $sql_cek->id_pembelian)->get('tbl_trans_beli')->row();
                $sql_cek_brg    = $this->db->where('id', $sql_cek->id_produk)->get('tbl_m_produk')->row();
                $sql_cek_sat    = $this->db->where('id', $sql_cek->id_satuan)->get('tbl_m_satuan')->row();
                $tgl_diterima   = explode('/', $tgl_trm);
                $jml_terima     = $sql_cek->jml_diterima + $jml_trm;
                $jml_stok       = ($sql_cek_brg->jml < 0 ? $jml_trm : $sql_cek_brg->jml + $jml_trm);
                $jml_kurang     = ($sql_cek->jml * $sql_cek->jml_satuan) - $jml_terima;
                
                $hrg_pcs        = $sql_cek->subtotal / ($sql_cek->jml * $sql_cek->jml_satuan);
                $hrg_ppn        = ($sql_bli->status_ppn == '1' ? ($setting->jml_ppn / 100) * $hrg_pcs : 0);
                $hrg_pcs_akhir  = $hrg_pcs + $hrg_ppn;
                                
                // Update data diterima
                $data_penj = array(
                    'tgl_terima'   => (!empty($tgl_trm) ? $this->tanggalan->tgl_indo_sys($tgl_trm).' '.date('H:i:s') : date('Y-m-d H:i:s')),
                    'jml_diterima' => (int)$jml_terima,
                );
                
                // Simpan stok barang
                $data_brg = array(
                    'tgl_modif'      => date('Y-m-d H:i:s'),
                    'jml'            => $jml_stok,
                    'harga_beli'     => $hrg_pcs_akhir,
                    'harga_beli_ppn' => $hrg_ppn,
                );
                
                // Simpan stok awal barang
                $data_brg_log = array(
                    'tgl_simpan' => $this->tanggalan->tgl_indo_sys($tgl_trm).' '.date('H:i:s'),
                    'id_produk'  => $sql_cek_brg->id,
                    'jml'        => $jml_trm,
                    'jml_satuan' => 1,
                    'satuan'     => $sql_cek_sat->satuanTerkecil,
                    'harga'      => $sql_cek->harga,
                    'keterangan' => 'Pembelian '.anchor(base_url('transaksi/trans_beli_det.php?id='.general::enkrip($sql_cek->id_pembelian)), $sql_cek->no_nota),
                );

                $data_brg_hist = array(
                    'tgl_simpan'  => (!empty($tgl_trm) ? $this->tanggalan->tgl_indo_sys($tgl_trm) : date('Y-m-d')).' '.date('H:i:s'),
                    'tgl_masuk'   => (!empty($tgl_trm) ? $this->tanggalan->tgl_indo_sys($tgl_trm) : date('Y-m-d')),
                    'id_produk'   => $sql_cek_brg->id,
                    'id_user'     => $this->ion_auth->user()->row()->id,
                    'id_gudang'   => $gudang,
                    'id_pembelian'=> $sql_cek->id_pembelian,
                    'id_supplier' => $sql_bli->id_supplier,
                    'kode'        => $sql_cek_brg->kode,
                    'no_nota'     => $sql_cek->no_nota,
                    'jml'         => $jml_trm,
                    'jml_satuan'  => 1,
                    'satuan'      => (!empty($sql_cek_sat->satuanTerkecil) ? $sql_cek_sat->satuanTerkecil : 'PCS'),
                    'nominal'     => $hrg_pcs_akhir,
                    'keterangan'  => 'Pembelian '.$sql_bli->no_nota,
                    'status'      => '1',
                );
                
                // Jika jumlah kurang lebih dari 0, update
                if($jml_kurang >= 0){
                    $sql_cek_stok = $this->db->where('id_produk', $sql_cek_brg->id)->where('id_gudang', $gudang)->get('tbl_m_produk_stok');
                    
                    if($sql_cek_stok->num_rows() > 0){
                        $stoknya    = $sql_cek_stok->row();
                        $stok       = $jml_trm + $stoknya->jml;
                       
                       // Simpan stok gudang
                       $data_gudang_stok = array(
                           'tgl_modif' => date('Y-m-d H:i:s'),
                           'id_user'    => $this->ion_auth->user()->row()->id,
                           'id_gudang'  => $gudang,
                           'id_produk'  => $sql_cek_brg->id,
                           'jml'        => $stok,
                           'jml_satuan' => 1,
                           'satuanKecil'=> (!empty($sql_cek_sat->satuanTerkecil) ? $sql_cek_sat->satuanTerkecil : 'PCS'),
                           'status'     => '0'
                       );
                       
                       crud::update('tbl_m_produk_stok', 'id', $stoknya->id, $data_gudang_stok);
                    }else{
                        $stoknya    = $sql_cek_stok->row();
                        $stok       = $jml_trm;
                       
                       // Simpan stok gudang
                       $data_gudang_stok = array(
                           'tgl_simpan' => date('Y-m-d H:i:s'),
                           'id_user'    => $this->ion_auth->user()->row()->id,
                           'id_gudang'  => $gudang,
                           'id_produk'  => $sql_cek_brg->id,
                           'jml'        => $stok,
                           'jml_satuan' => 1,
                           'satuanKecil'=> (!empty($sql_cek_sat->satuanTerkecil) ? $sql_cek_sat->satuanTerkecil : 'PCS'),
                           'status'     => '0'
                       );
                       
                       crud::simpan('tbl_m_produk_stok', $data_gudang_stok);
                    }                    
                    
                    crud::update('tbl_m_produk', 'id', $sql_cek_brg->id, $data_brg);
                    crud::update('tbl_trans_beli_det', 'id', $sql_cek->id, $data_penj);
                    crud::simpan('tbl_m_produk_saldo', $data_brg_log);
                    crud::simpan('tbl_m_produk_hist', $data_brg_hist);
                    
                    $sql_cek_mts = $this->db->get('tbl_trans_mutasi');
                    $nm          = $sql_cek_mts->num_rows() + 1;
                    $no_mutasi   = sprintf("%05s", $nm);
                    
                    $data_trx_mts = array(
                        'tgl_simpan'    => (!empty($tgl_trm) ? $this->tanggalan->tgl_indo_sys($tgl_trm) : date('Y-m-d')).' '.date('H:i:s'),
                        'tgl_modif'     => '0000-00-00 00:00:00',
                        'tgl_masuk'     => (!empty($tgl_trm) ? $this->tanggalan->tgl_indo_sys($tgl_trm) : date('Y-m-d')),
                        'id_user'       => $this->ion_auth->user()->row()->id,
                        'id_app'        => $setting->id_app,
                        'id_gd_asal'    => 0,
                        'id_gd_tujuan'  => $gudang,
                        'id_gd_tujuan'  => $gudang,
                        'no_nota'       => $no_mutasi,
                        'keterangan'    => 'Pembelian '.$sql_cek->no_nota,
                        'tipe'          => '2',
                    );
                    
                    crud::simpan('tbl_trans_mutasi', $data_trx_mts);
                    $last_id = crud::last_id();
                    
                        $data_trx_mts_det = array(
                            'id_mutasi'    => $last_id,
                            'no_nota'      => $no_mutasi,
                            'tgl_simpan'   => (!empty($tgl_trm) ? $this->tanggalan->tgl_indo_sys($tgl_trm) : date('Y-m-d')).' '.date('H:i:s'),
                            'id_satuan'    => $sql_cek_sat->id,
                            'satuan'       => (!empty($sql_cek_sat->satuanTerkecil) ? $sql_cek_sat->satuanTerkecil : 'PCS'),
                            'keterangan'   => '',
                            'kode'         => $sql_cek->kode,
                            'produk'       => $sql_cek->produk,
                            'jml'          => (int)$jml_trm,
                            'jml_satuan'   => 1
                        );
                    crud::simpan('tbl_trans_mutasi_det', $data_trx_mts_det);
                    
                    $this->session->set_flashdata('gudang', '<div class="alert alert-success">Data stok disimpan</div>');
                }else{
                    $this->session->set_flashdata('gudang', '<div class="alert alert-danger">Data stok tidak sesuai</div>');
                }
//                
//                echo '<pre>';
//                print_r($data_penj);
//                echo '</pre>';
                
//                echo '<pre>';
//                print_r($data_brg_hist);
//                echo '</pre>';
                
                redirect(base_url('gudang/trans_po_terima.php?id='.$nota));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_po_terima_finish() {
        if (akses::aksesLogin() == TRUE) {
            $id      = $this->input->get('id');            
            $sql_cek     = $this->db->where('id', general::dekrip($id))->get('tbl_trans_beli')->row();
            
            // Jika jumlah kurang lebih dari 0, update
            if(!empty($id)){
                crud::update('tbl_trans_beli', 'id', $sql_cek->id, array('id_penerima' => $this->ion_auth->users()->row()->id, 'status_penerimaan' => '3'));
            }
            
            $this->session->set_flashdata('gudang', '<div class="alert alert-success">Data Penerimaan Selesai</div>');                
            redirect(base_url('gudang/trans_po_terima.php?id='.$id));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_po_terima_reset() {
        if (akses::aksesLogin() == TRUE) {
            $id          = $this->input->get('id');            
            $sql_cek     = $this->db->where('id', general::dekrip($id))->get('tbl_trans_beli')->row();
            $sql_cek_det = $this->db->where('id_pembelian', general::dekrip($id))->get('tbl_trans_beli_det')->result();

            foreach ($sql_cek_det as $det){
                $sql_prod      = $this->db->where('id', $det->id_produk)->get('tbl_m_produk')->row();
                $sql_prod_hist = $this->db->where('id_produk', $det->id_produk)->like('keterangan', $det->no_nota)->get('tbl_m_produk_hist')->row();
                $sql_cek_hist  = $this->db->where('id_produk', $det->id_produk)->like('no_nota', $det->no_nota)->get('tbl_m_produk_hist')->result();
                $jml_produk    = $sql_prod->jml - $det->jml_diterima;
                
                /* Cek penerimaan msg2 gudang */
                foreach ($sql_cek_hist as $hist){
                    $sql_stok   = $this->db->where('id_produk', $det->id_produk)->where('id_gudang', $hist->id_gudang)->get('tbl_m_produk_stok')->row();
                    $stok_akhir = $sql_stok->jml - $hist->jml;
                    
                    $this->db->where('id_produk', $det->id_produk)->where('id_gudang', $sql_prod_hist->id_gudang)->update('tbl_m_produk_stok', array('jml'=>$stok_akhir));
                    crud::delete('tbl_m_produk_hist', 'id', $hist->id);
                }
                
                $sql_stok_sum = $this->db->select_sum('jml')->where('id_produk', $det->id_produk)->get('tbl_m_produk_stok')->row();
                
                /* Data reset penerimaan */
                $beli_det = array(
                    'tgl_terima'    => '0000-00-00',
                    'jml_diterima'  => '0',
                );

                crud::update('tbl_m_produk', 'id', $sql_prod_hist->id_produk, array('jml'=>$sql_stok_sum->jml));
                crud::update('tbl_trans_beli_det', 'id', $det->id, $beli_det);
            }

            crud::update('tbl_trans_beli', 'id', $sql_prod_hist->id_pembelian, array('status_penerimaan'=>'0'));

            $this->session->set_flashdata('gudang', '<div class="alert alert-success">Data Penerimaan Selesai</div>');                
            redirect(base_url('gudang/trans_po_terima.php?id='.$id));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_cari_stok() {
        if (akses::aksesLogin() == TRUE) {
            $kode = str_replace(' ', '', $this->input->post('kode'));
            $brcde= $this->input->post('barcode');
            $prod = $this->input->post('produk');
            $sa   = $this->input->post('sa');
            $mrk  = $this->input->post('merk');
            $lok  = $this->input->post('lokasi');
            
            $where  = "(tbl_m_produk.kode LIKE '%".$kode."%' OR tbl_m_produk.barcode LIKE '%".$kode."%' OR tbl_m_produk.id_merk LIKE '".$mrk."')";
            // $where2 = "(tbl_m_produk.jml LIKE '%".$sa."')";

            $jml    = $this->db
//                           ->where($where)
                           ->like('kode', $kode)
                           ->like('barcode', $brcde, (!empty($brcde) ? 'none' : ''))
                           ->like('produk', $prod)
                           ->like('id_lokasi', $lok, (!empty($lok) ? 'none' : ''))
                           ->like('id_merk', $mrk, (!empty($mrk) ? 'none' : ''))
//                           ->like('jml', $sa, (!empty($sa) ? 'none' : ''))
                           ->get('tbl_m_produk')->num_rows();
            
            if($jml > 0){
                redirect(base_url('gudang/data_stok_list.php?'.(!empty($kode) ? 'filter_kode='.$kode : '').(!empty($brcde) ? 'filter_barcode='.$brcde : '').(!empty($mrk) ? 'filter_merk='.$mrk : '').(!empty($lok) ? 'filter_lokasi='.$lok : '').(!empty($prod) ? '&filter_produk='.$prod : '').(!empty($sa) ? '&filter_stok='.$sa : '').(!empty($hpp) ? '&filter_hpp='.$hpp : '').(!empty($hrga) ? '&filter_harga='.$hrga : '').'&jml='.$jml));
            }else{
                redirect(base_url('gudang/data_stok_list.php'));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_cari_mutasi() {
        if (akses::aksesLogin() == TRUE) {
            $no_nota   = $this->input->post('no_nota');
            $lokasi    = $this->input->post('cabang');
            $t_nota    = $this->input->post('tgl');
            $t_bayar   = $this->tanggalan->tgl_indo_sys($this->input->post('tgl_bayar'));
            $t_tempo   = $this->input->post('tgl_tempo');
            $customer  = $this->input->post('customer');
            $sales     = $this->input->post('sales');
            $id_user   = $this->ion_auth->user()->row()->id;
            $id_grup   = $this->ion_auth->get_users_groups()->row();
            
            
            $tg_nota  = explode('/', $t_nota);
            $tgl_nota = $tg_nota[2].'-'.$tg_nota[0].'-'.$tg_nota[1];
            $tg_tempo = explode('/', $t_tempo);
            $tgl_tempo= $tg_tempo[2].'-'.$tg_tempo[0].'-'.$tg_tempo[1];
            $nota      = explode('/', $no_nota);
            
//            $sql = $this->db->select('no_nota, kode_nota_dpn, kode_nota_blk, kode_nota_dpn, kode_nota_blk, DATE(tgl_masuk) as tgl_masuk, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, jml_total, jml_gtotal, ppn, jml_ppn, id_user, id_sales, id_pelanggan, status_nota, status_bayar')
//                           ->like('no_nota', substr($nota[0], 1))
////                           ->or_like('kode_nota_dpn', $nt, 'before')
////                           ->or_like('kode_nota_blk', $nt, 'after')
//                           ->like('DATE(tgl_masuk)', ($tgl_nota != '--' ? $tgl_nota : ''))
//                           ->like('DATE(tgl_keluar)', ($tgl_tempo != '--' ? $tgl_tempo : ''))
//                           ->like('id_pelanggan', $customer)
//                           ->like('id_sales', $sales)
//                           ->like('id_user', ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'owner' ? '' : $id_user))
//                           ->order_by('no_nota','desc')
//                           ->get('tbl_trans_jual')->num_rows();

            redirect(base_url('gudang/data_mutasi.php?'.(!empty($no_nota) ? 'filter_nota='. substr($no_nota, 1) : '').(!empty($t_nota) ? '&filter_tgl='.$tgl_nota : '').(!empty($t_tempo) ? '&filter_tgl_tempo='.$tgl_tempo : '').(!empty($customer) ? '&filter_cust='.$customer : '').(!empty($sales) ? '&filter_sales='.$sales : '').(!empty($t_bayar) ? '&filter_tgl_bayar='.$t_bayar : '')));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    

    public function ex_data_stok(){
        if (akses::aksesLogin() == TRUE) {
            $filter_kode     = $this->input->get('filter_kode');
            $filter_produk   = $this->input->get('filter_produk');
            $filter_hpp      = $this->input->get('filter_hpp');
            $filter_harga    = $this->input->get('filter_harga');
            $filter_stok     = $this->input->get('filter_stok');
            $filter_merk     = $this->input->get('filter_merk');
            $filter_lokasi   = $this->input->get('filter_lokasi');
            
            if(!empty($filter_stok)){
                $sql = $this->db->select('id, id_merk, id_satuan, kode, barcode, produk, jml, harga_beli, harga_jual, harga_grosir')
                                ->where('jml <', $filter_stok)
                                ->order_by(!empty($sort_type) ? $sort_type : 'produk', (isset($sort_order) ? $sort_order : 'asc'))
                                ->get('tbl_m_produk')->result();
            }else{
                $sql = $this->db->select('id, id_merk, id_satuan, kode, barcode, produk, jml, harga_beli, harga_jual, harga_grosir')
                                ->like('kode', $filter_kode)
                                ->like('produk', $filter_produk)
                                ->like('id_merk', $filter_merk, (!empty($filter_merk) ? 'none' : ''))
//                            ->where('id_merk', '0')
                                ->like('id_lokasi', $filter_lokasi, (!empty($filter_lokasi) ? 'none' : ''))
//                            ->like('jml', $filter_stok)
                                ->order_by(!empty($sort_type) ? $sort_type : 'produk', (isset($sort_order) ? $sort_order : 'asc'))
                                ->get('tbl_m_produk')->result();
            }
                       
            $objPHPExcel = new PHPExcel();
            
            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(TRUE);
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No.')
                    ->setCellValue('B1', 'Kode')
                    ->setCellValue('C1', 'Barcode')
                    ->setCellValue('D1', 'Nama Barang')
                    ->setCellValue('E1', 'Jml')
                    ->setCellValue('F1', 'Harga Beli')
                    ->setCellValue('G1', 'Harga Jual')
                    ->setCellValue('H1', 'Harga Grosir')
                    ->setCellValue('I1', 'MERK')
                    ->setCellValue('J1', 'SATUAN');
            
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(7);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);  
            
            if(!empty($sql)){
                $no    = 1;
                $cell  = 2;
                foreach ($sql as $produk){
                    $merk = $this->db->where('id', $produk->id_merk)->get('tbl_m_merk')->row();
                    $sat  = $this->db->where('id', $produk->id_satuan)->get('tbl_m_satuan')->row();
                    $stk  = $this->db->select_sum('jml')->where('id_produk', $produk->id)->get('tbl_m_produk_stok')->row();
                    
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':D'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$cell.':G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$cell, $val,PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$cell.':H'.$cell)->getNumberFormat()->setFormatCode("_(\"\"* #,##0_);_(\"\"* \(#,##0\);_(\"\"* \"-\"??_);_(@_)");
                    
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $produk->kode.' ')
                            ->setCellValue('C'.$cell, (!empty($produk->barcode) ? $produk->barcode : $produk->kode))
                            ->setCellValue('D'.$cell, $produk->produk)
                            ->setCellValue('E'.$cell, (!empty($stk->jml) ? $stk->jml : '0'))
                            ->setCellValue('F'.$cell, ($produk->harga_beli))
                            ->setCellValue('G'.$cell, ($produk->harga_jual))
                            ->setCellValue('H'.$cell, ($produk->harga_grosir))
                            ->setCellValue('I'.$cell, strtoupper(str_replace(array('/','/\/','-','+','&','=','_'),' ', $merk->merk)).' ')
                            ->setCellValue('J'.$cell, strtoupper(str_replace(array('/','/\/','-','+','&','=','_'),' ', $sat->satuanTerkecil)).' ');
                    
                    $no++;
                    $cell++;
                }                
            }
            
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
            header('Content-Disposition: attachment;filename="data_stok_'.date('ymd').'.xls"');

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

    public function ex_data_stok_brcd(){
        if (akses::aksesLogin() == TRUE) {
            $filter_kode     = $this->input->get('filter_kode');
            $filter_produk   = $this->input->get('filter_produk');
            $filter_hpp      = $this->input->get('filter_hpp');
            $filter_harga    = $this->input->get('filter_harga');
            $filter_stok     = $this->input->get('filter_stok');
            $filter_merk     = $this->input->get('filter_merk');
            $filter_lokasi   = $this->input->get('filter_lokasi');
            
            if(!empty($filter_stok)){
                $sql = $this->db->select('id, id_merk, id_satuan, kode, barcode, produk, jml, harga_beli, harga_jual, harga_grosir')
                                ->where('jml <', $filter_stok)
                                ->order_by(!empty($sort_type) ? $sort_type : 'produk', (isset($sort_order) ? $sort_order : 'asc'))
                                ->get('tbl_m_produk')->result();
            }else{
                $sql = $this->db->select('id, id_merk, id_satuan, kode, barcode, produk, jml, harga_beli, harga_jual, harga_grosir')
                                ->like('kode', $filter_kode)
                                ->like('produk', $filter_produk)
                                ->like('id_merk', $filter_merk, (!empty($filter_merk) ? 'none' : ''))
//                            ->where('id_merk', '0')
                                ->like('id_lokasi', $filter_lokasi, (!empty($filter_lokasi) ? 'none' : ''))
//                            ->like('jml', $filter_stok)
                                ->order_by(!empty($sort_type) ? $sort_type : 'produk', (isset($sort_order) ? $sort_order : 'asc'))
                                ->get('tbl_m_produk')->result();
            }
                       
            $objPHPExcel = new PHPExcel();
            
            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(TRUE);
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'kode')
                    ->setCellValue('B1', 'barcode')
                    ->setCellValue('C1', 'barang')
                    ->setCellValue('D1', 'harga');
            
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(45);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            
            if(!empty($sql)){
                $no    = 1;
                $cell  = 2;
                foreach ($sql as $produk){
                    $stk  = $this->db->select_sum('jml')->where('id_produk', $produk->id)->get('tbl_m_produk_stok')->row();
                    
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell.':C'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$cell.':D'.$cell)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
                    
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $produk->kode)
                            ->setCellValue('B'.$cell, (!empty($produk->barcode) ? $produk->barcode.' ' : ' '))
                            ->setCellValue('C'.$cell, ltrim($produk->produk, ' '))
                            ->setCellValue('D'.$cell, (int)$produk->harga_jual.' ');
                    
                    $no++;
                    $cell++;
                }                
            }
            
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('st');

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
                    ->setDescription("Kunjungi https://tigerasoft.co.id")
                    ->setKeywords("Pasifik POS")
                    ->setCategory("Untuk mencetak nota dot matrix");



            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
//            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_barcode.xls"');

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

    public function ex_data_stok_temp(){
        if (akses::aksesLogin() == TRUE) {
                       
            $objPHPExcel = new PHPExcel();
            
            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(TRUE);
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No.')
                    ->setCellValue('B1', 'Kode')
                    ->setCellValue('C1', 'Barcode')
                    ->setCellValue('D1', 'Nama Barang')
                    ->setCellValue('E1', 'Jml')
                    ->setCellValue('F1', 'Harga Beli')
                    ->setCellValue('G1', 'Harga Jual')
                    ->setCellValue('H1', 'Harga Grosir')
                    ->setCellValue('I1', 'MERK')
                    ->setCellValue('J1', 'SATUAN');
            
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(19);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(17);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);  
            
            
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A2', '1')
                            ->setCellValue('B2', 'PS')
                            ->setCellValue('C2', '1234567890 ')
                            ->setCellValue('D2', 'Nama Produk 1')
                            ->setCellValue('E2', '1')
                            ->setCellValue('F2', '1000')
                            ->setCellValue('G2', '5000')
                            ->setCellValue('H2', '4000')
                            ->setCellValue('I2', 'SHINPO')
                            ->setCellValue('J2', 'PCS');
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A3', '2')
                            ->setCellValue('B3', 'PX')
                            ->setCellValue('C3', '1234567890 ')
                            ->setCellValue('D3', 'Nama Produk 2')
                            ->setCellValue('E3', '0')
                            ->setCellValue('F3', '1000')
                            ->setCellValue('G3', '5000')
                            ->setCellValue('H3', '4000')
                            ->setCellValue('I3', 'SHINPO')
                            ->setCellValue('J3', 'LUSIN');
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A4', 'x')
                            ->setCellValue('B4', 'diisi kode barang / kode huruf saja. Cth: PS')
                            ->setCellValue('C4', 'diisi kode barcode')
                            ->setCellValue('D4', 'diisi nama produk')
                            ->setCellValue('E4', 'diisi jml produk')
                            ->setCellValue('F4', 'diisi harga beli')
                            ->setCellValue('G4', 'diisi harga jual')
                            ->setCellValue('H4', 'diisi harga jual grosir')
                            ->setCellValue('I4', 'diisi merk')
                            ->setCellValue('J4', 'diisi satuan');
            
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
            header('Content-Disposition: attachment;filename="data_stok_'.(isset($_GET['filename']) ? $_GET['filename'] : 'template').'.xls"');

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

    public function ex_data_op_temp(){
        if (akses::aksesLogin() == TRUE) {
                       
            $objPHPExcel = new PHPExcel();
            
            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(TRUE);
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No.')
                    ->setCellValue('B1', 'Kode')
                    ->setCellValue('C1', 'Barcode')
                    ->setCellValue('D1', 'Nama Barang')
                    ->setCellValue('E1', 'Jml')
                    ->setCellValue('F1', 'Harga Beli')
                    ->setCellValue('G1', 'Harga Jual')
                    ->setCellValue('H1', 'Harga Grosir')
                    ->setCellValue('I1', 'MERK')
                    ->setCellValue('J1', 'TANGGAL');
            
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(19);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(17);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);  
            
            
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A2', '1')
                            ->setCellValue('B2', 'PS')
                            ->setCellValue('C2', '1234567890 ')
                            ->setCellValue('D2', 'Nama Produk 1')
                            ->setCellValue('E2', '1')
                            ->setCellValue('F2', '1000')
                            ->setCellValue('G2', '5000')
                            ->setCellValue('H2', '4000')
                            ->setCellValue('I2', 'SHINPO')
                            ->setCellValue('J2', '30/12/2019');
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A3', '2')
                            ->setCellValue('B3', 'PX')
                            ->setCellValue('C3', '1234567890 ')
                            ->setCellValue('D3', 'Nama Produk 2')
                            ->setCellValue('E3', '0')
                            ->setCellValue('F3', '1000')
                            ->setCellValue('G3', '5000')
                            ->setCellValue('H3', '4000')
                            ->setCellValue('I3', 'SHINPO')
                            ->setCellValue('J3', '06/02/2020');
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A4', 'x')
                            ->setCellValue('B4', 'diisi kode barang / kode huruf saja. Cth: PS')
                            ->setCellValue('C4', 'diisi kode barcode')
                            ->setCellValue('D4', 'diisi nama produk')
                            ->setCellValue('E4', 'diisi stok akhir')
                            ->setCellValue('F4', 'diisi harga beli')
                            ->setCellValue('G4', 'diisi harga jual')
                            ->setCellValue('H4', 'diisi harga jual grosir')
                            ->setCellValue('I4', 'diisi merk')
                            ->setCellValue('J4', 'diisi tanggal SO');
            
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Stok Opname');

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
            header('Content-Disposition: attachment;filename="data_opn_template.xls"');

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
    
    public function json_barang() {
        if (akses::aksesLogin() == TRUE) {
            $term  = $this->input->get('term');
            $sql   = $this->db->select('tbl_m_produk.id, tbl_m_produk.id_satuan, tbl_m_produk.kode, tbl_m_produk.produk, tbl_m_produk.jml, tbl_m_produk.harga_jual, tbl_m_produk.harga_beli, tbl_m_produk.harga_beli')
//                              ->join('tbl_m_satuan','tbl_m_satuan.id=tbl_m_produk.id_satuan')
                              ->where("(tbl_m_produk.produk LIKE '%".$term."%' OR tbl_m_produk.kode LIKE '%".$term."' OR tbl_m_produk.barcode LIKE '".$term."')")
//                              ->like('tbl_m_produk.produk', $term,'')->or_like('tbl_m_produk.kode',$term, 'match')->or_like('tbl_m_produk.barcode',$term, 'match')
                              ->order_by('tbl_m_produk.jml', ($_GET['mod'] == 'beli' ? 'asc' : 'desc'))
                              ->get('tbl_m_produk')->result();
            $sg    = $this->ion_auth->user()->row()->status_gudang;
            
            if(!empty($sql)){
                foreach ($sql as $sql){
                    $sql_satuan = $this->db->select('*')->where('id', $sql->id_satuan)->get('tbl_m_satuan')->row();
                    $sql_stok   = $this->db->select('SUM(jml * jml_satuan) AS jml')->where('id_produk', $sql->id)->where('id_gudang', $sg)->get('tbl_m_produk_stok')->row();
                    
                    if($_GET['mod'] == 'beli'){
                        $produk[] = array(
                            'id'        	=> general::enkrip($sql->id),
                            'kode'      	=> $sql->kode,
                            'produk'    	=> $sql->produk,
                            'jml'       	=> $sql->jml,
                            'satuan'    	=> $sql_satuan->satuan,
                            'harga'     	=> number_format($sql->harga_jual, 0, ',', '.'),
                            'harga_beli'	=> number_format($sql->harga_beli, 0, ',', '.'),
                            'harga_grosir'	=> number_format($sql->harga_grosir, 0, ',', '.'),
                        );
                    }else{
//                        if($sql_stok->jml > 0){
                            $produk[] = array(
                                'id'            => general::enkrip($sql->id),
                                'kode'          => $sql->kode,
                                'produk'        => $sql->produk,
                                'jml'           => $sql->jml,
                                'satuan'        => $sql_satuan->satuan,
                                'harga'         => number_format($sql->harga_jual, 0, ',', '.'),
                                'harga_beli'    => number_format($sql->harga_beli, 0, ',', '.'),
                                'harga_grosir'	=> number_format($sql->harga_grosir, 0, ',', '.'),
                            );                            
//                        }
                    }
                }
                
                echo json_encode($produk);
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
	
	
    public function so_reset() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $sql_so     = $this->db->where('id', general::dekrip($id))->get('tbl_util_so')->row();
            $sql_so_det = $this->db->where('id_so', general::dekrip($id))->get('tbl_util_so_det')->result();

            foreach ($sql_so_det as $so) {
                $sql_cek_gd = $this->db->where('id_produk', $so->id_produk)->where('id_gudang', $sql_so->id_gudang)->get('tbl_m_produk_stok')->row();
                $stok_gd = $so->jml;

                crud::update('tbl_m_produk_stok', 'id', $sql_cek_gd->id, array('jml' => $stok_gd));

                $sql_sum_gd = $this->db->select_sum('jml')->where('id_produk', $so->id_produk)->get('tbl_m_produk_stok')->row();
                $stok_sum = ($sql_sum_gd->jml < 0 ? $stok_gd : $sql_sum_gd->jml);

                crud::update('tbl_m_produk', 'id', $so->id_produk, array('jml' => $stok_sum));
            }
            
            crud::update('tbl_util_so', 'id', general::dekrip($id), array('reset' => '1'));
            redirect(base_url('gudang/data_opname_list.php'));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
}
