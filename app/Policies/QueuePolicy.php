<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Queue;

class QueuePolicy
{
    /**
     * Menentukan apakah user bisa membatalkan antrian.
     */
    public function cancel(User $user, Queue $queue)
    {
        // User hanya bisa cancel jika antrian miliknya dan status masih WAITING
        return $user->id === $queue->user_id && $queue->status === 'WAITING';
    }
}