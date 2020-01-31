<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Customers</title>
  <?php $this->load->view('retailer/includes/table-css');?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <?php $this->load->view('retailer/includes/navbar');?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
 <?php $this->load->view('retailer/includes/sidebar');?>
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Customers</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-right">
             <button type="button" class="btn btn-primary btn-rounded waves-light waves-effect w-md" data-toggle="modal" data-target="#add-customer">Add Customer</button>
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
                  <!--<th width="15%">Address</th>-->
                  <th width="10%">Status</th>
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
  <?php $this->load->view('retailer/includes/footer');?>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
  
  <div id="add-customer" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-uppercase text-center m-b-30">
                    <span><h4>Add Customer</h4></span>
                </h2>
                <form class="form-horizontal" id="Myform" action="<?=site_url('retailer/customers/addCustomer')?>" method="post" enctype="multipart/form-data">
                    <div class="form-group m-b-25">
                        <div class="col-12">
                            <label for="select">Customer Name English<span style="color: red;">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Customer Name Engish" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' required>
                        </div>
                    </div>

                    <div class="form-group m-b-25">
                        <div class="col-12">
                            <label for="select">Customer Name Arabic</label>
                            <input type="text" name="name_arabic" id="name_arabic" class="form-control" placeholder="Customer Name Arabic" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))'>
                        </div>
                    </div>

                    <div class="form-group m-b-25">
                        <div class="col-12">
                            <label for="select">Customer Phone<span style="color: red;">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Customer Phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="9" minlength="9" required>
                        </div>
                    </div>

                    <div class="form-group m-b-25">
                        <div class="col-12">
                            <label for="select">Customer Email<span style="color: red;">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Customer Email"  required>
                        </div>
                    </div>

                    <div class="form-group m-b-25">
                        <div class="col-12">
                            <label for="select">Customer Address<span style="color: red;">*</span></label>
                            <textarea type="text" name="address" id="address" class="form-control" placeholder="Customer Address" required></textarea>
                        </div>    
                    </div>
                    
                    <div class="form-group m-b-25">
                         <div class="col-12">
                             <label for="select">Upload image</label>
                             <input type="file" name="image" id="image" class="form-control" onchange="preview_image(this,'0')">
                         </div>
                     </div>
                    
                    <div class="form-group m-b-25">
                         <div class="col-12">
                           <div id="bank-image"><img class="img-fluid" id="output_image" width="100%" style="padding-top:5px;"/></div>
                           <p>(Note - dimensions 250*250)</p>
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
<?php $this->load->view('retailer/includes/table-script');?>
<script type="text/javascript">
    $(document).ready(function(){
      var dataTable = $('#user_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
          url:"<?=site_url('retailer/customers/get')?>",
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
      phone   = $('#phone').val();
      // alert(mobile);
       $.ajax({
        method: "POST",
        url: "<?php echo site_url('retailer/customers/checkCustomer');?>",
        dataType : "json",
        data : { phone      : phone
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
            toastr.error("Mobile number already registered");       
            return false;            
          }           
        }             
      });            
    });
  </script>
  <script>
      function preview_image(id,check)
      {
        var id = id.id;
        var x = document.getElementById(id);
        var size = x.files[0].size;
        if (size > 5000000) {
        //   alert('Please select an image with size less than 5 mb.');
          toastr.error("Please select an image with size less than 5 mb."); 
          document.getElementById(id).value = "";
        }
        else {
          var val = x.files[0].type;
          var type = val.substr(val.indexOf("/") + 1);
          s_type = ['jpeg','jpg','png'];
          var flag = 0;
          for (var i = 0; i < s_type.length; i++) {
            if (s_type[i] == type) {
              flag = flag + 1;
            }
          }
          if (flag == 0) {
            // alert('This file format is not supported.');
            toastr.error("This file format is not supported."); 
            document.getElementById(id).value = "";
          }
          else {
            if (type != 'pdf') {
              $('#bank-image').css('display','block');
              var reader = new FileReader();
              reader.onload = function()
              {
                if (check == '0') {
                    var output = document.getElementById('output_image');
                }
                else {
                  var output = document.getElementById('output_image_edit');
                }
               output.src = reader.result;
              }
              reader.readAsDataURL(x.files[0]);
            }
            else {
              $('#bank-image').css('display','none');
            }
          }

        }
      }
  </script>
</body>
</html>
