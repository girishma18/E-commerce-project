<?php 
   require_once '../core/init.php';
 if(!is_logged_in()) {
    login_error_redirect();
   }
   include 'includes/head.php';
   include 'includes/navigation.php';

   //Delete product
   if(isset($_GET['delete'])) {
     $id = sanitize($_GET['delete']);
     $db->query("UPDATE products SET deleted = 1,featured = 0 WHERE id = '$id'");
      header('Loaction: products.php');
   }
   $dbpath = '';
   if(isset($_GET['add']) || isset($_GET['edit'])) {
   $brandquery = $db->query("SELECT * FROM brand ORDER BY brand");
   $parentquery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category ");
   $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
   $brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):'');
   $parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):'');
   $category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):'');
   $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
   $tax = ((isset($_POST['tax']) && $_POST['tax'] != '')?sanitize($_POST['tax']):'');
   $qty = ((isset($_POST['bag_size']) && $_POST['bag_size'] != '')?sanitize($_POST['bag_size']):'');
   $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
   $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):'');
   $sizes = rtrim($sizes,',');
   $saved_image = '';
   
    if(isset($_GET['edit'])&& !empty($_GET['edit'])) {
    $edit_id= (int)$_GET['edit'];
    $edit_id= sanitize($edit_id);
    $productresults=$db->query("SELECT * FROM products WHERE id='$edit_id'");
    $product = mysqli_fetch_assoc($productresults);
    if(isset($_GET['delete_image'])){
      $image_url = $_SERVER['DOCUMENT_ROOT'].$product['image']; 
      unlink($image_url);
      $db->query("UPDATE products SET image = '' WHERE id = '$edit_id'");
      header('Loaction: products.php?edit='.$edit_id);
     
    }
    $category = ((isset($_POST['child']) && $_POST['child']!= '')?sanitize($_POST['child']): $product['categories']);
    $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$product['title']);
    $brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):$product['brand']);
    $parentq = $db->query("SELECT * FROM categories WHERE id = '$category'");
    $parentresult = mysqli_fetch_assoc($parentq); 
    $parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):$parentresult['parent']);
    $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$product['price']);
    $tax = ((isset($_POST['tax']) && $_POST['tax'] != '')?sanitize($_POST['tax']):$product['Tax']);
    $qty = ((isset($_POST['bag_size']) && $_POST['bag_size'] != '')?sanitize($_POST['bag_size']):$product['qty']);
    $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):$product['description']);
    $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):$product['sizes']);
    $sizes = rtrim($sizes,',');
    $saved_image = (($product['image']!= '')?$product['image']:'');
    $dbpath = $saved_image;
     }

      if(!empty($sizes)) {
       $sizestring = sanitize($sizes);
       $sizestring = rtrim($sizestring,',');
       $sizesarray = explode(',',$sizestring);
       $size = array();
       $prices = array();
       $quantity = array();
        foreach($sizesarray as $string) {
             $string_array= explode(':',$string);
             $size[]= $string_array[0];
             $prices[]= $string_array[1];
             $quantity[]= $string_array[2];
              } 
     }else{$sizesarray = array();}
   
   if($_POST) {
    
     //if(isset($_POST['child'])){
     //$categories=sanitize($_POST['child']);
     //}
    $errors = array();
     $required = array('title', 'brand', 'price', 'parent','child', 'sizes','tax','bag_size');
     foreach($required as $field) {
       if($_POST[$field] == '') {
         $errors[] = 'ALL fields with and asterick are required.';
         break;
       }
     }
     if($_FILES['photo']['name']!='') {
       $photo = $_FILES['photo'];
       $name = $photo['name'];
       $namesarray = array_pad(explode('.',$name),2,0);
       $filename = $namesarray[0];
       $fileext = $namesarray[1];
       $mime = array_pad(explode('/',$photo['type']),2,0);
       $mimetype = $mime[0];
       $mimeext = $mime[1];
       $temploc = $photo['tmp_name'];
       $filesize = $photo['size'];
       $allowed = array('png','jpg','jpeg','gif');
       $uploadname = md5(microtime()).'.'.$fileext;
       $uploadpath = BASE.'/img/'.$uploadname;
       $dbpath = '/img/'.$uploadname;
       if($mimetype!= 'image') {
         $errors[]= 'The file must be an image.';
       }
       if(!in_array($fileext, $allowed)) {
         $errors[] = 'The file extension must be png,jpg,jpeg or gif.';
       }
      if($filesize > 15000000) {
         $errors[]= 'The file size must be under 15MB.';
       }
       if($fileext != $mimeext && ($mimeext == 'jpeg' && $fileext != 'jpg')) {
          $errors[] = 'File extension does not match the file';
       }
     }
     if(!empty($errors)) {
       echo display_errors($errors);
     }else{
       //upload file and insert into database
       if(!empty($_FILES)) {
       move_uploaded_file($temploc,$uploadpath);
     }
       $insertsql = "INSERT INTO products(title,price,brand,categories,sizes,image,Tax,qty,description)
       VALUES('$title','$price','$brand','$category','$sizes','$dbpath','$tax','$qty','$description')";
       if(isset($_GET['edit'])) {
         $insertsql = "UPDATE products SET title = '$title',price = '$price',brand = '$brand',categories = '$category',sizes='$sizes',image='$dbpath',Tax='$tax',qty = '$qty',description = '$description' WHERE id = '$edit_id'"; 
       }
       $db->query($insertsql);
       header('Location: products.php');
     }
   }
   ?>
  <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add a New');  ?> Product</h2> <hr> 
   <form action = "products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="POST" enctype = "multipart/form-data">
   <div class="container-fluid">
     <div class="row">
     <div class="form-group col-md-3"> 
      <label for="title">Title*:</label>
      <input type="text" name="title" class="form-control" id="title" value="<?=$title?>">
     </div>
     <div class="form-group col-md-3"> 
      <label for="brand">Brand*:</label>
     <select class="form-control" id="brand" name="brand">
       <option value=""<?=(($brand == '')?'selected':'');?>></option>
       <?php while($b = mysqli_fetch_assoc($brandquery)): ?>
       <option value="<?= $b['id'];?>"<?=(($brand == $b['id'])?'selected':'');?> > <?=$b['brand'];?></option>
       <?php endwhile; ?>
     </select>
     </div>
     <div class="form-group col-md-3">
       <label for="parent"> Parent Category*:</label>
       <select class="form-control" id="parent" name="parent"> 
       <option value=""<?=(($parent == '')?'selected':'');?>></option>       
       <?php while($p= mysqli_fetch_assoc($parentquery)):?>
       <option value="<?=$p['id'];?>"<?=(($parent == $p['id'])?'selected':'');?> > <?=$p['category'];?></option>
       <?php endwhile; ?>
       </select>
    </div>
     <div class="form-group col-md-3">
       <label for="child"> child category*:</label>
        <select class="form-control" id="child" name="child"> 
       </select>
    </div>
    <div class="form-group col-md-3">
       <label for="price"> price*:</label>
        <input type="text" class="form-control" id="price" name="price" value="<?=$price?>"> 
       </select>
    </div>
    <div class="form-group col-md-3">
       <label> Brand price & Qty*:</label>
        <button class="btn btn-primary form-control" onclick="jQuery('#sizesmodal').modal('toggle');return false;">Brand price & Qty</button>
    </div>
    <div class="form-group col-md-3">
       <label for="sizes"> Brand price & Qty Preview*:</label>
        <input type="text" id="sizes" class="form-control" name="sizes" value="<?=$sizes;?>" readonly> 
    </div>
     <div class="form-group col-md-3">
       <label for="bag_size">Bag size*:</label>
        <input type="text" id="bag size" class="form-control" name="bag size" value="<?=$qty;?>"> 
    </div>
    <div class="form-group col-md-3">
       <label for="tax">Tax*:</label>
        <input type="text" id="tax" class="form-control" name="tax" value="<?=$tax;?>"> 
    </div>
     <div class="form-group col-md-3">
      <?php if($saved_image != ''):?>
      <div class="saved-image">
       <img src="<?=$saved_image;?>" alt="saved image" class="img-thum"> <br>
      <a href= "products.php?delete_image=1&edit=<?=$edit_id;?>" class="text-danger">Delete Image </a>
       </div>
     <?php else: ?>
       <label for="photo">Product Photo:</label>
        <input type="file" id="photo" class="form-control" name="photo"> 
      <?php endif; ?>
    </div>
     <div class="form-group col-md-6">
       <label for="description">Description:</label>
        <textarea type="text" id="description" class="form-control" name="description" rows="6" > <?=$description;?>   </textarea> 
    </div>
    </div>
    <div class="form-group col-md-3">
    <a href="products.php" class="btn btn-default">Cancel</a>
        <input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');  ?> Product" class=" btn btn-success"> 
    </div> 
    </div>
    </div>
    </form>

<!-- Modal -->
<div class="modal fade" id="sizesmodal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sizesModalLabel">Brand Price & qty</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php for($i=1;$i<=12;$i++):?>
        <div class="row">   
        <div class="form-group col-md-4"> 
        <label for="size<?=$i;?>"> Brand: </label>
        <input type="text" value="<?=((!empty($size[$i-1]))?$size[$i-1]:'');?>" name="size<?=$i;?>" id="size<?=$i;?>" class="form-control"> 
        </div>

        <div class="form-group col-md-4"> 
        <label for="eprice<?=$i;?>"> Threshold: </label>
        <input type="text" value="<?=((!empty($prices[$i-1]))?$prices[$i-1]:'');?>" name="eprice<?=$i;?>" id="eprice<?=$i;?>" class="form-control"> 
        </div>

        <div class="form-group col-md-4"> 
        <label for="qty<?=$i;?>"> Quantity: </label>
        <input type="number" value="<?=((!empty($quantity[$i-1]))?$quantity[$i-1]:'');?>" min="0" name="qty<?=$i;?>" id="qty<?=$i;?>" class="form-control">
        </div>
        </div>
        <?php endfor; ?>
       </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updatesizes(); jQuery('#sizesmodal').modal('toggle');return false;">Save changes</button>
      </div>
    </div>
  </div>
</div>
   <?php }else {
   $sql = "SELECT * FROM products WHERE deleted = 0";
   $presults = $db->query($sql);
   if(isset($_GET['featured'])){
   	$id = (int)$_GET['id'];
   	$featured = (int)$_GET['featured'];
   	$featuredsql = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
   	$db->query($featuredsql);
   	header('Location: products.php');
   }
   ?>
<h2 class="text-center"> Products </h2> <hr> 
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add product </a>
<div class="clearfix"> </div>
 <table class="table table-bordered table-condensed table-striped">   

    <thead> 
    <th></th><th>Product</th><th>Price</th><th>Category</th><th>Featured</th><th>Sold</th>
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
        <a href="products.php?edit=<?=$product['id'] ?>" class="btn btn-xs btn-default"> <i class="material-icons"> create </i>  </a>
        <a href="products.php?delete=<?=$product['id'] ?>" class="btn btn-xs btn-default"> <i class="material-icons"> clear </i>  </a>
         </td>
       <td><?=$product['title'];?> </td>
       <td>Rs <?=$product['price'];?> </td>
       <td> <?=$category;?></td>
       <td><a href="products.php?featured=<?=(($product['featured'] == 0)?'1':'0');?>&id=<?=$product['id'];?>" class="btn btn-xs btn-default">
       <i class="material-icons"> <?=(($product['featured'] == 0)?'add':'remove');?></i>
       &nbsp <?=(($product['featured'] == 1)?'Featured product':'');?>
       </a>  </td>
       <td>0</td>
      </tr>
     <?php endwhile; ?>
    </tbody>
   </table> 
 <?php }include 'includes/footer.php'; ?>
 <script> 
  jQuery('document').ready(function(){
   get_child_options('<?=$category;?>');
  });
 </script>