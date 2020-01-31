<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Coupons</title>
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
            <h1>Coupon Packages</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-right">
             <button type="button" class="btn btn-primary btn-rounded waves-light waves-effect w-md" data-toggle="modal" data-target="#add-package">Add Package</button>
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
                  <th width="20%">Package Name</th>
                  <th width="20%">Product Name</th>
                  <th width="15%">Minimum No Of Cans</th> 
                  <!--<th width="10%">Package Count</th>-->
                  <th width="15%">Package Minimum Price</th>
                  <th width="15%">Package Validity</th>
                  <th width="5%">Status</th>
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
  
  <div id="add-package" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-uppercase text-center m-b-30">
                    <span><h4>Add Package</h4></span>
                </h2>
                <form class="form-horizontal" id="Myform" action="<?=site_url('admin/coupons/addCoupon')?>" method="post">

                    <div class="form-group m-b-25">
                        <div class="col-12">
                          <label for="select">Product<span style="color: red;">*</span></label>
                          <select class="form-control select2"  name="product_id" id="product_id" required>
                             <option value="">---Select Product---</option>
                             <?php foreach($products as $product) {?>
                                <option value="<?=$product->product_id?>"><?=$product->product_name?>
                                 </option>
                             <?php };?>
                          </select>
                        </div>
                    </div>

                    <div class="form-group m-b-25">
                      <div class="col-12">
                          <label for="select">Minimum No Of Cans<span style="color: red;">*</span></label>
                          <input type="text" name="cans" id="cans" class="form-control" placeholder="Minimum No Of Cans" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="4"required>
                      </div>
                    </div>

                    <div class="form-group m-b-25">
                        <div class="col-12">
                            <label for="select">Package Name English<span style="color: red;">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Package Name English" required>
                        </div>
                    </div>
                    
                     <div class="form-group m-b-25">
                        <div class="col-12">
                            <label for="select">Package Name Arabic</label>
                            <input type="text" name="name_arabic" id="name_arabic" class="form-control" placeholder="Package Name Arabic">
                        </div>
                     </div>

                    <!--<div class="form-group m-b-25">-->
                    <!--  <div class="col-12">-->
                    <!--      <label for="select">Package Count<span style="color: red;">*</span></label>-->
                    <!--      <input type="text" name="count" id="count" class="form-control" placeholder="Package Count" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="4"required>-->
                    <!--  </div>-->
                    <!--</div>-->

                    <div class="form-group m-b-25">
                      <div class="col-12">
                          <label for="select">Package Minimum Price<span style="color: red;">*</span></label>
                          <input type="number" name="price" id="price" class="form-control" placeholder="Package Minimum Price" step="any" required>
                      </div>
                    </div>

                    <div class="form-group m-b-25">
                      <div class="col-12">
                          <label for="select">Package Validity(No of days)<span style="color: red;">*</span></label>
                          <input type="text" name="validity" id="validity" class="form-control" placeholder="Package Validity" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="4"required>
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

 <div id="edit-package" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-uppercase text-center m-b-30">
                    <span><h4>Edit Package</h4></span>
                </h2>
                <form class="form-horizontal" action="<?=site_url('admin/coupons/editCoupon')?>" method="post">
                   
                    <div class="form-group m-b-25">
                        <div class="col-12">
                          <label for="select">Product<span style="color: red;">*</span></label>
                          <select class="form-control select2"  name="product_id" id="product" required>                         
                          </select>
                        </div>
                    </div>

                    <div class="form-group m-b-25">
                      <div class="col-12">
                          <label for="select">Minimum No Of Cans<span style="color: red;">*</span></label>
                          <input type="text" name="cans" id="no_of_bottles" class="form-control" placeholder="Minimum No Of Cans" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="4"required>
                      </div>
                    </div>

                    <div class="form-group m-b-25">
                        <div class="col-12">
                            <label for="select">Package Name English</label>
                            <input type="text" name="name" id="pack_name" class="form-control" placeholder="Package Name English" required>
                            <input type="hidden" name="pack_id" id="pack_id">
                        </div>
                    </div>
                    
                    <div class="form-group m-b-25">
                        <div class="col-12">
                            <label for="select">Package Name Arabic</label>
                            <input type="text" name="name_arabic" id="package_arabic" class="form-control" placeholder="Package Name Arabic">
                        </div>
                     </div>

                    <!--<div class="form-group m-b-25">-->
                    <!--  <div class="col-12">-->
                    <!--      <label for="select">Package Count</label>-->
                    <!--      <input type="text" name="count" id="pack_count" class="form-control" placeholder="Package Count" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="4"required>-->
                    <!--  </div>-->
                    <!--</div>-->

                    <div class="form-group m-b-25">
                      <div class="col-12">
                          <label for="select">Package Minimun Price</label>
                          <input type="number" name="price" id="pack_default_price" class="form-control" placeholder="Package Minimum Price" step="any" required>
                      </div>
                    </div>

                    <div class="form-group m-b-25">
                      <div class="col-12">
                          <label for="select">Package Validity(No of days)</label>
                          <input type="text" name="validity" id="pack_validity" class="form-control" placeholder="Package Validity" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="4"required>
                      </div>
                      <p id="error-messagee" style="color: red;"></p> 
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
          url:"<?=site_url('admin/coupons/get')?>",
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
      product= $('#product_id').val();
      // alert(mobile);
       $.ajax({
        method: "POST",
        url: "<?php echo site_url('admin/coupons/checkCoupon');?>",
        dataType : "json",
        data : { name      : name,
                 product   : product
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
            toastr.error("Pack with same name already added");            
          }           
        }             
      });            
    });

    function edit(id)
    {
      $('#pack_id').val(id);
      // alert(id);
      $.ajax({
          method: "POST",
          url: "<?php echo site_url('admin/coupons/getCouponById');?>",
          dataType : "json",
          data : { id : id },
          success : function( data ){
            var coupon = data.coupons;
            var product= data.products;
            $('#pack_name').val(coupon.pack_name);
            $('#package_arabic').val(coupon.pack_name_arabic);
            $('#no_of_bottles').val(coupon.no_of_bottles);
            // $('#pack_count').val(coupon.pack_count);
            $('#pack_default_price').val(coupon.pack_default_price);
            $('#pack_validity').val(coupon.pack_validity);
            $('#product').html(product);
            $('#edit-package').modal('show');
            // alert(data);
          }
        });
    }
  </script>
</body>
</html>
