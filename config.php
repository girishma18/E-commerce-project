<?php
 define('BASEURL',$_SERVER['DOCUMENT_ROOT'].'/svdjm');
 define('BASE',$_SERVER['DOCUMENT_ROOT']);
 define('CART_COOKIE','SBwi72uCklwiqzz2');
 define('CART_COOKIE_EXPIRE',time() + (86400 *30));


 define('CURRENCY','inr');
 define('CHECKOUTMODE','TEST'); //change test to live when you are ready to go live

 if(CHECKOUTMODE == 'TEST') {
 	define('STRIPE_PRIVATE','sk_test_MFdAk1Oa6y5ZcikkXxTYNdK1');
 	define('STRIPE_PUBLIC','pk_test_ZAvpA207S5n134jjllT5LhfF');
 }

if(CHECKOUTMODE == 'LIVE') {
 	define('STRIPE_PRIVATE','');
 	define('STRIPE_PUBLIC','');
 }


 ?>