<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Users</title>
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
            <h1>Retail Managers</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-right">
              <a href="<?=site_url('admin/users/add_retailManager')?>"><button type="button" class="btn btn-primary btn-rounded waves-light waves-effect w-md">Add Retail Manager</button></a>
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
              <table id="user_data" class="table table-bordered table-striped" style="width: 100%;">
                <thead>
                <tr>
                  <th width="10%">Image</th>
                  <th width="25%">Name</th>
                  <th width="10%">Phone</th>
                  <th width="10%">Email</th>           
                  <th width="10%">Status</th>
                  <th width="15%">Password</th>
                  <th width="5%">Edit</th>
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
   
    <div id="change-password" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
          <div class="modal-dialog">
              <div class="modal-content">

                  <div class="modal-body">
                      <h2 class="text-uppercase text-center m-b-30">
                        <span><h4>Change password</h4></span>
                      </h2>
                      <form class="form-horizontal" action="<?=site_url('admin/users/changePassword_retailManager')?>" method="post" onsubmit="return check()">
                          <div class="form-group m-b-25">
                              <div class="col-12">
                                  <label for="select">Password</label>
                                  <input type="password" name="password" id="pass1" class="form-control" required>
                              </div>
                          </div>
                          <input type="hidden" name="user_id" id="user_id">
                          <div class="form-group m-b-25">
                              <div class="col-12">
                                  <label for="select">Confirm password</label>
                                  <input type="password"  id="pass2" class="form-control" required>
                              </div>
                          </div>
                          <div class="" style="text-align:center;color:red;padding-bottom:10px;" id="message">
                          </div>
                          <div class="form-group account-btn text-center m-t-10">
                              <div class="col-12">
                                  <button type="reset" class="btn w-lg btn-rounded btn-light waves-effect m-l-5" data-dismiss="modal">Back</button>
                                  <button class="btn w-lg btn-rounded btn-primary waves-effect waves-light" type="submit">Change</button>
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
          url:"<?=site_url('admin/users/get_retailManager')?>",
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
    
  function change_password(key)
  {
    $('#user_id').val(key);
    $('#change-password').modal('show');
  }
  function check()
  {
    var pass1 = $('#pass1').val();

    var length = pass1.length;
    if (length < 6) {
      $('#message').text('Password should contain atleast 6 characters..!');
      return false;
    }
    else {
      var pass2 = $('#pass2').val();
      if (pass1 == pass2) {
        return true;
      }
      else {
        $('#message').text('Password mismatch');
        return false;
      }
    }
  }


  </script>
  
</body>
</html>
