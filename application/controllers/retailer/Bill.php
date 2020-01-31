<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bill extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('retailer/M_requests','request');
			$this->load->model('retailer/M_sales','sales');
			$this->load->model('Common');
			if (!retailer()) {
				redirect('users/retailer');
			}
	}
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
			 $sub_array = array();
            $sub_array[] = $res->customer_name;
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
        $stock_check     = $this->Common->get_details('product_stock',array('product_id'=>$product_id));
        if($stock_check->num_rows()>0)
        {
            $data->stock  = $stock_check->row()->stock;
        }
        else
        {
            $data->stock = '0';
        }
		print_r(json_encode($data));
	}

	public function request_bill($id)
	{   
		// $request         = $this->Common->get_details('retail_supervisor_requests',array('rsrequest_id'=>$id))->row();
		// $data['request'] = $request;
		// $data['product'] = $this->Common->get_details('products',array('product_id'=>$request->product_id))->row()->product_name;
		// $this->load->view('retailer/billing/request_bill',$data);

        $request         = $this->Common->get_details('retail_supervisor_requests',array('rsrequest_id'=>$id))->row();
        $request->products= $this->Common->get_details('retail_super_req_products',array('request_id'=>$id))->result();
        $GrandTotal= 0;
        foreach($request->products as $req)
        {
            $product      = $this->Common->get_details('products',array('product_id'=>$req->product_id))->row();
            $req->product = $product->product_name;
            $req->price   = $product->price;
            $req->stock   = $this->Common->get_details('product_stock',array('product_id'=>$req->product_id))->row()->stock;
            $req->total   = $req->quantity*$req->price;
           
        }
        
        $data['request'] = $request;
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
        $customer_name = $customer->name_english;
        $customer_phone= $customer->customer_phone;

        $order  = [
                     'customer_id'     => $customer_id,
                     'customer_name'   => $customer_name,
                     'customer_phone'  => $customer_phone,
                     'total'           => $GrandTotal,
                     'amount_received' => $amount_recieved,
                     'billing_date'    => date('Y-m-d'),
                     'billing_time'    => date('H:i:s'),
                     'retailer_id'     => $retailer_id,
                     'credit_balance'  => $credit,
                     'type'            => 'retail',
                     'added_by'        => 'retailer',
                     'status'          => 'completed',
                     'completed_date'  => date('Y-m-d'),
					 'timestamp'       => $current
	            ];
           if($ID = $this->Common->insert('customer_orders',$order))
           {                    
             for($i = 0; $i<count($_POST['ProductName']); $i++)  
             {
      			   $ProductName  = $_POST['ProductName'][$i];
      			   $Product_Id   = $_POST['ProductID'][$i];
      			   $Price        = $_POST['Price'][$i];
      			   $Quantity     = $_POST['Quantity'][$i];
      			   $Total        = $_POST['Total'][$i];
      			   
      			   $items        = [
                                    'order_id'       => $ID,
                                    'product_id'    => $Product_Id,
                                    'product_name'  => $ProductName,
                                    'price'         => $Price,
                                    'quantity'      => $Quantity,
                                    'total'         => $Total,
                                    'timestamp'     => $current
      			                   ];
			     $this->Common->insert('customer_ordered_products',$items);

                $stock_details = $this->Common->get_details('product_stock',array('product_id'=>$Product_Id))->row();
                $stocks        = $stock_details->stock;
                $stock_id      = $stock_details->stock_id;
                $new_stock     = $stocks-$Quantity;
                if($stocks>=$Quantity)
                {
                    $stock_array = [
                                     'stock' => $new_stock
                                   ];
                }
                else
                {
                    $stock_array = [
                                     'stock' => '0'
                                   ];
                }
                $this->Common->update('stock_id',$stock_id,'product_stock',$stock_array);
            }

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
            		                     'added_on'    => $current,
                                     'added_by'    => 'retailer'
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
                                   // 'invoice_no'     => $invoice_no,
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
        $amount_recieved = $this->security->xss_clean($this->input->post('amount_recived'));

        $GrandTotal= 0;     
        for($i = 0; $i<count($_POST['ProductName']); $i++) 
        {
             $Total=$_POST['Total'][$i]; 
             $GrandTotal=$GrandTotal+$Total;
             
        };
        $credit        = $GrandTotal-$amount_recieved; 
       
        $customer        = $this->Common->get_details('customers',array('customer_id'=>$customer_id))->row();
        $customer_name  = $customer->name_english;
        $customer_phone = $customer->customer_phone;

        
	      $order      = [
		                     'customer_id'     => $customer_id,
		                     'customer_name'   => $customer_name,
		                     'customer_phone'  => $customer_phone,
		                     'total'           => $GrandTotal,
                             'amount_received' => $amount_recieved,
		                     'billing_date'    => date('Y-m-d'),
		                     'billing_time'    => date('H:i:s'),
		                     'retailer_id'     => $retailer_id,
		                     'credit_balance'  => $credit,
                             'type'            => 'retail',
                             'added_by'        => 'retailer',
                             'status'          => 'completed',
                             'completed_date'  => date('Y-m-d'),
							 'timestamp'       => $current
			           ];
         if($ID = $this->Common->insert('customer_orders',$order))
         { 
            for($i = 0; $i<count($_POST['ProductName']); $i++)  
            {
               $ProductName  = $_POST['ProductName'][$i];
               $Product_Id   = $_POST['ProductID'][$i];
               $Price        = $_POST['Price'][$i];
               $Quantity     = $_POST['Quantity'][$i];
               $Total        = $_POST['Total'][$i];
               
               $items        = [
                                    'order_id'      => $ID,
                                    'product_id'    => $Product_Id,
                                    'product_name'  => $ProductName,
                                    'price'         => $Price,
                                    'quantity'      => $Quantity,
                                    'total'         => $Total,
                                    'timestamp'     => $current
                               ];
                               // print_r($items);
                $this->Common->insert('customer_ordered_products',$items);

                $stock_details = $this->Common->get_details('product_stock',array('product_id'=>$Product_Id))->row();
                $stocks        = $stock_details->stock;
                $stock_id      = $stock_details->stock_id;
                $new_stock     = $stocks-$Quantity;
                if($stocks>=$Quantity)
                {
                    $stock_array = [
                                     'stock' => $new_stock
                                   ];
                }
                else
                {
                    $stock_array = [
                                     'stock' => '0'
                                   ];
                }
                $this->Common->update('stock_id',$stock_id,'product_stock',$stock_array);
            }

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
                                   // 'invoice_no'     => $invoice_no,
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
		$sales    = $this->Common->get_details('customer_orders',array('order_id'=>$id))->row();
		$sales->details = $this->Common->get_details('customer_ordered_products',array('order_id'=>$id))->result();
		foreach($sales->details as $detail)
		{
			$detail->product_name = $this->Common->get_details('products',array('product_id'=>$detail->product_id))->row()->product_name;
		}
		$data['sales'] = $sales;
		$this->load->view('retailer/billing/details',$data);
	}

    public function invoice($id)
	{
		$sales    = $this->Common->get_details('customer_orders',array('order_id'=>$id))->row();
		// $sales->address = $this->Common->get_details('customers',array('customer_id'=>$sales->customer_id))->row()->customer_address;
		$retailer = $this->Common->get_details('retail_managers',array('rmanager_id'=>$sales->retailer_id))->row();
		$sales->retailer_name = $retailer->name;
		$sales->retailer_phone= $retailer->phone;
		$sales->details = $this->Common->get_details('customer_ordered_products',array('order_id'=>$id))->result();
		foreach($sales->details as $detail)
		{
			$detail->product_name = $this->Common->get_details('products',array('product_id'=>$detail->product_id))->row()->product_name;
		}
		$data['sales'] = $sales;
		$this->load->view('retailer/billing/print',$data);
	}
	
	public function addCustomer()
	{
		date_default_timezone_set('Asia/Kolkata');
        $timestamp = date('Y-m-d H:i:s');
        $user      = $this->session->userdata['retailer'];
        $retailer_id= $user['retailer_id'];
       
        $phone        = $this->security->xss_clean($this->input->post('phone'));
        $name         = substr($phone, -4);
        
        $phonecheck  = $this->Common->get_details('customers',array('customer_phone'=>$phone));
        if($phonecheck->num_rows()>0)
        {
            $this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Mobile number already registered..!');
			redirect('retailer/bill/new');
        }
        else
        {
    	    $array        = [
    							'name_english'         => 'Customer'.$name,
    							'customer_phone'       => $phone,
    							'customer_image'       => 'uploads/admin/customers/user.png',
    							'status'               => '1',
    							'added_by'             => 'retailer',
    							'added_retailer'       => $retailer_id,
    							'timestamp'            => $timestamp
    				        ];
    		if ($this->Common->insert('customers',$array)) 
    		{
    			$this->session->set_flashdata('alert_type', 'success');
    			$this->session->set_flashdata('alert_title', 'Success');
    			$this->session->set_flashdata('alert_message', 'New customer added..!');
    			redirect('retailer/bill/new');
    		}
    		else 
    		{
    			$this->session->set_flashdata('alert_type', 'error');
    			$this->session->set_flashdata('alert_title', 'Failed');
    			$this->session->set_flashdata('alert_message', 'Failed to add customer..!');
    			redirect('retailer/bill/new');
    		}
        }	
	}
}
?>
