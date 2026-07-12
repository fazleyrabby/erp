<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Permission;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    session(['companySettings' => [['name' => 'Test Company', 'currency' => '$', 'barcode_exists' => 'Yes']]]);
});

it('can render the sale return list page for walkin_sale', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/sale/sale-returnList/walkin_sale');
    $response->assertStatus(200);
});
