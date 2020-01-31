<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			// $this->load->model('admin/M_dashboardAdmin','dash');
			$this->load->model('Common');
			if (!warehouse()) {
				redirect('users/warehouse');
			}
	}
	public function index()
	{
		$warehouse    = $this->session->userdata['warehouse'];
		$warehouse_id = $warehouse['warehouse_id'];
        $current      = date('Y-m-d');
        
		$this->load->view('warehouse/dashboard/dashboard');
	}
	
	
}
?>
