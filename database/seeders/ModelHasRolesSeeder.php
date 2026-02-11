<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ModelHasRolesSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | Role assignments
            |--------------------------------------------------------------------------
            | Map users to roles using a reliable identifier (email / username)
            */
            $assignments = [
                'davies.simoonga@profout.com'   => 'Super Admin',
            ];

            foreach ($assignments as $email => $roleName) {

                $user = User::where('email', $email)->first();
                $role = Role::where('name', $roleName)->first();

                if ($user && $role) {
                    // This automatically populates model_has_roles
                    $user->syncRoles([$role->name]);
                }
            }
        });
    }
}
