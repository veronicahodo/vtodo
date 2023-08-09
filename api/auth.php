<?php

// auth.php

// Handles authentication API calls

require_once('vuser.php');
require_once('vcrud.php');
require_once('vtoken.php');

function authValidatePassword(VCRUD $c) {
    $username = $_REQUEST['username'] ?? '';
    $password = $_REQUEST['password'] ?? '';

    $user = new USER();
    if ($userId = $user->validatePassword($username,$password)) {
        $token = new TOKEN();

        $tokenKey = $token->generateToken($userId,$c);
        return = [
            'status'=>'ok',
            'token'=> $tokenKey
            ];
    }
    
}
