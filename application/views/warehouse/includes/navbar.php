 <?php $user = $this->session->userdata['warehouse']; ?>
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?=site_url('admin/dashboard')?>" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
       <li class="nav-item dropdown">
         <li class="nav-item">
          <a href="<?=site_url('users/warehouseLogout')?>">
            <i class="fas fa-power-off"> Logout</i>
          </a>
        </li>      
      </li>
    </ul>
  </nav>