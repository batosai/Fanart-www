<?php

class Form_Signup extends Form_Base
{
    public function init()
    {
      parent::init();

      $this->setAction( $this->_viewRenderer->url(array('controller' => 'signup', 'action' => 'create'), 'default', true))
           ->setMethod('post')
           ->setAttrib('class', 'form-horizontal')
           ->setTitle('Inscription');

      $email =  $this->createElement('text', 'email', array('label' => 'Votre email'));
      $email->setRequired(true)
            ->addValidator('NotEmpty', true, array('messages' => 'Email manquant'))
            ->addValidator('EmailAddress', false, array('messages' => array(
              Zend_Validate_EmailAddress::INVALID_HOSTNAME => 'Email incorrect',
              Zend_Validate_EmailAddress::INVALID_FORMAT => 'Email incorrect'
            )))
            ->addValidator('Db_NoRecordExists', false, array('table' => 'users', 'field' => 'email', 'messages' => 'Email existe déjà.'))
            ->setAttrib('class', 'medium')
            ->setDecorators(array('Email'));

      $login =  $this->createElement('text', 'login', array('label' => 'Votre pseudo'));
      $login->setRequired(true)
            ->addValidator('NotEmpty', true, array('messages' => 'Pseudo manquant'))
            ->addValidator('Db_NoRecordExists', false, array('table' => 'users', 'field' => 'login', 'messages' => 'Le pseudo existe déjà.'))
            ->addValidator('StringLength', false, array(4, 'messages' => 'Pseudo incorrect : minimum 4 caractères'))
            ->addValidator('alnum', false, array('messages' => 'Pseudo incorrect : uniquement alphanumérique'))
            ->setAttrib('class', 'xlarge');

      $password = $this->createElement('password', 'password', array('label' => 'Mot de passe'));
      $password->setRequired(true)
               ->addValidator('NotEmpty', true, array('messages' => 'Mot de passe manquant'))
               ->addValidator('StringLength', false, array(4, 'messages' => 'Mot de passe incorrect : minimum 4 caractères'))
               ->addValidator('alnum', false, array('messages' => 'Mot de passe incorrect : uniquement alphanumérique'))
               ->setAttrib('class', 'xlarge');

      $password_confirm = $this->createElement('password', 'password_confirm', array('label' => 'Confirmation de votre mot de passe'));
      $password_confirm->setRequired(true)
                       ->addValidator('NotEmpty', true, array('messages' => 'Confirmation du mot de passe erronée'))
                       ->addValidator('Identical', false, array('password', 'messages' => 'Confirmation du mot de passe erronée'))
                       ->setAttrib('class', 'xlarge');

      $captcha = $this->createElement('captcha', 'captcha', array(
                     'label' => "Merci de saisir le cryptogramme visuel* :",
                     'captcha' => array(
                         'captcha' => 'image',
                         'wordLen' => 6,
                         'timeout' => 300,
                         'width' => 98,
                         'height' => 45,
                         'fontSize' => 16,
                         'dotNoiseLevel' => 0,
                         'lineNoiseLevel' => 0,
                         'imgDir' => APPLICATION_PATH . '/../temp/captcha/',
                         'imgUrl' => $this->_viewRenderer->baseUrl('media/captcha/'),
                         'font' => APPLICATION_PATH . '/../public/font/Arial.ttf',
                         'style' => 'vertical-align:center;'
                     )
                 ));
      $captcha->setAttrib('class', 'medium');

      $captcha->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'content-captcha'))
             ->addDecorator('Label', array('tag' => 'p'))
             ->removeDecorator('ElementCustom')
             ->removeDecorator('Errors')
             ->setErrorMessages(array('Captcha incorrect.'));

      $submit =  $this->createElement('submit', 'submit', array('label' => 'Enregistrer'));
      $submit->setAttrib('class', 'btn primary')
             ->setDecorators(array('Submit'));

      $this->addElement($email)
           ->addElement($login)
           ->addElement($password)
           ->addElement($password_confirm)
           ->addElement($captcha)
           ->addElement($submit);

      $this->defaultFilters();
    }
}