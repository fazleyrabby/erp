<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    session(['companySettings' => [['name' => 'Test Company', 'currency' => '$']]]);
});

it('can render the chart of accounts page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/chart/Of/Accounts/view');
    $response->assertStatus(200);
});

it('can fetch chart of accounts data for datatable', function () {
    $this->withoutExceptionHandling();
    // In AccountController, getCodeRange needs a parent_id but usually works without one or fails cleanly
    $response = $this->get('/chart/Of/Accounts/get/Code/Range');
    $response->assertStatus(200);
});
