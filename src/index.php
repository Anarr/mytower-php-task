<?php

require_once __DIR__ . '/../vendor/autoload.php';

$c = new \MyTower\App\Controllers\MainController(new \MyTower\Domain\Services\FileUploadService());
$c->uploadForm();