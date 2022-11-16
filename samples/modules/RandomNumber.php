<?php

namespace Aproxi\Module;

class RandomNumber extends ModuleBase {
  public function get($endpoint, $params=[]){
    return rand();
  }

  public function post($endpoint, $params=[]){
    return null;
  }

  public function response($res, $method, $endpoint, $params){
    if($method === "get"){
      http_response_code(200);
      header("Content-Type: text/plain");
      echo $res;
    }else{
      http_response_code(404);
    }
  }
}
