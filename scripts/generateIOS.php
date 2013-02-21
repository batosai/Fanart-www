<?php

require 'prepend.php';

function console($text)
{
  echo "\n\t - $text";
}

console('Démarrage');

$filesTable = new Model_DbTable_Files();


$select = $filesTable->select()->setIntegrityCheck(false)
                               ->from(array('f' => 'files'))
                               ->join(array('d' => 'drawings'), 'd.id=f.fk_id', array('drawing_id' =>'id', 'drawing_name' => 'name'))
                               ->join(array('u' => 'users'), 'u.id=d.user_id', array('user_id' =>'id', 'login'))
                               ->where('f.fk_name LIKE ?', '%drawings.id')
                               ->where('d.visible = ?', 1)
                               ->where('d.trash = ?', 0)
															 ->order('d.created_at DESC')->limit(1);

if ($rows = $filesTable->fetchAll($select))
{
  $drawings = array();
  foreach ($rows as $row)
  {
		$format = explode('.', $row->filename);
		$format = $format[1];
		$image = "{$row->drawing_id}.jpg";//"../public/ios/full/{$row->drawing_id}.jpg";
    $image2x = "../public/ios/full/{$row->drawing_id}@2x.jpg";
    $source = "../data/uploads/users/{$row->user_id}/drawings/{$row->guid}.$format";

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
  }
}