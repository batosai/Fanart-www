<?php

class Account_Form_Password extends Form_Base
{

    public function init()
    {
      parent::init();

      $this->setAction( $this->_viewRenderer->url(array('module' => 'account', 'controller' => 'session', 'action' => 'update-password'), 'default', true))
           ->setMethod('post')
           ->setAttrib('class', 'form-horizontal')
           ->setTitle('Mot de passe oublié ?')
           ->setSuccess('Vous allez recevoir un e-mail avec votre nouveau mot de passe.');

      $email = $this->createElement('text', 'email', array('label' => 'E-mail'));
      $email->setRequired(true);
            /*->addValidator('NotEmpty', true, array('messages' => 'E-mail manquant'))
            ->addValidator('Db_RecordExists', false, array('table' => 'users', 'field' => 'email', 'messages' => 'E-mail non enregistré.'))
            ->addValidator('EmailAddress', false, array('messages' => array(
              Zend_Validate_EmailAddress::INVALID_HOSTNAME => 'Email incorrect',
              Zend_Validate_EmailAddress::INVALID_FORMAT => 'Email incorrect'
            )));*/

      $submit =  $this->createElement('submit', 'submit', array('label' => 'Envoyer'));
      $submit->setAttrib('class', 'btn primary')
             ->setDecorators(array('Submit'));

      $this->addElement($email)
           ->addElement($submit);
    }
}