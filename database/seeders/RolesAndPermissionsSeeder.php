<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | Permissions
            |--------------------------------------------------------------------------
            */
            $permissions = [
                // Projects
                'project.create',
                'project.view',
                'project.update',
                'project.delete',
                // Orders
                'order.create',
                'order.view',
                'order.verify',
                'order.update',
                'order.delete',
                // Users
                'user.create',
                'user.view',
                'user.update',
                'user.delete',
                // Reports
                'report.view',
                //requests
                'request.create',
                'request.view',
                'request.update',
                'request.delete',
                //approvals
                'approval.view',
                'approval.approve',
                'approval.reject',
                //certificates
                'certificate.create',
                'certificate.view',
                'certificate.update',
                'certificate.delete',
                //salesindex
                'salesindex.view',
                'salesindex.update',
                'salesindex.delete',
                'salesindex.create',
            ];

            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name'       => $permission,
                    'guard_name' => 'web',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Roles
            |--------------------------------------------------------------------------
            */
            $roles = [
                'Super Admin',
                'Technical',
                'Admin',
                'Manager',
                'Junior Finance',
                'Senior Finance',
                'Finance',
                'Sales',
                'Staff',
            ];

            foreach ($roles as $role) {
                Role::firstOrCreate([
                    'name'       => $role,
                    'guard_name' => 'web',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Role → Permission Mapping
            |--------------------------------------------------------------------------
            */

            // Super Admin → everything
            Role::where('name', 'Super Admin')
                ->first()
                ->syncPermissions(Permission::all());

            // Finance Officer → order-related permissions
            Role::where('name', 'Finance')
                ->first()
                ->syncPermissions([
                    'request.view',
                    'approval.view',
                    'approval.approve',
                    'approval.reject',
                    'certificate.view',
                ]);
            // Finance Officer → order-related permissions
            Role::where('name', 'manager')
            ->first()
            ->syncPermissions([
                'request.view',
                'approval.view',
                'approval.approve',
                'approval.reject',
                'certificate.view',
                'certificate.create',
                'certificate.update',
                'certificate.delete',
                'salesindex.view',
                'salesindex.update',
                'salesindex.delete',
                'salesindex.create',
                'order.view',
                'order.verify',
            ]);

             // Finance Technical → order-related permissions
            Role::where('name', 'Technical')
                ->first()
                ->syncPermissions([
                    'request.view',
                    'approval.view',
                    'certificate.view',
                    'certificate.create',
                    'certificate.update',
                    'certificate.delete',
                ]);


            // Staff → basic usage
            Role::where('name', 'Staff')
                ->first()
                ->syncPermissions([
                    'project.view',
                    'order.view',
                    'user.view',
                    'report.view',
                    'request.create',
                    'request.view',
                    'request.update',
                    'request.delete',
                ]);
        });
    }
}
