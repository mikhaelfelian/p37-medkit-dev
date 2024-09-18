<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php

class akses extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    // Cek Sudah login belun
    function aksesLogin() {
        if (!$this->ion_auth->logged_in()):
            return FALSE;
        else:
            $user   = $this->ion_auth->user()->row();
            $grup   = $this->ion_auth->get_users_groups()->row();
            
            if ($grup->name != 'pasien'):
                return TRUE;
            else:
                return FALSE;
            endif;
        endif;
    }
    
    // Cek Sudah login belun ebagai pasien
    function aksesLoginP() {
        if (!$this->ion_auth->logged_in()):
            return FALSE;
        else:
            $user   = $this->ion_auth->user()->row();
            $grup   = $this->ion_auth->get_users_groups()->row();
            
            if ($grup->name == 'pasien'):
                return TRUE;
            else:
                return FALSE;
            endif;
        endif;
    }
    
    // Cek Root
    function aksesRoot() {
        if (!$this->ion_auth->is_admin()):
            return TRUE;
        else:
            return FALSE;
        endif;
    }
    

    function hakSA() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'superadmin'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function hakOwner() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'owner'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function hakOwner2() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'owner2'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function hakAdminM() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'adminm'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function hakAdmin() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'admin'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function hakPurchasing() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'purchasing'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function hakSales() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'sales'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function hakGudang() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'gudang'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function hakKasir() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'kasir'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function hakDokter() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'dokter'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function hakPerawat() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'perawat' OR $grup->name == 'perawat_ranap'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function hakFarmasi() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'farmasi'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function hakAnalis() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'analis'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function hakRad() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'radiografer'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function hakGizi() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'gizi'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function hakFisioterapi() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'fisioterapi'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function hakPasien() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups()->row();
        
        if ($grup->name == 'pasien'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }
    
    function menuAksesTop() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups($user->id)->row();
        
        switch ($grup->name){
            case 'superadmin':
                $this->load->view('admin-lte-3/includes/menu/menu_top_sadmin');
                break;
            
            case 'owner':
                $this->load->view('admin-lte-3/includes/menu/menu_top_owner');
                break;
            
            case 'owner2':
                $this->load->view('admin-lte-3/includes/menu/menu_top_owner2');
                break;
            
            case 'adminm':
                $this->load->view('admin-lte-3/includes/menu/menu_top_admin');
                break;
            
            case 'admin':
                $this->load->view('admin-lte-3/includes/menu/menu_top_admin');
                break;
            
            case 'sales':
                $this->load->view('admin-lte-3/includes/menu/menu_top_sales');
                break;
            
            case 'purchasing':
                $this->load->view('admin-lte-3/includes/menu/menu_top_purchasing');
                break;
            
            case 'gudang':
                $this->load->view('admin-lte-3/includes/menu/menu_top_gudang');
                break;
            
            case 'kasir':
                $this->load->view('admin-lte-3/includes/menu/menu_top_kasir');
                break;
            
            default:
                $this->load->view('admin-lte-3/includes/menu/menu_top_sadmin');
                break;
        }
    }
    
    function notifAkses() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups($user->id)->row();
        
        switch ($grup->name){
//            case 'superadmin':
//                $this->load->view('admin-lte-2/includes/menu/notif_sadmin');
//                break;
//            
//            case 'owner':
//                $this->load->view('admin-lte-2/includes/menu/notif_owner');
//                break;
//            
//            case 'owner2':
//                $this->load->view('admin-lte-2/includes/menu/notif_owner2');
//                break;
//            
//            case 'adminm':
//                $this->load->view('admin-lte-2/includes/menu/notif_admin');
//                break;
//            
//            case 'admin':
//                $this->load->view('admin-lte-2/includes/menu/notif_admin');
//                break;
//            
//            case 'purchasing':
//                $this->load->view('admin-lte-2/includes/menu/notif_purchasing');
//                break;
//            
//            case 'gudang':
//                $this->load->view('admin-lte-2/includes/menu/notif_gudang');
//                break;
//            
//            case 'perawat':
//                $this->load->view('admin-lte-2/includes/menu/notif_sales');
//                break;
//            
//            case 'kasir':
//                $this->load->view('admin-lte-2/includes/menu/notif_kasir');
//                break;
            
            case 'dokter':
                $this->load->view('admin-lte-3/includes/menu/notif_dokter');
                break;
            
//            case 'farmasi':
//                $this->load->view('admin-lte-2/includes/menu/notif_sales');
//                break;
//            
//            case 'analis':
//                $this->load->view('admin-lte-2/includes/menu/notif_sales');
//                break;
//            
//            case 'radiografer':
//                $this->load->view('admin-lte-2/includes/menu/notif_sales');
//                break;
//            
//            default:
//                $this->load->view('admin-lte-2/includes/menu/notif_sadmin');
//                break;
        }
    }
    
    function menuAkses() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups($user->id)->row();
        
        switch ($grup->name){
            case 'superadmin':
                $this->load->view('admin-lte-2/includes/menu/menu_sadmin');
                break;
            
            case 'owner':
                $this->load->view('admin-lte-2/includes/menu/menu_owner');
                break;
            
            case 'owner2':
                $this->load->view('admin-lte-2/includes/menu/menu_owner2');
                break;
            
            case 'adminm':
                $this->load->view('admin-lte-2/includes/menu/menu_admin');
                break;
            
            case 'admin':
                $this->load->view('admin-lte-2/includes/menu/menu_admin');
                break;
            
            case 'sales':
                $this->load->view('admin-lte-2/includes/menu/menu_sales');
                break;
            
            case 'purchasing':
                $this->load->view('admin-lte-2/includes/menu/menu_purchasing');
                break;
            
            case 'gudang':
                $this->load->view('admin-lte-2/includes/menu/menu_gudang');
                break;
            
            case 'kasir':
                $this->load->view('admin-lte-2/includes/menu/menu_kasir');
                break;
            
            default:
                $this->load->view('admin-lte-2/includes/menu/menu');
                break;
        }
    }
        
    function menuAksesSidebar() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups($user->id)->row();
        
        switch ($grup->name){
            case 'superadmin':
                $this->load->view('admin-lte-3/includes/menu/menu_side_sadmin');
                break;
            
            case 'owner':
                $this->load->view('admin-lte-3/includes/menu/menu_side_owner');
                break;
            
            case 'owner2':
                $this->load->view('admin-lte-3/includes/menu/menu_side_owner2');
                break;
            
            case 'adminm':
                $this->load->view('admin-lte-3/includes/menu/menu_side_admin');
                break;
            
            case 'admin':
                $this->load->view('admin-lte-3/includes/menu/menu_side_admin');
                break;
            
            case 'sales':
                $this->load->view('admin-lte-3/includes/menu/menu_side_sales');
                break;
            
            case 'purchasing':
                $this->load->view('admin-lte-3/includes/menu/menu_side_purchasing');
                break;
            
            case 'gudang':
                $this->load->view('admin-lte-3/includes/menu/menu_side_gudang');
                break;
            
            case 'kasir':
                $this->load->view('admin-lte-3/includes/menu/menu_side_kasir');
                break;
            
            default:
                $this->load->view('admin-lte-3/includes/menu/menu_side_sadmin');
                break;
        }
    }
    
    function contentAkses() {
        $user = $this->ion_auth->user()->row();
        $grup = $this->ion_auth->get_users_groups($user->id)->row();
        
        switch ($grup->name){
            case 'superadmin':
                $this->load->view('admin-lte-2/includes/menu/content_sadmin');
                break;
            
            case 'owner':
                $this->load->view('admin-lte-2/includes/menu/content_owner');
                break;
            
            case 'owner2':
                $this->load->view('admin-lte-2/includes/menu/content_owner2');
                break;
            
            case 'adminm':
                $this->load->view('admin-lte-2/includes/menu/content_admin');
                break;
            
            case 'admin':
                $this->load->view('admin-lte-2/includes/menu/content_admin');
                break;
            
            case 'sales':
                $this->load->view('admin-lte-2/includes/menu/content_sales');
                break;
            
            case 'purchasing':
                $this->load->view('admin-lte-2/includes/menu/content_purchasing');
                break;
            
            case 'gudang':
                $this->load->view('admin-lte-2/includes/menu/content_gudang');
                break;
            
            case 'kasir':
                $this->load->view('admin-lte-2/includes/menu/content_kasir');
                break;
            
            case 'pasien':
                $this->load->view('admin-lte-2/includes/menu/content_pasien');
                break;
            
            default:
                $this->load->view('admin-lte-2/includes/menu/content');
                break;
        }
    }
}
