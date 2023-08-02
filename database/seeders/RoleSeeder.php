<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(['admin', 'responsable', 'referent', 'referent_regional', 'tete_de_reseau', 'responsable_territoire'] as $roleName) {
            Role::create(['name' => $roleName]);
        }
    }
}
