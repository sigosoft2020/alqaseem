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
          <small class="float-right">Date: <?php echo date('d-M-Y'); ?>
            <br>
            <small>Invoice #<?php echo 'INVSREQ'.$details->rpr_id;?></small>
          </small>          
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        Billed By
        <address>
          <strong>Al Qaseem</strong><br>
          9999999
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
       Requested By
        <address>
        <?php if($request->supervisor_id=='0')
          { ?> 
             <strong><?php echo $request->agency_name;?></strong><br>
             <?php echo $details->agency_phone;?>
        <?php  }
          else
          { ?>
              <strong><?php echo $details->supervisor;?></strong><br>
              <?php echo $details->supervisor_phone;?>
        <?php }?>
        </address>
      </div>

      <div class="col-sm-4 invoice-col">
       Sold To
        <address>
             <strong><?php echo $request->agency_name;?></strong><br>
             <?php echo $details->agency_phone;?>
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
            <th>Allowed Cans</th>
          </tr>
          </thead>
          <tbody>
               <tr>   
                  <td><?=$details->product_name?></td>
                  <td><?=$details->quantity?></td>
                  <td><?=$details->new_cans_allowed?></td>
               </tr> 
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
          <table class="table">
           
          </table>
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
