<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Warehouse</title>
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
            <h1>Pending Requests</h1>
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
              <table id="user_data" class="table table-bordered table-striped" style="width: 100%;">
                <thead>
                <tr>
                  <th width="5%">Request No</th> 
                  <th width="20%">Supervisor Name</th>
                  <th width="20%">Agency Name</th>
                  <th width="20%">Product Name</th>
                  <th width="15%">Date and Time</th>
                  <th width="5%">Broken Count</th>
                  <th width="5%">Smell/Defect Count</th>
                  <th width="5%">Status</th>   
                  <th width="5%">Change Status</th>           
                </tr>
                </thead>
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

    <div id="edit-status" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
          <div class="modal-dialog">
              <div class="modal-content">

                  <div class="modal-body">
                      <h2 class="text-uppercase text-center m-b-30">
                          <span><h4>Allowed Cans</h4></span>
                      </h2>
                      <form class="form-horizontal" action="<?=site_url('admin/warehouse/updateStatus')?>" method="post">
                           <input type="hidden" name="rproduct_id" id="rproduct_id" class="form-control">
                           <input type="hidden" name="request_id" id="request_id" class="form-control">
                          <div class="form-group m-b-25">
                              <div class="col-12">
                                  <label for="select">No of cans allowed</label>
                                  <input type="text" name="cans_allowed" id="cans_allowed" class="form-control"  onkeypress="return event.charCode >= 48 && event.charCode <= 57"  placeholder="No of cans allowed" required>
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

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<?php $this->load->view('admin/includes/table-script');?>
<script type="text/javascript">
    $(document).ready(function(){
      var dataTable = $('#user_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
          url:"<?=site_url('admin/warehouse/get_pending')?>",
          type:"POST"
        },
        "columnDefs":[
          {
            "target":[0,3,4],
            "orderable":true
          }
        ]
      });
    });

  </script>
  <script type="">
    function complete(id,request_id)
    {
      $('#rproduct_id').val(id);
      $('#request_id').val(request_id);
      // alert(id);
      $('#edit-status').modal('show');         
    }
  </script>
 
</body>
</html>
