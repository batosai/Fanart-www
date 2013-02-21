<?php

class Service_CategoriesV1 extends Service_Base
{
  const VERSION = '1.0';
  private $_table;
  private $_subcategoriesTable;

  public function __construct()
  {
    parent::__construct();
    $this->_table = new Model_DbTable_Categories();
    $this->_subcategoriesTable = new Model_DbTable_Subcategories();
  }

  public function getVersion()
  {
    return self::VERSION;
  }

  public function get()
  {
    $select = $this->_table->select()->order('id ASC');
    $categories = array();

    if ($rows = $this->_table->fetchAll($select))
    {
      foreach($rows as $row)
      {
        $cat = array();
        $cat['id'] = $row->id;
        $cat['name'] = ucfirst($row->name);
        $cat['subcategories'] = array();

        $select = $this->_subcategoriesTable->select()
                                            ->setIntegrityCheck(false)
                                            ->from(array('s' => 'subcategories'), array('name'))
                                            ->join(array('d' => 'drawings'), 'd.sub_category_id=s.id', array('sub_category_id'))
                                            ->where('category_id = ?', $row->id)
                                            ->distinct('d.sub_category_id')
                                            ->order('s.name ASC');

        if ($subcategories = $this->_subcategoriesTable->fetchAll($select)) {
          foreach ($subcategories as $subcategory)
          {
            $subcat = array();
            $subcat['id'] = $subcategory->sub_category_id;
            $subcat['name'] = ucfirst($subcategory->name);
            $subcat['parent'] = $row->id;

            array_push($cat['subcategories'], $subcat);
          }// end foreach
        }//end if

        array_push($categories, $cat);
      }//enforeach
    }// end if

    return $categories;
  }
}
