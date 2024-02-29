<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Services\Api\ApiResponseService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    protected bool $filters = false;
    protected ApiResponseService $apiResponse;

    protected array $keysAvailable = ['search', 'sortBy', 'sortDirection', 'page', 'limit', 'take', 'offset'];


    public function __construct()
    {
        $this->apiResponse = new ApiResponseService();
    }

    protected function enableFilters(): void
    {
        $this->filters = true;
    }

    protected function getFilters(Request $request)
    {
        if ($this->filters) {
            return $request->only($this->keysAvailable);
        }
    }
}
