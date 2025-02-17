<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $documentation = [
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
                'example' => [
                    'curl' => 'curl -X POST http://your-app.test/api/upload-multiple-compressed \
                        -H "X-API-Key: your-project-api-key" \
                        -F "photos[]=@photo1.jpg" \
                        -F "photos[]=@photo2.png" \
                        -F "path=gallery" \
                        -F "quality=75" \
                        -F "max_width=1600" \
                        -F "max_height=1600"',
                    'response' => [
                        'message' => 'Files processed successfully',
                        'compressed_files' => [
                            [
                                'original_name' => 'photo1.jpg',
                                'path' => 'project-name/gallery/photos/1683900000_64789abc12345.jpg',
                                'full_url' => 'http://your-app.test/storage/project-name/gallery/photos/1683900000_64789abc12345.jpg',
                                'compressed' => true,
                                'dimensions' => ['width' => 1600, 'height' => 1200]
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
                'examples' => [
                    [
                        'description' => 'Delete using relative path',
                        'curl' => 'curl -X DELETE http://your-app.test/api/files \
                            -H "X-API-Key: your-project-api-key" \
                            -H "Content-Type: application/json" \
                            -d \'{"path": "project-name/users/avatar/file.jpg"}\'',
                    ],
                    [
                        'description' => 'Delete using full URL',
                        'curl' => 'curl -X DELETE http://your-app.test/api/files \
                            -H "X-API-Key: your-project-api-key" \
                            -H "Content-Type: application/json" \
                            -d \'{"path": "http://your-app.test/storage/project-name/users/avatar/file.jpg"}\''
                    ]
                ]
            ]
        ]
    ];

    return view('welcome', ['documentation' => $documentation]);
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
