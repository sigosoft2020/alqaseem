<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->model('Common');
			$this->load->model('android/M_customers','customers');
			$this->load->model('android/M_order','order');
	}
	
    public function index()
	{
	    $customers = $this->customers->getCustomers();
	    $return    = [
	                    'message'   => 'success',
	                    'customers' => $customers
	                 ];
	    print_r(json_encode($return));
	}
	
	public function add()
	{   
	    date_default_timezone_set("Asia/Calcutta");
	    $timestamp = date('Y-m-d H:i:s');
	    
		$emailCheck = true;
		$mobileCheck = true;
        
        $agency_id= $this->security->xss_clean($this->input->post('agency_id'));
		$name     = $this->security->xss_clean($this->input->post('name'));
		$email    = $this->security->xss_clean($this->input->post('email'));
		$mobile   = $this->security->xss_clean($this->input->post('mobile'));
		$address  = $this->security->xss_clean($this->input->post('address'));
	
	    $data = [
        			'name_english'       => $name,
        			'customer_email'     => $email,
        			'customer_address'   => $address,
        			'customer_phone'     => $mobile,
        			'customer_image'     => 'uploads/admin/customers/user.png',
        			'status'             => '1',
        			'added_by'           => 'agency',
        			'added_agency'       => $agency_id,
        			'timestamp'          => $timestamp
        		];
	   
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
	
	public function pastOrders()
	{
	    $customer_id = $this->input->post('customer_id');
	    $orders      = $this->customers->getPastOrders($customer_id);
	    foreach($orders as $order)
	    {
	        $order->products = $this->order->getOrderedProducts($order->order_id);
	        $order->address  = $this->Common->get_details('ordered_address',array('order_id' => $order->order_id))->row();
	    }
	    
	    $return   = [
	                   'message'  => 'success',
	                   'orders'   => $orders
	                ];
	   print_r(json_encode($return));            
	}
	
	public function serachCustomer()
	{
	    $key       = $this->input->post('key');
	    $keyword   = str_replace(" ", "%", $key);
	    $customers = $this->customers->serachCustomer($keyword);
	    
	    $return = [
        			'message'   => 'success',
        			'customers' => $customers
        		  ];
		
		print_r(json_encode($return));
	}
	
	public function edit()
	{
	    $customer_id = $this->security->xss_clean($this->input->post('customer_id'));
	    $name        = $this->security->xss_clean($this->input->post('name'));
		$mobile      = $this->security->xss_clean($this->input->post('mobile'));
		$address     = $this->security->xss_clean($this->input->post('address'));
		
		$mobileCheck = true;
		
		$checkMobile = $this->Common->get_details('customers',array('customer_phone' => $mobile,'customer_id!='=>$customer_id));
        if($checkMobile->num_rows()>0)
        {
            $mobileCheck = false;
        }
  
        if($mobileCheck)
        {
  		     $details = [
                			'name_english'   => $name,
                			'customer_phone' => $mobile,
                			'customer_address'=> $address
	                    ];
          
          	if ($this->Common->update('customer_id',$customer_id,'customers',$details)) 
    		{
    		    $customer = $this->Common->get_details('customers',array('customer_id'=>$customer_id))->row();
    			$return = [
            				'message'     => 'success',
            				'mobileCheck' => $mobileCheck,
                            'data'        => [
                                               'user_id' => $customer_id,
            				                   'name'    => $customer->name_english,
            				                   'phone'   => $customer->customer_phone,
            				                   'address' => $customer->customer_address
                                             ]
            			  ];
    		}
    		else 
    		{
    			$return = [
            				'message'      => 'failed',
            				'mobileCheck'  => $mobileCheck,
                            'data'         => []
            			  ];
    		}
    	 }
         else
         {
            $return = [
                          'message'     => 'failed',
                          'mobileCheck' => $mobileCheck,
                          'data'        => []
                      ];
         }

		print_r(json_encode($return));
	}
	
	public function serachCustomerByNameOrMobile()
	{
	    $key       = $this->input->post('key');
	    $keyword   = str_replace(" ", "%", $key);
	    $customers = $this->customers->serachCustomerByNameOrMobile($keyword);
	    
	    $return = [
        			'message'   => 'success',
        			'customers' => $customers
        		  ];
		
		print_r(json_encode($return));
	}
}
