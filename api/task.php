<?php

// api/task.php

// Handles most of the fuctions related to tasks

require_once('vcrud.php');

class TASK {
  private $fields = [
    'taskId' => 0,
    'title' => '',
    'parentId' => 0,
    'content' => '',
    'completed' => 0,
    'archived' => 0
  ];
  
  function __construct($title,$parentId=0,$content='',$completed=0) {
    
  }

  function create(VCRUD $c) {
    $this->fields['taskId'] = $c->create('tasks',$fields);
  }

  function read($taskId, VCRUD $c) {
    // very breakable [TODO]
    $data = $c->read('tasks',[['taskId','=',$taskId]]);
    $this->fields = $data[0];
  }

  function update(VCRUD $c) {
    $c->update('tasks',$this->fields,[['taskId','=',$this->fields['taskId']]]);
  }

  function delete($taskId, VCRUD $c) {
    $this->fields['archived'] = 1;
    $this->update($c);
  }

  function get($field) {
    return $this->fields[$field];
  }

  function set($field,$value) {
    $this->fields[$field] = $value;
  }

}
