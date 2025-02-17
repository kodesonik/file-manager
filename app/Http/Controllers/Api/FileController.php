<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FileController extends Controller
{
    protected $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    protected function generateFileName($file)
    {
        $extension = $file->getClientOriginalExtension();
        return time() . '_' . uniqid() . '.' . $extension;
    }

    protected function getUploadPath($project, $path, $inputName)
    {
        $parts = array_filter([
            $project->folder,
            $path ? trim($path, '/') :  $inputName
        ]);

        return implode('/', $parts);
    }

    protected function getFullUrl($path)
    {
        return str_replace('\\/', '/', asset('storage/' . $path));
    }

    protected function isImage($file)
    {
        return in_array(strtolower($file->getClientOriginalExtension()), $this->allowedImageExtensions);
    }

    protected function compressAndSaveImage($file, $uploadPath, $fileName)
    {
        $image = Image::make($file);

        // Resize if width or height is greater than 2000px while maintaining aspect ratio
        if ($image->width() > 2000 || $image->height() > 2000) {
            $image->resize(2000, 2000, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // Compress the image with 80% quality
        $fullPath = public_path('storage/' . $uploadPath . '/' . $fileName);
        $image->save($fullPath, 80);

        return $uploadPath . '/' . $fileName;
    }

    public function upload(Request $request)
    {


        // Get the first file input from the request
        $fileInput = collect($request->allFiles())->first();
        if (!$fileInput) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }


        $inputName = array_key_first($request->allFiles());


        $request->validate([
            $inputName => 'required|file|max:10240', // 10MB max file size
            // 'path' => 'required|string',
        ]);


        $project = $request->attributes->get('project');
        $uploadPath = $this->getUploadPath($project, $request->input('path', ''), $inputName);

        $file = $request->file($inputName);
        $originalName = $file->getClientOriginalName();
        $fileName = $this->generateFileName($file);

        // Create directory if it doesn't exist
        if (!Storage::disk('public')->exists($uploadPath)) {
            Storage::disk('public')->makeDirectory($uploadPath);
        }

        $path = $file->storeAs($uploadPath, $fileName, 'public');

        return response()->json([
            'message' => 'File uploaded successfully',
            'file' => [
                'original_name' => $originalName,
                'name' => $fileName,
                'path' => $path,
                'full_url' => $this->getFullUrl($path)
            ]
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    public function uploadMultiple(Request $request)
    {
        // Get the first file input array from the request
        $fileInputs = collect($request->allFiles())->first();
        if (!$fileInputs) {
            return response()->json(['error' => 'No files uploaded'], 400);
        }

        $inputName = array_key_first($request->allFiles());

        $request->validate([
            $inputName . '.*' => 'required|file|max:10240', // 10MB max file size
            'path' => 'nullable|string',
        ]);

        $project = $request->attributes->get('project');
        $uploadPath = $this->getUploadPath($project, $request->input('path', ''), $inputName);

        // Create directory if it doesn't exist
        if (!Storage::disk('public')->exists($uploadPath)) {
            Storage::disk('public')->makeDirectory($uploadPath);
        }

        $uploadedFiles = [];

        foreach ($request->file($inputName) as $file) {
            $originalName = $file->getClientOriginalName();
            $fileName = $this->generateFileName($file);
            $path = $file->storeAs($uploadPath, $fileName, 'public');

            $uploadedFiles[] = [
                'original_name' => $originalName,
                'name' => $fileName,
                'path' => $path,
                'full_url' => $this->getFullUrl($path)
            ];
        }

        return response()->json([
            'message' => 'Files uploaded successfully',
            'files' => $uploadedFiles
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    public function uploadMultipleCompressed(Request $request)
    {
        // Get the first file input array from the request
        $fileInputs = collect($request->allFiles())->first();
        if (!$fileInputs) {
            return response()->json(['error' => 'No files uploaded'], 400);
        }

        $inputName = array_key_first($request->allFiles());

        $request->validate([
            $inputName . '.*' => 'required|file|max:10240', // 10MB max file size
            'path' => 'nullable|string',
            'quality' => 'nullable|integer|min:1|max:100',
            'max_width' => 'nullable|integer|min:100',
            'max_height' => 'nullable|integer|min:100',
        ]);

        $project = $request->attributes->get('project');
        $uploadPath = $this->getUploadPath($project, $request->input('path', ''), $inputName);

        // Get compression settings
        $quality = $request->input('quality', 80);
        $maxWidth = $request->input('max_width', 2000);
        $maxHeight = $request->input('max_height', 2000);

        // Create directory if it doesn't exist
        if (!Storage::disk('public')->exists($uploadPath)) {
            Storage::disk('public')->makeDirectory($uploadPath);
        }

        $uploadedFiles = [];
        $skippedFiles = [];

        foreach ($request->file($inputName) as $file) {
            $originalName = $file->getClientOriginalName();
            $fileName = $this->generateFileName($file);

            if ($this->isImage($file)) {
                try {
                    $image = Image::make($file);

                    // Resize if needed
                    if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
                        $image->resize($maxWidth, $maxHeight, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                    }

                    // Save compressed image
                    $fullPath = Storage::disk('public')->path($uploadPath . '/' . $fileName);
                    $image->save($fullPath, $quality);
                    $path = $uploadPath . '/' . $fileName;

                    $uploadedFiles[] = [
                        'original_name' => $originalName,
                        'name' => $fileName,
                        'path' => $path,
                        'full_url' => $this->getFullUrl($path),
                        'compressed' => true,
                        'dimensions' => [
                            'width' => $image->width(),
                            'height' => $image->height()
                        ]
                    ];
                } catch (\Exception $e) {
                    // If compression fails, store original file
                    $path = $file->storeAs($uploadPath, $fileName, 'public');
                    $uploadedFiles[] = [
                        'original_name' => $originalName,
                        'name' => $fileName,
                        'path' => $path,
                        'full_url' => $this->getFullUrl($path),
                        'compressed' => false,
                        'error' => 'Compression failed'
                    ];
                }
            } else {
                // Store non-image files without compression
                $path = $file->storeAs($uploadPath, $fileName, 'public');
                $skippedFiles[] = [
                    'original_name' => $originalName,
                    'name' => $fileName,
                    'path' => $path,
                    'full_url' => $this->getFullUrl($path),
                    'reason' => 'Not an image file'
                ];
            }
        }

        return response()->json([
            'message' => 'Files processed successfully',
            'compressed_files' => $uploadedFiles,
            'skipped_files' => $skippedFiles
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        $project = $request->attributes->get('project');
        $filePath = $request->input('path');

        // Check if the input is a full URL and extract the path
        if (filter_var($filePath, FILTER_VALIDATE_URL)) {
            $storageUrl = asset('storage/');
            if (strpos($filePath, $storageUrl) === 0) {
                $filePath = substr($filePath, strlen($storageUrl) + 1);
            } else {
                return response()->json(['error' => 'Invalid storage URL'], 400);
            }
        }

        // Ensure the file is within the project's directory
        if (!str_starts_with($filePath, $project->name . '/')) {
            return response()->json(['error' => 'Invalid file path'], 400);
        }

        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        Storage::disk('public')->delete($filePath);

        return response()->json([
            'message' => 'File deleted successfully'
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }
}
