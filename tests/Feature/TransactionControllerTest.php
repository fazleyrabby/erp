<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    session(['companySettings' => [['name' => 'Test Company', 'currency' => '$']]]);
});

it('can render the transaction view page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/account/view/transactions');
    $response->assertStatus(200);
});
