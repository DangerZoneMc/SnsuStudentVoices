<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Announcement;

class AnnouncementPolicy
{
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }
} 