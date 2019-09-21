<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/svdjm/core/init.php';
$name = isset($_POST['full_name'])?sanitize($_POST['full_name']):''; 
$email = isset($_POST['email'])?sanitize($_POST['email']):''; 
$street = isset($_POST['street'])?sanitize($_POST['street']):''; 
$street2 = isset($_POST['street2'])?sanitize($_POST['street2']):''; 
$city = isset($_POST['city'])?sanitize($_POST['city']):''; 
$state = isset($_POST['state'])?sanitize($_POST['state']):''; 
$zipcode = isset($_POST['zipcode'])?sanitize($_POST['zipcode']):''; 
$product_id = isset($_POST['product_id'])?sanitize($_POST['product_id']):''; 

$errors = array();
$required = array(
    'full_name' => 'Full Name',
    'email'     => 'Email',
    'street'    => 'street',
    'city'      => 'city',
    'state'     => 'state',
    'zipcode'   => 'zipcode',
    'country'   => 'country',
   );
  
  //check if all require fields are filled out
  foreach($required as $f => $d) {
  	if(empty($_POST[$f]) || $_POST[$f]=='') {
  		$errors[] = $d.' is required.';
  	}
  }

  //check if valid email address
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
  	$errors[] = 'Please enter a valid Email.';
  }
  if(!empty($errors)){
  	echo display_errors($errors);
  }else {
  	echo 'passed';
  }
?>