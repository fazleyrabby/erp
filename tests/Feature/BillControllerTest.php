<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    session(['companySettings' => [['name' => 'Test Company', 'currency' => '$']]]);
});

it('can render the bill index page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/account/bills/view');
    $response->assertStatus(200);
});

it('can render the bill create page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/account/bills/create');
    $response->assertStatus(200);
});

it('can render the pay bills page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/account/bills/payment');
    $response->assertStatus(200);
});
