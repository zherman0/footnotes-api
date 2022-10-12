<?php
# 0. Loading limonade framework
require_once('lib/limonade.php');
require_once('inc/HikingLogClass.php');
require_once('inc/UserClass.php');
require_once('inc/LocationsClass.php');


# 1. Setting global options of our application
function configure()
{
    # A. Setting environment
    // $localhost = preg_match('/^localhost(\:\d+)?/', $_SERVER['HTTP_HOST']);
    // $env =  $localhost ? ENV_DEVELOPMENT : ENV_PRODUCTION;
    // option('env', $env);
  
    # B. Initiate db connexion
    # C. Other options
    setlocale(LC_TIME, "US/Mountain");
}


# 2. Setting code that will be executed bfore each controller function
function before()
{
}

# 3. Defining routes and controllers
include('./locations-apis.php');
include('./hiking-apis.php');
include('./user-apis.php');
  

# 4. Running the limonade blog app
run();
