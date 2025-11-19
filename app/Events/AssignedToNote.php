<?php

namespace App\Events;

use App\Dtos\NoteResourceDto;
use App\Models\Note;
use App\Models\User;
use App\Utils\BroadcastNoteStateEnum;
use App\Utils\NoteState;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssignedToNote implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    /**
     * __construct
     *
     * @param  mixed $user
     * @param  mixed $noteAssigned
     * @param  bool $added Indicate if the note is assigned or deleted from
     * @return void
     */
    public function __construct(public User $user, public Note $noteAssigned, public BroadcastNoteStateEnum $state = BroadcastNoteStateEnum::ASSIGNED)
    {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('note-assigned.'.$this->user->id),
        ];
    }

    public function broadcastWith(): array{
        return [
            "note" => get_object_vars(new NoteResourceDto($this->noteAssigned)),
            "status" => $this->state
        ];
    }
}
