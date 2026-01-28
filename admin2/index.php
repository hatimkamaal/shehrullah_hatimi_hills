<?php

$current_directory = basename(__DIR__);
DEFINE('CURRENT_DIR' , $current_directory);

DEFINE('SECURE_LANDING_PAGE', 'home'); 
DEFINE('IS_APP_SECURE', true); // This should be set true if application has secure pages, else false
// DEFINE('VIEW_TEMPLATE', '_template.php');

    // Specify the path to your .env file
    

include_once '_gui.php';
include_once '_dto.php';
require_once '_framework.php';