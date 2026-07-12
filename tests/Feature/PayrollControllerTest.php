<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Permission;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Permission::firstOrCreate(['name' => 'payroll.team.view']);
    Permission::firstOrCreate(['name' => 'payroll.salary.view']);
    Permission::firstOrCreate(['name' => 'payroll.leave.view']);
    $this->user->givePermissionTo(['payroll.team.view', 'payroll.salary.view', 'payroll.leave.view']);
    $this->actingAs($this->user);
    session(['companySettings' => [['name' => 'Test Company', 'currency' => '$', 'barcode_exists' => 'Yes']]]);
});

it('can render the our team page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/payroll/ourTeam');
    $response->assertStatus(200);
});

it('can render the salary sheet page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/payroll/salary/net/sheet/view');
    $response->assertStatus(200);
});

it('can render the leave management page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/payroll/leave/management/index');
    $response->assertStatus(200);
});
