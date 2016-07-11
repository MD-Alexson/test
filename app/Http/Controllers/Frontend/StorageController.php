<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Project;
use Response;

class StorageController extends Controller
{

    public function getImage($domain, $input_path)
    {
        $project = Project::findOrFail($domain);
        $segments = explode("/", $input_path);
        $image = $segments[count($segments) -1];

        $path = storage_path('app/') . "/".$project->user->id."/".$domain."/".$input_path;
        $handler = new \Symfony\Component\HttpFoundation\File\File($path);
        $lifetime = 86400;

        $file_time = $handler->getMTime();

        $header_content_type = $handler->getMimeType();
        $header_content_length = $handler->getSize();
        $header_etag = md5($file_time . $path);
        $header_last_modified = gmdate('r', $file_time);
        $header_expires = gmdate('r', $file_time + $lifetime);

        $headers = array(
            'Content-Disposition' => 'inline; filename="' . $image . '"',
            'Last-Modified' => $header_last_modified,
            'Cache-Control' => 'must-revalidate',
            'Expires' => $header_expires,
            'Pragma' => 'public',
            'Etag' => $header_etag
        );

        $h1 = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $header_last_modified;
        $h2 = isset($_SERVER['HTTP_IF_NONE_MATCH']) && str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == $header_etag;

        if ($h1 || $h2) {
            return Response::make('', 304, $headers);
        }

        $headers = array_merge($headers, array(
            'Content-Type' => $header_content_type,
            'Content-Length' => $header_content_length
        ));

        return Response::make(file_get_contents($path), 200, $headers);
    }

    public function getFile($domain, $input_path)
    {
        $project = Project::findOrFail($domain);
        $path = storage_path('app/') . "/".$project->user->id."/".$domain."/".$input_path;
        return Response::download($path);
    }
}