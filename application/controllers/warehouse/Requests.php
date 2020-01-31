<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requests extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('warehouse/M_pending','pending');
			$this->load->model('warehouse/M_completed','completed');
			$this->load->model('Common');
			if (!warehouse()) {
				redirect('users/warehouse');
			}
	}
//pending requests//
	public function pending()
	{
		$this->load->view('warehouse/requests/pending');
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
			$sub_array[] = '<a href="'.site_url('warehouse/requests/updateStatus/'.$res->srequest_id).'"><button type="button" class="btn btn-primary" style="font-size:12px;">Complete</button></a>';
			$data[] = $sub_array;
		}

		$output = array(
							"draw"   => intval($_POST['draw']),
							"recordsTotal" => $this->pending->get_all_data(),
							"recordsFiltered" => $this->pending->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}
//pending requests//

//completed requets//
    public function completed()
	{
		$this->load->view('warehouse/requests/completed');
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
			// $sub_array[] = $res->broken_count;
			$sub_array[] = $res->new_cans_allowed;
			$sub_array[] = '<span style="color: green;">Completed</span>';
			$sub_array[] = '<a href="'.site_url('warehouse/requests/invoice/'.$res->srequest_id).'"><button type="button" class="btn btn-primary" style="font-size:12px;">Print</button></a>';
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

//change status//
	 public function updateStatus($request_id)
	 {
	 	$w_manager    = $this->session->userdata['warehouse'];
		$w_manager_id = $w_manager['warehouse_id'];

	 	$cans          = [
	 	                    'status'            => 'c',
	 	                    'completed_date'    => date('Y-m-d'),
	 	                    'completed_by'      => 'w',
	 	                    'wmanager_id'       => $w_manager_id
	 	                 ];
	 	if($this->Common->update('srequest_id',$request_id,'supervisor_requests',$cans))
	 	{
	 		$products = $this->Common->get_details('supervisor_request_products',array('request_id'=>$request_id))->result();
	 		foreach($products as $product)
	 		{
	 			$new_cans = [
	 				          'new_cans_allowed' => $product->quantity
	 			            ];
	 			$this->Common->update('rpr_id',$product->rpr_id,'supervisor_request_products',$new_cans);             
	 		}
	 	}
	   
	    $this->session->set_flashdata('alert_type', 'success');
		$this->session->set_flashdata('alert_title', 'Success');
		$this->session->set_flashdata('alert_message', 'Order completed..!');
		redirect('warehouse/requests/completed');
	 }
// change status ends here//	


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
		$this->load->view('warehouse/requests/invoice',$data);
	}
//end of print//
}
?>
