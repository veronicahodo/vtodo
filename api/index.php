<?php

// index.php

// Main interface for the vtodo application's server calls

require_once('vcrud.php');
require_once('vtoken.php');
require_once('vuser.php');

$crud = new VCRUD($DBUSER,$DBPASS,$DBHOST,$DBNAME);


$output = [
    'status'=>'error',
    'message'=>'Unspecified error'
  ];


$command = $_REQUEST['command'] ?? '';

function doLogin() {
    // handles the login process.
    // expects:
    //    username: the users name. 
    //        [TODO] make this syntax enforceable
    //    password: the password in plain text

    $username = $_REQUEST['username'] ?? '';
    $password = $_REQUEST['password'] ?? '';
    
}

switch(strtolower($command) {
    'login':
        $output = doLogin();
       break;
    default:
        $output['message'] = 'No valid command specified';
}

$crud->close();
header('Content-type: application/json');
print(json_encode($output));
