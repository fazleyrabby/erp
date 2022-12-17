<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Accounts\ChartOfAccounts;

class CoaSeeder extends Seeder
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
            'Purchase',
            'Cash',
            'Cash Amount',
            'Discount'
        ];


        $slugs = [
            'Asset',
            'Liability',
            'Income',
            'Expense',
            'Bank',
            'Sales',
            'Purchase',
            'cash',
            'cash-amount',
            'Discount'
        ];


        $ourcodes = [
            '100000000',
            '200000000',
            '300000000',
            '400000000',
            '500000000',
            '301000000',
            '401000000',
            '501000000',
            '501010000',
            '502000000'
        ];

       

        $limitTo = [
            '199999999',
            '299999999',
            '399999999',
            '499999999',
            '599999999',
            '309900000',
            '409900000',
            '509900000',
            '501099000',
            '509900000'
        ];

        $parentId = [
            '0',
            '0',
            '0',
            '0',
            '0',
            '3',
            '4',
            '5',
            '8',
            '5'
        ];

        $status = [
            'Active',
            'Active',
            'Active',
            'Active',
            'Active',
            'Active',
            'Active',
            'Active',
            'Active',
            'Active'
        ];

        $deleted = [
            'No',
            'No',
            'No',
            'No',
            'No',
            'No',
            'No',
            'No',
            'No',
            'No'
        ];

        $created_by = [
            '0',
            '0',
            '0',
            '0',
            '0',
            '0',
            '0',
            '0',
            '0',
            '0'
        ];
        //create permissions
        for ($i = 0; $i < count($names); $i++) {
            $coas = ChartOfAccounts::create([   'name'          =>  $names[$i],
                                                'slug'          =>  $slugs[$i],
                                                'code'          =>  $ourcodes[$i],
                                                'our_code'      =>  $ourcodes[$i],
                                                'limit_from'    =>  $ourcodes[$i],
                                                'limit_to'      =>  $limitTo[$i],
                                                'parent_id'     =>  $parentId[$i],
                                                'status'        =>  $status[$i],
                                                'deleted'       =>  $deleted[$i],
                                                'created_by'    =>  $created_by[$i],
                                                ]);
        }
        
    }

 
       
    
    
}
