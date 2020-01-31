<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('admin/supervisor_requests/M_approved','approved');
			$this->load->model('admin/supervisor_requests/M_completed','completed');
			$this->load->model('admin/M_stock','stock');
			$this->load->model('Common');
			if (!admin()) {
				redirect('users/admin');
			}
	}
//pending requests//

	public function pending()
	{
		$this->load->view('admin/warehouse/pending');
	}

	public function get_pending()
	{
        $result = $this->approved->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$sub_array[] = 'SREQ'.$res->srequest_id;
			$sub_array[] = $res->supervisor_name;
			$sub_array[] = $res->agency_name;
			$product     = $this->Common->get_details('products',array('product_id'=>$res->product_id))->row();
			$sub_array[] = $product->product_name; 
			$sub_array[] = date('d-M-Y',strtotime($res->date)).' '.$res->time ;
			$sub_array[] = $res->broken_count;
			$sub_array[] = $res->smell_defect_count;
			$sub_array[] = '<span style="color: blue;">Pending</span>';
			$sub_array[] = '<a href="'.site_url('admin/warehouse/updateStatus/'.$res->srequest_id).'"><button type="button" class="btn btn-primary" style="font-size:12px;">Complete</button></a>';
			$data[] = $sub_array;
		}

		$output = array(
							"draw"   => intval($_POST['draw']),
							"recordsTotal" => $this->approved->get_all_data(),
							"recordsFiltered" => $this->approved->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}
//pending requests//

//completed requets//
    public function completed()
	{
		$this->load->view('admin/warehouse/completed');
	}

	public function get_completed()
	{
        $result = $this->completed->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$sub_array = array();
			$sub_array[] = 'SREQ'.$res->srequest_id;
			$sub_array[] = $res->supervisor_name;
			$sub_array[] = $res->agency_name;
			$product     = $this->Common->get_details('products',array('product_id'=>$res->product_id))->row();
			$sub_array[] = $product->product_name; 
			$sub_array[] = date('d-M-Y',strtotime($res->date)).' '.$res->time ;
			// $sub_array[] = $res->broken_count;
			$sub_array[] = $res->new_cans_allowed;
			$sub_array[] = '<span style="color: green;">Completed</span>';
			$sub_array[] = '<a href="'.site_url('admin/warehouse/invoice/'.$res->srequest_id).'"><button type="button" class="btn btn-primary" style="font-size:12px;">Print</button></a>';
			$data[] = $sub_array;
		}

		$output = array(
							"draw"   => intval($_POST['draw']),
							"recordsTotal" => $this->completed->get_all_data(),
							"recordsFiltered" => $this->completed->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}

//completed requests ends here//	

//change status//
	 public function updateStatus($request_id)
	 {
	 	
	 	$cans          = [
	 	                    'status'            => 'c',
	 	                    'completed_date'    => date('Y-m-d')
	 	                 ];
	 	if($this->Common->update('srequest_id',$request_id,'supervisor_requests',$cans))
	 	{
	 		$products = $this->Common->get_details('supervisor_request_products',array('request_id'=>$request_id))->result();
	 		foreach($products as $product)
	 		{
	 			$new_cans = [
	 				          'new_cans_allowed' => $product->quantity
	 			            ];
	 			$this->Common->update('rpr_id',$product->rpr_id,'supervisor_request_products',$new_cans);             
	 		}
	 	}
	   
	    $this->session->set_flashdata('alert_type', 'success');
		$this->session->set_flashdata('alert_title', 'Success');
		$this->session->set_flashdata('alert_message', 'Order completed..!');
		redirect('admin/warehouse/completed');
	 }
// change status ends here//	

// view stock starts here//

	 public function stock()
	 {  
	 	$date   = date('Y-m-d');

	 	$stocks = $this->stock->get_stocks();
	 	foreach($stocks as $stock)
	 	{
	 		$stock->inwards         = 0+$this->stock->get_todays_inwards($date,$stock->product_id);
	 		$stock_saledToCustomer   = $this->stock->stock_saledToCustomer($date,$stock->product_id);
	 		$stock_saledToSupervisor = $this->stock->stock_saledToSupervisor($date,$stock->product_id);
	 		$stock_saledToAgency     = $this->stock->stock_saledToAgency($date,$stock->product_id);
	 		$stock->outwards         = $stock_saledToCustomer+$stock_saledToSupervisor+$stock_saledToAgency;
	 	}
	 	$data['stocks']  = $stocks;
	 	$this->load->view('admin/warehouse/view_stock',$data);
	 }
// view stock ends here// 

// add stock starts here//
	public function add_stock()
	{   
		$stocks         = $this->stock->get_addedStocks();
		foreach($stocks as $stock)
		{
			if($stock->updated_by=='a')
			{
				$stock->updated = 'Admin';
			}
			else
			{
				$w_manager      = $this->Common->get_details('warehouse_managers',array('wmanager_id'=>$stock->warehouse_manager_id))->row();
				$stock->updated = $w_manager->name.'<br>'.$w_manager->phone;
			}
		}
		$data['stocks']  = $stocks;
		$data['products']= $this->Common->get_details('products',array('product_status'=>'1'))->result();
		$this->load->view('admin/warehouse/added_stock',$data);
	}
    
    public function addStock()
    {
    	date_default_timezone_set('Asia/Kolkata');
        $timestamp = date('Y-m-d H:i:s');
        
		$product_id   = $this->security->xss_clean($this->input->post('product_id'));
        $no_of_items  = $this->security->xss_clean($this->input->post('no_of_items'));
        $notes        = $this->security->xss_clean($this->input->post('notes'));
        
		$array        = [
							'product_id'  => $product_id,
							'quantity'    => $no_of_items,
							'notes'       => $notes,
							'type'        => 'a',
							'updated_by'  => 'a',
							'date'        => date('Y-m-d'),
							'timestamp'   => $timestamp
				        ];
		if ($this->Common->insert('stock_history',$array)) 
		{   
             
            $stock_check  = $this->Common->get_details('product_stock',array('product_id'=>$product_id));
	        if($stock_check->num_rows()>0)
	        {   
	        	$current_stock= $stock_check->row()->stock;
	        	$new_stock    = $current_stock+$no_of_items;
	        	$stock_array  = [
	                              'stock'  => $new_stock
	                            ];
	            $this->Common->update('product_id',$product_id,'product_stock',$stock_array);                
	        }
	        else
	        {
	        	$stock_array  = [ 
	        		              'product_id' => $product_id,
	                              'stock'      => $no_of_items,
	                              'timestamp'  => $timestamp
	                            ];
	            $this->Common->insert('product_stock',$stock_array);                
	        } 

			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'New stock added..!');

			redirect('admin/warehouse/add_stock');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add stock..!');

			redirect('admin/warehouse/add_stock');
		}
    }

// add stock ends here// 

// remove stock starts here//
	public function remove_stock()
	{   
		$stocks         = $this->stock->get_removedStocks();
		foreach($stocks as $stock)
		{
			if($stock->updated_by=='a')
			{
				$stock->updated = 'Admin';
			}
			else
			{
				$w_manager      = $this->Common->get_details('warehouse_managers',array('wmanager_id'=>$stock->warehouse_manager_id))->row();
				$stock->updated = $w_manager->name.'<br>'.$w_manager->phone;
			}
		}
		$data['stocks']  = $stocks;
		$data['products']= $this->Common->get_details('products',array('product_status'=>'1'))->result();
		$this->load->view('admin/warehouse/removed_stock',$data);
	}
    
    public function removeStock()
    {
    	date_default_timezone_set('Asia/Kolkata');
        $timestamp = date('Y-m-d H:i:s');
        
		$product_id   = $this->security->xss_clean($this->input->post('product_id'));
        $no_of_items  = $this->security->xss_clean($this->input->post('no_of_items'));
        $notes        = $this->security->xss_clean($this->input->post('notes'));
        
		$array        = [
							'product_id'  => $product_id,
							'quantity'    => $no_of_items,
							'notes'       => $notes,
							'type'        => 'r',
							'updated_by'  => 'a',
							'date'        => date('Y-m-d'),
							'timestamp'   => $timestamp
				        ];
		if ($this->Common->insert('stock_history',$array)) 
		{              
            $stock_check  = $this->Common->get_details('product_stock',array('product_id'=>$product_id));
	        if($stock_check->num_rows()>0)
	        {   
	        	$current_stock= $stock_check->row()->stock;
	        	if($current_stock > $no_of_items)
	        	{
	        		$new_stock    = $current_stock-$no_of_items;
	        	}
	        	else
	        	{
	        		$new_stock    = '0';
	        	}
	        	$stock_array  = [
	                              'stock'  => $new_stock
	                            ];
	            $this->Common->update('product_id',$product_id,'product_stock',$stock_array);                
	        }
	        else
	        {
	        	$stock_array  = [ 
	        		              'product_id' => $product_id,
	                              'stock'      => '0',
	                              'timestamp'  => $timestamp
	                            ];
	            $this->Common->insert('product_stock',$stock_array);                
	        } 

			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'Stock removed..!');

			redirect('admin/warehouse/remove_stock');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to remove stock..!');

			redirect('admin/warehouse/remove_stock');
		}
    }

    public function getStock()
    {
    	$product_id  = $this->input->post('product_id');
    	$stock_check = $this->Common->get_details('product_stock',array('product_id'=>$product_id));
    	if($stock_check->num_rows()>0)
    	{
    		$data['stock'] = $stock_check->row()->stock;
    	}
    	else
    	{
    		$data['stock'] = '0';
    	}
    	print_r(json_encode($data));
    }
// remove stock ends here//   

//added stock history starts here//
 public function addedStockHistory()
 {
 	if(isset($_POST['submit']))
 	{
 	   $start_date = $this->input->post('start_date');
 	   $end_date   = $this->input->post('end_date');	

 	   $stocks     = $this->stock->get_addedStockHistory($start_date,$end_date);
 	   foreach($stocks as $stock)
		{
			if($stock->updated_by=='a')
			{
				$stock->updated = 'Admin';
			}
			else
			{
				$w_manager      = $this->Common->get_details('warehouse_managers',array('wmanager_id'=>$stock->warehouse_manager_id))->row();
				$stock->updated = $w_manager->name.'<br>'.$w_manager->phone;
			}
		}
 	}
    else
    {
    	$stocks         = $this->stock->get_addedStocks();
		foreach($stocks as $stock)
		{
			if($stock->updated_by=='a')
			{
				$stock->updated = 'Admin';
			}
			else
			{
				$w_manager      = $this->Common->get_details('warehouse_managers',array('wmanager_id'=>$stock->warehouse_manager_id))->row();
				$stock->updated = $w_manager->name.'<br>'.$w_manager->phone;
			}
		}
    }
    $data['stocks']  = $stocks;
 	$this->load->view('admin/warehouse/added_stock_history',$data);
 }
// added stock history ends here//

//removed stock history starts here//
public function removedStockHistory()
 {
 	if(isset($_POST['submit']))
 	{
 	   $start_date = $this->input->post('start_date');
 	   $end_date   = $this->input->post('end_date');	

 	   $stocks     = $this->stock->get_removedStockHistory($start_date,$end_date);
 	   foreach($stocks as $stock)
		{
			if($stock->updated_by=='a')
			{
				$stock->updated = 'Admin';
			}
			else
			{
				$w_manager      = $this->Common->get_details('warehouse_managers',array('wmanager_id'=>$stock->warehouse_manager_id))->row();
				$stock->updated = $w_manager->name.'<br>'.$w_manager->phone;
			}
		}
 	}
    else
    {
    	$stocks         = $this->stock->get_removedStocks();
		foreach($stocks as $stock)
		{
			if($stock->updated_by=='a')
			{
				$stock->updated = 'Admin';
			}
			else
			{
				$w_manager      = $this->Common->get_details('warehouse_managers',array('wmanager_id'=>$stock->warehouse_manager_id))->row();
				$stock->updated = $w_manager->name.'<br>'.$w_manager->phone;
			}
		}
    }
    $data['stocks']  = $stocks;
 	$this->load->view('admin/warehouse/removed_stock_history',$data);
 }
//removed stock history ends here//  

//order history starts here//
public function orderHistory()
 {  
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
	 	   	$stocks         = $this->stock->get_orderStocks();
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
	 	   	$stocks         = $this->stock->get_orderStocksByAgency($agency);
			foreach($stocks as $stock)
			{
				$product            = $this->Common->get_details('products',array('product_id'=>$stock->product_id))->row();
				$stock->product_name = $product->product_name; 
			}
 	   }
   //supervisor search//   
 	   elseif($supervisor!='' && $agency=='' && $date=='')
 	   {
	 	   	$stocks         = $this->stock->get_orderStocksBySupervisor($supervisor);
			foreach($stocks as $stock)
			{
				$product            = $this->Common->get_details('products',array('product_id'=>$stock->product_id))->row();
				$stock->product_name = $product->product_name; 
			}
 	   }
    
    //date search//
 	   elseif($date!='' && $agency=='' && $supervisor=='')
 	   {
	 	   	$stocks         = $this->stock->get_orderStocksByDate($date);
			foreach($stocks as $stock)
			{
				$product            = $this->Common->get_details('products',array('product_id'=>$stock->product_id))->row();
				$stock->product_name = $product->product_name; 
			}
 	   }

   //agency and supervisor search//

 	   elseif($agency!='' && $supervisor!='' && $date=='')
 	   {
	 	   	$stocks         = $this->stock->get_orderStocksByAgencySupervisor($agency,$supervisor);
			foreach($stocks as $stock)
			{
				$product    = $this->Common->get_details('products',array('product_id'=>$stock->product_id))->row();
				$stock->product_name = $product->product_name; 
			}
 	   }

   //agency and date//

 	   elseif($agency!='' && $date!='' && $supervisor=='')
 	   {
	 	   	$stocks         = $this->stock->get_orderStocksByAgencyDate($agency,$date);
			foreach($stocks as $stock)
			{
				$product    = $this->Common->get_details('products',array('product_id'=>$stock->product_id))->row();
				$stock->product_name = $product->product_name; 
			}
 	   }

   //supervisor and date//

 	   elseif($supervisor!='' && $date!='' && $agency=='')
 	   {
	 	   	$stocks         = $this->stock->get_orderStocksBySupervisorDate($supervisor,$date);
			foreach($stocks as $stock)
			{
				$product    = $this->Common->get_details('products',array('product_id'=>$stock->product_id))->row();
				$stock->product_name = $product->product_name; 
			}
 	   }
   //supervisor, agency and date//
       elseif($supervisor!='' && $date!='' && $agency!='')
 	   {
	 	   	$stocks         = $this->stock->get_orderStocksBySupervisorAgencyDate($supervisor,$date,$agency);
			foreach($stocks as $stock)
			{
				$product     = $this->Common->get_details('products',array('product_id'=>$stock->product_id))->row();
				$stock->product_name = $product->product_name; 
			}
 	   }
 	}
    else
    {
    	$stocks         = $this->stock->get_orderStocks();
		foreach($stocks as $stock)
		{
			$product            = $this->Common->get_details('products',array('product_id'=>$stock->product_id))->row();
			$stock->product_name = $product->product_name; 
		}
    }
    $data['stocks']  = $stocks;
 	$this->load->view('admin/warehouse/stock_history',$data);
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
	$this->load->view('admin/warehouse/stockHistoryInvoice',$data);
}
//stock history invoice ends here//

//print invoice//	
	public function invoice($id)
	{   
        $order           = $this->Common->get_details('supervisor_requests',array('srequest_id'=>$id))->row();
		$order->products = $this->Common->get_details('supervisor_request_products',array('request_id'=>$id))->result(); 
        foreach($order->products as $product)
        {
        	$product->name = $this->Common->get_details('products',array('product_id'=>$product->product_id))->row()->product_name;
        }
        $data['order']  = $order;
		$this->load->view('admin/supervisor_requests/invoice',$data);
	}
//end of print//
}
?>
