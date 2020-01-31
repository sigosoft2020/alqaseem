<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('admin/M_dashboard','dash');
			$this->load->model('Common');
			if (!admin()) {
				redirect('users/admin');
			}
	}
	public function index()
	{
		$admin    = $this->session->userdata['admin'];
		$admin_id = $admin['admin_id'];
        $current  = date('Y-m-d');
        
        $data['new_orders']= $this->dash->getNewOrders($current);
        $data['pending']   = $this->dash->getPendingOrders($current);
        $data['completed'] = $this->dash->getCompletedOrders($current);
        $data['cancelled'] = $this->dash->getCancelledOrders($current);
        
        $data['new_requests']      = $this->dash->getNewRequestsToday($current);
        $data['approved_requests'] = $this->dash->getApprovedRequestsToday($current);
        $data['completed_requests']= $this->dash->getCompletedRequestsToday($current);
        $data['cancelled_requests']= $this->dash->getCancelledRequestsToday($current);
        
        $data['retail_sales']      = $this->dash->getRetailSalesToday($current);
        $data['new_customers']     = $this->dash->getCustomersToday($current);
        $data['new_agencies']      = $this->dash->getAgenciesToday($current);
        $data['expense_today']     = $this->dash->getExpenseToday($current);
        
        $data['customers'] = $this->dash->getCustomers();
        $data['requests']  = $this->dash->getRequests();
        $data['retails']   = $this->dash->getRetails();
        $data['orders']    = $this->dash->getOrders();

		$this->load->view('admin/dashboard/dashboard',$data);
	}
	
	
}
?>
