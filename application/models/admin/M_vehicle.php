<?php

class M_vehicle extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  function get_vehicles()
  {
    $this->db->select('*');
    $this->db->from('vehicle');
    $this->db->order_by('vehicle_id','desc');
    return $this->db->get()->result();
  }
  function make_query()
  {
    $table = "vehicle";
    $select_column = array("vehicle_id","vehicle_no","person_name","person_phone","person_address","person_image","password","status","timestamp");
    $order_column = array("vehicle_no","person_name",null,null,null,null,null,null);

    $this->db->select($select_column);
    $this->db->from($table);
    if (isset($_POST["search"]["value"])) {
      $this->db->like("person_name",$_POST["search"]["value"]);
    }
    if (isset($_POST["order"])) {
      $this->db->order_by($_POST['order']['0']['column'],$_POST['order']['0']['dir']);
    }
    else {
      $this->db->order_by("vehicle_id","desc");
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
    $this->db->from("vehicle");
    return $this->db->count_all_results();
  }
}

?>
