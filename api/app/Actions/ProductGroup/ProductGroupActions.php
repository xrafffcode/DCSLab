<?php

namespace App\Actions\ProductGroup;

use App\Actions\Randomizer\RandomizerActions;
use App\Models\ProductGroup;
use App\Traits\CacheHelper;
use App\Traits\LoggerHelper;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ProductGroupActions
{
    use CacheHelper;
    use LoggerHelper;

    public function __construct()
    {
    }

    public function create(
        array $productGroupArr
    ): ProductGroup {
        DB::beginTransaction();
        $timer_start = microtime(true);

        try {
            $company_id = $productGroupArr['company_id'];
            $code = $productGroupArr['code'];
            $name = $productGroupArr['name'];
            $category = $productGroupArr['category'];

            $productGroup = new ProductGroup();
            $productGroup->company_id = $company_id;
            $productGroup->code = $code;
            $productGroup->name = $name;
            $productGroup->category = $category;
            $productGroup->save();

            DB::commit();

            $this->flushCache();

            return $productGroup;
        } catch (Exception $e) {
            DB::rollBack();
            $this->loggerDebug(__METHOD__, $e);
            throw $e;
        } finally {
            $execution_time = microtime(true) - $timer_start;
            $this->loggerPerformance(__METHOD__, $execution_time);
        }
    }

    public function readAny(
        int $companyId,
        string $search = '',
        bool $paginate = true,
        int $page = 1,
        int $perPage = 10,
        array $with = [],
        bool $withTrashed = false,
        bool $useCache = true
    ): Paginator|Collection {
        $timer_start = microtime(true);
        $recordsCount = 0;

        try {
            $cacheSearch = empty($search) ? '[empty]' : $search;
            $cacheKey = 'readAny_'.$companyId.'_'.$cacheSearch.'-'.$paginate.'-'.$page.'-'.$perPage;
            if ($useCache) {
                $cacheResult = $this->readFromCache($cacheKey);

                if (! is_null($cacheResult)) {
                    return $cacheResult;
                }
            }

            $result = null;

            if (! $companyId) {
                return null;
            }

            $relationship = ['company'];
            $relationship = count($with) > 0 ? $with : $relationship;
            $query = ProductGroup::with($relationship);

            $query = $query->whereCompanyId($companyId);

            if (! empty($search)) {
                $query = $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                });
            }

            if ($withTrashed) {
                $query = $query->withTrashed();
            }

            $query = $query->latest();

            if ($paginate) {
                $perPage = is_numeric($perPage) ? abs($perPage) : Config::get('dcslab.PAGINATION_LIMIT');
                $page = is_numeric($page) ? abs($page) : 1;

                $result = $query->paginate(perPage: $perPage, page: $page);
            } else {
                $result = $query->get();
            }

            $recordsCount = $result->count();

            $this->saveToCache($cacheKey, $result);

            return $result;
        } catch (Exception $e) {
            $this->loggerDebug(__METHOD__, $e);
            throw $e;
        } finally {
            $execution_time = microtime(true) - $timer_start;
            $this->loggerPerformance(__METHOD__, $execution_time);
        }
    }

    public function read(ProductGroup $productGroup): ProductGroup
    {
        return $productGroup->load('company');
    }

    public function update(
        ProductGroup $productGroup,
        array $productGroupArr,
    ): ProductGroup {
        DB::beginTransaction();
        $timer_start = microtime(true);

        try {
            $code = $productGroupArr['code'];
            $name = $productGroupArr['name'];
            $category = $productGroupArr['category'];

            $productGroup->update([
                'code' => $code,
                'name' => $name,
                'category' => $category,
            ]);

            DB::commit();

            $this->flushCache();

            return $productGroup->refresh();
        } catch (Exception $e) {
            DB::rollBack();
            $this->loggerDebug(__METHOD__, $e);
            throw $e;
        } finally {
            $execution_time = microtime(true) - $timer_start;
            $this->loggerPerformance(__METHOD__, $execution_time);
        }
    }

    public function delete(ProductGroup $productGroup): bool
    {
        DB::beginTransaction();
        $timer_start = microtime(true);

        $retval = false;
        try {
            $retval = $productGroup->delete();

            DB::commit();

            $this->flushCache();

            return $retval;
        } catch (Exception $e) {
            DB::rollBack();
            $this->loggerDebug(__METHOD__, $e);
            throw $e;
        } finally {
            $execution_time = microtime(true) - $timer_start;
            $this->loggerPerformance(__METHOD__, $execution_time);
        }
    }

    public function generateUniqueCode(): string
    {
        $rand = app(RandomizerActions::class);
        $code = $rand->generateAlpha().$rand->generateNumeric();

        return $code;
    }

    public function isUniqueCode(string $code, int $companyId, ?int $exceptId = null): bool
    {
        $result = ProductGroup::whereCompanyId($companyId)->where('code', '=', $code);

        if ($exceptId) {
            $result = $result->where('id', '<>', $exceptId);
        }

        return $result->count() == 0 ? true : false;
    }
}
