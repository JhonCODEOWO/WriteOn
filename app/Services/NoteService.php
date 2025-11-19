<?php

namespace App\Services;

use App\Dtos\NoteDto;
use App\Dtos\NoteResourceDto;
use App\Events\UpdateNote;
use App\Interfaces\RequestDtoInterface;
use App\Models\Note;
use App\Models\User;
use App\UserResourceDto;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDOException;
use Throwable;

class NoteService
{
    
    /**
     *  Show all notes from DB 
     *  @notes Add logic to retrieve notes from own user and notes that can edit.
     *
     * @return LengthAwarePaginator
     */
    public function showAll(?string $user_ID = null): LengthAwarePaginator{
        //Define what query generate based on value of $user_ID
        $query = (isset($user_ID))
                            ? Note::where('user_id', $user_ID)
                                ->orWhereHas('collaborators', function($q) use ($user_ID){
                                    $q->where('user_id', $user_ID);
                                })

                            : Note::query();

        $notes = $query->paginate(10);
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

            //Get tags key from array dto element
            
            //Try to add tags
            $tags = $data->toArray()['tags'];

            if(count($tags) > 0)
                $note->tags()->attach($tags);

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
        $arrayData = $data->toArray();
        try {
            $note = $this->find($uuid);
            $note->update($arrayData); //Update note data
            $note->tags()->syncWithoutDetaching($arrayData['tags'] ?? []);
            DB::commit();
            UpdateNote::dispatch($note);
            //TODO: VERIFY IF IS NECESSARY UPDATE ALL TAGS TOO
            Log::info($note);
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
     * @return bool True if the note was deleted successfully
     */
    public function delete(string $uuid): bool
    {
        try {
            DB::beginTransaction();
            $note = $this->find($uuid);
            $note->delete();
            DB::commit();
            return true;
        } catch (PDOException $ex) {
            DB::rollBack();
            Log::error('Cant delete note with uuid ' . $uuid . ' ' . $ex->getMessage());
            return false;
        }
    }

    
    /**
     *  Get available collaborators to assign by the given note
     *
     * @param  mixed $note
     * @return array
     */
    public function availableCollaborators(Note $note): array{
        $collaborators = $note->collaborators->pluck('id');

        return User::whereNotIn('id', $collaborators)->get()->map(fn($user) => new UserResourceDto($user))->toArray();
    }

        
    /**
     *  Add collaborators to the note model provided
     *
     * @param  array $collaborators
     * @param  App\Models\Note $note
     * @return void
     */
    public function addCollaborators(array $collaborators, Note $note): bool{
        DB::beginTransaction();
        try {
            $note->collaborators()->syncWithoutDetaching($collaborators);
            DB::commit();
            return true;
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('An error occurred on addCollaborators method ' . $ex->getMessage());
            abort(500);
        }
    }
    
    /**
     *  Delete the given from the given note
     *
     * @param  App\Models\Note $note
     * @param  App\Models\User $user
     * @return bool
     */
    public function deleteCollaborator(Note $note, User $user): bool{
        DB::beginTransaction();
        try {
            $note->collaborators()->detach($user->id);
            DB::commit();
            return true;
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error("Can't delete collaborator cause of an error " . $ex->getMessage());
            return false;
        }
    }
}
