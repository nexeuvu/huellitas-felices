<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear el rol principal
        $adminRole = Role::create(['name' => 'Administrador']);

        // Lista de permisos organizados por entidad
        $permissions = [
            // Panel principal
            'dashboard.view',

            // Cliente
            'admin.customer.index',
            'admin.customer.store',
            'admin.customer.update',
            'admin.customer.destroy',
            'admin.customer.consultar-dni',
            'admin.customer.export-pdf',
            'admin.customer.export-excel',

            // Empleado
            'admin.employee.index',
            'admin.employee.store',
            'admin.employee.update',
            'admin.employee.destroy',
            'admin.employee.consultar-dni',
            'admin.employee.export-pdf',
            'admin.employee.export-excel',

            // Especie
            'admin.species.index',
            'admin.species.store',
            'admin.species.update',
            'admin.species.destroy',
            'admin.species.export-pdf',
            'admin.species.export-excel',

            // Raza
            'admin.breed.index',
            'admin.breed.store',
            'admin.breed.update',
            'admin.breed.destroy',
            'admin.breed.export-pdf',
            'admin.breed.export-excel',

            // Mascota
            'admin.pet.index',
            'admin.pet.store',
            'admin.pet.update',
            'admin.pet.destroy',
            'admin.pet.export-pdf',
            'admin.pet.export-excel',

            // Servicio
            'admin.service.index',
            'admin.service.store',
            'admin.service.update',
            'admin.service.destroy',
            'admin.service.export-pdf',
            'admin.service.export-excel',

            // Veterinario
            'admin.veterinary.index',
            'admin.veterinary.store',
            'admin.veterinary.update',
            'admin.veterinary.destroy',
            'admin.veterinary.export-pdf',
            'admin.veterinary.export-excel',

            // Cita
            'admin.appointment.index',
            'admin.appointment.store',
            'admin.appointment.update',
            'admin.appointment.destroy',
            'admin.appointment.export-pdf',
            'admin.appointment.export-excel',

            // Historial Médico
            'admin.history.index',
            'admin.history.store',
            'admin.history.update',
            'admin.history.destroy',
            'admin.history.export-pdf',
            'admin.history.export-excel',

            // Factura
            'admin.invoices.index',
            'admin.invoices.store',
            'admin.invoices.update',
            'admin.invoices.destroy',
            'admin.invoices.export-pdf',
            'admin.invoices.export-excel',

            // Detalle Factura
            'admin.invoices_detail.index',
            'admin.invoices_detail.store',
            'admin.invoices_detail.update',
            'admin.invoices_detail.destroy',
            'admin.invoices_detail.export-pdf',
            'admin.invoices_detail.export-excel',
        ];

        // Crear cada permiso y asignarlo al rol
        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm])->syncRoles([$adminRole]);
        }

        // Crear el usuario administrador inicial
        User::factory()->create([
            'name' => 'Administrador Veterinaria',
            'email' => 'huellitas@gmail.com',
            'password' => bcrypt('admin123')
        ])->assignRole('Administrador');
    }
}
