<?php

namespace App\Enums;

enum PostStatusEnum: string
{
    case pending = "Pending";
    case published = "Published";

    case cancel = "Cancel";
}
