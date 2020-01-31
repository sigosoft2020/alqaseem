<?php

class M_expense extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  function make_query()
  {
    $table = "agency_expense";
    $select_column = array("aexp_id","cat_id","agency_id","agency_name","expense_name","expense_amount","date","time","timestamp");
    $order_column = array(null,"name_english",null,null,null,null,null,null);

    $this->db->select($select_column);
    $this->db->from($table);
    if (isset($_POST["search"]["value"])) {
      $this->db->like("agency_name",$_POST["search"]["value"]);
    }
    if (isset($_POST["order"])) {
      $this->db->order_by($_POST['order']['0']['column'],$_POST['order']['0']['dir']);
    }
    else {
      $this->db->order_by("aexp_id","desc");
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
    $this->db->from("agency_expense");
    return $this->db->count_all_results();
  }

}

?>
