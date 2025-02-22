<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run()
    {
        $organizations = [
            [
                'name' => 'Computer Society',
                'description' => 'Organization for computer enthusiasts',
                'college' => 'CEIT',
            ],
            [
                'name' => 'Mathematics Club',
                'description' => 'For students passionate about mathematics',
                'college' => 'CAS',
            ],
            // Add more organizations as needed
        ];

        foreach ($organizations as $org) {
            Organization::create($org);
        }
    }
} 