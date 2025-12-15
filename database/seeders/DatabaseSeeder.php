<?php

namespace Database\Seeders;

use App\Models\Categories;
use App\Models\Payment;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        User::factory(10)->create();

        Categories::factory()->create([
            'name' => 'Coffee',
        ]);

        Categories::factory()->create([
            'name' => 'Non-coffee',
        ]);

        Categories::factory()->create([
            'name' => 'Tea',
        ]);

        Categories::factory()->create([
            'name' => 'Snacks',
        ]);
        
        $this->call([
            MenuItemSeeder::class,
            CartSeeder::class,
            CartItemSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
