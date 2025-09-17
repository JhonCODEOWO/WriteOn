<?php

namespace App\Services;

use App\Dtos\NoteDto;
use App\Dtos\NoteResourceDto;
use App\Interfaces\RequestDtoInterface;
use App\Models\Note;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NoteService
{
    
    /**
     *  Show all notes from DB 
     *  @notes Add logic to retrieve notes from own user and notes that can edit.
     *
     * @return LengthAwarePaginator
     */
    public function showAll(): LengthAwarePaginator{
        /** @var \Illuminate\Pagination\LengthAwarePaginator $notes */
        $notes = Note::paginate(10);
        $notes->setCollection($notes->getCollection()->map(fn($note) => new NoteResourceDto($note)));
        return $notes;
    }
    /**
     *  Create a new note in database using request dto
     *
     * @param  App\Dtos\NoteDto $data - All data parsed by DTO.
     * @return App\Models\Note Note created correctly
     */
    public function createNote(NoteDto $data): NoteResourceDto
    {
        DB::beginTransaction();
        try {
            //Try to create new note and fresh the instance created
            $note = Note::create($data->toArray())->fresh();

            //TODO: CREATE OR NOT TAGS BEFORE RELATED EACH ONE

            //Try to add tags
            $note->tags()->attach($data->toArray()['tags']);

            DB::commit();
            return new NoteResourceDto($note);
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error("Unable to create note " . $ex->getMessage());
            abort(500, 'Cant create new note');
        }
    }

    
    /**
     *  Find one note in database
     *
     * @param  string $uuid
     * @return Note Note found in the database without transform on DTO.
     */
    public function find(string $uuid): Note
    {
        $note = Note::findOrFail($uuid);
        return $note;
    }
    
    /**
     *  Update a note
     *
     * @param  mixed $uuid
     * @param  mixed $data
     * @return Note
     */
    public function update(string $uuid, NoteDto $data): Note{
        DB::beginTransaction();
        try {
            $note = $this->find($uuid);
            $note->update($data->toArray());
            DB::commit();
            //TODO: VERIFY IF IS NECESSARY UPDATE ALL TAGS TOO
            return $note;
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('Cant update note ' .$ex->getMessage());
            abort(500);
        }
    }

    /**
     * Delete a note from database
     *
     * @param  string $uuid
     * @return void
     */
    public function delete(string $uuid): bool
    {
        DB::beginTransaction();
        try {
            $note = $this->find($uuid);
            $note->delete();
            DB::commit();
            return true;
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('Cant delete note with uuid ' . $uuid);
            abort(500);
        }
    }
}
