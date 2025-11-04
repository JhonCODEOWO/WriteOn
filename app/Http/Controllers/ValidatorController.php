<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateUniqueRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ValidatorController extends Controller
{
    public function unique(ValidateUniqueRequest $request){
        $available = true;
        $validatedRequestData = $request->validated();
        
        //Create rule unique
        $uniqueRule = Rule::unique($validatedRequestData["table"], $validatedRequestData["column"]);

        if($request->validated('updating') === true) $uniqueRule->ignore($validatedRequestData["id"]); //If updating is true then apply ignore rules.

        $validation = Validator::make(
            ["value" => $validatedRequestData["value"]],
            [
                "value" => [$uniqueRule]
            ],
        );

        if($validation->fails()) $available = false;

        return response()->json([
            "valid" => $available
        ]);
    }
}
