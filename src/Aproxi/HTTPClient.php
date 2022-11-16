<?php

namespace Aproxi;
use GuzzleHttp\Client;

class HTTPClient {
  public function __construct($config){
    $this->http_client = new Client($config);
  }

  public function minifyResponse($res, $format="plain"){
    $res = [
      "status" => $res->getStatusCode(),
      "headers" => $res->getHeaders(),
      "body" => (string)$res->getBody(),
    ];

    switch(strtolower($format)){
    case "json":
      $res["body"] = json_decode($res["body"]);
      break;

    // [TODO]
    case "csv":
    }

    return $res;
  }

  public function request($request_method, $endpoint, $params=[], $format="plain"){
    $params = ["query" => $params];
    return $this->minifyResponse($this->http_client->request($request_method, $endpoint, $params), $format);
  }

  public function get($endpoint, $params=[], $format="plain"){
    return $this->request("GET", $endpoint, $params, $format);
  }

  public function post($endpoint, $params=[], $format="plain"){
    return $this->request("POST", $endpoint, $params, $format);
  }
}
