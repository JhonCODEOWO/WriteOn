<?php

namespace App\Services;

use App\Models\User;
use App\UserResourceDto;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }



    public function all(): LengthAwarePaginator{
        $users = User::where('name', 'like', '%'.''.'%')->paginate(20);
        $users->setCollection($users->getCollection()->map(fn($user) => new UserResourceDto($user)));
        return $users;
    }
}
