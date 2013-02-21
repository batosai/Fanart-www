<?php
class SitemapController extends Zend_Controller_Action
{
  public function init()
  {
    $subcategoriesTable = new Model_DbTable_Subcategories();
    $usersTable = new Model_DbTable_Users();
    $fileTable = new Model_DbTable_Files();
    
    $select = $subcategoriesTable->select()->setIntegrityCheck(false)
                                        ->from($subcategoriesTable)
                                        ->join('drawings', 'drawings.sub_category_id=subcategories.id', array('drawings.sub_category_id'))
                                        ->where('drawings.visible = ?', 1)
                                        ->distinct('drawings.sub_category_id')
                                        ->order('subcategories.name ASC');
    $subcategories = $subcategoriesTable->fetchAll($select);

    $pagesSubcategories = array();
    foreach ($subcategories as $subcategory)
    {
      $pagesSubcategories[] = array(
        'label' => $subcategory->name,
        'uri' => $this->view->subcategoryUrl($subcategory)
      );
    }

    $select = $usersTable->select()->where('validate = 1')->order('login ASC');
    $users = $usersTable->fetchAll($select);
    $pagesUsers = array();
    foreach ($users as $user)
    {
      $pagesUsers[] = array(
        'label' => $user->login,
        'uri' => $this->view->userUrl($user)
      );
    }

    $select = $fileTable->select()->setIntegrityCheck(false)
                                  ->from($fileTable)
                                  ->join('drawings', 'drawings.id=files.fk_id', array('drawing_id' => 'id', 'drawing_name' => 'name'))
                                  ->where('files.fk_name LIKE ?', '%drawings.id')
                                  ->where('drawings.visible = ?', 1)
                                  ->order('drawings.created_at DESC');
    $files = $fileTable->fetchAll($select);
    $pagesFiles = array();
    foreach ($files as $file)
    {
      $pagesFiles[] = array(
        'uri' => $this->view->drawingUrl($file->drawing_id, $file->drawing_name)
      );
    }

    $this->view->navigation(
      new Zend_Navigation(array(
       array(
          'label'       => 'Accueil',
          'controller'  => 'index',
          'action'      => 'index'
       ),
       array(
          'label'  => 'Catégories',
          'route'  => 'categories',
          'pages'   => $pagesSubcategories
       ),
       array(
           'label'  => 'Utilisateurs',
           'route'  => 'users',
           'pages' => $pagesUsers
       ),
       array(
            'label'       => 'Contact',
            'controller'  => 'contact',
            'action'      => 'index',
            'pages'       => $pagesFiles
       )
      )
    ));
  }

  public function indexAction()
  {
    $this->_helper->layout->disableLayout();
  }

  public function redirectAction()
  {
    $this->_redirect('/sitemap');
  }
}