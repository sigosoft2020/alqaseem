<?php

class M_payments extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }

  function get_pendings($id)
  {
    $this->db->select('*');
    $this->db->from('customer_pending_payments');
    $this->db->where('type','retailer');
    $this->db->where('retailer_id',$id);
    $this->db->where('amount>','0');
    $this->db->order_by('cpp_id','desc');
    return $this->db->get()->result();
  }

  function get_history($id)
  {
    $this->db->select('*');
    $this->db->from('payment_history');
    $this->db->where('type','retailer');
    $this->db->where('retailer_id',$id);
    $this->db->order_by('ph_id','desc');
    return $this->db->get()->result();
  }

  function make_query()
  {
    $user       = $this->session->userdata['retailer'];
    $retailer_id= $user['retailer_id'];

    $table = "customer_pending_payments";
    $select_column = array("cpp_id","customer_id","customer_name","type","retailer_id","updated_on");
    $order_column = array(null,"customer_name",null,null);

    $this->db->select($select_column);
    $this->db->from($table);
    $this->db->join('customers','customers.customer_id=customer_pending_payments=customer_id');
    $this->db->where('type','retailer');
    $this->db->where('retailer_id',$retailer_id);
    if (isset($_POST["search"]["value"])) {
      $this->db->like("customer_name",$_POST["search"]["value"]);
    }
    if (isset($_POST["order"])) {
      $this->db->order_by($_POST['order']['0']['column'],$_POST['order']['0']['dir']);
    }
    else {
      $this->db->order_by("cpp_id","desc");
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
    $user       = $this->session->userdata['retailer'];
    $retailer_id= $user['retailer_id'];

    $this->db->select("*");
    $this->db->from("customer_pending_payments");
    $this->db->where('type','retailer');
    $this->db->where('retailer_id',$retailer_id);
    return $this->db->count_all_results();
  }
}

?>
