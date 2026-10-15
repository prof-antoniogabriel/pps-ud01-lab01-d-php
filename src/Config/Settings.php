<?php

declare(strict_types=1);

namespace App\Config;

final class Settings
{
    public static function environment(): string
    {
        return getenv('APP_ENV') ?: 'development';
    }
}
