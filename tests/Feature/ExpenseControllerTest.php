<?php

use App\Models\User;
use App\Models\Expense\Expense;
use App\Models\payroll\OurTeam;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

beforeEach(function () {
    // Create and login a user before each test since these are admin routes
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    // Set required session data for views globally
    session(['companySettings' => [['name' => 'Test Company', 'currency' => '$']]]);
});

it('can render the expense index page', function () {
    $this->withoutExceptionHandling();
    $response = $this->withSession(['companySettings' => [['name' => 'Test Company']]])
                     ->get('/expense/View');
    
    // We expect a 200 OK or 302 if there's some middleware redirection for roles/permissions
    $response->assertStatus(200);
});

it('can render the create expense page', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/expense/create');
    $response->assertStatus(200);
});
