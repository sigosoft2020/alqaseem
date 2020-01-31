<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Agencies</title>
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
            <h1>Agency Daily Status</h1>
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
                    <th width="20%">Agency Name </th>  
                    <th width="15%">Today's Sale</th>
                    <th width="15%">Cans in hand</th>
                    <th width="10%">Broken cans</th>
                    <th width="15%">Defect cans</th>
                    <th width="15%">New cans recieved</th> 
                    <th width="10%">Amount Received</th>                
                    <th width="10%">Credit</th>
                    <th width="10%">Expense</th>
                  </tr>
                </thead>
                <tbody>
                    <?php foreach($agencies as $agency) {?>
                     <tr>
                         <td><?=$agency->agency_name?></td>
                         <td><?php if($agency->total_sale=='0' || $agency->total_sale=='') { echo ""; } else { echo "AED ".$agency->total_sale; } ?></td>
                         <td></td>
                         <td></td>
                         <td></td>
                         <td></td>
                         <td><?php if($agency->payment_received=='0' || $agency->payment_received=='') { echo ""; } else { echo "AED ".$agency->payment_received; } ?></td>
                         <td><?php if($agency->total_credit=='0' || $agency->total_credit=='') { echo ""; } else { echo "AED ".$agency->total_credit; } ?></td>
                         <td><?php if($agency->total_expense=='0' || $agency->total_expense=='') { echo ""; } else { echo "AED ".$agency->total_expense; } ?></td>
                     </tr>
                    <?php };?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
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
