<?php

class M_cofillRequests extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  function make_query()
  {
    $table = "co_filling_requests";
    $select_column = array("co_request_id","customer_id","customer_name","timestamp");
    $order_column = array(null,"customer_name",null,null);

    $this->db->select($select_column);
    $this->db->from($table);
    $this->db->where('status','pending');
    if (isset($_POST["search"]["value"])) {
      $this->db->like("customer_name",$_POST["search"]["value"]);
    }
    if (isset($_POST["order"])) {
      $this->db->order_by($_POST['order']['0']['column'],$_POST['order']['0']['dir']);
    }
    else {
      $this->db->order_by("co_request_id","desc");
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
    $this->db->from("co_filling_requests");
    $this->db->where('status','pending');
    $this->db->order_by("co_request_id","desc");
    return $this->db->count_all_results();
  }
}

?>
