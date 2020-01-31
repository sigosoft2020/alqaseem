<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->model('Common');
			$this->load->model('android/M_order','orders');
	}
	
	public function accept()
	{   
	    $agency_id = $this->security->xss_clean($this->input->post('agency_id'));
		$order_id  = $this->security->xss_clean($this->input->post('order_id'));

		$status     = [
			             'status'          => 'processing',
			             'agency_assigned' => '1'
		              ];
		if ($this->Common->update('order_id',$order_id,'customer_orders',$status)) 
		{   
		    $orders = [
		                'order_id'      => $order_id,
		                'agency_id'     => $agency_id,
		                'status'        => 'assigned',
		                'assigned_time' => date('Y-m-d H:i:s'),
		                'timestamp'     => date('Y-m-d H:i:s')
		              ];
		    $this->Common->insert('agency_orders',$orders);
		    
			$return = [
				        'message' => 'success'
			          ];
		}
		else 
		{
			$return = [
			        	'message' => 'failed'
			          ];
		}
		print_r(json_encode($return));
	}
	
	public function newRequests()
	{   
	    $date      = date('Y-m-d');
	    $agency_id = $this->security->xss_clean($this->input->post('agency_id'));
	    $locations = $this->orders->getLatestLocation($agency_id);
	    if($locations!='')
	    {
    	    $latitude  = $locations->latitude;
    	    $longitude = $locations->longitude;
    	    
    	    $orders    = $this->orders->getNewRequests($latitude,$longitude,$date);
    	   // print_r($orders);
    	    foreach($orders as $order)
    	    {   
    	        $status_check    = $this->Common->get_details('agency_rejected_orders',array('order_id'=>$order->order_id,'agency_id'=>$agency_id));
    	        if($status_check->num_rows()>0)
    	        {
    	            $order->reject_status = true;
    	        }
    	        else
    	        {
    	            $order->reject_status = false;
    	        }
    	        
    	        $order->products = $this->orders->getOrderedProducts($order->order_id);
    	        $order->address  = $this->Common->get_details('ordered_address',array('order_id'=>$order->order_id))->row();
    	        
    	    }
	    }
	    else
	    {
	        $orders = '';
	    }
	    
	    
	    $return    = [
	                   'message'  => 'success',
	                   'orders'   => $orders
	                 ];
	    print_r(json_encode($return));               
	}
	
	public function approvedRequests()
	{
	    $agency_id = $this->security->xss_clean($this->input->post('agency_id'));
	    $orders    = $this->orders->getApprovedRequests($agency_id);
	    foreach($orders as $order)
	    {   
	        $order->products = $this->orders->getOrderedProducts($order->order_id);
	        $order->address  = $this->Common->get_details('ordered_address',array('order_id'=>$order->order_id))->row();
	    }
	    
	    $return    = [
	                   'message'  => 'success',
	                   'orders'   => $orders
	                 ];
	    print_r(json_encode($return));               
	}
	
	public function completedRequests()
	{
	    $agency_id = $this->security->xss_clean($this->input->post('agency_id'));
	    $orders    = $this->orders->getCompletedRequests($agency_id);
	    foreach($orders as $order)
	    {   
	        $order->products = $this->orders->getOrderedProducts($order->order_id);
	        $order->address  = $this->Common->get_details('ordered_address',array('order_id'=>$order->order_id))->row();
	    }
	    
	    $return    = [
	                   'message'  => 'success',
	                   'orders'   => $orders
	                 ];
	    print_r(json_encode($return));               
	}
	
	public function getOrderDetails()
	{
	    $order_id        = $this->security->xss_clean($this->input->post('order_id'));
	    $order           = $this->orders->getOrderDetails($order_id);
	    $order->products = $this->orders->getOrderedProducts($order_id);
	    $order->address  = $this->Common->get_details('ordered_address',array('order_id'=>$order_id))->row();
	    
	    $return          = [
	                         'message'    => 'success',
	                         'order'      => $order
	                       ];
	   print_r(json_encode($return));                       
	}
	
	public function rejectOrder()
	{
	   date_default_timezone_set('Asia/Kolkata');
		
	   $order_id        = $this->security->xss_clean($this->input->post('order_id'));
	   $agency_id       = $this->security->xss_clean($this->input->post('agency_id'));
	   
	   $array           = [
	                         'agency_id'  => $agency_id,
	                         'order_id'   => $order_id,
	                         'timestamp'  => date('Y-m-d H:i:s')
	                      ];
	  if($this->Common->insert('agency_rejected_orders',$array))
	  {
	      $return      = [
	                        'message'    => 'success'
	                     ];
	  }
	  else
	  {
	      $return      = [
	                        'message'    => 'failed'
	                     ];
	  }
	  print_r(json_encode($return));
	}
	
	public function completeOrder()
	{
	   date_default_timezone_set('Asia/Kolkata');
	   $time          = date('h:i A');
		
	   $order_id        = $this->security->xss_clean($this->input->post('order_id'));
	   $agency_id       = $this->security->xss_clean($this->input->post('agency_id'));
	   $payment_method  = $this->security->xss_clean($this->input->post('payment_method'));
	   $amount_received = $this->security->xss_clean($this->input->post('amount_received'));
	   $credit_amount   = $this->security->xss_clean($this->input->post('credit_amount'));
	   
	   $order           = $this->Common->get_details('customer_orders',array('order_id'=>$order_id))->row();
	   $agency_order    = $this->Common->get_details('agency_orders',array('order_id'=>$order_id,'agency_id'=>$agency_id))->row()->aorder_id;
	   $customer_id     = $order->customer_id;
	   $total           = $order->total;
	   
	   $order_status    = [
	                         'payment_method'  => $payment_method,
	                         'amount_received' => $amount_received,
	                         'credit_balance'  => $credit_amount,
	                         'status'          => 'completed',
	                         'completed_date'  => date('Y-m-d'),
	                         'completed_time'  => $time
	                      ];
	                      
	   $agency_status   = [
	                         'status'          => 'completed',
	                         'completed_time'  => date('Y-m-d H:i:s')        
	                      ];     
	                      
	   if($this->Common->update('order_id',$order_id,'customer_orders',$order_status))
	   {
	       $this->Common->update('aorder_id',$agency_order,'agency_orders',$agency_status);
	       
	       if($total>0)
	       {
	           $payment  = [
	                         'customer_id'  => $customer_id,
	                         'amount_paid'  => $total,
	                         'type'         => 'agency',
	                         'agency_id'    => $agency_id,
	                         'timestamp'    => date('Y-m-d H:i:s')
	                       ];
	           $this->Common->insert('payment_history',$payment);                
	       }
	       
	       $return  = [
	                     'message'   => 'success'
	                  ];
	   }
	   else
	   {
	       $return  = [
	                     'message'    => 'failed'
	                  ];
	   }
	   print_r(json_encode($return));                   
	}
	
	public function newSaleWithCoupon()
	{   
	    date_default_timezone_set('Asia/Kolkata');
		$timestamp     = date('Y-m-d H:i:s');
		$time          = date('h:i A');
		
		$customer_id    = $this->security->xss_clean($this->input->post('customer_id'));
		$agency_id      = $this->security->xss_clean($this->input->post('agency_id'));
    	$latitude       = $this->security->xss_clean($this->input->post('latitude'));
    	$longitude      = $this->security->xss_clean($this->input->post('longitude'));
    	$location       = $this->security->xss_clean($this->input->post('location'));
    	$payment_method = $this->security->xss_clean($this->input->post('payment_method'));
    	$total          = $this->security->xss_clean($this->input->post('total'));
    	
    	$coupons        = $this->security->xss_clean($this->input->post('coupon_id'));
        $returns        = $this->security->xss_clean($this->input->post('return_bottles'));
        $Quantity       = $this->security->xss_clean($this->input->post('quantity'));
    	
    	$customer       = $this->Common->get_details('customers',array('customer_id'=>$customer_id))->row();
        $customer_name  = $customer->name_english;
        $customer_phone = $customer->customer_phone;
        
		$order = [
        			'customer_id'    => $customer_id,
        			'customer_name'  => $customer_name,
        			'customer_phone' => $customer_phone,
        			'total'          => $total,
        			'amount_received'=> $total,
        			'payment_method' => $payment_method,
        			'latitude'       => $latitude,
        			'longitude'      => $longitude,
        			'location'       => $location,
        			'status'         => 'completed',
        			'billing_date'   => date('Y-m-d'),
        			'billing_time'   => date('H:i:s'),
        			'completed_date' => date('Y-m-d'),
        			'completed_time' => date('h:i A'),
        			'ordered_time'   => $time,
        			'timestamp'      => $timestamp
        		];


		if ($id = $this->Common->insert('customer_orders',$order)) 
		{
		    $coupon_ids     = json_decode($coupons , true);
            $return_bottles = json_decode($returns , true);
            $quantities     = json_decode($Quantity , true);
            
           $i=0;
		   foreach ($coupon_ids as $coup)
           {   
                $quantity       = $quantities[$i];
                $return_bottle  = $return_bottles[$i];
                $coupon_id      = $coup;
                
                $coupon         = $this->Common->get_details('coupon_packages',array('cpack_id'=>$coupon_id))->row();
                $product_id     = $coupon->product_id;
        
                $product        = $this->Common->get_details('products',array('product_id'=>$product_id))->row();
                $product_name   = $product->product_name;
                $price          = $product->price;
                $total          = '0';
                
    			$array = [  
        					'product_id'   => $product_id,
        					'product_name' => $product_name,
        					'price'        => $price,
        					'quantity'     => $quantity,
        					'total'        => $total,
        					'order_id'     => $id,
        					'returned_bottles' => $return_bottle,
        					'coupon_applied'   => '1',
        					'coupon_id'        => $coup,
        					'timestamp'        => $timestamp
        				 ];
    			$this->Common->insert('customer_ordered_products',$array);
    			
    			$stock_details = $this->Common->get_details('product_stock',array('product_id'=>$product_id))->row();
                $stocks        = $stock_details->stock;
                $stock_id      = $stock_details->stock_id;
                $Quant         = $quantity;
                $new_stock     = $stocks-$Quant;
                if($stocks>=$Quant)
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
                
                $purchased   = $this->Common->get_details('coupon_purchases',array('pack_id'=>$coupon_id,'customer_id'=>$customer_id))->row();
    		    $used        = $purchased->used_coupons;
    		    $unused      = $purchased->unused_coupons;
    		    $total_used  = $purchased->total_coupons;
    		    $updated_used  = $used+$quantity;
    		    $updated_unused= $unused-$quantity;
    		    $purchase_id   = $purchased->cpurchase_id;
    		    
    		    if($unused>=$Quant)
    		    {
    		       $bottles = [
    		                     'used_coupons'  => $updated_used,
    		                     'unused_coupons'=> $updated_unused
    		                  ]; 
    		    }
    		    else
    		    {
    		        $bottles = [
    		                     'used_coupons'  => $total_used,
    		                     'unused_coupons'=> 0
    		                   ]; 
    		    }
    		    $this->Common->update('cpurchase_id',$purchase_id,'coupon_purchases',$bottles);
    		    $i++;
           }
           
           $agency_array  = [
                              'order_id'   => $id,
                              'agency_id'  => $agency_id,
                              'status'     => 'completed',
                              'assigned_time' => date('Y-m-d H:i:s'),
                              'completed_time'=> date('Y-m-d H:i:s'),
                              'timestamp'     => date('Y-m-d H:i:s')
                            ];
           $this->Common->insert('agency_orders',$agency_array);
           
           $return  = [
        		         'message' => 'success'  
        		      ];
		}
		else 
		{
			$return = [
				        'message' => 'failed'
			          ];
		}

    print_r(json_encode($return));
	}
	
	public function newSaleWithoutCoupon()
	{   
	    date_default_timezone_set('Asia/Kolkata');
		$timestamp     = date('Y-m-d H:i:s');
		$time          = date('h:i A');
		
		$customer_id    = $this->security->xss_clean($this->input->post('customer_id'));
		$agency_id      = $this->security->xss_clean($this->input->post('agency_id'));
    	$latitude       = $this->security->xss_clean($this->input->post('latitude'));
    	$longitude      = $this->security->xss_clean($this->input->post('longitude'));
    	$location       = $this->security->xss_clean($this->input->post('location'));
    	$payment_method = $this->security->xss_clean($this->input->post('payment_method'));
    	$total          = $this->security->xss_clean($this->input->post('total'));
    	
    	$products       = $this->security->xss_clean($this->input->post('product_id'));
        $returns        = $this->security->xss_clean($this->input->post('return_bottles'));
        $Quantity       = $this->security->xss_clean($this->input->post('quantity'));
        $Prices         = $this->security->xss_clean($this->input->post('price'));
        $total          = $this->security->xss_clean($this->input->post('total'));
        
    	$customer       = $this->Common->get_details('customers',array('customer_id'=>$customer_id))->row();
        $customer_name  = $customer->name_english;
        $customer_phone = $customer->customer_phone;
        
		$order = [
        			'customer_id'    => $customer_id,
        			'customer_name'  => $customer_name,
        			'customer_phone' => $customer_phone,
        			'total'          => $total,
        			'amount_received'=> $total,
        			'payment_method' => $payment_method,
        			'latitude'       => $latitude,
        			'longitude'      => $longitude,
        			'location'       => $location,
        			'status'         => 'completed',
        			'billing_date'   => date('Y-m-d'),
        			'billing_time'   => date('H:i:s'),
        			'completed_date' => date('Y-m-d'),
        			'completed_time' => date('h:i A'),
        			'ordered_time'   => $time,
        			'timestamp'      => $timestamp
        		];


		if ($id = $this->Common->insert('customer_orders',$order)) 
		{
		    $product        = json_decode($products , true);
            $return_bottles = json_decode($returns , true);
            $quantities     = json_decode($Quantity , true);
            $prices         = json_decode($Prices, true);        
            
           $i=0;
		   foreach ($product as $prod)
           {   
                $quantity       = $quantities[$i];
                $return_bottle  = $return_bottles[$i];
                $product_id     = $prod;
                $price          = $prices[$i];
        
                $product        = $this->Common->get_details('products',array('product_id'=>$product_id))->row();
                $product_name   = $product->product_name;
                $total          = $price*$quantity;
                
    			$array = [  
        					'product_id'   => $product_id,
        					'product_name' => $product_name,
        					'price'        => $price,
        					'quantity'     => $quantity,
        					'total'        => $total,
        					'order_id'     => $id,
        					'returned_bottles' => $return_bottle,
        					'timestamp'        => $timestamp
        				 ];
    			$this->Common->insert('customer_ordered_products',$array);
    			
    			$stock_details = $this->Common->get_details('product_stock',array('product_id'=>$product_id))->row();
                $stocks        = $stock_details->stock;
                $stock_id      = $stock_details->stock_id;
                $Quant         = $quantity;
                $new_stock     = $stocks-$Quant;
                if($stocks>=$Quant)
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
                
    		    $i++;
           }
           
           $agency_array  = [
                              'order_id'   => $id,
                              'agency_id'  => $agency_id,
                              'status'     => 'completed',
                              'assigned_time' => date('Y-m-d H:i:s'),
                              'completed_time'=> date('Y-m-d H:i:s'),
                              'timestamp'     => date('Y-m-d H:i:s')
                            ];
           $this->Common->insert('agency_orders',$agency_array);
           
           $return  = [
        		         'message' => 'success'  
        		      ];
		}
		else 
		{
			$return = [
				        'message' => 'failed'
			          ];
		}

    print_r(json_encode($return));
	}
	
	
}
