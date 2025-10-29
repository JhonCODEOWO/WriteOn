<?php

namespace App\Services;

use App\Dtos\UserRequestDto;
use App\Models\User;
use App\UserResourceDto;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function create(UserRequestDto $req): User{
        DB::beginTransaction();
        try {
            $user = User::create($req->toArray());
            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            Log::error("Can't create the user requested " . $th->getMessage());
            abort(500, "Unexpected error, read logs");
        }
    }
}
