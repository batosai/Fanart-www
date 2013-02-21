<?php

class Model_DbTable_Users extends Zend_Db_Table_Abstract
{
  protected $_name = 'users';
  protected $_rowClass = 'Model_User';

  public function getAuthAdapter()
  {
    return new Zend_Auth_Adapter_DbTable($this->getAdapter(), $this->_name, 'email', 'password', 'MD5(?) AND validate = 1');
  }

  public function fetchByEmail($email)
  {
    $select = $this->select()->where('email = ?', (string) $email);

    return $this->fetchRow($select);
  }

  public function fetchOneByCredentials($login, $password)
  {
    $select = $this->select()->where('login = ?', (string) $login)
                             ->where('password = md5(?)', $password)
                             ->limit(1);

    return $this->fetchRow($select);
  }
}
