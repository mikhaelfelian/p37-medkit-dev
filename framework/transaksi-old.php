<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
/**
 * Description of transaksi
 *
 * @author mike
 */
class transaksi extends CI_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->library('cart');
        $this->load->library('excel/PHPExcel');
    }

    
    public function index() {
        if (akses::aksesLogin() == TRUE) {
            /* Blok pagination */
            $data['cetak']      = '<button type="button" onclick="window.location.href = \''.base_url('transaksi/cetak_data_penj.php?'.(!empty($nt) ? 'filter_nota='.$nt : '').(!empty($tg) ? '&filter_tgl='.$tg : '').(!empty($tp) ? '&filter_tgl_tempo='.$tp : '').(!empty($cs) ? '&filter_cust='.$cs : '').(!empty($sl) ? '&filter_sales='.$sl : '').(!empty($jml) ? '&jml='.$jml : '')).'\'" class="btn btn-warning"><i class="fa fa-print"></i> Cetak</button>';
            /* --End Blok pagination-- */
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/trans/beli/sidebar_beli';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/trans/index', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function trans_beli_list() {
        if (akses::aksesLogin() == TRUE) {
            /* -- Grup hak akses -- */
            $grup        = $this->ion_auth->get_users_groups()->row();
            $id_user     = $this->ion_auth->user()->row()->id;
            $id_grup     = $this->ion_auth->get_users_groups()->row();
            $pengaturan  = $this->db->get('tbl_pengaturan')->row();

            /* -- Blok Filter -- */
            $query   = $this->input->get('q');
            $hal     = $this->input->get('halaman');
            $jml     = $this->input->get('jml');

            $nt = $this->input->get('filter_nota');
            $tg = $this->input->get('filter_tgl');
            $tp = $this->input->get('filter_tgl_tempo');
            $tb = $this->input->get('filter_tgl_bayar');
            $lk = $this->input->get('filter_lokasi');
            $sl = $this->input->get('filter_supplier');
            $sn = $this->input->get('filter_status');
            $sb = $this->input->get('filter_bayar');
            /* -- End Blok Filter -- */
            
            /* -- jml halaman pada list -- */
            if(!empty($jml)){
                $jml_hal = $jml;
            }else{
                $jml_hal = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.no_nota, tbl_trans_beli.no_po, DATE(tbl_trans_beli.tgl_masuk) as tgl_masuk, DATE(tbl_trans_beli.tgl_bayar) as tgl_bayar, DATE(tbl_trans_beli.tgl_keluar) as tgl_keluar, tbl_trans_beli.jml_total, tbl_trans_beli.jml_retur, tbl_trans_beli.jml_subtotal, tbl_trans_beli.jml_gtotal, tbl_trans_beli.id_user, tbl_trans_beli.id_supplier, tbl_trans_beli.status_nota, tbl_trans_beli.status_bayar')
                                ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli.id_supplier')
//                                ->like('tbl_trans_beli.id_user', ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'adminm' || $id_grup->name == 'purchasing' ? '' : $id_user))
                                ->like('tbl_m_supplier.nama', $sl)
//                                ->like('tbl_trans_beli.no_nota', $nt)
//                                ->like('DATE(tbl_trans_beli.tgl_masuk)', $tg)
//                                ->like('DATE(tbl_trans_beli.tgl_keluar)', $tp)
//                                ->like('DATE(tbl_trans_beli.tgl_bayar)', $tb)
//                                ->like('tbl_trans_beli.status_bayar', $sb)
                                ->order_by('tbl_trans_beli.id','desc')
                                ->get('tbl_trans_beli')->num_rows();
            }            

            /* -- Form Error -- */
            $data['hasError']                = $this->session->flashdata('form_error');
            
            // Config Pagination
            $config['base_url']              = base_url('transaksi/beli/index.php?case='.$case.(!empty($tgl) ? '&tgl='.$tgl : '').(!empty($tgl_awal) ? '&tgl_awal='.$tgl_awal : '').(!empty($tgl_akhir) ? '&tgl_akhir='.$tgl_akhir : '').(!empty($jml) ? '&jml='.$jml : ''));
            $config['total_rows']            = $jml_hal;

            $config['query_string_segment']  = 'halaman';
            $config['page_query_string']     = TRUE;
            $config['per_page']              = $pengaturan->jml_item;
            $config['num_links']             = 2;

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
            /* -- End Blok Pagination -- */
            
            if(!empty($hal)){
                   $data['sql_beli'] = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.no_po, tbl_trans_beli.no_nota, DATE(tbl_trans_beli.tgl_masuk) as tgl_masuk, DATE(tbl_trans_beli.tgl_bayar) as tgl_bayar, DATE(tbl_trans_beli.tgl_keluar) as tgl_keluar, tbl_trans_beli.jml_total, tbl_trans_beli.jml_retur, tbl_trans_beli.jml_subtotal, tbl_trans_beli.jml_gtotal, tbl_trans_beli.id_user, tbl_trans_beli.id_supplier, tbl_trans_beli.status_nota, tbl_trans_beli.status_bayar, tbl_m_supplier.nama, tbl_m_supplier.npwp, tbl_m_supplier.alamat, tbl_m_supplier.no_tlp, tbl_m_supplier.cp')
                           ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli.id_supplier')
//                           ->like('tbl_trans_beli.id_user', ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'adminm' || $id_grup->name == 'purchasing' ? '' : $id_user))
                           ->like('tbl_m_supplier.nama', $sl)
                           ->like('tbl_trans_beli.no_nota', $nt)
                           ->like('DATE(tbl_trans_beli.tgl_masuk)', $tg)
                           ->like('DATE(tbl_trans_beli.tgl_keluar)', $tp)
                           ->like('DATE(tbl_trans_beli.tgl_bayar)', $tb)
                           ->like('tbl_trans_beli.status_bayar', $sb)
                           ->limit($config['per_page'],$hal)
                           ->order_by('tbl_trans_beli.id','desc')
                           ->get('tbl_trans_beli')->result();
            }else{
                   $data['sql_beli'] = $this->db->select('tbl_trans_beli.id, tbl_trans_beli.no_po, tbl_trans_beli.no_nota, DATE(tbl_trans_beli.tgl_masuk) as tgl_masuk, DATE(tbl_trans_beli.tgl_bayar) as tgl_bayar, DATE(tbl_trans_beli.tgl_keluar) as tgl_keluar, tbl_trans_beli.jml_total, tbl_trans_beli.jml_retur, tbl_trans_beli.jml_subtotal, tbl_trans_beli.jml_gtotal, tbl_trans_beli.id_user, tbl_trans_beli.id_supplier, tbl_trans_beli.status_nota, tbl_trans_beli.status_bayar, tbl_m_supplier.nama, tbl_m_supplier.npwp, tbl_m_supplier.alamat, tbl_m_supplier.no_tlp, tbl_m_supplier.cp')
                           ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli.id_supplier')
//                           ->like('tbl_trans_beli.id_user', ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'adminm' || $id_grup->name == 'purchasing' ? '' : $id_user))
                           ->like('tbl_m_supplier.nama', $sl)
//                           ->like('tbl_trans_beli.no_nota', $nt)
//                           ->like('DATE(tbl_trans_beli.tgl_masuk)', $tg)
//                           ->like('DATE(tbl_trans_beli.tgl_keluar)', $tp)
//                           ->like('DATE(tbl_trans_beli.tgl_bayar)', $tb)
//                           ->like('tbl_trans_beli.status_bayar', $sb)
//                           ->limit($config['per_page'])                           
                           ->order_by('tbl_trans_beli.id','desc')
                           ->get('tbl_trans_beli')->result();
            }

            $this->pagination->initialize($config);

            /* Blok pagination */
            $data['total_rows'] = $config['total_rows'];
            $data['PerPage']    = $config['per_page'];
            $data['pagination'] = $this->pagination->create_links();
            /* --End Blok pagination-- */
                        
            /* Blok pagination */
            $data['cetak']      = '<button type="button" onclick="window.location.href = \''.base_url('transaksi/cetak_data_penj.php?'.(!empty($nt) ? 'filter_nota='.$nt : '').(!empty($tg) ? '&filter_tgl='.$tg : '').(!empty($tp) ? '&filter_tgl_tempo='.$tp : '').(!empty($cs) ? '&filter_cust='.$cs : '').(!empty($sl) ? '&filter_sales='.$sl : '').(!empty($jml) ? '&jml='.$jml : '')).'\'" class="btn btn-warning"><i class="fa fa-print"></i> Cetak</button>';
            /* --End Blok pagination-- */
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/trans/beli/sidebar_beli';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/trans/beli/index', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function trans_beli_po_list() {
        if (akses::aksesLogin() == TRUE) {
            /* -- Grup hak akses -- */
            $grup        = $this->ion_auth->get_users_groups()->row();
            $id_user     = $this->ion_auth->user()->row()->id;
            $id_grup     = $this->ion_auth->get_users_groups()->row();
            $pengaturan  = $this->db->get('tbl_pengaturan')->row();

            /* -- Blok Filter -- */
            $query   = $this->input->get('q');
            $hal     = $this->input->get('halaman');
            $jml     = $this->input->get('jml');

            $nt = $this->input->get('filter_nota');
            $tg = $this->input->get('filter_tgl');
            $tp = $this->input->get('filter_tgl_tempo');
            $tb = $this->input->get('filter_tgl_bayar');
            $lk = $this->input->get('filter_lokasi');
            $sl = $this->input->get('filter_supplier');
            $sn = $this->input->get('filter_status');
            $sb = $this->input->get('filter_bayar');
            /* -- End Blok Filter -- */
            
            /* -- jml halaman pada list -- */
            if(!empty($jml)){
                $jml_hal = $jml;
            }else{
                $jml_hal = $this->db->select('tbl_trans_beli_po.id, tbl_trans_beli_po.id_supplier, tbl_trans_beli_po.no_nota, DATE(tbl_trans_beli_po.tgl_masuk) as tgl_masuk, DATE(tbl_trans_beli_po.tgl_bayar) as tgl_bayar, DATE(tbl_trans_beli_po.tgl_keluar) as tgl_keluar, tbl_trans_beli_po.keterangan, tbl_trans_beli_po.pengiriman, tbl_m_supplier.nama, tbl_m_supplier.npwp, tbl_m_supplier.alamat, tbl_m_supplier.no_tlp, tbl_m_supplier.cp')
                           ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli_po.id_supplier')
                           ->like('tbl_m_supplier.nama', $sl)
                           ->order_by('tbl_trans_beli_po.id','desc')
                           ->get('tbl_trans_beli_po')->num_rows();
            }            

            /* -- Form Error -- */
            $data['hasError']                = $this->session->flashdata('form_error');
            
            // Config Pagination
            $config['base_url']              = base_url('transaksi/beli/trans_beli_po_list.php?case='.$case.(!empty($tgl) ? '&tgl='.$tgl : '').(!empty($tgl_awal) ? '&tgl_awal='.$tgl_awal : '').(!empty($tgl_akhir) ? '&tgl_akhir='.$tgl_akhir : '').(!empty($jml) ? '&jml='.$jml : ''));
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
            
            if(!empty($hal)){
                   $data['sql_beli'] = $this->db->select('tbl_trans_beli_po.id, tbl_trans_beli_po.id_supplier, tbl_trans_beli_po.no_nota, DATE(tbl_trans_beli_po.tgl_masuk) as tgl_masuk, DATE(tbl_trans_beli_po.tgl_bayar) as tgl_bayar, DATE(tbl_trans_beli_po.tgl_keluar) as tgl_keluar, tbl_trans_beli_po.keterangan, tbl_trans_beli_po.pengiriman, tbl_m_supplier.nama, tbl_m_supplier.npwp, tbl_m_supplier.alamat, tbl_m_supplier.no_tlp, tbl_m_supplier.cp')
                           ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli_po.id_supplier')
                           ->like('tbl_m_supplier.nama', $sl)  
                           ->limit($config['per_page'],$hal)
                           ->order_by('tbl_trans_beli_po.id','desc')
                           ->get('tbl_trans_beli_po')->result();
            }else{
                   $data['sql_beli'] = $this->db->select('tbl_trans_beli_po.id, tbl_trans_beli_po.id_supplier, tbl_trans_beli_po.no_nota, DATE(tbl_trans_beli_po.tgl_masuk) as tgl_masuk, DATE(tbl_trans_beli_po.tgl_bayar) as tgl_bayar, DATE(tbl_trans_beli_po.tgl_keluar) as tgl_keluar, tbl_trans_beli_po.keterangan, tbl_trans_beli_po.pengiriman, tbl_m_supplier.nama, tbl_m_supplier.npwp, tbl_m_supplier.alamat, tbl_m_supplier.no_tlp, tbl_m_supplier.cp')
                           ->join('tbl_m_supplier', 'tbl_m_supplier.id=tbl_trans_beli_po.id_supplier')
                           ->like('tbl_m_supplier.nama', $sl)
                           ->limit($config['per_page'])
                           ->order_by('tbl_trans_beli_po.id','desc')
                           ->get('tbl_trans_beli_po')->result();
            }

            $this->pagination->initialize($config);

            /* Blok pagination */
            $data['total_rows'] = $config['total_rows'];
            $data['PerPage']    = $config['per_page'];
            $data['pagination'] = $this->pagination->create_links();
            /* --End Blok pagination-- */
                        
            /* Blok pagination */
            $data['cetak']      = '<button type="button" onclick="window.location.href = \''.base_url('transaksi/cetak_data_penj.php?'.(!empty($nt) ? 'filter_nota='.$nt : '').(!empty($tg) ? '&filter_tgl='.$tg : '').(!empty($tp) ? '&filter_tgl_tempo='.$tp : '').(!empty($cs) ? '&filter_cust='.$cs : '').(!empty($sl) ? '&filter_sales='.$sl : '').(!empty($jml) ? '&jml='.$jml : '')).'\'" class="btn btn-warning"><i class="fa fa-print"></i> Cetak</button>';
            /* --End Blok pagination-- */
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/trans/beli/sidebar_beli';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/trans/beli/trans_beli_po_list', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function trans_beli() {
        if (akses::aksesLogin() == TRUE) {
            $setting            = $this->db->get('tbl_pengaturan')->row();
            $id                 = $this->input->get('id');
            $id_produk          = $this->input->get('id_item');
            $id_resep           = $this->input->get('id_resep');
            $id_lab             = $this->input->get('id_lab');
            $id_item            = $this->input->get('id_item');
            $id_po              = $this->input->get('id_po');
            $id_supp            = $this->input->get('id_supplier');
            $status             = $this->input->get('status');
            $dft_pas            = $this->input->get('dft_pas');
            $dft_id             = $this->input->get('dft_id');
            $userid             = $this->ion_auth->user()->row()->id;

            $data['sess_beli']      = $this->session->userdata('trans_beli');
            $data['sql_po']         = $this->db->where('id', general::dekrip($id_po))->get('tbl_trans_beli_po')->row();
            $data['sql_supplier']   = $this->db->where('id', $data['sql_po']->id_supplier)->get('tbl_m_supplier')->row();
            
            if(!empty($data['sess_beli'])){                
                $data['sql_item']       = $this->db->where('id', general::dekrip($id_produk))->get('tbl_m_produk')->row();
                $data['sql_satuan']     = $this->db->get('tbl_m_satuan')->result();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/trans/beli/sidebar_beli';
            /* --- Sidebar Menu --- */
            
//            echo '<pre>';
//            print_r($this->session->userdata('trans_beli'));
//            echo '</pre>';

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/trans/beli/trans_beli', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function trans_beli_edit() {
        if (akses::aksesLogin() == TRUE) {
            $setting  = $this->db->get('tbl_pengaturan')->row();
            $id       = $this->input->get('id');
            $id_item  = $this->input->get('id_item');
            $userid   = $this->ion_auth->user()->row()->id;
            
            if(!empty($id)){
                $data['sess_beli']      = $this->session->userdata('trans_beli_edit');
                $data['sql_beli']       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_beli')->row();
                $data['sql_beli_det']   = $this->db->where('id_pembelian', $data['sql_beli']->id)->get('tbl_trans_beli_det')->result();
                $data['sql_supplier']   = $this->db->where('id', $data['sql_beli']->id_supplier)->get('tbl_m_supplier')->row();
                $data['sql_item']       = $this->db->where('id', general::dekrip($id_item))->get('tbl_m_produk')->row();
                $data['sql_satuan']     = $this->db->get('tbl_m_satuan')->result();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/trans/beli/sidebar_beli';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/trans/beli/trans_beli_edit', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function trans_beli_det() {
        if (akses::aksesLogin() == TRUE) {
            $setting  = $this->db->get('tbl_pengaturan')->row();
            $id       = $this->input->get('id');
            $userid   = $this->ion_auth->user()->row()->id;

            
            
            if(!empty($id)){
                $data['sql_beli']       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_beli')->row();
                $data['sql_beli_det']   = $this->db->where('id_pembelian', general::dekrip($id))->get('tbl_trans_beli_det')->result();
                $data['sql_supplier']   = $this->db->where('id', $data['sql_beli']->id_supplier)->get('tbl_m_supplier')->row();
                $data['sql_item']       = $this->db->where('id', general::dekrip($id_produk))->get('tbl_m_produk')->row();
                $data['sql_satuan']     = $this->db->get('tbl_m_satuan')->result();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/trans/beli/sidebar_beli';
            /* --- Sidebar Menu --- */
//            
//            echo '<pre>';
//            print_r($data['sql_beli']);
//            echo '</pre>';

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/trans/beli/trans_beli_det', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function trans_beli_po() {
        if (akses::aksesLogin() == TRUE) {
            $setting                = $this->db->get('tbl_pengaturan')->row();
            $id                     = $this->input->get('id');
            $id_produk              = $this->input->get('id_item');
            $id_resep               = $this->input->get('id_resep');
            $id_lab                 = $this->input->get('id_lab');
            $id_item                = $this->input->get('id_item');
            $status                 = $this->input->get('status');
            $dft_pas                = $this->input->get('dft_pas');
            $dft_id                 = $this->input->get('dft_id');
            $userid                 = $this->ion_auth->user()->row()->id;

            $data['sess_beli']   = $this->session->userdata('trans_beli_po');
            
            if(!empty($data['sess_beli'])){
                $data['sql_supplier']   = $this->db->where('id', $data['sess_beli']['id_supplier'])->get('tbl_m_supplier')->row();
                $data['sql_item']       = $this->db->where('id', general::dekrip($id_produk))->get('tbl_m_produk')->row();
                $data['sql_satuan']     = $this->db->get('tbl_m_satuan')->result();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/trans/beli/sidebar_beli';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/trans/beli/trans_beli_po', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function trans_beli_po_edit() {
        if (akses::aksesLogin() == TRUE) {
            $setting                = $this->db->get('tbl_pengaturan')->row();
            $id                     = $this->input->get('id');
            $id_produk              = $this->input->get('id_item');
            $id_resep               = $this->input->get('id_resep');
            $id_lab                 = $this->input->get('id_lab');
            $id_item                = $this->input->get('id_item');
            $status                 = $this->input->get('status');
            $dft_pas                = $this->input->get('dft_pas');
            $dft_id                 = $this->input->get('dft_id');
            $userid                 = $this->ion_auth->user()->row()->id;
            
            if(!empty($id)){
                $data['sql']            = $this->db->where('id', general::dekrip($id))->get('tbl_trans_beli_po')->row();
                $data['sql_det']        = $this->db->where('id_pembelian', $data['sql']->id)->get('tbl_trans_beli_po_det')->result();
                $data['sql_supplier']   = $this->db->where('id', $data['sql']->id_supplier)->get('tbl_m_supplier')->row();
                $data['sql_item']       = $this->db->where('id', general::dekrip($id_produk))->get('tbl_m_produk')->row();
                $data['sql_satuan']     = $this->db->get('tbl_m_satuan')->result();
                
                $data['sess_beli']      = $this->session->userdata('trans_beli_po_edit');
            }
            
//            echo '<pre>';
//            print_r($data['sql']);
//            echo '<pre>';
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/trans/beli/sidebar_beli';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/trans/beli/trans_beli_po_edit', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function trans_beli_po_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id                   = $this->input->get('id');
            $userid               = $this->ion_auth->user()->row()->id;
            
            $sql_medc = $this->db->where('id', general::dekrip($id))->get('tbl_trans_beli_po');
            
            if($sql_medc->num_rows() > 0){
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil dihapus</div>');
                crud::delete('tbl_trans_beli_po', 'id', general::dekrip($id));
            }
            
            redirect(base_url('transaksi/beli/trans_beli_po_list.php'));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function trans_beli_po_det() {
        if (akses::aksesLogin() == TRUE) {
            $setting                = $this->db->get('tbl_pengaturan')->row();
            $id                     = $this->input->get('id');
            $id_produk              = $this->input->get('id_item');
            $id_resep               = $this->input->get('id_resep');
            $id_lab                 = $this->input->get('id_lab');
            $id_item                = $this->input->get('id_item');
            $status                 = $this->input->get('status');
            $dft_pas                = $this->input->get('dft_pas');
            $dft_id                 = $this->input->get('dft_id');
            $userid                 = $this->ion_auth->user()->row()->id;
            
            if(!empty($id)){
                $data['sql_beli']       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_beli_po')->row();
                $data['sql_beli_det']   = $this->db->where('id_pembelian', $data['sql_beli']->id)->get('tbl_trans_beli_po_det')->result();
                $data['sql_supplier']   = $this->db->where('id', $data['sql_beli']->id_supplier)->get('tbl_m_supplier')->row();
                $data['sql_item']       = $this->db->where('id', general::dekrip($id_produk))->get('tbl_m_produk')->row();
                $data['sql_satuan']     = $this->db->get('tbl_m_satuan')->result();
                
                $data['sess_beli']      = $this->session->userdata('trans_beli_po_edit');
            }
            
//            echo '<pre>';
//            print_r($data['sql']);
//            echo '<pre>';
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/trans/beli/sidebar_beli';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/trans/beli/trans_beli_po_det', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function trans_beli_print_ex_po() {
        if (akses::aksesLogin() == TRUE) {
            $setting    = $this->db->get('tbl_pengaturan')->row();
            $id         = $this->input->get('id');
            $aid        = general::dekrip($id);
            $sql        = $this->db->select('DATE(tgl_simpan) as tgl_simpan, no_nota, id_supplier, id_user, keterangan, pengiriman')->where('tbl_trans_beli_po.id', $aid)->get('tbl_trans_beli_po')->row();
            $sql_det    = $this->db->select('tbl_trans_beli_po_det.id, tbl_trans_beli_po_det.kode, tbl_trans_beli_po_det.produk, tbl_trans_beli_po_det.jml, tbl_trans_beli_po_det.satuan, tbl_trans_beli_po_det.keterangan_itm, tbl_m_satuan.satuanTerkecil as sk, tbl_m_satuan.satuanBesar as sb')->join('tbl_m_satuan', 'tbl_m_satuan.id=tbl_trans_beli_po_det.id_satuan')->where('tbl_trans_beli_po_det.id_pembelian', $aid)->get('tbl_trans_beli_po_det');
            $member     = $this->db->where('id', $sql->id_supplier)->get('tbl_m_supplier')->row();
            $sales      = $this->db->where('id', $sql->id_sales)->get('tbl_m_sales')->row();
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
                    ->setCellValue('F1', ucwords(strtolower($pengaturan->kota)).', '.$this->tanggalan->tgl_indo($sql->tgl_simpan));
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A2', strtoupper($pengaturan->alamat))
                    ->setCellValue('F2', 'Kepada Yth : ');
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3', strtoupper($pengaturan->kota))->mergeCells('A2:E2')
                    ->setCellValue('F3', strtoupper($member->nama));
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F4', strtoupper($member->alamat));
//            $objPHPExcel->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//            $objPHPExcel->setActiveSheetIndex(0)
//                    ->setCellValue('D5', strtoupper($sales->kode))->mergeCells('D5:E5')
//                    ->setCellValue('F5', strtoupper($member->lokasi));

            $objPHPExcel->getActiveSheet()->getStyle('A5:D5')->getFont()->setBold(TRUE);
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A5', 'PURCHASE ORDER')->mergeCells('A5:E5');

            $objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A6', 'No. PO : '.$sql->no_nota)->mergeCells('A6:E6');

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B7:C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D7:E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('G7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A7', 'No.')
                    ->setCellValue('B7', 'Banyaknya')->mergeCells('B7:C7')
                    ->setCellValue('D7', 'Nama Barang')->mergeCells('D7:E7')
                    ->setCellValue('F7', 'Kode')
                    ->setCellValue('G7', 'Keterangan')->mergeCells('G7:H7');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(28);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13);

            // Detail barang
            $no    = 1;
            $cell  = 8;
            $cel   = 8;
            foreach ($sql_det->result() as $items){
                // Format Angka
                $objPHPExcel->getActiveSheet()->getStyle('G' . $cell.':H'.$cell)->getNumberFormat()->setFormatCode("#.##0");

                // Format Alignment
                $objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':C'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$cell.':E'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, $no)
                            ->setCellValue('B'.$cell, $items->jml)
                            ->setCellValue('C'.$cell, $items->satuan)
                            ->setCellValue('D'.$cell, $items->produk)->mergeCells('D'.$cell.':E'.$cell)
                            ->setCellValue('F'.$cell, $items->kode)
                            ->setCellValue('G'.$cell, $items->keterangan_itm)->mergeCells('G'.$cell.':H'.$cell);

                $no++;
                $cell++;
            }
//
            // Maximal baris
            if($sql_det->num_rows() > 46){
               $sell = $cell;
            }else{
                $jmlbaris = 39 - (int) $no;

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
//            // Font Nota Detail
            $objPHPExcel->getActiveSheet()->getStyle('A8:H'.$cell)->getFont()->setSize('10')->setName('Times New Roman');



            // Hitung sell bawah
            $sell2    = $sell + 1;
            $sellbwh1 = $sell2 + 1;
            $sellbwh2 = $sellbwh1 + 1;
            $sellbwh3 = $sellbwh2 + 1;
            $sellbwh4 = $sellbwh3 + 1;

            // border bawah
            $objPHPExcel->getActiveSheet()->getStyle('A'.$sell2.':H'.$sell2)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);

            // Subtotal, ppn, grand total
            $objPHPExcel->getActiveSheet()->getStyle('A'.$sell2.':F'.$sell2)->getFont()->setSize('11')->setName('Times New Roman');
            $objPHPExcel->getActiveSheet()->getStyle('A'.$sell2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $sell2)->getNumberFormat()->setFormatCode("#.##0");
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $sell2, $sql->keterangan)->mergeCells('A' . $sell2.':H' . $sell2);

            $objPHPExcel->getActiveSheet()->getStyle('A'.$sellbwh1.':F'.$sellbwh1)->getFont()->setSize('11')->setName('Times New Roman');
            $objPHPExcel->getActiveSheet()->getStyle('A'.$sellbwh1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $sellbwh1)->getNumberFormat()->setFormatCode("#.##0");
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $sellbwh1, $sql->pengiriman)->mergeCells('A' . $sellbwh1.':H' . $sellbwh1);

            // border penutup
            $objPHPExcel->getActiveSheet()->getStyle('A'.$sellbwh3)->getFont()->setSize('10')->setName('Times New Roman');
            $objPHPExcel->getActiveSheet()->getStyle('A'.$sellbwh3.':H'.$sellbwh3)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUMDASHED);
            /* END CONTENT EXCEL -- */

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Purchase Order');

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
//                    ->setTitle("Nota Penbelian " . $sql->row()->no_nota . ($sql->row()->cetak == '1' ? ' Copy Customer' : ''))
                    ->setSubject("Aplikasi Bengkel POS")
                    ->setDescription("Kunjungi http://mikhaelfelian.web.id")
                    ->setKeywords("POS")
                    ->setCategory("Untuk mencetak nota dot matrix");



            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.strtolower($sql->no_nota).'.xls"');

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
    

    public function set_trans_beli() {
        if (akses::aksesLogin() == TRUE) {
            $tgl_masuk  = $this->input->post('tgl_masuk');
            $tgl_tempo  = $this->input->post('tgl_keluar');
            $plgn       = $this->input->post('id_supplier');
            $no_nota    = $this->input->post('no_nota');
            $no_po      = $this->input->post('kode_po');
            $id_po      = $this->input->post('id_po');
            $status_ppn = $this->input->post('status_ppn');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id_supplier', 'ID Supplier', 'required');
            $this->form_validation->set_rules('tgl_masuk', 'Tgl Faktur', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id_supplier'   => form_error('id_supplier'),
                    'tgl_masuk'     => form_error('tgl_masuk'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('transaksi/beli/trans_beli.php'));
            } else {
                $tgl_msk = $this->tanggalan->tgl_indo_sys($tgl_masuk);
                $tgl_klr = $this->tanggalan->tgl_indo_sys($tgl_tempo);
                
                $sql_beli   = $this->db->where('YEAR(tgl_simpan)', date('Y'))->where('MONTH(tgl_simpan)', date('m'))->get('tbl_trans_beli');
                $nota_str   = $sql_beli->num_rows() + 1;
                $nota       = (!empty($no_nota) ? $no_nota : 'FP'.date('m').date('y').sprintf('%03d', $nota_str));
                
                $sql_po_det = $this->db->where('id_pembelian', $id_po)->get('tbl_trans_beli_po_det')->result();

                $data = array(
                    'no_nota'      => $nota,
                    'id_po'        => $id_po,
                    'no_po'        => $no_po,
                    'tgl_simpan'   => date('Y-m-d H:i:s'),
                    'tgl_masuk'    => $tgl_msk,
                    'tgl_keluar'   => $tgl_klr,
                    'id_po'        => $id_po,
                    'id_supplier'  => $plgn,
                    'id_user'      => $this->ion_auth->user()->row()->id,
                    'status_ppn'   => (!empty($status_ppn) ? $status_ppn : '0'),
                );
                
                foreach ($sql_po_det as $po_det){
                    $sql_brg     = $this->db->where('id', $po_det->id_produk)
                                            ->get('tbl_m_produk')->row();
                    $sql_satuan  = $this->db->where('id', $sql_brg->id_satuan)->get('tbl_m_satuan')->row();
                    $pengaturan  = $this->db->get('tbl_pengaturan')->row();
    
                    $jml_pcs     = (!empty($sql_satuan->jml) ? $sql_satuan->jml : '1') * $po_det->jml;
                    $harga_pcs   = ($harga * $po_det->jml) / $jml_pcs;
                    $harga_sat   = $harga_pcs * $sql_satuan->jml;
    
                    $disk1       = $harga_pcs - (($diskon1 / 100) * $harga_pcs);
                    $disk2       = $disk1 - (($diskon2 / 100) * $disk1);
                    $disk3       = $disk2 - (($diskon3 / 100) * $disk2);
    
                    $harga_ppn   = ($trans_beli['status_ppn'] == '1' ? ($pengaturan->jml_ppn / 100) * $disk3 : 0);
                    $harga_tot   = $disk3 + $harga_ppn;
                    $subtotal    = ($disk3 * $jml_pcs) - $potongan;
                    $jml_qty     = $po_det->jml;
    
                    $jml_satuan  = $sql_satuan2->jml * $jml_qty;
                    
                    $cart = array(
                        'id'      => rand(1,1024).$sql_brg->id,
                        'qty'     => $jml_qty,
                        'price'   => (float)($sql_brg->harga_beli > 0 ? $sql_brg->harga_beli : '1'),
                        'name'    => rtrim($sql_brg->produk),
                        'options' => array(
                                'no_nota'       => $no_po,
                                'id_barang'     => $sql_brg->id,
                                'id_satuan'     => $sql_brg->id_satuan,
                                'satuan'        => $sql_satuan->satuanTerkecil,
//                                'satuan_ket'    => ($sql_satuan->jml != 1 ? ' ('.(!empty($jml_subtotal) ? $jml_qty : $qty) * $sql_satuan->jml.' '.$sql_satuan2->satuanTerkecil.')' : ''),
                                'jml'           => $qty,
                                'jml_satuan'    => (!empty($sql_satuan->jml) ? $sql_satuan->jml : '1'),
                                'kode'          => $sql_brg->kode,
                                'harga'         => $harga_tot,
                                'ppn'           => $harga_ppn,
                                'potongan'      => $potongan,
                                'disk1'         => (float)$diskon1,
                                'disk2'         => (float)$diskon2,
                                'disk3'         => (float)$diskon3,
                                'subtotal'      => (float)$subtotal,
                        )
                    );
                    
                    $this->cart->insert($cart);
//                    
//                    echo '<pre>';
//                    print_r($cart);
//                    echo '</pre>';
                }
//                
//                echo '<pre>';
//                print_r($data);
//                echo '</pre>';
//                echo '<pre>';
//                print_r($sql_po_det);
//                echo '</pre>';
                
                
                
                $this->session->set_userdata('trans_beli', $data);
                redirect(base_url('transaksi/beli/trans_beli.php?id='.general::enkrip($nota)));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_trans_beli_upd() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $tgl_masuk  = $this->input->post('tgl_masuk');
            $tgl_tempo  = $this->input->post('tgl_keluar');
            $plgn       = $this->input->post('id_supplier');
            $no_nota    = $this->input->post('no_nota');
            $no_po      = $this->input->post('no_po');
            $id_po      = $this->input->post('id_po');
            $status_ppn = $this->input->post('status_ppn');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id_supplier', 'ID Supplier', 'required');
            $this->form_validation->set_rules('tgl_masuk', 'Tgl Faktur', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id_supplier'   => form_error('id_supplier'),
                    'tgl_masuk'     => form_error('tgl_masuk'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('transaksi/beli/trans_beli.php'));
            } else {
                $tgl_msk = $this->tanggalan->tgl_indo_sys($tgl_masuk);
                $tgl_klr = $this->tanggalan->tgl_indo_sys($tgl_tempo);

                $data = array(
                    'no_nota'      => $no_nota,
                    'no_po'        => $no_po,
                    'tgl_simpan'   => date('Y-m-d H:i:s'),
                    'tgl_masuk'    => $tgl_msk,
                    'tgl_keluar'   => $tgl_klr,
                    'id_po'        => $id_po,
                    'id_supplier'  => $plgn,
                    'id_user'      => $this->ion_auth->user()->row()->id,
                    'status_ppn'   => (!empty($status_ppn) ? $status_ppn : '0'),
                );
                
                crud::update('tbl_trans_beli', 'id', general::dekrip($id), $data);
                
                $this->session->set_userdata('trans_beli_edit', $data);
                redirect(base_url('transaksi/beli/trans_beli_edit.php?id='.$id));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_trans_beli_batal() {
        if (akses::aksesLogin() == TRUE) {
            $no_nota  = $this->input->get('id');
            
            if(!empty($no_nota)){
                $this->session->unset_userdata('trans_beli');
                $this->cart->destroy();
            }
            
            redirect(base_url('transaksi/beli/trans_beli.php'));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_trans_beli_po() {
        if (akses::aksesLogin() == TRUE) {
            $tgl_masuk  = $this->input->post('tgl_masuk');
            $tgl_tempo  = $this->input->post('tgl_tempo');
            $plgn       = $this->input->post('id_supplier');
            $ket        = $this->input->post('keterangan');
            $alamat     = $this->input->post('pengiriman');
            $status_ppn = $this->input->post('status_ppn');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id_supplier', 'ID Supplier', 'required');
            $this->form_validation->set_rules('tgl_masuk', 'Tgl Faktur', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id_supplier'   => form_error('id_supplier'),
                    'tgl_masuk'     => form_error('tgl_masuk'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('transaksi/beli/trans_beli.php'));
            } else {
                $tgl_msk = $this->tanggalan->tgl_indo_sys($tgl_masuk);
                $tgl_klr = $this->tanggalan->tgl_indo_sys($tgl_tempo);
                
                $sql_beli   = $this->db->where('YEAR(tgl_simpan)', date('Y'))->where('MONTH(tgl_simpan)', date('m'))->get('tbl_trans_beli_po');
                $nota_str   = $sql_beli->num_rows() + 1;
                $nota       = (!empty($no_nota) ? $no_nota : 'FP'.date('m').date('y').sprintf('%03d', $nota_str));
                $supplier   = $this->db->where('id', $plgn)->get('tbl_m_supplier')->row();
                
                $data = array(
                    'no_nota'      => $nota,
                    'tgl_simpan'   => date('Y-m-d H:i:s'),
                    'tgl_masuk'    => $tgl_msk,
                    'tgl_keluar'   => $tgl_klr,
                    'id_supplier'  => $plgn,
                    'id_user'      => $this->ion_auth->user()->row()->id,
                    'supplier'     => strtoupper($supplier->nama),
                    'keterangan'   => $ket,
                    'pengiriman'   => $alamat,
                    'status_ppn'   => (!empty($status_ppn) ? $status_ppn : '0'),
                );
                
                $this->session->set_userdata('trans_beli_po', $data);
                redirect(base_url('transaksi/beli/trans_beli_po.php?id='.general::enkrip($nota)));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_trans_beli_po_upd() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $tgl_masuk  = $this->input->post('tgl_masuk');
            $tgl_tempo  = $this->input->post('tgl_tempo');
            $plgn       = $this->input->post('id_supplier');
            $supp       = $this->input->post('supplier');
            $ket        = $this->input->post('keterangan');
            $alamat     = $this->input->post('pengiriman');
            $status_ppn = $this->input->post('status_ppn');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id_supplier', 'ID Supplier', 'required');
            $this->form_validation->set_rules('tgl_masuk', 'Tgl Faktur', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id_supplier'   => form_error('id_supplier'),
                    'tgl_masuk'     => form_error('tgl_masuk'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('transaksi/beli/trans_beli_po_edit.php?id='.$id));
            } else {
                $tgl_msk = $this->tanggalan->tgl_indo_sys($tgl_masuk);
                $tgl_klr = $this->tanggalan->tgl_indo_sys($tgl_tempo);
                
                $sql_beli   = $this->db->where('YEAR(tgl_simpan)', date('Y'))->where('MONTH(tgl_simpan)', date('m'))->get('tbl_trans_beli_po');
                $nota_str   = $sql_beli->num_rows() + 1;
                $nota       = (!empty($no_nota) ? $no_nota : 'FP'.date('m').date('y').sprintf('%03d', $nota_str));

                $data = array(
                    'no_nota'      => $nota,
                    'tgl_simpan'   => date('Y-m-d H:i:s'),
                    'tgl_masuk'    => $tgl_msk,
                    'id_supplier'  => $plgn,
                    'id_user'      => $this->ion_auth->user()->row()->id,
                    'supplier'     => $supp,
                    'keterangan'   => $ket,
                    'pengiriman'   => $alamat
                );                
                crud::update('tbl_trans_beli_po', 'id', general::dekrip($id), $data);
                
                
                $this->session->set_userdata('trans_beli_po_edit', $data);
                redirect(base_url('transaksi/beli/trans_beli_po_edit.php?id='.$id));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_trans_beli_proses() {
        if (akses::aksesLogin() == TRUE) {
            $no_nota    = $this->input->post('no_nota');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('no_nota', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'no_nota'   => form_error('no_nota'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('transaksi/beli/trans_beli.php'));
            } else {
                $trans_beli  = $this->session->userdata('trans_beli');
                $pengaturan  = $this->db->get('tbl_pengaturan')->row();
                $tgl_msk     = $this->tanggalan->tgl_indo_sys($tgl_masuk);
                $tgl_klr     = $this->tanggalan->tgl_indo_sys($tgl_tempo);
                                
                $sql_supp    = $this->db->where('id', $trans_beli['id_supplier'])->get('tbl_m_supplier')->row();

                $data = array(
                    'id_po'        => $trans_beli['id_po'],
                    'no_po'        => $trans_beli['no_po'],
                    'no_nota'      => $trans_beli['no_nota'],
                    'tgl_simpan'   => date('Y-m-d H:i:s'),
                    'tgl_masuk'    => (!empty($trans_beli['tgl_masuk']) ? $trans_beli['tgl_masuk'] : '0000-00-00'),
                    'tgl_keluar'   => (!empty($trans_beli['tgl_keluar']) ? $trans_beli['tgl_keluar'] : '0000-00-00'),
                    'id_po'        => (!empty($trans_beli['id_po']) ? $trans_beli['id_po'] : '0'),
                    'id_supplier'  => (!empty($trans_beli['id_supplier']) ? $trans_beli['id_supplier'] : '0'),
                    'id_user'      => $this->ion_auth->user()->row()->id,
                    'supplier'     => $sql_supp->nama,
                    'jml_total'    => $this->cart->total(),
                    'status_ppn'   => (!empty($trans_beli['status_ppn']) ? $trans_beli['status_ppn'] : '0'),
                );
                crud::simpan('tbl_trans_beli', $data);
                $last_id = crud::last_id();
                
                $jml_total    = 0;
                $jml_diskon   = 0;
                $jml_potongan = 0;
                $jml_subtotal = 0;
                foreach ($this->cart->contents() as $cart){
                    $sql_brg      = $this->db->where('id', $cart['options']['id_barang'])->get('tbl_m_produk')->row();
                    $total        = $cart['price'] * $cart['qty'] * $cart['options']['jml_satuan'];
                    
                    $disk1        = $cart['price'] - (($cart['options']['disk1'] / 100) * $cart['price']);
                    $disk2        = $disk1 - (($cart['options']['disk2'] / 100) * $disk1);
                    $disk3        = $disk2 - (($cart['options']['disk3'] / 100) * $disk2);
                    
                    $hrg_pcs      = $cart['options']['subtotal'] / ($cart['options']['jml'] * $cart['options']['jml_satuan']);
                    $hrg_ppn      = ($trans_beli['status_ppn'] == 1 ? ($pengaturan->jml_ppn / 100) * $hrg_pcs : 0);
                    $hrg_pcs_akhir= $hrg_pcs + $hrg_ppn;
                     
                    $diskon       = $total - ($cart['options']['potongan'] + $cart['options']['subtotal']);
                    $jml_akhir    = $sql_brg->jml + ($cart['qty'] * $cart['options']['jml_satuan']);
                    $jml_total    = $jml_total + $cart['options']['subtotal'];
                    $jml_potongan = $jml_potongan + $cart['options']['potongan'];
                    $jml_subtotal = $jml_subtotal + $cart['options']['subtotal'];
                    $jml_diskon   = $jml_diskon + $diskon;
                    
                    $data_pemb_det = array(
                        'id_pembelian' => $last_id,
                        'id_produk'    => $sql_brg->id,
                        'id_satuan'    => $sql_brg->id_satuan,
                        'no_nota'      => $trans_beli['no_nota'],
                        'tgl_simpan'   => $trans_beli['tgl_simpan'],
                        'kode'         => $cart['options']['kode'],
                        'produk'       => $cart['name'],
                        'jml'          => $cart['options']['jml'],
                        'jml_satuan'   => (int)$cart['options']['jml_satuan'],
                        'satuan'       => $cart['options']['satuan'],
                        'keterangan'   => $cart['options']['satuan_ket'],
                        'harga'        => $cart['price'],
                        'disk1'        => ($cart['options']['disk1']),
                        'disk2'        => ($cart['options']['disk2']),
                        'disk3'        => ($cart['options']['disk3']),
                        'diskon'       => ($diskon),
                        'potongan'     => ceil($cart['options']['potongan']),
                        'subtotal'     => ($cart['options']['subtotal']),
                    );
                    
                     // Simpan stok barang
                     $data_brg = array(
                        'tgl_modif'         => date('Y-m-d H:i:s'),
                        'jml'               => (float)$jml_akhir,
                        'harga_beli'        => (float)$hrg_pcs_akhir,
                        'harga_beli_ppn'    => (float)$hrg_ppn,
                     );
                     
                     
                    crud::simpan('tbl_trans_beli_det', $data_pemb_det);
                    crud::update('tbl_m_produk', 'id', $cart['options']['id_barang'], $data_brg);
                                         
                     /* Catat log barang keluar ke tabel ini */
                     $data_pemb_hist = array(
                         'tgl_simpan'   => date('Y-m-d H:i'),
                         'id_supplier'  => $trans_beli['id_supplier'],
                         'id_produk'    => $sql_brg->id,
                         'id_user'      => $this->ion_auth->user()->row()->id,
                         'id_pembelian' => $last_id,
                         'no_nota'      => $trans_beli['no_nota'],
                         'kode'         => $cart['options']['kode'],
                         'keterangan'   => 'Pembelian '.$trans_beli['no_nota'],
                         'jml'          => (int)$cart['qty'],
                         'jml_satuan'   => (int)$cart['options']['jml_satuan'],
                         'satuan'       => $cart['options']['satuan'],
                         'nominal'      => (float)$cart['subtotal'],
                         'status'       => '1'
                     );
                     
                     crud::simpan('tbl_m_produk_hist', $data_pemb_hist);
                     /* -- END -- */

                    
//                     echo '<pre>';
//                     print_r($data_pemb_det);
//                     echo '</pre>';                    
//                     echo '<pre>';
//                     print_r($data_pemb_hist);
//                     echo '</pre>';
//                     echo '<pre>';
//                     print_r($data_brg);
//                     echo '</pre>';
                }
                
                if($trans_beli['status_ppn'] == '1'){
                    $jml_ppn        = ($trans_beli['status_ppn'] == 1 ? ($pengaturan->jml_ppn / 100) * $jml_subtotal : 0);
                    $jml_gtotal     = $jml_subtotal + $jml_ppn;
                    $jml_dpp        = $jml_subtotal - $jml_ppn;
                    $ppn            = $pengaturan->jml_ppn;
                }elseif($trans_beli['status_ppn'] == '2'){
                    $jml_ppn        = $jml_subtotal - ($jml_subtotal / $pengaturan->ppn);
                    $jml_gtotal     = $jml_subtotal;
                    $jml_dpp        = $jml_subtotal - $jml_ppn;
                    $ppn            = $pengaturan->jml_ppn;
                }else{
                    $ppn            = 0;
                    $jml_ppn        = 0;
                    $jml_gtotal     = $jml_subtotal + $jml_ppn;
                    $jml_dpp        = $jml_subtotal - $jml_ppn;
                }
                
                $data_pemb_update = array(
                    'jml_total'     => (float)$jml_total,
                    'jml_diskon'    => (float)$jml_diskon,
                    'jml_potongan'  => (float)$jml_potongan,
                    'jml_subtotal'  => (float)$jml_subtotal,
                    'jml_dpp'       => (float)$jml_dpp,
                    'ppn'           => (float)$ppn,
                    'jml_ppn'       => (float)$jml_ppn,
                    'jml_gtotal'    => (float)$jml_gtotal,
                    'status_retur'  => '0',
                );
                
                crud::update('tbl_trans_beli','id',$last_id, $data_pemb_update);

                /* -- Hapus semua session -- */
                $this->session->unset_userdata('trans_beli');
                $this->cart->destroy();
                /* -- Hapus semua session -- */

                $this->session->set_flashdata('transaksi', '<div class="alert alert-success">Transaksi berhasil disimpan</div>');
                redirect(base_url('transaksi/trans_beli_det.php?id='.general::enkrip($last_id)));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_trans_beli_proses_upd() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $no_nota    = $this->input->post('no_nota');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'   => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('transaksi/beli/trans_beli_edit.php?id='.$id));
            } else {
                $trans_beli  = $this->session->userdata('trans_beli_edit');
                $pengaturan  = $this->db->get('tbl_pengaturan')->row();
                $sql_beli    = $this->db->where('id', general::dekrip($id))->get('tbl_trans_beli')->row();
                $sql_beli_det= $this->db->select('SUM(diskon) AS diskon, SUM(potongan) AS potongan, SUM(subtotal) AS subtotal')->where('id_pembelian', $sql_beli->id)->get('tbl_trans_beli_det')->row();
                $tgl_msk     = $this->tanggalan->tgl_indo_sys($tgl_masuk);
                $tgl_klr     = $this->tanggalan->tgl_indo_sys($tgl_tempo);
                
                $jml_total      = $sql_beli_det->diskon + $sql_beli_det->potongan + $sql_beli_det->subtotal;
                $jml_diskon     = $sql_beli_det->diskon;
                $jml_potongan   = $sql_beli_det->potongan;
                $jml_subtotal   = $sql_beli_det->subtotal;
                  
                if($trans_beli['status_ppn'] == '1'){
                    $jml_ppn        = ($sql_beli->status_ppn == 1 ? ($pengaturan->jml_ppn / 100) * $sql_beli_det->subtotal : 0);
                    $jml_gtotal     = $sql_beli_det->subtotal + $jml_ppn;
                    $jml_dpp        = $sql_beli_det->subtotal - $jml_ppn;
                    $ppn            = $pengaturan->jml_ppn;
                }elseif($trans_beli['status_ppn'] == '2'){
                    $jml_ppn        = $sql_beli_det->subtotal - ($sql_beli_det->subtotal / $pengaturan->ppn);
                    $jml_gtotal     = $sql_beli_det->subtotal;
                    $jml_dpp        = $sql_beli_det->subtotal - $jml_ppn;
                    $ppn            = $pengaturan->jml_ppn;
                }else{
                    $ppn            = 0;
                    $jml_ppn        = 0;
                    $jml_gtotal     = $sql_beli_det->subtotal + $jml_ppn;
                    $jml_dpp        = $sql_beli_det->subtotal - $jml_ppn;
                }
                
                $data_pemb_update = array(
                    'jml_total'     => (float)$jml_total,
                    'jml_diskon'    => (float)$jml_diskon,
                    'jml_potongan'  => (float)$jml_potongan,
                    'jml_subtotal'  => (float)$jml_subtotal,
                    'jml_dpp'       => (float)$jml_dpp,
                    'ppn'           => (float)$ppn,
                    'jml_ppn'       => (float)$jml_ppn,
                    'jml_gtotal'    => (float)$jml_gtotal,
                    'status_retur'  => '0',
                );
                
                crud::update('tbl_trans_beli','id',$sql_beli->id, $data_pemb_update);

                /* -- Hapus semua session -- */
                $this->session->unset_userdata('trans_beli_edit');
                $this->cart->destroy();
                /* -- Hapus semua session -- */

                $this->session->set_flashdata('transaksi', '<div class="alert alert-success">Transaksi berhasil disimpan</div>');
                redirect(base_url('transaksi/beli/trans_beli_edit.php?id='.general::enkrip($sql_beli->id)));
                
//                echo '<pre>';
//                print_r($data_pemb_update);
//                echo '</pre>';
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_trans_beli_po_proses() {
        if (akses::aksesLogin() == TRUE) {
            $no_nota    = $this->input->post('no_nota');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('no_nota', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'no_nota'   => form_error('no_nota'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('transaksi/beli/trans_beli.php'));
            } else {
                $tgl_msk    = $this->tanggalan->tgl_indo_sys($tgl_masuk);
                $tgl_klr    = $this->tanggalan->tgl_indo_sys($tgl_tempo);
                
                $sql_beli   = $this->db->where('YEAR(tgl_simpan)', date('Y'))->where('MONTH(tgl_simpan)', date('m'))->get('tbl_trans_beli_po');
                $nota_str   = $sql_beli->num_rows() + 1;
                $nota       = 'PO'.date('m').date('y').sprintf('%03d', $nota_str);
                                
                $trans_beli  = $this->session->userdata('trans_beli_po');

                $data = array(
                    'no_nota'      => $nota,
                    'tgl_simpan'   => date('Y-m-d H:i:s'),
                    'tgl_masuk'    => (!empty($trans_beli['tgl_masuk']) ? $trans_beli['tgl_masuk'] : '0000-00-00'),
                    'tgl_keluar'   => (!empty($trans_beli['tgl_keluar']) ? $trans_beli['tgl_keluar'] : '0000-00-00'),
                    'id_supplier'  => (!empty($trans_beli['id_supplier']) ? $trans_beli['id_supplier'] : '0'),
                    'id_user'      => $this->ion_auth->user()->row()->id,
                    'keterangan'   => $trans_beli['keterangan'],
                    'pengiriman'   => $trans_beli['pengiriman']
                );
//                
//                echo '<pre>';
//                print_r($data);
//                echo '</pre>';
                
                crud::simpan('tbl_trans_beli_po', $data);
                $last_id = crud::last_id();
                
                foreach ($this->cart->contents() as $cart){
                    $sql_brg      = $this->db->where('id', $cart['options']['id_barang'])->get('tbl_m_produk')->row();
                    $total        = $cart['price'] * $cart['qty'] * $cart['options']['jml_satuan'];
                    
                    $data_pemb_det = array(
                        'id_pembelian' => $last_id,
                        'id_produk'    => $sql_brg->id,
                        'id_satuan'    => $sql_brg->id_satuan,
                        'no_nota'      => $trans_beli['no_nota'],
                        'tgl_simpan'   => $trans_beli['tgl_simpan'],
                        'kode'         => $cart['options']['kode'],
                        'produk'       => $cart['name'],
                        'jml'          => $cart['options']['jml'],
                        'jml_satuan'   => (int)$cart['options']['jml_satuan'],
                        'satuan'       => $cart['options']['satuan'],
                        'keterangan'   => $cart['options']['satuan_ket']
                    );
//                
//                    echo '<pre>';
//                    print_r($data_pemb_det);
//                    echo '</pre>';
                     
                    crud::simpan('tbl_trans_beli_po_det', $data_pemb_det);
                }
                
                $this->cart->destroy();
                $this->session->unset_userdata('trans_beli_po');
                
                redirect(base_url('transaksi/beli/trans_beli_po_det.php?id='.general::enkrip($last_id)));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_trans_beli_po_proses_upd() {
        if (akses::aksesLogin() == TRUE) {
            $id    = $this->input->post('id');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'   => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('transaksi/beli/trans_beli_po_list.php'));
            } else {
                $tgl_msk    = $this->tanggalan->tgl_indo_sys($tgl_masuk);
                $tgl_klr    = $this->tanggalan->tgl_indo_sys($tgl_tempo);
                
                $this->cart->destroy();
                $this->session->unset_userdata('trans_beli_po_edit');
                
                redirect(base_url('transaksi/beli/trans_beli_po_edit.php?id='.$id));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_trans_beli_po_batal() {
        if (akses::aksesLogin() == TRUE) {
            $no_nota  = $this->input->get('id');
            
            if(!empty($no_nota)){
                $this->session->unset_userdata('trans_beli_po');
            }
            
            redirect(base_url('transaksi/beli/trans_beli_po.php'));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    
    public function cart_beli_simpan() {
        if (akses::aksesLogin() == TRUE) {
            $rowid    = $this->input->post('rowid');
            $no_nota  = $this->input->post('no_nota');
            $id_brg   = $this->input->post('id_item');
            $satuan   = $this->input->post('satuan');
            $kode     = $this->input->post('kode');
            $qty      = general::format_angka_db($this->input->post('jml'));
            $diskon1  = general::format_angka_db($this->input->post('disk1'));
            $diskon2  = general::format_angka_db($this->input->post('disk2'));
            $diskon3  = general::format_angka_db($this->input->post('disk3'));
            $harga    = general::format_angka_db($this->input->post('harga'));
            $potongan = general::format_angka_db($this->input->post('potongan'));

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('kode', 'Kode', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'kode' => form_error('kode'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('transaksi/trans_beli.php?id='.$no_nota));
            } else {
                if(!empty($rowid)){
                    $cart = array(
                        'rowid' => $rowid,
                        'qty'   => 0
                    );
                    $this->cart->update($cart);
                }
                
                $sql_brg     = $this->db->where('id', general::dekrip($id_brg))
                                        ->get('tbl_m_produk')->row();
                $sql_satuan  = $this->db->where('id', $sql_brg->id_satuan)->get('tbl_m_satuan')->row();
                $trans_beli  = $this->session->userdata('trans_beli');
                $pengaturan  = $this->db->get('tbl_pengaturan')->row();

                $jml_pcs     = (!empty($sql_satuan->jml) ? $sql_satuan->jml : '1') * $qty;
                $harga_pcs   = ($harga * $qty) / $jml_pcs;
                $harga_sat   = $harga_pcs * $sql_satuan->jml;

                $disk1       = $harga_pcs - (($diskon1 / 100) * $harga_pcs);
                $disk2       = $disk1 - (($diskon2 / 100) * $disk1);
                $disk3       = $disk2 - (($diskon3 / 100) * $disk2);

                $harga_ppn   = ($trans_beli['status_ppn'] == '1' ? ($pengaturan->jml_ppn / 100) * $disk3 : 0);
                $harga_tot   = $disk3 + $harga_ppn;
                $subtotal    = ($disk3 * $jml_pcs) - $potongan;
                $jml_qty     = general::format_angka_db($qty);

                $jml_satuan  = $sql_satuan2->jml * $qty;

                // Cek di keranjang
                foreach ($this->cart->contents() as $cart){
                    // Cek ada datanya kagak?
                    if($sql_brg->kode == $cart['options']['kode']){
                        $jml_subtotal      = ($cart['qty'] + $qty) * $sql_satuan->jml;
                        $jml_qty           = ($cart['qty'] + $qty);

                        $this->cart->update(array('rowid'=>$cart['rowid'], 'qty'=>0));
                    }
                }

                $cart = array(
                    'id'      => rand(1,1024).$sql_brg->id,
                    'qty'     => $jml_qty,
                    'price'   => $harga, // number_format($harga, 2, '.',','),
                    'name'    => rtrim($sql_brg->produk),
                    'options' => array(
                            'no_nota'       => general::dekrip($no_nota),
                            'id_barang'     => $sql_brg->id,
                            'id_satuan'     => $sql_brg->id_satuan,
                            'satuan'        => $sql_satuan->satuanTerkecil,
//                            'satuan_ket'    => ($sql_satuan->jml != 1 ? ' ('.(!empty($jml_subtotal) ? $jml_qty : $qty) * $sql_satuan->jml.' '.$sql_satuan2->satuanTerkecil.')' : ''),
                            'jml'           => $qty,
                            'jml_satuan'    => (!empty($sql_satuan->jml) ? $sql_satuan->jml : '1'),
                            'kode'          => $sql_brg->kode,
                            'harga'         => $harga_tot,
                            'ppn'           => $harga_ppn,
                            'potongan'      => $potongan,
                            'disk1'         => (float)$diskon1,
                            'disk2'         => (float)$diskon2,
                            'disk3'         => (float)$diskon3,
                            'subtotal'      => (float)$subtotal,
                    )
                );
                
//                echo '<pre>';
//                print_r($cart);
//                echo '</pre>';
//                echo '<pre>';
//                print_r($this->cart->contents());
//                echo '</pre>';

                $this->cart->insert($cart);
                redirect(base_url('transaksi/beli/trans_beli.php?id='.$no_nota));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function cart_beli_upd() {
        if (akses::aksesLogin() == TRUE) {
            $id       = $this->input->post('id');
            $no_nota  = $this->input->post('no_nota');
            $id_brg   = $this->input->post('id_item');
            $satuan   = $this->input->post('satuan');
            $kode     = $this->input->post('kode');
            $qty      = general::format_angka_db($this->input->post('jml'));
            $diskon1  = general::format_angka_db($this->input->post('disk1'));
            $diskon2  = general::format_angka_db($this->input->post('disk2'));
            $diskon3  = general::format_angka_db($this->input->post('disk3'));
            $harga    = general::format_angka_db($this->input->post('harga'));
            $potongan = general::format_angka_db($this->input->post('potongan'));

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('kode', 'Kode', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'kode' => form_error('kode'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('transaksi/trans_beli.php?id='.$no_nota));
            } else {
                $sql_brg     = $this->db->where('id', general::dekrip($id_brg))->get('tbl_m_produk')->row();
                $sql_satuan  = $this->db->where('id', $sql_brg->id_satuan)->get('tbl_m_satuan')->row();
                $sql_beli    = $this->db->where('id', general::dekrip($id))->get('tbl_trans_beli')->row();
                $trans_beli  = $this->session->userdata('trans_beli_edit');
                $pengaturan  = $this->db->get('tbl_pengaturan')->row();

                $jml_pcs     = (!empty($sql_satuan->jml) ? $sql_satuan->jml : '1') * $qty;
                $harga_pcs   = ($harga * $qty) / $jml_pcs;
                $harga_sat   = $harga_pcs * $sql_satuan->jml;

                $disk1       = $harga_pcs - (($diskon1 / 100) * $harga_pcs);
                $disk2       = $disk1 - (($diskon2 / 100) * $disk1);
                $disk3       = $disk2 - (($diskon3 / 100) * $disk2);
                $diskon      = $harga - $disk3;

                $harga_ppn   = ($trans_beli['status_ppn'] == '1' ? ($pengaturan->jml_ppn / 100) * $disk3 : 0);
                $harga_tot   = $disk3 + $harga_ppn;
                $subtotal    = ($disk3 * $jml_pcs) - $potongan;
                $jml_qty     = $qty;
                $jml_satuan  = $sql_satuan2->jml * $qty;

                $data_pemb_det = array(
                    'id_pembelian' => (int)general::dekrip($id),
                    'id_produk'    => (int)$sql_brg->id,
                    'id_satuan'    => (int)$sql_brg->id_satuan,
                    'no_nota'      => $sql_beli->no_nota,
                    'tgl_simpan'   => date('Y-m-d H:i:s'),
                    'kode'         => $sql_brg->kode,
                    'produk'       => $sql_brg->produk,
                    'jml'          => (float)$qty,
                    'jml_satuan'   => (int)$sql_satuan->jml,
                    'satuan'       => $sql_satuan->satuanTerkecil,
                    'keterangan'   => '',
                    'harga'        => (float)$harga,
                    'disk1'        => (float)$diskon1,
                    'disk2'        => (float)$diskon2,
                    'disk3'        => (float)$diskon3,
                    'diskon'       => (float)$diskon,
                    'potongan'     => (float)$potongan,
                    'subtotal'     => (float)$subtotal,
                );
                
                // Simpan ke pembelian
                crud::simpan('tbl_trans_beli_det', $data_pemb_det);
                
                
                $sql_item       = $this->db->where('id', $sql_brg->id)->get('tbl_m_produk')->row();
                $stok_akhir     = $sql_item->jml + $qty;
                
                // Update Stok Akhirnya
                crud::update('tbl_m_produk', 'id', $sql_brg->id, array('jml'=>$stok_akhir));
                                
                
//                echo '<pre>';
//                print_r($data_pemb_det);
//                echo '</pre>';
//                echo '<pre>';
//                print_r($this->cart->contents());
//                echo '</pre>';
//                
                redirect(base_url('transaksi/beli/trans_beli_edit.php?id='.$id));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function cart_beli_po_simpan() {
        if (akses::aksesLogin() == TRUE) {
            $no_nota  = $this->input->post('no_nota');
            $id_brg   = $this->input->post('id_item');
            $satuan   = $this->input->post('satuan');
            $kode     = $this->input->post('kode');
            $qty      = $this->input->post('jml');
            $ket      = $this->input->post('ket');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('kode', 'Kode', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'kode' => form_error('kode'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('transaksi/trans_beli.php?id='.$no_nota));
            } else {
                $sql_brg     = $this->db->where('id', general::dekrip($id_brg))
                                        ->get('tbl_m_produk')->row();
                $sql_satuan  = $this->db->where('id', $sql_brg->id_satuan)->get('tbl_m_satuan')->row();
                $trans_beli  = $this->session->userdata('trans_beli');
                $pengaturan  = $this->db->get('tbl_pengaturan')->row();

                $jml_pcs     = (!empty($sql_satuan->jml) ? $sql_satuan->jml : '1') * $qty;
                $harga_pcs   = ($harga * $qty) / $jml_pcs;
                $harga_sat   = $harga_pcs * $sql_satuan->jml;

                $disk1       = $harga_pcs - (($diskon1 / 100) * $harga_pcs);
                $disk2       = $disk1 - (($diskon2 / 100) * $disk1);
                $disk3       = $disk2 - (($diskon3 / 100) * $disk2);

                $harga_ppn   = ($trans_beli['status_ppn'] == '1' ? ($pengaturan->jml_ppn / 100) * $disk3 : 0);
                $harga_tot   = $disk3 + $harga_ppn;
                $subtotal    = ($disk3 * $jml_pcs) - $potongan;
                $jml_qty     = general::format_angka_db($qty);

                $jml_satuan  = $sql_satuan2->jml * $qty;

                // Cek di keranjang
                foreach ($this->cart->contents() as $cart){
                    // Cek ada datanya kagak?
                    if($sql_brg->kode == $cart['options']['kode']){
                        $jml_subtotal      = ($cart['qty'] + $qty) * $sql_satuan->jml;
                        $jml_qty           = ($cart['qty'] + $qty);

                        $this->cart->update(array('rowid'=>$cart['rowid'], 'qty'=>0));
                    }
                }

                $cart = array(
                    'id'      => rand(1,1024).$sql_brg->id,
                    'qty'     => (float)$jml_qty,
                    'price'   => 1, // number_format($harga, 2, '.',','),
                    'name'    => rtrim($sql_brg->produk),
                    'options' => array(
                            'no_nota'       => general::dekrip($no_nota),
                            'id_barang'     => $sql_brg->id,
                            'id_satuan'     => $sql_brg->id_satuan,
                            'satuan'        => $sql_satuan->satuanTerkecil,
                            'jml'           => $qty,
                            'jml_satuan'    => (!empty($sql_satuan->jml) ? $sql_satuan->jml : '1'),
                            'kode'          => $sql_brg->kode,
                            'harga'         => $harga_tot,
                            'keterangan'    => $ket,
                    )
                );

                $this->cart->insert($cart);
                redirect(base_url('transaksi/beli/trans_beli_po.php?id='.$no_nota));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function cart_beli_po_upd() {
        if (akses::aksesLogin() == TRUE) {
            $id       = $this->input->post('id');
            $no_nota  = $this->input->post('no_nota');
            $id_brg   = $this->input->post('id_item');
            $satuan   = $this->input->post('satuan');
            $kode     = $this->input->post('kode');
            $qty      = $this->input->post('jml');
            $ket      = $this->input->post('ket');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('kode', 'Kode', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'kode' => form_error('kode'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('transaksi/trans_beli_po_edit.php?id='.$id));
            } else {
                $sql          = $this->db->where('id', general::dekrip($id_brg))->get('tbl_trans_beli_po')->row();
                $sql_brg      = $this->db->where('id', general::dekrip($id_brg))->get('tbl_m_produk')->row();
                $sql_satuan   = $this->db->where('id', $satuan)->get('tbl_m_satuan')->row();
                $total        = $cart['price'] * $cart['qty'] * $cart['options']['jml_satuan'];
                
                $data_pemb_det = array(
                    'id_pembelian' => general::dekrip($id),
                    'id_produk'    => $sql_brg->id,
                    'id_satuan'    => $sql_brg->id_satuan,
                    'no_nota'      => $sql->no_nota,
                    'tgl_simpan'   => date('Y-m-d H:i:s'),
                    'kode'         => $sql_brg->kode,
                    'produk'       => $sql_brg->produk,
                    'jml'          => general::format_angka_db($qty),
                    'jml_satuan'   => (int)$cart['options']['jml_satuan'],
                    'satuan'       => $sql_satuan->satuanTerkecil,
                    'keterangan'   => $ket
                );
            
//                echo '<pre>';
//                print_r($data_pemb_det);
//                echo '</pre>';
                 
                crud::simpan('tbl_trans_beli_po_det', $data_pemb_det);
                redirect(base_url('transaksi/beli/trans_beli_po_edit.php?id='.$id));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function cart_beli_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_item    = $this->input->get('item_id');
            $rute       = $this->input->get('route');

            if(!empty($id_item)){
                $cart = array(
                    'rowid' => general::dekrip($id_item),
                    'qty'   => 0
                );
                $this->cart->update($cart);
            }

            redirect(base_url((!empty($rute) ? $rute : 'transaksi/beli/trans_beli.php').'?id='.$id));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function cart_beli_upd_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_item    = $this->input->get('item_id');
            $rute       = $this->input->get('route');

            if(!empty($id_item)){
                $sql_beli_det   = $this->db->where('id', general::dekrip($id_item))->get('tbl_trans_beli_det')->row();
                $sql_item       = $this->db->where('id', $sql_beli_det->id_produk)->get('tbl_m_produk')->row();
                $stok_akhir     = $sql_item->jml - $sql_beli_det->jml;
                
                // Update Stok Akhirnya
//                crud::update('tbl_m_produk', 'id', $sql_item->id, array('jml'=>$stok_akhir));
                
                // Hapus dari detail pembelian
                crud::delete('tbl_trans_beli_det', 'id', general::dekrip($id_item));
            }

            redirect(base_url((!empty($rute) ? $rute : 'transaksi/beli/trans_beli_edit.php').'?id='.$id));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function cart_beli_po_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_item    = $this->input->get('item_id');
            $rute       = $this->input->get('route');

            if(!empty($id_item)){
                $cart = array(
                    'rowid' => general::dekrip($id_item),
                    'qty'   => 0
                );
                $this->cart->update($cart);
            }

            redirect(base_url((!empty($rute) ? $rute : 'transaksi/beli/trans_beli_po.php').'?id='.$id));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function cart_beli_po_upd_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_item    = $this->input->get('item_id');
            $rute       = $this->input->get('route');

            if(!empty($id_item)){
                crud::delete('tbl_trans_beli_po_det', 'id', general::dekrip($id_item));
            }

            redirect(base_url((!empty($rute) ? $rute : 'transaksi/beli/trans_beli_po_edit.php').'?id='.$id));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_cari_pemb() {
        if (akses::aksesLogin() == TRUE) {
            $no_nota   = $this->input->post('no_nota');
            $lokasi    = $this->input->post('cabang');
            $tgl_trans = $this->input->post('tgl');
            $tgl_tempo = $this->input->post('tgl_tempo');
            $tgl_bayar = $this->input->post('tgl_bayar');
            $supplier  = $this->input->post('supplier');
            $sb        = $this->input->post('filter_bayar');
            $rute      = $this->input->post('route');

            redirect(base_url('transaksi/beli/index'.(!empty($rute) ? '_tempo' : '').'.php?'.(!empty($rute) ? 'route=tempo&' : '').(!empty($no_nota) ? 'filter_nota='.$no_nota : '').(!empty($lokasi) ? '&filter_lokasi='.$lokasi : '').(!empty($tgl_trans) ? '&filter_tgl='.$this->tanggalan->tgl_indo_sys($tgl_trans) : '').(!empty($tgl_tempo) ? '&filter_tgl_tempo='.$this->tanggalan->tgl_indo_sys($tgl_tempo) : '').(!empty($supplier) ? '&filter_supplier='.$supplier : '').(!empty($tgl_bayar) ? '&filter_tgl_bayar='.$this->tanggalan->tgl_indo_sys($tgl_bayar) : '').(isset($sb) && !empty($sb) ? '&filter_bayar='.$sb : '')));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_cari_pemb_po() {
        if (akses::aksesLogin() == TRUE) {
            $no_nota   = $this->input->post('no_nota');
            $lokasi    = $this->input->post('cabang');
            $tgl_trans = $this->input->post('tgl');
            $tgl_tempo = $this->input->post('tgl_tempo');
            $tgl_bayar = $this->input->post('tgl_bayar');
            $supplier  = $this->input->post('supplier');
            $sb        = $this->input->post('filter_bayar');
            $rute      = $this->input->post('route');

            redirect(base_url('transaksi/beli/trans_beli_po_list'.(!empty($rute) ? '_tempo' : '').'.php?'.(!empty($rute) ? 'route=tempo&' : '').(!empty($no_nota) ? 'filter_nota='.$no_nota : '').(!empty($lokasi) ? '&filter_lokasi='.$lokasi : '').(!empty($tgl_trans) ? '&filter_tgl='.$this->tanggalan->tgl_indo_sys($tgl_trans) : '').(!empty($tgl_tempo) ? '&filter_tgl_tempo='.$this->tanggalan->tgl_indo_sys($tgl_tempo) : '').(!empty($supplier) ? '&filter_supplier='.$supplier : '').(!empty($tgl_bayar) ? '&filter_tgl_bayar='.$this->tanggalan->tgl_indo_sys($tgl_bayar) : '').(isset($sb) && !empty($sb) ? '&filter_bayar='.$sb : '')));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function json_po() {
        if (akses::aksesLogin() == TRUE) {
            $term  = $this->input->get('term');
            $sql   = $this->db->select('tbl_trans_beli_po.id, tbl_trans_beli_po.id_supplier, tbl_trans_beli_po.no_nota, tbl_m_supplier.nama AS supplier, tbl_trans_beli_po.pengiriman')
                              ->join('tbl_m_supplier','tbl_m_supplier.id=tbl_trans_beli_po.id_supplier')
                              ->like('tbl_m_supplier.nama',$term)
                              ->or_like('tbl_trans_beli_po.no_nota',$term)
                              ->limit(10)->get('tbl_trans_beli_po')->result();
            
            if(!empty($sql)){
                foreach ($sql as $sql){
                    $sql_supp = $this->db->where('id', $sql->id_supplier)->get('tbl_m_supplier')->row();
                            
                    $supp[] = array(
                        'id'            => general::enkrip($sql->id),
                        'id_supplier'   => general::enkrip($sql->id_supplier),
                        'no_nota'       => $sql->no_nota,
                        'supplier'      => $sql->supplier,
                        'alamat'        => $sql_supp->alamat,
                    );
                }
                
                if(!empty($term)){
                    echo json_encode($supp);
                }
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function json_supplier() {
        if (akses::aksesLogin() == TRUE) {
            $term  = $this->input->get('term');
            $sql   = $this->db->select('id, kode, npwp, nama, alamat')
                              ->like('nama',$term)
                              ->or_like('kode',$term)
                              ->or_like('alamat',$term)
                              ->limit(10)->get('tbl_m_supplier')->result();
            
            if(!empty($sql)){
                foreach ($sql as $sql){
                    $produk[] = array(
                        'id'         => $sql->id,
                        'kode'       => $sql->kode,
                        'npwp'       => $sql->npwp,
                        'nama'       => $sql->nama,
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
    
    public function json_item() {
        if (akses::aksesLogin() == TRUE) {
            $term  = $this->input->get('term');
            $sql   = $this->db->select('id, kode, produk, jml, harga_beli')
//                              ->where('status_subt','1')
                              ->like('kode',$term)
                              ->or_like('produk',$term)
                              ->limit(10)->get('tbl_m_produk')->result();
            
            if(!empty($sql)){
                foreach ($sql as $sql){
                    $produk[] = array(
                        'id'        => $sql->id,
                        'id_item'   => general::enkrip($sql->id),
                        'kode'      => $sql->kode,
                        'produk'    => $sql->produk,
                        'jml'       => (float)$sql->jml,
                        'harga_beli'=> (float)$sql->harga_beli,
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
}
