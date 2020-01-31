<?php

class M_wallet extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  function make_query()
  {
    $table = "customer_wallet";
    $select_column = array("wallet_id","customer_wallet.customer_id","customer_name","customer_phone","type","amount");
    $order_column = array(null,"customer_name",null,null);

    $this->db->select($select_column);
    $this->db->from($table);
    $this->db->join('customers','customers.customer_id=customer_wallet.customer_id');
    $this->db->where('amount>','0');
    if (isset($_POST["search"]["value"])) {
      $this->db->like("customer_name",$_POST["search"]["value"]);
    }
    if (isset($_POST["order"])) {
      $this->db->order_by($_POST['order']['0']['column'],$_POST['order']['0']['dir']);
    }
    else {
      $this->db->order_by("wallet_id","desc");
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
    $this->db->from("customer_wallet");
    $this->db->where('amount>','0');
    return $this->db->count_all_results();
  }
}

?>
