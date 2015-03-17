<?php

class Wordpress_ImportsController extends Zend_Controller_Action
{
  public function init()
  {
      $this->_helper->layout->disableLayout();
  }

  public function indexAction()
  {


    /*
    * Model File modify + FileController
    */


    $table = new Model_DbTable_Users();
    $select = $table->select();
    $users = $table->fetchAll($select);

    $this->view->users = $users;

    //
    $subcategoriesTable = new Model_DbTable_Subcategories();
    $select2 = $subcategoriesTable->select()->setIntegrityCheck(false)
                                     ->from($subcategoriesTable)
                                     ->join('drawings', 'drawings.sub_category_id=subcategories.id', array('drawings.sub_category_id'))
                                     ->where('drawings.visible = ?', 1)
                                     ->distinct('drawings.sub_category_id')
                                     ->order('subcategories.name ASC');

    $subcategories = $subcategoriesTable->fetchAll($select2);

    $this->view->subcategories = $subcategories;

    //
    $table = new Model_DbTable_Files();
    $select = $table->select()->setIntegrityCheck(false)
                                     ->from($table)
                                     ->join('drawings', 'drawings.id=files.fk_id', array('drawing_id' => 'id', 'drawing_name' => 'name', 'drawing_cat' => 'sub_category_id', 'drawing_user' => 'user_id', 'drawing_create' => 'created_at', 'comment' => 'comment'))
                                     ->where('files.fk_name LIKE ?', '%drawings.id')
                                     ->where('drawings.visible = ?', 1)
                                     ->order('drawings.created_at DESC');

    $draws = $table->fetchAll($select);

    $this->view->draws = $draws;

    //

    $this->view->filter = new Opsone_Filter_Slug();
  }

  public function usersAction()
  {
    $table = new Model_DbTable_Users();
    $select = $table->select()->where('validate = 1');
    $users = $table->fetchAll($select);

    $this->view->users = $users;
  }

  public function categoriesAction()
  {
    $table = new Model_DbTable_Categories();
    $subcategoriesTable = new Model_DbTable_Subcategories();


    $select = $table->select()->order('position ASC');
    $select2 = $subcategoriesTable->select()->setIntegrityCheck(false)
                                     ->from($subcategoriesTable)
                                     ->join('drawings', 'drawings.sub_category_id=subcategories.id', array('drawings.sub_category_id'))
                                     ->where('drawings.visible = ?', 1)
                                     ->distinct('drawings.sub_category_id')
                                     ->order('subcategories.name ASC');

    $subcategories = $subcategoriesTable->fetchAll($select2);

    $categories = $table->fetchAll($select);
    $subcategories = $subcategoriesTable->fetchAll($select2);

    // $subcategoriesId = array();

    // foreach ($categories as $category)
    // {
    //   foreach ($subcategories as $subcategory)
    //   {
    //     if ($subcategory->category_id == $category->id)
    //     {
    //       if (!in_array($subcategory->id, $subcategoriesId))
    //       {
    //         $subcategoriesId[] = $subcategory->id;
    //         $category->subcategories[$subcategory->sub_category_id] = clone $subcategory;
    //       }
    //       $category->subcategories[$subcategory->sub_category_id]->drawings_count++;
    //     }
    //   }
    // }

    $this->view->categories = $categories;
    $this->view->subcategories = $subcategories;

  }

  public function drawsAction()
  {
    $table = new Model_DbTable_Files();


    $select = $table->select()->setIntegrityCheck(false)
                                     ->from($table)
                                     ->join('drawings', 'drawings.id=files.fk_id', array('drawing_id' => 'id', 'drawing_name' => 'name', 'drawing_cat' => 'sub_category_id', 'drawing_user' => 'user_id', 'drawing_create' => 'created_at'))
                                     ->where('files.fk_name LIKE ?', '%drawings.id')
                                     // ->where('drawings.visible = ?', 1)
                                     ->order('drawings.created_at DESC');

    $draws = $table->fetchAll($select);

    $this->view->draws = $draws;
  }

  public function commentsAction()
  {
    $table = new Model_DbTable_Comments();

    $select = $table->select()->where('state_id = 1');
    $comments = $table->fetchAll($select);

    $this->view->comments = $comments;
  }
}
