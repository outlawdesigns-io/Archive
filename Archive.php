<?php

class Archive{
  public static $archiveTypes = array(
    'rar',
    'zip',
    '7z',
    'tar',
    'gz'
  );
  public static $unpackMethods = array(
    'unrar -x ',
    'unzip ',
    '7zr x ',
    'tar xvzf ',
    'tar xvzf '
  );
  public static $packMethods = array(
    '',
    'zip -r ',
    '7zr a ',
    'tar -czvf ',
    'tar -czvf '
  );
  public static $errorPatterns = array(
    "/cannot/i",
    "/unknown/i",
    "/error/i",
    "/failure/i",
    "/warning/i"
  );

  public function __construct(){}

  public static function extract($absolutePath, $destination = ""){
    $unpackIndex = array_search(pathinfo($absolutePath)['extension'],self::$archiveTypes);
    if($unpackIndex === false){
      throw new \Exception('No Support For ' . pathinfo($absolutePath)['extension']);
    }else{
      $unpackMethod = self::$unpackMethods[$unpackIndex];
    }
    if($destination){
      try{
        $destinationArg = self::getDestinationArg(pathinfo($absolutePath)['extension']);
      }catch(Exception $e){
        throw new Exception($e->getMessage());
      }
      $output = shell_exec($unpackMethod . escapeshellarg($absolutePath) . $destinationArg . $destination . " 2>&1");
    }else{
      $output = shell_exec($unpackMethod . escapeshellarg($absolutePath) . " 2>&1");
    }
    if(!self::validateOutput($output)){
      throw new Exception($output);
    }
    return $output;
  }
  public static function getDestinationArg($archiveType){
    switch($archiveType){
      case self::$archiveTypes[0]:
        return " ";
      break;
      case self::$archiveTypes[1]:
        return " -d ";
      break;
      case self::$archiveTypes[2]:
        return " -o";
      break;
      case self::$archiveTypes[3]:
        return " -C ";
      break;
      case self::$archiveTypes[4]:
        return " -C ";
      break;
      default:
        throw new Exception("No Support For " . $archiveType);
    }
  }
  public static function validateOutput($output){
    foreach(self::$errorPatterns as $errorPattern){
      if(preg_match($errorPattern,$output)){
        return false;
      }
    }
    return true;
  }
  public static function create($archive,$input){
    $packIndex = array_search(pathinfo($archive)['extension'],self::$archiveTypes);
    if($packIndex === false || $packIndex == 0){
      throw new \Exception('No Support For ' . pathinfo($absolutePath)['extension']);
    }else{
      $packMethod = self::$packMethods[$packIndex];
    }
    $output = shell_exec($packMethod . escapeshellarg($archive) . " " . escapeshellarg($input) . " 2>&1");
    if(!self::validateOutput($output)){
      throw new Exception($output);
    }
    return $output;
  }
  public static function add($archive,$file){}
}
