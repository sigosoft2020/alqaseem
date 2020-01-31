<?php

class M_order extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  function getCartData($customer_id)
  {
     $this->db->select('products.product_id,products.product_name as product_name_english,products.product_name_arabic,products.cat_id,products.price,products.product_vat,products.product_status,products.product_image,cart.quantity,cart.cart_id');
     $this->db->from('cart');
     $this->db->join('products','products.product_id=cart.product_id');
     $this->db->order_by('cart.cart_id',"desc");
     $this->db->where('cart.customer_id',$customer_id);
     return $this->db->get()->result();
  }
  
   function getOrders($id)
   {
     $this->db->select('order_id,customer_id,customer_name,customer_phone,location,latitude,longitude,payment_method,total,amount_received,credit_balance,delivery_date,delivery_time,completed_date,completed_time,status,agency_assigned,timestamp');
     $this->db->from('customer_orders');
     $this->db->where('customer_id',$id);
     $this->db->where('type','app');
     $this->db->order_by('order_id','desc');
     return $this->db->get()->result();
   }
  
   function getOrderedProducts($order_id)
   {
     $this->db->select('customer_ordered_products.*,products.product_image,products.product_name_arabic');
     $this->db->from('customer_ordered_products');
     $this->db->join('products','products.product_id = customer_ordered_products.product_id');
     $this->db->where('customer_ordered_products.order_id',$order_id);
     return $this->db->get()->result();
   }
  
   function getOrderDetails($id)
   {
     $this->db->select('order_id,customer_id,customer_name,customer_phone,location,latitude,longitude,payment_method,total,amount_received,credit_balance,delivery_date,delivery_time,completed_date,completed_time,status,agency_assigned,timestamp');
     $this->db->from('customer_orders');
     $this->db->where('order_id',$id);
     return $this->db->get()->row();
   }
  
   function getNewRequests($latitude,$longitude,$date)
   {   
    //   $this->db->select('locations.agency_id,customer_orders.latitude,customer_orders.longitude,locations.date,locations.time,locations.timestamp,customer_orders.order_id,customer_orders.customer_name,customer_orders.customer_phone,customer_orders.total,customer_orders.delivery_date,customer_orders.delivery_time');
    //   $this->db->from('customer_orders');
    //   $this->db->join('locations','customer_orders.latitude=locations.latitude AND customer_orders.longitude=locations.longitude AND locations.date=customer_orders.billing_date AND locations.time=customer_orders.ordered_time');
    //   $this->db->where('locations.agency_id',$id);
    //   $this->db->where('customer_orders.status','pending');
    //   $this->db->where('customer_orders.agency_assigned','0');
    //   $this->db->order_by('customer_orders.order_id','desc');
    //   return $this->db->get()->result();
    
        $query = "SELECT order_id,customer_name,customer_phone,total,delivery_date,delivery_time,billing_date,SQRT(POW(69.1 * (latitude - $latitude), 2) +POW(69.1 * (longitude - $longitude) * COS(latitude / 57.3), 2)) AS distance FROM customer_orders  HAVING distance<10 AND billing_date='$date' ORDER BY distance;";
        $res = $this->db->query($query);
        return $res->result();
    
    }
  
   function getApprovedRequests($id)
   {
      $this->db->select('agency_orders.agency_id,agency_orders.status,agency_orders.assigned_time,customer_orders.order_id,customer_orders.customer_name,customer_orders.customer_phone,customer_orders.total,customer_orders.delivery_date,customer_orders.delivery_time,customer_orders.latitude,customer_orders.longitude');
      $this->db->from('agency_orders');
      $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
      $this->db->where('agency_orders.agency_id',$id);
      $this->db->where('agency_orders.status','assigned');
      $this->db->order_by('customer_orders.order_id','desc');
      return $this->db->get()->result();
   }
  
   function getCompletedRequests($id)
   {
      $this->db->select('agency_orders.agency_id,agency_orders.status,agency_orders.assigned_time,customer_orders.order_id,customer_orders.customer_name,customer_orders.customer_phone,customer_orders.total,customer_orders.delivery_date,customer_orders.delivery_time,customer_orders.latitude,customer_orders.longitude');
      $this->db->from('agency_orders');
      $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
      $this->db->where('agency_orders.agency_id',$id);
      $this->db->where('agency_orders.status','completed');
      $this->db->order_by('customer_orders.order_id','desc');
      return $this->db->get()->result();
   }
   
   function getDrivers($latitude,$longitude,$date,$time)
   {
        $query = "SELECT agency_id,date,time,SQRT(POW(69.1 * (latitude - $latitude), 2) +POW(69.1 * (longitude - $longitude) * COS(latitude / 57.3), 2)) AS distance FROM locations WHERE location_id IN (SELECT MAX(location_id) FROM locations GROUP BY agency_id) HAVING distance<10 AND date='$date' ORDER BY distance;";
        $res = $this->db->query($query);
        return $res->result();
    } 
    
    function getLatestLocation($id)
    {
        $this->db->select('*');
        $this->db->from('locations');
        $this->db->where('agency_id',$id);
        $this->db->order_by('location_id','desc');
        $this->db->limit('1');
        return $this->db->get()->row();
    }
    
    public function getCoupon($customer_id,$product_id,$date)
    {
        // $this->db->select('*');
        // $this->db->from('coupon_purchases');
        // $this->db->where('customer_id',$customer_id);
        // $this->db->where('product_id',$product_id);
        // $this->db->where('expiry_date>=',$date);
        // $this->db->where('unused_coupons>',0);
        // return $this->db->get();
        
        $this->db->select('coupon_purchases.*,coupon_packages.product_id');
        $this->db->from('coupon_purchases');
        $this->db->join('coupon_packages','coupon_packages.cpack_id=coupon_purchases.pack_id');
        $this->db->where('coupon_purchases.customer_id',$customer_id);
        $this->db->where('coupon_packages.product_id',$product_id);
        $this->db->where('coupon_purchases.expiry_date>=',$date);
        $this->db->where('coupon_purchases.unused_coupons>',0);
        return $this->db->get();
    }
   
}

?>