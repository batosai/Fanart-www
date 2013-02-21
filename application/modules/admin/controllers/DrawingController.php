<?php

class Admin_DrawingController extends Zend_Controller_Action
{
  private $_table;

  public function init()
  {
    $this->_table = new Model_DbTable_Drawings();

    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'url' => $this->view->url(array('module' => 'admin'), 'default', true)
      ),
      array(
        'name' => 'Dessins',
        'active' => true
      )
    );
  }

  public function indexAction()
  {
    $select = $this->_table->select()->setIntegrityCheck(false)
                                     ->from($this->_table)
                                     ->join('subcategories','subcategories.id=drawings.sub_category_id',array('subcategories.name AS sub_category_name'))
                                     ->join('users','users.id=drawings.user_id',array('users.login AS login', 'users.email AS email'))
                                     ->order('drawings.id DESC');

    $paginator = Zend_Paginator::factory($select);
    $paginator->setCurrentPageNumber($this->_getParam('page'));
    $paginator->setItemCountPerPage(20);

    $this->view->paginator = $paginator;
  }

  public function editAction()
  {
    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'url' => $this->view->url(array(), 'default', true)
      ),
      array(
        'name' => 'Dessins',
        'url' => $this->view->url(array('module' => 'admin', 'controller' => 'drawing'), 'default', true)
      ),
      array(
        'name' => 'Editer',
        'active' => true
      )
    );

    $drawing = $this->_table->find($this->_getParam('id'))->current();

    $form = new Admin_Form_Drawing();
    $form->setAction( $this->view->url(array('module' => 'admin', 'controller' => 'drawing', 'action' => 'update', 'id' => $drawing->id), 'default', true));
    $form->setDefaults($drawing->toArray());

    $this->view->drawing = $drawing;
    $this->view->form = $form;
  }

  public function updateAction()
  {
    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'url' => $this->view->url(array(), 'default', true)
      ),
      array(
        'name' => 'Dessins',
        'url' => $this->view->url(array('module' => 'admin', 'controller' => 'drawing'), 'default', true)
      ),
      array(
        'name' => 'Editer',
        'active' => true
      )
    );

    $drawing = $this->_table->find($this->_getParam('id'))->current();

    $form = new Admin_Form_Drawing();
    $form->setAction( $this->view->url(array('module' => 'admin', 'controller' => 'drawing', 'action' => 'update', 'id' => $drawing->id), 'default', true));

    $data = $this->_request->getPost();

    if (!$form->isValid($data))
    {
      $this->_helper->flashMessenger($form);
      $this->view->form = $form;
      return $this->render('edit');
    }

    $drawing->sub_category_id = $form->sub_category_id->getValue();
    $drawing->tags = $form->tags->getValue();
    $drawing->save();

    $this->_helper->redirector('index', 'drawing', 'admin');
  }

  public function acceptAction()
  {
    $drawing = $this->_table->find($this->_getParam('id'))->current();
    $drawing->visible = 1;
    $drawing->save();
		$this->_imagesios($drawing->id);
    $this->_helper->redirector('index');
  }

  public function deleteAction()
  {
    $drawing = $this->_table->find($this->_getParam('id'))->current();
    $file = $drawing->findFile();

    $file->delete();
    $drawing->delete();

    $this->_helper->redirector('index');
  }

	public function _imagesios($id)
	{
		$filesTable = new Model_DbTable_Files();


		$select = $filesTable->select()->setIntegrityCheck(false)
		                               ->from(array('f' => 'files'))
		                               ->join(array('d' => 'drawings'), 'd.id=f.fk_id', array('drawing_id' =>'id', 'drawing_name' => 'name'))
		                               ->join(array('u' => 'users'), 'u.id=d.user_id', array('user_id' =>'id', 'login'))
		                               ->where('f.fk_name LIKE ?', '%drawings.id')
		                               ->where('d.visible = ?', 1)
		                               ->where('d.trash = ?', 0)
																	 ->where('d.id = ?', $id)
																	 ->order('d.created_at DESC');

		$row = $filesTable->fetchRow($select);
		
		$format = explode('.', $row->filename);
		$format = strtolower($format[1]);
		$image = UPLOADS_PATH."/../../public/ios/thumbnails/"."{$row->drawing_id}.jpg";
    $image2x = UPLOADS_PATH."/../../public/ios/thumbnails/{$row->drawing_id}@2x.jpg";
    $source = UPLOADS_PATH."/users/{$row->user_id}/drawings/{$row->guid}.$format";

    $newMaximumWidth = 104;
    $newMaximumHeight = 158;
    $canvas = new Imagick();
    $canvas->newImage($newMaximumWidth, $newMaximumHeight, "white");
    $canvas->setFormat("jpg");

    list($w, $h, $type) = getimagesize($source);
    $img = new Imagick($source);
    if ($w > $h) {
      $img->rotateImage(new ImagickPixel('none'), 90);
    }
    $img->ThumbnailImage($newMaximumWidth, $newMaximumHeight, true, false);

    $canvas->compositeImage($img, imagick::COMPOSITE_OVER, ($canvas->getImageWidth() - $img->getImageWidth() ) / 2, ($canvas->getImageHeight() - $img->getImageHeight() ) / 2);
    $canvas->writeImage($image);
    $canvas->destroy();
    $img->destroy();
		
		
		/////@2x
    $newMaximumWidth = 208;
    $newMaximumHeight = 316;
    $canvas = new Imagick();
    $canvas->newImage($newMaximumWidth, $newMaximumHeight, "white");
    $canvas->setFormat("jpg");

    list($w, $h, $type) = getimagesize($source);
    $img = new Imagick($source);
    if ($w > $h) {
      $img->rotateImage(new ImagickPixel('none'), 90);
    }
    $img->ThumbnailImage($newMaximumWidth, $newMaximumHeight, true, false);

    $canvas->compositeImage($img, imagick::COMPOSITE_OVER, ($canvas->getImageWidth() - $img->getImageWidth() ) / 2, ($canvas->getImageHeight() - $img->getImageHeight() ) / 2);
    $canvas->writeImage($image2x);
    $canvas->destroy();
    $img->destroy();


		/////full
		$image = UPLOADS_PATH."/../../public/ios/full/"."{$row->drawing_id}.jpg";
    $image2x = UPLOADS_PATH."/../../public/ios/full/{$row->drawing_id}@2x.jpg";
    $source = UPLOADS_PATH."/users/{$row->user_id}/drawings/{$row->guid}.$format";


    list($w, $h, $type) = getimagesize($source);
    $newMaximumWidth = 320;
    $newMaximumHeight = 480;
    if ($w > $h) {
      $newMaximumWidth = 480;
      $newMaximumHeight = 320;
    }

    $img = new Imagick($source);
    $img->ThumbnailImage($newMaximumWidth, $newMaximumHeight, true, false);
    $img->writeImage($image);
    $img->destroy();

    list($w, $h, $type) = getimagesize($source);
    $newMaximumWidth = 640;
    $newMaximumHeight = 960;
    if ($w > $h) {
      $newMaximumWidth = 960;
      $newMaximumHeight = 640;
    }

    $img = new Imagick($source);
    $img->ThumbnailImage($newMaximumWidth, $newMaximumHeight, true, false);
    $img->writeImage($image2x);
    $img->destroy();
	}
}
