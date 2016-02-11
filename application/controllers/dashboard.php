<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct(){
        parent::__construct();

        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->model('users_model');
        $this->load->model('merchant_model');
        if (!$this->session->userdata('admin')){ 
            redirect('login');		
        }
	date_default_timezone_set('Asia/Kolkata');
    }
	
    public function index() {
		$data['role_details'] = $this->session->userdata('role_details');
		if($data['role_details']->merchants_list == 1){
        
		$admin_id = $this->session->userdata('admin')->logged_id;
		
        $data['log_info'] = $this->users_model->logingInfo($admin_id);
        $data['merchant_list'] = $this->merchant_model->getAllMerchants();
        $this->load->view('admin/merchants/merchant_show', $data);
		}else{
			$this->load->view('admin/access', $data);
		}
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
