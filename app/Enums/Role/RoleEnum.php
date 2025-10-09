<?php

namespace App\Enums\Role;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case MODERATOR = 'moderator';
}
