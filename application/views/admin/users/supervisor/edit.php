<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Users</title>
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
            <h1>Supervisor</h1>
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
                <h3 class="card-title">Edit Supervisor</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="<?=site_url('admin/users/editSupervisor')?>" method="POST">
                <div class="card-body">
                  <div class="row">
                  <!-- <div class="col-md-6">  -->
                   <div class="col-12 col-sm-6">   
                      <div class="form-group">
                        <label for="select">Name<span style="color: red;">*</span></label>
                        <input type="text" name="name" value="<?=$supervisor->name?>" id="name" class="form-control" placeholder=" Name" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' required>
                        <input type="hidden" name="user_id" value="<?=$supervisor->supervisor_id?>">
                      </div>
                    </div>  
                    <div class="col-12 col-sm-6">     
                      <div class="form-group">
                        <label for="select">Name Arabic</label>
                        <input type="text" name="name_arabic" value="<?=$supervisor->name_arabic?>" id="name_arabic" class="form-control" placeholder="Name Arabic" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))'>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6"> 
                      <div class="form-group">
                        <label for="select">Phone<span style="color: red;">*</span></label>
                        <input type="text" name="phone" id="phone" value="<?=$supervisor->phone?>" class="form-control" placeholder=" Phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="11" required>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6">   
                      <div class="form-group">
                        <label for="select">Email<span style="color: red;">*</span></label>
                        <input type="email" name="email" id="email" value="<?=$supervisor->email?>" class="form-control" placeholder="Email"  required>
                      </div>
                    </div>
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
                    <div class="col-12 col-sm-6">
                       <div id="current-image">
                          <img src="<?=base_url() . $supervisor->image?>" height="250px" width="250px">
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
          width: 250,
          height: 250,
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
