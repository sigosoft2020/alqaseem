<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Project Detail</title>
  <?php $this->load->view('admin/includes/header');?>
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <?php $this->load->view('admin/includes/navbar');?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
 <?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Order Details</h1>
          </div>
          
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          <div class="row">
               <table class="table">
                <tr>
                  <th >Invoice No</th>
                  <td>INVOR<?php echo $order->order_id;?></td>
                </tr>
                <tr>
                  <th >Customer Name</th>
                  <td><?php echo $order->customer_name;?></td>
                </tr>
                <tr>
                  <th >Customer Phone</th>
                  <td><?php echo $order->customer_phone;?></td>
                </tr>
                <tr>
                  <th >Order Date</th>
                  <td><?php echo date('d-M-Y',strtotime($order->billing_date))?> <?php echo date('h:i A',strtotime($order->billing_time))?></td>
                </tr>
              </table>

              <table class="table table-striped">
                 <thead>
                  <tr>
                    <th>Product</th>
                    <th>Coupon Used</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                  </tr>
                 </thead>
                 <tbody>
                    <?php foreach($order->products as $product)
                    {?>
                       <tr>   
                          <td><?=$product->product_name?></td>
                          <td><?php if($product->coupon_applied=='0'){ echo "No"; } else { echo "Yes"; }?></td>
                          <td>AED <?=$product->price?></td>
                          <td><?=$product->quantity?></td>
                          <td>AED <?=$product->total?></td>
                       </tr> 
                    <?php  }?>
                  </tbody>
             </table>
             <div class="table-responsive">
              <table class="table">
                <tr>
                  <th style="width:84%">Subtotal</th>
                  <td><b>AED <?php echo $order->total;?></b></td>
                </tr>
                <?php if($order->credit_balance!='0') {?>
                <tr>
                  <th style="width:78%">Credit Amount</th>
                  <td><b>AED <?php echo $order->credit_balance;?></b></td>
                </tr>
                <?php };?>
              </table>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php $this->load->view('admin/includes/footer');?>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<?php $this->load->view('admin/includes/scripts');?>
</body>
</html>
