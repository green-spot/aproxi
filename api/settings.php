<?php

return [
  "root_dir" => __DIR__,
  "cache_dir" => __DIR__ . "/cache",

  "apis" => [
    "wp" => [
      /*
       * module
       */
      "module" => "WordPress",

      /*
       * cache settings
       */
      "cache_config" => [
        "enabled" => true,
        "expiration" => 60, // seconds
        "background" => true, // inspired by stale-while-revalidate
      ],

      /*
       * guzzle options
       */
      "http_client_config" => [
        //"auth" => ["", ""], BASIC AUTH
        "timeout" => 5.0,
      ],

      /*
       * endpoints
       * accept all: "get" => "*"
       * deny all: "get" => []
       */
      "enabled_apis" => [
        "get" => "*",
        "post" => []
      ],

      /*
       * filters
       */
      "filters" => [],

      // URL
      "base_url" => "",
    ],
  ]
];
