<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Accounts\AccountConfiguration;

class accountsConfiguration extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Asset',
            'Liability',
            'Income',
            'Expense',
            'Bank',
            'Sales',
            'Purchase'
        ];

        $coaId = [
            '1',
            '2',
            '3',
            '4',
            '5',
            '205',
            '206'
        ];

        //create permissions
        for ($i = 0; $i < count($names); $i++) {
            $configs = AccountConfiguration::create(['name' =>  $names[$i],'tbl_acc_coa_id'=>$coaId[$i]]);
        }
        
    }

 
       
    
    
}
