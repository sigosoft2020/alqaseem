<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Biiling Details</title>
  <?php $this->load->view('retailer/includes/table-css');?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <?php $this->load->view('retailer/includes/navbar');?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php $this->load->view('retailer/includes/sidebar');?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Bill Details</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">

          <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-condensed">
                <tbody>                 
                   <tr>
                     <td>Invoice No </td>
                     <td>INVOR<?=$sales->order_id?></td>
                   </tr>            
                   <tr>
                     <td>Customer Name </td>
                     <td><?=$sales->customer_name?></td>
                   </tr>
                   <tr>
                     <td>Customer Phone </td>
                     <td><?=$sales->customer_phone?></td>
                   </tr>
                   <tr>
                     <td>Billing Date </td>
                     <td><?php echo date('d-M-Y',strtotime($sales->billing_date));?></td>
                   </tr>
                   <tr>
                     <td>Billing Time </td>
                     <td><?php echo date('H:i',strtotime($sales->billing_time));?></td>
                   </tr>
                </tbody>
              </table>
               
              <table class="table table-condensed" style="width: 100%;">
                <thead>
                <tr>
                  <th width="25%">Product Name</th> 
                  <th width="20%">Price</th>
                  <th width="15%">Quantity</th>
                  <th width="10%">Total</th>
                </tr>
                </thead>
                <tbody>                 
                  <?php foreach($sales->details as $detail)
                  {?>
                     <tr>   
                        <td><?=$detail->product_name?></td>
                        <td>AED <?=$detail->price?></td>
                        <td><?=$detail->quantity?></td>
                        <td>AED <?=$detail->total?></td>
                     </tr> 
                  <?php  }?>                 
                </tbody>
                <tfoot>
                  <tr> 
                      <td></td>
                      <td></td>
                      <td><b>Grand Total</b></td>
                      <td><b>AED <?php echo $sales->total;?></b></td>
                  </tr> 
                  <?php if($sales->credit_balance!='0') {?>
                   <tr> 
                      <td></td>
                      <td></td>
                      <td><b>Credit Amount</b></td>
                      <td><b>AED <?php echo $sales->credit_balance;?></b></td>
                   </tr> 
                  <?php };?>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php $this->load->view('retailer/includes/footer');?>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<?php $this->load->view('retailer/includes/table-script');?>
 <script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable({
        "ordering": false
    });

        //Buttons examples
        var table = $('#datatable-buttons').DataTable({
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf']
        });

        table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
    } );
</script>
</body>
</html>
