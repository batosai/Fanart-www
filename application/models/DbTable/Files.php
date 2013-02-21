<?php

class Model_DbTable_Files extends Zend_Db_Table_Abstract
{
  const IMG_EXTENSIONS = 'jpg,jpeg,png,gif';

  protected $_name = 'files';
  protected $_rowClass = 'Model_File';

  public function findByGuid($guid)
  {
    $select = $this->select()->where('guid = ?', $guid);

    return $this->fetchRow($select);
  }
}
