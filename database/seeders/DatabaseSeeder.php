<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
            CategorySeeder::class,
            DemoCatalogSeeder::class,
            HomepageSectionSeeder::class,
            CmsPageSeeder::class,
        ]);
    }
}
