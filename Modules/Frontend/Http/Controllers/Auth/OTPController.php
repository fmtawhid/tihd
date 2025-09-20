<?php


namespace Modules\Frontend\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Auth;
use Str;
use App\Models\Device;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeviceEmail;
use App\Models\Setting;
use Jenssegers\Agent\Agent;
use App\Models\UserMultiProfile;
use Modules\Frontend\Trait\LoginTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OTPController extends Controller
{
    use LoginTrait;

    public function otpLogin()
    {
        $userId = auth()->id(); 
        
        $settings = Setting::getAllSettings($userId);
        $isOtpLoginEnabled = Setting::where('name', 'is_otp_login')->value('val') == 1;
        
        return view('frontend::auth.otp_login', compact('settings', 'isOtpLoginEnabled'));
    }


    public function otpLoginStore(Request $request)
    {
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' =>  $request->email,
            'mobile' =>  $request->mobile,
            'password' => Hash::make(Str::random(8)),
            'user_type' => 'user',
            'login_type' => 'otp'
        ];

        $user=User::where('email', $request->email)->first();

        $user = User::create($data);

        $request->session()->regenerate();

        $user->createOrUpdateProfileWithAvatar();

        $user->assignRole($data['user_type']);

        $user->save();

        if($user->login_type == 'otp' )
        {
            Auth::login($user);
            $this->setDevice($user, $request);
        }
        else
        {
            $user=Auth::user();
            Auth::logout();
            $this->removeDevice($user, $request);
           return Redirect::to('/login')->with('error', 'Something went wrong! During login');
        }

        return redirect('/'); // Redirect to intended page
    }

    public function checkUserExists(Request $request)
    {
        $data = $request->all();

        $current_device=$request->has('device_id')?$request->device_id:$request->getClientIp();

        $flag = 0;
        $user = User::where('mobile', $request->mobile)->where('login_type','otp')->with('subscriptionPackage')->first();

        if(!empty($user))
        {

            if($user->user_type !='user'){

                return response()->json(['message'=>"Admin doesn't have access to login", 'status' => 406]);
            }

            $response=$this->CheckDeviceLimit($user, $current_device);

            if(isset($response['error'])) {

                return response()->json(['message'=>$response['error'], 'status' => 406]);
            }

            $this->setDevice($user, $request);

            Auth::login($user);
            $flag = 1;
        }

        return response()->json(['is_user_exists' => $flag, 'url' => route('user.login')]);
    }



// public function sendOtp(Request $request)
// {
//     $request->validate([
//         'mobile' => 'required|string|min:10|max:15'
//     ]);

//     $mobile = $request->mobile;
//     $otp = rand(100000, 999999); // 6-digit OTP

//     // Store OTP in cache for 5 minutes
//     Cache::put('otp_' . $mobile, $otp, now()->addMinutes(5));

//     // Get API credentials from .env
//     $smsApiKey = env('SMS_API_KEY');
//     $smsApiUrl = env('SMS_API_URL');

//     // Send SMS
//     $response = Http::get($smsApiUrl, [
//         'api_key' => $smsApiKey,
//         'msg' => "Your OTP is $otp",
//         'to' => $mobile
//     ]);

//     return response()->json(['success' => true, 'message' => 'OTP sent']);
// }
public function sendOtp(Request $request)
{
    $request->validate([
        'mobile' => 'required|string|min:10|max:15'
    ]);

    $mobile = $request->mobile;
    $otp = rand(100000, 999999); // 6-digit OTP

    // Store OTP in cache
    Cache::put('otp_' . $mobile, $otp, now()->addMinutes(5));

    // Store mobile in session
    $request->session()->put('otp_mobile', $mobile);

    // Send SMS via API
    $smsApiKey = env('SMS_API_KEY');
    $smsApiUrl = env('SMS_API_URL');

    Http::get($smsApiUrl, [
        'api_key' => $smsApiKey,
        'msg' => "Your OTP is $otp",
        'to' => $mobile
    ]);

    return response()->json(['success' => true, 'message' => 'OTP sent']);
}

    // public function verifyOtp(Request $request)
    // {
    //     $request->validate([
    //         'mobile' => 'required|digits_between:10,15',
    //         'otp' => 'required|digits:6'
    //     ]);

    //     $cachedOtp = Cache::get('otp_' . $request->mobile);

    //     if (!$cachedOtp || $cachedOtp != $request->otp) {
    //         return response()->json(['success' => false, 'message' => 'Invalid or expired OTP'], 400);
    //     }

    //     // OTP মিলছে, ইউজার আছে কিনা চেক করো
    //     $user = User::where('mobile', $request->mobile)->first();
    //     if (!$user) {
    //         return response()->json(['success' => true, 'action' => 'register']);
    //     }

    //     Auth::login($user);
    //     return response()->json(['success' => true, 'action' => 'login', 'url' => route('home')]);
    // }
    public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|digits:6',
    ]);

    // Get mobile from session
    $mobile = $request->session()->get('otp_mobile');
    if (!$mobile) {
        return response()->json(['success' => false, 'message' => 'Session expired'], 400);
    }

    $cachedOtp = Cache::get('otp_' . $mobile);

    if (!$cachedOtp || $cachedOtp != $request->otp) {
        return response()->json(['success' => false, 'message' => 'Invalid or expired OTP'], 400);
    }

    // Check if user exists
    $user = User::where('mobile', $mobile)->first();
    if (!$user) {
        return response()->json(['success' => true, 'action' => 'register']);
    }

    Auth::login($user);
    return response()->json(['success' => true, 'action' => 'login', 'url' => route('home')]);
}



}
