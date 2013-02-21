<?php

class Opsone_View_Helper_Slug extends Zend_View_Helper_Abstract
{
  private $_filter;

  public function __construct()
  {
    $this->_filter = new Opsone_Filter_Slug();
  }

  public function slug($value)
  {
    return $this->_filter->filter($value);
  }
}