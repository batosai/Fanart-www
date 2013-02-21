<?php

class CategoryController extends Zend_Controller_action
{
  private $_table;
  private $_subcategoriesTable;
  private $_filesTable;
  private $_session;

  public function init()
  {
    $this->_session = new Zend_Session_Namespace('drawing');
    $this->_table = new Model_DbTable_Categories();
    $this->_subcategoriesTable = new Model_DbTable_Subcategories();
    $this->_filesTable = new Model_DbTable_Files();

    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'active' => true
      )
    );
  }

  public function indexAction()
  {
    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'url' => $this->view->url(array(), 'default', true)
      ),
      array(
        'name' => 'Catégories',
        'active' => true
      )
    );

    $select = $this->_table->select()->order('position ASC');
    $select2 = $this->_subcategoriesTable->select()->setIntegrityCheck(false)
                                         ->from($this->_subcategoriesTable)
                                         ->join('drawings', 'drawings.sub_category_id=subcategories.id', array('drawings.sub_category_id'))
                                         ->where('drawings.visible = ?', 1)
                                         //->distinct('drawings.sub_category_id')
                                         ->order('subcategories.name ASC');

    $categories = $this->_table->fetchAll($select);
    $subcategories = $this->_subcategoriesTable->fetchAll($select2);

    $subcategoriesId = array();

    foreach ($categories as $category)
    {
      foreach ($subcategories as $subcategory)
      {
        if ($subcategory->category_id == $category->id)
        {
          if (!in_array($subcategory->id, $subcategoriesId))
          {
            $subcategoriesId[] = $subcategory->id;
            $category->subcategories[$subcategory->sub_category_id] = clone $subcategory;
          }
          $category->subcategories[$subcategory->sub_category_id]->drawings_count++;
        }
      }
    }

    $this->view->categories = $categories;
  }

  public function showAction()
  {
      if ($this->_session) unset($this->_session->previous);
      $select = $this->_subcategoriesTable->select()->where('name_url = ?', $this->_getParam('slug'));
      $subcategory = $this->_subcategoriesTable->fetchRow($select);

      $this->view->breadcrumb = array(
        array(
          'name' => 'Accueil',
          'url' => $this->view->url(array(), 'default', true)
        ),
        array(
          'name' => 'Catégories',
          'url' => $this->view->categoryUrl()
        ),
        array(
          'name' => $subcategory->name,
          'active' => true
        )
      );

      $select = $this->_filesTable->select()->setIntegrityCheck(false)
                                            ->from($this->_filesTable)
                                            ->join('drawings', 'drawings.id=files.fk_id', array('drawing_id' => 'id', 'drawing_name' => 'name'))
                                            ->where('files.fk_name LIKE ?', '%drawings.id')
                                            ->where('drawings.visible = ?', 1)
                                            ->where('drawings.sub_category_id = ?', $subcategory->id);
      
      if ($subcategory->category_id == 8)
      {
        $select->order('drawings.created_at ASC');
      }
      else
      {
        $select->order('drawings.created_at DESC');
      }
      

      $paginator = Zend_Paginator::factory($select);
      $paginator->setCurrentPageNumber($this->_getParam('page'));
      $paginator->setItemCountPerPage(16);

      $this->view->paginator = $paginator;
	  $this->view->subcategory = $subcategory;
  }

  public function optionsAction()
  {
    $term = $this->_getParam('term');
    $q = $this->_subcategoriesTable->select()->order('name DESC');

    foreach (preg_split('/\s+/', $term, null, PREG_SPLIT_NO_EMPTY) as $word) {
      $q->where('name LIKE ?', "%$word%");
    }

    $this->_helper->json($this->_subcategoriesTable->fetchAll($q)->toArray());
  }
}