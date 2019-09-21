<br>
<div class="container">
 <?php 
 $sql= "SELECT * FROM categories WHERE parent=0";
 $pquery= $db->query($sql) ?>
 
  
  <?php 
   while($parent = mysqli_fetch_assoc($pquery)): ?>
   <?php $parent_id= $parent['id'];
         $sql2= "SELECT * FROM categories WHERE parent= '$parent_id'";
         $cquery = $db->query($sql2); ?>
    <div class="btn-group">
    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
     <?php echo $parent['category']; ?>
     </button>
    <div class="dropdown-menu">
    <?php while($child = mysqli_fetch_assoc($cquery)): ?>
    <a class="dropdown-item" href="category.php?cat=<?=$child['id'];?>"> <?php echo $child['category']; ?> </a>
    <?php endwhile; ?>
  </div> 
  </div>
<?php endwhile; ?>
  
</div>
<br><br>