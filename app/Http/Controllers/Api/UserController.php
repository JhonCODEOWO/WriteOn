<?php

namespace App\Http\Controllers\Api;

use App\Dtos\UserRequestDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use App\Services\UserService;
use App\UserResourceDto;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UserService $userService)
    {
        $users = $userService->all();
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request, UserService $userService)
    {
        $dto = new UserRequestDto($request->validated('name'), $request->validated('email'), $request->validated('password'));
        $newUser = new UserResourceDto($userService->create($dto));
        return response()->json($newUser);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
