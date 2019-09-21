$cartq = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $cart = mysqli_fetch_assoc($cartq);
    var_dump($cart);
    $previous_items = json_decode($cart['items'],true);
    $item_match = 0;
    $new_items = array();
    foreach($previous_items as $pitem) {
    	if($item[0]['id'] == $pitem['id'] && $item[0]['size'] == $pitem['size']) {
    		$pitem['quantity'] = $pitem['quantity'] + $item[0]['quantity'];
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
	$db->query("UPDATE cart SET items = '{$items_json}',expire_date = '{cart_expire}' WHERE id = '{$cart_id}'");
	setcookie(CART_COOKIE,'',1,"/",null);
	setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);

