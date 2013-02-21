<?php

class Admin_Model_DbTable_Users extends Zend_Db_Table_Abstract
{
  protected $_name = 'admin_users';
  protected $_rowClass = 'Admin_Model_User';

  public function getAuthAdapter()
  {
    return new Zend_Auth_Adapter_DbTable($this->getAdapter(), $this->_name, 'email', 'password', 'SHA1(CONCAT(password_salt, ?))');
  }

  public function fetchOneByCredentials($email, $password)
  {
    $select = $this->select()->where('email = ?', (string) $email)
                             ->where('password = sha1(CONCAT(password_salt, ?))', $password)
                             ->limit(1);

    return $this->fetchRow($select);
  }
}
