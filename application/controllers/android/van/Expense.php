<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->model('Common');
	}
	
    public function add()
	{   
	    date_default_timezone_set("Asia/Calcutta");
	    $timestamp  = date('Y-m-d H:i:s');
	    
	    $agency_id      = $this->security->xss_clean($this->input->post('agency_id'));
	    $category_id    = $this->security->xss_clean($this->input->post('category_id'));
		$expense_name   = $this->security->xss_clean($this->input->post('expense_name'));
		$expense_amount = $this->security->xss_clean($this->input->post('expense_amount'));
		$date           = $this->security->xss_clean($this->input->post('date'));
		$time           = $this->security->xss_clean($this->input->post('time'));
		$agency_name    = $this->Common->get_details('agencies',array('agency_id'=>$agency_id))->row()->agency_name;
		
		$array          = [
		                    'agency_id'     => $agency_id,
		                    'cat_id'        => $category_id,
		                    'agency_name'   => $agency_name,
		                    'expense_name'  => $expense_name,
		                    'expense_amount'=> $expense_amount,
		                    'date'          => $date,
		                    'time'          => $time,
		                    'timestamp'     => $timestamp
		                  ];

       if($this->Common->insert('agency_expense',$array))
       {
          $message   = 'success';
       }
       else
       {
           $message = 'failed';
       }
        $return     = [
                         'message'  => $message
                      ];
		print_r(json_encode($return));
	}
	
	public function category()
	{
	    $category = $this->Common->get_details('expense_category',array('status'=>'1'))->result();
	    $data['category'] = $category;
	    
	    $return = [
	                 'message'  => 'success',
	                 'category' => $category
	              ];
	   print_r(json_encode($return));           
	}

}
