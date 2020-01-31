<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('admin/M_product','product');
			$this->load->model('Common');
			if (!admin()) {
				redirect('users/admin');
			}
	}
	public function index()
	{
		$this->load->view('admin/product/view');
	}
	
	public function add()
	{   
		$data['categories'] = $this->Common->get_details('category',array())->result();
		$this->load->view('admin/product/add',$data);
	}

	public function get()
	{
        $result = $this->product->make_datatables();
		$data = array();
		foreach ($result as $res) {
			$sub_array = array();
			$sub_array[] = '<img src="' . base_url() . $res->product_image . '" height="100px">';
			$sub_array[] = $res->product_name.'<br>'.$res->product_name_arabic;
			$sub_array[] = $res->price;
			$sub_array[] = $res->product_vat;
			$category    = $this->Common->get_details('category',array('category_id'=>$res->cat_id))->row();
			$sub_array[] = $category->category_english.'<br>'.$category->category_arabic;
			if($res->product_status=='1')
			{
				$status = 'Active';
				$action = '<a class="btn btn-danger" style="font-size:12px;" href="' . site_url('admin/product/disable/'.$res->product_id) . '" >Disable</a>';
			}
			else
			{
				$status = 'Blocked';
				$action = '<a class="btn btn-success" style="font-size:12px;" href="' . site_url('admin/product/enable/'.$res->product_id) . '" >Enable</a>';
			}
			$sub_array[] = $status.'<br>'.$action;
			$sub_array[] = '<a class="btn btn-link" style="font-size:24px;color:blue" href="' . site_url('admin/product/edit/'.$res->product_id) . '" ><i class="fa fa-pencil"></i></a>';
			$data[] = $sub_array;
		}

		$output = array(
			"draw"   => intval($_POST['draw']),
			"recordsTotal" => $this->product->get_all_data(),
			"recordsFiltered" => $this->product->get_filtered_data(),
			"data" => $data
		);
		echo json_encode($output);
	}
	
	public function addProduct()
	{
		date_default_timezone_set('Asia/Kolkata');
        $timestamp = date('Y-m-d H:i:s');
        
		$name         = $this->security->xss_clean($this->input->post('product_name'));
		$cat_id       = $this->security->xss_clean($this->input->post('category_id'));
		$name_arabic  = $this->security->xss_clean($this->input->post('product_name_arabic'));
        // $weight       = $this->security->xss_clean($this->input->post('weight'));
        // $unit         = $this->security->xss_clean($this->input->post('unit'));
        $vat          = $this->security->xss_clean($this->input->post('product_vat'));
        $price        = $this->security->xss_clean($this->input->post('price'));

		$image     = $this->input->post('image');
		$img       = substr($image, strpos($image, ",") + 1);

		$url       = FCPATH.'uploads/admin/products/';
		$rand      = date('Ymd').mt_rand(1001,9999);
		$userpath  = $url.$rand.'.png';
		$path      = "uploads/admin/products/".$rand.'.png';
		file_put_contents($userpath,base64_decode($img));

		$array = [
			        'cat_id'              => $cat_id,
					'product_name'        => $name,
					'product_image'       => $path,
					'product_name_arabic' => $name_arabic,
					// 'weight'              => $weight,
					// 'unit'                => $unit,
					'product_vat'         => $vat,
					'price'               => $price,
					'product_status'      => '1',
					'timestamp'           => $timestamp
		        ];
		if ($ID = $this->Common->insert('products',$array)) {
			$stock_array   = [
				               'product_id' => $ID,
				               'stock'      => '0',
				               'timestamp'  => $timestamp
			                 ];
			$this->Common->insert('product_stock',$stock_array); 
			
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'New product added..!');

			redirect('admin/product');
		}
		else {
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add product..!');

			redirect('admin/product/add');
		}
	}

	public function edit($id)
	{
	  $product       = $this->Common->get_details('products',array('product_id'=>$id))->row();
	  $data['product']    = $product;
	  $data['categories'] = $this->Common->get_details('category',array())->result();
	  $this->load->view('admin/product/edit',$data);
	}

	public function editProduct()
	{
		$product_id   = $this->input->post('product_id');
		$cat_id       = $this->security->xss_clean($this->input->post('category_id'));
		$name         = $this->security->xss_clean($this->input->post('product_name'));       
        $vat          = $this->security->xss_clean($this->input->post('product_vat'));
        $price        = $this->security->xss_clean($this->input->post('price'));

		$check       = $this->Common->get_details('products',array('cat_id'=>$cat_id,'product_name' => $name ,'product_id!=' => $product_id))->num_rows();
		if ($check > 0) {
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add product..!');

			redirect('admin/product/edit/'.$product_id);
		}
		else {
			// Adding base64 file to server
			$image  = $this->input->post('image');
			if ($image != '') 
			{
				$img = substr($image, strpos($image, ",") + 1);

				$url      = FCPATH.'uploads/admin/products/';
				$rand     = date('Ymd').mt_rand(1001,9999);
				$userpath = $url.$rand.'.png';
				$path     = "uploads/admin/products/".$rand.'.png';
				file_put_contents($userpath,base64_decode($img));

				// Remove old image from the server
				$old = $this->Common->get_details('products',array('product_id' => $product_id))->row()->product_image;
				$remove_path = FCPATH . $old;
				unlink($remove_path);

				$array = [  
					        'cat_id'              => $cat_id,
							'product_name'        => $name,
							'product_image'       => $path,
							'product_vat'         => $vat,
							'price'               => $price
				        ];
			}
			else 
			{
				$array = [  
					        'cat_id'              => $cat_id,
							'product_name'        => $name,
							'product_vat'         => $vat,
							'price'               => $price
				        ];
			}

			if ($this->Common->update('product_id',$product_id,'products',$array)) {
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Product edited successfully..!');

				redirect('admin/product');
			}
			else {
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to update product..!');

				redirect('admin/product/edit/'.$product_id);
			}
		}
	}

	public function checkProduct()
	{
		$name = $this->input->post('name');
		$cat  = $this->input->post('cat');
		$check= $this->Common->get_details('products',array('cat_id'=>$cat,'product_name'=>$name));
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
			       'product_status' => '1'
		         ];
	
		if ($this->Common->update('product_id',$id,'products',$array)) {
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'Product activated successfully..!');

			redirect('admin/product');
		}
		else {
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to activate product..!');

			redirect('admin/product');
		}
	}
	public function disable($id)
	{
		$array = [
			       'product_status' => '0'
		         ];
	
		if ($this->Common->update('product_id',$id,'products',$array)) 
		{
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'Product blocked successfully..!');

			redirect('admin/product');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to block product..!');

			redirect('admin/product');
		}
	}
}
?>
