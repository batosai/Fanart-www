<?php

class Admin_Model_User extends Zend_Db_Table_Row_Abstract
{
  protected function _insert()
  {
    $this->created_at = $this->updated_at = Zend_Date::now()->getIso();

    $this->_setPassword();
  }

  protected function _update()
  {
    $this->updated_at = Zend_Date::now()->getIso();

    $this->_setPassword();
  }

  private function _setPassword()
  {
    if (isset($this->_modifiedFields['password']))
    {
      $this->password_salt = Opsone_Token::get();
      $this->password = sha1($this->password_salt . $this->password);
    }
  }

  public function findAvatar()
  {
    $table = new Model_DbTable_Files();
    $select = $table->select()->where('fk_name = ?', "users/{$this->id}.id")->where('fk_id = ?', $this->id);
    return $table->fetchRow($select);
  }
}
