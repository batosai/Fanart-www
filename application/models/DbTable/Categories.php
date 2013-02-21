<?php

class Model_DbTable_Categories extends Zend_Db_Table_Abstract
{
  protected $_name = 'categories';
  protected $_rowClass = 'Model_Category';
}