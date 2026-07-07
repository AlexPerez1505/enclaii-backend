<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CustomerSuccessRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permisos del módulo de Customer Success
        $permisos = [
            'customer_success.anuncios.view',
            'customer_success.anuncios.create',
            'customer_success.anuncios.update',
            'customer_success.anuncios.delete',
            'customer_success.politicas.manage',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso],
                ['guard_name' => 'web']
            );
        }

        // Rol Customer Success
        $rol = Role::firstOrCreate(
            ['name' => 'Customer Success'],
            ['guard_name' => 'web']
        );

        $rol->syncPermissions($permisos);
    }
}
