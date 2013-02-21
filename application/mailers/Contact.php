<?php

class Mailer_Contact extends Mailer_Base
{
  public function create($post)
  {
    $params = array(
      'post' => $post
    );

    $this->_mail->setSubject('Fan art formulaire de contact');
    $this->_mail->addTo($this->_view->config()->contact);
    $this->_setScriptHtml('contact', $params);

    return $this->_mail->send();
  }
}
