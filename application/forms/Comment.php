<?php

class Form_Comment extends Form_Base
{
    public function init()
    {
      parent::init();

      $this->setAction( $this->_viewRenderer->url(array('controller' => 'drawing', 'action' => 'create'), 'default', true))
           ->setMethod('post')
           ->setAttrib('class', 'form-horizontal')
           ->setTitle('Ajouter un commentaires')
           ->setSuccess('Votre commentaire est enregistré.');

      $note = $this->createElement('radio', 'note', array('label' => 'Votre note'));
      $note->setRequired(true)
           ->addValidator('NotEmpty', true, array('messages' => 'Veuillez donner une note'))
           ->addMultiOptions(array(1=>1, 2=>2, 3=>3, 4=>4, 5=>5))
           ->setDecorators(array('Stars'));

      $value = $this->createElement('textarea', 'value', array('label' => 'Votre message'));
      $value->setRequired(true)
            ->addValidator('NotEmpty', true, array('messages' => 'Le message est manquant'))
            ->setAttrib('class', 'span4')
            ->setAttrib('rows', '4')
            ->setdescription('L\'administrateur se réserve le droit de supprimer tout commentaire.');

      $drawing_id =  $this->createElement('hidden', 'drawing_id');

      $submit =  $this->createElement('submit', 'submit', array('label' => 'Enregistrer'));
      $submit->setAttrib('class', 'btn primary')
             ->setDecorators(array('Submit'));

      $this->addElement($drawing_id)
           ->addElement($note)
           ->addElement($value)
           ->addElement($submit);

      $this->defaultFilters();
    }
}