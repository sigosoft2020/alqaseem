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
    <a href="<?=base_url('warehouse/dashboard')?>" class="brand-link">
      <img src="<?=base_url()?>login_assets/images/icons/favicon.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light" style="color: white;">Al Qaseem</span>
    </a>

 <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="<?=site_url('warehouse/dashboard')?>" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-item has">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-bell"></i>
            <p>Approved Invoices<i class="right fas fa-angle-left"></i></p>
          </a>
           <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=site_url('warehouse/requests/pending')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pending</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=site_url('warehouse/requests/completed')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Completed</p>
                </a>
              </li>
            </ul>  
        </li>

        <li class="nav-item">
          <a href="<?=site_url('warehouse/stock')?>" class="nav-link">
            <i class="nav-icon fas fa-box"></i>
            <p>View Stock</p>
          </a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>Manage Stock<i class="right fas fa-angle-left"></i></p>
            </a>
             <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?=site_url('warehouse/stock/add_stock')?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Add Stock</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?=site_url('warehouse/stock/remove_stock')?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Remove Stock</p>
                  </a>
                </li>
              </ul>  
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-file-invoice"></i>
              <p>Stock History<i class="right fas fa-angle-left"></i></p>
            </a>
             <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?=site_url('warehouse/history/orderHistory')?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Order History Bills</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?=site_url('warehouse/history/addedStockHistory')?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>New Stock History</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?=site_url('warehouse/history/removedStockHistory')?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Stock Removals</p>
                  </a>
                </li>
              </ul>  
          </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
</aside>