<?php
///////////////////////////////////////////////////
/////  The only works included in index.php ///////
///////  This is to keep things a little //////////
///////  more organized as the apis grow //////////
///////////////////////////////////////////////////

// # matches GET /
// dispatch('/', 'getHikinglog');
// function getHikinglog()
// {
//     redirect_to('hike'); # redirects to the index
// }

# matches GET /hike
dispatch('/hike', 'get_hikes');
function get_hikes()
{
    $hike = new HikingLogClass;
    $results = $hike->getHikes();
    set('hike', $results);
    
    return json($results);
}
# matches GET /hike/12
dispatch('/hike/:id', 'get_hikes');
function get_hike()
{
    $id = params('id');
    $hike = new HikingLogClass;
    $results = $hike->getHikes($id);
    set('hike', $results);
    
    return json($results);
}

# matches OPTIONS /hike/1
//dispatch_options('/hike/:id', 'get_options_hike');
function get_options_hike()
{
    if (params('id')) {
        return html("OK");
    } else {
        halt(NOT_FOUND, "This options doesn't exists"); # raises error / renders an error page
    }
}

 # matches POST /hike
 dispatch_post('/hike', 'add_hike');
 function add_hike()
 {
     $hike = new HikingLogClass;
     if (array_key_exists('hikeId', $_POST)) {
        $results = $hike->saveHike($_POST, $_POST['hikeId']);    
     } else {
        $results = $hike->saveHike($_POST);    
     }
     set('hike', $results);
     if ($results) {
         return json($results);
     } else {
         halt(SERVER_ERROR, "AN error occured while trying to create a new log"); # raises error / renders an error page
     }
 }
 
# matches PUT /hike/1
dispatch_put('/act/:id', 'update_hike');
function update_hike()
{
   // Leaving this here so I don't forget
   // Using put, I cannot get the file properly like I can with POST
   // so I just faked the POST and make that code handle the update 
   $id = params('hikeId');
   $hike = new HikingLogClass;
   $results = $hike->saveHike($_POST, $id);
   set('hike', $results);
   if ($results) {
       return json($results);
   } else {
       halt(SERVER_ERROR, "An error occured while trying to update place ".$id); # raises error / renders an error page
   }
}

# matches GET /hikesearch/<some_params>
dispatch('/hikesearch/**', 'hike_search');
function hike_search()
{
    if (params(0)) {
        // Get search params
        $arg = [];
        $args = params(0);
        // Make array with each set of params
        $search = explode("/", $args);
        for ($index = 0; $index < count($search) - 1; $index= $index + 2) {
            // Each one of these should be in <param>=<value>, if not things will go wrong
            $arg[$search[$index]] = $search[$index + 1];
        }
        $api = new HikingLogClass;
        // error_log("Dispatch search arg");
        // error_log(print_r($arg, true));
        $results = $api->search($arg);
        set('search', $results);
        return json($results);
    } else {
        halt(NOT_FOUND, "This search doesn't exists"); # raises error / renders an error page
    }
}
