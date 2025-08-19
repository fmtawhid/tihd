<?php

namespace Modules\Entertainment\Http\Controllers\Backend;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Trait\ModuleTrait;
use Modules\Entertainment\Models\Entertainment;
use Yajra\DataTables\DataTables;
use Modules\Entertainment\Models\Review;
use Modules\Entertainment\Trait\ImportMovieTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Cache;


class ReviewController extends Controller
{

    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }

    public function __construct()
    {
        $this->traitInitializeModuleTrait(
            'review.title', // module title
            'review', // module name
            'fa-solid fa-clipboard-list' // module icon
        );
    }
/**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index(Request $request)
    {

        $filter = [
            'status' => $request->status,
        ];

        $module_action = 'List';

        return view('entertainment::backend.review.index', compact('module_action', 'filter'));

    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = __('review.title');
        return $this->performBulkAction(Review::class, $ids, $actionType, $moduleName);
    }

    public function index_data( Datatables $datatable,Request $request)
    {
        $query = Review::query()->with(['user', 'entertainment'])->withTrashed();

        $query->whereHas('entertainment', function($querydata) {
            $movieEnabled = isenablemodule('movie') == 1;
            $tvshowEnabled = isenablemodule('tvshow') == 1;

            if ($movieEnabled && $tvshowEnabled) {

                $querydata->whereIn('type', ['movie', 'tvshow']);
            } elseif ($movieEnabled && !$tvshowEnabled) {

                $querydata->where('type', 'movie');
            } elseif (!$movieEnabled && $tvshowEnabled) {

                $querydata->where('type', 'tvshow');
            } else {

                $querydata->whereNotIn('type', ['movie', 'tvshow']);
            }
        });


        $filter = $request->filter;

        return $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row" id="datatable-row-'.$row->id.'" name="datatable_ids[]" value="'.$row->id.'" data-type="review" onclick="dataTableRowCheck('.$row->id.',this)">';
            })
            ->addColumn('action', function ($data) {
                return view('entertainment::backend.review.action', compact('data'));
            })


            ->editColumn('user_id', function ($review) {
                return view('entertainment::backend.review.user_profile', compact('review'));
            })
            ->editColumn('entertainment_id', function($data) {
             return $data->entertainment ? $data->entertainment->name : '';
            })

            ->filterColumn('entertainment_id', function($query, $keyword) {
                if (!empty($keyword)) {
                    $query->whereHas('entertainment', function($q) use ($keyword) {
                        $q->where('name', 'like', '%' . $keyword . '%');
                    });
                }
            })

            ->editColumn('type', function($data) {
                return $data->entertainment ? ucwords($data->entertainment->type) : '';
            })
            ->filterColumn('type', function($query, $keyword) {
                if (!empty($keyword)) {
                    $query->whereHas('entertainment', function($q) use ($keyword) {
                        $q->where('type', 'like', '%' . $keyword . '%');
                    });
                }
            })
            ->orderColumn('type', function ($query, $order) {
                $query->orderBy('entertainment_id', $order);
            })

            ->editColumn('updated_at', function ($data) {
                $diff =Carbon::now()->diffInHours($data->updated_at);

                if ($diff < 25) {
                    return $data->updated_at->diffForHumans();
                } else {
                    return $data->updated_at->isoFormat('ddd, MMM D, YYYY h:mm A');
                }
            })

            ->filterColumn('user_id', function ($query, $keyword) {
                if (!empty($keyword)) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('first_name', 'like', '%' . $keyword . '%')->orWhere('last_name', 'like', '%' . $keyword . '%');
                });
                }
            })
            ->filterColumn('entertainment_id', function ($query, $keyword) {
                if (!empty($keyword)) {
                    $query->whereHas('entertainment', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
                }
            })
            ->rawColumns(['check','user_id','entertainment_id'])
            ->orderColumns(['id'], '-:column $1')
            ->make(true);


    }
    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return view('entertainment::create');
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    //  {
    //     //
    // }
    public function create()
    {
        $users = User::all();
        $entertainments = Entertainment::all();

        return view('entertainment::backend.review.create', compact('users', 'entertainments'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'entertainment_id' => 'required|exists:entertainments,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        Review::create($request->only('user_id','entertainment_id','rating','review'));

        return redirect()->route('backend.reviews.index')
            ->with('success', __('messages.create_success', ['form' => __('review.title')]));
    }



    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('entertainment::show');
    }

    /**
     * Show the form for editing the specified resource.
     */

    // public function edit($id)
    // {
    //     return view('entertainment::edit');
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, $id)
    // {
    //     //
    // }
    public function edit($id)
    {
        $review = Review::findOrFail($id);
        $users = User::all();
        $entertainments = Entertainment::all();

        return view('entertainment::backend.review.edit', compact('review', 'users', 'entertainments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'entertainment_id' => 'required|exists:entertainments,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        $review = Review::findOrFail($id);
        $review->update($request->only('user_id', 'entertainment_id', 'rating', 'review'));

        return redirect()->route('backend.reviews.index')
            ->with('success', __('messages.update_success', ['form' => __('review.title')]));
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {
        $data = Review::findOrFail($id);

        Cache::flush();
        $data->delete();
        $formtitle = __('review.title');
        $message = trans('messages.delete_form', ['form' => $formtitle]);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function restore($id)
    {
        $data = Review::withTrashed()->findOrFail($id);

        $data->restore();
        Cache::flush();
        $formtitle = __('review.title');
        $message = trans('messages.restore_form', ['form' => $formtitle]);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function forceDelete($id)
    {
        $data = Review::withTrashed()->findOrFail($id);
        $data->forceDelete();
        Cache::flush();
        $formtitle = __('review.title');
        $message = trans('messages.permanent_delete_form', ['form' => $formtitle]);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

}


