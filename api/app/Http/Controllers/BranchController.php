<?php

namespace App\Http\Controllers;

use App\Actions\Branch\BranchActions;
use App\Http\Requests\BranchRequest;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use App\Models\Company;
use Exception;

class BranchController extends BaseController
{
    private $branchActions;

    public function __construct(BranchActions $branchActions)
    {
        parent::__construct();

        $this->branchActions = $branchActions;
    }

    public function store(BranchRequest $branchRequest)
    {
        $request = $branchRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->branchActions->create($request);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return is_null($result) ? response()->error($errorMsg) : response()->success();
    }

    public function readAny(BranchRequest $branchRequest)
    {
        $request = $branchRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->branchActions->readAny(
                companyId: $request['company_id'],
                useCache: $request['refresh'],
                with: [],
                withTrashed: false,

                search: $request['search'],
                isMain: $request['is_main'],
                status: $request['status'],

                paginate: $request['paginate'],
                page: $request['page'],
                perPage: $request['per_page'],
            );
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        if (is_null($result)) {
            return response()->error($errorMsg);
        } else {
            $response = BranchResource::collection($result);

            return $response;
        }
    }

    public function read(Branch $branch, BranchRequest $branchRequest)
    {
        $request = $branchRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->branchActions->read($branch);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        if (is_null($result)) {
            return response()->error($errorMsg);
        } else {
            $response = new BranchResource($result);

            return $response;
        }
    }

    public function getBranchByCompany(Company $company)
    {
        $result = null;
        $errorMsg = '';

        try {
            $result = $this->branchActions->getBranchByCompany(company: $company);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        if (is_null($result)) {
            return response()->error($errorMsg);
        } else {
            $response = BranchResource::collection($result);

            return $response;
        }
    }

    public function getMainBranchByCompany(Company $company)
    {
        $result = null;
        $errorMsg = '';

        try {
            $result = $this->branchActions->getMainBranchByCompany(company: $company);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        if (is_null($result)) {
            return response()->error($errorMsg);
        } else {
            return $result;
        }
    }

    public function update(Branch $branch, BranchRequest $branchRequest)
    {
        $request = $branchRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->branchActions->update(
                branch: $branch,
                data: $request
            );
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return is_null($result) ? response()->error($errorMsg) : response()->success();
    }

    public function delete(Branch $branch, BranchRequest $branchRequest)
    {
        if ($branch->is_main) {
            return response()->error(trans('rules.branch.delete_main_branch'), 422);
        }

        $result = false;
        $errorMsg = '';

        try {
            $result = $this->branchActions->delete($branch);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return ! $result ? response()->error($errorMsg) : response()->success();
    }
}
