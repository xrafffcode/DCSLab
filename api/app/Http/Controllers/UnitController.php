<?php

namespace App\Http\Controllers;

use App\Actions\Unit\UnitActions;
use App\Http\Requests\UnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Exception;

class UnitController extends BaseController
{
    private $unitActions;

    public function __construct(UnitActions $unitActions)
    {
        parent::__construct();

        $this->unitActions = $unitActions;
    }

    public function store(UnitRequest $unitRequest)
    {
        $request = $unitRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->unitActions->create($request);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return is_null($result) ? response()->error($errorMsg) : response()->success();
    }

    public function readAny(UnitRequest $unitRequest)
    {
        $request = $unitRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->unitActions->readAny(
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
            $response = UnitResource::collection($result);

            return $response;
        }
    }

    public function read(Unit $unit, UnitRequest $unitRequest)
    {
        $request = $unitRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->unitActions->read($unit);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        if (is_null($result)) {
            return response()->error($errorMsg);
        } else {
            $response = new UnitResource($result);

            return $response;
        }
    }

    public function update(Unit $unit, UnitRequest $unitRequest)
    {
        $request = $unitRequest->validated();

        $result = null;
        $errorMsg = '';

        try {
            $result = $this->unitActions->update(
                unit: $unit,
                data: $request
            );
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return is_null($result) ? response()->error($errorMsg) : response()->success();
    }

    public function delete(Unit $unit, UnitRequest $unitRequest)
    {
        $result = false;
        $errorMsg = '';

        try {
            $result = $this->unitActions->delete($unit);
        } catch (Exception $e) {
            $errorMsg = app()->environment('production') ? '' : $e->getMessage();
        }

        return ! $result ? response()->error($errorMsg) : response()->success();
    }
}
