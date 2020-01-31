<?php

class M_coupon extends CI_Model
{
   function __construct()
   {
    $this->load->database();
   }
    function getCoupons($customer_id)
    {
        $this->db->select('coupon_purchases.customer_id,coupon_purchases.total_coupons,coupon_purchases.used_coupons,coupon_purchases.unused_coupons,coupon_purchases.start_date,coupon_purchases.expiry_date,coupon_packages.cpack_id,coupon_packages.product_id,coupon_packages.pack_name,coupon_packages.pack_name_arabic,coupon_packages.no_of_bottles,coupon_packages.pack_count,coupon_packages.pack_default_price as pack_price,coupon_packages.pack_validity,products.product_name,products.product_name_arabic');  
        $this->db->from('coupon_purchases');
        $this->db->join('coupon_packages','coupon_packages.cpack_id=coupon_purchases.pack_id');
        $this->db->join('products','products.product_id=coupon_packages.product_id');
        $this->db->order_by('coupon_purchases.cpurchase_id',"desc");
        $this->db->where('coupon_purchases.customer_id',$customer_id);
        return $this->db->get()->result();
    }
  
    function getCoupon()
    {
      $this->db->select('*');
      $this->db->from('coupon_packages');
      $this->db->where('status','1');
      $this->db->order_by('cpack_id','desc');
      return $this->db->get()->result();
    }
  
    function getOrdersByCopons($id)
    {
      $this->db->select('customer_ordered_products.coupon_id,customer_ordered_products.order_id,customer_orders.customer_name,customer_orders.customer_phone,customer_orders.total,customer_orders.latitude,customer_orders.longitude,customer_orders.delivery_date,customer_orders.delivery_time,customer_orders.delivery_date,customer_orders.status');
      $this->db->from('customer_ordered_products');
      $this->db->join('customer_orders','customer_orders.order_id=customer_ordered_products.order_id');
      $this->db->where('customer_ordered_products.coupon_id',$id);
      $this->db->where('customer_orders.status','completed');
      $this->db->order_by('customer_orders.order_id','desc');
      return $this->db->get()->result();
    }
    
    function getCouponPurchases($id)
    {
        $this->db->select('coupon_purchases.customer_id,coupon_purchases.coupon_price,coupon_purchases.total_coupons,coupon_purchases.used_coupons,coupon_purchases.unused_coupons,coupon_purchases.start_date,coupon_purchases.expiry_date,coupon_packages.cpack_id,coupon_packages.product_id,coupon_packages.pack_name,coupon_packages.pack_name_arabic,coupon_packages.no_of_bottles,coupon_packages.pack_count,coupon_packages.pack_default_price as pack_price,coupon_packages.pack_validity,products.product_name,products.product_name_arabic');  
        $this->db->from('coupon_purchases');
        $this->db->join('coupon_packages','coupon_packages.cpack_id=coupon_purchases.pack_id');
        $this->db->join('products','products.product_id=coupon_packages.product_id');
        $this->db->order_by('coupon_purchases.cpurchase_id',"desc");
        $this->db->where('coupon_purchases.pack_id',$id);
        return $this->db->get()->result();
    }
  
}

?>