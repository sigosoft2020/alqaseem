<?php
$menu1=$this->uri->segment(1);
$menu2=$this->uri->segment(2);
?>

<style>
aside {
  background: linear-gradient(-135deg, #339785, #0190d6);
}
aside p{
  color: white;
}
aside i{
  color: white;
}
aside nav a:hover{
  background: #0190d6;
}
aside nav nav-link {
  color: #fff;
}

</style>

<aside class="main-sidebar elevation-4">   
     <!-- Brand Logo -->
    <a href="<?=base_url('retailer/dashboard')?>" class="brand-link">
      <img src="<?=base_url()?>login_assets/images/icons/favicon.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light" style="color: white;">Al Qaseem</span>
    </a>
    
     <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?=site_url('retailer/dashboard')?>" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                <i class="nav-icon fas fa-money-bill-alt"></i>
                <p>Billing<i class="right fas fa-angle-left"></i></p>
              </a>
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?=site_url('retailer/bill/new')?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>New Bill</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?=site_url('retailer/bill/requests')?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Supervisor Requests</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?=site_url('retailer/bill/history')?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Billing History</p>
                    </a>
                  </li>
              </ul>    
            </li>
            <li class="nav-item has-treeview">
                <a href="<?=site_url('retailer/customers')?>" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                <p>Manage Customer</p>
              </a>
              <!-- <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?=site_url('retailer/customers')?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>New Customer</p>
                    </a>
                  </li>
              </ul>   -->  
            </li>
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                <i class="nav-icon fa fa-credit-card"></i>
                <p>Pending Payment<i class="right fas fa-angle-left"></i></p>
              </a>
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?=site_url('retailer/payments/pending')?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Manage Pending Payment</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?=site_url('retailer/payments/history')?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Payment History</p>
                    </a>
                  </li>
              </ul>    
            </li>
            <!-- <li class="nav-item">-->
            <!--  <a href="<?=site_url('retailer/wallet')?>" class="nav-link">-->
            <!--    <i class="nav-icon fas fa-wallet"></i>-->
            <!--    <p>Customer Wallet</p>-->
            <!--  </a>-->
            <!--</li>-->
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
</aside>    