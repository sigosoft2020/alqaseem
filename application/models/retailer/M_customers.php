<?php

class M_customers extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  function make_query()
  {
    $table = "customers";
    $select_column = array("customer_id","name_english","name_arabic","customer_phone","customer_email","customer_address","customer_image","status","timestamp");
    $order_column = array(null,"name_english",null,null,null,null,null,null);

    $this->db->select($select_column);
    $this->db->from($table);
    if (isset($_POST["search"]["value"])) {
      $this->db->like("name_english",$_POST["search"]["value"]);
    }
    if (isset($_POST["order"])) {
      $this->db->order_by($_POST['order']['0']['column'],$_POST['order']['0']['dir']);
    }
    else {
      $this->db->order_by("customer_id","desc");
    }
  }
  function make_datatables()
  {
    $this->make_query();
    if ($_POST["length"] != -1) {
      $this->db->limit($_POST["length"],$_POST["start"]);
    }
    $query = $this->db->get();
    return $query->result();
  }
  function get_filtered_data()
  {
    $this->make_query();
    $query = $this->db->get();
    return $query->num_rows();
  }
  function get_all_data()
  {
    $this->db->select("*");
    $this->db->from("customers");
    return $this->db->count_all_results();
  }

 //get orders starts here//
  function get_orders($id)
  {
    $this->db->select('*');
    $this->db->from('customer_orders');
    $this->db->where('customer_id',$id);
    $this->db->where('status','completed');
    $this->db->where('type','app');
    return $this->db->get()->result();
  }
 //get orders ends here// 
}

?>
