<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->model('Common');
	}
	
    public function index()
	{   
	    $mobile   = $this->security->xss_clean($this->input->post('mobile'));

		$check = $this->Common->get_details('customers',array('customer_phone'=>$mobile,'status'=>'1'));
		if ( $check->num_rows() > 0 ) 
		{
			$return = [
        				'message' => 'success',
        				'name'    => $check->row()->name_english,
        				'email'   => $check->row()->customer_email,
        				'mobile'  => $check->row()->customer_phone,
        				'user_id' => $check->row()->customer_id,
        				'image'   => $check->row()->customer_image,
        				'address' => $check->row()->customer_address
        			  ];
		}
		else 
		{
			$return = [
        				'message' => 'failed'
        			  ];
		}
		print_r(json_encode($return));
	}

}
