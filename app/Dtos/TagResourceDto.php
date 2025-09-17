<?php

namespace App\Dtos;

use App\Models\Tag;

class TagResourceDto
{
    private string $id;
    private string $name;
    /**
     * Create a new class instance.
     */
    public function __construct(Tag $tag)
    {
        $this->id = $tag->id;
        $this->name = $tag->name;
    }

    public function toArray(){
        return get_object_vars($this);
    }
}
