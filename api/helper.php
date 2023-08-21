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
