<?php

namespace App\Enums;

use App\Models\Ip;
use App\Models\User;

enum BanType
{
    case User;
    case Ip;
    case Points;

    public function bannable(): string
    {
        return match ($this) {
            BanType::User => User::class,
            BanType::Ip => Ip::class,
            BanType::Points => User::class,
        };
    }
}
