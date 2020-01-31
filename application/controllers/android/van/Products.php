<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->model('Common');
			$this->load->model('android/M_products','products');
	}
	
    public function index()
	{   
	    $products = $this->products->getProducts();
		foreach($products as $product)
		{   
		    $category          = $this->Common->get_details('category',array('category_id'=>$product->cat_id))->row();
		    $product->category = $category->category_english.'/'.$category->category_arabic;
		    $product->stock    = $this->Common->get_details('product_stock',array('product_id'=>$product->product_id))->row()->stock;
		    
		}
		$return = [
        			'message' => 'success',
        			'products' => $products
        		  ];
		print_r(json_encode($return));
	}

}
