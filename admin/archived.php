<?php require_once $_SERVER['DOCUMENT_ROOT'].'/svdjm/core/init.php';
if(!is_logged_in()) {
   	login_error_redirect();
   }

 include 'includes/head.php';
 include 'includes/navigation.php';
   $sql = "SELECT * FROM products WHERE deleted = 1";
   $presults = $db->query($sql);
   if(isset($_GET['restore'])) {
     $id = sanitize($_GET['restore']);
     $db->query("UPDATE products SET deleted = 0 WHERE id = '$id'");
     header('Loaction: products.php');
   }
 ?>


<h2 class="text-center"> Archived Products </h2>
<hr>
 
<!--Archived products table-->
<table class="table table-bordered table-condensed table-striped">   

    <thead> 
    <th></th><th>Product</th><th>Price</th><th>Category</th><th>Sold</th>
    </thead>
    <tbody>
    <?php while($product = mysqli_fetch_assoc($presults)):
    $childid = $product['categories'];
    $catsql = "SELECT * FROM categories WHERE id = '$childid'";
    $result = $db->query($catsql);
    $child = mysqli_fetch_assoc($result);
    $parentid = $child['parent'];
    $psql = "SELECT * FROM categories WHERE id = '$parentid'";
    $presult = $db->query($psql);
    $parent = mysqli_fetch_assoc($presult);
    $category = $parent['category'].'-'.$child['category'];
    ?>
     
    <tr>
       
       <td>
        <a href="archived.php?restore=<?=$product['id'] ?>" class="btn btn-xs btn-default"> <i class="material-icons"> restore </i>  </a>
         </td>
       <td><?=$product['title'];?> </td>
       <td>Rs <?=$product['price'];?> </td>
       <td> <?=$category;?></td>
       <td>0</td>
      </tr>
     <?php endwhile; ?>
    </tbody>
   </table> 

 <?php include 'includes/footer.php'; ?>
 