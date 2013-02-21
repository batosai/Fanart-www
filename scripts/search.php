<?php

require 'prepend.php';

$indexPath = LUCENE_PATH;

system("rm -R $indexPath");

$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');

$index = Zend_Search_Lucene::create($indexPath);

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
  $doc->addField(Zend_Search_Lucene_Field::Keyword('drawing_id', $file->drawing_id));
  $doc->addField(Zend_Search_Lucene_Field::Text('url', $viewRenderer->view->drawingUrl($file->drawing_id, $file->drawing_name)));
  $doc->addField(Zend_Search_Lucene_Field::Text('title', $viewRenderer->view->escape($file->drawing_name)));
  //$doc->addField(Zend_Search_Lucene_Field::Text('image', $viewRenderer->view->fileUrl($file, 210, 280)));
  // $doc->addField(Zend_Search_Lucene_Field::Text('drawing_id', $file->drawing_id));
  $doc->addField(Zend_Search_Lucene_Field::Text('image', $viewRenderer->view->baseUrl('/') . "file/cache/guid/{$file->guid}/width/210/height/280"));
  $doc->addField(Zend_Search_Lucene_Field::Text('contents', $viewRenderer->view->escape($file->drawing_comment)));
  $doc->addField(Zend_Search_Lucene_Field::Text('tags', $viewRenderer->view->escape($file->drawing_tags)));
  $doc->addField(Zend_Search_Lucene_Field::Text('user', $file->user_login));
  $doc->addField(Zend_Search_Lucene_Field::Text('category', $file->category_name));
  $doc->addField(Zend_Search_Lucene_Field::Text('subCategory', $file->sub_category_name));
  
  $index->addDocument($doc);
}
system("chmod -R 777 $indexPath");