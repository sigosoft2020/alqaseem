<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Biiling History</title>
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
            <h1>Sales History</h1>
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
              <table id="datatable" class="table table-bordered table-striped" style="width: 100%;">
                <thead>
                <tr>
                  <th width="25%">Invoice No</th> 
                  <th width="15%">Customer Name</th>
                  <th width="20%">Customer Phone</th>
                  <th width="15%">Total</th>
                  <th width="15%">Billing Date</th>
                  <th width="5%">View</th>
                  <th width="5%">Print</th>
                </tr>
                </thead>
                <tbody>                 
                  <?php  foreach($history as $his)
                    {?>
                     <tr>   
                        <td>INVOR<?=$his->order_id?></td>
                        <td><?=$his->customer_name?></td>
                        <td><?=$his->customer_phone?></td>
                        <td><?=$his->total?></td>
                        <td><?php echo date('d-M-Y',strtotime($his->billing_date)).' '.$his->billing_time?></td>
                        <td><a href="<?=site_url('retailer/bill/details/'.$his->order_id)?>" class="btn btn-primary" style="font-size: 10px;" >View</a></td>
                        <td><a href="<?=site_url('retailer/bill/invoice/'.$his->order_id)?>" class="btn btn-primary" style="font-size: 10px;" >Print</a></td>
                     </tr> 
                  <?php  }?>                 
                </tbody>
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
