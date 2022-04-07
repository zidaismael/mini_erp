<?php

define('ROOT_DIR',__DIR__.'/../');

require "Autoloader.php";

Autoloader::registerAutoloader();
Autoloader::addClassesDirectory(ROOT_DIR.'sources/app/core');
Autoloader::addClassesDirectory(ROOT_DIR.'tests/fixture');
