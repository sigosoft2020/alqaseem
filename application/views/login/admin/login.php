<!DOCTYPE html>
<html lang="en">
<head>
  <title>Al Qaseem | Login</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="<?=base_url()?>login_assets/images/icons/favicon.png"/>
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>login_assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>login_assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>login_assets/vendor/animate/animate.css">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>login_assets/vendor/css-hamburgers/hamburgers.min.css">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>login_assets/vendor/select2/select2.min.css">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>login_assets/css/util.css">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>login_assets/css/main.css">
 <link href="<?=base_url()?>plugins/toaster/toaster.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <div class="limiter">
    <div class="container-login100">
      <div class="wrap-login100">
        <div class="login100-pic js-tilt" data-tilt>
          <img src="<?=base_url()?>login_assets/images/img-01.png" alt="IMG">
        </div>

        <form class="login100-form validate-form" action="<?=site_url('users/adminLogin')?>" method="post">
          <span class="login100-form-title">Admin Login</span>

          <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
            <input class="input100" type="text" name="username" placeholder="Username" required>
            <span class="focus-input100"></span>
            <span class="symbol-input100">
              <i class="fa fa-user" aria-hidden="true"></i>
            </span>
          </div>

          <div class="wrap-input100 validate-input" data-validate = "Password is required">
            <input class="input100" type="password" name="password" placeholder="Password" required>
            <span class="focus-input100"></span>
            <span class="symbol-input100">
              <i class="fa fa-lock" aria-hidden="true"></i>
            </span>
          </div>
          
          <div class="container-login100-form-btn">
            <button class="login100-form-btn" type="submit">Login</button>
          </div>

        </form>
      </div>
    </div>
  </div>
  
  <script src="<?=base_url()?>login_assets/vendor/jquery/jquery-3.2.1.min.js"></script>
  <script src="<?=base_url()?>login_assets/vendor/bootstrap/js/popper.js"></script>
  <script src="<?=base_url()?>login_assets/vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?=base_url()?>login_assets/vendor/select2/select2.min.js"></script>
  <script src="<?=base_url()?>login_assets/vendor/tilt/tilt.jquery.min.js"></script>
  <script >
    $('.js-tilt').tilt({
      scale: 1.1
    })
  </script>
  <script src="<?=base_url()?>login_assets/js/main.js"></script>

  <script src="<?=base_url()?>plugins/toaster/toaster.min.js"></script>
  <script type="text/javascript">
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-full-width",
    "preventDuplicates": false,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }
  toastr.<?=$this->session->flashdata('alert_type')?>("<?=$this->session->flashdata('alert_message')?>","<?php echo $this->session->flashdata('alert_title')?>");
  </script>
</body>
</html>