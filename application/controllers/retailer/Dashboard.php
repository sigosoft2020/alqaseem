<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			// $this->load->model('admin/M_dashboardAdmin','dash');
			$this->load->model('Common');
			if (!retailer()) {
				redirect('users/retailer');
			}
	}
	public function index()
	{
		$retailer    = $this->session->userdata['retailer'];
		$retailer_id = $retailer['retailer_id'];
        $current     = date('Y-m-d');
        
		$this->load->view('retailer/dashboard/dashboard');
	}
	
	
}
?>
