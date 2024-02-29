<?php

namespace App\Repositories;

use App\Models\BankAccount;
use App\Repositories\BaseRepository;
use App\Services\ExternalService\PipedreamService;

class BankAccountRepository extends BaseRepository
{

    private $scheduled = false;

    public function __construct()
    {
        parent::__construct(BankAccount::class);
    }

    public function transaction(array $data): array
    {
        if (isset($data['scheduled'])) {
            $this->scheduled = $data['scheduled'];
            unset($data['scheduled']);
        }

        $sender = $this->model->find($data['sender']);
        $receiver = $this->model->find($data['receiver']);

        if ($this->scheduled) {
            return $this->createPendingTransaction($data, $sender, $receiver);
        }

        $pipedreamService = new PipedreamService();

        if (!$pipedreamService->authorizeTransaction($data)) {
            return ['message' => 'Transaction not authorized', 'status' => 401, 'data' => [], 'type' => 'error'];
        }


        if ($sender->balance < $data['amount']) {
            return ['message' => 'Insufficient balance', 'status' => 401, 'data' => [], 'type' => 'error'];
        }

        $sender->balance -= $data['amount'];
        $receiver->balance += $data['amount'];

        $sender->save();
        $receiver->save();

        return ['message' => 'Transaction authorized', 'status' => 200, 'data' => [], 'type' => 'success'];
    }

    private function createPendingTransaction($data, $sender, $receiver): array
    {
        $pendingTransactionRepository = new PendingTransactionRepository();
        $data = [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'amount' => $data['amount'],
            'status' => 'pending',
        ];
        $pendingTransactionRepository->create($data);
        return ['message' => 'Transaction scheduled', 'status' => 200, 'data' => [], 'type' => 'success'];
    }

}
