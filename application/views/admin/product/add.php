<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Add Product</title>
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
            <h1>Products</h1>
          </div>
        
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Add Product</h3>
            
          </div>
          <!-- /.card-header -->
        <form role="form" id="Myform" action="<?=site_url('admin/product/addProduct')?>" method="POST">  
          <div class="card-body">
            <div class="row">
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label>Category</label>
                  <select class="form-control select2" name="category_id" id="category_id" style="width: 100%;" required>
                    <option value="">---Select Category---</option>
                    <?php foreach($categories as $cat) {?>
                     <option value="<?=$cat->category_id?>"><?php echo $cat->category_english.'/'.$cat->category_arabic;?></option>
                    <?php };?>
                  </select>
                </div>
              </div>
              
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  
                </div>
              </div>

              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Product Name English<span style=" color: red;">*</span></label>
                  <input type="text" class="form-control" placeholder="Product Name English" name="product_name" id="product_name" required>
                </div>
              </div>

              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Product Name Arabic</label>
                  <input type="text" class="form-control" placeholder="Product Name Arabic" name="product_name_arabic" id="product_name_arabic">
                </div>
              </div>

           <!--    <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Product Weight<span style=" color: red;">*</span></label>
                  <input type="text" class="form-control" name="weight" id="weight" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>
              </div>

              <div class="col-12 col-sm-6">
                 <label for="exampleInputEmail1">Unit<span style=" color: red;">*</span></label>
                 <select name="unit" class="form-control">
                   <option value="L">Litre</option>
                   <option value="ml">ml</option>
                 </select>
              </div> -->
              
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Product VAT(%)<span style=" color: red;">*</span></label>
                  <input type="number" class="form-control" placeholder="Product VAT(%)" name="product_vat" id="product_vat" step="any" required>
                </div>
              </div>  

              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Product Price<span style=" color: red;">*</span></label>
                  <input type="number" class="form-control" placeholder="Product Price" name="price" id="price" step="any" required>
                </div>
              </div> 

              <div class="col-12 col-sm-6">
                 <div class="form-group">
                  <label for="exampleInputFile">Product Image<span style=" color: red;">*</span></label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input"  id="upload" required>
                      <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                    </div>
                  </div>
                </div>
              </div>  

              <div class="col-12 col-sm-6">     
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
            <!-- /.row -->
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <button type="submit" id="submit-button" class="btn btn-primary">Submit</button>
          </div>
         </form>  
        </div>
        <!-- /.card -->

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
      // alert('This file format is not supported.');
      toastr.error("This file format is not supported.");
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
  <script type="">
    $('#Myform').on('submit', function(e){
       e.preventDefault();
      // CODE HERE
      name   = $('#product_name').val();
      cat    = $('#category_id').val();
      // alert(mobile);
       $.ajax({
        method: "POST",
        url: "<?php echo site_url('admin/product/checkProduct');?>",
        dataType : "json",
        data : { name      : name,
                 cat       : cat
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
            toastr.error("Product already added.");
          }           
        }             
      });            
    });
  </script>
</body>
</html>
