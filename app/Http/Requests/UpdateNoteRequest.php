<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $note = $this->route('note'); //Get note by Model binding
        return $note->user_id === $this->user()->id || $note->collaborators()->where('user_id', $this->user()->id)->exists(); //Validate if owner is the same or is a collaborator
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => "sometimes|string|min:8",
            "content" => "sometimes|string",
            "is_shared" => "sometimes|boolean",
            "tags" => "sometimes|array|min:1",
            "tags.*" => "required|string|exists:tags,id"
        ];
    }

    //Prepare data before apply validations
    protected function prepareForValidation()
    {
        $note = $this->route('note');
        if($this->user()->id !== $note->user->id && $this->has('is_shared')) $this->merge(["is_shared" => $note->is_shared]); //If the user is different than owner then apply the same older value of the note
    }
}
