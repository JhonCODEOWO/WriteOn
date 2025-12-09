<?php

namespace App\Dtos;
use App\Interfaces\RequestDtoInterface;

/**
 *  A DTO to define the structure of simple responses.
 */
class ResponseDto implements RequestDtoInterface {
    public function __construct(public bool $success, public string $message)
    {
        
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}