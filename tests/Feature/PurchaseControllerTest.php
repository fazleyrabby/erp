<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Permission;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Permission::firstOrCreate(['name' => 'purchase.view']);
    Permission::firstOrCreate(['name' => 'purchase.add']);
    $this->user->givePermissionTo(['purchase.view', 'purchase.add']);
    $this->actingAs($this->user);
    session(['companySettings' => [['name' => 'Test Company', 'currency' => '$', 'barcode_exists' => 'Yes']]]);
});

it('can render the purchase index page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/purchase');
    $response->assertStatus(200);
});

it('can render the purchase add page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/purchase/add');
    $response->assertStatus(200);
});
