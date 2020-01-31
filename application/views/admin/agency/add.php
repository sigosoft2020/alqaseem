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
                <h3 class="card-title">Add Agency</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id="Myform" action="<?=site_url('admin/agency/addData')?>" method="POST">
                <div class="card-body">
                  <div class="row">
                    <!-- <div class="col-md-7">  -->
                    <div class="col-12 col-sm-6">  
                      <div class="form-group">
                        <label for="select">Name<span style="color: red;">*</span></label>
                        <input type="text" name="agency_name" id="agency_name" class="form-control" placeholder="Agency Name" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' required>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6">  
                      <div class="form-group">
                        <label for="select">Name Arabic</label>
                        <input type="text" name="agency_arabic" id="agency_arabic" class="form-control" placeholder="Agency Name Arabic" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))'>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6">  
                      <div class="form-group">
                        <label for="select">Code<span style="color: red;">*</span></label>
                        <input type="text" name="agency_code" id="agency_code" class="form-control" placeholder="Agency Code"  required>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6">  
                      <div class="form-group">
                        <label for="select">Phone<span style="color: red;">*</span></label>
                        <input type="text" name="agency_phone" id="agency_phone" class="form-control" placeholder="Agency Phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="11" required>
                      </div>
                    </div>  
                    <div class="col-12 col-sm-6">  
                      <div class="form-group">
                        <label for="select">Vehicle Number<span style="color: red;">*</span></label>
                        <input type="text" name="vehicle_number" id="vehicle_number" class="form-control" placeholder="Vehicle Number" maxlength="100" required>
                      </div>
                    </div>  
                    <div class="col-12 col-sm-6">  
                      <div class="form-group">
                        <label for="select">Staff Name English<span style="color: red;">*</span></label>
                       <input type="text" name="agency_staff" id="agency_staff" class="form-control" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' placeholder="Agency Staff English" required>
                      </div>
                    </div>  
                    <div class="col-12 col-sm-6">  
                      <div class="form-group">
                        <label for="select">Staff Name Arabic</label>
                       <input type="text" name="staff_arabic" id="staff_arabic" class="form-control" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' placeholder="Agency Staff Arabic">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Initial Cans</label>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group m-b-25">
                              <div class="col-12">
                                <table id="product-price">
                                  <thead>
                                    <tr>
                                      <td width="40%">Product</td>
                                      <td width="10">Price</td> 
                                      <td width="35">No of cans</td> 
                                      <td width="10">Total</td>                  
                                      <td width="5"></td>
                                    </tr>
                                  </thead>
                                  <tr>
                                     <td>
                                        <select class="form-control select2" name="product_id" id="typeahead" onchange="Getproduct()">
                                          <option value="">---Select Product---</option>
                                          <?php foreach($products as $product) {?>
                                            <option value="<?php echo $product->product_id;?>"><?php echo $product->product_name;?></option>
                                          <?php };?>  
                                        </select>
                                        
                                        <input type="hidden" name="ProductName" id="ProductName">
                                     </td>
                                     
                                     <td><input class="form-control" name="Price" placeholder="Price" type="text" id="Price" onkeypress="return event.charCode >= 48 && event.charCode <= 57" oninput="GetPtotalByPrice()"></td>

                                     <input type="hidden" class="form-control" name="ProductStock" id="ProductStock" value="">

                                     <td><input class="form-control" name="Quantity" placeholder="No of cans"  type="text" id="Quantity" onkeypress="return event.charCode >= 48 && event.charCode <= 57" oninput="GetPtotal()"></td>
                                     
                                     <td> <input class="form-control " name="Total" placeholder="Total" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  id="Total"></td>
                                      
                                     <input type="hidden" name="ProductID" id="ProductID">

                                     <td><input type="button" class="btn btn-info m-l add_btn_o" name="add" id="add" onclick="insertRow();" value="Add"></td>
                                  </tr>
                                </table>

                                  <table id="myTable" cellspacing="10">
                                     <tr>
                                    </tr><br>
                                  </table><br>
                                  
                                  <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                      <label>Grand Total(₹)</label>
                                      <input class="form-control" type="text" id="grandTotal" value="0" readonly>
                                   </div>
                                  </div>
                                 
                                </div>
                              </div>
                            </div>
                          </div>
                       </div>
                    <div class="col-12 col-sm-6">   
                      <div class="col-12">
                        <label for="select">Password<span style="color: red;">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" minlength="6" maxlength="8" autocomplete="off" required>
                      </div>
                    </div>  
                    <div class="col-12 col-sm-6">  
                      <div class="form-group">
                        <label for="exampleInputFile">Image</label>
                        <div class="input-group">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input"  id="upload">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                          </div>
                        </div>
                      </div>
                    </div>  
                    <!-- </div> -->
                     <!-- <div class="col-md-6">      -->
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
                <!-- /.card-body -->
 
                <div class="card-footer">
                  <button type="submit" id="submit-button" class="btn btn-primary">Submit</button>
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
            toastr.error("Mobile number already registered");          
          }   
          else if(data== 2)
          {
            toastr.error("Agency code already registered");        
          }           
        }             
      });            
    });
  </script>
  <script type="text/javascript">     
        var list = [];     
        var index = 1;
             function insertRow(){
               var ProductName = document.getElementById('ProductName').value;
               var Price = document.getElementById('Price').value;
               var Stock    = document.getElementById('ProductStock').value;
               var Quantity = document.getElementById('Quantity').value;
               var Total    = document.getElementById('Total').value;
               var Product_Id = document.getElementById('ProductID').value;

               if(ProductName==null || ProductName=="")
               {
                 // alert("Select product")
                 toastr.error("Please select product");
               }
               else if( Price==null || Price=="")
               {
                 toastr.error("Please add price");
               }
               else if( Quantity==null || Quantity=="")
               {
                toastr.error("Please add no of cans");
               }
               else  if( Stock=='0')
               {
                // alert("Sorry! Only " +Stock+ " items are available ")
                  toastr.error("Sorry! Out of stock");
               } 
               else  if( parseInt($('#Quantity').val(),10) > parseInt($('#ProductStock').val(),10))
               {
                // alert("Sorry! Only " +Stock+ " items are available ")
                 toastr.error("Sorry! Only " +Stock+ " items are available ");
               } 
               else  if( Total==null || Total=="")
               {
                 toastr.error("Please add total price");
               }  
               else
               {           
                var table=document.getElementById("myTable");
                var row=table.insertRow(table.rows.length);
                var cell1=row.insertCell(0);

                var t1=document.createElement("input");
                    t1.id = "index"+index;
                    t1.value=index;
                    t1.name="no[]";
                    t1.className = "form-control col-md-12 col-xs-12";
                    t1.style.marginTop = "10px"

                    cell1.appendChild(t1);

                var cell2=row.insertCell(1);

                var t2=document.createElement("input");
                    t2.id = "ProductName"+index;
                    t2.value=ProductName;
                    t2.name="ProductName[]";
                    t2.className = "form-control col-md-12 col-xs-12";
                    t2.style.marginTop = "10px"

                    cell2.appendChild(t2);
                    
                    var cell3=row.insertCell(2);
                    var t3=document.createElement("input");
                    t3.id = "Price"+index;
                    t3.value=Price;
                    t3.name="Price[]";
                    t3.className = "form-control col-md-12 col-xs-12";
                    t3.style.marginTop = "10px"
                    cell3.appendChild(t3);

                    var cell4=row.insertCell(3);
                    var t4=document.createElement("input");
                    t4.id = "Quantity"+index;
                    t4.value=Quantity;
                    t4.name="Quantity[]";
                    t4.className = "form-control col-md-12 col-xs-12";
                    t4.style.marginTop = "10px"
                    cell4.appendChild(t4);
                                                                 
                var cell5=row.insertCell(4);
                var t5=document.createElement("input");
                    t5.id = "Total"+index;
                    t5.value=Total;
                    t5.name="Total[]";
                    t5.className = "form-control col-md-12 col-xs-12";
                    t5.style.marginTop = "10px"

                    cell5.appendChild(t5);
                

                  var cell6=row.insertCell(5);
                  var t6=document.createElement("BUTTON");
                  var t = document.createTextNode("Remove");
                  t6.appendChild(t);
                  document.body.appendChild(t6);

                  t6.className = "btn btn-danger m-l remove";
                  t6.style.marginTop = "10px"

                  cell6.appendChild(t6);

                  var cell7=row.insertCell(6);
                  var t7=document.createElement("input");
                  t7.id = "ProductID"+index;
                  t7.value=Product_Id;
                  t7.name="ProductID[]";
                  t7.type="hidden";
                  t7.className = "form-control col-md-6 col-xs-12";
                  t7.style.marginTop = "10px"
                  cell7.appendChild(t7);
                  
          index++;

           document.getElementById('typeahead').value="";
           document.getElementById('ProductName').value="";
           document.getElementById('Price').value="";
           document.getElementById('Quantity').value="";
           document.getElementById('Total').value="";
           document.getElementById('ProductID').value="";
        }
       var arr = document.getElementsByName('Total[]');
        var totalLength = arr.length;
        var subTotal=0;
        var grandTotal=0;
        for(i=0;i<totalLength;i++)
        {
         subTotal = subTotal+parseFloat(arr[i].value);
        }
        grandTotal=subTotal;
        document.getElementById('grandTotal').value=grandTotal; 
    }

    $(function()  
    {  
     
    $('body').delegate('.remove','click',function()  
    {  
    $(this).parent().parent().remove();  
    var arr = document.getElementsByName('Total[]');
        var totalLength = arr.length;
        var subTotal=0;
         var grandTotal=0;
         var gst=0;
        for(i=0;i<totalLength;i++)
        {
         subTotal = subTotal+parseFloat(arr[i].value);
        }
        
         grandTotal=subTotal;
        document.getElementById('grandTotal').value=grandTotal;
        
    });  
    }); 

 </script> 
 <script type="">
    function Getproduct()
      {    
        var id = document.getElementById('typeahead').value;

        $.ajax({
          method: "POST",
          url: "<?php echo site_url('admin/bill/get_product');?>",
          dataType : "json",
          data : { id : id },
          success : function( data )
          {
            $('#ProductID').val(data.product_id);
            $('#ProductName').val(data.product_name);
            $('#Price').val(data.price);
            $('#ProductStock').val(data.stock);
            document.getElementById('Quantity').value='';
            document.getElementById('Total').value='';
          }
        });
       }

      function GetPtotal()
      {
          var Price     = document.getElementById('Price').value;
          var Quantity  = document.getElementById('Quantity').value;
          
           var ptotal  = Price*Quantity;
           var ptotale = (ptotal).toFixed(2);
           document.getElementById('Total').value = ptotale;
      }

      function GetPtotalByPrice()
      {
          var Price     = document.getElementById('Price').value;
          var Quantity  = document.getElementById('Quantity').value;
          
           var ptotal  = Price*Quantity;
           var ptotale = (ptotal).toFixed(2);
           document.getElementById('Total').value = ptotale;
      }
  </script>
</body>
</html>
