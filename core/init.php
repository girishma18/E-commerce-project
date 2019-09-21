<?php
 $db= mysqli_connect('127.0.0.1','root','','svdjm');
 if(mysqli_connect_error()){
 	 echo 'database connection failed with following errors: '.mysqli_connect_error();
 	 die();
 }
 session_start();

 //define('BASEURL',$_SERVER['DOCUMENT_ROOT'].'/svdjm');
 require_once $_SERVER['DOCUMENT_ROOT'].'/svdjm/config.php';
 require_once BASEURL.'/helpers/helpers.php';
 require_once BASEURL.'/vendor/autoload.php';

 $cart_id = '';
 if(isset($_COOKIE[CART_COOKIE])) {
 	$cart_id = sanitize($_COOKIE[CART_COOKIE]);
 }

 if(isset($_SESSION['sbuser'])){
 	$user_id = $_SESSION['sbuser'];
 	$query = $db->query("SELECT * FROM users WHERE id = '$user_id'");
 	$user_data = mysqli_fetch_assoc($query);
 	$fn = array_pad(explode(' ',$user_data['full_name']),2,0);
 	$user_data['first'] = $fn[0];
 	$user_data['last'] = $fn[1];
 }
 
 if(isset($_SESSION['success_flash'])) {
 	
 	echo '<div class="alert alert-success"> <p class="text-success text-center">'.$_SESSION['success_flash'].'</p></div>';
 	unset($_SESSION['success_flash']);
 }

 if(isset($_SESSION['error_flash'])) {
 	
 	echo '<div class="alert alert-danger"> <p class="text-danger text-center">'.$_SESSION['error_flash'].'</p></div>';
 	unset($_SESSION['error_flash']);
 }
?>