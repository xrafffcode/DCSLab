<?php

namespace App\Http\Controllers;

use App\Actions\ProductCategory\ProductCategoryActions;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use Exception;

class ProductCategoryController extends BaseController
{
    private $productCategoryActions;

    public function __construct(ProductCategoryActions $productCategoryActions)
    {
        parent::__construct();

        $this->productCategoryActions = $productCategoryActions;
    }

    public function store(ProductCategoryRequest $productCategoryRequest)
    {
        $request = $productCategoryRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->productCategoryActions->create($request);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return is_null($result) ? response()->error($errorMsg) : response()->success();
    }

    public function readAny(ProductCategoryRequest $productCategoryRequest)
    {
        $request = $productCategoryRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->productCategoryActions->readAny(
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
            $response = ProductCategoryResource::collection($result);

            return $response;
        }
    }

    public function read(ProductCategory $productCategory, ProductCategoryRequest $productCategoryRequest)
    {
        $request = $productCategoryRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->productCategoryActions->read($productCategory);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        if (is_null($result)) {
            return response()->error($errorMsg);
        } else {
            $response = new ProductCategoryResource($result);

            return $response;
        }
    }

    public function update(ProductCategory $productCategory, ProductCategoryRequest $productCategoryRequest)
    {
        $request = $productCategoryRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->productCategoryActions->update(
                productCategory: $productCategory,
                data: $request
            );
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return is_null($result) ? response()->error($errorMsg) : response()->success();
    }

    public function delete(ProductCategory $productCategory, ProductCategoryRequest $productCategoryRequest)
    {
        $result = false;
        $errorMsg = '';

        try {
            $result = $this->productCategoryActions->delete($productCategory);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return ! $result ? response()->error($errorMsg) : response()->success();
    }
}
