<?php

namespace App\Dtos;

use App\Interfaces\RequestDtoInterface;
use Mews\Purifier\Facades\Purifier;

class NoteDto implements RequestDtoInterface
{
    private ?string $title;
    private ?string $content;
    private ?bool $is_shared;
    private ?string $user_id;
    private ?array $tags;

    /**
     * Create a new dto instance
     * @param array $reqData Data validated to pass in dto.
     */
    public function __construct(array $reqData, string $user_id)
    {
        $this->title = $reqData['title'] ?? null;
        $this->content = Purifier::clean($reqData['content'] ?? '');
        $this->is_shared = $reqData['is_shared'] ?? false;
        $this->user_id = $user_id;
        $this->tags = $reqData['tags'] ?? [];
    }

    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn($element) => $element !== null);
    }
}
