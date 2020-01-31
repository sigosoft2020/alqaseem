<?php

class M_pending extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  function make_query()
  {
    $table = "supervisor_request_products";
    $select_column = array("srequest_id","request_id","rpr_id","supervisor_id","supervisor_name","agency_id","agency_name","product_id","supervisor_request_products.broken_count","supervisor_request_products.smell_defect_count","quantity","status","date","time");
    $order_column = array(null,null,'supervisor_name',null,"agency_name",null,null,null,null,null,null);

    $this->db->select($select_column);
    $this->db->from($table);
    $this->db->join('supervisor_requests','supervisor_requests.srequest_id=supervisor_request_products.request_id');
    $this->db->where('supervisor_requests.status','p');
    if (isset($_POST["search"]["value"])) {
      $this->db->like("supervisor_name",$_POST["search"]["value"]);
    }
    if (isset($_POST["order"])) {
      $this->db->order_by($_POST['order']['0']['column'],$_POST['order']['0']['dir']);
    }
    else {
      $this->db->order_by("srequest_id","desc");
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
    $this->db->select("supervisor_requests.*,supervisor_request_products.*");
    $this->db->from("supervisor_request_products");
    $this->db->join('supervisor_requests','supervisor_requests.srequest_id=supervisor_request_products.request_id');
    $this->db->where('supervisor_requests.status','p');
    return $this->db->count_all_results();
  }
}

?>
