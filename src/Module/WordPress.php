<?php

namespace Aproxi\Module;

class WordPress extends ModuleBase {
  public function getEndpoint($endpoint){
    $base_url = $this->setting["base_url"];
    return "{$base_url}/wp-json/wp/v2{$endpoint}";
  }

  public function get($endpoint, $params=[]){
    return $this->http_client->get($this->getEndpoint($endpoint), $params, "json");
  }

  public function post($endpoint, $params=[]){
    return $this->http_client->post($this->getEndpoint($endpoint), $params, "json");
  }

  public function response($res, $method, $endpoint, $params){
    http_response_code($res["status"]);
    header("Content-Type: application/json");

    // WP REST API固有のHTTPヘッダを追加
    $accept_headers = [
      "X-WP-Total",
      "X-WP-TotalPages"
    ];

    foreach($accept_headers as $header){
      $header_var = $res["headers"][$header];
      if(isset($header_var[0])) header("{$header}: {$header_var[0]}");
    }

    echo json_encode($res["body"]);
  }
}
