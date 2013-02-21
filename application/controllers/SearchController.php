<?php

class SearchController extends Zend_Controller_Action
{
    private $_index;
    private $_session;

    public function init()
    {
      $this->view->breadcrumb = array(
        array(
          'name' => 'Accueil',
          'active' => true
        )
      );

      $this->_session = new Zend_Session_Namespace('drawing');
      $this->_index = Zend_Search_Lucene::open(LUCENE_PATH);
    }

    public function indexAction()
    { 
      $query = $this->_getParam('q');
      $hits = $this->_index->find($query.'*');
      /*foreach ($hits as $hit) {
          printf("%d %f %s<br />", $hit->id, $hit->score, $hit->image);
      }*/

      $paginator = Zend_Paginator::factory($hits);
      $paginator->setCurrentPageNumber($this->_getParam('page'));
      $paginator->setItemCountPerPage(16);

      $this->_session->previous = 'search';
      $this->_session->q = $query;
      $this->_session->page = $this->_getParam('page');

      $this->view->paginator = $paginator;
      $this->view->q = $query;
    }

    /*public function testAction()
    {
      system("rm -R ".LUCENE_PATH);

      $index = Zend_Search_Lucene::create(LUCENE_PATH);

      $fileTable = new Model_DbTable_Files();
      $select = $fileTable->select()->setIntegrityCheck(false)
                                    ->from($fileTable)
                                    ->join('drawings', 'drawings.id=files.fk_id', array('drawing_id' => 'id', 'drawing_name' => 'name', 'drawing_comment' => 'comment', 'drawing_tags' => 'tags'))
                                    ->join('subcategories','subcategories.id=drawings.sub_category_id',array('sub_category_name' => 'name'))
                                    ->join('categories','categories.id=subcategories.category_id',array('category_name' => 'name'))
                                    ->join('users','users.id=drawings.user_id',array('user_login' => 'login'))
                                    ->where('files.fk_name LIKE ?', '%drawings.id')
                                    ->where('drawings.visible = ?', 1)
                                    ->order('drawings.created_at DESC');
      $files = $fileTable->fetchAll($select);
      $pagesFiles = array();
      foreach ($files as $file)
      {
        $doc = new Zend_Search_Lucene_Document();
        $doc->addField(Zend_Search_Lucene_Field::Text('url', $this->view->drawingUrl($file->drawing_id, $file->drawing_name)));
        $doc->addField(Zend_Search_Lucene_Field::Text('title', $this->view->escape($file->drawing_name)));
        $doc->addField(Zend_Search_Lucene_Field::Text('image', $this->view->fileUrl($file, 210, 280)));
        $doc->addField(Zend_Search_Lucene_Field::Text('contents', $this->view->escape($file->drawing_comment)));
        $doc->addField(Zend_Search_Lucene_Field::Text('tags', $this->view->escape($file->drawing_tags)));
        $doc->addField(Zend_Search_Lucene_Field::Text('user', $file->user_login));
        $doc->addField(Zend_Search_Lucene_Field::Text('category', $file->category_name));
        $doc->addField(Zend_Search_Lucene_Field::Text('subCategory', $file->sub_category_name));

        $index->addDocument($doc);
      }
      exit;
    }*/
}
