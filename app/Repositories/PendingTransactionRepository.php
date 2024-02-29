<?php

namespace App\Repositories;

use App\Models\PendingTransaction;
use App\Repositories\BaseRepository;
use App\Services\ExternalService\PipedreamService;

class PendingTransactionRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(PendingTransaction::class);
    }

    public function getPendingTransactions()
    {
        return $this->model->where('status', 'pending')->get();
    }

    public function transactionScheduled($data): void
    {
        $bankAccountRepository = new BankAccountRepository();
        $pendingTransaction = $this->getById($data->id);

        if (!$this->authorizeTransaction($data, $pendingTransaction)) {
            return;
        }

        $sender = $bankAccountRepository->getById($data->sender_id);
        $receiver = $bankAccountRepository->getById($data->receiver_id);

        if ($sender->balance < $data->amount) {
            $pendingTransaction->status = 'rejected';
            $pendingTransaction->save();
            return;
        }

        $sender->balance -= $data->amount;
        $receiver->balance += $data->amount;

        $sender->save();
        $receiver->save();

        $pendingTransaction = $this->getById($data->id);
        $pendingTransaction->status = 'authorized';
        $pendingTransaction->save();
    }

    private function authorizeTransaction($data, $pendingTransaction): bool
    {
        $pipedreamService = new PipedreamService();

        $dataPipedream = [
            'sender' => $data->sender_id,
            'receiver' => $data->receiver_id,
            'amount' => $data->amount,
        ];
        if (!$pipedreamService->authorizeTransaction($dataPipedream)) {
            $pendingTransaction->status = 'rejected';
            $pendingTransaction->save();
            return false;
        }
        return true;
    }
}
