<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->model('Common');
			$this->load->model('android/M_order','order');
	}
	
    public function placeOrder()
	{   
	    date_default_timezone_set('Asia/Kolkata');
		$timestamp     = date('Y-m-d H:i:s');
		$date          = date('Y-m-d');
		$time          = date('h:i A');
		
		$delivery_date = $this->security->xss_clean($this->input->post('delivery_date'));
		$delivery_time = $this->security->xss_clean($this->input->post('delivery_time'));
		$customer_id   = $this->security->xss_clean($this->input->post('customer_id'));
        
        $building       = $this->security->xss_clean($this->input->post('building'));
    	$house          = $this->security->xss_clean($this->input->post('housenumber'));
    	$latitude       = $this->security->xss_clean($this->input->post('latitude'));
    	$longitude      = $this->security->xss_clean($this->input->post('longitude'));
    	$location       = $this->security->xss_clean($this->input->post('location'));
    	$payment_method = $this->security->xss_clean($this->input->post('payment_method'));
    	
    	$customer       = $this->Common->get_details('customers',array('customer_id'=>$customer_id))->row();
        $customer_name  = $customer->name_english;
        $customer_phone = $customer->customer_phone;
        
		$details        = $this->order->getCartData($customer_id);
		
		if(count($details) != 0)
		{
		    $total = 0;
    		foreach ($details as $item) 
    		{   
    		    $coupons_check = $this->order->getCoupon($customer_id,$item->product_id,$date); 
    			if($coupons_check->num_rows()>0)
    			{
    			   $total = $total+0;
    			}
    			else
    			{
    			  $total = $total + ( $item->price * $item->quantity );
    			}
    		}
    
    		$order = [
            			'delivery_date'  => $delivery_date,
            			'delivery_time'  => $delivery_time,
            			'customer_id'    => $customer_id,
            			'customer_name'  => $customer_name,
            			'customer_phone' => $customer_phone,
            			'total'          => $total,
            			'payment_method' => $payment_method,
            			'latitude'       => $latitude,
            			'longitude'      => $longitude,
            			'location'       => $location,
            			'status'         => 'pending',
            			'billing_date'   => date('Y-m-d'),
            			'billing_time'   => date('H:i:s'),
            			'ordered_time'   => $time,
            			'timestamp'      => $timestamp
            		];

    
    		if ($id = $this->Common->insert('customer_orders',$order)) 
    		{
    			foreach ($details as $item) 
    			{
    			    $coupon_check = $this->order->getCoupon($customer_id,$item->product_id,$date); 
    			    if($coupon_check->num_rows()>0)
    			    {
    			        $used          = $coupon_check->row()->used_coupons;
            		    $unused        = $coupon_check->row()->unused_coupons;
            		    $total_used    = $coupon_check->row()->total_coupons;
            		    $updated_used  = $used+$item->quantity;
            		    $updated_unused= $unused-$item->quantity;
            		    $purchase_id   = $coupon_check->row()->cpurchase_id;
            		    $coupon_id     = $coupon_check->row()->pack_id;
            		    
    			        $array = [  
                					'product_id'    => $item->product_id,
                					'product_name'  => $item->product_name_english,
                					'price'         => '0',
                					'quantity'      => $item->quantity,
                					'total'         => '0',
                					'order_id'      => $id,
                					'coupon_applied'=>'1',
                					'coupon_id'     => $coupon_id,
                					'timestamp'     => $timestamp
            				     ];
            			$this->Common->insert('customer_ordered_products',$array);
            			
            			if($unused>=$item->quantity)
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
    			        
    			    }
    			    else
    			    {
        				$array = [  
                					'product_id'   => $item->product_id,
                					'product_name' => $item->product_name_english,
                					'price'        => $item->price,
                					'quantity'     => $item->quantity,
                					'total'        => $item->price * $item->quantity,
                					'order_id'     => $id,
                					'timestamp'    => $timestamp
                				];
    				    $this->Common->insert('customer_ordered_products',$array);
    			    }
    			    
    				$stock_details = $this->Common->get_details('product_stock',array('product_id'=>$item->product_id))->row();
                    $stocks        = $stock_details->stock;
                    $stock_id      = $stock_details->stock_id;
                    $Quantity      = $item->quantity;
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
    			
    			$address = [
                    			'building' => $building,
                    			'house'    => $house,
                    			'location' => $location,
                    			'order_id' => $id,
                    			'timestamp'=> $timestamp 
                    		];
        
        		$this->Common->insert('ordered_address',$address);
    			
                $this->Common->delete('cart',array('customer_id' => $customer_id));
                
//                 // $drivers = $this->Common->get_details('locations',array('latitude'=>$latitude,'longitude'=>$longitude,'date'=>$date,'time'=>$time))->result();
                $drivers = $this->order->getDrivers($latitude,$longitude,$date,$time);
                // $drivers    = $this->order->getDrivers($date);
                foreach($drivers as $driver)
                {
                    // $distance  = $this->order->getMaxlocation($driver->latitude,$driver->longitude,$date,$time);
                    $device_token  = $this->Common->get_details('agencies',array('agency_id'=>$driver->agency_id))->row()->fcm_token;
                    $content       = $customer_name.'('.$customer_phone.') ,'.$building.','.$house.','.$location;
                    $this->send_notification($device_token,$content);
                }
                // print_r($drivers);
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
		}
        else
        {
            $return = [
                        'message' => 'failed'  
                      ];
        }
        print_r(json_encode($return));
	}
	
	public function send_notification($id,$content)
	{
	    $SERVER_API_KEY = "AAAAL4YgxK0:APA91bFtUJIzdLrTqJJwHoWJpnE_ZAuMGxKq2_ljF3z6Fx0LVuhTEB01MOSwVrxxtAOi4ncEzMB8vXaxgIpNnpkeo9Qb_WhNqs5D-LYXbpDD1PMR5iWDKJNU9uwYLrnrqxm7ETf41mLb";
    	$header = [
    		'Authorization: key='. $SERVER_API_KEY,
    		'Content-Type: Application/json'
    	];
    	$msg = [
    		'title' => 'New Order',
    		'body'  => $content
    	];
    	
    	$notification = [
    		'title'             => 'New Order',
    		'body'              => $content,
    		'content_available' => true
    	];
    	
    	$payload = [
    		'data'         => $msg,
    		'notification' => $notification,
    		'to'           => $id,
    		'priority'     => 10
    	];
    	$url = 'https://fcm.googleapis.com/fcm/send';
    
    	$curl = curl_init();
    
    	curl_setopt_array($curl, array(
    		 CURLOPT_URL            => "https://fcm.googleapis.com/fcm/send",
    		 CURLOPT_RETURNTRANSFER => true,
    		 CURLOPT_CUSTOMREQUEST  => "POST",
    		 CURLOPT_POSTFIELDS     => json_encode($payload),
    		 CURLOPT_HTTPHEADER     => $header,
    	));
    
    	$response = curl_exec($curl);
    	$err = curl_error($curl);
    
    	curl_close($curl);
        
        return true;
	}
	
	public function getOrders()
	{
		$customer_id = $this->security->xss_clean($this->input->post('customer_id'));
		$orders = $this->order->getOrders($customer_id);
		$return = [
        			'message' => 'success',
        			'orders'  => $orders
        		  ];
		print_r(json_encode($return));
	}
	
	public function getOrderDetails()
	{
		$order_id   = $this->security->xss_clean($this->input->post('order_id'));
		$order      = $this->order->getOrderDetails($order_id);
		$products   = $this->order->getOrderedProducts($order_id);
		$address    = $this->Common->get_details('ordered_address',array('order_id' => $order_id))->row();

		$return     = [
            			'message'  => 'success',
            			'order'    => $order,
            			'products' => $products,
            			'address'  => $address,
        		     ];
		print_r(json_encode($return));
	}


}
