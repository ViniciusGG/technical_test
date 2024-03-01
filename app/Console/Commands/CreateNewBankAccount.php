<?php

namespace App\Console\Commands;

use App\Repositories\BankAccountRepository;
use Illuminate\Console\Command;

class CreateNewBankAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank-account:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new bank account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('What is your name?');
        $balance = $this->ask('What is your balance?');
        while (!is_numeric($balance) || $balance < 0) {
            $this->error('Balance must be a number and greater than 0.');
            $balance = $this->ask('What is your balance?');
        }

        $this->info("Creating a new bank account for {$name} with a balance of {$balance}.");

        $data = [
            'name' => $name,
            'balance' => $balance,
        ];

        $user = (new BankAccountRepository())->create($data);

        $this->info("Account created with ID: {$user->id}");
    }
}
