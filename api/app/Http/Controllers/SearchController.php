<?php

namespace App\Http\Controllers;

use App\Actions\Branch\BranchActions;
use App\Actions\Company\CompanyActions;
use App\Actions\User\UserActions;
use App\Http\Requests\SearchRequest;

class SearchController extends BaseController
{
    private $userActions;

    private $companyActions;

    private $branchActions;

    public function __construct(
        UserActions $userActions,
        CompanyActions $companyActions,
        BranchActions $branchActions
    ) {
        parent::__construct();

        $this->userActions = $userActions;
        $this->companyActions = $companyActions;
        $this->branchActions = $branchActions;
    }

    public function search(SearchRequest $searchRequest)
    {
        $request = $searchRequest->validated();

    }
}
