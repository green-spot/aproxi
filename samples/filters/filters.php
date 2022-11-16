<?php

namespace Aproxi\Filter;

function normalizeWpRestApi($res, $endpoint, $params=[]){
  if($endpoint !== "/posts") return $res;

  foreach($res["body"] as $record){
    if(isset($record->content->rendered)){
      $record->content = $record->content->rendered;
    }

    if(isset($record->title->rendered)){
      $record->title = $record->title->rendered;
    }
  }

  return $res;
}


function replaceURL($res, $endpoint, $params=[]){
  if($endpoint !== "/posts") return $res;

  foreach($res["body"] as $record){
    $record->content = str_replace("example.com", "example.co.jp", $record->content);
  }

  return $res;
}
