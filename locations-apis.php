<?php
///////////////////////////////////////////////////
/////  The only works included in index.php ///////
///////  This is to keep things a little //////////
///////  more organized as the apis grow //////////
///////////////////////////////////////////////////

# matches GET /
dispatch('/', 'getLocation');
function getLocation()
{
    redirect_to('location'); # redirects to the index
}

# matches GET /location
dispatch('/location', 'get_locations');
function get_locations()
{
    $location = new LocationsClass;
    $results = $location->getLocations();
    set('location', $results);
    
    return json($results);
}
# matches GET /location/12
dispatch('/location/:id', 'get_location');
function get_location()
{
    $id = params('id');
    $location = new LocationsClass;
    $results = $location->getLocations($id);
    set('location', $results);
    
    return json($results);
}

# matches OPTIONS /location/1
dispatch_options('/location/:id', 'get_options_location');
function get_options_location()
{
    if (params('id')) {
        return html("OK");
    } else {
        halt(NOT_FOUND, "This options doesn't exists"); # raises error / renders an error page
    }
}

 # matches POST /location
 dispatch_post('/location', 'location_create');
 function location_create()
 {
     $location = new LocationsClass;
     if (array_key_exists('locationId', $_POST)) {
        $results = $location->saveLocation($_POST, $_POST['locationId']);    
     } else {
        $results = $location->saveLocation($_POST);    
     }
     set('location', $results);
     if ($results) {
         return json($results);
     } else {
         halt(SERVER_ERROR, "AN error occured while trying to create a new log"); # raises error / renders an error page
     }
 }
 
# matches PUT /location/1
dispatch_put('/location/:id', 'location_update');
function location_update()
{
   // Leaving this here so I don't forget
   // Using put, I cannot get the file properly like I can with POST
   // so I just faked the POST and make that code handle the update 
   $id = params('id');
   $location = new LocationsClass;
   $results = $location->saveLocation($_POST, $id);
   set('location', $results);
   if ($results) {
       return json($results);
   } else {
       halt(SERVER_ERROR, "An error occured while trying to update place ".$id); # raises error / renders an error page
   }
}

# matches GET /locationearch/<some_params>
dispatch('/locationsearch/**', 'location_search');
function location_search()
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
        $api = new LocationsClass;
        // error_log("Dispatch search arg");
        // error_log(print_r($arg, true));
        $results = $api->search($arg);
        set('search', $results);
        return json($results);
    } else {
        halt(NOT_FOUND, "This search doesn't exists"); # raises error / renders an error page
    }
}

