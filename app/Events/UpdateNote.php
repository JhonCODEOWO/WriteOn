<?php

namespace App\Events;

use App\Dtos\NoteResourceDto;
use App\Models\Note;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateNote implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The DTO to retrieve in this broadcast
     */
    private NoteResourceDto $note_resource;
    /**
     * Create a new event instance.
     * @param $note The note model to initialize in this broadcast id is used to create a private channel
     */
    public function __construct(public Note $note)
    {
        $this->note_resource = new NoteResourceDto($note);
    }

    /**
     * Get the channels the event should broadcast on.
     * Define el canal a donde se mandará la información, en este caso el el canal existente con el mismo id de la nota
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            // new PrivateChannel('note.'.$this->note->id),
            new PresenceChannel('note.'.$this->note->id)
        ];
    }

        
    /**
     *  Set the response to retrieve in each broadcast emission
     *
     * @return array
     */
    public function broadcastWith(): array {
        return ["note" => $this->note_resource];
    }
}
