<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Permission;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Permission::firstOrCreate(['name' => 'account.reports.vouchers']);
    $this->user->givePermissionTo(['account.reports.vouchers']);
    $this->actingAs($this->user);
    session(['companySettings' => [['name' => 'Test Company', 'currency' => '$', 'barcode_exists' => 'Yes']]]);
});

it('can render the vouchers report page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/account/reports/vouchers');
    $response->assertStatus(200);
});
