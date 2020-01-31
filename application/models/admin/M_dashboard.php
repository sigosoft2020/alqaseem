<?php

class M_dashboard extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  
  function getNewOrders($date)
  {
      $this->db->select('*');
      $this->db->from('customer_orders');
      $this->db->where('billing_date',$date);
      $this->db->where('type','app');
      $this->db->where('status','pending');
      return $this->db->get()->num_rows();
  }
  
  function getPendingOrders($date)
  {
      $this->db->select('*');
      $this->db->from('customer_orders');
      $this->db->where('billing_date',$date);
      $this->db->where('type','app');
      $this->db->where('status','processing');
      return $this->db->get()->num_rows();
  }
  
  function getCompletedOrders($date)
  {
      $this->db->select('*');
      $this->db->from('customer_orders');
      $this->db->where('completed_date',$date);
      $this->db->where('type','app');
      $this->db->where('status','completed');
      return $this->db->get()->num_rows();
  }
  
  function getCancelledOrders($date)
  {
      $this->db->select('*');
      $this->db->from('customer_orders');
      $this->db->where('cancelled_date',$date);
      $this->db->where('type','app');
      $this->db->where('status','cancelled');
      return $this->db->get()->num_rows();
  }
  
  function getNewRequests($current)
  {
      $this->db->select('*');
      $this->db->from('supervisor_requests');
      $this->db->where('requested_date',$current);
      $this->db->where('status','p');
      return $this->db->get()->num_rows();
  }
  
  function getRequests()
  {
    $this->db->select("*");
    $this->db->from("supervisor_requests");
    $this->db->order_by('srequest_id','desc');
    $this->db->limit('4');
    return $this->db->get()->result();
  }
  
  function getOrders()
  {
    $this->db->select("*");
    $this->db->from("customer_orders");
    $this->db->where('type','app');
    $this->db->order_by('order_id','desc');
    $this->db->limit('4');
    return $this->db->get()->result();
  }
  
//supervisor requests//

  function getNewRequestsToday($date)
  {
      $this->db->select('*');
      $this->db->from('supervisor_requests');
      $this->db->where('date',$date);
      $this->db->where('status','p');
      return $this->db->get()->num_rows();
  }
  
  function getApprovedRequestsToday($date)
  {
      $this->db->select('*');
      $this->db->from('supervisor_requests');
      $this->db->where('date',$date);
      $this->db->where('status','a');
      return $this->db->get()->num_rows();
  }
  
  function getCompletedRequestsToday($date)
   {
      $this->db->select('*');
      $this->db->from('supervisor_requests');
      $this->db->where('date',$date);
      $this->db->where('status','c');
      return $this->db->get()->num_rows();
  }
  
  function getCancelledRequestsToday($date)
  {
      $this->db->select('*');
      $this->db->from('supervisor_requests');
      $this->db->where('date',$date);
      $this->db->where('status','r');
      return $this->db->get()->num_rows();
  }
  
 //retail sales//
 
 function getRetailSalesToday($date)
  {
      $this->db->select('*');
      $this->db->from('customer_orders');
      $this->db->where('billing_date',$date);
      $this->db->where('type','retail');
      $this->db->where('status','completed');
      return $this->db->get()->num_rows();
  }
  
  function getRetails()
  {
    $this->db->select("customer_orders.*,customers.customer_image");
    $this->db->from("customer_orders");
    $this->db->join('customers','customers.customer_id=customer_orders.customer_id');
    $this->db->where('customer_orders.type','retail');
    $this->db->where('customer_orders.status','completed');
    $this->db->order_by('customer_orders.order_id','desc');
    $this->db->limit('4');
    return $this->db->get()->result();
  }
 
//new customers//
 
  function getCustomers()
  {
    $this->db->select("*");
    $this->db->from("customers");
    $this->db->where('status','1');
    $this->db->order_by('customer_id','desc');
    $this->db->limit('8');
    return $this->db->get()->result();
  }
  
  function getCustomersToday($date)
  {
    $this->db->select("*");
    $this->db->from("customers");
    $this->db->where('status','1');
    $this->db->where('DATE(timestamp)',$date);
    return $this->db->get()->num_rows();
  }

//new agencies//
  
  function getAgenciesToday($date)
  {
    $this->db->select("*");
    $this->db->from("agencies");
    $this->db->where('agency_status','1');
    $this->db->where('DATE(timestamp)',$date);
    return $this->db->get()->num_rows();
  }
  
//agency expense//

  function getExpenseToday($date)
  {
    $this->db->select("SUM(expense_amount) as total");
    $this->db->from("agency_expense");
    $this->db->where('date',$date);
    $result  = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }

}

?>
