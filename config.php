<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Make sure required variables are set
$dotenv->required(['JWT_SECRET'])->notEmpty();