<?php

class M_history extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  function get_history()
  {
    $this->db->select("*");
    $this->db->from("customer_orders");
    $this->db->where('status','completed');
    $this->db->order_by('order_id','desc');
    return $this->db->get()->result();
  }

  function get_agencyHistory()
  {
    $this->db->select("*");
    $this->db->from("agency_sales");
    $this->db->order_by('as_id','desc');
    return $this->db->get()->result();
  }

}

?>
