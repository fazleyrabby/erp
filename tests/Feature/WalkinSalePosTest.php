<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Permission;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Permission::firstOrCreate(['name' => 'sale.add']);
    Permission::firstOrCreate(['name' => 'sale.view']);
    $this->user->givePermissionTo(['sale.add', 'sale.view']);
    $this->actingAs($this->user);
    session(['companySettings' => [['name' => 'Test Company', 'currency' => '$', 'barcode_exists' => 'Yes']]]);
});

it('renders the walkin sale POS screen with touch UI', function () {
    $response = $this->get('/sale/add/walkin_sale');
    $response->assertStatus(200);
    // Touch POS elements present
    $response->assertSee('pos-product-grid');
    $response->assertSee('posCatTabs');
    $response->assertSee('id="barcode"', false);
    $response->assertSee('loadPosProducts');
    // Existing cart/pay DOM contract preserved
    $response->assertSee('id="manageCartTable"', false);
    $response->assertSee('id="grandSum"', false);
    $response->assertSee('id="payment"', false);
    $response->assertSee('id="warehouse"', false);
});

it('walkin sale screen keeps non-walkin search select for other types', function () {
    $response = $this->get('/sale/add/party_sale');
    $response->assertStatus(200);
    $response->assertSee('id="products"', false);
    // grid markup should NOT be rendered for party sale (only the @if walkin block adds #posProductGrid)
    $response->assertDontSee('id="posProductGrid"', false);
});
