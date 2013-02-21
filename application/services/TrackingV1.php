<?php

class Service_TrackingV1 extends Service_Base
{
  const VERSION = '1.0';
  private $_table;
  private $_drawingsTable;

  public function __construct()
  {
    parent::__construct();
    $this->_table = new Model_DbTable_Trackings();
    $this->_drawingsTable = new Model_DbTable_Drawings();
  }

  public function getVersion()
  {
    return self::VERSION;
  }

  public function drawing($id=null)
  {
    if ($id)
    {
      $drawing = $this->_drawingsTable->find($id)->current();
      $drawing->view += 1;
      $drawing->save();
    }

    return array('error' => 'false');
  }

  public function launch($id=null)
  {
    extract($_POST);

    $select = $this->_table->select()->where('id = ?', $id);

    if ($row = $this->_table->fetchRow($select)) //update
    {
      $row->model = $model;
      $row->name = $name;
      $row->system_name = $system_name;
      $row->system_version = $system_version;
      $row->localized_model = $localized_model;
      $row->locale = $locale;
      $row->network = $network;
      $row->version = isset($version) ? $version : 1;
      $row->save();
    }
    else //insert
    {
      $row = $this->_table->createRow($_POST);
      $row->save();
    }

    return array('error' => 'false');
  }
}