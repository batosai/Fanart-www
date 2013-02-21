<?php

class Model_Category extends Zend_Db_Table_Row_Abstract
{
  public $subcategories;

  protected function _insert()
  {
    $this->created_at = $this->updated_at = Zend_Date::now()->getIso();
  }

  protected function _update()
  {
    $this->updated_at = Zend_Date::now()->getIso();
  }

  private function findSubcategories()
  {

  }

  public function findSubCategoriesCount()
  {
    $table = new Model_DbTable_Subcategories();

    $select = $table->getAdapter()->select()->from('subcategories', 'COUNT(id)')
                                            ->where('category_id = ?', $this->id);

    return $table->getAdapter()->fetchOne($select);
  }
}
