<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bill extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			// $this->load->model('admin/bill/M_history','history');
			// $this->load->model('retailer/M_sales','sales');
			$this->load->model('Common');
			if (!admin()) {
				redirect('users/admin');
			}
	}
//retail price starts//
    public function retailPrice()
    { 
      $data['retail'] = $this->Common->get_details('default_price',array('type'=>'retail'))->row();  
      $this->load->view('admin/bill/retail_fillPrice',$data);
    }
//retail price ends//

//co filling price starts here//
   public function cofillingPrice()
    { 
      $data['cofill'] = $this->Common->get_details('default_price',array('type'=>'co'))->row();  
      $this->load->view('admin/bill/co_fillPrice',$data);
    }
//co filling price ends here//

//edit price starts//
    public function priceUpdate($param)
    {
        $price_id  = $this->security->xss_clean($this->input->post('price_id'));
        $price     = $this->security->xss_clean($this->input->post('price'));

        $array     = [
                       'price' => $price
                     ];
        if($this->Common->update('price_id',$price_id,'default_price',$array))
        {
            $this->session->set_flashdata('alert_type', 'success');
            $this->session->set_flashdata('alert_title', 'Success');
            $this->session->set_flashdata('alert_message', 'Price edited sucessfully..!');
        }
        else
        {
            $this->session->set_flashdata('alert_type', 'error');
            $this->session->set_flashdata('alert_title', 'Failed');
            $this->session->set_flashdata('alert_message', 'Failed to edit price..!');           
        }
        if($param =='re') 
        {
           redirect('admin/bill/retailPrice');  
        }
        else
        {
            redirect('admin/bill/cofillingPrice'); 
        }      
    }
//edit price ends// 

//retail filling starts here//

//retail filling ends here//

	public function history()
	{
		$retailer    = $this->session->userdata['retailer'];
		$retailer_id = $retailer['retailer_id'];
        $current     = date('Y-m-d');
        
        $history     = $this->sales->get_history($retailer_id);
        
        $data['history'] = $history;
		$this->load->view('retailer/billing/history',$data);
	}

	public function new()
	{
		$data['customers']  = $this->Common->get_details('customers',array('status'=>'1'))->result();
		$data['products']   = $this->Common->get_details('products',array('product_status'=>'1'))->result();
		$this->load->view('retailer/billing/new',$data);
	}

	public function requests()
	{
		$this->load->view('retailer/billing/requests');
	}
	
    public function get_requests()
	{
        $result = $this->request->make_datatables();
		$data = array();
		foreach ($result as $res) {
			$sub_array = array();
			$sub_array[] = $res->customer_name;
			$product_name= $this->Common->get_details('products',array('product_id'=>$res->product_id))->row()->product_name;
			$sub_array[] = $product_name;
			$sub_array[] = $res->quantity;
			$sub_array[] = date('d-M-Y h:i A',strtotime($res->ordered_time));
			$sub_array[] = 'Pending';
			$sub_array[] = '<a href="' . site_url('retailer/bill/request_bill/'.$res->rsrequest_id) . '" class="btn btn-primary" style="font-size:12px;">Bill Now</a>';
			$data[] = $sub_array;
		}

		$output = array(
			"draw"   => intval($_POST['draw']),
			"recordsTotal" => $this->request->get_all_data(),
			"recordsFiltered" => $this->request->get_filtered_data(),
			"data" => $data
		);
		echo json_encode($output);
	}

	public function get_product()
	{
		$product_id      =  $this->input->post('id');
		$data            = $this->Common->get_details('products',array('product_id'=>$product_id))->row();
		print_r(json_encode($data));
	}

	public function request_bill($id)
	{   
		$request         = $this->Common->get_details('retail_supervisor_requests',array('rsrequest_id'=>$id))->row();
		$data['request'] = $request;
		$data['product'] = $this->Common->get_details('products',array('product_id'=>$request->product_id))->row()->product_name;
		$this->load->view('retailer/billing/request_bill',$data);
	}

	public function addData()
	{
		date_default_timezone_set('Asia/Kolkata');
		$current     = date('Y-m-d H:i:s');
		$retailer    = $this->session->userdata['retailer'];
		$retailer_id = $retailer['retailer_id'];
        
        $customer_id     = $this->security->xss_clean($this->input->post('customer_id'));
        $amount_recieved = $this->security->xss_clean($this->input->post('amount_recived'));
		$GrandTotal= 0;		
		for($i = 0; $i<count($_POST['ProductName']); $i++) 
		{
			 $Total=$_POST['Total'][$i]; 
			 $GrandTotal=$GrandTotal+$Total;
			 
		};
        $credit        = $GrandTotal-$amount_recieved; 
        $customer      = $this->Common->get_details('customers',array('customer_id'=>$customer_id))->row();
        $customer_name = $customer->customer_name;
        $customer_phone= $customer->customer_phone;

	    $order         = [
		                     'customer_id'     => $customer_id,
		                     'customer_name'   => $customer_name,
		                     'customer_phone'  => $customer_phone,
		                     'total'           => $GrandTotal,
		                     'amount_received' => $amount_recieved,
		                     'billing_date'    => date('Y-m-d'),
		                     'billing_time'    => date('H:i:s'),
		                     'retailer_id'     => $retailer_id,
		                     'credit_balance'  => $credit,
							 'timestamp'     => $current
			            ];
         if($ID = $this->Common->insert('retail_sales',$order))
         {                    
           for($i = 0; $i<count($_POST['ProductName']); $i++)  
           {
			   $ProductName  = $_POST['ProductName'][$i];
			   $Product_Id   = $_POST['ProductID'][$i];
			   $Quantity     = $_POST['Quantity'][$i];
			   $Total        = $_POST['Total'][$i];
			   
			   $items        = [
                                    'sale_id'       => $ID,
                                    'product_id'    => $Product_Id,
                                    'quantity'      => $Quantity,
                                    'total'         => $Total,
                                    'timestamp'     => $current
			                     ];
			    $this->Common->insert('retail_sales_products',$items);
            }
            $invoice_no    = 'INVRS'.$ID;
		    $array_invoice = [
		    	              'invoice_no' => $invoice_no
		                     ];
            $this->Common->update('rsale_id',$ID,'retail_sales',$array_invoice);

            if($credit>0)
            {
            	$pending_check      = $this->Common->get_details('customer_pending_payments',array('customer_id'=>$customer_id,'type'=>'retailer','retailer_id'=>$retailer_id));
            	if($pending_check->num_rows()>0)
            	{
                   $pending_amount  = $pending_check->row()->amount;
                   $new_amount      = $credit+ $pending_amount;
                   $pending_id      = $pending_check->row()->cpp_id;
                   $payment_pending = [
            		                    'amount'     =>$new_amount,
            		                    'updated_on' =>$current
            	                	  ];
            	   $this->Common->update('cpp_id',$pending_id,'customer_pending_payments',$payment_pending);            	  
            	}
            	else
            	{
            		$payment_pending = [
            		                     'customer_id' =>$customer_id,
            		                     'amount'      =>$credit,
            		                     'type'        => 'retailer',
            		                     'retailer_id' => $retailer_id,
            		                     'added_on'    => $current
            	                	   ];
            	    $this->Common->insert('customer_pending_payments',$payment_pending);     
            	}
            	
            	$wallet_check = $this->Common->get_details('customer_wallet',array('customer_id'=>$customer_id,'type'=>'credit'));
            	if($wallet_check->num_rows()>0)
            	{
            		$amount     = $wallet_check->row()->amount;
            		$new_amount = $credit+$amount;
            		$wallet_id  = $wallet_check->row()->wallet_id;
            		$wallet_array = [
            			               'amount' => $new_amount 
            		                ];
            		$this->Common->update('wallet_id',$wallet_id,'customer_wallet',$wallet_array);                
            	}
            	else
            	{
            		$wallet_array = [
            			              'customer_id' => $customer_id,
            			              'type'        => 'credit',
            			              'amount'      => $credit,
            			              'timestamp'   => $current
            		                ];
            		$this->Common->insert('customer_wallet',$wallet_array);                
            	}
            }

            $payment_history  = [
                                   'customer_id'    => $customer_id,
                                   'invoice_no'     => $invoice_no,
                                   'amount_paid'    => $amount_recieved,
                                   'balance_amount' => $credit,
                                   'type'           =>'retailer',
                                   'retailer_id'    => $retailer_id,
                                   'timestamp'      =>$current
                                ];
            $this->Common->insert('payment_history',$payment_history);

            $this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'Bill created sucessfully..!');
	        redirect('retailer/bill/history');
        }
        else
        {
        	$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to create bill..!');
            redirect('retailer/bill/new');
        }
        
	}


	public function addRequestData()
	{
		date_default_timezone_set('Asia/Kolkata');
		$current     = date('Y-m-d H:i:s');
		$retailer    = $this->session->userdata['retailer'];
		$retailer_id = $retailer['retailer_id'];
        
        $request_id      = $this->security->xss_clean($this->input->post('request_id'));
        $customer_id     = $this->security->xss_clean($this->input->post('customer_id'));
        $product_id      = $this->security->xss_clean($this->input->post('product_id'));
        $quantity        = $this->security->xss_clean($this->input->post('Quantity'));
        $total           = $this->security->xss_clean($this->input->post('Total'));
        $amount_recieved = $this->security->xss_clean($this->input->post('amount_recived'));
        $credit          = $total-$amount_recieved; 
        $customer        = $this->Common->get_details('customers',array('customer_id'=>$customer_id))->row();
        $customer_name  = $customer->customer_name;
        $customer_phone = $customer->customer_phone;

	    $order          = [
		                     'customer_id'     => $customer_id,
		                     'customer_name'   => $customer_name,
		                     'customer_phone'  => $customer_phone,
		                     'total'           => $total,
		                     'amount_received' => $amount_recieved,
		                     'billing_date'    => date('Y-m-d'),
		                     'billing_time'    => date('H:i:s'),
		                     'retailer_id'     => $retailer_id,
		                     'credit_balance'  => $credit,
							 'timestamp'     => $current
			            ];
         if($ID = $this->Common->insert('retail_sales',$order))
         {                    
		   $items        = [
                                'sale_id'       => $ID,
                                'product_id'    => $product_id,
                                'quantity'      => $quantity,
                                'total'         => $total,
                                'timestamp'     => $current
		                    ];
		    $this->Common->insert('retail_sales_products',$items);

            $invoice_no    = 'INVRS'.$ID;
		    $array_invoice = [
		    	              'invoice_no' => $invoice_no
		                     ];
            $this->Common->update('rsale_id',$ID,'retail_sales',$array_invoice);

            if($credit>0)
            {
            	$pending_check      = $this->Common->get_details('customer_pending_payments',array('customer_id'=>$customer_id,'type'=>'retailer','retailer_id'=>$retailer_id));
            	if($pending_check->num_rows()>0)
            	{
                   $pending_amount  = $pending_check->row()->amount;
                   $new_amount      = $credit+ $pending_amount;
                   $pending_id      = $pending_check->row()->cpp_id;
                   $payment_pending = [
            		                    'amount'     =>$new_amount,
            		                    'updated_on' =>$current
            	                	  ];
            	   $this->Common->update('cpp_id',$pending_id,'customer_pending_payments',$payment_pending);            	  
            	}
            	else
            	{
            		$payment_pending = [
            		                     'customer_id' =>$customer_id,
            		                     'amount'      =>$credit,
            		                     'type'        => 'retailer',
            		                     'retailer_id' => $retailer_id,
            		                     'added_on'    => $current
            	                	   ];
            	    $this->Common->insert('customer_pending_payments',$payment_pending);     
            	}
            	
            	$wallet_check = $this->Common->get_details('customer_wallet',array('customer_id'=>$customer_id,'type'=>'credit'));
            	if($wallet_check->num_rows()>0)
            	{
            		$amount     = $wallet_check->row()->amount;
            		$new_amount = $credit+$amount;
            		$wallet_id  = $wallet_check->row()->wallet_id;
            		$wallet_array = [
            			               'amount' => $new_amount 
            		                ];
            		$this->Common->update('wallet_id',$wallet_id,'customer_wallet',$wallet_array);                
            	}
            	else
            	{
            		$wallet_array = [
            			              'customer_id' => $customer_id,
            			              'type'        => 'credit',
            			              'amount'      => $credit,
            			              'timestamp'   => $current
            		                ];
            		$this->Common->insert('customer_wallet',$wallet_array);                
            	}
            }

            $payment_history  = [
                                   'customer_id'    => $customer_id,
                                   'invoice_no'     => $invoice_no,
                                   'amount_paid'    => $amount_recieved,
                                   'balance_amount' => $credit,
                                   'type'           =>'retailer',
                                   'retailer_id'    => $retailer_id,
                                   'timestamp'      =>$current
                                ];
            $this->Common->insert('payment_history',$payment_history);
            
            $request_status   = [
            	                  'status' => 'completed'
                                ];
            $this->Common->update('rsrequest_id',$request_id,'retail_supervisor_requests',$request_status);

            $this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'Bill created sucessfully..!');
	        redirect('retailer/bill/history');
        }
        else
        {
        	$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to create bill..!');
            redirect('retailer/request');
        }
        
	}

	public function details($id)
	{
		$sales    = $this->Common->get_details('retail_sales',array('rsale_id'=>$id))->row();
		$sales->details = $this->Common->get_details('retail_sales_products',array('sale_id'=>$id))->result();
		foreach($sales->details as $detail)
		{
			$detail->product_name = $this->Common->get_details('products',array('product_id'=>$detail->product_id))->row()->product_name;
		}
		$data['sales'] = $sales;
		$this->load->view('retailer/billing/details',$data);
	}

    public function invoice($id)
	{
		$sales    = $this->Common->get_details('retail_sales',array('rsale_id'=>$id))->row();
		// $sales->address = $this->Common->get_details('customers',array('customer_id'=>$sales->customer_id))->row()->customer_address;
		$retailer = $this->Common->get_details('retail_managers',array('rmanager_id'=>$sales->retailer_id))->row();
		$sales->retailer_name = $retailer->name;
		$sales->retailer_phone= $retailer->phone;
		$sales->details = $this->Common->get_details('retail_sales_products',array('sale_id'=>$id))->result();
		foreach($sales->details as $detail)
		{
			$detail->product_name = $this->Common->get_details('products',array('product_id'=>$detail->product_id))->row()->product_name;
		}
		$data['sales'] = $sales;
		$this->load->view('retailer/billing/print',$data);
	}
}
?>
