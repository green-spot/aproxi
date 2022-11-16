<?php

namespace Aproxi\Module;
use Aproxi\Aproxi;

class ClearCache extends ModuleBase {
  public function get($endpoint, $params=[]){
    $aproxi = Aproxi::getInstance();

    $endpoint = ltrim($endpoint, "/");
    if($aproxi->moduleExists($endpoint)){
      $aproxi->clearGetCacheAll($endpoint);
      return 1;
    }

    return 0;
  }

  public function post($endpoint, $params=[]){
    return $this->get($endpoint, $params);
  }

  public function response($res, $method, $endpoint, $params){
    http_response_code(200);
    header("Content-Type: text/plain");
    echo $res;
  }
}
