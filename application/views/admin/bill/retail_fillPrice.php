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
  <?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Retail Fill Price</h1>
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
                  <th width="10%">Unit</th> 
                  <th width="15%">Price</th>
                  <th width="10%">Edit</th>
                </tr>
                </thead>
                <tbody>                
                   <tr>   
                    <td><?php echo $retail->unit; ?></td>
                    <td><?php echo $retail->price; ?></td>
                    <td><button class="btn btn-primary" type="button" onclick="edit('<?=$retail->price_id?>')" >Edit</button></td>
                   </tr>                
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

    <div id="edit-price" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
          <div class="modal-dialog">
              <div class="modal-content">

                  <div class="modal-body">
                      <h2 class="text-uppercase text-center m-b-30">
                          <span><h4>Edit Price</h4></span>
                      </h2>
                    <form class="form-horizontal" action="<?=site_url('admin/bill/priceUpdate/re')?>" method="post">
                       
                       <input type="hidden" class="form-control" name="price_id" id="price_id">      
                       <div class="form-group m-b-25">
                           <div class="col-12">
                               <label for="select">Price</label>
                               <input type="number" placeholder="Price" class="form-control" name="price" id="price" required>
                           </div>
                       </div>

                       <div class="form-group account-btn text-center m-t-10">
                           <div class="col-12">
                               <button type="reset" class="btn w-lg btn-rounded btn-light waves-effect m-l-5" data-dismiss="modal">Back</button>
                               <button class="btn w-lg btn-rounded btn-primary waves-effect waves-light" type="submit">Update</button>
                           </div>
                       </div>
                   </form>
                 </div>
              </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
      </div>

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
<script type="">
  function edit(id)
    {
      $('#price_id').val(id);
      $('#edit-price').modal('show');      
    }
</script>
</body>
</html>
