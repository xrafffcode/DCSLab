<?php

namespace App\Http\Controllers;

use App\Actions\User\UserActions;
use App\Http\Requests\SearchRequest;

class SearchController extends BaseController
{
    private $userActions;

    public function __construct(UserActions $userActions)
    {
        parent::__construct();

        $this->userActions = $userActions;
    }

    public function search(SearchRequest $searchRequest)
    {

    }
}
