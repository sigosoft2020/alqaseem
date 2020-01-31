<?php

class M_cart extends CI_Model
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
  
  function getCart($customer_id)
  {
    $this->db->select('products.price,cart.quantity,cart.cart_id');
    $this->db->from('cart');
    $this->db->join('products','products.product_id = cart.product_id');
    $this->db->where('cart.customer_id',$customer_id);
    return $this->db->get()->result();
  }

}

?>