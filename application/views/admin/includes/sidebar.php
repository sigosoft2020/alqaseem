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
    <a href="<?=base_url('admin/dashboard')?>" class="brand-link">
      <img src="<?=base_url()?>login_assets/images/icons/favicon.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light" style="color: white;">Al Qaseem</span>
    </a>

 <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="<?=site_url('admin/dashboard')?>" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-truck"></i>
            <p>Orders<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?=site_url('admin/orders/new_orders')?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>New Orders</p>
              </a>
            </li>  
            <li class="nav-item">
              <a href="<?=site_url('admin/orders/pending')?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Pending Vans Delivery</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=site_url('admin/orders/completed')?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Completed Orders</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=site_url('admin/orders/cancelled')?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Cancelled Orders</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=site_url('admin/orders/retail')?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Retail Sales</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=site_url('admin/orders/cofilling')?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Co filling Sales</p>
              </a>
            </li>
          </ul>    
        </li>

        <li class="nav-item">
          <a href="<?=site_url('admin/category')?>" class="nav-link">
            <i class="nav-icon fas fa-clone"></i>
            <p>Category</p>
          </a>
        </li>
        
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-shopping-bag"></i>
            <p>Product<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=site_url('admin/product/add')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Product</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=site_url('admin/product')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Product</p>
                </a>
              </li>
          </ul>    
        </li>

        <!-- <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-boxes"></i>
            <p>Accessories<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=site_url('admin/accessories/add')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Accessory</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=site_url('admin/accessories')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Accessory</p>
                </a>
              </li>
          </ul>    
        </li> -->

        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-shuttle-van"></i>
            <p>Agencies<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <!--  <li class="nav-item">
                <a href="<?=site_url('admin/agency/add')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Agency</p>
                </a>
              </li> -->
              <li class="nav-item">
                <a href="<?=site_url('admin/agency')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Agency</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=site_url('admin/agency/status')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Agency Status<i class="right fas fa-angle-left"></i></p>
                </a>
                 <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?=site_url('admin/agency/daily')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Daily Status</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?=site_url('admin/agency/weekly')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Weekly Status</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?=site_url('admin/agency/monthly')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Monthly Status</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?=site_url('admin/agency/lifetime')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Lifetime Status</p>
                      </a>
                    </li>
                </ul> 
              </li>
          </ul>   
        </li>

        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-truck-pickup"></i>
            <p>Own Vehicle<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=site_url('admin/vehicle/add')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Vehicle</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=site_url('admin/vehicle')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Vehicle</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=site_url('admin/vehicle/status')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Vehicle Status<i class="right fas fa-angle-left"></i></p>
                </a>
                 <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?=site_url('admin/vehicle/daily')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Daily Status</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?=site_url('admin/vehicle/weekly')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Weekly Status</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?=site_url('admin/vehicle/monthly')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Monthly Status</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?=site_url('admin/vehicle/lifetime')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Lifetime Status</p>
                      </a>
                    </li>
                </ul> 
              </li>
          </ul>   
        </li>
        
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-users"></i>
              <p>Users<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?=site_url('admin/users/warehouse_managers')?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Warehouse Managers</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?=site_url('admin/users/supervisors')?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Supervisors</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?=site_url('admin/users/retail_managers')?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Retail Managers</p>
                  </a>
                </li>
            </ul>    
        </li>

        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fa fa-user"></i>
            <p>Customers<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?=site_url('admin/customers/add')?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Customer</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=site_url('admin/customers')?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Manage Customer</p>
              </a>
            </li>
          </ul>    
        </li>

        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-bell"></i>
            <p>Supervisor Requests<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=site_url('admin/supervisor_requests/pending')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pending Requests</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=site_url('admin/supervisor_requests/approved')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Approved Requests</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=site_url('admin/supervisor_requests/cancelled')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Cancelled Requests</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=site_url('admin/supervisor_requests/completed')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Completed Requests</p>
                </a>
              </li>
          </ul>    
        </li>

        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>Warehouse<i class="right fas fa-angle-left"></i></p>
            </a>
          <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Approved Invoices<i class="right fas fa-angle-left"></i></p>
                </a>
                 <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?=site_url('admin/warehouse/pending')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Pending</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?=site_url('admin/warehouse/completed')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Completed</p>
                      </a>
                    </li>
                  </ul>  
              </li>
              <li class="nav-item">
                <a href="<?=site_url('admin/warehouse/stock')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Stock</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Stock<i class="right fas fa-angle-left"></i></p>
                </a>
                 <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?=site_url('admin/warehouse/add_stock')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Add Stock</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?=site_url('admin/warehouse/remove_stock')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Remove Stock</p>
                      </a>
                    </li>
                  </ul>  
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock History<i class="right fas fa-angle-left"></i></p>
                </a>
                 <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?=site_url('admin/warehouse/orderHistory')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Order History Bills</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?=site_url('admin/warehouse/addedStockHistory')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>New Stock History</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?=site_url('admin/warehouse/removedStockHistory')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Stock Removals</p>
                      </a>
                    </li>
                  </ul>  
              </li>
          </ul>    
        </li>

        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-money-bill-alt"></i>
            <p>Billing<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Agency Billing<i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?=site_url('admin/bill/agencyBill')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>New Bill</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?=site_url('admin/bill/agencyBillHistory')?>" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Billing History</p>
                      </a>
                    </li>
                </ul>    
              </li>
              <li class="nav-item">
                <a href="<?=site_url('admin/bill/co_fill')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Co Filling Requests</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=site_url('admin/bill/retailBill')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>New Retail Filling</p>
                </a> 
              </li>
              <li class="nav-item">
                <a href="<?=site_url('admin/bill/history')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>History</p>
                </a> 
              </li>
          </ul>    
        </li>

        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tag"></i>
            <p>Coupon System<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=site_url('admin/coupons')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Coupon Packages</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=site_url('admin/coupons/purchases')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Coupon Purchases</p>
                </a>
              </li>
          </ul>    
        </li>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fa fa-credit-card"></i>
            <p>Payments<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=site_url('admin/payments/pending')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Pending Payment</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=site_url('admin/payments/history')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Payment History</p>
                </a>
              </li>
          </ul>    
        </li>
        
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-calculator"></i>
            <p>Expense<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=site_url('admin/expense/category')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Expense Category</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=site_url('admin/expense')?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Agency Expense</p>
                </a>
              </li>
          </ul>    
        </li>
      <!--   <li class="nav-item">
          <a href="<?=site_url('admin/wallet')?>" class="nav-link">
            <i class="nav-icon fas fa-wallet"></i>
            <p>Customer Wallet</p>
          </a>
        </li> -->
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
</aside>
