<?php

namespace App\Console\Commands;

use App\Repositories\BankAccountRepository;
use App\Repositories\PendingTransactionRepository;
use Illuminate\Console\Command;

class TransactionProcessCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process transactions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing transactions...');

        $pendingTransactionRepository = new PendingTransactionRepository();

        $transactions = $pendingTransactionRepository->getPendingTransactions();

        foreach ($transactions as $transaction) {
            $pendingTransactionRepository->transactionScheduled($transaction);
            $this->info("Transaction ID: {$transaction->id} processed");
        }

        $this->info('Transactions processed');

    }
}
