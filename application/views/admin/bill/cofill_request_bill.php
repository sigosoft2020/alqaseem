<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Billing</title>
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
            <h1>Billing</h1>
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
                <h3 class="card-title">Co Fill Billing</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id="Myform" action="<?=site_url('admin/bill/addCofillData')?>" method="POST">
                <div class="card-body">
                 <div class="row">
                   <div class="col-md-8"> 

                      <div class="form-group">
                        <label for="exampleInputEmail1">Customer Name<span style=" color: red;">*</span></label>
                        <input type="text" class="form-control" name="customer_name" value="<?=$request->customer_name?>" readonly>
                        <input type="hidden" name="customer_id" value="<?=$request->customer_id?>">
                        <input type="hidden" name="request_id" value="<?=$request->co_request_id?>">
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
                                      </tr>
                                    </thead>
                                    
                                      <?php foreach($request->products as $req){?>
                                      <tr>
                                       <td>                                          
                                          <input type="text" class="form-control" name="ProductName[]" id="ProductName" value="<?=$req->product?>" readonly>
                                          <input type="hidden" name="ProductID[]" id="ProductID" value="<?=$req->product_id?>">
                                       </td>
                                       
                                       <td><input class="form-control" name="Price[]" placeholder="Price" value="<?=$req->price?>" type="number" id="Price<?=$req->product_id?>" step="any" oninput="GetPtotalByPrice('<?=$req->product_id?>')"></td>

                                       <input type="hidden" class="form-control" name="ProductStock" id="Stock<?=$req->product_id?>" value="<?=$req->stock?>">

                                       <td><input class="form-control" name="Quantity[]" placeholder="Quantity"  type="text" id="Quantity<?=$req->product_id?>" value="<?=$req->quantity?>" readonly></td>
                                       
                                       <td> <input class="form-control " name="Total[]" placeholder="" type="text" id="Total<?=$req->product_id ?>" value="<?=$req->total?>" readonly></td>
                                        
                                       <!-- <input type="hidden" name="ProductID[]"  value="<?=$req->product_id?>"> -->
                                      </tr> 
                                    <?php };?> 
                                   
                                  </table>

                                <?php $grand_total = 0;
                                      foreach($request->products as $req)
                                      {
                                        $grand_total = $grand_total+$req->total;
                                      }
                                 ?>
                                    
                                  <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                      <label>Grand Total(₹)</label>
                                      <input class="form-control" type="text" id="grandTotal" value="<?=$grand_total?>" readonly>
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
                // alert("Sorry! Only " +Stock+ " items are available ")
                  toastr.error("Sorry!..Out of stock");
               } 
               else  if( parseInt($('#Quantity').val(),10) > parseInt($('#ProductStock').val(),10))
               {
                // alert("Sorry! Only " +Stock+ " items are available ")
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
            $('#Price').val(data.default_price);
            $('#ProductStock').val(data.stock);
          }
        });
      }

      function GetPtotal(id)
      {   
        var qty_id      = "Quantity" + id;
        var qty_result  = document.getElementById(qty_id);
        var Quantity    = qty_result.value;

        var stock_id      = "Stock" + id;
        var stock_result  = document.getElementById(stock_id);
        var Stock         = stock_result.value;
        
        var price_id      = "Price" + id;
        var price_result  = document.getElementById(price_id);
        var Price         = price_result.value;
        // alert(Stock);
        // var Price    = document.getElementById('Price').value;
        // var Quantity = document.getElementById('Quantity').value;
        var total_id      = "Total" + id;
        var total_result  = document.getElementById(total_id);
        var Total         = total_result.value;
        
        if( Quantity > Stock)
         {
          // alert("Sorry! Only " +Stock+ " items are available ")
            toastr.error("Sorry! Only " +Stock+ " items are available ");
            document.getElementById(qty_id).value = ''; 
         } 
         else if( Quantity == 0)
         {
            toastr.error("Sorry! Out of stock ");
            document.getElementById(qty_id).value = ''; 
         }
         else
         {
           var ptotal  = Price*Quantity;
           var ptotale = (ptotal).toFixed(2);
           document.getElementById(total_id).value = ptotale;

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
            // alert(totalLength);
          }
      }

      function GetPtotalByPrice(id)
      {   
        var qty_id      = "Quantity" + id;
        var qty_result  = document.getElementById(qty_id);
        var Quantity    = qty_result.value;
        
        var price_id      = "Price" + id;
        var price_result  = document.getElementById(price_id);
        var Price         = price_result.value;
        // alert(Price);
        // var Price    = document.getElementById('Price').value;
        // var Quantity = document.getElementById('Quantity').value;
        var total_id      = "Total" + id;
        var total_result  = document.getElementById(total_id);
        var Total         = total_result.value;
        
        var ptotal       = Price*Quantity;
        var ptotale      = (ptotal).toFixed(2);
        if(Quantity=='' || Total=='')
        {
          document.getElementById(total_id).value = '';
        }
        else
        {
          document.getElementById(total_id).value = ptotale;
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

  </script>
</body>
</html>
