<?php

namespace Facebook\WebDriver;

require 'vendor/autoload.php';

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

header("Content-type: text/html; charset=utf-8");

require_once('vendor/autoload.php');

$host = 'http://localhost:4444/wd/hub';
$capabilities = DesiredCapabilities::chrome();
$driver = RemoteWebDriver::create($host, $capabilities, 5000);

$url = "https://podcasts.google.com/";
$driver->get($url);
echo "Buscando URLs...\n";

sleep(3);

$element = $driver->findElement(WebDriverBy::tagName("a"));
//$element = $element->getText();
var_dump($element);

//$driver->quit();
echo "Fim da busca\n";
