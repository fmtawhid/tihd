<?php

namespace Modules\Subscriptions\Models;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Subscriptions\Models\SubscriptionTransactions;
use Jenssegers\Agent\Agent;


class Subscription extends BaseModel
{
    use HasFactory;

    protected $fillable = ['plan_id',
        'user_id',
        'device_id',
        'start_date',
        'end_date',
        'status',
        'amount',
        'discount_percentage',
        'tax_amount',
        'total_amount',
        'name',
        'identifier',
        'type',
        'duration',
        'level',
        'plan_type',
        'payment_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function subscription_transaction()
    {
        return $this->hasOne(SubscriptionTransactions::class, 'subscriptions_id', 'id');
    }

    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }

    protected static function newFactory()
    {
        return \Modules\Subscriptions\Database\factories\SubscriptionFactory::new();
    }
    public static function checkPlanSupportDevice($user_id){
        $user = User::where('id',$user_id)->first();
        $currentSubscription = Subscription::where('user_id', $user_id)
        ->where('status', 'active')
        ->orderBY('id','desc')
        ->first();
        $agent = new Agent();

        // Determine device type
        if ($agent->isMobile()) {
            $deviceType = 'mobile';
        } elseif ($agent->isTablet()) {
            $deviceType = 'tablet';
        } elseif ($agent->isDesktop()) {
            $deviceType = 'desktop';
        } else {
            $deviceType = 'unknown'; // For any unsupported device types
        }

        // If there's no active subscription, only allow mobile
        if (!$currentSubscription) {

            return response()->json(['isDeviceSupported' => $deviceType === 'mobile', 'device_name' => $deviceType]);
        }

        if ($user && $user->subscriptionPackage && $user->subscriptionPackage->plan) {
            $planLimitation = $user->subscriptionPackage->plan->planLimitation;
        } else {
            $planLimitation = [];
        }

        if(!empty($planLimitation )){
            $deviceLimits = $planLimitation->where('limitation_slug', 'supported-device-type')->first();

            // Decode the device limits from JSON
            $deviceLimitsArray = $deviceLimits ? json_decode($deviceLimits->limit, true) : [];
          // Check if the current device type is supported
            if (isset($deviceLimitsArray[$deviceType]) && $deviceLimitsArray[$deviceType] == 1) {
                return response()->json(['isDeviceSupported' => true, 'device_name' => $deviceType]);
            }
            // Check if the device is desktop and if laptop is allowed
            if ($deviceType === 'desktop' && isset($deviceLimitsArray['laptop']) && $deviceLimitsArray['laptop'] == 1) {
                return response()->json(['isDeviceSupported' => true, 'device_name' => 'laptop']);
            }

        }
        return response()->json(['isDeviceSupported' => false, 'device_name' => $deviceType]);
    }
}
