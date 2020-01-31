<?php

class M_customers extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  
  function getCustomers()
  {
    $this->db->select('customer_id,name_english,name_arabic,customer_phone,customer_email,customer_address,customer_image,status');
    $this->db->from('customers');
    $this->db->where('status','1');
    $this->db->order_by('customer_id','desc');
    return $this->db->get()->result();
  }
  
  function getPastOrders($id)
  {
      $this->db->select('order_id,customer_id,customer_name,customer_phone,location,latitude,longitude,total,payment_method,amount_received,delivery_date,delivery_time,completed_date,completed_time');
      $this->db->from('customer_orders');
      $this->db->where('customer_id',$id);
      $this->db->where('type','app');
      $this->db->where('status','completed');
      return $this->db->get()->result();
  }
  
  function serachCustomer($keyword)
  {
    $this->db->select('customer_id,name_english,name_arabic,customer_phone,customer_email,customer_address,customer_image,status');
    $this->db->from('customers');
    $this->db->where("name_english LIKE '$keyword%'");
    $this->db->where('status','1');
    return $this->db->get()->result();
  }
  
  function serachCustomerByNameOrMobile($keyword)
  {
    $this->db->select('customer_id,name_english,name_arabic,customer_phone,customer_email,customer_address,customer_image,status');
    $this->db->from('customers');
    $this->db->group_start();
    $this->db->where("name_english LIKE '$keyword%'");
    $this->db->or_where("customer_phone LIKE '$keyword%'");
    $this->db->group_end();
    $this->db->where('status','1');
    return $this->db->get()->result();
  }
  
}

?>