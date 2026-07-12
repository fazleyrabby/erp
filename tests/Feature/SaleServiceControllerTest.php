<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Permission;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Permission::firstOrCreate(['name' => 'sale.service.view']);
    $this->user->givePermissionTo(['sale.service.view']);
    $this->actingAs($this->user);
    session(['companySettings' => [['name' => 'Test Company', 'currency' => '$', 'barcode_exists' => 'Yes']]]);
});

it('can render the sale service view orders page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/sale/service/view');
    $response->assertStatus(200);
});
