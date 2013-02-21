<?php

class Account_Form_Signin extends Form_Base
{
    public function init()
    {
      parent::init();

      $this->setAction( $this->_viewRenderer->url(array('module' => 'account', 'controller' => 'session', 'action' => 'create'), 'default', true))
           ->setMethod('post')
           ->setAttrib('class', 'form-horizontal')
           ->setTitle('Espace membre');

      $login =  $this->createElement('text', 'login', array('label' => 'Pseudo'));
      $login->setRequired(true)
            ->addValidator('NotEmpty', true, array('messages' => 'Pseudo manquant'))
            ->setAttrib('class', 'xlarge');

      $password =  $this->createElement('password', 'password', array('label' => 'Mot de passe'));
      $password->setRequired(true)
               ->addValidator('NotEmpty', true, array('messages' => 'mot de passe manquant'))
               ->setAttrib('class', 'xlarge');

      $submit =  $this->createElement('submit', 'submit', array('label' => 'Connexion'));
      $submit->setAttrib('class', 'btn primary')
             ->setDecorators(array('Submit'));

      $this->addElement($login)
           ->addElement($password)
           ->addElement($submit);

      $this->defaultFilters();
    }
}