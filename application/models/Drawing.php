<?php

class Model_Drawing extends Zend_Db_Table_Row_Abstract
{
  protected function _insert()
  {
    $this->created_at = $this->updated_at = Zend_Date::now()->getIso();
  }

  protected function _update()
  {
    $this->updated_at = Zend_Date::now()->getIso();
  }
  
  protected function _delete()
  {
    $this->indexingDelete();
  }

  public function indexingDelete()
  {
    $index = Zend_Search_Lucene::open(LUCENE_PATH);
    $term = new Zend_Search_Lucene_Index_Term($this->id, 'drawing_id');
    $query = new Zend_Search_Lucene_Search_Query_Term($term);

    $hits  = $index->find($query);

    foreach ($hits as $hit) {
      $index->delete($hit->id);
    }
  }

  public function findUser()
  {
    $table = new Model_DbTable_Users();

    return $table->find($this->user_id)->current();
  }

  public function findFile()
  {
    $table = new Model_DbTable_Files();

    $select = $table->select()->where('fk_name LIKE ?', '%drawings.id')
                              ->where('fk_id = ?', $this->id)
                              ->limit(1);

    return $table->fetchRow($select);
  }

  public function findSubCategory()
  {
    $table = new Model_DbTable_Subcategories();

    return $table->find($this->sub_category_id)->current();
  }

  public function findComments()
  {
    $table = new Model_DbTable_Comments();

    $select = $table->select()->setIntegrityCheck(false)
                              ->from($table)
                              ->join('users', 'comments.user_id=users.id', array('login'))
                              ->where('drawing_id = ?', $this->id)
                              ->where('state_id = 1')
                              ->order('created_at DESC');

    return $table->fetchAll($select);
  }
}
