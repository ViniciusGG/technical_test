<?php

use App\Models\BankAccount;

test('index returns an ok response', function () {

    BankAccount::factory()->count(5)->create();

    $response = $this->get(route('accounts.index'));
    $response->assertStatus(200)
        ->assertJsonCount(5, 'content.data.data');
});

test('show returns an ok response', function () {

    $account = BankAccount::factory()->create();

    $response = $this->get(route('accounts.show', $account->id));
    $response->assertStatus(200)
        ->assertJsonFragment(['id' => $account->id]);
});

test('store returns an ok response', function () {

    $data = BankAccount::factory()->make()->toArray();

    $response = $this->post(route('accounts.store'), $data);
    $response->assertStatus(201)
        ->assertJsonFragment(['name' => $data['name']]);
});

test('update returns an ok response', function () {

    $account = BankAccount::factory()->create();
    $data = BankAccount::factory()->make()->toArray();

    $response = $this->put(route('accounts.update', $account->id), $data);
    $response->assertStatus(200)
        ->assertJsonFragment(['name' => $data['name']]);
});
