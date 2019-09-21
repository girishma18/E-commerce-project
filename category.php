<?php include 'includes/head.php'; 
      include 'includes/navigation.php';
      require_once 'core/init.php';
      include 'includes/sort of products.php';
      include 'includes/leftbar.php';

      ?>
       <link rel="stylesheet" href="../svdjm/css/main.css"/>
       <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
 
<?php 
 if(isset($_GET['cat'])){
    $cat_id = sanitize($_GET['cat']);
	}else{
		$cat_id = '';
	}
   $sql= "SELECT * FROM products WHERE categories = '$cat_id'";
   $productq=$db->query($sql);
   $category = get_category($cat_id);
    ?>
  
<div class="col-md-8">
 <div class="row">
  
  
   <?php while($product = mysqli_fetch_assoc($productq)): ?>
   <div class="col-md-4">
    <h4 > <?= $product['title']; ?> </h4>
    <image src="<?= $product['image']; ?>"  alt="<?= $product['title']; ?>" class="img-th"/>
    <h5 class="price"> price: Rs <?= $product['price']; ?> </h5>
    <h5> <?= $product['Tax']; ?>% Tax</h5>
    <h5> Qty: <?= $product['qty']; ?> kgs</h5>
    <button type="button" class="btn btn-sm btn-success" onclick= "detailsmodal( <?= $product['id']; ?> )" > details </button>
  </div>
  

  <?php endwhile; ?>
  </div>
 </div>

<!-- Modal -->


<?php include 'includes/rightbar.php';
     include 'includes/footer.php'; ?>