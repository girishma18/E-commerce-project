<?php
 function display_errors($errors) {
 	$display = '<ul class="alert alert-danger">';
 	foreach($errors as $error) {
 		$display .= '<li class="text-danger">'.$error.'</li>';
 	}
 	$display .= '</ul>';
 	return $display;
 }

 function sanitize($dirty)
 {
 	return htmlentities($dirty,ENT_QUOTES,"UTF-8");
 }
 function login($user_id) {
 	$_SESSION['sbuser'] = $user_id;
 	global $db;
 	$date = date("Y-m-d H:i:s");
 	$db->query("UPDATE users SET last_login = '$date' WHERE id = 'user_id'");
 	$_SESSION['success_flash'] = 'You are now logged in!';
 	header('Location: index.php');
 }

 function is_logged_in() {
 if(isset($_SESSION['sbuser']) && $_SESSION['sbuser']>0){
 	return true;
 }
  return false;
  }

 function login_error_redirect($url = 'login.php'){
 	$_SESSION['error_flash'] = 'You must be logged in to access that page.';
 	header('Location: '.$url);
 } 

 function has_permission($permission = 'admin'){
 	global $user_data;
    $permissions = array_pad(explode(',',$user_data['permissions']),2,0);
    if(in_array($permission,$permissions,true)){
    	return true;
    }
    return false;
 }

 function permission_error_redirect($url = 'login.php'){
 	$_SESSION['error_flash'] = 'You do not have permission to access that page';
 	header('Location: '.$url);
 } 

 function pretty_date($date){
 	return date("M d, Y h:i A",strtotime($date));
 }

 function get_category($child_id){
 	global $db;
 	$id = sanitize($child_id);
 	$sql = "SELECT p.id AS 'pid', p.category AS 'parent', c.id AS 'cid', c.category AS 'child'
            FROM categories c
            INNER JOIN categories p
            ON c.parent = p.id 
            WHERE c.id = '$id'";
     $query = $db->query($sql);
     $category = mysqli_fetch_assoc($query);
     return $category;
 }
 function sizestoarray($string){
     $sizesarray = explode(',',$string);
     $returnarray = array();
     foreach($sizesarray as $size){
         $s = explode(':',$size);
         $returnarray[]= array('size'=> $s[0],'price'=>$s[1],'quantity'=>$s[2]);
     }
     return $returnarray;
 }

 function sizestostring($sizes){
     $sizestring = '';
     foreach($sizes as $size){
         $sizestring.= $size['size'].':'.$size['price'].':'.$size['quantity'].',';
     }
     $trimed = rtrim($sizestring,',');
     return $trimed;
 }
