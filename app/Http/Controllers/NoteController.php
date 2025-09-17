<?php

namespace App\Http\Controllers;

use App\Dtos\NoteDto;
use App\Dtos\NoteResourceDto;
use App\Http\Requests\AddCollaboratorsToNoteRequest;
use App\Http\Requests\AddCollabToNoteRequest;
use App\Http\Requests\DestroyCollabRequest;
use App\Models\Note;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\User;
use App\Services\NoteService;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(NoteService $noteService)
    {
        return $noteService->showAll();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request, NoteService $noteService)
    {
        $noteDto = new NoteDto($request->validated(), $request->user()->id);

        return response()->json($noteService->createNote($noteDto));
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note, NoteService $noteService)
    {
        return response()->json(new NoteResourceDto($noteService->find($note->id)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, Note $note, NoteService $noteService)
    {
        $noteDto = new NoteDto($request->validated(), $request->user()->id);
        $updated = new NoteResourceDto($noteService->update($note->id, $noteDto));
        return response()->json($updated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note, NoteService $noteService)
    {
        return $noteService->delete($note->id);
    }
    
    /**
     *  Controller action witch retrieves all collaborators available to assign in a note
     *
     * @param  mixed $note
     * @param  mixed $noteService
     * @return void
     */
    public function getAvailableCollaborators(Note $note, NoteService $noteService){
        return $noteService->availableCollaborators($note);
    }
    
    /**
     *  Controller action witch add collaborators to a note only if the user request is the owner and the note shared flag is active
     *
     * @param  App\Models\Note $note
     * @param  App\Services\NoteService $noteService
     * @param  App\Http\Request\AddCollabToNoteRequest $request
     * @return bool True if the action was successful otherwise an abort error
     */
    public function addCollaborators(Note $note, NoteService $noteService, AddCollabToNoteRequest $request){
        $safe = $request->validated();
        return $noteService->addCollaborators($safe['collaborators'], $note);
    }

    public function destroyCollaborator(Note $note, User $user, DestroyCollabRequest $request, NoteService $noteService){
        return $noteService->deleteCollaborator($note, $user);
    }
}
