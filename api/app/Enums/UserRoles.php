<?php

namespace App\Enums;

use App\Traits\EnumHelper;

enum UserRoles: string
{
    use EnumHelper;

    case USER = 'user';
    case DEVELOPER = 'developer';
    case ADMINISTRATOR = 'administrator';

    //region Extensions

    //endregion
}
