<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ValidateUniqueRequest extends FormRequest
{
    /** 
     *  Key-value constant to define witch tables and columns can be requested for unique validations
     * - Key = Table available to use for
     * - Value = Array of columns available to use
    */
    private const WHITELIST_TABLES_FIELDS = [
        //Key=table, value=available columns unique
        "users" => ['email'],
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "table" => "required|string",
            "value" => "required|string",
            "column" => "required|string",
            "updating" => "sometimes|boolean",
            "id" => "required_if:updating,true"
        ];
    }

    /**
     *  Prepare data from query parameters and route parameters and apply a array map to make global operations
     *  in each element
     *  @return void
     */
    protected function prepareForValidation(){
        $dataPrepared = [
            "table" => strtolower($this->route('table')),
            "value" => strtolower($this->route('value')),
            "column" => strtolower($this->query('column')),
            "updating" => filter_var($this->query('updating', false), FILTER_VALIDATE_BOOL),
            "id" => $this->query('id', null)
        ];
        $this->merge(array_map(fn($input) => gettype($input) === "string"? trim($input): $input, $dataPrepared));
    }

    /**
     * Apply extra validation after the first validation has passed successfully
    */
    public function withValidator($validator){
        $validator->after(function ($validator) {
            $table = $this->validated('table');
            $column = $this->validated('column');

            if(!isset(self::WHITELIST_TABLES_FIELDS[$table]) || !in_array($column, self::WHITELIST_TABLES_FIELDS[$table])) $validator->errors()->add('not-accessible-for-validate', 'Cant validate using table or column requested');

        });
    }
}
