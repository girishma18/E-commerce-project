<?php 
   require_once $_SERVER['DOCUMENT_ROOT'].'/svdjm/core/init.php';
   unset($_SESSION['sbuser']);
   header('Location: login.php');

   ?>