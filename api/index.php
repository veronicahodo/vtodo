<?php

// index.php

// Main interface for the vtodo application's server calls

$output = [
    'status'=>'error',
    'message'=>'Unspecified error'
  ];


$command = $_REQUEST['command'] ?? '';

switch(strtolower($command) {
    'createTask':
       break;
    'readTask':
        break;
    'updateTask':
        break;
    'deleteTask':
        break;
}


header('Content-type: application/json');
print(json_encode($output));
