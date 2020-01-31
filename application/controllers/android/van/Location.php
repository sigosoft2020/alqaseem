<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->model('Common');
	}
	
    public function add()
	{
	    $agency_id  = $this->security->xss_clean($this->input->post('agency_id'));
		$lat        = $this->security->xss_clean($this->input->post('lat'));
		$lon        = $this->security->xss_clean($this->input->post('long'));
		
		date_default_timezone_set("Asia/Calcutta"); 
		$date       = date('Y-m-d');
		$time       = date('h:i A');

		$array = [
        			'agency_id' => $agency_id,
        			'latitude'  => $lat,
        			'longitude' => $lon,
        			'date'      => $date,
        			'time'      => $time,
        			'timestamp' => date('Y-m-d H:i:s')
        		 ];

		if ($this->Common->insert('locations',$array)) 
		{
			$return = [
				        'message' => 'success'
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
