<?php

namespace Modules\Securite\App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Info(title="Role Validator", version="1.0")
 */
class RoleValidator
{

    public static function create(array $data){
        $validator = Validator::make($data,[
            'name'=> 'required|string|max:255|unique:roles,name',
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
            'name'=> 'required|string|max:255|unique:roles,name,'.$data['id'],
            'description' => 'nullable|string|max:500',
            'state' => 'required|in:ACTIVE,BLOCKED'
        ]);

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function assignPermission(array $data){
        $validator = Validator::make($data,[
            'permission_id' => 'required|exists:permissions,id'
        ]);

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function unassignPermission(array $data){
        $validator = Validator::make($data,[
            'permission_id' => 'required|exists:permissions,id'
        ]);

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}

