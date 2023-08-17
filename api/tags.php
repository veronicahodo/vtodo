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
        // Kids, do not do this at home. This is terrible awful programming
        // [TODO] Make it not break with a slight breeze. And error check
        return [
            'status'=>'ok',
            'tag'=>$c->read($this->table,['tagId','=',$tagId])
            ];
    }

    function update($fields, VCRUD $c) {
        // Ok, you know by now that this shit is just unsafe as can be
        // [TODO] Unfuck.
        $c->update($this->table,$fields,['tagId','=',$this->fields['tagId']);
        return ['status'=>'ok'];
            
    }

    function delete($tagId, VCRUD $c) {
        // This should really find would be orphans and move them under
        // either a catch all or supply? (I don't fucking like that idea)
        $c->delete($this->table,['tagId','=',$tagId]);
        return ['status'=>'ok'];
    }


    function search($tags, VCRUD $c) {
        // searches for all tasks that match ANY of the tags supplied
        // Once again, coding like a pirate on the carabeean sojsfjslfjsljrf
        $conditions = [];
        foreach($tags as $tag) {
            $conditions[] = ['tagId','
        }
        return [
            'status' => 'ok',
            'results'=> $results
            ];
            
    }

}
