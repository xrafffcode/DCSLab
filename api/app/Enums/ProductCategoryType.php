<?php

namespace App\Enums;

use App\Traits\EnumHelper;

enum ProductCategoryType: int
{
    use EnumHelper;

    case PRODUCT = 1;
    case SERVICE = 2;
}
