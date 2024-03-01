<?php
use Illuminate\Support\Facades\Http;

use App\Models\BankAccount;

test('transactions between accounts should be successful', function () {

    BankAccount::factory()->create([
        'id' => 1,
        'balance' => 200
    ]);

    BankAccount::factory()->create([
        'id' => 2,
        'balance' => 200
    ]);

    Http::fake([
        config('settings.endpoint_pipedream') => Http::response(['authorized' => true], 200)
    ]);

    $data = [
        'sender' => 1,
        'receiver' => 2,
        'amount' => 100,
    ];

    $response = $this->post(route('transactions'), $data);

    $response->assertStatus(200)
        ->assertJsonFragment(['status' => true])
        ->assertJsonFragment(['message' => 'Transaction authorized']);

});

test('transactions between accounts should fail if not authorized', function () {
    BankAccount::factory()->create([
        'id' => 1,
        'balance' => 200
    ]);

    BankAccount::factory()->create([
        'id' => 2,
        'balance' => 200
    ]);

    Http::fake([
        config('settings.endpoint_pipedream') => Http::response(['authorized' => false], 401)
    ]);

    $data = [
        'sender' => 1,
        'receiver' => 2,
        'amount' => 100,
    ];

    $response = $this->post(route('transactions'), $data);

    $response->assertStatus(401)
        ->assertJsonFragment(['status' => false])
        ->assertJsonFragment(['message' => 'Transaction not authorized']);
});

test('transactions between accounts should fail due to insufficient balance', function () {

    BankAccount::factory()->create([
        'id' => 1,
        'balance' => 100
    ]);

    BankAccount::factory()->create([
        'id' => 2,
        'balance' => 100
    ]);

    $data = [
        'sender' => 1,
        'receiver' => 2,
        'amount' => 200,
    ];

    $response = $this->post(route('transactions'), $data);

    $response->assertStatus(401)
        ->assertJsonFragment(['status' => false])
        ->assertJsonFragment(['message' => 'Insufficient balance']);
});

test('scheduled transactions should be successful', function () {

    BankAccount::factory()->count(2)->create();

    $data = [
        'sender' => 1,
        'receiver' => 2,
        'amount' => 100,
        'scheduled' => true,
        'data_scheduled' => now()->addDay()->format('Y-m-d'),
    ];

    $response = $this->post(route('transactions'), $data);

    $response->assertStatus(200)
        ->assertJsonFragment(['status' => true])
        ->assertJsonFragment(['message' => 'Transaction scheduled']);

    $this->assertDatabaseHas('pending_transactions', [
        'sender_id' => 1,
        'receiver_id' => 2,
        'amount' => 100,
        'status' => 'pending',
    ]);
});

it('should schedule the transaction process task at 05:00 daily', function () {
    BankAccount::factory()->create([
        'id' => 1,
        'balance' => 200
    ]);

    BankAccount::factory()->create([
        'id' => 2,
        'balance' => 200
    ]);

    Http::fake([
        config('settings.endpoint_pipedream') => Http::response(['authorized' => true], 200)
    ]);

    $data = [
        'sender' => 1,
        'receiver' => 2,
        'amount' => 100,
        'scheduled' => true,
        'data_scheduled' => now()->format('Y-m-d'),
    ];

    $this->post(route('transactions'), $data);

    $this->artisan('transaction:process');

    $this->assertDatabaseHas('pending_transactions', [
        'sender_id' => 1,
        'receiver_id' => 2,
        'amount' => 100,
        'status' => 'authorized',
    ]);

});