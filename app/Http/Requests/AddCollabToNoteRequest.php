<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AddCollabToNoteRequest extends FormRequest
{
    /**
     *  Determine if the user is the owner of the note and it is shared
     */
    public function authorize(): bool
    {
        $note = $this->route('note');
        return $this->user()->id === $note->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "collaborators" => "required|array|min:1",
            "collaborators.*" => "required|string|exists:users,id"
        ];
    }

    protected function prepareForValidation()
    {
        //Take actions to prevent another not valid requests before execute operations using it
        $collaborators = $this->input('collaborators');
        
        if(is_array($collaborators)){
            $filteredUsers = array_filter($this->input('collaborators'), fn($uuid) => $uuid != $this->user()->id); //Clean array to get only users different than owner.

            //Checks if the array contains only 1 element and if that element is the UUID of the user.
            if(count($collaborators) === 1 && array_any($collaborators, fn($element) => $this->user()->id === $element)) {
                throw ValidationException::withMessages(["collaborators" => "Can't add yourself as collaborator"]);
            }

            //Apply users
            $this->merge([
                "collaborators" => array_values($filteredUsers)
            ]);
        }
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException("Can't add collaborators you aren't the owner of this note or it isn't shared yet, try to enable shared option");
    }
}
