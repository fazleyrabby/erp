<?php

namespace Database\Seeders;

use App\Models\Accounts\AccountConfiguration;
use App\Models\Accounts\ChartOfAccounts;
use Illuminate\Database\Seeder;

class accountsConfiguration extends Seeder
{
    public function run()
    {
        $configs = ['Asset', 'Liability', 'Income', 'Expense', 'Bank'];

        foreach ($configs as $name) {
            $coa = ChartOfAccounts::where('name', $name)->first();
            if ($coa) {
                AccountConfiguration::firstOrCreate(
                    ['name' => $name],
                    ['tbl_acc_coa_id' => $coa->id]
                );
            }
        }

        $saleCoa = ChartOfAccounts::where('name', 'Sales Income')->first();
        if ($saleCoa) {
            AccountConfiguration::firstOrCreate(
                ['name' => 'Sale'],
                ['tbl_acc_coa_id' => $saleCoa->id]
            );
        }

        $purchaseCoa = ChartOfAccounts::where('name', 'Purchase Expense')->first();
        if ($purchaseCoa) {
            AccountConfiguration::firstOrCreate(
                ['name' => 'Purchase'],
                ['tbl_acc_coa_id' => $purchaseCoa->id]
            );
        }
    }
}
