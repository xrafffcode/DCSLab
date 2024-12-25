<?php

namespace App\Http\Controllers;

use App\Actions\Brand\BrandActions;
use App\Http\Requests\BrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Exception;

class BrandController extends BaseController
{
    private $brandActions;

    public function __construct(BrandActions $brandActions)
    {
        parent::__construct();

        $this->brandActions = $brandActions;
    }

    public function store(BrandRequest $brandRequest)
    {
        $request = $brandRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->brandActions->create($request);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return is_null($result) ? response()->error($errorMsg) : response()->success();
    }

    public function readAny(BrandRequest $brandRequest)
    {
        $request = $brandRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->brandActions->readAny(
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
            $response = BrandResource::collection($result);

            return $response;
        }
    }

    public function read(Brand $brand, BrandRequest $brandRequest)
    {
        $request = $brandRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->brandActions->read($brand);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        if (is_null($result)) {
            return response()->error($errorMsg);
        } else {
            $response = new BrandResource($result);

            return $response;
        }
    }

    public function update(Brand $brand, BrandRequest $brandRequest)
    {
        $request = $brandRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->brandActions->update(
                brand: $brand,
                data: $request
            );
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return is_null($result) ? response()->error($errorMsg) : response()->success();
    }

    public function delete(Brand $brand, BrandRequest $brandRequest)
    {
        $result = false;
        $errorMsg = '';

        try {
            $result = $this->brandActions->delete($brand);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return ! $result ? response()->error($errorMsg) : response()->success();
    }
}
