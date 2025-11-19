<?php

use App\Models\Note;
use App\Models\User;
use App\UserResourceDto;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Broadcast::channel('note.{noteID}', function(User $user, $noteID) {
//     //Try to load the note
//     $note = Note::find($noteID);

//     //If the user trying to subscribe is collaborator then give the subscription
//     return $note && $note->is_shared;
// });

//Presence Channel
Broadcast::channel('note.{noteID}', function(User $user, $noteID) {
    //Try to load the note
    $note = Note::find($noteID);
    $user = new UserResourceDto($user);

    //If the user trying to subscribe is collaborator then give the subscription
    return $note && $note->is_shared ? $user: false;
});

Broadcast::channel('note-assigned.{userID}', function(User $user, $userID){
    return $user->id === (string) $userID;
});