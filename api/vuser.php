<?php

// vuser.php

// User management class

require_once('vcrud.php');

class VUSER
{
    private $userId;
    private $username;
    private $hash;      // sha512 hash of plain text password + salt
    private $salt;


    function __construct()
    {
        $this->userId = 0;
        $this->username = '';
        $this->hash = '';
        $this->salt = '';
    }


    function create($username, $password, VCRUD $c)
    {
        $this->salt = random_bytes(32);
        $c->create('users', [
            'username' => $username,
            'hash' => hash('sha512', $password . $this->salt),
            'salt' => $this->salt
        ]);
    }

    function load($userId, VCRUD $c)
    {
        $data = $c->read('users', [['userId', '=', $userId]]);
        if ($data) {
            $this->userId = $data[0]['userId'];
            $this->username = $data[0]['username'];
            $this->hash = $data[0]['hash'];
            $this->salt = $data[0]['salt'];
            return $data[0];
        } else {
            return false;
        }
    }

    function save(VCRUD $c)
    {
        if ($this->userId > 0) {
            $c->create('users', [
                'username' => $this->username,
                'hash' => $this->hash,
                'salt' => $this->salt
            ]);
        } else {
            $c->update('users', [
                'username' => $this->username,
                'hash' => $this->hash,
                'salt' => $this->salt
            ], [
                ['userId', '=', $this->userId]
            ]);
        }
    }


    function getUserData($userId, VCRUD $c)
    {
        $data = $c->read('userdata', [['userId', '=', $userId]]);
        // lol so not failsafe [TODO]
        return $data[0];
    }

    function validatePassword($username, $password, VCRUD $c)
    {
        // I'm conflicted on leaving this to where it doesn't
        // modify our internal user. I don't know why we would ever NOT
        // want current user data in this situation but ... why waste cycles?
        // I'm obviously open to suggestions
        $user = $c->read('users', [['username', '=', $username]]);
        if ($user) {
            if (hash('sha512', $password . $user[0]['salt'])) {
                return $user[0]['userId'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
