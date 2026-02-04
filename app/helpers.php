<?php

if (! function_exists('twcss')) {
    function twcss($path)
    {
        $fullPath = public_path($path);

        if (file_exists($fullPath)) {
            return $path . '?v=' . filemtime($fullPath);
        }

        return $path; // Return original path if file doesn't exist
    }
}
