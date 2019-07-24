<?php

class Archive{
  public static $archiveTypes = array('rar','zip','7z');
  public $unpackMethods = array('unp','unzip','p7zip -d');

  public function __construct(){}

  public static function extract($absolutePath, $destination = ""){
    $unpackIndex = array_search(pathinfo($absolutePath)['extension'],self::$archiveTypes);
    if($unpackIndex === false){
      throw new \Exception('No Support For ' . pathinfo($absolutePath)['extension']);
    }else{
      $unpackMethod = self::$unpackMethods[$unpackIndex];
    }
  }
}
