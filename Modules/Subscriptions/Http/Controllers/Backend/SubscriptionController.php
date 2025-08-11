<?php

namespace Modules\Subscriptions\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
// use Illuminate\Routing\Controller;
use Modules\Subscriptions\Models\Subscription;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Currency;
use Illuminate\Http\Request;
use PDF; // উপরে use statement যোগ করুন


class SubscriptionController extends Controller
{
    protected string $exportClass = '\App\Exports\SubscriptionExport';
    public function __construct()
    {
        // Page Title
        $this->module_title = 'Subscriptions';

        // module name
        $this->module_name = 'subscriptions';

        // module icon
        $this->module_icon = 'fa-solid fa-clipboard-list';

        view()->share([
            'module_title' => $this->module_title, 
            'module_icon' => $this->module_icon,
            'module_name' => $this->module_name,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        $module_action = 'User List';
        $export_import = true;
        $export_columns = [

            [
                'value' => 'user_details',
                'text' => __('messages.user'),
            ],
            [
                'value' => 'name',
                'text' => __('messages.name'),
            ],
            [
                'value' => 'start_date',
                'text' => __('messages.start_date'),
            ],
            [
                'value' => 'end_date',
                'text' => __('messages.end_date'),
            ],
            [
                'value' => 'amount',
                'text' => __('dashboard.amount'),
            ],
            [
                'value' => 'tax_amount',
                'text' => __('tax.title') . ' ' . __('dashboard.amount'),
            ],
            [
                'value' => 'total_amount',
                'text' => __('messages.total_amount'),
            ],
            [
                'value' => 'status',
                'text' => __('plan.lbl_status'),
            ],
        ];
        $export_url = route('backend.subscriptions.export');

        return view('subscriptions::backend.subscriptions.index', compact('module_action','export_import', 'export_columns', 'export_url'));
    }

    public function index_data(Datatables $datatable,Request $request)
    {
        $query = Subscription::query()
            ->with('user');

        $datatable = $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" data-type="subscriptions" onclick="dataTableRowCheck('.$row->id.', this)">';
            })

            ->editColumn('user_id', function ($data) {
             return view('components.user-detail-card', ['image' => setBaseUrlWithFileName(optional($data->user)->file_url) ?? default_user_avatar() , 'name' => optional($data->user)->full_name ?? default_user_name(),'email' => optional($data->user)->email ?? '-'])->render();
                // return view('subscriptions::backend.subscriptions.user_details', compact('data'));
            })
            ->editColumn('start_date', function ($data) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $data->start_date);
                return formatDate($start_date->format('Y-m-d'));
            })
            ->editColumn('end_date', function ($data) {
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $data->end_date);
                return formatDate($end_date->format('Y-m-d'));
            })
            ->editColumn('amount', function ($data) {
                return Currency::format($data->amount);
            })
            ->editColumn('tax_amount', function ($data) {
                return Currency::format($data->tax_amount);
            })
            ->editColumn('total_amount', function ($data) {
                return Currency::format($data->total_amount);
            })
            ->editColumn('name', function ($data) {
                return $data->name;
            })
            ->filterColumn('status', function($query, $keyword) {
                if ($keyword == 'inactive') {
                    $query->where('status', 'inactive');
                } else if ($keyword == 'active') {
                    $query->where('status', 'active');
                }
            })
            ->filterColumn('user_id', function($query, $keyword) {
                if (!empty($keyword)) {
                    $query->whereHas('user', function($q) use ($keyword) {

                        $q->where('first_name', 'like', '%' . $keyword . '%')->orWhere('last_name', 'like', '%' . $keyword . '%')->orWhere('email', 'like', '%' . $keyword . '%');

                    });
                }
            })
            ->filterColumn('start_date', function($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(start_date, '%D %M %Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('end_date', function($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(end_date, '%D %M %Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('amount', function($query, $keyword) {
                // Remove any non-numeric characters except for the decimal point
                $cleanedKeyword = preg_replace('/[^0-9.]/', '', $keyword);

                // Check if the cleaned keyword is not empty
                if ($cleanedKeyword !== '') {
                    // Filter the query by removing non-numeric characters from the amount column
                    $query->whereRaw("CAST(REGEXP_REPLACE(amount, '[^0-9.]', '') AS DECIMAL(10, 2)) LIKE ?", ["%{$cleanedKeyword}%"]);
                }
            })
            ->filterColumn('total_amount', function($query, $keyword) {

                $cleanedKeyword = preg_replace('/[^0-9.]/', '', $keyword);

                if ($cleanedKeyword !== '') {
                    $query->whereRaw("CAST(REGEXP_REPLACE(total_amount, '[^0-9.]', '') AS DECIMAL(10, 2)) LIKE ?", ["%{$cleanedKeyword}%"]);
                }
            })
            ->addColumn('action', function ($row) {
                $invoiceUrl = route('backend.subscriptions.invoice', $row->id);
                return '<a href="'.$invoiceUrl.'" class="btn btn-sm btn-primary" download>
                            <i class="fa fa-file-pdf"></i> Download
                        </a>';
            })




            ->orderColumns(['id'], '-:column $1');

        return $datatable->rawColumns(array_merge(['check','user_id', 'start_date', 'end_date', 'amount', 'name', 'action']))
            ->toJson();
    }
    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'subscription';
        $messageKey = __('subscription.Post_status');


        return $this->performBulkAction(subscription::class, $ids, $actionType, $messageKey, $moduleName);
    }

    public function exportPdf(Request $request)
    {
        $ids = $request->input('ids', []);
        $columns = $request->input('columns', []);

        $subscriptions = Subscription::with('user')
            ->whereIn('id', $ids)
            ->get();

        $data = $subscriptions->map(function ($row) use ($columns) {
            $selectedData = [];
            foreach ($columns as $column) {
                switch ($column) {
                    case 'user_details':
                        $user = $row->user;
                        $selectedData[$column] = $user
                            ? 'Name: ' . ($user->full_name ?? '-') . ', Email: ' . ($user->email ?? '-')
                            : 'Name: -, Email: -';
                        break;
                    default:
                        $selectedData[$column] = $row[$column];
                        break;
                }
            }
            return $selectedData;
        });

        $pdf = PDF::loadView('subscriptions::backend.subscriptions.pdf', [
            'data' => $data,
            'columns' => $columns,
        ]);

        return $pdf->download('subscriptions.pdf');
    }

    public function manualSubscription(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'plan_id' => 'required|exists:plan,id',
                'amount' => 'required|numeric',
                'payment_status' => 'required|in:paid,pending',
                'payment_type' => 'required|string',
                'transaction_id' => 'nullable|string',
            ]);

            $user = \App\Models\User::find($request->user_id);
            $plan = \Modules\Subscriptions\Models\Plan::find($request->plan_id);

            $start_date = now();
            $end_date = match ($plan->duration) {
                'days', 'day' => now()->addDays($plan->duration_value),
                'months', 'month' => now()->addMonths($plan->duration_value),
                'years', 'year' => now()->addYears($plan->duration_value),
                default => now(), 
            };

            $subscription = Subscription::create([
                'plan_id' => $plan->id,
                'user_id' => $user->id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status' => $request->payment_status == 'paid' ? 'active' : 'pending',
                'amount' => $request->amount,
                'tax_amount' => 0,
                'total_amount' => $request->amount,
                'name' => $plan->name,
                'identifier' => $plan->identifier,
                'type' => $plan->duration,
                'duration' => $plan->duration_value,
                'level' => $plan->level,
                'plan_type' => $plan->plan_type,
            ]);

            \Modules\Subscriptions\Models\SubscriptionTransactions::create([
                'subscriptions_id' => $subscription->id,
                'user_id' => $user->id,
                'amount' => $request->amount,
                'tax_data' => null,
                'payment_status' => $request->payment_status,
                'payment_type' => $request->payment_type,
                'transaction_id' => $request->transaction_id,
            ]);

            if ($request->payment_status == 'paid') {
                $user->is_subscribe = 1;
                $user->save();
            }

            return redirect()->route('backend.subscriptions.index')->with('success', 'Manual subscription added successfully!');
        }

        $users = \App\Models\User::all();
        $plans = \Modules\Subscriptions\Models\Plan::all();

        return view('subscriptions::backend.subscriptions.manual', compact('users', 'plans'));
    }


    public function downloadInvoice($id)
    {
        // Subscription + সম্পর্কিত তথ্য লোড করো
        $subscription = Subscription::with('plan', 'subscription_transaction', 'user')->find($id);

        if (!$subscription) {
            abort(404, 'Subscription not found');
        }

        // Invoice view render করো
        $view = view('frontend::components.partials.invoice', [
            'data' => $subscription
        ])->render();

        // PDF তৈরি করো
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($view);

        // ডাউনলোড করো
        return $pdf->download('invoice-'.$subscription->id.'.pdf');
    }

 
    // AJAX Search for Select2
    public function search(Request $request)
    {
        $search = $request->q;

        $users = \App\Models\User::where(function($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            })
            ->select('id', 'first_name', 'last_name', 'email', 'mobile')
            ->limit(10)
            ->get();

        $formatted = $users->map(function ($user) {
            $fullName = trim($user->first_name . ' ' . $user->last_name);
            return [
                'id' => $user->id,
                'text' => $fullName . ' (' . ($user->email ?? 'No Email') . ')',
            ];
        });

        return response()->json($formatted);
    }


    public function info($id)
    {
        $user = \App\Models\User::with(['subscriptions.plan'])->find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json([
            'name' => $user->full_name,
            'email' => $user->email,
            'phone' => $user->mobile,
            'subscriptions' => $user->subscriptions->map(function ($sub) {
                return [
                    'plan' => $sub->plan->name ?? 'N/A',
                    'price' => $sub->plan->price ?? 'N/A',
                    'start_date' => $sub->start_date,
                    'end_date' => $sub->end_date,
                    'status' => $sub->status
                ];
            })
        ]);
    }


}
