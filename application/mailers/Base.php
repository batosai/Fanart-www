<?php

class Mailer_Base
{
  protected $_mail;
  protected $_layout;
  protected $_view;

  public function __construct()
  {
    $transport = Zend_Mail::getDefaultTransport();

    if ($transport instanceof Zend_Mail_Transport_File) {
      $transport->setOptions(array('callback' => create_function('', 'return "mailer_' . date('Ymd_His') .'.eml";')));
    }

    $this->_mail = new Zend_Mail('UTF-8');

    $this->_layout = new Zend_Layout();
    $this->_layout->setLayoutPath(APPLICATION_PATH . '/layouts/mails');

    $this->_view = new Zend_View();
    $this->_view->setScriptPath(APPLICATION_PATH . '/views/mails');
    $this->_view->addHelperPath(APPLICATION_PATH . '/views/helpers');
    $this->_view->addHelperPath('J/View/Helper', 'J_View_Helper');
    $this->_view->addHelperPath('Opsone/View/Helper', 'Opsone_View_Helper');
  }

  protected function _setScriptHtml($script, array $params = array())
  {
    $this->_layout->assign($params);
    $this->_view->assign($params);

    $this->_layout->content = $this->_view->render($script . '.phtml');

    $html = $this->_layout->render();

    $this->_mail->setBodyHtml($html);
    $this->_mail->setBodyText(strip_tags($html));
  }
}
