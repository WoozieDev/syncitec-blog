<?php

namespace App\Enums;

enum CommentStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function isPublic(): bool
    {
        return $this === self::Approved;
    }
}
