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
    public function index(Request $request, BankAccountRepository $repository)
    {
        $this->enableFilters();
        $filters = $this->getFilters($request);
        $account = $repository->filter($filters);
        $message = empty($account) ? 'No records found' : 'Records found';
        return $this->apiResponse->successResponse($message, $account->toArray());
    }

}
