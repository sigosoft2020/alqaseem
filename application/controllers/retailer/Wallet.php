<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('retailer/M_wallet','wallet');
			$this->load->model('Common');
			if (!retailer()) {
				redirect('users/retailer');
			}
	}
	public function index()
	{
		$this->load->view('retailer/wallet/view');
	}
	
	public function get()
	{
        $result = $this->wallet->make_datatables();
		$data = array();
		foreach ($result as $res) {
			$sub_array = array();
			$sub_array[] = $res->customer_name;
			$sub_array[] = $res->customer_phone;
			$sub_array[] = $res->amount;
			if($res->type=='debit')
			{
				$wallet_type = "Debit";
			}
			{
				$wallet_type = "Credit";
			}
			$sub_array[] = $wallet_type;
			$data[] = $sub_array;
		}

		$output = array(
			"draw"   => intval($_POST['draw']),
			"recordsTotal" => $this->wallet->get_all_data(),
			"recordsFiltered" => $this->wallet->get_filtered_data(),
			"data" => $data
		);
		echo json_encode($output);
	}
	
}
?>
