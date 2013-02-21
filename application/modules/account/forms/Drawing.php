<?php

class Account_Form_Drawing extends Form_Base
{
    public function init()
    {
      parent::init();

      $this->setAction( $this->_viewRenderer->url(array('module' => 'account', 'controller' => 'drawing', 'action' => 'create'), 'default', true))
           ->setMethod('post')
           ->setAttrib('class', 'form-horizontal')
           ->setAttrib('enctype', 'multipart/form-data')
           ->setTitle('Ajouter un dessin');

      $sub_category_id = $this->createElement('hidden', 'sub_category_id');
      $sub_category_id->setRequired(false);

      $sub_category_text = $this->createElement('text', 'sub_category_text', array('label' => 'Catégorie'));
      $sub_category_text->setRequired(true)
                        ->addValidator('NotEmpty', true, array('messages' => 'La catégorie est manquante'))
                        ->setAttrib('class', 'xlInput');

      $name = $this->createElement('text', 'name', array('label' => 'Nom'));
      $name->setRequired(true)
           ->addValidator('NotEmpty', true, array('messages' => 'Le nom est manquant'))
           ->setAttrib('class', 'xlInput');

      $image = $this->createElement('file', 'image', array('label' => 'Votre dessin'));
      $image->setRequired(true)
            //->addValidator('Upload', true, array('messages' => 'Le fichier est manquant'))
            ->addValidator('Count', false, 1)
            ->addValidator('Size', false, '10MB')
            ->addValidator('Extension', false, array('jpeg,jpg,png,gif', 'messages' => 'Format non supporté'))
            //->setValueDisabled(true)
            ->setDecorators(array('File'));

      $comment = $this->createElement('textarea', 'comment', array('label' => 'Description'));
      $comment->setAttrib('class', 'span6')
              ->setAttrib('rows', '4')
              ->setdescription('Veuillez donner des informations concernant votre dessin (outils utilisés, temps passé...)');

      $tags = $this->createElement('text', 'tags', array('label' => 'Tags'));
      $tags->setAttrib('class', 'xlInput')
           ->setdescription('Mots clés séparés par un espace, utilisé par le moteur de recherche pour trouvé votre fan art');
              
      $cgu = $this->createElement('checkbox', 'cgu', array('label' => 'J\'ai lu et j\'accepte les conditions générales d\'utilisation'));
      $cgu->setRequired(true)
          ->addValidator('InArray', true, array(array(1), 'messages' => 'L\'acceptation des conditions générales est obligatoire'))
          ->setDecorators(array('Checkbox'));

      $submit = $this->createElement('submit', 'submit', array('label' => 'Enregistrer'));
      $submit->setAttrib('class', 'btn primary')
             ->setDecorators(array('Submit'));

      $this->addElement($sub_category_id)
           ->addElement($sub_category_text)
           ->addElement($name)
           ->addElement($image)
           ->addElement($comment)
           ->addElement($tags)
           ->addElement($cgu)
           ->addElement($submit);

      $this->defaultFilters();
    }
}