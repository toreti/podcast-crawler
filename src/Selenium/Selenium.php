<?php

namespace App\Selenium;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

class Selenium
{
    public static function createChrome(string $url, int $secondsSleeping = 3): RemoteWebDriver
    {
        $host = 'http://localhost:4444/wd/hub';
        $capabilities = DesiredCapabilities::chrome();
        $driver = RemoteWebDriver::create($host, $capabilities, 5000);
        $driver->get($url);
        sleep($secondsSleeping);
        return $driver;
    }
}
