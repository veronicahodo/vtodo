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

    function create(VCRUD $c) {
        // this is so fucking wrong on so many levels
        // [TODO] error checking
        return [
            'status'=>'ok',
            'tagId'=>$c->create($this->table,$fields)
            ];
    }

    function read($tagId, VCRUD $c) {
        return [
            'status'=>'ok',
            $c->read
    }
}
