<?php
defined('BASEPATH') OR exit('No direct script access allowed');

  class Password extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->model('Common');
	}
	
	public function change()
	{   
	    $agency_id   = $this->security->xss_clean($this->input->post('agency_id'));
	    $old         = md5($this->security->xss_clean($this->input->post('old_password')));
	    $new         = md5($this->security->xss_clean($this->input->post('New_password')));
	    
		$checkPassword = $this->Common->get_details('agencies',array('agency_id' => $agency_id,'agency_password'=>$old));
		if($checkPassword->num_rows()>0)
		{
		    $array   = [
		                'agency_password'  => $new
		              ];
		    if($this->Common->update('agency_id',$agency_id,'agencies',$array))
		    {
    		    $return  = [
    		                   'status'   => true,
    		                   'message'  => 'success'
    		               ];
		    }
		    else
		    {
		        $return  = [
    		                   'status'   => false,
    		                   'message'  => 'Failed to update password'
    		               ];
		    }
		}
        else
        {
            $return  = [
                          'status'  => false,
                          'message' => 'Old password is not correct'
                       ];
        }

		print_r(json_encode($return));
	}
	
	public function agency_check()
	{
	    $agency_code   = $this->security->xss_clean($this->input->post('agency_code'));
	    $checkCode     = $this->Common->get_details('agencies',array('agency_code'=>$agency_code));
	    if($checkCode->num_rows()>0)
	    {   
	        $agency_id = $checkCode->row()->agency_id;
	        $return  = [
	                      'status'   => true,
	                      'message'  => 'success',
	                      'data'     => [
	                                       'agency_code' => $agency_code,
	                                       'agency_id'   => $agency_id
	                                    ]
	                   ];
	    }
	    else
	    {
	        $return = [
	                      'status'   => false,
	                      'message'  => 'Invalid agency code',
	                      'data'     => array()
	                  ];
	    }
	    print_r(json_encode($return));
	}
	
	public function reset_password()
	{
	    $agency_id   = $this->security->xss_clean($this->input->post('agency_id'));
	    $password    = md5($this->security->xss_clean($this->input->post('password')));
	    
	    $array       = [
		                'agency_password'  => $password
		              ];
	    if($this->Common->update('agency_id',$agency_id,'agencies',$array))
	    {
		    $return  = [
		                   'status'   => true,
		                   'message'  => 'success'
		               ];
	    }
	    else
	    {
	        $return  = [
		                   'status'   => false,
		                   'message'  => 'Failed to update password'
		               ];
	    }
	    print_r(json_encode($return));
	}

}
