<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('admin/users/M_warehouse','warehouse');
			$this->load->model('admin/users/M_supervisor','supervisor');
			$this->load->model('admin/users/M_retail','retail');
			$this->load->model('Common');
			if (!admin()) {
				redirect('users/admin');
			}
	}

//warehouse manager//

	public function warehouse_managers()
	{
		$this->load->view('admin/users/warehouse/view');
	}
	
	public function add_warehousemanager()
	{
	   $this->load->view('admin/users/warehouse/add');
	}

	public function get_warehouse()
	{
        $result = $this->warehouse->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$sub_array[] = '<img src="' . base_url() . $res->image . '" height="100px">';
			$sub_array[] = $res->name.'<br>'.$res->name_arabic;
			$sub_array[] = $res->phone;
			$sub_array[] = $res->email;
			if($res->status=='1')
			{
				$status = 'Active';
				$action = '<a class="btn btn-danger" style="font-size:12px;" href="' . site_url('admin/users/disable_warehousemanager/'.$res->wmanager_id) . '" >Disable</a>';
			}
			else
			{
				$status = 'Blocked';
				$action = '<a class="btn btn-success" style="font-size:12px;" href="' . site_url('admin/users/enable_warehousemanager/'.$res->wmanager_id) . '" >Enable</a>';
			}
			$sub_array[] = $status.'<br>'.$action;
			$sub_array[] = '<button type="button" style="font-size:12px;" class="btn btn-primary btnSelect" onclick="change_password(' . $res->wmanager_id . ')">Change Password</button>';
			$sub_array[] = '<a href="' . site_url('admin/users/edit_warehousemanager/'.$res->wmanager_id) . '"><button type="button" class="btn btn-primary" style="font-size:12px;">Edit</button></a>';
			$data[] = $sub_array;
		}

		$output = array(
							"draw"   => intval($_POST['draw']),
							"recordsTotal" => $this->warehouse->get_all_data(),
							"recordsFiltered" => $this->warehouse->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}
	
	public function addWarehouseManager()
	{
		date_default_timezone_set('Asia/Kolkata');
        $timestamp = date('Y-m-d H:i:s');
        
		$name         = $this->security->xss_clean($this->input->post('name'));
		$name_arabic  = $this->security->xss_clean($this->input->post('name_arabic'));
        $phone        = $this->security->xss_clean($this->input->post('phone'));
        $email        = $this->security->xss_clean($this->input->post('email'));
        $password     = md5($this->security->xss_clean($this->input->post('password')));

        $image    = $this->input->post('image');
        if($image!='')
        {
        	$img      = substr($image, strpos($image, ",") + 1);

			$url      = FCPATH.'uploads/admin/users/warehouse_managers/';
			$rand     = date('Ymd').mt_rand(1001,9999);
			$userpath = $url.$rand.'.png';
			$path     = "uploads/admin/users/warehouse_managers/".$rand.'.png';
			file_put_contents($userpath,base64_decode($img));
        }
		else
		{
			$path  = 'uploads/admin/users/warehouse_managers/user.png';
		}

		$array = [
					'name'        => $name,
					'name_arabic' => $name_arabic,
					'phone'       => $phone,
					'email'       => $email,
					'password'    => $password,
					'image'       => $path,
					'status'      => '1',
					'timestamp'   => $timestamp
		        ];
		if ($this->Common->insert('warehouse_managers',$array)) 
		{
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'New manager added..!');

			redirect('admin/users/warehouse_managers');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add manager..!');

			redirect('admin/users/add_warehousemanager');
		}
	}

	public function edit_warehousemanager($id)
	{
	  $w_manager =$this->Common->get_details('warehouse_managers',array('wmanager_id'=>$id))->row();
	  $data['w_manager'] = $w_manager;
	  $this->load->view('admin/users/warehouse/edit',$data);
	}

	public function editWarehouseManager()
	{
		$user_id     = $this->input->post('user_id');
		$name         = $this->security->xss_clean($this->input->post('name'));
		$name_arabic  = $this->security->xss_clean($this->input->post('name_arabic'));
        $phone        = $this->security->xss_clean($this->input->post('phone'));
        $email        = $this->security->xss_clean($this->input->post('email'));

		$check_mobile   = $this->Common->get_details('warehouse_managers',array('phone' => $phone ,'wmanager_id!=' => $user_id))->num_rows();
		$check_email     = $this->Common->get_details('warehouse_managers',array('email' => $email ,'wmanager_id!=' => $user_id))->num_rows();
		if ($check_mobile > 0) 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Mobile number already exist..!');

			redirect('admin/users/edit_warehousemanager/'.$user_id);
		}
		elseif ($check_email > 0) 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Email already exist..!');

			redirect('admin/users/edit_warehousemanager/'.$user_id);
		}
		else 
		{
			$image  = $this->input->post('image');
			if ($image != '') 
			{
				$img      = substr($image, strpos($image, ",") + 1);

				$url      = FCPATH.'uploads/admin/users/warehouse_managers/';
				$rand     = date('Ymd').mt_rand(1001,9999);
				$userpath = $url.$rand.'.png';
				$path     = "uploads/admin/users/warehouse_managers/".$rand.'.png';
				file_put_contents($userpath,base64_decode($img));

			   $array = [
							'name'        => $name,
							'name_arabic' => $name_arabic,
							'phone'       => $phone,
							'email'       => $email,
							'image'       => $path,
				        ];
		    }
		    else
		    {
               $array = [
							'name'        => $name,
							'name_arabic' => $name_arabic,
							'phone'       => $phone,
							'email'       => $email,
				        ];
		    }    
			
			if ($this->Common->update('wmanager_id',$user_id,'warehouse_managers',$array)) 
			{
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Warehouse manager edited successfully..!');

				redirect('admin/users/warehouse_managers');
			}
			else 
			{
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to update warehouse manager..!');

				redirect('admin/users/edit_warehousemanager/'.$user_id);
			}
		}
	}
	public function checkWarehouseManager()
	{
		$phone  = $this->input->post('phone');
		$email  = $this->input->post('email');
		$check_phone = $this->Common->get_details('warehouse_managers',array('phone'=>$phone));
		$check_email  = $this->Common->get_details('warehouse_managers',array('email'=>$email));
		if($check_phone->num_rows()>0)
		{
			$data ='1';
		}
		elseif($check_email->num_rows()>0)
		{
			$data ='2';
		}
		else
		{
			$data ='0';
		}
		print_r($data);
	}
    
    public function disable_warehousemanager($id)
	{
		$array = [
			       'status' => '0'
		         ];
	
		if ($this->Common->update('wmanager_id',$id,'warehouse_managers',$array)) {
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'User blocked successfully..!');

			redirect('admin/users/warehouse_managers');
		}
		else {
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to block User..!');

			redirect('admin/users/warehouse_managers');
		}
	}

	public function enable_warehousemanager($id)
	{
		$array = [
			       'status' => '1'
		         ];
	
		if ($this->Common->update('wmanager_id',$id,'warehouse_managers',$array)) {
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'User activated successfully..!');

			redirect('admin/users/warehouse_managers');
		}
		else {
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to activate agency..!');

			redirect('admin/users/warehouse_managers');
		}
	}

	public function changePassword_Wmanager()
    {
        $user_id   = $this->security->xss_clean($this->input->post('user_id'));
        $password  = md5($this->security->xss_clean($this->input->post('password')));

        $array = [
		            'password' => $password
		        ];

        $this->Common->update('wmanager_id',$user_id,'warehouse_managers',$array);

        $this->session->set_flashdata('alert_type', 'success');
        $this->session->set_flashdata('alert_title', 'Success');
        $this->session->set_flashdata('alert_message', 'Password updated..!');

        redirect('admin/users/warehouse_managers');
    }

//end of warehouse manager//

//supervisors start from here//

	public function supervisors()
	{
		$this->load->view('admin/users/supervisor/view');
	}
	
	public function add_supervisor()
	{
	   $this->load->view('admin/users/supervisor/add');
	}

	public function get_supervisor()
	{
        $result = $this->supervisor->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$sub_array[] = '<img src="' . base_url() . $res->image . '" height="100px">';
			$sub_array[] = $res->name.'<br>'.$res->name_arabic;
			$sub_array[] = $res->phone;
			$sub_array[] = $res->email;
			if($res->status=='1')
			{
				$status = 'Active';
				$action = '<a class="btn btn-danger" style="font-size:12px;" href="' . site_url('admin/users/disable_supervisor/'.$res->supervisor_id) . '" >Disable</a>';
			}
			else
			{
				$status = 'Blocked';
				$action = '<a class="btn btn-success" style="font-size:12px;" href="' . site_url('admin/users/enable_supervisor/'.$res->supervisor_id) . '" >Enable</a>';
			}
			$sub_array[] = $status.'<br>'.$action;
			$sub_array[] = '<button type="button" style="font-size:12px;" class="btn btn-primary btnSelect" onclick="change_password(' . $res->supervisor_id . ')">Change Password</button>';
			$sub_array[] = '<a href="' . site_url('admin/users/edit_supervisor/'.$res->supervisor_id) . '"><button type="button" class="btn btn-primary" style="font-size:12px;">Edit</button></a>';
			$data[] = $sub_array;
		}

		$output = array(
							"draw"   => intval($_POST['draw']),
							"recordsTotal" => $this->supervisor->get_all_data(),
							"recordsFiltered" => $this->supervisor->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}
	
	public function addSupervisor()
	{
		date_default_timezone_set('Asia/Kolkata');
        $timestamp = date('Y-m-d H:i:s');
        
		$name         = $this->security->xss_clean($this->input->post('name'));
		$name_arabic  = $this->security->xss_clean($this->input->post('name_arabic'));
        $phone        = $this->security->xss_clean($this->input->post('phone'));
        $email        = $this->security->xss_clean($this->input->post('email'));
        $password     = md5($this->security->xss_clean($this->input->post('password')));

        $image    = $this->input->post('image');
        if($image!='')
        {
        	$img      = substr($image, strpos($image, ",") + 1);

			$url      = FCPATH.'uploads/admin/users/supervisors/';
			$rand     = date('Ymd').mt_rand(1001,9999);
			$userpath = $url.$rand.'.png';
			$path     = "uploads/admin/users/supervisors/".$rand.'.png';
			file_put_contents($userpath,base64_decode($img));
        }
		else
		{
			$path  = 'uploads/admin/users/supervisors/user.png';
		}

		$array = [
					'name'        => $name,
					'name_arabic' => $name_arabic,
					'phone'       => $phone,
					'email'       => $email,
					'password'    => $password,
					'image'       => $path,
					'status'      => '1',
					'timestamp'   => $timestamp
		        ];
		if ($this->Common->insert('supervisors',$array)) 
		{
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'New supervisor added..!');

			redirect('admin/users/supervisors');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add supervisor..!');

			redirect('admin/users/add_supervisor');
		}
	}

	public function edit_supervisor($id)
	{
	  $supervisor =$this->Common->get_details('supervisors',array('supervisor_id'=>$id))->row();
	  $data['supervisor'] = $supervisor;
	  $this->load->view('admin/users/supervisor/edit',$data);
	}

	public function editSupervisor()
	{
		$user_id     = $this->input->post('user_id');
		$name         = $this->security->xss_clean($this->input->post('name'));
		$name_arabic  = $this->security->xss_clean($this->input->post('name_arabic'));
        $phone        = $this->security->xss_clean($this->input->post('phone'));
        $email        = $this->security->xss_clean($this->input->post('email'));

		$check_mobile   = $this->Common->get_details('supervisors',array('phone' => $phone ,'supervisor_id!=' => $user_id))->num_rows();
		$check_email     = $this->Common->get_details('supervisors',array('email' => $email ,'supervisor_id!=' => $user_id))->num_rows();
		if ($check_mobile > 0) 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Mobile number already exist..!');

			redirect('admin/users/edit_supervisor/'.$user_id);
		}
		elseif ($check_email > 0) 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Email already exist..!');

			redirect('admin/users/edit_supervisor/'.$user_id);
		}
		else 
		{
			$image  = $this->input->post('image');
			if ($image != '') 
			{
				$img      = substr($image, strpos($image, ",") + 1);

				$url      = FCPATH.'uploads/admin/users/supervisors/';
				$rand     = date('Ymd').mt_rand(1001,9999);
				$userpath = $url.$rand.'.png';
				$path     = "uploads/admin/users/supervisors/".$rand.'.png';
				file_put_contents($userpath,base64_decode($img));

			   $array = [
							'name'        => $name,
							'name_arabic' => $name_arabic,
							'phone'       => $phone,
							'email'       => $email,
							'image'       => $path,
				        ];
		    }
		    else
		    {
               $array = [
							'name'        => $name,
							'name_arabic' => $name_arabic,
							'phone'       => $phone,
							'email'       => $email,
				        ];
		    }    
			
			if ($this->Common->update('supervisor_id',$user_id,'supervisors',$array)) 
			{
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'User edited successfully..!');

				redirect('admin/users/supervisors');
			}
			else 
			{
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to update user..!');

				redirect('admin/users/edit_supervisor/'.$user_id);
			}
		}
	}
	public function checksupervisor()
	{
		$phone  = $this->input->post('phone');
		$email  = $this->input->post('email');
		$check_phone = $this->Common->get_details('supervisors',array('phone'=>$phone));
		$check_email  = $this->Common->get_details('supervisors',array('email'=>$email));
		if($check_phone->num_rows()>0)
		{
			$data ='1';
		}
		elseif($check_email->num_rows()>0)
		{
			$data ='2';
		}
		else
		{
			$data ='0';
		}
		print_r($data);
	}
    
    public function disable_supervisor($id)
	{
		$array = [
			       'status' => '0'
		         ];
	
		if ($this->Common->update('supervisor_id',$id,'supervisors',$array)) {
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'User blocked successfully..!');

			redirect('admin/users/supervisors');
		}
		else {
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to block User..!');

			redirect('admin/users/supervisors');
		}
	}

	public function enable_supervisor($id)
	{
		$array = [
			       'status' => '1'
		         ];
	
		if ($this->Common->update('supervisor_id',$id,'supervisors',$array)) {
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'User activated successfully..!');

			redirect('admin/users/supervisors');
		}
		else {
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to activate user..!');

			redirect('admin/users/supervisors');
		}
	}

	public function changePassword_supervisor()
    {
        $user_id   = $this->security->xss_clean($this->input->post('user_id'));
        $password  = md5($this->security->xss_clean($this->input->post('password')));

        $array = [
		            'password' => $password
		         ];

        $this->Common->update('supervisor_id',$user_id,'supervisors',$array);

        $this->session->set_flashdata('alert_type', 'success');
        $this->session->set_flashdata('alert_title', 'Success');
        $this->session->set_flashdata('alert_message', 'Password updated..!');

        redirect('admin/users/supervisors');
    }

//end of supervisors//

//Retail managers start from here//

	public function retail_managers()
	{
		$this->load->view('admin/users/retail/view');
	}
	
	public function add_retailManager()
	{
	   $this->load->view('admin/users/retail/add');
	}

	public function get_retailManager()
	{
        $result = $this->retail->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$sub_array[] = '<img src="' . base_url() . $res->image . '" height="100px">';
			$sub_array[] = $res->name.'<br>'.$res->name_arabic;
			$sub_array[] = $res->phone;
			$sub_array[] = $res->email;
			if($res->status=='1')
			{
				$status = 'Active';
				$action = '<a class="btn btn-danger" style="font-size:12px;" href="' . site_url('admin/users/disable_retailManager/'.$res->rmanager_id) . '" >Disable</a>';
			}
			else
			{
				$status = 'Blocked';
				$action = '<a class="btn btn-success" style="font-size:12px;" href="' . site_url('admin/users/enable_retailManager/'.$res->rmanager_id) . '" >Enable</a>';
			}
			$sub_array[] = $status.'<br>'.$action;
			$sub_array[] = '<button type="button" style="font-size:12px;" class="btn btn-primary btnSelect" onclick="change_password(' . $res->rmanager_id . ')">Change Password</button>';
			$sub_array[] = '<a href="' . site_url('admin/users/edit_retailManager/'.$res->rmanager_id) . '"><button type="button" class="btn btn-primary" style="font-size:12px;">Edit</button></a>';
			$data[] = $sub_array;
		}

		$output = array(
							"draw"   => intval($_POST['draw']),
							"recordsTotal" => $this->retail->get_all_data(),
							"recordsFiltered" => $this->retail->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}
	
	public function addRetailManager()
	{
		date_default_timezone_set('Asia/Kolkata');
        $timestamp = date('Y-m-d H:i:s');
        
		$name         = $this->security->xss_clean($this->input->post('name'));
		$name_arabic  = $this->security->xss_clean($this->input->post('name_arabic'));
        $phone        = $this->security->xss_clean($this->input->post('phone'));
        $email        = $this->security->xss_clean($this->input->post('email'));
        $password     = md5($this->security->xss_clean($this->input->post('password')));

        $image    = $this->input->post('image');
        if($image!='')
        {
        	$img      = substr($image, strpos($image, ",") + 1);

			$url      = FCPATH.'uploads/admin/users/retail_managers/';
			$rand     = date('Ymd').mt_rand(1001,9999);
			$userpath = $url.$rand.'.png';
			$path     = "uploads/admin/users/retail_managers/".$rand.'.png';
			file_put_contents($userpath,base64_decode($img));
        }
		else
		{
			$path  = 'uploads/admin/users/retail_managers/user.png';
		}

		$array = [
					'name'        => $name,
					'name_arabic' => $name_arabic,
					'phone'       => $phone,
					'email'       => $email,
					'password'    => $password,
					'image'       => $path,
					'status'      => '1',
					'timestamp'   => $timestamp
		        ];
		if ($this->Common->insert('retail_managers',$array)) 
		{
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'New retail manager added..!');

			redirect('admin/users/retail_managers');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add retail manager..!');

			redirect('admin/users/add_retailManager');
		}
	}

	public function edit_retailManager($id)
	{
	  $retail =$this->Common->get_details('retail_managers',array('rmanager_id'=>$id))->row();
	  $data['retail'] = $retail;
	  $this->load->view('admin/users/retail/edit',$data);
	}

	public function editRetailManager()
	{
		$user_id     = $this->input->post('user_id');
		$name         = $this->security->xss_clean($this->input->post('name'));
		$name_arabic  = $this->security->xss_clean($this->input->post('name_arabic'));
        $phone        = $this->security->xss_clean($this->input->post('phone'));
        $email        = $this->security->xss_clean($this->input->post('email'));

		$check_mobile   = $this->Common->get_details('retail_managers',array('phone' => $phone ,'rmanager_id!=' => $user_id))->num_rows();
		$check_email     = $this->Common->get_details('retail_managers',array('email' => $email ,'rmanager_id!=' => $user_id))->num_rows();
		if ($check_mobile > 0) 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Mobile number already exist..!');

			redirect('admin/users/edit_retailManager/'.$user_id);
		}
		elseif ($check_email > 0) 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Email already exist..!');

			redirect('admin/users/edit_retailManager/'.$user_id);
		}
		else 
		{
			$image  = $this->input->post('image');
			if ($image != '') 
			{
				$img      = substr($image, strpos($image, ",") + 1);

				$url      = FCPATH.'uploads/admin/users/retail_managers/';
				$rand     = date('Ymd').mt_rand(1001,9999);
				$userpath = $url.$rand.'.png';
				$path     = "uploads/admin/users/retail_managers/".$rand.'.png';
				file_put_contents($userpath,base64_decode($img));

			   $array = [
							'name'        => $name,
							'name_arabic' => $name_arabic,
							'phone'       => $phone,
							'email'       => $email,
							'image'       => $path,
				        ];
		    }
		    else
		    {
               $array = [
							'name'        => $name,
							'name_arabic' => $name_arabic,
							'phone'       => $phone,
							'email'       => $email,
				        ];
		    }    
			
			if ($this->Common->update('rmanager_id',$user_id,'retail_managers',$array)) 
			{
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Retail manager edited successfully..!');

				redirect('admin/users/retail_managers');
			}
			else 
			{
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to update retail manager..!');

				redirect('admin/users/edit_retailManager/'.$user_id);
			}
		}
	}
	public function checkRetailManager()
	{
		$phone  = $this->input->post('phone');
		$email  = $this->input->post('email');
		$check_phone = $this->Common->get_details('retail_managers',array('phone'=>$phone));
		$check_email  = $this->Common->get_details('retail_managers',array('email'=>$email));
		if($check_phone->num_rows()>0)
		{
			$data ='1';
		}
		elseif($check_email->num_rows()>0)
		{
			$data ='2';
		}
		else
		{
			$data ='0';
		}
		print_r($data);
	}
    
    public function disable_retailManager($id)
	{
		$array = [
			       'status' => '0'
		         ];
	
		if ($this->Common->update('rmanager_id',$id,'retail_managers',$array)) {
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'User blocked successfully..!');

			redirect('admin/users/retail_managers');
		}
		else {
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to block User..!');

			redirect('admin/users/retail_managers');
		}
	}

	public function enable_retailManager($id)
	{
		$array = [
			       'status' => '1'
		         ];
	
		if ($this->Common->update('rmanager_id',$id,'retail_managers',$array)) {
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'User activated successfully..!');

			redirect('admin/users/retail_managers');
		}
		else {
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to activate user..!');

			redirect('admin/users/retail_managers');
		}
	}

	public function changePassword_retailManager()
    {
        $user_id   = $this->security->xss_clean($this->input->post('user_id'));
        $password  = md5($this->security->xss_clean($this->input->post('password')));

        $array = [
		            'password' => $password
		        ];

        $this->Common->update('rmanager_id',$user_id,'retail_managers',$array);

        $this->session->set_flashdata('alert_type', 'success');
        $this->session->set_flashdata('alert_title', 'Success');
        $this->session->set_flashdata('alert_message', 'Password updated..!');

        redirect('admin/users/retail_managers');
    }

//end of retail managers//
}
?>
