<?php

namespace App\Helper;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait Media
{

    public function uploads($file, $path)
    {
        if ($file) {

            $fileName   = time() . $file->getClientOriginalName();
            $filePath = Storage::disk('spaces')->putFile($path, $file, 'public');

            return $file = [
                'fileName' => config('constants.DO_SPACES_PUBLIC') . $filePath,
            ];
        }
    }

    public function fileSize($file, $precision = 2)
    {
        $size = $file->getSize();

        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }

        return $size;
    }

    public function getCloudFrontHlsPath($data)
    {
        $url = $data['content_source']; // Replace with your URL

        // Use pathinfo to get the file extension
        $fileInfo = pathinfo($url);
        $extension = isset($fileInfo['extension']) ? '.' . $fileInfo['extension'] : '';

        // Replace the extension with ".m3u8"
        $newUrl = str_replace($extension, '.m3u8', $url);

        // Define a regular expression pattern to match the dynamic value before ".m3u8"
        $pattern = '/\/([^\/]+)\.m3u8$/';

        // Use preg_match to find and capture the dynamic part
        if (preg_match($pattern, $newUrl, $matches)) {
            $key = $matches[1];
        } else {
            return "";
        }

        return config('constants.CLOUDFRONT_URL') . config('constants.S3_PATH') . $key . "/HLS/" . $key . ".m3u8";
    }

    public function getOriginHlsPath($data)
    {
        $url = $data['content_source'];
        $fileInfo = pathinfo($url);
        $extension = isset($fileInfo['extension']) ? '.' . $fileInfo['extension'] : '';

        $newUrl = str_replace($extension, '.m3u8', $url);
        $pattern = '/\/([^\/]+)\.m3u8$/';

        // Use preg_match to find and capture the dynamic part
        if (preg_match($pattern, $newUrl, $matches)) {
            $key = $matches[1];
        } else {
            return "";
        }
        return config('constants.ORIGIN_URL') . $key . ".m3u8";
    }
}
