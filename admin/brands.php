<?php 
   require_once '../core/init.php';
   if(!is_logged_in()) {
    login_error_redirect();
   }

   include 'includes/head.php';
   include 'includes/navigation.php';
  //get get brands from database
  $sql= "SELECT * FROM brand ORDER BY brand";
  $results= $db->query($sql);
  $errors= array();

  //edit brand
  if(isset($_GET['edit']) && !empty($_GET['edit'])){
      $edit_id= (int)$_GET['edit'];
      $edit_id= sanitize($edit_id);
      $sql2 = "SELECT * FROM brand WHERE id= '$edit_id'";
      $edit_result= $db->query($sql2);
      $ebrand = mysqli_fetch_assoc($edit_result);
   	}

  //delete brand
   
   	if(isset($_GET['remove']) && !empty($_GET['remove'])){
      $delete_id= (int)$_GET['remove'];
      $delete_id= sanitize($delete_id);
      $sql = "DELETE FROM brand WHERE id= '$delete_id'";
      $db->query($sql);
      header('Location: brands.php');
   	}
   
  //if add form is submitted
  if(isset($_POST['add_submit'])){
  	$brand= sanitize($_POST['brand']); // sanitize helps by not converting brand name to html entities
  	//check if brand is blank
  	if($_POST['brand']=='') {
       $errors[].= 'you must enter a brand!';
	  	}
	  	//check if brand exists in database
	  	$sql = "SELECT * FROM brand WHERE brand ='$brand' ";
	  	if(isset($_GET['edit'])) {
	  		$sql= "SELECT * FROM brand WHERE brand ='$brand' AND id != '$edit_id'  " ;
	  	}
	  	$result = $db->query($sql);
	  	$count = mysqli_num_rows($result);
	  	if($count>0){
	  		$errors[] .= $brand. ' already exists. please choose another brand name';
	  	}
	  	//display errors
	  	 if(!empty($errors)) {
	  		echo display_errors($errors);
	  	  } else {
	  		//add brand to database
            $sql = "INSERT INTO brand (brand) VALUES('$brand')";
            if(isset($_GET['edit'])) {
            	$sql="UPDATE brand SET brand = '$brand' WHERE id= '$edit_id '";
            }
            $db->query($sql);
            header('Location: brands.php');
	  	}
  }
 ?>

<h2 class="text-center"> brands</h2> <hr>
<!--brand form-->

 <form class="form-inline" action="brands.php <?= ((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" method="post">

  <div class="form-group">
   <?php 
   $brand_value='';
   if(isset($_GET['edit'])) {
   	$brand_value= $ebrand['brand'];
   } else {
   	if(isset($_POST['brand'])) {
   		$brand_value = sanitize($_POST['brand']);
   	}
   } ?>

    <label for="brand" > <?=((isset($_GET['edit']))?'Edit':'Add a')  ?> Brand: </label>
    <input type="text" name="brand" id="brand" class="form-control" value="<?=$brand_value;?>" >
    <?php if(isset($_GET['edit'])): ?>
      <a href="brands.php" class="btn btn-default"> cancel </a>
    
    <?php endif;  ?>
    <input type="submit" name="add_submit" class="btn btn-success " value="<?=((isset($_GET['edit']))?'Edit':'Add ')  ?> Brand " >
  </div>
 </form>
</div>
<hr>

 <table class="table table-bodered table-striped" style="width:auto; margin:0 auto;">
  <thead> 
    <th></th><th>brands</th><th></th>
  </thead>
  <tbody> 
   <?php while($brand= mysqli_fetch_assoc($results) ):?>
   <tr>
    <td><a href="brands.php?edit=<?= $brand['id'] ?>" class="btn btn-xs btn-default">  <i class="material-icons"> create </i>   </a></td>
    <td> <?= $brand['brand'] ?></td>
    <td><a href="brands.php?remove=<?= $brand['id'] ?>" class="btn btn-xs btn-default">  <i class="material-icons"> clear </i>   </a></td>
  </tr>
  <?php endwhile; ?>
</tbody>
 </table>

 <?php include 'includes/footer.php'; ?>