<?php

class M_sales extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  function get_history($id)
  {
    $this->db->select("*");
    $this->db->from("customer_orders");
    $this->db->where('type','retail');
    $this->db->where('added_by','retailer');
    $this->db->where('retailer_id',$id);
    $this->db->order_by('order_id','desc');
    return $this->db->get()->result();
  }
}

?>
