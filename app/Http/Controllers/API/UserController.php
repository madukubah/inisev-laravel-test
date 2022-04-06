<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return $this->sendResponse($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();


        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email|unique:users'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $user = User::create($input);

        return $this->sendResponse($user, 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

        if (is_null($user)) {
            return $this->sendError('User not found.');
        }

        return $this->sendResponse( $user, 'User retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $input = $request->all();
        
        $inputValidation = [];
        if(isset($input['name'])) $inputValidation['name'] = 'required';
        if(isset($input['email'])) $inputValidation['email'] = 'required|email|unique:users';

        $validator = Validator::make($input, $inputValidation);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        if(isset($input['name'])) $user->name = $input['name'];
        if( isset($input['email'])) $user->email = $input['email'];

        $user->save();

        return $this->sendResponse($user, 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (is_null($user)) {
            return $this->sendError('User not found.');
        }
        
        $user->delete();

        return $this->sendResponse([], 'User deleted successfully.');
    }
}
