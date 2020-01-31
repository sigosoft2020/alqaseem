<?php

class M_agencies extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  function get_agencies()
  {
    $this->db->select('*');
    $this->db->from('agencies');
    $this->db->order_by('agency_id','desc');
    return $this->db->get()->result();
  }
  function make_query()
  {
    $table = "agencies";
    $select_column = array("agency_id","agency_name","name_arabic","agency_code","agency_phone","agency_image","agency_password","vehicle_number","agency_staff","staff_arabic","agency_status","initial_cans_allotted","timestamp");
    $order_column = array(null,"agency_name","agency_code",null,null,"vehicle_number","agency_staff",null,null,null);

    $this->db->select($select_column);
    $this->db->from($table);
    if (isset($_POST["search"]["value"])) {
      $this->db->like("agency_name",$_POST["search"]["value"]);
    }
    if (isset($_POST["order"])) {
      $this->db->order_by($_POST['order']['0']['column'],$_POST['order']['0']['dir']);
    }
    else {
      $this->db->order_by("agency_id","desc");
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
    $this->db->from("agencies");
    return $this->db->count_all_results();
  }
  
  function getAgencies()
  {
      $this->db->select('*');
      $this->db->from('agencies');
      $this->db->where('agency_status','1');
      $this->db->order_by('agency_id','desc');
      return $this->db->get()->result();
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
  
  function getExpenseToday($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(expense_amount) as total');
    $this->db->from('agency_expense');
    $this->db->where('agency_expense.agency_id',$agency_id);
    $this->db->where('agency_expense.date',$date);
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
  
//week//
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
  
  function getExpenseWeek($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(expense_amount) as total');
    $this->db->from('agency_expense');
    $this->db->where('agency_expense.agency_id',$agency_id);
     $this->db->where('WEEKOFYEAR(date)=WEEKOFYEAR(NOW())');
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
  
//month//
  function getMonthTotalSale($agency_id)
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
  
  function getExpenseMonth($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(expense_amount) as total');
    $this->db->from('agency_expense');
    $this->db->where('agency_expense.agency_id',$agency_id);
    $this->db->where('MONTH(date)', date('m')); //For current month
    $this->db->where('YEAR(date)', date('Y')); // For current year
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
  
//life time//
   function getTotalSale($agency_id)
  {
    $date = date('Y-m-d');  
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
  

 function getTotalCredit($agency_id)
  {
    $date = date('Y-m-d');  
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
 
 function getTotalPayment($agency_id)
  {
    $date = date('Y-m-d');  
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
  
  function getExpenseTotal($agency_id)
  {
    $date = date('Y-m-d');  
    $this->db->select('SUM(expense_amount) as total');
    $this->db->from('agency_expense');
    $this->db->where('agency_expense.agency_id',$agency_id);
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
  
}

?>
