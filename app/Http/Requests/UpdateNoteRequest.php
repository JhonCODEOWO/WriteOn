<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //TODO: VALIDATE IF THE NOTE HAS COLABORATORS AND ITS ONE OF THEM IN THE REQUEST
        $note = $this->route('note'); //Get note by Model binding
        return ($note->user_id === $this->user()->id)? true: false; //Validate if owner is the same
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
}
