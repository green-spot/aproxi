<?php

require_once "loader.php";

Aproxi\Aproxi::initialize(include __DIR__ . "/settings.php");
Aproxi\Aproxi::getInstance()->run();

//var_dump($aproxi->get("wp", "/posts"));
