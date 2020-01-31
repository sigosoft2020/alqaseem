<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('admin/M_payments','payments');
			$this->load->model('Common');
			if (!admin()) {
				redirect('users/admin');
			}
	}
	public function pending()
	{   
		$pendings = $this->payments->get_pendings();
		foreach($pendings as $pending)
		{   
			$customer_details  = $this->Common->get_details('customers',array('customer_id'=>$pending->customer_id))->row();
			$pending->customer = $customer_details->name_english;
            $pending->phone    = $customer_details->customer_phone;
            if($pending->type=='retailer')
            {
            	$pending->retailer= $this->Common->get_details('retail_managers',array('rmanager_id'=>$pending->retailer_id))->row()->name;
            }
            elseif($pending->type=='agency')
            {
            	$pending->agency= $this->Common->get_details('agencies',array('agency_id'=>$pending->agency_id))->row()->agency_name;
            }
		}
		$data['pendings'] = $pendings;
		$this->load->view('admin/payments/pending',$data);
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
                            'type'           => 'admin',
                            'timestamp'      => $timestamp
			              ];
			if($id=$this->Common->insert('payment_history',$payment))
			{
                $invoce  = [
                	         'invoice_no'  => 'INVPN'.$id
                           ];
			    $this->Common->update('ph_id',$id,'payment_history',$invoce);

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
			redirect('admin/payments/pending');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add payment..!');

			redirect('admin/payments/pending');
		}
	}

	public function history()
	{
		$history = $this->payments->get_history();
		foreach($history as $his)
		{   
			$customer_details  = $this->Common->get_details('customers',array('customer_id'=>$his->customer_id))->row();
			$his->customer = $customer_details->name_english;
            $his->phone    = $customer_details->customer_phone;
		}
		$data['history'] = $history;
		$this->load->view('admin/payments/history',$data);
	}

    public function invoice($id)
	{
		$history  = $this->Common->get_details('payment_history',array('ph_id'=>$id))->row();
		
		$customer = $this->Common->get_details('customers',array('customer_id'=>$history->customer_id))->row();
		$history->customer_name = $customer->name_english;
		$history->customer_phone= $customer->customer_phone;
		$data['history'] = $history;
		$this->load->view('admin/payments/print',$data);
	}

}
?>
