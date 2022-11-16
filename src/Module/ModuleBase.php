<?php

namespace Aproxi\Module;

abstract class ModuleBase {
  public function __construct($path, $setting){
    $this->path = $path;
    $this->setting = $setting;
    $this->http_client = new \Aproxi\HTTPClient(\Aproxi\el($setting, "http_client_config", []));
    $this->store = new \Aproxi\CacheStore($path, \Aproxi\el($setting, "cache_config", []));
  }

  public function applyFilters($res, $endpoint, $params){
    foreach(\Aproxi\el($this->setting, "filters", []) as $filter_name){
      $filter = "Aproxi\\Filter\\{$filter_name}";
      $res = call_user_func_array($filter, [$res, $endpoint, $params]);
    }

    return $res;
  }

  public function getWithCache($endpoint, $params=[]){
    if(!$this->store->isEnabled()) return $this->applyFilters($this->get($endpoint, $params), $endpoint, $params);

    $module = $this;
    $key = serialize([$endpoint, $params]);
    return $this->store->withCache($key, function()use($module, $endpoint, $params){
      return $module->applyFilters($module->get($endpoint, $params), $endpoint, $params);
    });
  }

  public function updateGetCache($endpoint, $params=[]){
    $module = $this;
    $key = serialize([$endpoint, $params]);
    $this->store->update($key, $module->applyFilters($module->get($endpoint, $params), $endpoint, $params));
  }

  public function clearGetCache($endpoint, $params=[]){
    $key = serialize([$endpoint, $params]);
    $this->store->clear($key);
  }

  public function clearGetCacheAll(){
    $this->store->clearAll();
  }

  abstract public function get($endpoint, $params=[]);
  abstract public function post($endpoint, $params=[]);
  abstract public function response($res, $method, $endpoint, $params);
}
