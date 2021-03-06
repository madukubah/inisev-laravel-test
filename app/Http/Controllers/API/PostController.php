<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Post;
use App\Models\UserSubscription;
use App\Models\Website;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use App\Jobs\SendEmailJob;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();

        return $this->sendResponse($posts);
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
            'website_id' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $website = Website::find($input['website_id']);
        if (is_null($website)) {
            return $this->sendError('website not found.');
        }

        $post = Post::create($input);

        $userSubscriptions = UserSubscription::where('website_id', '=', $input['website_id'])->get();
        foreach($userSubscriptions as $userSubscription){

            $user = User::where('id', '=', $userSubscription->user_id)->first();
            if (is_null($user)) continue;

            $send_mail = $user->email;
            $data = [
                'title' => $input['title'],
                'description' => $input['description']
            ];
  
            dispatch(new SendEmailJob($send_mail, $data));
        }

        return $this->sendResponse($userSubscriptions, 'post created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if (is_null($post)) {
            return $this->sendError('post not found.');
        }

        return $this->sendResponse( $post, 'post retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $input = $request->all();
        
        $inputValidation = [];
        if(isset($input['website_id'])) $inputValidation['website_id'] = 'required';
        if(isset($input['title'])) $inputValidation['title'] = 'required';
        if(isset($input['description'])) $inputValidation['description'] = 'required';

        $validator = Validator::make($input, $inputValidation);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        if(isset($input['website_id'])) $post->website_id = $input['website_id'];
        if(isset($input['title'])) $post->title = $input['title'];
        if(isset($input['description'])) $post->description = $input['description'];

        $post->save();

        return $this->sendResponse($post, 'post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if (is_null($post)) {
            return $this->sendError('post not found.');
        }
        
        $post->delete();

        return $this->sendResponse([], 'post deleted successfully.');
    }
}
