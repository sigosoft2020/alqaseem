<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupons extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('admin/M_coupons','coupons');
			$this->load->model('Common');
			if (!admin()) {
				redirect('users/admin');
			}
	}
//Coupon packages starts from here//	
	public function index()
	{   
		$products = $this->Common->get_details('products',array('product_status'=>'1'))->result();
		$data['products'] = $products;
		$this->load->view('admin/coupons/view',$data);
	}
	
	public function get()
	{
        $result = $this->coupons->make_datatables();
		$data = array();
		foreach ($result as $res) 
		{
			$sub_array = array();
			$sub_array[] = $res->pack_name.'<br>'.$res->pack_name_arabic;
			$product     = $this->Common->get_details('products',array('product_id'=>$res->product_id))->row();
			$product_eng = $product->product_name;
			$product_ar  = $product->product_name_arabic;
			$sub_array[] = $product_eng.'<br>'.$product_ar;
			$sub_array[] = $res->no_of_bottles;
// 			$sub_array[] = $res->pack_count;
			$sub_array[] = $res->pack_default_price;
			$sub_array[] = $res->pack_validity.' Days';
			if($res->status=='1')
			{
				$status = 'Active';
				$action = '<a class="btn btn-danger" style="font-size:12px;" href="' . site_url('admin/coupons/disable/'.$res->cpack_id) . '" >Disable</a>';
			}
			else
			{
				$status = 'Blocked';
				$action = '<a class="btn btn-success" style="font-size:12px;" href="' . site_url('admin/coupons/enable/'.$res->cpack_id) . '" >Enable</a>';
			}
			$sub_array[] = $status.'<br>'.$action;
			$sub_array[] = '<button type="button" class="btn btn-primary" onclick="edit('. $res->cpack_id .')" style="font-size:12px;">Edit</button>';
			$data[] = $sub_array;
		}

		$output = array(
							"draw"   => intval($_POST['draw']),
							"recordsTotal" => $this->coupons->get_all_data(),
							"recordsFiltered" => $this->coupons->get_filtered_data(),
							"data" => $data
						);
		echo json_encode($output);
	}
	
	public function addCoupon()
	{
		date_default_timezone_set('Asia/Kolkata');
        $timestamp = date('Y-m-d H:i:s');
        
        $product_id = $this->security->xss_clean($this->input->post('product_id'));
		$name       = $this->security->xss_clean($this->input->post('name'));
		$name_arabic= $this->security->xss_clean($this->input->post('name_arabic'));
		$cans       = $this->security->xss_clean($this->input->post('cans'));
        // $count      = $this->security->xss_clean($this->input->post('count'));
        $validity   = $this->security->xss_clean($this->input->post('validity'));
        $price      = $this->security->xss_clean($this->input->post('price'));
      
		$array     = [
				        'product_id'        => $product_id,
						'pack_name'         => $name,
						'pack_name_arabic'  => $name_arabic,
						'no_of_bottles'     => $cans,
				// 		'pack_count'        => $count,
						'pack_default_price'=> $price,
						'pack_validity'     => $validity,
						'used_coupons'      => '0',
						'remaining_coupons' => $cans,
						'status'            => '1',
						'timestamp'         => $timestamp
			        ];
		if ($this->Common->insert('coupon_packages',$array)) 
		{
			$this->session->set_flashdata('alert_type', 'success');
			$this->session->set_flashdata('alert_title', 'Success');
			$this->session->set_flashdata('alert_message', 'New package added..!');
			redirect('admin/coupons');
		}
		else 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Failed to add package..!');
			redirect('admin/coupons');
		}
	}

	public function editCoupon()
	{
		$pack_id    = $this->input->post('pack_id');
		$product_id = $this->security->xss_clean($this->input->post('product_id'));
		$name       = $this->security->xss_clean($this->input->post('name'));
		$name_arabic= $this->security->xss_clean($this->input->post('name_arabic'));
		$cans       = $this->security->xss_clean($this->input->post('cans'));
        // $count      = $this->security->xss_clean($this->input->post('count'));
        $validity   = $this->security->xss_clean($this->input->post('validity'));
        $price      = $this->security->xss_clean($this->input->post('price'));

		$check      = $this->Common->get_details('coupon_packages',array('pack_name' => $name ,'remaining_coupons!='=>'0','cpack_id!=' => $pack_id))->num_rows();
		if ($check > 0) 
		{
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_title', 'Failed');
			$this->session->set_flashdata('alert_message', 'Package with same name already exist..!');

			redirect('admin/coupons');
		}
		else 
		{
            $array = [  
            	        'product_id'        => $product_id,
						'pack_name'         => $name,
						'pack_name_arabic'  => $name_arabic,
						'no_of_bottles'     => $cans,
				// 		'pack_count'        => $count,
						'pack_default_price'=> $price,
						'pack_validity'     => $validity,
						'remaining_coupons' => $cans
		             ];
		 
			if ($this->Common->update('cpack_id',$pack_id,'coupon_packages',$array)) {
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Package edited successfully..!');
				redirect('admin/coupons');
			}
			else 
			{
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to update package..!');

				redirect('admin/coupons');
			}
		}
	}
	public function checkCoupon()
	{
		$name         = $this->input->post('name');
		$product      = $this->input->post('product');
		$check_coupon = $this->Common->get_details('coupon_packages',array('product_id'=>$product,'pack_name'=>$name));
		if($check_coupon->num_rows()>0)
		{
			$data ='1';
		}
		else
		{
			$data ='0';
		}
		print_r($data);
	}

    public function getCouponById()
	{
		$id   = $_POST['id'];
		$coupon          = $this->Common->get_details('coupon_packages',array('cpack_id' => $id))->row();
		$data['coupons'] = $coupon;
		$products = $this->Common->get_details('products',array('product_status'=>'1'))->result();
		$options = '<option value="">-- Choose Product ---</option>';
         foreach ($products as $product) 
         {
            if ($product->product_id == $coupon->product_id) 
            {
                $options = $options . '<option value="' . $product->product_id . '" selected>' . $product->product_name . '</option>';
            }
            else 
            {
                $options = $options . '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
            }
        }
        $data['products'] = $options;
		print_r(json_encode($data));
	}

	public function disable($id)
	{
			$array = [
				       'status' => '0'
			         ];
		
			if ($this->Common->update('cpack_id',$id,'coupon_packages',$array)) 
			{
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Package blocked successfully..!');
				redirect('admin/coupons');
			}
			else 
			{
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to block package..!');
                redirect('admin/coupons');
			}
	}

	public function enable($id)
	{
			$array = [
				       'status' => '1'
			         ];
		
			if ($this->Common->update('cpack_id',$id,'coupon_packages',$array)) 
			{
				$this->session->set_flashdata('alert_type', 'success');
				$this->session->set_flashdata('alert_title', 'Success');
				$this->session->set_flashdata('alert_message', 'Package activated successfully..!');
				redirect('admin/coupons');
			}
			else {
				$this->session->set_flashdata('alert_type', 'error');
				$this->session->set_flashdata('alert_title', 'Failed');
				$this->session->set_flashdata('alert_message', 'Failed to activate package..!');
				redirect('admin/coupons');
			}
	}
//coupon packages ends here//

//coupon purchases starts here//

    public function purchases()
	{
	   $purchases = $this->coupons->get_coupons();	
	    foreach($purchases as $purchase)
	    {
	      $purchase->used_coupons   = $this->Common->get_details('coupon_purchases',array('pack_id'=>$purchase->cpack_id))->num_rows();     
          $total_purchase           = $this->coupons->getCouponPurchases($purchase->cpack_id);
          $purchase->total_purchase = 0+$total_purchase;
	    }
	   $data['purchases'] = $purchases;
	   $this->load->view('admin/coupons/purchase',$data);
	}
	

//coupon purchases ends here//	

  public function purchasedCustomers($id)
  {
      $customers  = $this->coupons->getPurchasedCoupons($id);
      foreach($customers as $customer)
      {   
          $customer_details = $this->Common->get_details('customers',array('customer_id'=>$customer->customer_id))->row();
          $customer->name   = $customer_details->name_english;
          $pack_details     = $this->Common->get_details('coupon_packages',array('cpack_id'=>$customer->pack_id))->row();
          $customer->pack_name = $pack_details->pack_name.'<br>'.$pack_details->pack_name_arabic;
      }
      $data['customers'] = $customers;
      $this->load->view('admin/coupons/purchased_customers',$data);
  }

}
?>
