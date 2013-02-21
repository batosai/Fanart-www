<?php

class Service_NetworkV1 extends Service_Base
{
  const VERSION = '1.0';

  public function getVersion()
  {
    return self::VERSION;
  }

  public function testing()
  {
    return array();
  }
}