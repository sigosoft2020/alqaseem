<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accessories extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('admin/M_accessories','accessories');
			$this->load->model('Common');
			if (!admin()) {
				redirect('users/admin');
			}
	}
	public function index()
	{
		$this->load->view('admin/accessories/view');
	}
	
	public function add()
	{
	   $this->load->view('admin/accessories/add');
	}

	public function get()
	{
        $result = $this->accessories->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$sub_array[] = '<img src="' . base_url() . $res->acc_image . '" height="100px">';
			$sub_array[] = $res->acc_name;
			$sub_array[] = $res->acc_price;
			$sub_array[] = $res->acc_vat;
			$sub_array[] = $res->acc_description;
			if($res->acc_status=='1')
			{
				$status = 'Active';
				$action = '<a class="btn btn-danger" style="font-size:12px;" href="' . site_url('admin/accessories/disable/'.$res->acc_id) . '" >Disable</a>';
			}
			else
			{
				$status = 'Blocked';
				$action = '<a class="btn btn-success" style="font-size:12px;" href="' . site_url('admin/accessories/enable/'.$res->acc_id) . '" >Enable</a>';
			}
			$sub_array[] = $status.'<br>'.$action;
			$sub_array[] = '<a class="btn btn-link" style="font-size:24px;color:blue" href="' . site_url('admin/accessories/edit/'.$res->acc_id) . '" ><i class="fa fa-pencil"></i></a>';
			$data[] = $sub_array;
		}

		$output = array(
							"draw"   => intval($_POST['draw']),
							"recordsTotal" => $this->accessories->get_all_data(),
							"recordsFiltered" => $this->accessories->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}
	
	public function addData()
	{
		date_default_timezone_set('Asia/Kolkata');
        $timestamp = date('Y-m-d H:i:s');
        
		$name         = $this->security->xss_clean($this->input->post('acc_name'));
        $description  = $this->security->xss_clean($this->input->post('acc_description'));
        $vat          = $this->security->xss_clean($this->input->post('acc_vat'));
        $price        = $this->security->xss_clean($this->input->post('acc_price'));

		$image     = $this->input->post('image');
		$img       = substr($image, strpos($image, ",") + 1);

		$url      = FCPATH.'uploads/admin/accessories/';
		$rand     = date('Ymd').mt_rand(1001,9999);
		$userpath = $url.$rand.'.png';
		$path     = "uploads/admin/accessories/".$rand.'.png';
		file_put_contents($userpath,base64_decode($img));

		$array = [
					'acc_name'       => $name,
					'acc_price'      => $price,
					'acc_image'      => $path,
					'acc_description'=> $description,
					'acc_vat'         => $vat,
					'acc_status'      => '1',
					'timestamp'       => $timestamp
		        ];
		if ($this->Common->insert('accessories',$array)) {
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'New accessory added..!');

			redirect('admin/accessories');
		}
		else {
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add accessory..!');

			redirect('admin/accessories/add');
		}
	}

	public function edit($id)
	{
	  $accessory =$this->Common->get_details('accessories',array('acc_id'=>$id))->row();
	  $data['accessory'] = $accessory;
	  $this->load->view('admin/accessories/edit',$data);
	}

	public function editData()
	{
		$accessory_id = $this->input->post('accessory_id');
		$name         = $this->security->xss_clean($this->input->post('acc_name'));
        $description  = $this->security->xss_clean($this->input->post('acc_description'));
        $vat          = $this->security->xss_clean($this->input->post('acc_vat'));
        $price        = $this->security->xss_clean($this->input->post('acc_price'));

		$check       = $this->Common->get_details('accessories',array('acc_name' => $name ,'acc_price'=>$price,'acc_vat'=>$vat, 'acc_id!=' => $accessory_id))->num_rows();
		if ($check > 0) 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add product..!');

			redirect('admin/accessories/edit/'.$accessory_id);
		}
		else 
		{
			// Adding base64 file to server
			$image  = $this->input->post('image');
			$status = $this->input->post('status');
			if ($image != '') 
			{
				$img = substr($image, strpos($image, ",") + 1);

				$url      = FCPATH.'uploads/admin/accessories/';
				$rand     = date('Ymd').mt_rand(1001,9999);
				$userpath = $url.$rand.'.png';
				$path     = "uploads/admin/accessories/".$rand.'.png';
				file_put_contents($userpath,base64_decode($img));

				// Remove old image from the server
				$old = $this->Common->get_details('accessories',array('product_id' => $accessory_id))->row()->acc_image;
				$remove_path = FCPATH . $old;
				unlink($remove_path);

				$array = [
							'acc_name'       => $name,
							'acc_image'      => $path,
							'acc_description'=> $description,
							'acc_vat'         => $vat,
							'acc_price'       => $price,
							'acc_status'      => $status
				        ];
			}
			else 
			{
				$array = [
							'acc_name'       => $name,
							'acc_price'      => $price,
							'acc_description'=> $description,
							'acc_vat'         => $vat,
							'acc_status'      => $status
				        ];
			}

			if ($this->Common->update('acc_id',$accessory_id,'accessories',$array)) {
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Accessory edited successfully..!');

				redirect('admin/accessories');
			}
			else {
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to update accessory..!');

				redirect('admin/accessories/edit/'.$accessory_id);
			}
		}
	}
	public function checkAccessory()
	{
		$name = $this->input->post('name');
		$check= $this->Common->get_details('accessories',array('acc_name'=>$name));
		if($check->num_rows()>0)
		{
			$data ='1';
		}
		else
		{
			$data ='0';
		}
		print_r($data);
	}

	public function enable($id)
	{
		$array = [
			       'acc_status' => '1'
		         ];
	
		if ($this->Common->update('acc_id',$id,'accessories',$array))  {
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'Accessory activated successfully..!');

			redirect('admin/accessories');
		}
		else {
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to activate accessory..!');

			redirect('admin/accessories');
		}
	}
	public function disable($id)
	{
		$array = [
			       'acc_status' => '0'
		         ];
	
		if ($this->Common->update('acc_id',$id,'accessories',$array)) 
		{
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'Accessory blocked successfully..!');

			redirect('admin/accessories');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to block accessory..!');

			redirect('admin/accessories');
		}
	}
}
?>
