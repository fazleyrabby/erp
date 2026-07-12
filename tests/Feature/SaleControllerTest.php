<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Permission;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Permission::firstOrCreate(['name' => 'sale.view']);
    Permission::firstOrCreate(['name' => 'sale.add']);
    $this->user->givePermissionTo(['sale.view', 'sale.add']);
    $this->actingAs($this->user);
    session(['companySettings' => [['name' => 'Test Company', 'currency' => '$', 'barcode_exists' => 'Yes']]]);
});

it('can render the sale view page for ts', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/sale/viewSales/ts');
    $response->assertStatus(200);
});

it('can render the sale view page for walkin', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/sale/viewSales/walkin_sale');
    $response->assertStatus(200);
});

it('can render the sale add page for walkin', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/sale/add/walkin_sale');
    $response->assertStatus(200);
});
