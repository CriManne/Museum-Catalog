<?php

declare(strict_types=1);

namespace App\Util;

use DI\Container;
use DI\ContainerBuilder;

class DIC {

    /**
     * Return the DI container
     * @return Container The DI container
     * @throws \Exception
     */
    public static function getContainer(): Container {
        $builder = new ContainerBuilder();
        $builder->addDefinitions('config/container.php');
        return $builder->build();
    }
}
