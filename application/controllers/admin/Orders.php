<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('admin/M_orders','orders');
			$this->load->model('Common');
			if (!admin()) {
				redirect('users/admin');
			}
	}
    
    public function new_orders()
	{   
		$orders = $this->orders->get_newOrders();
		$data['orders'] = $orders;
		$this->load->view('admin/orders/new_orders',$data);
	}

	public function pending()
	{   
		$orders = $this->orders->get_pendingOrders();
		$data['orders'] = $orders;
		$this->load->view('admin/orders/pending',$data);
	}

	public function completed()
	{   
		$orders = $this->orders->get_completedOrders();
		$data['orders'] = $orders;
		$this->load->view('admin/orders/completed',$data);
	}
	
	public function cancelled()
	{   
		$orders = $this->orders->get_cancelledOrders();
		$data['orders'] = $orders;
		$this->load->view('admin/orders/cancelled',$data);
	}

	public function retail()
	{   
		$orders = $this->orders->get_retailOrders();
		$data['orders'] = $orders;
		$this->load->view('admin/orders/retail',$data);
	}

	public function cofilling()
	{   
		$orders = $this->orders->get_cofillingOrders();
		$data['orders'] = $orders;
		$this->load->view('admin/orders/cofilling',$data);
	}

	public function invoice($id)
	{   
        $order           = $this->Common->get_details('customer_orders',array('order_id'=>$id))->row();
		$order->products = $this->Common->get_details('customer_ordered_products',array('order_id'=>$id))->result(); 
        
        $data['order']  = $order;
		$this->load->view('admin/orders/invoice',$data);
	}

	public function view($id)
	{   
		$order           = $this->Common->get_details('customer_orders',array('order_id'=>$id))->row();
		$order->products = $this->Common->get_details('customer_ordered_products',array('order_id'=>$id))->result(); 
        
        $data['order']  = $order;
		$this->load->view('admin/orders/details',$data);
	}

}
?>
