<?php

namespace App\Dtos;

use App\Models\Note;
use App\Models\User;
use App\UserResourceDto;
use Illuminate\Support\Collection;

class NoteResourceDto
{
    public string $id;
    public string $title;
    public string $content;
    public array $tags;
    public UserResourceDto $owner;
    public bool $is_shared;
    public Collection $collaborators;
    /**
     * Create a new class instance.
     */
    public function __construct(Note $note)
    {
        $this->id = $note->id;
        $this->title = $note->title;
        $this->content = $note->content;
        $this->tags = array_map(fn($tag) => $tag['name'],$note->tags->toArray());
        $this->is_shared = $note->is_shared;
        $this->collaborators = $note->collaborators->map(fn($collaborator) => new UserResourceDto($collaborator));
        $this->owner = new UserResourceDto($note->user);
    }
}
