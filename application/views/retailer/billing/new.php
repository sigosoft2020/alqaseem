<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Billing</title>
  <?php $this->load->view('retailer/includes/header');?>
  <link rel="stylesheet" href="<?=base_url()?>plugins/image-crop/croppie.css">
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
            <h1>Billing</h1>
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
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">New Retail Billing</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id="Myform" action="<?=site_url('retailer/bill/addData')?>" method="POST">
                <div class="card-body">
                 <div class="row">
                   <div class="col-md-8"> 

                      <div class="form-group">
                        <label for="exampleInputEmail1">Customer<span style=" color: red;">*</span></label>
                        <select class="form-control select2"  name="customer_id" id="customer_id" required>
                           <option value="">---Select Customer---</option>
                           <?php foreach($customers as $customer) {?>
                               <option value="<?=$customer->customer_id?>"><?=$customer->customer_phone?>
                               </option>
                           <?php };?>
                        </select>
                        <input type="hidden" name="sale_type" value="retail">
                      </div>

                      <div class="form-group">
                        <label for="exampleInputEmail1"></label>
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group m-b-25">
                                <div class="col-12">
                                  <table id="product-price">
                                    <thead>
                                      <tr>
                                        <td width="45%">Product</td>
                                        <td width="20%">Price</td>
                                        <td width="20%">Quantity</td>
                                        <td width="20%">Total</td>                     
                                        <td width="5%"></td>
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
                                       
                                       <td><input class="form-control" name="Price" placeholder="Price" type="number" id="Price" step="any" oninput="GetPtotalByPrice()"></td>

                                       <input type="hidden" class="form-control" name="ProductStock" id="ProductStock" value="">

                                       <td><input class="form-control" name="Quantity" placeholder="Quantity"  type="text" id="Quantity" onkeypress="return event.charCode >= 48 && event.charCode <= 57" oninput="GetPtotal()"></td>
                                       
                                       <td> <input class="form-control " name="Total" placeholder="" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  id="Total"></td>
                                        
                                       <input type="hidden" name="ProductID" id="ProductID">
                                       
                                       <input type="hidden" name="vat" id="vat">
                                       <input type="hidden" name="vat_total" id="vat_total"> 
                                       <input type="hidden" name="vat_only" id="vat_only">

                                       <td><input type="button" class="btn btn-info m-l add_btn_o" name="add" id="add" onclick="insertRow();" value="Add"></td>
                                    </tr>
                                  </table>
                                 
                                <table id="myTable" cellspacing="10">
                                   <tr>
                                  </tr><br>
                                </table><br>
                                    
                                  <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                      <label>Sub Total(AED)</label>
                                      <input class="form-control" type="text" id="subTotal" value="0" readonly>
                                    </div>
                                  </div>
                                  <br>
                                  
                                  <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                      <label>Total VAT Amount(AED)</label>
                                      <input class="form-control" type="text" id="vatTotal" value="0" readonly>
                                    </div>
                                  </div>
                                   <br>
                                   
                                    <div class="row">
                                      <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label>Grand Total(₹)</label>
                                        <input class="form-control" type="text" id="grandTotal" value="0" readonly>
                                     </div>
                                    </div>
                                   
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="form-group col-md-6 col-sm-12 col-xs-12">
                            <label for="exampleInputPassword1">Amount Received<span style=" color: red;">*</span></label>
                             <input type="text" class="form-control" name="amount_recived" id="amount_recived" placeholder="Amount Received" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                          </div>
                      </div>                  
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
                    <form class="form-horizontal" id="Myform" action="<?=site_url('retailer/bill/addCustomer')?>" method="post" enctype="multipart/form-data">
                        <div class="form-group m-b-25">
                            <div class="col-12">
                                <label for="select">Customer Phone<span style="color: red;">*</span></label>
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="Customer Phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="9" minlength="9" required>
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
<?php $this->load->view('retailer/includes/scripts');?>

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
               var vat_total = document.getElementById('vat_total').value;
               var vat_only  = document.getElementById('vat_only').value;

               if(ProductName==null || ProductName=="")
               {
                  toastr.error("Please select product"); 
               }
               else if( Price==null || Price=="")
               {
                 toastr.error("Please add price"); 
               }
               else if( Quantity==null || Quantity=="")
               {
                 toastr.error("Please add quantity"); 
               }
               else  if( Stock=='0')
               {
                toastr.error("Sorry!..Out of stock"); 
               } 
               else  if( parseInt($('#Quantity').val(),10) > parseInt($('#ProductStock').val(),10))
               {
                 toastr.error("Sorry! Only " +Stock+ " items are available "); 
               } 
               else  if( Total==null || Total=="")
               {
                 toastr.error("Please enter the total price");
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
                  
                  var cell8=row.insertCell(7);
                  var t8=document.createElement("input");
                  t8.id = "vat"+index;
                  t8.value=vat;
                  t8.name="vat[]";
                  t8.type="hidden";
                  t8.className = "form-control col-md-6 col-xs-12";
                  t8.style.marginTop = "10px"
                  cell8.appendChild(t8);
                   
                  var cell9=row.insertCell(8);
                  var t9=document.createElement("input");
                  t9.id = "vat_total"+index;
                  t9.value=vat_total;
                  t9.name="vat_total[]";
                  t9.type="hidden";
                  t9.className = "form-control col-md-6 col-xs-12";
                  t9.style.marginTop = "10px"
                  cell9.appendChild(t9);

                  var cell10=row.insertCell(9);
                  var t10=document.createElement("input");
                  t10.id = "vat_only"+index;
                  t10.value=vat_only;
                  t10.name="vat_only[]";
                  t10.type="hidden";
                  t10.className = "form-control col-md-6 col-xs-12";
                  t10.style.marginTop = "10px"
                  cell10.appendChild(t10);
                  
          index++;

           document.getElementById('typeahead').value="";
           document.getElementById('ProductName').value="";
           document.getElementById('Price').value="";
           document.getElementById('Quantity').value="";
           document.getElementById('Total').value="";
           document.getElementById('ProductID').value="";
           document.getElementById('vat').value="";
           document.getElementById('vat_total').value="";
           document.getElementById('vat_only').value="";
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
        
        var arr = document.getElementsByName('vat_total[]');
        var totalLength = arr.length;
        var VAT=0;
        for(i=0;i<totalLength;i++)
        {
         VAT = VAT+parseFloat(arr[i].value);
        }
        var vat_sum= (parseFloat(VAT)).toFixed(2);
        document.getElementById('subTotal').value=vat_sum;

        var arr = document.getElementsByName('vat_only[]');
        var totalLength = arr.length;
        var Vat=0;
        for(i=0;i<totalLength;i++)
        {
         Vat = Vat+parseFloat(arr[i].value);
        }
        var vat_only_sum= (parseFloat(Vat)).toFixed(2);
        document.getElementById('vatTotal').value=vat_only_sum;
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
          
            var arr = document.getElementsByName('vat_total[]');
            var totalLength = arr.length;
            var VAT=0;
            for(i=0;i<totalLength;i++)
            {
             VAT = VAT+parseFloat(arr[i].value);
            }
            var vat_sum= (parseFloat(VAT)).toFixed(2);
            document.getElementById('subTotal').value=vat_sum;
    
            var arr = document.getElementsByName('vat_only[]');
            var totalLength = arr.length;
            var Vat=0;
            for(i=0;i<totalLength;i++)
            {
             Vat = Vat+parseFloat(arr[i].value);
            }
            var vat_only_sum= (parseFloat(Vat)).toFixed(2);
            document.getElementById('vatTotal').value=vat_only_sum;
          
      });  
    }); 

 </script>   


<script type="">
    function Getproduct()
      {    
        var id = document.getElementById('typeahead').value;
        $.ajax({
          method: "POST",
          url: "<?php echo site_url('retailer/bill/get_product');?>",
          dataType : "json",
          data : { id : id },
          success : function( data )
          {
            $('#ProductID').val(data.product_id);
            $('#ProductName').val(data.product_name);
            $('#Price').val(data.price);
            $('#ProductStock').val(data.stock);
            $('#vat').val(data.product_vat);
            document.getElementById('Quantity').value='';
            document.getElementById('Total').value='';
          }
        });
       }

      function GetPtotal()
      {
          var Price     = document.getElementById('Price').value;
          var Quantity  = document.getElementById('Quantity').value;
          var vat       = document.getElementById('vat').value;
          
           var ptotal  = Price*Quantity;
           var ptotale = (ptotal).toFixed(2);
           document.getElementById('Total').value = ptotale;
           
           var vat1 =parseFloat(ptotale)/(1+(parseInt(vat)/100));
           document.getElementById('vat_total').value = (parseFloat(vat1)).toFixed(2 );

           var vat2 = ptotale-vat1;
           document.getElementById('vat_only').value = (parseFloat(vat2)).toFixed(2 );
      }

      function GetPtotalByPrice()
      {
          var Price     = document.getElementById('Price').value;
          var Quantity  = document.getElementById('Quantity').value;
          var vat       = document.getElementById('vat').value;
          
           var ptotal  = Price*Quantity;
           var ptotale = (ptotal).toFixed(2);
           document.getElementById('Total').value = ptotale;
           
           var vat1 =parseFloat(ptotale)/(1+(parseInt(vat)/100));
           document.getElementById('vat_total').value = (parseFloat(vat1)).toFixed(2 );

           var vat2 = ptotale-vat1;
           document.getElementById('vat_only').value = (parseFloat(vat2)).toFixed(2 );
      }

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
</body>
</html>
