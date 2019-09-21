
         
  <nav class="navbar navbar-expand-lg bg-dark navbar-dark">        
  <div class="container">         
      
  <a class="navbar-brand" href="/svdjm/admin/index.php">SVDGM Admin</a>
  
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu"  aria-expanded="false" >
    <span class="navbar-toggler-icon"></span>
  </button> 

  <div class="collapse navbar-collapse" id="main-menu">
    <ul class="nav navbar-nav">
      <li class="nav-item"> <a class="nav-link" href="brands.php"> Brands </a> </li>
      <li class="nav-item"> <a  class="nav-link" href="categories.php"> categories </a> </li>
      <li class="nav-item"> <a  class="nav-link" href="products.php"> products </a> </li>
      <li class="nav-item"> <a  class="nav-link" href="archived.php"> Archived </a> </li>
      <?php if(has_permission('admin')):?>
         <li class="nav-item"> <a  class="nav-link" href="users.php"> Users </a> </li>
      <?php endif;  ?>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?=$user_data['first'];?>!
        <span class="caret"></span>
       </a>
       <ul class="dropdown-menu" role="menu">
        <li> <a href = "change_password.php">Change Password</a> </li>
        <li> <a href = "logout.php">Logout</a> </li>

        </ul>
      </li>
      <!--<li class="nav-item">
        <a class="nav-link" href="shop.php">SHOP NOW</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="contact.php">CONTACT US</a>
      </li> -->
    </ul>
  </div>
</nav>
 
</div>