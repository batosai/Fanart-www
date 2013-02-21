<?php

class Model_DbTable_Comments extends Zend_Db_Table_Abstract
{
  protected $_name = 'comments';
  protected $_rowClass = 'Model_Comment';

  public function fetchAccepted($id=null)
  {
    $select = $this->select()->where('state_id = 2');

    if ($id) {
     $select->where('drawing_id = ?', $id);
    }

    return $this->fetchAll($select);
  }

  public function fetchCancel($id=null)
  {
    $select = $this->select()->where('state_id = 3');

    if ($id) {
     $select->where('drawing_id = ?', $id);
    }

    return $this->fetchAll($select);
  }

  public function fetchWait($id=null)
  {
    $select = $this->select()->where('state_id = 1');

    if ($id) {
     $select->where('drawing_id = ?', $id);
    }

    return $this->fetchAll($select);
  }
}