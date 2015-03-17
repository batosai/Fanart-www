<?php

class Zend_View_Helper_FileUrl extends Zend_View_Helper_Abstract
{
  public function fileUrl(Model_File $file, $width = null, $height = null, $type = null)
  {
    $action = $type ? $type : 'cache';
    switch (pathinfo($file->getPath(), PATHINFO_EXTENSION))
    {
      case 'jpg':
      case 'jpeg':
      case 'gif':
      case 'png':
        return $this->view->url(array('controller' => 'file', 'action' => $action, 'guid' => $file->guid, 'width' => $width, 'height' => $height), 'default', true);
      break;

      default:
        return $this->view->url(array('controller' => 'file', 'action' => 'get', 'guid' => $file->guid), 'default', true);
    }
  }
}
