<?php

namespace App\Http\Controllers;

use App\Actions\Warehouse\WarehouseActions;
use App\Http\Requests\WarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use Exception;

class WarehouseController extends BaseController
{
    private $warehouseActions;

    public function __construct(WarehouseActions $warehouseActions)
    {
        parent::__construct();

        $this->warehouseActions = $warehouseActions;
    }

    public function store(WarehouseRequest $warehouseRequest)
    {
        $request = $warehouseRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->warehouseActions->create($request);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return is_null($result) ? response()->error($errorMsg) : response()->success();
    }

    public function readAny(WarehouseRequest $warehouseRequest)
    {
        $request = $warehouseRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->warehouseActions->readAny(
                useCache: $request['refresh'],
                with: [],
                withTrashed: $request['with_trashed'],

                search: $request['search'],
                companyId: $request['company_id'],
                branchId: $request['branch_id'],
                status: $request['status'],

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
            $response = WarehouseResource::collection($result);

            return $response;
        }
    }

    public function read(Warehouse $warehouse, WarehouseRequest $warehouseRequest)
    {
        $request = $warehouseRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->warehouseActions->read($warehouse);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        if (is_null($result)) {
            return response()->error($errorMsg);
        } else {
            $response = new WarehouseResource($result);

            return $response;
        }
    }

    public function update(Warehouse $warehouse, WarehouseRequest $warehouseRequest)
    {
        $request = $warehouseRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->warehouseActions->update(
                $warehouse,
                $request
            );
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return is_null($result) ? response()->error($errorMsg) : response()->success();
    }

    public function delete(Warehouse $warehouse, WarehouseRequest $warehouseRequest)
    {
        $result = false;
        $errorMsg = '';

        try {
            $result = $this->warehouseActions->delete($warehouse);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return ! $result ? response()->error($errorMsg) : response()->success();
    }
}
