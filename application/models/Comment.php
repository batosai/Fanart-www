<?php

class Model_Comment extends Zend_Db_Table_Row_Abstract
{
  protected function _insert()
  {
    $this->created_at = Zend_Date::now()->getIso();
    $this->state_id = 1;
  }

  public function findAvatar()
  {
    $table = new Model_DbTable_Files();
    $select = $table->select()->where('fk_name = ?', "users/{$this->user_id}.id")->where('fk_id = ?', $this->user_id)->limit(1);
    return $table->fetchRow($select);
  }

  public function findUser()
  {
    $table = new Model_DbTable_Users();

    return $table->find($this->user_id)->current();
  }
}
