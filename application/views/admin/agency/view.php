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
            <h1>Agencies</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-right">
             <a href="<?=site_url('admin/agency/add')?>"><button type="button" class="btn btn-primary btn-rounded waves-light waves-effect w-md">Add Agency</button></a> 
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
                  <th width="10%">Agency Image</th> 
                  <th width="25%">Agency Name</th>
                  <th width="10%">Agency Code</th>
                  <th width="10%">Phone</th>
                  <th width="15%">Vehicle Number</th>
                  <th width="20%">Staff</th> 
                  <th width="10%">Initial Cans Allotted</th>                
                  <th width="10%">Status</th>
                  <th width="10%">Edit</th>
                </tr>
                </thead>
                <tbody>
                  <?php foreach($agencies as $ag) {?>
                  <tr>
                    <td><img src="<?=base_url() . $ag->agency_image?>" height="100px"></td>
                    <td><?php echo $ag->agency_name.'<br>'.$ag->name_arabic;?></td>
                    <td><?=$ag->agency_code?></td>
                    <td><?=$ag->agency_phone?></td>
                    <td><?=$ag->vehicle_number?></td>
                    <td><?php echo $ag->agency_staff.'<br>'.$ag->staff_arabic;?></td>
                    <td>
                        <?php foreach ($ag->cans as $can) 
                           {
                             echo $can->product_name." - ".$can->initial_cans."<br>";
                           }
                        ?>     
                    </td>
                    <?php if($ag->agency_status=='1')
                    {
                      $status = 'Active';
                      $action = '<a class="btn btn-danger" style="font-size:12px;" href="' . site_url('admin/agency/disable/'.$ag->agency_id) . '" >Disable</a>';
                    }
                    else
                    {
                      $status = 'Blocked';
                      $action = '<a class="btn btn-success" style="font-size:12px;" href="' . site_url('admin/agency/enable/'.$ag->agency_id) . '" >Enable</a>';
                    } ?>
                    <td><?=$status.'<br>'.$action?></td>
                    <td><a href="<?=site_url('admin/agency/edit/'.$ag->agency_id)?>"><button type="button" class="btn btn-link" style="font-size:20px;color:blue"><i class="fa fa-pencil"></i></button></a></td>
                  <?php };?>
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

      
    } );

</script>
 
</body>
</html>
