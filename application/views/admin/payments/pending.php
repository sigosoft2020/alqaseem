<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Al Qaseem | Pending Payments</title>
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
            <h1>Pending Payments</h1>
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
              <table id="datatable" class="table table-bordered table-striped" style="width: 100%;">
                <thead>
                <tr>
                  <th width="25%">Customer Name</th>
                  <th width="20%">Customer Phone</th>
                  <th width="15%">Credit Amount</th>
                  <th width="15%">To</th>
                  <th width="10%">Action</th>
                </tr>
                </thead>
                <tbody>                 
                  <?php foreach($pendings as $pending)
                   { ?>
                     <tr>   
                        <td><?=$pending->customer?></td>
                        <td><?=$pending->phone?></td>
                        <td><?=$pending->amount?></td>
                        <td>
                          <?php if($pending->type=='retailer')
                                {
                                  echo "Retailer".'<br>'.$pending->retailer;
                                }
                                elseif($pending->type=='agency')
                                {
                                  echo "Agency".'<br>'.$pending->agency;
                                }
                                else
                                {
                                  echo "Admin";
                                }
                          ?>
                        </td>
                        <td><button class="btn btn-primary" onclick="pay('<?=$pending->cpp_id?>','<?=$pending->amount?>')">Pay Now</button></td>
                     </tr> 
                  <?php  }?>                 
                </tbody>
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

  <div id="add-payment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="text-uppercase text-center m-b-30">
                    <span><h4>Pay Here</h4></span>
                </h2>
                <form class="form-horizontal" id="Myform" action="<?=site_url('admin/payments/addPayment')?>" method="post">
                    <div class="form-group m-b-25">
                        <div class="col-12">
                            <label for="select">Current Credit Amount</label>
                            <input type="text" name="amount" id="amount" class="form-control" readonly>
                            <input type="hidden" id="payment_id" name="payment_id">
                        </div>
                    </div>

                    <div class="form-group m-b-25">
                        <div class="col-12">
                            <label for="select">Amount Received</label>
                            <input type="text" name="amount_received" id="received" class="form-control" placeholder="Amount Received" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                        </div>
                        <p id="error-messagee" style="color: red;"></p> 
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
<?php $this->load->view('admin/includes/table-script');?>
 <script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable({
        "ordering": false
    });

        //Buttons examples
        var table = $('#datatable-buttons').DataTable({
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf']
        });

        table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
    } );
</script>
<script type="">
  function pay(id,amount)
  {
    // alert(id);
    $('#payment_id').val(id); 
    $('#amount').val(amount); 
    $('#add-payment').modal('show');
  }
</script>
 <script type="">
    $('#Myform').on('submit', function(e){
       e.preventDefault();
      // CODE HERE
      current    = parseFloat($('#amount').val());
      received   = parseFloat($('#received').val());
      // alert(received);
      if(current >= received)
      {
        $('#error-messagee').text('');
        document.getElementById("Myform").submit();
        return true;
      }
      else
      {
        $('#error-messagee').text('Amount entered should be less than current credit value');
        $('#error-messagee').fadeIn().delay(1500).fadeOut(1800);
        return false;            
      }                    
    });
  </script>
</body>
</html>
