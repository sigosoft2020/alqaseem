<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Invoice</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 4 -->

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url()?>assets/dist/css/adminlte.min.css">

  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-12">
        <h2 class="page-header">
          <i class="fas fa-globe"></i> Al Qaseem
          <small class="float-right">Date: <?php echo date('d-M-Y',strtotime($order->date)); ?>
            <br>
            <small>Invoice #INVSR<?php echo $order->srequest_id;?></small>
          </small>          
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        From
        <address>
          <strong>Al Qaseem</strong><br>
          908999999999
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        To
        <address>
          <strong>Supervisor: <?php echo $order->supervisor_name;?></strong><br>
          Agency: <?php echo $order->agency_name;?><br>
          <!-- <?php echo $sales->address; ?> -->
        </address>
      </div>
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Broken Count</th>
            <th>Smell/Defect Count</th>
            <?php if($order->status=='c') {?>
              <th>New Cans Allowed</th>
            <?php };?>  
          </tr>
          </thead>
          <tbody>
            <?php foreach($order->products as $product)
            {?>
               <tr>   
                  <td><?=$product->name?></td>
                  <td><?=$product->quantity?></td>
                  <!-- <td><?=$product->total?></td> -->
                  <td><?=$product->broken_count?></td>
                  <td><?=$product->smell_defect_count?></td>
                  <?php if($order->status=='c') {?>
                    <td><?=$product->new_cans_allowed?></td>
                  <?php };?>  
               </tr> 
            <?php  }?>
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-6">
      </div>
      <!-- /.col -->
      <div class="col-6">
        <div class="table-responsive">
          <!-- <table class="table">
            <tr>
              <th style="width:50%">Subtotal:</th>
              <td>AED <?php echo $order->total;?></td>
            </tr>
            <tr>
              <th>Amount Recieved</th>
              <td>AED <?=$order->amount_received?></td>
            </tr>
            <?php if($order->credit_balance!='0') {?>
            <tr>
              <th>Credit Balance</th>
              <td>AED <?=$order->credit_balance?></td>
            </tr>
           <?php };?> 
          </table> -->
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->

<script type="text/javascript"> 
  window.addEventListener("load", window.print());
</script>
</body>
</html>
