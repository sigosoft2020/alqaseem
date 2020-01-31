<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('admin/M_expense','expense');
			$this->load->model('admin/Expense_category','category');
			$this->load->model('Common');
			if (!admin()) {
				redirect('users/admin');
			}
	}

	public function index()
	{
		$this->load->view('admin/expense/agency_expense');
	}

	public function get()
	{
        $result = $this->expense->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$arabic      = $this->Common->get_details('agencies',array('agency_id'=>$res->agency_id))->row()->name_arabic;
			$sub_array[] = $res->agency_name.'<br>'.$arabic;
			$category    = $this->Common->get_details('expense_category',array('expcat_id'=>$res->cat_id))->row();
			$category_name = $category->category_name.'<br>'.$category->category_arabic;
			$sub_array[] = $category_name;
			$sub_array[] = $res->expense_name;
			$sub_array[] = 'AED '.$res->expense_amount;
			$sub_array[] = date('d-M-Y',strtotime($res->date)).'<br>'.$res->time;
			$data[]      = $sub_array;
		}

		$output = array(
							"draw"   => intval($_POST['draw']),
							"recordsTotal" => $this->expense->get_all_data(),
							"recordsFiltered" => $this->expense->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}
	
	public function category()
	{
	    $this->load->view('admin/expense/category');
	}
	
	public function getCategory()
	{
        $result = $this->category->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$sub_array[] = $res->category_name.'<br>'.$res->category_arabic;
			if($res->status=='1')
			{
				$status = 'Active';
				$action = '<a class="btn btn-danger" style="font-size:12px;" href="' . site_url('admin/expense/disable_category/'.$res->expcat_id) . '" >Disable</a>';
			}
			else
			{
				$status = 'Blocked';
				$action = '<a class="btn btn-success" style="font-size:12px;" href="' . site_url('admin/expense/enable_category/'.$res->expcat_id) . '" >Enable</a>';
			}
			$sub_array[] = $status.'<br>'.$action;
			$sub_array[] = '<button type="button" class="btn btn-primary" onclick="edit('. $res->expcat_id .')" style="font-size:12px;">Edit</button>';
			$data[]      = $sub_array;
		}

		$output = array(
							"draw"            => intval($_POST['draw']),
							"recordsTotal"    => $this->category->get_all_data(),
							"recordsFiltered" => $this->category->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}
	
	public function addCategory()
	{
		date_default_timezone_set('Asia/Kolkata');
        $timestamp = date('Y-m-d H:i:s');
        
		$name       = $this->security->xss_clean($this->input->post('name'));
		$name_arabic= $this->security->xss_clean($this->input->post('name_arabic'));
      
		$array     = [
						'category_name'     => $name,
						'category_arabic'   => $name_arabic,
						'status'            => '1',
						'timestamp'         => $timestamp
			        ];
		if ($this->Common->insert('expense_category',$array)) 
		{
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'New category added..!');
			redirect('admin/expense/category');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add category..!');
			redirect('admin/expense/category');
		}
	}
	
	public function editCategory()
	{
		$cat_id     = $this->input->post('category_id');
		$name       = $this->security->xss_clean($this->input->post('name'));
		$name_arabic= $this->security->xss_clean($this->input->post('name_arabic'));
		$check      = $this->Common->get_details('expense_category',array('category_name' => $name ,'expcat_id!=' => $cat_id))->num_rows();
		if ($check > 0) 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Category with same name already exist..!');

			redirect('admin/expense/category');
		}
		else 
		{
            $array = [  
						'category_name'    => $name,
						'category_arabic'  => $name_arabic,
		             ];
		 
			if ($this->Common->update('expcat_id',$cat_id,'expense_category',$array)) {
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Package edited successfully..!');
				redirect('admin/expense/category');
			}
			else 
			{
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to update package..!');

				redirect('admin/expense/category');
			}
		}
	}
	
	public function checkCategory()
	{
		$name         = $this->input->post('name');
		$check_category = $this->Common->get_details('expense_category',array('category_name'=>$name));
		if($check_category->num_rows()>0)
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
		$id    = $_POST['id'];
		$data  = $this->Common->get_details('expense_category',array('expcat_id' => $id))->row();
		
		print_r(json_encode($data));
	}

	public function disable_category($id)
	{
			$array = [
				       'status' => '0'
			         ];
		
			if ($this->Common->update('expcat_id',$id,'expense_category',$array)) 
			{
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Category blocked successfully..!');
				redirect('admin/expense/category');
			}
			else 
			{
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to block category..!');
                redirect('admin/expense/category');
			}
	}

	public function enable_category($id)
	{
			$array = [
				       'status' => '1'
			         ];
		
			if ($this->Common->update('expcat_id',$id,'expense_category',$array)) 
			{
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Category activated successfully..!');
				redirect('admin/expense/category');
			}
			else {
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to activate category..!');
				redirect('admin/expense/category');
			}
	}

}
?>
