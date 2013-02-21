<?php

class Form_Contact extends Form_Base
{
    public function init()
    {
      parent::init();

      $this->setAction( $this->_viewRenderer->url(array('controller' => 'contact', 'action' => 'send'), 'default', true))
           ->setMethod('post')
           ->setAttrib('class', 'form-horizontal')
           ->setTitle('Contact')
           ->setSuccess('Votre message à bien été envoyé.');

      $email =  $this->createElement('text', 'email', array('label' => 'Votre email'));
      $email->setRequired(true)
            ->addValidator('NotEmpty', true, array('messages' => 'Email manquant'))
            ->addValidator('EmailAddress', false, array('messages' => array(
              Zend_Validate_EmailAddress::INVALID_HOSTNAME => 'Email incorrect',
              Zend_Validate_EmailAddress::INVALID_FORMAT => 'Email incorrect'
            )))
            ->setAttrib('class', 'medium')
            ->setDecorators(array('Email'));

      $object =  $this->createElement('text', 'object', array('label' => 'Objet'));
      $object->setRequired(true)
             ->addValidator('NotEmpty', true, array('messages' => 'Objet manquant'))
             ->setAttrib('class', 'xlarge');

      $message = $this->createElement('textarea', 'message', array('label' => 'Votre message'));
      $message->setRequired(true)
              ->addValidator('NotEmpty', true, array('messages' => 'Message manquant'))
              ->setAttrib('class', 'span4')
              ->setAttrib('rows', '4')
              ->setdescription('Ce message est à la destination de l\'administrateur du site');

      $submit =  $this->createElement('submit', 'submit', array('label' => 'Enregistrer'));
      $submit->setAttrib('class', 'btn primary')
             ->setDecorators(array('Submit'));

      $this->addElement($email)
           ->addElement($object)
           ->addElement($message)
           ->addElement($submit);

      $this->defaultFilters();
    }
}