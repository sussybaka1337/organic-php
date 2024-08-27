<?php

namespace App\Libraries;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigEnvironment
{
    private static ?Environment $twig = null;
    private function __construct()
    {

    }
    public static function getInstance(): Environment
    {
        if (self::$twig === null) {
            $loader = new FilesystemLoader('resources/views');
            self::$twig = new Environment($loader);
            $globalFunctions = [
                new TwigFunction('auth', function () {
                    return auth();
                })
            ];
            foreach ($globalFunctions as $globalFunction) {
                self::$twig->addFunction($globalFunction);
            }
        }
        return self::$twig;
    }
}