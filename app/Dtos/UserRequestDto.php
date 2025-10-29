<?php

namespace App\Dtos;
use App\Interfaces\RequestDtoInterface;

/**
 * UserRequestDto
 * 
 * Data Transfer Object used to encapsulate relevant information about
 * a user when creating or updating a user.
 */
class UserRequestDto implements RequestDtoInterface {
        
    /**
     * __construct
     *
     * @param  null|string $name  The fullname of the user
     * @param  null|string $email  The user's email address
     * @param  null|string $password The user's password (hashed at the end by Laravel)
     * @return void
     */
    public function __construct(public ?string $name, public ?string $email, public ?string $password){}

    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn($property)=> $property!=null);
    }
}