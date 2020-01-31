<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Bill</title>
  <?php $this->load->view('admin/includes/table-css');?>
</head>
<body class="hold-transition sidebar-mini">
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
            <h1>Billing History</h1>
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
                    <th width="25%">Agency Name</th>
                    <th width="15%">Date of sale</th>
                    <th width="10%">Type of sale</th>
                    <th width="10%">Total</th>                
                    <!-- <th width="10%">Credit</th> -->
                    <th width="10%">Print</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($orders as $order) {?>
                   <tr>
                     <td><?=$order->agency_name?></td>
                     <td><?php echo date('d-M-Y',strtotime($order->sale_date))?></td>
                     <td><?php if($order->added_by=='a') { echo "Admin Bill"; }
                               else { echo "Supervisor Request"; };
                         ?>
                     </td>
                     <td><?=$order->total?></td>
                     <td><a href="<?=site_url('admin/bill/agencyInvoice/'.$order->as_id)?>"><button type="button" class="btn btn-primary" style="font-size:12px;">Print</button></a>
                     </td>
                   </tr>
                  <?php };?>
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
  <?php $this->load->view('admin/includes/footer');?>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<?php $this->load->view('admin/includes/table-script');?>
  <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').DataTable({
            "order": [[ 0, "desc" ]], //or asc 
            "columnDefs" : [{"targets":3, "type":"date-eu"}],
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
