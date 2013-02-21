<?php

class Model_User extends Zend_Db_Table_Row_Abstract
{
  protected function _insert()
  {
    $this->created_at = $this->updated_at = Zend_Date::now()->getIso();
    $this->validate = 1;

	$filter = new Opsone_Filter_Slug();
	$this->login_url = $filter->filter($this->login);

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
      $this->password = md5($this->password);
    }
  }

  public function findDrawingsCount()
  {
    $table = new Model_DbTable_Drawings();

    $select = $table->getAdapter()->select()->from('drawings', 'COUNT(id)')
                                            ->where('user_id = ?', $this->id)
                                            ->where('visible = ?', 1);

    return $table->getAdapter()->fetchOne($select);
  }

  public function findAvatar()
  {
    $table = new Model_DbTable_Files();
    $select = $table->select()->where('fk_name = ?', "users/{$this->id}.id")->where('fk_id = ?', $this->id)->limit(1);
    return $table->fetchRow($select);
  }
}
