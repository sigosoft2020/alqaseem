<?php

class M_products extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  function getProducts()
  {
    $this->db->select('*');
    $this->db->from('products');
    $this->db->where('product_status','1');
    $this->db->order_by('product_id',"desc");
    return $this->db->get()->result();
  }
  function cartCheck($product_id,$customer_id)
  {
    $this->db->select('quantity');
    $this->db->from('cart');
    $this->db->where('customer_id',$customer_id);
    $this->db->where('product_id',$product_id);
    $result = $this->db->get();

    if($result->num_rows() > 0)
    {
        return $result->row()->quantity;
    }
    else
    {
        return 0;
    }
  }

}

?>