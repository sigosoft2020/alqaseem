<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends CI_Controller {
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
// view stock starts here//

	 public function index()
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
	 	$this->load->view('warehouse/stocks/view_stock',$data);
	 }
// view stock ends here// 

// add stock starts here//
	public function add_stock()
	{   
	    $w_manager    = $this->session->userdata['warehouse'];
		$w_manager_id = $w_manager['warehouse_id'];

		$stocks         = $this->stock->get_addedStocks($w_manager_id);
		
		$data['stocks']  = $stocks;
		$data['products']= $this->Common->get_details('products',array('product_status'=>'1'))->result();
		$this->load->view('warehouse/stocks/added_stock',$data);
	}
    
    public function addStock()
    {
    	date_default_timezone_set('Asia/Kolkata');
        $timestamp    = date('Y-m-d H:i:s');
        $w_manager    = $this->session->userdata['warehouse'];
		$w_manager_id = $w_manager['warehouse_id'];
        
		$product_id   = $this->security->xss_clean($this->input->post('product_id'));
        $no_of_items  = $this->security->xss_clean($this->input->post('no_of_items'));
        $notes        = $this->security->xss_clean($this->input->post('notes'));
        
		$array        = [
							'product_id'  => $product_id,
							'quantity'    => $no_of_items,
							'notes'       => $notes,
							'type'        => 'a',
							'updated_by'  => 'w',
							'warehouse_manager_id' => $w_manager_id,
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

			redirect('warehouse/stock/add_stock');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add stock..!');

			redirect('warehouse/stock/add_stock');
		}
    }

// add stock ends here// 

// remove stock starts here//
	public function remove_stock()
	{   
		$w_manager    = $this->session->userdata['warehouse'];
		$w_manager_id = $w_manager['warehouse_id'];

		$stocks         = $this->stock->get_removedStocks($w_manager_id);
		$data['stocks']  = $stocks;
		$data['products']= $this->Common->get_details('products',array('product_status'=>'1'))->result();
		$this->load->view('warehouse/stocks/removed_stock',$data);
	}
    
    public function removeStock()
    {
    	date_default_timezone_set('Asia/Kolkata');
        $timestamp    = date('Y-m-d H:i:s');
        $w_manager    = $this->session->userdata['warehouse'];
		$w_manager_id = $w_manager['warehouse_id'];
        
		$product_id   = $this->security->xss_clean($this->input->post('product_id'));
        $no_of_items  = $this->security->xss_clean($this->input->post('no_of_items'));
        $notes        = $this->security->xss_clean($this->input->post('notes'));
        
		$array        = [
							'product_id'  => $product_id,
							'quantity'    => $no_of_items,
							'notes'       => $notes,
							'type'        => 'r',
							'updated_by'  => 'w',
							'warehouse_manager_id' => $w_manager_id,
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

			redirect('warehouse/stock/remove_stock');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to remove stock..!');

			redirect('warehouse/stock/remove_stock');
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


}
?>
