<?php

namespace FreeGeo\ApiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use \Symfony\Component\DependencyInjection\ContainerInterface;

class FreeGeoApiBundle extends Bundle
{

    private static $containerInstance = null;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        self::$containerInstance = $container;
    }

    public static function getContainer()
    {
        return self::$containerInstance;
    }
}
