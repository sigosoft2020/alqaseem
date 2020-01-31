<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agency extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('admin/M_agencies','agencies');
			$this->load->model('Common');
			if (!admin()) {
				redirect('users/admin');
			}
	}
	public function index()
	{   
		$agencies = $this->agencies->get_agencies();
		foreach($agencies as $ag)
		{
			$ag->cans =$this->Common->get_details('agency_initial_cans',array('agency_id'=>$ag->agency_id))->result();
		}
		$data['agencies']  = $agencies;
		$this->load->view('admin/agency/view',$data);
	}
	
	public function add()
	{ 
	   $data['products'] = $this->Common->get_details('products',array('product_status'=>'1','cat_id'=>'1'))->result();	
	   $this->load->view('admin/agency/add',$data);
	}

	public function get()
	{
        $result = $this->agencies->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$sub_array[] = '<img src="' . base_url() . $res->agency_image . '" height="100px">';
			$sub_array[] = $res->agency_name.'<br>'.$res->name_arabic;
			$sub_array[] = $res->agency_code;
			$sub_array[] = $res->agency_phone;
			$sub_array[] = $res->vehicle_number;
			$sub_array[] = $res->agency_staff.'<br>'.$res->staff_arabic;
			// $sub_array[] = $res->initial_cans_allotted;
			$cans        = $this->Common->get_details('agency_initial_cans',array('agency_id'=>$res->agency_id))->result();
			foreach($cans as $can)
			{
				$cans_alloted = $can->product_name.' - '.$can->initial_cans;
			}
			$sub_array[] =$cans_alloted;
			if($res->agency_status=='1')
			{
				$status = 'Active';
				$action = '<a class="btn btn-danger" style="font-size:12px;" href="' . site_url('admin/agency/disable/'.$res->agency_id) . '" >Disable</a>';
			}
			else
			{
				$status = 'Blocked';
				$action = '<a class="btn btn-success" style="font-size:12px;" href="' . site_url('admin/agency/enable/'.$res->agency_id) . '" >Enable</a>';
			}
			$sub_array[] = $status.'<br>'.$action;
			$sub_array[] = '<a href="' . site_url('admin/agency/edit/'.$res->agency_id) . '"><button type="button" class="btn btn-link" style="font-size:20px;color:blue"><i class="fa fa-pencil"></i></button></a>';
			$data[] = $sub_array;
		}

		$output = array(
							"draw"   => intval($_POST['draw']),
							"recordsTotal" => $this->agencies->get_all_data(),
							"recordsFiltered" => $this->agencies->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}
	
	public function addData()
	{
		date_default_timezone_set('Asia/Kolkata');
        $timestamp = date('Y-m-d H:i:s');
        
		$name         = $this->security->xss_clean($this->input->post('agency_name'));
		$name_arabic  = $this->security->xss_clean($this->input->post('agency_arabic'));
        $code         = $this->security->xss_clean($this->input->post('agency_code'));
        $vehicle_num  = $this->security->xss_clean($this->input->post('vehicle_number'));
        $phone        = $this->security->xss_clean($this->input->post('agency_phone'));
        $staff        = $this->security->xss_clean($this->input->post('agency_staff'));
        $staff_arabic = $this->security->xss_clean($this->input->post('staff_arabic'));
        // $cans         = $this->security->xss_clean($this->input->post('initial_cans'));
        $password     = md5($this->security->xss_clean($this->input->post('password')));
        
        $image    = $this->input->post('image');
        if($image!='')
        {
        	$img      = substr($image, strpos($image, ",") + 1);

			$url      = FCPATH.'uploads/admin/agency/';
			$rand     = date('Ymd').mt_rand(1001,9999);
			$userpath = $url.$rand.'.png';
			$path     = "uploads/admin/agency/".$rand.'.png';
			file_put_contents($userpath,base64_decode($img));
        }
		else
		{
			$path     = "uploads/admin/agency/default.png";
		}

		$array = [
					'agency_name'       => $name,
					'name_arabic'       => $name_arabic,
					'agency_code'       => $code,
					'agency_phone'      => $phone,
					'vehicle_number'    => $vehicle_num,
					'agency_staff'      => $staff,
					'staff_arabic'      => $staff_arabic,
					// 'initial_cans_allotted' => $cans,
					'agency_password'   => $password,
					'agency_image'      => $path,
					'agency_status'     => '1',
					'timestamp'         => $timestamp
		        ];
		if ($ID=$this->Common->insert('agencies',$array)) 
		{   
		   if($_POST['ProductName']!='') 
		   {    
		   	    $GrandTotal= 0;     
		        for($i = 0; $i<count($_POST['ProductName']); $i++) 
		        {
		             $Total=$_POST['Total'][$i]; 
		             $GrandTotal=$GrandTotal+$Total;
		             
		        };
		        
		   	    $bill = [
           	             'agency_id'     => $ID,
	                     'agency_name'   => $name,
	                     'total'         => $GrandTotal,
	                     'sale_date'     => date('Y-m-d'),
	                     'sale_time'     => date('H:i:s'),
	                     'added_by'      => 'a',
	                     'timestamp'     => $timestamp
                   ];
                $bill_id = $this->Common->insert('agency_sales',$bill);  

            for($i = 0; $i<count($_POST['ProductName']); $i++)  
            {
               $ProductName  = $_POST['ProductName'][$i];
               $Product_Id   = $_POST['ProductID'][$i];
               $Price        = $_POST['Price'][$i];
               $Quantity     = $_POST['Quantity'][$i];
               $Total        = $_POST['Total'][$i];
               
               $items        = [
                                    'agency_id'      => $ID,
                                    'product_id'    => $Product_Id,
                                    'product_name'  => $ProductName,
                                    'price'         => $Price,
                                    'initial_cans'  => $Quantity,
                                    'total'         => $Total,
                                    'timestamp'     => $timestamp
                               ];
                $this->Common->insert('agency_initial_cans',$items);

                $stock_details = $this->Common->get_details('product_stock',array('product_id'=>$Product_Id))->row();
                $stocks        = $stock_details->stock;
                $stock_id      = $stock_details->stock_id;
                $new_stock     = $stocks-$Quantity;
                if($stocks>=$Quantity)
                {
                    $stock_array = [
                                     'stock' => $new_stock
                                   ];
                }
                else
                {
                    $stock_array = [
                                     'stock' => '0'
                                   ];
                }
                $this->Common->update('stock_id',$stock_id,'product_stock',$stock_array);

                 $bil_items  = [
                                    'as_id'         => $bill_id,
                                    'product_id'    => $Product_Id,
                                    'product_name'  => $ProductName,
                                    'price'         => $Price,
                                    'quantity'      => $Quantity,
                                    'total'         => $Total,
                                    'timestamp'     => $timestamp
                               ];
                $this->Common->insert('agency_sale_products',$bil_items);
            }
           }  
          
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'New agency added..!');
			redirect('admin/agency');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add agency..!');
			redirect('admin/agency/add');
		}
	}

	public function edit($id)
	{
	  $agency =$this->Common->get_details('agencies',array('agency_id'=>$id))->row();
	  $data['agency'] = $agency;
	  $this->load->view('admin/agency/edit',$data);
	}

	public function editData()
	{
		$agency_id    = $this->input->post('agency_id');
		$name         = $this->security->xss_clean($this->input->post('agency_name'));
		$name_arabic  = $this->security->xss_clean($this->input->post('agency_arabic'));
        $code         = $this->security->xss_clean($this->input->post('agency_code'));
        $vehicle_num  = $this->security->xss_clean($this->input->post('vehicle_number'));
        $phone        = $this->security->xss_clean($this->input->post('agency_phone'));
        $staff        = $this->security->xss_clean($this->input->post('agency_staff'));
        $staff_arabic = $this->security->xss_clean($this->input->post('staff_arabic'));
        // $cans         = $this->security->xss_clean($this->input->post('initial_cans'));

		$check_mobile   = $this->Common->get_details('agencies',array('agency_phone' => $phone ,'agency_id!=' => $agency_id))->num_rows();
		$check_code     = $this->Common->get_details('agencies',array('agency_code' => $code ,'agency_id!=' => $agency_id))->num_rows();
		if ($check_mobile > 0) 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Mobile number already exist..!');

			redirect('admin/agency');
		}
		elseif ($check_code > 0) 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Agency code already exist..!');

			redirect('admin/agency');
		}
		else 
		{
			$image  = $this->input->post('image');
			if ($image != '') 
			{
				$img = substr($image, strpos($image, ",") + 1);

				$url      = FCPATH.'uploads/admin/agency/';
				$rand     = date('Ymd').mt_rand(1001,9999);
				$userpath = $url.$rand.'.png';
				$path     = "uploads/admin/agency/".$rand.'.png';
				file_put_contents($userpath,base64_decode($img));

				// Remove old image from the server
				$old = $this->Common->get_details('agencies',array('agency_id' => $agency_id))->row()->agency_image;
				$remove_path = FCPATH . $old;
				unlink($remove_path);

			   $array = [
						'agency_name'       => $name,
						'name_arabic'       => $name_arabic,
						'agency_code'       => $code,
						'agency_phone'      => $phone,
						'vehicle_number'    => $vehicle_num,
						'agency_staff'      => $staff,
						'staff_arabic'      => $staff_arabic,
						// 'initial_cans_allotted' => $cans,
						'agency_image'      => $path
			        ];
		    }
		    else
		    {
                $array = [
						'agency_name'       => $name,
						'name_arabic'       => $name_arabic,
						'agency_code'       => $code,
						'agency_phone'      => $phone,
						'vehicle_number'    => $vehicle_num,
						'agency_staff'      => $staff,
						'staff_arabic'      => $staff_arabic,
						// 'initial_cans_allotted' => $cans
			        ];
		    }    
			
			if ($this->Common->update('agency_id',$agency_id,'agencies',$array)) 
			{
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Agency edited successfully..!');

				redirect('admin/agency');
			}
			else 
			{
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to update agency..!');

				redirect('admin/agency');
			}
		}
	}
	public function checkAgency()
	{
		$phone = $this->input->post('phone');
		$code  = $this->input->post('code');
		$check_phone = $this->Common->get_details('agencies',array('agency_phone'=>$phone));
		$check_code  = $this->Common->get_details('agencies',array('agency_code'=>$code));
		if($check_phone->num_rows()>0)
		{
			$data ='1';
		}
		elseif($check_code->num_rows()>0)
		{
			$data ='2';
		}
		else
		{
			$data ='0';
		}
		print_r($data);
	}

    public function getAgencyById()
	{
		$id = $_POST['id'];
		$data = $this->Common->get_details('agencies',array('agency_id' => $id))->row();
		print_r(json_encode($data));
	}

	public function daily()
	{   
	   $agencies    = $this->agencies->getAgencies(); 
	   foreach($agencies as $agency)
	   {
	       $agency->total_sale       = $this->agencies->getTodaysTotalSale($agency->agency_id); 
		   $agency->total_credit     = $this->agencies->getTodaysTotalCredit($agency->agency_id);
		   $agency->payment_received = $this->agencies->getTodaysTotalPayment($agency->agency_id);
		   $agency->total_expense    = $this->agencies->getExpenseToday($agency->agency_id);
	   }
	   
	   $data['agencies'] = $agencies;
	   $this->load->view('admin/agency/daily_status',$data);
	}

	public function weekly()
	{   
	   $agencies    = $this->agencies->getAgencies(); 
	   foreach($agencies as $agency)
	   {
	       $agency->total_sale       = $this->agencies->getWeeksTotalSale($agency->agency_id); 
		   $agency->total_credit     = $this->agencies->getWeeksTotalCredit($agency->agency_id);
		   $agency->payment_received = $this->agencies->getWeeksTotalPayment($agency->agency_id);
		   $agency->total_expense    = $this->agencies->getExpenseWeek($agency->agency_id);
	   }
	   $data['agencies'] = $agencies;
	   $this->load->view('admin/agency/weekly_status',$data);
	}

	public function monthly()
	{   
	    $agencies    = $this->agencies->getAgencies(); 
	    foreach($agencies as $agency)
	    {
	       $agency->total_sale       = $this->agencies->getMonthTotalSale($agency->agency_id); 
		   $agency->total_credit     = $this->agencies->getMonthTotalCredit($agency->agency_id);
		   $agency->payment_received = $this->agencies->getMonthTotalPayment($agency->agency_id);
	 	   $agency->total_expense    = $this->agencies->getExpenseMonth($agency->agency_id);
	    }
	    $data['agencies'] = $agencies;
		$this->load->view('admin/agency/monthly_status',$data);
	}

	public function lifetime()
	{   
	    $agencies    = $this->agencies->getAgencies(); 
	    foreach($agencies as $agency)
	    {
	       $agency->total_sale       = $this->agencies->getTotalSale($agency->agency_id); 
		   $agency->total_credit     = $this->agencies->getTotalCredit($agency->agency_id);
		   $agency->payment_received = $this->agencies->getTotalPayment($agency->agency_id);
	 	   $agency->total_expense    = $this->agencies->getExpenseTotal($agency->agency_id);
	    }
	    $data['agencies'] = $agencies;
		$this->load->view('admin/agency/lifetime_status',$data);
	}

	public function disable($id)
	{
			$array = [
				       'agency_status' => '0'
			         ];
		
			if ($this->Common->update('agency_id',$id,'agencies',$array)) {
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Agency blocked successfully..!');

				redirect('admin/agency');
			}
			else {
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to block agency..!');

				redirect('admin/agency');
			}
	}

	public function enable($id)
	{
			$array = [
				       'agency_status' => '1'
			         ];
		
			if ($this->Common->update('agency_id',$id,'agencies',$array)) {
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Agency activated successfully..!');

				redirect('admin/agency');
			}
			else {
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to activate agency..!');

				redirect('admin/agency');
			}
	}
}
?>
