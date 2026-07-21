<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Subtask;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get active users
        $superAdmin = User::where('email', 'super.admin@demo.com')->first();
        $manager = User::where('email', 'manager@demo.com')->first();
        $sales = User::where('email', 'sales@demo.com')->first();
        $employee = User::where('email', 'employee@demo.com')->first();

        // 1. ERP Dashboard Redesign (processing)
        $p1 = Project::create([
            'name' => 'ERP Dashboard Redesign',
            'description' => 'Redesign the main dashboard interface to match Tabler standard layout and styling guidelines.',
            'user_id' => $superAdmin ? $superAdmin->id : null,
            'status' => 'processing',
            'priority' => 'high',
            'due_date' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $p1->subtasks()->createMany([
            ['title' => 'Sketch UI mockups', 'is_completed' => true],
            ['title' => 'Implement blade template layouts', 'is_completed' => false],
            ['title' => 'Integrate responsive css modifications', 'is_completed' => false],
        ]);

        // 2. Automated Email System (Pending)
        $p2 = Project::create([
            'name' => 'Automated Email System',
            'description' => 'Set up queue sweepers and cron jobs to dispatch automated notifications to CRM contacts.',
            'user_id' => $manager ? $manager->id : null,
            'status' => 'Pending',
            'priority' => 'medium',
            'due_date' => now()->addDays(14)->format('Y-m-d'),
        ]);

        $p2->subtasks()->createMany([
            ['title' => 'Configure SMTP credentials', 'is_completed' => false],
            ['title' => 'Create email blade layout templates', 'is_completed' => false],
            ['title' => 'Draft queue jobs', 'is_completed' => false],
        ]);

        // 3. Spatie Permission Audit (testing)
        $p3 = Project::create([
            'name' => 'Spatie Permission Audit',
            'description' => 'Verify permission gates across inventory, payroll, and billing routes for client security verification.',
            'user_id' => $employee ? $employee->id : null,
            'status' => 'testing',
            'priority' => 'high',
            'due_date' => now()->addDays(3)->format('Y-m-d'),
        ]);

        $p3->subtasks()->createMany([
            ['title' => 'Run permission route checks', 'is_completed' => true],
            ['title' => 'Audit employee portal views', 'is_completed' => true],
            ['title' => 'Fix missing role assignment guards', 'is_completed' => false],
        ]);

        // 4. Update Composer Dependencies (completed)
        $p4 = Project::create([
            'name' => 'Update Composer Dependencies',
            'description' => 'Upgrade Laravel framework, datatables, and livewire helper files to their latest stable version.',
            'user_id' => $sales ? $sales->id : null,
            'status' => 'completed',
            'priority' => 'low',
            'due_date' => now()->subDays(2)->format('Y-m-d'),
        ]);

        $p4->subtasks()->createMany([
            ['title' => 'Run composer update', 'is_completed' => true],
            ['title' => 'Execute PHPUnit tests', 'is_completed' => true],
        ]);
    }
}
