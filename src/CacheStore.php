<?php

namespace Aproxi;
use GuzzleHttp\Psr7\Request;

class CacheStore {
  static $root_dir = __DIR__ . "/../..";
  static $cache_dir = "/tmp";

  public function __construct($namespace, $setting){
    $this->namespace = $namespace;
    $this->directory = self::$cache_dir . "/" . $this->namespace;
    $this->setting = $setting;
  }

  public function getFilePath($key){
    return $this->directory . "/" . hash("sha256", $key);
  }

  public function isEnabled(){
    return el($this->setting, "enabled", false);
  }

  public function isBackgroundUpdates(){
    return el($this->setting, "background", false);
  }

  public function expired($key){
    if(!isset($this->setting["expiration"])) return false;
    return filemtime($this->getFilePath($key)) < time() - $this->setting["expiration"];
  }

  public function withCache($key, $getter){
    $dir = $this->directory;
    if(!file_exists($dir)) mkdir($dir, 0755, true);

    $fpath = $this->getFilePath($key);

    if(file_exists($fpath)){
      if($this->expired($key)){
        if($this->isBackgroundUpdates()){
          $root_dir = self::$root_dir;
          $shell_arg = escapeshellarg($key);
          exec("php {$root_dir}/bg-updater.php {$this->namespace} {$shell_arg}  > /dev/null &");

          return unserialize(file_get_contents($fpath));

        }else{
          $this->clear($key);
        }

      }else{
        return unserialize(file_get_contents($fpath));
      }
    }

    $res = $getter();
    file_put_contents($fpath, serialize($res), LOCK_EX);
    return $res;
  }

  public function update($key, $content){
    $fpath = $this->getFilePath($key);
    file_put_contents($fpath, serialize($content), LOCK_EX);
  }

  public function clear($key){
    $fpath = $this->getFilePath($key);
    if(file_exists($fpath)) unlink($fpath);
  }

  public function clearAll(){
    $dir = $this->directory . "/";

    foreach(scandir($dir) as $fname){
      if(!preg_match("/^\\./", $fname) && is_file($dir . $fname)){
        unlink($dir . $fname);
      }
    }
  }
}
