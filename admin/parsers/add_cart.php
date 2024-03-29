<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/svdjm/core/init.php';
$product_id = isset($_POST['product_id'])?sanitize($_POST['product_id']):''; 
$size = isset($_POST['size'])?sanitize($_POST['size']):''; 
$available = isset($_POST['available'])?sanitize($_POST['available']):'';
$quantity = isset($_POST['quantity'])?sanitize($_POST['quantity']):'';
$item = array();
$item[] = array(
'id'       => $product_id,
'size'     => $size,
'quantity' => $quantity,
); 
$domain = ($_SERVER['HTTP_HOST']!= 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
$query = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
$product = mysqli_fetch_assoc($query);
$_SESSION['success_flash'] = $product['title']. ' is added to your cart.';

//check to see if the cookie exists
if($cart_id!= ''){
$cartq = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $cart = mysqli_fetch_assoc($cartq);
    //var_dump($cart);    
    $previous_items = json_decode($cart['items'],true); //var_dump($previous_items);
    $item_match = 0;
    $new_items = array();
    foreach($previous_items as $pitem) {
    	if($item[0]['id'] == $pitem['id'] && $item[0]['size'] == $pitem['size']) {
    		$pitem['quantity'] = $pitem['quantity'] + $item[0]['quantity'];
    		//var_dump($pitem);
    		if($pitem['quantity'] > $available) {
    			$pitem['quantity'] = $available;	
    	}
    		$item_match = 1; 
    	}
    	$new_items[] = $pitem;
    }
    if($item_match != 1) {
    	$new_items = array_merge($item , $previous_items);
    }
	$items_json = json_encode($new_items);
	$cart_expire = date("Y-m-d H:i:s",strtotime("+30 days"));
	$db->query("UPDATE cart SET items = '{$items_json}',expire_date = '{$cart_expire}',paid = '0' WHERE id = '{$cart_id}'");
	setcookie(CART_COOKIE,'',1,"/",$domain,false); //unset cookie
	setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false); //set cookie
	
}else{                                                                                                                                                                                                                                       
	//add the cart to the database and set cookie
	$items_json = json_encode($item);
	$cart_expire= date("Y-m-d H:i:s", strtotime("+30 days")); 
	$db->query("INSERT INTO cart (items,expire_date) VALUES ('{$items_json}','{$cart_expire}')");
	$cart_id = $db->insert_id;
	setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
}

?>                         