<?php

namespace Modules\User\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\User\Transformers\UserProfileResource;
use App\Models\User;
use App\Models\Device;
use Modules\Subscriptions\Models\Subscription;
use Modules\Entertainment\Models\ContinueWatch;
use Modules\Entertainment\Models\Watchlist;
use Modules\Entertainment\Models\EntertainmentDownload;
use Modules\Entertainment\Models\UserReminder;
use Modules\User\Transformers\AccountSettingResource;
use App\Models\UserMultiProfile;
use App\Models\Role;
use Modules\Page\Models\Page;
use App\Models\UserWatchHistory;

class UserController extends Controller
{
    public function profileDetails(Request $request){
        $userId = $request->user_id ? $request->user_id : auth()->user()->id;

        $user = User::with('subscriptionPackage', 'watchList', 'continueWatch')->where('id', $userId)->first();

        if($user->is_subscribe == 1){
            $user['plan_details'] = $user->subscriptionPackage;
        }

        $responseData = new UserProfileResource($user);

        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('users.user_details'),
        ], 200);
    }


    public function accountSetting(Request $request)
    {
        $userId = auth()->user()->id;
        $user = User::with('subscriptionPackage')->where('id', $userId)->first();
        $subscribe_device_id = $user->subscriptionPackage->device_id ?? null;
        $devices = Device::where('user_id', $userId)->get();

        $your_device = null;
        $other_device = [];

        if($subscribe_device_id){

            foreach ($devices as $device) {
                if ($device->device_id == $subscribe_device_id) {
                    $your_device = $device;
                } else {
                    $other_device[] = $device;
                }
            }

        }else{

            $device = Device::where('user_id', $userId)->first();
            $your_device = $device;
        }

        $user['your_device']= $your_device;
        $user['other_device']= $other_device;

        $user['page'] =  Page::where('status',1)->get();


        if ($user->is_subscribe == 1) {
            $user->plan_details = $user->subscriptionPackage;
        }

        $responseData = new AccountSettingResource($user);

        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('users.account_setting'),
        ], 200);
    }
    public function deviceLogout(Request $request){

        $userId = auth()->check() ? auth()->user()->id : $request->input('user_id');

        $deviceQuery = Device::where('user_id', $userId);

        if ($request->has('device_id')) {
            $deviceQuery->where('device_id', $request->device_id);
        }

        if ($request->has('id')) {
            $deviceQuery->orWhere('id', $request->id);
        }

        $device = $deviceQuery->first();

        if (!$device) {
            return response()->json([
                'status' => false,
                'message' => __('users.device_not_found'), // Change the message to suit your needs
            ], 404);
        }

    
        $device->delete();


        return response()->json([
            'status' => true,
            'message' => __('users.device_logout'),
        ], 200);
    }

    public function deleteAccount(Request $request){
        $userId = auth()->user()->id;

        User::where('id', $userId)->forceDelete();
        Device::where('user_id', $userId)->delete();
        Subscription::where('user_id', $userId)->update(['status' => 'deactivated']);
        ContinueWatch::where('user_id', $userId)->delete();
        Watchlist::where('user_id', $userId)->delete();
        EntertainmentDownload::where('user_id', $userId)->delete();
        UserReminder::where('user_id', $userId)->delete();
        UserMultiProfile::where('user_id', $userId)->forceDelete();

        return response()->json([
            'status' => true,
            'message' => __('users.delete_account'),
        ], 200);
    }

    public function logoutAll(Request $request){

        $userId = auth()->check() ? auth()->user()->id : $request->input('user_id');

        $device = Device::where('user_id', $userId)->where('device_id','!=', $request->device_id)->delete();

        return response()->json([
            'status' => true,
            'message' => __('users.device_logout'),
        ], 200);
    }

    public function saveWatchHistory(Request $request)
    {
        $user = auth()->user();
        $data = $request->all();

        $profile_id=$request->has('profile_id') && $request->profile_id
        ? $request->profile_id
        : getCurrentProfile($user->id, $request);

        $data['profile_id']=$profile_id;


        $search_data  = [
            'user_id' => $user->id,
            'entertainment_id' =>$data['entertainment_id'],
            'profile_id' => $data['profile_id'],
            'entertainment_type' => $data['entertainment_type']
        ];
        UserWatchHistory::create($search_data);

        ContinueWatch::where('user_id',$user->id)->where('profile_id',$profile_id)->where('entertainment_id',$data['entertainment_id'])->where('entertainment_type', $data['entertainment_type'])->forceDelete();


        return response()->json(['status' => true, 'message' => __('movie.history_save')]);
    }
}
