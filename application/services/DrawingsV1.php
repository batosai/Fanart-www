<?php

class Service_DrawingsV1 extends Service_Base
{
  const VERSION = '1.0';
  private $_table;
  private $_filesTable;
  private $_subcategoriesTable;

  public function __construct()
  {
    parent::__construct();
    $this->_table = new Model_DbTable_Drawings();
    $this->_filesTable = new Model_DbTable_Files();
    $this->_subcategoriesTable = new Model_DbTable_Subcategories();
  }

  public function getVersion()
  {
    return self::VERSION;
  }

  public function get($filter = null, $subcategory = null)
  {
    if ($subcategory) {
      $subCat = $this->_subcategoriesTable->find($subcategory)->current();
    }

     $select = $this->_filesTable->select()->setIntegrityCheck(false)
                                      ->from(array('f' => 'files'))
                                      ->join(array('d' => 'drawings'), 'd.id=f.fk_id', array('drawing_id' =>'id', 'drawing_name' => 'name'))
                                      ->join(array('u' => 'users'), 'u.id=d.user_id', array('user_id' =>'id', 'login'))
                                      ->where('f.fk_name LIKE ?', '%drawings.id')
                                      ->where('d.visible = ?', 1)
                                      ->where('d.trash = ?', 0);

    if ($filter == 'top') {
      $select->order('d.view DESC');
    }
		else {
		  if ($subcategory && $subCat->category_id == 8) {
		    $select->order('d.created_at ASC');
		  }
		  else {
		    $select->order('d.created_at DESC');
		  }
		}

    if ($subcategory) {
      $select->where('d.sub_category_id = ?', $subcategory);
    }

    if ($rows = $this->_filesTable->fetchAll($select))
    {
      $drawings = array();
      foreach ($rows as $row)
      {
        $draw = array();
        $draw['id'] = $row->drawing_id;
        $draw['name'] = $row->drawing_name;
        $draw['thumbnails'] = 'http://www.fan-art.fr/ios/thumbnails/'.$row->drawing_id.'.jpg';//$this->_view->serverUrl('/file/cache/guid/'.$row->guid.'/width/104/height/158');
        $draw['full'] = 'http://www.fan-art.fr/ios/full/'.$row->drawing_id.'.jpg';//$this->_view->serverUrl('/file/cache/guid/'.$row->guid.'/width/320/height/480');
        $draw['fullRetina'] = 'http://www.fan-art.fr/ios/full/'.$row->drawing_id.'@2x.jpg';//$this->_view->serverUrl('/file/cache/guid/'.$row->guid.'/width/640/height/960');

        array_push($drawings, $draw);
      }
    }

    return $drawings;
  }

  public function getComments($id){}
}