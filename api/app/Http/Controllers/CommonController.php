<?php

namespace App\Http\Controllers;

use App\Enums\RecordStatus;

class CommonController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCountries()
    {
        return [
            ['name' => 'Indonesia', 'code' => 'ID'],
            ['name' => 'Singapore', 'code' => 'SG'],
        ];
    }

    public function getStatus()
    {
        return [
            ['name' => 'components.dropdown.values.statusDDL.active', 'code' => RecordStatus::ACTIVE->value],
            ['name' => 'components.dropdown.values.statusDDL.inactive', 'code' => RecordStatus::INACTIVE->value],
        ];
    }
}
