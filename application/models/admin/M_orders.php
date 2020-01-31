<?php

class M_orders extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  function get_newOrders()
  {
    $this->db->select("*");
    $this->db->from("customer_orders");
    $this->db->where('type','app');
    $this->db->where('status','pending');
    $this->db->order_by('order_id','desc');
    return $this->db->get()->result();
  }
  
  function get_pendingOrders()
  {
    $this->db->select("*");
    $this->db->from("customer_orders");
    $this->db->where('type','app');
    $this->db->where('status','processing');
    $this->db->order_by('order_id','desc');
    return $this->db->get()->result();
  }

  function get_completedOrders()
  {
    $this->db->select("*");
    $this->db->from("customer_orders");
    $this->db->where('type','app');
    $this->db->where('status','completed');
    $this->db->order_by('order_id','desc');
    return $this->db->get()->result();
  }
  
  function get_cancelledOrders()
  {
    $this->db->select("*");
    $this->db->from("customer_orders");
    $this->db->where('type','app');
    $this->db->where('status','cancelled');
    $this->db->order_by('order_id','desc');
    return $this->db->get()->result();
  }
  
  function get_retailOrders()
  {
    $this->db->select("*");
    $this->db->from("customer_orders");
    $this->db->where('type','retail');
    $this->db->where('status','completed');
    $this->db->order_by('order_id','desc');
    return $this->db->get()->result();
  }

  function get_cofillingOrders()
  {
    $this->db->select("*");
    $this->db->from("customer_orders");
    $this->db->where('type','co_filling');
    $this->db->where('status','completed');
    $this->db->order_by('order_id','desc');
    return $this->db->get()->result();
  }

}

?>
