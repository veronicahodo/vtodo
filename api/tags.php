<?php

// tag.php

// Class to define tags and working with them
require_once('vcrud.php');

class Tag {
    private $fields;
    private $table = 'tags';

    function __constructor() {
        $fields = [
            'tagId' = 0,
            'label' = '',
            'color' = 'ffffff'
        ];
    }

    function create($fields, VCRUD $c) {
        
    }
}
