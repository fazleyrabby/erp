<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    session(['companySettings' => [['name' => 'Test Company', 'currency' => '$']]]);
});

it('can render the bank view page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/account/banks/view');
    $response->assertStatus(200);
});
