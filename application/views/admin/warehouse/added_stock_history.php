<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Stock</title>
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
            <h1>Added Stock History</h1>
          </div>
        </div>
        
          <div class="card-body">
           <form role="form" method="post" action="<?=site_url('admin/warehouse/addedStockHistory')?>"> 
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>From</label>
                  <input type="date" class="form-control" name="start_date"value="<?=date('Y-m-d')?>">
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label>To</label>
                  <input type="date" class="form-control" name="end_date"value="<?=date('Y-m-d')?>">
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
              <b><?=$date?></b><br><br>
              <table id="datatable" class="table table-bordered table-striped" style="width: 100%;">
                <thead>
                <tr>
                  <th width="25%">Product Name</th> 
                  <th width="15%">Date</th>
                  <th width="20">Added By</th>
                  <th width="15%">No Of Items</th>
                  <th width="20%">Notes</th>
                </tr>
                </thead>
                <tbody>                
                  <?php  foreach($stocks as $stock)
                    {?>
                   <tr>   
                    <td><?=$stock->product_name.'<br>'.$stock->product_name_arabic?></td>
                    <td><?php echo date('d-M-Y H:i',strtotime($stock->timestamp)); ?></td>
                    <td><?=$stock->updated?></td>
                    <td><?=$stock->quantity?></td>
                    <td><?=$stock->notes?></td>
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
