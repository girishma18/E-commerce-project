<?php 
      require_once 'core/init.php';
      include 'includes/head.php'; 
      include 'includes/navigation.php';
      ?>
       <link rel="stylesheet" href="../svdjm/css/main.css"/>
     <br>  
 <div class="container">
     <h2 class="text-center"> FEATURED PRODUCTS <h2>
  <div>
  <hr>
<?php $sql= "SELECT * FROM products WHERE featured=1";
   $featured=$db->query($sql); 
   
   include 'includes/sort of products.php';
   include 'includes/leftbar.php';
   ?>


<div class="col-md-8">
 <div class="row">
  
  
   <?php while($product = mysqli_fetch_assoc($featured)): ?>
   <div class="col-md-4">
    <h5 class="text-center"> <?= $product['title']; ?> </h5>
    <image src="<?= $product['image']; ?>"  alt="<?= $product['title']; ?>" class="img-th"/>
    <h5 class="price"> price: Rs <?= $product['price']; ?> </h5>
    <h5> <?= $product['Tax']; ?>% Tax</h5>
    <h5> Qty: <?= $product['qty']; ?> kgs</h5>
    <button type="button" class="btn btn-sm btn-success" onclick= "detailsmodal( <?= $product['id']; ?> )" > details </button> 
  </div>
  <?php endwhile; ?>
  </div>
 </div>




<?php include 'includes/rightbar.php';
   include 'includes/footer.php'; ?>