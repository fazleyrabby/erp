<?php

use App\Models\User;
use App\Models\payroll\OurTeam;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(DatabaseTransactions::class);

beforeEach(function () {
    // Ensure Employee role exists
    $role = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);

    $this->user = User::factory()->create();
    $this->user->assignRole($role);

    // Link user -> employee (our_teams.user_id)
    $this->employee = new OurTeam;
    $this->employee->member_name = 'Test Emp';
    $this->employee->user_id = $this->user->id;
    $this->employee->status = 'Active';
    $this->employee->deleted = 'No';
    $this->employee->save();

    session(['companySettings' => [['name' => 'Test Company', 'currency' => '$', 'barcode_exists' => 'Yes']]]);
    $this->actingAs($this->user);
});

it('renders the employee dashboard for an employee user', function () {
    $response = $this->get('/dashboard');
    $response->assertStatus(200);
    $response->assertSee('Welcome');
    $response->assertSee('Attendance');
    $response->assertSee('My Attendance');
    $response->assertSee('My Leaves');
    $response->assertSee('Apply for Leave');
});

it('employee dashboard links resolve to real routes', function () {
    // my-attendance
    $r = $this->get(route('employee.my-attendance'));
    $r->assertStatus(200);

    // my-leaves
    $r = $this->get(route('employee.my-leaves'));
    $r->assertStatus(200);

    // apply-leave
    $r = $this->get(route('employee.apply-leave'));
    $r->assertStatus(200);
});

it('dashboard still works for employee without an OurTeam record (no null errors)', function () {
    $this->employee->delete();
    $response = $this->get('/dashboard');
    $response->assertStatus(200);
});
