<?php

class Mailer_User extends Mailer_Base
{
  public function create(Model_User $user, $password)
  {
    $params = array(
      'login' => $user->login,
      'password' => $password 
    );

    $this->_mail->setSubject('Confirmation d\'inscription');
    $this->_mail->addTo($user->email, $user->login);
    $this->_setScriptHtml('signup', $params);

    return $this->_mail->send();
  }

  public function password(Model_User $user, $password)
  {
    $params = array(
      'login' => $user->login,
      'password' => $password 
    );

    $this->_mail->setSubject('Nouveau mot de passe');
    $this->_mail->addTo($user->email, $user->login);
    $this->_setScriptHtml('password', $params);

    return $this->_mail->send();
  }
}
