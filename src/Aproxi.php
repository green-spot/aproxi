<?php

namespace Aproxi;
require_once __DIR__ . "/functions.php";

class Aproxi {
  private static $singleton;

  private function __construct($settings){
    $this->modules = [];

    CacheStore::$root_dir = $settings["root_dir"];
    CacheStore::$cache_dir = $settings["cache_dir"];

    // create APIs
    foreach($settings["apis"] as $path => $setting){
      $klass = "Aproxi\\Module\\{$setting['module']}";
      $this->modules[$path] = new $klass($path, $setting);
    }
  }

  public static function initialize($settings){
    self::$singleton = new self($settings);
  }

  public static function getInstance(){
    return self::$singleton;
  }

  public function moduleExists($path){
    return isset($this->modules[$path]);
  }

  public function get($path, $endpoint, $params=[]){
    return $this->modules[$path]->getWithCache($endpoint, $params);
  }

  public function post($path, $endpoint, $params=[]){
    return $this->modules[$path]->post($endpoint, $params);
  }

  public function updateGetCache($path, $endpoint, $params=[]){
    return $this->modules[$path]->updateGetCache($endpoint, $params);
  }

  public function clearGetCache($path, $endpoint, $params=[]){
    return $this->modules[$path]->clearGetCache($endpoint, $params);
  }

  public function clearGetCacheAll($path){
    return $this->modules[$path]->clearGetCacheAll();
  }

  public function run(){
    foreach($this->modules as $path => $module){
      // add GET Route
      Router::get("^/{$path}(/.*)$", function($endpoint)use($module){
        $enabled_apis = el($module->setting["enabled_apis"], "get", "*");

        if($enabled_apis !== "*" && !in_array($endpoint, $enabled_apis)){
          http_response_code(404);
          return false;
        }

        $res = $module->getWithCache($endpoint, $_GET);
        $module->response($res, "get", $endpoint, $_GET);
      });

      // add POST Route
      Router::post("^/{$path}(/.*)$", function($endpoint)use($module){
        $enabled_apis = el($module->setting["enabled_apis"], "post", "*");

        if($enabled_apis !== "*" && !in_array($endpoint, $enabled_apis)){
          http_response_code(404);
          return false;
        }

        $res = $module->post($endpoint, $_POST);
        $module->response($res, "post", $endpoint, $_POST);
      });
    }

    Router::run(el($_SERVER, "PATH_INFO", "/"));
  }
}
