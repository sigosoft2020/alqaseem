<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupons extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->model('Common');
			$this->load->model('android/M_coupon','coupon');
			$this->load->model('android/M_order','orders');
	}
	
    public function index()
	{
	    $coupons   = $this->coupon->getCoupon();
	    $return    = [
	                    'message'   => 'success',
	                    'coupons'   => $coupons
	                 ];
	    print_r(json_encode($return));
	}
	
	public function getCouponPurchases()
	{
       $coupon_id = $this->input->post('coupon_id');
    //   $orders    = $this->coupon->getOrdersByCopons($coupon_id);
       $orders    = $this->coupon->getCouponPurchases($coupon_id);
      foreach($orders as $order)
      {
          $customer              = $this->Common->get_details('customers',array('customer_id'=>$order->customer_id))->row();
          $order->customer_name  = $customer->name_english;
          $order->customer_phone = $customer->customer_phone;
          $order->customer_address = $customer->customer_address;
      }
       $return    = [
                       'message'   => 'success',
                       'orders'    => $orders 
                    ];
        print_r(json_encode($return));                
	}
	
	public function newSale()
	{
	   date_default_timezone_set('Asia/Kolkata');
	   $date          = date('Y-m-d');
	   $time          = date('h:i A');
		
	   $customer_id        = $this->security->xss_clean($this->input->post('customer_id'));
	   $coupon_id          = $this->security->xss_clean($this->input->post('coupon_id'));
	   $price              = $this->security->xss_clean($this->input->post('price'));
	   $payment_method     = $this->security->xss_clean($this->input->post('payment_method')); 
	   
	   $coupon             = $this->Common->get_details('coupon_packages',array('cpack_id'=>$coupon_id))->row();
	   $validity           = $coupon->pack_validity;
	   $count              = $coupon->no_of_bottles;
	   $product_id         = $coupon->product_id;
	   $expirydate         = date("Y-m-d", strtotime($date ." +".$validity."day") );
	   
	   $array              = [
	                            'pack_id'       => $coupon_id,
	                            'product_id'    => $product_id,
	                            'customer_id'   => $customer_id,
	                            'total_coupons' => $count,
	                            'used_coupons'  => '0',
	                            'unused_coupons'=> $count,
	                            'coupon_price'  => $price,
	                            'start_date'    => date('Y-m-d'),
	                            'expiry_date'   => $expirydate,
	                            'payment_method'=> $payment_method,
	                            'timestamp'     => date('Y-m-d H:i:s')
	                         ];
	  
	  if($this->Common->insert('coupon_purchases',$array))
	  {
	      $return = [
	                   'message'  => 'success'
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
	
	public function getCouponDetails()
	{
       $coupon_id = $this->input->post('coupon_id');
       $coupon    = $this->Common->get_details('coupon_packages',array('cpack_id'=>$coupon_id,'status'=>'1'))->row();
       $return    = [
                       'message'   => 'success',
                       'coupon'    => $coupon 
                    ];
        print_r(json_encode($return));                
	}
	
	public function customerCoupon()
	{   
	    $customer_id    = $this->input->post('customer_id');
	    $customer_check = $this->Common->get_details('coupon_purchases',array('customer_id'=>$customer_id));
	    if($customer_check->num_rows()>0)
	    {
	        $coupons  = $this->coupon->getCoupons($customer_id);
	    }
	    else
	    {
	       $coupons  = array(); 
	    }
	    
	    $return   = [
	                  'message' => 'success',
	                  'data'    => $coupons
	                ];
		print_r(json_encode($return));
	}
    
    public function customerCouponStatus()
	{   
	    $date           = date('Y-m-d');
	    $customer_id    = $this->input->post('customer_id');
	    $coupon_check = $this->Common->get_details('coupon_purchases',array('customer_id'=>$customer_id,'expiry_date>='=>$date,'unused_coupons>'=>'0'));
	    if($coupon_check->num_rows()>0)
	    {
	       $status   = true;
	    }
	    else
	    {
	       $status = false;
	    }
	    
	    $return   = [
	                  'message' => 'success',
	                  'data'    => $status
	                ];
		print_r(json_encode($return));
	}

	
}
