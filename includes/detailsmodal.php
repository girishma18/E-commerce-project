<?php 
require_once '../core/init.php';
 $id= $_POST['id'];
 $id= (int)$id;
 $sql= "SELECT * FROM products Where id='$id'";
 $result= $db->query($sql);
 $product= mysqli_fetch_assoc($result);
 $sizestring= $product['sizes'];
 $size_array=explode(',',$sizestring);
?>
       

<?php ob_start(); ?>
 <div class="modal fade" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" > <?= $product['title']?>  </h5>
        <button type="button" class="close" onclick="closemodal()"  aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
       <div class="container-fluid">
          <span id="modal_errors" > </span> 
        
          <div class="row">

           <div class="col-md-6">
            <div class="center-block"> 
            <img src=" <?= $product['image']?>" alt=" <?= $product['title']?>" class="details img-thum"> 
           </div>
          </div>

          <div class="col-md-6">
           <h4> Description </h4>
           <font size="3">  <?= $product['description']?> </font>
           <hr>
           <font size="4"> price: Rs  <?= $product['price']?> </font>
           <br>
           <font size="3">  <?= $product['Tax']?>% Tax </font>

           <form action="add_cart.php" method="post" id="add_product_form" >
            <input type = "hidden" name = "product_id"  id = "<?=$id;?>" value = "<?=$id;?>" >
            <input type = "hidden" name = "available" id = "available" value = "">
            <div class="form-group">
            <div class=col-xs-3> 
            <label for="quantity"> <font size="4"> Quantity:  </font> </label>
            <input type="number" min=1 class="form-control" id="quantity" name="quantity" > 
            </div> <br>
            <div class="form-group">
            <label for="size"> <font size="4"> Size: </font> </label>
            <select name ="size" id="size" class="form-control">
            <option value=""></option>
            <?php foreach($size_array as $string) {
             $string_array= explode(':',$string);
             $size= $string_array[0];
             $price= $string_array[1];
             $available= $string_array[2];
             if($available > 0){
             echo '<option vlaue="'.$size.'" data-available="'.$available.'">' .$size. ' </option>';
               }
              } ?>
            </select>
            </div>
            </form>
            </div>
          </div>
         </div>
      </div>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closemodal()" >Close</button>
        <button class="btn btn-warning" onclick = "add_to_cart();return false;" > <i class="material-icons"> shopping_cart </i> Add To Cart </button> 
        </div>

    </div>
  </div>
</div>

<script> 
jQuery('#size').change(function(){
 var available = jQuery('#size option:selected').data("available");
 jQuery('#available').val(available);
});
function closemodal()
{
  jQuery('#details-modal').modal('hide');
  setTimeout(function(){
    jQuery('#details-modal').remove();
    jQuery('.modal-backdrop').remove();
  },500);
}
</script>

<?php echo ob_get_clean(); ?>