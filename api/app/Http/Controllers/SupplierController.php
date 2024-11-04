<?php

namespace App\Http\Controllers;

use App\Actions\Supplier\SupplierActions;
use App\Http\Requests\SupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Exception;
use Illuminate\Support\Facades\Auth;

class SupplierController extends BaseController
{
    private $supplierActions;

    public function __construct(SupplierActions $supplierActions)
    {
        parent::__construct();

        $this->supplierActions = $supplierActions;
    }

    public function store(SupplierRequest $supplierRequest)
    {
        $request = $supplierRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->supplierActions->create($request);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return is_null($result) ? response()->error($errorMsg) : response()->success();
    }

    public function readAny(SupplierRequest $supplierRequest)
    {
        $request = $supplierRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->supplierActions->readAny(
                user: Auth::user(),
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
            $response = SupplierResource::collection($result);

            return $response;
        }
    }

    public function read(Supplier $supplier, SupplierRequest $supplierRequest)
    {
        $request = $supplierRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->supplierActions->read($supplier);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        if (is_null($result)) {
            return response()->error($errorMsg);
        } else {
            $response = new SupplierResource($result);

            return $response;
        }
    }

    public function update(Supplier $supplier, SupplierRequest $supplierRequest)
    {
        $request = $supplierRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->supplierActions->update(
                supplier: $supplier,
                data: $request
            );
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return is_null($result) ? response()->error($errorMsg) : response()->success();
    }

    public function delete(Supplier $supplier, SupplierRequest $supplierRequest)
    {
        $result = false;
        $errorMsg = '';

        try {
            $result = $this->supplierActions->delete($supplier);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return ! $result ? response()->error($errorMsg) : response()->success();
    }
}
