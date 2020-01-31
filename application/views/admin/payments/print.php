<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>invoice </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
    .invoice-box {
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: rignt;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(3) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
        .text-center{text-align: center;}
        .text-right{text-align: right;}
        .text-left{text-align: left;}
        body { margin: 0px;padding: 0;font: 12pt "Tahoma"; }
    * {box-sizing: border-box;-moz-box-sizing: border-box;}
    .main{padding: 15px;}
    .row {margin-left:0; margin-right:0;}
    .row:before,
    .row:after{
        display: table;content: " ";}
    .row:after {clear: both;}
    .col-md-12 {width: 100%; float: left; padding: 0 10px;}
    .col-md-8 {width: 66.66%; float: left; padding: 0 10px;}
    .col-md-7 {width: 58.33333333%; float: left; padding: 0 10px;}
    .col-md-6 {width: 50%; float: left; padding: 0 10px;}
    .col-md-5 {width: 41.66666667%; float: left; padding: 0 10px;}
    .col-md-4 {width: 33.33%; float: left; padding: 0 10px;}
    .col-md-3 {width: 25%; float: left; padding: 0 10px;}
    .fr{float: right;}
    .fl{float: left;}
    .fwn{font-weight: normal;}
    .content h4{ font-size: 13px; margin: 6px 0;}
    .content tr{font-size: 13px;}
    .content td{font-size: 13px; text-align: center;}
    .header{text-align: center;}
    .header img{ width: 200px; margin: auto;}
    .header p{margin-top: 7px;}
    .text-center{text-align: center;}
    table, th border-collapse: collapse;}
    table, td{ border-collapse: collapse;}
    th, td {padding: 5px;}
    .rr p{ font-size: 12px;}
</style>
<style type="text/css" media="print">
@page {
    size: auto;
    margin: 29px; 
}
</style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
                <td colspan="5">
                    <table class="row">
                        <tr>
                            <td class="title col-md-3">
                                <img src="<?=base_url()?>login_assets/images/icons/favicon.png" style="width:50%; max-width:50px;">
                            </td>
                            
                            <td class="col-md-6 text-right">
                                <h4>Invoice No : #INVPY<?php echo $history->ph_id;?></h4>
                                <h4>Date: <?php echo date('d-M-Y',strtotime($history->timestamp));?></h4>
                            </td>
                        </tr>
                    </table>
                </td>
            
            <tr class="information">
                <td colspan="7">
                    <table>
                        <tr>                       
                             <td>
                                  Al Qaseem<br>
                                  009998877 <br>
                             </td>
                             <td>
                                 <?php echo $history->customer_name;?><br>
                                 <?php echo $history->customer_phone;?><br>
                            </td> 
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
              <td colspan="12">
                <tfoot>               
                    <tr>      
                      <td><b>AMOUNT PAID </b></td>
                      
                      <td><b>AED <?=$history->amount_paid?></b></td>
                    </tr>
                    <?php if($history->balance_amount!='0') {?>
                      <tr>
                        <td><b>CREDIT AMOUNT</b></td>
                       
                        <td><b>AED <?=$history->balance_amount?></b></td>
                      </tr>
                    <?php };?>
                </tfoot> 
              </td>
            </tr>
        </table>   
    </div>

<script type="text/javascript"> 
  window.addEventListener("load", window.print());
</script>

</body>
</html>