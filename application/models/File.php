<?php

class Model_File extends Zend_Db_Table_Row_Abstract
{
  private $_filepath;

  protected function _insert()
  {
    $this->created_at = Zend_Date::now()->getIso();

    if (!$this->guid) {
      $this->guid = Opsone_Token::get();
    }
    $this->rename();
  }

  protected function _update()
  {
    $this->rename();
    //rename($this->_filepath, $this->getPath());
    //unlink($this->_filepath);
  }

  protected function _postInsert()
  {
    /*if ($this->_filepath) {
      copy($this->_filepath, $this->getPath());
    }*/
  }

  protected function _postDelete()
  {
    unlink($this->getPath());
  }

  public function setFile($filepath)
  {
    $this->_filepath = $filepath;
    $this->filename = basename($filepath);
  }

  public function rename()
  {
    list($prefix) = explode('.', $this->fk_name);

    $suffix = mb_strtolower(pathinfo($this->filename, PATHINFO_EXTENSION));

    $path = sprintf('%s/%s/%s.%s', self::getDir(), $prefix, $this->guid, $suffix);

    rename($this->_filepath, $path);
    //var_dump($this->_filepath);var_dump($path);exit;
  }

  public function getPath()
  {
    if ($this->id)
    {
      list($prefix) = explode('.', $this->fk_name);

      $suffix = mb_strtolower(pathinfo($this->filename, PATHINFO_EXTENSION));

      return sprintf('%s/%s/%s.%s', self::getDir(), $prefix, $this->guid, $suffix);
    }
    else if ($this->_filepath) {
      return $this->_filepath;
    }
    else {
      throw new Exception('Model_File object unset');
    }
  }

  public function getDrawing()
  {
    $table = new Model_DbTable_Drawings();

    $select = $table->select()->where('id = ?', $this->fk_id)
                              ->limit(1);

    return $table->fetchRow($select);
  }

  public static function getDir()
  {
    return realpath(APPLICATION_PATH . '/../data/uploads');
  }
}
