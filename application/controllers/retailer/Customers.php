<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('retailer/M_customers','customers');
			$this->load->model('Common');
			if (!retailer()) {
				redirect('users/retailer');
			}
	}
	public function index()
	{
		$this->load->view('retailer/customers/view');
	}
	
	public function get()
	{
        $result = $this->customers->make_datatables();
		$data = array();
		foreach ($result as $res) {
			$sub_array = array();
			$sub_array[] = '<img src="' . base_url() . $res->customer_image . '" height="100px">';
			$sub_array[] = $res->name_english.'<br>'.$res->name_arabic;
			$sub_array[] = $res->customer_phone;
			$sub_array[] = $res->customer_email;
// 			$sub_array[] = $res->customer_address;
			if($res->status=='1')
			{
				$status = 'Active';
			}
			else
			{
				$status = 'Blocked';
			}
			$sub_array[] = $status;
			$data[] = $sub_array;
		}

		$output = array(
			"draw"   => intval($_POST['draw']),
			"recordsTotal" => $this->customers->get_all_data(),
			"recordsFiltered" => $this->customers->get_filtered_data(),
			"data" => $data
		);
		echo json_encode($output);
	}

	public function addCustomer()
	{
		date_default_timezone_set('Asia/Kolkata');
        $timestamp = date('Y-m-d H:i:s');
        $user      = $this->session->userdata['retailer'];
        $retailer_id= $user['retailer_id'];
       
		$name         = $this->security->xss_clean($this->input->post('name'));
		$name_arabic  = $this->security->xss_clean($this->input->post('name_arabic'));
        $phone        = $this->security->xss_clean($this->input->post('phone'));
        $email        = $this->security->xss_clean($this->input->post('email'));
        $address      = $this->security->xss_clean($this->input->post('address'));
        
        if($_FILES['image']['name'] != '')
        {
			$file     = $_FILES['image'];
			$tar      = "uploads/admin/customers/";
			$rand     = date('Ymd').mt_rand(1001,9999);
			$tar_file = $tar . $rand . basename($file['name']);
			move_uploaded_file($file["tmp_name"], $tar_file);
		}
		else
		{
		    $tar_file = 'uploads/admin/customers/user.png';
		}
        
	    $array        = [
							'name_english'         => $name,
						    'name_arabic'          => $name_arabic, 
							'customer_phone'       => $phone,
							'customer_email'       => $email,
							'customer_address'     => $address,
							'customer_image'       => $tar_file,
							'status'               => '1',
							'added_by'             => 'retailer',
							'added_retailer'       => $retailer_id,
							'timestamp'            => $timestamp
				        ];
		if ($this->Common->insert('customers',$array)) 
		{
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'New customer added..!');

			redirect('retailer/customers');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add customer..!');

			redirect('retailer/customers');
		}
	}

	public function checkCustomer()
	{
		$phone = $this->input->post('phone');
		$check_phone = $this->Common->get_details('customers',array('customer_phone'=>$phone));
		if($check_phone->num_rows()>0)
		{
			$data ='1';
		}
		else
		{
			$data ='0';
		}
		print_r($data);
	}

	
}
?>
