<?php

namespace App\Http\Controllers;

use App\Actions\ProductGroup\ProductGroupActions;
use App\Http\Requests\ProductGroupRequest;
use App\Http\Resources\ProductGroupResource;
use App\Models\ProductGroup;
use Exception;

class ProductGroupController extends Controller
{
    private $productGroupActions;

    public function __construct(ProductGroupActions $productGroupActions)
    {
        parent::__construct();

        $this->productGroupActions = $productGroupActions;
    }

    public function store(ProductGroupRequest $productGroupRequest)
    {
        $request = $productGroupRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->productGroupActions->create($request);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return is_null($result) ? response()->error($errorMsg) : response()->success();
    }

    public function readAny(ProductGroupRequest $productGroupRequest)
    {
        $request = $productGroupRequest->validated();

        $companyId = $request['company_id'];

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->productGroupActions->readAny(
                useCache: $request['refresh'],
                withTrashed: $request['with_trashed'],

                search: $request['search'],
                companyId: $request['company_id'],

                paginate: $request['paginate'],
                page: $request['page'],
                perPage: $request['per_page'],
                limit: $request['limit'],
            );
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        if (is_null($result)) {
            return response()->error($errorMsg);
        } else {
            $response = new ProductGroupResource($result);

            return $response;
        }
    }

    public function read(ProductGroup $productGroup, ProductGroupRequest $productGroupRequest)
    {
        $request = $productGroupRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->productGroupActions->read($productGroup);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        if (is_null($result)) {
            return response()->error($errorMsg);
        } else {
            $response = new ProductGroupResource($result);

            return $response;
        }
    }

    public function update(ProductGroup $productGroup, ProductGroupRequest $productGroupRequest)
    {
        $request = $productGroupRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->productGroupActions->update(
                productGroup: $productGroup,
                data: $request
            );
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return is_null($result) ? response()->error($errorMsg) : response()->success();
    }

    public function delete(ProductGroup $productGroup, ProductGroupRequest $productGroupRequest)
    {
        $result = false;
        $errorMsg = '';

        try {
            $result = $this->productGroupActions->delete($productGroup);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return ! $result ? response()->error($errorMsg) : response()->success();
    }
}
