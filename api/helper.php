<?php


// helper.php

// Helper functions used by most calls

function checkPassed($required)
{
    $passed = true;

    foreach ($required as $r) {
        if (!isset($_REQUEST[$r])) {
            $passed = false;
        }
    }

    return $passed;
}


function assignTask($taskId,$userId,VCRUD $c) {
    $c->create('assignments',['taskId'=>$taskId,'userId'=>$userId]);
    return true; // [TODO] not make this wrong
}