<?php

class Model_DbTable_Drawings extends Zend_Db_Table_Abstract
{
  protected $_name = 'drawings';
  protected $_rowClass = 'Model_Drawing';
}