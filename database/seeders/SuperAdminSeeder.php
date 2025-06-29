<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Ganti email ini dengan email user admin kamu
        $email = 'test@example.com';

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->command->warn("User dengan email {$email} tidak ditemukan.");

            return;
        }

        // Buat role super_admin jika belum ada
        $role = Role::firstOrCreate(['name' => 'super_admin']);

        // Buat permission view_filament jika belum ada
        $permission = Permission::firstOrCreate(['name' => 'view_filament']);

        // Assign role dan permission ke user
        $user->assignRole($role);
        $user->givePermissionTo('view_filament');

        $this->command->info("User {$email} telah diberi role super_admin dan permission view_filament.");
    }
}
