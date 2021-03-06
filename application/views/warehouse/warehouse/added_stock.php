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
            <h1>Added Stock History</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-right">
             <button type="button" class="btn btn-primary btn-rounded waves-light waves-effect w-md" data-toggle="modal" data-target="#add-stock">Add Stock</button>
            </ol>
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
                    <td><?=$stock->product_name?></td>
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


<!-- add stock starts here -->
<div id="add-stock" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-uppercase text-center m-b-30">
                    <span><h4>Add Stock</h4></span>
                </h2>
                <form class="form-horizontal" id="Myform" action="<?=site_url('admin/warehouse/addStock')?>" method="post">
                    <div class="form-group m-b-25">
                        <div class="col-12">
                            <label for="select">Product Name<span style="color: red;">*</span></label>
                            <select class="form-control select2" name="product_id" required>
                              <option value="">---Select Product---</option>
                              <?php foreach($products as $product) {?>
                                <option value="<?=$product->product_id?>"><?=$product->product_name?></option>
                              <?php };?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group m-b-25">
                      <div class="col-12">
                          <label for="select">No Of Items<span style="color: red;">*</span></label>
                          <input type="text" name="no_of_items" id="no_of_items" class="form-control" placeholder="No Of Items" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="4"required>
                      </div>
                    </div>

                    <div class="form-group m-b-25">
                      <div class="col-12">
                          <label for="select">Notes</label>
                          <textarea type="text" name="notes" id="notes" class="form-control" placeholder="Notes"></textarea> 
                      </div>
                    </div>

                    <div class="form-group account-btn text-center m-t-10">
                        <div class="col-12">
                            <button type="reset" class="btn btn-default btn-rounded waves-light waves-effect w-md " data-dismiss="modal">Back</button>
                            <button class="btn btn-primary btn-rounded waves-light waves-effect w-md " type="submit">Add</button>
                        </div>
                    </div>
                </form>
           </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- add stock ends here -->

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

    } );
</script>
</body>
</html>
