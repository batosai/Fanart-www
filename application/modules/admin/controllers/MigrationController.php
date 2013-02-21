<?php

class Admin_MigrationController extends Zend_Controller_Action
{
  private $_user;
  private $_drawing;
  private $_file;

  public function init()
  {
    $this->_user = new Model_DbTable_Users();
    $this->_drawing = new Model_DbTable_Drawings();
    $this->_file = new Model_DbTable_Files();
		set_time_limit(-1);
  }
/*
  public function dirAction()
  {
    $users = $this->_user->fetchAll();

    foreach($users as $user)
    {
      mkdir(UPLOADS_PATH . '/users/' . $user->id);
      mkdir(UPLOADS_PATH . '/users/' . $user->id . '/drawings/');
    }
    exit;
  }

  public function drawingAction()
  {
    $drawings = $this->_drawing->fetchAll();

    foreach($drawings as $drawing)
    {
      if(!$drawing->trash) {
        $drawing->visible = 1;
        $drawing->save();
      }
    }
    exit;
  }

  public function fileAction()
  {
    $drawings = $this->_drawing->fetchAll();

    foreach($drawings as $drawing)
    {
      $token = sha1(microtime() . uniqid(mt_rand(), true));
      $guid = substr($token, 0, 40);

      $file = $this->_file->createRow(array(
        'filename' => $drawing->id.'.jpg',
        'fk_name' => 'users/'.$drawing->user_id.'/drawings.id',
        'fk_id' => $drawing->id,
        'guid' => $guid,
        'created_at' => $drawing->created_at
      ));
      $file->save();
      //copy($this->_filepath, $this->getPath());
    }
    exit;
  }

  public function filecopyAction()
  {
    $files = $this->_file->fetchAll();

    foreach($files as $file)
    {
      list($prefix) = explode('.', $file->fk_name);

      if(!file_exists(UPLOADS_PATH . '/original/'.$file->fk_id.'.jpg')){
        echo $file->fk_id.'<br />';
        continue;
      }

      if(file_exists(UPLOADS_PATH .'/'. $prefix))
      if(!file_exists(UPLOADS_PATH .'/'. $prefix .'/'. $file->guid.'.jpg')) {
        copy(UPLOADS_PATH . '/original/'.$file->fk_id.'.jpg', UPLOADS_PATH .'/'. $prefix .'/'. $file->guid.'.jpg');
      }
    }
    exit;
  }
*/
/*
	public function imagesiosAction()
	{
		$filesTable = new Model_DbTable_Files();


		$select = $filesTable->select()->setIntegrityCheck(false)
		                               ->from(array('f' => 'files'))
		                               ->join(array('d' => 'drawings'), 'd.id=f.fk_id', array('drawing_id' =>'id', 'drawing_name' => 'name'))
		                               ->join(array('u' => 'users'), 'u.id=d.user_id', array('user_id' =>'id', 'login'))
		                               ->where('f.fk_name LIKE ?', '%drawings.id')
		                               ->where('d.visible = ?', 1)
		                               ->where('d.trash = ?', 0)
																	 ->order('d.created_at DESC');

		if ($rows = $filesTable->fetchAll($select))
		{
		  $drawings = array();
		  foreach ($rows as $row)
		  {
				$format = explode('.', $row->filename);
				$format = $format[1];
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
		exit;
	}
*/
}
