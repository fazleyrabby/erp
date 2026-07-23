<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        $this->call(RolePermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CompanySettingSeeder::class);
        $this->call(CoaSeeder::class);
        $this->call(accountsConfiguration::class);
        $this->call(ProductSeeder::class);
        $this->call(PartySeeder::class);
        $this->call(PayrollSeeder::class);
        $this->call(TransactionSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(LeadSeeder::class);

        Model::reguard();
    }
}
