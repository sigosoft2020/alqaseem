<?php

class M_stock extends CI_Model
{
  function __construct()
  {
    $this->load->database();
  }
  function get_stocks()
  {
    $this->db->select("product_stock.*,products.*");
    $this->db->from("products");
    $this->db->join('product_stock','product_stock.product_id=products.product_id');
    $this->db->where('products.product_status','1');
    $this->db->order_by('products.product_id','desc');
    return $this->db->get()->result();
  }

  function get_todays_inwards($date,$id)
  {
    $this->db->select("SUM(quantity) as total");
    $this->db->from("stock_history");
    $this->db->where('product_id',$id);
    $this->db->where('type','a');
    $this->db->where('date', $date); 
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

  function stock_saledToCustomer($date,$id)
  {
    $this->db->select("SUM(customer_ordered_products.quantity) as total");
    $this->db->from("customer_ordered_products");
    $this->db->join('customer_orders','customer_orders.order_id=customer_ordered_products.order_id');
    $this->db->where('customer_ordered_products.product_id',$id);
    $this->db->where('customer_orders.status','completed');
    $this->db->where('customer_orders.completed_date', $date); 
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

  function stock_saledToAgency($date,$id)
  {
    $this->db->select("SUM(agency_sale_products.quantity) as total");
    $this->db->from("agency_sale_products");
    $this->db->join('agency_sales','agency_sales.as_id=agency_sale_products.as_id');
    $this->db->where('agency_sale_products.product_id',$id);
    $this->db->where('agency_sales.sale_date', $date); 
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

  function stock_saledToSupervisor($date,$id)
  {
    $this->db->select("SUM(supervisor_request_products.quantity) as total");
    $this->db->from("supervisor_request_products");
    $this->db->join('supervisor_requests','supervisor_requests.srequest_id=supervisor_request_products.request_id');
    $this->db->where('supervisor_request_products.product_id',$id);
    $this->db->where('supervisor_requests.status','c');
    $this->db->where('supervisor_requests.completed_date', $date); 
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

  function get_todays_outwards($date,$id)
  {
    $this->db->select("SUM(quantity) as total");
    $this->db->from("stock_history");
    $this->db->where('product_id',$id);
    $this->db->where('type!=','a');
    $this->db->where('date', $date); 
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

  function get_addedStocks()
  {
    $this->db->select("stock_history.*,products.product_id,products.product_name,product_name_arabic");
    $this->db->from("stock_history");
    $this->db->join('products','products.product_id=stock_history.product_id');
    $this->db->where('stock_history.type','a');
    $this->db->order_by('stock_history.sh_id','desc');
    return $this->db->get()->result();
  }
  
  function get_removedStocks()
  {
    $this->db->select("stock_history.*,products.product_id,products.product_name,product_name_arabic");
    $this->db->from("stock_history");
    $this->db->join('products','products.product_id=stock_history.product_id');
    $this->db->where('stock_history.type','r');
    $this->db->order_by('stock_history.sh_id','desc');
    return $this->db->get()->result();
  }

  function get_addedStockHistory($start,$end)
  {
    $this->db->select("stock_history.*,products.product_id,products.product_name,product_name_arabic");
    $this->db->from("stock_history");
    $this->db->join('products','products.product_id=stock_history.product_id');
    $this->db->where('stock_history.type','a');
    $this->db->where('stock_history.date>=',$start);
    $this->db->where('stock_history.date<=',$end);
    $this->db->order_by('stock_history.sh_id','desc');
    return $this->db->get()->result();
  }

  function get_removedStockHistory($start,$end)
  {
    $this->db->select("stock_history.*,products.product_id,products.product_name,product_name_arabic");
    $this->db->from("stock_history");
    $this->db->join('products','products.product_id=stock_history.product_id');
    $this->db->where('stock_history.type','r');
    $this->db->where('stock_history.date>=',$start);
    $this->db->where('stock_history.date<=',$end);
    $this->db->order_by('stock_history.sh_id','desc');
    return $this->db->get()->result();
  }

  function get_orderStocks()
  {
    $this->db->select("supervisor_request_products.*,supervisor_requests.srequest_id,supervisor_requests.srequest_id,supervisor_requests.supervisor_id,supervisor_requests.supervisor_name,supervisor_requests.agency_id,supervisor_requests.agency_name,supervisor_requests.requested_date");
    $this->db->from("supervisor_request_products");
    $this->db->join('supervisor_requests','supervisor_requests.srequest_id=supervisor_request_products.request_id');
    $this->db->where('supervisor_requests.status','c');
    $this->db->order_by('supervisor_requests.srequest_id','desc');
    return $this->db->get()->result();
  }

  function get_orderStocksByAgency($agency)
  {
    $this->db->select("supervisor_request_products.*,supervisor_requests.srequest_id,supervisor_requests.srequest_id,supervisor_requests.supervisor_id,supervisor_requests.supervisor_name,supervisor_requests.agency_id,supervisor_requests.agency_name,supervisor_requests.requested_date");
    $this->db->from("supervisor_request_products");
    $this->db->join('supervisor_requests','supervisor_requests.srequest_id=supervisor_request_products.request_id');
    $this->db->where('supervisor_requests.status','c');
    $this->db->where('supervisor_requests.agency_id',$agency);
    $this->db->order_by('supervisor_requests.srequest_id','desc');
    return $this->db->get()->result();
  }

  function get_orderStocksBySupervisor($supervisor)
  {
    $this->db->select("supervisor_request_products.*,supervisor_requests.srequest_id,supervisor_requests.srequest_id,supervisor_requests.supervisor_id,supervisor_requests.supervisor_name,supervisor_requests.agency_id,supervisor_requests.agency_name,supervisor_requests.requested_date");
    $this->db->from("supervisor_request_products");
    $this->db->join('supervisor_requests','supervisor_requests.srequest_id=supervisor_request_products.request_id');
    $this->db->where('supervisor_requests.status','c');
    $this->db->where('supervisor_requests.supervisor_id',$supervisor);
    $this->db->order_by('supervisor_requests.srequest_id','desc');
    return $this->db->get()->result();
  }
  
  function get_orderStocksByDate($date)
  {
    $this->db->select("supervisor_request_products.*,supervisor_requests.srequest_id,supervisor_requests.srequest_id,supervisor_requests.supervisor_id,supervisor_requests.supervisor_name,supervisor_requests.agency_id,supervisor_requests.agency_name,supervisor_requests.requested_date");
    $this->db->from("supervisor_request_products");
    $this->db->join('supervisor_requests','supervisor_requests.srequest_id=supervisor_request_products.request_id');
    $this->db->where('supervisor_requests.status','c');
    $this->db->where('supervisor_requests.requested_date',$date);
    $this->db->order_by('supervisor_requests.srequest_id','desc');
    return $this->db->get()->result();
  }

  function get_orderStocksByAgencySupervisor($agency,$supervisor)
  {
    $this->db->select("supervisor_request_products.*,supervisor_requests.srequest_id,supervisor_requests.srequest_id,supervisor_requests.supervisor_id,supervisor_requests.supervisor_name,supervisor_requests.agency_id,supervisor_requests.agency_name,supervisor_requests.requested_date");
    $this->db->from("supervisor_request_products");
    $this->db->join('supervisor_requests','supervisor_requests.srequest_id=supervisor_request_products.request_id');
    $this->db->where('supervisor_requests.status','c');
    $this->db->where('supervisor_requests.agency_id',$agency);
    $this->db->where('supervisor_requests.supervisor_id',$supervisor);
    $this->db->order_by('supervisor_requests.srequest_id','desc');
    return $this->db->get()->result();
  }

  function get_orderStocksByAgencyDate($agency,$date)
  {
    $this->db->select("supervisor_request_products.*,supervisor_requests.srequest_id,supervisor_requests.srequest_id,supervisor_requests.supervisor_id,supervisor_requests.supervisor_name,supervisor_requests.agency_id,supervisor_requests.agency_name,supervisor_requests.requested_date");
    $this->db->from("supervisor_request_products");
    $this->db->join('supervisor_requests','supervisor_requests.srequest_id=supervisor_request_products.request_id');
    $this->db->where('supervisor_requests.status','c');
    $this->db->where('supervisor_requests.agency_id',$agency);
    $this->db->where('supervisor_requests.requested_date',$date);
    $this->db->order_by('supervisor_requests.srequest_id','desc');
    return $this->db->get()->result();
  }
  
  function get_orderStocksBySupervisorDate($supervisor,$date)
  {
    $this->db->select("supervisor_request_products.*,supervisor_requests.srequest_id,supervisor_requests.srequest_id,supervisor_requests.supervisor_id,supervisor_requests.supervisor_name,supervisor_requests.agency_id,supervisor_requests.agency_name,supervisor_requests.requested_date");
    $this->db->from("supervisor_request_products");
    $this->db->join('supervisor_requests','supervisor_requests.srequest_id=supervisor_request_products.request_id');
    $this->db->where('supervisor_requests.status','c');
    $this->db->where('supervisor_requests.requested_date',$date);
    $this->db->where('supervisor_requests.supervisor_id',$supervisor);
    $this->db->order_by('supervisor_requests.srequest_id','desc');
    return $this->db->get()->result();
  }
  
  function get_orderStocksBySupervisorAgencyDate($supervisor,$date,$agency)
  {
    $this->db->select("supervisor_request_products.*,supervisor_requests.srequest_id,supervisor_requests.srequest_id,supervisor_requests.supervisor_id,supervisor_requests.supervisor_name,supervisor_requests.agency_id,supervisor_requests.agency_name,supervisor_requests.requested_date");
    $this->db->from("supervisor_request_products");
    $this->db->join('supervisor_requests','supervisor_requests.srequest_id=supervisor_request_products.request_id');
    $this->db->where('supervisor_requests.status','c');
    $this->db->where('supervisor_requests.agency_id',$agency);
    $this->db->where('supervisor_requests.requested_date',$date);
    $this->db->where('supervisor_requests.supervisor_id',$supervisor);
    $this->db->order_by('supervisor_requests.srequest_id','desc');
    return $this->db->get()->result();
  }
}

?>
