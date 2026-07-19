<?php

namespace Database\Seeders;

use App\Models\Accounts\ChartOfAccounts;
use Illuminate\Database\Seeder;

class CoaSeeder extends Seeder
{
    public function run()
    {
        $coas = [
            ['name' => 'Asset', 'slug' => 'Asset', 'code' => 100000000, 'limit_from' => 100000000, 'limit_to' => 199999999, 'parent_id' => 0, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 0],
            ['name' => 'Liability', 'slug' => 'Liability', 'code' => 200000000, 'limit_from' => 200000000, 'limit_to' => 299999999, 'parent_id' => 0, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 0],
            ['name' => 'Income', 'slug' => 'Income', 'code' => 300000000, 'limit_from' => 300000000, 'limit_to' => 399999999, 'parent_id' => 0, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 0],
            ['name' => 'Expense', 'slug' => 'Expense', 'code' => 400000000, 'limit_from' => 400000000, 'limit_to' => 499999999, 'parent_id' => 0, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 0],
            ['name' => 'Bank', 'slug' => 'Bank', 'code' => 500000000, 'limit_from' => 500000000, 'limit_to' => 599999999, 'parent_id' => 0, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 0],
            ['name' => 'Cash', 'slug' => 'cash', 'code' => 501000000, 'limit_from' => 501000000, 'limit_to' => 509900000, 'parent_id' => 5, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 0],
            ['name' => 'Cash Amount', 'slug' => 'cash-amount', 'code' => 501010000, 'limit_from' => 501010000, 'limit_to' => 501099000, 'parent_id' => 6, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 0],
            ['name' => 'Sales Income', 'slug' => 'sales-income', 'code' => 301000000, 'limit_from' => 301000000, 'limit_to' => 309900000, 'parent_id' => 3, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 0],
            ['name' => 'Purchase Expense', 'slug' => 'purchase-expense', 'code' => 401000000, 'limit_from' => 401000000, 'limit_to' => 409900000, 'parent_id' => 4, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 0],
            ['name' => 'Service Income', 'slug' => 'service-income', 'code' => 302000000, 'limit_from' => 302000000, 'limit_to' => 309900000, 'parent_id' => 3, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Salary Expense', 'slug' => 'salary-expense', 'code' => 402000000, 'limit_from' => 402000000, 'limit_to' => 409900000, 'parent_id' => 4, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Utility Expense', 'slug' => 'utility-expense', 'code' => 403000000, 'limit_from' => 403000000, 'limit_to' => 409900000, 'parent_id' => 4, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Rent Expense', 'slug' => 'rent-expense', 'code' => 404000000, 'limit_from' => 404000000, 'limit_to' => 409900000, 'parent_id' => 4, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Transport Expense', 'slug' => 'transport-expense', 'code' => 405000000, 'limit_from' => 405000000, 'limit_to' => 409900000, 'parent_id' => 4, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Office Expense', 'slug' => 'office-expense', 'code' => 406000000, 'limit_from' => 406000000, 'limit_to' => 409900000, 'parent_id' => 4, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Discount', 'slug' => 'discount', 'code' => 502000000, 'limit_from' => 502000000, 'limit_to' => 509900000, 'parent_id' => 5, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
        ];

        foreach ($coas as $coa) {
            ChartOfAccounts::firstOrCreate(
                ['name' => $coa['name']],
                $coa
            );
        }
    }
}
