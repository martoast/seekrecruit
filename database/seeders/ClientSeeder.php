<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        // The create_clients_and_add_tenant_foreign_keys migration inserts a
        // default JAE Tijuana client for existing data. Use firstOrCreate so
        // `migrate:fresh --seed` doesn't double up.
        $clients = [
            [
                'name' => 'JAE Tijuana',
                'slug' => 'jae-tijuana',
                'industry' => 'Manufacturing',
                'is_active' => true,
            ],
            [
                'name' => 'Acme Engineering',
                'slug' => 'acme-engineering',
                'industry' => 'Automotive',
                'is_active' => true,
            ],
            [
                'name' => 'Tijuana Electronics',
                'slug' => 'tijuana-electronics',
                'industry' => 'Electronics & IoT',
                'is_active' => true,
            ],
        ];

        foreach ($clients as $client) {
            Client::firstOrCreate(['slug' => $client['slug']], $client);
        }
    }
}
