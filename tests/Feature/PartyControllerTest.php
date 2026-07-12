<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Permission;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Permission::firstOrCreate(['name' => 'crm.party.view']);
    Permission::firstOrCreate(['name' => 'crm.party.add']);
    $this->user->givePermissionTo(['crm.party.view', 'crm.party.add']);
    $this->actingAs($this->user);
    session(['companySettings' => [['name' => 'Test Company', 'currency' => '$', 'barcode_exists' => 'Yes']]]);
});

it('can render the party view page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/parties/view/Customer');
    $response->assertStatus(200);
});
