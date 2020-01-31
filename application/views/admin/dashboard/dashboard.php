<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Dashboard</title>
    <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="<?=base_url()?>login_assets/images/icons/favicon.png"/>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url()?>assets/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <?php $this->load->view('admin/includes/navbar');?>
  <!-- /.navbar -->
  
  <!-- Main Sidebar Container -->
  
    <!-- Sidebar -->
    <?php $this->load->view('admin/includes/sidebar');?>
    <!-- /.sidebar -->
    
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?=$new_orders?></h3>
                <p>New Orders Today</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
               <a href="<?=site_url('admin/orders/new_orders')?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> 
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?=$pending?></h3>
                <p>Pending Orders Today</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
               <a href="<?=site_url('admin/orders/pending')?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> 
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?=$completed?></h3>
                <p>Completed Orders Today</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
               <a href="<?=site_url('admin/orders/completed')?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> 
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?=$cancelled?></h3>
                <p>Cancelled Orders Today</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
               <a href="<?=site_url('admin/orders/cancelled')?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> 
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-3">
            <a href="<?=site_url('admin/supervisor_requests/pending')?>">  
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-info elevation-1"><i class="fas fa-bell"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Supervisor Requests Today</span>
                    <span class="info-box-number"><?=$new_requests?></span>
                  </div>
                </div>
            </a> 
          </div>

          <div class="col-12 col-sm-6 col-md-3">
            <a href="<?=site_url('admin/supervisor_requests/approved')?>">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-bell"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Approved Requests Today</span>
                    <span class="info-box-number"><?=$approved_requests?></span>
                  </div>
                </div>
            </a>
          </div>
          
          <div class="col-12 col-sm-6 col-md-3">
            <a href="<?=site_url('admin/supervisor_requests/completed')?>">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-success elevation-1"><i class="fas fa-bell"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Completed Requests Today</span>
                    <span class="info-box-number"><?=$completed_requests?></span>
                  </div>
                </div>
            </a>
          </div>

           <div class="col-12 col-sm-6 col-md-3">
            <a href="<?=site_url('admin/supervisor_requests/cancelled')?>">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-bell"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Cancelled Requests Today</span>
                    <span class="info-box-number"><?=$cancelled_requests?></span>
                  </div>
                </div>
            </a>
          </div>
          <!-- ./col -->
          <div class="col-12 col-sm-6 col-md-3">
            <a href="<?=site_url('admin/orders/retail')?>">
                <div class="info-box mb-3">
                  <div class="info-box-content">
                    <span class="info-box-text">Retail Sales Today</span>
                    <span class="info-box-number"><?=$retail_sales?></span>
                  </div>
                  <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>   
                </div>
            </a>
          </div>

          <div class="col-12 col-sm-6 col-md-3">
            <a href="<?=site_url('admin/customers')?>">
                <div class="info-box mb-3">
                  <div class="info-box-content">
                    <span class="info-box-text">New Customers Today</span>
                    <span class="info-box-number"><?=$new_customers?></span>
                  </div>
                  <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user"></i></span>
                </div>
            </a>
          </div>

          <div class="col-12 col-sm-6 col-md-3">
            <a href="<?=site_url('admin/agency')?>">
                <div class="info-box mb-3">
                  <div class="info-box-content">
                    <span class="info-box-text">New Agencies Today</span>
                    <span class="info-box-number"><?=$new_agencies?></span>
                  </div>
                  <span class="info-box-icon bg-success elevation-1"><i class="fas fa-truck"></i></span>
                </div>
            </a>
          </div>

          <div class="col-12 col-sm-6 col-md-3">
            <a href="<?=site_url('admin/expense')?>">
                <div class="info-box mb-3">
                   <div class="info-box-content">
                    <span class="info-box-text">Agency Expense Today</span>
                    <span class="info-box-number"><?php if($expense_today!='' || $expense_today!='0'){ echo 'AED '.$expense_today; }?></span>
                  </div>
                  <span class="info-box-icon bg-success elevation-1"><i class="fas fa-calculator"></i></span>
                </div>
             </a>     
          </div>
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <div class="col-md-8">

            <div class="row">
              <div class="col-md-6">
                <!-- USERS LIST -->
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">New Customers</h3>
                    <div class="card-tools">
                    </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body p-0">
                    <ul class="users-list clearfix">
                    <?php foreach($customers as $customer) {?>
                      <li>
                        <img src="<?=base_url().$customer->customer_image?>" alt="User Image">
                        <a class="users-list-name" href="#"><?=$customer->name_english?></a>
                        <span class="users-list-date"><?=$customer->name_arabic?></span>
                      </li>
                    <?php };?>
                    </ul>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer text-center">
                    <a href="<?=site_url('admin/customers')?>">View All Customers</a>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <!-- DIRECT CHAT -->
              <div class="card direct-chat direct-chat-warning">
                  <div class="card-header">
                    <h3 class="card-title">Latest Supervisor Requests</h3>
                    <div class="card-tools">                    
                    </div>
                  </div>
                  <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                    <?php foreach($requests as $req) {?> 
                      <li class="item">
                        <div class="product-info">
                          <a href="<?=site_url('admin/supervisor_requests/pending')?>" class="product-title"><?=$req->supervisor_name?></a>
                          <span class="product-description">
                            <?=$req->agency_name?>
                          </span>
                        </div>
                      </li>
                    <?php };?>  
                    </ul>  
                  </div>
                  <div class="card-footer text-center">
                    <a href="<?=site_url('admin/supervisor_requests/pending')?>">View All Requests</a>
                  </div>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">Latest Orders</h3>
                <div class="card-tools">
                </div>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <th>Order ID</th>
                      <th>Customer</th>
                      <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($orders as $order) {?>  
                      <tr>
                        <td><a href="pages/examples/invoice.html">OR<?=$order->order_id?></a></td>
                        <td><?=$order->customer_name?></td>
                        <td>
                          <?php if($order->status=='pending') {?>
                           <span class="badge badge-info">Pending</span>
                          <?php } elseif($order->status=='processing') {?> 
                            <span class="badge badge-info">Pending</span>
                          <?php } elseif($order->status=='completed') {?> 
                            <span class="badge badge-success">Completed</span>
                          <?php } else {?> 
                            <span class="badge badge-danger">Cancelled</span>
                          <?php };?>  
                        </td>
                      </tr>
                    <?php };?>
                    </tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>
              <!-- /.card-body -->
               <div class="card-footer clearfix">
                <a href="<?=site_url('admin/orders/pending')?>" class="btn btn-sm btn-secondary float-right">View All Orders</a>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.row -->
          </div>  

          <div class="col-md-4">      
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">New Retail Sales</h3>
                <div class="card-tools">
                </div>
              </div>
              
              <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                <?php foreach($retails as $ret) {?>
                  <li class="item">    
                    <div class="product-img">
                      <img src="<?=base_url().$ret->customer_image?>" alt="Product Image" class="img-size-50">
                    </div>         
                    <div class="product-info">
                      <a href="<?=site_url('admin/orders/retail')?>" class="product-title"><?=$ret->customer_name?>
                        <span class="badge badge-warning float-right">AED <?=$ret->total?></span></a>
                      <span class="product-description">
                        <?php echo date('d-M-Y h:i A',strtotime($ret->timestamp));?>
                      </span>
                    </div>
                  </li>
                <?php };?>
                </ul>  
              </div>
              <div class="card-footer text-center">
                <a href="<?=site_url('admin/orders/retail')?>">View All Sales</a>
              </div>
              <!-- /.card-footer -->
            </div>

            <!-- PRODUCT LIST -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Recently Added Stocks</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                  <li class="item">
                    <div class="product-img">
                      <img src="<?=base_url()?>assets/dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">Samsung TV
                        <span class="badge badge-warning float-right">$1800</span></a>
                      <span class="product-description">
                        Samsung 32" 1080p 60Hz LED Smart HDTV.
                      </span>
                    </div>
                  </li>
                  <!-- /.item -->
                  <li class="item">
                    <div class="product-img">
                      <img src="<?=base_url()?>assets/dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">Bicycle
                        <span class="badge badge-info float-right">$700</span></a>
                      <span class="product-description">
                        26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                      </span>
                    </div>
                  </li>
                  <!-- /.item -->
                  <li class="item">
                    <div class="product-img">
                      <img src="<?=base_url()?>assets/dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">
                        Xbox One <span class="badge badge-danger float-right">
                        $350
                      </span>
                      </a>
                      <span class="product-description">
                        Xbox One Console Bundle with Halo Master Chief Collection.
                      </span>
                    </div>
                  </li>
                  <!-- /.item -->
                  <li class="item">
                    <div class="product-img">
                      <img src="<?=base_url()?>assets/dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">PlayStation 4
                        <span class="badge badge-success float-right">$399</span></a>
                      <span class="product-description">
                        PlayStation 4 500GB Console (PS4)
                      </span>
                    </div>
                  </li>
                  <!-- /.item -->
                </ul>
              </div>
              <!-- /.card-body -->
              <div class="card-footer text-center">
                <a href="javascript:void(0)" class="uppercase">View All Products</a>
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php $this->load->view('admin/includes/footer');?>
  
</div>
<!-- ./wrapper -->

<!-- jQuery -->
   <script src="<?=base_url()?>plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?=base_url()?>plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?=base_url()?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="<?=base_url()?>plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?=base_url()?>plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?=base_url()?>plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?=base_url()?>plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?=base_url()?>plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?=base_url()?>plugins/moment/moment.min.js"></script>
<script src="<?=base_url()?>plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?=base_url()?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?=base_url()?>plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?=base_url()?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url()?>assets/dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?=base_url()?>assets/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?=base_url()?>assets/dist/js/demo.js"></script>
</body>
</html>
