<?php

class Archive{
  public static $archiveTypes = array('rar','zip','7z');
  public $unpackMethods = array('unp','unzip','p7zip -d');
}
