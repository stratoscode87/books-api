<?php

namespace App\Enums\Review;

enum ReviewStatus: string
{
    case Processing = 'processing';
    case Completed = 'completed';
    case Error = 'error';
}
