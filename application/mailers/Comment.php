<?php

class Mailer_Comment extends Mailer_Base
{
  public function create(Model_Drawing $drawing, Model_Comment $comment)
  {
    $user = $drawing->findUser();
    $params = array(
      'login' => $user->login,
      'drawing' => $drawing,
      'comment' => $comment
    );

    $this->_mail->setSubject('Un nouveau commentaire a été posté sur votre fanart "'. $drawing->name .'"');
    $this->_mail->addTo($user->email, $user->login);
    $this->_setScriptHtml('comment', $params);

    return $this->_mail->send();
  }
}
