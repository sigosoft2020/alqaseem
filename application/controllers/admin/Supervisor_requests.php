<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supervisor_requests extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('admin/supervisor_requests/M_pending','pending');
			$this->load->model('admin/supervisor_requests/M_approved','approved');
			$this->load->model('admin/supervisor_requests/M_cancelled','cancelled');
			$this->load->model('admin/supervisor_requests/M_completed','completed');
			$this->load->model('Common');
			if (!admin()) {
				redirect('users/admin');
			}
	}
//Pending Requests//

	public function pending()
	{
		$this->load->view('admin/supervisor_requests/pending');
	}

	public function get_pending()
	{
        $result = $this->pending->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$sub_array[] = 'SREQ'.$res->srequest_id;
			$sub_array[] = $res->supervisor_name;
			$sub_array[] = $res->agency_name;
			$product     = $this->Common->get_details('products',array('product_id'=>$res->product_id))->row();
			$sub_array[] = $product->product_name; 
			$sub_array[] = date('d-M-Y',strtotime($res->date)).' '.$res->time ;
			$sub_array[] = $res->broken_count;
			$sub_array[] = $res->smell_defect_count;
			$sub_array[] = '<span style="color: blue;">Pending</span>';
		    $sub_array[] = '<a class="btn btn-success" style="font-size:12px;" href="' . site_url('admin/supervisor_requests/approve/'.$res->srequest_id) . '" >Approve</a>';		    
			$sub_array[] = '<a class="btn btn-danger" style="font-size:12px;" href="' . site_url('admin/supervisor_requests/cancel/'.$res->srequest_id) . '" >Cancel</a>';

			$data[]      = $sub_array;
		}

		$output = array(
							"draw"   => intval($_POST['draw']),
							"recordsTotal" => $this->pending->get_all_data(),
							"recordsFiltered" => $this->pending->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}
	
	public function approve($id)
	{
		$array = [
			       'status'        => 'a',
			       'approved_date' => date('Y-m-d')
		         ];
	
		if ($this->Common->update('srequest_id',$id,'supervisor_requests',$array))  {
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'Request approved successfully..!');
			redirect('admin/supervisor_requests/approved');
		}
		else {
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to approve request..!');
			redirect('admin/supervisor_requests/approved');
		}
	}

	public function cancel($id)
	{
		$array = [
			       'status'         => 'r',
			       'cancelled_date' => date('Y-m-d')
		         ];
	
		if ($this->Common->update('srequest_id',$id,'supervisor_requests',$array))
		{
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'Request cancelled successfully..');
			redirect('admin/supervisor_requests/cancelled');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to cancel accessory..!');
			redirect('admin/supervisor_requests/cancelled');
		}
	}

//Pending requests ends here//

// Approved requests starts here//
   
	public function approved()
	{
		$this->load->view('admin/supervisor_requests/approved');
	}

	public function get_approved()
	{
        $result = $this->approved->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$sub_array[] = 'SREQ'.$res->srequest_id;
			$sub_array[] = $res->supervisor_name;
			$sub_array[] = $res->agency_name;
			$product     = $this->Common->get_details('products',array('product_id'=>$res->product_id))->row();
			$sub_array[] = $product->product_name; 
			$sub_array[] = date('d-M-Y',strtotime($res->date)).' '.$res->time ;
			$sub_array[] = $res->broken_count;
			$sub_array[] = $res->smell_defect_count;
			$sub_array[] = '<span style="color: green;">Approved</span>';
			$sub_array[] = '<a href="'.site_url('admin/Supervisor_requests/invoice/'.$res->srequest_id).'"><button type="button" class="btn btn-primary" style="font-size:12px;">Print</button></a>';
			$data[] = $sub_array;
		}

		$output = array(
							"draw"   => intval($_POST['draw']),
							"recordsTotal" => $this->approved->get_all_data(),
							"recordsFiltered" => $this->approved->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}

// Approved requests ends here//

//cancelled requests starts here//
   
  	public function cancelled()
	{
		$this->load->view('admin/supervisor_requests/cancelled');
	}

	public function get_cancelled()
	{
        $result = $this->cancelled->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$sub_array = array();
			$sub_array[] = 'SREQ'.$res->srequest_id;
			$sub_array[] = $res->supervisor_name;
			$sub_array[] = $res->agency_name;
			$product     = $this->Common->get_details('products',array('product_id'=>$res->product_id))->row();
			$sub_array[] = $product->product_name; 
			$sub_array[] = date('d-M-Y',strtotime($res->date)).' '.$res->time ;
			$sub_array[] = $res->broken_count;
			$sub_array[] = $res->smell_defect_count;
			$sub_array[] = '<span style="color: red;">Cancelled</span>';
			$data[] = $sub_array;
		}

		$output = array(
							"draw"   => intval($_POST['draw']),
							"recordsTotal" => $this->cancelled->get_all_data(),
							"recordsFiltered" => $this->cancelled->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}

//cancelled requests ends here//	

// completed requests here //

    public function completed()
	{
		$this->load->view('admin/supervisor_requests/completed');
	}

	public function get_completed()
	{
        $result = $this->completed->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$sub_array = array();
			$sub_array[] = 'SREQ'.$res->srequest_id;
			$sub_array[] = $res->supervisor_name;
			$sub_array[] = $res->agency_name;
			$product     = $this->Common->get_details('products',array('product_id'=>$res->product_id))->row();
			$sub_array[] = $product->product_name; 
			$sub_array[] = date('d-M-Y',strtotime($res->date)).' '.$res->time ;
			$sub_array[] = $res->broken_count;
			$sub_array[] = $res->smell_defect_count;
			$sub_array[] = '<span style="color: green;">Completed</span>';
			$sub_array[] = '<a href="'.site_url('admin/Supervisor_requests/invoice/'.$res->srequest_id).'"><button type="button" class="btn btn-primary" style="font-size:12px;">Print</button></a>';
			$data[] = $sub_array;
		}

		$output = array(
							"draw"   => intval($_POST['draw']),
							"recordsTotal" => $this->completed->get_all_data(),
							"recordsFiltered" => $this->completed->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}
//completed requests ends here//

//print invoice//	
	public function invoice($id)
	{   
        $order           = $this->Common->get_details('supervisor_requests',array('srequest_id'=>$id))->row();
		$order->products = $this->Common->get_details('supervisor_request_products',array('request_id'=>$id))->result(); 
        foreach($order->products as $product)
        {
        	$product->name = $this->Common->get_details('products',array('product_id'=>$product->product_id))->row()->product_name;
        }
        $data['order']  = $order;
		$this->load->view('admin/supervisor_requests/invoice',$data);
	}
//end of print//

}
?>
