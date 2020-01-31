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
	    $code     = $this->security->xss_clean($this->input->post('agency_code'));
		$pass     = $this->security->xss_clean($this->input->post('password'));
		$password = md5($pass);
		$fcm_token= $this->security->xss_clean($this->input->post('fcm_token'));

		$details =  [
            			'agency_code'      => $code,
            			'agency_password'  => $password,
            			'agency_status'    => '1'
            		];

		$check = $this->Common->get_details('agencies',$details);
		if ($check->num_rows() > 0 ) 
		{  
		    $fcm     = [
		                 'fcm_token'  => $fcm_token
		               ];
		    if($this->Common->update('agency_id',$check->row()->agency_id,'agencies',$fcm))
		    {
    			$return = [
            				  'message'    => 'success',
            				  'agency_id'  => $check->row()->agency_id,
            				  'name'       => $check->row()->agency_name,
            				  'name_arabic'=> $check->row()->name_arabic,
            				  'mobile'     => $check->row()->agency_phone,
            				  'agency_code'=> $check->row()->agency_code,
            				  'staff_name' => $check->row()->agency_staff,
            				  'staff_name_arabic'=> $check->row()->staff_arabic,
            				  'latitude'  => $check->row()->latitude,
            				  'logitude'  => $check->row()->longitude,
            				  'vehicle_number'=> $check->row()->vehicle_number,
            				  'image'     => $check->row()->agency_image,
            				  'status'    => $check->row()->agency_status,
            				  'fcm_token' => $fcm_token,
            			  ];
		    }
		    else
		    {
		        $return = [
		                     'message'    => 'failed',
		                     'agency'     => array()
		                  ];
		    }
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
