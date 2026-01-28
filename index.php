<?php
echo 'Site is under maintenance. Please check back later.';
exit();


// error_reporting(E_ALL ^ E_NOTICE);  
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

DEFINE('LANDING_PAGE', 'Home');

include_once 'eco_sys.php';
$ecosys = new Eco_sys();
$ecosys->bootstrap();