<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->model('Common');
	}
	
    public function index()
	{   
	    $customer_id = $this->security->xss_clean($this->input->post('customer_id'));
	    $details = $this->Common->get_details('customers',array('customer_id' => $customer_id))->row();
	    $return = [
        	        'message' => 'success',
        	        'customer' => [
        	                         'id'      => $details->customer_id,
        	                         'name'    => $details->name_english,
        	                         'phone'   => $details->customer_phone,
        	                         'email'   => $details->customer_email,
        	                         'image'   => $details->customer_image,
        	                         'address' => $details->customer_address
        	                      ]
        	      ];
	    print_r(json_encode($return));
	}

}
