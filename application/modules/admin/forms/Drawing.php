<?php

class Admin_Form_Drawing extends Form_Base
{
    public function init()
    {
      parent::init();

      $this->setMethod('post')
           ->setAttrib('class', 'form-horizontal')
           ->setTitle('Modifier un dessin');

      $sub_category_id = $this->createElement('hidden', 'sub_category_id');
      $sub_category_id->setRequired(false);

      $sub_category_text = $this->createElement('text', 'sub_category_text', array('label' => 'Catégorie'));
      $sub_category_text->setRequired(true)
                        ->addValidator('NotEmpty', true, array('messages' => 'La catégorie est manquante'))
                        ->setAttrib('class', 'xlInput');

      $comment = $this->createElement('textarea', 'comment', array('label' => 'Description'));
      $comment->setAttrib('class', 'span6')
              ->setAttrib('rows', '4')
              ->setdescription('Veuillez donner des informations concernant votre dessin (outils utilisés, temps passé...)');
      
      $tags = $this->createElement('text', 'tags', array('label' => 'Tags'));
      $tags->setAttrib('class', 'xlInput');

      $submit = $this->createElement('submit', 'submit', array('label' => 'Enregistrer'));
      $submit->setAttrib('class', 'btn primary')
             ->setDecorators(array('Submit'));

      $this->addElement($sub_category_id)
           ->addElement($sub_category_text)
           ->addElement($tags)
           ->addElement($submit);

      $this->defaultFilters();
    }
}