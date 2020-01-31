<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->model('Common');
	}
	
    public function index()
	{   
	    $customer_id = $this->security->xss_clean($this->input->post('customer_id'));
	    $name        = $this->security->xss_clean($this->input->post('name'));
        $email       = $this->security->xss_clean($this->input->post('email'));
		$mobile      = $this->security->xss_clean($this->input->post('mobile'));
		$image       = $this->security->xss_clean($this->input->post('profile'));
		$address     = $this->security->xss_clean($this->input->post('address'));
		
		$emailCheck = true;
		$mobileCheck = true;
		
		$checkMobile = $this->Common->get_details('customers',array('customer_phone' => $mobile,'customer_id!='=>$customer_id));
        $checkEmail  = $this->Common->get_details('customers',array('customer_email' => $email,'customer_id!='=>$customer_id));
        
        if($checkMobile->num_rows()>0)
        {
            $mobileCheck = false;
        }
  
        if($checkEmail->num_rows()>0)
        {
            $emailCheck = false;
        }
        
        if($emailCheck && $mobileCheck)
        {
          	if ($image != '') 
          	 {  
          	    $url  = FCPATH.'uploads/admin/customers/';
    			$rand = date('Ymd').mt_rand(1001,9999);
    			$userpath = $url.$rand.'.png';
    			$path = "uploads/admin/customers/".$rand.'.png';
    			file_put_contents($userpath,base64_decode($image));
    			
                $details = [
                			'name_english'   => $name,
                			'customer_phone' => $mobile,
                			'customer_email' => $email,
                			'customer_image' => $path,
                			'customer_address'=> $address
        		          ];
          	 }
          	 else
          	 {
          	     $details = [
                			'name_english'   => $name,
                			'customer_phone' => $mobile,
                			'customer_email' => $email,
                			'customer_address'=> $address
        		          ];
          	 }
          	 
          	if ($this->Common->update('customer_id',$customer_id,'customers',$details)) 
    		{
    		    $customer = $this->Common->get_details('customers',array('customer_id'=>$customer_id))->row();
    			$return = [
            				'message'     => 'success',
            				'mobileCheck' => $mobileCheck,
                            'emailCheck'  => $emailCheck,
                            'data'        => [
                                               'user_id' => $customer_id,
            				                   'name'    => $customer->name_english,
            				                   'email'   => $customer->customer_email,
            				                   'phone'   => $customer->customer_phone,
            				                   'image'   => $customer->customer_image,
            				                   'address' => $customer->customer_address
                                             ]
            			  ];
    		}
    		else 
    		{
    			$return = [
            				'message'      => 'failed',
            				'mobileCheck'  => $mobileCheck,
                            'emailCheck'   => $emailCheck,
                            'data'         => []
            			  ];
    		}
    	 }
         else
         {
            $return = [
                          'message'     => 'failed',
                          'mobileCheck' => $mobileCheck,
                          'emailCheck'  => $emailCheck,
                          'data'        => []
                      ];
         }

		print_r(json_encode($return));
	}

}
