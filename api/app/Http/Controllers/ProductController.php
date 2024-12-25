<?php

namespace App\Http\Controllers;

use App\Actions\Product\ProductActions;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Exception;

class ProductController extends BaseController
{
    private $productActions;

    public function __construct(ProductActions $productActions)
    {
        parent::__construct();

        $this->productActions = $productActions;
    }

    public function store(ProductRequest $productRequest)
    {
        $request = $productRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->productActions->create($request);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return is_null($result) ? response()->error($errorMsg) : response()->success();
    }

    public function readAny(ProductRequest $productRequest)
    {
        $request = $productRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->productActions->readAny(
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
            $response = ProductResource::collection($result);

            return $response;
        }
    }

    public function read(Product $product, ProductRequest $productRequest)
    {
        $request = $productRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->productActions->read($product);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        if (is_null($result)) {
            return response()->error($errorMsg);
        } else {
            $response = new ProductResource($result);

            return $response;
        }
    }

    public function update(Product $product, ProductRequest $productRequest)
    {
        $request = $productRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->productActions->update(
                product: $product,
                data: $request
            );
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return is_null($result) ? response()->error($errorMsg) : response()->success();
    }

    public function delete(Product $product, ProductRequest $productRequest)
    {
        $result = false;
        $errorMsg = '';

        try {
            $result = $this->productActions->delete($product);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return ! $result ? response()->error($errorMsg) : response()->success();
    }
}
