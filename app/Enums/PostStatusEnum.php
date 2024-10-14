<?php

namespace App\Enums;

enum PostStatusEnum: string
{
    case pending = 'Pending';
    case published = 'Published';

    case rejected = 'Rejected';

    public function getColor(): string
    {

        return match ($this) {
            self::published => 'success',
            self::pending => 'warning',
            self::rejected => 'danger'
        };

    }
}
