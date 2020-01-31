<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Status extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('android/M_status','status');
			$this->load->model('Common');
	}

	public function index()
	{   
	    $agency_id = $this->input->post('agency_id');
	    $status    = $this->input->post('status');
	    if($status=='today')
	    {
		   $total_sale    = $this->status->getTodaysTotalSale($agency_id);
		   $total_swipe   = $this->status->getTodaysTotalSwipe($agency_id);
		   $total_cash    = $this->status->getTodaysTotalCash($agency_id);
		   $total_credit  = $this->status->getTodaysTotalCredit($agency_id);
		   $payment_received = $this->status->getTodaysTotalPayment($agency_id);
		   $payment_list     = $this->status->getTodaysTotalPaymentList($agency_id);
	    }
	    elseif($status=='week')
	    {
	        $total_sale    = $this->status->getWeeksTotalSale($agency_id);
	        $total_swipe   = $this->status->getWeeksTotalSwipe($agency_id);
	        $total_cash    = $this->status->getWeeksTotalCash($agency_id);
	        $total_credit  = $this->status->getWeeksTotalCredit($agency_id);
	        $payment_received = $this->status->getWeeksTotalPayment($agency_id);
	        $payment_list     = $this->status->getWeeksTotalPaymentList($agency_id);
	    }
	    elseif($status=='month')
	    {
	        $total_sale    = $this->status->getMonthTotalSale($agency_id);
	        $total_swipe   = $this->status->getMonthTotalSwipe($agency_id);
	        $total_cash    = $this->status->getMonthTotalCash($agency_id);
	        $total_credit  = $this->status->getMonthTotalCredit($agency_id);
	        $payment_received = $this->status->getMonthTotalPayment($agency_id);
	        $payment_list     = $this->status->getMonthTotalPaymentList($agency_id);
	    }
	    elseif($status=='lifetime')
	    {
	        $total_sale    = $this->status->getTotalSale($agency_id);
	        $total_swipe   = $this->status->getTotalSwipe($agency_id);
	        $total_cash    = $this->status->getTotalCash($agency_id);
	        $total_credit  = $this->status->getTotalCredit($agency_id);
	        $payment_received = $this->status->getTotalPayment($agency_id);
	        $payment_list     = $this->status->getTotalPaymentList($agency_id);
	    }
	    
	    $return   = [
	                   'message'    => 'success',
	                   'total_sale' => $total_sale,
	                   'total_swipe'=> $total_swipe,
	                   'total_cash' => $total_cash,
	                   'total_credit'=> $total_credit,
	                   'payment_received' => $payment_received,
	                   'payment_list' => $payment_list
	                ];
		print_r(json_encode($return));
	}
	
	
}
?>
