<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bill extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('admin/M_history','history');
			$this->load->model('admin/M_cofillRequests','cofill');
			$this->load->model('Common');
			if (!admin()) {
				redirect('users/admin');
			}
	}
   //retail filling starts here//
     public function retailBill()
     {
        $data['customers']  = $this->Common->get_details('customers',array('status'=>'1'))->result();
        $data['products']   = $this->Common->get_details('products',array('product_status'=>'1'))->result();
        $this->load->view('admin/bill/newRetailBill',$data);
     }

    public function addData()
    {
        date_default_timezone_set('Asia/Kolkata');
        $current     = date('Y-m-d H:i:s');
        
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

        $order         = [
                             'customer_id'     => $customer_id,
                             'customer_name'   => $customer_name,
                             'customer_phone'  => $customer_phone,
                             'total'           => $GrandTotal,
                             'amount_received' => $amount_recieved,
                             'billing_date'    => date('Y-m-d'),
                             'billing_time'    => date('H:i:s'),
                             'credit_balance'  => $credit,
                             'type'            => 'retail',
                             'added_by'        => 'admin',
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
                $pending_check      = $this->Common->get_details('customer_pending_payments',array('customer_id'=>$customer_id,'type'=>'admin'));
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
                                         'type'        => 'admin',
                                         'added_on'    => $current,
                                         'added_by'    => 'admin'
                                       ];
                    $this->Common->insert('customer_pending_payments',$payment_pending);     
                }
                
                $wallett_check = $this->Common->get_details('customer_wallet',array('customer_id'=>$customer_id,'type'=>'credit'));
                if($wallett_check->num_rows()>0)
                {
                    $amount     = $wallett_check->row()->amount;
                    $new_amount = $credit+$amount;
                    $wallet_id  = $wallett_check->row()->wallet_id;
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
                                   'type'           =>'admin',
                                   'timestamp'      =>$current
                                ];
            $this->Common->insert('payment_history',$payment_history);

            $this->session->set_flashdata('alert_type', 'success');
            $this->session->set_flashdata('alert_title', 'Success');
            $this->session->set_flashdata('alert_message', 'Bill created sucessfully..!');
            redirect('admin/bill/history');
        }
        else
        {
            $this->session->set_flashdata('alert_type', 'error');
            $this->session->set_flashdata('alert_title', 'Failed');
            $this->session->set_flashdata('alert_message', 'Failed to create bill..!');
            redirect('admin/bill/new');
        }
        
    }
   //retail filling ends here//

   //co filling starts here//
    public function co_fill()
    {   
        $this->load->view('admin/bill/co_filling_requets');
    }

    public function get_co_fill_requests()
    {
        $result = $this->cofill->make_datatables();
        $data = array();
        foreach ($result as $res) {
            $sub_array = array();
            $sub_array[] = $res->customer_name;
            $sub_array[] = date('d-M-Y h:i A',strtotime($res->timestamp));
            $sub_array[] = 'Pending';
            $sub_array[] = '<a href="' . site_url('admin/bill/cofill_bill/'.$res->co_request_id) . '" class="btn btn-primary" style="font-size:12px;">Bill Now</a>';
            $data[] = $sub_array;
        }

        $output = array(
            "draw"   => intval($_POST['draw']),
            "recordsTotal" => $this->cofill->get_all_data(),
            "recordsFiltered" => $this->cofill->get_filtered_data(),
            "data" => $data
        );
        echo json_encode($output);
    }

    public function cofill_bill($id)
    {   
      $request         = $this->Common->get_details('co_filling_requests',array('co_request_id'=>$id))->row();
        $request->products= $this->Common->get_details('cofill_req_products',array('co_req_id'=>$id))->result();
        foreach($request->products as $req)
        {
            $product      = $this->Common->get_details('products',array('product_id'=>$req->product_id))->row();
            $req->product = $product->product_name;
            $req->price   = $product->default_price;
            $req->stock   = $this->Common->get_details('product_stock',array('product_id'=>$req->product_id))->row()->stock;
            $req->total   = $req->quantity*$req->price;
        }
        $data['request'] = $request;
        $this->load->view('admin/bill/cofill_request_bill',$data);
    }

    public function addCofillData()
    {
        date_default_timezone_set('Asia/Kolkata');
        $current     = date('Y-m-d H:i:s');
        
        $customer_id     = $this->security->xss_clean($this->input->post('customer_id'));
        $request_id      = $this->security->xss_clean($this->input->post('request_id'));
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

        $order         = [
                             'customer_id'     => $customer_id,
                             'customer_name'   => $customer_name,
                             'customer_phone'  => $customer_phone,
                             'total'           => $GrandTotal,
                             'amount_received' => $amount_recieved,
                             'billing_date'    => date('Y-m-d'),
                             'billing_time'    => date('H:i:s'),
                             'credit_balance'  => $credit,
                             'type'            => 'co_filling',
                             'added_by'        => 'admin',
                             'timestamp'       => $current,
                             'status'          => 'completed',
                             'completed_date'  => date('Y-m-d')
                        ];
         if($ID = $this->Common->insert('customer_orders',$order))
         { 
           $products  = $_POST['ProductID'];  
           // print_r($products);                  
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
                $pending_check      = $this->Common->get_details('customer_pending_payments',array('customer_id'=>$customer_id,'type'=>'admin'));
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
                                         'type'        => 'admin',
                                         'added_on'    => $current
                                       ];
                    $this->Common->insert('customer_pending_payments',$payment_pending);     
                }
                
                $wallett_check = $this->Common->get_details('customer_wallet',array('customer_id'=>$customer_id,'type'=>'credit'));
                if($wallett_check->num_rows()>0)
                {
                    $amount     = $wallett_check->row()->amount;
                    $new_amount = $credit+$amount;
                    $wallet_id  = $wallett_check->row()->wallet_id;
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
                                   'type'           =>'admin',
                                   'timestamp'      =>$current
                                ];
            $this->Common->insert('payment_history',$payment_history);

            $request_status   = [
                                  'status' => 'completed'
                                ];
            $this->Common->update('co_request_id',$request_id,'co_filling_requests',$request_status);

            $this->session->set_flashdata('alert_type', 'success');
            $this->session->set_flashdata('alert_title', 'Success');
            $this->session->set_flashdata('alert_message', 'Bill created sucessfully..!');
            redirect('admin/bill/history');
        }
        else
        {
            $this->session->set_flashdata('alert_type', 'error');
            $this->session->set_flashdata('alert_title', 'Failed');
            $this->session->set_flashdata('alert_message', 'Failed to create bill..!');
            redirect('admin/bill/co_fill');
        }
        
    }

  //co filling ends here//

  //order history starts here//
	public function history()
	{
        $orders     = $this->history->get_history();
        foreach($orders as $order)
        {
           if($order->added_by=='customer')
           {
            $agency_id  = $this->Common->get_details('agency_orders',array('order_id'=>$order->order_id))->row()->agency_id;
            $agency_check = $this->Common->get_details('agencies',array('agency_id'=>$agency_id));
            if($agency_check->num_rows()>0)
            {
                $order->agency = $agency_check->row()->agency_name;
            }
            else
            {
                $order->agency = '';
            }
           }
        }
        $data['orders'] = $orders;
		$this->load->view('admin/bill/history',$data);
	}
  //order history ends here//


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

  public function get_customer()
  {
    $customer_id     =  $this->input->post('id');
    $check           = $this->Common->get_details('customer_wallet',array('customer_id'=>$customer_id,'amount>'=>'0','type'=>'debit'));
    if($check->num_rows()>0)
    {
        $data['wallet']   = $check->row();
        $data['status']     = '1';
    }
    else
    {
        $data['wallet']   = '';
        $data['status']   = '0';
    }
    print_r(json_encode($data));
  }


    public function invoice($id)
	{   
        $order           = $this->Common->get_details('customer_orders',array('order_id'=>$id))->row();
		$order->products = $this->Common->get_details('customer_ordered_products',array('order_id'=>$id))->result(); 

        $data['order']  = $order;
		$this->load->view('admin/bill/invoice',$data);
	}

  //agency billing starts here//
    public function agencyBill()
    {
        $data['agencies'] = $this->Common->get_details('agencies',array('agency_status'=>'1'))->result();
        $data['products'] = $this->Common->get_details('products',array('product_status'=>'1'))->result();
        $this->load->view('admin/bill/agency_bill',$data);

    }


    public function addAgencyBillData()
    {
        date_default_timezone_set('Asia/Kolkata');
        $current     = date('Y-m-d H:i:s');
        
        $agency_id     = $this->security->xss_clean($this->input->post('agency_id'));
        // $amount_recieved = $this->security->xss_clean($this->input->post('amount_recived'));
        $GrandTotal= 0;     
        for($i = 0; $i<count($_POST['ProductName']); $i++) 
        {
             $Total=$_POST['Total'][$i]; 
             $GrandTotal=$GrandTotal+$Total;
             
        };
       
        $agency        = $this->Common->get_details('agencies',array('agency_id'=>$agency_id))->row();
        $agency_name = $agency->agency_name;

        $order         = [
                             'agency_id'     => $agency_id,
                             'agency_name'   => $agency_name,
                             'total'           => $GrandTotal,
                             // 'amount_received' => $amount_recieved,
                             'sale_date'    => date('Y-m-d'),
                             'sale_time'    => date('H:i:s'),
                             // 'credit_balance'  => $credit,
                             'added_by'        => 'a',
                             'timestamp'       => $current
                        ];
         if($ID = $this->Common->insert('agency_sales',$order))
         {                    
           for($i = 0; $i<count($_POST['ProductName']); $i++)  
           {
               $ProductName  = $_POST['ProductName'][$i];
               $Product_Id   = $_POST['ProductID'][$i];
               $Price        = $_POST['Price'][$i];
               $Quantity     = $_POST['Quantity'][$i];
               $Total        = $_POST['Total'][$i];
               
               $items        = [
                                    'as_id'      => $ID,
                                    'product_id'    => $Product_Id,
                                    'product_name'  => $ProductName,
                                    'price'         => $Price,
                                    'quantity'      => $Quantity,
                                    'total'         => $Total,
                                    'timestamp'     => $current
                               ];
                $this->Common->insert('agency_sale_products',$items);

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

            $invoice_no    = 'INVAS'.$ID;
            $array_invoice = [
                              'invoice_no' => $invoice_no
                             ];
            $this->Common->update('as_id',$ID,'agency_sales',$array_invoice);


            $this->session->set_flashdata('alert_type', 'success');
            $this->session->set_flashdata('alert_title', 'Success');
            $this->session->set_flashdata('alert_message', 'Bill created sucessfully..!');
            redirect('admin/bill/agencyBill');
        }
        else
        {
            $this->session->set_flashdata('alert_type', 'error');
            $this->session->set_flashdata('alert_title', 'Failed');
            $this->session->set_flashdata('alert_message', 'Failed to create bill..!');
            redirect('admin/bill/agencyBill');
        }
        
    }

    public function agencyBillHistory()
    {
        $orders     = $this->history->get_agencyHistory();
        
        $data['orders'] = $orders;
        $this->load->view('admin/bill/agency_history',$data);
    }

    public function agencyInvoice($id)
    {
        $order           = $this->Common->get_details('agency_sales',array('as_id'=>$id))->row();
        $order->agency_phone = $this->Common->get_details('agencies',array('agency_id'=>$order->agency_id))->row()->agency_phone;
        $order->products     = $this->Common->get_details('agency_sale_products',array('as_id'=>$id))->result(); 

        $data['order']  = $order;
        $this->load->view('admin/bill/agency_invoice',$data);
    }
  //agency billing ends here// 
  
  	public function addCustomer()
	{
		date_default_timezone_set('Asia/Kolkata');
        $timestamp = date('Y-m-d H:i:s');
        
        $phone        = $this->security->xss_clean($this->input->post('phone'));
        $name         = substr($phone, -4);
        
        $phonecheck  = $this->Common->get_details('customers',array('customer_phone'=>$phone));
        if($phonecheck->num_rows()>0)
        {
            $this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Mobile number already registered..!');
			redirect('admin/bill/retailBill');
        }
        else
        {
    	    $array        = [
    							'name_english'         => 'Customer'.$name,
    							'customer_phone'       => $phone,
    							'customer_image'       => 'uploads/admin/customers/user.png',
    							'status'               => '1',
    							'added_by'             => 'admin',
    							'timestamp'            => $timestamp
    				        ];
    		if ($this->Common->insert('customers',$array)) 
    		{
    			$this->session->set_flashdata('alert_type', 'success');
    			$this->session->set_flashdata('alert_title', 'Success');
    			$this->session->set_flashdata('alert_message', 'New customer added..!');
    			redirect('admin/bill/retailBill');
    		}
    		else 
    		{
    			$this->session->set_flashdata('alert_type', 'error');
    			$this->session->set_flashdata('alert_title', 'Failed');
    			$this->session->set_flashdata('alert_message', 'Failed to add customer..!');
    			redirect('retailer/bill/retailBill');
    		}
        }	
	}
}
?>
