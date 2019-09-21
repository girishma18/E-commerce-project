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
   $sql = "SELECT * FROM products";
   $cat_id = (($_POST['cat']!= '')? sanitize($_POST['cat']):'');
   if ($cat_id == '') {
    $sql .= ' WHERE deleted = 0';
     } else {
    $sql .= " WHERE categories = '{$cat_id}' AND deleted = 0";
    }
    $price_sort = (($_POST['price_sort'] != '') ? sanitize($_POST['price_sort']) : '');
    $min_price = (($_POST['min_price'] != '') ? sanitize($_POST['min_price']) : ''); 
    $max_price = (($_POST['max_price'] != '') ? sanitize($_POST['max_price']) : ''); 
    $brand = (($_POST['brand'] != '') ? sanitize($_POST['brand']) : '');
    if ($min_price != '') {
        $sql .= " AND price >= $min_price ";
    }
    if ($max_price != '') {
        $sql .= " AND price <= $max_price ";
    }
    if ($brand != '') {
        $sql .= " AND brand = '{$brand}'";
    }
    if ($price_sort == 'low') {
        $sql .= " ORDER BY price";
    }
    if ($price_sort == 'high') {
        $sql .= " ORDER BY price DESC";
    }
   $productq =$db->query($sql);
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