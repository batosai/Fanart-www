<?php

class Opsone_View_Helper_Number extends Zend_View_Helper_Abstract
{
  public function number($number, $precision = null)
  {
    return Zend_Locale_Format::toNumber($number, array('precision' => $precision));
  }
}
