<?php

namespace Aproxi;

function el($o, $k, $d=null){
  return is_array($o) ? (isset($o[$k]) ? $o[$k] : $d) : (isset($o->$k) ? $o->$k : $d);
}

function require_all($path, $recursive=true){
  foreach(scandir($path) as $fname){
    if(!preg_match("/^\\./", $fname)){
      if(is_file($path . $fname)){
        require_once $path . $fname;
      }else if($recursive && is_dir($path . $fname)){
        require_all($path . $fname . "/", true);
      }
    }
  }
}