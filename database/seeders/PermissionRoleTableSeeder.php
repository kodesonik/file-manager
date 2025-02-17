<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'admin')->firstOrFail();

        $permissions = Permission::all();

        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );

        // Ensure project permissions exist and are assigned to admin
        $projectPermissions = [
            'browse_projects',
            'read_projects',
            'edit_projects',
            'add_projects',
            'delete_projects',
        ];

        foreach ($projectPermissions as $permissionName) {
            $permission = Permission::firstOrNew(['key' => $permissionName]);
            if (!$permission->exists) {
                $permission->fill([
                    'table_name' => 'projects',
                    'key' => $permissionName,
                ])->save();
                $role->permissions()->attach($permission->id);
            }
        }
    }
}
