<?php

namespace App;

use App\Models\User;

class UserResourceDto
{
    public string $id;
    public string $name;
    public string $email;
    /**
     * Create a new class instance.
     */
    public function __construct(User $user)
    {
        $this->id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
    }
}
