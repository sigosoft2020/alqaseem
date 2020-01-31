<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupons extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->model('Common');
			$this->load->model('android/M_coupon','coupon');
	}
	
    public function index()
	{   
	    $customer_id    = $this->input->post('customer_id');
	    $customer_check = $this->Common->get_details('coupon_purchases',array('customer_id'=>$customer_id));
	    if($customer_check->num_rows()>0)
	    {
	        $coupons  = $this->coupon->getCoupons($customer_id);
	    }
	    else
	    {
	       $coupons  = array(); 
	    }
	    
	    $return   = [
	                  'message' => 'success',
	                  'data'    => $coupons
	                ];
		print_r(json_encode($return));
	}

}
