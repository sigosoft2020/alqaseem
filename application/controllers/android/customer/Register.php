<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->model('Common');
	}
	
    public function index()
	{   
	    date_default_timezone_set("Asia/Calcutta");
	    $timestamp = date('Y-m-d H:i:s');
	    
		$emailCheck = true;
		$mobileCheck = true;

		$name     = $this->security->xss_clean($this->input->post('name'));
		$email    = $this->security->xss_clean($this->input->post('email'));
		$password = $this->security->xss_clean($this->input->post('password'));
		$mobile   = $this->security->xss_clean($this->input->post('mobile'));
		$address  = $this->security->xss_clean($this->input->post('address'));
		$image    = $this->security->xss_clean($this->input->post('image'));
		
		if ($image != '') 
        {  
      	    $url  = FCPATH.'uploads/admin/customers/';
			$rand = date('Ymd').mt_rand(1001,9999);
			$userpath = $url.$rand.'.png';
			$path = "uploads/admin/customers/".$rand.'.png';
			file_put_contents($userpath,base64_decode($image));
			
		    $data = [
            			'name_english'       => $name,
            			'customer_email'     => $email,
            			'customer_password'  => md5($password),
            			'customer_address'   => $address,
            			'customer_phone'     => $mobile,
            			'customer_image'     => $path,
            			'status'             => '1',
            			'added_by'           => 'customer',
            			'timestamp'          => $timestamp
            		];
		}
		else
		{
           $data =  [
            			'name_english'       => $name,
            			'customer_email'     => $email,
            			'customer_password'  => md5($password),
            			'customer_address'   => $address,
            			'customer_phone'     => $mobile,
            			'customer_image'     => 'uploads/admin/customers/user.png',
            			'status'             => '1',
            			'added_by'           => 'customer',
            			'timestamp'          => $timestamp
        		    ];
		}
		
		$checkMobile = $this->Common->get_details('customers',array('customer_phone' => $mobile))->num_rows();
		$checkEmail = $this->Common->get_details('customers',array('customer_email' => $email))->num_rows();
		if ( $checkMobile > 0 ) 
		{
			$mobileCheck = false;
		}
		if ( $checkEmail > 0 ) 
		{
			$emailCheck = false;
		}
		if($mobileCheck && $emailCheck)
		{
		    if ($id = $this->Common->insert('customers',$data)) 
		    {
        		  $return = [
            				   'message' => 'success',
            				   'mobile'  => $mobileCheck,
            				   'email'   => $emailCheck,
            				   'data'    => [
            				                  'user_id' => $id,
            				                  'name'    => $name,
            				                  'email'   => $email,
            				                  'phone'   => $mobile,
            				                  'address' => $address,
            				                  'image'   => $data['customer_image']
            				                ]
            				];
			}
			else 
			{
				$return = [
        					'message' => 'failed',
        					'mobile'  => $mobileCheck,
        					'email'   => $emailCheck,
        					'data'    => []
        				  ];
			}
		}
		else 
		{
			   $return = [
            				'message' => 'failed',
            				'mobile'  => $mobileCheck,
            				'email'   => $emailCheck,
            				'data'    => []
            			 ];
		}
		print_r(json_encode($return));
	}

}
