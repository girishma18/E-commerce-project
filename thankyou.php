<?php
require_once 'core/init.php';

// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

// Token is created using Checkout or Elements!
// Get the payment token ID submitted by the form:
$token = isset($_POST['stripeToken'])?sanitize($_POST['stripeToken']):'';
//var_dump($token);
//get rest of the post data
$full_name = isset($_POST['full_name'])?sanitize($_POST['full_name']):'';
$email = isset($_POST['email'])?sanitize($_POST['email']):'';
$street = isset($_POST['street'])?sanitize($_POST['street']):'';
$street2 = isset($_POST['street2'])?sanitize($_POST['street2']):'';
$city = isset($_POST['city'])?sanitize($_POST['city']):'';
$state = isset($_POST['state'])?sanitize($_POST['state']):'';
$zip_code = isset($_POST['zipcode'])?sanitize($_POST['zipcode']):'';
$country = isset($_POST['country'])?sanitize($_POST['country']):'';
$tax = isset($_POST['tax'])?sanitize($_POST['tax']):'';
$sub_total = isset($_POST['sub_total'])?sanitize($_POST['sub_total']):'';
$grand_total = isset($_POST['grand_total'])?sanitize($_POST['grand_total']):'';
$cart_id = isset($_POST['cart_id'])?sanitize($_POST['cart_id']):'';
$description = isset($_POST['description'])?sanitize($_POST['description']):'';
$charge_amount = $grand_total*100; 
$metadata = array(
"cart_id"   => $cart_id,
"tax"       => $tax,
"sub_total" => $sub_total,
);

// create the charge on stripe's servers - this will charge the user's card
try {
   $Charge = \Stripe\Charge ::create(array(
  "amount" => $charge_amount,
  "currency" => CURRENCY,
  "description" => $description,
  "source" => "tok_visa",
  "receipt_email" => $email,
  "metadata" => $metadata)
   ); 

   //ajust inventory 
   $itemq = $db->query("SELECT * FROM cart WHERE id='{$cart_id}'");
   $iresults = mysqli_fetch_assoc($itemq);
   $items = json_decode($iresults['items'],true); 
   foreach($items as $item){
     $newsizes = array();
     $item_id = $item['id'];
     $productq = $db->query("SELECT sizes FROM products WHERE id='{$item_id}'");
     $product = mysqli_fetch_assoc($productq);
     $sizes = sizestoarray($product['sizes']);
     foreach($sizes as $size){
       if($size['size']== $item['size']){ 
         $q = $size['quantity'] - $item['quantity']; var_dump($q);
         $newsizes[] = array('size'=>$size['size'],'price'=>$size['price'],'quantity'=>$q);
       } else {
         $newsizes[] = array('size'=>$size['size'],'price'=>$size['price'],'quantity'=>$size['quantity']);
       }
     }
     $sizestring = sizestostring($newsizes);
     $db->query("UPDATE products SET sizes = '{$sizestring}' WHERE id = '{$item_id}'");
   }

   //update cart
    $db->query("UPDATE cart SET paid = 1 WHERE id='{$cart_id}'");
    $db->query("INSERT INTO transactions
    (cart_id,full_name,email,street,street2,city,state,zip_code,country,sub_total,tax,grand_total,description) VALUES
    ('$cart_id','$full_name','$email','$street','$street2','$city','$state','$zip_code','$country','$sub_total','$tax','$grand_total','$description')"); 

    $domain = ($_SERVER['HTTP_HOST'] != 'localhost')? '.'.$_SERVER['HTTP_HOST']:false;
    setcookie(CART_COOKIE,'',1,"/",$domain,false);
    include 'includes/head.php'; 
    include 'includes/navigation.php';
    ?>
    <div class="container">
    <h1 class="text-center text-success"> Thank You! </h1>
    <p>Your card been successfully charged <?=$grand_total;?>. You have been emailed a receipt. Please check your spam folder if it is not in your inbox. Adittionally you can print this page as a receipt.</p>

    <p>Your receipt number is: <strong> <?=$cart_id;?> </strong> </p>
    <p>Your order will be shipped to address below:</p>
    <address>
      <?=$full_name;?> <br>
      <?=$street;?> <br>
      <?=(($street2 != '')? $street2.'<br>':'');?>
      <?=$city.', '.$state.' '.$zip_code;?> <br>
      <?=$country;?> <br>
      
    </address>
    </div>
    <?php
    include 'includes/footer.php';
}
catch(\Stripe\Error\card $e) {
  //the card has been declined
  //echo $e;  
}

?>