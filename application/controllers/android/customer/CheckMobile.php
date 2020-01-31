<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CheckMobile extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->model('Common');

	}
    public function index()
	{
		$mobile = $this->security->xss_clean($this->input->post('mobile'));

		$array = [
        			'customer_phone' => $mobile
        		 ];
        		 
		$check = $this->Common->get_details('customers',$array);
		if ($check->num_rows() > 0) {
			$return = [
				'message' => 'success',
				'customer_id' => $check->row()->customer_id
			];
		}
		else {
			$return = [
				'message' => 'failed'
			];
		}
		print_r(json_encode($return));
	}

}
