<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Stock History</title>
  <?php $this->load->view('admin/includes/table-css');?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <?php $this->load->view('admin/includes/navbar');?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
  <?php $this->load->view('admin/includes/sidebar');?>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Stock History</h1>
          </div>
        </div>
        
          <div class="card-body">
           <form role="form" method="post" action="<?=site_url('admin/warehouse/orderHistory')?>"> 
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Agency</label>
                  <select class="form-control" name="agency">
                    <option value="">---Select Agency---</option>
                    <option value="all">All</option>
                    <?php foreach($agencies as $agency) {?>
                      <option value="<?=$agency->agency_id?>"><?=$agency->agency_name?></option>
                    <?php };?>
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label>Supervisor</label>
                  <select class="form-control" name="supervisor">
                    <option value="">---Select Supervisor---</option>
                    <option value="all">All</option>
                    <?php foreach($supervisors as $supervisor) {?>
                      <option value="<?=$supervisor->supervisor_id?>"><?=$supervisor->name?></option>
                    <?php };?>
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label>Date</label>
                  <input type="date" class="form-control" name="date">
                </div>
              </div>
              
              <div class="col-md-1">
                 <div class="form-group">
                    <label style="color: white;">.</label>
                    <input type="submit" name="submit" class="form-control btn btn-primary" value="Submit">
                 </div>
              </div>

             </div>
            </form> 
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
                  <th width="10%">Request No</th> 
                  <th width="15%">Order Date</th>
                  <th width="15">Product Name</th>
                  <th width="5">Quantity</th>
                  <th width="10">Allowed Cans</th>
                  <th width="5">Billed By</th>
                  <th width="15%">Requested By</th>
                  <th width="15%">Sold to</th>
                  <th width="5%">Print</th>
                </tr>
                </thead>
                <tbody>                
                  <?php  foreach($stocks as $stock)
                    {?>
                     <tr>   
                      <td><?php echo 'SREQ'.$stock->srequest_id; ?></td>
                      <td><?php echo date('d-M-Y',strtotime($stock->requested_date)); ?></td>
                      <td><?php echo $stock->product_name; ?></td>
                      <td><?php echo $stock->quantity; ?></td>
                      <td><?php echo $stock->new_cans_allowed; ?></td>
                      <td><?php echo 'Admin';?></td>
                      <td><?=$stock->supervisor_name?></td>
                      <td><?=$stock->agency_name?></td>
                      <td><a href="<?=site_url('admin/warehouse/stockHistoryInvoice/'.$stock->rpr_id)?>" class="btn btn-primary" style="font-size: 10px;" >Print</a></td>
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
