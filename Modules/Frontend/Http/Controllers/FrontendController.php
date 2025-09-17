<?php

namespace Modules\Frontend\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MobileSetting;
use Modules\Entertainment\Models\Entertainment;
use Modules\Banner\Models\Banner;

use Modules\Genres\Models\Genres;
use Modules\LiveTV\Models\LiveTvChannel;
use Modules\Entertainment\Models\EntertainmentView;


use App\Models\Device;
use App\Models\User;
use Modules\Banner\Transformers\SliderResource;
use Modules\Subscriptions\Models\Plan;
use Modules\Subscriptions\Models\Subscription;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Modules\Entertainment\Transformers\MoviesResource;
use Modules\Entertainment\Transformers\TvshowResource;
use Modules\Tax\Models\Tax;
use Modules\Constant\Models\Constant;
use Modules\FAQ\Models\FAQ;
use App\Services\RecommendationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Subscriptions\Trait\SubscriptionTrait;
use Illuminate\Support\Facades\Cache;

class FrontendController extends Controller
{
    use SubscriptionTrait;
    /**
     * Display a listing of the resource.
     */
    protected $recommendationService;
    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;

    }

    // public function index(Request $request)
    // {
    //     $user_id = auth()->id();
    //     $cacheKey = 'slider';
    //     Cache::flush();

    //     $sliders = Cache::get($cacheKey);
    //     if (!$sliders) {
    //        $sliderList = Banner::where('status', 1)->get();
    //        $sliders = SliderResource::collection($sliderList->map(function ($slider) use ($user_id) {
    //             return new SliderResource($slider, $user_id);
    //        }));

    //        $sliders = $sliders->toArray(request());
    //        Cache::put($cacheKey, $sliders);

    //     }
       

    //     return view('frontend::index', compact('user_id','sliders'));
    // }

   public function index(Request $request)
{
    $user_id = auth()->id();

    // ---- Slider Data ----
    $sliderCacheKey = 'sliders';
    $sliders = Cache::get($sliderCacheKey);

    if (!$sliders) {
        $sliderList = Banner::where('status', 1)->get();
        $sliders = SliderResource::collection(
            $sliderList->map(fn($slider) => new SliderResource($slider, $user_id))
        )->toArray(request());

        Cache::put($sliderCacheKey, $sliders, now()->addMinutes(30));
    }

    // ---- Mobile Setting Data ----
    $mobileCacheKey = 'mobile_settings';
    $mobileSettings = Cache::get($mobileCacheKey);

    if (!$mobileSettings) {
        $settings = MobileSetting::orderBy('position', 'asc')->get();

        $mobileSettings = [];
        foreach ($settings as $setting) {
            $slug = $setting->slug;
            $value = json_decode($setting->value, true);

            switch ($slug) {
                case 'your-favorite-personality':
                    if(!empty($value)) {
                        // raw ids
                        $mobileSettings[$slug . '_ids'] = $value;

                        // CastCrew data
                        $mobileSettings[$slug] = \Modules\CastCrew\Models\CastCrew::whereIn('id', $value)->get();
                    } else {
                        $mobileSettings[$slug . '_ids'] = [];
                        $mobileSettings[$slug] = collect();
                    }
                    break;

                case 'popular-movies':
                case 'latest-movies':
                case 'top-10':
                case 'popular-tvshows':
                case 'popular-videos':
                case '500-free-movies':
                case 'enjoy-in-your-native-tongue':
                    // Entertainment type fetch
                    $type = match($slug) {
                        'popular-movies', 'latest-movies', 'top-10', '500-free-movies' => 'movie',
                        'popular-tvshows' => 'tvshow',
                        'popular-videos' => 'video',
                        default => null
                    };

                    if($type) {
                        if(!empty($value)) {
                            $mobileSettings[$slug] = Entertainment::whereIn('id', $value)
                                ->where('type', $type)
                                ->where('status', 1)
                                ->whereDate('release_date', '<=', now())
                                ->get();
                        } else {
                            $mobileSettings[$slug] = collect();
                        }
                    }
                    break;

                case 'genre':
                    $mobileSettings[$slug] = Genres::where('status', 1)
                        ->whereIn('id', $value ?: [])
                        ->take(10)
                        ->get();
                    break;

                case 'top-channels':
                    $mobileSettings[$slug] = LiveTvChannel::where('status', 1)
                        ->whereIn('id', $value ?: [])
                        ->take(10)
                        ->get();
                    break;

                default:
                    $mobileSettings[$slug] = collect(); // fallback
            }
        }

        Cache::put($mobileCacheKey, $mobileSettings, now()->addMinutes(30));
    }

    return view('frontend::index', compact('user_id', 'sliders', 'mobileSettings'));
}


//     public function index(Request $request)
// {
//     $user_id = auth()->id();

//     // ---- Slider Data ----
//     $sliderCacheKey = 'sliders';
//     $sliders = Cache::get($sliderCacheKey);

//     if (!$sliders) {
//         $sliderList = Banner::where('status', 1)->get();
//         $sliders = SliderResource::collection(
//             $sliderList->map(fn($slider) => new SliderResource($slider, $user_id))
//         )->toArray(request());

//         Cache::put($sliderCacheKey, $sliders, now()->addMinutes(30));
//     }

//     // ---- Mobile Setting Data ----
//     $mobileCacheKey = 'mobile_settings';
//     $mobileSettings = Cache::get($mobileCacheKey);

//     if (!$mobileSettings) {
//         $settings = MobileSetting::orderBy('position', 'asc')->get();

//         $mobileSettings = [];
//         foreach ($settings as $setting) {
//             $slug = $setting->slug;

//             // getTypeValue() এর মতো করে slug অনুযায়ী ডেটা আনছি
//             switch ($slug) {
//                 case 'popular-movies':
//                     $mobileSettings[$slug] = Entertainment::where('type', 'movie')
//                         ->where('IMDb_rating', '>', 5)
//                         ->whereDate('release_date', '<=', now())
//                         ->where('status', 1)
//                         ->orderBy('IMDb_rating', 'desc')
//                         ->take(10)
//                         ->get();
//                     break;

//                 case 'latest-movies':
//                     $mobileSettings[$slug] = Entertainment::where('type', 'movie')
//                         ->whereDate('release_date', '<=', now())
//                         ->where('status', 1)
//                         ->orderBy('release_date', 'desc')
//                         ->take(10)
//                         ->get();
//                     break;

//                 case 'top-10':
//                     $topEntertainmentIds = EntertainmentView::groupBy('entertainment_id')
//                         ->select('entertainment_id', DB::raw('count(*) as total'))
//                         ->orderBy('total', 'desc')
//                         ->take(10)
//                         ->pluck('entertainment_id');
//                     $mobileSettings[$slug] = Entertainment::whereIn('id', $topEntertainmentIds)
//                         ->whereDate('release_date', '<=', now())
//                         ->where('status', 1)
//                         ->get();
//                     break;
//                 case 'popular-tvshows':
//                     $ids = json_decode($setting->value, true);
//                     $mobileSettings[$slug] = Entertainment::whereIn('id', $ids ?: [])
//                         ->where('type', 'tvshows')
//                         ->whereDate('release_date', '<=', now())
//                         ->where('status', 1)
//                         ->get();
//                     break;


//                 case 'genre':
//                     $mobileSettings[$slug] = Genres::where('status', 1)->take(10)->get();
//                     break;

//                 case 'top-channels':
//                     $mobileSettings[$slug] = LiveTvChannel::where('status', 1)->take(10)->get();
//                     break;

//                 // অন্য slug থাকলে এখানেও add করতে পারবেন
//                 default:
//                     $mobileSettings[$slug] = collect(); // fallback empty collection
//             }
//         }

//         Cache::put($mobileCacheKey, $mobileSettings, now()->addMinutes(30));
 
//     }

//     return view('frontend::index', compact('user_id', 'sliders', 'mobileSettings'));
// }




    public function searchList()
    {
        $entertainment_list = Entertainment::query()
        ->with([
            'entertainmentGenerMappings',
            'plan',
            'entertainmentTalentMappings',
            'entertainmentStreamContentMappings',
            'entertainmentReviews' => function ($query) {
                $query->whereBetween('rating', [4, 5]);
            }
        ])
        ->where('status', 1);

    // Fetch movies
    $movieList = $entertainment_list->where('type', 'movie')->take(10)->get();
    $movieData = (isenablemodule('movie') == 1) ? MoviesResource::collection($movieList) : [];

    $entertainment_data = Entertainment::query()
    ->with([
        'entertainmentGenerMappings',
        'plan',
        'entertainmentTalentMappings',
        'entertainmentStreamContentMappings',
        'entertainmentReviews' => function ($query) {
            $query->whereBetween('rating', [4, 5]);
        }
    ])
    ->where('status', 1);


    // Fetch TV shows
    $tvshowList = $entertainment_data->where('type', 'tvshow')->take(10)->get();
    $tvshowData = (isenablemodule('tvshow') == 1) ? TvshowResource::collection($tvshowList) : [];


        return view('frontend::search', compact('movieData', 'tvshowData'));
    }

    public function tvshowList()
    {

        return view('frontend::movie');
    }

    public function continueWatchList()
    {
        return view('frontend::continueWatch');
    }

    public function languageList()
    {
        $languageIds = MobileSetting::getValueBySlug('enjoy-in-your-native-tongue');
        $popular_language = Constant::whereIn('id',json_decode($languageIds))->get();

        return view('frontend::language',compact('popular_language'));
    }
    public function languageData(Request $request){
        $perPage = $request->input('per_page', 10);
        $languageIds = MobileSetting::getValueBySlug('enjoy-in-your-native-tongue');
        $popular_language = Constant::whereIn('id',json_decode($languageIds));

        $html = '';
        $popular_language = $popular_language->paginate($perPage);
            foreach($popular_language as $language) {
                $html .= view('frontend::components.card.card_language',['popular_language' => $language])->render();
            }
            $hasMore = $popular_language->hasMorePages();
            return response()->json([
                'status' => true,
                'html' => $html,
                'message' => __('movie.tvshow_list'),
                'hasMore' => $hasMore,
            ], 200);
    }
    public function topChannelList()
    {
        return view('frontend::topChannel');
    }
    public function genresList()
    {
        return view('frontend::genres');
    }

    public function comingsoon()
    {
        return view('frontend::comingsoon');
    }
    public function livetv()
    {
        return view('frontend::livetv');
    }
    public function subscriptionPlan()
    {
        $plans = Plan::with('planLimitation')->where('status',1)->get();
        $activeSubscriptions = Subscription::where('user_id', auth()->id())->where('status', 'active')->where('end_date', '>', now())->orderBy('id','desc')->first();
        $currentPlanId = $activeSubscriptions ? $activeSubscriptions->plan_id : null;
        $subscriptions = Subscription::where('user_id', auth()->id())
        ->with('subscription_transaction')
        ->where('end_date', '<', now())
        ->get();

        return view('frontend::subscriptionplan', compact('plans','currentPlanId','activeSubscriptions'));
    }
    public function watchList()
    {
        return view('frontend::watchlist');
    }

    public function accountSetting()
    {
        $user = auth()->user();

         $subscriptions = Subscription::where('user_id', auth()->id())
            ->where('status', 'active')
            ->orderBy('id','desc')
            ->first();

        $devices = $user->devices;

        $your_device = null;
        $other_devices = [];

        $currentDeviceIp = request()->getClientIp();

        foreach ($devices as $device) {
            if ($device->device_id == $currentDeviceIp) {
                $your_device = $device;
            } else {
                $other_devices[] = $device;
            }
        }

        return view('frontend::accountSetting', compact('subscriptions', 'user', 'your_device', 'other_devices'));
    }


    public function deviceLogout(Request $request)
    {
        $userId = auth()->user()->id;

        $deviceQuery = Device::where('user_id', $userId);

        if ($request->has('device_id')) {
            $deviceQuery->where('device_id', $request->device_id);
        }

        if ($request->has('id')) {
            $deviceQuery->orWhere('id', $request->id);
        }

        $device = $deviceQuery->first();
        if (!$device) {
            return redirect()->back()->with('error', __('users.device_not_found'));
        }

        $device->delete();

        $sessionQuery = DB::table('sessions')->where('user_id', $userId);

        if ($request->has('device_id')) {
            $sessionQuery->where('ip_address', $request->device_id);
        }

        if ($request->has('id')) {
            $sessionQuery->orWhere('id', $request->id);
        }

        $session = $sessionQuery->first();
        if ($session) {
            $sessionQuery->delete();
        }

        return redirect()->back()->with('success', __('users.device_logout'));
    }


    public function faq()
    {
        $content = FAQ::where('status',1)->get();
        return view('frontend::faq',compact('content'));
    }


    public function PaymentHistory()
    {
        $subscriptions = Subscription::where('user_id', auth()->id())
        ->with('subscription_transaction')
        ->get();

        $activeSubscriptions =  Subscription::where('user_id', auth()->id())
        ->where('status', 'active')
        ->where('end_date', '>', now())
        ->orderBy('id', 'desc')
        ->first();


        return view('frontend::paymentHistory', compact('activeSubscriptions', 'subscriptions'));

    }


    public function allReview($id)
    {
        $entertainment = Entertainment::findOrFail($id);
        $reviews = $entertainment->entertainmentReviews;
        $ratingCounts = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0,
        ];
        foreach ($reviews as $review) {
            if (isset($ratingCounts[$review->rating])) {
                $ratingCounts[$review->rating]++;
            }
        }
        $totalRating = $reviews->sum('rating');
        $reviewCount = $reviews->count();
        $averageRating = $reviewCount > 0 ? $totalRating / $reviewCount : 0;

        return view('frontend::review', compact('entertainment', 'reviews', 'averageRating', 'ratingCounts', 'reviewCount'));
    }

    public function EpisodeDetails()
    {
        return view('frontend::episode_detail');
    }

    public function VideoDetails()
    {
     return view('frontend::video_detail');
    }

    public function profile()
    {
        return view('frontend::components.user.profile');
    }

    public function cancelSubscription(Request $request)
    {
        try {
            $planId = $request->input('plan_id');
            Subscription::where('user_id', auth()->id())
                ->where('id', $request->id)
                ->where('status', 'active')
                ->update(['status' => 'cancel']);

            $otherSubscription=Subscription::where('user_id', auth()->id())
                ->where('status', 'active')->get();

            if($otherSubscription->isEmpty()){

                $user=User::where('id',auth()->id() )->first();

                $user->update(['is_subscribe'=>0]);

            }



            return redirect('/');
        } catch (\Exception $e) {
            return redirect('/');
        }
    }

    public function decryptUrl(Request $request)
  {
      $encryptedUrl = $request->input('encrypted_url');

      try {
          $decryptedUrl = Crypt::decryptString($encryptedUrl);
          return response()->json(['url' => $decryptedUrl], 200);
      } catch (\Exception $e) {
          return response()->json(['error' => 'Invalid URL'], 400);
      }
  }
    public function getPaymentDetails(Request $request)
    {
        $planId = $request->input('plan_id');
        $plan = Plan::find($planId);
        $discount_percentage = $plan->discount_percentage;

        $discount_amount= ($discount_percentage*$plan->price)/100;

        $taxes=Tax::where('status',1)->get();
        $baseAmount = $plan->total_price;
        $totalTaxamount = 0;
        $totalTax=0;
        $taxesArray = [];
        foreach ($taxes as $tax) {

            if (strtolower($tax->type) == 'fixed') {

                $totalTax = $tax->value;

            } elseif (strtolower($tax->type) == 'percentage') {

                $totalTax = ($baseAmount * $tax->value) / 100;

            }

            $taxesArray[] = [
                'name' => $tax->title, // Assuming there's a 'name' field for the tax
                'type' => $tax->type,
                'value' => $tax->value,
                'tax_amount' => $totalTax
            ];

            $totalTaxamount +=  $totalTax;
        }

        $totalAmount = $baseAmount + $totalTaxamount;
        $total = $totalAmount ;

        return response()->json([
            'price'=>$plan->price,
            'subtotal' => $baseAmount,
            'discount' => $discount_percentage,
            'discount_amount'=>$discount_amount,
            'tax' => $totalTaxamount ,
            'total' => $total,
            'tax_array'=> $taxesArray
        ]);
    }

    public function checkSubscription($planId)
   {
    $user = auth()->user();
    $currentSubscription = Subscription::where('user_id', $user->id)->where('status', 'active')->get();

    $planData=Plan::Where('id',$planId)->first();

    $level=$planData->level;

    foreach($currentSubscription as $plan)
    {

        if ($plan->level >= $level) {
            return response()->json(['isActive' => true]);
        }
    }
    return response()->json(['isActive' => false]);
   }


   public function checkDeviceType() {
        $checkDeviceType = Subscription::checkPlanSupportDevice(auth()->id());
        return $checkDeviceType;
    }


    public function downloadInvoice(Request $request)
    {
        // Retrieve the booking by ID with related services, user, and products
        $subscription = Subscription::with('plan','subscription_transaction','user')->find($request->id);
            if (!$subscription) {
                return response()->json(['status' => false, 'message' => 'subscription not found'], 404);
            }


        // Render the view for the invoice
        $view = view('frontend::components.partials.invoice',['data' => $subscription])->render();

        // Generate the PDF from the rendered view
        $pdf = Pdf::loadHTML($view);

        // Return the generated PDF as a download
        return $pdf->download('invoice.pdf');
    }



}


