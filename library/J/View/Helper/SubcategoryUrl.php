<?php

class Zend_View_Helper_SubcategoryUrl extends Zend_View_Helper_Abstract
{
  public function subcategoryUrl(Model_Subcategory $subcategory)
  {
    $filter = new Opsone_Filter_Slug();

    return $this->view->url(array('id' => $subcategory->id, 'slug' => $filter->filter($subcategory->name)), 'category', true);

    return $this->view->url(array('controller' => 'category', 'action' => 'show', 'id' => $subcategory->id), 'default', true);
  }
}