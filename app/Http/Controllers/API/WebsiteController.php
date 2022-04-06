<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Website;
use Illuminate\Http\Request;
use Validator;

class WebsiteController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $websites = Website::all();

        return $this->sendResponse($websites);
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
            'name' => 'required|unique:websites',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $website = Website::create($input);

        return $this->sendResponse($input, 'website created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function show(Website $website)
    {
        if (is_null($website)) {
            return $this->sendError('website not found.');
        }

        return $this->sendResponse( $website, 'website retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Website $website)
    {
        $input = $request->all();
        
        $inputValidation = [];
        if(isset($input['name'])) $inputValidation['name'] = 'required';

        $validator = Validator::make($input, $inputValidation);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        if(isset($input['name'])) $website->name = $input['name'];

        $website->save();

        return $this->sendResponse($website, 'website updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function destroy(Website $website)
    {
        if (is_null($website)) {
            return $this->sendError('website not found.');
        }
        
        $website->delete();

        return $this->sendResponse([], 'website deleted successfully.');
    }
}
