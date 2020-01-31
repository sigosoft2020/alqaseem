<?php

class Common extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
	public function insert($table,$data)
  {
    $this->db->insert($table,$data);
    $insert_id = $this->db->insert_id();
    return  $insert_id;
  }
  public function get_details($table,$cond)
  {
    $res = $this->db->get_where($table,$cond);
    return $res;
  }
  public function update($base_id,$id,$table,$data) {
    $this->db->where($base_id, $id);
    $this->db->update($table, $data);
    return true;
  }
  public function delete($table,$cond)
  {
    $this->db->delete($table, $cond);
    return true;
  }
  public function get_star($table,$where_l,$where_r,$order,$order_r)
  {
    $this->db->select('*');
    $this->db->from($table);
    if ($where_l != '') {
      $this->db->where($where_l,$where_r);
    }
    $this->db->order_by($order, $order_r);
    $result = $this->db->get();
  	return $result;
  }
}

?>
