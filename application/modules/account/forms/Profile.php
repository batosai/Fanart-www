<?php

class Account_Form_Profile extends Form_Base
{
    public function init()
    {
      parent::init();

      $this->setAction( $this->_viewRenderer->url(array('module' => 'account', 'controller' => 'profile', 'action' => 'update'), 'default', true))
           ->setMethod('post')
           ->setAttrib('class', 'form-horizontal')
           ->setAttrib('enctype', 'multipart/form-data')
           ->setTitle('Mes informations');

      $email = $this->createElement('text', 'email', array('label' => 'Votre email'));
      $email->setRequired(true)
            ->addValidator('NotEmpty', true, array('messages' => 'Email manquant'))
            ->addValidator('EmailAddress', false, array('messages' => array(
              Zend_Validate_EmailAddress::INVALID_HOSTNAME => 'Email incorrect',
              Zend_Validate_EmailAddress::INVALID_FORMAT => 'Email incorrect'
            )))
            ->addValidator('Db_NoRecordExists', false, array('table' => 'users', 'field' => 'email', 'messages' => 'Email existe déjà.', 'exclude' => array('field' => 'id', 'value' => $this->_viewRenderer->identity->id)))
            ->setAttrib('class', 'medium')
            ->setDecorators(array('Email'));

      $avatar = $this->createElement('file', 'avatar', array('label' => 'Votre avatar'));
      $avatar->addValidator('Extension', false, array('jpeg,jpg,png,gif', 'messages' => 'Format non supporté'))
             ->addValidator('Count', false, 1)
             ->addValidator('Size', false, '10MB')
             ->setDecorators(array('File'));

      $password = $this->createElement('password', 'password', array('label' => 'Mot de passe'));
      $password->setRequired(true)
               ->addValidator('NotEmpty', true, array('messages' => 'Mot de passe manquant'))
               ->setAttrib('class', 'xlarge');

      $password_confirm = $this->createElement('password', 'password_confirm', array('label' => 'Confirmation de votre mot de passe'));
      $password_confirm->setRequired(true)
                       ->addValidator('NotEmpty', true, array('messages' => 'Confirmation du mot de passe erronée'))
                       ->addValidator('Identical', false, array('password', 'messages' => 'Confirmation du mot de passe erronée'))
                       ->setAttrib('class', 'xlarge');

      $notification_comment = $this->createElement('checkbox', 'notification_comment', array('label' => 'Recevoir une notification par email lorsque je reçois un nouveau commentaire'));
      $notification_comment->setDecorators(array('Checkbox'));

      $notification_user = $this->createElement('checkbox', 'notification_user', array('label' => 'Recevoir une notification par email lorsqu\'une personne suivie ajoute un nouveau fanart.'));
      $notification_user->setDecorators(array('Checkbox'));

      $submit = $this->createElement('submit', 'submit', array('label' => 'Enregistrer'));
      $submit->setAttrib('class', 'btn primary')
             ->setDecorators(array('Submit'));

      $this->addElement($email)
           ->addElement($avatar)
           ->addElement($password)
           ->addElement($password_confirm)
           ->addElement($notification_comment)
           //->addElement($notification_user)
           ->addElement($submit);
    }
}