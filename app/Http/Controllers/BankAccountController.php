<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBankAccountRequest;
use App\Http\Requests\UpdateBankAccountRequest;
use App\Repositories\BankAccountRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Bank account management
 * APIs for managing bank accounts
 */

class BankAccountController extends Controller
{
    private $repository;

    public function __construct(BankAccountRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * Get all bank accounts
     * @queryParam search string Search term. Example: John
     * @queryParam sortBy string Column to sort by. Example: created_at
     * @queryParam sortDirection string Sort direction. Example: asc
     * @queryParam page int Number of items per page. Example: 1
     * @queryParam take int Number of items to take. Example: 10
     * @param BankAccountRepository $repository
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $this->enableFilters();
        $filters = $this->getFilters($request);
        $account = $this->repository->filter($filters);
        $message = empty($account) ? 'No records found' : 'Records found';
        return $this->apiResponse->successResponse($message, $account->toArray());
    }

    /**
     * Create a new bank account
     * @bodyParam name string required Name of the account. Example: John Doe
     * @bodyParam balance numeric required Initial balance of the account. Example: 1000
     * @param StoreBankAccountRequest $request
     * @param BankAccountRepository $repository
     * @return JsonResponse
     */
    public function store(StoreBankAccountRequest $request)
    {
        $requestValidated = $request->validated();
        $account = $this->repository->create($requestValidated);
        return $this->apiResponse->successResponse('Account created', $account->toArray(), 201);
    }

    /**
     * Update a bank account
     * @urlParam id required The ID of the account. Example: 1
     * @bodyParam name string required Name of the account. Example: John Doe
     * @bodyParam balance numeric required Initial balance of the account. Example: 1000
     * @param StoreBankAccountRequest $request
     * @param BankAccountRepository $repository
     * @return JsonResponse
     */
    public function update(UpdateBankAccountRequest $request, $id)
    {
        $requestValidated = $request->validated();
        $account = $this->repository->update($id, $requestValidated);
        return $this->apiResponse->successResponse('Account updated', $account->toArray());
    }

    /**
     * Get a bank account
     * @urlParam id required The ID of the account. Example: 1
     * @param BankAccountRepository $repository
     * @return JsonResponse
     */
    public function show($id)
    {
        $account = $this->repository->getById($id);
        return $this->apiResponse->successResponse('Account found', $account->toArray());
    }


}
