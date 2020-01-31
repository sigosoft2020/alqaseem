<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->model('Common');
			$this->load->model('android/M_products','products');
			$this->load->model('android/M_cart','cart');
	}
	
    public function index()
	{   
	    $customer_id = $this->security->xss_clean($this->input->post('customer_id'));
		$product_id  = $this->security->xss_clean($this->input->post('product_id'));
		$quantity    = $this->security->xss_clean($this->input->post('quantity'));
        
        $stock       = $this->Common->get_details('product_stock',array('product_id'=>$product_id))->row()->stock;
		$item        = [
        			     'customer_id' => $customer_id,
        			     'product_id'  => $product_id
        		       ];
		if($quantity == 0)
		{
		    $this->Common->delete('cart',$item);
		    $return = [
			            'message' => 'success'
		              ];
		}
		elseif($quantity > $stock)
		{
		    
		    $return = [
			             'message' => 'failed'
		              ];
		}
		else
		{
		    $check = $this->Common->get_details('cart',$item);
    		if ($check->num_rows() > 0) 
    		{
    			$cart_id = $check->row()->cart_id;
    			$this->Common->update('cart_id',$cart_id,'cart',array('quantity' => $quantity));
    		}
    		else 
    		{
    			$item['quantity']   = $quantity;
    			$this->Common->insert('cart',$item);
    		} 
    		
    	    $return = [
			        'message' => 'success'
		          ];	
		}
		
		print_r(json_encode($return));
	}
	
	public function getCartData()
	{
		$customer_id = $this->input->post('customer_id');
		$products    = $this->cart->getCartData($customer_id);
		$total  = 0;
		foreach ($products as $product) 
		{   
		    $product->stock    = $this->Common->get_details('product_stock',array('product_id'=>$product->product_id))->row()->stock;
		    $subtotal          = $product->quantity * $product->price;
		    $product->subtotal = number_format((float)$subtotal, 2, '.', '');
			$total             = $total + ($product->quantity * $product->price);
		}
		$return = [
        			'message'  => 'success',
        			'products' => $products,
        			'total'    => number_format((float)$total, 2, '.', '')
        		  ];
		print_r(json_encode($return));
	}
	
	public function deleteCartData()
	{
	    $cart_id = $this->security->xss_clean($this->input->post('cart_id'));
	    if($this->Common->delete('cart',array('cart_id' => $cart_id)))
	    {
	        $return = [
	          'message' => 'success'  
	        ];
	    }
	    else
	    {
	        $return = [
	          'message' => 'failed'  
	        ];
	    }
	    print_r(json_encode($return));
	}
	
	public function updateCart()
	{
		$customer_id = $this->security->xss_clean($this->input->post('customer_id'));
		$cart_id     = $this->security->xss_clean($this->input->post('cart_id'));
		$quantity    = $this->security->xss_clean($this->input->post('quantity'));

		$array = [
        			'quantity' => $quantity
        	 	];

		if ($this->Common->update('cart_id',$cart_id,'cart',$array)) 
		{
			$products = $this->cart->getCart($customer_id);
			$total = 0;
			$subtotal = 0;
			foreach ($products as $product) 
			{
			    if($product->cart_id == $cart_id)
			    {
			        $subtotal = $product->quantity * $product->price;
			    }
			    
				$total = $total + ($product->quantity * $product->price);
			}
			$return = [
        				'message' => 'success',
        				'total' => number_format((float)$total, 2, '.', ''),
        				'subtotal' => number_format((float)$subtotal, 2, '.', '')
		         	 ];
		}
		else 
		{
			$return = [
			        	'message' => 'failed'
			          ];
		}
		print_r(json_encode($return));
	}
	
}
