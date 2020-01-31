<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Accessories</title>
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
            <h1>Accessories</h1>
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
                <h3 class="card-title">Edit Accessory</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="<?=site_url('admin/accessories/editData')?>" method="POST">
                <div class="card-body">
                  <div class="row">
                  <div class="col-md-6"> 
                    <div class="form-group">
                      <label for="exampleInputEmail1">Accessory Name<span style=" color: red;">*</span></label>
                      <input type="text" class="form-control" name="acc_name" id="acc_name" placeholder="Product Name" value="<?php echo @$accessory->acc_name?>" required>
                      <input type="hidden" name="accessory_id" value="<?php echo @$accessory->acc_id?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Accessory Price<span style=" color: red;">*</span></label>
                      <input type="number" class="form-control" name="acc_price" id="acc_price" placeholder="Accessory Price" value="<?php echo @$accessory->acc_price?>" step="any" required>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Accessory Description<span style=" color: red;">*</span></label>
                       <textarea class="form-control" rows="3" name="acc_description" placeholder="Product Description" required><?php echo @$accessory->acc_description?></textarea>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Accessory VAT(%)<span style=" color: red;">*</span></label>
                      <input type="number" class="form-control" name="acc_vat" id="acc_vat" placeholder="VAT(%)" value="<?php echo @$accessory->acc_vat?>" step="any" required>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputFile">Accessory Image<span style=" color: red;">*</span></label>
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input"  id="upload">
                          <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Accessory Status</label>
                      <select class="form-control" name="status">
                        <option value="1" <?php if($accessory->acc_status=='1') { echo "selected"; }?>>Active</option>
                        <option value="0" <?php if($accessory->acc_status=='0') { echo "selected"; }?>>Blocked</option>
                      </select>
                    </div>
                  </div>
                   <div class="col-md-6"> 
                     <div id="current-image">
                        <img src="<?=base_url() . $accessory->acc_image?>" height="400px" width="400px">
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
          width: 400,
          height: 400,
          type: 'rectangle'
      },
      boundary: {
          width: 500,
          height: 500
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
