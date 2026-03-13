<?php

namespace Modules\Securite\App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Info(title="Permission Validator", version="1.0")
 */
class PermissionValidator
{

    public static function create(array $data){
        $validator = Validator::make($data,[
            'name'=> 'required|string|max:255|unique:permissions,name',
            'description' => 'nullable|string|max:500',
            'state' => 'required|in:ACTIVE,BLOCKED'
        ]);

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function update(array $data){
        $validator = Validator::make($data,[
            'name'=> 'required|string|max:255|unique:permissions,name,'.$data['id'],
            'description' => 'nullable|string|max:500',
            'state' => 'required|in:ACTIVE,BLOCKED'
        ]);

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}

