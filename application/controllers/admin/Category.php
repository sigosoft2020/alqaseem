<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('admin/M_category','category');
			$this->load->model('Common');
			if (!admin()) {
				redirect('users/admin');
			}
	}
	public function index()
	{   	
		$this->load->view('admin/category/view');
	}

	public function get()
	{
        $result = $this->category->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$sub_array[] = $res->category_english.'<br>'.$res->category_arabic;
			$sub_array[] = '<button type="button" class="btn btn-link" style="font-size:20px;color:blue" onclick="edit('.$res->category_id.')" ><i class="fa fa-pencil"></i></button>';
			$data[] = $sub_array;
		}

		$output = array(
							"draw"   => intval($_POST['draw']),
							"recordsTotal" => $this->category->get_all_data(),
							"recordsFiltered" => $this->category->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}
	
	public function addCategory()
	{
		date_default_timezone_set('Asia/Kolkata');
        $timestamp = date('Y-m-d H:i:s');
        
		$name_english = $this->security->xss_clean($this->input->post('name_english'));
        $name_arabic  = $this->security->xss_clean($this->input->post('name_arabic'));
       
		$array = [
					'category_english'  => $name_english,
					'category_arabic'   => $name_arabic,
					'timestamp'         => $timestamp
		        ];
		if ($this->Common->insert('category',$array)) 
		{   
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'New category added..!');
			redirect('admin/category');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add category..!');
			redirect('admin/category');
		}
	}

	public function editCategory()
	{
		$cat_id       = $this->input->post('cat_id');
		$name_english = $this->security->xss_clean($this->input->post('name_english'));
        $name_arabic  = $this->security->xss_clean($this->input->post('name_arabic'));

		$check_category = $this->Common->get_details('category',array('category_english' => $name_english,'category_id!=' => $cat_id))->num_rows();
		if ($check_category > 0) 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Category already exist..!');
			redirect('admin/category');
			
		}
		else 
		{  
			$array = [
						'category_english'  => $name_english,
						'category_arabic'   => $name_arabic,
						'timestamp'         => $timestamp
		             ];
		
			if ($this->Common->update('category_id',$cat_id,'category',$array)) 
			{
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Category updated..!');
				redirect('admin/category');
			}
			else 
			{
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to update category..!');
				redirect('admin/category');
			}
		}
	}
	public function checkCategory()
	{
		$cat = $this->input->post('name');
		$check_cat = $this->Common->get_details('category',array('category_english'=>$cat));
		if($check_cat->num_rows()>0)
		{
			$data ='1';
		}
		else
		{
			$data ='0';
		}
		print_r($data);
	}

    public function getCategoryById()
	{
		$id = $_POST['id'];
		$data = $this->Common->get_details('category',array('category_id' => $id))->row();
		print_r(json_encode($data));
	}

}
?>
