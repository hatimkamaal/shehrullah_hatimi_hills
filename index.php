<?php
// error_reporting(E_ALL ^ E_NOTICE);  
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

DEFINE('LANDING_PAGE', 'Home');

include_once 'eco_sys.php';
$ecosys = new Eco_sys();
$ecosys->bootstrap();