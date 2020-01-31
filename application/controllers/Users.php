<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
    public function __construct()
    {
            parent::__construct();
            $this->load->model('Common');
    }

//admin login starts here//
    public function admin()
    {
        if(isset($_COOKIE['admin_id'])){
            $session = [
                'admin_id' => $_COOKIE['admin_id'],
                'name' => $_COOKIE['admin_name']
            ];
            $this->session->set_userdata('admin',$session);
            redirect('admin/dashboard');
        }
        $this->load->view('login/admin/login');
    }
    
    public function adminLogin()
    {
        $username = $this->security->xss_clean($this->input->post('username'));
        $pass     = $this->security->xss_clean($this->input->post('password'));
        $password = md5($pass);

        $details = [
                        'username' => $username,
                        'password' => $password
                    ];

        $check = $this->Common->get_details('admin',$details);
        if ( $check->num_rows() > 0 ) 
        {
            $user = $check->row();
            $session = [
                            'admin_id' => $user->admin_id,
                            'name'     => $user->username
                        ];
            $this->session->set_userdata('admin',$session);

            $hour = time() + 3600 * 24 * 30;
            setcookie('admin_id', $user->admin_id, $hour);
            setcookie('admin_name', $user->username, $hour);
            redirect('admin/dashboard');
        }
        else 
        {
            $this->session->set_flashdata('alert_type', 'error');
            $this->session->set_flashdata('alert_title', 'Failed');
            $this->session->set_flashdata('alert_message', 'Invalid username or password!');
            redirect('users/admin');
        }

    }
    public function adminLogout()
    {
        setcookie('admin_id');
        setcookie('admin_name');

        $this->session->unset_userdata('admin');
        
        $this->session->set_flashdata('alert_type', 'success');
        $this->session->set_flashdata('alert_title', 'Success');
        $this->session->set_flashdata('alert_message', 'Logged out successfully..!');
        redirect('users/admin');
    }
// admin login ends here//

//retailer login starts here//
    public function retailer()
    {
        if(isset($_COOKIE['retailer_id'])){
            $session = [
                'retailer_id' => $_COOKIE['retailer_id'],
                'name'        => $_COOKIE['retailer_name']
            ];
            $this->session->set_userdata('retailer',$session);
            redirect('retailer/dashboard');
        }
        $this->load->view('login/retailer/login');
    }
    
    public function retailLogin()
    {
        $phone    = $this->security->xss_clean($this->input->post('phone'));
        $pass     = $this->security->xss_clean($this->input->post('password'));
        $password = md5($pass);

        $details = [
                        'phone'    => $phone,
                        'password' => $password,
                        'status'   => '1'
                    ];

        $check = $this->Common->get_details('retail_managers',$details);
        if ( $check->num_rows() > 0 ) 
        {
            $user = $check->row();
            $session = [
                            'retailer_id' => $user->rmanager_id,
                            'name'        => $user->name
                        ];
            $this->session->set_userdata('retailer',$session);

            $hour = time() + 3600 * 24 * 30;
            setcookie('retailer_id', $user->rmanager_id, $hour);
            setcookie('retailer_name', $user->name, $hour);
            redirect('retailer/dashboard');
        }
        else 
        {
            $this->session->set_flashdata('alert_type', 'error');
            $this->session->set_flashdata('alert_title', 'Failed');
            $this->session->set_flashdata('alert_message', 'Invalid mobile number or password!');
            redirect('users/retailer');
        }

    }
    public function retailerLogout()
    {
        setcookie('retailer_id');
        setcookie('retailer_name');

        $this->session->unset_userdata('retailer');
        
        $this->session->set_flashdata('alert_type', 'success');
        $this->session->set_flashdata('alert_title', 'Success');
        $this->session->set_flashdata('alert_message', 'Logged out successfully..!');
        redirect('users/retailer');
    }
//retailer login ends here//    

//warehouse login starts here//
    public function warehouse()
    {
        if(isset($_COOKIE['warehouse_id'])){
            $session = [
                'warehouse_id' => $_COOKIE['warehouse_id'],
                'name'        => $_COOKIE['warehouse_name']
            ];
            $this->session->set_userdata('warehouse',$session);
            redirect('warehouse/dashboard');
        }
        $this->load->view('login/warehouse/login');
    }
    
    public function warehouseLogin()
    {
        $phone    = $this->security->xss_clean($this->input->post('phone'));
        $pass     = $this->security->xss_clean($this->input->post('password'));
        $password = md5($pass);

        $details = [
                        'phone'    => $phone,
                        'password' => $password,
                        'status'   => '1'
                    ];

        $check = $this->Common->get_details('warehouse_managers',$details);
        if ( $check->num_rows() > 0 ) 
        {
            $user = $check->row();
            $session = [
                            'warehouse_id' => $user->wmanager_id,
                            'name'        => $user->name
                        ];
            $this->session->set_userdata('warehouse',$session);

            $hour = time() + 3600 * 24 * 30;
            setcookie('warehouse_id', $user->wmanager_id, $hour);
            setcookie('warehouse_name', $user->name, $hour);
            redirect('warehouse/dashboard');
        }
        else 
        {
            $this->session->set_flashdata('alert_type', 'error');
            $this->session->set_flashdata('alert_title', 'Failed');
            $this->session->set_flashdata('alert_message', 'Invalid mobile number or password!');
            redirect('users/warehouse');
        }

    }
    public function warehouseLogout()
    {
        setcookie('warehouse_id');
        setcookie('warehouse_name');

        $this->session->unset_userdata('warehouse');
        
        $this->session->set_flashdata('alert_type', 'success');
        $this->session->set_flashdata('alert_title', 'Success');
        $this->session->set_flashdata('alert_message', 'Logged out successfully..!');
        redirect('users/warehouse');
    }
//warehouse login ends here//    

}
