<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Expense Category</title>
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
            <h1>Expense Category</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-right">
             <button type="button" class="btn btn-primary btn-rounded waves-light waves-effect w-md" data-toggle="modal" data-target="#add-category">Add Category</button>
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
                  <th width="60%">Category Name</th>
                  <th width="20%">Status</th>
                  <th width="10%">Edit</th>
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
  
  <div id="add-category" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-uppercase text-center m-b-30">
                    <span><h4>Add Category</h4></span>
                </h2>
                <form class="form-horizontal" id="Myform" action="<?=site_url('admin/expense/addCategory')?>" method="post">

                    <div class="form-group m-b-25">
                        <div class="col-12">
                            <label for="select">Category Name English<span style="color: red;">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Category Name English" required>
                        </div>
                    </div>
                    
                     <div class="form-group m-b-25">
                        <div class="col-12">
                            <label for="select">Category Name Arabic</label>
                            <input type="text" name="name_arabic"  class="form-control" placeholder="Category Name Arabic">
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
<!-- add package ends here -->

<!-- edit package starts here  -->

 <div id="edit-category" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-uppercase text-center m-b-30">
                    <span><h4>Edit Category</h4></span>
                </h2>
                <form class="form-horizontal" action="<?=site_url('admin/expense/editCategory')?>" method="post">
                   
                    <div class="form-group m-b-25">
                        <div class="col-12">
                            <label for="select">Category Name English<span style="color: red;">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Category Name English" required>
                            <input type="hidden" name="category_id" id="category">
                        </div>
                    </div>
                    
                     <div class="form-group m-b-25">
                        <div class="col-12">
                            <label for="select">Category Name Arabic</label>
                            <input type="text" name="name_arabic" id="name_arabic" class="form-control" placeholder="Category Name Arabic">
                        </div>
                     </div>
                  
                    <div class="form-group account-btn text-center m-t-10">
                        <div class="col-12">
                            <button type="reset" class="btn btn-default btn-rounded waves-light waves-effect w-md " data-dismiss="modal">Back</button>
                            <button class="btn btn-primary btn-rounded waves-light waves-effect w-md " type="submit">Update</button>
                        </div>
                    </div>
                </form>
           </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- edit package ends here -->

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
          url:"<?=site_url('admin/expense/getCategory')?>",
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
    $('#Myform').on('submit', function(e){
       e.preventDefault();
      // CODE HERE
      name   = $('#name').val();
      // alert(mobile);
       $.ajax({
        method: "POST",
        url: "<?php echo site_url('admin/expense/checkCategory');?>",
        dataType : "json",
        data : { name      : name
               },
        success : function( data )
        {
           // alert(data);
          if (data == 0) 
          {
           
            document.getElementById("Myform").submit();
            return true;
          }
          else if(data== 1)
          {
            toastr.error("Category with same name already added");     
            return false;
          }           
        }             
      });            
    });

    function edit(id)
    {
      $('#category').val(id);
      // alert(id);
      $.ajax({
          method: "POST",
          url: "<?php echo site_url('admin/expense/getCategoryById');?>",
          dataType : "json",
          data : { id : id },
          success : function( data ){
            $('#name').val(data.category_name);
            $('#name_arabic').val(data.category_arabic);
            $('#edit-category').modal('show');
            // alert(data);
          }
        });
    }
  </script>
</body>
</html>
