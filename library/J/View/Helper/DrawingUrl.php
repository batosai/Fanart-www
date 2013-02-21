<?php

class Zend_View_Helper_DrawingUrl extends Zend_View_Helper_Abstract
{
  public function drawingUrl($id, $name)
  {
    $filter = new Opsone_Filter_Slug();

    return $this->view->url(array('id' => $id, 'slug' => $filter->filter($name)), 'drawing', true);

    return $this->view->url(array('controller' => 'drawing', 'action' => 'show', 'id' => $id), 'default', true);
  }
}