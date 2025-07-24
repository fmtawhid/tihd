<?php

namespace Modules\Filemanager\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Modules\Filemanager\Models\Filemanager;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Modules\Filemanager\Http\Requests\FilemanagerRequest;
use App\Trait\ModuleTrait;
use App\Models\Setting;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessFileUpload;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\DB;
use Modules\Entertainment\Models\Entertainment;

class FilemanagersController extends Controller
{
    protected string $exportClass = '\App\Exports\FilemanagerExport';

    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
    }

    public function __construct()
    {
        $this->traitInitializeModuleTrait(
            'filemanager.title', // module title
            'media', // module name
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
        $module_action = 'List';
        $searchQuery = $request->get('query');
        $perPage = 27;
        $page = $request->get('page', 1);

        $result = getMediaUrls($searchQuery, $perPage, $page);
        $mediaUrls = $result['mediaUrls'];
        $hasMore = $result['hasMore'];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('filemanager::backend.filemanager.partial', compact('mediaUrls'))->render(),
                'hasMore' => $hasMore,
            ]);
        }

        return view('filemanager::backend.filemanager.index', compact('module_action', 'mediaUrls', 'hasMore'));
    }



    public function getMediaStore(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 27; // Number of items per page

        $searchQuery = $request->get('query');
        $result = getMediaUrls($searchQuery, $perPage, $page);


        $mediaUrls = $result['mediaUrls'];
        $hasMore = $result['hasMore'];

        $html = view('filemanager::backend.filemanager.partial', compact('mediaUrls'))->render();

            return response()->json([
                'html' => $html,
                'hasMore' => $hasMore,
            ]);
    }

    public function store(FilemanagerRequest $request)
    {
        $jobs = [];

        foreach ($request->file('file_url') as $file) {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $uniqueFileName = pathinfo($originalName, PATHINFO_FILENAME) . '-' . uniqid() . '.' . $extension;
            $temporaryPath = $file->storeAs('temp', $uniqueFileName);
            // $temporaryPath = $file->store('temp');
            $filemanager = Filemanager::create([
                'file_url' => $temporaryPath,
                'file_name' => $uniqueFileName,
            ]);

            // Get the active storage disk from the environment or request
            $diskType = env('ACTIVE_STORAGE', 'local'); // Default to 'local' if not set

            $job = new ProcessFileUpload($filemanager, $temporaryPath, $diskType);
            $jobs[] = $job;
        }

        $batch = Bus::batch($jobs)->dispatch();
        $message = trans('filemanager.file_added');
        return redirect()->route('backend.media-library.index')->with('success', $message);
    }


    public function upload(Request $request)
    {
        $fileChunk = $request->file('file_chunk');
        $fileName = $request->input('file_name');
        $index = $request->input('index');
        $totalChunks = $request->input('total_chunks');

        $temporaryDirectory = storage_path('app/temp/uploads/');
        $filePath = $temporaryDirectory . $fileName;

        // Store the chunk
        $fileChunk->move($temporaryDirectory, $fileName);

        // If all chunks are uploaded, merge them
        if ($index + 1 == $totalChunks) {
            $outputFilePath = storage_path('app/temp/uploads/') . $fileName;
            // Create the final file from chunks
            $outputFile = fopen($outputFilePath, 'ab');

            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkFilePath = $temporaryDirectory . $fileName;
                $chunkFile = fopen($chunkFilePath, 'rb');
                stream_copy_to_stream($chunkFile, $outputFile);
                fclose($chunkFile);
                unlink($chunkFilePath);
            }

            fclose($outputFile);

        }


        return response()->json(['success' => true]);
    }

    public function destroy(Request $request)
{
    $url = $request->input('url');

    $activeDisk = DB::table('settings')->where('name', 'disc_type')->value('val') ?? env('ACTIVE_STORAGE','local');

   // $activeDisk =env('ACTIVE_STORAGE');

    // Assuming the URL is a direct path within dg-ocean storage
    $parsedUrl = parse_url($url);
    $path = ltrim($parsedUrl['path'], '/');

    // Adjust the path if the active disk is 'local'
    if ($activeDisk === 'local') {
        // $path = 'public/' . $path; // Adjust this based on how you store files in local storage
        $path = str_replace('storage/', 'public/', $path);
    }
    $fileName = basename($path);

    // Attempt to delete the file from dg-ocean storage
    if (Storage::disk($activeDisk)->exists($path) && Storage::disk($activeDisk)->delete($path)) {
        // Find and delete the corresponding record from the filemanager table
        $filemanager = Filemanager::where('file_name', $fileName)->first(); // Use 'file_name' if it matches
        if ($filemanager) {
            $filemanager->forceDelete();
        }
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 500);
}

   public function  SearchMedia(Request $request){

    $query = $request->input('query');
    $mediaUrls = getMediaUrls($query);
    return response()->json(['mediaUrls' => $mediaUrls]);

   }









}
