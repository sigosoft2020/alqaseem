<?php
  function admin()
  {
    $ci =& get_instance();
	  $ci->load->library('session');
	  $teacher = $ci->session->userdata('admin');
    if (isset($teacher)) {
      return true;
    }
    else {
      return false;
    }
  }

  function retailer()
  {
    $ci =& get_instance();
    $ci->load->library('session');
    $teacher = $ci->session->userdata('retailer');
    if (isset($teacher)) {
      return true;
    }
    else {
      return false;
    }
  }

  function warehouse()
  {
    $ci =& get_instance();
    $ci->load->library('session');
    $teacher = $ci->session->userdata('warehouse');
    if (isset($teacher)) {
      return true;
    }
    else {
      return false;
    }
  }

  function getUserId($key)
  {
    $ci =& get_instance();
    $ci->load->database();

    $value = $ci->db->select('stu_id')->from('students')->where('auth',$key)->get();

    if($value->num_rows() > 0)
    {
      return $value->row()->stu_id;
    }
    else {
      return false;
    }
  }
 ?>
