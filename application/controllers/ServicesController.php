<?php

class ServicesController extends Zend_Controller_Action
{
  private $_server;
  private $_version;

  public function init()
  {
    $this->_helper->layout->disableLayout();
    $this->_helper->viewRenderer->setNoRender();
    $this->_version = $this->_getParam('v', 1);

    $this->_server = new Zend_Json_Server();
    
    if($this->_getParam('key') != 'ea5af636cd2c0c07242ee43c07cbefbd')
    {
      exit;
    }

    if($this->_getParam('format') == 'json')
    {
      $jsonRequest = new Zend_Json_Server_Request();

      $jsonRequest->loadJson(
        json_encode(array('jsonrpc' => '2.0',
        'method' => $this->_getParam('method'),
        'params' => $this->getRequest()->getQuery(),
        'id' => time()// 0 = app desactivé.
      )));

      $this->_server->setRequest($jsonRequest);
    }

    if(!isset($jsonRequest) && !$this->_server->getRequest()->getRawJson())
    {
      $this->_server = new Zend_Rest_Server();
    }
  }

  public function networkAction()
  {
    $this->_server->setClass('Service_NetworkV1');
    echo $this->_server->handle();
  }

  public function drawingsAction()
  {
    $this->_server->setClass('Service_DrawingsV1');
    echo $this->_server->handle();
  }

  public function categoriesAction()
  {
    $this->_server->setClass('Service_CategoriesV1');
    echo $this->_server->handle();
  }

  public function trackingAction()
  {
    $this->_server->setClass('Service_TrackingV1');
    echo $this->_server->handle();
  }
}
