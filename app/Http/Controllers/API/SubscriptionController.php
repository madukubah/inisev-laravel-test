<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\UserSubscription;
use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;
use Validator;

class SubscriptionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userSubscriptions = UserSubscription::all();

        return $this->sendResponse($userSubscriptions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        $input = $request->all();


        $validator = Validator::make($input, [
            'user_id' => 'required',
            'website_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $user = User::find($input['user_id']);
        if (is_null($user)) {
            return $this->sendError('user not found.');
        }

        $website = Website::find($input['website_id']);
        if (is_null($website)) {
            return $this->sendError('website not found.');
        }

        $userSubscription = UserSubscription::create($input);

        return $this->sendResponse($userSubscription, 'Subscription created successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserSubscription  $userSubscription
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe($id)
    {
        UserSubscription::find($id)->delete();
        return $this->sendResponse([], 'Subscription deleted successfully.');
    }
}
