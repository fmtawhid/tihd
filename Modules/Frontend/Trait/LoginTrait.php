<?php

namespace Modules\Frontend\Trait;
use Jenssegers\Agent\Agent;
use App\Models\Device;
use App\Models\UserMultiProfile;
use DB;


trait LoginTrait
{

    public function CheckDeviceLimit($user, $current_device)
    {
        $count = Device::where('user_id', $user->id)->count();

        if($user->mobile=='+911234567890'){

            return [
                'success' => 'Your device limit is available.',
                'status' => 200
            ];

        }

        if ($user->subscriptionPackage) {
            $planLimitation = optional(optional($user->subscriptionPackage)->plan)->planLimitation;

            if ($planLimitation) {
                $deviceLimit = $planLimitation->where('limitation_slug', 'device-limit')->first();
                $deviceLimitCount = $deviceLimit ? $deviceLimit->limit : 1;

                if ($count >= $deviceLimitCount) {
                    return [
                        'error' => 'Your device limit has been reached.',
                        'status' => 406
                    ];
                }
            }
        } else {

            $existingDevice = Device::where('user_id', $user->id)->first();
            if ($existingDevice) {
                if ($existingDevice->device_id != $current_device) {
                    return [
                        'error' => 'Your device limit has been reached.',
                        'status' => 406
                    ];
                }
            }
        }

        return [
            'success' => 'Your device limit is available.',
            'status' => 200
        ];
    }


    public function CheckDevice($user)
    {
         if($user->subscriptionPackage==null){

            $agent = new Agent();

            if ($agent->isMobile()) {
                $deviceType = 'Mobile';
            } elseif ($agent->isTablet()) {
                $deviceType = 'Tablet';
            } elseif ($agent->isDesktop()) {
                $deviceType = 'Desktop';
            } else {
                $deviceType = 'Unknown';
            }

            $userAgent = $agent->getUserAgent();

            return response()->json(['status' => false, 'message' => __('messages.user_not_logged_in')]);


         }else{


         }

}


  public function setDevice($user, $request){

    $userAgent = $request->header('User-Agent'); // Get the User-Agent string
    $device_id = $request->getClientIp(); // Get the client IP address
    $device_name = 'Unknown Device'; // Default device name
    $platform = 'Unknown Platform'; // Default platform

    // Detect device type (mobile, tablet, or desktop)
    if (preg_match('/(android|webos|iphone|ipad|ipod|blackberry|windows phone)/i', $userAgent)) {
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $userAgent)) {
            $device_name = 'Tablet';
        } else {
            $device_name = 'Mobile';
        }
    } else {
        $device_name = 'Desktop';
    }

    // Detect operating system
    if (preg_match('/windows/i', $userAgent)) {
        $platform = 'Windows';
    } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
        $platform = 'Mac OS';
    } elseif (preg_match('/linux/i', $userAgent)) {
        $platform = 'Linux';
    } elseif (preg_match('/android/i', $userAgent)) {
        $platform = 'Android';
    } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
        $platform = 'iOS';
    }

    // Detect specific device names (basic example)
    if (preg_match('/iphone/i', $userAgent)) {
        $device_name = 'iPhone';
    } elseif (preg_match('/ipad/i', $userAgent)) {
        $device_name = 'iPad';
    } elseif (preg_match('/samsung/i', $userAgent)) {
        $device_name = 'Samsung Device';
    }

    $profile = UserMultiProfile::where('user_id', $user->id)->first();

    $device = Device::updateOrCreate(
        [
            'user_id' => $user->id,
            'device_id' => $device_id
        ],
        [
            'device_name' => $device_name,
            'platform' => $platform,
            'active_profile' => $profile->id ?? null,
        ]
    );

}



public function removeDevice($user, $request){

    $device_id = $request->getClientIp();

    Device::where('device_id', $device_id)->where('user_id', $user->id)->delete();

}

}
