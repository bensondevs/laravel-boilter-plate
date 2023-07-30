<?php

use App\Services\StorageFileService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Check if variable is containing base64 type of string
 *
 * @param string $string
 * @return  bool
 */
if (!function_exists('is_base64_string')) {
    function is_base64_string(string $string)
    {
        $temporary = explode(';base64,', $string);
        if (isset($temporary[1])) {
            $string = $temporary[1];
        }

        return base64_encode(base64_decode($string, true)) === $string;
    }
}

/**
 * Get base64 string extension
 *
 * @param string $string
 * @return  string
 */
if (!function_exists('base64_extension')) {
    function base64_extension(string $string)
    {
        if (!is_base64_string($string)) return false;

        $temporary = explode(';base64,', $string);
        $fileType = $temporary[0];
        $dataType = explode('data:', $fileType);

        if (!isset($dataType[1])) return;

        $dataType = $dataType[1];
        $temporaryDataType = explode('/', $dataType);

        if (!isset($temporaryDataType[1])) return;

        return $temporaryDataType[1];
    }
}

/**
 * Upload file brought by laravel request into
 * specified directory
 *
 * @param mixed $fileRequest
 * @param string $directory
 * @return  mixed
 */
if (!function_exists('upload_file')) {
    function upload_file($fileRequest, string $directory)
    {
        if (!is_last_character($directory, '/')) {
            $directory .= '/';
        }

        $service = new StorageFileService();
        if (is_base64_string($fileRequest)) {
            $service->makeFileFromBase64($fileRequest);
        } else if ($fileRequest instanceof UploadedFile) {
            $service->setUploadedFile($fileRequest);
        }

        $service->upload();

        return $service->record();
    }
}

/**
 * Alias for "upload_file"
 *
 * @param mixed $fileRequest
 * @param string $directory
 * @return  mixed
 */
if (!function_exists('uploadFile')) {
    function uploadFile($fileRequest, string $directory)
    {
        return upload_file($fileRequest, $directory);
    }
}

if (!function_exists('path_to_uploaded_file')) {
    /**
     * Create uploaded file instance by supplying the path.
     *
     * Note: Usually this method is used for testing purpose.
     *
     * @param string $path
     * @param bool $public
     * @return UploadedFile
     */
    function path_to_uploaded_file(string $path, bool $public = false): UploadedFile
    {
        $name = \Illuminate\Support\Facades\File::name($path);
        $extension = \Illuminate\Support\Facades\File::extension($path);
        $fullName = $name . '.' . $extension;
        $mimeType = \Illuminate\Support\Facades\File::mimeType($path);

        return new UploadedFile(
            $path,
            $fullName,
            $mimeType,
            $public
        );
    }
}

/**
 * Upload base64 file
 *
 * @param string $base64File
 * @param string $path
 * @param string $filename
 * @return bool
 */
if (!function_exists('upload_base64_file')) {
    function upload_base64_file(
        string $base64File,
        string $path = 'uploads/documents',
        string $filename = ''
    )
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0755, true, true);
        }

        $base64String = substr($base64File, strpos($base64File, ",") + 1);
        $fileData = base64_decode($base64String);
        $extension = explode('/', explode(':', substr($base64File, 0, strpos($base64File, ';')))[1])[1];

        if (!$filename) $filename = now()->format('YmdHis');

        // Prepare image path
        $path = (substr($path, -1) == '/') ?
            $path :
            $path . '/';
        $filename = random_string(5) . $filename . '.' . $extension;
        $filePath = $path . $filename;

        return Storage::put($filePath, $fileData) ? $filePath : false;
    }
}

/**
 * Alias for "upload_base64_file"
 *
 * @param string $base64File
 * @param string $path
 * @param string $filename
 * @return bool
 */
if (!function_exists('uploadBase64File')) {
    function uploadBase64File(
        string $base64File,
        string $path = 'uploads/documents',
        string $filename = ''
    )
    {
        return upload_base64_file($base64File, $path, $filename);
    }
}

/**
 * Upload base64 image
 *
 * @param string $base64Image
 * @param string $path
 * @param string $filename
 * @return bool
 */
if (!function_exists('upload_base64_image')) {
    function upload_base64_image(
        string $base64Image,
        string $imagePath = 'uploads/test',
        string $imageName = ''
    )
    {
        if (!File::exists($base64Image)) {
            File::makeDirectory($imagePath, $mode = 0755, true, true);
        }

        $base64String = substr($base64Image, strpos($base64Image, ",") + 1);
        $imageData = base64_decode($base64String);
        $extension = explode('/', explode(':', substr($base64Image, 0, strpos($base64Image, ';')))[1])[1];

        // Prepare image path
        $imagePath = (substr($imagePath, -1) == '/') ?
            $imagePath :
            $imagePath . '/';
        $fileName = random_string(5) . ($imageName ? $imageName : Carbon::now()->format('YmdHis')) . '.' . $extension;
        $filePath = $imagePath . $fileName;

        $putImage = Storage::put($filePath, $imageData);

        return $putImage ? $filePath : false;
    }
}

/**
 * Alias for "upload_base64_image"
 *
 * @param string $base64Image
 * @param string $path
 * @param string $filename
 * @return bool
 */
if (!function_exists('uploadBase64Image')) {
    function uploadBase64Image(
        string $base64Image,
        string $path = 'uploads/test',
        string $imageName = ''
    )
    {
        return upload_base64_image(
            $base64Image,
            $path,
            $imageName
        );
    }
}

/**
 * Delete file from the diretcory
 *
 * @param string $path
 * @return bool
 */
if (!function_exists('delete_file')) {
    function delete_file(string $path)
    {
        return Storage::delete($path);
    }
}

/**
 * Alias for "delete_file"
 *
 * @param string $path
 * @return bool
 */
if (!function_exists('deleteFile')) {
    function deleteFile(string $path)
    {
        delete_file($path);
    }
}

/**
 * Check file type of laravel file request
 *
 * @param FileRequest $fileRequest
 * @return string
 */
if (!function_exists('get_request_file_type')) {
    function get_request_file_type($fileRequest)
    {
        return $fileRequest->getMimeType();
    }
}

/**
 * Apply file extension to file
 *
 * @param string $filename
 * @param string $extension
 * @return string
 */
if (!function_exists('apply_file_ext')) {
    function apply_file_ext(string $filename, string $extension = '.jpg')
    {
        if (!str_starts_with($extension, '.')) {
            $extension = '.' . $extension;
        }

        if (substr($filename, -(strlen($extension))) === $extension) {
            return $filename;
        }

        $filename .= $extension;
        return $filename;
    }
}

/**
 * Make sure the folder/path directory is exist.
 *
 * If not exists, the this will create the path.
 *
 * @param string $dirPath
 * @return void
 */
if (!function_exists('dir_must_exists')) {
    function dir_must_exists(string $dirPath): void
    {
        try {
            $paths = explode('/', $dirPath);

            $checkedPath = '';
            foreach ($paths as $path) {
                $checkedPath = concat_paths([
                    $checkedPath,
                    $path
                ], false);

                if (!file_exists($checkedPath)) {
                    if (!mkdir($checkedPath)) {
                        Log::info('Directory not created: ' . $checkedPath);
                    }
                }
            }
        } catch (Exception $exception) {
            Log::info("dir_must_exists: $exception");
        }
    }
}

/**
 * Get the stub path.
 *
 * @param string $stubName
 * @return string
 */
if (!function_exists('stub_path')) {
    function stub_path(string $stubName): string
    {
        return resource_path(concat_paths([
            'stubs',
            $stubName
        ]));
    }
}

if (!function_exists('package_stub_path')) {
    /**
     * Get package stub path.
     * 
     * @param string $stubName
     * @return string
     */
    function package_stub_path(string $stubName): string
    {
        return base_path(concat_paths([
            '../Templates/ClassGenerator',
            $stubName,
        ]));
    }
}

/**
 * Get folder file names.
 *
 * @param string $folderPath
 * @return array
 */
if (!function_exists('load_folder_file_names')) {
    function load_folder_file_names(string $folderPath): array
    {
        $fileNames = scandir($folderPath);

        return array_filter($fileNames, function ($fileName) {
            return (!in_array($fileName, ['.', '..']));
        });
    }
}
