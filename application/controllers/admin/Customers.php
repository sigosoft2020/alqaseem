<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('admin/M_customers','customers');
			$this->load->model('Common');
			if (!admin()) {
				redirect('users/admin');
			}
	}

	public function index()
	{
		$this->load->view('admin/customers/view');
	}

	public function add()
	{
		$this->load->view('admin/customers/add');
	}

	public function get()
	{
        $result = $this->customers->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$sub_array[] = '<img src="' . base_url() . $res->customer_image . '" height="100px">';
			$sub_array[] = $res->name_english.'<br>'.$res->name_arabic;
			// $sub_array[] = $res->name_arabic;
			$sub_array[] = $res->customer_phone;
			$sub_array[] = $res->customer_email;
// 			$sub_array[] = $res->customer_address;
			// $sub_array[] = '';
		    $sub_array[] = '<a class="btn btn-primary" style="font-size:12px;" href="' . site_url('admin/customers/orders/'.$res->customer_id) . '" >View</a>';
		    $sub_array[] = '';
		    if($res->status=='1')
			{
				$status = 'Active';
				$action = '<a class="btn btn-danger" style="font-size:12px;" href="' . site_url('admin/customers/disable/'.$res->customer_id) . '" >Disable</a>';
			}
			else
			{
				$status = 'Blocked';
				$action = '<a class="btn btn-success" style="font-size:12px;" href="' . site_url('admin/customers/enable/'.$res->customer_id) . '" >Enable</a>';
			}
			$sub_array[] = $status.'<br>'.$action;
		    $sub_array[] = '<a class="btn btn-primary" style="font-size:12px;" href="' . site_url('admin/customers/edit/'.$res->customer_id) . '" >Edit</a>';

			$data[]      = $sub_array;
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
        
		$name         = $this->security->xss_clean($this->input->post('name'));
		$name_arabic  = $this->security->xss_clean($this->input->post('name_arabic'));
        $phone        = $this->security->xss_clean($this->input->post('phone'));
        $email        = $this->security->xss_clean($this->input->post('email'));
        $address      = $this->security->xss_clean($this->input->post('address'));
        $password     = $this->security->xss_clean($this->input->post('password'));

		$image     = $this->input->post('image');
		if($image!='')
		{
			$img       = substr($image, strpos($image, ",") + 1);

			$url      = FCPATH.'uploads/admin/customers/';
			$rand     = date('Ymd').mt_rand(1001,9999);
			$userpath = $url.$rand.'.png';
			$path     = "uploads/admin/customers/".$rand.'.png';
			file_put_contents($userpath,base64_decode($img));

			$array = [
						'name_english'   => $name,
						'name_arabic'    => $name_arabic, 
						'customer_phone' => $phone,
						'customer_image' => $path,
						'customer_email' => $email,
						'customer_address'=> $address,
						'customer_password'=> md5($password),
						'status'         => '1',
						'timestamp'      => $timestamp
			        ];
	    }
	    else
	    {
           $array = [
						'name_english'   => $name,
						'name_arabic'    => $name_arabic,
						'customer_phone' => $phone,
						'customer_image' => 'uploads/admin/customers/user.png',
						'customer_email' => $email,
						'customer_address'=> $address,
						'customer_password'=> md5($password),
						'status'         => '1',
						'timestamp'      => $timestamp
			        ];
	    }		        
		if ($this->Common->insert('customers',$array))
		{
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'New customer added..!');

			redirect('admin/customers');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add customer..!');

			redirect('admin/customers/add');
		}
	}
	
	public function edit($id)
	{
		$data['user'] = $this->Common->get_details('customers',array('customer_id'=>$id))->row();
	    $this->load->view('admin/customers/edit',$data);	
	}

	public function editCustomer()
	{
		date_default_timezone_set('Asia/Kolkata');
        $timestamp = date('Y-m-d H:i:s');
        
        $customer_id= $this->security->xss_clean($this->input->post('customer_id'));
		$name       = $this->security->xss_clean($this->input->post('name'));
		$name_arabic= $this->security->xss_clean($this->input->post('name_arabic'));
        $phone      = $this->security->xss_clean($this->input->post('phone'));
        $email      = $this->security->xss_clean($this->input->post('email'));
        $address    = $this->security->xss_clean($this->input->post('address'));
        
        $check_mobile   = $this->Common->get_details('customers',array('customer_phone' => $phone ,'customer_id!=' => $customer_id))->num_rows();
		$check_email     = $this->Common->get_details('customers',array('customer_email' => $email ,'customer_id!=' => $customer_id))->num_rows();
		if ($check_mobile > 0) 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Mobile number already exist..!');
            redirect('admin/customers/edit/'.$customer_id);
		}
		elseif ($check_email > 0) 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Email already exist..!');
            redirect('admin/customers/edit/'.$customer_id);
        }
		else
		{
			$image     = $this->input->post('image');
			if($image!='')
			{
				$img      = substr($image, strpos($image, ",") + 1);

				$url      = FCPATH.'uploads/admin/customers/';
				$rand     = date('Ymd').mt_rand(1001,9999);
				$userpath = $url.$rand.'.png';
				$path     = "uploads/admin/customers/".$rand.'.png';
				file_put_contents($userpath,base64_decode($img));

				$array = [
							'name_english'   => $name,
							'name_arabic'    => $name_arabic,
							'customer_phone' => $phone,
							'customer_image' => $path,
							'customer_email' => $email,
							'customer_address'=> $address
				        ];
		    }
		    else
		    {
	           $array = [
							'name_english'   => $name,
							'name_arabic'    => $name_arabic,
							'customer_phone' => $phone,
							'customer_email' => $email,
							'customer_address'=> $address
				        ];
		    }		        
			if ($this->Common->update('customer_id',$customer_id,'customers',$array))
			{
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Customer edited successfully..!');

				redirect('admin/customers');
			}
			else 
			{
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to edit customer..!');

				redirect('admin/customers/edit/'.$customer_id);
			}
	    }		
	}

	public function disable($id)
	{
		$array = [
			       'status' => '0'
		         ];
	
		if ($this->Common->update('customer_id',$id,'customers',$array))  {
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'Customer blocked successfully..!');
			redirect('admin/customers');
		}
		else {
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to blocked customer..!');
			redirect('admin/customers');
		}
	}

	public function enable($id)
	{
		$array = [
			       'status' => '1'
		         ];
	
		if ($this->Common->update('customer_id',$id,'customers',$array))
		{
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'Customer activated successfully..');
			redirect('admin/customers');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to activate customer..!');
			redirect('admin/customers');
	    }		
	}

	public function checkCustomer()
	{
		$phone       = $this->input->post('phone');
		$email       = $this->input->post('email');
		$check_phone = $this->Common->get_details('customers',array('customer_phone'=>$phone));
	    $check_email = $this->Common->get_details('customers',array('customer_email'=>$email));	
		if($check_phone->num_rows()>0)
		{
			$data ='1';
		}
		else if($check_email->num_rows()>0)
		{
			$data ='2';
		}
		else
		{
			$data ='0';
		}
		print_r($data);
	}

	public function orders($id)
	{   
		$orders = $this->customers->get_orders($id);
		foreach($orders as $order)
		{  
		   $agent_id            = $this->Common->get_details('agency_orders',array('order_id'=>$order->order_id))->row()->agency_id;	
		   $order->delivered_by = $this->Common->get_details('agencies',array('agency_id'=>$agent_id))->row()->agency_name;
		}
		$data['orders']    = $orders;
		$this->load->view('admin/customers/orders',$data);
	}


}
?>
