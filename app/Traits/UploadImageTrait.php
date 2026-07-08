<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UploadImageTrait
{
    public function uploadImage($file, $directory)
    {
        // $file should be an instance of UploadedFile
        if ($file instanceof \Illuminate\Http\UploadedFile) {
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $originalName = $file->getClientOriginalName();
            $filename = time() . '_' . $originalName;
            $file->storeAs($directory, $filename, 'public');

            return $filename;
        }

        return null;
    }

    public function updateImage(Request $request, $inputName, $directory, $oldFileName = null)
    {
        if ($request->hasFile($inputName)) {
            $file = $request->file($inputName);

            // Delete old file if exists
            if ($oldFileName) {
                $oldFilePath = $directory . '/' . $oldFileName; // combine directory + filename
                if (Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                }
            }

            return $this->uploadImage($file, $directory);
        }

        return $oldFileName;
    }


    // Helper for multiple gallery images
    public function uploadMultipleImages($existingImages, $files, $directory)
    {
        foreach ($files as $file) {
            $existingImages[] = $this->uploadImage($file, $directory);
        }
        return $existingImages;
    }

    public function handleCKEditorUpload(Request $request, $directory = 'ckeditor')
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = $this->uploadImage($file, $directory);

            if ($filename) {
                $url = asset('storage/' . $directory . '/' . $filename);
                return response()->json([
                    'uploaded' => 1,
                    'fileName' => $filename,
                    'url' => $url
                ]);
            }
        }

        return response()->json([
            'uploaded' => 0,
            'error' => ['message' => 'File upload failed.']
        ]);
    }
}
