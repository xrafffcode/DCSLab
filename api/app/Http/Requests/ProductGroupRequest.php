<?php

namespace App\Http\Requests;

use App\Helpers\HashidsHelper;
use App\Helpers\RequestHelper;
use App\Models\ProductGroup;
use App\Rules\IsValidCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        /** @var \App\User */
        $user = Auth::user();
        $productGroup = $this->route('productGroup');

        $currentRouteMethod = $this->route()->getActionMethod();
        switch ($currentRouteMethod) {
            case 'readAny':
                return $user->can('viewAny', ProductGroup::class) ? true : false;
            case 'read':
                return $user->can('view', ProductGroup::class, $productGroup) ? true : false;
            case 'store':
                return $user->can('create', ProductGroup::class) ? true : false;
            case 'update':
                return $user->can('update', ProductGroup::class, $productGroup) ? true : false;
            case 'delete':
                return $user->can('delete', ProductGroup::class, $productGroup) ? true : false;
            default:
                return false;
        }
    }

    public function rules()
    {
        $currentRouteMethod = $this->route()->getActionMethod();
        switch ($currentRouteMethod) {
            case 'readAny':
                $rules_read_any = [
                    'company_id' => ['required', new IsValidCompany(), 'bail'],
                    'search' => ['present', 'string'],
                    'paginate' => ['required', 'boolean'],
                    'page' => ['required_if:paginate,true', 'numeric'],
                    'per_page' => ['required_if:paginate,true', 'numeric'],
                    'refresh' => ['nullable', 'boolean'],
                ];

                return $rules_read_any;
            case 'read':
                $rules_read = [

                ];

                return $rules_read;
            case 'store':
                $rules_store = [
                    'company_id' => ['required', new IsValidCompany(), 'bail'],
                    'code' => ['required', 'string'],
                    'name' => ['required', 'string'],
                    'category' => ['required', 'string'],
                ];

                return array_merge($rules_store);
            case 'update':
                $productGroup = $this->route('productGroup');
                $rules_update = [
                    'company_id' => ['required', new IsValidCompany(), 'bail'],
                    'code' => ['required', 'string'],
                    'name' => ['required', 'string'],
                    'category' => ['required', 'string'],
                ];

                return array_merge($rules_update);
            case 'delete':
                $rules_delete = [

                ];

                return $rules_delete;
            default:
                return [
                    '' => 'required',
                ];
        }
    }

    public function attributes()
    {
        return [
            'company_id' => trans('validation_attributes.product_group.company'),
            'code' => trans('validation_attributes.product_group.code'),
            'name' => trans('validation_attributes.product_group.name'),
            'category' => trans('validation_attributes.product_group.category'),
        ];
    }

    public function validationData()
    {
        $additionalArray = [];

        return array_merge($this->all(), $additionalArray);
    }

    public function prepareForValidation()
    {
        $currentRouteMethod = $this->route()->getActionMethod();
        switch ($currentRouteMethod) {
            case 'readAny':
                $this->merge([
                    'with_trashed' => $this->has('with_trashed') ? RequestHelper::safeReturn($this->with_trashed) : null,

                    'search' => $this->has('search') ? RequestHelper::safeReturn($this->search) : null,
                    'company_id' => $this->has('company_id') ? HashidsHelper::decodeId($this->company_id) : null,

                    'page' => $this->has('page') ? RequestHelper::safeReturn($this->page) : null,
                    'per_page' => $this->has('per_page') ? RequestHelper::safeReturn($this->per_page) : null,
                    'limit' => $this->has('limit') ? RequestHelper::safeReturn($this->limit) : null,
                ]);
                break;
            case 'read':
                $this->merge([]);
                break;
            case 'store':
            case 'update':
                $this->merge([
                    'company_id' => $this->has('company_id') ? HashidsHelper::decodeId($this->company_id) : null,
                ]);
                break;
            default:
                $this->merge([]);
        }
    }
}
