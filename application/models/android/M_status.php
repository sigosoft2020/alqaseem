<?php

class M_status extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  
  function getTodaysTotalSale($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(customer_orders.total) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('customer_orders.completed_date',$date);
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
  
  function getWeeksTotalSale($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(customer_orders.total) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('WEEKOFYEAR(customer_orders.completed_date)=WEEKOFYEAR(NOW())');
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
  
  function getMonthTotalSale($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(customer_orders.total) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('MONTH(customer_orders.completed_date)', date('m')); //For current month
    $this->db->where('YEAR(customer_orders.completed_date)', date('Y')); // For current year
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
  
  function getTotalSale($agency_id)
  {
    
    $this->db->select('SUM(customer_orders.total) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
  
 //swipe//
 
  function getTodaysTotalSwipe($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(customer_orders.total) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('customer_orders.payment_method','Swipe');
    $this->db->where('customer_orders.completed_date',$date);
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
  
  function getWeeksTotalSwipe($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(customer_orders.total) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('customer_orders.payment_method','Swipe');
    $this->db->where('WEEKOFYEAR(customer_orders.completed_date)=WEEKOFYEAR(NOW())');
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
  
  function getMonthTotalSwipe($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(customer_orders.total) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('customer_orders.payment_method','Swipe');
    $this->db->where('MONTH(customer_orders.completed_date)', date('m')); //For current month
    $this->db->where('YEAR(customer_orders.completed_date)', date('Y')); // For current year
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
  
  function getTotalSwipe($agency_id)
  {
    $this->db->select('SUM(customer_orders.total) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('customer_orders.payment_method','Swipe');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
 //swipe ends//
 
 //cash//
  function getTodaysTotalCash($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(customer_orders.total) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('customer_orders.payment_method','Cash');
    $this->db->where('customer_orders.completed_date',$date);
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
  
  function getWeeksTotalCash($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(customer_orders.total) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('customer_orders.payment_method','Cash');
    $this->db->where('WEEKOFYEAR(customer_orders.completed_date)=WEEKOFYEAR(NOW())');
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
  
  function getMonthTotalCash($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(customer_orders.total) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('customer_orders.payment_method','Cash');
    $this->db->where('MONTH(customer_orders.completed_date)', date('m')); //For current month
    $this->db->where('YEAR(customer_orders.completed_date)', date('Y')); // For current year
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
  
  function getTotalCash($agency_id)
  {
    $this->db->select('SUM(customer_orders.total) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('customer_orders.payment_method','Cash');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
 //cash ends//
 
 //credit//
  function getTodaysTotalCredit($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(customer_orders.credit_balance) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('customer_orders.completed_date',$date);
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
  
  function getWeeksTotalCredit($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(customer_orders.credit_balance) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('WEEKOFYEAR(customer_orders.completed_date)=WEEKOFYEAR(NOW())');
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
  
  function getMonthTotalCredit($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(customer_orders.credit_balance) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('MONTH(customer_orders.completed_date)', date('m')); //For current month
    $this->db->where('YEAR(customer_orders.completed_date)', date('Y')); // For current year
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
  
  function getTotalCredit($agency_id)
  {
    $this->db->select('SUM(customer_orders.credit_balance) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
 //credit ends//
 
 //payment received//
  
  function getTodaysTotalPayment($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(customer_orders.amount_received) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('customer_orders.completed_date',$date);
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
  
  function getWeeksTotalPayment($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(customer_orders.amount_received) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('WEEKOFYEAR(customer_orders.completed_date)=WEEKOFYEAR(NOW())');
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
  
  function getMonthTotalPayment($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(customer_orders.amount_received) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('MONTH(customer_orders.completed_date)', date('m')); //For current month
    $this->db->where('YEAR(customer_orders.completed_date)', date('Y')); // For current year
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
  
  function getTotalPayment($agency_id)
  {
    $this->db->select('SUM(customer_orders.amount_received) as total');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $result = $this->db->get();
    if($result->num_rows()>0)
    {
        return $result->row()->total;
    }
    else
    {
        return false;
    }
  }
 //payments ends//
 
 //payment list//
 
  function getTodaysTotalPaymentList($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('customer_orders.order_id,customer_orders.customer_name,customer_orders.customer_phone,customer_orders.total,customer_orders.credit_balance,customer_orders.amount_received,customer_orders.completed_date,customer_orders.completed_time,ordered_address.house,ordered_address.building,ordered_address.location');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->join('ordered_address','ordered_address.order_id=customer_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('customer_orders.completed_date',$date);
    return $this->db->get()->result();
  }
  
  function getWeeksTotalPaymentList($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('customer_orders.order_id,customer_orders.customer_name,customer_orders.customer_phone,customer_orders.total,customer_orders.credit_balance,customer_orders.amount_received,customer_orders.completed_date,customer_orders.completed_time,ordered_address.house,ordered_address.building,ordered_address.location');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->join('ordered_address','ordered_address.order_id=customer_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('WEEKOFYEAR(customer_orders.completed_date)=WEEKOFYEAR(NOW())');
    return $this->db->get()->result();
  }
  
  function getMonthTotalPaymentList($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('customer_orders.order_id,customer_orders.customer_name,customer_orders.customer_phone,customer_orders.total,customer_orders.credit_balance,customer_orders.amount_received,customer_orders.completed_date,customer_orders.completed_time,ordered_address.house,ordered_address.building,ordered_address.location');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->join('ordered_address','ordered_address.order_id=customer_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    $this->db->where('MONTH(customer_orders.completed_date)', date('m')); //For current month
    $this->db->where('YEAR(customer_orders.completed_date)', date('Y')); // For current year
    return $this->db->get()->result();
  }
  
  function getTotalPaymentList($agency_id)
  {
    $this->db->select('customer_orders.order_id,customer_orders.customer_name,customer_orders.customer_phone,customer_orders.total,customer_orders.credit_balance,customer_orders.amount_received,customer_orders.completed_date,customer_orders.completed_time,ordered_address.house,ordered_address.building,ordered_address.location');
    $this->db->from('agency_orders');
    $this->db->join('customer_orders','customer_orders.order_id=agency_orders.order_id');
    $this->db->join('ordered_address','ordered_address.order_id=customer_orders.order_id');
    $this->db->where('agency_orders.status','completed');
    $this->db->where('agency_orders.agency_id',$agency_id);
    return $this->db->get()->result();
  }

 
}

?>