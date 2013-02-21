<?php

class Opsone_View_Helper_Config extends Zend_View_Helper_Abstract
{
  private $_config;

  public function __construct()
  {
    $this->_config = Zend_Registry::get('Zend_Config');
  }

  public function config()
  {
    return $this->_config;
  }
}
