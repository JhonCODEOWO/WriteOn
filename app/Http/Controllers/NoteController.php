<?php

namespace App\Http\Controllers;

use App\Dtos\NoteDto;
use App\Dtos\NoteResourceDto;
use App\Models\Note;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Services\NoteService;

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
}
