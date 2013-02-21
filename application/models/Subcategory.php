<?php

class Model_Subcategory extends Zend_Db_Table_Row_Abstract
{
  public $drawings_count = 0;

  protected function _insert()
  {
    $this->created_at = $this->updated_at = Zend_Date::now()->getIso();
  }

  protected function _update()
  {
    $this->updated_at = Zend_Date::now()->getIso();
  }

  public function findDrawingsCount()
  {
    $table = new Model_DbTable_Drawings();

    $select = $table->getAdapter()->select()->from('drawings', 'COUNT(id)')
                                            ->where('sub_category_id = ?', $this->id)
                                            ->where('visible = ?', 1);

    return $table->getAdapter()->fetchOne($select);
  }
}
