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
	    $agency_id = $this->security->xss_clean($this->input->post('agency_id'));
	    $check     = $this->Common->get_details('agencies',array('agency_id' => $agency_id));
	    if($check->num_rows()>0)
	    {
	         $return    = [
                	          'message' => 'success',
                	          'agency'  => [
                	                          'agency_id'     => $check->row()->agency_id,
                            				  'name'          => $check->row()->agency_name,
                            				  'name_arabic'   => $check->row()->name_arabic,
                            				  'mobile'        => $check->row()->agency_phone,
                            				  'agency_code'   => $check->row()->agency_code,
                            				  'staff_name'    => $check->row()->agency_staff,
                            				  'staff_name_arabic'=> $check->row()->staff_arabic,
                            				  'latitude'      => $check->row()->latitude,
                            				  'logitude'      => $check->row()->longitude,
                            				  'vehicle_number'=> $check->row()->vehicle_number,
                            				  'image'         => $check->row()->agency_image,
                            				  'status'        => $check->row()->agency_status
                	                      ]
        	            ];
	    }
	    else
	    {
	        $return    = [
	                          'message' => 'failed',
	                          'agency'  => array()
	                     ];  
	    }
	   
	    print_r(json_encode($return));
	}
	
	public function update()
	{   
	    $agency_id   = $this->security->xss_clean($this->input->post('agency_id'));
	    $name        = $this->security->xss_clean($this->input->post('name'));
	    $name_arabic = $this->security->xss_clean($this->input->post('name_arabic'));
        $agency_code = $this->security->xss_clean($this->input->post('agency_code'));
        $vehicle_no  = $this->security->xss_clean($this->input->post('vehicle_no'));
        $staff_name  = $this->security->xss_clean($this->input->post('staff_name'));
        $staff_name_arbic = $this->security->xss_clean($this->input->post('staff_name_arabic'));
		$mobile      = $this->security->xss_clean($this->input->post('mobile'));
		$image       = $this->security->xss_clean($this->input->post('image'));
		
		$codeCheck   = true;
		$mobileCheck = true;
		
		$checkMobile = $this->Common->get_details('agencies',array('agency_phone' => $mobile,'agency_id!='=>$agency_id));
        $checkCode   = $this->Common->get_details('agencies',array('agency_code' => $agency_code,'agency_id!='=>$agency_id));
        
        if($checkMobile->num_rows()>0)
        {
            $mobileCheck = false;
        }
  
        if($checkCode->num_rows()>0)
        {
            $codeCheck = false;
        }
        
        if($codeCheck && $mobileCheck)
        {
          	if ($image != '') 
          	 {  
          	    $url  = FCPATH.'uploads/admin/agency/';
    			$rand = date('Ymd').mt_rand(1001,9999);
    			$userpath = $url.$rand.'.png';
    			$path = "uploads/admin/agency/".$rand.'.png';
    			file_put_contents($userpath,base64_decode($image));
    			
                $details = [
                			'agency_name'    => $name,
                			'name_arabic'    => $name_arabic,
                			'agency_code'    => $agency_code,
                			'agency_phone'   => $mobile,
                			'vehicle_number' => $vehicle_no,
                			'agency_staff'   => $staff_name,
                			'staff_arabic'   => $staff_name_arbic,
                			'agency_image'   => $path
        		          ];
          	 }
          	 else
          	 {
          	     $details = [
                			'agency_name'    => $name,
                			'name_arabic'    => $name_arabic,
                			'agency_code'    => $agency_code,
                			'agency_phone'   => $mobile,
                			'vehicle_number' => $vehicle_no,
                			'agency_staff'   => $staff_name,
                			'staff_arabic'   => $staff_name_arbic,
        		          ];
          	 }
          	 
          	if ($this->Common->update('agency_id',$agency_id,'agencies',$details)) 
    		{
    		    $agency = $this->Common->get_details('agencies',array('agency_id'=>$agency_id));
    			$return = [
            				'message'     => 'success',
            				'mobileCheck' => $mobileCheck,
                            'codeCheck'   => $codeCheck,
                            'data'        => [
                                               'agency_id'     => $agency->row()->agency_id,
                            				   'name'          => $agency->row()->agency_name,
                            				   'name_arabic'   => $agency->row()->name_arabic,
                            				   'mobile'        => $agency->row()->agency_phone,
                            				   'agency_code'   => $agency->row()->agency_code,
                            				   'staff_name'    => $agency->row()->agency_staff,
                            				   'staff_name_arabic'=> $agency->row()->staff_arabic,
                            				   'latitude'      => $agency->row()->latitude,
                            				   'logitude'      => $agency->row()->longitude,
                            				   'vehicle_number'=> $agency->row()->vehicle_number,
                            				   'image'         => $agency->row()->agency_image,
                            				   'status'        => $agency->row()->agency_status
                                             ]
            			  ];
    		}
    		else 
    		{
    			$return = [
            				'message'      => 'failed',
            				'mobileCheck'  => $mobileCheck,
                            'codeCheck'    => $codeCheck,
                            'data'         => []
            			  ];
    		}
    	 }
         else
         {
            $return = [
                          'message'     => 'failed',
                          'mobileCheck' => $mobileCheck,
                          'codeCheck'   => $codeCheck,
                          'data'        => []
                      ];
         }

		print_r(json_encode($return));
	}

}
