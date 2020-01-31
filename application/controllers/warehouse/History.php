<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('warehouse/M_stock','stock');
			$this->load->model('Common');
			if (!warehouse()) {
				redirect('users/warehouse');
			}
	}
//added stock history starts here//
 public function addedStockHistory()
 {
 	$w_manager    = $this->session->userdata['warehouse'];
	$w_manager_id = $w_manager['warehouse_id'];

 	if(isset($_POST['submit']))
 	{
 	   $start_date = $this->input->post('start_date');
 	   $end_date   = $this->input->post('end_date');	

 	   $stocks     = $this->stock->get_addedStockHistory($start_date,$end_date,$w_manager_id);
 	   
 	}
    else
    {
    	$stocks         = $this->stock->get_addedStocks($w_manager_id);		
    }
    $data['stocks']  = $stocks;
 	$this->load->view('warehouse/history/added_stock_history',$data);
 }
// added stock history ends here//

//removed stock history starts here//
public function removedStockHistory()
 {
 	$w_manager    = $this->session->userdata['warehouse'];
	$w_manager_id = $w_manager['warehouse_id'];

 	if(isset($_POST['submit']))
 	{
 	   $start_date = $this->input->post('start_date');
 	   $end_date   = $this->input->post('end_date');	

 	   $stocks     = $this->stock->get_removedStockHistory($start_date,$end_date,$w_manager_id);
 	   
 	}
    else
    {
    	$stocks         = $this->stock->get_removedStocks($w_manager_id);
    }
    $data['stocks']  = $stocks;
 	$this->load->view('warehouse/history/removed_stock_history',$data);
 }
//removed stock history ends here//  

//order history starts here//
public function orderHistory()
 {  
 	$w_manager    = $this->session->userdata['warehouse'];
	$w_manager_id = $w_manager['warehouse_id'];

 	$data['agencies']    = $this->Common->get_details('agencies',array())->result();
    $data['supervisors']  = $this->Common->get_details('supervisors',array())->result();
 	if(isset($_POST['submit']))
 	{
 	   $date       = $this->input->post('date');
 	   $agency     = $this->input->post('agency');
 	   $supervisor = $this->input->post('supervisor');	
   //all search//
    if($agency=='all' || $supervisor=='all')
 	   {
	 	   	$stocks         = $this->stock->get_orderStocks($w_manager_id);
			foreach($stocks as $stock)
			{
				$product            = $this->Common->get_details('products',array('product_id'=>$stock->product_id))->row();
				$stock->product_name = $product->product_name; 
			}
 	   }
   //all search ends//

   //agnecy search//
 	   elseif($agency!='' && $supervisor=='' && $date=='')
 	   {
	 	   	$stocks         = $this->stock->get_orderStocksByAgency($agency,$w_manager_id);
			foreach($stocks as $stock)
			{
				$product            = $this->Common->get_details('products',array('product_id'=>$stock->product_id))->row();
				$stock->product_name = $product->product_name; 
			}
 	   }
   //supervisor search//   
 	   elseif($supervisor!='' && $agency=='' && $date=='')
 	   {
	 	   	$stocks         = $this->stock->get_orderStocksBySupervisor($supervisor,$w_manager_id);
			foreach($stocks as $stock)
			{
				$product            = $this->Common->get_details('products',array('product_id'=>$stock->product_id))->row();
				$stock->product_name = $product->product_name; 
			}
 	   }
    
    //date search//
 	   elseif($date!='' && $agency=='' && $supervisor=='')
 	   {
	 	   	$stocks         = $this->stock->get_orderStocksByDate($date,$w_manager_id);
			foreach($stocks as $stock)
			{
				$product            = $this->Common->get_details('products',array('product_id'=>$stock->product_id))->row();
				$stock->product_name = $product->product_name; 
			}
 	   }

   //agency and supervisor search//

 	   elseif($agency!='' && $supervisor!='' && $date=='')
 	   {
	 	   	$stocks         = $this->stock->get_orderStocksByAgencySupervisor($agency,$supervisor,$w_manager_id);
			foreach($stocks as $stock)
			{
				$product    = $this->Common->get_details('products',array('product_id'=>$stock->product_id))->row();
				$stock->product_name = $product->product_name; 
			}
 	   }

   //agency and date//

 	   elseif($agency!='' && $date!='' && $supervisor=='')
 	   {
	 	   	$stocks         = $this->stock->get_orderStocksByAgencyDate($agency,$date,$w_manager_id);
			foreach($stocks as $stock)
			{
				$product    = $this->Common->get_details('products',array('product_id'=>$stock->product_id))->row();
				$stock->product_name = $product->product_name; 
			}
 	   }

   //supervisor and date//

 	   elseif($supervisor!='' && $date!='' && $agency=='')
 	   {
	 	   	$stocks         = $this->stock->get_orderStocksBySupervisorDate($supervisor,$date,$w_manager_id);
			foreach($stocks as $stock)
			{
				$product    = $this->Common->get_details('products',array('product_id'=>$stock->product_id))->row();
				$stock->product_name = $product->product_name; 
			}
 	   }
   //supervisor, agency and date//
       elseif($supervisor!='' && $date!='' && $agency!='')
 	   {
	 	   	$stocks         = $this->stock->get_orderStocksBySupervisorAgencyDate($supervisor,$date,$agency,$w_manager_id);
			foreach($stocks as $stock)
			{
				$product     = $this->Common->get_details('products',array('product_id'=>$stock->product_id))->row();
				$stock->product_name = $product->product_name; 
			}
 	   }
 	}
    else
    {
    	$stocks         = $this->stock->get_orderStocks($w_manager_id);
		foreach($stocks as $stock)
		{
			$product            = $this->Common->get_details('products',array('product_id'=>$stock->product_id,$w_manager_id))->row();
			$stock->product_name = $product->product_name; 
		}
    }
    $data['stocks']  = $stocks;
 	$this->load->view('warehouse/history/stock_history',$data);
 }
//order history ends here//

//stock history invoice starts here//
public function stockHistoryInvoice($id)
{
	$details = $this->Common->get_details('supervisor_request_products',array('rpr_id'=>$id))->row();
	$request = $this->Common->get_details('supervisor_requests',array('srequest_id'=>$details->request_id))->row();
	$details->agency_phone  = $this->Common->get_details('agencies',array('agency_id'=>$request->agency_id))->row()->agency_phone;
	if($request->supervisor_id=='0')
	{
		$details->supervisor = $request->agency_name;
	}
	else
	{
      $details->supervisor       = $request->supervisor_name;
      $details->supervisor_phone = $this->Common->get_details('supervisors',array('supervisor_id'=>$request->supervisor_id))->row()->phone;
	}
	$details->product_name = $this->Common->get_details('products',array('product_id'=>$details->product_id))->row()->product_name;
	$data['request'] = $request;
	$data['details'] = $details;
	$this->load->view('warehouse/history/stockHistoryInvoice',$data);
}
//stock history invoice ends here//
}
?>
