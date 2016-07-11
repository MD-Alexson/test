<?php

use App\File;
use App\Video;

// DATE & TIME

function getDatetime($time = false)
{
    if (!$time) {
        $time = time();
    }
    $date = new DateTime();
    $date->setTimestamp($time);
    return $date->format('Y-m-d H:i:s');
}

function getTime($datetime = false)
{
    if ($datetime) {
        return strtotime($datetime);
    } else {
        return time();
    }
}

function getTimePlus($time = false, $plus = 1, $term = "months")
{
    if (!$time) {
        $time = time();
    }
    return strtotime("+".$plus." ".$term, $time);
}

// STORAGE:

function folderSize($folder)
{
    $file_size = 0;
    foreach (Storage::allFiles($folder) as $file) {
        $file_size += Storage::size($file);
    }
    return (int) $file_size;
}

function usedSpace()
{
    return folderSize("/".Auth::guard('backend')->id()."/");
}

function maxSpace()
{
    return Auth::guard('backend')->user()->plan->space * 1024 * 1024 * 1024;
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

function imageSave($image, $path, $width, $height)
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

    $img = Image::make(File::get($image))->fit($width, $height);
    $img->save("/tmp/".$rnd_file);

    $img_size = Image::make("/tmp/".$rnd_file)->filesize();
    $allowed  = (int) Auth::guard('backend')->user()->plan->space * 1024 * 1024 * 1024;

    File::delete("/tmp/".$rnd_file);

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

function videoSave($video, $post)
{
    if (!is_object($video)) {
        return false;
    } else if (!$video->isValid()) {
        return false;
    } else if (usedSpace() + $video->getSize() > maxSpace()) {
        return false;
    }
    $ext      = strtolower($video->getClientOriginalExtension());
    $name     = $video->getClientOriginalName();
    $filename = translit(pathinfo($name, PATHINFO_FILENAME));
    $name     = $filename.'.'.$ext;
    $extensions = ['mp4'];
    if (!in_array($ext, $extensions)) {
        return false;
    }

    if($post->videos->count()){
        foreach($post->videos as $vid){
            videoDelete($vid->id);
        }
    }

    $path = "/".Auth::guard('backend')->id().'/'.$post->category->project->domain.'/posts/'.$post->id.'/';
    if (!Storage::exists($path)) {
        Storage::makeDirectory($path, 0775, true, true);
    }

    if(Storage::exists($path.$name)){
        $name = str_random(2)."_".$name;
    }

    $full_path = $path.$name;

    if (Storage::put($full_path, file_get_contents($video))) {
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

function backendPathToUrl($path)
{
    if (strlen($path)) {
        $arr    = explode("/", $path);
        $arr[1] = 'filepath';
        return implode("/", $arr);
    } else {
        return false;
    }
}

function translit($str)
{
    $tr     = array(
        "А" => "a", "Б" => "b", "В" => "v", "Г" => "g",
        "Д" => "d", "Е" => "e", "Ж" => "j", "З" => "z", "И" => "i",
        "Й" => "y", "К" => "k", "Л" => "l", "М" => "m", "Н" => "n",
        "О" => "o", "П" => "p", "Р" => "r", "С" => "s", "Т" => "t",
        "У" => "u", "Ф" => "f", "Х" => "h", "Ц" => "ts", "Ч" => "ch",
        "Ш" => "sh", "Щ" => "sch", "Ъ" => "", "Ы" => "yi", "Ь" => "",
        "Э" => "e", "Ю" => "yu", "Я" => "ya", "а" => "a", "б" => "b",
        "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "j",
        "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
        "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
        "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
        "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y",
        "ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya",
        " -" => "", "," => "", " " => "-", "." => "", "/" => "_",
        "-" => ""
    );
    $result = preg_replace('/[^A-Za-z0-9_\-]/', '', strtr($str, $tr));

    return $result;
}
