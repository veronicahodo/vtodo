<?php

// index.php

// Main interface for the vtodo application's server calls

require_once('helper.php');
require_once('vcrud.php');
require_once('vtoken.php');
require_once('vuser.php');
require_once('config.php');
//require_once('auth.php');

$crud = new VCRUD($DBUSER, $DBPASS, $DBHOST, $DBNAME);


$output = [
    'status' => 'error',
    'message' => 'Unspecified error'
];


// authentication functions [auth.]

function authLogin(VCRUD $c)
{
    $required = ['username', 'password'];
    if (checkPassed($required)) {
        $user = new VUSER();
        $userId = $user->validatePassword(htmlspecialchars($_REQUEST['username']), htmlspecialchars($_REQUEST['password']), $c);
        $token = new VTOKEN();
        $tokenKey = $token->generateToken($userId, $c);
        return [
            'status' => 'ok',
            'userId' => $userId,
            'token' => $tokenKey
        ];
    } else {
        return [
            'status' => 'error',
            'message' => '[auth.login] username/password required'
        ];
    }
}



function taskCreate(VCRUD $c) {
    $request = ['title','token'];
    if (checkPassed($request)) {
        $token = new VTOKEN();
        if ($userId = $token->getUserIdFromToken()) {
            $task = new TASK();
            $task->set('title',htmlspecialchars($_REQUEST['title'] ?? ''));
            $task->set('parent',htmlspecialchars($_REQUEST['parent'] ?? 0));
            $task->set('content',htmlspecialchars($_REQUEST['content'] ?? ''));
            $task->set('complete',htmlspecialchars($_REQUEST['completed'] ?? 0));
            $taskFields['archived'] = 0;
            $task->create($c);
            assignTask($task->get('taskId'),$userId,$c);
            return [
                'status'=>'ok',
                'taskId'=>$task->get('taskId')
            ];
        }
        
    } else {
        return [ 
            'status'=>'error',
            'message'=>'[task.create] New tasks must have a title and token'
        ];
    }
    
}


function userRead(VCRUD $c)
{
    $required = ['userId', 'token'];
    // [TODO] Turn the token into a userId
    if (checkPassed($required)) {
        $user = new VUSER();
        $user->load(htmlspecialchars($_REQUEST['userId']), $c);
        return [
            'status' => 'ok',
            'user' => $user
        ];
    } else {
        return [
            'status' => 'error',
            'message' => '[user.read] userId required'
        ];
    }
}


function userUpdate(VCRUD $c)
{
    $request = ['userId', 'token'];
    $fields = ['userId', 'username'];
    // [TODO] token shits
    if (checkPassed($request)) {
        $user = new VUSER();
        $user->load(htmlspecialchars($_REQUEST['userId']), $c);
    } else {
        return [
            'status' => 'error',
            'message' => '[user.update] userId and token required'
        ];
    }
}

$command = $_REQUEST['command'] ?? '';

switch (strtolower($command)) {
    case 'auth.login':
        $output = authLogin($crud);
        break;
    case 'task.create':
        $output = taskCreate($crud);
        break;
    case 'user.read':
        $output = userRead($crud);
        break;
    case 'user.update':
        $output = userUpdate($crud);
        break;
    default:
        $output['message'] = 'No valid command specified';
}

$crud->close();
header('Content-type: application/json');
print(json_encode($output));
