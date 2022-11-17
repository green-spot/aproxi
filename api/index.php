<?php

require_once __DIR__ . "/../vendor/autoload.php";

Aproxi\Aproxi::initialize(include __DIR__ . "/settings.php");
Aproxi\Aproxi::getInstance()->run();

//var_dump($aproxi->get("wp", "/posts"));
