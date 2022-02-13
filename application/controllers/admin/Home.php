<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$admin = $this->session->userdata('admin');
		
		if(empty($admin)){
			$this->session->set_flashdata('msg','Your session has been expired');
			redirect(base_url().'admin/login/index');
		}
	}
    
    public function index()
	{
		// $this->load->helper('common_helper');
		$data['mainModule'] = 'dashboard';
		$data['subModule'] = '';
		$admin = $this->session->userdata('admin');
		$this->load->view('admin/dashboard',$data);
	}

}

/* End of file Home.php */
/* Location: ./application/controllers/admin/Home.php*/
?>
