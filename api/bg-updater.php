<?php

if(php_sapi_name() !== "cli") exit;

require_once "loader.php";

Aproxi\Aproxi::initialize(include __DIR__ . "/settings.php");

$namespace = $argv[1];
list($endpoint, $params) = unserialize($argv[2]);
Aproxi\Aproxi::getInstance()->updateGetCache($namespace, $endpoint, $params);
