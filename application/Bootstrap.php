<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

  protected function _initResourceLoader()
  {
    $this->getResourceLoader()->addResourceType('mailer', 'mailers', 'Mailer');
  }

  protected function _initCache()
  {
    $this->bootstrap('cachemanager');
    $cache = $this->getResource('cachemanager')->getCache('default');

    Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
    Zend_Locale::setCache($cache);
    Zend_Translate::setCache($cache);

    return $cache;
  }

  protected function _initConfig()
  {
    $this->bootstrap('db');

    $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/config.ini', APPLICATION_ENV, array('allowModifications' => true));

    define('UPLOADS_PATH', realpath(APPLICATION_PATH . '/../data/uploads'));
    define('LUCENE_PATH', realpath(APPLICATION_PATH . '/../temp').'/search');

    /*$categoriesTable = new Model_DbTable_ArticleCategories();

    $config->categories = $categoriesTable->fetchLabels();*/

    return $config;
  }

  protected function _initRegistry()
  {
    $this->bootstrap('db');
    $this->bootstrap('cache');
    $this->bootstrap('log');
    $this->bootstrap('config');

    Zend_Registry::set('Zend_Cache', $this->getResource('cache'));
    Zend_Registry::set('Zend_Db', $this->getResource('db'));
    Zend_Registry::set('Zend_Log', $this->getResource('log'));
    Zend_Registry::set('Zend_Config', $this->getResource('config'));
  }

  protected function _initViewHelpers()
  {
    $this->bootstrap('view');

    $view = $this->getResource('view');
    $view->addHelperPath('Opsone/View/Helper', 'Opsone_View_Helper');
    $view->addHelperPath('J/View/Helper', 'J_View_Helper');
  }

  protected function _initSecureSession()
  {
    $this->bootstrap('session');

    $session = new Zend_Session_Namespace();

    if ($session->initialized === null)
    {
      Zend_Session::regenerateId();
      $session->initialized = true;
    }

    return $session;
  }

  protected function _initRoutes()
  {
    $this->bootstrap('locale');
    $this->bootstrap('frontController');

    $locale = $this->getResource('locale');
    $frontController = $this->getResource('frontController');

    $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini');

    $router = $frontController->getRouter();
    $router->addConfig($config, 'routes');

    return $router;
  }

  protected function _initPlugins()
  {
    $this->bootstrap('frontController');

    $frontController = $this->getResource('frontController');
    $frontController->registerPlugin(new Plugin_Navigation());
  }

  protected function _initNavigation()
  {
    return $navigation = new Zend_Navigation();
  }

  protected function _initZFDebug()
  {
    $this->bootstrap('frontController');

    $options = array(
      'plugins' => array(
        'Html',
        'Exception',
        'File' => array(
          'base_path' => APPLICATION_PATH,
          'library' => array(
            'Opsone'
          )
        ),
        'Variables',
        'Memory',
        'Time',
        'Log'
      ),
    );

    if ($this->hasResource('db'))
    {
      $this->bootstrap('db');
      $db = $this->getResource('db');
      $options['plugins']['Database']['adapter'] = $db;
    }

    if ($this->hasResource('cache'))
    {
      $this->bootstrap('cache');
      $cache = $this->getResource('cache');
      $options['plugins']['Cache']['backend'] = $cache->getBackend();
    }

    if ($this->getEnvironment() == 'development')
    {
      $autoloader = Zend_Loader_Autoloader::getInstance();
      $autoloader->registerNamespace('ZFDebug');

      $debug = new ZFDebug_Controller_Plugin_Debug($options);

      $frontController = $this->getResource('frontController');
      // $frontController->registerPlugin($debug);
    }
  }
}

