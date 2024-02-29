<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BankAccount::create([
            'name' => 'John Doe',
            'balance' => 1000,
        ]);

        BankAccount::create([
            'name' => 'Jane Doe',
            'balance' => 2000,
        ]);

        BankAccount::create([
            'name' => 'Mark Doe',
            'balance' => 3000,
        ]);

        BankAccount::create([
            'name' => 'Sara Doe',
            'balance' => 4000,
        ]);
    }
}
