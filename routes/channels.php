<?php

use App\Models\Note;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('note.{noteID}', function($user, $noteID) {
    //Try to load the note
    $note = Note::find($noteID);

    //If the user trying to subscribe is collaborator then give the subscription
    return $note && $note->is_shared && $note->collaborators->contains($user->id);
});
