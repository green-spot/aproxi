<?php

namespace Aproxi;

class NextRoute extends \Exception {}

class Router {
  /* route map array */
  static $map_get = [];
  static $map_post = [];
  static $map_other_methods = [];

  /* before routers */
  static $before_routers = [];

  /* --------------------------------------------------
   * route methods
   */
  public static function run($path_info, $request_method=null){
    if(!$request_method) $request_method = el($_SERVER, "REQUEST_METHOD");

    $path_info = "/" . ltrim($path_info, "/");
    $failed = true;

    switch($request_method){
    case "GET": $map = self::$map_get; break;
    case "POST": $map = self::$map_post; break;
    default: $map = self::$map_other_methods;
    }

    foreach(self::$before_routers as $before_router) $before_router();

    foreach($map as $set){
      $re = $set[0];
      $fn = $set[1];

      if(preg_match("/^".str_replace("/", "\\/", $re)."$/", $path_info, $args)){
        array_shift($args);
        $args = array_map(function($arg){
          return urldecode($arg);
        }, $args);

        try{
          call_user_func_array($fn, $args);
          $failed = false;
          break;
        }catch(NextRoute $e){
        }
      }
    }

    if($failed){
      header("Status: 404 Not Found");
      echo "404 not found.";
      exit;
    }
  }

  public static function get($re, $fn){
    self::$map_get[] = [$re, $fn];
  }

  public static function post($re, $fn){
    self::$map_post[] = [$re, $fn];
  }

  public static function other_methods($re, $fn){
    self::$map_other_methods[] = [$re, $fn];
  }

  public static function pass(){
    throw new NextRoute();
  }
}
