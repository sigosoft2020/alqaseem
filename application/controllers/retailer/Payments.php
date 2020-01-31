<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('retailer/M_payments','payments');
			$this->load->model('Common');
			if (!retailer()) {
				redirect('users/retailer');
			}
	}
	public function pending()
	{   
	    $user       = $this->session->userdata['retailer'];
        $retailer_id= $user['retailer_id'];
 
		$pendings = $this->payments->get_pendings($retailer_id);
		foreach($pendings as $pending)
		{   
			$customer_details  = $this->Common->get_details('customers',array('customer_id'=>$pending->customer_id))->row();
			$pending->customer = $customer_details->name_english;
            $pending->phone    = $customer_details->customer_phone;
		}
		$data['pendings'] = $pendings;
		$this->load->view('retailer/payments/pending',$data);
	}
	
	public function get()
	{
        $result = $this->payments->make_datatables();
		$data = array();
		foreach ($result as $res) {
			$sub_array = array();
			$sub_array[] = '<img src="' . base_url() . $res->customer_image . '" height="100px">';
			$sub_array[] = $res->customer_name;
			$sub_array[] = $res->customer_phone;
			$sub_array[] = $res->customer_email;
			$sub_array[] = $res->customer_address;
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
			"recordsTotal" => $this->payments->get_all_data(),
			"recordsFiltered" => $this->payments->get_filtered_data(),
			"data" => $data
		);
		echo json_encode($output);
	}

	public function addPayment()
	{
		date_default_timezone_set('Asia/Kolkata');
        $timestamp   = date('Y-m-d H:i:s');
        $user        = $this->session->userdata['retailer'];
        $retailer_id = $user['retailer_id'];
       
		$payment_id      = $this->security->xss_clean($this->input->post('payment_id'));
        $amount_received = $this->security->xss_clean($this->input->post('amount_received'));
        $current_amount  = $this->security->xss_clean($this->input->post('amount'));
        $balance         = $current_amount-$amount_received;
	    $array           = [
							'amount'        => $balance,
							'updated_on'    => $timestamp
				           ];
		if ($this->Common->update('cpp_id',$payment_id,'customer_pending_payments',$array)) 
		{   
			$payment_details = $this->Common->get_details('customer_pending_payments',array('cpp_id'=>$payment_id))->row();
			$payment    = [
                            'customer_id'    => $payment_details->customer_id,
                            'amount_paid'    => $amount_received,
                            'balance_amount' => $balance,
                            'type'           => 'retailer',
                            'retailer_id'    =>  $retailer_id,
                            'timestamp'      => $timestamp
			              ];
			if($id=$this->Common->insert('payment_history',$payment))
			{
                
	            $wallet_check = $this->Common->get_details('customer_wallet',array('customer_id'=>$payment_details->customer_id,'type'=>'credit'));
	        	if($wallet_check->num_rows()>0)
	        	{
	        		$amount     = $wallet_check->row()->amount;
	        		$new_amount = $amount-$amount_received;
	        		$wallet_id  = $wallet_check->row()->wallet_id;
	        		$wallet_array = [
	        			               'amount' => $new_amount 
	        		                ];
	        		$this->Common->update('wallet_id',$wallet_id,'customer_wallet',$wallet_array);   
	        	}
            
			  $this->session->set_flashdata('alert_type', 'success');
			  $this->session->set_flashdata('alert_title', 'Success');
			  $this->session->set_flashdata('alert_message', 'Payment added..!');
            }
			redirect('retailer/payments/pending');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add payment..!');

			redirect('retailer/payments/pending');
		}
	}

	public function history()
	{
		$user       = $this->session->userdata['retailer'];
        $retailer_id= $user['retailer_id'];
 
		$history = $this->payments->get_history($retailer_id);
		foreach($history as $his)
		{   
			$customer_details  = $this->Common->get_details('customers',array('customer_id'=>$his->customer_id))->row();
			$his->customer = $customer_details->name_english;
            $his->phone    = $customer_details->customer_phone;
		}
		$data['history'] = $history;
		$this->load->view('retailer/payments/history',$data);
	}

    public function invoice($id)
	{
		$history  = $this->Common->get_details('payment_history',array('ph_id'=>$id))->row();
		$retailer = $this->Common->get_details('retail_managers',array('rmanager_id'=>$history->retailer_id))->row();
		$history->retailer_name = $retailer->name;
		$history->retailer_phone= $retailer->phone;
		$customer = $this->Common->get_details('customers',array('customer_id'=>$history->customer_id))->row();
		$history->customer_name = $customer->name_english;
		$history->customer_phone= $customer->customer_phone;
		$data['history'] = $history;
		$this->load->view('retailer/payments/print',$data);
	}

}
?>
