<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('admin/M_vehicle','vehicle');
			$this->load->model('Common');
			if (!admin()) {
				redirect('users/admin');
			}
	}
	public function index()
	{   
		$vehicles = $this->vehicle->get_vehicles();
		foreach($vehicles as $veh)
		{
			$veh->cans =$this->Common->get_details('vehicle_initial_cans',array('vehicle_id'=>$veh->vehicle_id))->result();
		}
		$data['vehicles']  = $vehicles;
		$this->load->view('admin/vehicle/view',$data);
	}
	
	public function add()
	{
	  $data['products'] = $this->Common->get_details('products',array('product_status'=>'1','cat_id'=>'1'))->result();	
	   $this->load->view('admin/vehicle/add',$data);
	}

	public function get()
	{
        $result = $this->vehicle->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$sub_array[] ='<img src="' . base_url() . $res->person_image . '" height="100px">';
			$sub_array[] = $res->person_name;
			$sub_array[] = $res->person_phone;
			$sub_array[] = $res->person_address;
			$sub_array[] = $res->vehicle_no;
			if($res->status=='1')
			{
				$stat   = 'Active';
				$action = '<a class="btn btn-danger" style="font-size:12px;" href="' . site_url('admin/vehicle/disable/'.$res->vehicle_id) . '" >Disable</a>';
			}
			else
			{
				$stat   = 'Blocked';
				$action = '<a class="btn btn-success" style="font-size:12px;" href="' . site_url('admin/vehicle/enable/'.$res->vehicle_id) . '" >Enable</a>';
			}
			$sub_array[] = $stat.'<br>'.$action;
			$sub_array[] = '<button type="button" style="font-size:12px;" class="btn btn-primary btnSelect" onclick="change_password(' . $res->vehicle_id . ')">Change Password</button>';
			$sub_array[] = '<a href="' . site_url('admin/vehicle/edit/'.$res->vehicle_id) . '"><button type="button" class="btn btn-link" style="font-size:20px;color:blue"><i class="fa fa-pencil"></i></button></a>';
			$data[] = $sub_array;
		}

		$output = array(
							"draw"   => intval($_POST['draw']),
							"recordsTotal" => $this->vehicle->get_all_data(),
							"recordsFiltered" => $this->vehicle->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}
	
	public function addData()
	{
		date_default_timezone_set('Asia/Kolkata');
        $timestamp = date('Y-m-d H:i:s');
        
		$name         = $this->security->xss_clean($this->input->post('person_name'));
		$name_arabic  = $this->security->xss_clean($this->input->post('person_arabic'));
        $vehicle_num  = $this->security->xss_clean($this->input->post('vehicle_number'));
        $phone        = $this->security->xss_clean($this->input->post('person_phone'));
        $address      = $this->security->xss_clean($this->input->post('person_address'));
        // $cans         = $this->security->xss_clean($this->input->post('initial_cans'));
        $password     = md5($this->security->xss_clean($this->input->post('password')));

        $image    = $this->input->post('image');
        if($image!='')
        {
        	$img      = substr($image, strpos($image, ",") + 1);

			$url      = FCPATH.'uploads/admin/vehicle/';
			$rand     = date('Ymd').mt_rand(1001,9999);
			$userpath = $url.$rand.'.png';
			$path     = "uploads/admin/vehicle/".$rand.'.png';
			file_put_contents($userpath,base64_decode($img));
        }
        else
        {
        	$path     = "uploads/admin/vehicle/default.png";
        }
		
		$array = [
					'person_name'       => $name,
					'name_arabic'       => $name_arabic,
					// 'person_code'       => $code,
					'person_phone'      => $phone,
					'vehicle_no'        => $vehicle_num,
					'person_address'    => $address,
					// 'initial_cans_allotted' => $cans,
					'password'          => $password,
					'person_image'      => $path,
					'status'            => '1',
					'timestamp'         => $timestamp
		        ];
		if ($ID=$this->Common->insert('vehicle',$array)) 
		{
		  if($_POST['ProductName']!='')	
		  {
			for($i = 0; $i<count($_POST['ProductName']); $i++)  
            {
               $ProductName  = $_POST['ProductName'][$i];
               $Product_Id   = $_POST['ProductID'][$i];
               $Quantity     = $_POST['Quantity'][$i];
               
               $items        = [
                                    'vehicle_id'      => $ID,
                                    'product_id'    => $Product_Id,
                                    'product_name'  => $ProductName,
                                    'initial_cans'  => $Quantity,
                                    'timestamp'     => $timestamp
                               ];
                $this->Common->insert('vehicle_initial_cans',$items);

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
            }  
           }
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'New vehicle added..!');

			redirect('admin/vehicle');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add vehicle..!');

			redirect('admin/vehicle/add');
		}
	}

	public function edit($id)
	{
	  $vehicle =$this->Common->get_details('vehicle',array('vehicle_id'=>$id))->row();
	  $data['vehicle'] = $vehicle;
	  $this->load->view('admin/vehicle/edit',$data);
	}

	public function editData()
	{
		$vehicle_id    = $this->input->post('vehicle_id');
		$name         = $this->security->xss_clean($this->input->post('person_name'));
        $vehicle_num  = $this->security->xss_clean($this->input->post('vehicle_number'));
        $name_arabic  = $this->security->xss_clean($this->input->post('person_arabic'));
        $phone        = $this->security->xss_clean($this->input->post('person_phone'));
        $address      = $this->security->xss_clean($this->input->post('person_address'));

		$check_mobile   = $this->Common->get_details('vehicle',array('person_phone' => $phone,'vehicle_id!=' => $vehicle_id))->num_rows();
		$check_veh_num  = $this->Common->get_details('vehicle',array('vehicle_no' => $vehicle_num,'vehicle_id!=' => $vehicle_id))->num_rows();
		if ($check_mobile > 0) 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Mobile number already registered..!');
			redirect('admin/vehicle');
		}
		elseif ($check_veh_num > 0) 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Vehicle number already registered..!');
			redirect('admin/vehicle');
		}
		else 
		{
			$image  = $this->input->post('image');
			if ($image != '') 
			{
				$img = substr($image, strpos($image, ",") + 1);

				$url      = FCPATH.'uploads/admin/vehicle/';
				$rand     = date('Ymd').mt_rand(1001,9999);
				$userpath = $url.$rand.'.png';
				$path     = "uploads/admin/vehicle/".$rand.'.png';
				file_put_contents($userpath,base64_decode($img));

				// Remove old image from the server
				$old = $this->Common->get_details('vehicle',array('vehicle_id' => $vehicle_id))->row()->person_image;
				$remove_path = FCPATH . $old;
				unlink($remove_path);

			    $array = [
							'person_name'       => $name,
							'name_arabic'       => $name_arabic,
							// 'person_code'       => $code,
							'person_phone'      => $phone,
							'vehicle_no'        => $vehicle_num,
							'person_address'    => $address,
							'person_image'      => $path
				        ];
		    }
		    else
		    {
                $array = [
							'person_name'       => $name,
							'name_arabic'       => $name_arabic,
							// 'person_code'       => $code,
							'person_phone'      => $phone,
							'vehicle_no'        => $vehicle_num,
							'person_address'    => $address
				        ];
		    }    
			
			if ($this->Common->update('vehicle_id',$vehicle_id,'vehicle',$array)) 
			{
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Vehicle edited successfully..!');
				redirect('admin/vehicle');
			}
			else 
			{
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to update vehicle..!');
				redirect('admin/vehicle');
			}
		}
	}

	public function checkVehicle()
	{
		$phone    = $this->input->post('phone');
		$vehicle  = $this->input->post('vehicle');
		$check_phone   = $this->Common->get_details('vehicle',array('person_phone'=>$phone));
		$check_vehicleNum  = $this->Common->get_details('vehicle',array('vehicle_no'=>$vehicle));
		if($check_phone->num_rows()>0)
		{
			$data ='1';
		}
		elseif($check_vehicleNum->num_rows()>0)
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
		$this->load->view('admin/vehicle/daily_status');
	}

	public function weekly()
	{   
		$this->load->view('admin/vehicle/weekly_status');
	}

	public function monthly()
	{   
		$this->load->view('admin/vehicle/monthly_status');
	}

	public function lifetime()
	{   
		$this->load->view('admin/vehicle/lifetime_status');
	}

	public function disable($id)
	{
			$array = [
				       'status' => '0'
			         ];
		
			if ($this->Common->update('vehicle_id',$id,'vehicle',$array)) 
			{
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Vehicle blocked successfully..!');
				redirect('admin/vehicle');
			}
			else 
			{
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to block vehicle..!');
				redirect('admin/vehicle');
			}
	}

	public function enable($id)
	{
			$array = [
				       'status' => '1'
			         ];
		
			if ($this->Common->update('vehicle_id',$id,'vehicle',$array)) 
			{
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Vehicle activated successfully..!');
				redirect('admin/vehicle');
			}
			else 
			{
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to activate vehicle..!');
				redirect('admin/vehicle');
			}
	}

	public function changePassword()
    {
        $user_id   = $this->security->xss_clean($this->input->post('user_id'));
        $password  = md5($this->security->xss_clean($this->input->post('password')));

        $array = [
		            'password' => $password
		        ];

        $this->Common->update('vehicle_id',$user_id,'vehicle',$array);

        $this->session->set_flashdata('alert_type', 'success');
        $this->session->set_flashdata('alert_title', 'Success');
        $this->session->set_flashdata('alert_message', 'Password updated..!');

        redirect('admin/vehicle');
    }
}
?>
