<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Nwidart\Modules\Facades\Module;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $namespace = config('modules.namespace', 'Modules');
        foreach (Module::allEnabled() as $module) {
            $seederClass = $namespace . '\\' . $module->getName() . '\\Database\\Seeders\\' . $module->getName() . 'DatabaseSeeder';
            if (class_exists($seederClass)) {
                $this->call($seederClass);
            }
        }
    }
}
