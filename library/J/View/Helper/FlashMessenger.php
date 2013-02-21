<?php
class J_View_Helper_FlashMessenger extends Zend_View_Helper_FormErrors
{
  private $_messages;
  private $_type;

  public function __construct()
  {
    $this->setElementStart('<div %s><a class="close" href="#">×</a><p>');
    $this->setElementSeparator ('</p><p>');
    $this->setElementEnd('</p></div>');

    $this->_messages = array();

    $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');

    $datas = $flashMessenger->hasCurrentMessages() ? $flashMessenger->getCurrentMessages() : $flashMessenger->getMessages();

    foreach ($datas as $data)
    {
        if ($data instanceof Zend_Form)
        {
            foreach ($data as $v)
            {
              if($formMessages = $v->getMessages())
              {
                foreach ($formMessages as $message)
                {
                    $this->_messages[] = $message;
                }
                $this->_type = 'ERRORS';
              }
            }

            if(!count($this->_messages))
            {
              $this->_messages[] = $data->getSuccess();
              $this->_type = 'SUCCESS';
            }
        }
        elseif(is_array($data))
        {
          $this->_type = 'SUCCESS';
          if(count($data) > 1) {
            $this->_type = $data[1];
          }
          $this->_messages[] = $data[0];
        }
        else {
          $this->_messages[] = $data;
          $this->_type = 'SUCCESS';
        }
    }

    $flashMessenger->clearCurrentMessages();
    $flashMessenger->clearMessages();
  }

  public function flashMessenger()
  {
    $params = array('class' => 'alert');
    switch($this->_type)
    {
      case('ERRORS'): $params['class'] .= ' alert-error';
        break;
      case('SUCCESS'): $params['class'] .= ' alert-success';
        break;
    }

    if(!empty($this->_messages)) {
      return $this->formErrors($this->_messages, $params);
    }

  }
}
