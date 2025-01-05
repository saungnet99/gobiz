<?php

namespace App\Providers;

use App\Classes\Migrate602;

class MigrationConfig
{
    public function alteration()
    {
        $migrate = new Migrate602;
        $migrate->tableCreation();
        
        return;
    }
}
