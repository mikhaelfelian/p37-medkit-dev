<?php
/**
 * Description of transaksi
 *
 * @author USER
 */
class medcheck extends CI_Controller {
    // put your code here
    function __construct() {
        parent::__construct();
        $this->load->library('cart');
        $this->load->library('excel/PHPExcel');

        $this->load->library('qrcode/ciqrcode');
    }
    
    public function index() {
        if (akses::aksesLogin() == TRUE) {
            /* -- Grup hak akses -- */
            $role        = $this->input->get('role');
            $tipe        = $this->input->get('tipe');
            $grup        = $this->ion_auth->get_users_groups()->row();
            $id_user     = $this->ion_auth->user()->row()->id;
            $id_grup     = $this->ion_auth->get_users_groups()->row();
            $id_dokter   = $this->ion_auth->get_users_groups()->row();
            $pengaturan  = $this->db->get('tbl_pengaturan')->row();

            /* -- Blok Filter -- */
            $hal     = $this->input->get('halaman');
            $id      = $this->input->get('id');
            $cs      = $this->input->get('filter_nama');
            $by      = $this->input->get('filter_bayar');
            $tp      = $this->input->get('filter_tipe');
            $tg      = $this->input->get('filter_tgl');
            $sp      = $this->input->get('filter_periksa');
            $jml     = $this->input->get('jml');
            
            # Jika User dokter, maka pilih rajal dan ranap
            if(akses::hakDokter()){
//                $jml_sql = $this->db->query(""
//                            . "SELECT "
//                            . "DISTINCT tbl_trans_medcheck.id, tbl_trans_medcheck.id_app, tbl_trans_medcheck.id_user, tbl_trans_medcheck.id_dokter, tbl_trans_medcheck.id_nurse, tbl_trans_medcheck.id_analis, tbl_trans_medcheck.id_pasien, tbl_trans_medcheck.id_poli, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.tgl_simpan, TIME(tbl_trans_medcheck.tgl_simpan) AS waktu_masuk, DATE(tbl_trans_medcheck.tgl_bayar) as tgl_bayar, DATE(tbl_trans_medcheck.tgl_keluar) as tgl_keluar, TIME(tbl_trans_medcheck.tgl_keluar) AS waktu_keluar, tbl_trans_medcheck.jml_total, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.ppn, tbl_trans_medcheck.jml_ppn, tbl_trans_medcheck.tipe, tbl_trans_medcheck.status, tbl_trans_medcheck.status_nota, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_trans_medcheck.status_periksa "
//                            . "FROM tbl_trans_medcheck "
//                            . "LEFT JOIN tbl_trans_medcheck_dokter ON tbl_trans_medcheck.id_dokter=tbl_trans_medcheck_dokter.id_dokter "
//                            . "WHERE tbl_trans_medcheck.status_hps ='0' "
//                            . "AND tbl_trans_medcheck.tipe != '1' "
//                            . "AND tbl_trans_medcheck.tipe != '4' "
//                            . "AND tbl_trans_medcheck.tipe LIKE '%".$tp."%' "
//                            . "AND tbl_trans_medcheck.status_bayar LIKE '%".$by."%' "
//                            . "AND tbl_trans_medcheck.id_dokter = '".$id_user."' "
//                            . "AND tbl_trans_medcheck.pasien LIKE '%".$cs."%' "
//                            . "AND DATE(tbl_trans_medcheck.tgl_masuk) LIKE '%".$this->tanggalan->tgl_indo_sys($tg)."%' "
//                            . "AND tbl_trans_medcheck_dokter.id_dokter LIKE '%".$id_user."%' "
//                            . "ORDER BY tbl_trans_medcheck.id DESC;"
//                            . "")->num_rows();
                
                $jml_sql =  $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.id_app, tbl_trans_medcheck.id_user, tbl_trans_medcheck.id_dokter, tbl_trans_medcheck.id_nurse, tbl_trans_medcheck.id_analis, tbl_trans_medcheck.id_pasien, tbl_trans_medcheck.id_poli, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.tgl_simpan, TIME(tbl_trans_medcheck.tgl_simpan) AS waktu_masuk, DATE(tbl_trans_medcheck.tgl_bayar) as tgl_bayar, DATE(tbl_trans_medcheck.tgl_keluar) as tgl_keluar, TIME(tbl_trans_medcheck.tgl_keluar) AS waktu_keluar, tbl_trans_medcheck.jml_total, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.ppn, tbl_trans_medcheck.jml_ppn, tbl_trans_medcheck.tipe, tbl_trans_medcheck.status, tbl_trans_medcheck.status_nota, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_trans_medcheck.status_periksa')
                                    ->join('tbl_trans_medcheck_dokter', 'tbl_trans_medcheck_dokter.id_medcheck=tbl_trans_medcheck.id', 'left')
                                    ->where('tbl_trans_medcheck.status_hps', '0')
                                    ->where('tbl_trans_medcheck.tipe !=', '1')
                                    ->where('tbl_trans_medcheck.tipe !=', '4')
                                    ->where('tbl_trans_medcheck.status_bayar', $by)
                                    ->where('tbl_trans_medcheck.id_dokter', $id_user)
                                    ->like('DATE(tbl_trans_medcheck.tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                    ->like('tbl_trans_medcheck.pasien', $cs)
                                    ->like('tbl_trans_medcheck.tipe', $tp)
//                                    ->like('tbl_trans_medcheck.id_dokter', ($id_grup->name == 'dokter' ? $id_user : ''), ($id_grup->name == 'dokter' ? 'none' : ''))
                                    ->like('tbl_trans_medcheck_dokter.id_dokter', $id_user, ($id_grup->name == 'dokter' ? $id_user : ''))
//                                    ->like('tbl_trans_medcheck.status_periksa', $sp)
//                                    ->like('tbl_trans_medcheck.status_bayar', $by, (isset($_GET['filter_bayar']) ? 'none' : ''))
                                    ->order_by('id', 'desc')
                                    ->get('tbl_trans_medcheck')->num_rows();
            }else{
                $jml_sql = $this->db->select('id, id_app, id_user, id_dokter, id_nurse, id_analis, id_pasien, id_poli, no_nota, no_rm, tgl_simpan, TIME(tgl_simpan) AS waktu_masuk, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, TIME(tgl_keluar) AS waktu_keluar, jml_total, jml_gtotal, ppn, jml_ppn, tipe, status, status_nota, tipe, status_periksa')
                                ->where('status_hps', '0')
                                ->like('DATE(tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                ->like('tipe', ($id_grup->name == 'perawat_ranap' ? '3' : ''), ($id_grup->name == 'perawat_ranap' ? 'none' : ''))
                                ->like('id_dokter', ($id_grup->name == 'dokter' ? $id_user : ''), ($id_grup->name == 'dokter' ? 'none' : ''))
                                ->like('id', general::dekrip($id))
                                ->like('pasien', $cs)
                                ->like('tipe', $tp)
                                ->like('status_bayar', $by)
                                ->like('status_periksa', $sp)
                                ->order_by('id','desc')
                                ->get('tbl_trans_medcheck')->num_rows();                
            }

            if(!empty($jml)){
                $jml_hal = $jml;
            }else{
                $jml_hal = $jml_sql;
            }
            /* -- End Blok Filter -- */

            /* -- Form Error -- */
            $data['hasError']                = $this->session->flashdata('form_error');

            /* -- Blok Pagination -- */
            $config['base_url']              = base_url('medcheck/index.php?tipe=1'.(!empty($cs) ? '&filter_nama='.$cs : '').(!empty($tg) ? '&filter_tgl='.$tg : '').(!empty($tp) ? '&filter_tipe='.$tp : '').(!empty($sp) ? '&filter_periksa='.$sp : '').(isset($by) ? '&filter_bayar='.$by : '').(!empty($jml_hal) ? '&jml='.$jml_hal : ''));
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

            // Jika User dokter, maka pilih rajal dan ranap
            if(akses::hakDokter() == TRUE){
                if (!empty($hal)) {
//                    $data['penj']   = $this->db->query(""
//                            . "SELECT "
//                            . "DISTINCT tbl_trans_medcheck.id, tbl_trans_medcheck.id_app, tbl_trans_medcheck.id_user, tbl_trans_medcheck.id_dokter, tbl_trans_medcheck.id_nurse, tbl_trans_medcheck.id_analis, tbl_trans_medcheck.id_pasien, tbl_trans_medcheck.id_poli, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.tgl_simpan, TIME(tbl_trans_medcheck.tgl_simpan) AS waktu_masuk, DATE(tbl_trans_medcheck.tgl_bayar) as tgl_bayar, DATE(tbl_trans_medcheck.tgl_keluar) as tgl_keluar, TIME(tbl_trans_medcheck.tgl_keluar) AS waktu_keluar, tbl_trans_medcheck.jml_total, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.ppn, tbl_trans_medcheck.jml_ppn, tbl_trans_medcheck.tipe, tbl_trans_medcheck.status, tbl_trans_medcheck.status_nota, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_trans_medcheck.status_periksa "
//                            . "FROM tbl_trans_medcheck "
//                            . "LEFT JOIN tbl_trans_medcheck_dokter ON tbl_trans_medcheck.id_dokter=tbl_trans_medcheck_dokter.id_dokter "
//                            . "WHERE tbl_trans_medcheck.status_hps ='0' "
//                            . "AND tbl_trans_medcheck.tipe != '1' "
//                            . "AND tbl_trans_medcheck.tipe != '4' "
//                            . "AND tbl_trans_medcheck.tipe LIKE '%".$tp."%' "
//                            . "AND tbl_trans_medcheck.status_bayar LIKE '%".$by."%' "
//                            . "AND tbl_trans_medcheck.id_dokter = '".$id_user."' "
//                            . "AND tbl_trans_medcheck.pasien LIKE '%".$cs."%' "
//                            . "AND DATE(tbl_trans_medcheck.tgl_masuk) LIKE '%".$this->tanggalan->tgl_indo_sys($tg)."%' "
//                            . "AND tbl_trans_medcheck_dokter.id_dokter LIKE '%".$id_user."%' "
//                            . "ORDER BY tbl_trans_medcheck.id DESC LIMIT ".$hal.",".$config['per_page'].";"
//                            . "")->result();
                    
                    $data['penj'] = $this->db
                                    ->select('tbl_trans_medcheck.id, tbl_trans_medcheck.id_app, tbl_trans_medcheck.id_dft, tbl_trans_medcheck.id_user, tbl_trans_medcheck.id_dokter, tbl_trans_medcheck.id_nurse, tbl_trans_medcheck.id_analis, tbl_trans_medcheck.id_pasien, tbl_trans_medcheck.id_poli, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.tgl_simpan, TIME(tbl_trans_medcheck.tgl_simpan) AS waktu_masuk, DATE(tbl_trans_medcheck.tgl_bayar) as tgl_bayar, DATE(tbl_trans_medcheck.tgl_keluar) as tgl_keluar, TIME(tbl_trans_medcheck.tgl_keluar) AS waktu_keluar, tbl_trans_medcheck.jml_total, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.ppn, tbl_trans_medcheck.jml_ppn, tbl_trans_medcheck.tipe, tbl_trans_medcheck.status, tbl_trans_medcheck.status_nota, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_trans_medcheck.status_periksa')
                                    ->join('tbl_trans_medcheck_dokter', 'tbl_trans_medcheck_dokter.id_medcheck=tbl_trans_medcheck.id', 'left')
                                    ->where('tbl_trans_medcheck.status_hps', '0')
                                    ->where('tbl_trans_medcheck.tipe !=', '1')
                                    ->where('tbl_trans_medcheck.tipe !=', '4')
                                    ->where('tbl_trans_medcheck.status_bayar', $by)
                                    ->like('DATE(tbl_trans_medcheck.tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                    ->like('tbl_trans_medcheck.pasien', $cs)
                                    ->like('tbl_trans_medcheck.tipe', $tp)
//                                    ->like('tbl_trans_medcheck.status_bayar', $by, (isset($_GET['filter_bayar']) ? 'none' : ''))
//                                    ->like('tbl_trans_medcheck.status_periksa', $sp)
                                    ->like('tbl_trans_medcheck.id_dokter', ($id_grup->name == 'dokter' ? $id_user : ''), ($id_grup->name == 'dokter' ? 'none' : ''))
                                    ->or_like('tbl_trans_medcheck_dokter.id_dokter', $id_user)
                                    ->limit($config['per_page'], $hal)
                                    ->order_by('id', 'desc')
                                    ->get('tbl_trans_medcheck')->result();
                } else {
//                    $data['penj']   = $this->db->query(""
//                            . "SELECT "
//                            . "DISTINCT tbl_trans_medcheck.id, tbl_trans_medcheck.id_app, tbl_trans_medcheck.id_user, tbl_trans_medcheck.id_dokter, tbl_trans_medcheck.id_nurse, tbl_trans_medcheck.id_analis, tbl_trans_medcheck.id_pasien, tbl_trans_medcheck.id_poli, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.tgl_simpan, TIME(tbl_trans_medcheck.tgl_simpan) AS waktu_masuk, DATE(tbl_trans_medcheck.tgl_bayar) as tgl_bayar, DATE(tbl_trans_medcheck.tgl_keluar) as tgl_keluar, TIME(tbl_trans_medcheck.tgl_keluar) AS waktu_keluar, tbl_trans_medcheck.jml_total, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.ppn, tbl_trans_medcheck.jml_ppn, tbl_trans_medcheck.tipe, tbl_trans_medcheck.status, tbl_trans_medcheck.status_nota, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_trans_medcheck.status_periksa "
//                            . "FROM tbl_trans_medcheck "
//                            . "LEFT JOIN tbl_trans_medcheck_dokter ON tbl_trans_medcheck.id_dokter=tbl_trans_medcheck_dokter.id_dokter "
//                            . "WHERE tbl_trans_medcheck.status_hps ='0' "
//                            . "AND tbl_trans_medcheck.tipe != '1' "
//                            . "AND tbl_trans_medcheck.tipe != '4' "
//                            . "AND tbl_trans_medcheck.tipe LIKE '%".$tp."%' "
//                            . "AND tbl_trans_medcheck.status_bayar LIKE '%".$by."%' "
//                            . "AND tbl_trans_medcheck.id_dokter LIKE '%".$id_user."%' "
//                            . "AND tbl_trans_medcheck.pasien LIKE '%".$cs."%' "
//                            . "AND DATE(tbl_trans_medcheck.tgl_masuk) LIKE '%".$this->tanggalan->tgl_indo_sys($tg)."%' "
//                            . "OR tbl_trans_medcheck_dokter.id_dokter LIKE '%".$id_user."%' "
//                            . "ORDER BY tbl_trans_medcheck.id DESC LIMIT ".$config['per_page'].";"
//                            . "")->result();
                    $data['penj'] = $this->db
                                    ->select('tbl_trans_medcheck.id, tbl_trans_medcheck.id_app, tbl_trans_medcheck.id_dft, tbl_trans_medcheck.id_user, tbl_trans_medcheck.id_dokter, tbl_trans_medcheck.id_nurse, tbl_trans_medcheck.id_analis, tbl_trans_medcheck.id_pasien, tbl_trans_medcheck.id_poli, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.tgl_simpan, TIME(tbl_trans_medcheck.tgl_simpan) AS waktu_masuk, DATE(tbl_trans_medcheck.tgl_bayar) as tgl_bayar, DATE(tbl_trans_medcheck.tgl_keluar) as tgl_keluar, TIME(tbl_trans_medcheck.tgl_keluar) AS waktu_keluar, tbl_trans_medcheck.jml_total, tbl_trans_medcheck.jml_gtotal, tbl_trans_medcheck.ppn, tbl_trans_medcheck.jml_ppn, tbl_trans_medcheck.tipe, tbl_trans_medcheck.status, tbl_trans_medcheck.status_nota, tbl_trans_medcheck.status_bayar, tbl_trans_medcheck.tipe, tbl_trans_medcheck.status_periksa')
                                    ->join('tbl_trans_medcheck_dokter', 'tbl_trans_medcheck_dokter.id_medcheck=tbl_trans_medcheck.id', 'left')
                                    ->where('tbl_trans_medcheck.status_hps', '0')
                                    ->where('tbl_trans_medcheck.tipe !=', '1')
                                    ->where('tbl_trans_medcheck.tipe !=', '4')
                                    ->where('tbl_trans_medcheck.status_bayar', $by)
                                    ->where('tbl_trans_medcheck.id_dokter', $id_user)
                                    ->like('DATE(tbl_trans_medcheck.tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                    ->like('tbl_trans_medcheck.pasien', $cs)
                                    ->like('tbl_trans_medcheck.tipe', $tp)
//                                    ->like('tbl_trans_medcheck.id_dokter', ($id_grup->name == 'dokter' ? $id_user : ''), ($id_grup->name == 'dokter' ? 'none' : ''))
                                    ->or_like('tbl_trans_medcheck_dokter.id_dokter', $id_user, ($id_grup->name == 'dokter' ? $id_user : ''))
//                                    ->like('tbl_trans_medcheck.status_periksa', $sp)
//                                    ->like('tbl_trans_medcheck.status_bayar', $by, (isset($_GET['filter_bayar']) ? 'none' : ''))
                                    ->limit($config['per_page'])
                                    ->order_by('id', 'desc')
                                    ->get('tbl_trans_medcheck')->result();
                }                
            }else{
                // Bukan Dokter
                if (!empty($hal)) {
                    $data['penj'] = $this->db->select('id, id_app, id_dft, id_user, id_dokter, id_nurse, id_analis, id_pasien, id_poli, no_nota, no_rm, tgl_simpan, TIME(tgl_simpan) AS waktu_masuk, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, TIME(tgl_keluar) AS waktu_keluar, jml_total, jml_gtotal, ppn, jml_ppn, tipe, status, status_nota, status_bayar, tipe, status_periksa')
                                    ->where('status_hps', '0')
                                    ->like('DATE(tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                    ->like('tipe', ($id_grup->name == 'perawat_ranap' ? '3' : $tp), ($id_grup->name == 'perawat_ranap' ? 'none' : ''))
                                    ->like('id_dokter', ($id_grup->name == 'dokter' ? $id_user : ''), ($id_grup->name == 'dokter' ? 'none' : ''))
                                    ->like('id', general::dekrip($id))
                                    ->like('pasien', $cs)
//                                    ->like('tipe', $tp)
                                    ->like('status_bayar', $by)
                                    ->like('status_periksa', $sp)
                                    ->limit($config['per_page'], $hal)
                                    ->order_by('id', 'desc')
                                    ->get('tbl_trans_medcheck')->result();
                } else {
                    $data['penj'] = $this->db->select('id, id_app, id_dft, id_user, id_dokter, id_nurse, id_analis, id_pasien, id_poli, no_nota, no_rm, tgl_simpan, TIME(tgl_simpan) AS waktu_masuk, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, TIME(tgl_keluar) AS waktu_keluar, jml_total, jml_gtotal, ppn, jml_ppn, tipe, status, status_nota, status_bayar, tipe, status_periksa')
                                    ->where('status_hps', '0')
                                    ->like('DATE(tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                    ->like('tipe', ($id_grup->name == 'perawat_ranap' ? '3' : $tp), ($id_grup->name == 'perawat_ranap' ? 'none' : ''))
                                    ->like('id_dokter', ($id_grup->name == 'dokter' ? $id_user : ''), ($id_grup->name == 'dokter' ? 'none' : ''))
                                    ->like('id', general::dekrip($id))
                                    ->like('pasien', $cs)
//                                    ->like('tipe', $tp)
                                    ->like('status_bayar', $by)
                                    ->like('status_periksa', $sp)
                                    ->limit($config['per_page'])
                                    ->order_by('id', 'desc')
                                    ->get('tbl_trans_medcheck')->result();
                }
            }
            
            $this->pagination->initialize($config);

            /* Blok pagination */
            $data['total_rows'] = $config['total_rows'];
            $data['PerPage']    = $config['per_page'];
            $data['pagination'] = $this->pagination->create_links();
            $data['cetak']      = '<button type="button" onclick="window.location.href = \''.base_url('transaksi/cetak_data_penj.php?'.(!empty($nt) ? 'filter_nota='.$nt : '').(!empty($tg) ? '&filter_tgl='.$tg : '').(!empty($tp) ? '&filter_tgl_tempo='.$tp : '').(!empty($cs) ? '&filter_cust='.$cs : '').(!empty($sl) ? '&filter_sales='.$sl : '').(!empty($jml) ? '&jml='.$jml : '')).'\'" class="btn btn-warning"><i class="fa fa-print"></i> Cetak</button>';
            /* --End Blok pagination-- */
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */
               
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/'.(isset($_GET['tipe']) ? 'index' : 'content'), $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();            
        }
    }
    
    public function data_radiologi() {
        if (akses::aksesLogin() == TRUE) {
            /* -- Grup hak akses -- */
            $role        = $this->input->get('role');
            $tipe        = $this->input->get('tipe');
            $grup        = $this->ion_auth->get_users_groups()->row();
            $id_user     = $this->ion_auth->user()->row()->id;
            $id_grup     = $this->ion_auth->get_users_groups()->row();
            $id_dokter   = $this->ion_auth->get_users_groups()->row();
            $pengaturan  = $this->db->get('tbl_pengaturan')->row();

            /* -- Blok Filter -- */
            $hal     = $this->input->get('halaman');
            $id      = $this->input->get('id');
            $cs      = $this->input->get('filter_nama');
            $by      = $this->input->get('filter_bayar');
            $tp      = $this->input->get('filter_tipe');
            $tg      = $this->input->get('filter_tgl');
            $sp      = $this->input->get('filter_periksa');
            $st      = $this->input->get('status');
            $jml     = $this->input->get('jml');
            
            $jml_sql = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.id_pasien, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.pasien, tbl_trans_medcheck_rad.tgl_simpan, tbl_trans_medcheck_rad.id_radiografer, tbl_trans_medcheck_rad.id_dokter, tbl_trans_medcheck_rad.no_sample, tbl_trans_medcheck_rad.no_rad, tbl_trans_medcheck_rad.status')
                                        ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_rad.id_medcheck')
                                        ->like('tbl_trans_medcheck_rad.status', (isset($st) ? $st : ''))
                                        ->like('tbl_trans_medcheck.pasien', (!empty($cs) ? $cs : ''))
                                        ->like('DATE(tbl_trans_medcheck.tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                        ->order_by('tbl_trans_medcheck_rad.id', 'DESC')
                                        ->get('tbl_trans_medcheck_rad')->num_rows();

            if(!empty($jml)){
                $jml_hal = $jml;
            }else{
                $jml_hal = $jml_sql;
            }
            
            /* -- End Blok Filter -- */

            /* -- Form Error -- */
            $data['hasError']                = $this->session->flashdata('form_error');

            /* -- Blok Pagination -- */
            $config['base_url']              = base_url('medcheck/data_radiologi.php?status='.(isset($st) ? $st : '').(!empty($cs) ? '&filter_nama='.$cs : '').(!empty($tg) ? '&filter_tgl='.$tg : '').(!empty($tp) ? '&filter_tipe='.$tp : '').(!empty($sp) ? '&filter_periksa='.$sp : '').(!empty($jml_hal) ? '&jml='.$jml_hal : ''));
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
                   $data['penj'] = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.id_pasien, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.pasien, tbl_trans_medcheck_rad.id AS id_rad, tbl_trans_medcheck_rad.tgl_simpan, tbl_trans_medcheck_rad.id_radiografer, tbl_trans_medcheck_rad.id_dokter, tbl_trans_medcheck_rad.no_sample, tbl_trans_medcheck_rad.no_rad, tbl_trans_medcheck_rad.status')
                                        ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_rad.id_medcheck')
                                        ->like('tbl_trans_medcheck_rad.status', (isset($st) ? $st : ''))
                                        ->like('tbl_trans_medcheck.pasien', (!empty($cs) ? $cs : ''))
                                        ->like('DATE(tbl_trans_medcheck.tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                        ->limit($config['per_page'],$hal)
                                        ->order_by('tbl_trans_medcheck_rad.id', 'DESC')
                                        ->get('tbl_trans_medcheck_rad')->result();
            }else{
                   $data['penj'] = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.id_pasien, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.pasien, tbl_trans_medcheck_rad.id AS id_rad, tbl_trans_medcheck_rad.tgl_simpan, tbl_trans_medcheck_rad.id_radiografer, tbl_trans_medcheck_rad.id_dokter, tbl_trans_medcheck_rad.no_sample, tbl_trans_medcheck_rad.no_rad, tbl_trans_medcheck_rad.status')
                                        ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_rad.id_medcheck')
                                        ->like('tbl_trans_medcheck_rad.status', (isset($st) ? $st : ''))
                                        ->like('tbl_trans_medcheck.pasien', (!empty($cs) ? $cs : ''))
                                        ->like('DATE(tbl_trans_medcheck.tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                        ->limit($config['per_page'])
                                        ->order_by('tbl_trans_medcheck_rad.id', 'DESC')
                                        ->get('tbl_trans_medcheck_rad')->result();
            }
            
            $this->pagination->initialize($config);

            /* Blok pagination */
            $data['total_rows'] = $config['total_rows'];
            $data['PerPage']    = $config['per_page'];
            $data['pagination'] = $this->pagination->create_links();
            $data['cetak']      = '<button type="button" onclick="window.location.href = \''.base_url('transaksi/cetak_data_penj.php?'.(!empty($nt) ? 'filter_nota='.$nt : '').(!empty($tg) ? '&filter_tgl='.$tg : '').(!empty($tp) ? '&filter_tgl_tempo='.$tp : '').(!empty($cs) ? '&filter_cust='.$cs : '').(!empty($sl) ? '&filter_sales='.$sl : '').(!empty($jml) ? '&jml='.$jml : '')).'\'" class="btn btn-warning"><i class="fa fa-print"></i> Cetak</button>';
            /* --End Blok pagination-- */
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/data_radiologi', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();            
        }
    }
    
    public function data_laborat() {
        if (akses::aksesLogin() == TRUE) {
            /* -- Grup hak akses -- */
            $role        = $this->input->get('role');
            $tipe        = $this->input->get('tipe');
            $grup        = $this->ion_auth->get_users_groups()->row();
            $id_user     = $this->ion_auth->user()->row()->id;
            $id_grup     = $this->ion_auth->get_users_groups()->row();
            $id_dokter   = $this->ion_auth->get_users_groups()->row();
            $pengaturan  = $this->db->get('tbl_pengaturan')->row();

            /* -- Blok Filter -- */
            $hal     = $this->input->get('halaman');
            $id      = $this->input->get('id');
            $cs      = $this->input->get('filter_nama');
            $by      = $this->input->get('filter_bayar');
            $tp      = $this->input->get('filter_tipe');
            $tg      = $this->input->get('filter_tgl');
            $sp      = $this->input->get('filter_periksa');
            $st      = $this->input->get('status');
            $jml     = $this->input->get('jml');
            
            $jml_sql = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.id_pasien, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.pasien, tbl_trans_medcheck_lab.tgl_simpan, tbl_trans_medcheck_lab.id_analis, tbl_trans_medcheck_lab.id_dokter, tbl_trans_medcheck_lab.no_sample, tbl_trans_medcheck_lab.no_lab, tbl_trans_medcheck_lab.status')
                                        ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_lab.id_medcheck')
                                        ->like('tbl_trans_medcheck_lab.status', (isset($st) ? $st : ''))
                                        ->like('tbl_trans_medcheck.pasien', (!empty($cs) ? $cs : ''))
                                        ->like('DATE(tbl_trans_medcheck.tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                        ->order_by('tbl_trans_medcheck_lab.id', 'DESC')
                                        ->get('tbl_trans_medcheck_lab')->num_rows();

            if(!empty($jml)){
                $jml_hal = $jml;
            }else{
                $jml_hal = $jml_sql;
            }
            
            /* -- End Blok Filter -- */

            /* -- Form Error -- */
            $data['hasError']                = $this->session->flashdata('form_error');

            /* -- Blok Pagination -- */
            $config['base_url']              = base_url('medcheck/data_laborat.php?status='.(isset($st) ? $st : '').(!empty($cs) ? '&filter_nama='.$cs : '').(!empty($tg) ? '&filter_tgl='.$tg : '').(!empty($tp) ? '&filter_tipe='.$tp : '').(!empty($sp) ? '&filter_periksa='.$sp : '').(!empty($jml_hal) ? '&jml='.$jml_hal : ''));
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
                   $data['penj'] = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.id_pasien, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.pasien, tbl_trans_medcheck_lab.id AS id_rad, tbl_trans_medcheck_lab.tgl_simpan, tbl_trans_medcheck_lab.id_analis, tbl_trans_medcheck_lab.id_dokter, tbl_trans_medcheck_lab.no_sample, tbl_trans_medcheck_lab.no_lab, tbl_trans_medcheck_lab.status')
                                        ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_lab.id_medcheck')
                                        ->like('tbl_trans_medcheck_lab.status', (isset($st) ? $st : ''))
                                        ->like('tbl_trans_medcheck.pasien', (!empty($cs) ? $cs : ''))
                                        ->like('DATE(tbl_trans_medcheck.tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                        ->limit($config['per_page'],$hal)
                                        ->order_by('tbl_trans_medcheck_lab.id', 'DESC')
                                        ->get('tbl_trans_medcheck_lab')->result();
            }else{
                   $data['penj'] = $this->db->select('tbl_trans_medcheck.id, tbl_trans_medcheck.id_pasien, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.pasien, tbl_trans_medcheck_lab.id AS id_rad, tbl_trans_medcheck_lab.tgl_simpan, tbl_trans_medcheck_lab.id_analis, tbl_trans_medcheck_lab.id_dokter, tbl_trans_medcheck_lab.no_sample, tbl_trans_medcheck_lab.no_lab, tbl_trans_medcheck_lab.status')
                                        ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_lab.id_medcheck')
                                        ->like('tbl_trans_medcheck_lab.status', (isset($st) ? $st : ''))
                                        ->like('tbl_trans_medcheck.pasien', (!empty($cs) ? $cs : ''))
                                        ->like('DATE(tbl_trans_medcheck.tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                        ->limit($config['per_page'])
                                        ->order_by('tbl_trans_medcheck_lab.id', 'DESC')
                                        ->get('tbl_trans_medcheck_lab')->result();
            }
            
            $this->pagination->initialize($config);

            /* Blok pagination */
            $data['total_rows'] = $config['total_rows'];
            $data['PerPage']    = $config['per_page'];
            $data['pagination'] = $this->pagination->create_links();
            $data['cetak']      = '<button type="button" onclick="window.location.href = \''.base_url('transaksi/cetak_data_penj.php?'.(!empty($nt) ? 'filter_nota='.$nt : '').(!empty($tg) ? '&filter_tgl='.$tg : '').(!empty($tp) ? '&filter_tgl_tempo='.$tp : '').(!empty($cs) ? '&filter_cust='.$cs : '').(!empty($sl) ? '&filter_sales='.$sl : '').(!empty($jml) ? '&jml='.$jml : '')).'\'" class="btn btn-warning"><i class="fa fa-print"></i> Cetak</button>';
            /* --End Blok pagination-- */
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/data_laborat', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();            
        }
    }
    
    
    public function trans_medcheck_dft() {
        if (akses::aksesLogin() == TRUE) {
            /* -- Grup hak akses -- */
            $role        = $this->input->get('role');
            $tipe        = $this->input->get('tipe');
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
            $cs      = $this->input->get('filter_nama');
            $sn      = $this->input->get('filter_status');
            $sl      = $this->input->get('filter_sales');
            $stts    = $this->input->get('status');
            $jml     = $this->input->get('jml');
//            $jml_sql = ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'admin' ? $this->db->get('tbl_trans_jual')->num_rows() : $this->db->where('id_user', $id_user)->where('tgl_masuk', date('Y-m-d'))->get('tbl_trans_jual')->num_rows());

            if(!empty($jml)){
                $jml_hal = $jml;
            }else{
                $jml_hal = $this->db->select('*')
                                ->where('status_akt !=', '2')
                                ->where('DATE(tgl_masuk)', (!empty($tg) ? $tg : date('Y-m-d')))
                                ->like('nama', $cs)
                                ->get('tbl_pendaftaran')->num_rows();
            }
            /* -- End Blok Filter -- */

            /* -- Form Error -- */
            $data['hasError']                = $this->session->flashdata('form_error');

            /* -- Blok Pagination -- */
            $config['base_url']              = base_url('medcheck/data_pendaftaran.php?'.(!empty($filter_nama) ? '&filter_nama='.$filter_nama : '').'&jml='.$jml);
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
                   $data['penj'] = $this->db->select('*')
                           ->where('status_akt !=', '2')
                           ->where('DATE(tgl_masuk)', (!empty($tg) ? $tg : date('Y-m-d')))
                           ->like('nama', $cs)
                           ->limit($config['per_page'],$hal)
                           ->order_by('status_akt','asc')
                           ->get('tbl_pendaftaran')->result();
            }else{
                   $data['penj'] = $this->db->select('*')
                           ->where('status_akt !=', '2')
                           ->where('DATE(tgl_masuk)', (!empty($tg) ? $tg : date('Y-m-d')))
                           ->like('nama', $cs)
                           ->limit($config['per_page'])
                           ->order_by('status_akt','asc')
                           ->get('tbl_pendaftaran')->result();
            }

            $this->pagination->initialize($config);

            /* Blok pagination */
            $data['total_rows'] = $config['total_rows'];
            $data['PerPage']    = $config['per_page'];
            $data['pagination'] = $this->pagination->create_links();
            $data['cetak']      = '<button type="button" onclick="window.location.href = \''.base_url('transaksi/cetak_data_penj.php?'.(!empty($nt) ? 'filter_nota='.$nt : '').(!empty($tg) ? '&filter_tgl='.$tg : '').(!empty($tp) ? '&filter_tgl_tempo='.$tp : '').(!empty($cs) ? '&filter_cust='.$cs : '').(!empty($sl) ? '&filter_sales='.$sl : '').(!empty($jml) ? '&jml='.$jml : '')).'\'" class="btn btn-warning"><i class="fa fa-print"></i> Cetak</button>';
            /* --End Blok pagination-- */
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/data_pendf', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();            
        }
    }
    
    public function trans_medcheck_dft_tambah() {
        if (akses::aksesLogin() == TRUE) {
            $setting                = $this->db->get('tbl_pengaturan')->row();
            $id_medc                = $this->input->get('id_medc');
            $id                     = $this->input->get('id');
            $id_resep               = $this->input->get('id_resep');
            $id_pasien              = $this->input->get('id_pasien');
            $id_item                = $this->input->get('id_item');
            $id_item_res            = $this->input->get('id_item_resep');
            $dft_id                 = $this->input->get('dft');
            $userid                 = $this->ion_auth->user()->row()->id;

            $data['poli']           = $this->db->where('status_ant', '1')->get('tbl_m_poli')->result();
            $data['platform']       = $this->db->get('tbl_m_platform')->result();
            $data['gelar']          = $this->db->get('tbl_m_gelar')->result();
            $data['sql_doc']        = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            $data['kerja']          = $this->db->get('tbl_m_jenis_kerja')->result();
            $data['sql_poli']       = $this->db->where('id', $data['sql_dft_id']->id_poli)->get('tbl_m_poli')->row();
            $data['sql_dft_id']     = $this->db->where('id', general::dekrip($dft_id))->get('tbl_pendaftaran')->row();            
            $data['sql_itm_pas']    = $this->db->select('id, nama, nama_pgl')->get('tbl_m_pasien')->result();
            $data['sql_pas']        = $this->db->where('id', general::dekrip($id_pasien))->get('tbl_m_pasien')->row();
            $data['sql_penjamin']   = $this->db->where('status', '1')->get('tbl_m_penjamin')->result();
            
            if(!empty($id_pasien)){
               $data['pasien']         = $this->db->where('id', general::dekrip($id_pasien))->get('tbl_m_pasien')->row(); 
            }
            
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/med_daftar', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();            
        }
    }
    
    public function trans_medcheck_dft_konfirm() {
        if (akses::aksesLogin() == TRUE) {
            $setting                = $this->db->get('tbl_pengaturan')->row();
            $id_medc                = $this->input->get('id_medc');
            $id                     = $this->input->get('dft');
            $id_resep               = $this->input->get('id_resep');
            $id_item                = $this->input->get('id_item');
            $id_item_res            = $this->input->get('id_item_resep');
            $userid                 = $this->ion_auth->user()->row()->id;

            $data['poli']       = $this->db->where('status_ant', '1')->get('tbl_m_poli')->result();
            $data['gelar']      = $this->db->get('tbl_m_gelar')->result();
            $data['sql_doc']    = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            $data['kerja']      = $this->db->get('tbl_m_jenis_kerja')->result();
            $data['sql_dft_id'] = $this->db->where('id', general::dekrip($id))->get('tbl_pendaftaran')->row();
            $data['sql_poli']   = $this->db->where('id', $data['sql_dft_id']->id_poli)->get('tbl_m_poli')->row();
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/med_daftar_konfirm', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();            
        }
    }
    
    public function trans_medcheck_dft_konfirm_simpan() {
        if (akses::aksesLogin() == TRUE) {
            $id           = $this->input->post('id');
            $antrian      = $this->input->post('no_antrian');
            $hadir        = $this->input->post('status_hdr');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'NIK', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'     => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);
                redirect(base_url('medcheck/data_pendaftaran.php'));
            } else {
                $tmsk    = $this->tanggalan->tgl_indo_sys($tgl_masuk);
                
                $data_pas = array(
                    'tgl_modif'    => date('Y-m-d H:i:s'),
                    'no_urut'      => (int)$antrian,
                    'status_akt'   => '1',
                    'status_hdr'   => $hadir
                );
                
                $this->session->set_flashdata('master', '<div class="alert alert-success">Data member berhasil diubah</div>');
                crud::update('tbl_pendaftaran', 'id', general::dekrip($id), $data_pas);
                $last_id = general::dekrip($id);                
                
                redirect(base_url('medcheck/data_pendaftaran.php'));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();            
        }
    }
    
    public function trans_medcheck_dft_simpan() {
        if (akses::aksesLogin() == TRUE) {
            $id_pasien    = $this->input->post('id_pasien');
            $no_rm        = $this->input->post('no_rm');
            $nik_lama     = $this->input->post('nik_lama');
            $nik_baru     = $this->input->post('nik_baru');
            $gelar        = $this->input->post('gelar');
            $nama         = $this->input->post('nama');
            $no_hp        = $this->input->post('no_hp');
            $no_rmh       = $this->input->post('no_rmh');
            $tmp_lahir    = $this->input->post('tmp_lahir');
            $tgl_lahir    = $this->input->post('tgl_lahir');
            $alamat       = $this->input->post('alamat');
            $alamat_dom   = $this->input->post('alamat_dom');
            $jns_klm      = $this->input->post('jns_klm');
            $pekerjaan    = $this->input->post('pekerjaan');
            $tipe_pas     = $this->input->post('tipe_pas');
            $file         = $this->input->post('file');
            $file_id      = $this->input->post('file_id');
            $alergi       = $this->input->post('alergi');
            $inst         = $this->input->post('instansi');
            $inst_alamat  = $this->input->post('instansi_almt');
            
            $tgl_masuk    = $this->input->post('tgl_masuk');
            $plat         = $this->input->post('platform');
            $poli         = $this->input->post('poli');
            $dokter       = $this->input->post('dokter');
            
            $pengaturan   = $this->db->get('tbl_pengaturan')->row();
            
            // Pilih tipe pasien
            switch ($tipe_pas){ 
                
                default:
                    // value data pasien                        
                    $this->session->set_flashdata('tipe_pas', $tipe_pas);
                    $this->session->set_flashdata('nik_baru', $nik_baru);
                    $this->session->set_flashdata('nama', $nama);
                    $this->session->set_flashdata('tmp_lahir', $tmp_lahir);
                    $this->session->set_flashdata('tgl_lahir', $tgl_lahir);
                    $this->session->set_flashdata('jns_klm', $jns_klm);
                    $this->session->set_flashdata('no_hp', $no_hp);
                    $this->session->set_flashdata('no_rmh', $no_rmh);
                    $this->session->set_flashdata('alamat', $alamat);
                    $this->session->set_flashdata('alamat_dom', $alamat_dom);
                    $this->session->set_flashdata('pekerjaan', $pekerjaan);
                    $this->session->set_flashdata('poli', $poli);
                    $this->session->set_flashdata('plat', $plat);
                    $this->session->set_flashdata('dokter', $dokter);
                    $this->session->set_flashdata('alergi', $alergi);

                    $this->session->set_flashdata('master', '<div class="alert alert-danger">Silahkan pilih tipe pilihan pasien dahulu</div>');
                    redirect(base_url('medcheck/daftar.php'));
                    break;
                
                // Pasien Lama
                case '1':
                    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        
                    $this->form_validation->set_rules('gelar', 'Gelar', 'required');
                    $this->form_validation->set_rules('nama', 'Nama Pasien', 'required');
                    $this->form_validation->set_rules('jns_klm', 'Jenis Kelamin', 'required');
        
                    if ($this->form_validation->run() == FALSE) {
                        $msg_error = array(
                            'gelar'   => form_error('gelar'),
                            'nama'    => form_error('nama'),
                            'jns_klm' => form_error('jns_klm'),
                        );

                        // value data pasien                        
                        $this->session->set_flashdata('tipe_pas', $tipe_pas);
                        $this->session->set_flashdata('nik_baru', $nik_baru);
                        $this->session->set_flashdata('nama', $nama);
                        $this->session->set_flashdata('tmp_lahir', $tmp_lahir);
                        $this->session->set_flashdata('tgl_lahir', $tgl_lahir);
                        $this->session->set_flashdata('jns_klm', $jns_klm);
                        $this->session->set_flashdata('no_hp', $no_hp);
                        $this->session->set_flashdata('no_rmh', $no_rmh);
                        $this->session->set_flashdata('alamat', $alamat);
                        $this->session->set_flashdata('alamat_dom', $alamat_dom);
                        $this->session->set_flashdata('pekerjaan', $pekerjaan);
                        $this->session->set_flashdata('poli', $poli);
                        $this->session->set_flashdata('dokter', $dokter);
                        $this->session->set_flashdata('alergi', $alergi);
                        
                        $this->session->set_flashdata('form_error', $msg_error);
                        redirect(base_url('medcheck/daftar.php'));
                    } else {
                        $tmsk    = $this->tanggalan->tgl_indo_sys($tgl_masuk);
                        $sql_cek1= $this->db->where('id', general::dekrip($id_pasien))->get('tbl_m_pasien');
                        $sql_cek2= $this->db->like('nik', $nik)->get('tbl_m_pasien');
                        
                        if($sql_cek1->num_rows() > 0){
                            $sql_num    = $this->db->where('DATE(tgl_masuk)', $tmsk)->where('id_poli', $poli)->get('tbl_pendaftaran');
                            $pasien     = $sql_cek1->row();
                            $sql_glr    = $this->db->where('id', $pasien->id_gelar)->get('tbl_m_gelar')->row();
                            $no_urut    = $sql_num->num_rows() + 1;
                            
                        }elseif($sql_cek2->num_rows() > 0){
                            $sql_num    = $this->db->where('DATE(tgl_masuk)', $tmsk)->where('id_poli', $poli)->get('tbl_pendaftaran');
                            $pasien     = $sql_cek2->row();
                            $sql_glr    = $this->db->where('id', $pasien->id_gelar)->get('tbl_m_gelar')->row();
                            $no_urut    = $sql_num->num_rows() + 1;
                            
                        }else{
                            $this->session->set_flashdata('master', '<div class="alert alert-danger">NIK atau No. RM Pasien tidak ditemukan di database !!</div>');
                        }
                        
                        $data_pas = array(
                            'tgl_simpan'   => date('Y-m-d H:i:s'),
                            'tgl_masuk'    => $tmsk.' '.date('H:i'),
                            'id_pasien'    => general::dekrip($id_pasien),
                            'id_gelar'     => (!empty($gelar) ? $gelar : '0'),
                            'id_poli'      => (!empty($poli) ? $poli : '0'),
                            'id_dokter'    => (!empty($dokter) ? $dokter : '0'),
                            'id_pekerjaan' => (!empty($pekerjaan) ? $pekerjaan : '0'),
                            'no_urut'      => $no_urut,
                            'nik'          => $nik_lama,
                            'nama'         => $nama,
                            'nama_pgl'     => strtoupper($sql_glr->gelar.' '.$nama),
                            'tmp_lahir'    => $tmp_lahir,
                            'tgl_lahir'    => $this->tanggalan->tgl_indo_sys($tgl_lahir),
                            'jns_klm'      => (!empty($jns_klm) ? $jns_klm : 0),
                            'kontak'       => $no_hp,
                            'kontak_rmh'   => $no_rmh,
                            'alamat'       => $alamat,
                            'alamat_dom'   => $alamat_dom,
                            'instansi'          => $inst,
                            'instansi_alamat'   => $inst_alamat,
                            'file_base64'  => $file,
                            'file_base64_id'=> $file_id,
                            'alergi'       => $alergi,
                            'tipe_bayar'   => (!empty($plat) ? $plat : '0'),
                            'status'       => $tipe_pas,
                            'status_akt'   => '0',
                            'status_hdr'   => '0',
                            'status_dft'   => '1',
                        );
                        
                        $data_pas2 = array(
                            'tgl_modif'    => date('Y-m-d H:i:s'),
                            'id_gelar'     => (!empty($gelar) ? $gelar : 0),
                            'id_pekerjaan' => (!empty($pekerjaan) ? $pekerjaan : 0),
                            'nik'          => $nik_lama,
                            'nama'         => $nama,
                            'nama_pgl'     => strtoupper($sql_glr->gelar.' '.$nama),
                            'tmp_lahir'    => $tmp_lahir,
                            'tgl_lahir'    => $this->tanggalan->tgl_indo_sys($tgl_lahir),
                            'jns_klm'      => (!empty($jns_klm) ? $jns_klm : 0),
                            'no_hp'        => $no_hp,
                            'no_telp'      => $no_rmh,
                            'alamat'       => $alamat,
                            'alamat_dom'   => $alamat_dom,
//                            'file_base64'  => $file,
                            'status'       => '1'
                        );

                        $this->session->set_flashdata('master', '<div class="alert alert-success">Data member berhasil diubah</div>');

                        crud::simpan('tbl_pendaftaran', $data_pas);
                        $last_id = crud::last_id();                

                        redirect(base_url('medcheck/data_pendaftaran.php?id='.general::enkrip($last_id)));
                    }
                    break;
                
                // Pasien Baru
                case '2':
                    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        
                    $this->form_validation->set_rules('gelar', 'Gelar', 'required');
                    $this->form_validation->set_rules('nama', 'Nama Pasien', 'required');
                    $this->form_validation->set_rules('jns_klm', 'Jenis Kelamin', 'required');
                    $this->form_validation->set_rules('alamat', 'Alamat', 'required');
//                    $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');
        
                    if ($this->form_validation->run() == FALSE) {
                        $msg_error = array(
                            'gelar'   => form_error('gelar'),
                            'nama'    => form_error('nama'),
                            'jns_klm' => form_error('jns_klm'),
                            'alamat'  => form_error('alamat'),
                        );
                        
                        // value data pasien
                        $this->session->set_flashdata('nik_baru', $nik_baru);
                        $this->session->set_flashdata('nama', $nama);
                        $this->session->set_flashdata('tmp_lahir', $tmp_lahir);
                        $this->session->set_flashdata('tgl_lahir', $tgl_lahir);
                        $this->session->set_flashdata('jns_klm', $jns_klm);
                        $this->session->set_flashdata('no_hp', $no_hp);
                        $this->session->set_flashdata('no_rmh', $no_rmh);
                        $this->session->set_flashdata('alamat', $alamat);
                        $this->session->set_flashdata('alamat_dom', $alamat_dom);
                        $this->session->set_flashdata('pekerjaan', $pekerjaan);
                        $this->session->set_flashdata('poli', $poli);
                        $this->session->set_flashdata('dokter', $dokter);
                        $this->session->set_flashdata('alergi', $alergi);
                        
                        $this->session->set_flashdata('form_error', $msg_error);
                        redirect(base_url('medcheck/daftar.php?tipe_pas='.$tipe_pas));
                    } else {
                        $tmsk    = $this->tanggalan->tgl_indo_sys($tgl_masuk);
                        $sql_num = $this->db->where('DATE(tgl_masuk)', $tmsk)->where('id_poli', $poli)->get('tbl_pendaftaran');
                        $sql_glr = $this->db->where('id', $gelar)->get('tbl_m_gelar')->row();
                        $kode    = sprintf('%05d', $sql_num);
                        $sql_kat = $this->db->get('tbl_m_kategori');
                        
                        # Check point
                        $this->session->set_userdata('form_timestamp', $this->input->post('timestamp'));
                        
                        $sql_cek = $this->db->where('kode', $no_rm)->get('tbl_pendaftaran');
                        
                        $no_urut = $sql_num->num_rows() + 1;
                        
                        $data_pas = array(
                            'tgl_simpan'   => date('Y-m-d H:i:s'),
                            'tgl_masuk'    => $tmsk,
                            'id_gelar'     => (!empty($gelar) ? $gelar : 0),
                            'id_poli'      => (!empty($poli) ? $poli : 0),
                            'id_dokter'    => (!empty($dokter) ? $dokter : 0),
                            'id_pekerjaan' => (!empty($pekerjaan) ? $pekerjaan : 0),
                            'tipe_bayar'   => (!empty($plat) ? $plat : '0'),
                            'no_urut'      => $no_urut,
                            'nik'          => $nik_baru,
                            'nama'         => $nama,
                            'nama_pgl'     => strtoupper($sql_glr->gelar.' '.$nama),
                            'tmp_lahir'    => $tmp_lahir,
                            'tgl_lahir'    => (!empty($tgl_lahir) ? $this->tanggalan->tgl_indo_sys($tgl_lahir) : '0000-00-00'),
                            'jns_klm'      => $jns_klm,
                            'kontak'       => $no_hp,
                            'kontak_rmh'   => $no_rmh,
                            'alamat'       => (!empty($alamat) ? $alamat : ''),
                            'alamat_dom'   => (!empty($alamat_dom) ? $alamat_dom : ''),
                            'instansi'          => $inst,
                            'instansi_alamat'   => $inst_alamat,
                            'file_base64'  => $file,
                            'file_base64_id'=> $file_id,
                            'alergi'       => $alergi,
                            'tipe_bayar'   => (!empty($plat) ? $plat : '0'),
                            'status'       => $tipe_pas,
                            'status_akt'   => '0'
                        );
                        
                        $data_pas2 = array(
                            'tgl_modif'    => date('Y-m-d H:i:s'),
                            'id_gelar'     => $gelar,
                            'id_pekerjaan' => $pekerjaan,
                            'nik'          => $nik_lama,
                            'nama'         => $nama,
                            'nama_pgl'     => strtoupper($sql_glr->gelar.' '.$nama),
                            'tmp_lahir'    => $tmp_lahir,
                            'tgl_lahir'    => $this->tanggalan->tgl_indo_sys($tgl_lahir),
                            'jns_klm'      => $jns_klm,
                            'no_hp'        => $no_hp,
                            'no_telp'      => $no_rmh,
                            'alamat'       => $alamat,
                            'alamat_dom'   => $alamat_dom,
                            'file_base64'  => $file,
                            'status'       => '1'
                        );
                        
                        # Transact SQL
                        $this->db->trans_off();
                        $this->db->trans_start();
                        
                        # Simpan ke tabel pendaftaran
                        $this->db->insert('tbl_pendaftaran', $data_pas);
                        $last_id = $this->db->insert_id();
                        
                        # Transact SQL End
                        $this->db->trans_complete();
                        
//                        if ($this->db->trans_status() === FALSE) {
//                            # Rollback jika gagal
//                            $this->db->trans_rollback();
//
//                            # Tampilkan pesan error
//                            $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Pendaftaran pasien gagal !!</div>');
//                        }else{
//                            $this->db->trans_commit();
//                            
//                            # Tampilkan pesan sukses
//                            $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Pendaftaran pasien berhasil !!</div>'); 
//                        }           

                        redirect(base_url('medcheck/data_pendaftaran.php?id='.general::enkrip($last_id)));
                    }
                    break;
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();            
        }
    }
    
    public function trans_medcheck_dft_update() {
        if (akses::aksesLogin() == TRUE) {
            $dft          = $this->input->post('id_dft');
            $id_pasien    = $this->input->post('id_pasien');
            $no_rm        = $this->input->post('no_rm');
            $nik_lama     = $this->input->post('nik_lama');
            $nik_baru     = $this->input->post('nik_baru');
            $gelar        = $this->input->post('gelar');
            $nama         = $this->input->post('nama');
            $no_hp        = $this->input->post('no_hp');
            $no_rmh       = $this->input->post('no_rmh');
            $tmp_lahir    = $this->input->post('tmp_lahir');
            $tgl_lahir    = $this->input->post('tgl_lahir');
            $alamat       = $this->input->post('alamat');
            $alamat_dom   = $this->input->post('alamat_dom');
            $jns_klm      = $this->input->post('jns_klm');
            $pekerjaan    = $this->input->post('pekerjaan');
            $tipe_pas     = $this->input->post('tipe_pas');
            $file         = $this->input->post('file');
            $file_id      = $this->input->post('file_id');
            $alergi       = $this->input->post('alergi');
            $inst         = $this->input->post('instansi');
            $inst_alamat  = $this->input->post('instansi_almt');
            
            $tgl_masuk    = $this->input->post('tgl_masuk');
            $plat         = $this->input->post('platform');
            $poli         = $this->input->post('poli');
            $dokter       = $this->input->post('dokter');
            
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('gelar', 'Gelar', 'required');
            $this->form_validation->set_rules('nama', 'Nama Pasien', 'required');
            $this->form_validation->set_rules('jns_klm', 'Jenis Kelamin', 'required');
//            $this->form_validation->set_rules('alamat', 'Alamat', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'gelar'   => form_error('gelar'),
                    'nama'    => form_error('nama'),
                    'jns_klm' => form_error('jns_klm'),
//                    'alamat'  => form_error('alamat'),
                );
                
                // value data pasien
                $this->session->set_flashdata('nik_baru', $nik_baru);
                $this->session->set_flashdata('nama', $nama);
                $this->session->set_flashdata('tmp_lahir', $tmp_lahir);
                $this->session->set_flashdata('tgl_lahir', $tgl_lahir);
                $this->session->set_flashdata('jns_klm', $jns_klm);
                $this->session->set_flashdata('no_hp', $no_hp);
                $this->session->set_flashdata('no_rmh', $no_rmh);
                $this->session->set_flashdata('alamat', $alamat);
                $this->session->set_flashdata('alamat_dom', $alamat_dom);
                $this->session->set_flashdata('pekerjaan', $pekerjaan);
                $this->session->set_flashdata('poli', $poli);
                $this->session->set_flashdata('dokter', $dokter);
                $this->session->set_flashdata('alergi', $alergi);
                
                $this->session->set_flashdata('form_error', $msg_error);
                redirect(base_url('medcheck/daftar.php?tipe_pas='.$tipe_pas));
            } else {
                $tmsk    = $this->tanggalan->tgl_indo_sys($tgl_masuk);
                $sql_num = $this->db->where('DATE(tgl_masuk)', $tmsk)->where('id_poli', $poli)->get('tbl_pendaftaran');
                $sql_glr = $this->db->where('id', $gelar)->get('tbl_m_gelar')->row();
                $kode    = sprintf('%05d', $sql_num);
                $sql_kat = $this->db->get('tbl_m_kategori');
                
                # Check point
                $this->session->set_userdata('form_timestamp', $this->input->post('timestamp'));
                
                $sql_cek = $this->db->where('id', general::dekrip($dft))->get('tbl_pendaftaran');
                
                $no_urut = $sql_num->num_rows() + 1;
                
                $data_pas = array(
                    'tgl_simpan'   => date('Y-m-d H:i:s'),
                    'tgl_masuk'    => date('Y-m-d'),
                    'id_gelar'     => (!empty($gelar) ? $gelar : 0),
                    'id_poli'      => (!empty($poli) ? $poli : 0),
                    'id_dokter'    => (!empty($dokter) ? $dokter : 0),
                    'id_pekerjaan' => (!empty($pekerjaan) ? $pekerjaan : 0),
                    'nik'          => $nik_baru,
                    'nama'         => $nama,
                    'nama_pgl'     => strtoupper($sql_glr->gelar.' '.$nama),
                    'tmp_lahir'    => $tmp_lahir,
                    'tgl_lahir'    => (!empty($tgl_lahir) ? $this->tanggalan->tgl_indo_sys($tgl_lahir) : '0000-00-00'),
                    'jns_klm'      => $jns_klm,
                    'kontak'       => $no_hp,
                    'kontak_rmh'   => $no_rmh,
                    'alamat'       => (!empty($alamat) ? $alamat : ''),
                    'alamat_dom'   => (!empty($alamat_dom) ? $alamat_dom : ''),
                    'instansi'          => $inst,
                    'instansi_alamat'   => $inst_alamat,
                    'file_base64'  => $file,
                    'file_base64_id'=> $file_id,
                    'alergi'       => $alergi,
                    'tipe_bayar'   => (!empty($plat) ? $plat : '0'),
                    'status_akt'   => '0'
                );
                
//                echo general::dekrip($dft);
//                echo '<pre>';
//                print_r($data_pas);
//                echo '</pre>';

                # Transact SQL
                $this->db->trans_off();
                $this->db->trans_start();
                
                # Simpan ke tabel pendaftaran
                $this->db->where('id', general::dekrip($dft))->update('tbl_pendaftaran', $data_pas);
                $last_id = general::dekrip($dft);
                
                # Transact SQL End
                $this->db->trans_complete();
                
//                if ($this->db->trans_status() === FALSE) {
//                    # Rollback jika gagal
//                    $this->db->trans_rollback();
////
//                    # Tampilkan pesan error
//                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Pendaftaran pasien gagal !!</div>');
//                }else{
//                    $this->db->trans_commit();
//                    
//                    # Tampilkan pesan sukses
//                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Pendaftaran pasien berhasil !!</div>'); 
//                }           
//
                redirect(base_url('medcheck/data_pendaftaran.php?id='.general::enkrip($last_id)));
            }
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();            
        }
    }
    
    public function trans_medcheck() {
        if (akses::aksesLogin() == TRUE) {
            $setting            = $this->db->get('tbl_pengaturan')->row();
            $id                 = $this->input->get('id');
            $id_produk          = $this->input->get('id_produk');
            $id_resep           = $this->input->get('id_resep');
            $id_lab             = $this->input->get('id_lab');
            $id_rad             = $this->input->get('id_rad');
            $id_rsm             = $this->input->get('id_resm');
            $id_item            = $this->input->get('id_item');
            $id_item_res        = $this->input->get('id_item_resep');
            $id_form            = $this->input->get('id_form');
            $status             = $this->input->get('status');
            $dft_pas            = $this->input->get('dft_pas');
            $dft_id             = $this->input->get('dft_id');
            $userid             = $this->ion_auth->user()->row()->id;
            
            $data['sess_jual']    = $this->session->userdata('trans_medcheck');
            $data['kategori']     = $this->db->get('tbl_m_kategori')->result();
            $data['poli']         = $this->db->where('status','1')->get('tbl_m_poli')->result();
            $data['sql_doc']      = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();              
            $data['sql_dft_pas']  = $this->db->where('id', general::dekrip($dft_pas))->get('tbl_m_pasien')->row();
            $data['sql_dft_id']   = $this->db->where('id', general::dekrip($dft_id))->get('tbl_pendaftaran')->row();

            if(!empty($data['sess_jual']) OR !empty($id)){
                $data['sql_produk']         = $this->db->where('id', general::dekrip($id_produk))->get('tbl_m_produk')->row();
                $data['sql_produk_ip']      = $this->db->where('id_produk', general::dekrip($id_produk))->get('tbl_m_produk_ref_input')->result();
                $data['sql_sat_pake']       = $this->db->get('tbl_m_satuan_pakai')->result();
                $data['sql_kat_lab']        = $this->db->where('status_lab', '1')->get('tbl_m_kategori')->result();
                $data['sql_medc']           = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $data['sql_medc_det']       = $this->db->where('id_medcheck', general::dekrip($id))->where('status', $status)->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();
                $data['sql_medc_det_rw']    = $this->db->where('id', general::dekrip($id_item))->get('tbl_trans_medcheck_det')->row();
                $data['sql_medc_res']       = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_resep');
                $data['sql_medc_res_rw']    = $this->db->where('id', general::dekrip($id_resep))->get('tbl_trans_medcheck_resep')->row();
                $data['sql_medc_res_m']     = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_resep')->result();
                $data['sql_medc_res_dt']    = $this->db->where('id_medcheck', $data['sql_medc']->id)->where('id_resep', general::dekrip($id_resep))->get('tbl_trans_medcheck_resep_det')->result();
                $data['sql_medc_res_dt_rw'] = $this->db->where('id', general::dekrip($id_item_res))->get('tbl_trans_medcheck_resep_det')->row();
                $data['sql_medc_lab']       = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_lab');
                $data['sql_medc_lab_rw']    = $this->db->where('id', general::dekrip($id_lab))->get('tbl_trans_medcheck_lab')->row();
                $data['sql_medc_lab_m']     = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_lab')->result();
                $data['sql_medc_lab_dt']    = $this->db->where('id_medcheck', general::dekrip($id))->where('id_lab', general::dekrip($id_lab))->where('status', '3')->group_by('id_lab_kat')->get('tbl_trans_medcheck_det')->result();
                $data['sql_medc_rm']        = $this->db->where('id_medcheck', general::dekrip($id))->order_by('id', 'desc')->get('tbl_trans_medcheck_rm')->result();
                $data['sql_medc_rm_rw']     = $this->db->where('id', general::dekrip($id_item))->get('tbl_trans_medcheck_rm')->row();
                $data['sql_medc_rad']       = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_rad');
                $data['sql_medc_rad_rw']    = $this->db->where('id_medcheck', general::dekrip($id))->where('id', general::dekrip($id_rad))->get('tbl_trans_medcheck_rad')->row();
                $data['sql_medc_rad_dt']    = $this->db->where('id_medcheck', general::dekrip($id))->where('id_rad', general::dekrip($id_rad))->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();
                $data['sql_medc_rsm']       = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_resume');
                $data['sql_medc_rsm_rw']    = $this->db->where('id', general::dekrip($id_rsm))->get('tbl_trans_medcheck_resume')->row();
                $data['sql_medc_rsm_dt']    = $this->db->where('id_medcheck', general::dekrip($id))->where('id_resume', general::dekrip($id_rsm))->get('tbl_trans_medcheck_resume_det')->result();
                $data['sql_medc_rsm_dt2']   = $this->db->where('id_medcheck', general::dekrip($id))->where('id_resume', general::dekrip($id_rsm))->where('status_rnp', '1')->get('tbl_trans_medcheck_resume_det')->result();
                $data['sql_medc_rsm_dt3']   = $this->db->where('id_medcheck', general::dekrip($id))->where('id_resume', general::dekrip($id_rsm))->where('status_trp', '1')->get('tbl_trans_medcheck_resume_det')->result();
                $data['sql_medc_rsm_dt4']   = $this->db->where('id', general::dekrip($id_item))->get('tbl_trans_medcheck_resume_det')->row();
                $data['sql_medc_mcu']       = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_mcu');
//                $data['sql_medc_mcu_rw']= $this->db->where('id_medcheck', general::dekrip($id))->where('id', general::dekrip($id_mcu))->get('tbl_trans_medcheck_mcu')->row();
//                $data['sql_medc_mcu_dt']= $this->db->where('id_medcheck', general::dekrip($id))->where('id_mcu', general::dekrip($id_mcu))->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();
                $data['sql_medc_file']      = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_file')->result();
                $data['sql_pasien']         = $this->db->where('id', $data['sql_medc']->id_pasien)->get('tbl_m_pasien')->row();
                $data['sql_dokter']         = $this->db->where('id_user', $data['sql_medc']->id_dokter)->get('tbl_m_karyawan')->row();
                $data['sql_petugas']        = $this->db->where('id_user', $data['sql_medc']->id_user)->get('tbl_m_karyawan')->row();
                $data['sql_poli']           = $this->db->where('id', $data['sql_medc']->id_poli)->get('tbl_m_poli')->row();
                $data['sql_dft']            = $this->db->where('id', $data['sql_medc']->id_dft)->get('tbl_pendaftaran')->row();
                $data['sql_penjamin']       = $this->db->where('id', $data['sql_medc']->tipe_bayar)->get('tbl_m_penjamin')->row();
                $data['sql_icd']            = $this->db->where('id', $data['sql_medc']->id_icd)->get('tbl_m_icd')->row();
                $data['sql_icd10']          = $this->db->where('id', $data['sql_medc']->id_icd10)->get('tbl_m_icd')->row();
                
                $st_medrep                  = (!empty($status) ? $status : $data['sql_medc']->status);
                $data['st_medrep']          = $st_medrep;
                
                switch ($st_medrep){                        
                    case '1':
                        $view = 'med_trans_periksa';
                        break;
                    
                    case '2':
                        $view = 'med_trans_tindakan';
                        break;
                    
                    case '3':
                        $view = 'med_trans_lab';
                        break;
                    
                    case '4':
                        $view = 'med_trans_obat';
                        break;
                    
                    case '5':
                        $view = 'med_trans_rad';
                        break;
                    
                    case '6':
                        $data['sql_medc_srt']   = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_surat')->result();
                        $view = 'med_trans_surat';
                        break;
                    
                    case '7':
                        $view = 'med_trans_rm';
                        break;
                    
                    case '8':
                        $view = 'med_trans_upload';
                        break;
                    
                    case '9':
                        $view = 'med_trans_resume';
                        break;
                    
                    case '10':
                        # Modul MCU
                        $view = 'med_trans_mcu';
                        break;
                    
                    case '11':
                        # Modul untuk rawat bersama
                        $data['sql_raber'] = $this->db->select('tbl_trans_medcheck_dokter.id, tbl_trans_medcheck_dokter.id_medcheck, tbl_trans_medcheck_dokter.id_dokter, tbl_trans_medcheck_dokter.tgl_simpan, tbl_trans_medcheck_dokter.keterangan, tbl_m_karyawan.nama_dpn, tbl_m_karyawan.nama, tbl_m_karyawan.nama_blk')->join('tbl_m_karyawan', 'tbl_m_karyawan.id_user=tbl_trans_medcheck_dokter.id_dokter')->where('tbl_trans_medcheck_dokter.id_medcheck', $data['sql_medc']->id)->get('tbl_trans_medcheck_dokter')->result();
                        
                        $view = 'med_dokter';
                        break;
                    
                    case '12':
                        # Modul untuk kuitansi
                        $data['sql_kwitansi'] = $this->db->where('id_medcheck', $data['sql_medc']->id)->get('tbl_trans_medcheck_kwi')->result();
                        
                        $view = 'med_kwitansi';
                        break;
                    
                    case '13':
                        # Modul untuk Inform Consent
                        $data['sql_medc_inf']   = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_surat_inform')->result();
                        $data['sql_medc_inf_rw']= $this->db->where('id', general::dekrip($id_form))->get('tbl_trans_medcheck_surat_inform')->row();
                        
                        $view = 'med_trans_inform';
                        break;
                }
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/'.(!empty($view) ? $view : 'med_trans'), $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function trans_medcheck_retur() {
        if (akses::aksesLogin() == TRUE) {
            $setting            = $this->db->get('tbl_pengaturan')->row();
            $id                 = $this->input->get('pasien');
            $id_produk          = $this->input->get('id_produk');
            $id_resep           = $this->input->get('id_resep');
            $id_lab             = $this->input->get('id_lab');
            $id_item            = $this->input->get('id_item');
            $status             = $this->input->get('status');
            $dft_pas            = $this->input->get('dft_pas');
            $dft_id             = $this->input->get('dft_id');
            $userid             = $this->ion_auth->user()->row()->id;

            $data['sess_retur']   = $this->session->userdata('trans_medcheck_retur');
            $data['sql_doc']      = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            $data['sql_medc']     = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
            $data['sql_pas']      = $this->db->where('id', $data['sql_medc']->id_pasien)->get('tbl_m_pasien')->row();

            if(!empty($data['sess_retur'])){
                $data['sql_medc_row']   = $this->db->where('id', $data['sess_retur']['id'])->get('tbl_trans_medcheck')->row();
                $data['sql_medc_det']   = $this->db->where('id_medcheck', general::dekrip($id))->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();            
                $data['sql_medc_retur'] = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_retur')->result(); 
                $data['sql_produk']     = $this->db->where('id', general::dekrip($id_produk))->get('tbl_m_produk')->row();          
                $data['sql_pasien']     = $this->db->where('id', $data['sql_medc_row']->id_pasien)->get('tbl_m_pasien')->row();
                $data['sql_dokter']     = $this->db->where('id', $data['sql_medc_row']->id_dokter)->get('tbl_m_karyawan')->row();
                $data['sql_poli']       = $this->db->where('id', $data['sql_medc_row']->id_poli)->get('tbl_m_poli')->row();              
            }

            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/med_trans_retur', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function trans_medcheck_retur_ranap() {
        if (akses::aksesLogin() == TRUE) {
            $setting            = $this->db->get('tbl_pengaturan')->row();
            $id                 = $this->input->get('id');
            $id_produk          = $this->input->get('id_produk');
            $id_resep           = $this->input->get('id_resep');
            $id_lab             = $this->input->get('id_lab');
            $id_item            = $this->input->get('id_item');
            $status             = $this->input->get('status');
            $dft_pas            = $this->input->get('dft_pas');
            $dft_id             = $this->input->get('dft_id');
            $userid             = $this->ion_auth->user()->row()->id;

            $data['sess_retur']   = $this->session->userdata('trans_medcheck_retur');
            $data['sql_doc']      = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();              
            $data['sql_dft_pas']  = $this->db->where('id', general::dekrip($dft_pas))->get('tbl_m_pasien')->row();
            $data['sql_dft_id']   = $this->db->where('id', general::dekrip($dft_id))->get('tbl_pendaftaran')->row();
            $data['sql_medc']       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();

            if(!empty($id)){
                $data['sql_medc_det']   = $this->db->select('tbl_trans_medcheck_det.id, tbl_trans_medcheck_det.id_medcheck, tbl_trans_medcheck_det.id_item_kat, tbl_m_kategori.keterangan, tbl_m_kategori.kategori')->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')->where('tbl_trans_medcheck_det.id_medcheck', general::dekrip($id))->group_by('tbl_trans_medcheck_det.id_item_kat')->get('tbl_trans_medcheck_det')->result();            
                $data['sql_medc_retur'] = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_retur')->result(); 
                $data['sql_produk']     = $this->db->where('id', general::dekrip($id_produk))->get('tbl_m_produk')->row();          
                $data['sql_pasien']     = $this->db->where('id', $data['sql_medc']->id_pasien)->get('tbl_m_pasien')->row();
                $data['sql_dokter']     = $this->db->where('id', $data['sql_medc']->id_dokter)->get('tbl_m_karyawan')->row();
                $data['sql_poli']       = $this->db->where('id', $data['sql_medc']->id_poli)->get('tbl_m_poli')->row();              
            }

            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/med_trans_retur_rnp', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function trans_medcheck_tindakan() {
        if (akses::aksesLogin() == TRUE) {
            $setting              = $this->db->get('tbl_pengaturan')->row();
            $id                   = $this->input->get('id');
            $id_produk            = $this->input->get('id_produk');
            $status               = $this->input->get('status');
            $dft                  = $this->input->get('dft');
            $userid               = $this->ion_auth->user()->row()->id;

            $data['sess_jual']    = $this->session->userdata('trans_medcheck');
            $data['kategori']     = $this->db->get('tbl_m_kategori')->result();
            $data['poli']         = $this->db->get('tbl_m_poli')->result();
            $data['sql_doc']      = $this->db->where('status', '2')->get('tbl_m_sales')->result();

            if(!empty($id)){
                $data['sql_produk']     = $this->db->where('id', general::dekrip($id_produk))->get('tbl_m_produk')->row();
                $data['sql_medc']       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $data['sql_medc_det']   = $this->db->select('tbl_trans_medcheck_det.id, tbl_trans_medcheck_det.id_medcheck, tbl_trans_medcheck_det.id_item_kat, tbl_m_kategori.keterangan, tbl_m_kategori.kategori')->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')->where('tbl_trans_medcheck_det.id_medcheck', general::dekrip($id))->group_by('tbl_trans_medcheck_det.id_item_kat')->get('tbl_trans_medcheck_det')->result();
                $data['sql_medc_plat']  = $this->db->where('id', $data['sql_medc']->metode)->get('tbl_m_platform')->row();
                $data['sql_dft']        = $this->db->where('id', $data['sql_medc']->id_dft)->get('tbl_pendaftaran')->row();            
                $data['sql_poli']       = $this->db->where('id', $data['sql_medc']->id_poli)->get('tbl_m_poli')->row();         
                $data['sql_pasien']     = $this->db->where('id', $data['sql_medc']->id_pasien)->get('tbl_m_pasien')->row();
                $data['sql_dokter']     = $this->db->where('id_user', $data['sql_medc']->id_dokter)->get('tbl_m_karyawan')->row();
                $data['sql_petugas']    = $this->db->where('id_user', $data['sql_medc']->id_user)->get('tbl_m_karyawan')->row();
                $data['sql_penjamin']   = $this->db->where('id', $data['sql_medc']->tipe_bayar)->get('tbl_m_penjamin')->row();
                $data['sql_penjamin2']  = $this->db->where('status', '1')->get('tbl_m_penjamin')->result();
                $data['sql_icd']        = $this->db->where('id', $data['sql_medc']->id_icd)->get('tbl_m_icd')->row();
                $data['sql_icd10']      = $this->db->where('id', $data['sql_medc']->id_icd10)->get('tbl_m_icd')->row();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/med_tindakan', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function trans_medcheck_trf() {
        if (akses::aksesLogin() == TRUE) {
            $setting              = $this->db->get('tbl_pengaturan')->row();
            $id                   = $this->input->get('id');
            $id_produk            = $this->input->get('id_produk');
            $status               = $this->input->get('status');
            $dft                  = $this->input->get('dft');
            $userid               = $this->ion_auth->user()->row()->id;

            $data['sess_jual']    = $this->session->userdata('trans_medcheck');
            $data['kategori']     = $this->db->get('tbl_m_kategori')->result();
            $data['poli']         = $this->db->get('tbl_m_poli')->result();
            $data['sql_doc']      = $this->db->where('id_user_group', '10')->get('tbl_m_karyawan')->result();

            if(!empty($id)){
                $data['sql_produk']     = $this->db->where('id', general::dekrip($id_produk))->get('tbl_m_produk')->row();
                $data['sql_medc']       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $data['sql_medc_det']   = $this->db->where('id_medcheck', general::dekrip($id))->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();            
                $data['sql_medc_trf']   = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_trf')->result();            
                $data['sql_pasien']     = $this->db->where('id', $data['sql_medc']->id_pasien)->get('tbl_m_pasien')->row();
                $data['sql_dokter']     = $this->db->where('id', $data['sql_medc']->id_dokter)->get('tbl_m_karyawan')->row();
                $data['sql_poli']       = $this->db->where('id', $data['sql_medc']->id_poli)->get('tbl_m_poli')->row();  
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/med_transfer', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
        
    public function trans_medcheck_detail() {
        if (akses::aksesLogin() == TRUE) {
            $setting              = $this->db->get('tbl_pengaturan')->row();
            $id                   = $this->input->get('id');
            $id_produk            = $this->input->get('id_produk');
            $status               = $this->input->get('status');
            $dft                  = $this->input->get('dft');
            $userid               = $this->ion_auth->user()->row()->id;

            $data['sess_jual']    = $this->session->userdata('trans_medcheck');
            $data['kategori']     = $this->db->get('tbl_m_kategori')->result();
            $data['poli']         = $this->db->get('tbl_m_poli')->result();
            $data['sql_doc']      = $this->db->where('status', '2')->get('tbl_m_sales')->result();

            if(!empty($id)){
                $data['sql_produk']     = $this->db->where('id', general::dekrip($id_produk))->get('tbl_m_produk')->row();
                $data['sql_medc']       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $data['sql_medc_det']   = $this->db->where('id_medcheck', general::dekrip($id))->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();            
                $data['sql_pasien']     = $this->db->where('id', $data['sql_medc']->id_pasien)->get('tbl_m_pasien')->row();
                $data['sql_dokter']     = $this->db->where('id', $data['sql_medc']->id_dokter)->get('tbl_m_karyawan')->row();
                $data['sql_poli']       = $this->db->where('id', $data['sql_medc']->id_poli)->get('tbl_m_poli')->row();  
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/med_detail', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function trans_medcheck_bayar() {
        if (akses::aksesLogin() == TRUE) {                   
            $id         = $this->input->get('id');
            $id_produk  = $this->input->get('item_id');
            $status     = $this->input->get('status');
            $userid     = $this->ion_auth->user()->row()->id;

            if(!empty($id)){
                $data['setting']      = $this->db->get('tbl_pengaturan')->row();
                $data['sql_medc']     = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $data['sql_medc_det'] = $this->db->select('tbl_trans_medcheck_det.id, tbl_trans_medcheck_det.id_medcheck, tbl_trans_medcheck_det.id_item_kat, tbl_m_kategori.keterangan, tbl_m_kategori.kategori')->join('tbl_m_kategori', 'tbl_m_kategori.id=tbl_trans_medcheck_det.id_item_kat')->where('tbl_trans_medcheck_det.id_medcheck', general::dekrip($id))->group_by('tbl_trans_medcheck_det.id_item_kat')->get('tbl_trans_medcheck_det')->result();             
                $data['sql_medc_det2']= $this->db->where('id', general::dekrip($id_produk))->get('tbl_trans_medcheck_det')->row();            
                $data['sql_medc_plat']= $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_plat')->result();
                $data['sql_produk']   = $this->db->where('id', ($_GET['status'] != '1' ? $data['sql_medc_det2']->id_item : general::dekrip($id_produk)))->get('tbl_m_produk')->row();
                $data['sql_satuan']   = $this->db->get('tbl_m_satuan')->result();
                $data['sql_pasien']   = $this->db->where('id', $data['sql_medc']->id_pasien)->get('tbl_m_pasien')->row();               
                $data['sql_dokter']   = $this->db->where('id_user', $data['sql_medc_det2']->id_dokter)->get('tbl_m_karyawan')->row();               
                $data['sql_platform'] = $this->db->order_by('id', 'asc')->get('tbl_m_platform')->result();                
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/med_bayar', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function trans_medcheck_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id                   = $this->input->get('id');
            $userid               = $this->ion_auth->user()->row()->id;
            
            $sql_medc = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck');
            
            if($sql_medc->num_rows() > 0){
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil dihapus</div>');
                crud::update('tbl_trans_medcheck', 'id', general::dekrip($id), array('status_hps'=>'2'));
            }
            
            redirect(base_url('medcheck/index.php?tipe='.$sql_medc->row()->tipe));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function trans_medcheck_restore() {
        if (akses::aksesLogin() == TRUE) {
            $id                   = $this->input->get('id');
            $userid               = $this->ion_auth->user()->row()->id;
            
            $sql_medc = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck');
            
            if($sql_medc->num_rows() > 0){
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil dihapus</div>');
                crud::update('tbl_trans_medcheck', 'id', general::dekrip($id), array('status_hps'=>'0'));
            }
            
            redirect(base_url('medcheck/index.php?tipe='.$sql_medc->row()->tipe));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function trans_medcheck_invoice() {
        if (akses::aksesLogin() == TRUE) {                   
            $id                   = $this->input->get('id');
            $id_produk            = $this->input->get('id_produk');
            $status               = $this->input->get('status');
            $userid               = $this->ion_auth->user()->row()->id;

            if(!empty($id)){
                $data['setting']        = $this->db->get('tbl_pengaturan')->row();
                $data['sql_produk']     = $this->db->where('id', general::dekrip($id_produk))->get('tbl_m_produk')->row();
                $data['sql_medc']       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $data['sql_medc_det']   = $this->db->where('id_medcheck', general::dekrip($id))->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();            
                $data['sql_medc_sum']   = $this->db->select('SUM(diskon) AS diskon, SUM(potongan) AS potongan, SUM(subtotal) AS subtotal')->where('id_medcheck', $data['sql_medc']->id)->get('tbl_trans_medcheck_det')->row();            
                $data['sql_medc_plat']  = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_plat')->result();            
                $data['sql_pasien']     = $this->db->where('id', $data['sql_medc']->id_pasien)->get('tbl_m_pasien')->row();
                $data['sql_dokter']     = $this->db->where('id', $data['sql_medc']->id_dokter)->get('tbl_m_karyawan')->row();
                $data['sql_poli']       = $this->db->where('id', $data['sql_medc']->id_poli)->get('tbl_m_poli')->row();
                $data['sql_platform']   = $this->db->get('tbl_m_platform')->result();             
            }

            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/med_invoice', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function trans_medcheck_print_dm() {
        if (akses::aksesLogin() == TRUE) {                   
            $id                   = $this->input->get('id');
            $id_produk            = $this->input->get('id_produk');
            $status               = $this->input->get('status');
            $userid               = $this->ion_auth->user()->row()->id;

            if(!empty($id)){
                $data['setting']        = $this->db->get('tbl_pengaturan')->row();
                $data['sql_produk']     = $this->db->where('id', general::dekrip($id_produk))->get('tbl_m_produk')->row();
                $data['sql_medc']       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $data['sql_medc_sum']   = $this->db->select('SUM(diskon) AS diskon, SUM(potongan) AS potongan, SUM(subtotal) AS subtotal')->where('id_medcheck', $data['sql_medc']->id)->get('tbl_trans_medcheck_det')->row();            
                $data['sql_medc_det']   = $this->db->where('id_medcheck', general::dekrip($id))->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();            
                $data['sql_medc_plat']  = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_plat')->result();
                $data['sql_pasien']     = $this->db->where('id', $data['sql_medc']->id_pasien)->get('tbl_m_pasien')->row();
                $data['sql_dokter']     = $this->db->where('id', $data['sql_medc']->id_dokter)->get('tbl_m_karyawan')->row();
                $data['sql_poli']       = $this->db->where('id', $data['sql_medc']->id_poli)->get('tbl_m_poli')->row(); 
            }

            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */

//            $this->load->view('admin-lte-3/1_atas', $data);
//            $this->load->view('admin-lte-3/2_header', $data);
//            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/med_print_dm', $data);
//            $this->load->view('admin-lte-3/5_footer',$data);
//            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function trans_medcheck_print_dm_ranap() {
        if (akses::aksesLogin() == TRUE) {                   
            $id                   = $this->input->get('id');
            $id_produk            = $this->input->get('id_produk');
            $status               = $this->input->get('status');
            $userid               = $this->ion_auth->user()->row()->id;

            if(!empty($id)){
                $data['setting']        = $this->db->get('tbl_pengaturan')->row();
                $data['sql_produk']     = $this->db->where('id', general::dekrip($id_produk))->get('tbl_m_produk')->row();
                $data['sql_medc']       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $data['sql_medc_sum']   = $this->db->select('SUM(diskon) AS diskon, SUM(potongan) AS potongan, SUM(subtotal) AS subtotal')->where('id_medcheck', $data['sql_medc']->id)->get('tbl_trans_medcheck_det')->row();            
                $data['sql_medc_det']   = $this->db->where('id_medcheck', general::dekrip($id))->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();            
                $data['sql_medc_plat']  = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_plat')->result();
                $data['sql_pasien']     = $this->db->where('id', $data['sql_medc']->id_pasien)->get('tbl_m_pasien')->row();
                $data['sql_dokter']     = $this->db->where('id', $data['sql_medc']->id_dokter)->get('tbl_m_karyawan')->row();
                $data['sql_poli']       = $this->db->where('id', $data['sql_medc']->id_poli)->get('tbl_m_poli')->row();              
            }

            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */

//            $this->load->view('admin-lte-3/1_atas', $data);
//            $this->load->view('admin-lte-3/2_header', $data);
//            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/med_print_dm', $data);
//            $this->load->view('admin-lte-3/5_footer',$data);
//            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    

    public function trans_resep_tambah() {
        if (akses::aksesLogin() == TRUE) {
            $setting                = $this->db->get('tbl_pengaturan')->row();
            $id_medc                = $this->input->get('id_medc');
            $id                     = $this->input->get('id');
            $id_resep               = $this->input->get('id_resep');
            $id_item                = $this->input->get('id_item');
            $id_item_res            = $this->input->get('id_item_resep');
            $userid                 = $this->ion_auth->user()->row()->id;

            $data['sess_jual']      = $this->session->userdata('trans_medcheck');
            $data['kategori']       = $this->db->get('tbl_m_kategori')->result();
            $data['poli']           = $this->db->get('tbl_m_poli')->result();
            $data['sql_doc']        = $this->db->where('status', '2')->get('tbl_m_karyawan')->result();

            if(!empty($id)){
                $data['sql_sat_pake']       = $this->db->get('tbl_m_satuan_pakai')->result();
                $data['sql_medc']           = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $data['sql_medc_det']       = $this->db->where('id_medcheck', $data['sql_medc']->id)->where('status', $status)->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();
                $data['sql_medc_det_rw']    = $this->db->where('id', general::dekrip($id_item))->get('tbl_trans_medcheck_det')->row();
                $data['sql_medc_srt']       = $this->db->where('id_medcheck', $data['sql_medc']->id)->get('tbl_trans_medcheck_surat')->result();
                $data['sql_medc_res']       = $this->db->where('id_medcheck', $data['sql_medc']->id)->limit(5)->get('tbl_trans_medcheck_resep');
                $data['sql_medc_res_rw']    = $this->db->where('id', general::dekrip($id_resep))->get('tbl_trans_medcheck_resep')->row();
                $data['sql_medc_res_dt']    = $this->db->where('id_medcheck', $data['sql_medc']->id)->where('id_resep', general::dekrip($id_resep))->get('tbl_trans_medcheck_resep_det')->result();
                $data['sql_medc_res_dt_rw'] = $this->db->where('id', general::dekrip($id_item_res))->get('tbl_trans_medcheck_resep_det')->row();
                $data['sql_produk']         = $this->db->where('id', (!empty($id_item) ? general::dekrip($id_item) : $data['sql_medc_res_dt_rw']->id_item))->get('tbl_m_produk')->row();
                $data['sql_pasien']         = $this->db->where('id', $data['sql_medc']->id_pasien)->get('tbl_m_pasien')->row();
                $data['sql_dokter']         = $this->db->where('id_user', $data['sql_medc']->id_dokter)->get('tbl_m_karyawan')->row();
                $data['sql_poli']           = $this->db->where('id', $data['sql_medc']->id_poli)->get('tbl_m_poli')->row();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/med_trans_resep', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function trans_resep_detail() {
        if (akses::aksesLogin() == TRUE) {
            $setting              = $this->db->get('tbl_pengaturan')->row();
            $id_medc              = $this->input->get('id_medc');
            $id                   = $this->input->get('id');
            $userid               = $this->ion_auth->user()->row()->id;

            $data['sess_jual']    = $this->session->userdata('trans_medcheck');
            $data['kategori']     = $this->db->get('tbl_m_kategori')->result();
            $data['poli']         = $this->db->get('tbl_m_poli')->result();
            $data['sql_doc']      = $this->db->where('status', '2')->get('tbl_m_karyawan')->result();

            if(!empty($id)){
                $data['sql_medc']       = $this->db->where('id', general::dekrip($id_medc))->get('tbl_trans_medcheck')->row();
                $data['sql_medc_res_rw']= $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck_resep')->row();
                $data['sql_dokter']     = $this->db->where('id', $data['sql_medc']->id_dokter)->get('tbl_m_karyawan')->row();
                $data['sql_poli']       = $this->db->where('id', $data['sql_medc']->id_poli)->get('tbl_m_poli')->row();
                $data['sql_pasien']     = $this->db->where('id', $data['sql_medc']->id_pasien)->get('tbl_m_pasien')->row();
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/med_trans_resep_det', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    


    public function medcheck_kasir_list() {
        if (akses::aksesLogin() == TRUE) {
            /* -- Grup hak akses -- */
            $role        = $this->input->get('role');
            $tipe        = $this->input->get('tipe');
            $grup        = $this->ion_auth->get_users_groups()->row();
            $id_user     = $this->ion_auth->user()->row()->id;
            $id_grup     = $this->ion_auth->get_users_groups()->row();
            $id_dokter   = $this->ion_auth->get_users_groups()->row();
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
            $stts    = $this->input->get('status');
            $jml     = $this->input->get('jml');
            $jml_sql = $this->db->select('id, id_app, id_user, id_dokter, id_nurse, id_analis, id_pasien, id_poli, no_nota, no_rm, tgl_simpan, TIME(tgl_simpan) AS waktu_masuk, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, TIME(tgl_keluar) AS waktu_keluar, jml_total, jml_gtotal, ppn, jml_ppn, tipe, status, status_nota')
                           ->where('status_hps', '0')
                           ->like('id_dokter', ($id_grup->name == 'dokter' ? $id_user : ''), ($id_grup->name == 'dokter' ? 'none' : ''))
                           ->order_by('id','desc')
                           ->get('tbl_trans_medcheck')->num_rows();

            if(!empty($jml)){
                $jml_hal = $jml_sql;
            }else{
                $jml_hal = $this->db->select('id, id_app, id_user, id_dokter, id_nurse, id_analis, id_pasien, id_poli, no_nota, no_rm, tgl_simpan, TIME(tgl_simpan) AS waktu_masuk, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, TIME(tgl_keluar) AS waktu_keluar, jml_total, jml_gtotal, ppn, jml_ppn, tipe, status, status_nota, tipe')
                                ->where('status_hps', '0')
                                ->like('id_dokter', ($id_grup->name == 'dokter' ? $id_user : ''), ($id_grup->name == 'dokter' ? 'none' : ''))
                                ->order_by('id','desc')
                                ->get('tbl_trans_medcheck')->num_rows();
            }
            /* -- End Blok Filter -- */

            /* -- Form Error -- */
            $data['hasError']                = $this->session->flashdata('form_error');

            /* -- Blok Pagination -- */
            $config['base_url']              = base_url('medcheck/data_medcheck.php?filter_nota='.$nt.'&filter_tgl='.$tg.'&filter_sales='.$sl.'&filter_status='.$sn.'&jml='.$jml);
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
                   $data['penj'] = $this->db->select('id, id_app, id_user, id_dokter, id_nurse, id_analis, id_pasien, id_poli, no_nota, no_rm, tgl_simpan, TIME(tgl_simpan) AS waktu_masuk, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, TIME(tgl_keluar) AS waktu_keluar, jml_total, jml_gtotal, ppn, jml_ppn, tipe, status, status_nota, tipe')
                           ->where('status_hps', '0')
                           ->where('status_bayar !=', '1')
                           ->like('id_dokter', ($id_grup->name == 'dokter' ? $id_user : ''), ($id_grup->name == 'dokter' ? 'none' : ''))
                           ->limit($config['per_page'],$hal)
                           ->order_by('id','desc')
                           ->get('tbl_trans_medcheck')->result();
            }else{
                   $data['penj'] = $this->db->select('id, id_app, id_user, id_dokter, id_nurse, id_analis, id_pasien, id_poli, no_nota, no_rm, tgl_simpan, TIME(tgl_simpan) AS waktu_masuk, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, TIME(tgl_keluar) AS waktu_keluar, jml_total, jml_gtotal, ppn, jml_ppn, tipe, status, status_nota, tipe')
                           ->where('status_hps', '0')
                           ->where('status_bayar !=', '1')
                           ->like('id_dokter', ($id_grup->name == 'dokter' ? $id_user : ''), ($id_grup->name == 'dokter' ? 'none' : ''))
                           ->limit($config['per_page'])
                           ->order_by('id','desc')
                           ->get('tbl_trans_medcheck')->result();
            }
            
            $this->pagination->initialize($config);

            /* Blok pagination */
            $data['total_rows'] = $config['total_rows'];
            $data['PerPage']    = $config['per_page'];
            $data['pagination'] = $this->pagination->create_links();
            $data['cetak']      = '<button type="button" onclick="window.location.href = \''.base_url('transaksi/cetak_data_penj.php?'.(!empty($nt) ? 'filter_nota='.$nt : '').(!empty($tg) ? '&filter_tgl='.$tg : '').(!empty($tp) ? '&filter_tgl_tempo='.$tp : '').(!empty($cs) ? '&filter_cust='.$cs : '').(!empty($sl) ? '&filter_sales='.$sl : '').(!empty($jml) ? '&jml='.$jml : '')).'\'" class="btn btn-warning"><i class="fa fa-print"></i> Cetak</button>';
            /* --End Blok pagination-- */
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/index', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();            
        }
    }
    
    public function medcheck_pemb_list() {
        if (akses::aksesLogin() == TRUE) {
            /* -- Grup hak akses -- */
            $role        = $this->input->get('role');
            $tipe        = $this->input->get('tipe');
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
            $cs      = $this->input->get('filter_nama');
            $sn      = $this->input->get('filter_status');
            $sl      = $this->input->get('filter_sales');
            $stts    = $this->input->get('status');
            $jml     = $this->input->get('jml');

            if(!empty($jml)){
                $jml_hal = $jml;
            }else{
                $jml_hal = $this->db->select('id, id_app, id_user, id_dokter, id_nurse, id_analis, id_pasien, id_poli, no_nota, no_rm, tgl_simpan, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, TIME(tgl_keluar) AS waktu_keluar, jml_total, jml_gtotal, ppn, jml_ppn, tipe, status, status_nota')
                                    ->where('status', '5')
                                    ->where('status_bayar !=', '1')
                                    ->like('pasien', $cs)
                                    ->order_by('tgl_simpan','desc')
                                    ->get('tbl_trans_medcheck')->num_rows();
            }
            /* -- End Blok Filter -- */

            /* -- Form Error -- */
            $data['hasError']                = $this->session->flashdata('form_error');

            /* -- Blok Pagination -- */
            $config['base_url']              = base_url('medcheck/data_pemb.php?tipe=1'.(!empty($cs) ? '&filter_nama='.$cs : '').(!empty($tg) ? '&filter_tgl='.$tg : '').(!empty($sp) ? '&filter_periksa='.$sp : '').(isset($by) ? '&filter_bayar='.$by : '').(!empty($jml_hal) ? '&jml='.$jml_hal : ''));
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
                   $data['penj'] = $this->db->select('id, id_app, id_user, id_dokter, id_nurse, id_analis, id_pasien, id_poli, tgl_masuk, no_nota, no_rm, tgl_simpan, TIME(tgl_simpan) AS waktu_masuk, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, TIME(tgl_keluar) AS waktu_keluar, jml_total, jml_gtotal, ppn, jml_ppn, tipe, status, status_nota, status_bayar, tipe')
                           ->where('status', '5')
                           ->where('status_bayar !=', '1')
                           ->like('pasien', $cs)
                           ->like('DATE(tgl_masuk)', $this->tanggalan->tgl_indo_sys($tg))
                           ->limit($config['per_page'],$hal)
//                           ->like('no_nota', $fn[0])
//                           ->like('DATE(tgl_bayar)', $tb)
//                           ->like('DATE(tgl_keluar)', $tp)
//                           ->like('id_pasien', $cs)
//                           ->like('id_sales', $sl)
//                           ->like('id_user', ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'admin' ? '' : $id_user))
//                           ->like('tgl_masuk', ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'adminm' || $id_grup->name == 'admin' ? $tg : date('Y-m-d')))
                           ->order_by('tgl_simpan','desc')
                           ->get('tbl_trans_medcheck')->result();
            }else{
                   $data['penj'] = $this->db->select('id, id_app, id_user, id_dokter, id_nurse, id_analis, id_pasien, id_poli, tgl_masuk, no_nota, no_rm, tgl_simpan, TIME(tgl_simpan) AS waktu_masuk, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, TIME(tgl_keluar) AS waktu_keluar, jml_total, jml_gtotal, ppn, jml_ppn, tipe, status, status_nota, status_bayar, tipe')
//                           ->where('status_nota !=', '4')
                           ->where('status', '5')
                           ->where('status_bayar !=', '1')
                           ->like('pasien', $cs)
                           ->like('DATE(tgl_masuk)', $this->tanggalan->tgl_indo_sys($tg))
                           ->limit($config['per_page'])
//                           ->like('no_nota', $fn[0])
//                           ->like('DATE(tgl_bayar)', $tb)
//                           ->like('DATE(tgl_keluar)', $tp)
//                           ->like('id_pasien', $cs)
//                           ->like('id_sales', $sl)
//                           ->like('id_user', ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'admin' ? '' : $id_user))
//                           ->like('tgl_masuk', ($id_grup->name == 'superadmin' || $id_grup->name == 'owner' || $id_grup->name == 'adminm' || $id_grup->name == 'admin' ? $tg : date('Y-m-d')))
                           ->order_by('tgl_simpan','desc')
                           ->get('tbl_trans_medcheck')->result();
            }

            $this->pagination->initialize($config);

            /* Blok pagination */
            $data['total_rows'] = $config['total_rows'];
            $data['PerPage']    = $config['per_page'];
            $data['pagination'] = $this->pagination->create_links();
            $data['cetak']      = '<button type="button" onclick="window.location.href = \''.base_url('transaksi/cetak_data_penj.php?'.(!empty($nt) ? 'filter_nota='.$nt : '').(!empty($tg) ? '&filter_tgl='.$tg : '').(!empty($tp) ? '&filter_tgl_tempo='.$tp : '').(!empty($cs) ? '&filter_cust='.$cs : '').(!empty($sl) ? '&filter_sales='.$sl : '').(!empty($jml) ? '&jml='.$jml : '')).'\'" class="btn btn-warning"><i class="fa fa-print"></i> Cetak</button>';
            /* --End Blok pagination-- */
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/data_pemb', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();            
        }
    }
    
    public function medcheck_batal_list() {
        if (akses::aksesLogin() == TRUE) {
            /* -- Grup hak akses -- */
            $role        = $this->input->get('role');
            $tipe        = $this->input->get('tipe');
            $grup        = $this->ion_auth->get_users_groups()->row();
            $id_user     = $this->ion_auth->user()->row()->id;
            $id_grup     = $this->ion_auth->get_users_groups()->row();
            $id_dokter   = $this->ion_auth->get_users_groups()->row();
            $pengaturan  = $this->db->get('tbl_pengaturan')->row();

            /* -- Blok Filter -- */
            $hal     = $this->input->get('halaman');
            $id      = $this->input->get('id');
            $cs      = $this->input->get('filter_nama');
            $by      = $this->input->get('filter_bayar');
            $tp      = $this->input->get('filter_tipe');
            $tg      = $this->input->get('filter_tgl');
            $sp      = $this->input->get('filter_periksa');
            $jml     = $this->input->get('jml');
            
            // Jika User dokter, maka pilih rajal dan ranap
            if(akses::hakDokter()){
                $jml_sql = $this->db->select('id, id_app, id_user, id_dokter, id_nurse, id_analis, id_pasien, id_poli, no_nota, no_rm, tgl_simpan, TIME(tgl_simpan) AS waktu_masuk, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, TIME(tgl_keluar) AS waktu_keluar, jml_total, jml_gtotal, ppn, jml_ppn, tipe, status, status_nota, tipe, status_periksa')
                                ->where('status_hps', '1')
                                ->where('tipe !=', '1')
                                ->where('tipe !=', '4')
                                ->like('DATE(tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                ->like('id_dokter', ($id_grup->name == 'dokter' ? $id_user : ''), ($id_grup->name == 'dokter' ? 'none' : ''))
                                ->like('id', general::dekrip($id))
                                ->like('pasien', $cs)
                                ->like('tipe', $tp)
                                ->like('status_bayar', $by)
                                ->like('status_periksa', $sp)
                                ->order_by('id','desc')
                                ->get('tbl_trans_medcheck')->num_rows();
            }else{
                $jml_sql = $this->db->select('id, id_app, id_user, id_dokter, id_nurse, id_analis, id_pasien, id_poli, no_nota, no_rm, tgl_simpan, TIME(tgl_simpan) AS waktu_masuk, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, TIME(tgl_keluar) AS waktu_keluar, jml_total, jml_gtotal, ppn, jml_ppn, tipe, status, status_nota, tipe, status_periksa')
                                ->where('status_hps', '1')
                                ->like('DATE(tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                ->like('tipe', ($id_grup->name == 'perawat_ranap' ? '3' : ''), ($id_grup->name == 'perawat_ranap' ? 'none' : ''))
                                ->like('id_dokter', ($id_grup->name == 'dokter' ? $id_user : ''), ($id_grup->name == 'dokter' ? 'none' : ''))
                                ->like('id', general::dekrip($id))
                                ->like('pasien', $cs)
                                ->like('tipe', $tp)
                                ->like('status_bayar', $by)
                                ->like('status_periksa', $sp)
                                ->order_by('id','desc')
                                ->get('tbl_trans_medcheck')->num_rows();                
            }

            if(!empty($jml)){
                $jml_hal = $jml;
            }else{
                $jml_hal = $jml_sql;
            }
            /* -- End Blok Filter -- */

            /* -- Form Error -- */
            $data['hasError']                = $this->session->flashdata('form_error');

            /* -- Blok Pagination -- */
            $config['base_url']              = base_url('medcheck/data_hapus.php?tipe=1'.(!empty($cs) ? '&filter_nama='.$cs : '').(!empty($tg) ? '&filter_tgl='.$tg : '').(!empty($tp) ? '&filter_tipe='.$tp : '').(!empty($sp) ? '&filter_periksa='.$sp : '').(isset($by) ? '&filter_bayar='.$by : '').(!empty($jml_hal) ? '&jml='.$jml_hal : ''));
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

            // Jika User dokter, maka pilih rajal dan ranap
            if(akses::hakDokter() == TRUE){
                if (!empty($hal)) {
                    $data['penj'] = $this->db->select('id, id_app, id_user, id_dokter, id_nurse, id_analis, id_pasien, id_poli, no_nota, no_rm, tgl_simpan, TIME(tgl_simpan) AS waktu_masuk, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, TIME(tgl_keluar) AS waktu_keluar, jml_total, jml_gtotal, ppn, jml_ppn, tipe, status, status_nota, status_bayar, tipe, status_periksa')
                                    ->where('status_hps', '1')
                                    ->where('tipe !=', '1')
                                    ->where('tipe !=', '4')
                                    ->like('DATE(tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                    ->like('id_dokter', ($id_grup->name == 'dokter' ? $id_user : ''), ($id_grup->name == 'dokter' ? 'none' : ''))
                                    ->like('id', general::dekrip($id))
                                    ->like('pasien', $cs)
                                    ->like('tipe', $tp)
                                    ->like('status_bayar', $by)
                                    ->like('status_periksa', $sp)
                                    ->limit($config['per_page'], $hal)
                                    ->order_by('id', 'desc')
                                    ->get('tbl_trans_medcheck')->result();
                } else {
                    $data['penj'] = $this->db->select('id, id_app, id_user, id_dokter, id_nurse, id_analis, id_pasien, id_poli, no_nota, no_rm, tgl_simpan, TIME(tgl_simpan) AS waktu_masuk, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, TIME(tgl_keluar) AS waktu_keluar, jml_total, jml_gtotal, ppn, jml_ppn, tipe, status, status_nota, status_bayar, tipe, status_periksa')
                                    ->where('status_hps', '1')
                                    ->where('tipe !=', '1')
                                    ->where('tipe !=', '4')
                                    ->like('DATE(tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                    ->like('id_dokter', ($id_grup->name == 'dokter' ? $id_user : ''), ($id_grup->name == 'dokter' ? 'none' : ''))
                                    ->like('id', general::dekrip($id))
                                    ->like('pasien', $cs)
                                    ->like('tipe', $tp)
                                    ->like('status_bayar', $by)
                                    ->like('status_periksa', $sp)
                                    ->limit($config['per_page'])
                                    ->order_by('id', 'desc')
                                    ->get('tbl_trans_medcheck')->result();
                }                
            }else{
                // Bukan Dokter
                if (!empty($hal)) {
                    $data['penj'] = $this->db->select('id, id_app, id_user, id_dokter, id_nurse, id_analis, id_pasien, id_poli, no_nota, no_rm, tgl_simpan, TIME(tgl_simpan) AS waktu_masuk, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, TIME(tgl_keluar) AS waktu_keluar, jml_total, jml_gtotal, ppn, jml_ppn, tipe, status, status_nota, status_bayar, tipe, status_periksa')
                                    ->where('status_hps', '1')
                                    ->like('DATE(tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                    ->like('tipe', ($id_grup->name == 'perawat_ranap' ? '3' : ''), ($id_grup->name == 'perawat_ranap' ? 'none' : ''))
                                    ->like('id_dokter', ($id_grup->name == 'dokter' ? $id_user : ''), ($id_grup->name == 'dokter' ? 'none' : ''))
                                    ->like('id', general::dekrip($id))
                                    ->like('pasien', $cs)
                                    ->like('tipe', $tp)
                                    ->like('status_bayar', $by)
                                    ->like('status_periksa', $sp)
                                    ->limit($config['per_page'], $hal)
                                    ->order_by('id', 'desc')
                                    ->get('tbl_trans_medcheck')->result();
                } else {
                    $data['penj'] = $this->db->select('id, id_app, id_user, id_dokter, id_nurse, id_analis, id_pasien, id_poli, no_nota, no_rm, tgl_simpan, TIME(tgl_simpan) AS waktu_masuk, DATE(tgl_bayar) as tgl_bayar, DATE(tgl_keluar) as tgl_keluar, TIME(tgl_keluar) AS waktu_keluar, jml_total, jml_gtotal, ppn, jml_ppn, tipe, status, status_nota, status_bayar, tipe, status_periksa')
                                    ->where('status_hps', '1')
                                    ->like('DATE(tgl_simpan)', $this->tanggalan->tgl_indo_sys($tg))
                                    ->like('tipe', ($id_grup->name == 'perawat_ranap' ? '3' : ''), ($id_grup->name == 'perawat_ranap' ? 'none' : ''))
                                    ->like('id_dokter', ($id_grup->name == 'dokter' ? $id_user : ''), ($id_grup->name == 'dokter' ? 'none' : ''))
                                    ->like('id', general::dekrip($id))
                                    ->like('pasien', $cs)
                                    ->like('tipe', $tp)
                                    ->like('status_bayar', $by)
                                    ->like('status_periksa', $sp)
                                    ->limit($config['per_page'])
                                    ->order_by('id', 'desc')
                                    ->get('tbl_trans_medcheck')->result();
                }
            }
            
            $this->pagination->initialize($config);

            /* Blok pagination */
            $data['total_rows'] = $config['total_rows'];
            $data['PerPage']    = $config['per_page'];
            $data['pagination'] = $this->pagination->create_links();
            $data['cetak']      = '<button type="button" onclick="window.location.href = \''.base_url('transaksi/cetak_data_penj.php?'.(!empty($nt) ? 'filter_nota='.$nt : '').(!empty($tg) ? '&filter_tgl='.$tg : '').(!empty($tp) ? '&filter_tgl_tempo='.$tp : '').(!empty($cs) ? '&filter_cust='.$cs : '').(!empty($sl) ? '&filter_sales='.$sl : '').(!empty($jml) ? '&jml='.$jml : '')).'\'" class="btn btn-warning"><i class="fa fa-print"></i> Cetak</button>';
            /* --End Blok pagination-- */
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */
            
            /* Load view tampilan */
            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/med_list_batal', $data);
            $this->load->view('admin-lte-3/5_footer', $data);
            $this->load->view('admin-lte-3/6_bawah', $data);
        }else{
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();            
        }
    }
    
    
    public function set_medcheck() {
        if (akses::aksesLogin() == TRUE) {
            $pasien     = $this->input->post('id_pasien');
            $dft_id     = $this->input->post('dft_id');
            $dokter     = $this->input->post('dokter');
            $poli       = $this->input->post('poli');
            $keluhan    = $this->input->post('keluhan');
            $ttv        = $this->input->post('ttv');
            $alergi     = $this->input->post('alergi');
            $tipe       = $this->input->post('tipe');
            $kategori   = $this->input->post('kategori');
            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            $pengaturan2= $this->db->where('id', $this->ion_auth->user()->row()->id_app)->get('tbl_pengaturan_cabang')->row();

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id_pasien', 'Nama Pasien', 'required');
            $this->form_validation->set_rules('tipe', 'Tipe', 'required');
            $this->form_validation->set_rules('poli', 'Klinik', 'required');
            $this->form_validation->set_rules('dokter', 'Dokter', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'tipe'    => form_error('tipe'),
                    'pasien'  => form_error('id_pasien'),
                    'poli'    => form_error('poli'),
                    'dokter'  => form_error('dokter'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php'.(!empty($dft_id) ? '?dft_pas='.general::enkrip($pasien).'&dft_id='.general::enkrip($dft_id) : '')));
            } else {
                $sql_dft    = $this->db->where('id', (!empty($dft_id) ? $dft_id : '0'))->get('tbl_pendaftaran')->row();
                $sql_pas    = $this->db->where('id', $pasien)->get('tbl_m_pasien')->row();
                
                $sql_rm     = $this->db->where('MONTH(tgl_simpan)', date('m'))->where('YEAR(tgl_simpan)', date('Y'))->get('tbl_trans_medcheck');
                $str_rm     = $sql_rm->num_rows() + 1;
                $no_rm      = date('ymd').sprintf('%04d', $str_rm);
                
                $sql_akun   = $this->db->select('COUNT(id) AS jml')->where('DATE(tgl_simpan)', date('Y-m-d'))->get('tbl_trans_medcheck')->row();
                $str_akun   = $sql_akun->jml + 1;
                $no_akun    = strtoupper(date('Mdy').sprintf('%04d', $str_akun));
                
                # Check Point
                $form_timestamp     = $this->input->post('timestamp');
                $stored_timestamp   = $this->session->userdata('form_timestamp');
//                $this->session->set_userdata('form_timestamp', $this->input->post('timestamp'));
                
                $data = array(
                    'id_app'       => $this->ion_auth->user()->row()->id_app,
                    'id_user'      => $this->ion_auth->user()->row()->id,
                    'id_nurse'     => $this->ion_auth->user()->row()->id,
                    'id_pasien'    => (!empty($pasien) ? $pasien : '0'),
                    'id_dokter'    => (!empty($dokter) ? $dokter : '0'),
                    'id_poli'      => (!empty($poli) ? $poli :'0'),
                    'id_dft'       => (!empty($dft_id) ? $dft_id : '0'),
                    'tgl_simpan'   => date('Y-m-d H:i:s'),
                    'tgl_masuk'    => date('Y-m-d H:i:s'),
                    'pasien'       => $sql_pas->nama_pgl,
                    'keluhan'      => $keluhan,
                    'ttv'          => $ttv,
                    'alergi'       => $sql_dft->alergi,
                    'tipe'         => (!empty($tipe) ? $tipe : '0'),
                    'no_rm'        => $no_rm,
                    'no_akun'      => $no_akun,
                    'tipe_bayar'   => (!empty($sql_dft->tipe_bayar) ? $sql_dft->tipe_bayar : '0'),
                    'status'       => '1',
                    'status_nota'  => '1',
                );
                
                /* Transaksi Database */
                $this->db->query('SET autocommit = 0;');
                $this->db->trans_start();

                # Masukkan ke tabel medcheck
                $this->db->insert('tbl_trans_medcheck', $data);
                $last_id = crud::last_id();
                
                # Jika tipe medcheck lab atau radiologi
                if($tipe == '1'){                   
                    $nomer      = $this->db->where('MONTH(tgl_simpan)', date('m'))->get('tbl_trans_medcheck_lab')->num_rows() + 1;
                    $no_surat   = sprintf('%03d', $nomer).'/'.$pengaturan->kode_surat.'/'.date('m').'/'.date('Y');
                    $grup       = $this->ion_auth->get_users_groups()->row();
                    $is_farm    = ($grup->name == 'analis' ? '2' : '0');
                    $is_farm_id = ($grup->name == 'analis' ? $this->ion_auth->user()->row()->id : '0');
                    $is_doc_id  = ($grup->name == 'dokter' ? $this->ion_auth->user()->row()->id : '0');
                
                    $data_lab = array(
                        'tgl_simpan'    => date('Y-m-d H:i:s'),
                        'tgl_masuk'     => date('Y-m-d H:i:s'),
                        'id_medcheck'   => $last_id,
                        'id_pasien'     => $pasien,
                        'id_user'       => $this->ion_auth->user()->row()->id,
                        'id_analis'     => $is_farm_id,
                        'id_dokter'     => $is_doc_id,
                        'no_lab'        => $no_surat,
                        'status'        => '0',
                        'status_cvd'    => '0',
                    );
                    
                    # Simpan ke tabel lab
                    $this->db->insert('tbl_trans_medcheck_lab', $data_lab);
                
                }elseif($tipe == '4'){
                    $nomer      = $this->db->where('MONTH(tgl_simpan)', date('m'))->get('tbl_trans_medcheck_rad')->num_rows() + 1;
                    $no_surat   = sprintf('%03d', $nomer).'/'.$pengaturan->kode_rad.'/'.date('m').'/'.date('Y');
                    $grup       = $this->ion_auth->get_users_groups()->row();
                    $is_rad     = ($grup->name == 'radiografer' ? '2' : '0');
                    $is_rad_id  = ($grup->name == 'radiografer' ? $this->ion_auth->user()->row()->id : '0');
                    $is_doc_id  = ($grup->name == 'dokter' ? $this->ion_auth->user()->row()->id : '0');
                   
                    $data_rad = array(
                        'tgl_simpan'    => date('Y-m-d H:i:s'),
                        'tgl_masuk'     => date('Y-m-d H:i:s'),
                        'id_medcheck'   => $last_id,
                        'id_pasien'     => $pasien,
                        'id_user'       => $this->ion_auth->user()->row()->id,
                        'id_radiografer'=> $is_rad_id,
                        'id_dokter'     => $is_doc_id,
                        'no_rad'        => $no_surat,
                        'no_sample'     => '-',
                        'status'        => '0',
                    );
                    
                    # Simpan ke tabel rad
                    $this->db->insert('tbl_trans_medcheck_rad', $data_rad);
                }

                # Update ke tabel pendaftaran
                $this->db->where('id', $dft_id)->update('tbl_pendaftaran', array('status_akt' => '2', 'file_base64'=>''));

                # Cek status transact MySQL
                if ($this->db->trans_status() === FALSE) {
                    # Rollback jika gagal
                    $this->db->trans_rollback();

                    # Tampilkan pesan error
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Data Medical Checkup gagal disimpan !!</div>');
                } else {
//                        $this->db->trans_commit();
                    $this->db->trans_complete();

                    # Buat session jika sudah berhasil commit
                    $this->session->set_userdata('trans_medcheck', $data);

                    # Tampilkan pesan sukses jika sudah berhasil commit
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data Medical Checkup Sudah disimpan !!</div>');
                }

                redirect(base_url('medcheck/index.php?tipe='.$tipe));

//                crud::simpan('tbl_trans_medcheck', $data);
//                $last_id = crud::last_id();
//                
//                if(!empty($last_id)){
//                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data Medical Checkup Sudah disimpan !!</div>');
//                    crud::update('tbl_pendaftaran', 'id', $dft_id, array('status_akt'=>'2'));
//                    
//                    // Set session
//                    $this->session->set_userdata('trans_medcheck', $data);
//                }else{
//                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Data Medical Checkup gagal disimpan !!</div>');
//                }
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_pasien() {
        if (akses::aksesLogin() == TRUE) {
            $id = $this->input->get('dft');
            
            $this->load->helper('file');
            
            if(!empty($id)){
                $pengaturan = $this->db->get('tbl_pengaturan')->row();
                $sql_dft    = $this->db->where('id', general::dekrip($id))->get('tbl_pendaftaran')->row();
                $sql_cek    = $this->db->where('nik', $sql_dft->nik)->where('nama', $sql_dft->nama)->where('alamat', $sql_dft->alamat)->get('tbl_m_pasien');
                $sql_num    = $this->db->order_by('id', 'DESC')->limit(1)->get('tbl_m_pasien')->row();
                $num        = $sql_num->id + 1;
                
                $data_pas = array(
                    'tgl_simpan'        => $sql_dft->tgl_simpan,
                    'tgl_modif'         => $sql_dft->tgl_simpan,
                    'id_gelar'          => $sql_dft->id_gelar,
                    'id_kategori'       => '1',
                    'id_pekerjaan'      => $sql_dft->id_pekerjaan,
                    'kode_dpn'          => $pengaturan->kode_pasien,
                    'nik'               => $sql_dft->nik,
                    'nama'              => $sql_dft->nama,
                    'nama_pgl'          => $sql_dft->nama_pgl,
                    'tmp_lahir'         => $sql_dft->tmp_lahir,
                    'tgl_lahir'         => $sql_dft->tgl_lahir,
                    'jns_klm'           => $sql_dft->jns_klm,
                    'no_hp'             => $sql_dft->kontak,
                    'no_telp'           => $sql_dft->kontak_rmh,
                    'alamat'            => $sql_dft->alamat,     // (!empty($sql_dft->alamat) || $sql_dft->alamat != 0 ? $sql_dft->alamat : ''),
                    'alamat_dom'        => $sql_dft->alamat_dom, // (!empty($sql_dft->alamat_dom) || $sql_dft->alamat_dom != 0 ? $sql_dft->alamat_dom : ''),
                    'instansi'          => (!empty($sql_dft->instansi) || $sql_dft->instansi !='-' ? $sql_dft->instansi : ''),
                    'instansi_alamat'   => (!empty($sql_dft->instansi_alamat) || $sql_dft->instansi_alamat !='-' ? $sql_dft->instansi_alamat : ''),                  
                    'status'            => '1'
                );
                
                /* Transaksi Database */
                $this->db->query('SET autocommit = 0;');
                $this->db->trans_start();
                
                if($sql_dft->status == '2'){
                    // Nek kembar, wajib tendang su
                    if($sql_cek->num_rows() > 0){
                        $pasien_id = $sql_cek->row()->id;

                        # Update data pasien
                        $this->db->where('id', $pasien_id)->update('tbl_m_pasien', $data_pas);

                        $last_id = $pasien_id;
                    }else{
                        $this->db->insert('tbl_m_pasien', $data_pas);
                        $last_id = crud::last_id();
                    }
                }else{
                    $sql_pas = $this->db->where('id', $sql_dft->id_pasien)->get('tbl_m_pasien')->row();                    
                    $this->db->where('id', $sql_pas->id)->update('tbl_m_pasien', $data_pas);
                    $last_id = $sql_pas->id;
                }
                
                # Config File Foto Pasien
                $kode               = sprintf('%05d', $last_id);
                $no_rm              = strtolower($pengaturan->kode_pasien).$kode;
                $path               = 'file/pasien/'.$no_rm.'/';
                
                # Buat Folder Untuk Foto Pasien
                if(!file_exists($path)){
                    mkdir($path, 0777, true);
                }
                
                # Simpan foto dari kamera ke dalam format file *.png dari base64
                if (!empty($sql_dft->file_base64)) {
                    $filename           = $path.'profile_'.$kode.'.png';
                    general::base64_to_jpeg($sql_dft->file_base64, $filename);
                }
                
                # Simpan foto dari kamera ke dalam format file *.png dari base64 KTP / ID PASIEN
                if (!empty($sql_dft->file_base64_id)) {
                    $filename_id        = $path.'ID_'.$kode.'.png';
                    general::base64_to_jpeg($sql_dft->file_base64_id, $filename_id);
                }
                
                # Integrasi data pasien untuk akses login pasien
                $sql_user       = $this->db->select('id, username')->where('username', $no_rm)->get('tbl_ion_users');
                $sql_user_rw    = $sql_user->row();
                $email          = $no_rm.'@'.$pengaturan->website; # Format Email
                $user           = $no_rm; # Format username pasien menggunakan no rm
                $pass2          = ($sql_dft->tgl_lahir == '0000-00-00' ? $user : $this->tanggalan->tgl_indo8($sql_dft->tgl_lahir)); # Format kata sandi pasien menggunakan tanggal lahir dd-mm-yyyy jika tanggal lahir kosong maka passwordnya sama dengan username
                
                # Cek username tersebut sudah pernah di pakai atau belum
                if($sql_user->num_rows() == 0){
                    $data_user = array(
                        'email'         => $email,
                        'first_name'    => $sql_dft->nama,
                        'nama'          => $sql_dft->nama_pgl,
                        'address'       => $sql_dft->alamat,
                        'phone'         => $sql_dft->kontak,
                        'birthdate'     => $sql_dft->tgl_lahir,
                        'file_name'     => (file_exists($filename) ? $filename : ''),
                        'username'      => $no_rm,
                        'tipe'          => '2',
                    );
                    
                    # Simpan ke modul user
                    $this->ion_auth->register($user, $pass2, $email, $data_user, array('15'));
                    
                    $sql_user_ck = $this->db->select('id, username')->where('username', $user)->get('tbl_ion_users')->row();
                    $id_user = $sql_user_ck->id;
                }else{
                    $data_user = array(
                        'email'         => $email,
                        'first_name'    => $sql_dft->nama,
                        'nama'          => $sql_dft->nama_pgl,
                        'address'       => $sql_dft->alamat,
                        'phone'         => $sql_dft->kontak,
                        'birthdate'     => $sql_dft->tgl_lahir,
                        'file_name'     => (file_exists($filename) ? $filename : ''),
                        'username'      => $no_rm,
                        'password'      => $pass2,
                        'tipe'          => '2',
                    );
                    
                    $this->ion_auth->update($id_user, $data_user);
                    $id_user = $sql_user_rw->id;                    
                }
                
                # Simpan ID User dari tabel ion_users ke tabel pasien
                $data_pas_upd = array(
                    'id_user' => $id_user,
                    'kode'    => $kode,
                );
                
                $this->db->where('id', $last_id)->update('tbl_m_pasien', $data_pas_upd); 

                    
                # Cek File Sudah jadi
                if(file_exists($filename) OR file_exists($filename_id)){  
                    # Data File
                    $data_file = array(
                        'file_name'     => (file_exists($filename) ? $filename : ''),
                        'file_name_id'  => (file_exists($filename_id) ? $filename_id : ''),
                        'file_type'     => (file_exists($filename) || file_exists($filename_id) ? 'image/png' : ''),
                        'file_ext'      => (file_exists($filename) || file_exists($filename_id) ? '.png' : ''),
                    );

                    # Simpan File Gambar ke tabel
                    $this->db->where('id', $last_id)->update('tbl_m_pasien', $data_file);
                    
                    # Hapus gambar pada pendaftaran supaya tidak penuh
                    $this->db->where('id', $sql_dft->id)->update('tbl_pendaftaran', array('file_base64'=>'','file_base64_id'=>''));
                }
                
                # Cek status transact MySQL
                if ($this->db->trans_status() === FALSE) {
                    # Rollback jika gagal
                    $this->db->trans_rollback();

                    # Tampilkan pesan error
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Data pasien gagal disimpan !!</div>');
                } else {
//                    $this->db->trans_commit();
                    $this->db->trans_complete();

                    # Tampilkan pesan sukses jika sudah berhasil commit
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data pasien sudah disimpan !!</div>');
                }  

                $this->session->set_flashdata('member', '<div class="alert alert-success">Data member berhasil diubah</div>');
                redirect(base_url('medcheck/tambah.php?dft_pas='.general::enkrip($last_id).'&dft_id='.general::enkrip($sql_dft->id)));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_update() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $anamnesa   = $this->input->post('anamnesa');
            $diagnosa   = $this->input->post('diagnosa');
            $periksa    = $this->input->post('pemeriksaan');
            $program    = $this->input->post('program');
            $icd        = $this->input->post('icd');
            $icd10      = $this->input->post('icd10');
            $status     = $this->input->post('status');
            $st_priksa  = $this->input->post('status_periksa');
            $rute       = $this->input->post('route');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');
//            $this->form_validation->set_rules('anamnesa', 'Tipe', 'required');
//            $this->form_validation->set_rules('status_periksa', 'Periksa', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'                => form_error('id'),
//                    'status_periksa'    => form_error('status_periksa'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                $this->session->set_flashdata('medcheck_toast', 'toastr.error("Entri data pemeriksaan gagal !");');
                redirect(base_url('medcheck/tambah.php?id='.$id.$rute));
            } else {
                $sql_medc = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                        
                $data = array(
                    'tgl_modif'         => date('Y-m-d H:i:s'),
                    'anamnesa'          => $anamnesa,
                    'pemeriksaan'       => $periksa,
                    'diagnosa'          => $diagnosa,
                    'program'           => $program,
                    'id_icd'            => (!empty($icd) ? $icd : '0'),
                    'id_icd10'          => (!empty($icd10) ? $icd10 : '0'),
                    'status'            => $status,
                    'status_periksa'    => $st_priksa,
                    'status_nota'       => '2',
                );
                
                $this->db->where('id', general::dekrip($id))->update('tbl_trans_medcheck', $data);
//                
                $this->session->set_flashdata('medcheck_toast', 'toastr.success("Entri data pemeriksaan berhasil !");');
                
                if(!empty($rute)){
                    redirect(base_url('medcheck/tambah.php?id='.$id.$rute));
                }else{
                    redirect(base_url('medcheck/index.php?tipe='.$sql_medc->tipe));
                }
                   
//                redirect(base_url('medcheck/index.php?tipe='.$sql_medc->tipe));
//                redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
//                
//                echo '<pre>';
//                print_r($data);
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_update_penj() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $penjamin   = $this->input->post('platform');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'                => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php'));
            } else {
                $sql_medc       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_medc_det   = $this->db->select('id, id_resep, id_item, kode, item, jml, jml_satuan, harga, disk1, disk2, disk3, potongan, subtotal')->where('id_medcheck', $sql_medc->id)->where('status', '4')->get('tbl_trans_medcheck_det')->result();
                $sql_pnjm       = $this->db->where('id', $penjamin)->get('tbl_m_penjamin')->row();
                
                $data = array(
                    'tgl_modif'         => date('Y-m-d H:i:s'),
                    'tipe_bayar'        => $penjamin,
                );
                
                foreach ($sql_medc_det as $det){
                    $sql_item       = $this->db->where('id', $det->id_item)->get('tbl_m_produk')->row();
                    $sql_res_det    = $this->db->where('id_resep', $det->id_resep)->where('id_item', $det->id_item)->get('tbl_trans_medcheck_resep_det')->row();
                    $sql_res_det_rc = $this->db->where('id_resep', $det->id_resep)->get('tbl_trans_medcheck_resep_det_rc')->result();
                
                    $harga          = $sql_item->harga_jual;
                    $ass            = $harga * $sql_pnjm->persen;
                    $harga_pcs      = ($sql_pnjm->persen != 0 ? $ass : $harga); # Jika penjamin asuransi, maka harga obat di tambah sesuai setelan % pada database
                    $harga_tot      = ($sql_item->status_racikan == '1' ? $harga : $harga_pcs);
                    $potongan       = $det->potongan;
                    
                    $disk1          = $harga_tot - (($det->disk1 / 100) * $harga_tot);
                    $disk2          = $disk1 - (($det->disk2 / 100) * $disk1);
                    $disk3          = $disk2 - (($det->disk3 / 100) * $disk2);
                    $diskon         = $harga_tot - $disk3;
                    $subtotal       = ($disk3 - $potongan) * (int)$det->jml;
                    
                    $data_item = array(
                        'id_resep'  => $det->id_resep,
                        'id_item'   => $det->id_item,
                        'item'      => $det->item,
                        'harga'     => ($sql_item->status_racikan == '1' ? $harga : $harga_tot),
                        'disk1'     => $det->disk1,
                        'disk2'     => $det->disk2,
                        'disk3'     => $det->disk3,
                        'potongan'  => $det->potongan,
                        'subtotal'  => $subtotal,
                    );
                    
                    foreach ($sql_res_det_rc as $racikan){
                        $sql_item_rc    = $this->db->where('id', $racikan->id_item)->get('tbl_m_produk')->row();
                        $harga_rc       = $sql_item_rc->harga_jual;
                        $ass_rc         = $harga_rc * $sql_pnjm->persen;
                        $harga_tot_rc   = ($sql_pnjm->persen != 0 ? $ass_rc : $harga_rc); # Jika penjamin asuransi, maka harga obat racikan di tambah sesuai setelan % pada database
                    
                        $subtotal_rc    = $harga_tot_rc * (int)$racikan->jml;
                        
                        $data_item_rc = array(
                            'harga'     => $harga_tot_rc,
                            'subtotal'  => $subtotal_rc,
                        );
                        
                        # Update isi data di tabel racikan detail beserta perubahan harganya jika ada asuransi
                        $this->db->where('id', $racikan->id)->update('tbl_trans_medcheck_resep_det_rc', $data_item_rc);
                    }
                    
                    # Ambil data detail json dari tabel racikan
                    $sql_res_det_rc2    = $this->db->where('id_resep', $det->id_resep)->get('tbl_trans_medcheck_resep_det_rc')->result();
                    $resep              = ($sql_item->status_racikan == '1' ? json_encode($sql_res_det_rc2) : '');

                    # Update isi data di tabel resep beserta perubahan harganya jika ada asuransi
                    $this->db->where('id', $sql_res_det->id)->update('tbl_trans_medcheck_resep_det', $data_item);

                    # Update isi data di tabel medcheck det beserta perubahan harganya jika ada asuransi
                    $this->db->where('id', $det->id)->update('tbl_trans_medcheck_det', $data_item);

                    # Update isi data racikan di tabel resep dan nota
                    $this->db->where('id', $sql_res_det->id)->update('tbl_trans_medcheck_resep_det', array('resep'=>$resep));
                    $this->db->where('id', $det->id)->update('tbl_trans_medcheck_det', array('resep'=>$resep));

//                    echo '<pre>';
//                    print_r($resep);
//                    echo '</pre>';
                }
                
                # Update pada tabel medcheck nya, ubah data sesuai penjamin
                $this->db->where('id', $sql_medc->id)->update('tbl_trans_medcheck', $data);
//                
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Perubahan data penjamin berhasil !!</div>');
                redirect(base_url('medcheck/tindakan.php?id='.$id));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_transfer() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $poli_asl   = $this->input->post('poli_asl');
            $poli_7an   = $this->input->post('poli_7an');
            $dokter     = $this->input->post('dokter');
            $tipe       = $this->input->post('tipe');
            $catatan    = $this->input->post('catatan');
            $status     = $this->input->post('status');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                );

                $this->session->set_flashdata('anamnesa', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id));
            } else {
                $sql_medc       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                crud::delete('tbl_trans_medcheck_trf', 'id_medcheck', $sql_medc->id);
                
                $data = array(
                    'id_medcheck'           => $sql_medc->id,
                    'id_user'               => $this->ion_auth->user()->row()->id,
                    'id_pasien'             => $sql_medc->id_pasien,
                    'id_dokter'             => $dokter,
                    'id_poli_asal'          => $poli_asl,
                    'id_poli_tujuan'        => $poli_7an,
                    'tipe'                  => $tipe,
                    'tgl_simpan'            => date('Y-m-d H:i:s'),
                    'keterangan_perawat'    => $catatan,
                    'status'                => '0',
                );
                
                $data_medc = array(
                    'id_poli'   => $poli_7an,
                    'id_dokter' => $dokter,
                    'tipe'      => $tipe,
                    'tgl_modif' => date('Y-m-d H:i:s'),
                );
                
//                echo '<pre>';
//                print_r($data_medc);
//                echo '</pre>';
                
                crud::simpan('tbl_trans_medcheck_trf', $data);
                $last_id = crud::last_id();                
                crud::update('tbl_trans_medcheck', 'id', $sql_medc->id, $data_medc);
                
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transfer Pasien Berhasil !!</div>');
                redirect(base_url('medcheck/transfer.php?id='.general::enkrip($sql_medc->id).'&trf_id='.general::enkrip($last_id).'&status='.$sql_medc->status));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_doc() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $dokter     = $this->input->post('dokter');
            $catatan    = $this->input->post('catatan');
            $status     = $this->input->post('status');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                );

                $this->session->set_flashdata('anamnesa', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
            } else {
                $sql_medc   = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_cek    = $this->db->where('id_medcheck', $sql_medc->id)->where('id_dokter', $dokter)->get('tbl_trans_medcheck_dokter');
                
                $data = array(
                    'id_medcheck'   => $sql_medc->id,
                    'id_user'       => $this->ion_auth->user()->row()->id,
                    'id_pasien'     => $sql_medc->id_pasien,
                    'id_dokter'     => $dokter,
                    'tgl_simpan'    => date('Y-m-d H:i:s'),
                    'keterangan'    => $catatan
                );
                
                # Cek jika sudah ada nama dokter sama dengan dokter utama tendang
                if($sql_medc->id_dokter == $dokter){
                    # Tampilkan bahwa sudah ada value kembar
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger"> <b>'.$this->ion_auth->user($sql_medc->id_dokter)->row()->first_name.'</b> sudah di atur sebagai dokter utama,<br/>silahkan memilih nama lain !!</div>');                    
                }else{
                    # Cek jika sudah ada id_medcheck dan nama dokter yang sama silahkan tendang
                    if ($sql_cek->num_rows() > 0) {
                        # Tampilkan bahwa sudah ada value kembar
                        $this->session->set_flashdata('medcheck', '<div class="alert alert-danger"><b>'.$this->ion_auth->user($dokter)->row()->first_name.'</b> sudah tersimpan sebelumnya, silahkan cek pada daftar dokter !!</div>');
                    } else {
                        # Transact SQL
                        $this->db->trans_start();

                        # Masukkan ke tabel medcheck_dokter
                        $this->db->insert('tbl_trans_medcheck_dokter', $data);

                        # Cek status transact MySQL
                        if ($this->db->trans_status() === FALSE) {
                            # Rollback jika gagal
                            $this->db->trans_rollback();

                            # Tampilkan pesan error
                            $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Data rawat bersama gagal disimpan !!</div>');
                        } else {
                            $this->db->trans_complete();

                            # Tampilkan pesan sukses jika sudah berhasil commit
                            $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data rawat bersama disimpan !!</div>');
                        }
                    }
                }
                
                redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->id).'&status='.$status));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_doc_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_item    = $this->input->get('id_item');
            $status     = $this->input->get('status');
            
            if(!empty($id)){
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Item berhasil di hapus</div>');
                crud::delete('tbl_trans_medcheck_dokter', 'id', general::dekrip($id_item));
            }
            
            redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_update_inv() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            
            if(!empty($id)){
                $sql_medc         = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_medc_det     = $this->db->where('id_medcheck', $sql_medc->id)->get('tbl_trans_medcheck_det')->result();
                $sql_medc_det_sum = $this->db->select('SUM(subtotal) AS subtotal, SUM(diskon) AS diskon, SUM(potongan) AS potongan')->where('id_medcheck', $sql_medc->id)->get('tbl_trans_medcheck_det')->row();
                
                $jml_total  = $sql_medc_det_sum->subtotal + $sql_medc_det_sum->potongan;
                $jml_diskon =$sql_medc_det_sum->potongan;
                
                $data = array(
                    'jml_total'     => $jml_total,
                    'jml_diskon'    => $jml_diskon,
                    'jml_subtotal'  => $sql_medc_det_sum->subtotal,
                    'jml_gtotal'    => $sql_medc_det_sum->subtotal,
                );
                
                crud::update('tbl_trans_medcheck', 'id', $sql_medc->id, $data);
            }
            
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil di proses !!</div>');
                redirect(base_url('medcheck/invoice/bayar.php?id='.$id));
//                           
//                echo '<pre>';
//                print_r($data);
//                echo '</pre>';
//                           
//                echo '<pre>';
//                print_r($sql_medc_det_sum);
//                echo '</pre>';
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_proses(){
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $status     = $this->input->post('status');
//            $jml_total  = $this->input->post('jml_total');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                );

                $this->session->set_flashdata('anamnesa', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id));
            } else {
                $sql_medc       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_medc_det   = $this->db->select('SUM(potongan) AS potongan, SUM(diskon) AS diskon, SUM(subtotal) AS subtotal')->where('id_medcheck', $sql_medc->id)->get('tbl_trans_medcheck_det')->row();   
                $sql_medc_det2  = $this->db->where('id_medcheck', $sql_medc->id)->get('tbl_trans_medcheck_det')->result(); 
                $pengaturan     = $this->db->get('tbl_pengaturan')->row();
                

                $nomer          = $this->db->where('MONTH(tgl_simpan)', date('m'))->where('YEAR(tgl_simpan)', date('Y'))->get('tbl_trans_medcheck')->num_rows() + 1;
                $no_nota        = 'INV/'.date('Y').'/'.date('m').'/'.sprintf('%05d', $nomer);
                
                $jml_total      = $sql_medc_det->subtotal + $sql_medc_det->potongan + $sql_medc_det->diskon;
                $jml_pot        = $sql_medc_det->potongan;
                $jml_diskon     = $sql_medc_det->diskon;
                $diskon         = ($jml_diskon / $jml_total) * 100;
                $jml_subtotal   = $sql_medc_det->subtotal;
                $ppn            = $pengaturan->ppn;
                $jml_ppn        = $pengaturan->ppn;
                $jml_gtotal     = ceil($sql_medc_det->subtotal);
                
                $data = array(
                    'tgl_modif'     => date('Y-m-d H:i:s'),
                    'no_nota'       => $no_nota,
                    'jml_total'     => (float)$jml_total,
                    'jml_diskon'    => (float)$jml_diskon,
                    'diskon'        => ($diskon > 1 ? (float)round($diskon, 2) : '0'),
                    'jml_potongan'  => (float)$jml_pot,
                    'jml_subtotal'  => (float)$jml_subtotal,
                    'jml_gtotal'    => (float)$jml_subtotal,
                    'status'        => '5',
                );
       
//                echo '<pre>';
//                print_r($data);
//                echo '</pre>';
                
                # Transactional Database
                $this->db->trans_off();
                $this->db->trans_start();
                
                # Kueri MySQL tulis disini
                if($sql_medc->status < 5){                    
                    # Update data nota dll
                    $this->db->where('id', general::dekrip($id))->update('tbl_trans_medcheck', $data);           
                      
                    foreach ($sql_medc_det2 as $medc_det){
                          $sql_item        = $this->db->where('id', $medc_det->id_item)->get('tbl_m_produk')->row();
                          $sql_item_ref    = $this->db->where('id_produk', $sql_item->id)->get('tbl_m_produk_ref');   
                          $sql_satuan      = $this->db->where('id', $sql_item->id_satuan)->get('tbl_m_satuan')->row();
                          $sql_gudang      = $this->db->where('status', '1')->get('tbl_m_gudang')->row();         # Cek gudang aktif dari gudang utama
    
                          
                          # Item racikan kumpulkan dahulu disini
                          if(!empty($medc_det->resep)){                          
                              foreach (json_decode($medc_det->resep) as $rc){
                                  $sql_item_rc          = $this->db->where('id', $rc->id_item)->get('tbl_m_produk')->row();
                                  $sql_gudang_stok_rc   = $this->db->where('id_gudang', $sql_gudang->id)->where('id_produk', $sql_item_rc->id)->get('tbl_m_produk_stok')->row();
                                  
                                  # Cek resep Item stockable atau tidak ? 
                                  if($sql_item_rc->status_subt == '1'){
                                      $jml_akhir_rc         = $sql_item_rc->jml - $rc->jml;
                                      $jml_akhir_stk        = $sql_gudang_stok_rc->jml - $rc->jml;
                                      
                                      $data_item_rc = array(
                                          'tgl_modif'  => date('Y-m-d H:i:s'),
                                          'jml'        => $jml_akhir_rc,      ($jml_akhir_rc < 0 ? 0 : (int) $jml_akhir_rc)
                                      );
                                      
                                      $data_penj_hist_rc = array(
                                          'tgl_simpan'    => $rc->tgl_simpan,
                                          'tgl_masuk'     => $sql_medc->tgl_masuk,
                                          'id_gudang'     => $sql_gudang->id,
                                          'id_pelanggan'  => $sql_medc->id_pasien,
                                          'id_produk'     => $sql_item_rc->id,
                                          'id_user'       => $this->ion_auth->user()->row()->id,
                                          'id_penjualan'  => $sql_medc->id,
                                          'no_nota'       => $no_nota,
                                          'kode'          => $sql_item_rc->kode,
                                          'produk'        => $sql_item_rc->produk,
                                          'keterangan'    => $sql_medc->pasien.' - '.$medc_det->item,
                                          'jml'           => (int)$rc->jml,
                                          'jml_satuan'    => (int)$sql_satuan->jml,
                                          'satuan'        => $sql_satuan->satuanTerkecil,
                                          'nominal'       => (float)$rc->harga,
                                          'status'        => '4'
                                      );
                                      
                                      # Kurangi stok di database item yang relate ke racikan
                                      $this->db->where('id', $rc->id_item)->update('tbl_m_produk', $data_item_rc);
                                      
                                      # Simpan ke tabel riwayat produk
                                      $this->db->insert('tbl_m_produk_hist', $data_penj_hist_rc);
                                      
                                  }
                              }
                          }                      
                          # -- END OF RACIKAN
                          
                          # Cek Item Produk non resep stockable
                          if($sql_item->status_subt == '1'){
                                $sql_gudang_stok = $this->db->where('id_gudang', $sql_gudang->id)->where('id_produk', $sql_item->id)->get('tbl_m_produk_stok')->row();
                                $jml_akhir       = $sql_item->jml - $medc_det->jml;
                                $jml_akhir_stk   = $sql_gudang_stok->jml - $medc_det->jml;
                                                    
                                $data_item = array(
                                    'tgl_modif'  => date('Y-m-d H:i'),
                                    'jml'        => $jml_akhir,      ($jml_akhir < 0 ? 0 : (int) $jml_akhir)
                                );
                                
                                $data_penj_hist = array(
                                    'tgl_simpan'    => $medc_det->tgl_simpan,
                                    'tgl_masuk'     => $sql_medc->tgl_masuk,
                                    'id_gudang'     => $sql_gudang->id,
                                    'id_pelanggan'  => $sql_medc->id_pasien,
                                    'id_produk'     => $sql_item->id,
                                    'id_user'       => $this->ion_auth->user()->row()->id,
                                    'id_penjualan'  => $sql_medc->id,
                                    'no_nota'       => $no_nota,
                                    'kode'          => $sql_item->kode,
                                    'produk'        => $sql_item->produk,
                                    'keterangan'    => $sql_medc->pasien,
                                    'jml'           => (int)$medc_det->jml,
                                    'jml_satuan'    => (int)$sql_satuan->jml,
                                    'satuan'        => $sql_satuan->satuanTerkecil,
                                    'nominal'       => (float)$medc_det->harga,
                                    'status'        => '4'
                                );
                                
                                # Kurangi Stok Item Stockable Non Racikan
                                $this->db->where('id', $sql_item->id)->update('tbl_m_produk', $data_item);
                                
                                # Simpan ke tabel riwayat produk
                                $this->db->insert('tbl_m_produk_hist', $data_penj_hist);
                          }                      
                          # -- END OF ITEM
                          
                          # Jika punya refrensi item, maka jabarkan dulu
                            if($sql_item_ref->num_rows() > 0){
                                foreach ($sql_item_ref->result() as $reff){
                                    $sql_item_rf      = $this->db->where('id', $reff->id_produk_item)->get('tbl_m_produk')->row();
                                  
                                    # Cek apakah stockabel
                                    if($sql_item_rf->status_subt == '1'){
                                        $jml_akhir_reff   = $sql_item2->jml - ($reff->jml * $medc_det->jml);
                                  
                                        $data_item_reff = array(
                                            'tgl_modif'  => date('Y-m-d H:i:s'),
                                            'jml'        => $jml_akhir_reff
                                        );
                                      
                                        $data_penj_hist_rf = array(
                                            'tgl_simpan'    => $medc_det->tgl_simpan,
                                            'tgl_masuk'     => $sql_medc->tgl_masuk,
                                            'id_gudang'     => $sql_gudang->id,
                                            'id_pelanggan'  => $sql_medc->id_pasien,
                                            'id_produk'     => $sql_item_rf->id,
                                            'id_user'       => $this->ion_auth->user()->row()->id,
                                            'id_penjualan'  => $sql_medc->id,
                                            'no_nota'       => $no_nota,
                                            'kode'          => $sql_item_rf->kode,
                                            'produk'        => $sql_item_rf->produk,
                                            'keterangan'    => $sql_medc->pasien.' - REFERENCE ITEM',
                                            'jml'           => (int)$reff->jml,
                                            'jml_satuan'    => (int)$sql_satuan->jml,
                                            'satuan'        => $sql_satuan->satuanTerkecil,
                                            'nominal'       => (float)$rc->harga,
                                            'status'        => '4'
                                        );
                                                                            
                                        # Kurangi stok, untuk item referensinya jika statusnya stockable
                                        $this->db->where('id', $sql_item_rf->id)->update('tbl_m_produk', $data_item_reff);
                                        
                                        # Simpan te tabel riwayat produk
                                        $this->db->insert('tbl_m_produk_hist', $data_penj_hist_rf);
                                    }
                                }
                            }
                          
                        # Kalau remun tidak kosong, maka lakukan simpan
                        # Remun untuk menghitung pendapatan dokter                        
                        if($sql_item->remun_tipe > 0){
                            $sql_cek_remun = $this->db
                                                    ->where('id_medcheck', $sql_medc->id)
                                                    ->where('id_medcheck_det', $medc_det->id)
                                                    ->where('id_dokter', $medc_det->id_dokter)
                                                    ->where('id_item', $sql_item->id)
                                                    ->get('tbl_trans_medcheck_remun');
                            
                            # Tentukan remun tipenya dan hitung total remunnya
                            $remun      = ($sql_item->remun_tipe == '2' ? $sql_item->remun_nom : (($sql_item->remun_perc / 100) * $medc_det->harga));
                            $remun_tot  = $remun * $medc_det->jml;
                            
                            $data_remun = array(
                                'id_medcheck'       => (int)$sql_medc->id,
                                'id_medcheck_det'   => (int)$medc_det->id,
                                'id_dokter'         => (int)$medc_det->id_dokter,
                                'id_item'           => (int)$sql_item->id,
                                'tgl_simpan'        => date('Y-m-d H:i:s'),
                                'item'              => $sql_item->produk,
                                'harga'             => (float)$medc_det->harga,
                                'jml'               => (float)$medc_det->jml,
                                'remun_perc'        => (float)$sql_item->remun_perc,
                                'remun_nom'         => (float)$sql_item->remun_nom,
                                'remun_tipe'        => (int)$sql_item->remun_tipe,
                                'remun_subtotal'    => (float)$remun_tot,
                            );
                            
                            # Cek jika ada tidak ada value kembar
                            if($sql_cek_remun->num_rows() == 0){
                                # Simpan ke tabel Remun
                                $this->db->insert('tbl_trans_medcheck_remun', $data_remun);
                            }
                        }
                          
                        # Kalau apresiasi tidak kosong, maka lakukan simpan
                        # Apresiasi untuk menghitung pendapatan dari lab
                        if($sql_item->apres_tipe > 0){
                            $sql_cek_apres = $this->db
                                                    ->where('id_medcheck', $sql_medc->id)
                                                    ->where('id_medcheck_det', $medc_det->id)
                                                    ->where('id_dokter', $medc_det->id_dokter)
                                                    ->where('id_item', $sql_item->id)
                                                    ->get('tbl_trans_medcheck_apres');
                            
                            # Tentukan apresiasi tipenya dan hitung total remunnya
                            $apres      = ($sql_item->apres_tipe == '2' ? $sql_item->apres_nom : (($sql_item->apres_perc / 100) * $medc_det->harga));
                            $apres_tot  = $apres * $medc_det->jml;
                            
                            $data_apres = array(
                                'id_medcheck'       => (int)$sql_medc->id,
                                'id_medcheck_det'   => (int)$medc_det->id,
                                'id_dokter'         => (int)$medc_det->id_dokter,
                                'id_item'           => (int)$sql_item->id,
                                'tgl_simpan'        => date('Y-m-d H:i:s'),
                                'item'              => $sql_item->produk,
                                'harga'             => (float)$medc_det->harga,
                                'jml'               => (float)$medc_det->jml,
                                'apres_perc'        => (float)$sql_item->apres_perc,
                                'apres_nom'         => (float)$sql_item->apres_nom,
                                'apres_tipe'        => (int)$sql_item->apres_tipe,
                                'apres_subtotal'    => (float)$apres_tot,
                            );
                            
                            # Cek jika ada tidak ada value kembar
                            if($sql_cek_apres->num_rows() == 0){
                                # Simpan ke tabel Remun
                                $this->db->insert('tbl_trans_medcheck_apres', $data_apres);
                            }
                        }
                    }
                    
//                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil di proses !!</div>');
                }else{
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Transaksi sudah pernah di proses !!</div>');
                }
                
                $this->db->trans_complete();
                
//                # Kirim pesan gagal atau sukses
//                if ($this->db->trans_status() === FALSE) {
//                    # Rollback
//                    $this->db->trans_rollback();
//
//                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Transaksi gagal di proses !!</div>');
//                } else {
//                    # Complete
//                    $this->db->trans_commit();
//
//                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil di proses !!</div>');
//                }
                
                redirect(base_url('medcheck/index.php?tipe='.$sql_medc->tipe));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_proses_batal(){
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $status     = $this->input->post('status');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                );

                $this->session->set_flashdata('anamnesa', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id));
            } else {
                $sql_medc       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_medc_det   = $this->db->select('SUM(potongan) AS potongan, SUM(diskon) AS diskon, SUM(subtotal) AS subtotal')->where('id_medcheck', $sql_medc->id)->get('tbl_trans_medcheck_det')->row();   
                $sql_medc_det2  = $this->db->where('id_medcheck', $sql_medc->id)->get('tbl_trans_medcheck_det')->result(); 
                $pengaturan     = $this->db->get('tbl_pengaturan')->row();
                
                $jml_total      = $sql_medc_det->subtotal + $sql_medc_det->potongan + $sql_medc_det->diskon;
                
                $data = array(
                    'tgl_modif'     => date('Y-m-d H:i:s'),
                    'jml_total'     => $jml_total,
                    'jml_potongan'  => 0,
                    'jml_diskon'    => 0,
                    'diskon'        => 0,
                    'jml_subtotal'  => 0,
                    'jml_gtotal'    => 0,
                    'status'        => '2',
                );
                
                
                # Transactional Database
                // $this->db->query('SET autocommit = 0;');
                $this->db->trans_start();
                
                # Update data nota dll
                $this->db->where('id', general::dekrip($id))->update('tbl_trans_medcheck', $data);           
                  
                foreach ($sql_medc_det2 as $medc_det){
                      $sql_item        = $this->db->where('id', $medc_det->id_item)->get('tbl_m_produk')->row();
                      $sql_item_ref    = $this->db->where('id_produk', $sql_item->id)->get('tbl_m_produk_ref');   
                      $sql_satuan      = $this->db->where('id', $sql_item->id_satuan)->get('tbl_m_satuan')->row();
                      $sql_gudang      = $this->db->where('status', '1')->get('tbl_m_gudang')->row();    // Cek gudang aktif dari gudang utama

                      
                      # Item racikan kumpulkan dahulu disini
                      if(!empty($medc_det->resep)){                          
                          foreach (json_decode($medc_det->resep) as $rc){
                              $sql_item_rc          = $this->db->where('id', $rc->id_item)->get('tbl_m_produk')->row();
                              $sql_gudang_stok_rc   = $this->db->where('id_gudang', $sql_gudang->id)->where('id_produk', $sql_brg->id)->get('tbl_m_produk_stok')->row();
                              
                              # Cek resep Item stockable atau tidak ? 
                              if($sql_item_rc->status_subt == '1'){
                                  $jml_akhir_rc         = $sql_item_rc->jml + $rc->jml;
                                  $jml_akhir_stk        = $sql_gudang_stok_rc->jml + $rc->jml;
                                  
                                  $data_item_rc = array(
                                      'tgl_modif'  => date('Y-m-d H:i:s'),
                                      'jml'        => ($jml_akhir_rc < 0 ? 0 : (int) $jml_akhir_rc)
                                  );
                                  
                                  # Balikin stok di database item yang relate ke racikan
                                  $this->db->where('id', $rc->id_item)->update('tbl_m_produk', $data_item_rc);
                                  
                                  # Hapus ke tabel riwayat produk
                                  $this->db->where('id_penjualan', $sql_medc->id)->where('id_produk', $sql_item_rc->id)->delete('tbl_m_produk_hist');
                                  
                              }
                          }
                      }                      
                      # -- END OF RACIKAN
                      
                      # Cek Item Produk non resep stockable
                      if($sql_item->status_subt == '1'){
                            $sql_gudang_stok = $this->db->where('id_gudang', $sql_gudang->id)->where('id_produk', $sql_item->id)->get('tbl_m_produk_stok')->row();
                            $jml_akhir       = $sql_item->jml + $medc_det->jml;
                            $jml_akhir_stk   = $sql_gudang_stok->jml + $medc_det->jml;
                                                
                            $data_item = array(
                                'tgl_modif'  => date('Y-m-d H:i'),
                                'jml'        => ($jml_akhir < 0 ? 0 : (int) $jml_akhir)
                            );
                            
                            # Balikin Stok Item Stockable Non Racikan
                            $this->db->where('id', $sql_item->id)->update('tbl_m_produk', $data_item);
                            
                            # Hapus ke tabel riwayat produk
                            $this->db->where('id_penjualan', $sql_medc->id)->where('id_produk', $sql_item->id)->delete('tbl_m_produk_hist');
                      }                      
                      # -- END OF ITEM
                      
                      # Jika punya refrensi item, maka jabarkan dulu
                        if($sql_item_ref->num_rows() > 0){
                            foreach ($sql_item_ref->result() as $reff){
                                $sql_item_rf      = $this->db->where('id', $reff->id_produk_item)->get('tbl_m_produk')->row();
                              
                                # Cek apakah stockabel
                                if($sql_item_rf->status_subt == '1'){
                                    $jml_akhir_reff   = $sql_item2->jml + ($reff->jml * $medc_det->jml);
                              
                                    $data_item_reff = array(
                                        'tgl_modif'  => date('Y-m-d H:i:s'),
                                        'jml'        => $jml_akhir_reff
                                    );
                                                                        
                                    # Balikin stok, untuk item referensinya jika statusnya stockable
                                    $this->db->where('id', $reff->id_produk_item)->update('tbl_m_produk', $data_item_reff);
                                    
                                    # Hapus te tabel riwayat produk
                                    $this->db->where('id_penjualan', $sql_medc->id)->where('id_produk', $sql_item_rf->id)->delete('tbl_m_produk_hist');
                                }
                            }
                        }
                      
                    // Kalau remun tidak kosong, maka lakukan simpan
                    if($sql_item->remun_tipe > 0){
                        $sql_cek_remun = $this->db
                                                ->where('id_medcheck', $sql_medc->id)
                                                ->where('id_medcheck_det', $medc_det->id)
                                                ->where('id_dokter', $medc_det->id_dokter)
                                                ->where('id_item', $sql_item->id)
                                                ->get('tbl_trans_medcheck_remun');
                        
                        # Tentukan remun tipenya dan hitung total remunnya
                        $remun      = ($sql_item->remun_tipe == '2' ? $sql_item->remun_nom : (($sql_item->remun_perc / 100) * $medc_det->harga));
                        $remun_tot  = $remun * $medc_det->jml;
                        
                        $data_remun = array(
                            'id_medcheck'       => (int)$sql_medc->id,
                            'id_medcheck_det'   => (int)$medc_det->id,
                            'id_dokter'         => (int)$medc_det->id_dokter,
                            'id_item'           => (int)$sql_item->id,
                            'tgl_simpan'        => date('Y-m-d H:i:s'),
                            'item'              => $sql_item->produk,
                            'harga'             => (float)$medc_det->harga,
                            'jml'               => (float)$medc_det->jml,
                            'remun_perc'        => (float)$sql_item->remun_perc,
                            'remun_nom'         => (float)$sql_item->remun_nom,
                            'remun_tipe'        => (int)$sql_item->remun_tipe,
                            'remun_subtotal'    => (float)$remun_tot,
                        );
                        
                        # Cek jika ada tidak ada value kembar
                        if($sql_cek_remun->num_rows() > 0){
                            # Hapus ke tabel Remun
                            $this->db->where('id', $sql_cek_remun->row()->id)->delete('tbl_trans_medcheck_remun');
                            
                        }
                    }
                }
                
                # Trans Complete
                $this->db->trans_complete();
                
                # Kirim pesan gagal atau sukses
                if ($this->db->trans_status() === FALSE) {
                    # Rollback
                    $this->db->trans_rollback();
                    
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Transaksi gagal di proses !!</div>');
                }else{
                    # Complete
                    $this->db->trans_commit();
                     
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil di proses !!</div>');
                }
                
                redirect(base_url('medcheck/tindakan.php?id='.general::enkrip($sql_medc->id)));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_proses_farm() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_resep   = $this->input->post('id_resep');
            $status     = $this->input->post('status');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                );

                $this->session->set_flashdata('anamnesa', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id));
            } else {
                $sql_medc_resep  = $this->db->where('id_medcheck', general::dekrip($id))->where('id_resep', general::dekrip($id_resep))->get('tbl_trans_medcheck_resep_det');
                
                // Cek Barang Form
                if($sql_medc_resep->num_rows() > 0){
                    # Transaksi Start
                    $this->db->trans_start();
                    
                    # Delete Resep
                    $this->db->where('id_resep', general::dekrip($id_resep))->delete('tbl_trans_medcheck_det');
                    
                    foreach ($sql_medc_resep->result() as $cart){
                        $sql_racikan    = $this->db->select('SUM(subtotal) AS harga')->where('id_resep_det', $cart->id)->get('tbl_trans_medcheck_resep_det_rc')->row();
                        $harga          = $sql_racikan->harga + $cart->harga;
                        $subtotal       = $sql_racikan->harga + ($cart->jml * $cart->harga);
                        
                        $data_res_det = array(
                            'id_medcheck'   => (int)$cart->id_medcheck,
                            'id_resep'      => (int)$cart->id_resep,
                            'id_item'       => (int)$cart->id_item,
                            'id_item_kat'   => (int)$cart->id_item_kat,
                            'id_item_sat'   => (int)$cart->id_item_sat,
                            'id_user'       => (int)$cart->id_user,
                            'tgl_simpan'    => $cart->tgl_simpan,
                            'tgl_modif'     => date('Y-m-d H:i:s'),
                            'kode'          => $cart->kode,
                            'item'          => $cart->item,
                            'dosis'         => $cart->dosis,
                            'dosis_ket'     => $cart->dosis_ket,
                            'resep'         => $cart->resep,
                            'harga'         => (float)$harga,
                            'jml'           => (float)$cart->jml,
                            'jml_satuan'    => (int)$cart->jml_satuan,
                            'subtotal'      => (float)$subtotal,
                            'satuan'        => $cart->satuan,
                            'status_pj'     => $cart->status_pj,
                            'status'        => '4',
                        );
                        
                        # Simpan Resep
                        $this->db->insert('tbl_trans_medcheck_det', $data_res_det);
                    }

                    # Update Resep
                    $this->db->where('id', general::dekrip($id_resep))->update('tbl_trans_medcheck_resep', array('status'=>'4'));
                    $this->session->set_flashdata('medcheck_toast', 'toastr.success("Resep berhasil di proses");');

                    # Trans Complete
                    $this->db->trans_complete();
                }
                
                redirect(base_url('medcheck/tambah.php?act=res_input&id='.$id.'&id_resep='.$id_resep.'&status='.$status));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_proses_farm_batal() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_resep   = $this->input->post('id_resep');
            $status     = $this->input->post('status');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                );

                $this->session->set_flashdata('anamnesa', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id));
            } else {
                $sql_medc_resep  = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_resep_det');
                
                // Cek Barang Form
                if($sql_medc_resep->num_rows() > 0){
                    crud::delete('tbl_trans_medcheck_det','id_resep', general::dekrip($id_resep));
//                    $del = $this->db->where('id_resep', general::dekrip($id_resep))->get('tbl_trans_medcheck_det');

                    crud::update('tbl_trans_medcheck_resep', 'id', general::dekrip($id_resep), array('status'=>'2'));
                    $this->session->set_flashdata('medcheck_toast', 'toastr.error("Resep berhasil di batalkan");');
                }
                
                redirect(base_url('medcheck/tambah.php?act=res_input&id='.$id.'&id_resep='.$id_resep.'&status='.$status));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    

    public function set_medcheck_bayar() {
        if (akses::aksesLogin() == TRUE) {
            $no_nota    = general::dekrip($this->input->post('no_nota'));
            $tgl_byr    = explode('/',$this->input->post('tgl_bayar'));
            $metode_byr = $this->input->post('metode_bayar');
            $bank       = $this->input->post('bank');
            $no_kartu   = $this->input->post('no_kartu');
            $jml_gtotal = str_replace('.', '', $this->input->post('jml_gtotal'));
            $disc       = $this->input->post('diskon');
            $jml_diskon = str_replace('.', '', $this->input->post('jml_diskon'));
            $jml_bayar  = str_replace('.', '', $this->input->post('jml_bayar'));
            $jml_biaya  = str_replace('.', '', $this->input->post('jml_biaya'));

            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('no_nota', 'ID Transaksi', 'required');
            $this->form_validation->set_rules('jml_bayar', 'Jml Bayar', 'required');
            $this->form_validation->set_rules('metode_bayar', 'Metode Pembayaran', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'no_nota' => form_error('no_nota'),
                    'bayar'   => form_error('jml_bayar'),
                    'metode'  => form_error('metode_bayar'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/invoice/bayar.php?id='.general::enkrip($no_nota)));
            } else {
                $tgl_bayar      = date('Y-m-d');
                $sql_cek        = $this->db->where('id', $no_nota)->get('tbl_trans_medcheck')->row();
                $sql_cek_plat   = $this->db->where('id_medcheck', $sql_cek->id)->get('tbl_trans_medcheck_plat')->num_rows();
                $sql_plat       = $this->db->where('id', $metode_byr)->get('tbl_m_platform')->row();
                $sql_medc_det   = $this->db->where('id_medcheck', $sql_cek->id)->get('tbl_trans_medcheck_det');
                $jml_kurang     = $jml_gtotal - $jml_bayar;
                $jml_kembali    = $jml_bayar - $jml_gtotal;
                $diskon         = number_format((($sql_cek->jml_total - $jml_gtotal) / $sql_cek->jml_total) * 100, 2);
                $jml_tot_disk   = $sql_cek->jml_total - $jml_gtotal;
                $ppn            = ($sql_cek->status_ppn == '1' ? $sql_cek->ppn : '0');
                $jml_ppn        = ($sql_cek->status_ppn == '1' ? ($sql_cek->ppn / 100) * $jml_gtotal : '0');
                $gtotal         = ($sql_cek->status_ppn == '1' ? $jml_gtotal + $jml_ppn : $jml_gtotal);
                
                // Simpan platform pembayaran
                $data_platform = array(
                    'tgl_simpan'  => $tgl_bayar.' '.date('H:i'),
                    'id_platform' => $metode_byr,
                    'id_medcheck' => $no_nota,
                    'no_nota'     => $sql_cek->no_nota,
                    'platform'    => (!empty($bank) ? $bank : '-'),
                    'keterangan'  => (!empty($no_kartu) ? $no_kartu : ''),
                    'nominal'     => (float)$jml_bayar,
                );
                
                # Ceking jika sudah pernah di posting, maka tidak bisa posting
                if($sql_cek->status == 5){
    
                    /* Kalo pembayaran kurang */
                    if($sql_cek->status_bayar > 1){
                        $jml_tot_bayar  = $sql_cek->jml_bayar + $jml_bayar;
                        $jml_tot_biaya  = $sql_cek->jml_biaya + $jml_biaya;
                        $jml_tot_gtot   = $sql_cek->jml_gtotal + $jml_biaya;
                        $jml_sisa_bayar = $sql_cek->jml_kurang - $jml_bayar;
                        $jml_sisa_kmbli = $jml_tot_bayar - $sql_cek->jml_gtotal;
    
                        if($jml_sisa_bayar <= 0){
                            $trans = array(
                                'id_kasir'     => $this->ion_auth->user()->row()->id,
                                'tgl_bayar'    => $tgl_bayar.' '.date('H:i:s'),
                                'tgl_modif'    => date('Y-m-d H:i:s'),
                                'jml_gtotal'   => (float)$jml_tot_gtot,
                                'jml_bayar'    => $jml_tot_bayar,
                                'jml_kurang'   => (int)($jml_sisa_bayar < 0 ? 0 : $jml_sisa_bayar),
                                'jml_kembali'  => (int)($jml_sisa_kmbli < 0 ? 0 : $jml_sisa_kmbli),
                                'status_bayar' => '1',
                                'status_nota'  => '3',
                                'metode'	   => $metode_byr,
                             );
    
                             # Update Nota Penjualan grand total perhitungan
                             $this->db->where('id', $no_nota)->update('tbl_trans_medcheck', $trans);
                            
                             # Simpan ke platfom pembayaran
                             $this->db->insert('tbl_trans_medcheck_plat', $data_platform);
                             
                             # Redirected
                             $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil dibayar !!</div>');
                             redirect(base_url('medcheck/invoice/detail.php?id='.general::enkrip($no_nota).'#jml_bayar')); 
                        }else{
                            $trans = array(
                                'tgl_bayar'    => $tgl_bayar.' '.date('H:i:s'),
                                'tgl_modif'    => date('Y-m-d H:i:s'),
                                'jml_gtotal'   => (float)$jml_tot_gtot,
                                'jml_bayar'    => $jml_tot_bayar,
                                'jml_kurang'   => (int)$jml_sisa_bayar,
                                'metode'	   => $metode_byr,
                             );
    
                             # update nota
                             $this->db->where('id', $no_nota)->update('tbl_trans_medcheck', $trans);
                            
                             # Simpan ke platfom pembayaran
                             $this->db->insert('tbl_trans_medcheck_plat', $data_platform);
                             
                             $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil dibayar !!</div>');
                             redirect(base_url('medcheck/invoice/bayar.php?id='.general::enkrip($no_nota).'#jml_bayar'));
                        }
                    }else{
                        /* Cek Pembayaran jika kurang, otomatis menjadi DP */
                        if($jml_bayar < $jml_gtotal){
                            $trans = array(
                                'tgl_bayar'    => $tgl_bayar.' '.date('H:i:s'),
                                'tgl_modif'    => date('Y-m-d H:i:s'),
                                'jml_subtotal' => (float)$jml_gtotal,
                                'diskon'       => (float)$diskon,
                                'jml_diskon'   => (float)$jml_tot_disk,
                                'jml_gtotal'   => (float)$gtotal,
                                'jml_bayar'    => (float)$jml_bayar,
                                'jml_kurang'   => (int)$jml_kurang,
                                'status_bayar' => '2',
                                'metode'	   => $metode_byr,
                             );
    
                             # update nota
                             $this->db->where('id', $no_nota)->update('tbl_trans_medcheck', $trans);
    
                             # Simpan ke platfom pembayaran
                             $this->db->insert('tbl_trans_medcheck_plat', $data_platform);
    
                             redirect(base_url('medcheck/invoice/bayar.php?id='.general::enkrip($no_nota).'#jml_bayar'));
                        }else{
                            /* Jika jumlah pembayaran lunas */
                            $trans = array(
                                'id_kasir'     => $this->ion_auth->user()->row()->id,
                                'tgl_bayar'    => $tgl_bayar.' '.date('H:i:s'),
                                'tgl_modif'    => date('Y-m-d H:i:s'),
                                'jml_subtotal' => (float)$jml_gtotal,
                                'diskon'       => (float)$diskon,
                                'jml_diskon'   => (float)$jml_tot_disk,
                                'jml_gtotal'   => (float)$gtotal,
                                'jml_bayar'    => (float)$jml_bayar,
                                'jml_kembali'  => (float)$jml_kembali,
                                'status_bayar' => '1',
                                'status_nota'  => '3',
                                'metode' 	   => $metode_byr,
                             );
    
                             # update nota
                             $this->db->where('id', $no_nota)->update('tbl_trans_medcheck', $trans);
    
                             # Simpan ke platfom pembayaran
                             $this->db->insert('tbl_trans_medcheck_plat',$data_platform);
                             
                             $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil dibayar !!</div>');
                             redirect(base_url('medcheck/invoice/detail.php?id='.general::enkrip($no_nota)));
                        }
                    }
                }else{
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Transaksi gagal di proses !!</div>');
                    redirect(base_url('medcheck/index.php?tipe=1&filter_bayar=0'));
                }
                
//                echo '<pre>';
//                print_r($trans);
//                echo '</pre>';
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_bayar_batal() {
        if (akses::aksesLogin() == TRUE) {
            $no_nota    = general::dekrip($this->input->post('no_nota'));
            $id         = $this->input->post('id');
            $tgl_byr    = explode('/',$this->input->post('tgl_bayar'));
            $metode_byr = $this->input->post('metode_bayar');
            $bank       = $this->input->post('bank');
            $no_kartu   = $this->input->post('no_kartu');

            $pengaturan = $this->db->get('tbl_pengaturan')->row();
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID Transaksi', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id' => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tindakan.php?id='.$id));
            } else {
                $tgl_bayar      = date('Y-m-d');
                $sql_cek        = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                
                // Simpan platform pembayaran
                $data = array(
                    'jml_bayar'     => 0,
                    'jml_kurang'    => 0,
                    'jml_kembali'   => 0,
                    'status_bayar'  => '0',
                );
                
                # Transactional Database
                // $this->db->query('SET autocommit = 0;');
                $this->db->trans_start();
                
                # Hapus Platform Pembayaran
                $this->db->where('id_medcheck', $sql_cek->id)->delete('tbl_trans_medcheck_plat');
                                
                # Update jumlah bayar dll
                $this->db->where('id', $sql_cek->id)->update('tbl_trans_medcheck', $data); 
                
                # Trans Complete
                $this->db->trans_complete();
                
                # Kirim pesan gagal atau sukses
                if ($this->db->trans_status() === FALSE) {
                    # Rollback
                    $this->db->trans_rollback();
                    
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Transaksi gagal di proses !!</div>');
                }else{
                    # Complete
                    $this->db->trans_commit();
                     
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil di proses !!</div>');
                }

                redirect(base_url('medcheck/tindakan.php?id='.general::enkrip($sql_cek->id)));

//                    echo '<pre>';
//                    print_r($trans);
//                    echo '</pre>';
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_lab() {
        if (akses::aksesLogin() == TRUE) {
            $id     = $this->input->get('id');
            $status = $this->input->get('status');
            
            if(!empty($id)){
                $sql_medc   = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck'); 
                $pengaturan = $this->db->get('tbl_pengaturan')->row();
                   
                $nomer      = $this->db->where('MONTH(tgl_simpan)', date('m'))->get('tbl_trans_medcheck_lab')->num_rows() + 1;
                $no_surat   = sprintf('%03d', $nomer).'/'.$pengaturan->kode_surat.'/'.date('m').'/'.date('Y');
                $grup       = $this->ion_auth->get_users_groups()->row();
                $is_farm    = ($grup->name == 'analis' ? '2' : '0');
                $is_farm_id = ($grup->name == 'analis' ? $this->ion_auth->user()->row()->id : '0');
                $is_doc_id  = ($grup->name == 'dokter' ? $this->ion_auth->user()->row()->id : '0');
               
                $data = array(
                    'tgl_simpan'    => date('Y-m-d H:i:s'),
                    'tgl_masuk'     => date('Y-m-d H:i:s'),
                    'id_medcheck'   => $sql_medc->row()->id,
                    'id_pasien'     => $sql_medc->row()->id_pasien,
                    'id_user'       => $this->ion_auth->user()->row()->id,
                    'id_analis'     => $is_farm_id,
                    'id_dokter'     => $is_doc_id,
                    'no_lab'        => $no_surat,
                    'status'        => '0',
                    'status_cvd'    => '0',
                );
                
                if($sql_medc->num_rows() > 0){
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data permintaan lab di buat</div>');
                    crud::simpan('tbl_trans_medcheck_lab', $data);
                    $last_id = crud::last_id();
                    
                    redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->row()->id).'&id_lab='.general::enkrip($last_id).'&status='.$status));
                }else{
                    redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->row()->id).'&status='.$status));
                }
            }else{
                redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->row()->id).'&status='.$status));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_lab_upd() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_lab     = $this->input->post('id_lab');
            $tgl_masuk  = $this->input->post('tgl_masuk');
            $no_sample  = $this->input->post('no_sampel');
            $dokter     = $this->input->post('dokter');
            $ket        = $this->input->post('keterangan');
            $status     = $this->input->post('status');
            $is_cvd     = $this->input->post('status_cvd');
            $is_duplo   = $this->input->post('duplo');
            $act         = $this->input->post('act');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('no_sampel', 'No Sample', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                    'no_sampel' => form_error('no_sampel'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status.'&act='.$act.'&id_lab='.$id_lab));
            } else {
                $sql_medc       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row(); 
                $pengaturan     = $this->db->get('tbl_pengaturan')->row();
                
                $data = array(
                    'tgl_masuk'     => $this->tanggalan->tgl_indo_sys($tgl_masuk).' '.date('H:i:s'),
                    'tgl_modif'     => date('Y-m-d H:i:s'),
                    'id_dokter'     => $dokter,
                    'no_sample'     => $no_sample,
                    'status_cvd'    => $is_cvd,
                    'ket'           => $ket,
                    'status'        => '1',
                    'status_duplo'  => (!empty($is_duplo) ? $is_duplo : '0'),
                );
                
                crud::update('tbl_trans_medcheck_lab', 'id', general::dekrip($id_lab), $data);

                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data berhasil di update</div>');
                redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_lab_upd_hsl() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $status     = $this->input->post('status');
            $hasil      = $_POST['hasil_lab'];
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                );

                $this->session->set_flashdata('anamnesa', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id.'status='.$status));
            } else {
                $sql_medc       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row(); 
                $pengaturan     = $this->db->get('tbl_pengaturan')->row();
                
                $data = array(
                    'tgl_simpan'    => date('Y-m-d H:i:s'),
                    'tgl_masuk'     => (!empty($tgl_masuk) ? $this->tanggalan->tgl_indo_sys($tgl_masuk) : date('Y-m-d')),
                    'tgl_keluar'    => $this->tanggalan->tgl_indo_sys($tgl_keluar),
                    'tgl_kontrol'   => $this->tanggalan->tgl_indo_sys($tgl_kontrol),
                    'id_medcheck'   => $sql_medc->id,
                    'id_dokter'     => $sql_medc->id_dokter,
                    'id_pasien'     => $sql_medc->id_pasien,
                    'id_user'       => $this->ion_auth->user()->row()->id,
                    'no_surat'      => $no_surat,
                    'tb'            => (float)general::format_angka_db($tb),
                    'td'            => $td,
                    'bb'            => (float)general::format_angka_db($bb),
                    'bw'            => $bw,
                    'jml_hari'      => (float)$jml_hari,
                    'hasil'         => $hasil,
                    'tipe'          => $tipe_surat
                );
                
                foreach ($hasil as $key => $hsl){
                    $item_id    = general::dekrip($key);
                    $item_lab   = $hsl;
                    
                    $data_lab = array(
                        'id_analis'     => $this->ion_auth->user()->row()->id,
                        'tgl_modif'     => date('Y-m-d H:i:s'),
                        'hasil_lab'     => $item_lab,
                    );
                    
                    crud::update('tbl_trans_medcheck_det', 'id', $item_id, $data_lab);
                }

                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Hasil lab berhasil di simpan</div>');
                redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_lab_print() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_lab     = $this->input->post('id_lab');
            $status     = $this->input->post('status');
            $act        = $this->input->post('act');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                );

                $this->session->set_flashdata('anamnesa', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id.'status='.$status));
            } else {
                $sql_medc       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row(); 
                $pengaturan     = $this->db->get('tbl_pengaturan')->row();
                
                $this->db->where('status', '3')->where('id_medcheck', $sql_medc->id)->update('tbl_trans_medcheck_det', array('status_ctk' => '0'));
                $this->session->unset_userdata('lab_print');
                
                foreach ($_POST['print'] as $key => $print){
                    $cetak[] = array(
                        'id'            => $key,
                        'id_lab'        => $_POST['print_lab'][$key],
                        'id_lab_hsl'    => $_POST['print_lab_hsl'][$key],
                        'id_kat'        => $_POST['print_kat'][$key],
                        'value'         => $_POST['print'][$key],
                    );
                    
                    $this->db->where('id', $key)->update('tbl_trans_medcheck_det', array('status_ctk' => $_POST['print'][$key]));
                }
                
//                echo '<pre>';
//                print_r($cetak);
//                echo '</pre>';
                
                $this->session->set_userdata('lab_print', $cetak);

                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Hasil lab berhasil di simpan</div>');
                redirect(base_url('medcheck/surat/cetak_pdf_lab.php?id='.$id.'&id_lab='.$id_lab));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_lab_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $item_id    = $this->input->get('item_id');
            $status     = $this->input->get('status');
            $userid     = $this->ion_auth->user()->row()->id;
            
            $sql_medc = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck');
            
            if($sql_medc->num_rows() > 0){
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil dihapus</div>');
//                crud::delete('tbl_trans_medcheck_det', 'id_lab', general::dekrip($item_id));
//                crud::delete('tbl_trans_medcheck_lab', 'id', general::dekrip($item_id));
                
                /* Transaksi Database */
                $this->db->trans_begin();

                # Hapus ke tabel medcheck det
                $this->db->where('id_lab', general::dekrip($item_id))->delete('tbl_trans_medcheck_det');
                $this->db->where('id_lab', general::dekrip($item_id))->delete('tbl_trans_medcheck_lab_hsl');
                
                # Hapus ke tabel medcheck lab
                $this->db->where('id', general::dekrip($item_id))->delete('tbl_trans_medcheck_lab');

                # Cek status transact MySQL
                if ($this->db->trans_status() === FALSE) {
                    # Rollback jika gagal
                    $this->db->trans_rollback();

                    # Tampilkan pesan error
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Data lab gagal dihapus !!</div>');
                } else {
                    $this->db->trans_commit();

                    # Tampilkan pesan sukses jika sudah berhasil commit
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data lab berhasil dihapus !!</div>');
                }
            }

            redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_rad() {
        if (akses::aksesLogin() == TRUE) {
            $id     = $this->input->get('id');
            $status = $this->input->get('status');
            
            if(!empty($id)){
                $sql_medc   = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck'); 
                $pengaturan = $this->db->get('tbl_pengaturan')->row();
                   
                $nomer      = $this->db->where('MONTH(tgl_simpan)', date('m'))->get('tbl_trans_medcheck_rad')->num_rows() + 1;
                $no_surat   = sprintf('%03d', $nomer).'/'.$pengaturan->kode_rad.'/'.date('m').'/'.date('Y');
                $grup       = $this->ion_auth->get_users_groups()->row();
                $is_rad     = ($grup->name == 'radiografer' ? '2' : '0');
                $is_rad_id  = ($grup->name == 'radiografer' ? $this->ion_auth->user()->row()->id : '0');
                $is_doc_id  = ($grup->name == 'dokter' ? $this->ion_auth->user()->row()->id : '0');
               
                $data = array(
                    'tgl_simpan'    => date('Y-m-d H:i:s'),
                    'tgl_masuk'     => date('Y-m-d H:i:s'),
                    'id_medcheck'   => $sql_medc->row()->id,
                    'id_pasien'     => $sql_medc->row()->id_pasien,
                    'id_user'       => $this->ion_auth->user()->row()->id,
                    'id_radiografer'=> $is_rad_id,
                    'id_dokter'     => $is_doc_id,
                    'no_rad'        => $no_surat,
                    'no_sample'     => '-',
                    'status'        => '0',
                );
                
                if($sql_medc->num_rows() > 0){
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data permintaan radiologi di buat</div>');
                    crud::simpan('tbl_trans_medcheck_rad', $data);
                    $last_id = crud::last_id();
                    
                    redirect(base_url('medcheck/tambah.php?act='.($grup->name == 'dokter' ? 'rad_input' : 'rad_surat').'&id='.general::enkrip($sql_medc->row()->id).'&id_rad='.general::enkrip($last_id).'&status='.$status));
                }else{
                    redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->row()->id).'&status='.$status));
                }
                
//                echo '<pre>';
//                print_r($data);
//                
//                if($sql_medc->num_rows() > 0){
//                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data permintaan lab di buat</div>');
//                    crud::simpan('tbl_trans_medcheck_lab', $data);
//                    $last_id = crud::last_id();
//                    
//                    redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->row()->id).'&id_lab='.general::enkrip($last_id).'&status='.$status));
//                }else{
//                    redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->row()->id).'&status='.$status));
//                }
            }else{
//                redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->row()->id).'&status='.$status));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_rad_upd() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_rad     = $this->input->post('id_rad');
            $id_user    = $this->input->post('id_user');
            $status     = $this->input->post('status');
            $status_rad = $this->input->post('status_rad');
            $no_sample  = $this->input->post('no_sampel');
            $dokter     = $this->input->post('dokter');
            $dokter_rad = $this->input->post('dokter_kirim');
            $dokter_nm  = $this->input->post('dokter_kirim_nm');
            $tipe_dr    = $this->input->post('tipe_dokter');
            $kesan      = $this->input->post('kesan');
            $route      = $this->input->post('route');
            $act        = $this->input->post('act');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');
//            $this->form_validation->set_rules('tipe_dokter', 'Tipe Dokter', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'            => form_error('id'),
//                    'tipe_dokter'   => form_error('tipe_dokter'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?act=rad_surat&id='.$id.'&id_rad='.$id_rad.'&status='.$status));
            } else {
                $sql_medc_rad = $this->db->where('id', general::dekrip($id_rad))->get('tbl_trans_medcheck_rad')->row();

                $data = array(
                    'tgl_modif'         => date('Y-m-d H:i:s'),
                    'id_dokter'         => $dokter,
                    'id_dokter_kirim'   => $dokter_rad,
                    'id_radiografer'    => (!empty($id_user) ? $id_user : $this->ion_auth->user()->row()->id),
                    'dokter_kirim'      => $dokter_nm,
                    'no_sample'         => $no_sample,
                    'ket'               => $kesan,
                    'status'            => $status_rad,
                    'status_dokter_krm' => $tipe_dr,
                );
                
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data radiologi berhasil disimpan</div>');
                crud::update('tbl_trans_medcheck_rad', 'id', $sql_medc_rad->id, $data);
                
                redirect(base_url('medcheck/tambah.php?act=rad_surat&id='.$id.'&id_rad='.general::enkrip($sql_medc_rad->id).'&status='.$status.(!empty($route) ? '&route='.$route : '')));                
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_rad_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $item_id    = $this->input->get('item_id');
            $status     = $this->input->get('status');
            $userid     = $this->ion_auth->user()->row()->id;
            
            $sql_medc = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck');
            
            if($sql_medc->num_rows() > 0){
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil dihapus</div>');
                
                /* Transaksi Database */
                $this->db->trans_begin();

                # Hapus ke tabel medcheck det
                $this->db->where('id_rad', general::dekrip($item_id))->delete('tbl_trans_medcheck_det');
                $this->db->where('id_rad', general::dekrip($item_id))->delete('tbl_trans_medcheck_rad_det');
                $this->db->where('id_rad', general::dekrip($item_id))->delete('tbl_trans_medcheck_rad_file');
                
                # Hapus ke tabel medcheck lab
                $this->db->where('id', general::dekrip($item_id))->delete('tbl_trans_medcheck_rad');

                # Cek status transact MySQL
                if ($this->db->trans_status() === FALSE) {
                    # Rollback jika gagal
                    $this->db->trans_rollback();

                    # Tampilkan pesan error
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Data lab gagal dihapus !!</div>');
                } else {
                    $this->db->trans_commit();

                    # Tampilkan pesan sukses jika sudah berhasil commit
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data lab berhasil dihapus !!</div>');
                }
            }

            redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_mcu() {
        if (akses::aksesLogin() == TRUE) {
            $id     = $this->input->get('id');
            $status = $this->input->get('status');
            
            if(!empty($id)){
                $sql_medc   = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck'); 
                $pengaturan = $this->db->get('tbl_pengaturan')->row();
                   
                $nomer      = $this->db->where('MONTH(tgl_simpan)', date('m'))->get('tbl_trans_medcheck_mcu')->num_rows() + 1;
                $no_surat   = sprintf('%03d', $nomer).'/MCU/'.date('m').'/'.date('Y');
                $grup       = $this->ion_auth->get_users_groups()->row();
                $is_doc_id  = ($grup->name == 'dokter' ? $this->ion_auth->user()->row()->id : '0');
               
                $data = array(
                    'tgl_simpan'    => date('Y-m-d H:i:s'),
                    'tgl_masuk'     => date('Y-m-d H:i:s'),
                    'id_medcheck'   => $sql_medc->row()->id,
                    'id_pasien'     => $sql_medc->row()->id_pasien,
                    'id_user'       => $this->ion_auth->user()->row()->id,
                    'id_dokter'     => $is_doc_id,
                    'no_mcu'        => $no_surat,
                    'no_sample'     => '-',
                    'status'        => '0',
                );
                
                if($sql_medc->num_rows() > 0){
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data permintaan mcu di buat</div>');
                    crud::simpan('tbl_trans_medcheck_mcu', $data);
                    $last_id = crud::last_id();
                    
                    redirect(base_url('medcheck/tambah.php?act='.($grup->name == 'dokter' ? 'mcu_input' : 'mcu_surat').'&id='.general::enkrip($sql_medc->row()->id).'&id_mcu='.general::enkrip($last_id).'&status='.$status));
                }else{
                    redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->row()->id).'&status='.$status));
                }
                
//                echo '<pre>';
//                print_r($data);
//                echo '</pre>';
            }else{
//                redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->row()->id).'&status='.$status));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_mcu_upd() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_mcu     = $this->input->post('id_mcu');
            $id_user    = $this->input->post('id_user');
            $status     = $this->input->post('status');
            $status_mcu = $this->input->post('status_mcu');
            $no_sample  = $this->input->post('no_sampel');
            $dokter     = $this->input->post('dokter');
            $dokter_mcu = $this->input->post('dokter_kirim');
            $dokter_nm  = $this->input->post('dokter_kirim_nm');
            $tipe_dr    = $this->input->post('tipe_dokter');
            $kesan      = $this->input->post('kesan');
            $route      = $this->input->post('route');
            $act        = $this->input->post('act');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');
//            $this->form_validation->set_rules('tipe_dokter', 'Tipe Dokter', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'            => form_error('id'),
//                    'tipe_dokter'   => form_error('tipe_dokter'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?act=mcu_surat&id='.$id.'&id_mcu='.$id_mcu.'&status='.$status));
            } else {
                $sql_medc_mcu = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_mcu')->row();

                $data = array(
                    'tgl_modif'         => date('Y-m-d H:i:s'),
                    'id_dokter'         => $dokter,
                    'id_dokter_kirim'   => $dokter_mcu,
                    'id_mcuiografer'    => (!empty($id_user) ? $id_user : $this->ion_auth->user()->row()->id),
                    'dokter_kirim'      => $dokter_nm,
                    'no_sample'         => $no_sample,
                    'ket'               => $kesan,
                    'status'            => $status_mcu,
                    'status_dokter_krm' => $tipe_dr,
                );
                
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data mcuiologi berhasil disimpan</div>');
                crud::update('tbl_trans_medcheck_mcu', 'id', $sql_medc_mcu->id, $data);
                
                redirect(base_url('medcheck/tambah.php?act=mcu_surat&id='.$id.'&id_mcu='.general::enkrip($sql_medc_mcu->id).'&status='.$status.(!empty($route) ? '&route='.$route : '')));                
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_mcu_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $item_id    = $this->input->get('item_id');
            $status     = $this->input->get('status');
            $userid     = $this->ion_auth->user()->row()->id;
            
            $sql_medc = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck');
            
            if($sql_medc->num_rows() > 0){
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil dihapus</div>');
                
                /* Transaksi Database */
                $this->db->trans_begin();

                # Hapus ke tabel medcheck det
//                $this->db->where('id_mcu', general::dekrip($item_id))->delete('tbl_trans_medcheck_det');
                $this->db->where('id_mcu', general::dekrip($item_id))->delete('tbl_trans_medcheck_mcu_det');
                
                # Hapus ke tabel medcheck lab
                $this->db->where('id', general::dekrip($item_id))->delete('tbl_trans_medcheck_mcu');

                # Cek status transact MySQL
                if ($this->db->trans_status() === FALSE) {
                    # Rollback jika gagal
                    $this->db->trans_rollback();

                    # Tampilkan pesan error
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Data lab gagal dihapus !!</div>');
                } else {
                    $this->db->trans_commit();

                    # Tampilkan pesan sukses jika sudah berhasil commit
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data lab berhasil dihapus !!</div>');
                }
            }

            redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_resep() {
        if (akses::aksesLogin() == TRUE) {
            $id     = $this->input->get('id');
            $status = $this->input->get('status');
            
            if(!empty($id)){
                $sql_medc   = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck'); 
                $pengaturan = $this->db->get('tbl_pengaturan')->row();
    
                $nomer      = $this->db->where('MONTH(tgl_simpan)', date('m'))->get('tbl_trans_medcheck_resep')->num_rows() + 1;
                $no_surat   = sprintf('%03d', $nomer).'/'.$pengaturan->kode_resep.'/'.date('m').'/'.date('Y');
                $grup       = $this->ion_auth->get_users_groups()->row();
                $is_farm    = ($grup->name == 'farmasi' ? '2' : '0');
                $is_farm_id = ($grup->name == 'farmasi' ? $this->ion_auth->user()->row()->id : '0');                
                $is_doc_id  = ($grup->name == 'dokter' ? $this->ion_auth->user()->row()->id : $sql_medc->id_dokter);
               
                $data = array(
                    'tgl_simpan'    => date('Y-m-d H:i:s'),
                    'id_medcheck'   => $sql_medc->row()->id,
                    'id_pasien'     => $sql_medc->row()->id_pasien,
                    'id_user'       => $this->ion_auth->user()->row()->id,
                    'id_farmasi'    => $is_farm_id,
                    'id_dokter'     => $is_doc_id,
                    'no_resep'      => $no_surat,
                    'status'        => $is_farm,
                );
                
                if($sql_medc->num_rows() > 0){
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data surat berhasil disimpan</div>');
                    crud::simpan('tbl_trans_medcheck_resep', $data);
                    $last_id = crud::last_id();
                                       
                    redirect(base_url('medcheck/tambah.php?act=res_input&id='.general::enkrip($sql_medc->row()->id).'&id_resep='.general::enkrip($last_id).'&status='.$status));
                }else{
                    redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->row()->id).'&status='.$status));
                }
            }else{
                redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->row()->id).'&status='.$status));
            }
//
//           $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data surat berhasil disimpan</div>');
//           crud::simpan('tbl_trans_medcheck_resep', $data);
//           $last_id = crud::last_id();
//           
//           redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->id).'&status='.$status.'&id_resep='.general::enkrip($last_id)));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    # Menandai resep obat pulang rawat inap
    public function set_medcheck_resep_upd() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_rsp     = $this->input->get('id_resep');
            $status     = $this->input->get('status');
            $status_plg = $this->input->get('status_plg');
            
            if(!empty($id) AND !empty($id_rsp)){
                $sql_res_ck = $this->db->where('id_medcheck', general::dekrip($id))->where('status_plg', '1')->get('tbl_trans_medcheck_resep'); 
                $sql_res    = $this->db->where('id', general::dekrip($id_rsp))->get('tbl_trans_medcheck_resep')->row(); 
                $pengaturan = $this->db->get('tbl_pengaturan')->row();
               
                $data = array(
                    'tgl_modif'  => date('Y-m-d H:i:s'),
                    'status_plg' => $status_plg,
                );
                
                if($sql_res_ck->num_rows() == 1 AND $sql_res_ck->row()->status_plg == $status_plg){
                    $res = $sql_res_ck->row();
                    $this->session->set_flashdata('medcheck_toast', 'toastr.error("Resep pulang hanya bisa di simpan 1 kali, silahkan batalkan resep sebelumnya !");');
                }else{                
                    # Simpan data ke dalam database
                    $this->db->where('id', $sql_res->id)->update('tbl_trans_medcheck_resep', $data);
                
                    if($status_plg == '0'){
                        $this->session->set_flashdata('medcheck_toast', 'toastr.warning("Resep ['.$sql_res->no_resep.'] sudah dibatalkan sebagai resep pulang.");');
                    }else{
                        $this->session->set_flashdata('medcheck_toast', 'toastr.success("Resep ['.$sql_res->no_resep.'] sudah set sebagai resep pulang dan akan tampil pada resume medis pasien.");');
                    }
                }
                
                redirect(base_url('medcheck/tambah.php?act=res_input&id='.$id.'&id_resep='.general::enkrip($sql_res->id).'&status='.$status));
            }else{
                redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->row()->id).'&status='.$status));
            }
//
//           $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data surat berhasil disimpan</div>');
//           crud::simpan('tbl_trans_medcheck_resep', $data);
//           $last_id = crud::last_id();
//           
//           redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->id).'&status='.$status.'&id_resep='.general::enkrip($last_id)));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_resep_proses() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_resep   = $this->input->post('id_resep');
            $status     = $this->input->post('status');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                );

                $this->session->set_flashdata('id', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id.'status='.$status));
            } else {
                $sql_medc       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row(); 
                $sql_medc_res   = $this->db->where('id', general::dekrip($id_resep))->get('tbl_trans_medcheck_resep')->row();  
                $pengaturan     = $this->db->get('tbl_pengaturan')->row();
                
                foreach ($this->cart->contents() as $cart){
                    $sql_item       = $this->db->where('id', $cart['options']['id_item'])->get('tbl_m_produk')->row();
                                       
                    $data[] = array(
                        'id_medcheck'   => (int)$sql_medc->id,
                        'id_resep'      => (int)general::dekrip($id_resep),
                        'id_item'       => (int)$cart['options']['id_item'],
                        'id_item_kat'   => (int)$cart['options']['id_item_kat'],
                        'id_item_sat'   => (int)$cart['options']['id_item_sat'],
                        'kode'          => $sql_item->kode,
                        'item'          => $sql_item->produk,
                        'dosis'         => $cart['options']['dos_jml1'].' '.$cart['options']['dos_sat'].' Tiap '.$cart['options']['dos_jml2'].' '.$cart['options']['dos_wkt'],
                        'dosis_ket'     => $cart['options']['dos_ket'],
                        'harga'         => $cart['options']['harga'],
                        'jml'           => (int)$cart['qty'],
                        'jml_satuan'    => '1',
                        'satuan'        => $cart['options']['satuan'],
                        'status'        => (int)$status,
                    );
                    
                    $data_resep = array(
                        'id_medcheck'   => (int)$sql_medc->id,
                        'id_resep'      => (int)general::dekrip($id_resep),
                        'id_item'       => (int)$cart['options']['id_item'],
                        'id_item_kat'   => (int)$cart['options']['id_item_kat'],
                        'id_item_sat'   => (int)$cart['options']['id_item_sat'],
                        'id_user'       => $this->ion_auth->user()->row()->id,
                        'tgl_simpan'    => $cart['options']['tgl_simpan'],
                        'tgl_modif'     => date('Y-m-d H:i:s'),
                        'kode'          => $sql_item->kode,
                        'item'          => $sql_item->produk,
                        'dosis'         => $cart['options']['dos_jml1'].' '.$cart['options']['dos_sat'].' Tiap '.$cart['options']['dos_jml2'].' '.$cart['options']['dos_wkt'],
                        'dosis_ket'     => $cart['options']['dos_ket'],
                        'harga'         => $cart['options']['harga'],
                        'jml'           => (int)$cart['qty'],
                        'jml_satuan'    => '1',
                        'satuan'        => $cart['options']['satuan'],
                        'status'        => (int)$status,
                        'status_resep'  => '',
                    );
                    
                    // Simpan ke tabel resep
                    crud::simpan('tbl_trans_medcheck_resep_det', $data_resep);
                }
                
                // Convert ke json biar gampang
                $item = json_encode($data);
                
                // Simpan ke database
                crud::update('tbl_trans_medcheck_resep', 'id', $sql_medc_res->id, array('item'=>$item, 'status'=>'1'));
                
                // Hapus cart session ci
                $this->cart->destroy();
                
                // Balikin ke halaman semula
                redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->id).'&status='.$status));
                
//                echo '<pre>';
//                print_r(json_decode($item));
//                echo '</pre>';
//                
//                echo '<pre>';
//                print_r($this->cart->contents());
//                echo '</pre>';    
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_resep_status() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_resep   = $this->input->post('id_resep');
            $id_farmasi = $this->input->post('id_farmasi');
            $msg        = $this->input->post('msg');
            $status     = $this->input->post('status');
            $status_res = $this->input->post('status_res');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('id_resep', 'ID Resep', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                    'id_resep'  => form_error('id_resep'),
                );

                $this->session->set_flashdata('resep', $msg_error);

                redirect(base_url('medcheck/tambah.php?act=res_input&id='.$id.'&id_resep='.$id_resep.'&status='.$status));
            } else {
                // Set status diproses oleh farmasi atau tidak
                $data = array(
                    'tgl_modif'   => date('Y-m-d H:i:s'),
                    'id_farmasi'  => general::dekrip($id_farmasi),
                    'status'      => $status_res,
                );
                
                crud::update('tbl_trans_medcheck_resep', 'id', general::dekrip($id_resep), $data);
                
                $this->session->set_flashdata('medcheck_toast', 'toastr.success("'.$msg.'")');
                redirect(base_url('medcheck/tambah.php?act=res_input&id='.$id.'&id_resep='.$id_resep.'&status='.$status));
                
//                if($status_res == '2'){
//                    // Jika proses redirect ke input page
//                    redirect(base_url('medcheck/tambah.php?id='.$id.'&id_resep='.$id_resep.'&status='.$status));
//                }else{
//                    redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
//                }
                
//                echo '<pre>';
//                print_r($data);
//                echo '</pre>';
            }
            
//            if(!empty($id_resep)){
//                crud::update('tbl_trans_medcheck_resep', 'id', general::dekrip($id_resep), $data);
//                
//                
//                if($status_res == '2'){
//                    // Jika proses redirect ke input page
//                    redirect(base_url('medcheck/tambah.php?id='.$id.'&id_resep='.$id_resep.'&status='.$status));
//                }else{
//                    redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
//                }
//            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_resep_farm() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_resep   = $this->input->post('id_resep');
            $id_produk  = $this->input->post('id_produk');
            $status     = $this->input->post('status');
            $status_res = $this->input->post('status_res');
            
            if(!empty($id_resep)){
                $sql_resep      = $this->db->where('id', general::dekrip($id_resep))->get('tbl_trans_medcheck_resep')->row();
                $sql_resep_det  = $this->db->where('id_resep', general::dekrip($id_resep))->get('tbl_trans_medcheck_resep_det')->result();
                $item           = json_decode($sql_resep->item);
                
                foreach ($sql_resep_det as $cart){
                    $id_item = general::dekrip($id_produk);

                    $subtotal       = $cart->jml * $cart->harga;                    
                    $data_res_det = array(
                        'id_medcheck'   => (int)$cart->id_medcheck,
                        'id_resep'      => (int)general::dekrip($id_resep),
                        'id_item'       => (int)$cart->id_item,
                        'id_item_kat'   => (int)$cart->id_item_kat,
                        'id_item_sat'   => (int)$cart->id_item_sat,
                        'id_user'       => (int)$cart->id_user,
                        'tgl_simpan'    => $cart->tgl_simpan,
                        'tgl_modif'     => date('Y-m-d H:i:s'),
                        'kode'          => $cart->kode,
                        'item'          => $cart->item,
                        'dosis'         => $cart->dosis,
                        'dosis_ket'     => $cart->dosis_ket,
                        'harga'         => (float)$cart->harga,
                        'jml'           => (int)$cart->jml,
                        'jml_satuan'    => (int)$cart->jml_satuan,
                        'subtotal'      => (float)$subtotal,
                        'satuan'        => $cart->satuan,
                        'status'        => '4',
//                        'status'        => (int)$cart->status,
                    );
                    
                    crud::simpan('tbl_trans_medcheck_det', $data_res_det);
     
                    if($cart->id_item == $id_item){
                        crud::simpan('tbl_trans_medcheck_det', (array)$cart);
                        $last_id = crud::last_id();
                    }
                    
                    $farmasi = array(
                        'tgl_modif'     => date('Y-m-d H:i:s'),
                        'id_farmasi'    => $this->ion_auth->user()->row()->id,
                        'status'        => $status
                    );
                    
                    crud::update('tbl_trans_medcheck_resep', 'id', (int)general::dekrip($id_resep), $farmasi);
                }
                
                redirect(base_url('medcheck/resep/tambah.php?id='.$id.'&id_resep='.general::enkrip($sql_resep->id).'&status='.$status));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_resep_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $item_id    = $this->input->get('item_id');
            $status     = $this->input->get('status');
            $userid     = $this->ion_auth->user()->row()->id;
            
            $sql_medc = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck');
            
            if($sql_medc->num_rows() > 0){
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil dihapus</div>');
                
                /* Transaksi Database */
                $this->db->trans_start();

                # Update ke tabel resep
                $this->db->where('id_resep', general::dekrip($item_id))->delete('tbl_trans_medcheck_resep_det');
                $this->db->where('id_resep', general::dekrip($item_id))->delete('tbl_trans_medcheck_det');
                $this->db->where('id', general::dekrip($item_id))->delete('tbl_trans_medcheck_resep');
                
                $this->db->trans_complete();
            }
            
            redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_surat() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $status     = $this->input->post('status');
            $tgl_masuk  = $this->input->post('tgl_masuk');
            $tgl_keluar = $this->input->post('tgl_keluar');
            $tgl_kontrol= $this->input->post('tgl_kontrol');
            $tgl_lahir  = $this->input->post('tgl_lahir');
            $wkt_lahir  = $this->input->post('wkt_lahir');
            $nm_lahir   = $this->input->post('nm_lahir');
            $nm_ayah    = $this->input->post('nm_ayah');
            $nm_ibu     = $this->input->post('nm_ibu');
            $jns_klm    = $this->input->post('jns_klm');
            $hasil      = $this->input->post('hasil');
            $dokter     = $this->input->post('dokter');
            $tb         = $this->input->post('tb');
            $td         = $this->input->post('td');
            $bb         = $this->input->post('bb');
            $bw         = $this->input->post('bw');
            $bw_ket     = $this->input->post('bw_ket');
            $tipe_surat = $this->input->post('tipe_surat');
            $sembuh     = $this->input->post('sembuh');
            $mati_tgl   = $this->input->post('mati_tgl');
            $mati_wkt   = $this->input->post('mati_wkt');
            $mati_tmp   = $this->input->post('mati_tmp');
            $mati_pny   = $this->input->post('mati_penyebab');
            $ruj_dokter = $this->input->post('ruj_dokter');
            $ruj_faskes = $this->input->post('ruj_faskes');
            $ruj_keluhan= $this->input->post('ruj_keluhan');
            $ruj_diagnos= $this->input->post('ruj_diagnosa');
            $ruj_terapi = $this->input->post('ruj_terapi');
            $cvd_tg_prks= $this->input->post('cvd_tgl_periksa');
            $cvd_tg_awal= $this->input->post('cvd_tgl_awal');
            $cvd_tg_akhr= $this->input->post('cvd_tgl_akhir');
            $nza_tg_prks= $this->input->post('napza_tgl_periksa');
            $nza_hasil  = $this->input->post('napza_hasil');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                );

                $this->session->set_flashdata('anamnesa', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id.'status='.$status));
            } else {
                $sql_medc       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row(); 
                $pengaturan     = $this->db->get('tbl_pengaturan')->row();
                
                switch ($tipe_surat){
                    case '1';
                        $str_tipe   = $pengaturan->kode_surat_sht;
                        break;
                    
                    case '2';
                        $str_tipe   = $pengaturan->kode_surat_skt;
                        break;
                    
                    case '3';
                        $str_tipe   = $pengaturan->kode_surat_rnp;
                        break;
                    
                    case '4';
                        $str_tipe   = $pengaturan->kode_surat_kntrl;
                        break;
                    
                    case '5';
                        $str_tipe   = $pengaturan->kode_surat_lahir;
                        break;
                    
                    case '6';
                        $str_tipe   = $pengaturan->kode_surat_mati;
                        break;
                    
                    case '7';
                        $str_tipe   = $pengaturan->kode_surat_covid;
                        break;
                    
                    case '8';
                        $str_tipe   = $pengaturan->kode_surat_rujukan;
                        break;
                    
                    case '9';
                        $str_tipe   = $pengaturan->kode_surat_cvd;
                        break;
                    
                    case '10';
                        $str_tipe   = $pengaturan->kode_surat_cvd;
                        break;
                    
                    case '11';
                        $str_tipe   = $pengaturan->kode_surat_cvd;
                        break;
                    
                    case '12';
                        $str_tipe   = $pengaturan->kode_surat_cvd;
                        break;
                    
                    case '13';
                        $str_tipe   = $pengaturan->kode_periksa;
                        break;
                }
                
                $nomer          = $this->db->where('tipe', $tipe_surat)->where('MONTH(tgl_simpan)', date('m'))->where('YEAR(tgl_simpan)', date('Y'))->get('tbl_trans_medcheck_surat')->num_rows() + 1;
                $no_surat       = sprintf('%03d', $nomer).'/'.$str_tipe.'/'.date('m').'/'.date('Y');
                $tgl_awal       = date_create($this->tanggalan->tgl_indo_sys($tgl_masuk));
                $tgl_akhir      = date_create($this->tanggalan->tgl_indo_sys($tgl_keluar));
                $diff           = date_diff($tgl_awal, $tgl_akhir);
                $hari           = $diff->format("%d%");
                $jml_hari       = ($tgl_awal == $tgl_akhir ? '1' : ($hari > 1 ? $hari + 1 : '2'));
                
                $data = array(
                    'tgl_simpan'    => date('Y-m-d H:i:s'),
                    'tgl_masuk'     => (!empty($tgl_masuk) ? $this->tanggalan->tgl_indo_sys($tgl_masuk) : date('Y-m-d')),
                    'tgl_keluar'    => (!empty($tgl_keluar) ? $this->tanggalan->tgl_indo_sys($tgl_keluar) : '0000-00-00'),
                    'tgl_kontrol'   => (!empty($tgl_kontrol) ? $this->tanggalan->tgl_indo_sys($tgl_kontrol) : '0000-00-00'),
                    'id_medcheck'   => $sql_medc->id,
                    'id_dokter'     => $sql_medc->id_dokter,
                    'id_pasien'     => $sql_medc->id_pasien,
                    'id_user'       => $this->ion_auth->user()->row()->id,
                    'no_surat'      => $no_surat,
                    'lahir_tgl'     => (!empty($tgl_lahir) ? $this->tanggalan->tgl_indo_sys($tgl_lahir).' '.$wkt_lahir : '0000-00-00'),
                    'lahir_nm'      => $nm_lahir,
                    'lahir_nm_ayah' => $nm_ayah,
                    'lahir_nm_ibu'  => $nm_ibu,
                    'jns_klm'       => (!empty($jns_klm) ? $jns_klm : 'L'),
                    'mati_tgl'      => (!empty($mati_tgl) ? $this->tanggalan->tgl_indo_sys($mati_tgl).' '.$mati_wkt : '0000-00-00 00:00:00'),
                    'mati_tmp'      => $mati_tmp,
                    'mati_penyebab' => $mati_pny,
                    'ruj_dokter'    => $ruj_dokter,
                    'ruj_faskes'    => $ruj_faskes,
                    'ruj_keluhan'   => $ruj_keluhan,
                    'ruj_diagnosa'  => $ruj_diagnos,
                    'ruj_terapi'    => $ruj_terapi,
                    'cvd_tgl_periksa' => (!empty($cvd_tg_akhr) ? $this->tanggalan->tgl_indo_sys($cvd_tg_akhr) : '0000-00-00'),
                    'cvd_tgl_awal'  => (!empty($cvd_tg_awal) ? $this->tanggalan->tgl_indo_sys($cvd_tg_awal) : '0000-00-00'),
                    'cvd_tgl_akhir' => (!empty($cvd_tg_akhr) ? $this->tanggalan->tgl_indo_sys($cvd_tg_akhr) : '0000-00-00'),
                    'nza_tgl_periksa'=> (!empty($nza_tg_prks) ? $this->tanggalan->tgl_indo_sys($nza_tg_prks) : '0000-00-00'),
                    'nza_status'    => (!empty($nza_hasil) ? $nza_hasil : '0'),
                    'tb'            => (float)general::format_angka_db($tb),
                    'td'            => $td,
                    'bb'            => (float)general::format_angka_db($bb),
                    'bw'            => ($bw == '+' ? '+' : '-'),
                    'bw_ket'        => (!empty($bw_ket) ? $bw_ket : ''),
                    'jml_hari'      => (float)$jml_hari,
                    'hasil'         => (!empty($hasil) ? $hasil : '0'),
                    'tipe'          => $tipe_surat,
                    'status_sembuh' => (!empty($sembuh) ? $sembuh : '0'),
                );
//                
//                echo '<pre>';
//                print_r($data);
//                echo '</pre>';

                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data surat berhasil disimpan</div>');
                crud::simpan('tbl_trans_medcheck_surat', $data);             
                redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->id).'&status='.$status));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_inform() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $status     = $this->input->post('status');
            $tgl_masuk  = $this->input->post('tgl_masuk');
            $tipe_surat = $this->input->post('tipe_surat');
            $nama       = $this->input->post('nama');
            $jns_klm    = $this->input->post('jns_klm');
            $tgl_lahir  = $this->input->post('tgl_lahir');
            $alamat     = $this->input->post('alamat');
            $hubungan   = $this->input->post('hubungan');
            $ruang      = $this->input->post('kamar');
            $saksi1     = $this->input->post('saksi1');
            $saksi2     = $this->input->post('saksi2');
            $dokter     = $this->input->post('dokter');
            $penjamin   = $this->input->post('penjamin');
            $penanggung = $this->input->post('penanggung');
            $tindakan   = $this->input->post('tindakan');
            $setuju     = $this->input->post('status_stj');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id' => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id.'status='.$status));
            } else {
                $sql_medc       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row(); 
                $pengaturan     = $this->db->get('tbl_pengaturan')->row();
                
                switch ($tipe_surat){
                    case '1';
                        $str_tipe   = $pengaturan->kode_surat;
                        break;
                    
                    case '2';
                        $str_tipe   = $pengaturan->kode_surat;
                        break;
                }
                
                $nomer          = $this->db->where('tipe', $tipe_surat)->where('MONTH(tgl_simpan)', date('m'))->where('YEAR(tgl_simpan)', date('Y'))->get('tbl_trans_medcheck_surat_inform')->num_rows() + 1;
                $no_surat       = sprintf('%03d', $nomer).'/'.$str_tipe.'/'.date('m').'/'.date('Y');
                $tgl_awal       = date_create($this->tanggalan->tgl_indo_sys($tgl_masuk));
                $tgl_akhir      = date_create($this->tanggalan->tgl_indo_sys($tgl_keluar));
                $diff           = date_diff($tgl_awal, $tgl_akhir);
                $hari           = $diff->format("%d%");
                
                $data = array(
                    'tgl_simpan'    => date('Y-m-d H:i:s'),
                    'tgl_masuk'     => (!empty($tgl_masuk) ? $this->tanggalan->tgl_indo_sys($tgl_masuk) : date('Y-m-d')),
                    'tgl_lahir'     => (!empty($tgl_lahir) ? $this->tanggalan->tgl_indo_sys($tgl_lahir) : date('Y-m-d')),
                    'id_medcheck'   => $sql_medc->id,
                    'id_dokter'     => $dokter,
                    'id_pasien'     => $sql_medc->id_pasien,
                    'id_user'       => $this->ion_auth->user()->row()->id,
                    'no_surat'      => $no_surat,
                    'nama'          => $nama,
                    'jns_klm'       => $jns_klm,
                    'alamat'        => $alamat,
                    'ruang'         => $ruang,
                    'penjamin'      => $penjamin,
                    'penanggung'    => $penanggung,
                    'ruang'         => $ruang,
                    'nama_saksi1'   => $saksi1,
                    'nama_saksi2'   => $saksi2,
                    'tindakan'      => $tindakan,
                    'status_hub'    => (!empty($hubungan) ? $hubungan : '0'),
                    'status_stj'    => (!empty($setuju) ? $setuju : '0'),
                    'status_ttd'    => '0',
                    'tipe'          => $tipe_surat,
                );

//                echo '<pre>';
//                print_r($data);
//                echo '</pre>';
//                echo '<pre>';
//                print_r($_POST);
//                echo '</pre>';
//
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data surat berhasil disimpan</div>');
                crud::simpan('tbl_trans_medcheck_surat_inform', $data);
                $last_id = crud::last_id();
                
                redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->id).'&status='.$status.(!empty($last_id) ? '&form_id='.general::enkrip($last_id) : '')));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_inform_upd() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_form    = $this->input->post('id_form');
            $foto       = $this->input->post('foto');
            $status     = $this->input->post('status');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id' => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id.'status='.$status));
            } else {
                $sql_medc       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_pas        = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row();
                $pengaturan     = $this->db->get('tbl_pengaturan')->row();
                
                switch ($tipe_surat){
                    case '1';
                        $str_tipe   = $pengaturan->kode_surat;
                        break;
                    
                    case '2';
                        $str_tipe   = $pengaturan->kode_surat;
                        break;
                }
                
                $nomer          = $this->db->where('tipe', $tipe_surat)->where('MONTH(tgl_simpan)', date('m'))->get('tbl_trans_medcheck_surat_inform')->num_rows() + 1;
                $no_surat       = sprintf('%03d', $nomer).'/'.$str_tipe.'/'.date('m').'/'.date('Y');
                $tgl_awal       = date_create($this->tanggalan->tgl_indo_sys($tgl_masuk));
                $tgl_akhir      = date_create($this->tanggalan->tgl_indo_sys($tgl_keluar));
                $diff           = date_diff($tgl_awal, $tgl_akhir);
                $hari           = $diff->format("%d%");
                
                # Config File Foto Pasien
                $kode               = sprintf('%05d', $sql_pas->kode);
                $no_rm              = strtolower($sql_pas->kode_dpn.$sql_pas->kode);
                $path               = 'file/pasien/'.$no_rm.'/';
                
                # Simpan foto dari kamera ke dalam format file *.png dari base64
                if (!empty($foto)) {
                    $filename           = $path.'ttd_pernyt_'.$kode.'.png';
                    general::base64_to_jpeg($foto, $filename);
                }
                
                $data = array(
                    'file_name1'     => $filename,
                    'status_ttd'    => (!empty($foto) ? '1' : '0'),
                );

//                echo '<pre>';
//                print_r($data);
//                echo '</pre>';
//                echo '<pre>';
//                print_r($_POST);
//                echo '</pre>';
//
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data surat berhasil disimpan</div>');
                crud::update('tbl_trans_medcheck_surat_inform', 'id', general::dekrip($id_form), $data);
                $last_id = crud::last_id();
                
                redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->id).'&status='.$status.(!empty($last_id) ? '&form_id='.general::enkrip($last_id) : '')));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    


    public function set_medcheck_resume() {
        if (akses::aksesLogin() == TRUE) {
            $id     = $this->input->get('id');
            $status = $this->input->get('status');
            
            if(!empty($id)){
                $sql_medc   = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck');
                $pengaturan = $this->db->get('tbl_pengaturan')->row();
                   
                $nomer      = $this->db->where('MONTH(tgl_simpan)', date('m'))->get('tbl_trans_medcheck_resume')->num_rows() + 1;
                $no_surat   = sprintf('%03d', $nomer).'/'.$pengaturan->kode_surat.'/'.date('m').'/'.date('Y');
                $grup       = $this->ion_auth->get_users_groups()->row();
                $is_rad     = ($grup->name == 'radiografer' ? '2' : '0');
                $is_rad_id  = ($grup->name == 'radiografer' ? $this->ion_auth->user()->row()->id : '0');
                $is_doc_id  = ($grup->name == 'dokter' ? $this->ion_auth->user()->row()->id : '0');
               
                $data = array(
                    'tgl_simpan'    => date('Y-m-d H:i:s'),
                    'tgl_masuk'     => date('Y-m-d'),
                    'id_medcheck'   => $sql_medc->row()->id,
                    'id_pasien'     => $sql_medc->row()->id_pasien,
                    'id_user'       => $this->ion_auth->user()->row()->id,
                    'id_dokter'     => $is_doc_id,
                    'no_surat'      => $no_surat,
                    'pasien'        => $sql_medc->row()->pasien,
                    'status'        => '0',
                    'status_rsm'    => '0',
                );
                
                if($sql_medc->num_rows() > 0){
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data permintaan resume medis di buat</div>');
                    crud::simpan('tbl_trans_medcheck_resume', $data);
                    $last_id = crud::last_id();
                    
                    redirect(base_url('medcheck/tambah.php?act='.($grup->name == 'dokter' ? 'resm_input' : 'resm_surat').'&id='.general::enkrip($sql_medc->row()->id).'&id_resm='.general::enkrip($last_id).'&status='.$status));
                }else{
                    redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->row()->id).'&status='.$status));
                }
            }else{
//                redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->row()->id).'&status='.$status));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }    
    
    public function set_medcheck_resume_upd() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_rsm     = $this->input->post('id_resume');
            $id_user    = $this->input->post('id_user');
            $status     = $this->input->post('status');
            $dokter_rsm = $this->input->post('dokter_kirim');
            $no_sample  = $this->input->post('no_sample');
            $saran      = $this->input->post('saran');
            $kesimpulan = $this->input->post('kesimpulan');
            $route      = $this->input->post('route');
            $act        = $this->input->post('act');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'            => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?act=resm_surat&id='.$id.'&id_resm='.$id_rsm.'&status='.$status));
            } else {
                $sql_medc_resm = $this->db->where('id', general::dekrip($id_rsm))->get('tbl_trans_medcheck_resume')->row();

                $data = array(
                    'tgl_modif'   => date('Y-m-d H:i:s'),
                    'id_dokter'   => $dokter_rsm,
                    'no_sample'   => $no_sample,
                    'saran'       => $saran,
                    'kesimpulan'  => $kesimpulan,
                );
                
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data resume berhasil disimpan</div>');
                crud::update('tbl_trans_medcheck_resume', 'id', $sql_medc_resm->id, $data);
                
                redirect(base_url('medcheck/tambah.php?act=resm_surat&id='.$id.'&id_resm='.general::enkrip($sql_medc_resm->id).'&status='.$status.(!empty($route) ? '&route='.$route : '')));                
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_resume_hsl() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_rsm     = $this->input->post('id_resume');
            $status     = $this->input->post('status');
            $periksa    = $this->input->post('pemeriksaan');
            $hasil      = $this->input->post('hasil');
            $route      = $this->input->post('route');
            $act        = $this->input->post('act');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'            => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?act=resm_surat&id='.$id.'&id_resm='.$id_rsm.'&status='.$status));
            } else {
                $sql_medc_resm = $this->db->where('id', general::dekrip($id_rsm))->get('tbl_trans_medcheck_resume')->row();

                $data = array(
                    'id_medcheck' => general::dekrip($id),
                    'id_resume'   => general::dekrip($id_rsm),
                    'id_user'     => $this->ion_auth->user()->row()->id,
                    'tgl_simpan'  => date('Y-m-d H:i:s'),
                    'param'       => $periksa,
                    'param_nilai' => $hasil,
                    'status_rnp'  => '0',
                    'status_trp'  => '0',
                );
                
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data pemeriksaan resume berhasil disimpan</div>');
                crud::simpan('tbl_trans_medcheck_resume_det', $data);
                
                redirect(base_url('medcheck/tambah.php?act=resm_input&id='.$id.'&id_resm='.general::enkrip($sql_medc_resm->id).'&status='.$status.(!empty($route) ? '&route='.$route : '')));                
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_resume_hsl2() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_rsm     = $this->input->post('id_resume');
            $status     = $this->input->post('status');
            $periksa    = $this->input->post('pemeriksaan');
            $hasil      = $this->input->post('hasil');
            $route      = $this->input->post('route');
            $act        = $this->input->post('act');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'            => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?act=resm_input&id='.$id.'&id_resm='.$id_rsm.'&status='.$status));
            } else {
                $sql_medc_resm = $this->db->where('id', general::dekrip($id_rsm))->get('tbl_trans_medcheck_resume')->row();
                
                for($n = 1; $n <= 13; $n++){
                    $data = array(
                        'id_medcheck' => general::dekrip($id),
                        'id_resume'   => general::dekrip($id_rsm),
                        'id_user'     => $this->ion_auth->user()->row()->id,
                        'tgl_simpan'  => date('Y-m-d H:i:s'),
                        'param'       => $this->input->post('pemeriksaan'.$n),
                        'param_nilai' => $this->input->post('hasil'.$n),
                        'status_rnp'  => '1',
                        'status_trp'  => '0',
                    );
                    
                    crud::simpan('tbl_trans_medcheck_resume_det', $data);
                }
                
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data pemeriksaan resume berhasil disimpan</div>');
                redirect(base_url('medcheck/tambah.php?act=resm_input_rnp&id='.$id.'&id_resm='.general::enkrip($sql_medc_resm->id).'&status='.$status.(!empty($route) ? '&route='.$route : '')));                
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_resume_hsl2_upd() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_rsm     = $this->input->post('id_resume');
            $status     = $this->input->post('status');
            $periksa    = $this->input->post('pemeriksaan');
            $hasil      = $this->input->post('hasil');
            $route      = $this->input->post('route');
            $act        = $this->input->post('act');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'            => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?act=resm_input&id='.$id.'&id_resm='.$id_rsm.'&status='.$status));
            } else {
                $sql_medc_resm = $this->db->where('id', general::dekrip($id_rsm))->get('tbl_trans_medcheck_resume')->row();                
                
                # Hapus dahulu
                $this->db->where('id_resume', $sql_medc_resm->id)->delete('tbl_trans_medcheck_resume_det');
                
                for($n = 1; $n <= 13; $n++){
                    $data = array(
                        'id_medcheck' => general::dekrip($id),
                        'id_resume'   => general::dekrip($id_rsm),
                        'id_user'     => $this->ion_auth->user()->row()->id,
                        'tgl_simpan'  => date('Y-m-d H:i:s'),
                        'param'       => $this->input->post('pemeriksaan'.$n),
                        'param_nilai' => $this->input->post('hasil'.$n),
                        'status_rnp'  => '1',
                        'status_trp'  => '0',
                    );
                    
                    $this->db->insert('tbl_trans_medcheck_resume_det', $data);
                }
                
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data pemeriksaan resume berhasil disimpan !!</div>');
                redirect(base_url('medcheck/tambah.php?act=resm_input_rnp&id='.$id.'&id_resm='.general::enkrip($sql_medc_resm->id).'&status='.$status.(!empty($route) ? '&route='.$route : '')));                
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_resume_hsl3() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_rsm     = $this->input->post('id_resume');
            $status     = $this->input->post('status');
            $periksa    = $this->input->post('pemeriksaan');
            $hasil1     = $this->input->post('hasil1');
            $hasil2     = $this->input->post('hasil2');
            $hasil3     = $this->input->post('hasil3');
            $route      = $this->input->post('route');
            $act        = $this->input->post('act');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'            => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?act=resm_surat&id='.$id.'&id_resm='.$id_rsm.'&status='.$status));
            } else {
                $sql_medc_resm = $this->db->where('id', general::dekrip($id_rsm))->get('tbl_trans_medcheck_resume')->row();

                $data = array(
                    'id_medcheck' => general::dekrip($id),
                    'id_resume'   => general::dekrip($id_rsm),
                    'id_user'     => $this->ion_auth->user()->row()->id,
                    'tgl_simpan'  => date('Y-m-d H:i:s'),
                    'param'       => $periksa,
                    'param_nilai' => (!empty($hasil1) ? $hasil1 : '').(!empty($hasil2) ? '#('.$hasil2.')' : '').(!empty($hasil3) ? '#/ '.$hasil3.'' : ''),
                    'status_rnp'  => '0',
                    'status_trp'  => '1',
                );
                
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data pemeriksaan resume berhasil disimpan</div>');
                crud::simpan('tbl_trans_medcheck_resume_det', $data);
                
                redirect(base_url('medcheck/tambah.php?act=resm_input_trp&id='.$id.'&id_resm='.general::enkrip($sql_medc_resm->id).'&status='.$status.(!empty($route) ? '&route='.$route : '')));                
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_resume_hsl3_upd() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_rsm     = $this->input->post('id_resume');
            $id_item    = $this->input->post('id_resume_det');
            $status     = $this->input->post('status');
            $periksa    = $this->input->post('pemeriksaan');
            $hasil1     = $this->input->post('hasil1');
            $hasil2     = $this->input->post('hasil2');
            $hasil3     = $this->input->post('hasil3');
            $route      = $this->input->post('route');
            $act        = $this->input->post('act');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'            => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?act=resm_surat&id='.$id.'&id_resm='.$id_rsm.'&status='.$status));
            } else {
                $sql_medc_resm = $this->db->where('id', general::dekrip($id_rsm))->get('tbl_trans_medcheck_resume')->row();

                $data = array(
                    'id_user'     => $this->ion_auth->user()->row()->id,
                    'tgl_simpan'  => date('Y-m-d H:i:s'),
                    'param'       => $periksa,
                    'param_nilai' => (!empty($hasil1) ? $hasil1 : '').(!empty($hasil2) ? '#('.$hasil2.')' : '').(!empty($hasil3) ? '#/ '.$hasil3.'' : ''),
                );
                
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data pemeriksaan resume berhasil disimpan</div>');
                $this->db->where('id', general::dekrip($id_item))->update('tbl_trans_medcheck_resume_det', $data);
                
                redirect(base_url('medcheck/tambah.php?act=resm_input_trp&id='.$id.'&id_resm='.general::enkrip($sql_medc_resm->id).'&status='.$status.(!empty($route) ? '&route='.$route : '')));                
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_resume_ctk() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_rsm     = $this->input->get('id_resm');
            $status     = $this->input->post('status');
            $periksa    = $this->input->post('pemeriksaan');
            $hasil1     = $this->input->post('hasil1');
            $hasil2     = $this->input->post('hasil2');
            $hasil3     = $this->input->post('hasil3');
            $route      = $this->input->post('route');
            $act        = $this->input->post('act');
            
//            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
//
//            $this->form_validation->set_rules('id', 'ID', 'required');
//
//            if ($this->form_validation->run() == FALSE) {
//                $msg_error = array(
//                    'id'            => form_error('id'),
//                );
//
//                $this->session->set_flashdata('form_error', $msg_error);
//
//                redirect(base_url('medcheck/tambah.php?act=resm_surat&id='.$id.'&id_resm='.$id_rsm.'&status='.$status));
//            } else {
                $sql_medc      = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_medc_resm = $this->db->where('id', general::dekrip($id_rsm))->get('tbl_trans_medcheck_resume')->row();
                $sql_medc_file = $this->db->where('id_medcheck_rsm', $sql_medc_resm->id)->get('tbl_trans_medcheck_file');

                $data_file = array(
                    'id_medcheck'       => $sql_medc->id,
                    'id_medcheck_rsm'   => $sql_medc_resm->id,
                    'id_pasien'         => $sql_medc->id_pasien,
                    'id_user'           => $this->ion_auth->user()->row()->id,
                    'tgl_simpan'        => date('Y-m-d H:i:s'),
                    'tgl_masuk'         => date('Y-m-d H:i'),
                    'judul'             => 'RESUME MEDIS RAWAT INAP '.$sql_medc_resm->no_surat,
                    'file_name'         => '/medcheck/surat/cetak_pdf_rsm_rnp.php?id='.$id.'&id_resm='.$id_rsm,
                    'file_ext'          => '.pdf',
                    'file_type'         => 'application/pdf',
                    'status'            => '2',
                );
                
                if($sql_medc_file->num_rows() == 0){
                    crud::simpan('tbl_trans_medcheck_file', $data_file);
                }else{
                    crud::update('tbl_trans_medcheck_file', 'id', $sql_medc_resm->id, $data_file);
                }
                
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data pemeriksaan resume berhasil disimpan</div>');
  
                redirect(base_url('medcheck/surat/cetak_pdf_rsm_rnp.php?id='.$id.'&id_resm='.$id_rsm));  
                
//                echo '<pre>';
//                print_r($data_file);
//                echo '</pre>';              
//            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_resume_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $item_id    = $this->input->get('item_id');
            $status     = $this->input->get('status');
            $userid     = $this->ion_auth->user()->row()->id;
            
            $sql_medc = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck');
            
            if($sql_medc->num_rows() > 0){
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil dihapus</div>');
                
                /* Transaksi Database */
                $this->db->trans_begin();
                
                # Hapus ke tabel medcheck lab
                $this->db->where('id', general::dekrip($item_id))->delete('tbl_trans_medcheck_resume');

                # Cek status transact MySQL
                if ($this->db->trans_status() === FALSE) {
                    # Rollback jika gagal
                    $this->db->trans_rollback();

                    # Tampilkan pesan error
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Data resume medis gagal dihapus !!</div>');
                } else {
                    $this->db->trans_commit();

                    # Tampilkan pesan sukses jika sudah berhasil commit
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data resume medis berhasil dihapus !!</div>');
                }
            }

            redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_resume_hapus_hsl() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_resm    = $this->input->get('id_resm');
            $item_id    = $this->input->get('item_id');
            $status     = $this->input->get('status');
            $act        = $this->input->get('act');
            $userid     = $this->ion_auth->user()->row()->id;
            
            $sql_medc = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck');
            
            if($sql_medc->num_rows() > 0){
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil dihapus</div>');
                
                /* Transaksi Database */
                $this->db->trans_begin();
                
                # Hapus ke tabel medcheck lab
                $this->db->where('id', general::dekrip($item_id))->delete('tbl_trans_medcheck_resume_det');

                # Cek status transact MySQL
                if ($this->db->trans_status() === FALSE) {
                    # Rollback jika gagal
                    $this->db->trans_rollback();

                    # Tampilkan pesan error
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Data resume medis gagal dihapus !!</div>');
                } else {
                    $this->db->trans_commit();

                    # Tampilkan pesan sukses jika sudah berhasil commit
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data resume medis berhasil dihapus !!</div>');
                }
            }

            redirect(base_url('medcheck/tambah.php?act='.$act.'&id='.$id.'&id_resm='.$id_resm.'&status='.$status));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_resume_hapus_hsl_rnp() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_resm    = $this->input->get('id_resm');
            $item_id    = $this->input->get('item_id');
            $status     = $this->input->get('status');
            $userid     = $this->ion_auth->user()->row()->id;
            
            $sql_medc = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck');
            
            if($sql_medc->num_rows() > 0){                
                /* Transaksi Database */
                $this->db->trans_begin();
                
                # Hapus ke tabel medcheck lab
                $this->db->where('id_resume', general::dekrip($id_resm))->delete('tbl_trans_medcheck_resume_det');

                # Cek status transact MySQL
                if ($this->db->trans_status() === FALSE) {
                    # Rollback jika gagal
                    $this->db->trans_rollback();

                    # Tampilkan pesan error
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Data resume medis gagal dihapus !!</div>');
                } else {
                    $this->db->trans_commit();

                    # Tampilkan pesan sukses jika sudah berhasil commit
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data resume medis berhasil dihapus !!</div>');
                }
            }

            redirect(base_url('medcheck/tambah.php?act=resm_input_rnp&id='.$id.'&id_resm='.$id_resm.'&status='.$status));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_upload() {
        if (akses::aksesLogin() == TRUE) {
            $this->load->helper('file');
            
            $id         = $this->input->post('id');
            $judul      = $this->input->post('judul');
            $ket        = $this->input->post('ket');
            $status     = $this->input->post('status');
            $foto       = $this->input->post('file_foto');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('judul', 'Judul', 'required');
            $this->form_validation->set_rules('tipe_ft', 'Tipe', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                    'judul'     => form_error('judul'),
                    'tipe'      => form_error('tipe_ft'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id.'&status=8'));
            } else {
               $sql_medc    = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
               $sql_pasien  = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row();
               $no_rm       = strtolower($sql_pasien->kode_dpn).$sql_pasien->kode;
               $folder      = realpath('./file/pasien/'.$no_rm);
               
                if (!empty($_FILES['fupload']['name'])) {
                    $config['upload_path']      = $folder;
                    $config['allowed_types']    = 'jpg|png|pdf|jpeg|jfif';
                    $config['remove_spaces']    = TRUE;
                    $config['overwrite']        = TRUE;
                    $config['file_name']        = 'medc_'.$sql_medc->no_rm.'_upl'.sprintf('%05d', rand(1,256)).'_'.strtolower(str_replace(' ', '_', $judul)); // general::dekrip($id).sprintf('%05d', rand(1,256));
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('fupload')) {
                        $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Error : <b>' . $this->upload->display_errors() . '</b></div>');
                        redirect(base_url('medcheck/tambah.php?id='.$id.'&status=8&err='.$this->upload->display_errors()));
                    } else {
                        $f      = $this->upload->data();
                        $path   = $folder . '/';
                        $file   = file_get_contents($f['full_path']);
                        $file64 = 'data:'.$f['file_type'].';base64,'.base64_encode($file);

                        $data_file = array(
                            'id_medcheck'   => $sql_medc->id,
                            'id_pasien'     => $sql_medc->id_pasien,
                            'id_user'       => $this->ion_auth->user()->row()->id,
                            'tgl_simpan'    => date('Y-m-d H:i:s'),
                            'tgl_masuk'     => date('Y-m-d H:i'),
                            'judul'         => $judul,
                            'keterangan'    => $ket,
                            'file_name_ori' => $f['client_name'],
                            'file_name'     => '/file/pasien/'.$no_rm.'/'.$f['file_name'],
                            'file_ext'      => $f['file_ext'],
                            'file_type'     => $f['file_type'],
//                            'file_base64'   => $file64,
                            'status'        => '1',
                        );
                        
                        crud::simpan('tbl_trans_medcheck_file', $data_file);
                        $last_id = crud::last_id();
                        
                        if(!empty($last_id)){
                            $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Berkas berhasil diunggah !!</div>');
                        }
                        
                        redirect(base_url('medcheck/tambah.php?id='.$id.'&status=8'));
                    }
                }else{                    
                    $filename   = 'medc_'.$sql_medc->no_rm.'_upl'.sprintf('%05d', rand(1,256)).'_cam.png';
                    $path       = $folder.'/'.$filename;
                    
                    $data_file = array(
                        'id_medcheck'   => $sql_medc->id,
                        'id_pasien'     => $sql_medc->id_pasien,
                        'id_user'       => $this->ion_auth->user()->row()->id,
                        'tgl_simpan'    => date('Y-m-d H:i:s'),
                        'tgl_masuk'     => date('Y-m-d H:i'),
                        'judul'         => $judul,
                        'keterangan'    => $ket,
                        'file_base64'   => $foto,
                        'file_name'     => '/file/pasien/'.$no_rm.'/'.$filename,
                        'file_ext'      => '.png',
                        'file_type'     => 'image/png',
                        'status'        => '1',
                    );
                    
                    # Pastikan terdapat foto tidak kosong
                    if(!empty($foto)){                    
                        # Buat File Dari kamera
                        general::base64_to_jpeg($foto, $path);
                        
                        # Simpan ke database
                        crud::simpan('tbl_trans_medcheck_file', $data_file);
                        $last_id = crud::last_id(); 

                        if (!empty($last_id)) {
                            $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Berkas berhasil diunggah !!</div>');
                        }                     
                    }else{
                        $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Tidak ada berkas atau foto yang disimpan !!</div>');
                    }
                        


                    redirect(base_url('medcheck/tambah.php?id=' . $id . '&status=8'));
                }
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_upload_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_item    = $this->input->get('item_id');
            $file       = $this->input->get('file');
            $status     = $this->input->get('status');
            
            if(!empty($id_item)){
                $sql_medc    = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_pasien  = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row();
                $no_rm       = strtolower($sql_pasien->kode_dpn).$sql_pasien->kode;
                $folder      = realpath('./'.$file);
               
                $path   = $folder.'/';
                $berkas = $file;
                
                if(file_exists($berkas)){
                    unlink($berkas);
                }
                
                crud::delete('tbl_trans_medcheck_file', 'id', general::dekrip($id_item));                
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Berkas berhasil di hapus</div>');
            }
            
            redirect(base_url('medcheck/tambah.php?id='.$this->input->get('id').'&status='.$this->input->get('status')));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    } 
    
    public function set_medcheck_diskon_item() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $status     = $this->input->post('status');
            $tgl_resep  = $this->input->post('tgl_resep');
            $nm_resep   = $this->input->post('nm_resep');
            $ket        = $this->input->post('ket');
            $act        = $this->input->post('act');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                );

                $this->session->set_flashdata('anamnesa', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id.'status='.$status));
            } else {
                $sql_medc       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row(); 
                $pengaturan     = $this->db->get('tbl_pengaturan')->row();
                
                $nomer          = $this->db->where('MONTH(tgl_simpan)', date('m'))->get('tbl_trans_medcheck_resep')->num_rows() + 1;
                $no_surat       = sprintf('%03d', $nomer).'/'.$pengaturan->kode_resep.'/'.date('m').'/'.date('Y');
                
                $data = array(
                    'tgl_simpan'     => (!empty($tgl_masuk) ? $this->tanggalan->tgl_indo_sys($tgl_resep) : date('Y-m-d')).' '.date('H:i:s'),
                    'id_medcheck'   => $sql_medc->id,
                    'id_pasien'     => $sql_medc->id_pasien,
                    'id_user'       => $this->ion_auth->user()->row()->id,
                    'no_resep'      => $no_surat,
                    'status'        => '0',
                );

                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data surat berhasil disimpan</div>');
                crud::simpan('tbl_trans_medcheck_resep', $data);
                $last_id = crud::last_id();
                
                redirect(base_url('medcheck/tambah.php?id='.general::enkrip($sql_medc->id).'&status='.$status.'&id_resep='.general::enkrip($last_id)));
                
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_retur() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $pasien     = $this->input->post('id_pasien');
            $tgl        = $this->input->post('tgl');
            $ket        = $this->input->post('ket');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id' => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/retur/retur.php'));
            } else {                        
                $data = array(
                    'id'         => general::dekrip($id),
                    'tgl'        => $this->tanggalan->tgl_indo_sys($tgl),
                    'keterangan' => $ket,
                );
                
                $this->session->set_userdata('trans_medcheck_retur', $data);
                redirect(base_url('medcheck/retur/retur.php?act=retur_input&id='.$id));

                
//                $this->session->set_userdata('trans_medcheck_retur', $data);
//                redirect(base_url('medcheck/retur.php?id='.general::enkrip(rand(1,256))));
                
//                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Transaksi berhasil diselesaikan</div>');
//                crud::update('tbl_trans_medcheck', 'id', general::dekrip($id), $data);             
//                redirect(base_url('medcheck/index.php?tipe='.$sql_medc->tipe));
//                redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_kwi() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $status     = $this->input->post('status');
            $dari       = $this->input->post('dari');
            $nominal    = $this->input->post('nominal');
            $ket        = $this->input->post('keterangan');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('dari', 'Dari', 'required');
            $this->form_validation->set_rules('nominal', 'Sejumlah', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'       => form_error('id'),
                    'dari'     => form_error('dari'),
                    'nominal'  => form_error('nominal'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
            } else {
                $sql_medc_rad = $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_rad')->row();

                $data = array(
                    'tgl_simpan'        => date('Y-m-d H:i:s'),
                    'tgl_masuk'         => date('Y-m-d'),
                    'id_medcheck'       => general::dekrip($id),
                    'id_user'           => $this->ion_auth->user()->row()->id,
                    'dari'              => $dari,
                    'nominal'           => general::format_angka_db($nominal),
                    'ket'               => $ket
                );
                
//                echo '<pre>';
//                print_r($data);
                
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data kwitansi berhasil disimpan</div>');
                crud::simpan('tbl_trans_medcheck_kwi', $data);
                
                redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status.(!empty($route) ? '&route='.$route : '')));                
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_medcheck_kwi_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_kwi     = $this->input->get('id_kwi');
            $status     = $this->input->get('status');
            $act        = $this->input->get('act');
            $rute       = $this->input->get('route');
            
            if(!empty($id)){
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Item berhasil di hapus</div>');
                crud::delete('tbl_trans_medcheck_kwi', 'id', general::dekrip($id_kwi));
            }
            
            redirect(base_url((!empty($rute) ? $rute : 'medcheck/tambah.php?'.(!empty($act) ? 'act='.$act.'&' : '').'id='.$id.(!empty($id_kwi) ? '&id_kwi='.$id_kwi.'&' : '').'&status='.$status)));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_icd() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_rm      = $this->input->post('id_rm');
            $icd        = $this->input->post('icd');
            $status     = $this->input->post('status');
            $st_icd     = $this->input->post('status_icd');
            $rute       = $this->input->post('route');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('icd', 'ICD', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'                => form_error('id'),
                    'icd'               => form_error('icd'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
            } else {
                $sql_medc = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_icd  = $this->db->where('id', $icd)->get('tbl_m_icd')->row();
                
                $is_doc_id  = ($grup->name == 'dokter' ? $this->ion_auth->user()->row()->id : '0');
                        
                $data = array(
                    'tgl_simpan'        => date('Y-m-d H:i:s'),
                    'id_medcheck'       => (int)$sql_medc->id,
                    'id_medcheck_rm'    => (int)(!empty($id_rm) ? general::dekrip($id_rm) : '0'),
                    'id_icd'            => (int)(!empty($icd) ? $icd : '0'),
                    'id_user'           => (int)$this->ion_auth->user()->row()->id,
                    'id_dokter'         => (int)$is_doc_id,
                    'kode'              => $sql_icd->kode,
                    'diagnosa'          => $sql_icd->diagnosa,
                    'diagnosa_en'       => $sql_icd->diagnosa_en,
                    'status_icd'        => (!empty($st_icd) ? $st_icd : '0'),
                );
                
                # Simpan ke tabel ICD
                $this->db->insert('tbl_trans_medcheck_icd', $data);
                
                $this->session->set_flashdata('medcheck_toast', 'toastr.success("ICD berhasil di tambahkan !");');
                redirect(base_url((!empty($rute) ? $rute : 'medcheck/tambah.php?id='.$id.'&status='.$status)));
//               
//                echo '<pre>';
//                print_r($data);
//                echo '</pre>';
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function set_medcheck_icd_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_resm    = $this->input->get('id_resm');
            $item_id    = $this->input->get('id_item');
            $status     = $this->input->get('status');
            $act        = $this->input->get('act');
            $userid     = $this->ion_auth->user()->row()->id;
            
            $sql_medc = $this->db->where('id', general::dekrip($item_id))->get('tbl_trans_medcheck_icd');
            
            if($sql_medc->num_rows() > 0){
                /* Transaksi Database */
                $this->db->trans_begin();
                
                # Hapus ke tabel medcheck lab
                $this->db->where('id', general::dekrip($item_id))->delete('tbl_trans_medcheck_icd');

                # Cek status transact MySQL
                if ($this->db->trans_status() === FALSE) {
                    # Rollback jika gagal
                    $this->db->trans_rollback();

                    # Tampilkan pesan error
                    $this->session->set_flashdata('medcheck_toast', 'toastr.error("ICD gagal dihapus !");');
                } else {
                    $this->db->trans_commit();

                    # Tampilkan pesan sukses jika sudah berhasil commit
                    $this->session->set_flashdata('medcheck_toast', 'toastr.success("ICD Berhasil dihapus !");');
                }
            }

            redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }


    public function cart_medcheck_simpan() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_lab     = $this->input->post('id_lab');
            $id_lab_kat = $this->input->post('id_lab_kat');
            $id_rad     = $this->input->post('id_rad');
            $id_item    = $this->input->post('id_item');
            $id_dokter  = $this->input->post('id_dokter');
            $id_resep   = $this->input->post('id_resep');
            $hrg        = $this->input->post('harga');
            $jml        = $this->input->post('jml');
            $diskon1    = $this->input->post('disk1');
            $diskon2    = $this->input->post('disk2');
            $diskon3    = $this->input->post('disk3');
            $pot        = $this->input->post('potongan');
            $keterangan = $this->input->post('keterangan');
            $hasil      = $this->input->post('hasil_lab');
            $status     = $this->input->post('status');
            $status_hsl = $this->input->post('status_hsl');
            $status_itm = $this->input->post('status_item');
            $act        = $this->input->post('act');
            $rute       = $this->input->post('route');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('id_item', 'Kode', 'required');
            $this->form_validation->set_rules('harga', 'Harga', 'required');
            $this->form_validation->set_rules('jml', 'Jml', 'required|greater_than[0]');
            
            $this->form_validation->set_message('greater_than', 'Harap gunakan menu retur. Kolom %s harus lebih besar dari 0');        
            
            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                    'kode'      => form_error('id_item'),
                    'harga'     => form_error('harga'),
                    'jml'       => form_error('jml'),
                );

                $this->session->set_flashdata('form_error', $msg_error);
                $this->session->set_flashdata('jml', $jml);
                
                redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status.'&id_produk='.$id_item.'&harga='.(float)$hrg));
            } else {
                $sql_medc   = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_item   = $this->db->where('id', general::dekrip($id_item))->get('tbl_m_produk')->row();
                $sql_medc_ck= $this->db->where('id_medcheck', general::dekrip($id))->where('id_item', general::dekrip($id_item))->get('tbl_trans_medcheck_det');
                $sql_radg_ck= $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_rad');
                $sql_sat    = $this->db->where('id', $sql_item->id_satuan)->get('tbl_m_satuan')->row();
                $harga      = general::format_angka_db($hrg);
                $potongan   = general::format_angka_db($pot);
                $jml_pot    = $potongan * $jml;
                $dokter     = (!empty($id_dokter) ? $id_dokter : $sql_medc->id_dokter);
                
                $disk1      = $harga - (($diskon1 / 100) * $harga);
                $disk2      = $disk1 - (($diskon2 / 100) * $disk1);
                $disk3      = $disk2 - (($diskon3 / 100) * $disk2);
                $diskon     = $harga - $disk3;
                $subtotal   = ($disk3 - $potongan) * (int)$jml;

                $data = array(
                    'tgl_simpan'    => date('Y-m-d H:i:s'),
                    'tgl_modif'     => date('Y-m-d H:i:s'),
                    'tgl_masuk'     => date('Y-m-d H:i:s'),
                    'id_medcheck'   => (int)$sql_medc->id,
                    'id_item'       => (int)$sql_item->id,
                    'id_item_kat'   => (int)$sql_item->id_kategori,
                    'id_item_sat'   => (int)$sql_item->id_satuan,
                    'id_user'       => (int)$this->ion_auth->user()->row()->id,
                    'id_dokter'     => (int)$dokter,
                    'id_lab'        => (int)general::dekrip($id_lab),
                    'id_lab_kat'    => (int)general::dekrip($id_lab_kat),
                    'id_rad'        => (int)general::dekrip($id_rad),
                    'kode'          => $sql_item->kode,
                    'item'          => $sql_item->produk,
                    'keterangan'    => $keterangan,
                    'hasil_lab'     => $hasil,
                    'harga'         => $harga,
                    'jml'           => (int)$jml,
                    'jml_satuan'    => '1',
                    'satuan'        => $sql_sat->satuanTerkecil,
                    'disk1'         => (float)$diskon1,
                    'disk2'         => (float)$diskon2,
                    'disk3'         => (float)$diskon3,
                    'diskon'        => (float)$diskon,
                    'potongan'      => (float)general::format_angka_db($jml_pot),
                    'subtotal'      => (float)$subtotal,
                    'status'        => (!empty($status_itm) ? $status_itm : $sql_item->status),
                    'status_hsl'    => (!empty($status_hsl) ? $status_hsl : '0'),
                );  
                
                # Cek apakah sudah di posting atau belum ?
                # Kalau sudah yg bisa input hny rad, lab, dokter
                if($sql_medc->status < 5){
                    # Start Transact SQL
                    $this->db->trans_off();
                    $this->db->trans_start();
                    
                    # Simpan pada tabel medcheck det
                    $this->db->insert('tbl_trans_medcheck_det', $data);

                    $this->db->trans_complete();
                    
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data item sudah disimpan !!</div>');                    
                }else{
                    
                    if(akses::hakSA() == TRUE OR akses::hakOwner() == TRUE OR akses::hakRad() == TRUE OR akses::hakAnalis() == TRUE){
                        # Start Transact SQL
                        $this->db->trans_off();
                        $this->db->trans_start();
                    
                        # Simpan pada tabel medcheck det
                        $this->db->insert('tbl_trans_medcheck_det', $data);

                        $this->db->trans_complete();
                    
                        $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data item sudah disimpan !!</div>');   
                    }else{
                        $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Transaksi ini sudah diposting, sehingga tidak di perbolehkan menambah entrian</div>');
                    }
                }
                
                redirect(base_url('medcheck/tambah.php?'.(!empty($act) ? 'act='.$act.'&' : '').'id='.$id.(!empty($id_lab) ? '&id_lab='.$id_lab : '').(!empty($id_rad) ? '&id_rad='.$id_rad : '').(!empty($id_resep) ? '&id_resep='.$id_resep : '').'&status='.$status.(!empty($rute) ? '&route='.$rute : '')));
                      
////                # Cek apakah ada item radiologi
////                if($status == '5' AND $sql_radg_ck->num_rows() == 0){
////                    $pengaturan     = $this->db->get('tbl_pengaturan')->row();
////                    $nomer          = $this->db->where('MONTH(tgl_simpan)', date('m'))->get('tbl_trans_medcheck_rad')->num_rows() + 1;
////                    $no_surat       = sprintf('%03d', $nomer).'/'.$pengaturan->kode_rad.'/'.date('m').'/'.date('Y');
////                    
////                    $data_radc = array(
////                        'tgl_simpan'    => date('Y-m-d H:i:s'),
////                        'tgl_masuk'     => date('Y-m-d H:i:s'),
////                        'id_medcheck'   => (int)$sql_medc->id,
////                        'id_pasien'     => (int)$sql_medc->id_pasien,
////                        'id_user'       => (int)$this->ion_auth->user()->row()->id,
////                        'no_rad'        => $no_surat,
////                        'status'        => '0',
////                    );
////                    
////                    # Masukkan ke tabel medcheck radiologi
////                    $this->db->insert('tbl_trans_medcheck_rad', $data_radc);                 
////                }
//                
//                $this->db->insert('tbl_trans_medcheck_det', $data);
//                
//                # Cek status transact MySQL
//                if ($this->db->trans_status() === FALSE){
//                    # Rollback jika gagal
//                    $this->db->trans_rollback();
//                    
//                    # Tampilkan pesan error
//                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Data item gagal disimpan, silahkan masukkan ulang !!</div>');
//                }else{
//                    $this->db->trans_commit();
//                             
//                    $this->db->trans_complete();           
//                    # Tampilkan pesan sukses jika sudah berhasil commit
//                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data item sudah disimpan !!</div>');
//                }
//               
//                echo '<pre>';
//                print_r($data);
//                echo '</pre>';
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }      

    public function cart_medcheck_retur_ranap() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_item    = $this->input->post('id_item');
            $id_produk  = $this->input->post('id_produk');
            $no_urut    = $this->input->post('no_urut');
            $jml        = $this->input->post('jml');
            $status     = $this->input->post('status');
            $status_hsl = $this->input->post('status_hsl');
            $status_itm = $this->input->post('status_item');
            $act        = $this->input->post('act');
            $rute       = $this->input->post('route');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('id_item', 'Kode', 'required');
            $this->form_validation->set_rules('jml', 'Jml', 'required|greater_than[0]');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'    => form_error('id'),
                    'kode'  => form_error('id_item'),
                    'jml'   => form_error('jml')
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/retur.php?id='.$id.'&item_id='.$id_item.'&id_produk='.$id_item));
            } else {
                $sql_medc       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_medc_det   = $this->db->where('id', general::dekrip($id_item))->get('tbl_trans_medcheck_det')->row();
                $sql_item       = $this->db->where('id', general::dekrip($id_produk))->get('tbl_m_produk')->row();
                $sql_sat        = $this->db->where('id', $sql_item->id_satuan)->get('tbl_m_satuan')->row();
                $jml_akhir      = $sql_medc_det->jml - $jml;
                
                $disk1          = $sql_medc_det->harga - (($sql_medc_det->disk1 / 100) * $sql_medc_det->harga);
                $disk2          = $disk1 - (($sql_medc_det->disk2 / 100) * $disk1);
                $disk3          = $disk2 - (($sql_medc_det->disk3 / 100) * $disk2);
                $diskon         = $sql_medc_det->harga - $disk3;
                $subtotal       = ($disk3 - $sql_medc_det->potongan) * (int)$jml_akhir;
                
                $data = array(
                    'tgl_simpan'        => date('Y-m-d H:i:s'),
                    'id_medcheck'       => (int)$sql_medc->id,
                    'id_medcheck_det'   => (int)$sql_medc_det->id,
                    'id_pasien'         => (int)$sql_medc->id_pasien,
                    'id_item'           => (int)$sql_item->id,
                    'id_user'           => (int)$this->ion_auth->user()->row()->id,
                    'kode'              => $sql_item->kode,
                    'item'              => $sql_item->produk,
                    'jml'               => (int)$jml,
                    'status_item'       => (!empty($status_itm) ? $status_itm : $sql_item->status),
                );
                
                $data_det = array(
                    'tgl_modif'         => date('Y-m-d H:i:s'),
                    'jml'               => (int)$jml_akhir,
                    'subtotal'		=> $subtotal
                );

                if($jml_akhir < 0){
                    # Gagal Return
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Item <b>'.$sql_item->produk.'</b> pada no '.$no_urut.', maksimal retur hanya <b>'.(float)$sql_medc_det->jml.'</b> !!</div>');
                }else{
                    # Start Transact SQL
                    $this->db->trans_off();
                    $this->db->trans_start();

                    # Kurangi Jumlah yang ada di medcheck det
                    $this->db->where('id', $sql_medc_det->id)->update('tbl_trans_medcheck_det', $data_det);

                    # Masukkan ke table retur
                    $this->db->insert('tbl_trans_medcheck_retur', $data);

                    $this->db->trans_complete();
                    
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        
                        # Retur Gagal
                        $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Data retur sudah gagal disimpan !!</div>');
                    }else{
                        $this->db->trans_commit();
                        
                        # Success Return
                        $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data retur sudah disimpan !!</div>');
                    }

                    
                    
                }
                
                redirect(base_url('medcheck/retur.php?id='.$id));
                
                    
//                echo '<pre>';
//                print_r($data);
//                echo '</pre>';
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }      

    // Update Invoice Dari kasir
    public function cart_medcheck_update() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_lab     = $this->input->post('id_lab');
            $id_item    = $this->input->post('item_id');
            $id_item_det= $this->input->post('item_id_det');
            $id_dokter  = $this->input->post('id_dokter');
            $id_resep   = $this->input->post('id_resep');
            $hrg        = $this->input->post('harga');
            $jml        = $this->input->post('jml');
            $diskon1    = $this->input->post('disk1');
            $diskon2    = $this->input->post('disk2');
            $diskon3    = $this->input->post('disk3');
            $pot        = $this->input->post('potongan');
            $keterangan = $this->input->post('keterangan');
            $hasil      = $this->input->post('hasil_lab');
            $status     = $this->input->post('status');
            $act        = $this->input->post('act');
            $lab_id     = $this->input->post('id_lab');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('item_id', 'Item', 'required');
            $this->form_validation->set_rules('harga', 'Harga', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                    'kode'      => form_error('id_item'),
                    'harga'     => form_error('harga')
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/invoice/bayar.php?id='.$id));
            } else {
                $sql_medc   = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_item   = $this->db->where('id', general::dekrip($id_item))->get('tbl_m_produk')->row();
                $sql_medc_ck= $this->db->where('id', general::dekrip($id_item_det))->get('tbl_trans_medcheck_det');
                $sql_radg_ck= $this->db->where('id_medcheck', general::dekrip($id))->get('tbl_trans_medcheck_rad');
                $sql_sat    = $this->db->where('id', $sql_item->id_satuan)->get('tbl_m_satuan')->row();
                $harga      = general::format_angka_db($hrg);
                $potongan   = general::format_angka_db($pot);
                $jml_pot    = $potongan * $jml;
                $dokter     = (!empty($id_dokter) ? $id_dokter : $sql_medc->id_dokter);
                
                $disk1      = $harga - (($diskon1 / 100) * $harga);
                $disk2      = $disk1 - (($diskon2 / 100) * $disk1);
                $disk3      = $disk2 - (($diskon3 / 100) * $disk2);
                $diskon     = $harga - $disk3;
                $subtotal   = ($disk3 - $potongan) * (int)$jml;
                
                $data = array(
//                    'tgl_simpan'    => date('Y-m-d H:i:s'),
                    'tgl_modif'     => date('Y-m-d H:i:s'),
//                    'tgl_masuk'     => date('Y-m-d H:i:s'),
//                    'id_medcheck'   => (int)$sql_medc->id,
//                    'id_item'       => (int)$sql_item->id,
//                    'id_item_kat'   => (int)$sql_item->id_kategori,
//                    'id_item_sat'   => (int)$sql_item->id_satuan,
//                    'id_user'       => (int)$this->ion_auth->user()->row()->id,
//                    'id_dokter'     => (int)$dokter,
//                    'id_lab'        => (int)$lab_id,
                    'kode'          => $sql_item->kode,
                    'item'          => $sql_item->produk,
//                    'keterangan'    => $keterangan,
//                    'hasil_lab'     => $hasil,
                    'harga'         => $harga,
                    'jml'           => (int)$jml,
                    'jml_satuan'    => '1',
                    'satuan'        => $sql_sat->satuanTerkecil,
                    'disk1'         => (float)$diskon1,
                    'disk2'         => (float)$diskon2,
                    'disk3'         => (float)$diskon3,
                    'diskon'        => (float)$diskon,
                    'potongan'      => (float)general::format_angka_db($jml_pot),
                    'subtotal'      => (float)$subtotal,
//                    'status'        => (int)$status,
                );
                
//                if($status == '5' AND $sql_radg_ck->num_rows() == 0){
//                    $pengaturan     = $this->db->get('tbl_pengaturan')->row();
//                    $nomer          = $this->db->where('MONTH(tgl_simpan)', date('m'))->get('tbl_trans_medcheck_rad')->num_rows() + 1;
//                    $no_surat       = sprintf('%03d', $nomer).'/'.$pengaturan->kode_rad.'/'.date('m').'/'.date('Y');
//                    
//                    $data_radc = array(
//                        'tgl_simpan'    => date('Y-m-d H:i:s'),
//                        'tgl_masuk'     => date('Y-m-d H:i:s'),
//                        'id_medcheck'   => (int)$sql_medc->id,
//                        'id_pasien'     => (int)$sql_medc->id_pasien,
//                        'id_user'       => (int)$this->ion_auth->user()->row()->id,
//                        'no_rad'        => $no_surat,
//                        'status'        => '0',
//                    );
//                    
//                    crud::simpan('tbl_trans_medcheck_rad', $data_radc);                    
//                }
                
                if($sql_medc_ck->num_rows() > 0){
                    $last_id = $sql_medc_ck->row()->id;
                    crud::update('tbl_trans_medcheck_det', 'id', $last_id, $data);
                }else{
                    crud::simpan('tbl_trans_medcheck_det', $data);
                    $last_id = crud::last_id();
                }
                
                
                // Update price di total penjualan
                $sql_medc_det_sum = $this->db->select('SUM(diskon) AS diskon, SUM(potongan) AS potongan, SUM(subtotal) AS subtotal')->where('id_medcheck', $sql_medc->id)->get('tbl_trans_medcheck_det')->row();
                $jml_total      = $sql_medc_det_sum->subtotal;
                $jml_diskon     = $sql_medc_det_sum->diskon;
                $jml_potongan   = $sql_medc_det_sum->potongan;
//                $diskon         = (($sql_medc_det_sum->potongan + $sql_medc_det_sum->diskon) / $sql_medc_det_sum->subtotal) * 100;
                $diskon         = (($sql_medc->jml_total - $sql_medc_det_sum->subtotal) / $sql_medc->jml_total) * 100;
                $jml_subtotal   = $jml_total - $jml_diskon - $jml_potongan;
                        
                $data_medc_tot = array(
//                    'jml_total'     => (float)$jml_total,
//                    'jml_diskon'    => $jml_diskon,
//                    'diskon'        => $diskon,
                    'jml_potongan'  => $jml_potongan,
                    'jml_subtotal'  => (float)$jml_total,
                    'jml_gtotal'    => (float)$jml_total,
                );
                
                crud::update('tbl_trans_medcheck', 'id', $sql_medc->id, $data_medc_tot);
                
//                echo '<pre>';
//                print_r($data);
//                echo '</pre>';                
//                echo '<pre>';
//                print_r($data_medc_tot);
//                echo '</pre>';                
                
                redirect(base_url('medcheck/invoice/bayar.php?id='.$id));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }      

    public function cart_medcheck_resep() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_resep   = $this->input->post('id_resep');
            $id_item    = $this->input->post('id_item');
            $id_dokter  = $this->input->post('id_dokter');
            $hrg        = $this->input->post('harga');
            $jml        = $this->input->post('jml');
            $diskon1    = $this->input->post('disk1');
            $diskon2    = $this->input->post('disk2');
            $diskon3    = $this->input->post('disk3');
            $pot        = $this->input->post('potongan');
            $keterangan = $this->input->post('keterangan');
            $hasil      = $this->input->post('hasil_lab');
            $status     = $this->input->post('status');
            $dos_jml1   = $this->input->post('dos_jml1');
            $dos_jml2   = $this->input->post('dos_jml2');
            $dos_sat    = $this->input->post('dos_sat');
            $dos_wkt    = $this->input->post('dos_wkt');
            $dos_ket    = $this->input->post('dos_ket');
            $act        = $this->input->post('act');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('id_item', 'Kode', 'required');
            $this->form_validation->set_rules('harga', 'Harga', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                    'kode'      => form_error('id_item'),
                    'harga'     => form_error('harga'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id));
            } else {
                $sql_medc   = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_item   = $this->db->where('id', general::dekrip($id_item))->get('tbl_m_produk')->row();
                $sql_sat    = $this->db->where('id', $sql_item->id_satuan)->get('tbl_m_satuan')->row();
                $sql_sat_pk = $this->db->where('id', $dos_sat)->get('tbl_m_satuan_pakai')->row();
                $harga      = general::format_angka_db($hrg);
                $potongan   = general::format_angka_db($pot);
                $dokter     = (!empty($id_dokter) ? $id_dokter : $sql_medc->id_dokter);
                
                $disk1      = $harga - (($diskon1 / 100) * $harga);
                $disk2      = $disk1 - (($diskon2 / 100) * $disk1);
                $disk3      = $disk2 - (($diskon3 / 100) * $disk2);
                $diskon     = $harga - $disk3;
                $subtotal   = ($disk3 - $potongan) * (int)$jml;
                
                $keranjang = array(
                    'id'      => rand(1,1024).$sql_item->id,
                    'qty'     => (int)$jml,
                    'price'   => (!empty($disk3) ? $disk3 : 0),
                    'name'    => str_replace(array('\'','\\','/'), ' ', $sql_item->produk),
                    'options' => array(
                        'id_medcheck'   => (int)$sql_medc->id,
                        'id_item'       => (int)$sql_item->id,
                        'id_item_kat'   => (int)$sql_item->id_kategori,
                        'id_item_sat'   => (int)$sql_item->id_satuan,
                        'id_user'       => (int)$this->ion_auth->user()->row()->id,
                        'id_dokter'     => (int)$dokter,
                        'tgl_simpan'    => date('Y-m-d H:i:s'),
                        'kode'          => $sql_item->kode,
                        'jml'           => (int)$jml,
                        'jml_satuan'    => '1',
                        'satuan'        => $sql_sat->satuanTerkecil,
                        'harga'         => general::format_angka_db($harga),
                        'disk1'         => (float)$diskon1,
                        'disk2'         => (float)$diskon2,
                        'disk3'         => (float)$diskon3,
                        'potongan'      => (float)$potongan,
                        'dos_jml1'      => $dos_jml1,
                        'dos_sat'       => $sql_sat_pk->satuan,
                        'dos_jml2'      => $dos_jml2,
                        'dos_wkt'       => general::tipe_obat_pakai($dos_wkt),
                        'dos_ket'       => $dos_ket,
                    )
                );

                $this->cart->insert($keranjang);
                redirect(base_url('medcheck/tambah.php?id='.$id.(!empty($id_resep) ? '&id_resep='.$id_resep : '').(!empty($act) ? '&act='.$act : '').'&status='.$status));
//
//                echo '<pre>';
//                print_r($keranjang);
//                echo '</pre>';
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }    

    public function cart_medcheck_resep_upd1() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_resep   = $this->input->post('id_resep');
            $id_item    = $this->input->post('id_item');
            $id_dokter  = $this->input->post('id_dokter');
            $hrg        = $this->input->post('harga');
            $jml        = $this->input->post('jml');
            $diskon1    = $this->input->post('disk1');
            $diskon2    = $this->input->post('disk2');
            $diskon3    = $this->input->post('disk3');
            $pot        = $this->input->post('potongan');
            $keterangan = $this->input->post('keterangan');
            $hasil      = $this->input->post('hasil_lab');
            $dos_jml1   = $this->input->post('dos_jml1');
            $dos_jml2   = $this->input->post('dos_jml2');
            $dos_sat    = $this->input->post('dos_sat');
            $dos_wkt    = $this->input->post('dos_wkt');
            $dos_ket    = $this->input->post('dos_ket');
            $act        = $this->input->post('act');
            $ed         = $this->input->post('ed');
            $ket        = $this->input->post('keterangan');
            $status     = $this->input->post('status');
            $status_mkn = $this->input->post('status_mkn');
            $status_res = $this->input->post('tipe');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('kode', 'Kode', 'required');
            $this->form_validation->set_rules('harga', 'Harga', 'required');
            $this->form_validation->set_rules('jml', 'Jml', 'required|greater_than[0]');
            
            $this->form_validation->set_message('greater_than', 'Harap gunakan menu retur. Kolom %s harus lebih besar dari 0'); 

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                    'kode'      => form_error('kode'),
                    'harga'     => form_error('harga'),
                    'jml'       => form_error('jml'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/resep/tambah.php?id='.$id.'&id_resep='.$id_resep.'&status='.$status.'&id_item='.$id_item.'&harga='.$hrg));
            } else {
                $sql_medc   = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_pnjm   = $this->db->where('id', $sql_medc->tipe_bayar)->get('tbl_m_penjamin')->row();
                $sql_item   = $this->db->where('id', general::dekrip($id_item))->get('tbl_m_produk')->row();
                $sql_sat    = $this->db->where('id', $sql_item->id_satuan)->get('tbl_m_satuan')->row();
                $sql_sat_pk = $this->db->where('id', $dos_sat)->get('tbl_m_satuan_pakai')->row();
                $harga      = general::format_angka_db($hrg);
                $ass        = $harga * $sql_pnjm->persen;
                $harga_tot  = ($sql_item->status_racikan == '1' ? $harga : ($sql_pnjm->persen != 0 ? $ass : $harga)); # Jika penjamin asuransi, maka harga obat di tambah sesuai setelan % pada database
                $potongan   = general::format_angka_db($pot);
                $dokter     = (!empty($id_dokter) ? $id_dokter : $sql_medc->id_dokter);
                
                $disk1      = $harga_tot - (($diskon1 / 100) * $harga_tot);
                $disk2      = $disk1 - (($diskon2 / 100) * $disk1);
                $disk3      = $disk2 - (($diskon3 / 100) * $disk2);
                $diskon     = $harga_tot - $disk3;
                $subtotal   = ($disk3 - $potongan) * (int)$jml;

                $data_resep = array(
                    'id_medcheck'   => (int)$sql_medc->id,
                    'id_resep'      => (int)general::dekrip($id_resep),
                    'id_item'       => (int)$sql_item->id,
                    'id_item_kat'   => (int)$sql_item->id_kategori,
                    'id_item_sat'   => (int)$sql_item->id_satuan,
                    'id_user'       => $this->ion_auth->user()->row()->id,
                    'tgl_simpan'    => date('Y-m-d H:i:s'),
                    'tgl_modif'     => date('Y-m-d H:i:s'),
//                    'tgl_ed'        => (!empty($ed) ? $this->tanggalan->tgl_indo_sys($ed) : '0000-00-00'),
                    'kode'          => $sql_item->kode,
                    'item'          => $sql_item->produk,
                    'dosis'         => (!empty($dos_jml1) ? $dos_jml1.' '.$sql_sat_pk->satuan.' Tiap '.$dos_jml2.' '.general::tipe_obat_pakai($dos_wkt) : ''),
                    'dosis_ket'     => $dos_ket,
                    'keterangan'    => $ket,
                    'harga'         => (!empty($disk3) ? round($disk3) : 0),
                    'jml'           => (int)$jml,
                    'jml_satuan'    => '1',
                    'satuan'        => $sql_sat->satuanTerkecil,
                    'status'        => (int)$status,
                    'status_resep'  => '0',
                    'status_pj'     => ($ass > 0 ? '1' : '0'),
                    'status_mkn'    => (!empty($status_mkn) ? $status_mkn : '0'),
                );
                
                if($sql_medc->status < 5){
                    # Transactional database
                    $this->db->query('SET autocommit = 0;');
                    $this->db->trans_start();
                
                    # Simpan ke tabel resep
                    $this->db->insert('tbl_trans_medcheck_resep_det', $data_resep);
                
                    # Complete
                    $this->db->trans_complete();
                    
                    $this->session->set_flashdata('medcheck_toast', 'toastr.success("Data item berhasil disimpan !!")');
                }
                                
                redirect(base_url('medcheck/tambah.php?'.(!empty($act) ? 'act='.$act.'&' : '').'id='.$id.'&id_resep='.$id_resep.'&status='.$status));

//                echo '<pre>';
//                print_r($data_resep);
//                echo '</pre>';
//                echo '<pre>';
//                print_r($keranjang);
//                echo '</pre>';
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }    

    public function cart_medcheck_resep_upd2() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_resep   = $this->input->post('id_resep');
            $id_item    = $this->input->post('id_item_resep');
            $id_dokter  = $this->input->post('id_dokter');
            $hrg        = $this->input->post('harga');
            $jml        = $this->input->post('jml');
            $diskon1    = $this->input->post('disk1');
            $diskon2    = $this->input->post('disk2');
            $diskon3    = $this->input->post('disk3');
            $pot        = $this->input->post('potongan');
            $keterangan = $this->input->post('keterangan');
            $hasil      = $this->input->post('hasil_lab');
            $status     = $this->input->post('status');
            $dos_jml1   = $this->input->post('dos_jml1');
            $dos_jml2   = $this->input->post('dos_jml2');
            $dos_sat    = $this->input->post('dos_sat');
            $dos_wkt    = $this->input->post('dos_wkt');
            $dos_ket    = $this->input->post('dos_ket');
            $act        = $this->input->post('act');
            $status_res = $this->input->post('tipe');
            $ed         = $this->input->post('ed');
            $ket        = $this->input->post('keterangan');
            $status_mkn = $this->input->post('status_mkn');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('kode', 'Kode', 'required');
            $this->form_validation->set_rules('harga', 'Harga', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                    'kode'      => form_error('kode'),
                    'harga'     => form_error('harga'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id));
            } else {
                $sql_medc   = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_res_rw = $this->db->where('id', general::dekrip($id_item))->get('tbl_trans_medcheck_resep_det')->row();
                $sql_item   = $this->db->where('id', $sql_res_rw->id_item)->get('tbl_m_produk')->row();
                $sql_sat    = $this->db->where('id', $sql_item->id_satuan)->get('tbl_m_satuan')->row();
                $sql_sat_pk = $this->db->where('id', $dos_sat)->get('tbl_m_satuan_pakai')->row();
                $harga      = general::format_angka_db($hrg);
                $ass        = $harga * $sql_pnjm->persen;
                $harga_tot  = ($sql_item->status_racikan == '1' ? $harga : ($sql_pnjm->persen != 0 ? $ass : $harga)); # Jika penjamin asuransi, maka harga obat di tambah sesuai setelan % pada database
                $potongan   = general::format_angka_db($pot);
                $dokter     = (!empty($id_dokter) ? $id_dokter : $sql_medc->id_dokter);
                
                $disk1      = $harga_tot - (($diskon1 / 100) * $harga_tot);
                $disk2      = $disk1 - (($diskon2 / 100) * $disk1);
                $disk3      = $disk2 - (($diskon3 / 100) * $disk2);
                $diskon     = $harga - $disk3;
                $subtotal   = ($disk3 - $potongan) * (int)$jml;
                
                $data_resep = array(
//                    'tgl_ed'        => (!empty($ed) ? $this->tanggalan->tgl_indo_sys($ed) : '0000-00-00'),
                    'jml'           => (int)$jml,
                    'dosis'         => (!empty($dos_jml1) ? $dos_jml1.' '.$sql_sat_pk->satuan.' Tiap '.$dos_jml2.' '.general::tipe_obat_pakai($dos_wkt) : ''),
                    'dosis_ket'     => $dos_ket,
                    'keterangan'    => $ket,
                    'status_mkn'    => (!empty($status_mkn) ? $status_mkn : '0'),
                    'status_resep'  => (int)$status_res, 
                );
                
                # Transactional database
                $this->db->trans_start();
                
                # Update ke tabel resep
                $this->db->where('id', $sql_res_rw->id)->update('tbl_trans_medcheck_resep_det', $data_resep);
                
                # Complete
                $this->db->trans_complete();
                
                $this->session->set_flashdata('medcheck_toast', 'toastr.success("Data item berhasil disimpan !!")');
                redirect(base_url('medcheck/tambah.php?'.(!empty($act) ? 'act=res_input&' : '').'id='.$id.'&id_resep='.$id_resep.'&status='.$status));

//                echo $sql_res_rw->id;
//                echo '<pre>';
//                print_r($data_resep);
//                echo '</pre>';
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }    

    public function cart_medcheck_resep_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_resep   = $this->input->get('id_resep');
            $id_item    = $this->input->get('id_item');
            $status     = $this->input->get('status');
            
            if(!empty($id_item)){
                $cart = array(
                    'rowid' => $id_item,
                    'qty'   => '0'
                );
                $this->cart->update($cart);
            }            
            
            redirect(base_url('medcheck/tambah.php?id='.$id.(!empty($id_resep) ? '&id_resep='.$id_resep : '').'&status='.$status));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }  

    public function cart_medcheck_resep_item_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_resep   = $this->input->get('id_resep');
            $id_item    = $this->input->get('id_item');
            $status     = $this->input->get('status');
            
            if(!empty($id_item)){                
                crud::delete('tbl_trans_medcheck_resep_det', 'id', general::dekrip($id_item));
                
                $this->session->set_flashdata('medcheck_toast', "toastr.error('Item sudah terhapus')");
            }
            
            redirect(base_url('medcheck/tambah.php?act=res_input&id='.$id.(!empty($id_resep) ? '&id_resep='.$id_resep : '').'&status='.$status));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }  
    
    public function cart_medcheck_resep_rc() {
        if (akses::aksesLogin() == TRUE) {
            $id             = $this->input->post('id');
            $id_resep       = $this->input->post('id_resep');
            $id_resep_det   = $this->input->post('id_resep_det');
            $id_item        = $this->input->post('id_item');
            $id_item_rc     = $this->input->post('id_item_rc');
            $id_dokter      = $this->input->post('id_dokter');
            $hrg            = $this->input->post('harga');
            $jml            = $this->input->post('jml');
            $diskon1        = $this->input->post('disk1');
            $diskon2        = $this->input->post('disk2');
            $diskon3        = $this->input->post('disk3');
            $pot            = $this->input->post('potongan');
            $keterangan     = $this->input->post('keterangan');
            $hasil          = $this->input->post('hasil_lab');
            $status         = $this->input->post('status');
            $satuan         = $this->input->post('satuan');
            $dos_jml1       = $this->input->post('dos_jml1');
            $dos_jml2       = $this->input->post('dos_jml2');
            $dos_sat        = $this->input->post('dos_sat');
            $dos_wkt        = $this->input->post('dos_wkt');
            $dos_ket        = $this->input->post('dos_ket');
            $act            = $this->input->post('act');
            $status_res     = $this->input->post('tipe');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('kode', 'Kode', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'    => form_error('id'),
                    'kode'  => form_error('kode'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?act=res_input_rc&id='.$id.'&id_resep='.$id_resep.'&status='.$status.'&id_produk='.$id_item.'&item_id='.$id_item_rc));
            } else {
//                $sql_item   = $this->db->where('id', general::dekrip($id_item))->get('tbl_m_produk')->row();
                $sql_item_rc= $this->db->where('id', general::dekrip($id_item_rc))->get('tbl_m_produk')->row();
                $sql_medc   = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_medc_rs= $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck_resep')->row();
                $sql_medc_dt= $this->db->where('id', general::dekrip($id_item))->get('tbl_trans_medcheck_resep_det')->row();
                $sql_pnjm   = $this->db->where('id', $sql_medc->tipe_bayar)->get('tbl_m_penjamin')->row();
                $sql_sat    = $this->db->where('id', $sql_item_rc->id_satuan)->get('tbl_m_satuan')->row();
                $sql_sat_pk = $this->db->where('id', $dos_sat)->get('tbl_m_satuan_pakai')->row();
                $harga      = general::format_angka_db($hrg);
                $ass        = $harga * $sql_pnjm->persen;
                $harga_tot  = ($sql_pnjm->persen != 0 ? $ass : $harga); # Jika penjamin asuransi, maka harga obat di tambah sesuai setelan % pada database
                $potongan   = general::format_angka_db($pot);
                $dokter     = (!empty($id_dokter) ? $id_dokter : $sql_medc->id_dokter);
                
                $subtotal   = $harga_tot * (float)$jml;
                
                $data_resep = array(
                    'id_medcheck'   => (int)$sql_medc->id,
                    'id_resep'      => (int)general::dekrip($id_resep),
                    'id_resep_det'  => (int)general::dekrip($id_resep_det),
                    'id_item'       => (int)$sql_item_rc->id,
                    'id_item_kat'   => (int)$sql_item_rc->id_kategori,
                    'id_item_sat'   => (int)$sql_item_rc->id_satuan,
                    'id_user'       => $this->ion_auth->user()->row()->id,
                    'tgl_simpan'    => date('Y-m-d H:i:s'),
                    'kode'          => $sql_item_rc->kode,
                    'item'          => $sql_item_rc->produk,
                    'satuan_farmasi'=> $dos_jml1.' '.$sql_sat_pk->satuan,
                    'catatan'       => $dos_ket,
                    'harga'         => (!empty($harga_tot) ? $harga_tot : '0'),
                    'jml'           => (float)$jml,
                    'jml_satuan'    => '1',
                    'subtotal'      => $subtotal,
                    'satuan'        => $sql_sat->satuanTerkecil,
                    'status'        => '0',
                );
                
                //Simpan ke tabel resep
                crud::simpan('tbl_trans_medcheck_resep_det_rc', $data_resep);
                
                //Update racikan
                $sql_sum_rc = $this->db->select('*')->where('id_resep_det', general::dekrip($id_resep_det))->get('tbl_trans_medcheck_resep_det_rc')->result();
                $item_rc    = json_encode($sql_sum_rc);
                crud::update('tbl_trans_medcheck_resep_det', 'id', general::dekrip($id_resep_det), array('resep'=>$item_rc));

                $this->session->set_flashdata('medcheck', 'toastr.success("Item '.$sql_item_rc->produk.' berhasil disimpan")');
                redirect(base_url('medcheck/tambah.php?act=res_input_rc&id='.$id.'&id_resep='.$id_resep.'&status='.$status.'&item_id='.$id_item));
                
//                echo general::dekrip($id_item_rc);
//                echo '<pre>';
//                print_r($data_resep);
//                echo '</pre>';
//                echo '<pre>';
//                print_r($keranjang);
//                echo '</pre>';
//                echo '<pre>';
//                print_r($sql_medc_dt);
//                echo '</pre>';
//                echo '<pre>';
//                print_r($sql_item_rc);
//                echo '</pre>';
//                echo '<pre>';
//                print_r($item_rc);
//                echo '</pre>';
//                echo general::dekrip($id_resep_det);
//                echo '<pre>';
//                print_r($item_rc);
//                echo '</pre>';
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    } 

    public function cart_medcheck_resep_rc_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_resep   = $this->input->get('id_resep');
            $id_item    = $this->input->get('item_id');
            $id_item_dt = $this->input->get('item_id_det');
            $status     = $this->input->get('status');
            
            if(!empty($id_item)){
                # Hapus Item Racikannya Dulu
                crud::delete('tbl_trans_medcheck_resep_det_rc', 'id', general::dekrip($id_item));
                
                # Lalu Update Harga Untuk Item Racikan
                $sql_item   = $this->db->where('id', general::dekrip($id_item))->get('tbl_trans_medcheck_resep_det_rc')->row();
                $sql_sum_rc = $this->db->select_sum('harga')->where('id_resep_det', $sql_item->id_resep_det)->get('tbl_trans_medcheck_resep_det_rc')->row();
                $sql_sum_rc2= $this->db->where('id_resep_det', general::dekrip($id_item_dt))->get('tbl_trans_medcheck_resep_det_rc')->result();
                $item_rc    = json_encode($sql_sum_rc2);                
                
                crud::update('tbl_trans_medcheck_resep_det', 'id', general::dekrip($id_item_dt), array('resep'=>$item_rc));
                
                $this->session->set_flashdata('medcheck_toast', 'toastr.error("Item '.strtoupper($sql_item->item).' berhasil dihapus")');
            }
            
            redirect(base_url('medcheck/tambah.php?act=res_input_rc&id='.$id.(!empty($id_resep) ? '&id_resep='.$id_resep : '').'&status='.$status.'&item_id='.$id_item_dt));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    } 

    public function cart_medcheck_rad() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_rad     = $this->input->post('id_rad');
            $id_item    = $this->input->post('id_item');
            $id_produk  = $this->input->post('id_produk');
            $id_dokter  = $this->input->post('id_dokter');
            $item_name  = $this->input->post('item_name');
            $item_val   = $this->input->post('item_value');
            $status     = $this->input->post('status');
            $act        = $this->input->post('act');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id));
            } else {
                $sql_medc       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_medc_rad   = $this->db->where('id', general::dekrip($id_rad))->get('tbl_trans_medcheck_rad')->row();
                
//                if (!empty($_FILES['fupload']['name'])) {
//                    $file_tmp   = $_FILES['fupload']['tmp_name'];
//                    $file_ext   = $_FILES['fupload']['type']; //pathinfo($_FILES['fupload']['type'], PATHINFO_EXTENSION);
//                    $file_data  = file_get_contents($file_tmp);
//                    $file       = 'data:' . $file_ext . ';base64,' . base64_encode($file_data);
//                    
//                    crud::update('tbl_trans_medcheck_det', 'id', general::dekrip($id_item), array('file_rad'=>$file));
//                }
                
                $data_rad = array(
                    'id_medcheck'       => $sql_medc->id,
                    'id_medcheck_det'   => general::dekrip($id_item),
                    'id_rad'            => $sql_medc_rad->id,
                    'id_radiografer'    => $sql_medc_rad->id_radiografer,
                    'tgl_simpan'        => date('Y-m-d H:i:s'),
                    'tgl_modif'         => date('Y-m-d H:i:s'),
                    'item_name'         => $item_name,
                    'item_value'        => $item_val,
                    'file'              => $file,
                    'status'            => '0',
                );
                
                if($sql_medc->status < 5){
                    # Transaksi Database
                    $this->db->query('SET autocommit = 0;');
                    $this->db->trans_start();
                    
                    # Simpan pada tabel detail radiologi
                    $this->db->insert('tbl_trans_medcheck_rad_det', $data_rad);
                    
                    # Cek status transact MySQL
                    if ($this->db->trans_status() === FALSE) {
                        # Rollback jika gagal
                        $this->db->trans_rollback();

                        # Tampilkan pesan error
                        $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Data gagal disimpan !!</div>');
                    } else {
//                        $this->db->trans_commit();
                        $this->db->trans_complete();

                        # Buat session jika sudah berhasil commit
                        $this->session->set_userdata('trans_medcheck', $data);

                        # Tampilkan pesan sukses jika sudah berhasil commit
//                        $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data Medical Checkup Sudah disimpan !!</div>');
                        $this->session->set_flashdata('medcheck_toast', 'toastr.success("Nilai pemeriksaan berhasil di simpan !!");');
                    }
                }else{
                    if(akses::hakRad() == TRUE OR akses::hakAnalis() == TRUE){
                        # Transaksi Database
                        $this->db->query('SET autocommit = 0;');
                        $this->db->trans_start();

                        # Simpan pada tabel detail radiologi
                        $this->db->insert('tbl_trans_medcheck_rad_det', $data_rad);

                        # Cek status transact MySQL
                        if ($this->db->trans_status() === FALSE) {
                            # Rollback jika gagal
                            $this->db->trans_rollback();

                            # Tampilkan pesan error
//                            $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Data gagal disimpan !!</div>');
                            $this->session->set_flashdata('medcheck_toast', 'toastr.success("Nilai pemeriksaan berhasil di simpan !!");');
                        } else {
//                            $this->db->trans_commit();
                            $this->db->trans_complete();

                            # Buat session jika sudah berhasil commit
                            $this->session->set_userdata('trans_medcheck', $data);

                            # Tampilkan pesan sukses jika sudah berhasil commit
//                            $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Data Medical Checkup Sudah disimpan !!</div>');
                            $this->session->set_flashdata('medcheck_toast', 'toastr.success("Nilai pemeriksaan berhasil di simpan !!");');
                        }
                    }else{
                        $this->session->set_flashdata('medcheck_toast', 'toastr.error("Transaksi ini sudah diposting, sehingga tidak di perbolehkan menambah / mengubah entrian");');
//                        $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Transaksi ini sudah diposting, sehingga tidak di perbolehkan menambah entrian</div>');
                    } 
                }
                
                
                
                redirect(base_url('medcheck/tambah.php?'.(!empty($act) ? '&act='.$act : '').(!empty($id) ? '&id='.$id : '').(!empty($id_rad) ? '&id_rad='.$id_rad : '').(!empty($status) ? '&status='.$status : '').(!empty($id_item) ? '&id_item='.$id_item : '').(!empty($id_produk) ? '&id_produk='.$id_produk : '')));
                
//                crud::update('tbl_trans_medcheck_det', 'id', general::dekrip($id_item), $data_rad);
//                
//                $sql_medc   = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
//                $sql_res_rw = $this->db->where('id', general::dekrip($id_item))->get('tbl_trans_medcheck_resep_det')->row();
//                $sql_item   = $this->db->where('id', $sql_res_rw->id_item)->get('tbl_m_produk')->row();
//                $sql_sat    = $this->db->where('id', $sql_item->id_satuan)->get('tbl_m_satuan')->row();
//                $sql_sat_pk = $this->db->where('id', $dos_sat)->get('tbl_m_satuan_pakai')->row();
//                $harga      = general::format_angka_db($hrg);
//                $potongan   = general::format_angka_db($pot);
//                $dokter     = (!empty($id_dokter) ? $id_dokter : $sql_medc->id_dokter);
//                
//                $disk1      = $harga - (($diskon1 / 100) * $harga);
//                $disk2      = $disk1 - (($diskon2 / 100) * $disk1);
//                $disk3      = $disk2 - (($diskon3 / 100) * $disk2);
//                $diskon     = $harga - $disk3;
//                $subtotal   = ($disk3 - $potongan) * (int)$jml;
//                
//                $data_resep = array(
//                    'jml'           => (int)$jml,
//                    'status_resep'  => (int)$status_res,
//                );
//                
//                crud::update('tbl_trans_medcheck_resep_det', 'id', $sql_res_rw->id, $data_resep);
//                    
//                  // Simpan ke tabel resep
//                crud::simpan('tbl_trans_medcheck_resep_det', $data_resep);
//
//                $this->cart->insert($keranjang);
//
//                echo general::dekrip($id_item);
//                echo '<pre>';
//                print_r($_FILES['fupload']);
//                echo '</pre>';
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    } 

    public function cart_medcheck_rad_file() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_rad     = $this->input->post('id_rad');
            $id_item    = $this->input->post('id_item');
            $id_produk  = $this->input->post('id_produk');
            $id_dokter  = $this->input->post('id_dokter');
            $judul      = $this->input->post('judul');
            $status     = $this->input->post('status');
            $act        = $this->input->post('act');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?'.(!empty($act) ? '&act='.$act : '').(!empty($id) ? '&id='.$id : '').(!empty($id_rad) ? '&id_rad='.$id_rad : '').(!empty($status) ? '&status='.$status : '').(!empty($id_item) ? '&id_item='.$id_item : '').(!empty($id_produk) ? '&id_produk='.$id_produk : '')));
            } else {
                $sql_medc       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_medc_rad   = $this->db->where('id', general::dekrip($id_rad))->get('tbl_trans_medcheck_rad')->row();               $sql_medc    = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_pasien     = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row();
                $no_rm          = strtolower($sql_pasien->kode_dpn).$sql_pasien->kode;
                $folder         = realpath('./file/pasien/'.$no_rm);
                
                if (!empty($_FILES['fupload']['name'])) {
                    $config['upload_path']      = $folder;
                    $config['allowed_types']    = 'jpg|png|jpeg';
                    $config['remove_spaces']    = TRUE;
                    $config['overwrite']        = TRUE;
                    $config['file_name']        = 'medc_'.$sql_medc->no_rm.'_rad'.sprintf('%05d', rand(1,256));
                    $this->load->library('upload', $config);
                    
                    if (!$this->upload->do_upload('fupload')) {
                        $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Error : <b>' . $this->upload->display_errors() . '</b></div>');
                        redirect(base_url('medcheck/tambah.php?'.(!empty($act) ? '&act='.$act : '').(!empty($id) ? '&id='.$id : '').(!empty($id_rad) ? '&id_rad='.$id_rad : '').(!empty($status) ? '&status='.$status : '').(!empty($id_item) ? '&id_item='.$id_item : '').(!empty($id_produk) ? '&id_produk='.$id_produk : '').'&err='.$this->upload->display_errors()));
//                        redirect(base_url('medcheck/tambah.php?id='.$id.'&status=8&err='.$this->upload->display_errors()));
                    } else {
                        $f      = $this->upload->data();
                        
                    }
                    
//                    $file_tmp   = $_FILES['fupload']['tmp_name'];
//                    $file_ext   = $_FILES['fupload']['type']; //pathinfo($_FILES['fupload']['type'], PATHINFO_EXTENSION);
//                    $file_data  = file_get_contents($file_tmp);
//                    $file       = 'data:' . $file_ext . ';base64,' . base64_encode($file_data);
                }
                
                $data_rad = array(
                    'id_medcheck'       => $sql_medc->id,
                    'id_medcheck_det'   => general::dekrip($id_item),
                    'id_rad'            => $sql_medc_rad->id,
                    'id_user'           => $this->ion_auth->user()->row()->id,
                    'tgl_simpan'        => date('Y-m-d H:i:s'),
                    'tgl_modif'         => date('Y-m-d H:i:s'),
                    'judul'             => $judul,
                    'file_name'         => $f['orig_name'],
                    'file_name_orig'    => $f['client_name'],
                    'file_ext'          => $f['file_ext'],
                    'file_type'         => $f['file_type'],
//                    'file_base64'       => (!empty($file) ? $file : ''),
                );
                
//                echo '<pre>';
//                print_r($data_rad);
//                echo '</pre>';
                
                crud::simpan('tbl_trans_medcheck_rad_file', $data_rad);
                redirect(base_url('medcheck/tambah.php?'.(!empty($act) ? 'act='.$act : '').(!empty($id) ? '&id='.$id : '').(!empty($id_rad) ? '&id_rad='.$id_rad : '').(!empty($status) ? '&status='.$status : '').(!empty($id_item) ? '&id_item='.$id_item : '').(!empty($id_produk) ? '&id_produk='.$id_produk : '')));
                
//                crud::update('tbl_trans_medcheck_det', 'id', general::dekrip($id_item), $data_rad);
//                
//                $sql_medc   = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
//                $sql_res_rw = $this->db->where('id', general::dekrip($id_item))->get('tbl_trans_medcheck_resep_det')->row();
//                $sql_item   = $this->db->where('id', $sql_res_rw->id_item)->get('tbl_m_produk')->row();
//                $sql_sat    = $this->db->where('id', $sql_item->id_satuan)->get('tbl_m_satuan')->row();
//                $sql_sat_pk = $this->db->where('id', $dos_sat)->get('tbl_m_satuan_pakai')->row();
//                $harga      = general::format_angka_db($hrg);
//                $potongan   = general::format_angka_db($pot);
//                $dokter     = (!empty($id_dokter) ? $id_dokter : $sql_medc->id_dokter);
//                
//                $disk1      = $harga - (($diskon1 / 100) * $harga);
//                $disk2      = $disk1 - (($diskon2 / 100) * $disk1);
//                $disk3      = $disk2 - (($diskon3 / 100) * $disk2);
//                $diskon     = $harga - $disk3;
//                $subtotal   = ($disk3 - $potongan) * (int)$jml;
//                
//                $data_resep = array(
//                    'jml'           => (int)$jml,
//                    'status_resep'  => (int)$status_res,
//                );
//                
//                crud::update('tbl_trans_medcheck_resep_det', 'id', $sql_res_rw->id, $data_resep);
//                    
//                  // Simpan ke tabel resep
//                crud::simpan('tbl_trans_medcheck_resep_det', $data_resep);
//
//                $this->cart->insert($keranjang);
//
//                echo general::dekrip($id_item);
//                echo '<pre>';
//                print_r($_FILES['fupload']);
//                echo '</pre>';
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    } 

    public function cart_medcheck_rad_file_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_item    = $this->input->get('file_id');
            $status     = $this->input->get('status');
            $act        = $this->input->get('act');
            
            if(!empty($id_item)){
                $sql_medc    = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_medc_rad= $this->db->where('id', general::dekrip($id_item))->get('tbl_trans_medcheck_rad_file')->row();
                $sql_pasien  = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row();
                $no_rm       = strtolower($sql_pasien->kode_dpn).$sql_pasien->kode;
                $folder      = realpath('./file/pasien/'.$no_rm);
               
                $path   = $folder.'/';
                $berkas = $path.$sql_medc_rad->file_name;
                
                if(file_exists($berkas)){
                    unlink($berkas);
                }
                
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Input hasil radiologi berhasil di hapus</div>');
                crud::delete('tbl_trans_medcheck_rad_file', 'id', general::dekrip($id_item));
            }
            
            redirect(base_url('medcheck/tambah.php?act='.$this->input->get('act').'&id='.$this->input->get('id').'&id_rad='.$this->input->get('id_rad').'&status='.$this->input->get('status').'&id_item=' . $this->input->get('id_item').'&id_produk=' . $this->input->get('id_produk')));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    } 

    public function cart_medcheck_rad_hsl_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_item    = $this->input->get('item_id');
            $hasil_id   = $this->input->get('hasil_id');
            $status     = $this->input->get('status');
            
            if(!empty($hasil_id)){                
                $this->session->set_flashdata('medcheck_toast', 'toastr.success("Nilai pemeriksaan berhasil di hapus !!");');
                crud::delete('tbl_trans_medcheck_rad_det', 'id', general::dekrip($hasil_id));
            }else{
                $this->session->set_flashdata('medcheck_toast', 'toastr.error("Nilai pemeriksaan gagal di hapus !!");');
            }
            
            redirect(base_url('medcheck/tambah.php?act='.$this->input->get('act').'&id='.$this->input->get('id').'&id_rad='.$this->input->get('id_rad').'&status='.$this->input->get('status').'&id_item='.$this->input->get('id_item').'&id_produk='.$this->input->get('id_produk')));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    } 

    public function cart_medcheck_lab_hsl_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_lab     = $this->input->get('id_lab');
            $id_item    = $this->input->get('item_id');
            $status     = $this->input->get('status');
            $act        = $this->input->get('act');
            
            if(!empty($id_item)){                
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Input hasil radiologi berhasil di hapus</div>');
                crud::delete('tbl_trans_medcheck_lab_hsl', 'id', general::dekrip($id_item));
            }
            
            redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status.'&act='.$act.'&id_lab='.$id_lab));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    } 

    public function cart_medcheck_rm() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_dokter  = $this->input->post('id_dokter');
            $id_nurse   = $this->input->post('id_perawat');
            $id_pasien  = $this->input->post('id_pasien');
            $id_icd     = $this->input->post('icd');
            $id_icd10   = $this->input->post('icd10');
            $tgl        = $this->input->post('tgl');
            $anamnesa   = $this->input->post('anamnesa');
            $priksa     = $this->input->post('pemeriksaan');
            $diagnos    = $this->input->post('diagnosa');
            $terapi     = $this->input->post('terapi');
            $program    = $this->input->post('program');
            $ns_subj    = $this->input->post('ns_subj');
            $ns_obj     = $this->input->post('ns_obj');
            $ns_ass     = $this->input->post('ns_ass');
            $ns_prog    = $this->input->post('ns_prog');
            $act        = $this->input->post('act');
            $status     = $this->input->post('status');
            $tipe       = $this->input->post('tipe');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id));
            } else {
                $sql_rm = $this->db->get('tbl_trans_medcheck_rm');
                $nomer  = $sql_rm->num_rows() + 1;
                $kode   = sprintf('%05d', $nomer);
                        
                $data_rad = array(
                    'id_medcheck' => general::dekrip($id),
                    'id_user'     => $this->ion_auth->user()->row()->id,
                    'id_perawat'  => $id_nurse,
                    'id_dokter'   => $id_dokter,
                    'id_pasien'   => general::dekrip($id_pasien),
                    'id_icd'      => (!empty($id_icd) ? $id_icd : '0'),
                    'id_icd10'    => (!empty($id_icd10) ? $id_icd10 : '0'),
                    'tgl_simpan'  => $this->tanggalan->tgl_indo_sys($tgl).' '.date('H:i:s'),
                    'kode'        => $kode,
                    'anamnesa'    => $anamnesa,
                    'pemeriksaan' => $priksa,
                    'diagnosa'    => $diagnos,
                    'terapi'      => $terapi,
                    'program'     => $program,
                    'ns_subj'     => $ns_subj,
                    'ns_obj'      => $ns_obj,
                    'ns_ass'      => $ns_ass,
                    'ns_prog'     => $ns_prog,
                    'status'      => '1',
                    'tipe'        => '0',
                );
                
                $this->db->insert('tbl_trans_medcheck_rm', $data_rad);
                $last_id = crud::last_id();
                
                if ($this->db->affected_rows() > 0) {
                    $this->session->set_flashdata('medcheck_toast', 'toastr.success("Entri rekam medis berhasil ditambahkan !");');
                } else {
                    $this->session->set_flashdata('medcheck_toast', 'toastr.error("Entri rekam medis gagal disimpan !");');
                }

                redirect(base_url('medcheck/tambah.php?act=rm_ubah&id='.$id.'&status='.$status.(!empty($last_id) ? '&id_item='.general::enkrip($last_id) : '')));
                
//                echo general::dekrip($id_item);
//                echo '<pre>';
//                print_r($data_rad);
//                echo '</pre>';
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    } 

    public function cart_medcheck_rm_upd() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_rm      = $this->input->post('id_rm');
            $id_dokter  = $this->input->post('id_dokter');
            $id_nurse   = $this->input->post('id_perawat');
            $id_pasien  = $this->input->post('id_pasien');
            $id_icd     = $this->input->post('icd');
            $id_icd10   = $this->input->post('icd10');
            $tgl        = $this->input->post('tgl');
            $anamnesa   = $this->input->post('anamnesa');
            $priksa     = $this->input->post('pemeriksaan');
            $diagnos    = $this->input->post('diagnosa');
            $terapi     = $this->input->post('terapi');
            $program    = $this->input->post('program');
            $ns_subj    = $this->input->post('ns_subj');
            $ns_obj     = $this->input->post('ns_obj');
            $ns_ass     = $this->input->post('ns_ass');
            $ns_prog    = $this->input->post('ns_prog');
            $act        = $this->input->post('act');
            $status     = $this->input->post('status');
            $tipe       = $this->input->post('tipe');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id));
            } else {
                $sql_rm = $this->db->get('tbl_trans_medcheck_rm');
                $nomer  = $sql_rm->num_rows() + 1;
                $kode   = sprintf('%05d', $nomer);
                        
                $data_rad = array(
                    'tgl_simpan'  => $this->tanggalan->tgl_indo_sys($tgl).' '.date('H:i:s'),
                    'id_icd'      => $id_icd,
                    'id_icd10'    => $id_icd10,
                    'anamnesa'    => $anamnesa,
                    'pemeriksaan' => $priksa,
                    'diagnosa'    => $diagnos,
                    'terapi'      => $terapi,
                    'program'     => $program,
                    'ns_subj'     => $ns_subj,
                    'ns_obj'      => $ns_obj,
                    'ns_ass'      => $ns_ass,
                    'ns_prog'     => $ns_prog,
                );

                crud::update('tbl_trans_medcheck_rm', 'id', general::dekrip($id_rm), $data_rad);
                redirect(base_url('medcheck/tambah.php?id='.$id.(!empty($id_resep) ? '&id_resep='.$id_resep : '').(!empty($act) ? '&act='.$act : '').'&status='.$status));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    } 

    public function cart_medcheck_rm_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_item    = $this->input->get('id_item');
            $id_nota    = $this->input->get('no_nota');
            $status     = $this->input->get('status');
            
            if(!empty($id)){
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Item berhasil di hapus</div>');
                crud::delete('tbl_trans_medcheck_rm', 'id', general::dekrip($id_item));
            }
            
            redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function cart_medcheck_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_lab     = $this->input->get('id_lab');
            $id_rad     = $this->input->get('id_rad');
            $id_nota    = $this->input->get('no_nota');
            $status     = $this->input->get('status');
            $act        = $this->input->get('act');
            $rute       = $this->input->get('route');
            
            if(!empty($id)){
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Item berhasil di hapus</div>');
                crud::delete('tbl_trans_medcheck_det', 'id', general::dekrip($id));
            }
            
            redirect(base_url((!empty($rute) ? $rute : 'medcheck/tambah.php?'.(!empty($act) ? 'act='.$act.'&' : '').'id='.$id_nota.(!empty($id_rad) ? '&id_rad='.$id_rad.'&' : '').(!empty($id_lab) ? '&id_lab='.$id_lab.'&' : '').'&status='.$status)));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function cart_medcheck_retur_ranap_hapus() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_item    = $this->input->get('item_id');
            $status     = $this->input->get('status');
            $act        = $this->input->get('act');
            $rute       = $this->input->get('route');
            
            if(!empty($id)){
                $sql_ret    = $this->db->where('id', general::dekrip($id_item))->get('tbl_trans_medcheck_retur')->row();
                $sql_det    = $this->db->where('id', $sql_ret->id_medcheck_det)->get('tbl_trans_medcheck_det')->row();
                $jml_ret    = $sql_ret->jml + $sql_det->jml;

                $disk1      = $sql_det->harga - (($sql_det->disk1 / 100) * $sql_det->harga);
                $disk2      = $disk1 - (($sql_det->disk2 / 100) * $disk1);
                $disk3      = $disk2 - (($sql_det->disk3 / 100) * $disk2);
                $diskon     = $sql_det->harga - $disk3;
                $subtotal   = ($disk3 - $sql_det->potongan) * $jml_ret;
                                
                $data_det = array(
                    'jml'       => (int)$jml_ret,
                    'subtotal'  => (float)$subtotal
                );
                
//                echo '<pre>';
//                print_r($sql_det);
//                echo '</pre>';
//                echo '<pre>';
//                print_r($data_det);
//                echo '</pre>';
                
                # Transaksi start
                $this->db->trans_off();
                $this->db->trans_start();
                
                # Update tabel medcheck detail
                $this->db->where('id', $sql_det->id)->update('tbl_trans_medcheck_det', $data_det);
                
                # Hapus tabel retur
                $this->db->where('id', $sql_ret->id)->delete('tbl_trans_medcheck_retur');
                
                # Trans Complete
                $this->db->trans_complete();
                
                if ($this->db->trans_status() === FALSE) {
                    # Something went wrong.
                    $this->db->trans_rollback();
                    
                    # Berikan pesan gagal                   
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-danger">Item gagal di hapus</div>');
                } else {
                    $this->db->trans_commit(); 
                    
                    # Berikan pesan sukses                   
                    $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Item berhasil di hapus</div>');
                }
            }
            
            redirect(base_url('medcheck/retur.php?id='.$id));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function cart_medcheck_status() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_lab     = $this->input->get('id_lab');
            $id_nota    = $this->input->get('no_nota');
            $status     = $this->input->get('status');
            $state      = $this->input->get('state');
            $act        = $this->input->get('act');
            
            if(!empty($id)){
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Item berhasil di hapus</div>');
                crud::update('tbl_trans_medcheck_det', 'id', general::dekrip($id), array('status_baca'=>$state));
            }
            
            redirect(base_url('medcheck/tambah.php?'.(!empty($act) ? 'act='.$act.'&' : '').'id='.$id_nota.(!empty($id_lab) ? '&id_lab='.$id_lab : '').'&status='.$status));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function cart_medcheck_lab_nilai() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id');
            $id_item    = $this->input->post('id_item');
            $id_det     = $this->input->post('id_det'); // ==> Medcheck Det
            $id_lab     = $this->input->post('id_lab'); // ==> Lab Det
            $item       = $this->input->post('item');
            $item_ket   = $this->input->post('item_ket');
            $hasil      = $this->input->post('keterangan');
            $nilai      = $_POST['nilai_normal'];
            $satuan     = $_POST['nilai_satuan'];
            $hasil      = $_POST['nilai_hasil'];
            $status_hsl = $_POST['status_hsl'];
            $status     = $this->input->post('status');
            $act        = $this->input->post('act');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('id_item', 'Kode', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg_error = array(
                    'id'        => form_error('id'),
                    'kode'      => form_error('id_item'),
                );

                $this->session->set_flashdata('form_error', $msg_error);

                redirect(base_url('medcheck/tambah.php?id='.$id.'&status='.$status));
            } else {
                $sql_medc   = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $sql_item   = $this->db->where('id', general::dekrip($id_item))->get('tbl_m_produk')->row();
                $sql_medc_ck= $this->db->where('id_medcheck', general::dekrip($id))->where('id_item', general::dekrip($id_item))->get('tbl_trans_medcheck_det');
                $sql_sat    = $this->db->where('id', $sql_item->id_satuan)->get('tbl_m_satuan')->row();
                $harga      = general::format_angka_db($hrg);
                $potongan   = general::format_angka_db($pot);
                $dokter     = (!empty($id_dokter) ? $id_dokter : $sql_medc->id_dokter);
                
                $data = array(
                    'tgl_modif'     => date('Y-m-d H:i:s'),
                    'tgl_baca'      => date('Y-m-d H:i:s'),
//                    'status_hsl_lab'=> $item_ket,
                );
                
                crud::update('tbl_trans_medcheck_det', 'id', general::dekrip($id_det), $data);                
                    
                foreach ($nilai as $key => $hsl){
                    $sql_nilai   = $this->db->where('id', general::dekrip($key))->get('tbl_m_produk_ref_input')->row();
                    $input_id    = $sql_nilai->id;
                    $input_name  = $sql_nilai->item_name;
                    $input_sat   = $satuan[$key];
                    $input_hsl   = $hasil[$key];
                    $input_hsl_st= $status_hsl[$key];
                    $input_val   = $hsl;
                    
                    $data_lab = array(
                        'id_medcheck'   => $sql_medc->id,
                        'id_lab'        => general::dekrip($id_lab),
                        'id_user'       => $this->ion_auth->user()->row()->id,
                        'id_item'       => general::dekrip($id_item),
                        'id_item_ref_ip'=> $input_id,
                        'tgl_simpan'    => date('Y-m-d H:i:s'),
                        'item_name'     => $input_name,
                        'item_value'    => $input_val,
                        'item_satuan'   => $input_sat,
                        'item_hasil'    => $input_hsl,
                        'status'        => $sql_nilai->status,
                        'status_hsl_lab'=> (!empty($input_hsl_st) ? $input_hsl_st : '0'),
                    );
                    
                    crud::simpan('tbl_trans_medcheck_lab_hsl', $data_lab);
//                    
//                    echo '<pre>';
//                    print_r($data_lab);
//                    echo '</pre>';
                }
                
//                echo $id_det;
//                echo '<pre>';
//                print_r($data);
//                echo '</pre>';
                
                redirect(base_url('medcheck/tambah.php?act='.$act.'&id='.$id.'&id_lab='.$id_lab.'&status='.$status));
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function cart_beli_simpan() {
        if (akses::aksesLogin() == TRUE) {
            $no_nota  = $this->input->post('no_nota');
            $id_brg   = $this->input->post('id_item');
            $satuan   = $this->input->post('satuan');
            $kode     = $this->input->post('kode');
            $qty      = $this->input->post('jml');
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
                $jml_qty     = $qty;

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
    

    public function hapus_medcheck_surat() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_nota    = $this->input->get('no_nota');
            $status     = $this->input->get('status');
            
            if(!empty($id)){
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Surat berhasil di hapus</div>');
                crud::delete('tbl_trans_medcheck_surat', 'id', general::dekrip($id));
            }
            
            redirect(base_url('medcheck/tambah.php?id='.$id_nota.'&status='.$status));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function hapus_medcheck_surat_inform() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->get('id');
            $id_nota    = $this->input->get('no_nota');
            $status     = $this->input->get('status');
            
            if(!empty($id)){
                $this->session->set_flashdata('medcheck', '<div class="alert alert-success">Surat berhasil di hapus</div>');
                crud::delete('tbl_trans_medcheck_surat_inform', 'id', general::dekrip($id));
            }
            
            redirect(base_url('medcheck/tambah.php?id='.$id_nota.'&status='.$status));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function cetak_medcheck_surat() {
        if (akses::aksesLogin() == TRUE) {
            $setting              = $this->db->get('tbl_pengaturan')->row();
            $id                   = $this->input->get('id');
            $id_medcheck          = $this->input->get('id_produk');
            $status               = $this->input->get('status');
            $userid               = $this->ion_auth->user()->row()->id;

            $data['sess_jual']    = $this->session->userdata('trans_medcheck');
            $data['kategori']     = $this->db->get('tbl_m_kategori')->result();
            $data['poli']         = $this->db->get('tbl_m_poli')->result();
            $data['sql_doc']      = $this->db->where('status', '2')->get('tbl_m_sales')->result();

            if(!empty($id)){
                $data['sql_medc']       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck')->row();
                $data['sql_medc_srt']   = $this->db->where('id', general::dekrip($id))->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();            
                $data['sql_pasien']     = $this->db->where('id', $data['sql_medc']->id_pasien)->get('tbl_m_pasien')->row();
                $data['sql_dokter']     = $this->db->where('id', $data['sql_medc']->id_dokter)->get('tbl_m_karyawan')->row();
                $data['sql_poli']       = $this->db->where('id', $data['sql_medc']->id_poli)->get('tbl_m_poli')->row();                
            }
            
            /* Sidebar Menu */
            $data['sidebar']    = 'admin-lte-3/includes/medcheck/sidebar_med';
            /* --- Sidebar Menu --- */

            $this->load->view('admin-lte-3/1_atas', $data);
            $this->load->view('admin-lte-3/2_header', $data);
            $this->load->view('admin-lte-3/3_navbar', $data);
            $this->load->view('admin-lte-3/includes/medcheck/med_detail', $data);
            $this->load->view('admin-lte-3/5_footer',$data);
            $this->load->view('admin-lte-3/6_bawah',$data);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function pdf_medcheck_surat() {
        if (akses::aksesLogin() == TRUE) {
            $setting            = $this->db->get('tbl_pengaturan')->row();
            $id                 = $this->input->get('id');
            $id_medcheck        = $this->input->get('no_nota');

            $sql_medc           = $this->db->where('id', general::dekrip($id_medcheck))->get('tbl_trans_medcheck')->row();
            $sql_medc_srt       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck_surat')->row(); 
            $sql_pasien         = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row(); 
            $sql_pekerjaan      = $this->db->where('id', $sql_pasien->id_pekerjaan)->get('tbl_m_jenis_kerja')->row();
            $sql_dokter         = $this->db->where('id_user', $sql_medc->id_dokter)->get('tbl_m_karyawan')->row();
            $kode_pasien        = $sql_pasien->kode_dpn.$sql_pasien->kode;
            $gambar1            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-esensia-2.png';
            $gambar2            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-bw-bg2-1440px.png';
            $gambar3            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-footer.png';
            
            $this->load->library('MedSuratPDF');
//            $pdf = new MedPDF('P', 'cm', 'a4');
            $pdf = new MedSuratPDF('P', 'cm', array(21.5,33));
            $pdf->SetAutoPageBreak('auto', 7.5);
//            $pdf->SetMargins(1, 1, 1);
            $pdf->header = 0;
            $pdf->addPage('','',false);
            
            // Gambar Watermark Tengah
            $pdf->Image($gambar2,5,4,17,19);
                        
            // Blok Isi Surat
            switch ($sql_medc_srt->tipe){
                
                case '1' :
                    $judul = "SURAT KETERANGAN ".strtoupper(general::tipe_surat($sql_medc_srt->tipe));

                    // Blok Judul
                    $pdf->SetFont('Arial', 'B', '14');
                    $pdf->Cell(19, .5, $judul, 0, 1, 'C');
                    $pdf->Ln(0);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(19, .5, 'Nomor : '.$sql_medc_srt->no_surat, 0, 1, 'C');
                    $pdf->Ln();

                    // Blok Paragraf Pertama
                    $fill = FALSE;
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Yang bertanda tangan di bawah ini,', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(19, .5, 'dokter di Klinik Utama Rawat Inap Esensia Semarang, menerangkan bahwa:', '', 0, 'L', $fill);
                    $pdf->Ln(1);

                    // Blok ID PASIEN
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Nama Lengkap', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $sql_pasien->nama_pgl, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Tanggal Lahir', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $this->tanggalan->tgl_indo2($sql_pasien->tgl_lahir), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Jenis Kelamin', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, general::jns_klm($sql_pasien->jns_klm), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Pekerjaan', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $sql_pekerjaan->jenis, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Alamat', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $sql_pasien->alamat, '0', 0, 'L', $fill);
                    $pdf->Ln(1);

                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Setelah dilakukan pemeriksaan, maka yang bersangkutan dinyatakan', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(19, .5, strtoupper(general::tipe_sehat($sql_medc_srt->hasil)), '', 0, 'C', $fill);
                    $pdf->Ln(1);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(19, .5, 'Keterangan :', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(4, .5, 'Tinggi Badan', '', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(5, .5, (float)$sql_medc_srt->tb.' cm', '', 0, 'L', $fill);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(4, .5, 'Tekanan Darah', '', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(5, .5, $sql_medc_srt->td.' mmHg', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(4, .5, 'Buta Warna', '', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(5, .5, $sql_medc_srt->bw, '', 0, 'L', $fill);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(4, .5, 'Berat Badan', '', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(5, .5, (float)$sql_medc_srt->bb.' kg', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(4, .5, 'Ket Buta Warna', '', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14.5, .5, $sql_medc_srt->bw_ket, '', 0, 'L', $fill);
                    
//                    $pdf->SetFont('Arial', 'B', '10');
//                    $pdf->Cell(2, .5, $this->tanggalan->tgl_indo2($sql_medc_srt->tgl_keluar).'.', '', 0, 'L', $fill);
//                    $pdf->Ln();
//                    $pdf->SetFont('Arial', '', '10');
//                    $pdf->Cell(19, .5, 'Harap yang berkepentingan maklum.', '', 0, 'L', $fill);
                    $pdf->Ln(1);
            
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Demikian surat ini dibuat agar dapat digunakan sebagaimana mestinya.', '', 0, 'L', $fill);
                    $pdf->Ln(2);
                    break;
                
                case '2' :
                    $judul = "SURAT KETERANGAN ".strtoupper(general::tipe_surat($sql_medc_srt->tipe));

                    // Blok Judul
                    $pdf->SetFont('Arial', 'B', '14');
                    $pdf->Cell(19, .5, $judul, 0, 1, 'C');
                    $pdf->Ln(0);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(19, .5, 'Nomor : '.$sql_medc_srt->no_surat, 0, 1, 'C');
                    $pdf->Ln();

                    // Blok Paragraf Pertama
                    $fill = FALSE;
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Yang bertanda tangan di bawah ini, dokter di Klinik Utama Rawat Inap Esensia Semarang,', '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(19, .5, 'menerangkan bahwa:', '0', 0, 'L', $fill);
                    $pdf->Ln(1);

                    // Blok ID PASIEN
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Nama Lengkap', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $sql_pasien->nama_pgl, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Tanggal Lahir', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $this->tanggalan->tgl_indo2($sql_pasien->tgl_lahir), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Jenis Kelamin', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, general::jns_klm($sql_pasien->jns_klm), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Pekerjaan', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $sql_pekerjaan->jenis, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Alamat', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $sql_pasien->alamat, '0', 0, 'L', $fill);
                    $pdf->Ln(1);

                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Perlu beristirahat karena sakit sehingga tidak dapat melaksanakan kewajibannya,', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(2, .5, 'selama : ', '', 0, 'L', $fill);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1.5, .5, $sql_medc_srt->jml_hari.' hari', '', 0, 'L', $fill);                  
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(5, .5, 'terhitung mulai tanggal', '', 0, 'L', $fill);
//                    $pdf->Ln();
//                    $pdf->Cell(2.5, .5, 'mulai tanggal', '', 0, 'L', $fill);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(2.5, .5, $this->tanggalan->tgl_indo2($sql_medc_srt->tgl_masuk), '', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(1.5, .5, 's/d', '', 0, 'C', $fill);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(2.5, .5, ($sql_medc_srt->status_sembuh == '1' ? 'Sembuh' : ($sql_medc_srt->tgl_keluar != '0000-00-00' ? $this->tanggalan->tgl_indo2($sql_medc_srt->tgl_keluar) : '-')).'.', '', 0, 'C', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Harap yang berkepentingan maklum.', '', 0, 'L', $fill);
                    $pdf->Ln(1);
            
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Demikian surat ini dibuat agar dapat digunakan sebagaimana mestinya.', '', 0, 'L', $fill);
                    $pdf->Ln(2);
                    break;
                
                case '3' :
                    $judul = "SURAT KETERANGAN ".strtoupper(general::tipe_surat($sql_medc_srt->tipe));

                    // Blok Judul
                    $pdf->SetFont('Arial', 'B', '14');
                    $pdf->Cell(19, .5, $judul, 0, 1, 'C');
                    $pdf->Ln(0);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(19, .5, 'Nomor : '.$sql_medc_srt->no_surat, 0, 1, 'C');
                    $pdf->Ln();

                    // Blok Paragraf Pertama
                    $fill = FALSE;
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Yang bertanda tangan di bawah ini, dokter di Klinik Utama Rawat Inap Esensia Semarang,', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(19, .5, 'menerangkan bahwa:', '', 0, 'L', $fill);
                    $pdf->Ln(1);

                    // Blok ID PASIEN
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(2, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Nama Lengkap', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $sql_pasien->nama_pgl, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(2, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Tanggal Lahir', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $this->tanggalan->tgl_indo2($sql_pasien->tgl_lahir), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(2, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Jenis Kelamin', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, general::jns_klm($sql_pasien->jns_klm), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(2, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Pekerjaan', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $sql_pekerjaan->jenis, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(2, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Alamat', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $sql_pasien->alamat, '0', 0, 'L', $fill);
                    $pdf->Ln(1);

                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Bahwa pasien sedang dalam perawatan di kamar rawat inap kami, sehingga tidak dapat', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(8.5, .5, 'melaksanakan kewajibannya mulai tanggal', '', 0, 'L', $fill);
//                    $pdf->Ln();
//                    $pdf->Cell(2.5, .5, 'mulai tanggal', '0', 0, 'L', $fill);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(2.5, .5, $this->tanggalan->tgl_indo2($sql_medc_srt->tgl_masuk), '', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(1, .5, 's/d ', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(7, .5, ($sql_medc_srt->status_sembuh == '1' ? 'Sembuh' : $this->tanggalan->tgl_indo2($sql_medc_srt->tgl_keluar)).'.', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Harap yang berkepentingan maklum.', '', 0, 'L', $fill);
                    $pdf->Ln(1);
            
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Demikian surat ini dibuat agar dapat digunakan sebagaimana mestinya.', '', 0, 'L', $fill);
                    $pdf->Ln(2);
                    break;
                
                case '4' :
                    $judul = "SURAT KETERANGAN ".strtoupper(general::tipe_surat($sql_medc_srt->tipe));

                    // Blok Judul
                    $pdf->SetFont('Arial', 'B', '14');
                    $pdf->Cell(19, .5, $judul, 0, 1, 'C');
                    $pdf->Ln(0);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(19, .5, 'Nomor : '.$sql_medc_srt->no_surat, 0, 1, 'C');
                    $pdf->Ln();

                    // Blok Paragraf Pertama
                    $fill = FALSE;
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Yang bertanda tangan di bawah ini, dokter di Klinik Utama Rawat Inap Esensia Semarang,', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(19, .5, 'menerangkan bahwa:', '', 0, 'L', $fill);
                    $pdf->Ln(1);

                    // Blok ID PASIEN
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(2, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Nama Lengkap', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $sql_pasien->nama_pgl, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(2, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Tanggal Lahir', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $this->tanggalan->tgl_indo2($sql_pasien->tgl_lahir), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(2, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Jenis Kelamin', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, general::jns_klm($sql_pasien->jns_klm), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(2, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Pekerjaan', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $sql_pekerjaan->jenis, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(2, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Alamat', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $sql_pasien->alamat, '0', 0, 'L', $fill);
                    $pdf->Ln(1);

                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Bahwa yang bersangkutan telah menjalani pemeriksaan di Klinik Utama Esensia Semarang.', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(19, .5, 'Sehubungan masih dalam perawatan atas sakitnya, ', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(4.25, .5, 'mohon kiranya untuk', '0', 0, 'L', $fill);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(2, .5, 'Kontrol', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(2.5, .5, 'pada tanggal ', '', 0, 'L', $fill);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(5, .5, ' '.$this->tanggalan->tgl_indo2($sql_medc_srt->tgl_kontrol).'.', '', 0, 'L', $fill);
                    $pdf->Ln(1);
            
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Demikian surat ini dibuat agar dapat digunakan sebagaimana mestinya.', '', 0, 'L', $fill);
                    $pdf->Ln(2);
                    break;
                
                case '5' :
                    $judul = "SURAT KETERANGAN ".strtoupper(general::tipe_surat($sql_medc_srt->tipe));

                    // Blok Judul
                    $pdf->SetFont('Arial', 'B', '14');
                    $pdf->Cell(19, .5, $judul, 0, 1, 'C');
                    $pdf->Ln(0);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(19, .5, 'Nomor : '.$sql_medc_srt->no_surat, 0, 1, 'C');
                    $pdf->Ln();

                    // Blok Paragraf Pertama
                    $fill = FALSE;
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Yang bertanda tangan di bawah ini,', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(9, .5, 'menerangkan bahwa telah lahir seorang bayi', '', 0, 'L', $fill);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(2.5, .5, general::jns_klm($sql_medc_srt->jns_klm), '', 0, 'C', $fill);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1.5, .5, ':', '', 0, 'L', $fill);
                    $pdf->Ln(1);

                    // BLOK WAKTU LAHIR
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Tanggal Lahir', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $this->tanggalan->tgl_indo2($sql_medc_srt->lahir_tgl), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Waktu Lahir', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $this->tanggalan->wkt_indo($sql_medc_srt->lahir_tgl).' WIB', '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Nama Anak', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, strtoupper($sql_medc_srt->lahir_nm), '0', 0, 'L', $fill);
                    $pdf->Ln(1);
                    
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(19, .5, 'ANAK DARI', '0', 0, 'C', $fill);
                    $pdf->Ln(1);
                    
                    // Blok ID PASIEN
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Nama Ayah', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_medc_srt->lahir_nm_ayah, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Nama Ibu', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_medc_srt->lahir_nm_ibu, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Alamat', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_pasien->alamat, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Berat Lahir', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, (float)$sql_medc_srt->bb.' Kg', '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Panjang Badan', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, (float)$sql_medc_srt->tb.' cm', '0', 0, 'L', $fill);
                    $pdf->Ln(1);
            
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Demikian Surat Keterangan Kelahiran ini dibuat dengan sesungguhnya,', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(19, .5, 'untuk dipergunakan sebagaimana mestinya.', '', 0, 'L', $fill);
                    $pdf->Ln(2);
                    break;
                
                case '6' :
                    $judul = "SURAT KETERANGAN ".strtoupper(general::tipe_surat($sql_medc_srt->tipe));

                    // Blok Judul
                    $pdf->SetFont('Arial', 'B', '14');
                    $pdf->Cell(19, .5, $judul, 0, 1, 'C');
                    $pdf->Ln(0);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(19, .5, 'Nomor : '.$sql_medc_srt->no_surat, 0, 1, 'C');
                    $pdf->Ln();

                    // Blok Paragraf Pertama
                    $fill = FALSE;
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Yang bertanda tangan di bawah ini, menerangkan bahwa :', '', 0, 'L', $fill);
                    $pdf->Ln(1);
                    
                    // Blok ID PASIEN
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Nama Lengkap', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_pasien->nama_pgl, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Tanggal Lahir', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $this->tanggalan->tgl_indo2($sql_pasien->tgl_lahir), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Jenis Kelamin', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, general::jns_klm($sql_pasien->jns_klm), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Pekerjaan', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_pekerjaan->jenis, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Alamat', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_pasien->alamat, '0', 0, 'L', $fill);
                    $pdf->Ln(1);
                    
                    $pdf->SetFont('Arial', 'B', '14');
                    $pdf->Cell(19, .5, 'TELAH MENINGGAL DUNIA', '0', 0, 'C', $fill);
                    $pdf->Ln(1);
                    
                    // BLOK WAKTU KEMATIAN
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Hari / Tanggal', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $this->tanggalan->tgl_indo6($sql_medc_srt->mati_tgl), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Waktu', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $this->tanggalan->wkt_indo($sql_medc_srt->mati_tgl).' WIB', '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Tempat', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_medc_srt->mati_tmp, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Penyebab', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_medc_srt->mati_penyebab, '0', 0, 'L', $fill);
                    $pdf->Ln(1);
            
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Demikian Surat Keterangan Kematian ini dibuat dengan sesungguhnya,', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(19, .5, 'untuk dipergunakan sebagaimana mestinya.', '', 0, 'L', $fill);
                    $pdf->Ln(2);
                    break;
                
                case '7' :
                    $judul = "SURAT KETERANGAN ".strtoupper(general::tipe_surat($sql_medc_srt->tipe));

                    // Blok Judul
                    $pdf->SetFont('Arial', 'B', '14');
                    $pdf->Cell(19, .5, $judul, 0, 1, 'C');
                    $pdf->Ln(0);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(19, .5, 'Nomor : '.$sql_medc_srt->no_surat, 0, 1, 'C');
                    $pdf->Ln();

                    // Blok Paragraf Pertama
                    $fill = FALSE;
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Yang bertanda tangan di bawah ini, menerangkan bahwa :', '', 0, 'L', $fill);
                    $pdf->Ln(1);
                    
                    // Blok ID PASIEN
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Nama Lengkap', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_pasien->nama_pgl, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Tanggal Lahir', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $this->tanggalan->tgl_indo2($sql_pasien->tgl_lahir), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Jenis Kelamin', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, general::jns_klm($sql_pasien->jns_klm), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Pekerjaan', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_pekerjaan->jenis, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Alamat', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_pasien->alamat, '0', 0, 'L', $fill);
                    $pdf->Ln(1);
                    
                    $pdf->SetFont('Arial', 'B', '14');
                    $pdf->Cell(19, .5, 'TELAH MENINGGAL DUNIA', '0', 0, 'C', $fill);
                    $pdf->Ln(1);
                    
                    // BLOK WAKTU KEMATIAN
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Hari / Tanggal', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $this->tanggalan->tgl_indo6($sql_medc_srt->mati_tgl), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Waktu', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $this->tanggalan->wkt_indo($sql_medc_srt->mati_tgl).' WIB', '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Tempat', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_medc_srt->mati_tmp, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Penyebab', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_medc_srt->mati_penyebab, '0', 0, 'L', $fill);
                    $pdf->Ln(1);
            
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Demikian Surat Keterangan Kematian ini dibuat dengan sesungguhnya,', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(19, .5, 'untuk dipergunakan sebagaimana mestinya.', '', 0, 'L', $fill);
                    $pdf->Ln(2);
                    break;
                
                case '8' :
                    $judul = "SURAT ".strtoupper(general::tipe_surat($sql_medc_srt->tipe));

                    // Blok Judul
                    $pdf->SetFont('Arial', 'B', '14');
                    $pdf->Cell(19, .5, $judul, 0, 1, 'C');
                    $pdf->Ln(0);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(19, .5, 'Nomor : '.$sql_medc_srt->no_surat, 0, 1, 'C');
                    $pdf->Ln();
                    
                    // BLOK TUJUAN RUJUKAN
                    $pdf->SetFont('Arial', 'B', '10');
//                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Kepada Yth', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_medc_srt->ruj_dokter, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
//                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'di', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_medc_srt->ruj_faskes, '0', 0, 'L', $fill);
                    $pdf->Ln(1);

                    // Blok Paragraf Pertama
                    $fill = FALSE;
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Mohon penatalaksanaan lebih lanjut terhadap pasien :', '', 0, 'L', $fill);
                    $pdf->Ln(1);
                    
                    // Blok ID PASIEN
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Nama Lengkap', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_pasien->nama_pgl, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Tanggal Lahir', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $this->tanggalan->tgl_indo2($sql_pasien->tgl_lahir), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Jenis Kelamin', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, general::jns_klm($sql_pasien->jns_klm), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Pekerjaan', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_pekerjaan->jenis, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Alamat', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(14, .5, $sql_pasien->alamat, '0', 0, 'L', $fill);
                    $pdf->Ln(1);
                    
                    // BLOK KETERANGAN
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(3.5, .5, 'RIWAYAT KELUHAN', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->MultiCell(19, .5, $sql_medc_srt->ruj_keluhan, '', 'J', false);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(3.5, .5, 'DIAGNOSIS SEMENTARA', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->MultiCell(19, .5, $sql_medc_srt->ruj_diagnosa, '', 'J', false);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(3.5, .5, 'TERAPI YANG TELAH DIBERIKAN', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->MultiCell(19, .5, $sql_medc_srt->ruj_terapi, '', 'J', false);
                    $pdf->Ln(1);
            
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Demikian surat rujukan ini, atas kerjasama dan perhatian Teman Sejawat', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(19, .5, 'kami ucapkan terimakasih.', '', 0, 'L', $fill);
                    $pdf->Ln(2);
                    
                    $ket = 'Salam Sejawat';
                    break;
                
                case '13' :
                    $judul = "SURAT ".strtoupper(general::tipe_surat($sql_medc_srt->tipe));

                    // Blok Judul
                    $pdf->SetFont('Arial', 'B', '14');
                    $pdf->Cell(19, .5, $judul, 0, 1, 'C');
                    $pdf->Ln(0);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(19, .5, 'Nomor : '.$sql_medc_srt->no_surat, 0, 1, 'C');
                    $pdf->Ln();

                    // Blok Paragraf Pertama
                    $fill = FALSE;
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Yang bertanda tangan di bawah ini, menerangkan bahwa :', '', 0, 'L', $fill);
                    $pdf->Ln(1);

                    // Blok ID PASIEN
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Nama Lengkap', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $sql_pasien->nama_pgl, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Tanggal Lahir', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $this->tanggalan->tgl_indo2($sql_pasien->tgl_lahir).' - '.$this->tanggalan->usia($sql_pasien->tgl_lahir), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Jenis Kelamin', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, general::jns_klm($sql_pasien->jns_klm), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Pekerjaan', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(13, .5, $sql_pekerjaan->jenis, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(3.5, .5, 'Alamat', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
//                    $pdf->Cell(13, .5, $sql_pasien->alamat, '0', 0, 'L', $fill);
                    $pdf->MultiCell(13, .5, $sql_pasien->alamat, '0', 'L');
                    $pdf->Ln(1);

                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Berdasarkan hasil pemeriksaan sampel urine untuk pemeriksaan Test Narkoba di Laboratorium Klinik Esensia,', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(7.5, .5, 'sampel urine pasien dinyatakan dengan hasil', '', 0, 'L', $fill);
                    $pdf->SetFont('Arial', 'Bi', '10');
                    $pdf->Cell(2, .5, ($sql_medc_srt->nza_status == '1' ? 'POSITIF' : 'NEGATIF'), '', 0, 'L', $fill);
                    $pdf->Ln(1);
            
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Demikian surat keterangan ini dibuat agar dapat digunakan sebagaimana mestinya.', '', 0, 'L', $fill);
                    $pdf->Ln(2);
                    break;
            }
            
            // QR GENERATOR VALIDASI
            $qr_validasi        = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-validasi-'.strtolower($kode_pasien).'.png';
            $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh manajemen klinik esensia. Pasien a/n. ';
            $params['data']    .= strtoupper($sql_pasien->nama_pgl).' ('.strtoupper($kode_pasien).')';
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_validasi; 
            $this->ciqrcode->generate($params);
            
            $gambar4            = $qr_validasi; 
                        
            // QR GENERATOR DOKTER
            $qr_dokter          = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-dokter-'.strtolower($sql_dokter->id).'.png';
            $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh dokter penanggung jawab ['.(!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').$sql_dokter->nama.(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk : '').']';
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_dokter;
            $this->ciqrcode->generate($params);
            
            $gambar5            = $qr_dokter;        
            
            // Gambar VALIDASI
//            $pdf->SetY(-10.5);
            $pdf->Image($gambar4,2,$pdf->GetY() + 1,2,2);
            $pdf->Image($gambar5,12.5,$pdf->GetY()+1,2,2);
            
//            $pdf->SetY(-11.5);
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(11.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, 'Semarang, '.$this->tanggalan->tgl_indo3($sql_medc_srt->tgl_simpan), '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '10');
            $pdf->Cell(4, .5, 'Validasi', '0', 0, 'C', $fill);
            $pdf->Cell(7.5, .5, '', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(7.5, .5, (!empty($ket) ? $ket : 'Dokter Pemeriksa'), '0', 0, 'L', $fill);
            $pdf->Ln(2.5);
            
//            $pdf->SetY(-8.5);
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(11.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, (!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').$sql_dokter->nama.(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk : ''), '', 0, 'L', $fill);
            $pdf->Ln();
                    
            $pdf->SetFillColor(235, 232, 228);
            $pdf->SetTextColor(0);
            $pdf->SetFont('Arial', '', '10');

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $pdf->Output($sql_pasien->nama_pgl. '.pdf', $type);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function pdf_medcheck_surat_inform() {
        if (akses::aksesLogin() == TRUE) {
            $setting            = $this->db->get('tbl_pengaturan')->row();
            $id                 = $this->input->get('id');
            $id_medcheck        = $this->input->get('no_nota');

            $sql_medc           = $this->db->where('id', general::dekrip($id_medcheck))->get('tbl_trans_medcheck')->row();
            $sql_medc_srt       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck_surat_inform')->row(); 
            $sql_pasien         = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row(); 
            $sql_pekerjaan      = $this->db->where('id', $sql_pasien->id_pekerjaan)->get('tbl_m_jenis_kerja')->row();
            $sql_dokter         = $this->db->where('id_user', $sql_medc->id_dokter)->get('tbl_m_karyawan')->row();
            $sql_dokter2        = $this->db->where('id_user', $sql_medc_srt->id_dokter)->get('tbl_m_karyawan')->row();
            $kode_pasien        = $sql_pasien->kode_dpn.$sql_pasien->kode;
            $gambar1            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-esensia-2.png';
            $gambar2            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-bw-bg2-1440px.png';
            $gambar3            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-footer.png';
            
            $this->load->library('MedSuratPDF');
            $pdf = new MedSuratPDF('P', 'cm', array(21.5,33));
            $pdf->SetAutoPageBreak('auto', 6.5);
//            $pdf->SetMargins(1, 1, 1);
            $pdf->header = 0;
            $pdf->addPage('','',false);
            
            // Gambar Watermark Tengah
            $pdf->Image($gambar2,5,4,17,19);
                        
            // Blok Isi Surat
            switch ($sql_medc_srt->tipe){
                
                case '1' :
                    $judul = "SURAT ".strtoupper(general::tipe_surat_inf($sql_medc_srt->tipe));

                    // Blok Judul
                    $pdf->SetFont('Arial', 'B', '14');
                    $pdf->Cell(19, .5, $judul, 0, 1, 'C');
                    $pdf->Ln(0);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(19, .5, 'Nomor : '.$sql_medc_srt->no_surat, 0, 1, 'C');
                    $pdf->Ln();

                    // Blok Paragraf Pertama
                    $fill = FALSE;
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Yang bertanda tangan dibawah ini,', '', 0, 'L', $fill);
                    $pdf->Ln(1);

                    // Blok PENANGGUNG JAWAB
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Nama Lengkap', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, strtoupper($sql_medc_srt->nama), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Tanggal Lahir', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, ($sql_medc_srt->tgl_lahir != '0000-00-00' ? $this->tanggalan->tgl_indo2($sql_medc_srt->tgl_lahir).' ('.$this->tanggalan->usia($sql_medc_srt->tgl_lahir).')' : '-').(!empty($sql_medc_srt->jns_klm) ? ' / '.general::jns_klm($sql_medc_srt->jns_klm) : ''), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Alamat', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
//                    $pdf->Cell(12, .5, $sql_medc_srt->alamat, '0', 0, 'L', $fill);
                    $pdf->MultiCell(12.5, .5, $sql_medc_srt->alamat, '', 'L', $fill);
//                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Hubungan dengan pasien', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, general::tipe_hubungan($sql_medc_srt->status_hub), '0', 0, 'L', $fill);
                    $pdf->Ln(1);
                    
                    # Blok Paragraf kedua
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Dengan ini menyatakan,', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(10, .5, 'Telah melihat, memahami dan menyetujui tarif yang berlaku di ', '', 0, 'L', $fill);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(9, .5, strtoupper($setting->judul), '', 0, 'L', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Ln();
                    $pdf->Cell(19, .5, 'beserta tindakan dan obat-obatan yang dimungkinkan. Sanggup menjamin kelancaran pembayaran semua biaya', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(19, .5, 'pelayanan bagi pasien :', '', 0, 'L', $fill);
                    $pdf->Ln(1);

                    // Blok ID PASIEN
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Nomor Rekam Medis', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, $sql_pasien->kode_dpn.$sql_pasien->kode, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'NIK', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, $sql_pasien->nik, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Nama Lengkap', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, $sql_pasien->nama_pgl, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Tanggal Lahir', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, $this->tanggalan->tgl_indo2($sql_pasien->tgl_lahir).' ('.$this->tanggalan->usia_lkp($sql_pasien->tgl_lahir).')'.(!empty($sql_pasien->jns_klm) ? ' / '.general::jns_klm($sql_pasien->jns_klm) : ''), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Alamat', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
//                    $pdf->Cell(12, .5, $sql_pasien->alamat, '0', 0, 'L', $fill);
                    $pdf->MultiCell(12.5, .5, $sql_pasien->alamat, '', 'L', $fill);
//                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Tanggal Masuk', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, $this->tanggalan->tgl_indo2($sql_medc->tgl_masuk), '0', 0, 'L', $fill);
                    $pdf->Ln();
//                    $pdf->SetFont('Arial', 'B', '10');
//                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
//                    $pdf->Cell(4.5, .5, 'Ruang Perawatan', '0', 0, 'L', $fill);
//                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
//                    $pdf->SetFont('Arial', '', '10');
//                    $pdf->Cell(12, .5, $sql_medc_srt->ruang, '0', 0, 'L', $fill);
//                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Dokter yang merawat', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, (!empty($sql_dokter2->nama_dpn) ? $sql_dokter2->nama_dpn.' ' : '').$sql_dokter2->nama.(!empty($sql_dokter2->nama_blk) ? ', '.$sql_dokter2->nama_blk.' ' : ''), '0', 0, 'L', $fill);
                    $pdf->Ln(1);
                    
                    # Tentukan penjamin ASURANSI / UMUM, jika 1 maka umum
                    if($sql_medc->tipe_bayar == '1'){                    
                        $pdf->SetFont('Arial', 'B', '10');
//                        $pdf->Cell(1, .5, '', '', 0, 'L', $fill);
                        $pdf->Cell(4.5, .5, 'PASIEN UMUM / PRIBADI', '0', 0, 'L', $fill);
                        $pdf->Ln();
                        $pdf->SetFont('Arial', '', '10');
//                        $pdf->Cell(1, .5, '', '', 0, 'L', $fill);
                        $pdf->Cell(19, .5, 'Bersedia dan siap membayar seluruh biaya perawatan dan tindakan serta obat - obatan yang diberikan terhadap', '', 0, 'L', $fill);
                        $pdf->Ln();
//                        $pdf->Cell(1, .5, '', '', 0, 'L', $fill);
                        $pdf->Cell(19, .5, 'pasien tersebut diatas, yang jumlahnya akan diketahui setelah pasien selesai Rawat Inap.', '', 0, 'L', $fill);
                        $pdf->Ln(1);
                    }else{
                        $pdf->SetFont('Arial', 'B', '10');
                        $pdf->Cell(4.5, .5, 'PASIEN ASURANSI', '0', 0, 'L', $fill);
                        $pdf->Ln();
                        $pdf->SetFont('Arial', 'B', '10');
                        $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                        $pdf->Cell(4.5, .5, 'Penjamin', '0', 0, 'L', $fill);
                        $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                        $pdf->SetFont('Arial', '', '10');
                        $pdf->Cell(12, .5, $sql_medc_srt->penjamin, '0', 0, 'L', $fill);
                        $pdf->Ln();
                        $pdf->SetFont('Arial', 'B', '10');
                        $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                        $pdf->Cell(4.5, .5, 'Keterangan', '0', 0, 'L', $fill);
                        $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                        $pdf->SetFont('Arial', '', '10');
                        $pdf->Cell(12, .5, $sql_medc_srt->penanggung, '0', 0, 'L', $fill);
                        $pdf->Ln();
                        $pdf->Cell(19, .5, 'Bila kelas yang dipilih melebihi dari jatah kelas yang ditentukan, bersedia membayar', '', 0, 'L', $fill);
                        $pdf->Ln();
                        $pdf->Cell(19, .5, 'seluruh selisih biaya sesuai dengan tarif yang berlaku.', '', 0, 'L', $fill);
                        $pdf->Ln(1);
                    }
            
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Demikian surat pernyataan ini dibuat dalam keadaan sadar tanpa ada tekanan dari pihak manapun.', '', 0, 'L', $fill);
                    $pdf->Ln();
            
                    # QR GENERATOR VALIDASI
                    $qr_validasi        = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-petugas-'.strtolower($sql_medc->no_rm).'.png';
                    $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh manajemen klinik esensia. Diwakili oleh ';
                    $params['data']    .= strtoupper($this->ion_auth->user($sql_medc_srt->id_user)->row()->first_name).',';
                    $params['data']    .= 'yang merupakan petugas yang bertugas pada tanggal '.date('d/m/Y H:i').'.';
                    $params['level']    = 'H';
                    $params['size']     = 2;
                    $params['savename'] = $qr_validasi; 
                    $this->ciqrcode->generate($params);
                    
                    $gambar4            = $qr_validasi; 
                                
                    # QR GENERATOR DOKTER
                    $qr_dokter          = FCPATH.'/'.$sql_medc_srt->file_name1;
//                    $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh dokter penanggung jawab ['.(!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').$sql_dokter->nama.(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk : '').']';
//                    $params['level']    = 'H';
//                    $params['size']     = 2;
//                    $params['savename'] = $qr_dokter;
//                    $this->ciqrcode->generate($params);
                    $gambar5            = $qr_dokter;        
                    
                    # Gambar VALIDASI
                    $pdf->SetY(-10.5);
//                    $pdf->Image($gambar4,1.25,22.75,3.5,3.5);
//                    $pdf->Image($gambar5,12.5,22.75,3.5,3.5);
                    $pdf->Image($gambar4,2,$pdf->GetY(),2,2);
                    $pdf->Image($gambar5,12.5,$pdf->GetY(),2,2);
                    
                    $pdf->SetY(-11.5);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(11.5, .5, '', '', 0, 'L', $fill);
                    $pdf->Cell(7.5, .5, 'Semarang, '.$this->tanggalan->tgl_indo3($sql_medc_srt->tgl_masuk), '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(4.5, .5, 'Petugas', '0', 0, 'C', $fill);
                    $pdf->Cell(7.5, .5, '', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(7.5, .5, (!empty($ket) ? $ket : 'Yang Membuat Pernyataan'), '0', 0, 'L', $fill);
                    $pdf->Ln(4);
                    
                    $pdf->SetY(-8.5);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(11.5, .5, $this->ion_auth->user($sql_medc_srt->id_user)->row()->first_name, '', 0, 'L', $fill);
                    $pdf->Cell(7.5, .5, strtoupper($sql_medc_srt->nama), '', 0, 'L', $fill);
                    $pdf->Ln();
                    break;
                    
                case '2' :
                    $judul = "SURAT ".strtoupper(general::tipe_surat_inf($sql_medc_srt->tipe));

                    // Blok Judul
                    $pdf->SetFont('Arial', 'B', '14');
                    $pdf->Cell(19, .5, $judul, 0, 1, 'C');
                    $pdf->Ln(0);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(19, .5, 'Nomor : '.$sql_medc_srt->no_surat, 0, 1, 'C');
                    $pdf->Ln();

                    // Blok Paragraf Pertama
                    $fill = FALSE;
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Yang bertanda tangan dibawah ini,', '', 0, 'L', $fill);
                    $pdf->Ln(1);

                    // Blok PENANGGUNG JAWAB
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Nama Lengkap', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, $sql_medc_srt->nama, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Tanggal Lahir', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, ($sql_medc_srt->tgl_lahir != '0000-00-00' ? $this->tanggalan->tgl_indo2($sql_medc_srt->tgl_lahir).' ('.$this->tanggalan->usia($sql_medc_srt->tgl_lahir).')' : '-').(!empty($sql_medc_srt->jns_klm) ? ' / '.general::jns_klm($sql_medc_srt->jns_klm) : ''), '0', 0, 'L', $fill);
                    $pdf->Ln();
//                    $pdf->SetFont('Arial', 'B', '10');
//                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
//                    $pdf->Cell(4, .5, 'Alamat', '0', 0, 'L', $fill);
//                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
//                    $pdf->SetFont('Arial', '', '10');
//                    $pdf->Cell(12.5, .5, $sql_medc_srt->alamat, '0', 0, 'L', $fill);
//                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Hubungan dengan pasien', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, general::tipe_hubungan($sql_medc_srt->status_hub), '0', 0, 'L', $fill);
                    $pdf->Ln(1);
                    
                    # Blok Paragraf kedua
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Dengan ini saya menyatakan dengan sesungguhnya telah memberikan pernyataan,', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(19, .5, general::tipe_surat_inf_stj($sql_medc_srt->status_stj), '', 0, 'C', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(10, .5, 'Untuk dilakukan pemeriksaan, pengobatan dan tindakan medis yaitu :', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'iB', '10');
                    $pdf->MultiCell(19, .5, $sql_medc_srt->tindakan, '', 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'terhadap pasien dengan identitas pasien sebagai berikut :', '', 0, 'L', $fill);
                    $pdf->Ln(1);

                    // Blok ID PASIEN
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Nomor Rekam Medis', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, $sql_pasien->kode_dpn.$sql_pasien->kode, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'NIK', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, $sql_pasien->nik, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Nama Lengkap', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, $sql_pasien->nama_pgl, '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Tanggal Lahir', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, $this->tanggalan->tgl_indo2($sql_pasien->tgl_lahir).' ('.$this->tanggalan->usia_lkp($sql_pasien->tgl_lahir).')'.(!empty($sql_pasien->jns_klm) ? ' / '.general::jns_klm($sql_pasien->jns_klm) : ''), '0', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Alamat', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->MultiCell(12, .5, $sql_pasien->alamat, '', 'L', $fill);
//                    $pdf->Ln();
//                    $pdf->SetFont('Arial', 'B', '10');
//                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
//                    $pdf->Cell(4, .5, 'Tanggal Masuk', '0', 0, 'L', $fill);
//                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
//                    $pdf->SetFont('Arial', '', '10');
//                    $pdf->Cell(12.5, .5, $this->tanggalan->tgl_indo2($sql_medc->tgl_masuk), '0', 0, 'L', $fill);
//                    $pdf->Ln();
//                    $pdf->SetFont('Arial', 'B', '10');
//                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
//                    $pdf->Cell(4, .5, 'Ruang Perawatan', '0', 0, 'L', $fill);
//                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
//                    $pdf->SetFont('Arial', '', '10');
//                    $pdf->Cell(12.5, .5, (!empty($sql_medc_srt->ruang) ? $sql_medc_srt->ruang : '-'), '0', 0, 'L', $fill);
//                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(1, .5, '', '0', 0, 'L', $fill);
                    $pdf->Cell(4.5, .5, 'Dokter yang merawat', '0', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12, .5, (!empty($sql_dokter2->nama_dpn) ? $sql_dokter2->nama_dpn.' ' : '').$sql_dokter2->nama.(!empty($sql_dokter2->nama_blk) ? ', '.$sql_dokter2->nama_blk.' ' : ''), '0', 0, 'L', $fill);
                    $pdf->Ln(1);
                    
                    # Paragraf bawah
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Yang cara, manfaat tujuan serta resiko yang ditimbulkan dari pemeriksaan pengobatan serta tindakan medis tersebut', '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->Cell(19, .5, 'diatas telah dijelaskan oleh dokter dan saya telah mengerti sepenuhnya.', '', 0, 'L', $fill);
                    $pdf->Ln();
            
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(19, .5, 'Demikian surat pernyataan ini dibuat dalam keadaan sadar tanpa ada tekanan dari pihak manapun.', '', 0, 'L', $fill);
                    $pdf->Ln();
            
                    # QR GENERATOR VALIDASI
                    $qr_validasi        = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-petugas-'.strtolower($sql_medc->no_rm).'.png';
                    $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh manajemen klinik esensia. Diwakili oleh ';
                    $params['data']    .= strtoupper($this->ion_auth->user($sql_medc_srt->id_user)->row()->first_name).',';
                    $params['data']    .= 'yang merupakan petugas yang bertugas pada tanggal '.date('d/m/Y H:i').'.';
                    $params['level']    = 'H';
                    $params['size']     = 2;
                    $params['savename'] = $qr_validasi; 
                    $this->ciqrcode->generate($params);
                    
                    $gambar4            = $qr_validasi; 
                                
                    # QR GENERATOR TANDA TANGAN
                    $qr_ttd             = FCPATH.'/'.$sql_medc_srt->file_name1;
                    $gambar5            = $qr_ttd;        
                                
                    # QR GENERATOR DOKTER
                    $qr_dokter          = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-dokter-'.strtolower($sql_dokter->id).'.png';
                    $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh dokter penanggung jawab ['.(!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').$sql_dokter->nama.(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk : '').']';
                    $params['level']    = 'H';
                    $params['size']     = 2;
                    $params['savename'] = $qr_dokter;
                    $this->ciqrcode->generate($params);
                    $gambar6            = $qr_dokter;        
                    
                    # Gambar VALIDASI
                    $pdf->SetY(-13.5);
                    $pdf->Image($gambar6,3.25,$pdf->GetY(),2,2);
                    $pdf->Image($gambar5,13.5,$pdf->GetY(),2,2);
                    
                    # QR Petugas di kolom saksi
                    $pdf->SetY(-10.5);
                    $pdf->Image($gambar4,3.25,$pdf->GetY(),2,2);
                    
                    $pdf->SetY(-14.5);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(12.5, .5, '', '', 0, 'L', $fill);
                    $pdf->Cell(6.5, .5, 'Semarang, '.$this->tanggalan->tgl_indo3($sql_medc_srt->tgl_masuk), '', 0, 'L', $fill);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(6.5, .5, 'Dokter Penanggung Jawab', '0', 0, 'C', $fill);
                    $pdf->Cell(6, .5, '', '0', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(6.5, .5, (!empty($ket) ? $ket : 'Yang Membuat Pernyataan'), '0', 0, 'L', $fill);
                    $pdf->Ln(4);
                    
                    $pdf->SetY(-11.5);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(6.5, .5,(!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').$sql_dokter->nama.(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk.' ' : ''), '', 0, 'L', $fill);
                    $pdf->Cell(6, .5, '', '0', 0, 'C', $fill);
                    $pdf->Cell(6.5, .5, $sql_medc_srt->nama, '', 0, 'L', $fill);
                    $pdf->Ln();
                    
                    $pdf->SetY(-11);
                    $pdf->SetFont('Arial', 'B', '10');
                    $pdf->Cell(6.5, .5, 'Saksi 1', '', 0, 'C', $fill);
                    $pdf->Cell(6, .5, '', '0', 0, 'C', $fill);
                    $pdf->Cell(6.5, .5, 'Saksi 2 '.$sql_pasien->nama, '', 0, 'L', $fill);
                    $pdf->Ln(4);
                    
                    $pdf->SetY(-8.5);
                    $pdf->SetFont('Arial', '', '10');
                    $pdf->Cell(6.5, .5, '( '.$this->ion_auth->user($sql_medc_srt->id_user)->row()->first_name.' )', '', 0, 'C', $fill);
                    $pdf->Cell(6, .5, '', '', 0, 'L', $fill);
//                    $pdf->Cell(6.5, .5, '( '.$sql_pasien->nama.' )', '', 0, 'C', $fill);
                    $pdf->Ln();
                    break;
            }
                    
            $pdf->SetFillColor(235, 232, 228);
            $pdf->SetTextColor(0);
            $pdf->SetFont('Arial', '', '10');

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $pdf->Output($sql_pasien->nama_pgl. '.pdf', $type);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function pdf_medcheck_lab() {
        if (akses::aksesLogin() == TRUE) {
            $setting            = $this->db->get('tbl_pengaturan')->row();
            $id_medcheck        = $this->input->get('id');
            $id_lab             = $this->input->get('id_lab');
            $status_ctk         = $this->input->get('status_ctk');
            
            $sql_medc           = $this->db->where('id', general::dekrip($id_medcheck))->get('tbl_trans_medcheck')->row();
            $sql_medc_srt       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck_surat')->row(); 
            $sql_medc_lab       = $this->db->where('id', general::dekrip($id_lab))->get('tbl_trans_medcheck_lab')->row(); 
            $sql_medc_lab_det   = $this->db->where('id_medcheck', general::dekrip($id_medcheck))->where('id_lab', general::dekrip($id_lab))->where('status', '3')->where('status_hsl', '1')->group_by('id_lab_kat')->get('tbl_trans_medcheck_det')->result(); 
            $sql_poli           = $this->db->where('id', $sql_medc->id_poli)->get('tbl_m_poli')->row(); 
            $sql_pasien         = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row(); 
            $sql_pekerjaan      = $this->db->where('id', $sql_pasien->id_pekerjaan)->get('tbl_m_jenis_kerja')->row();
            $sql_dokter         = $this->db->where('id_user', (!empty($sql_medc_lab->id_dokter) ? $sql_medc_lab->id_dokter : $sql_medc->id_dokter))->get('tbl_m_karyawan')->row();
            $sql_dokter2        = $this->db->where('id_user', '221')->get('tbl_m_karyawan')->row();
            $kode_pasien        = $sql_pasien->kode_dpn.$sql_pasien->kode;
            $gambar1            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-esensia-2.png'; // base_url('assets/theme/admin-lte-3/dist/img/logo-esensia-2.png');
            $gambar2            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-bw-bg2-1440px.png'; // base_url('assets/theme/admin-lte-3/dist/img/logo-bw-bg2-1440px.png');
            $gambar3            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-footer.png'; // base_url('assets/theme/admin-lte-3/dist/img/logo-footer.png');
            $sess_print         = $this->session->userdata('lab_print');
            
//            echo '<pre>';
//            print_r($sess_print);
//            echo '</pre>';

            $judul              = "HASIL PEMERIKSAAN LABORATORIUM";
            $judul2             = "Laboratory Result";

            $this->load->library('MedLabPDF');
            $pdf = new MedLabPDF('P', 'cm', array(21.5,33));
            $pdf->SetAutoPageBreak('auto', 6);
            $pdf->header = 0;
            $pdf->addPage('','',false);
            
            // Gambar Watermark Tengah
            $pdf->Image($gambar2,5,4,15,19);
            
            // Blok Judul
            $pdf->SetFont('Arial', 'B', '14');
            $pdf->Cell(19, .5, $judul, 0, 1, 'C');
            $pdf->Ln(0);
            $pdf->SetFont('Arial', 'Bi', '14');
            $pdf->Cell(19, .5, $judul2, 0, 1, 'C');
            $pdf->Ln();
            
            // Blok ID PASIEN
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(3, .5, 'No. Pemeriksaan', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(4.5, .5, $sql_medc_lab->no_lab, '0', 0, 'L', $fill);
            $pdf->Cell(2.5, .5, 'No. RM', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $sql_pasien->kode_dpn.$sql_pasien->kode, '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(3, .5, 'No. Sampel', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(4.5, .5, $sql_medc_lab->no_sample, '0', 0, 'L', $fill);
            $pdf->Cell(2.5, .5, 'Nama Name', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $sql_pasien->nama_pgl.' ('.$sql_pasien->jns_klm.')', '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(3, .5, 'Tgl Periksa', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(4.5, .5, $this->tanggalan->tgl_indo5($sql_medc_lab->tgl_masuk), '0', 0, 'L', $fill);
            $pdf->Cell(2.5, .5, 'NIK', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $sql_pasien->nik, '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(3, .5, 'Poli', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(4.5, .5, $sql_poli->lokasi, '0', 0, 'L', $fill);
            $pdf->Cell(2.5, .5, 'Tgl Lahir', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $this->tanggalan->tgl_indo2($sql_pasien->tgl_lahir).' / '.$this->tanggalan->usia_lkp($sql_pasien->tgl_lahir), '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(3, .5, 'Alamat', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->MultiCell(15.5, .5, (!empty($sql_pasien->alamat) ? $sql_pasien->alamat : (!empty($sql_pasien->alamat_dom) ? $sql_pasien->alamat_dom : '-')), '0', 'L');
            $pdf->Cell(3, .5, 'No. HP / Rmh', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->MultiCell(15.5, .5, $sql_pasien->no_hp.(!empty($penj->no_telp) ? ' / '.$penj->no_telp : ''), '0', 'L');
            $pdf->Cell(3, .5, 'Dokter Pengirim', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->MultiCell(15.5, .5, (!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').$sql_dokter->nama.(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk : ''), '0', 'L');
            $pdf->Ln();
            
            $fill = FALSE;
            $pdf->SetFont('Arial', 'B', '11');
            $pdf->Cell(7, .5, 'PEMERIKSAAN', 'T', 0, 'L', $fill);
            $pdf->Cell(4.5, .5, 'HASIL', 'T', 0, 'L', $fill);
            $pdf->Cell(5.5, .5, 'NILAI RUJUKAN', 'T', 0, 'L', $fill);
            $pdf->Cell(2, .5, 'SATUAN', 'T', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'Bi', '10');
            $pdf->Cell(7, .5, 'EXAMINATION', 'B', 0, 'L', $fill);
            $pdf->Cell(4.5, .5, 'RESULT', 'B', 0, 'L', $fill);
            $pdf->Cell(5.5, .5, 'REFERENCE VALUE', 'B', 0, 'L', $fill);
            $pdf->Cell(2, .5, 'MEASURE', 'B', 0, 'L', $fill);
            $pdf->Ln();
            
            # Jika status cetak 1, maka akan di cetak semua
            # Pilihan ini untuk mengakomodir ePasien supaya bisa mencetak hasil
            # Jika status cetak tidak ada, maka untuk perawat bisa memilih item yg di cetak
            if ($status_ctk == '1') {
                foreach ($sql_medc_lab_det as $det) {
                    $sql_kat = $this->db->where('id', $det->id_lab_kat)->get('tbl_m_kategori')->row();
                    $sql_det = $this->db->where('status_hsl', '1')->where('id_medcheck', $det->id_medcheck)->where('id_lab', $det->id_lab)->where('id_lab_kat', $det->id_lab_kat)->get('tbl_trans_medcheck_det')->result();

                    if (!empty($det->id_lab_kat)) {
                        $pdf->SetFont('Arial', 'Bi', '10');
                        $pdf->Cell(19, .5, $sql_kat->keterangan, '', 0, 'L', $fill);
                        $pdf->Ln();
                    }

                    foreach ($sql_det as $medc) {
                        $sql_lab_rws = $this->db->where('id_medcheck', $medc->id_medcheck)->get('tbl_trans_medcheck_lab');
                        if ($sql_lab_rws->num_rows() > 1) {
                            $sql_lab = $this->db->where('id_medcheck', $medc->id_medcheck)->where('id_lab', general::dekrip($this->input->get('id_lab')))->where('id_item', $medc->id_item)->get('tbl_trans_medcheck_lab_hsl')->result();
                        } else {
                            $sql_lab = $this->db->where('id_medcheck', $medc->id_medcheck)->where('id_item', $medc->id_item)->get('tbl_trans_medcheck_lab_hsl')->result();
                        }

                        $pdf->SetFont('Arial', '', '8');

                        if (!empty($det->id_lab_kat)) {
                            $pdf->Cell(.25, .5, '', '', 0, 'L', $fill);
                        }

                        $pdf->Cell(18.5, .5, $medc->item . ($lab_det->status_hsl_lab == '1' ? '*' : ''), '', 0, 'L', $fill);
                        $pdf->Ln();

                        foreach ($sql_lab as $lab) {
                            $pdf->Cell(.25, .5, '', '', 0, 'L', $fill);
                            $pdf->Cell(7, .5, ' - ' . html_entity_decode($lab->item_name) . ($lab->status_hsl_lab == '1' ? '*' : ''), '', 0, 'L', $fill);
//                            $pdf->Cell(7, .5, '', 'B', 0, 'L', $fill);
                            $pdf->Cell(4.5, .5, html_entity_decode($lab->item_hasil, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
                            
                            $x = $pdf->GetX();
                            $y = $pdf->GetY();                           
                            $pdf->MultiCell(5.5, .5, html_entity_decode($lab->item_value, ENT_NOQUOTES, 'utf-8'), '', 'L');                            
                            $pdf->SetXY($x + 5.5, $y);                            
                            $pdf->Cell(2, .5, html_entity_decode($lab->item_satuan, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
//                            $pdf->Cell(5.5, .5, html_entity_decode($lab->item_value, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
//                            $pdf->Cell(2, .5, html_entity_decode($lab->item_satuan, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
                            $pdf->Ln();
                        }
                    }
                }
            } else {
                # Untuk Mencetak dengan pilihan
                
                $sql_medc_lab_det2   = $this->db->where('status', '3')->where('status_ctk', '1')->where('status_hsl', '1')->group_by('id_lab_kat')->where('id_medcheck', general::dekrip($id_medcheck))->where('id_lab', general::dekrip($id_lab))->get('tbl_trans_medcheck_det')->result();
                
                $i = 0;
                foreach ($sql_medc_lab_det2 as $det2) {
                    $sql_kat2 = $this->db->where('id', $det2->id_lab_kat)->get('tbl_m_kategori')->row();
                    $sql_det2 = $this->db->where('status_ctk', '1')->where('status_hsl', '1')->where('id_medcheck', $det2->id_medcheck)->where('id_lab', $det2->id_lab)->where('id_lab_kat', $det2->id_lab_kat)->get('tbl_trans_medcheck_det')->result();                        
                    
                    if (!empty($det2->id_lab_kat)) {
                        $pdf->SetFont('Arial', 'Bi', '10');
                        $pdf->Cell(19, .5, $sql_kat2->keterangan, '', 0, 'L', $fill);
                        $pdf->Ln();
                    }

                    foreach ($sql_det2 as $medc2) {
                        $sql_lab_rws2 = $this->db->where('id_medcheck', $medc2->id_medcheck)->get('tbl_trans_medcheck_lab');
                        if ($sql_lab_rws2->num_rows() > 1) {
                            $sql_lab2 = $this->db->where('id_medcheck', $medc2->id_medcheck)->where('id_lab', $medc2->id_lab)->where('id_item', $medc2->id_item)->get('tbl_trans_medcheck_lab_hsl')->result();
                        } else {
                            $sql_lab2 = $this->db->where('id_medcheck', $medc2->id_medcheck)->where('id_item', $medc2->id_item)->get('tbl_trans_medcheck_lab_hsl')->result();
                        }

                        $pdf->SetFont('Arial', '', '8');

                        if (!empty($det2->id_lab_kat)) {
//                            if ($sess_print[$i]['value'] == '1' AND $sess_print[$i]['id_kat'] == $det->id_lab_kat) {
                                $pdf->Cell(.25, .5, '', '', 0, 'L', $fill);
//                            }
                        }
                        
//                        if($sess_print[$i]['value'] == '1' AND $sess_print[$i]['id'] == $medc->id){
                            $pdf->Cell(18.5, .5, $medc2->item . ($lab_det2->status_hsl_lab == '1' ? '*' : ''), '', 0, 'L', $fill);
                            $pdf->Ln();
//                        }

                        foreach ($sql_lab2 as $lab2) {
//                            if ($sess_print[$i]['value'] == '1' AND $sess_print[$i]['id_lab_hsl'] == $lab->id) {
                                $pdf->Cell(.25, .5, '', '', 0, 'L', $fill);
                                $pdf->Cell(7, .5, ' - '.html_entity_decode($lab2->item_name) . ($lab2->status_hsl_lab == '1' ? '*' : ''), '', 0, 'L', $fill);
                                $pdf->Cell(4.5, .5, html_entity_decode($lab2->item_hasil, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
                                $pdf->Cell(5.5, .5, html_entity_decode($lab2->item_value, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
                                $pdf->Cell(2, .5, html_entity_decode($lab2->item_satuan, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
                                $pdf->Ln();
//                            }
                        }
                    }
                    
                    $i++;
                }                
            }

//            if($status_ctk == '1'){
//                $i = 0;
//                $fill = FALSE;
//                foreach ($sql_medc_lab_det as $key => $lab) {
//                        $sql_kat = $this->db->where('id', $lab->id_kat)->get('tbl_m_kategori');
//                        $sql_lab = $this->db->where('id', $lab->id)->where('status_hsl', '1')->get('tbl_trans_medcheck_det');
//                        $sql_lab_hsl = $this->db->where('id_medcheck', $sql_medc->id)->where('id_lab', general::dekrip($id_lab))->where('id_item', $sql_lab->row()->id_item)->get('tbl_trans_medcheck_lab_hsl')->result();
//
//                        $item = ($sql_kat->num_rows() == '0' ? $sql_lab->row()->item : $sql_kat->row()->keterangan);
//
//                        $pdf->Cell(18.5, .5, $sql_lab->row()->item . ($lab_det->status_hsl_lab == '1' ? '*' : ''), '', 0, 'L', $fill);
//                        $pdf->Ln();
//
//                        foreach ($sql_lab_hsl as $lab_item) {
//                            $sql_lab_nilai_rw = $this->db->where('id', $lab_item->id_item_ref_ip)->get('tbl_m_produk_ref_input')->row();
//
//                            // Detail Pemeriksaan dan hasilnya
//                            $pdf->SetFont('Arial', '', '8');
//                            $pdf->Cell(7, .5, ' - ' . html_entity_decode($lab_item->item_name) . ($lab_item->status_hsl_lab == '1' ? '*' : ''), '', 0, 'L', $fill);
//                            $pdf->Cell(4.5, .5, html_entity_decode($lab_item->item_hasil, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
////                            $pdf->Cell(5.5, .5, html_entity_decode($lab_item->item_value, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
////                            $pdf->MultiCell(5, .5, 'p'.$lab_item->item_value, '1', 'L');
////                            $pdf->Cell(2, .5, html_entity_decode($lab_item->item_satuan, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
//                            $pdf->Ln();
//                        }
//                    $i++;
//                }                
//            }else{
//                $i = 0;
//                $fill = FALSE;
//                foreach ($sql_medc_lab_det as $key => $lab) {
////                    if ($sess_print[$i]['value'] == '1') {
//                        $sql_kat        = $this->db->where('id', $sess_print[$i]['id_kat'])->get('tbl_m_kategori');
//                        $sql_lab        = $this->db->where('id', $sess_print[$i]['id'])->where('status_hsl', '1')->get('tbl_trans_medcheck_det');
//                        $sql_lab_hsl    = $this->db->where('id_medcheck', $sql_medc->id)->where('id_lab', general::dekrip($id_lab))->where('id_item', $sql_lab->row()->id_item)->get('tbl_trans_medcheck_lab_hsl')->result();
//
//                        $item = ($sql_kat->num_rows() == '0' ? $sql_lab->row()->item : $sql_kat->row()->keterangan);
//
//                        if(!empty($lab->id_lab_kat)){
//                            $pdf->SetFont('Arial', 'Bi', '10');
//                            $pdf->Cell(19, .5, $lab->id_lab_kat, '', 0, 'L', $fill);
//                            $pdf->Ln();
//                        }
////                        
////                        $pdf->SetFont('Arial', '', '8');
////                        $pdf->Cell(18.5, .5, $sql_lab->row()->item . ($lab_det->status_hsl_lab == '1' ? '*' : ''), '', 0, 'L', $fill);
////                        $pdf->Ln();
////
////                        foreach ($sql_lab_hsl as $lab_item) {
////                            $sql_lab_nilai_rw = $this->db->where('id', $lab_item->id_item_ref_ip)->get('tbl_m_produk_ref_input')->row();
////
////                            // Detail Pemeriksaan dan hasilnya
////                            $pdf->SetFont('Arial', '', '8');
////                            $pdf->Cell(7, .5, ' - ' . html_entity_decode($lab_item->item_name) . ($lab_item->status_hsl_lab == '1' ? '*' : ''), '', 0, 'L', $fill);
////                            $pdf->Cell(4.5, .5, html_entity_decode($lab_item->item_hasil, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
////                            $pdf->Cell(5.5, .5, html_entity_decode($lab_item->item_value, ENT_NOQUOTES, 'utf-8'), '1', 0, 'L', $fill);
//////                            $pdf->MultiCell(5.5, .5, $lab_item->item_value, '1', 'L');
////                            $pdf->Cell(2, .5, html_entity_decode($lab_item->item_satuan, ENT_NOQUOTES, 'utf-8'), '1', 0, 'L', $fill);
////                            $pdf->Ln();
////                        }
////                    }
//
//
//
//
//
//
////                echo '<pre>';
////                print_r($lab);
////                echo '</pre>';
////                
////                if($sess_print[$i]['value'] == 1){
////                    $sql_kat        = $this->db->where('id', $sess_print[$i]['id_kat'])->get('tbl_m_kategori');
////                    $sql_lab        = $this->db->where('id', $sess_print[$i]['id'])->where('status_hsl', '1')->get('tbl_trans_medcheck_det');
////                    
////                    if ($sql_kat->num_rows() > 0) {
////                        echo $sql_kat->row()->keterangan;
////                        echo br();
////                    }
////                }
////                if($sess_print[$i]['value'] == 1){
////                    $sql_kat        = $this->db->where('id', $sess_print[$i]['id_kat'])->get('tbl_m_kategori');
////                    $sql_lab        = $this->db->where('id', $sess_print[$i]['id'])->where('status_hsl', '1')->get('tbl_trans_medcheck_det');
////                    
////                    $item = ($sql_kat->num_rows() == '0' ? $sql_lab->row()->item : $sql_kat->row()->keterangan);
////                    
////                    $pdf->SetFont('Arial', '', '10');
////                    
//////                    if ($sql_kat->num_rows() > 0) {
//////                        // Kategori Bidang Muncul disini
//////                        $pdf->SetFont('Arial', 'Bi', '10');
//////                        $pdf->Cell(19, .5, $sql_kat->row()->keterangan, '', 0, 'L', $fill);
//////                        $pdf->Ln();
//////                    }else{
//////                        $sql_lab_hsl = $this->db->where('id_medcheck', $sql_medc->id)->where('id_lab', general::dekrip($id_lab))->where('id_item', $sql_lab->row()->id_item)->get('tbl_trans_medcheck_lab_hsl')->result();
//////                        
//////                        $pdf->Cell(18.5, .5, $item . ($lab_det->status_hsl_lab == '1' ? '*' : ''), '', 0, 'L', $fill);
//////                        $pdf->Ln();
//////                            
//////                        foreach ($sql_lab_hsl as $lab_item) {
//////                            $sql_lab_nilai_rw = $this->db->where('id', $lab_item->id_item_ref_ip)->get('tbl_m_produk_ref_input')->row();
//////
//////                            // Detail Pemeriksaan dan hasilnya
//////                            $pdf->SetFont('Arial', '', '8');
//////                            $pdf->Cell(7, .5, ' - ' . html_entity_decode($lab_item->item_name) . ($lab_item->status_hsl_lab == '1' ? '*' : ''), '', 0, 'L', $fill);
//////                            $pdf->Cell(4.5, .5, html_entity_decode($lab_item->item_hasil, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
//////                            $pdf->Cell(5.5, .5, html_entity_decode($lab_item->item_value, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
//////                            $pdf->Cell(2, .5, html_entity_decode($lab_item->item_satuan, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
//////                            $pdf->Ln();
//////                        }
//////                    }
////                    
////                    
////                    if($sql_kat->num_rows() > 0){
////                        $sql_lab_det     = $this->db->where('id_medcheck', $sql_medc->id)->where('id_lab', general::dekrip($id_lab))->where('id_lab_kat', $sess_print[$i]['id_kat'])->where('status_hsl', '1')->get('tbl_trans_medcheck_det');
////
////                        foreach ($sql_lab_det->result() as $lab_det) {
////                            $sql_lab_hsl = $this->db->where('id_medcheck', $sql_medc->id)->where('id_lab', general::dekrip($id_lab))->where('id_item', $lab_det->id_item)->get('tbl_trans_medcheck_lab_hsl')->result();
////                            
////                            $pdf->SetFont('Arial', '', '10');
////                            $pdf->Cell(0.5, .5, '', '', 0, 'L', $fill);
////                            $pdf->Cell(18.5, .5, $lab_det->item . ($lab_det->status_hsl_lab == '1' ? '*' : ''), '', 0, 'L', $fill);
////                            $pdf->Ln();
////                            
////                            foreach ($sql_lab_hsl as $lab_item){
////                                $sql_lab_nilai_rw = $this->db->where('id', $lab_item->id_item_ref_ip)->get('tbl_m_produk_ref_input')->row();
////
////                                // Detail Pemeriksaan dan hasilnya
////                                $pdf->SetFont('Arial', '', '8');
////                                $pdf->Cell(0.5, .5, '', '', 0, 'L', $fill);
////                                $pdf->Cell(6.5, .5, ' - ' . html_entity_decode($lab_item->item_name) . ($lab_item->status_hsl_lab == '1' ? '*' : ''), '', 0, 'L', $fill);
////                                $pdf->Cell(4.5, .5, html_entity_decode($lab_item->item_hasil, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
////                                $pdf->Cell(5.5, .5, html_entity_decode($lab_item->item_value, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
////                                $pdf->Cell(2, .5, html_entity_decode($lab_item->item_satuan, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
////                                $pdf->Ln();
////                            }
////                        }
////                    }
////                    
//////                    $ck = $i;
//////                    foreach ($sql_lab as $lab_det) {
//////                        echo nbs(5).'No : '.$sess_print[$i]['value'].br();
////////                        echo 'val : '.$sess_print[$ck]['value'].br();
////////                        echo 'Item : '.$lab_det->item.br();
////////                        
//////                        $ck++;
//////                    }
////                }
////                if ($sess_print[$i]['value'][$lab->id_lab_kat] == '1') {
////                    $sql_kat        = $this->db->where('id', $lab->id_lab_kat)->get('tbl_m_kategori')->row();
////                    $sql_lab        = $this->db->where('id_medcheck', $sql_medc->id)->where('id_lab', general::dekrip($id_lab))->where('id_lab_kat', $lab->id_lab_kat)->where('status_hsl', '1')->get('tbl_trans_medcheck_det')->result();
////                    
////                    if (!empty($lab->id_lab_kat)) {
////                        // Kategori Bidang Muncul disini
////                        $pdf->SetFont('Arial', 'Bi', '10');
////                        $pdf->Cell(19, .5, $sess_print[$i]['value'][$lab->id_lab_kat].$sql_kat->keterangan, '', 0, 'L', $fill);
////                        $pdf->Ln();
////                    }
////
////                    $ctk = 0;
////                    foreach ($sql_lab as $lab_det) {
//////                        if ($sess_print[$ctk]['id'] == $lab_det->id) {
////                            $sql_lab_det = $this->db->where('id_medcheck', $sql_medc->id)->where('id_lab', general::dekrip($id_lab))->where('id_item', $lab_det->id_item)->get('tbl_trans_medcheck_lab_hsl')->result();
////                            
////                            // Item pemeriksaan
////                            $pdf->SetFont('Arial', '', '10');
////                            if (!empty($lab->id_lab_kat)) {
////                                $pdf->Cell(0.5, .5, '', '', 0, 'L', $fill);
////                            }
////
////                            $pdf->Cell(18.5, .5, $sess_print[$ctk]['id'].$lab_det->item . ($lab_det->status_hsl_lab == '1' ? '*' : ''), '', 0, 'L', $fill);
////                            $pdf->Ln();
////
////                            foreach ($sql_lab_det as $lab_item) {
////                                $sql_lab_nilai_rw = $this->db->where('id', $lab_item->id_item_ref_ip)->get('tbl_m_produk_ref_input')->row();
////
////                                // Detail Pemeriksaan dan hasilnya
////                                $pdf->SetFont('Arial', '', '8');
////                                if (!empty($lab->id_lab_kat)) {
////                                    $pdf->Cell(0.5, .5, '', '', 0, 'L', $fill);
////                                }
////                                $pdf->Cell(6.5, .5, ' - ' . html_entity_decode($lab_item->item_name) . ($lab_item->status_hsl_lab == '1' ? '*' : ''), '', 0, 'L', $fill);
////                                $pdf->Cell(4.5, .5, html_entity_decode($lab_item->item_hasil, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
////                                $pdf->Cell(5.5, .5, html_entity_decode($lab_item->item_value, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
////                                $pdf->Cell(2, .5, html_entity_decode($lab_item->item_satuan, ENT_NOQUOTES, 'utf-8'), '', 0, 'L', $fill);
////                                $pdf->Ln();
////                            }
////                            $ctk++;
//////                        }
////                    }
////                    $i++;
////                }else{
////                    $sql_lab        = $this->db->where('id_medcheck', $sql_medc->id)->where('id_lab', general::dekrip($id_lab))->where('id_lab_kat', $lab->id_lab_kat)->where('status_hsl', '1')->get('tbl_trans_medcheck_det')->result();
////                    
////                    foreach ($sql_lab as $lab_det) {
////                            $pdf->Cell(18.5, .5, $sess_print[$ctk]['id'].$lab_det->item . ($lab_det->status_hsl_lab == '1' ? '*' : ''), '', 0, 'L', $fill);
////                            $pdf->Ln();
////                    }
////                }
//                    $i++;
//                }
//            }
            
            $pdf->SetFont('Arial', 'i', '8');
            $pdf->Ln();
            $pdf->Cell(19, .5, ($sql_medc_lab->status_duplo == '1' ? '* Sudah dilakukan duplo' : ''), 'T', 0, 'L', $fill);
            $pdf->Ln();
            
            // Keterangan Hasil Lab selain template covid
            if ($sql_medc_lab->status_cvd == '0' AND !empty($sql_medc_lab->ket)) {
                $pdf->SetFont('Arial', 'B', '10');
                $pdf->Cell(19, .5, 'Catatan / Note', '', 0, 'L', $fill);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', '9');
                $pdf->MultiCell(6, .5, $sql_medc_lab->ket, '0', 'L');
            }elseif($sql_medc_lab->status_cvd != '0'){
                $pdf->SetFont('Arial', 'B', '10');
                $pdf->Cell(19, .5, 'Catatan / Note', '', 0, 'L', $fill);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', '8');
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);              
                $pdf->Cell(.5, .5, '1.', '', 0, 'C', $fill);             
                $pdf->Cell(18, .5, 'Hasil positiv berlaku untuk hasil PCR SARS CoV-2 atau Antigen dari Laboratorium Klinik Utama Rawat Inap', '', 0, 'L', $fill);
                $pdf->Ln();
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);              
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);             
                $pdf->Cell(18, .5, 'Esensia. Nilai tersebut tidak dapat dibandingkan dengan CT hasil PCR SARS CoV-2 atau Antigen dari', '', 0, 'L', $fill);
                $pdf->Ln();
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);              
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);             
                $pdf->Cell(18, .5, 'laboratorium lain.', '', 0, 'L', $fill);
                $pdf->Ln();
                $pdf->SetFont('Arial', 'i', '8');
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);              
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);             
                $pdf->Cell(18, .5, 'Positive results apply to PCR results for SARS CoV-2 or Antigens from the Esensia Inpatient Main', '', 0, 'L', $fill);
                $pdf->Ln();
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);              
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);             
                $pdf->Cell(18, .5, 'Clinical Laboratory. This value cannot be compared with CT PCR results from SARS CoV-2 or Antigens from', '', 0, 'L', $fill);
                $pdf->Ln();
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);              
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);             
                $pdf->Cell(18, .5, 'other laboratories.', '', 0, 'L', $fill);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', '8');
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);              
                $pdf->Cell(.5, .5, '2.', '', 0, 'C', $fill);             
                $pdf->Cell(18, .5, 'Kondisi tersebut hanya menggambarkan kondisi saat pengambilan sampel.', '', 0, 'L', $fill);
                $pdf->Ln();
                $pdf->SetFont('Arial', 'i', '8');
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);              
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);             
                $pdf->Cell(18, .5, 'These conditions only describe the conditions at the time of sampling.', '', 0, 'L', $fill);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', '8');
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);              
                $pdf->Cell(.5, .5, '3.', '', 0, 'C', $fill);             
                $pdf->Cell(18, .5, 'Bila hasil positif dan terdapat gejala klinis, segera konsultasikan ke faskes.', '', 0, 'L', $fill);
                $pdf->Ln();
                $pdf->SetFont('Arial', 'i', '8');
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);              
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);             
                $pdf->Cell(18, .5, 'These conditions only describe the conditions at the time of sampling.', '', 0, 'L', $fill);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', '8');
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);              
                $pdf->Cell(.5, .5, '4.', '', 0, 'C', $fill);             
                $pdf->Cell(18, .5, 'Bila hasil negatif tidak selalu berarti pasien tidak terinfeksi SARS CoV-2, dan perlu dilakukan', '', 0, 'L', $fill);
                $pdf->Ln();
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);              
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);             
                $pdf->Cell(18, .5, 'pemeriksaan secara berkala.', '', 0, 'L', $fill);
                $pdf->Ln();
                $pdf->SetFont('Arial', 'i', '8');
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);              
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);             
                $pdf->Cell(18, .5, 'A negative results  does not necessarily mean that the patient is not infected with SARS-CoV-2, and', '', 0, 'L', $fill);
                $pdf->Ln();
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);
                $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);
                $pdf->Cell(18, .5, 'periodic examinations need to be carried out.', '', 0, 'L', $fill);
                $pdf->Ln();
            }
           
            // QR GENERATOR VALIDASI
            $qr_validasi        = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-validasi-'.strtolower($kode_pasien).'.png';
            $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh manajemen klinik esensia. Pasien a/n. ';
            $params['data']    .= strtoupper($sql_pasien->nama_pgl).' ('.strtoupper($kode_pasien).')';
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_validasi;
            $this->ciqrcode->generate($params);
            
//            $gambar4            = base_url('file/qr/validasi-'.$sql_pasien->id.'.png');         
            $gambar4            = $qr_validasi;         
                        
            // QR GENERATOR DOKTER
            $qr_dokter          = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-dokter-'.strtolower($sql_dokter2->id).'.png';
            $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh dokter penanggung jawab ['.(!empty($sql_dokter2->nama_dpn) ? $sql_dokter2->nama_dpn.' ' : '').$sql_dokter2->nama.(!empty($sql_dokter2->nama_blk) ? ', '.$sql_dokter2->nama_blk : '').']';
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_dokter;
            $this->ciqrcode->generate($params);
            
            $gambar5            = $qr_dokter;
//            $gambar5            = base_url('file/qr/dokter-'.strtolower($sql_dokter2->id).'.png');
            
            // Gambar VALIDASI
            $getY = $pdf->GetY() + 1;
            $pdf->Image($gambar4,2,$getY,2,2);
            $pdf->Image($gambar5,12,$getY,2,2);
            
            
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(10.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, 'Semarang, '.$this->tanggalan->tgl_indo3($sql_medc_lab->tgl_masuk), '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '10');
            $pdf->Cell(4, .5, 'Validasi', '0', 0, 'C', $fill);
            $pdf->Cell(6.5, .5, '', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(7.5, .5, (!empty($ket) ? $ket : 'Dokter Pemeriksa'), '0', 0, 'L', $fill);
            $pdf->Ln(2.5);
            
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(10.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, (!empty($sql_dokter2->nama_dpn) ? $sql_dokter2->nama_dpn.' ' : '').$sql_dokter2->nama.(!empty($sql_dokter2->nama_blk) ? ', '.$sql_dokter2->nama_blk : ''), '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(10.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, $sql_dokter2->nik, '', 0, 'L', $fill);
            $pdf->Ln();
            
            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $pdf->Output($sql_pasien->nama_pgl. '.pdf', $type);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function pdf_medcheck_rad() {
        if (akses::aksesLogin() == TRUE) {
            $setting            = $this->db->get('tbl_pengaturan')->row();
//            $id                 = $this->input->get('id');
            $id_medcheck        = $this->input->get('id');
            $id_rad             = $this->input->get('id_rad');
            
            $sql_medc           = $this->db->where('id', general::dekrip($id_medcheck))->get('tbl_trans_medcheck')->row();
            $sql_medc_item      = $this->db->where('id_medcheck', general::dekrip($id_medcheck))
                                           ->where('id_rad', general::dekrip($id_rad))
//                                           ->where('status', '5')
                                           ->where('status_hsl', '1')
                                           ->get('tbl_trans_medcheck_det')->result(); 
            $sql_medc_rad       = $this->db->where('id', general::dekrip($id_rad))->get('tbl_trans_medcheck_rad')->row(); 
            $sql_pasien         = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row(); 
            $sql_pekerjaan      = $this->db->where('id', $sql_pasien->id_pekerjaan)->get('tbl_m_jenis_kerja')->row();
            $sql_dokter         = $this->db->where('id_user', $sql_medc->id_dokter)->get('tbl_m_karyawan')->row();
            $sql_dokter_rad     = $this->db->where('id_user', $sql_medc_rad->id_dokter)->get('tbl_m_karyawan')->row();
            $sql_dokter_krm     = $this->db->where('id_user', $sql_medc_rad->id_dokter_kirim)->get('tbl_m_karyawan')->row();
            $kode_pasien        = $sql_pasien->kode_dpn.$sql_pasien->kode;
            $gambar1            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-esensia-2.png';
            $gambar2            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-bw-bg2-1440px.png';
            $gambar3            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-footer.png';

            $judul  = "HASIL PEMERIKSAAN RADIOLOGI";
            $judul2 = "Radiology Result";
            
            $this->load->library('MedRadPDF');
            $pdf = new MedRadPDF('P', 'cm', array(21.5,33));
            $pdf->SetAutoPageBreak('auto', 6.5);
            $pdf->addPage();
            
            // Gambar Watermark Tengah
            $pdf->Image($gambar2,5,4,17,19);
            
            // Blok Judul
            $pdf->SetFont('Arial', 'B', '14');
            $pdf->Cell(19, .5, $judul, 0, 1, 'C');
            $pdf->Ln(0);
            $pdf->SetFont('Arial', 'Bi', '14');
            $pdf->Cell(19, .5, $judul2, 0, 1, 'C');
            $pdf->Ln();
            
            // Blok ID PASIEN
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(5.5, .5, 'Nama / Name', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, strtoupper($sql_pasien->nama_pgl).' ('.strtoupper($kode_pasien).')', '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Tgl Lahir / Date of Birth', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $this->tanggalan->tgl_indo($sql_pasien->tgl_lahir).' - '.$this->tanggalan->usia($sql_pasien->tgl_lahir).' ('.general::jns_klm($sql_pasien->jns_klm).')', '0', 0, 'L', $fill);
            $pdf->Ln();
//            $pdf->Cell(5.5, .5, 'Jenis Kelamin / Gender', '0', 0, 'L', $fill);
//            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
//            $pdf->Cell(8, .5, $this->tanggalan->usia($sql_pasien->tgl_lahir).' ('.general::jns_klm($sql_pasien->jns_klm).')', '0', 0, 'L', $fill);
//            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Alamat / Address', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, (!empty($sql_pasien->alamat) ? $sql_pasien->alamat : (!empty($sql_pasien->alamat_dom) ? $sql_pasien->alamat_dom : '-')), '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Tgl Periksa / Check Date', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $this->tanggalan->tgl_indo5($sql_medc->tgl_masuk), '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Tgl Selesai / End Date', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $this->tanggalan->tgl_indo5(date('Y-m-d H:i')), '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Dokter Pengirim / Reffered', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, (!empty($sql_dokter_krm->nama) ? (!empty($sql_dokter_krm->nama_dpn) ? $sql_dokter_krm->nama_dpn.' ' : '').$sql_dokter_krm->nama.(!empty($sql_dokter_krm->nama_blk) ? ', '.$sql_dokter_krm->nama_blk.' ' : '') : $sql_medc->nama), '0', 0, 'L', $fill);
            $pdf->Ln(1);
                        
            $fill = FALSE;
            foreach ($sql_medc_item as $rad){
                $sql_rad_det = $this->db->where('id_medcheck_det', $rad->id)->where('id_rad', $rad->id_rad)->get('tbl_trans_medcheck_rad_det')->result();
                
                $pdf->SetFont('Arial', 'B', '11');
                $pdf->Cell(19, .5, $rad->item, '', 0, 'L', $fill);
                $pdf->Ln();
                
                foreach ($sql_rad_det as $rad_hasil){
                    $pdf->SetFont('Arial', 'Bi', '7.5');
                    $pdf->Cell(5, .5, (!empty($rad_hasil->item_name) ? ' - ' : '').strtoupper($rad_hasil->item_name), '', 0, 'L', $fill);
                    $pdf->Cell(.5, .5, (!empty($rad_hasil->item_name) ? ':' : ''), '', 0, 'C', $fill);
                    $pdf->SetFont('Arial', '', '7.5');
//                    $pdf->Ln();
//                    $pdf->Cell(13.5, .5, ucfirst($rad_hasil->item_value), '', 0, 'L', $fill);
                    $pdf->MultiCell(13.5, .5, $rad_hasil->item_value, '', 'J', false);
//                    $pdf->Ln();
                }
                
                $pdf->Ln();
            }
            
            $pdf->SetFont('Arial', 'B', '11');
            $pdf->Cell(19, .5, 'KESAN', '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', '9');
            $pdf->MultiCell(19, .5, $sql_medc_rad->ket, '', 'J', false);
            $pdf->Ln();

            // QR GENERATOR VALIDASI
            $qr_validasi        = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-validasi-'.strtolower($kode_pasien).'.png';
            $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh manajemen klinik esensia. Pasien a/n. ';
            $params['data']    .= strtoupper($sql_pasien->nama_pgl).' ('.strtoupper($kode_pasien).')';
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_validasi; 
            $this->ciqrcode->generate($params);
            
            $gambar4            = $qr_validasi; 
                        
            // QR GENERATOR DOKTER
            $qr_dokter          = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-dokter-'.strtolower($sql_dokter_rad->id).'.png';
            $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh dokter penanggung jawab ['.(!empty($sql_dokter_rad->nama_dpn) ? $sql_dokter_rad->nama_dpn.' ' : '').$sql_dokter_rad->nama.(!empty($sql_dokter_rad->nama_blk) ? ', '.$sql_dokter_rad->nama_blk : '').']';
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_dokter;
            $this->ciqrcode->generate($params);
            
            $gambar5            = $qr_dokter;  
            
            // Gambar VALIDASI
            $getY = $pdf->GetY() + 1;
            $pdf->Image($gambar4,2,$getY,2,2);
            $pdf->Image($gambar5,12.5,$getY,2,2);
            
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(11.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, 'Semarang, '.$this->tanggalan->tgl_indo3($sql_medc_srt->tgl_simpan), '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '10');
            $pdf->Cell(4, .5, 'Validasi', '0', 0, 'C', $fill);
            $pdf->Cell(7.5, .5, '', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(7.5, .5, (!empty($ket) ? $ket : 'Dokter Spesialis Radiologi'), '0', 0, 'L', $fill);
            $pdf->Ln(2.5);
            
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(11, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(8, .5, (!empty($sql_dokter_rad->nama_dpn) ? $sql_dokter_rad->nama_dpn.' ' : '').$sql_dokter_rad->nama.(!empty($sql_dokter_rad->nama_blk) ? ', '.$sql_dokter_rad->nama_blk.' ' : ''), '0', 0, 'L', $fill);
            $pdf->Ln();
                    
            $pdf->SetFillColor(235, 232, 228);
            $pdf->SetTextColor(0);
            $pdf->SetFont('Arial', '', '10');
            
            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');
            
            ob_start();
            $pdf->Output($sql_pasien->nama_pgl. '.pdf', $type);
            ob_end_flush();
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function pdf_medcheck_resume() {
        if (akses::aksesLogin() == TRUE) {
            $setting            = $this->db->get('tbl_pengaturan')->row();
            $id_medcheck        = $this->input->get('id');
            $id_rsm             = $this->input->get('id_resm');
            
            $sql_medc           = $this->db->where('id', general::dekrip($id_medcheck))->get('tbl_trans_medcheck')->row();
            $sql_medc_det       = $this->db->where('id_medcheck', $sql_medc->id)->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();
            $sql_poli           = $this->db->where('id', $sql_medc->id_poli)->get('tbl_m_poli')->row(); 
            $sql_pasien         = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row(); 
            $sql_pekerjaan      = $this->db->where('id', $sql_pasien->id_pekerjaan)->get('tbl_m_jenis_kerja')->row();
            $sql_dokter         = $this->db->where('id_user', $sql_medc->id_dokter)->get('tbl_m_karyawan')->row();
            $sql_icd            = $this->db->where('id_user', $sql_medc->id_icd)->get('tbl_m_icd')->row();
            $kode_pasien        = $sql_pasien->kode_dpn.$sql_pasien->kode;
            $gambar1            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-esensia-2.png';
            $gambar2            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-bw-bg2-1440px.png';
            $gambar3            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-footer.png';

            $judul  = "RESUME MEDIS";
            $judul2 = "Medical Resume Result";
            
            $this->load->library('MedPDF');
            $pdf = new MedPDF('P', 'cm', array(21.5,33));
            $pdf->SetAutoPageBreak('auto', 8.5);
            $pdf->header = 0;
            $pdf->addPage('','',false);
            
            // Gambar Watermark Tengah
            $pdf->Image($gambar2,5,4,15,19);
            
            // Blok Judul
            $pdf->SetFont('Arial', 'B', '14');
            $pdf->Cell(19, .5, $judul, 0, 1, 'C');
            $pdf->Ln(0);
            $pdf->SetFont('Arial', 'Bi', '14');
            $pdf->Cell(19, .5, $judul2, 0, 1, 'C');
            $pdf->Ln();
            
            // Blok ID PASIEN
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(3, .5, 'ID Transaksi', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(4.5, .5, $sql_medc->no_rm, '0', 0, 'L', $fill);
            $pdf->Cell(2.5, .5, 'No. RM', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $sql_pasien->kode_dpn.$sql_pasien->kode, '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(3, .5, 'No. Invoice', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(4.5, .5, (!empty($sql_medc->no_nota) ? $sql_medc->no_nota : '-'), '0', 0, 'L', $fill);
            $pdf->Cell(2.5, .5, 'Nama Name', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $sql_pasien->nama_pgl.' ('.$sql_pasien->jns_klm.')', '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(3, .5, 'Tgl Periksa', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(4.5, .5, $this->tanggalan->tgl_indo5($sql_medc->tgl_masuk), '0', 0, 'L', $fill);
            $pdf->Cell(2.5, .5, 'NIK', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $sql_pasien->nik, '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(3, .5, 'Poli', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(4.5, .5, $sql_poli->lokasi, '0', 0, 'L', $fill);
            $pdf->Cell(2.5, .5, 'Tgl Lahir', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $this->tanggalan->tgl_indo2($sql_pasien->tgl_lahir).' / '.$this->tanggalan->usia_lkp($sql_pasien->tgl_lahir), '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(3, .5, 'Alamat', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->MultiCell(15.5, .5, (!empty($sql_pasien->alamat) ? $sql_pasien->alamat : (!empty($sql_pasien->alamat_dom) ? $sql_pasien->alamat_dom : '-')), '0', 'L');
            $pdf->Cell(3, .5, 'Dokter', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->MultiCell(15.5, .5, (!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').$sql_dokter->nama.(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk.' ' : ''), '0', 'L');
            $pdf->Ln();
            
            # DATA MEDIS PASIEN
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(3, .5, 'KELUHAN', '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->MultiCell(15.5, .5, $sql_medc->keluhan, '0', 'L');
            $pdf->Ln(0);
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(3, .5, 'TTV', '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->MultiCell(15.5, .5, $sql_medc->ttv, '0', 'L');
            $pdf->Ln(0);
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(3, .5, 'ANAMNESA', '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->MultiCell(15.5, .5, $sql_medc->anamnesa, '0', 'L');
            $pdf->Ln(0);
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(3, .5, 'DIAGNOSA', '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->MultiCell(15.5, .5, $sql_medc->diagnosa, '0', 'L');
            $pdf->Ln(0);
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(3, .5, 'DIAGNOSA ICD 10', '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->MultiCell(15.5, .5, $sql_icd->diagnosa, '0', 'L');
            $pdf->Ln(0);
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(3, .5, 'PEMERIKSAAN', '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->MultiCell(15.5, .5, $sql_medc->pemeriksaan, '0', 'L');
            $pdf->Ln(0);
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(3, .5, 'PROGRAM', '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->MultiCell(15.5, .5, $sql_medc->program, '0', 'L');
            $pdf->Ln();
            
            # DATA RESEP
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(1, .5, 'No.', 'LT', 0, 'L', $fill);
            $pdf->Cell(3, .5, 'Tgl', 'T', 0, 'L', $fill);
            $pdf->Cell(8.5, .5, 'Item', 'T', 0, 'L', $fill);
            $pdf->Cell(6.5, .5, 'Keterangan', 'TR', 0, 'L', $fill);
//            $pdf->Cell(2, .5, 'Harga', 'T', 0, 'R', $fill);
//            $pdf->Cell(2, .5, 'Subtotal', 'TR', 0, 'R', $fill);
            $pdf->Ln();
            
            $fill = FALSE;
            $no   = 1;
            foreach ($sql_medc_det as $det){
                $sql_kat = $this->db->where('id', $det->id_item_kat)->get('tbl_m_kategori')->row();
                $sql_det = $this->db->where('id_medcheck', $det->id_medcheck)->where('id_item_kat', $det->id_item_kat)->get('tbl_trans_medcheck_det')->result();
                
                $pdf->SetFont('Arial', 'B', '9');
                $pdf->Cell(19, .5, $sql_kat->keterangan . ' (' . $sql_kat->kategori . ')', 'LTR', 0, 'L', $fill);
                $pdf->Ln();
                
                $pdf->SetFont('Arial', '', '9');
                foreach ($sql_det as $medc){
                    
                    $pdf->Cell(1, .5, $no.'.', 'L', 0, 'C', $fill);
                    $pdf->Cell(3, .5, $this->tanggalan->tgl_indo5($medc->tgl_simpan), '', 0, 'L', $fill);
                    $pdf->Cell(8.5, .5, $medc->item, '', 0, 'L', $fill);
                    $pdf->Cell(6.5, .5, (!empty($medc->keterangan) ? $medc->keterangan : ''), 'R', 0, 'L', $fill);
//                    $pdf->Cell(2, .5, general::format_angka($medc->harga), '', 0, 'R', $fill);
//                    $pdf->Cell(2, .5, general::format_angka($medc->subtotal), 'R', 0, 'R', $fill);
                    $pdf->Ln();
                    
                    # Tampilkan ini jika ada resep
                    if (!empty($medc->resep)) {
                        $pdf->SetFont('Arial', 'i', '8');
                        foreach (json_decode($medc->resep) as $racikan) {
                            $pdf->Cell(4, .5, '', 'L', 0, 'C', $fill);
                            $pdf->Cell(15, .5, ' - '.$racikan->item, 'R', 0, 'L', $fill);
//                            $pdf->Cell(2, .5, general::format_angka($racikan->harga), '', 0, 'R', $fill);
//                            $pdf->Cell(2, .5, '', 'R', 0, 'R', $fill);
                            $pdf->Ln();
                        }
                    }
                    
                    # Tampilkan ini jika ada dosis
                    if (!empty($medc->dosis) OR !empty($medc->dosis_ket)) {
                        $pdf->SetFont('Arial', 'Bi', '8');
                        $pdf->Cell(4, .5, 'Dosis : ', 'L', 0, 'R', $fill);
                        $pdf->SetFont('Arial', 'i', '8');
                        $pdf->Cell(15, .5, $medc->dosis.(!empty($medc->dosis_ket) ? ' ('.$medc->dosis_ket.')' : ''), 'R', 0, 'L', $fill);
                        $pdf->Ln();
                    }
                    
                    $no++;
                }
            }
            $pdf->Cell(19, .5, '', 'T', 0, 'L', $fill);
//            
//            $pdf->Ln();
//            $pdf->SetFont('Arial', 'B', '11');
//            $pdf->Cell(19, .5, 'SARAN', '', 0, 'L', $fill);
//            $pdf->Ln();
//            $pdf->SetFont('Arial', '', '9');
//            $pdf->MultiCell(19, .5, $sql_medc_rsm->saran, '', 'J', false);
//            $pdf->Ln();            
//            $pdf->SetFont('Arial', 'B', '11');
//            $pdf->Cell(19, .5, 'KESIMPULAN', '', 0, 'L', $fill);
//            $pdf->Ln();
//            $pdf->SetFont('Arial', '', '9');
//            $pdf->MultiCell(19, .5, $sql_medc_rsm->kesimpulan, '', 'J', false);
//            $pdf->Ln();

            // QR GENERATOR VALIDASI
            $qr_validasi        = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-validasi-'.strtolower($kode_pasien).'.png';
            $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh manajemen klinik esensia. Pasien a/n. ';
            $params['data']    .= strtoupper($sql_pasien->nama_pgl).' ('.strtoupper($kode_pasien).')';
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_validasi; 
            $this->ciqrcode->generate($params);
            
            $gambar4            = $qr_validasi; 
                        
            // QR GENERATOR DOKTER
            $qr_dokter          = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-dokter-'.strtolower($sql_dokter->id).'.png';
            $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh dokter penanggung jawab ['.(!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').$sql_dokter->nama.(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk : '').']';
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_dokter;
            $this->ciqrcode->generate($params);
            
            $gambar5            = $qr_dokter;
            
            // Gambar VALIDASI
            $getY = $pdf->GetY() + 1;
            $pdf->Image($gambar4,2,$getY,2,2);
            $pdf->Image($gambar5,13.5,$getY,2,2);
            
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(10.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(8.5, .5, '', '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '10');
            $pdf->Cell(4, .5, 'Validasi', '0', 0, 'C', $fill);
            $pdf->Cell(6.5, .5, '', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(8.5, .5, 'Semarang, '.$this->tanggalan->tgl_indo3($sql_medc_rsm->tgl_masuk), '0', 0, 'C', $fill);
            $pdf->Ln(2.5);
            
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(10.5, .5, '', '0', 0, 'L', $fill);
            $pdf->Cell(8.5, .5, (!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').$sql_dokter->nama.(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk.' ' : ''), '0', 0, 'C', $fill);
            $pdf->Ln();
                    
            $pdf->SetFillColor(235, 232, 228);
            $pdf->SetTextColor(0);
            $pdf->SetFont('Arial', '', '10');
            
            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');
            
            ob_start();
            $pdf->Output($sql_pasien->nama_pgl. '.pdf', $type);
            ob_end_flush();
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
        
    public function pdf_medcheck_resume_lab() {
        if (akses::aksesLogin() == TRUE) {
            $setting            = $this->db->get('tbl_pengaturan')->row();
//            $id                 = $this->input->get('id');
            $id_medcheck        = $this->input->get('id');
            $id_rsm             = $this->input->get('id_resm');
            
            $sql_medc           = $this->db->where('id', general::dekrip($id_medcheck))->get('tbl_trans_medcheck')->row();
            $sql_medc_rsm       = $this->db->where('id', general::dekrip($id_rsm))->get('tbl_trans_medcheck_resume')->row(); 
            $sql_medc_rsm_hsl   = $this->db->where('id_resume', general::dekrip($id_rsm))->get('tbl_trans_medcheck_resume_det')->result(); 
            $sql_poli           = $this->db->where('id', $sql_medc->id_poli)->get('tbl_m_poli')->row(); 
            $sql_pasien         = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row(); 
            $sql_pekerjaan      = $this->db->where('id', $sql_pasien->id_pekerjaan)->get('tbl_m_jenis_kerja')->row();
            $sql_dokter         = $this->db->where('id_user', $sql_medc_rsm->id_dokter)->get('tbl_m_karyawan')->row();
            $kode_pasien        = $sql_pasien->kode_dpn.$sql_pasien->kode;
            $gambar1            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-esensia-2.png';
            $gambar2            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-bw-bg2-1440px.png';
            $gambar3            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-footer.png';
            $foto_file          = realpath($sql_pasien->file_name);
            $ck_foto            = (!empty($sql_pasien->file_name) ? FCPATH.'/'.$sql_pasien->file_name : FCPATH.'/assets/theme/admin-lte-3/dist/img/'.($sql_pasien->jns_klm == 'L' ? 'avatar7-men' : 'avatar7-women').'.png');
            $foto_pasien        = $ck_foto;
                    
            $judul  = "RESUME MEDICAL CHECKUP";
            $judul2 = "Resume Medical Checkup Result";
            
            $this->load->library('MedLabPDF');
            $pdf = new MedLabPDF('P', 'cm', array(21.5,33));
            $pdf->SetAutoPageBreak('auto', 6);
            $pdf->header = 0;
            $pdf->addPage('','',false);
            
            // Gambar Watermark Tengah
            $pdf->Image($gambar2,5,4,15,19);
            
            // Blok Judul
            $pdf->SetFont('Arial', 'B', '14');
            $pdf->Cell(19, .5, $judul, 0, 1, 'C');
            $pdf->Ln(0);
            $pdf->SetFont('Arial', 'Bi', '14');
            $pdf->Cell(19, .5, $judul2, 0, 1, 'C');
            $pdf->Ln();
            
            # Blok ID PASIEN
            $pdf->Image($foto_pasien,1,5.75,2.5,3);
            
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(2.5, .5, '', '0', 0, 'L', $fill);
            $pdf->Cell(2.5, .5, 'No. RM', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(7, .5, $sql_pasien->kode_dpn.$sql_pasien->kode, '', 0, 'L', $fill);
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(3, .5, 'No. Pemeriksaan', '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(3, .5, $sql_medc_rsm->no_surat, '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(2.5, .5, '', '0', 0, 'L', $fill);
            $pdf->Cell(2.5, .5, 'Nama Name', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(7, .5, $sql_pasien->nama_pgl.' ('.$sql_pasien->jns_klm.')', '', 0, 'L', $fill);
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(3, .5, 'No. Sampel', '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(3, .5, $sql_medc_rsm->no_sample, '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(2.5, .5, '', '0', 0, 'L', $fill);
            $pdf->Cell(2.5, .5, 'NIK', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(7, .5, $sql_pasien->nik, '', 0, 'L', $fill);
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(3, .5, 'Tgl Masuk', '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(3, .5, $this->tanggalan->tgl_indo5($sql_medc->tgl_masuk), '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(2.5, .5, '', '0', 0, 'L', $fill);
            $pdf->Cell(2.5, .5, 'Tanggal Lahir', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(7, .5, $this->tanggalan->tgl_indo2($sql_pasien->tgl_lahir).' ('.$this->tanggalan->usia_lkp($sql_pasien->tgl_lahir).')', '', 0, 'L', $fill);
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(3, .5, 'Poli', '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(3, .5, $sql_poli->lokasi, '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(2.5, .5, '', '0', 0, 'L', $fill);
            $pdf->Cell(2.5, .5, 'Alamat', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->MultiCell(13.5, .5, (!empty($sql_pasien->alamat) ? $sql_pasien->alamat : (!empty($sql_pasien->alamat_dom) ? $sql_pasien->alamat_dom : '-')), '0', 'L');
            $pdf->Cell(2.5, .5, '', '0', 0, 'L', $fill);
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(2.5, .5, 'Dokter Pengirim', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(13.5, .5, (!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').$sql_dokter->nama.(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk.' ' : ''), '0', 0, 'L', $fill);
            $pdf->Ln(1);
//            
//            $pdf->SetFont('Arial', '', '9');
//            $pdf->Cell(3, .5, 'No. Pemeriksaan', '0', 0, 'L', $fill);
//            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
//            $pdf->Cell(4.5, .5, $sql_medc_rsm->no_surat, '0', 0, 'L', $fill);
//            $pdf->Cell(2.5, .5, 'No. RM', '0', 0, 'L', $fill);
//            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
//            $pdf->Cell(8, .5, $sql_pasien->kode_dpn.$sql_pasien->kode, '0', 0, 'L', $fill);
//            $pdf->Ln();
//            $pdf->Cell(3, .5, 'No. Sampel', '0', 0, 'L', $fill);
//            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
//            $pdf->Cell(4.5, .5, $sql_medc_rsm->no_sample, '0', 0, 'L', $fill);
//            $pdf->Cell(2.5, .5, 'Nama Name', '0', 0, 'L', $fill);
//            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
//            $pdf->Cell(8, .5, $sql_pasien->nama_pgl.' ('.$sql_pasien->jns_klm.')', '0', 0, 'L', $fill);
//            $pdf->Ln();
//            $pdf->Cell(3, .5, 'Tgl Masuk', '0', 0, 'L', $fill);
//            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
//            $pdf->Cell(4.5, .5, $this->tanggalan->tgl_indo5($sql_medc->tgl_masuk), '0', 0, 'L', $fill);
//            $pdf->Cell(2.5, .5, 'NIK', '0', 0, 'L', $fill);
//            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
//            $pdf->Cell(8, .5, $sql_pasien->nik, '0', 0, 'L', $fill);
//            $pdf->Ln();
//            $pdf->Cell(3, .5, 'Poli', '0', 0, 'L', $fill);
//            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
//            $pdf->Cell(4.5, .5, $sql_poli->lokasi, '0', 0, 'L', $fill);
//            $pdf->Cell(2.5, .5, 'Tgl Lahir', '0', 0, 'L', $fill);
//            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
//            $pdf->Cell(8, .5, $this->tanggalan->tgl_indo2($sql_pasien->tgl_lahir).' / '.$this->tanggalan->usia_lkp($sql_pasien->tgl_lahir), '0', 0, 'L', $fill);
//            $pdf->Ln();
//            $pdf->Cell(3, .5, 'Alamat', '0', 0, 'L', $fill);
//            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
//            $pdf->MultiCell(15.5, .5, (!empty($sql_pasien->alamat) ? $sql_pasien->alamat : (!empty($sql_pasien->alamat_dom) ? $sql_pasien->alamat_dom : '-')), '0', 'L');
//            $pdf->Cell(3, .5, 'Dokter Pengirim', '0', 0, 'L', $fill);
//            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
//            $pdf->MultiCell(15.5, .5, (!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').$sql_dokter->nama.(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk.' ' : ''), '0', 'L');
//            $pdf->Ln();
                        
            $fill = FALSE;
            foreach ($sql_medc_rsm_hsl as $rsm){
                $pdf->SetFont('Arial', 'B', '10');
                $pdf->Cell(5.5, .5, $rsm->param, '', 0, 'L', $fill);
                $pdf->Cell(.5, .5, ':', '', 0, 'C', $fill);
                $pdf->SetFont('Arial', '', '10');
                $pdf->MultiCell(13, .5, $rsm->param_nilai, '0', 'L');
//                $pdf->Cell(13, .5, $rsm->param_nilai, '', 0, 'L', $fill);
//                $pdf->Ln();
            }
            
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '11');
            $pdf->Cell(19, .5, 'SARAN', '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', '9');
            $pdf->MultiCell(19, .5, $sql_medc_rsm->saran, '', 'J', false);
            $pdf->Ln();            
            $pdf->SetFont('Arial', 'B', '11');
            $pdf->Cell(19, .5, 'KESIMPULAN', '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', '9');
            $pdf->MultiCell(19, .5, $sql_medc_rsm->kesimpulan, '', 'J', false);
            $pdf->Ln();

            // QR GENERATOR VALIDASI
            $qr_validasi        = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-validasi-'.strtolower($kode_pasien).'.png';
            $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh manajemen klinik esensia. Pasien a/n. ';
            $params['data']    .= strtoupper($sql_pasien->nama_pgl).' ('.strtoupper($kode_pasien).')';
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_validasi; 
            $this->ciqrcode->generate($params);
            
            $gambar4            = $qr_validasi; 
                        
            // QR GENERATOR DOKTER
            $qr_dokter          = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-dokter-'.strtolower($sql_dokter->id).'.png';
            $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh dokter penanggung jawab ['.(!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').$sql_dokter->nama.(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk : '').']';
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_dokter;
            $this->ciqrcode->generate($params);
            
            $gambar5            = $qr_dokter;
            
            // Gambar VALIDASI
            $getY = $pdf->GetY() + 1;
            $pdf->Image($gambar4,2,$getY,2,2);
            $pdf->Image($gambar5,13.80,$getY,2,2);
            
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(10.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(8.5, .5, '', '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '10');
            $pdf->Cell(4, .5, 'Validasi', '0', 0, 'C', $fill);
            $pdf->Cell(6.5, .5, '', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(8.5, .5, 'Semarang, '.$this->tanggalan->tgl_indo3($sql_medc_rsm->tgl_masuk), '0', 0, 'C', $fill);
            $pdf->Ln(2.5);
            
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(10.5, .5, '', '0', 0, 'L', $fill);
            $pdf->Cell(8.5, .5, (!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').$sql_dokter->nama.(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk.' ' : ''), '0', 0, 'C', $fill);
            $pdf->Ln();
                    
            $pdf->SetFillColor(235, 232, 228);
            $pdf->SetTextColor(0);
            $pdf->SetFont('Arial', '', '10');
            
            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');
            
            ob_start();
            $pdf->Output($sql_pasien->nama_pgl. '.pdf', $type);
            ob_end_flush();
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
        
    public function pdf_medcheck_resume_rnp() {
        if (akses::aksesLogin() == TRUE) {
            $setting            = $this->db->get('tbl_pengaturan')->row();
//            $id                 = $this->input->get('id');
            $id_medcheck        = $this->input->get('id');
            $id_rsm             = $this->input->get('id_resm');
            
            $sql_medc           = $this->db->where('id', general::dekrip($id_medcheck))->get('tbl_trans_medcheck')->row();
            $sql_medc_rsm       = $this->db->where('id', general::dekrip($id_rsm))->get('tbl_trans_medcheck_resume')->row(); 
            $sql_medc_rsm_hsl   = $this->db->where('id_resume', general::dekrip($id_rsm))->where('status_rnp', '1')->get('tbl_trans_medcheck_resume_det')->result(); 
            $sql_medc_rsm_trp   = $this->db->where('id_resume', general::dekrip($id_rsm))->where('status_trp', '1')->get('tbl_trans_medcheck_resume_det')->result(); 
            $sql_medc_res       = $this->db->where('id_medcheck', $sql_medc->id)->where('status_plg', '1')->get('tbl_trans_medcheck_resep')->row(); 
            $sql_medc_res_det   = $this->db->where('id_resep', $sql_medc_res->id)->get('tbl_trans_medcheck_resep_det')->result(); 
            $sql_poli           = $this->db->where('id', $sql_medc->id_poli)->get('tbl_m_poli')->row(); 
            $sql_pasien         = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row(); 
            $sql_pekerjaan      = $this->db->where('id', $sql_pasien->id_pekerjaan)->get('tbl_m_jenis_kerja')->row();
            $sql_dokter         = $this->db->where('id_user', $sql_medc_rsm->id_dokter)->get('tbl_m_karyawan')->row();
            $kode_pasien        = $sql_pasien->kode_dpn.$sql_pasien->kode;
            $gambar1            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-esensia-2.png';
            $gambar2            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-bw-bg2-1440px.png';
            $gambar3            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-footer.png';

            $judul  = "RESUME MEDIS RAWAT INAP";
            $judul2 = "Inpatient Medical Resume";
            
            $this->load->library('MedPDF');
            $pdf = new MedPDF('P', 'cm', array(21.5,33));
            $pdf->SetAutoPageBreak('auto', 6.5);
            $pdf->header = 0;
            $pdf->addPage('','',false);
            
            // Gambar Watermark Tengah
            $pdf->Image($gambar2,5,4,15,19);
            
            // Blok Judul
            $pdf->SetFont('Arial', 'B', '11');
            $pdf->Cell(19, .5, $judul, 0, 1, 'C');
            $pdf->Ln(0);
            $pdf->SetFont('Arial', 'Bi', '10');
            $pdf->Cell(19, .5, $judul2, 0, 1, 'C');
            $pdf->Ln();
            
            // Blok ID PASIEN
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(3, .5, 'No. Pemeriksaan', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(4.5, .5, $sql_medc_rsm->no_surat, '0', 0, 'L', $fill);
            $pdf->Cell(2.5, .5, 'No. RM', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $sql_pasien->kode_dpn.$sql_pasien->kode, '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(3, .5, 'Tgl Masuk', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(4.5, .5, $this->tanggalan->tgl_indo5($sql_medc->tgl_masuk), '0', 0, 'L', $fill);
            $pdf->Cell(2.5, .5, 'Nama Name', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $sql_pasien->nama_pgl.' ('.$sql_pasien->jns_klm.')', '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(3, .5, 'NIK', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(4.5, .5, $sql_pasien->nik, '0', 0, 'L', $fill);
            $pdf->Cell(2.5, .5, 'Tgl Lahir', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $this->tanggalan->tgl_indo2($sql_pasien->tgl_lahir).' / '.$this->tanggalan->usia_lkp($sql_pasien->tgl_lahir), '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(3, .5, 'Poli', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(4.5, .5, $sql_poli->lokasi, '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(3, .5, 'Alamat', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->MultiCell(15.5, .5, (!empty($sql_pasien->alamat) ? $sql_pasien->alamat : (!empty($sql_pasien->alamat_dom) ? $sql_pasien->alamat_dom : '-')), '0', 'L');
            $pdf->Ln();
            
            $fill = FALSE;
            foreach ($sql_medc_rsm_hsl as $rsm){
                $pdf->SetFont('Arial', 'B', '8');
//                $pdf->MultiCell(5.5, .5, $rsm->param, '0', 'L');
                $pdf->Cell(4, .5, $rsm->param, '', 0, 'L', $fill);
                $pdf->Cell(.5, .5, ':', '', 0, 'C', $fill);
                $pdf->SetFont('Arial', '', '8');
                $pdf->MultiCell(14.5, .5, $rsm->param_nilai, '0', 'J');
            }
            
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '8');
            $pdf->Cell(19, .5, 'TERAPI PULANG', 'TB', 0, 'C', $fill);
            $pdf->Ln(); 
            
            $pdf->Cell(1, .5, 'No.', 'B', 0, 'C', $fill);
            $pdf->Cell(6, .5, 'Nama Obat', 'B', 0, 'L', $fill);
            $pdf->Cell(12, .5, 'Catatan', 'B', 0, 'L', $fill);
            $pdf->Ln();    
            
            $fill = FALSE;
            $no_trp = 1;
            foreach ($sql_medc_res_det as $trp){
//                $pdf->SetFont('Arial', '', 8);
////                $pdf->Cell(1, 0.5, $no_trp, 'LR', 0, 'C', $fill);
//
//                $x = $pdf->GetX() + 1; // Mengambil posisi X saat ini dan menambahkan offset
//                $y = $pdf->GetY(); // Mengambil posisi Y saat ini
//
//                $pdf->SetXY($x, $y); // Mengatur posisi X dan Y baru
//
//                $pdf->MultiCell(1, 0.5, $no_trp, 'LR', 'C', $fill);
//                $x += 0;
//                $pdf->MultiCell(6, 0.5, $trp->param, 'R', 'L', $fill);
//                $x += 5; // Menambahkan offset X untuk selanjutnya
//
//                $pdf->SetXY($x, $y); // Mengatur posisi X dan Y baru
//
//                $pdf->MultiCell(12, 0.5, $trp->param_nilai, 'R', 'L', $fill);
//
//                $pdf->SetFont('Arial', '', '8');
//                $pdf->Cell(1, .5, $no_trp, '', 0, 'C', $fill);
//                $pdf->Cell(6, .5, ucwords($trp->param), '', 0, 'L', $fill);
////                $pdf->SetFont('Arial', '', '8');
////                $pdf->Cell(12, .5, $trp->param_nilai, 'R', 0, 'L', $fill);
//                $pdf->MultiCell(12, .5, str_replace('#', ' ', $trp->param_nilai), '', 'L', $fill,$pdf->GetX(),15);
////                $pdf->Ln();


                $pdf->SetFont('Arial', '', '8');
                $pdf->Cell(1, .5, $no_trp, '', 0, 'C', $fill);
                $pdf->Cell(6, .5, ucwords($trp->item), '', 0, 'L', $fill);
                $pdf->MultiCell(12, .5, $trp->dosis.' '.(!empty($trp->dosis_ket) ? '('.$trp->dosis_ket.')' : '').(!empty($trp->keterangan) ? ' / '.$trp->keterangan : ''), '', 'L', $fill,$pdf->GetX(),15);
                
                $no_trp++;
            }
            $pdf->Cell(19, .5, '', 'T', 0, 'L', $fill);

            // QR GENERATOR VALIDASI
            $qr_validasi        = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-validasi-'.strtolower($kode_pasien).'.png';
            $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh manajemen klinik esensia. Pasien a/n. ';
            $params['data']    .= strtoupper($sql_pasien->nama_pgl).' ('.strtoupper($kode_pasien).')';
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_validasi; 
            $this->ciqrcode->generate($params);
            
            $gambar4            = $qr_validasi; 
                        
            // QR GENERATOR DOKTER
            $qr_dokter          = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-dokter-'.strtolower($sql_dokter->id).'.png';
            $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh dokter penanggung jawab ['.(!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').$sql_dokter->nama.(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk : '').']';
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_dokter;
            $this->ciqrcode->generate($params);
            
            $gambar5            = $qr_dokter;
            
            // Gambar VALIDASI
            $getY = $pdf->GetY() + 1;
            $pdf->Image($gambar4,2,$getY,2,2);
            $pdf->Image($gambar5,13.80,$getY,2,2);
            
            $pdf->SetFont('Arial', '', '8');
            $pdf->Cell(10.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(8.5, .5, '', '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '8');
            $pdf->Cell(4, .5, 'Petugas', '0', 0, 'C', $fill);
            $pdf->Cell(6.5, .5, '', '0', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '8');
            $pdf->Cell(8.5, .5, 'Semarang, '.$this->tanggalan->tgl_indo3($sql_medc_rsm->tgl_masuk), '0', 0, 'C', $fill);
            $pdf->Ln(2.5);
            
            $pdf->SetFont('Arial', '', '8');
            $pdf->Cell(10.5, .5, $this->ion_auth->user($sql_medc_rsm->id_user)->row()->first_name, '0', 0, 'L', $fill);
            $pdf->Cell(8.5, .5, (!empty($sql_dokter->nama_dpn) ? $sql_dokter->nama_dpn.' ' : '').$sql_dokter->nama.(!empty($sql_dokter->nama_blk) ? ', '.$sql_dokter->nama_blk.' ' : ''), '0', 0, 'C', $fill);
            $pdf->Ln();
                    
            $pdf->SetFillColor(235, 232, 228);
            $pdf->SetTextColor(0);
            $pdf->SetFont('Arial', '', '8');
            
            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');
            
            ob_start();
            $pdf->Output($sql_pasien->nama_pgl. '.pdf', $type);
            ob_end_flush();
//            
//            $sql_cek_file = $this->db->where('id_resume', $sql_medc_rsm->id)->get('tbl_trans_medcheck_file');
//            
//            if($sql_cek_file->num_rows() == 0){
//                $this->load->helper('download');
//                
//               $folder      = realpath('./file/pasien/'.$kode_pasien);
//               $filename    = $folder.'/kontol.pdf';
//               $fdata       = (base_url('medcheck/surat/cetak_pdf_rsm_rnp.php?id='.$id_medcheck.'&id_resm='.$id_rsm));
//               $fbase64     = 'data:application/pdf;base64,'.base64_encode(file_get_contents($pdf->Output($sql_pasien->nama_pgl. '.pdf', $type)));
//                
//                $data_file = array(
//                   'id_medcheck'   => $sql_medc->id,
//                   'id_pasien'     => $sql_medc->id_pasien,
//                   'id_user'       => $this->ion_auth->user()->row()->id,
//                   'tgl_simpan'    => date('Y-m-d H:i:s'),
//                   'tgl_masuk'     => date('Y-m-d H:i'),
//                   'judul'         => 'ppp',
//                   'keterangan'    => 'hhh',
////                   'file_name_ori' => $f['client_name'],
//                   'file_name'     => $filename,
//                   'file_ext'      => $f['file_ext'],
//                   'file_type'     => $f['file_type'],
//                   'file_base64'   => '-',
//                   'status'        => '1',
//               );
//                
//                crud::simpan('tbl_trans_medcheck_file', $data_file);
//                
//                force_download($filename, $fdata);
//            }
//            
//                        $data_file = array(
//                            'id_medcheck'   => $sql_medc->id,
//                            'id_pasien'     => $sql_medc->id_pasien,
//                            'id_user'       => $this->ion_auth->user()->row()->id,
//                            'tgl_simpan'    => date('Y-m-d H:i:s'),
//                            'tgl_masuk'     => date('Y-m-d H:i'),
//                            'judul'         => $judul,
//                            'keterangan'    => $ket,
//                            'file_name_ori' => $f['client_name'],
//                            'file_name'     => $f['file_name'],
//                            'file_ext'      => $f['file_ext'],
//                            'file_type'     => $f['file_type'],
////                            'file_base64'   => $file64,
//                            'status'        => '1',
//                        );
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function pdf_medcheck_nota_rajal() {
        if (akses::aksesLogin() == TRUE) {
            $setting            = $this->db->get('tbl_pengaturan')->row();
//            $id                 = $this->input->get('id');
            $id_medcheck        = $this->input->get('id');
            
            $sql_medc           = $this->db->where('id', general::dekrip($id_medcheck))->get('tbl_trans_medcheck')->row();
            $sql_medc_plat      = $this->db->where('id_medcheck', $sql_medc->id)->get('tbl_trans_medcheck_plat')->result();
            $sql_medc_det       = $this->db->where('id_medcheck', $sql_medc->id)->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();
            $sql_medc_det_sum   = $this->db->select('SUM(diskon) AS diskon, SUM(potongan) AS potongan, SUM(subtotal) AS subtotal')->where('id_medcheck', $sql_medc->id)->get('tbl_trans_medcheck_det')->row();
            $sql_pasien         = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row(); 
            $sql_pekerjaan      = $this->db->where('id', $sql_pasien->id_pekerjaan)->get('tbl_m_jenis_kerja')->row();
            $sql_dokter         = $this->db->where('id_user', $sql_medc->id_dokter)->get('tbl_m_karyawan')->row();
            $kode_pasien        = $sql_pasien->kode_dpn.$sql_pasien->kode;
            $gambar1            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-esensia-2.png';
            $gambar2            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-bw-bg2-1440px.png';
            $gambar3            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-footer.png';
            $kasir              = (!empty($sql_medc->id_kasir) ? $this->ion_auth->user($sql_medc->id_kasir)->row()->first_name : $this->ion_auth->user()->row()->first_name);

            $judul = "INVOICE";
            
            $this->load->library('MedPDF');
            $pdf = new MedPDF('P', 'cm', array(21.5,33));
            $pdf->SetAutoPageBreak('auto', 6.5);
            $pdf->addPage();
            
            // Gambar Watermark Tengah
            $pdf->Image($gambar2,5,4,17,19);
            
            // Blok Judul
            $pdf->SetFont('Arial', 'B', '14');
            $pdf->Cell(19, .5, $judul, 0, 1, 'C');
            $pdf->Ln();
            
            // Blok ID PASIEN
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(3.5, .5, 'Nama / Name', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, strtoupper($sql_pasien->nama_pgl).' ('.$sql_pasien->kode_dpn . $sql_pasien->kode.')', '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(3.5, .5, 'Alamat / Address', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, (!empty($sql_pasien->alamat) ? $sql_pasien->alamat : (!empty($sql_pasien->alamat_dom) ? $sql_pasien->alamat_dom : '-')), '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(3.5, .5, 'Tanggal / Date', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $this->tanggalan->tgl_indo5($sql_medc->tgl_masuk).' - '.$this->tanggalan->tgl_indo5(date('Y-m-d H:i')), '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(3.5, .5, 'Kasir / Cashier', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $this->ion_auth->user()->row()->first_name, '0', 0, 'L', $fill);
            $pdf->Ln(1);
                        
            $fill = FALSE;
            $pdf->SetTextColor(5,148,19);
            $pdf->SetFont('Arial', 'B', '11');
            $pdf->Cell(7, 1, 'DESKRIPSI', 'TB', 0, 'L', $fill);
            $pdf->Cell(1, 1, 'JML', 'TB', 0, 'C', $fill);
            $pdf->Cell(3, 1, 'HARGA', 'TB', 0, 'R', $fill);
            $pdf->Cell(2, 1, 'DISK (%)', 'TB', 0, 'R', $fill);
            $pdf->Cell(3, 1, 'POTONGAN', 'TB', 0, 'R', $fill);
            $pdf->Cell(3, 1, 'SUBTOTAL', 'TB', 0, 'R', $fill);
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln();
            
            $fill = FALSE;
            $no = 1; $gtotal = 0;
            foreach ($sql_medc_det as $det){
                $sql_medc_itm = $this->db->where('id_medcheck', $det->id_medcheck)->where('id_item_kat', $det->id_item_kat)->get('tbl_trans_medcheck_det')->result();
                $sql_medc_kat = $this->db->where('id', $det->id_item_kat)->get('tbl_m_kategori')->row();
                
                $pdf->SetFont('Arial', 'Bi', '8');
                $pdf->Cell(19, .5, strtoupper($sql_medc_kat->keterangan), '', 0, 'L', $fill);
                $pdf->Ln();
                
                // Detail Pemeriksaan dan hasilnya
                $sub = 0;
                foreach ($sql_medc_itm as $medc){                  
//                    if ($medc->jml >= 0) {
//                        $subtot  = $medc->harga * $medc->jml;
                        $sub     = $sub + $medc->subtotal;
                        
                        $pdf->SetFont('Arial', '', '7.5');
                        $pdf->Cell(7, .5, $medc->item, '', 0, '', $fill);
                        $pdf->Cell(1, .5, (float) $medc->jml, '', 0, 'C', $fill);
                        $pdf->Cell(3, .5, general::format_angka($medc->harga), '', 0, 'R', $fill);
                        $pdf->Cell(2, .5, ($medc->disk1 != 0 ? (float)$medc->disk1 : '').($medc->disk2 != 0 ? ' + '.(float)$medc->disk2 : '').($medc->disk3 != 0 ? ' + '.(float)$medc->disk3 : ''), '', 0, 'C', $fill);
                        $pdf->Cell(3, .5, general::format_angka($medc->potongan), '', 0, 'R', $fill);
                        $pdf->Cell(3, .5, general::format_angka($medc->subtotal), '', 0, 'R', $fill);
                        $pdf->Ln();
                        
                        foreach (json_decode($medc->resep) as $racikan) {
                            $pdf->SetFont('Arial', 'i', '9');
                            $pdf->Cell(7, .5, '- R/ '.strtolower($racikan->item), '', 0, '', $fill);
                            $pdf->Cell(1, .5, (float) $racikan->jml, '', 0, 'C', $fill);
                            $pdf->Cell(3, .5, general::format_angka($racikan->harga), '', 0, 'R', $fill);
                            $pdf->Cell(3, .5, general::format_angka($racikan->subtotal), '', 0, 'R', $fill);
                            $pdf->Ln();                            
                        }
                        
                        $no++;
//                    }
                }
                
                // Kolom Subtotal
                $pdf->SetFont('Arial', 'B', '8');
                $pdf->Cell(16, .5, 'SUBTOTAL', 'B', 0, 'R', $fill);
                $pdf->Cell(3, .5, general::format_angka($sub), 'B', 0, 'R', $fill);
                $pdf->Ln();
                
                $gtotal = $gtotal + $sub;
            }
            
            $sql_platform   = $this->db->where('id', $sql_medc->metode)->get('tbl_m_platform')->row();
            $jml_total      = $sql_medc_sum->subtotal + $sql_medc_sum->diskon + $sql_medc_sum->potongan;
            $jml_diskon     = $jml_total - ($jml_total - $sql_medc->jml_diskon);
            $diskon         = ($jml_diskon / $jml_total) * 100; 
            
            // Kolom Total
            $pdf->SetTextColor(5,148,19);
            $pdf->SetFont('Arial', 'B', '8');
            $pdf->Cell(16, .5, 'TOTAL', '', 0, 'R', $fill);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(3, .5, general::format_angka($sql_medc->jml_total), '', 0, 'R', $fill);
            $pdf->Ln();
            $pdf->SetTextColor(5,148,19);
            $pdf->Cell(16, .5, 'DISKON '.(!empty($diskon) ? $diskon.'%' : ''), '', 0, 'R', $fill);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(3, .5, '('.general::format_angka($jml_diskon).')', '', 0, 'R', $fill);
            $pdf->Ln();
            $pdf->Cell(13, .5, '', '', 0, 'R', $fill);
            $pdf->SetTextColor(5,148,19);
            $pdf->Cell(3, .5, 'GRAND TOTAL', 'T', 0, 'R', $fill);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(3, .5, general::format_angka($sql_medc_det_sum->subtotal), 'T', 0, 'R', $fill);
            $pdf->Ln();
            
            $jml_tot_byr = 0;
            foreach ($sql_medc_plat as $plat){
                $sql_plat    = $this->db->where('id', $plat->id_platform)->get('tbl_m_platform')->row();
                $jml_tot_byr = $jml_tot_byr + $plat->nominal;

                $pdf->SetFont('Arial', 'i', '8');
                $pdf->Cell(16, .5, '('.$this->tanggalan->tgl_indo5($plat->tgl_simpan).') '.$sql_plat->platform, '', 0, 'R', $fill);
                $pdf->SetFont('Arial', 'B', '8');
                $pdf->Cell(3, .5, general::format_angka($plat->nominal), '', 0, 'R', $fill);
                $pdf->Ln();
            }
            
            $jml_kembali = $jml_tot_byr - $sql_medc_det_sum->subtotal;
            
            $pdf->Cell(13, .5, '', '', 0, 'R', $fill);
            $pdf->SetTextColor(5,148,19);
            $pdf->Cell(3, .5, 'KEMBALIAN', '', 0, 'R', $fill);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(3, .5, ($jml_kembali >= 0 ? general::format_angka($jml_kembali) : 0), '', 0, 'R', $fill);
            
            $pdf->Ln(1);
            
            // QR GENERATOR VALIDASI
            $qr_validasi        = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-validasi-'.strtolower($kode_pasien).'.png';
            $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh manajemen klinik esensia. Pasien a/n. ';
            $params['data']    .= strtoupper($sql_pasien->nama_pgl).' ('.strtoupper($kode_pasien).')';
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_validasi; 
            $this->ciqrcode->generate($params);
            
            $gambar4            = $qr_validasi;
                        
            // QR GENERATOR PETUGAS KASIR
            $qr_kasir           = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-kasir-'.strtolower(md5($kasir)).'.png';
            $params['data']     = 'Telah diverifikasi oleh petugas kasir '.$kasir.'. Pada tanggal '.$this->tanggalan->tgl_indo5($sql_medc->tgl_bayar);
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_kasir;
            $this->ciqrcode->generate($params);
            
            $gambar5            = $qr_kasir;
            
            // Gambar VALIDASI
            $getY = $pdf->GetY() + 1;
            $pdf->Image($gambar4,1,$getY,2,2);
            $pdf->Image($gambar5,12.5,$getY,2,2);

            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(11.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, 'Semarang, '.$this->tanggalan->tgl_indo3($sql_medc->tgl_bayar), '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(11.5, .5, 'Validasi', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, 'Petugas Kasir', '', 0, 'L', $fill);
            $pdf->Ln(2.5);
            
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(11.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, '('.$kasir.')', '', 0, 'L', $fill);
            $pdf->Ln();

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $pdf->Output($sql_pasien->nama_pgl.'_INV'. '.pdf', $type);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function pdf_medcheck_nota_ranap() {
        if (akses::aksesLogin() == TRUE) {
            $setting            = $this->db->get('tbl_pengaturan')->row();
//            $id                 = $this->input->get('id');
            $id_medcheck        = $this->input->get('id');
            
            $sql_medc           = $this->db->where('id', general::dekrip($id_medcheck))->get('tbl_trans_medcheck')->row();
            $sql_medc_det       = $this->db->where('id_medcheck', $sql_medc->id)->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();
            $sql_pasien         = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row(); 
            $sql_pekerjaan      = $this->db->where('id', $sql_pasien->id_pekerjaan)->get('tbl_m_jenis_kerja')->row();
            $sql_dokter         = $this->db->where('id_user', $sql_medc->id_dokter)->get('tbl_m_karyawan')->row();
            $kode_pasien        = $sql_pasien->kode_dpn.$sql_pasien->kode;
            $gambar1            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-esensia-2.png';
            $gambar2            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-bw-bg2-1440px.png';
            $gambar3            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-footer.png';
            $kasir              = (!empty($sql_medc->id_kasir) ? $this->ion_auth->user($sql_medc->id_kasir)->row()->first_name : $this->ion_auth->user()->row()->first_name);

            $judul = "BILLING RAWAT INAP";
            
            $this->load->library('MedPDF');
            $pdf = new MedPDF('P', 'cm', array(21.5,33));
            $pdf->SetAutoPageBreak('auto', 6.5);
            $pdf->addPage();
            
            // Gambar Watermark Tengah
            $pdf->Image($gambar2,5,4,17,19);
            
            // Blok Judul
            $pdf->SetFont('Arial', 'B', '14');
            $pdf->Cell(19, .5, $judul, 0, 1, 'C');
            $pdf->Ln();
            
            // Blok ID PASIEN
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(5.5, .5, 'NIK / ID Card', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $sql_pasien->nik, '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Nama / Name', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, strtoupper($sql_pasien->nama_pgl).' ('.$sql_pasien->kode_dpn . $sql_pasien->kode.')', '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Tgl Lahir / Date of Birth', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $this->tanggalan->tgl_indo($sql_pasien->tgl_lahir), '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Jenis Kelamin / Gender', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $this->tanggalan->usia_lkp($sql_pasien->tgl_lahir).' ('.general::jns_klm($sql_pasien->jns_klm).')', '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Alamat / Address', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, (!empty($sql_pasien->alamat) ? $sql_pasien->alamat : (!empty($sql_pasien->alamat_dom) ? $sql_pasien->alamat_dom : '-')), '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'No. Telp / Phone Number', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $sql_pasien->no_hp, '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Tanggal / Date', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $this->tanggalan->tgl_indo5($sql_medc->tgl_masuk).' - '.$this->tanggalan->tgl_indo5(date('Y-m-d H:i')), '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Kasir / Cashier', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $this->ion_auth->user()->row()->first_name, '0', 0, 'L', $fill);
            $pdf->Ln(1);
                        
            $fill = FALSE;
            $pdf->SetFont('Arial', 'B', '11');
            $pdf->Cell(1, .5, '#', 'TB', 0, 'C', $fill);
            $pdf->Cell(4, .5, 'TGL', 'TB', 0, 'L', $fill);
            $pdf->Cell(7, .5, 'ITEM', 'TB', 0, 'L', $fill);
            $pdf->Cell(1, .5, 'JML', 'TB', 0, 'C', $fill);
            $pdf->Cell(3, .5, 'HARGA', 'TB', 0, 'R', $fill);
            $pdf->Cell(3, .5, 'SUBTOTAL', 'TB', 0, 'R', $fill);
            $pdf->Ln();
            
            $fill = FALSE;
            $no = 1; $gtotal = 0;
            foreach ($sql_medc_det as $det){
                $sql_medc_itm = $this->db
                                     ->where('id_medcheck', $det->id_medcheck)
                                     ->where('id_item_kat', $det->id_item_kat)
                                     ->where('jml >=', '0')
                                     ->order_by('tgl_simpan', 'asc')
                                     ->get('tbl_trans_medcheck_det')->result();
                $sql_medc_kat = $this->db->where('id', $det->id_item_kat)->get('tbl_m_kategori')->row();
                
                $pdf->SetFont('Arial', 'Bi', '10');
                $pdf->Cell(19, .5, ' '.strtoupper($sql_medc_kat->keterangan), '', 0, 'L', $fill);
                $pdf->Ln();
                
                // Detail Pemeriksaan dan hasilnya
                $sub = 0;
                foreach ($sql_medc_itm as $medc){                  
                    if ($medc->jml > 0) {
                        $sub     = $sub + $medc->subtotal;
                        $hrg_pcs = $medc->subtotal / $medc->jml;
               
                        $pdf->SetFont('Arial', '', '10');
                        $pdf->Cell(1, .5, $no . '.', '', 0, 'C', $fill);
                        $pdf->Cell(4, .5, $this->tanggalan->tgl_indo5($medc->tgl_simpan), '', 0, 'L', $fill);
                        $pdf->Cell(7, .5, $medc->item, '', 0, '', $fill);
                        $pdf->Cell(1, .5, (float) $medc->jml, '', 0, 'C', $fill);
                        $pdf->Cell(3, .5, general::format_angka($medc->harga), '', 0, 'R', $fill);
                        $pdf->Cell(3, .5, general::format_angka($medc->subtotal), '', 0, 'R', $fill);
                        $pdf->Ln();
                        
                        foreach (json_decode($medc->resep) as $racikan) {
                            $pdf->SetFont('Arial', 'i', '9');
                            $pdf->Cell(1, .5, '', '', 0, 'C', $fill);
                            $pdf->Cell(4, .5, '', '', 0, 'L', $fill);
                            $pdf->Cell(7, .5, '- R/ '.strtolower($racikan->item), '', 0, '', $fill);
                            $pdf->Cell(1, .5, (float) $racikan->jml, '', 0, 'C', $fill);
                            $pdf->Cell(3, .5, general::format_angka($racikan->harga), '', 0, 'R', $fill);
                            $pdf->Cell(3, .5, general::format_angka($racikan->subtotal), '', 0, 'R', $fill);
                            $pdf->Ln();                            
                        }
                        
                        $no++;
                    }
                }
                
                // Kolom Subtotal
                $pdf->SetFont('Arial', 'B', '10');
                $pdf->Cell(16, .5, 'SUBTOTAL', 'B', 0, 'R', $fill);
                $pdf->Cell(3, .5, general::format_angka($sub), 'B', 0, 'R', $fill);
                $pdf->Ln();
                
                $gtotal = $gtotal + $sub;
            }
                
            // Kolom Grandtotal
            $pdf->SetFont('Arial', 'B', '10');
            $pdf->Cell(16, .5, 'GRAND TOTAL', '', 0, 'R', $fill);
            $pdf->Cell(3, .5, general::format_angka($gtotal), '', 0, 'R', $fill);
            $pdf->Ln(1);
            
            // QR GENERATOR VALIDASI
            $qr_validasi        = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-validasi-'.strtolower($kode_pasien).'.png';
            $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh manajemen klinik esensia. Pasien a/n. ';
            $params['data']    .= strtoupper($sql_pasien->nama_pgl).' ('.strtoupper($kode_pasien).')';
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_validasi; 
            $this->ciqrcode->generate($params);
            
            $gambar4            = $qr_validasi;
                        
            // QR GENERATOR PETUGAS KASIR
            $qr_kasir           = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-kasir-'.strtolower(md5($kasir)).'.png';
            $params['data']     = 'Telah diverifikasi oleh petugas kasir '.$kasir.'. Pada tanggal '.$this->tanggalan->tgl_indo5($sql_medc->tgl_bayar);
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_kasir;
            $this->ciqrcode->generate($params);
            
            $gambar5            = $qr_kasir;
            
            // Gambar VALIDASI
            $getY = $pdf->GetY() + 1;
            $pdf->Image($gambar4,1,$getY,2,2);
            $pdf->Image($gambar5,12.5,$getY,2,2);

            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(11.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, 'Semarang, '.$this->tanggalan->tgl_indo3($sql_medc->tgl_masuk), '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(11.5, .5, 'Validasi', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, 'Petugas Kasir', '', 0, 'L', $fill);
            $pdf->Ln(2.5);
            
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(11.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, '('.$kasir.')', '', 0, 'L', $fill);
            $pdf->Ln();

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $pdf->Output($sql_pasien->nama_pgl.'_BILLING'. '.pdf', $type);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    # INVOICE RANAP
    public function pdf_medcheck_nota_ranap2() {
        if (akses::aksesLogin() == TRUE) {
            $setting            = $this->db->get('tbl_pengaturan')->row();
//            $id                 = $this->input->get('id');
            $id_medcheck        = $this->input->get('id');
            
            $sql_medc           = $this->db->where('id', general::dekrip($id_medcheck))->get('tbl_trans_medcheck')->row();
            $sql_medc_plat      = $this->db->where('id_medcheck', $sql_medc->id)->get('tbl_trans_medcheck_plat')->result();
            $sql_medc_det       = $this->db->where('id_medcheck', $sql_medc->id)->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();
            $sql_medc_det_sum   = $this->db->select('SUM(diskon) AS diskon, SUM(potongan) AS potongan, SUM(subtotal) AS subtotal')->where('id_medcheck', $sql_medc->id)->get('tbl_trans_medcheck_det')->row();
            $sql_pasien         = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row(); 
            $sql_pekerjaan      = $this->db->where('id', $sql_pasien->id_pekerjaan)->get('tbl_m_jenis_kerja')->row();
            $sql_dokter         = $this->db->where('id_user', $sql_medc->id_dokter)->get('tbl_m_karyawan')->row();
            $kode_pasien        = $sql_pasien->kode_dpn.$sql_pasien->kode;
            $gambar1            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-esensia-2.png';
            $gambar2            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-bw-bg2-1440px.png';
            $gambar3            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-footer.png';
            $kasir              = (!empty($sql_medc->id_kasir) ? $this->ion_auth->user($sql_medc->id_kasir)->row()->first_name : $this->ion_auth->user()->row()->first_name);

            $judul = "INVOICE RAWAT INAP";
            
            $this->load->library('MedPDF');
            $pdf = new MedPDF('P', 'cm', array(21.5,33));
            $pdf->SetAutoPageBreak('auto', 6.5);
            $pdf->addPage();
            
            // Gambar Watermark Tengah
            $pdf->Image($gambar2,5,4,17,19);
            
            // Blok Judul
            $pdf->SetFont('Arial', 'B', '14');
            $pdf->Cell(19, .5, $judul, 0, 1, 'C');
            $pdf->Ln();
            
            // Blok ID PASIEN
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(5.5, .5, 'NIK / ID Card', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $sql_pasien->nik, '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Nama / Name', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, strtoupper($sql_pasien->nama_pgl).' ('.$sql_pasien->kode_dpn . $sql_pasien->kode.')', '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Tgl Lahir / Date of Birth', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $this->tanggalan->tgl_indo($sql_pasien->tgl_lahir), '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Jenis Kelamin / Gender', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $this->tanggalan->usia_lkp($sql_pasien->tgl_lahir).' ('.general::jns_klm($sql_pasien->jns_klm).')', '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Alamat / Address', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, (!empty($sql_pasien->alamat) ? $sql_pasien->alamat : (!empty($sql_pasien->alamat_dom) ? $sql_pasien->alamat_dom : '-')), '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'No. Telp / Phone Number', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $sql_pasien->no_hp, '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Tanggal / Date', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $this->tanggalan->tgl_indo5($sql_medc->tgl_masuk).' - '.$this->tanggalan->tgl_indo5($sql_medc->tgl_bayar), '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(5.5, .5, 'Kasir / Cashier', '0', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '0', 0, 'C', $fill);
            $pdf->Cell(8, .5, $this->ion_auth->user()->row()->first_name, '0', 0, 'L', $fill);
            $pdf->Ln(1);
            
            $fill = FALSE;
            $pdf->SetTextColor(5,148,19);
            $pdf->SetFont('Arial', 'B', '11');
            $pdf->Cell(4, 1, 'TGL', 'TB', 0, 'L', $fill);
            $pdf->Cell(8, 1, 'DESKRIPSI', 'TB', 0, 'L', $fill);
            $pdf->Cell(1, 1, 'JML', 'TB', 0, 'C', $fill);
            $pdf->Cell(3, 1, 'HARGA', 'TB', 0, 'R', $fill);
            $pdf->Cell(3, 1, 'SUBTOTAL', 'TB', 0, 'R', $fill);
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln();
            
            $fill = FALSE;
            $no = 1; $gtotal = 0;
            foreach ($sql_medc_det as $det){
                $sql_medc_itm = $this->db->where('id_medcheck', $det->id_medcheck)->where('id_item_kat', $det->id_item_kat)->where('jml >=', '0')->get('tbl_trans_medcheck_det')->result();
                $sql_medc_kat = $this->db->where('id', $det->id_item_kat)->get('tbl_m_kategori')->row();
                
                $pdf->SetFont('Arial', 'Bi', '10');
                $pdf->Cell(19, .5, strtoupper($sql_medc_kat->keterangan), '', 0, 'L', $fill);
                $pdf->Ln();
                
                // Detail Pemeriksaan dan hasilnya
                $sub = 0;
                foreach ($sql_medc_itm as $medc){                  
//                    if ($medc->jml >= 0) {
                        $sub = $sub + $medc->subtotal;
                        
                        if($medc->jml > 0){
                            $pdf->SetFont('Arial', '', '10');
                            $pdf->Cell(4, .5, $this->tanggalan->tgl_indo5($medc->tgl_simpan), '', 0, 'L', $fill);
                            $pdf->Cell(8, .5, $medc->item, '', 0, '', $fill);
                            $pdf->Cell(1, .5, (float) $medc->jml, '', 0, 'C', $fill);
                            $pdf->Cell(3, .5, general::format_angka($medc->harga), '', 0, 'R', $fill);
                            $pdf->Cell(3, .5, general::format_angka($medc->subtotal), '', 0, 'R', $fill);
                            $pdf->Ln();
                        
                            foreach (json_decode($medc->resep) as $racikan) {
                                $pdf->SetFont('Arial', 'i', '9');
                                $pdf->Cell(4, .5, '', '', 0, 'C', $fill);
                                $pdf->Cell(8, .5, '- R/ '.strtolower($racikan->item), '', 0, '', $fill);
                                $pdf->Cell(1, .5, (float) $racikan->jml, '', 0, 'C', $fill);
                                $pdf->Cell(3, .5, general::format_angka($racikan->harga), '', 0, 'R', $fill);
                                $pdf->Cell(3, .5, general::format_angka($racikan->subtotal), '', 0, 'R', $fill);
                                $pdf->Ln();                            
                            }
                        }
                        
                        $no++;
//                    }
                }
                
                // Kolom Subtotal
                $pdf->SetFont('Arial', 'B', '10');
                $pdf->Cell(16, .5, 'SUBTOTAL', 'B', 0, 'R', $fill);
                $pdf->Cell(3, .5, general::format_angka($sub), 'B', 0, 'R', $fill);
                $pdf->Ln();
                
                $gtotal = $gtotal + $sub;
            }
            
            $jml_total      = $sql_medc_det_sum->subtotal + $sql_medc_det_sum->diskon + $sql_medc_det_sum->potongan;
            $jml_diskon     = $jml_total - $sql_medc_det_sum->subtotal;
            $diskon         = ($jml_diskon / $jml_total) * 100;
            
            // Kolom Total
            $pdf->SetTextColor(5,148,19);
            $pdf->SetFont('Arial', 'B', '10');
            $pdf->Cell(16, .5, 'TOTAL', '', 0, 'R', $fill);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(3, .5, general::format_angka($jml_total), '', 0, 'R', $fill);
            $pdf->Ln();
            $pdf->SetTextColor(5,148,19);
            $pdf->Cell(16, .5, 'DISKON '.(!empty($diskon) ? $diskon.'%' : ''), '', 0, 'R', $fill);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(3, .5, '('.general::format_angka($jml_diskon).')', '', 0, 'R', $fill);
            $pdf->Ln();
            $pdf->Cell(13, .5, '', '', 0, 'R', $fill);
            $pdf->SetTextColor(5,148,19);
            $pdf->Cell(3, .5, 'GRAND TOTAL', 'T', 0, 'R', $fill);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(3, .5, general::format_angka($sql_medc_det_sum->subtotal), 'T', 0, 'R', $fill);
            $pdf->Ln();
            
            $jml_tot_byr = 0;
            foreach ($sql_medc_plat as $plat){
                $sql_plat    = $this->db->where('id', $plat->id_platform)->get('tbl_m_platform')->row();
                $jml_tot_byr = $jml_tot_byr + $plat->nominal;                
                
                $pdf->SetFont('Arial', 'i', '10');
                $pdf->Cell(16, .5, '('.$this->tanggalan->tgl_indo5($plat->tgl_simpan).') '.$sql_plat->platform, '', 0, 'R', $fill);
                $pdf->SetFont('Arial', 'B', '10');
                $pdf->Cell(3, .5, general::format_angka($plat->nominal), '', 0, 'R', $fill);
                $pdf->Ln();
            }
            
            $kembalian = $jml_tot_byr - $sql_medc_det_sum->subtotal;
            $jml_kembali = ($kembalian < 0 ? '0' : $kembalian);
            
            $pdf->Cell(13, .5, '', '', 0, 'R', $fill);
            $pdf->SetTextColor(5,148,19);
            $pdf->Cell(3, .5, 'TOTAL BAYAR', '', 0, 'R', $fill);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(3, .5, general::format_angka($jml_tot_byr), '', 0, 'R', $fill);
            $pdf->Ln();
            $pdf->Cell(13, .5, '', '', 0, 'R', $fill);
            $pdf->SetTextColor(5,148,19);
            $pdf->Cell(3, .5, 'KEMBALIAN', '', 0, 'R', $fill);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(3, .5, general::format_angka($jml_kembali), '', 0, 'R', $fill);
            
            $pdf->Ln(1);
            
            // QR GENERATOR VALIDASI
            $qr_validasi        = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-validasi-'.strtolower($kode_pasien).'.png';
            $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh manajemen klinik esensia. Pasien a/n. ';
            $params['data']    .= strtoupper($sql_pasien->nama_pgl).' ('.strtoupper($kode_pasien).')';
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_validasi; 
            $this->ciqrcode->generate($params);
            
            $gambar4            = $qr_validasi;
                        
            // QR GENERATOR PETUGAS KASIR
            $qr_kasir           = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-kasir-'.strtolower(md5($kasir)).'.png';
            $params['data']     = 'Telah diverifikasi oleh petugas kasir '.$kasir.'. Pada tanggal '.$this->tanggalan->tgl_indo5($sql_medc->tgl_bayar);
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_kasir;
            $this->ciqrcode->generate($params);
            
            $gambar5            = $qr_kasir;
            
            // Gambar VALIDASI
            $getY = $pdf->GetY() + 1;
            $pdf->Image($gambar4,1,$getY,2,2);
            $pdf->Image($gambar5,12.5,$getY,2,2);

            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(11.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, 'Semarang, '.$this->tanggalan->tgl_indo3($sql_medc->tgl_bayar), '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(11.5, .5, 'Validasi', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, 'Petugas Kasir', '', 0, 'L', $fill);
            $pdf->Ln(2.5);
            
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(11.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, '('.$kasir.')', '', 0, 'L', $fill);
            $pdf->Ln();

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $pdf->Output($sql_pasien->nama_pgl.'_INV'. '.pdf', $type);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    # KUITANSI
    public function pdf_medcheck_nota_ranap3() {
        if (akses::aksesLogin() == TRUE) {
            $this->load->helper("terbilang");
            $setting            = $this->db->get('tbl_pengaturan')->row();
//            $id                 = $this->input->get('id');
            $id_medcheck        = $this->input->get('id');
            
            $sql_medc           = $this->db->where('id', general::dekrip($id_medcheck))->get('tbl_trans_medcheck')->row();
            $sql_medc_plat      = $this->db->where('id_medcheck', $sql_medc->id)->get('tbl_trans_medcheck_plat')->result();
            $sql_medc_det       = $this->db->where('id_medcheck', $sql_medc->id)->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();
            $sql_medc_det_sum   = $this->db->select('SUM(diskon) AS diskon, SUM(potongan) AS potongan, SUM(subtotal) AS subtotal')->where('id_medcheck', $sql_medc->id)->get('tbl_trans_medcheck_det')->row();
            $sql_pasien         = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row(); 
            $sql_pekerjaan      = $this->db->where('id', $sql_pasien->id_pekerjaan)->get('tbl_m_jenis_kerja')->row();
            $sql_dokter         = $this->db->where('id_user', $sql_medc->id_dokter)->get('tbl_m_karyawan')->row();
            $kode_pasien        = $sql_pasien->kode_dpn.$sql_pasien->kode;
            $gambar1            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-esensia-2.png';
            $gambar2            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-bw-bg2-1440px.png';
            $gambar3            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-footer.png';
            $kasir              = (!empty($sql_medc->id_kasir) ? $this->ion_auth->user($sql_medc->id_kasir)->row()->first_name : $this->ion_auth->user()->row()->first_name);

            $judul = "KWITANSI ".strtoupper(general::status_rawat2($sql_medc->tipe));
            
            $this->load->library('MedPDF');
            $pdf = new MedPDF('P', 'cm', array(21.5,33));
            $pdf->SetAutoPageBreak('auto', 6.5);
            $pdf->addPage();
            
            // Gambar Watermark Tengah
            $pdf->Image($gambar2,5,4,17,19);
            
            // Blok Judul
            $pdf->SetFont('Arial', 'B', '14');
            $pdf->Cell(19, .5, $judul, 0, 1, 'C');
            $pdf->Ln();
            
            // Blok ID PASIEN
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(5.5, .5, 'TELAH MENERIMA PEMBAYARAN DARI :', '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(19, .5, strtoupper($sql_pasien->nama_pgl).' ('.$sql_pasien->kode_dpn . $sql_pasien->kode.')'.' / '.$this->tanggalan->usia($sql_pasien->tgl_lahir).' ('.general::jns_klm($sql_pasien->jns_klm).')', '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(19, .5, (!empty($sql_pasien->alamat) ? $sql_pasien->alamat : (!empty($sql_pasien->alamat_dom) ? $sql_pasien->alamat_dom : '-')), '0', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(19, .5, $sql_pasien->no_hp, '0', 0, 'L', $fill);
            $pdf->Ln(1);
            
            $fill = FALSE;
            $pdf->SetTextColor(5,148,19);
            $pdf->SetFont('Arial', 'B', '11');
            $pdf->Cell(5, 1, 'NO. INVOICE', 'TB', 0, 'L', $fill);
            $pdf->Cell(4, 1, 'TANGGAL', 'TB', 0, 'L', $fill);
            $pdf->Cell(6, 1, 'PLATFORM', 'TB', 0, 'L', $fill);
            $pdf->Cell(4, 1, 'NOMINAL', 'TB', 0, 'R', $fill);
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln();
            
            $fill = FALSE;
            $no = 1; $gtotal = 0;
            foreach ($sql_medc_plat as $det){
                $sql_medc_itm = $this->db->where('id_medcheck', $det->id_medcheck)->where('id_item_kat', $det->id_item_kat)->get('tbl_trans_medcheck_det')->result();
                $sql_platform = $this->db->where('id', $det->id_platform)->get('tbl_m_platform')->row();
                
                $pdf->SetFont('Arial', '', '10');
                $pdf->Cell(5, .5, $det->no_nota, '', 0, 'L', $fill);
                $pdf->Cell(4, .5, $this->tanggalan->tgl_indo5($det->tgl_simpan), '', 0, 'L', $fill);
                $pdf->Cell(6, .5, $sql_platform->platform.($det->platform != '-' ? $det->platform : ''), '', 0, 'L', $fill);
                $pdf->Cell(4, .5, general::format_angka($det->nominal), '', 0, 'R', $fill);
                $pdf->Ln();
                
                $gtotal = $gtotal + $det->nominal;
            }
            
            // Kolom Total
//            $pdf->SetTextColor(5,148,19);
//            $pdf->SetFont('Arial', 'Bi', '10');            
//            $pdf->Cell(13, .5, '', '', 0, 'R', $fill);
//            $pdf->SetTextColor(5,148,19);
//            $pdf->Cell(3, .5, 'TOTAL BAYAR', '', 0, 'R', $fill);
//            $pdf->SetTextColor(0,0,0);
//            $pdf->Cell(3, .5, general::format_angka($gtotal), '', 0, 'R', $fill);
//            $pdf->Ln(1);
            
            // Kolom Grand Total
            $pdf->Ln();
            $pdf->SetTextColor(5,148,19);
            $pdf->SetFont('Arial', 'B', '10');            
            $pdf->Cell(13, .5, '', '', 0, 'R', $fill);
            $pdf->SetTextColor(5,148,19);
            $pdf->Cell(3, .5, 'GRAND TOTAL', 'T', 0, 'R', $fill);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(3, .5, general::format_angka($sql_medc->jml_gtotal), 'T', 0, 'R', $fill);
            $pdf->Ln();
            // Kolom Grand Total          
            $pdf->Cell(13, .5, '', '', 0, 'R', $fill);
            $pdf->SetTextColor(5,148,19);
            $pdf->Cell(3, .5, 'TOTAL BAYAR', '', 0, 'R', $fill);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(3, .5, general::format_angka($gtotal), '', 0, 'R', $fill);
            $pdf->Ln();
            // Kolom Grand Total          
            $pdf->Cell(13, .5, '', '', 0, 'R', $fill);
            $pdf->SetTextColor(5,148,19);
            $pdf->Cell(3, .5, 'KEMBALIAN', 'B', 0, 'R', $fill);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(3, .5, general::format_angka($sql_medc->jml_kembali), 'B', 0, 'R', $fill);
            $pdf->Ln(1);
            
            // Kolom Terbilang
            $pdf->SetFont('Arial', 'Bi', '11');
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(3, .5, '  TERBILANG :  ', 'TB', 0, 'L', $fill);
            $pdf->SetFont('Arial', 'i', '11');
            $pdf->Cell(16, .5, strtoupper(penyebut($sql_medc->jml_gtotal)), 'TB', 0, 'L', $fill);
            $pdf->Ln();
            
            $pdf->Ln(1);
            
            // QR GENERATOR VALIDASI
            $qr_validasi        = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-validasi-'.strtolower($kode_pasien).'.png';
            $params['data']     = 'Telah diverifikasi dan ditandatangani secara elektronik oleh manajemen klinik esensia. Pasien a/n. ';
            $params['data']    .= strtoupper($sql_pasien->nama_pgl).' ('.strtoupper($kode_pasien).')';
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_validasi; 
            $this->ciqrcode->generate($params);
            
            $gambar4            = $qr_validasi;
                        
            // QR GENERATOR PETUGAS KASIR
            $qr_kasir           = FCPATH.'/file/pasien/'.strtolower($kode_pasien).'/qr-kasir-'.strtolower(md5($kasir)).'.png';
            $params['data']     = 'Telah diverifikasi oleh petugas kasir '.$kasir.'. Pada tanggal '.$this->tanggalan->tgl_indo5($sql_medc->tgl_bayar);
            $params['level']    = 'H';
            $params['size']     = 2;
            $params['savename'] = $qr_kasir;
            $this->ciqrcode->generate($params);
            
            $gambar5            = $qr_kasir;
            
            // Gambar VALIDASI
            $getY = $pdf->GetY() + 1;
            $pdf->Image($gambar4,1,$getY,2,2);
            $pdf->Image($gambar5,12.5,$getY,2,2);

            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(11.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, 'Semarang, '.$this->tanggalan->tgl_indo3($sql_medc->tgl_bayar), '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(11.5, .5, 'Validasi', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, 'Petugas Kasir', '', 0, 'L', $fill);
            $pdf->Ln(2.5);
            
            $pdf->SetFont('Arial', '', '10');
            $pdf->Cell(11.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(7.5, .5, '('.$kasir.')', '', 0, 'L', $fill);
            $pdf->Ln();

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $pdf->Output($sql_pasien->nama_pgl.'_KWI'. '.pdf', $type);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    # LABEL
    public function pdf_medcheck_label() {
        if (akses::aksesLogin() == TRUE) {
            $this->load->helper("terbilang");
            $setting            = $this->db->get('tbl_pengaturan')->row();
            $id_resep           = $this->input->get('id_resep');
            $id_medcheck        = $this->input->get('id');
            
            $sql_medc           = $this->db->where('id', general::dekrip($id_medcheck))->get('tbl_trans_medcheck')->row();
            $sql_medc_plat      = $this->db->where('id_medcheck', $sql_medc->id)->get('tbl_trans_medcheck_plat')->result();
            $sql_medc_det       = $this->db->where('id_medcheck', $sql_medc->id)->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();
            $sql_medc_res       = $this->db->where('id', general::dekrip($id_resep))->get('tbl_trans_medcheck_resep')->row();
            $sql_medc_res_det   = $this->db->where('id_resep', general::dekrip($id_resep))->get('tbl_trans_medcheck_resep_det')->result();
            $sql_pasien         = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row(); 
            $sql_dokter         = $this->db->where('id_user', $sql_medc->id_dokter)->get('tbl_m_karyawan')->row();
            $kode_pasien        = $sql_pasien->kode_dpn.$sql_pasien->kode;
            $gambar1            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-esensia-2.png';
            $gambar2            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-bw-bg2-1440px.png';
            $gambar3            = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-footer.png';
            $kasir              = (!empty($sql_medc->id_kasir) ? $this->ion_auth->user($sql_medc->id_kasir)->row()->first_name : $this->ion_auth->user()->row()->first_name);

            
            $this->load->library('MedLblPDF');
            $pdf = new MedLblPDF('P', 'cm', 'a4');
            # LEft 1.65
            $pdf->SetMargins(1.40,1,0.1);
            $pdf->SetAutoPageBreak('auto', 9);
            $pdf->header = 0;
            $pdf->addPage('','',false);

            foreach ($sql_medc_res_det as $resep_det){                                
                $pdf->SetFont('Arial', '', '6');
                $pdf->Cell(1, .3, strtoupper($sql_pasien->kode_dpn . $sql_pasien->kode), 'LT', 0, 'L', $fill);
                $pdf->Cell(1.23, .3, '', 'T', 0, 'L', $fill);
                $pdf->Cell(1.23, .3, $this->tanggalan->tgl_indo2($sql_medc->tgl_masuk), 'TR', 0, 'R', $fill);
                $pdf->Cell(0.42, .3, '', '0', 0, '1', $fill); # Separator
                $pdf->Ln();
                $pdf->SetFont('Arial', '', '6');
                $pdf->Cell(3.46, .3, substr($sql_pasien->nama_pgl, 0, 19), 'LR', 0, 'L', $fill);
                $pdf->Cell(0.42, .3, '', '0', 0, '1', $fill); # Separator
                $pdf->Ln();
                $pdf->SetFont('Arial', '', '4.5');
                $pdf->Cell(3.46, .3, substr($resep_det->item, 0, 35), 'LR', 0, 'L', $fill);
                $pdf->Cell(0.42, .3, '', '0', 0, '1', $fill); # Separator
                $pdf->Ln();
                $pdf->SetFont('Arial', 'Bi', '6');
                $pdf->Cell(3.46, .3, (strlen($resep_det->dosis) == '8' ? '' : $resep_det->dosis), 'LR', 0, 'L', $fill);
                $pdf->Cell(0.42, .3, '', '', 0, '1', $fill); # Separator
                $pdf->Ln();
                $pdf->Cell(3.46, .3, '('.(float)$resep_det->jml.' '.$resep_det->satuan.')', 'LR', 0, 'L', $fill);
                $pdf->Cell(0.42, .3, '', '0', 0, '1', $fill); # Separator
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', '6');
                $pdf->Cell(3.46, .3, '- Semoga Lekas Sembuh -', 'LBR', 0, 'C', $fill);
                $pdf->Cell(0.42, .3, '', '0', 0, '1', $fill); # Separator
                $pdf->Ln(1.3);
            }

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $pdf->Output('label_'.str_replace(array('.', ' ', ','), '', strtolower($sql_pasien->nama)).'.pdf', $type);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    # KWITANSI
    public function pdf_medcheck_kwi() {
        if (akses::aksesLogin() == TRUE) {
            $this->load->helper("terbilang");
            $setting            = $this->db->get('tbl_pengaturan')->row();
            $id_medcheck        = $this->input->get('id');
            $id                 = $this->input->get('id_kwi');
            
            $sql_medc           = $this->db->where('id', general::dekrip($id_medcheck))->get('tbl_trans_medcheck')->row();
            $sql_medc_kwi       = $this->db->where('id', general::dekrip($id))->get('tbl_trans_medcheck_kwi')->row();
            $sql_pasien         = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row(); 
            $sql_dokter         = $this->db->where('id_user', $sql_medc->id_dokter)->get('tbl_m_karyawan')->row();
            $sql_user           = $this->db->where('id_user', $sql_medc_kwi->id_user)->get('tbl_m_karyawan')->row();
            $kode_pasien        = $sql_pasien->kode_dpn.$sql_pasien->kode;
            $gambar1 = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-header-es1.png'; // base_url('file/img/logo-header-es1.png');
            $gambar2 = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-header-es2.png'; // base_url('file/img/logo-header-es2.png');
            $gambar3 = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-header-es3.png'; // base_url('file/img/logo-header-es3.png');
            $gambar4 = FCPATH.'/assets/theme/admin-lte-3/dist/img/logo-header-es4.png'; // base_url('file/img/img/logo-header-es4.png');
            $kasir              = (!empty($sql_medc->id_kasir) ? $this->ion_auth->user($sql_medc->id_kasir)->row()->first_name : $this->ion_auth->user()->row()->first_name);

            $judul  = "RESUME MEDIS";
            $judul2 = "Medical Resume Result";
            
            $this->load->library('MedLblPDF');
            $pdf = new MedLblPDF('P', 'cm', array(21.5,33));
            $pdf->SetAutoPageBreak('auto', 6);
            $pdf->header = 0;
            $pdf->addPage('','',false);
                        
            // Gambar Logo Atas 1
            $pdf->Image($gambar1, 1.3, 1.15, 1.94, 1.21);
            // Gambar Logo Atas 2
            $pdf->Image($gambar2, 8.7, 1.15, 1.02, 1.15);
            // Gambar Logo Atas 3
            $pdf->Image($gambar3, 9.85, 1.15, 0.71, 1.59);
            // Gambar Logo Atas 4
            $pdf->Image($gambar4, 10.75, 1.15, 1.17, 1.23);

            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(11, 1.75, '', 'B', 0, 'L', $fill);
//            $pdf->Cell(2.5, 1.75, '', 'LTB', 0, 'L', $fill);
//            $pdf->Cell(5, 1.75, 'sas', 'LTB', 0, 'L', $fill);
//            $pdf->Cell(3.5, 1.75, '', 'LTB', 0, 'L', $fill);
            $pdf->Cell(3, 1.75, 'KWITANSI', 'B', 0, 'C', $fill);
            $pdf->Ln();
            $pdf->Cell(14, .5, '', '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(3, .5, 'Sudah terima dari', '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(7.5, .5, $sql_medc_kwi->dari, '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, 'Rp.', '', 0, '', $fill);
            $pdf->Cell(2.5, .5, general::format_angka($sql_medc_kwi->nominal), '', 0, 'R', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(3, .5, 'Terbilang', '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '', 0, 'C', $fill);
            $pdf->SetFont('Arial', 'i', '8');
            $pdf->MultiCell(10.5, .5, strtoupper(general::format_angka_str($sql_medc_kwi->nominal)).' RUPIAH', '', 'L');
//            $pdf->Cell(10.5, .5, strtoupper(general::format_angka_str($sql_medc_kwi->nominal)).' RUPIAH', 'R', 0, 'L', $fill);
//            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(3, .5, 'Untuk Pembayaran', '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, ':', '', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(10.5, .5, $sql_medc_kwi->ket, '', 0, 'C', $fill);
            $pdf->Ln();
//            $pdf->SetFont('Arial', 'B', '9');
//            $pdf->Cell(3, .5, 'Diagnosa', '', 0, 'L', $fill);
//            $pdf->Cell(.5, .5, ':', '', 0, 'C', $fill);
//            $pdf->SetFont('Arial', '', '9');
//            $pdf->Cell(10.5, .5, $sql_medc->diagnosa, '', 0, 'L', $fill);
//            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(3, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(10.5, .5, $sql_pasien->nama_pgl, '', 0, 'C', $fill);
            $pdf->Ln();
            $pdf->Cell(14, .5, '', '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', '9');
            $pdf->Cell(3, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(.5, .5, '', '', 0, 'C', $fill);
            $pdf->SetFont('Arial', '', '9');
            $pdf->Cell(7.5, .5, '', '', 0, 'L', $fill);
            $pdf->Cell(3, .5, 'Semarang, '.$this->tanggalan->tgl_indo3($sql_medc->tgl_masuk), '', 0, 'R', $fill);
            $pdf->Ln();
            $pdf->Cell(14, .5, '', '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', '7');
            $pdf->Cell(14, .5, $setting->judul, '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(14, .5, $setting->alamat, '', 0, 'L', $fill);
            $pdf->Ln();
            $pdf->Cell(9, .5, $setting->tlp, 'B', 0, 'L', $fill);
            $pdf->Cell(5, .5, $sql_user->nama, 'B', 0, 'R', $fill);
            $pdf->Ln();

            $type = (isset($_GET['type']) ? $_GET['type'] : 'I');

            $pdf->Output($sql_pasien->nama_pgl.'_KWI'. '.pdf', $type);
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    # XLS LABEL
    public function xls_medcheck_label(){
        if (akses::aksesLogin() == TRUE) {
            $setting            = $this->db->get('tbl_pengaturan')->row();
            $id_resep           = $this->input->get('id_resep');
            $id_medcheck        = $this->input->get('id');

            $sql_medc           = $this->db->where('id', general::dekrip($id_medcheck))->get('tbl_trans_medcheck')->row();
            $sql_medc_plat      = $this->db->where('id_medcheck', $sql_medc->id)->get('tbl_trans_medcheck_plat')->result();
            $sql_medc_det       = $this->db->where('id_medcheck', $sql_medc->id)->group_by('id_item_kat')->get('tbl_trans_medcheck_det')->result();
            $sql_medc_res       = $this->db->where('id', general::dekrip($id_resep))->get('tbl_trans_medcheck_resep')->row();
            $sql_medc_res_det   = $this->db->where('id_resep', general::dekrip($id_resep))->get('tbl_trans_medcheck_resep_det')->result();
            $sql_pasien         = $this->db->where('id', $sql_medc->id_pasien)->get('tbl_m_pasien')->row(); 
            $sql_dokter         = $this->db->where('id_user', $sql_medc->id_dokter)->get('tbl_m_karyawan')->row();
            $kode_pasien        = $sql_pasien->kode_dpn.$sql_pasien->kode;
            $res                = str_replace('/','', $sql_medc_res->no_resep);
            $no_res             = strtolower($res);

            $objPHPExcel = new PHPExcel();

            // Header Tabel Nota
            $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold(TRUE);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', '')
                    ->setCellValue('B1', 'Tanggal')
                    ->setCellValue('C1', 'RM')
                    ->setCellValue('D1', 'Pasien')
                    ->setCellValue('E1', 'Tgl Lahir')
                    ->setCellValue('F1', 'L/P')
                    ->setCellValue('G1', 'Item')
                    ->setCellValue('H1', 'Qty')
                    ->setCellValue('I1', 'Satuan')
                    ->setCellValue('J1', 'Exp')
                    ->setCellValue('K1', 'Dosis')
                    ->setCellValue('L1', 'Keterangan')
                    ->setCellValue('M1', 'Dokter')
                    ->setCellValue('N1', 'Judul');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(65);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(13);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(65);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(40);

            if(!empty($sql_medc_res_det)){
                $no    = 1;
                $cell  = 2;
                $total = 0;
                foreach ($sql_medc_res_det as $resep_det){
                    $sql_doc    = $this->db->where('id_user', $sql_medc->id_dokter)->get('tbl_m_karyawan')->row();
                    $item       = $this->db->where('id', $resep_det->id_item)->get('tbl_m_produk')->row();

                    $objPHPExcel->getActiveSheet()->getStyle('B'.$cell.':C'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$cell.':F'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$cell.':J'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('K'.$cell.':M'.$cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$cell, '')
                            ->setCellValue('B'.$cell, $this->tanggalan->tgl_indo($resep_det->tgl_simpan))
                            ->setCellValue('C'.$cell, $kode_pasien)
                            ->setCellValue('D'.$cell, $sql_pasien->nama)
                            ->setCellValue('E'.$cell, $this->tanggalan->tgl_indo($sql_pasien->tgl_lahir).' ('.$this->tanggalan->usia_lkp($sql_pasien->tgl_lahir).')')
                            ->setCellValue('F'.$cell, general::jns_klm($sql_pasien->jns_klm))
                            ->setCellValue('G'.$cell, $resep_det->item)
                            ->setCellValue('H'.$cell, (float)$resep_det->jml)
                            ->setCellValue('I'.$cell, $resep_det->satuan)
                            ->setCellValue('J'.$cell, ($resep_det->tgl_ed != '0000-00-00' ? $this->tanggalan->tgl_indo($resep_det->tgl_ed) : ''))
                            ->setCellValue('K'.$cell, ($resep_det->dosis == '  Tiap  ' ? '' : $resep_det->dosis))
                            ->setCellValue('L'.$cell, $resep_det->dosis_ket)
                            ->setCellValue('M'.$cell, (!empty($sql_doc->nama_dpn) ? $sql_doc->nama_dpn.' ' : '').$sql_doc->nama.(!empty($sql_doc->nama_blk) ? ', '.$sql_doc->nama_blk : ''))
                            ->setCellValue('N'.$cell, $setting->judul);

                    $no++;
                    $cell++;
                }
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Label Farmasi');

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
                    ->setKeywords($setting->judul_app)
                    ->setCategory("Untuk mencetak nota dot matrix");



            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="label_'.$no_res.'_'.str_replace(array(' ','.'),'_', strtolower($sql_pasien->nama)).'.xls"');

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


    # Pencarian modul pendaftaran
    public function set_cari_daftar() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id_medcheck');
            $nama       = $this->input->post('pasien');
            $tipe       = $this->input->post('tipe');
            $tgl        = $this->input->post('tgl_daftar');
            $status_byr = $this->input->post('status_bayar');

            $sql        = $sql   = $this->db->select('*')->where('status_akt !=', '2')->like('nama',$nama)->limit(10)->get('tbl_pendaftaran');
            
            $sql_row    = $sql->row();
            $sql_jml    = $sql->num_rows();
            
            redirect(base_url('medcheck/data_pendaftaran.php?'.(!empty($tgl) ? 'filter_tgl='.$this->tanggalan->tgl_indo_sys($tgl) : '').(!empty($nama) ? 'filter_nama='.$nama : '').(!empty($id) ? '&id='.general::enkrip($id) : '')));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    

    public function set_cari_medcheck() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id_medcheck');
            $nama       = $this->input->post('pasien');
            $tipe       = $this->input->post('tipe');
            $tgl        = $this->input->post('tgl');
            $status_byr = $this->input->post('status_bayar');

            $sql        = $this->db
                                ->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.tipe AS tipe_rawat, tbl_m_pasien.id AS id_pasien, tbl_m_pasien.nik, tbl_m_pasien.nama, tbl_m_pasien.nama_pgl, tbl_m_pasien.no_hp, tbl_m_pasien.tgl_lahir, tbl_m_pasien.jns_klm, tbl_m_pasien.alamat, tbl_m_poli.lokasi AS poli')
                                ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                ->where('tbl_trans_medcheck.status_hps', '0')
                                ->like('DATE(tbl_trans_medcheck.tgl_masuk)', $this->tanggalan->tgl_indo_sys($tgl))
                                ->like('tbl_trans_medcheck.id', $id)
                                ->like('tbl_trans_medcheck.pasien', $nama)
                                ->like('tbl_trans_medcheck.tipe', $tipe)
                                ->like('tbl_trans_medcheck.status_bayar', $status_byr)
                                ->get('tbl_trans_medcheck');
            
            $sql_row    = $sql->row();
            $sql_jml    = $sql->num_rows();
            
            redirect(base_url('medcheck/index.php?tipe=1'.(!empty($nama) ? '&filter_nama='.$nama : '').(!empty($id) ? '&id='.general::enkrip($id) : '').(!empty($tgl) ? '&filter_tgl='.$this->tanggalan->tgl_indo_sys($tgl) : '').(!empty($tipe) ? '&filter_tipe='.$tipe : '').(isset($status_byr) ? '&filter_bayar='.$status_byr : '')));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_cari_medcheck_rad() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id_medcheck');
            $nama       = $this->input->post('pasien');
            $tipe       = $this->input->post('tipe');
            $tgl        = $this->input->post('tgl');
            $status     = $this->input->post('status');

            $sql        = $this->db
                                ->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.tipe AS tipe_rawat, tbl_m_pasien.id AS id_pasien, tbl_m_pasien.nik, tbl_m_pasien.nama, tbl_m_pasien.nama_pgl, tbl_m_pasien.no_hp, tbl_m_pasien.tgl_lahir, tbl_m_pasien.jns_klm, tbl_m_pasien.alamat, tbl_m_poli.lokasi AS poli')
                                ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                ->where('tbl_trans_medcheck.status_hps', '0')
                                ->like('DATE(tbl_trans_medcheck.tgl_masuk)', $this->tanggalan->tgl_indo_sys($tgl))
                                ->like('tbl_trans_medcheck.id', $id)
                                ->like('tbl_trans_medcheck.pasien', $nama)
                                ->like('tbl_trans_medcheck.tipe', $tipe)
                                ->like('tbl_trans_medcheck.status_bayar', $status_byr)
                                ->get('tbl_trans_medcheck');
            
            $sql_row    = $sql->row();
            $sql_jml    = $sql->num_rows();
            
            redirect(base_url('medcheck/data_radiologi.php?status='.$status.(!empty($nama) ? '&filter_nama='.$nama : '').(!empty($id) ? '&id='.general::enkrip($id) : '').(!empty($tgl) ? '&filter_tgl='.$this->tanggalan->tgl_indo_sys($tgl) : '').(!empty($tipe) ? '&filter_tipe='.$tipe : '')));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_cari_medcheck_batal() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id_medcheck');
            $nama       = $this->input->post('pasien');
            $tipe       = $this->input->post('tipe');
            $tgl        = $this->input->post('tgl');
            $status_byr = $this->input->post('status_bayar');

            $sql        = $this->db
                                ->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.tipe AS tipe_rawat, tbl_m_pasien.id AS id_pasien, tbl_m_pasien.nik, tbl_m_pasien.nama, tbl_m_pasien.nama_pgl, tbl_m_pasien.no_hp, tbl_m_pasien.tgl_lahir, tbl_m_pasien.jns_klm, tbl_m_pasien.alamat, tbl_m_poli.lokasi AS poli')
                                ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                ->where('tbl_trans_medcheck.status_hps', '1')
                                ->like('DATE(tbl_trans_medcheck.tgl_masuk)', $this->tanggalan->tgl_indo_sys($tgl))
                                ->like('tbl_trans_medcheck.id', $id)
                                ->like('tbl_trans_medcheck.pasien', $nama)
                                ->like('tbl_trans_medcheck.tipe', $tipe)
                                ->like('tbl_trans_medcheck.status_bayar', $status_byr)
                                ->get('tbl_trans_medcheck');
            
            $sql_row    = $sql->row();
            $sql_jml    = $sql->num_rows();
            
            redirect(base_url('medcheck/data_hapus.php?tipe=1'.(!empty($nama) ? '&filter_nama='.$nama : '').(!empty($id) ? '&id='.general::enkrip($id) : '').(!empty($tgl) ? '&filter_tgl='.$this->tanggalan->tgl_indo_sys($tgl) : '').(!empty($tipe) ? '&filter_tipe='.$tipe : '').(isset($status_byr) ? '&filter_bayar='.$status_byr : '')));
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function set_cari_medcheck_bayar() {
        if (akses::aksesLogin() == TRUE) {
            $id         = $this->input->post('id_medcheck');
            $nama       = $this->input->post('pasien');
            $tgl_msk    = $this->input->post('tgl');

            $sql        = $this->db
                                ->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.tipe AS tipe_rawat, tbl_m_pasien.id AS id_pasien, tbl_m_pasien.nik, tbl_m_pasien.nama, tbl_m_pasien.nama_pgl, tbl_m_pasien.no_hp, tbl_m_pasien.tgl_lahir, tbl_m_pasien.jns_klm, tbl_m_pasien.alamat, tbl_m_poli.lokasi AS poli')
                                ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                                ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                                ->where('tbl_trans_medcheck.status >=', '5')
                                ->where('tbl_trans_medcheck.status_bayar !=', '1')
                                ->like('tbl_trans_medcheck.tgl_masuk', $this->tanggalan->tgl_indo_sys($tgl_msk))
                                ->like('tbl_trans_medcheck.pasien', $nama)
                                ->get('tbl_trans_medcheck');
            
            $sql_row    = $sql->row();
            $sql_jml    = $sql->num_rows();
            
            redirect(base_url('medcheck/data_pemb.php?tipe=1'.(!empty($nama) ? '&filter_nama='.$nama : '').(!empty($tgl_msk) ? '&filter_tgl='.$this->tanggalan->tgl_indo_sys($tgl_msk) : '')));
        } else {
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
                              ->limit(200)->get('tbl_m_pasien')->result();
            
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
    
    public function json_dokter() {
        if (akses::aksesLogin() == TRUE) {
            $term  = $this->input->get('term');
            $sql   = $this->db->select('id, id_user, kode, nik, nama, nama_dpn, nama_blk')->like('nama',$term)->where('id_user_group', '10')->get('tbl_m_karyawan')->result();
            
            if(!empty($sql)){
                foreach ($sql as $sql){
                    $produk[] = array(
                        'id'         => $sql->id,
                        'id_user'    => $sql->id_user,
                        'kode'       => $sql->kode,
                        'nik'        => $sql->nik,
                        'nama'       => (!empty($sql->nama_dpn) ? $sql->nama_dpn.' ' : '').$sql->nama.(!empty($sql->nama_blk) ? ', '.$sql->nama_blk : ''),
                    );
                }
                echo json_encode($produk);
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
            $stat  = $this->input->get('status');
            $page  = $this->input->get('page');
            $sg    = $this->ion_auth->user()->row()->status_gudang;
            
            switch ($page){
                default:
                    $sql = $this->db->select('tbl_m_produk.id, tbl_m_produk.id_satuan, tbl_m_produk.kode, tbl_m_produk.produk, tbl_m_produk.produk_alias, tbl_m_produk.produk_kand, tbl_m_produk.jml, tbl_m_produk.harga_jual, tbl_m_produk.harga_beli, tbl_m_produk.harga_beli, tbl_m_produk.status_brg_dep')
                                    ->where("(tbl_m_produk.produk LIKE '%" . $term . "%' OR tbl_m_produk.produk_alias LIKE '%" . $term . "%' OR tbl_m_produk.produk_kand LIKE '%" . $term . "%' OR tbl_m_produk.kode LIKE '%" . $term . "%' OR tbl_m_produk.barcode LIKE '" . $term . "')")
                                    ->order_by('tbl_m_produk.jml', ($_GET['mod'] == 'beli' ? 'asc' : 'desc'))
                                    ->get('tbl_m_produk')->result();
                    break;
                    
                case 'tindakan':
                    $sql = $this->db->select('tbl_m_produk.id, tbl_m_produk.id_satuan, tbl_m_produk.kode, tbl_m_produk.produk, tbl_m_produk.produk_alias, tbl_m_produk.produk_kand, tbl_m_produk.jml, tbl_m_produk.harga_jual, tbl_m_produk.harga_beli, tbl_m_produk.harga_beli, tbl_m_produk.status_brg_dep')
                                    ->where("(tbl_m_produk.produk LIKE '%" . $term . "%' OR tbl_m_produk.produk_alias LIKE '%" . $term . "%' OR tbl_m_produk.produk_kand LIKE '%" . $term . "%' OR tbl_m_produk.kode LIKE '%" . $term . "%' OR tbl_m_produk.barcode LIKE '" . $term . "')")
                                    ->where('tbl_m_produk.status', '2')
                                    ->or_where('tbl_m_produk.status', '6')
                                    ->order_by('tbl_m_produk.jml', ($_GET['mod'] == 'beli' ? 'asc' : 'desc'))
                                    ->get('tbl_m_produk')->result();
                    break;
                    
                case 'lab':
                    $sql = $this->db->select('tbl_m_produk.id, tbl_m_produk.id_satuan, tbl_m_produk.kode, tbl_m_produk.produk, tbl_m_produk.produk_alias, tbl_m_produk.produk_kand, tbl_m_produk.jml, tbl_m_produk.harga_jual, tbl_m_produk.harga_beli, tbl_m_produk.harga_beli, tbl_m_produk.status_brg_dep')
                                    ->where('tbl_m_produk.status', '3')
                                    ->where("(tbl_m_produk.produk LIKE '%" . $term . "%' OR tbl_m_produk.produk_alias LIKE '%" . $term . "%' OR tbl_m_produk.produk_kand LIKE '%" . $term . "%' OR tbl_m_produk.kode LIKE '%" . $term . "%' OR tbl_m_produk.barcode LIKE '" . $term . "')")
                                    ->get('tbl_m_produk')->result();
                    break;
                    
                case 'rad':
                    $sql = $this->db->select('tbl_m_produk.id, tbl_m_produk.id_satuan, tbl_m_produk.kode, tbl_m_produk.produk, tbl_m_produk.produk_alias, tbl_m_produk.produk_kand, tbl_m_produk.jml, tbl_m_produk.harga_jual, tbl_m_produk.harga_beli, tbl_m_produk.harga_beli, tbl_m_produk.status_brg_dep')
                                    ->where("(tbl_m_produk.produk LIKE '%" . $term . "%' OR tbl_m_produk.produk_alias LIKE '%" . $term . "%' OR tbl_m_produk.produk_kand LIKE '%" . $term . "%' OR tbl_m_produk.kode LIKE '%" . $term . "%' OR tbl_m_produk.barcode LIKE '" . $term . "')")
                                    ->where('tbl_m_produk.status', '5')
                                    ->order_by('tbl_m_produk.jml', ($_GET['mod'] == 'beli' ? 'asc' : 'desc'))
                                    ->get('tbl_m_produk')->result();
                    break;
                    
                case 'obat':
                    $sql = $this->db->select('tbl_m_produk.id, tbl_m_produk.id_satuan, tbl_m_produk.kode, tbl_m_produk.produk, tbl_m_produk.produk_alias, tbl_m_produk.produk_kand, tbl_m_produk.jml, tbl_m_produk.harga_jual, tbl_m_produk.harga_beli, tbl_m_produk.harga_beli, tbl_m_produk.status_brg_dep')
                                    ->where("(tbl_m_produk.produk LIKE '%" . $term . "%' OR tbl_m_produk.produk_alias LIKE '%" . $term . "%' OR tbl_m_produk.produk_kand LIKE '%" . $term . "%' OR tbl_m_produk.kode LIKE '%" . $term . "%' OR tbl_m_produk.barcode LIKE '" . $term . "')")
                                    ->where('tbl_m_produk.status', '4')
                                    ->order_by('tbl_m_produk.jml', ($_GET['mod'] == 'beli' ? 'asc' : 'desc'))
                                    ->get('tbl_m_produk')->result();
                    break;
            }

            if(!empty($sql)){
                foreach ($sql as $sql){
                    $sql_satuan = $this->db->where('id', $sql->id_satuan)->get('tbl_m_satuan')->row();
                    $sql_stok   = $this->db->select('SUM(jml * jml_satuan) AS jml')->where('id_produk', $sql->id)->where('id_gudang', $sg)->get('tbl_m_produk_stok')->row();
                        $produk[] = array(
                            'id'            => general::enkrip($sql->id),
                            'kode'          => $sql->kode,
                            'name'          => $sql->produk,
                            'alias'         => (!empty($sql->produk_alias) ? $sql->produk_alias : ''),
                            'kandungan'     => (!empty($sql->produk_kand) ? '('.strtolower($sql->produk_kand).')' : ''),
                            'jml'           => $sql_stok->jml.' '.$sql_satuan->satuanTerkecil,
                            'satuan'        => $sql_satuan->satuanTerkecil,
                            'harga'         => (float)$sql->harga_jual,
                            'harga_beli'    => (float)$sql->harga_beli,
                            'harga_grosir'  => (float)$sql->harga_grosir,
                        );
                }

                echo json_encode($produk);
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function json_icd() {
        if (akses::aksesLogin() == TRUE) {
            $term  = $this->input->get('term');
            $stat  = $this->input->get('status');
//            $sql   = $this->db->select('id,kode,diagnosa,diagnosa_en')->where('status_icd', $stat)->like('kode',$term)->like('diagnosa',$term)->like('diagnosa_en',$term)->limit(10)->get('tbl_m_icd')->result();
            $sql   = $this->db->select('id,kode,diagnosa,diagnosa_en')
                                ->where('status_icd', $stat)
                                ->where("(kode LIKE '%".$term."%' OR diagnosa LIKE '%".$term."%' OR diagnosa_en LIKE '%".$term."%')")
                            ->limit(10)->get('tbl_m_icd')->result();


            if(!empty($sql)){
                foreach ($sql as $sql){
                    $produk[] = array(
                        'id'            => $sql->id,
                        'kode'          => $sql->kode,
                        'diagnosa'      => $sql->diagnosa,
                        'diagnosa_en'   => $sql->diagnosa_en,
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
    
    public function json_po() {
        if (akses::aksesLogin() == TRUE) {
            $term  = $this->input->get('term');
            $sql   = $this->db
                          ->select('tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.tipe AS tipe_rawat, tbl_m_pasien.id AS id_pasien, tbl_m_pasien.nik, tbl_m_pasien.nama, tbl_m_pasien.nama_pgl, tbl_m_pasien.no_hp, tbl_m_pasien.tgl_lahir, tbl_m_pasien.jns_klm, tbl_m_pasien.alamat, tbl_m_poli.lokasi AS poli')
                           ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                           ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                           ->like('tbl_m_pasien.nama', $term)
                           ->or_like('tbl_m_pasien.nik', $term)
                           ->or_like('tbl_m_pasien.alamat', $term)
                           ->get('tbl_trans_medcheck')->result();
            
            if(!empty($sql)){
                foreach ($sql as $sql){
                    $produk[] = array(
                        'id'         => $sql->id,
                        'id_pasien'  => $sql->id_pasien,
                        'nik'        => $sql->nik,
                        'nama'       => $sql->nama_pgl,
                        'nama2'      => $sql->nama,
                        'no_hp'      => $sql->no_hp,
                        'tgl_lahir'  => $this->tanggalan->tgl_indo2($sql->tgl_lahir).' ('.$this->tanggalan->usia($sql->tgl_lahir).')',
                        'jns_klm'    => general::jns_klm($sql->jns_klm),
                        'alamat'     => $sql->alamat,
                        'no_trx'     => $sql->no_rm,
                        'no_nota'    => $sql->no_nota,
                        'tgl_masuk'  => $this->tanggalan->tgl_indo5($sql->tgl_masuk),
                        'poli'       => $sql->poli,
                        'tipe_rawat' => general::status_rawat2($sql->tipe_rawat),
                    );
                }
                
                echo json_encode($produk);
                
//                echo '<pre>';
//                print_r(json_encode($produk, JSON_PRETTY_PRINT));
//                echo '</pre>';
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    public function json_medcheck_retur() {
        if (akses::aksesLogin() == TRUE) {
            $term  = $this->input->get('term');
            $sql   = $this->db
                          ->select("tbl_trans_medcheck.id, tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.no_nota, tbl_trans_medcheck.no_rm, tbl_trans_medcheck.tipe AS tipe_rawat, tbl_m_pasien.id AS id_pasien, tbl_m_pasien.kode_dpn, tbl_m_pasien.kode, tbl_m_pasien.nik, tbl_m_pasien.nama, tbl_m_pasien.nama_pgl, tbl_m_pasien.no_hp, tbl_m_pasien.tgl_lahir, tbl_m_pasien.jns_klm, tbl_m_pasien.alamat, tbl_m_poli.lokasi AS poli")
                           ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                           ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                           ->where('tbl_trans_medcheck.status_bayar', '1')
                           ->like('tbl_m_pasien.nama', $term)
                           ->or_like('tbl_m_pasien.nik', $term)
                           ->or_like('tbl_m_pasien.alamat', $term)
                           ->or_like('tbl_trans_medcheck.no_rm', $term)
                           ->get('tbl_trans_medcheck')->result();
            
            if(!empty($sql)){
                foreach ($sql as $sql){
                    $produk[] = array(
                        'id'         => general::enkrip($sql->id),
                        'id_pasien'  => $sql->id_pasien,
                        'nik'        => $sql->nik,
                        'kode'       => $sql->kode_dpn.$sql->kode,
                        'nama'       => $sql->nama_pgl,
                        'nama2'      => $sql->nama,
                        'no_hp'      => $sql->no_hp,
                        'tgl_lahir'  => $this->tanggalan->tgl_indo2($sql->tgl_lahir).' ('.$this->tanggalan->usia($sql->tgl_lahir).')',
                        'jns_klm'    => general::jns_klm($sql->jns_klm),
                        'alamat'     => $sql->alamat,
                        'no_trx'     => $sql->no_rm,
                        'no_nota'    => $sql->no_nota,
                        'tgl_masuk'  => $this->tanggalan->tgl_indo5($sql->tgl_masuk),
                        'poli'       => $sql->poli,
                        'tipe_rawat' => general::status_rawat2($sql->tipe_rawat),
                    );
                }
                
                echo json_encode($produk);
                
//                echo '<pre>';
//                print_r(json_encode($produk, JSON_PRETTY_PRINT));
//                echo '</pre>';
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }

    public function json_medcheck_ant() {
        if (akses::aksesLogin() == TRUE) {
            $term  = $this->input->get('term');
            $sql   = $this->db->select('*')->where('status_akt !=', '2')->like('nama',$term)->limit(10)->get('tbl_pendaftaran')->result();
            
            if(!empty($sql)){
                foreach ($sql as $sql){
                    $produk[] = array(
                        'id'         => $sql->id,
                        'nik'        => $sql->nik,
                        'no_urut'    => $sql->no_urut,
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
    
    # JSON Label
    public function json_medcheck_label() {
        if (akses::aksesLogin() == TRUE) {
            $id_resep           = $this->input->get('id_resep');
            $id_medcheck        = $this->input->get('id');
            
            $sql   = $this->db->select("tbl_trans_medcheck.id, tbl_trans_medcheck.id_dokter, tbl_trans_medcheck_resep.tgl_simpan, tbl_m_pasien.kode_dpn, tbl_m_pasien.kode, tbl_m_pasien.nama, tbl_m_pasien.tgl_lahir, tbl_m_pasien.jns_klm, tbl_trans_medcheck_resep_det.item, tbl_trans_medcheck_resep_det.jml, tbl_trans_medcheck_resep_det.satuan, tbl_trans_medcheck_resep_det.keterangan as catatan, tbl_trans_medcheck_resep_det.dosis, tbl_trans_medcheck_resep_det.dosis_ket, tbl_trans_medcheck_resep_det.status_mkn")
                              ->where('tbl_trans_medcheck_resep_det.id_resep', general::dekrip($id_resep))
                              ->join('tbl_trans_medcheck_resep', 'tbl_trans_medcheck_resep.id=tbl_trans_medcheck_resep_det.id_resep')
                              ->join('tbl_trans_medcheck', 'tbl_trans_medcheck.id=tbl_trans_medcheck_resep_det.id_medcheck')
                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                              ->order_by('tbl_trans_medcheck_resep_det.id', 'ASC')
                              ->get('tbl_trans_medcheck_resep_det')->result();

            if(!empty($sql)){
                foreach ($sql as $resep){
                    $setting    = $this->db->get('tbl_pengaturan')->row();
                    $sql_doc    = $this->db->where('id_user', $resep->id_dokter)->get('tbl_m_karyawan')->row();
                    
                    $recipe[] = array(
                        'tgl'           => $this->tanggalan->tgl_indo($resep->tgl_simpan),
                        'rm'            => $resep->kode_dpn.''.$resep->kode,
                        'nama'          => $resep->nama,
                        'tgl_lahir'     => $this->tanggalan->tgl_indo($resep->tgl_lahir).' ('.$this->tanggalan->usia_lkp($resep->tgl_lahir).')',
                        'jns_klm'       => general::jns_klm($resep->jns_klm),
                        'item'          => $resep->item,
                        'jml'           => (float)$resep->jml,
                        'satuan'        => $resep->satuan,
                        'catatan'       => $resep->catatan,
                        'dosis'         => $resep->dosis,
                        'makan'         => general::tipe_obat_makan($resep->status_mkn),
                        'ket'           => $resep->dosis_ket,
                        'dokter'        => (!empty($sql_doc->nama_dpn) ? $sql_doc->nama_dpn.' ' : '').$sql_doc->nama.(!empty($sql_doc->nama_blk) ? ', '.$sql_doc->nama_blk : ''),
                        'judul'         => $setting->judul
                    );
                }

                echo json_encode($recipe);
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
    
    # JSON Label ID Pasien
    public function json_medcheck_label_id() {
        if (akses::aksesLogin() == TRUE) {
            $id_medcheck        = $this->input->get('id');
            
            $sql   = $this->db->select("tbl_trans_medcheck.tgl_masuk, tbl_trans_medcheck.id_dokter, tbl_trans_medcheck.no_rm AS trx, tbl_m_pasien.kode_dpn, tbl_m_pasien.kode, tbl_m_pasien.nama_pgl, tbl_m_pasien.tgl_lahir, tbl_m_pasien.jns_klm, tbl_m_pasien.alamat, tbl_m_poli.lokasi AS poli")
                              ->where('tbl_trans_medcheck.id', general::dekrip($id_medcheck))
                              ->join('tbl_m_pasien', 'tbl_m_pasien.id=tbl_trans_medcheck.id_pasien')
                              ->join('tbl_m_poli', 'tbl_m_poli.id=tbl_trans_medcheck.id_poli')
                              ->get('tbl_trans_medcheck')->result();

            if(!empty($sql)){
                foreach ($sql as $label){
                    $setting    = $this->db->get('tbl_pengaturan')->row();
                    $sql_doc    = $this->db->where('id_user', $label->id_dokter)->get('tbl_m_karyawan')->row();
                    
                    $cetak[] = array(
                        'tgl'           => $this->tanggalan->tgl_indo($label->tgl_masuk),
                        'trx'           => $label->trx,
                        'rm'            => $label->kode_dpn.''.$label->kode,
                        'nama'          => $label->nama_pgl,
                        'tgl_lahir'     => $this->tanggalan->tgl_indo($label->tgl_lahir).' ('.$this->tanggalan->usia_lkp($label->tgl_lahir).')',
                        'jns_klm'       => general::jns_klm($label->jns_klm),
                        'alamat'        => $label->alamat,
                        'dokter'        => (!empty($sql_doc->nama_dpn) ? $sql_doc->nama_dpn.' ' : '').$sql_doc->nama.(!empty($sql_doc->nama_blk) ? ', '.$sql_doc->nama_blk : ''),
                        'poli'          => $label->poli,
                        'judul'         => $setting->judul
                    );
                }

                echo json_encode($cetak);
            }
        } else {
            $errors = $this->ion_auth->messages();
            $this->session->set_flashdata('login', '<div class="alert alert-danger">Authentifikasi gagal, silahkan login ulang!!</div>');
            redirect();
        }
    }
}
