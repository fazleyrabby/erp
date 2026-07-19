<?php

namespace Database\Seeders;

use App\Models\inventory\Party;
use Illuminate\Database\Seeder;

class PartySeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            ['name' => 'TechMart Distributors', 'contact' => '01711111111', 'address' => 'Dhaka', 'party_type' => 'Supplier'],
            ['name' => 'Global Electronics Ltd', 'contact' => '01722222222', 'address' => 'Chittagong', 'party_type' => 'Supplier'],
            ['name' => 'Fresh Foods Corporation', 'contact' => '01733333333', 'address' => 'Khulna', 'party_type' => 'Supplier'],
            ['name' => 'Office Supplies BD', 'contact' => '01744444444', 'address' => 'Dhaka', 'party_type' => 'Supplier'],
            ['name' => 'Furniture World', 'contact' => '01755555555', 'address' => 'Gazipur', 'party_type' => 'Supplier'],
        ];

        foreach ($suppliers as $supplier) {
            Party::firstOrCreate(
                ['name' => $supplier['name']],
                array_merge($supplier, [
                    'status' => 'Active',
                    'deleted' => 'No',
                    'created_by' => 1,
                ])
            );
        }

        $customers = [
            ['name' => 'Abdul Karim', 'contact' => '01811111111', 'address' => 'Mirpur, Dhaka', 'party_type' => 'Customer'],
            ['name' => 'Fatima Begum', 'contact' => '01822222222', 'address' => 'Uttara, Dhaka', 'party_type' => 'Customer'],
            ['name' => 'Rahim Store', 'contact' => '01833333333', 'address' => 'Gulshan, Dhaka', 'party_type' => 'Customer'],
            ['name' => 'Shahid Electronics', 'contact' => '01844444444', 'address' => 'Chittagong', 'party_type' => 'Customer'],
            ['name' => 'Nasrin Trading Co', 'contact' => '01855555555', 'address' => 'Sylhet', 'party_type' => 'Customer'],
            ['name' => 'Jahangir Brothers', 'contact' => '01866666666', 'address' => 'Rajshahi', 'party_type' => 'Customer'],
        ];

        foreach ($customers as $customer) {
            Party::firstOrCreate(
                ['name' => $customer['name']],
                array_merge($customer, [
                    'status' => 'Active',
                    'deleted' => 'No',
                    'created_by' => 1,
                ])
            );
        }

        $walkins = [
            ['name' => 'Md. Hossain', 'contact' => '01911111111', 'address' => 'Mohakhali, Dhaka', 'party_type' => 'Walkin_Customer'],
            ['name' => 'Sumon Ahmed', 'contact' => '01922222222', 'address' => 'Banani, Dhaka', 'party_type' => 'Walkin_Customer'],
        ];

        foreach ($walkins as $walkin) {
            Party::firstOrCreate(
                ['name' => $walkin['name']],
                array_merge($walkin, [
                    'status' => 'Active',
                    'deleted' => 'No',
                    'created_by' => 1,
                ])
            );
        }
    }
}
