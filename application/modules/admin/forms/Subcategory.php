<?php

class Admin_Form_Subcategory extends Form_Base
{
    public function init()
    {
      parent::init();

      $table = new Model_DbTable_Categories();
      $select = $table->select()->from($table, array('id', 'name'));
      $categories = $table->getAdapter()->fetchPairs($select);

      $this->setAction( $this->_viewRenderer->url(array('module' => 'admin', 'controller' => 'subcategory', 'action' => 'create'), 'default', true))
           ->setMethod('post')
           ->setAttrib('class', 'form-horizontal')
           ->setTitle('Ajouter une sous-catégorie');

      $category_id = $this->createElement('select', 'category_id', array('label' => 'Categorie'));
      $category_id->setRequired(true)
                  ->addValidator('InArray', false, array(array_keys($categories)))
                  ->addMultiOptions(array('' => '') + $categories)
                  ->setErrorMessages(array('La categorie est manquante'))
                  ->setAttrib('class', 'xlInput');

      $name = $this->createElement('text', 'name', array('label' => 'Sous-Catégorie'));
      $name->setRequired(true)
           ->addValidator('NotEmpty', true, array('messages' => 'La sous-catégorie est manquante'))
           ->setAttrib('class', 'xlInput')
           ->setAttrib('id', 'sub_category_text');

      $submit = $this->createElement('submit', 'submit', array('label' => 'Enregistrer'));
      $submit->setAttrib('class', 'btn primary')
             ->setDecorators(array('Submit'));

      $this->addElement($category_id)
           ->addElement($name)
           ->addElement($submit);
//var_dump($categories);exit;
      $this->defaultFilters();
    }
}