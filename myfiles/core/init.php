<?php
$db=mysqli_connect("127.0.0.1","root","","eshop");

if(mysqli_connect_errno()) {

  echo 'Database connection failure'. mysqli_connect_error();
  die();
}
require_once $_SERVER['DOCUMENT_ROOT']. '../myfiles/config.php';
require_once BASEURL.'helpers/helpers.php';
