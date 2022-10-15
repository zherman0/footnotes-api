<?php
///////////////////////////////////////////////////
/////  The only works included in index.php ///////
///////  This is to keep things a little //////////
///////  more organized as the apis grow //////////
///////////////////////////////////////////////////

# matches GET /
// dispatch('/', 'getUser');
// function getUser()
// {
//     redirect_to('user'); # redirects to the index
// }

# matches GET /user
dispatch('/user', 'get_users');
function get_users()
{
    $api = new UserClass;
    $results = $api->getUsers();
    set('user', $results);
    
    return json($results);
}
# matches GET /user/12
dispatch('/user/:id', 'get_user');
function get_user()
{
    $id = params('id');
    $api = new UserClass;
    $results = $api->getUsers($id);
    set('user', $results);
    
    return json($results);
}

# matches GET /usersearch/<some_params>
dispatch('/usersearch/**', 'user_search');
function user_search()
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
        $api = new UserClass;
        error_log("Dispatch search arg");
        error_log(print_r($arg, true));
      $results = $api->search($arg);
        set('search', $results);
        return json($results);
    } else {
        halt(NOT_FOUND, "This search doesn't exists"); # raises error / renders an error page
    }
}

# matches OPTIONS /user/1
dispatch_options('/user/:id', 'get_options_user');
function get_options_user()
{
    return html("OK");
   
}
 # matches POST /user
 dispatch_post('/user', 'user_create');
 function user_create()
 {
     $api = new UserClass;
    
     if (array_key_exists('userId', $_POST)) {
        $results = $api->saveUser($_POST, $_POST['userId']);    
     } else {
        $results = $api->saveUser($_POST);  
     }
     set('user', $results);
     if ($results) {
         return json($results);
     } else {
         halt(SERVER_ERROR, "AN error occured while trying to create a new log"); # raises error / renders an error page
     }
 }
 
# matches PUT /user/1
dispatch_put('/user/:id', 'user_update');
function user_update()
{
   // Leaving this here so I don't forget
   // Using put, I cannot get the file properly like I can with POST
   // so I just faked the POST and make that code handle the update 
   $id = params('id');
   $api = new UserClass;
   $results = $api->saveUser($_POST, $id);
   set('user', $results);
   if ($results) {
       return json($results);
   } else {
       halt(SERVER_ERROR, "An error occured while trying to update place ".$id); # raises error / renders an error page
   }
}
