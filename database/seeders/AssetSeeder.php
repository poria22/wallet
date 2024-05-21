<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Asset::create([
            'name' => 'cash',
            'price' => 1
        ]);

        Asset::create([
            'name' => 'coin',
            'price' => 5
        ]);

        Asset::create([
            'name' => 'diamond',
            'price' => 10
        ]);
    }
}
