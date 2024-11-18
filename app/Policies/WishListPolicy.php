<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WishList;

class WishListPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Wishlist delete policy
    */
    public function delete(User $user, WishList $wishList ): bool
    {
        return $user->getAttribute(key: 'id') == $wishList->getAttribute(key: 'user_id');
    }
}
