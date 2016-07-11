<?php

use App\User;
use App\Video;
use App\File;

function folderSize($folder, $cloud = false)
{
    if($cloud){
        $file_size = 0;
        foreach (Storage::allFiles($folder) as $file) {
            $file_size += Storage::size($file);
        }
    } else {
        chdir(base_path('storage/app/'.$folder));
        $file_size = exec('du -s -B1 | cut -f1');
    }
    return (int) $file_size;
}

function usedSpace($user_id = false)
{
    if(!$user_id){
        $user_id = Auth::guard('backend')->id();
    }
    return folderSize("/".$user_id."/");
}

function maxSpace($user_id = false)
{
    if(!$user_id){
        $user_id = Auth::guard('backend')->id();
    }
    $user = User::findOrFail($user_id);
    return $user->plan->space * 1024 * 1024 * 1024;
}

function formatBytes($size, $precision = 2)
{
    $base     = log($size, 1024);
    $suffixes = array('байт', 'Кб', 'Мб', 'Гб', 'Тб');
    $result   = round(pow(1024, $base - floor($base)), $precision);
    if (is_nan($result)) {
        $result = 0;
    }
    return $result.' '.$suffixes[floor($base)];
}

function imageSave($image, $path, $width, $height = false)
{

    if (!$image->isValid()) {
        exit('Изображение неверного формата или повреждено! <a href="javascript: history.back();">Назад</a>');
    }
    $ext = $image->guessExtension();
    if ($ext !== 'png' && $ext !== 'jpg' && $ext !== 'jpeg' && $ext !== 'gif') {
        exit('Неверный формат изображения! <a href="javascript: history.back();">Назад</a>');
    }

    if (!file_exists($path)) {
        Storage::makeDirectory($path, 0775, true, true);
    }

    $rnd_file = str_random(8).'.'.$ext;
    $full     = $path.$rnd_file;
    if($height){
        $img = Image::make(\File::get($image))->fit($width, $height);
    } else {
        $img = Image::make(\File::get($image))->widen($width);
    }
    $img->save("/tmp/".$rnd_file);

    $img_size = Image::make("/tmp/".$rnd_file)->filesize();
    $allowed  = (int) Auth::guard('backend')->user()->plan->space * 1024 * 1024 * 1024;

    \File::delete("/tmp/".$rnd_file);

    if (usedSpace() + $img_size > $allowed) {
        $return_path = false;
    } else {
        $img         = $img->stream();
        Storage::put($full, $img->__toString());
        $return_path = $full;
    }

    return $return_path;
}

function fileSave($file, $post)
{
    if (!is_object($file)) {
        return false;
    } else if (!$file->isValid()) {
        return false;
    } else if (usedSpace() + $file->getSize() > maxSpace()) {
        return false;
    }
    $ext      = strtolower($file->getClientOriginalExtension());
    $name     = $file->getClientOriginalName();
    $filename = translit(pathinfo($name, PATHINFO_FILENAME));
    $name     = $filename.'.'.$ext;
    $extensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'zip', 'rar', '7z', 'png', 'jpg', 'jpeg', 'gif', 'mpga', 'txt', 'mp3', 'mp4', 'avi', 'psd', 'ai', 'cdr', 'tiff'];
    if (!in_array($ext, $extensions)) {
        return false;
    }

    $path = "/".Auth::guard('backend')->id().'/'.$post->category->project->domain.'/posts/'.$post->id.'/';
    if (!Storage::exists($path)) {
        Storage::makeDirectory($path, 0775, true, true);
    }

    if(Storage::exists($path.$name)){
        $name = str_random(2)."_".$name;
    }

    $full_path = $path.$name;
    if (Storage::put($full_path, file_get_contents($file))) {
        $file       = new File();
        $file->name = $name;
        $file->path = $full_path;
        $file->type = $ext;
        $file->post()->associate($post);
        $file->save();
    }
}

function fileSaveHW($file, $homework)
{
    $owner_id = $homework->post->category->project->user->id;
    if (!is_object($file)) {
        return $homework;
    } else if (!$file->isValid()) {
        return $homework;
    } else if (usedSpace($owner_id) + $file->getSize() > maxSpace($owner_id)) {
        return $homework;
    } else if($file->getSize() > 52428800){
        return $homework;
    }
    $ext      = strtolower($file->getClientOriginalExtension());
    $name     = $file->getClientOriginalName();
    $filename = translit(pathinfo($name, PATHINFO_FILENAME));
    $name     = $filename.'.'.$ext;
    $extensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'zip', 'rar', '7z', 'png', 'jpg', 'jpeg', 'gif', 'mpga', 'txt', 'mp3', 'mp4', 'avi', 'psd', 'ai', 'cdr', 'tiff'];
    if (!in_array($ext, $extensions)) {
        return $homework;
    }

    $path = "/".$owner_id.'/'.$homework->post->category->project->domain.'/posts/'.$homework->post->id.'/homeworks/'.$homework->suser->id.'/';
    if (!Storage::exists($path)) {
        Storage::makeDirectory($path, 0775, true, true);
    }

    if(Storage::exists($path.$name)){
        $name = str_random(2)."_".$name;
    }

    $full_path = $path.$name;
    if (Storage::put($full_path, file_get_contents($file))) {
        if(Storage::exists($homework->file_path)){
            Storage::delete($homework->file_path);
        }

        $homework->file_path = $full_path;
        $homework->file_name = $name;
    }
    return $homework;
}

function fileDelete($file_id){
    $file = File::findOrFail($file_id);
    if($file->post->category->project->user->id !== Auth::guard('backend')->id()){
        return false;
    }
    if(Storage::exists($file->path)){
        Storage::delete($file->path);
    }
    $file->delete();
}

function videoSave($file, $post)
{
    if (!is_object($file)) {
        return false;
    } else if (!$file->isValid()) {
        return false;
    } else if (usedSpace() + $file->getSize() > maxSpace()) {
        return false;
    }
    $ext      = strtolower($file->getClientOriginalExtension());
    $name     = $file->getClientOriginalName();
    $filename = translit(pathinfo($name, PATHINFO_FILENAME));
    $name     = $filename.'.'.$ext;
    $extensions = ['mp4'];
    if (!in_array($ext, $extensions)) {
        return false;
    }

    $path = "/".Auth::guard('backend')->id().'/'.$post->category->project->domain.'/posts/'.$post->id.'/';
    if (!Storage::exists($path)) {
        Storage::makeDirectory($path, 0775, true, true);
    }

    if(Storage::exists($path.$name)){
        $name = str_random(2)."_".$name;
    }

    $full_path = $path.$name;
    if (Storage::put($full_path, file_get_contents($file))) {
        $video       = new Video();
        $video->name = $name;
        $video->path = $full_path;
        $video->type = $ext;
        $video->post()->associate($post);
        $video->save();
    }
}

function videoDelete($video_id){
    $video = Video::findOrFail($video_id);
    if($video->post->category->project->user->id !== Auth::guard('backend')->id()){
        return false;
    }
    if(Storage::exists($video->path)){
        Storage::delete($video->path);
    }
    $video->delete();
}

function pathTo($path, $prepend)
{
    if(filter_var($path, FILTER_VALIDATE_URL)){
        return $path;
    } else if (strlen($path)) {
        $arr    = explode("/", $path);
        $arr[1] = $prepend;
        unset($arr[2]);
        return implode("/", $arr);
    } else {
        return false;
    }
}