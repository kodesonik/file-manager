<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// API Documentation route
Route::get('/', function () {
    return response()->json([
        'name' => 'File Manager API',
        'version' => '1.0',
        'endpoints' => [
            [
                'path' => '/api/upload',
                'method' => 'POST',
                'description' => 'Upload a single file',
                'parameters' => [
                    '{input_name}' => 'Required. File to upload (max: 10MB). The input name will be used as subfolder',
                    'path' => 'Required. Subdirectory path for the file'
                ],
                'headers' => [
                    'X-API-Key' => 'Required. Project API key'
                ],
                'response' => [
                    'message' => 'Success message',
                    'file' => [
                        'original_name' => 'Original file name',
                        'name' => 'Generated unique file name',
                        'path' => 'Relative path to file',
                        'full_url' => 'Complete URL to access file'
                    ]
                ],
                'example' => [
                    'curl' => 'curl -X POST http://your-app.test/api/upload \
                        -H "X-API-Key: your-project-api-key" \
                        -F "avatar=@image.jpg" \
                        -F "path=users"',
                    'response' => [
                        'message' => 'File uploaded successfully',
                        'file' => [
                            'original_name' => 'image.jpg',
                            'name' => '1683900000_64789abc12345.jpg',
                            'path' => 'project-name/users/avatar/1683900000_64789abc12345.jpg',
                            'full_url' => 'http://your-app.test/storage/project-name/users/avatar/1683900000_64789abc12345.jpg'
                        ]
                    ]
                ]
            ],
            [
                'path' => '/api/upload-multiple',
                'method' => 'POST',
                'description' => 'Upload multiple files',
                'parameters' => [
                    '{input_name}[]' => 'Required. Array of files to upload (max: 10MB each). The input name will be used as subfolder',
                    'path' => 'Optional. Subdirectory path for the files'
                ],
                'headers' => [
                    'X-API-Key' => 'Required. Project API key'
                ],
                'response' => [
                    'message' => 'Success message',
                    'files' => 'Array of uploaded files with details'
                ],
                'example' => [
                    'curl' => 'curl -X POST http://your-app.test/api/upload-multiple \
                        -H "X-API-Key: your-project-api-key" \
                        -F "documents[]=@doc1.pdf" \
                        -F "documents[]=@doc2.pdf" \
                        -F "path=contracts"',
                    'response' => [
                        'message' => 'Files uploaded successfully',
                        'files' => [
                            [
                                'original_name' => 'doc1.pdf',
                                'name' => '1683900000_64789abc12345.pdf',
                                'path' => 'project-name/contracts/documents/1683900000_64789abc12345.pdf',
                                'full_url' => 'http://your-app.test/storage/project-name/contracts/documents/1683900000_64789abc12345.pdf'
                            ],
                            [
                                'original_name' => 'doc2.pdf',
                                'name' => '1683900001_64789abc12346.pdf',
                                'path' => 'project-name/contracts/documents/1683900001_64789abc12346.pdf',
                                'full_url' => 'http://your-app.test/storage/project-name/contracts/documents/1683900001_64789abc12346.pdf'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'path' => '/api/upload-multiple-compressed',
                'method' => 'POST',
                'description' => 'Upload multiple files with image compression',
                'parameters' => [
                    '{input_name}[]' => 'Required. Array of files to upload (max: 10MB each). The input name will be used as subfolder',
                    'path' => 'Optional. Subdirectory path for the files',
                    'quality' => 'Optional. Image compression quality (1-100, default: 80)',
                    'max_width' => 'Optional. Maximum image width in pixels (min: 100, default: 2000)',
                    'max_height' => 'Optional. Maximum image height in pixels (min: 100, default: 2000)'
                ],
                'headers' => [
                    'X-API-Key' => 'Required. Project API key'
                ],
                'response' => [
                    'message' => 'Success message',
                    'compressed_files' => 'Array of successfully compressed image files',
                    'skipped_files' => 'Array of non-image files or files that failed compression'
                ],
                'example' => [
                    'curl' => 'curl -X POST http://your-app.test/api/upload-multiple-compressed \
                        -H "X-API-Key: your-project-api-key" \
                        -F "photos[]=@photo1.jpg" \
                        -F "photos[]=@photo2.png" \
                        -F "photos[]=@document.pdf" \
                        -F "path=gallery" \
                        -F "quality=75" \
                        -F "max_width=1600" \
                        -F "max_height=1600"',
                    'response' => [
                        'message' => 'Files processed successfully',
                        'compressed_files' => [
                            [
                                'original_name' => 'photo1.jpg',
                                'name' => '1683900000_64789abc12345.jpg',
                                'path' => 'project-name/gallery/photos/1683900000_64789abc12345.jpg',
                                'full_url' => 'http://your-app.test/storage/project-name/gallery/photos/1683900000_64789abc12345.jpg',
                                'compressed' => true,
                                'dimensions' => [
                                    'width' => 1600,
                                    'height' => 1200
                                ]
                            ]
                        ],
                        'skipped_files' => [
                            [
                                'original_name' => 'document.pdf',
                                'name' => '1683900002_64789abc12347.pdf',
                                'path' => 'project-name/gallery/photos/1683900002_64789abc12347.pdf',
                                'full_url' => 'http://your-app.test/storage/project-name/gallery/photos/1683900002_64789abc12347.pdf',
                                'reason' => 'Not an image file'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'path' => '/api/files',
                'method' => 'DELETE',
                'description' => 'Delete a file using either full URL or relative path',
                'parameters' => [
                    'path' => 'Required. Either the full URL or relative path to the file'
                ],
                'headers' => [
                    'X-API-Key' => 'Required. Project API key'
                ],
                'response' => [
                    'message' => 'Success or error message'
                ],
                'examples' => [
                    [
                        'description' => 'Delete using relative path',
                        'curl' => 'curl -X DELETE http://your-app.test/api/files \
                            -H "X-API-Key: your-project-api-key" \
                            -H "Content-Type: application/json" \
                            -d \'{"path": "project-name/users/avatar/file.jpg"}\'',
                        'response' => [
                            'message' => 'File deleted successfully'
                        ]
                    ],
                    [
                        'description' => 'Delete using full URL',
                        'curl' => 'curl -X DELETE http://your-app.test/api/files \
                            -H "X-API-Key: your-project-api-key" \
                            -H "Content-Type: application/json" \
                            -d \'{"path": "http://your-app.test/storage/project-name/users/avatar/file.jpg"}\'',
                        'response' => [
                            'message' => 'File deleted successfully'
                        ]
                    ]
                ]
            ]
        ]
    ], 200, [], JSON_UNESCAPED_SLASHES);
});

// Remove the default user route since we're not using it
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// API Routes
Route::middleware('project.auth')->group(function () {
    Route::post('/upload', [FileController::class, 'upload']);
    Route::post('/upload-multiple', [FileController::class, 'uploadMultiple']);
    Route::post('/upload-multiple-compressed', [FileController::class, 'uploadMultipleCompressed']);
    Route::delete('/files', [FileController::class, 'delete']);
});
