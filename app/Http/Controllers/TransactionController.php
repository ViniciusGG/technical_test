<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Repositories\BankAccountRepository;

/**
 * @group Transaction management
 * APIs for managing transactions
 */
class TransactionController extends Controller
{
    private $repository;

    public function __construct(BankAccountRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }
    /**
     * Transaction
     * Make a transaction between two bank accounts
     *
     * @bodyParam amount float required The amount to be transferred. Example: 100
     * @bodyParam sender int required The id of the sender bank account. Example: 1
     * @bodyParam receiver int required The id of the receiver bank account. Example: 2
     * @bodyParam scheduled boolean optional If the transaction is scheduled. Example: true
     * @bodyParam data_scheduled date optional The date of the scheduled transaction. Example: 2024-02-29
     *
     * @response 200 {"message":"Transaction authorized","status":200,"data":[],"type":"success"}
     * @response 401 {"message":"Transaction not authorized","status":401,"data":[],"type":"error"}
     * @response 401 {"message":"Insufficient balance","status":401,"data":[],"type":"error"}
     *
     * @param  \App\Http\Requests\TransactionRequest  $request
     * @param  \App\Repositories\BankAccountRepository  $repository
     * @return \Illuminate\Http\JsonResponse
     */
    public function transactions(TransactionRequest $request)
    {
        $requestValidated = $request->validated();

        $transaction = $this->repository->transaction($requestValidated);

        if ($transaction['type'] === 'error') {
            return $this->apiResponse->errorResponse(
                $transaction['message'],
                $transaction['data'],
                $transaction['status']
            );
        }

        return $this->apiResponse->successResponse(
            $transaction['message'],
            $transaction['data'],
            $transaction['status']
        );
    }
}
