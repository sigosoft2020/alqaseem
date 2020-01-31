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
            <h1>Agency Status</h1>
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
            <form method="POST" action="<?=site_url('admin/agency/status')?>">
             <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Search By</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="form-control select2bs4" style="width: 100%;">
                    <option value="daily" selected="selected">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="lifetime">Lifetime</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <button class="btn w-lg btn-rounded btn-primary waves-effect waves-light pull-right" type="submit">Submit</button>
                </div>
              </div>
             </div>
            </form>
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
                  <th width="25%">Total Sale</th>
                  <th width="10%">Cans in hand</th>
                  <th width="10%">Broken canss</th>
                  <th width="15%">Defect cans</th>
                  <th width="20%">New cans recieved</th> 
                  <th width="10%">Cash</th>                
                  <th width="10%">Credit</th>
                  <th width="10%">Expense</th>
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
          url:"<?=site_url('admin/agency/get')?>",
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

    function edit(id)
    {
      $('#agency_id').val(id);
      // alert(id);
      $.ajax({
          method: "POST",
          url: "<?php echo site_url('admin/agency/getAgencyById');?>",
          dataType : "json",
          data : { id : id },
          success : function( data ){
            $('#agency').val(data.agency_name);
            $('#code').val(data.agency_code);
            $('#phone').val(data.agency_phone);
            $('#v_number').val(data.vehicle_number);
            $('#staff').val(data.agency_staff);
            $('#cans').val(data.initial_cans_allotted);
            $('#edit-agency').modal('show');
            // alert(data);
          }
        });
    }
  </script>
  <script type="">
    $('#Myform').on('submit', function(e){
       e.preventDefault();
      // CODE HERE
      phone   = $('#agency_phone').val();
      code    = $('#agency_code').val();
      // alert(mobile);
       $.ajax({
        method: "POST",
        url: "<?php echo site_url('admin/agency/checkAgency');?>",
        dataType : "json",
        data : { phone      : phone,
                 code       : code
               },
        success : function( data )
        {
           // alert(data);
          if (data == 0) 
          {
            $('#error-messagee').text('');
            document.getElementById("Myform").submit();
            return true;
          }
          else if(data== 1)
          {
            $('#error-messagee').text('Mobile already exist');
            $('#error-messagee').fadeIn().delay(1500).fadeOut(1800);
            return false;            
          }   
          else if(data== 2)
          {
            $('#error-messagee').text('Agency Code already exist');
            $('#error-messagee').fadeIn().delay(1500).fadeOut(1800);
            return false;            
          }           
        }             
      });            
    });
  </script>
</body>
</html>
