<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Agency</title>
  <?php $this->load->view('admin/includes/header');?>
  <link rel="stylesheet" href="<?=base_url()?>plugins/image-crop/croppie.css">
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
            <h1>Agency</h1>
          </div>
          <div class="col-sm-6">
            
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Agency</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="<?=site_url('admin/agency/editData')?>" method="POST">
                <div class="card-body">
                  <div class="row">
                  <!-- <div class="col-md-6">  -->
                  <div class="col-12 col-sm-6">    
                    <div class="form-group">
                      <label for="select">Name English<span style="color: red;">*</span></label>
                        <input type="text" name="agency_name" id="agency" class="form-control" placeholder="Agency Name English" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' value="<?=$agency->agency_name?>" required>
                        <input type="hidden" name="agency_id" id="agency_id" value="<?=$agency->agency_id?>">
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">    
                    <div class="form-group">
                      <label for="select">Name Arabic</label>
                        <input type="text" name="agency_arabic" id="agency_arabic" class="form-control" placeholder="Agency Name Arabic" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' value="<?=$agency->name_arabic?>">
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">     
                    <div class="form-group">
                       <label for="select">Code<span style="color: red;">*</span></label>
                       <input type="text" name="agency_code" id="code" class="form-control" placeholder="Agency Code" value="<?=$agency->agency_code?>" required>
                    </div>
                  </div> 
                  <div class="col-12 col-sm-6">   
                    <div class="form-group">
                      <label for="select">Phone<span style="color: red;">*</span></label>
                      <input type="text" name="agency_phone" id="phone" value="<?=$agency->agency_phone?>" class="form-control" placeholder="Agency Phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="11" required>
                    </div>
                  </div>  
                  <div class="col-12 col-sm-6">  
                    <div class="form-group">
                      <label for="select">Vehicle Number<span style="color: red;">*</span></label>
                      <input type="text" name="vehicle_number" value="<?=$agency->vehicle_number?>" id="v_number" class="form-control" placeholder="Vehicle Number" maxlength="100" required>
                    </div>
                  </div>  
                  <div class="col-12 col-sm-6">   
                    <div class="form-group">
                      <label for="select">Staff Name English<span style="color: red;">*</span></label>
                      <input type="text" name="agency_staff" id="staff" value="<?=$agency->agency_staff?>" class="form-control" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' placeholder="Agency Staff English" required>
                    </div>
                  </div> 
                   <div class="col-12 col-sm-6">  
                      <div class="form-group">
                        <label for="select">Staff Name Arabic</label>
                       <input type="text" name="staff_arabic" id="staff_arabic" value="<?=$agency->staff_arabic?>" class="form-control" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' placeholder="Agency Staff Arabic">
                      </div>
                    </div>
                   <!--  <div class="form-group">
                      <label for="select">Initial Cans<span style="color: red;">*</span></label>
                      <input type="text" name="initial_cans" id="cans" value="<?=$agency->initial_cans_allotted?>" class="form-control" placeholder="Initial Cans" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="11" required>
                    </div> -->
                  <div class="col-12 col-sm-6">    
                    <div class="form-group">
                      <label for="exampleInputFile"> Image<span style=" color: red;">*</span></label>
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input"  id="upload">
                          <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                      </div>
                    </div>
                  </div>  
                  <!-- </div>
                   <div class="col-md-6">  -->
                  <div class="col-12 col-sm-6">    
                     <div id="current-image">
                        <img src="<?=base_url() . $agency->agency_image?>" height="300px" width="300px">
                      </div>    
                      <div class="upload-div" style="display:none;">
                        <div id="upload-demo"></div>
                        <div class="col-12 text-center">
                          <a href="#" class="btn btn-primary" style="border-radius : 5px;" id="crop-button">Crop</a>
                        </div>
                      </div>

                      <div class="upload-result text-center" id="upload-result" style="display : none; margin-bottom:10px;">
                      </div>
                      <input type="hidden" name="image" id="ameimg" >
                   <!-- </div> -->
                  </div> 
                 </div>  
               </div> 
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" id="submit-button" class="btn btn-primary">Update</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
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
<?php $this->load->view('admin/includes/scripts');?>
 <script src="<?=base_url()?>plugins/image-crop/croppie.js"></script>
  <script type="text/javascript">
  $uploadCrop = $('#upload-demo').croppie({
      enableExif: true,
      viewport: {
          width: 300,
          height: 300,
          type: 'rectangle'
      },
      boundary: {
          width: 400,
          height: 400
      }
  });


  $('#upload').on('change', function () {
    $("#submit-button").css("display", "none");
    var file = $("#upload")[0].files[0];
    var val = file.type;
    var type = val.substr(val.indexOf("/") + 1);
    if (type == 'png' || type == 'jpg' || type == 'jpeg') {

      $("#current-image").css("display", "none");
      $("#submit-button").css("display", "none");

      $(".upload-div").css("display", "block");
      $("#submit-button").css("display", "none");
      var reader = new FileReader();
        reader.onload = function (e) {
          $uploadCrop.croppie('bind', {
            url: e.target.result
          }).then(function(){
            console.log('jQuery bind complete');
          });

        }
        reader.readAsDataURL(this.files[0]);
    }
    else {
      alert('This file format is not supported.');
      document.getElementById("upload").value = "";
      $("#upload-result").css("display", "none");
      $("#submit-button").css("display", "none");
      $("#current-image").css("display", "block");
      $('#ameimg').val('');
    }
  });


  $('#crop-button').on('click', function (ev) {
      $("#submit-button").css("display", "block");
    $uploadCrop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function (resp) {
      html = '<img src="' + resp + '" />';
      $("#upload-result").html(html);
      $("#upload-result").css("display", "block");
      $(".upload-div").css("display", "none");
      $("#submit-button").css("display", "block");
      $('#ameimg').val(resp);
    });
  });
  </script>
</body>
</html>
