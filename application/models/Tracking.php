<?php

class Model_Tracking extends Zend_Db_Table_Row_Abstract
{
  protected function _insert()
  {
    $this->created_at = $this->updated_at = $this->last_at = Zend_Date::now()->getIso();
		$this->launch = 1;
  }

  protected function _update()
  {
    $this->updated_at = $this->last_at = Zend_Date::now()->getIso();
    $this->launch += 1;
  }
}
