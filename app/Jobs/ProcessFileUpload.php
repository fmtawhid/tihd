<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Filemanager\Models\Filemanager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class ProcessFileUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public $filemanager;
    public $filePath;
    public $diskType;

    /**
     * Create a new job instance.
     */
    public function __construct(Filemanager $filemanager, $filePath, $diskType)
    {
        $this->filemanager = $filemanager;
        $this->filePath = $filePath;
        $this->diskType = $diskType;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {

            if (!Storage::exists($this->filePath)) {
                throw new \Exception("File does not exist at path: {$this->filePath}");
            }

            $file = Storage::get($this->filePath);

            if ($this->diskType === 'local') {
                $media = $this->filemanager
                    ->addMediaFromString($file)
                    ->usingFileName($this->filemanager->file_name)
                    ->toMediaCollection('filemanager');

                $folderPath = 'public/streamit-laravel/' . $this->filemanager->file_name;
                Storage::disk('local')->put($folderPath, $file);
            } else {
                $folderPath = 'streamit-laravel/' . $this->filemanager->file_name;
                Storage::disk($this->diskType)->put($folderPath, $file);
            }

            $this->filemanager->save();
            Storage::delete($this->filePath);

            Artisan::call('config:clear');
            Artisan::call('cache:clear');
        } catch (\Exception $e) {

            throw $e;
        }
    }
}
