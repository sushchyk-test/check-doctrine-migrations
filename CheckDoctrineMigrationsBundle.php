<?php

namespace Alabama\CheckDoctrineMigrations;

use Alabama\CheckDoctrineMigrations\DependencyInjection\CheckDoctrineMigrationsExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CheckDoctrineMigrationsBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new CheckDoctrineMigrationsExtension();
    }
}
