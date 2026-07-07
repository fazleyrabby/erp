<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Role
        $roleSuperAdmin = Role::create(['name' => 'Super Admin']);
        $roleManager = Role::create(['name' => 'Manager']);
        $roleSalesMan = Role::create(['name' => 'Sales Man']);

        // Create Permissions
        $permissions = [

            'dashboard.view',

            'user.create',
            'user.view',
            'user.edit',
            'user.store',
            'user.delete',
            'user.changePassword',

            'role.create',
            'role.view',
            'role.edit',
            'role.store',
            'role.delete',

            'permission.create',
            'permission.view',
            'permission.edit',
            'permission.update',
            'permission.store',
            'permission.delete',

            'permissionToRole.create',
            'permissionToRole.view',
            'permissionToRole.store',
            'permissionToRole.delete',

            'companySetting.view',
            'companySetting.edit',
            'companySetting.update',

            'categories.view',
            'categories.store',
            'categories.edit',
            'categories.update',
            'categories.delete',

            'brands.view',
            'brands.store',
            'brands.edit',
            'brands.update',
            'brands.delete',

            'units.view',
            'units.store',
            'units.edit',
            'units.update',
            'units.delete',

            'warehouse.view',
            'warehouse.store',
            'warehouse.edit',
            'warehouse.update',
            'warehouse.delete',

            'products.view',
            'products.store',
            'products.edit',
            'products.update',
            'products.delete',

            'purchase.view',
            'purchase.add',
            'purchase.delete',

            'sale.view',
            'sale.add',
            'sale.delete',

            'damage.view',
            'damage.store',
            'damage.delete',

            'transport.view',
            'transport.store',
            'transport.edit',
            'transport.update',
            'transport.delete',

            'PaymentVoucher.view',
            'PaymentVoucher.store',
            'PaymentVoucher.edit',
            'PaymentVoucher.update',
            'PaymentVoucher.delete',

            'PaymentReceiveVoucher.view',
            'PaymentReceiveVoucher.store',
            'PaymentReceiveVoucher.edit',
            'PaymentReceiveVoucher.update',
            'PaymentReceiveVoucher.delete',

            'DiscountVoucher.view',
            'DiscountVoucher.store',
            'DiscountVoucher.edit',
            'DiscountVoucher.update',
            'DiscountVoucher.delete',

            'sale.service.view',
            'sale.service.add',
            'sale.service.edit',
            'sale.service.statusComplete',
            'sale.service.createOrderToWalkinSale',

        ];

        // Create Permissions
        $countLen = count($permissions);
        for ($i = 0; $i < $countLen; $i++) {
            $permission = Permission::create(['name' => $permissions[$i]]);
            $roleSuperAdmin->givePermissionTo($permission);
            $permission->assignRole($roleSuperAdmin);
        }

    }
}
