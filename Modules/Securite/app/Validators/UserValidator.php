<?php

namespace Modules\Securite\App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Info(title="User Validator", version="1.0")
 */
class UserValidator
{

    public static function create(array $data){
        $validator = Validator::make($data,[
            'nom'=> 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email'=> 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'state' => 'required|in:ACTIVE,BLOCKED'
        ]);

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function update(array $data){
        $validator = Validator::make($data,[
            'nom'=> 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email'=> 'required|email|unique:users,email,'.$data['id'],
            'password' => 'nullable|string|min:8',
            'state' => 'required|in:ACTIVE,BLOCKED'
        ]);

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function assignRole(array $data){
        $validator = Validator::make($data,[
            'role_id' => 'required|exists:roles,id'
        ]);

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function unassignRole(array $data){
        $validator = Validator::make($data,[
            'role_id' => 'required|exists:roles,id'
        ]);

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}

