<?php

namespace Database\Seeders;

use App\Models\Crm\Lead;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run()
    {
        $leads = [
            [
                'first_name' => 'Ahmed',
                'last_name' => 'Hassan',
                'email' => 'ahmed.hassan@example.com',
                'phone' => '01711111111',
                'company' => 'TechMart Bangladesh',
                'designation' => 'Procurement Manager',
                'source' => 'Website',
                'lead_status' => 'New',
                'potential_value' => 500000,
                'notes' => 'Interested in bulk电子产品 purchase. Requested catalog via website form.',
                'follow_up_date' => now()->addDays(3)->format('Y-m-d'),
                'assigned_to' => 3,
            ],
            [
                'first_name' => 'Fatima',
                'last_name' => 'Begum',
                'email' => 'fatima.begum@example.com',
                'phone' => '01722222222',
                'company' => 'StyleGarments Ltd.',
                'designation' => 'Owner',
                'source' => 'Referral',
                'lead_status' => 'Contacted',
                'potential_value' => 1200000,
                'notes' => 'Referred by existing customer Mr. Rahman. Called on July 15, interested in fabric supplies.',
                'follow_up_date' => now()->addDays(5)->format('Y-m-d'),
                'assigned_to' => 3,
            ],
            [
                'first_name' => 'Kamal',
                'last_name' => 'Hossain',
                'email' => 'kamal.h@example.com',
                'phone' => '01733333333',
                'company' => 'BD Furniture House',
                'designation' => 'Director',
                'source' => 'Social Media',
                'lead_status' => 'Qualified',
                'potential_value' => 800000,
                'notes' => 'Found us on Facebook. Visited showroom. Looking for office furniture package.',
                'follow_up_date' => now()->addDays(2)->format('Y-m-d'),
                'assigned_to' => 4,
            ],
            [
                'first_name' => 'Shamim',
                'last_name' => 'Reza',
                'email' => 'shamim.reza@example.com',
                'phone' => '01744444444',
                'company' => 'AgroTech Solutions',
                'designation' => 'CEO',
                'source' => 'Cold Call',
                'lead_status' => 'Proposal',
                'potential_value' => 2000000,
                'notes' => 'Sent proposal for irrigation equipment supply. Waiting for board approval.',
                'follow_up_date' => now()->addDays(7)->format('Y-m-d'),
                'assigned_to' => 3,
            ],
            [
                'first_name' => 'Nusrat',
                'last_name' => 'Jahan',
                'email' => 'nusrat.j@example.com',
                'phone' => '01755555555',
                'company' => 'GreenLeaf Pharmacy',
                'designation' => 'Operations Head',
                'source' => 'Email',
                'lead_status' => 'Negotiation',
                'potential_value' => 350000,
                'notes' => 'Email inquiry about medical supplies. Negotiating price. Counter-offer sent.',
                'follow_up_date' => now()->addDays(1)->format('Y-m-d'),
                'assigned_to' => 4,
            ],
            [
                'first_name' => 'Rafiq',
                'last_name' => 'Islam',
                'email' => 'rafiq.islam@example.com',
                'phone' => '01766666666',
                'company' => 'Dhaka Electronics',
                'designation' => 'Purchase Officer',
                'source' => 'Walk-in',
                'lead_status' => 'Won',
                'potential_value' => 150000,
                'notes' => 'Walked in on July 10. Purchased 50 units of product X. Converted to customer.',
                'follow_up_date' => null,
                'assigned_to' => 3,
            ],
            [
                'first_name' => 'Mizanur',
                'last_name' => 'Rahman',
                'email' => 'mizan.r@example.com',
                'phone' => '01777777777',
                'company' => 'QuickLogistics BD',
                'designation' => 'Manager',
                'source' => 'Website',
                'lead_status' => 'Lost',
                'potential_value' => 600000,
                'notes' => 'Requested quote for logistics equipment. Chose competitor due to pricing. Follow up next quarter.',
                'follow_up_date' => now()->addMonths(3)->format('Y-m-d'),
                'assigned_to' => 4,
            ],
        ];

        foreach ($leads as $data) {
            Lead::create($data);
        }
    }
}
