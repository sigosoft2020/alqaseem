<?php

class M_coupons extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  function make_query()
  {
    $table = "coupon_packages";
    $select_column = array("cpack_id","product_id","pack_name","pack_name_arabic","pack_count","no_of_bottles","pack_default_price","pack_validity","status","timestamp");
    $order_column = array(null,"pack_name",null,null);

    $this->db->select($select_column);
    $this->db->from($table);
    if (isset($_POST["search"]["value"])) {
      $this->db->like("pack_name",$_POST["search"]["value"]);
    }
    if (isset($_POST["order"])) {
      $this->db->order_by($_POST['order']['0']['column'],$_POST['order']['0']['dir']);
    }
    else {
      $this->db->order_by("cpack_id","desc");
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
    $this->db->from("coupon_packages");
    return $this->db->count_all_results();
  }

  function get_coupons()
  {
    $this->db->select('cpack_id,product_id,pack_name,pack_name_arabic,no_of_bottles,pack_default_price,pack_validity,status,timestamp');
    $this->db->from('coupon_packages');
    $this->db->order_by('cpack_id','desc');
    return $this->db->get()->result();
  }
  
  function getCouponPurchases($id)
  {
     $this->db->select("SUM(used_coupons) as total");
     $this->db->from("coupon_purchases");
     $this->db->where('pack_id',$id);
     $result = $this->db->get();
     if($result->num_rows() > 0)
     {
        return $result->row()->total;
     }
     else
     {
        return false;
     }
  }
  
  function getPurchasedCoupons($id)
  {
      $this->db->select('*');
      $this->db->from('coupon_purchases');
      $this->db->where('pack_id',$id);
      return $this->db->get()->result();
  }
}

?>
