<?php
use App\Models\User;
use App\Models\payroll\OurTeam;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;

uses(DatabaseTransactions::class);

it('debug sidebar', function () {
    $this->withoutExceptionHandling();
    $role = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    $emp = new OurTeam;
    $emp->member_name = 'Test Emp';
    $emp->user_id = $user->id;
    $emp->status = 'Active';
    $emp->deleted = 'No';
    $emp->save();
    session(['companySettings' => [['name' => 'Test Company', 'currency' => '$', 'barcode_exists' => 'Yes']]]);
    $this->actingAs($user);
    $r = $this->get('/dashboard');
    $r->assertStatus(200);
    $content = $r->getContent();
    echo "HAS My Attendance: ". (str_contains($content, 'My Attendance') ? "YES" : "NO") . "\n";
    echo "HAS Employee Portal: ". (str_contains($content, 'Employee Portal') ? "YES" : "NO") . "\n";
    echo "HAS sidebar-menu: ". (str_contains($content, 'sidebar-menu') ? "YES" : "NO") . "\n";
});
EOF
php -d error_reporting="E_ALL & ~E_DEPRECATED" vendor/bin/pest tests/Feature/_TmpDebug.php 2>&1 | tail -10
rm tests/Feature/_TmpDebug.php