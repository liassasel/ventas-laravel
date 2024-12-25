<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use App\Models\Product;
use App\Models\User;
use App\Models\Store;

class CheckDatabaseData extends Command
{
    protected $signature = 'check:data';
    protected $description = 'Check if there is data in the main tables';

    public function handle()
    {
        $this->info('Checking database data...');

        $this->checkModel(Sale::class, 'Sales');
        $this->checkModel(Product::class, 'Products');
        $this->checkModel(User::class, 'Users');
        $this->checkModel(Store::class, 'Stores');

        $this->info('Database check completed.');
    }

    private function checkModel($model, $name)
    {
        $count = $model::count();
        $this->info("$name count: $count");
        
        if ($count > 0) {
            $latest = $model::latest()->first();
            $this->info("Latest $name: " . json_encode($latest));
        }
    }
}

