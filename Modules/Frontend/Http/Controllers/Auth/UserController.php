<?php

namespace Modules\Frontend\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\UserMultiProfile;
use Modules\User\Transformers\UserMultiProfileResource;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function editProfile()
    {
         $user =Auth::user();

         $Profile=UserMultiProfile::where('user_id', $user->id)->get();

         $userProfile = UserMultiProfileResource::collection($Profile);

        return view('frontend::editProfile',compact('user','userProfile'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('frontend::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('frontend::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('frontend::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
