<?php

// api/task.php

// Handles most of the functions related to tasks

require_once('vcrud.php');

class Task
{
  private $fields;

  private $table = 'tasks';

  public function __construct($title, $parentId = 0, $content = '', $completed = 0)
  {
    $this->fields = [
      'taskId' => 0,
      'title' => $title,
      'parentId' => $parentId,
      'content' => $content,
      'completed' => $completed,
      'archived' => 0
    ];
  }

  public function create(VCRUD $c)
  {
    $this->fields['taskId'] = $c->create($this->table, $this->fields);
  }

  public function read($taskId, VCRUD $c)
  {
    $data = $c->read($this->table, [['taskId', '=', $taskId]]);
    $this->fields = $data[0];
  }

  public function update(VCRUD $c)
  {
    $c->update($this->table, $this->fields, [['taskId', '=', $this->fields['taskId']]]);
  }

  public function delete($taskId, VCRUD $c)
  {
    $this->fields['archived'] = 1;
    $this->update($c);
  }

  public function get($field)
  {
    return $this->fields[$field];
  }

  public function set($field, $value)
  {
    $this->fields[$field] = $value;
  }
}
