<?php 
      require_once 'core/init.php';
      include 'includes/head.php'; 
      include 'includes/navigation.php';
     if($cart_id != '') {
      $cartq = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
      $result = mysqli_fetch_assoc($cartq);
      $items = json_decode($result['items'],true); 
      $i = 1;
      $sub_total = 0;
      $item_count = 0;
      $tax = 0;
     }
      ?>
       
         <h2 class="text-center">My Shopping Cart </h2> <hr>
       <div class="container">
      <div class = "col-md-12">
        <div class="row">
          <?php if($cart_id == ''): ?>
          <div class="alert alert-danger">
            <p class="text-center text-danger"> 
               Your Shopping Cart is Empty!
            </p>
          </div>
          <?php else: ?>
           <table class="table table-bordered table-condensed table-striped">
             <thead><th>#</th><th>Item</th><th>Price</th><th>Quantity</th><th>Size</th><th>Tax Rate</th><th>Total</th></thead>
             <tbody> 
             <?php 
                 foreach($items as $item) {
                  $product_id = $item['id'];
                  $productq = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
                  $product = mysqli_fetch_assoc($productq);
                  $sarray = explode(',',$product['sizes']);
                  foreach($sarray as $sizestring) {
                    $s = explode(':',$sizestring);
                    if($s[0] == $item['size']) {
                      $available = $s[2];
                    }
                  }
                  ?>
                  <tr> 
                     <td><?=$i;?></td>
                     <td><?=$product['title'];?></td>
                     <td>Rs <?=$product['price'];?> </td>
                     <td>
                     <button class="btn btn-xs" onclick ="update_cart('removeone','<?=$product['id'];?>','<?=$item['size'];?>')"> - </button>
                     <?=$item['quantity'];?> 
                     <?php if($item['quantity'] < $available): ?>
                     <button class="btn btn-xs" onclick ="update_cart('addone','<?=$product['id'];?>','<?=$item['size'];?>')"> + </button>
                     <?php else: ?>
                      <span class="text-danger">Max Limit</span>
                     <?php endif; ?>
                     </td>
                     <td><?=$item['size'];?></td>
                     <td><?=$product['Tax'];?>%</td>
                     <td>Rs <?=$item['quantity'] * $product['price']*(1+$product['Tax']/100); ?> </td>
                  </tr>
                  <?php 
                     $i++;
                     $item_count += $item['quantity'];
                     $sub_total += $item['quantity'] * $product['price'];
                     $tax += $item['quantity'] * $product['price']* ($product['Tax']/100);
                     }
                    $grand_total = $sub_total + $tax;
                    ?>
             </tbody>
           </table>
           <table class="table table-bordered table-condensed table-striped text-center">
           <legend>Totals</legend>
          <thead><th>Total Items</th><th>Sub Total</th><th>Tax</th><th>Grand Total</th> </thead>
          <tbody>
            <tr>
            <td><?=$item_count;?></td>
            <td>Rs <?=$sub_total;?></td>
            <td>Rs <?=$tax;?></td>
            <td class="alert alert-success">Rs <?=$grand_total?></td>
            </tr>
          <tbody>
           </table>
<!--Checkout Button-->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#checkoutModal">
  <i class="material-icons"> shopping_cart </i> Check Out >>
</button>

<!-- Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="checkoutModalLabel">Shipping Address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <span id="payment-errors"> </span>

        <form action="thankyou.php" method="post" id="payment-form">

        <input type="hidden" name="tax" value="<?=$tax;?>">
        <input type="hidden" name="sub_total" value="<?=$sub_total;?>">
        <input type="hidden" name="grand_total" value="<?=$grand_total;?>">
        <input type="hidden" name="cart_id" value="<?=$cart_id;?>">
        <input type="hidden" name="description" value="<?=$item_count.' item'.(($item_count>1)?'s':'').' from SVDGM.';?>">

  
       <div id="step1" style="display:block;">
        <div class="row">
          
        <div class="form-group col-md-6">
          <label for="full_name">Full Name: </label>
          <input class="form-control" id="full_name" name="full_name" type="text" >
        </div>
        <div class="form-group col-md-6">
          <label for="email">Email: </label>
          <input class="form-control" id="email" name="email" type="email">
        </div>
        <div class="form-group col-md-6">
          <label for="street">Street Address: </label>
          <input class="form-control" id="street" name="street" type="text" data-stripe="address_line1">
        </div>
        <div class="form-group col-md-6">
          <label for="street2">Street Address 2: </label>
          <input class="form-control" id="street2" name="street2" type="text" data-stripe="address_line2">
        </div>
        <div class="form-group col-md-6">
          <label for="city">City: </label>
          <input class="form-control" id="city" name="city" type="text" data-stripe="address_city">
        </div>
        <div class="form-group col-md-6">
          <label for="state">State: </label>
          <input class="form-control" id="state" name="state" type="text" data-stripe="address_state">
        </div>
        <div class="form-group col-md-6">
          <label for="zipcode">Zipcode: </label>
          <input class="form-control" id="zipcode" name="zipcode" type="text" data-stripe="address_zip">
        </div>
        <div class="form-group col-md-6">
          <label for="country">Country: </label>
          <input class="form-control" id="country" name="country" type="text" data-stripe="address_country">
        </div>
       </div>
      </div>
       <div id="step2" style="display:none;">
       <div class="row">
       <div class="form-group col-md-3" > 
          <label for="name"> Name on Card: <label>
          <input class="form-control" id="name" type="text" data-stripe="name">
       </div>
       <div class="form-group col-md-3"> 
          <label for="number"> Card no: <label>
          <input class="form-control" id="number" type="text" data-stripe="number">
       </div>
       <div class="form-group col-md-2"> 
          <label for="cvc"> CVC: <label>
          <input class="form-control" id="cvc" type="text" data-stripe="cvc">
       </div>
       <div class="form-group col-md-2"> 
          <label for="exp-month"> Expire Month: <label>
          <select id="exp-month" class="form-control"data-stripe="exp_month" >
          <option value=""> </option>
          <?php for($i=1;$i<13;$i++):?>
          <option value="<?=$i;?>"><?=$i;?> </option>
          <?php endfor; ?>
          </select>
       </div>
       <div class="form-group col-md-2"> 
          <label for="exp-year"> Expire Year: <label>
          <select id="exp-year" class="form-control" data-stripe="exp_year">
          <option value=""> </option>
          <?php $yr = date("Y"); ?>
          <?php for($i=0;$i<11;$i++):?>
          <option value="<?=$yr + $i;?>"><?=$yr + $i;?> </option>
          <?php endfor; ?>
          </select>
       </div>
       </div>
      </div>
      
       </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="check_address()" id="next-button">Next >> </button>
        <button type="button" class="btn btn-secondary" onclick="back_address();" id="back-button" style="display:none;"> << Back</button>
        <button type="submit" class="btn btn-primary" id="checkout_button" style="display:none;">Check Out </button> 
        </form>
      </div>
    </div>
  </div>
</div>
          <?php endif; ?>
        </div>
      </div>
     </div>
     
<script>
function back_address(){
              jQuery('#payment-errors').html("");
            jQuery('#step1').css("display","block");
            jQuery('#step2').css("display","none");
            jQuery('#next-button').css("display","inline-block");
            jQuery('#back-button').css("display","none");
            jQuery('#checkout_button').css("display","none");
            jQuery('#checkoutModalLabel').html("Shipping Address");
}
 function check_address(){
  var data = {
    'full_name' : jQuery('#full_name').val(),
    'email' : jQuery('#email').val(),
    'street' : jQuery('#street').val(),
    'street2' : jQuery('#street2').val(),
    'city' : jQuery('#city').val(),
    'state' : jQuery('#state').val(),
    'zipcode' : jQuery('#zipcode').val(),
    'country' : jQuery('#country').val(),
   };
   jQuery.ajax({
    url : '/svdjm/admin/parsers/check_address.php',
        method: 'post',
        data : data,
        success : function(data) {
          if(data!= 'passed') {
            jQuery('#payment-errors').html(data);
            
          }
          if(data == 'passed') { 
            jQuery('#payment-errors').html("");
            jQuery('#step1').css("display","none");
            jQuery('#step2').css("display","block");
            jQuery('#next-button').css("display","none");
            jQuery('#back-button').css("display","inline-block");
            jQuery('#checkout_button').css("display","inline-block");
            jQuery('#checkoutModalLabel').html("Card Details");
              }
        },
        error : function() {alert("something went wrong");},
   })
        
 }

 Stripe.setpublishablekey('<?=STRIPE_PUBLIC;?>');

 function stripeResponseHandler(status, response) {

  // Grab the form:
  var $form = $('#payment-form');

  if (response.error) { // Problem!

    // Show the errors on the form
    $form.find('#payment-errors').text(response.error.message);
    $form.find('button').prop('disabled', false); // Re-enable submission

  } else { // Token was created!

    // Get the token ID:
    var token = response.id;

    // Insert the token into the form so it gets submitted to the server:
    $form.append($('<input type="hidden" name="stripeToken" />').val(token));

    // Submit the form:
    $form.get(0).submit();

  }
}

 jQuery(function($) {
   $('#payment-form').submit(function(event) {
     var $form= $(this);

     // Disable the submit button to prevent repeated clicks
     $form.find('button').prop('disabled',true);

     Stripe.card.createToken($form , stripeResponseHandler);

     //prevent the form from submitting with the default action

     return false;
   });
 });
</script>

<?php include 'includes/footer.php'; ?>