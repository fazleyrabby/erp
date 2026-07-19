<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'Super Admin',
            'Manager',
            'Sales Man',
            'Employee',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $permissions = [
            'dashboard.view',

            'user.create', 'user.view', 'user.edit', 'user.store', 'user.delete', 'user.changePassword',

            'role.create', 'role.view', 'role.edit', 'role.store', 'role.delete',

            'permission.create', 'permission.view', 'permission.edit', 'permission.update', 'permission.store', 'permission.delete',

            'permissionToRole.create', 'permissionToRole.view', 'permissionToRole.store', 'permissionToRole.delete',

            'companySetting.view', 'companySetting.edit', 'companySetting.update',

            'categories.view', 'categories.store', 'categories.edit', 'categories.update', 'categories.delete',
            'brands.view', 'brands.store', 'brands.edit', 'brands.update', 'brands.delete',
            'units.view', 'units.store', 'units.edit', 'units.update', 'units.delete',

            'warehouse.view', 'warehouse.store', 'warehouse.edit', 'warehouse.update', 'warehouse.delete',

            'products.view', 'products.store', 'products.edit', 'products.update', 'products.delete',

            'purchase.view', 'purchase.add', 'purchase.delete',
            'Purchase.return',

            'sale.view', 'sale.add', 'sale.delete',

            'damage.view', 'damage.store', 'damage.delete',

            'transport.view', 'transport.store', 'transport.edit', 'transport.update', 'transport.delete',

            'PaymentVoucher.view', 'PaymentVoucher.store', 'PaymentVoucher.edit', 'PaymentVoucher.update', 'PaymentVoucher.delete',
            'PaymentReceiveVoucher.view', 'PaymentReceiveVoucher.store', 'PaymentReceiveVoucher.edit', 'PaymentReceiveVoucher.update', 'PaymentReceiveVoucher.delete',
            'DiscountVoucher.view', 'DiscountVoucher.store', 'DiscountVoucher.edit', 'DiscountVoucher.update', 'DiscountVoucher.delete',

            'sale.service.view', 'sale.service.add', 'sale.service.edit', 'sale.service.statusComplete', 'sale.service.createOrderToWalkinSale',

            'invoices.view', 'invoices.add', 'invoices.edit', 'invoices.delete',

            'coa.view', 'journal.view', 'expense.view', 'bill.view', 'bank.view',
            'accounts.setting',

            'Supplier', 'Customer', 'Walkin Customer',
            'Inventory', 'Products', 'Damage', 'Warehouse', 'Purchase', 'Sale',
            'CRM', 'Accounts', 'Reports', 'Payroll', 'Setting',

            'party.ledger', 'monthlyAccounts.view',
            'walking.sale.view', 'party.sale.view', 'TS.sale.view', 'final.sale.view',

            'payroll.settings',

            'coa.create', 'coa.edit', 'coa.delete',
            'journal.add', 'journal.edit', 'journal.delete',
            'expense.add', 'expense.edit', 'expense.delete',
            'bill.add', 'bill.edit', 'bill.delete',
            'bank.add', 'bank.edit', 'bank.delete',
        ];

        $superAdmin = Role::where('name', 'Super Admin')->first();

        foreach ($permissions as $permName) {
            $permission = Permission::firstOrCreate(['name' => $permName]);
            $superAdmin->givePermissionTo($permission);
        }

        $manager = Role::where('name', 'Manager')->first();
        $managerPermissions = [
            'dashboard.view',
            'products.view',
            'purchase.view', 'purchase.add',
            'sale.view', 'sale.add',
            'categories.view', 'brands.view', 'units.view',
            'warehouse.view',
            'Supplier', 'Customer',
            'invoices.view', 'invoices.add',
            'PaymentVoucher.view', 'PaymentReceiveVoucher.view',
        ];
        foreach ($managerPermissions as $permName) {
            $permission = Permission::where('name', $permName)->first();
            if ($permission) {
                $manager->givePermissionTo($permission);
            }
        }
    }
}
